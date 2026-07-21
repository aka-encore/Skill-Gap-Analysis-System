<?php
/**
 * SkillBridge - Create New Assessment Form
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('faculty');

$facultyId = $_SESSION['profile_id'];
$db = Database::getInstance();

$skills = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $skillId = (int)($_POST['skill_id'] ?? 0);
        $duration = (int)($_POST['duration_minutes'] ?? 20);
        $passingMarks = (int)($_POST['passing_marks'] ?? 6);
        $totalMarks = (int)($_POST['total_marks'] ?? 10);
        $difficulty = trim($_POST['difficulty_level'] ?? 'intermediate');
        $status = trim($_POST['status'] ?? 'active');

        if (empty($title) || $skillId <= 0) {
            $error = 'Please enter an assessment title and select an associated skill.';
        } else {
            $newId = $db->insert('assessments', [
                'title' => $title,
                'description' => $description,
                'skill_id' => $skillId,
                'created_by_faculty_id' => $facultyId,
                'duration_minutes' => $duration,
                'passing_marks' => $passingMarks,
                'total_marks' => $totalMarks,
                'difficulty_level' => $difficulty,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            log_activity($_SESSION['user_id'], 'ASSESSMENT_CREATED', "Created assessment {$title} (ID: {$newId})");

            set_flash_message('success', 'Assessment created successfully! Now add questions to the Question Bank.');
            redirect(BASE_URL . 'faculty/question-bank.php?assessment_id=' . $newId);
        }
    }
}

$pageTitle = "Create Assessment - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-file-earmark-plus text-primary me-2"></i>Create New Assessment</h3>
        <p class="text-muted small mb-0">Configure assessment properties and target skill association</p>
    </div>
    <a href="<?= BASE_URL ?>faculty/assessments.php" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Assessments
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="saas-card max-w-4xl mx-auto">
    <div class="card-body p-4 p-md-5">
        <form action="<?= BASE_URL ?>faculty/assessment-create.php" method="POST">
            <?= csrf_field() ?>

            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Assessment Title *</label>
                <input type="text" name="title" class="saas-form-control w-100" placeholder="e.g., PHP 8 Core Concepts & PDO Mastery" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Target Technical Skill *</label>
                    <select name="skill_id" class="saas-form-select w-100" required>
                        <option value="">-- Select Skill --</option>
                        <?php foreach ($skills as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= (isset($_POST['skill_id']) && $_POST['skill_id'] == $s['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['category']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Difficulty Level</label>
                    <select name="difficulty_level" class="saas-form-select w-100">
                        <option value="beginner">Beginner</option>
                        <option value="intermediate" selected>Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" class="saas-form-control w-100" min="5" max="180" value="20" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">Passing Marks</label>
                    <input type="number" name="passing_marks" class="saas-form-control w-100" min="1" max="100" value="6" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">Total Marks</label>
                    <input type="number" name="total_marks" class="saas-form-control w-100" min="1" max="100" value="10" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Description & Instructions</label>
                <textarea name="description" class="saas-form-control w-100" rows="3" placeholder="Overview of topics tested..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Status</label>
                <select name="status" class="saas-form-select w-100">
                    <option value="active">Active (Available to students)</option>
                    <option value="draft">Draft (Hidden)</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
                Create & Continue to Question Bank <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
