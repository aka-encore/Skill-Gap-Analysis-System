<?php
/**
 * SkillBridge - Simplified Assessment Creation Wizard
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('faculty');
check_suspended_status();

$facultyId = $_SESSION['profile_id'];
$db = Database::getInstance();

// Fetch all standardized skills
$skills = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $skillId = (int)($_POST['skill_id'] ?? 0);
        $status = trim($_POST['status'] ?? 'active'); // 'active' = Published, 'draft' = Draft

        if (empty($title) || empty($category) || $skillId <= 0) {
            $error = 'Please complete all required fields.';
        } else {
            // Resolve skill name from skillId
            $skillRow = $db->fetch("SELECT name FROM skills WHERE id = ?", [$skillId]);
            $skillName = $skillRow ? $skillRow['name'] : '';

            // Auto-determine difficulty based on keywords in title, defaulting to beginner
            $difficulty = 'beginner';
            $titleLower = strtolower($title);
            if (strpos($titleLower, 'intermediate') !== false) {
                $difficulty = 'intermediate';
            } elseif (strpos($titleLower, 'advanced') !== false) {
                $difficulty = 'advanced';
            } elseif (strpos($titleLower, 'professional') !== false) {
                $difficulty = 'professional';
            } elseif (strpos($titleLower, 'expert') !== false) {
                $difficulty = 'expert';
            }

            // Find matching Question Bank or create a new one automatically
            $qbRow = $db->fetch(
                "SELECT * FROM question_banks 
                 WHERE LOWER(category) = LOWER(?) AND LOWER(skill) = LOWER(?) AND LOWER(difficulty) = LOWER(?)
                 LIMIT 1",
                [$category, $skillName, $difficulty]
            );

            $qbCreated = false;
            if ($qbRow) {
                $qbId = (int)$qbRow['id'];
            } else {
                $qbId = $db->insert('question_banks', [
                    'title' => $skillName . ' ' . ucfirst($difficulty) . ' Bank',
                    'category' => $category,
                    'skill' => $skillName,
                    'difficulty' => $difficulty,
                    'status' => 'published',
                    'created_by_faculty_id' => $facultyId,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $qbCreated = true;
            }

            // Count questions inside the selected Question Bank to automatically set marks & duration
            $qCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM questions WHERE question_bank_id = ?", [$qbId])['cnt'] ?? 0);
            $totalMarks = max(1, $qCount);
            $passThreshold = (float)get_system_setting('pass_mark_threshold', 60);
            $passingMarks = (int)round($totalMarks * ($passThreshold / 100.0));

            $newId = $db->insert('assessments', [
                'title' => $title,
                'description' => "Standardized assessment evaluation for {$title}.",
                'skill_id' => $skillId,
                'created_by_faculty_id' => $facultyId,
                'duration_minutes' => max(15, $qCount * 1), // 1 min per question or 15 mins min
                'passing_marks' => $passingMarks,
                'total_marks' => $totalMarks,
                'difficulty_level' => $difficulty,
                'status' => $status,
                'question_bank_id' => $qbId,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            log_activity($_SESSION['user_id'], 'ASSESSMENT_CREATED', "Created assessment {$title} (ID: {$newId})");
            invalidate_assessment_sync_cache($db);

            if ($qbCreated) {
                set_flash_message('success', 'Assessment and matching Question Bank created successfully! Please add questions to the new bank.');
                redirect(BASE_URL . 'faculty/question-bank.php?qb_id=' . $qbId);
            } else {
                set_flash_message('success', 'Assessment created and linked to matching Question Bank successfully.');
                redirect(BASE_URL . 'faculty/assessments.php');
            }
        }
    }
}

$pageTitle = "Create Assessment - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-file-earmark-plus text-primary me-2"></i>Create Assessment</h3>
        <p class="text-muted small mb-0">Follow the simplified workflow to configure and publish an institutional assessment</p>
    </div>
    <a href="<?= BASE_URL ?>faculty/assessments.php" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 small fw-semibold">
        <i class="bi bi-arrow-left me-1"></i> Back to Assessments
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="saas-card max-w-4xl mx-auto">
    <div class="card-body p-4 p-md-5">
        <form action="<?= BASE_URL ?>faculty/assessment-create.php" method="POST" id="assessWizardForm">
            <?= csrf_field() ?>

            <!-- Title -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-1">Assessment Title *</label>
                <input type="text" name="title" id="assessTitle" class="saas-form-control w-100" placeholder="e.g., HTML Fundamentals Assessment" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                <div class="form-text text-muted small">Enter a mandatory descriptive name for the test. Adding keywords like 'Intermediate' or 'Advanced' auto-maps to matching question banks.</div>
            </div>

            <!-- Category Selection -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-1">Category *</label>
                <select name="category" id="assessCategory" class="saas-form-select w-100" required onchange="filterSkills()">
                    <option value="">-- Choose Category --</option>
                    <option value="Frontend Development" <?= (isset($_POST['category']) && $_POST['category'] === 'Frontend Development') ? 'selected' : '' ?>>Frontend Development</option>
                    <option value="Backend Development" <?= (isset($_POST['category']) && $_POST['category'] === 'Backend Development') ? 'selected' : '' ?>>Backend Development</option>
                    <option value="Full Stack Development" <?= (isset($_POST['category']) && $_POST['category'] === 'Full Stack Development') ? 'selected' : '' ?>>Full Stack Development</option>
                </select>
            </div>

            <!-- Dynamic Skill Selection -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-1">Target Skill *</label>
                <select name="skill_id" id="assessSkill" class="saas-form-select w-100" required>
                    <option value="">-- Select Category First --</option>
                </select>
                <div class="form-text text-muted small">Skills are filtered dynamically based on the category. Manual creation is restricted.</div>
            </div>

            <!-- Publishing Toggle -->
            <div class="mb-4 border-top pt-4">
                <label class="form-label fw-bold text-dark mb-1">Status & Publishing</label>
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check form-switch fs-5">
                        <input class="form-check-input" type="checkbox" role="switch" id="statusPublishToggle" onchange="updateStatusValue()">
                        <label class="form-check-label text-dark fw-semibold small" id="statusLabel" for="statusPublishToggle">Draft</label>
                    </div>
                    <input type="hidden" name="status" id="statusValue" value="draft">
                </div>
                <div class="form-text text-muted small mt-1">Draft assessments remain hidden from students. Only Published assessments are visible.</div>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold w-100 shadow-xs mt-3">
                Create Assessment & Confirm <i class="bi bi-check-circle ms-1"></i>
            </button>
        </form>
    </div>
</div>

<script>
const allSkills = <?php echo json_encode($skills); ?>;

function filterSkills() {
    const category = document.getElementById('assessCategory').value;
    const skillSelect = document.getElementById('assessSkill');
    
    skillSelect.innerHTML = '';
    
    if (!category) {
        skillSelect.innerHTML = '<option value="">-- Choose Category First --</option>';
        return;
    }
    
    const filtered = allSkills.filter(s => s.category === category);
    if (filtered.length === 0) {
        skillSelect.innerHTML = '<option value="">No standardized skills found</option>';
    } else {
        skillSelect.innerHTML = '<option value="">-- Choose Skill --</option>';
        filtered.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.name;
            skillSelect.appendChild(opt);
        });
    }
}

function updateStatusValue() {
    const isChecked = document.getElementById('statusPublishToggle').checked;
    const valInput = document.getElementById('statusValue');
    const label = document.getElementById('statusLabel');
    
    if (isChecked) {
        valInput.value = 'active'; // Published -> active in DB
        label.textContent = 'Published';
        label.className = 'form-check-label text-success fw-bold small';
    } else {
        valInput.value = 'draft';
        label.textContent = 'Draft';
        label.className = 'form-check-label text-dark fw-semibold small';
    }
}

// Trigger initial setup
filterSkills();
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
