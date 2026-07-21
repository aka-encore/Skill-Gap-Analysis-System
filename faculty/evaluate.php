<?php
/**
 * SkillBridge - Faculty Performance Evaluation & Score Inspector
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');

$facultyId = $_SESSION['profile_id'];
$db = Database::getInstance();

$studentId = (int)($_GET['student_id'] ?? 0);
$students = $db->fetchAll("SELECT * FROM students ORDER BY first_name ASC");

if ($studentId === 0 && !empty($students)) {
    $studentId = $students[0]['id'];
}

$currentStudent = null;
if ($studentId > 0) {
    $currentStudent = $db->fetch("SELECT s.*, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?", [$studentId]);
}

$results = [];
if ($studentId > 0) {
    $results = $db->fetchAll(
        "SELECT ar.*, a.title as assessment_title, s.name as skill_name, s.category as skill_category
         FROM assessment_results ar
         JOIN assessments a ON ar.assessment_id = a.id
         JOIN skills s ON a.skill_id = s.id
         WHERE ar.student_id = ?
         ORDER BY ar.completed_at DESC",
        [$studentId]
    );
}

$pageTitle = "Evaluate Performance - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-check2-square text-primary me-2"></i>Performance Evaluation Inspector</h3>
        <p class="text-muted small mb-0">Detailed score evaluation and skill gap diagnostics per student</p>
    </div>
    <?php if ($currentStudent): ?>
        <a href="<?= BASE_URL ?>faculty/recommend-courses.php?student_id=<?= $studentId ?>" class="btn btn-warning rounded-pill px-4 fw-semibold shadow-xs">
            <i class="bi bi-award me-1"></i> Recommend Course
        </a>
    <?php endif; ?>
</div>

<!-- Student Picker Dropdown -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="<?= BASE_URL ?>faculty/evaluate.php" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9">
                <label class="form-label small text-muted mb-1 fw-semibold">Select Student to Evaluate</label>
                <select name="student_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($students as $st): ?>
                        <option value="<?= $st['id'] ?>" <?= $studentId == $st['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($st['student_code']) ?> - <?= htmlspecialchars($st['first_name'] . ' ' . $st['last_name']) ?> (<?= htmlspecialchars($st['department']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <a href="<?= BASE_URL ?>faculty/students.php" class="btn btn-outline-secondary btn-sm w-100 rounded-3">View Roster</a>
            </div>
        </form>
    </div>
</div>

<?php if ($currentStudent): ?>
    <!-- Student Profile Summary -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1 mb-2"><?= htmlspecialchars($currentStudent['student_code']) ?></span>
                <h4 class="fw-bold mb-1"><?= htmlspecialchars($currentStudent['first_name'] . ' ' . $currentStudent['last_name']) ?></h4>
                <p class="text-muted small mb-0"><?= htmlspecialchars($currentStudent['department']) ?> &bull; Semester <?= $currentStudent['current_semester'] ?> &bull; Contact: <?= htmlspecialchars($currentStudent['email']) ?></p>
            </div>
            <div class="text-md-end">
                <div class="text-muted small">Total Assessments Attempted</div>
                <div class="fw-bold fs-3 text-dark"><?= count($results) ?></div>
            </div>
        </div>
    </div>

    <!-- Student Assessment Performance Matrix -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h5 class="fw-bold mb-0">Evaluation History</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Assessment</th>
                            <th>Skill</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Gap Status</th>
                            <th class="pe-4 text-end">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($results)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No assessment results recorded for this student yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($results as $r): 
                                $gap = calculate_skill_gap((float)$r['score_percentage']);
                            ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark"><?= htmlspecialchars($r['assessment_title']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($r['skill_name']) ?></span></td>
                                    <td><?= $r['score_obtained'] ?> / <?= $r['total_questions'] ?></td>
                                    <td><strong class="text-dark"><?= number_format($r['score_percentage'], 1) ?>%</strong></td>
                                    <td><span class="badge <?= $gap['badge_class'] ?>"><?= strtoupper($r['status']) ?></span></td>
                                    <td>
                                        <?php if ($gap['is_weak']): ?>
                                            <span class="badge bg-danger"><i class="bi bi-exclamation-circle me-1"></i> Deficit (<?= number_format($gap['gap_percentage'], 1) ?>%)</span>
                                        <?php else: ?>
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Proficient</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end small text-muted"><?= format_date($r['completed_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
