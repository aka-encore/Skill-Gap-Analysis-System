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

$preSelectedSkillId = (int)($_GET['skill_id'] ?? 0);
$preSelectedDifficulty = trim($_GET['difficulty_level'] ?? '');

$skills = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $skillId = (int)($_POST['skill_id'] ?? 0);
        $duration = (int)($_POST['duration_minutes'] ?? 25);
        $totalMarks = 25;
        $passThreshold = (float)get_system_setting('pass_mark_threshold', 60);
        $passingMarks = (int)round($totalMarks * ($passThreshold / 100.0));
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
                            <option value="<?= $s['id'] ?>" <?= (($preSelectedSkillId > 0 && $preSelectedSkillId == $s['id']) || (isset($_POST['skill_id']) && $_POST['skill_id'] == $s['id'])) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['category']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Difficulty Level *</label>
                    <select name="difficulty_level" class="saas-form-select w-100" required>
                        <?php
                        $diffs = [
                            'beginner' => 'Beginner (Level 1)',
                            'easy' => 'Elementary (Level 2)',
                            'intermediate' => 'Intermediate (Level 3)',
                            'advanced' => 'Advanced (Level 4)',
                            'expert' => 'Expert (Level 5)'
                        ];
                        foreach ($diffs as $val => $label):
                            $isSelected = ($preSelectedDifficulty !== '' && $preSelectedDifficulty === $val) || (isset($_POST['difficulty_level']) && $_POST['difficulty_level'] === $val) || ($preSelectedDifficulty === '' && !isset($_POST['difficulty_level']) && $val === 'intermediate');
                        ?>
                            <option value="<?= $val ?>" <?= $isSelected ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" class="saas-form-control w-100" min="5" max="180" value="25" required>
                </div>
                <div class="col-md-4">
                    <?php 
                    $passThreshold = (float)get_system_setting('pass_mark_threshold', 60);
                    $calcPassingMarks = (int)round(25 * ($passThreshold / 100.0));
                    ?>
                    <label class="form-label fw-semibold small text-secondary">Passing Marks (Read-only)</label>
                    <input type="number" name="passing_marks" class="saas-form-control w-100 bg-light text-muted" value="<?= $calcPassingMarks ?>" readonly required>
                    <div class="text-muted" style="font-size: 10px; margin-top: 4px;">Fixed at <?= $passThreshold ?>% passing threshold.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">Total Marks (Read-only)</label>
                    <input type="number" name="total_marks" class="saas-form-control w-100 bg-light text-muted" value="25" readonly required>
                    <div class="text-muted" style="font-size: 10px; margin-top: 4px;">Fixed to match 25 questions count.</div>
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
