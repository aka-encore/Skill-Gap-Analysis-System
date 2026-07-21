<?php
/**
 * SkillBridge - System Administrator Control Dashboard
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('admin');

$db = Database::getInstance();

// Entity Counts
$totalStudents = (int)($db->fetch("SELECT COUNT(*) as cnt FROM students")['cnt'] ?? 0);
$totalFaculty = (int)($db->fetch("SELECT COUNT(*) as cnt FROM faculty")['cnt'] ?? 0);
$totalCourses = (int)($db->fetch("SELECT COUNT(*) as cnt FROM courses")['cnt'] ?? 0);
$totalSkills = (int)($db->fetch("SELECT COUNT(*) as cnt FROM skills")['cnt'] ?? 0);
$totalAssessments = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessments")['cnt'] ?? 0);
$totalNotifications = (int)($db->fetch("SELECT COUNT(*) as cnt FROM notifications")['cnt'] ?? 0);

// Recent Audit Logs
$recentLogs = $db->fetchAll(
    "SELECT l.*, u.username, u.role 
     FROM activity_logs l 
     LEFT JOIN users u ON l.user_id = u.id 
     ORDER BY l.created_at DESC LIMIT 6"
);

// Database Stats
$dbSizeRow = $db->fetch(
    "SELECT SUM(data_length + index_length) / 1024 / 1024 as db_size_mb 
     FROM information_schema.tables 
     WHERE table_schema = ?",
    [DB_NAME]
);
$dbSizeMb = round((float)($dbSizeRow['db_size_mb'] ?? 0.5), 2);

$pageTitle = "Admin Control Panel - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<!-- Welcome Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="saas-hero-banner">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 position-relative z-1">
                <div>
                    <span class="badge bg-white-subtle text-white border border-white-subtle rounded-pill px-3 py-1.5 mb-2 small fw-semibold">
                        <i class="bi bi-shield-lock-fill me-1"></i> System Administration
                    </span>
                    <h2 class="fw-bold mb-1">SkillBridge System Operations</h2>
                    <p class="mb-0 text-white-50">Database Size: <?= $dbSizeMb ?> MB &bull; Status: Healthy &bull; Environment: Apache / PHP <?= PHP_VERSION ?></p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= BASE_URL ?>admin/reports.php" class="btn btn-light rounded-pill px-4 fw-semibold text-dark">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Reports
                    </a>
                    <a href="<?= BASE_URL ?>admin/backup.php" class="btn btn-outline-light rounded-pill px-4">
                        <i class="bi bi-database-down me-1"></i> Backup DB
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 6 Entity Widgets Grid -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="saas-stat-card primary-card h-100" style="cursor:pointer;" onclick="window.location.href='<?= BASE_URL ?>admin/students.php'">
            <div class="stat-card-header">
                <span class="stat-card-title">Students</span>
                <div class="stat-icon-saas primary-gradient">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value"><?= $totalStudents ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="saas-stat-card accent-card h-100" style="cursor:pointer;" onclick="window.location.href='<?= BASE_URL ?>admin/faculty.php'">
            <div class="stat-card-header">
                <span class="stat-card-title">Faculty</span>
                <div class="stat-icon-saas accent-gradient">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value"><?= $totalFaculty ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="saas-stat-card success-card h-100" style="cursor:pointer;" onclick="window.location.href='<?= BASE_URL ?>admin/courses.php'">
            <div class="stat-card-header">
                <span class="stat-card-title">Courses</span>
                <div class="stat-icon-saas success-gradient">
                    <i class="bi bi-book"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value"><?= $totalCourses ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="saas-stat-card warning-card h-100" style="cursor:pointer;" onclick="window.location.href='<?= BASE_URL ?>admin/skills.php'">
            <div class="stat-card-header">
                <span class="stat-card-title">Skills</span>
                <div class="stat-icon-saas warning-gradient">
                    <i class="bi bi-gear-wide-connected"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value"><?= $totalSkills ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="saas-stat-card danger-card h-100" style="cursor:pointer;" onclick="window.location.href='<?= BASE_URL ?>admin/assessments.php'">
            <div class="stat-card-header">
                <span class="stat-card-title">Tests</span>
                <div class="stat-icon-saas primary-gradient">
                    <i class="bi bi-journal-check"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value"><?= $totalAssessments ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="saas-stat-card accent-card h-100" style="cursor:pointer;" onclick="window.location.href='<?= BASE_URL ?>admin/notifications.php'">
            <div class="stat-card-header">
                <span class="stat-card-title">Alerts</span>
                <div class="stat-icon-saas accent-gradient">
                    <i class="bi bi-bell"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value"><?= $totalNotifications ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & System Info -->
<div class="row g-4 mb-4">
    <!-- Doughnut User Ratio Chart -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-pie-chart text-primary me-2"></i>User Distribution Ratio</h5>
            </div>
            <div class="card-body p-4" style="height: 300px;">
                <canvas id="userRatioCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Shortcuts -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-sliders me-2 text-warning"></i>Admin Management Center</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <a href="<?= BASE_URL ?>admin/students.php" class="btn btn-light w-100 p-3 text-center rounded-3 border text-decoration-none">
                            <i class="bi bi-people text-primary fs-3 d-block mb-1"></i>
                            <strong class="text-dark small d-block">Manage Students</strong>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= BASE_URL ?>admin/faculty.php" class="btn btn-light w-100 p-3 text-center rounded-3 border text-decoration-none">
                            <i class="bi bi-person-badge text-info fs-3 d-block mb-1"></i>
                            <strong class="text-dark small d-block">Manage Faculty</strong>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= BASE_URL ?>admin/courses.php" class="btn btn-light w-100 p-3 text-center rounded-3 border text-decoration-none">
                            <i class="bi bi-book text-success fs-3 d-block mb-1"></i>
                            <strong class="text-dark small d-block">Manage Courses</strong>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= BASE_URL ?>admin/skills.php" class="btn btn-light w-100 p-3 text-center rounded-3 border text-decoration-none">
                            <i class="bi bi-gear-wide-connected text-warning fs-3 d-block mb-1"></i>
                            <strong class="text-dark small d-block">Manage Skills</strong>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= BASE_URL ?>admin/notifications.php" class="btn btn-light w-100 p-3 text-center rounded-3 border text-decoration-none">
                            <i class="bi bi-megaphone text-danger fs-3 d-block mb-1"></i>
                            <strong class="text-dark small d-block">Announcements</strong>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= BASE_URL ?>admin/settings.php" class="btn btn-light w-100 p-3 text-center rounded-3 border text-decoration-none">
                            <i class="bi bi-sliders text-dark fs-3 d-block mb-1"></i>
                            <strong class="text-dark small d-block">System Settings</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activity Audit Trail -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-info"></i>System Audit Activity Trail</h5>
                <a href="<?= BASE_URL ?>admin/activity-logs.php" class="btn btn-light btn-sm rounded-pill text-primary">View Full Logs</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Role</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th class="pe-4 text-end">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentLogs as $log): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark"><?= htmlspecialchars($log['username'] ?? 'System') ?></td>
                                    <td><span class="badge bg-secondary"><?= strtoupper($log['role'] ?? 'SYSTEM') ?></span></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($log['action']) ?></span></td>
                                    <td class="small text-muted"><?= htmlspecialchars($log['description']) ?></td>
                                    <td class="small font-monospace text-muted"><?= htmlspecialchars($log['ip_address']) ?></td>
                                    <td class="pe-4 text-end small text-muted"><?= format_date($log['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
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
    new Chart(document.getElementById('userRatioCanvas'), {
        type: 'doughnut',
        data: {
            labels: ['Students', 'Faculty', 'Admins'],
            datasets: [{
                data: [<?= $totalStudents ?>, <?= $totalFaculty ?>, 1],
                backgroundColor: ['#6366f1', '#06b6d4', '#1e293b']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
