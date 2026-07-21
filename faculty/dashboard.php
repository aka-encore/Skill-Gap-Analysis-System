<?php
/**
 * SkillBridge - Faculty Management Dashboard
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');

$facultyId = $_SESSION['profile_id'];
$db = Database::getInstance();

$faculty = $db->fetch("SELECT f.*, u.email FROM faculty f JOIN users u ON f.user_id = u.id WHERE f.id = ?", [$facultyId]);

// Faculty Metrics
$totalStudents = (int)($db->fetch("SELECT COUNT(*) as cnt FROM students")['cnt'] ?? 0);
$myAssessmentsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessments WHERE created_by_faculty_id = ?", [$facultyId])['cnt'] ?? 0);
$totalSubmissions = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results ar JOIN assessments a ON ar.assessment_id = a.id WHERE a.created_by_faculty_id = ?", [$facultyId])['cnt'] ?? 0);

$classAvgRow = $db->fetch("SELECT AVG(ar.score_percentage) as avg_score FROM assessment_results ar JOIN assessments a ON ar.assessment_id = a.id WHERE a.created_by_faculty_id = ?", [$facultyId]);
$classAvgScore = round((float)($classAvgRow['avg_score'] ?? 0), 1);

// Recent Student Submissions for this Faculty
$recentSubmissions = $db->fetchAll(
    "SELECT ar.*, a.title as assessment_title, st.first_name, st.last_name, st.student_code, s.name as skill_name
     FROM assessment_results ar
     JOIN assessments a ON ar.assessment_id = a.id
     JOIN students st ON ar.student_id = st.id
     JOIN skills s ON a.skill_id = s.id
     WHERE a.created_by_faculty_id = ?
     ORDER BY ar.completed_at DESC LIMIT 5",
    [$facultyId]
);

// Assessment Performance Summary for Chart
$assessmentPerf = $db->fetchAll(
    "SELECT a.title, AVG(ar.score_percentage) as avg_score
     FROM assessments a
     LEFT JOIN assessment_results ar ON a.id = ar.assessment_id
     WHERE a.created_by_faculty_id = ?
     GROUP BY a.id, a.title LIMIT 6",
    [$facultyId]
);

$chartTitles = [];
$chartScores = [];
foreach ($assessmentPerf as $ap) {
    $chartTitles[] = $ap['title'];
    $chartScores[] = round((float)($ap['avg_score'] ?? 0), 1);
}

$pageTitle = "Faculty Dashboard - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<!-- Welcome Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="saas-hero-banner">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 position-relative z-1">
                <div>
                    <span class="badge bg-white-subtle text-white border border-white-subtle rounded-pill px-3 py-1.5 mb-2 small fw-semibold">
                        <i class="bi bi-mortarboard-fill me-1"></i> Employee Code: <?= htmlspecialchars($faculty['employee_code']) ?>
                    </span>
                    <h2 class="fw-bold mb-1">Welcome back, Prof. <?= htmlspecialchars($faculty['last_name']) ?>! 👋</h2>
                    <p class="mb-0 text-white-50"><?= htmlspecialchars($faculty['designation']) ?> &bull; Department of <?= htmlspecialchars($faculty['department']) ?></p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= BASE_URL ?>faculty/assessment-create.php" class="btn btn-light rounded-pill px-4 fw-semibold text-primary">
                        <i class="bi bi-plus-circle me-1"></i> Create Assessment
                    </a>
                    <a href="<?= BASE_URL ?>faculty/question-bank.php" class="btn btn-outline-light rounded-pill px-4">
                        <i class="bi bi-question-circle me-1"></i> Question Bank
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 4 Key Metrics Widgets (Modern SaaS Cards) -->
<div class="stats-grid-saas mb-4">
    <!-- Card 1: Total Students -->
    <div class="saas-stat-card primary-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Students</span>
            <div class="stat-icon-saas primary-gradient">
                <i class="bi bi-people"></i>
            </div>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value"><?= $totalStudents ?></div>
        </div>
        <div class="stat-card-footer">
            <span class="stat-card-trend trend-primary">
                <i class="bi bi-mortarboard"></i> Department Enrollees
            </span>
        </div>
    </div>

    <!-- Card 2: My Assessments -->
    <div class="saas-stat-card accent-card" style="cursor:pointer;" onclick="window.location.href='<?= BASE_URL ?>faculty/assessments.php'">
        <div class="stat-card-header">
            <span class="stat-card-title">My Assessments</span>
            <div class="stat-icon-saas accent-gradient">
                <i class="bi bi-journal-plus"></i>
            </div>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value"><?= $myAssessmentsCount ?></div>
        </div>
        <div class="stat-card-footer">
            <span class="stat-card-trend trend-accent">
                <i class="bi bi-clock-history"></i> Active Modules
            </span>
        </div>
    </div>

    <!-- Card 3: Total Submissions -->
    <div class="saas-stat-card success-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Submissions</span>
            <div class="stat-icon-saas success-gradient">
                <i class="bi bi-card-checklist"></i>
            </div>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value"><?= $totalSubmissions ?></div>
        </div>
        <div class="stat-card-footer">
            <span class="stat-card-trend trend-success">
                <i class="bi bi-check-circle"></i> Evaluated Quiz Attempts
            </span>
        </div>
    </div>

    <!-- Card 4: Class Avg Score -->
    <div class="saas-stat-card warning-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Class Avg Score</span>
            <div class="stat-icon-saas warning-gradient">
                <i class="bi bi-bar-chart-line"></i>
            </div>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value gradient-value"><?= $classAvgScore ?>%</div>
        </div>
        <div class="stat-card-footer">
            <span class="stat-card-trend trend-warning">
                <i class="bi bi-graph-up-arrow"></i> Aggregate Mean
            </span>
        </div>
    </div>
</div>

<!-- Charts & Submissions Row -->
<div class="row g-4 mb-4">
    <!-- Bar Chart -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>Assessment Performance Breakdown</h5>
                <a href="<?= BASE_URL ?>faculty/skill-gap.php" class="btn btn-light btn-sm rounded-pill text-primary">Full Analytics</a>
            </div>
            <div class="card-body p-4" style="min-height: 320px;">
                <canvas id="facultyBarCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-lightning-charge me-2 text-warning"></i>Faculty Tools</h5>
            </div>
            <div class="card-body p-4 d-flex flex-column gap-3">
                <a href="<?= BASE_URL ?>faculty/assessment-create.php" class="p-3 border rounded-3 bg-light text-decoration-none d-flex align-items-center gap-3 text-dark">
                    <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                        <i class="bi bi-plus-lg"></i>
                    </div>
                    <div>
                        <strong class="d-block">Create New Assessment</strong>
                        <span class="text-muted small">Configure skill link, passing marks, and duration.</span>
                    </div>
                </a>

                <a href="<?= BASE_URL ?>faculty/evaluate.php" class="p-3 border rounded-3 bg-light text-decoration-none d-flex align-items-center gap-3 text-dark">
                    <div class="bg-info text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                        <i class="bi bi-check2-square"></i>
                    </div>
                    <div>
                        <strong class="d-block">Evaluate Student Submissions</strong>
                        <span class="text-muted small">Review test answers and score distributions.</span>
                    </div>
                </a>

                <a href="<?= BASE_URL ?>faculty/recommend-courses.php" class="p-3 border rounded-3 bg-light text-decoration-none d-flex align-items-center gap-3 text-dark">
                    <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                        <i class="bi bi-award"></i>
                    </div>
                    <div>
                        <strong class="d-block">Assign Course Recommendations</strong>
                        <span class="text-muted small">Target specific weak skills identified in class.</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Submissions Table -->
<div class="row">
    <div class="col-12">
        <div class="saas-card overflow-hidden">
            <div class="saas-card-header">
                <h5 class="fw-bold mb-0" style="color: var(--text-heading);"><i class="bi bi-clock-history me-2 text-info"></i>Recent Student Submissions</h5>
                <a href="<?= BASE_URL ?>faculty/evaluate.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="saas-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Student</th>
                                <th>Assessment</th>
                                <th>Skill</th>
                                <th>Score %</th>
                                <th>Status</th>
                                <th>Completed On</th>
                                <th class="pe-4 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentSubmissions)): ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="saas-empty-state">
                                            <div class="saas-empty-icon"><i class="bi bi-inbox"></i></div>
                                            <h6 class="fw-bold mb-1" style="color: var(--text-heading);">No submissions yet</h6>
                                            <p class="small mb-0" style="color: var(--text-muted);">Student assessment submissions will appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentSubmissions as $sub): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-semibold d-block" style="color: var(--text-heading);"><?= htmlspecialchars($sub['first_name'] . ' ' . $sub['last_name']) ?></span>
                                            <small style="color: var(--text-muted);"><?= htmlspecialchars($sub['student_code']) ?></small>
                                        </td>
                                        <td style="color: var(--text-body);"><?= htmlspecialchars($sub['assessment_title']) ?></td>
                                        <td><span class="badge saas-badge-primary"><?= htmlspecialchars($sub['skill_name']) ?></span></td>
                                        <td><strong style="color: var(--text-heading);"><?= number_format($sub['score_percentage'], 1) ?>%</strong></td>
                                        <td>
                                            <span class="badge <?= $sub['status'] === 'pass' ? 'saas-badge-success' : 'saas-badge-danger' ?>">
                                                <?= strtoupper($sub['status']) ?>
                                            </span>
                                        </td>
                                        <td><small style="color: var(--text-muted);"><?= format_date($sub['completed_at']) ?></small></td>
                                        <td class="pe-4 text-end">
                                            <a href="<?= BASE_URL ?>faculty/evaluate.php?student_id=<?= $sub['student_id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                <i class="bi bi-search me-1"></i>Inspect
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
    </div>
</div>

<script src="<?= BASE_URL ?>assets/js/charts-config.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    renderScoreBarChart(
        'facultyBarCanvas',
        <?= json_encode($chartTitles) ?>,
        <?= json_encode($chartScores) ?>
    );
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
