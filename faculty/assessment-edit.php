<?php
/**
 * SkillBridge - Edit Assessment Form
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('faculty');

$facultyId = $_SESSION['profile_id'];
$assessmentId = (int)($_GET['id'] ?? 0);
$db = Database::getInstance();

$assessment = $db->fetch("SELECT * FROM assessments WHERE id = ?", [$assessmentId]);
if (!$assessment) {
    set_flash_message('danger', 'Assessment not found.');
    redirect(BASE_URL . 'faculty/assessments.php');
}

if ((int)$assessment['created_by_faculty_id'] !== (int)$facultyId) {
    set_flash_message('danger', 'Unauthorized: You can only edit assessments that you created.');
    redirect(BASE_URL . 'faculty/assessments.php');
}

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
        $passingMarks = 20;
        $totalMarks = 25;
        $difficulty = trim($_POST['difficulty_level'] ?? 'intermediate');
        $status = trim($_POST['status'] ?? 'active');

        if (empty($title) || $skillId <= 0) {
            $error = 'Title and Associated Skill are required.';
        } else {
            $db->update('assessments', [
                'title' => $title,
                'description' => $description,
                'skill_id' => $skillId,
                'duration_minutes' => $duration,
                'passing_marks' => $passingMarks,
                'total_marks' => $totalMarks,
                'difficulty_level' => $difficulty,
                'status' => $status
            ], 'id = ? AND created_by_faculty_id = ?', [$assessmentId, $facultyId]);

            set_flash_message('success', 'Assessment details updated successfully.');
            redirect(BASE_URL . 'faculty/assessments.php');
        }
    }
}

$pageTitle = "Edit Assessment - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Assessment Details</h3>
        <p class="text-muted small mb-0">Update assessment configuration and target skill link</p>
    </div>
    <a href="<?= BASE_URL ?>faculty/assessments.php" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Assessments
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="saas-card max-w-4xl mx-auto">
    <div class="card-body p-4 p-md-5">
        <form action="<?= BASE_URL ?>faculty/assessment-edit.php?id=<?= $assessmentId ?>" method="POST">
            <?= csrf_field() ?>

            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Assessment Title *</label>
                <input type="text" name="title" class="saas-form-control w-100" required value="<?= htmlspecialchars($assessment['title']) ?>">
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Target Technical Skill *</label>
                    <select name="skill_id" class="saas-form-select w-100" required>
                        <?php foreach ($skills as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $assessment['skill_id'] == $s['id'] ? 'selected' : '' ?>>
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
                        ?>
                            <option value="<?= $val ?>" <?= $assessment['difficulty_level'] === $val ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" class="saas-form-control w-100" min="5" max="180" value="<?= $assessment['duration_minutes'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">Passing Marks (Read-only)</label>
                    <input type="number" name="passing_marks" class="saas-form-control w-100 bg-light text-muted" value="20" readonly required>
                    <div class="text-muted" style="font-size: 10px; margin-top: 4px;">Fixed at 80% passing threshold.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">Total Marks (Read-only)</label>
                    <input type="number" name="total_marks" class="saas-form-control w-100 bg-light text-muted" value="25" readonly required>
                    <div class="text-muted" style="font-size: 10px; margin-top: 4px;">Fixed to match 25 questions count.</div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Description</label>
                <textarea name="description" class="saas-form-control w-100" rows="3"><?= htmlspecialchars($assessment['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Status</label>
                <select name="status" class="saas-form-select w-100">
                    <option value="active" <?= $assessment['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="draft" <?= $assessment['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="archived" <?= $assessment['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="<?= BASE_URL ?>faculty/question-bank.php?assessment_id=<?= $assessmentId ?>" class="btn btn-outline-info rounded-pill px-4">
                    <i class="bi bi-question-circle me-1"></i> Manage Questions
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
