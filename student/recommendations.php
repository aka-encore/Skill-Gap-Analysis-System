<?php
/**
 * SkillBridge - Personalized Course Recommendations for Students
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$db = Database::getInstance();

// Dismiss recommendation handler
if (isset($_GET['dismiss_id'])) {
    $dismissId = (int)$_GET['dismiss_id'];
    $db->update('recommendations', ['is_dismissed' => 1], 'id = ? AND student_id = ?', [$dismissId, $studentId]);
    set_flash_message('success', 'Recommendation dismissed.');
    redirect(BASE_URL . 'student/recommendations.php');
}

// Enroll / Start Course handler
if (isset($_GET['enroll_course_id'])) {
    $courseId = (int)$_GET['enroll_course_id'];
    $existing = $db->fetch("SELECT id FROM student_progress WHERE student_id = ? AND course_id = ?", [$studentId, $courseId]);
    if (!$existing) {
        $db->insert('student_progress', [
            'student_id' => $studentId,
            'course_id' => $courseId,
            'progress_percentage' => 10,
            'status' => 'in_progress',
            'last_updated' => date('Y-m-d H:i:s')
        ]);
        set_flash_message('success', 'Enrolled in course successfully!');
    }
    redirect(BASE_URL . 'student/progress.php');
}

$recommendations = $db->fetchAll(
    "SELECT r.*, c.title as course_title, c.course_code, c.description as course_desc, c.duration_hours, c.difficulty_level, c.provider_url, s.name as skill_name,
            sp.progress_percentage, sp.status as enrollment_status
     FROM recommendations r
     JOIN courses c ON r.course_id = c.id
     JOIN skills s ON r.skill_id = s.id
     LEFT JOIN student_progress sp ON r.course_id = sp.course_id AND sp.student_id = r.student_id
     WHERE r.student_id = ? AND r.is_dismissed = 0
     ORDER BY r.priority_level DESC, r.created_at DESC",
    [$studentId]
);

$pageTitle = "Course Recommendations - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-lightbulb text-warning me-2"></i>Personalized Course Recommendations</h3>
        <p class="text-muted small mb-0">Courses recommended based on your recent skill gap assessment results</p>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($recommendations)): ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-check2-circle text-success display-3 mb-3"></i>
            <h4 class="fw-bold text-dark">No Skill Deficits Identified!</h4>
            <p class="text-muted small">You currently have no active course recommendations. Keep taking assessments to track your growth.</p>
        </div>
    <?php else: ?>
        <?php foreach ($recommendations as $r): 
            $priorityClass = match($r['priority_level']) {
                'high' => 'bg-danger text-white',
                'medium' => 'bg-warning text-dark',
                'low' => 'bg-info text-white'
            };
            $isEnrolled = !is_null($r['enrollment_status']);
        ?>
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="badge bg-light text-dark border me-1"><?= htmlspecialchars($r['course_code']) ?></span>
                                    <span class="badge bg-primary-subtle text-primary border"><i class="bi bi-tag me-1"></i><?= htmlspecialchars($r['skill_name']) ?></span>
                                </div>
                                <span class="badge <?= $priorityClass ?> rounded-pill px-3 py-1"><?= strtoupper($r['priority_level']) ?> PRIORITY</span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($r['course_title']) ?></h5>
                            <p class="text-muted small mb-3"><?= htmlspecialchars($r['course_desc'] ?? 'No course summary available.') ?></p>

                            <!-- Reason Box -->
                            <div class="p-3 bg-light rounded-3 border mb-3">
                                <small class="text-secondary fw-semibold d-block mb-1"><i class="bi bi-info-circle me-1"></i> Why Recommended?</small>
                                <div class="small text-dark"><?= htmlspecialchars($r['reason']) ?></div>
                            </div>
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                            <span class="small text-muted"><i class="bi bi-clock me-1"></i><?= $r['duration_hours'] ?> Hours</span>

                            <div class="d-flex gap-2">
                                <a href="<?= BASE_URL ?>student/recommendations.php?dismiss_id=<?= $r['id'] ?>" class="btn btn-outline-secondary btn-sm rounded-pill" data-confirm="Dismiss this recommendation?">Dismiss</a>
                                
                                <?php if ($isEnrolled): ?>
                                    <a href="<?= BASE_URL ?>student/progress.php" class="btn btn-success bg-gradient-success border-0 btn-sm rounded-pill px-3">
                                        <i class="bi bi-play-circle me-1"></i> Continue Learning
                                    </a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>student/recommendations.php?enroll_course_id=<?= $r['course_id'] ?>" class="btn btn-primary bg-gradient-primary border-0 btn-sm rounded-pill px-3">
                                        Enroll Now <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
