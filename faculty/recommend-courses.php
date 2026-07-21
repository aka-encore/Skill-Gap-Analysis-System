<?php
/**
 * SkillBridge - Faculty Course Recommendation Assigner
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('faculty');

$db = Database::getInstance();

$students = $db->fetchAll("SELECT * FROM students ORDER BY first_name ASC");
$courses = $db->fetchAll("SELECT * FROM courses WHERE status = 'active' ORDER BY title ASC");
$skills = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid CSRF token.';
    } else {
        $studentId = (int)($_POST['student_id'] ?? 0);
        $courseId = (int)($_POST['course_id'] ?? 0);
        $skillId = (int)($_POST['skill_id'] ?? 0);
        $priority = trim($_POST['priority_level'] ?? 'medium');
        $reason = trim($_POST['reason'] ?? '');

        if ($studentId <= 0 || $courseId <= 0 || $skillId <= 0) {
            $error = 'Please select a student, course, and associated skill.';
        } else {
            $db->insert('recommendations', [
                'student_id' => $studentId,
                'course_id' => $courseId,
                'skill_id' => $skillId,
                'reason' => $reason ?: 'Manually recommended by faculty to reinforce core skill concepts.',
                'priority_level' => $priority,
                'is_dismissed' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Notify Student
            $st = $db->fetch("SELECT user_id FROM students WHERE id = ?", [$studentId]);
            $crs = $db->fetch("SELECT title FROM courses WHERE id = ?", [$courseId]);

            if ($st) {
                $db->insert('notifications', [
                    'user_id' => $st['user_id'],
                    'title' => 'Faculty Course Recommendation',
                    'message' => "Faculty member recommended course '{$crs['title']}' for you.",
                    'link' => BASE_URL . 'student/recommendations.php',
                    'is_read' => 0,
                    'type' => 'recommendation',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            $success = 'Course recommendation assigned to student successfully.';
        }
    }
}

$pageTitle = "Recommend Courses - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-award text-warning me-2"></i>Assign Course Recommendation</h3>
        <p class="text-muted small mb-0">Manually target specific student skill gaps with tailored courses</p>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 max-w-3xl mx-auto">
    <div class="card-body p-4 p-md-5">
        <form action="<?= BASE_URL ?>faculty/recommend-courses.php" method="POST">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Target Student *</label>
                <select name="student_id" class="form-select" required>
                    <option value="">-- Select Student --</option>
                    <?php 
                    $preselect = (int)($_GET['student_id'] ?? 0);
                    foreach ($students as $st): 
                    ?>
                        <option value="<?= $st['id'] ?>" <?= $preselect === $st['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($st['student_code']) ?> - <?= htmlspecialchars($st['first_name'] . ' ' . $st['last_name']) ?> (<?= htmlspecialchars($st['department']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Recommended Course *</label>
                    <select name="course_id" class="form-select" required>
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_code']) ?> - <?= htmlspecialchars($c['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Target Skill *</label>
                    <select name="skill_id" class="form-select" required>
                        <option value="">-- Select Skill --</option>
                        <?php foreach ($skills as $sk): ?>
                            <option value="<?= $sk['id'] ?>"><?= htmlspecialchars($sk['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Priority Level</label>
                <select name="priority_level" class="form-select">
                    <option value="high">High Priority (Urgent Gap)</option>
                    <option value="medium" selected>Medium Priority</option>
                    <option value="low">Low Priority (Optional Enrichment)</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Recommendation Note / Reason</label>
                <textarea name="reason" class="form-control" rows="3" placeholder="Explain why this course is recommended for the student..."></textarea>
            </div>

            <button type="submit" class="btn btn-warning rounded-pill px-4 py-2 fw-semibold">
                Assign Course Recommendation <i class="bi bi-send ms-1"></i>
            </button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
