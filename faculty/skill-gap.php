<?php
/**
 * SkillBridge - Class Skill Gap Analytics & Bottlenecks for Faculty
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');

$db = Database::getInstance();

// Aggregate class-wide skill averages
$classSkills = $db->fetchAll(
    "SELECT s.name as skill_name, s.category,
            AVG(ar.score_percentage) as class_avg_score,
            COUNT(DISTINCT ar.student_id) as total_students_tested,
            SUM(CASE WHEN ar.score_percentage < 60 THEN 1 ELSE 0 END) as weak_students_count
     FROM skills s
     JOIN assessments a ON s.id = a.skill_id
     JOIN assessment_results ar ON a.id = ar.assessment_id
     GROUP BY s.id, s.name, s.category
     ORDER BY class_avg_score ASC"
);

$chartLabels = [];
$chartAvgScores = [];
foreach ($classSkills as $cs) {
    $chartLabels[] = $cs['skill_name'];
    $chartAvgScores[] = round((float)($cs['class_avg_score'] ?? 0), 1);
}

$pageTitle = "Class Skill Gap Analytics - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-bar-chart-line text-info me-2"></i>Class Skill Gap Analytics</h3>
        <p class="text-muted small mb-0">Identify institutional skill bottlenecks and group deficiencies</p>
    </div>
    <a href="<?= BASE_URL ?>faculty/recommend-courses.php" class="btn btn-warning rounded-pill px-4 fw-semibold">
        <i class="bi bi-award me-1"></i> Recommend Courses to Students
    </a>
</div>

<!-- Analytics Chart -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-0 py-3 px-4">
        <h5 class="fw-bold mb-0">Class Skill Score Averages</h5>
    </div>
    <div class="card-body p-4" style="height: 350px;">
        <canvas id="classSkillBarCanvas"></canvas>
    </div>
</div>

<!-- Detailed Bottlenecks Table -->
<div class="saas-card overflow-hidden">
    <div class="saas-card-header">
        <h5 class="fw-bold mb-0" style="color: var(--text-heading);">Skill Deficiency Heatmap</h5>
        <span class="badge saas-badge-primary">Total Skills Monitored: <?= count($classSkills) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="saas-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Skill Name</th>
                        <th>Category</th>
                        <th>Class Avg Score</th>
                        <th>Tested Students</th>
                        <th>Students with Deficit (&lt;60%)</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($classSkills)): ?>
                        <tr>
                            <td colspan="6">
                                <div class="saas-empty-state">
                                    <div class="saas-empty-icon"><i class="bi bi-bar-chart"></i></div>
                                    <h6 class="fw-bold mb-1" style="color: var(--text-heading);">No evaluation data yet</h6>
                                    <p class="small mb-0" style="color: var(--text-muted);">Skill gap data will appear after students complete assessments.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($classSkills as $cs): 
                            $avg = round((float)($cs['class_avg_score'] ?? 0), 1);
                        ?>
                            <tr>
                                <td class="ps-4 fw-semibold" style="color: var(--text-heading);"><?= htmlspecialchars($cs['skill_name']) ?></td>
                                <td><span class="badge saas-badge-primary"><?= htmlspecialchars($cs['category']) ?></span></td>
                                <td><strong class="text-<?= $avg >= 75 ? 'success' : ($avg >= 60 ? 'info' : 'danger') ?>"><?= $avg ?>%</strong></td>
                                <td style="color: var(--text-body);"><?= $cs['total_students_tested'] ?></td>
                                <td>
                                    <?php if ($cs['weak_students_count'] > 0): ?>
                                        <span class="badge saas-badge-danger"><?= $cs['weak_students_count'] ?> Student(s) Needed Intervention</span>
                                    <?php else: ?>
                                        <span class="badge saas-badge-success">No Deficits</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="<?= BASE_URL ?>faculty/recommend-courses.php" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                                        Assign Remedial Course
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/js/charts-config.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    renderScoreBarChart(
        'classSkillBarCanvas',
        <?= json_encode($chartLabels) ?>,
        <?= json_encode($chartAvgScores) ?>
    );
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
