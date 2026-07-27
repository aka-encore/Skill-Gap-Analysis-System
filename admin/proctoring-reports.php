<?php
/**
 * SkillBridge - Admin AI Proctoring Reports Dashboard
 * Centralized list of all proctored assessment attempts, warnings, and AI integrity risk levels.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('admin');

$db = Database::getInstance();

$search = trim($_GET['search'] ?? '');
$sql = "SELECT ps.*, ar.score_percentage, ar.status as test_status, ar.completed_at,
               a.title as assessment_title,
               s.first_name, s.last_name, s.student_code, s.department
        FROM assessment_proctoring_summaries ps
        JOIN assessment_results ar ON ps.result_id = ar.id
        JOIN assessments a ON ar.assessment_id = a.id
        JOIN students s ON ar.student_id = s.id
        WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (s.first_name LIKE ? OR s.last_name LIKE ? OR s.student_code LIKE ? OR a.title LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY ar.completed_at DESC LIMIT 100";
$reports = $db->fetchAll($sql, $params);

$pageTitle = "AI Proctoring Audits - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-shield-halved text-danger me-2"></i>AI Proctoring Integrity Audits</h3>
        <p class="text-muted small mb-0">Review real-time AI proctoring summary reports and suspicious activity logs for all student attempts.</p>
    </div>
</div>

<!-- Filter search bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-3">
        <form action="<?= BASE_URL ?>admin/proctoring-reports.php" method="GET" class="row g-2 align-items-center">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by student name, code, or assessment title..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary bg-gradient-primary border-0 w-100 rounded-3">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Proctoring summaries table -->
<div class="saas-card overflow-hidden">
    <div class="saas-card-header flex-wrap gap-2">
        <h5 class="fw-bold text-dark mb-0">Security Audits Archive</h5>
        <span class="badge saas-badge-primary">Total Attempts: <?= count($reports) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="saas-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Student</th>
                        <th>Department</th>
                        <th>Assessment</th>
                        <th>Score</th>
                        <th>Total Warnings</th>
                        <th>Integrity Risk</th>
                        <th>Completed At</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reports)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="saas-empty-state text-center py-5">
                                    <div class="saas-empty-icon mb-2"><i class="fa-solid fa-shield-halved text-muted display-4"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">No proctoring reports recorded</h6>
                                    <p class="text-muted small mb-0">Attempts proctored with webcam and browser focus tracking will be listed here.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reports as $rep): 
                            $risk = $rep['risk_level'];
                            $riskBadge = 'bg-success';
                            if ($risk === 'High Risk') {
                                $riskBadge = 'bg-danger';
                            } elseif ($risk === 'Medium Risk') {
                                $riskBadge = 'bg-warning text-dark';
                            }
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($rep['first_name'] . ' ' . $rep['last_name']) ?></div>
                                    <span class="badge bg-light text-secondary border small mt-1"><?= htmlspecialchars($rep['student_code']) ?></span>
                                </td>
                                <td><?= htmlspecialchars($rep['department']) ?></td>
                                <td class="fw-medium text-dark" style="max-width: 250px;"><?= htmlspecialchars($rep['assessment_title']) ?></td>
                                <td>
                                    <strong class="text-dark"><?= number_format($rep['score_percentage'], 1) ?>%</strong>
                                    <span class="badge <?= $rep['test_status'] === 'pass' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> small ms-1" style="font-size: 10px;"><?= strtoupper($rep['test_status']) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= $rep['total_violations'] ?> Warnings</span>
                                </td>
                                <td>
                                    <span class="badge <?= $riskBadge ?> d-inline-flex align-items-center gap-1 shadow-xs">
                                        <i class="fa-solid fa-shield-halved"></i> <?= htmlspecialchars($risk) ?>
                                    </span>
                                </td>
                                <td class="small text-muted"><?= format_date($rep['completed_at']) ?></td>
                                <td class="pe-4 text-end">
                                    <a href="<?= BASE_URL ?>admin/proctoring-report.php?result_id=<?= $rep['result_id'] ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i> Inspect Logs
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

<?php include __DIR__ . '/../includes/footer.php'; ?>
