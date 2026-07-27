<?php
/**
 * SkillBridge - Admin Proctoring Report Inspector
 * Displays detailed AI integrity checks and suspicious activity audit timelines to system administrators.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('admin');

$resultId = (int)($_GET['result_id'] ?? 0);
$db = Database::getInstance();

// Fetch attempt details
$attempt = $db->fetch(
    "SELECT ar.*, a.title as assessment_title, 
            s.id as student_id, s.first_name, s.last_name, s.student_code, s.department
     FROM assessment_results ar
     JOIN assessments a ON ar.assessment_id = a.id
     JOIN students s ON ar.student_id = s.id
     WHERE ar.id = ?",
    [$resultId]
);

if (!$attempt) {
    set_flash_message('danger', 'Proctoring report result not found.');
    redirect(BASE_URL . 'admin/proctoring-reports.php');
}

// Fetch proctoring summary
$summary = $db->fetch(
    "SELECT * FROM assessment_proctoring_summaries WHERE result_id = ?",
    [$resultId]
);

// Fetch proctoring logs
$logs = $db->fetchAll(
    "SELECT * FROM assessment_proctoring_logs WHERE result_id = ? ORDER BY created_at ASC",
    [$resultId]
);

$pageTitle = "Proctoring Report: " . $attempt['first_name'] . " " . $attempt['last_name'];
include __DIR__ . '/../includes/header.php';

// Default summary if none exists
if (!$summary) {
    $summary = [
        'total_violations' => 0,
        'phone_violations' => 0,
        'face_missing_violations' => 0,
        'multiple_face_violations' => 0,
        'tab_switch_violations' => 0,
        'focus_loss_violations' => 0,
        'camera_disconnect_violations' => 0,
        'risk_level' => 'Low Risk'
    ];
}

$risk = $summary['risk_level'];
$riskColor = 'text-success';
$riskBg = 'rgba(16, 185, 129, 0.1)';
$riskBorder = 'rgba(16, 185, 129, 0.3)';
$riskIcon = 'fa-circle-check';
$riskMsg = 'Secure Attempt: No critical anomalies detected during this proctored session.';

if ($risk === 'High Risk') {
    $riskColor = 'text-danger';
    $riskBg = 'rgba(239, 68, 68, 0.1)';
    $riskBorder = 'rgba(239, 68, 68, 0.3)';
    $riskIcon = 'fa-triangle-exclamation';
    $riskMsg = 'High Integrity Risk: Multiple violations or suspicious patterns identified.';
} elseif ($risk === 'Medium Risk') {
    $riskColor = 'text-warning';
    $riskBg = 'rgba(245, 158, 11, 0.1)';
    $riskBorder = 'rgba(245, 158, 11, 0.3)';
    $riskIcon = 'fa-circle-exclamation';
    $riskMsg = 'Medium Integrity Risk: Some minor guidelines were breached during the session.';
}
?>

<!-- Action Navigation -->
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <a href="<?= BASE_URL ?>admin/proctoring-reports.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Proctoring List
    </a>
    <button onclick="window.print()" class="btn btn-sm btn-outline-dark rounded-pill px-3">
        <i class="bi bi-printer me-1"></i> Print / Export Report
    </button>
</div>

<!-- Header Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1 mb-2"><?= htmlspecialchars($attempt['student_code']) ?></span>
                <h3 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($attempt['first_name'] . ' ' . $attempt['last_name']) ?></h3>
                <p class="text-muted small mb-0"><?= htmlspecialchars($attempt['department']) ?> &bull; Assessment: <strong><?= htmlspecialchars($attempt['assessment_title']) ?></strong></p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-inline-block text-center border-start ps-md-4">
                    <span class="small text-muted d-block mb-1">Score Obtained</span>
                    <span class="fs-2 fw-bold <?= $attempt['status'] === 'pass' ? 'text-success' : 'text-danger' ?>"><?= number_format($attempt['score_percentage'], 1) ?>%</span>
                    <span class="badge <?= $attempt['status'] === 'pass' ? 'bg-success' : 'bg-danger' ?> rounded-pill ms-2"><?= strtoupper($attempt['status']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Risk Panel -->
<div class="card border-0 shadow-sm rounded-4 mb-4" style="background: <?= $riskBg ?>; border: 1px solid <?= $riskBorder ?> !important;">
    <div class="card-body p-4 d-flex align-items-center gap-3">
        <div class="fs-1 <?= $riskColor ?>"><i class="fa-solid <?= $riskIcon ?>"></i></div>
        <div>
            <h5 class="fw-bold mb-1 <?= $riskColor ?>"><?= strtoupper($risk) ?> ASSESSMENT INTEGRITY</h5>
            <p class="mb-0 text-dark small" style="opacity: 0.85;"><?= $riskMsg ?></p>
        </div>
    </div>
</div>

<!-- Breakdown Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100 bg-white">
            <span class="text-muted small text-uppercase d-block mb-1" style="font-size: 10px;">Total Warnings</span>
            <span class="fs-3 fw-bold text-dark"><?= $summary['total_violations'] ?></span>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100 bg-white">
            <span class="text-muted small text-uppercase d-block mb-1" style="font-size: 10px;">Mobile Phones</span>
            <span class="fs-3 fw-bold <?= $summary['phone_violations'] > 0 ? 'text-danger' : 'text-success' ?>"><?= $summary['phone_violations'] ?></span>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100 bg-white">
            <span class="text-muted small text-uppercase d-block mb-1" style="font-size: 10px;">Face Absences</span>
            <span class="fs-3 fw-bold <?= $summary['face_missing_violations'] > 0 ? 'text-danger' : 'text-success' ?>"><?= $summary['face_missing_violations'] ?></span>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100 bg-white">
            <span class="text-muted small text-uppercase d-block mb-1" style="font-size: 10px;">Multiple Faces</span>
            <span class="fs-3 fw-bold <?= $summary['multiple_face_violations'] > 0 ? 'text-danger' : 'text-success' ?>"><?= $summary['multiple_face_violations'] ?></span>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100 bg-white">
            <span class="text-muted small text-uppercase d-block mb-1" style="font-size: 10px;">Tab Switches</span>
            <span class="fs-3 fw-bold <?= $summary['tab_switch_violations'] > 0 ? 'text-danger' : 'text-success' ?>"><?= $summary['tab_switch_violations'] ?></span>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100 bg-white">
            <span class="text-muted small text-uppercase d-block mb-1" style="font-size: 10px;">Camera Errors</span>
            <span class="fs-3 fw-bold <?= $summary['camera_disconnect_violations'] > 0 ? 'text-danger' : 'text-success' ?>"><?= $summary['camera_disconnect_violations'] ?></span>
        </div>
    </div>
</div>

<!-- Detailed Logs Timeline -->
<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-header bg-white border-0 py-3 px-4">
        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Audit Timeline & Activity Logs</h5>
    </div>
    <div class="card-body p-4">
        <?php if (empty($logs)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-clipboard-question display-4 mb-3"></i>
                <h6>No proctoring logs recorded.</h6>
                <p class="small mb-0">This attempt may have bypassed proctoring or completed without violations.</p>
            </div>
        <?php else: ?>
            <div class="timeline-container" style="position: relative; padding-left: 30px;">
                <div class="timeline-line" style="position: absolute; left: 9px; top: 10px; bottom: 10px; width: 2px; background: #e2e8f0;"></div>
                
                <?php foreach ($logs as $log): 
                    $badgeClass = 'bg-secondary';
                    $iconClass = 'fa-circle';
                    
                    if (str_contains($log['event_type'], 'Started') || str_contains($log['event_type'], 'Enabled') || str_contains($log['event_type'], 'Reconnected') || str_contains($log['event_type'], 'Restored')) {
                        $badgeClass = 'bg-success';
                        $iconClass = 'fa-check';
                    } elseif (str_contains($log['event_type'], 'Submitted')) {
                        $badgeClass = 'bg-primary';
                        $iconClass = 'fa-paper-plane';
                    } else {
                        $badgeClass = 'bg-danger';
                        $iconClass = 'fa-triangle-exclamation';
                    }
                ?>
                    <div class="timeline-item mb-4" style="position: relative;">
                        <!-- Icon Dot -->
                        <div class="timeline-dot rounded-circle d-flex align-items-center justify-content-center text-white <?= $badgeClass ?>" 
                             style="position: absolute; left: -30px; top: 2px; width: 20px; height: 20px; font-size: 10px; z-index: 100;">
                            <i class="fa-solid <?= $iconClass ?>"></i>
                        </div>
                        
                        <!-- Event Description -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2 ps-1">
                            <div>
                                <span class="badge bg-light text-dark border fw-bold mb-1"><?= htmlspecialchars($log['event_type']) ?></span>
                                <p class="mb-0 small text-dark fw-medium"><?= htmlspecialchars($log['description']) ?></p>
                            </div>
                            <span class="small font-monospace text-muted" style="font-size: 0.8rem;"><?= date('M d, Y H:i:s', strtotime($log['created_at'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
