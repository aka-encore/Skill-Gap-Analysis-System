<?php
/**
 * SkillBridge - Advanced Institutional Analytics Panel
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('admin');

$db = Database::getInstance();

$passCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE status = 'pass'")['cnt'] ?? 0);
$failCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE status = 'fail'")['cnt'] ?? 0);

$deptStats = $db->fetchAll(
    "SELECT s.department, AVG(ar.score_percentage) as avg_score, COUNT(ar.id) as total_tests
     FROM students s
     JOIN assessment_results ar ON s.id = ar.student_id
     GROUP BY s.department"
);

$deptNames = [];
$deptScores = [];
foreach ($deptStats as $d) {
    $deptNames[] = $d['department'];
    $deptScores[] = round((float)$d['avg_score'], 1);
}

$pageTitle = "System Analytics - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-pie-chart text-info me-2"></i>Institutional Skill Analytics</h3>
        <p class="text-muted small mb-0">High-level statistical metrics across departments and assessments</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Pass/Fail Doughnut -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0">Overall Assessment Pass/Fail Ratio</h5>
            </div>
            <div class="card-body p-4" style="height: 300px;">
                <canvas id="adminPassFailCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Department Performance Bar Chart -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0">Average Score by Academic Department</h5>
            </div>
            <div class="card-body p-4" style="height: 300px;">
                <canvas id="adminDeptBarCanvas"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/js/charts-config.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    renderPassFailDoughnutChart('adminPassFailCanvas', <?= $passCount ?>, <?= $failCount ?>);
    renderScoreBarChart('adminDeptBarCanvas', <?= json_encode($deptNames) ?>, <?= json_encode($deptScores) ?>);
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
