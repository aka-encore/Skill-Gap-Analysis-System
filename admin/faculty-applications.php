<?php
/**
 * SkillBridge - Faculty Registration Applications Management
 * Admin Module for reviewing, approving, and rejecting Faculty registration applications.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('admin');

$db = Database::getInstance();
$adminUserId = $_SESSION['user_id'] ?? 0;
$error = '';
$success = '';

// Handle POST actions (Approve / Reject)
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } else {
        $action = $_POST['action'] ?? '';
        $facId = (int)($_POST['faculty_id'] ?? 0);

        $facRecord = $db->fetch("SELECT f.*, u.email, u.username FROM faculty f JOIN users u ON f.user_id = u.id WHERE f.id = ?", [$facId]);

        if (!$facRecord) {
            $error = 'Faculty application record not found.';
        } else {
            $fullName = trim($facRecord['first_name'] . ' ' . $facRecord['last_name']);
            if (empty($fullName)) $fullName = $facRecord['username'];
            $email = $facRecord['email'];

            if ($action === 'approve') {
                $db->update('faculty', [
                    'approval_status' => 'approved',
                    'approval_date'   => date('Y-m-d H:i:s'),
                    'approved_by'     => $adminUserId
                ], 'id = ?', [$facId]);

                $db->update('users', [
                    'status' => 'active'
                ], 'id = ?', [$facRecord['user_id']]);

                // Dispatch SMTP Approval Email
                $mailRes = send_faculty_approval_email($email, $fullName);
                log_activity($adminUserId, 'FACULTY_APPLICATION_APPROVED', "Approved faculty application #{$facId} ({$fullName})");

                $success = "Faculty application for <strong>" . htmlspecialchars($fullName) . "</strong> has been APPROVED! Notification email sent.";
            } elseif ($action === 'reject') {
                $reason = trim($_POST['rejection_reason'] ?? '');
                $db->update('faculty', [
                    'approval_status'  => 'rejected',
                    'rejection_reason' => $reason
                ], 'id = ?', [$facId]);

                $db->update('users', [
                    'status' => 'rejected'
                ], 'id = ?', [$facRecord['user_id']]);

                // Dispatch SMTP Rejection Email
                $mailRes = send_faculty_rejection_email($email, $fullName, $reason);
                log_activity($adminUserId, 'FACULTY_APPLICATION_REJECTED', "Rejected faculty application #{$facId} ({$fullName})");

                $success = "Faculty application for <strong>" . htmlspecialchars($fullName) . "</strong> has been REJECTED. Notification email sent.";
            }
        }
    }
}

// Search & Filter Query Construction
$searchKeyword = trim($_GET['search'] ?? '');
$statusFilter = strtolower(trim($_GET['status'] ?? 'all'));
$deptFilter = trim($_GET['department'] ?? 'all');
$sortOrder = strtolower(trim($_GET['sort'] ?? 'newest')) === 'oldest' ? 'ASC' : 'DESC';

$whereClauses = [];
$params = [];

if (!empty($searchKeyword)) {
    $whereClauses[] = "(f.first_name LIKE ? OR f.last_name LIKE ? OR u.email LIKE ? OR f.college_name LIKE ? OR f.employee_code LIKE ?)";
    $term = '%' . $searchKeyword . '%';
    $params[] = $term; $params[] = $term; $params[] = $term; $params[] = $term; $params[] = $term;
}

if (in_array($statusFilter, ['pending', 'approved', 'rejected'])) {
    $whereClauses[] = "f.approval_status = ?";
    $params[] = $statusFilter;
}

if ($deptFilter !== 'all' && !empty($deptFilter)) {
    $whereClauses[] = "f.department = ?";
    $params[] = $deptFilter;
}

$whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

$applications = $db->fetchAll("
    SELECT f.*, u.email, u.username, u.status as user_status 
    FROM faculty f 
    JOIN users u ON f.user_id = u.id 
    {$whereSql} 
    ORDER BY f.created_at {$sortOrder}
", $params);

// Calculate Metrics Summary
$totalApps    = (int)($db->fetch("SELECT COUNT(*) as cnt FROM faculty")['cnt'] ?? 0);
$pendingApps  = (int)($db->fetch("SELECT COUNT(*) as cnt FROM faculty WHERE approval_status = 'pending'")['cnt'] ?? 0);
$approvedApps = (int)($db->fetch("SELECT COUNT(*) as cnt FROM faculty WHERE approval_status = 'approved'")['cnt'] ?? 0);
$rejectedApps = (int)($db->fetch("SELECT COUNT(*) as cnt FROM faculty WHERE approval_status = 'rejected'")['cnt'] ?? 0);

// Fetch distinct departments for filter dropdown
$departmentsList = array_column($db->fetchAll("SELECT DISTINCT department FROM faculty WHERE department IS NOT NULL AND department != ''"), 'department');

$pageTitle = "Faculty Applications – Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(139, 92, 246, 0.15); border: 1px solid rgba(139, 92, 246, 0.3); color: #a78bfa; font-size: 1.5rem;">
            <i class="bi bi-file-earmark-text"></i>
        </div>
        <div>
            <h3 class="fw-bold mb-0" style="color: var(--text-heading);">Faculty Applications</h3>
            <p class="text-muted small mb-0">Review, approve, and manage faculty registration requests across institutions</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>admin/faculty-applications.php?export=csv" class="btn btn-primary rounded-3 px-3 py-2 shadow-sm small fw-semibold" style="background: #6366f1; border-color: #6366f1;">
        <i class="bi bi-box-arrow-up-right me-1.5"></i> Export Applications
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4 shadow-xs"><i class="bi bi-exclamation-triangle me-1"></i> <?= $error ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2.5 px-3 small border-0 rounded-3 mb-4 shadow-xs"><i class="bi bi-check-circle me-1"></i> <?= $success ?></div>
<?php endif; ?>

<!-- Summary Metrics Row (SaaS Card Grid System) -->
<div class="stats-grid-saas mb-4">
    <!-- Card 1: Total Applications -->
    <div class="saas-stat-card primary-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Applications</span>
            <div class="stat-icon-saas primary-gradient">
                <i class="bi bi-people"></i>
            </div>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value"><?= $totalApps ?></div>
        </div>
        <div class="stat-card-footer">
            <span class="badge rounded-pill" style="background: rgba(139, 92, 246, 0.15); color: #a78bfa; border: 1px solid rgba(139, 92, 246, 0.3); font-weight: 600; padding: 4px 10px; font-size: 0.75rem;">
                <i class="bi bi-file-person me-1"></i> Submitted Requests
            </span>
        </div>
    </div>

    <!-- Card 2: Pending Review -->
    <div class="saas-stat-card accent-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Pending Review</span>
            <div class="stat-icon-saas accent-gradient">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value"><?= $pendingApps ?></div>
        </div>
        <div class="stat-card-footer">
            <span class="badge rounded-pill" style="background: rgba(99, 102, 241, 0.15); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.3); font-weight: 600; padding: 4px 10px; font-size: 0.75rem;">
                <i class="bi bi-hourglass-split me-1"></i> Awaiting Approval
            </span>
        </div>
    </div>

    <!-- Card 3: Approved -->
    <div class="saas-stat-card success-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Approved</span>
            <div class="stat-icon-saas success-gradient">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value"><?= $approvedApps ?></div>
        </div>
        <div class="stat-card-footer">
            <span class="badge rounded-pill" style="background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: 600; padding: 4px 10px; font-size: 0.75rem;">
                <i class="bi bi-person-check me-1"></i> Active Faculty
            </span>
        </div>
    </div>

    <!-- Card 4: Rejected -->
    <div class="saas-stat-card danger-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Rejected</span>
            <div class="stat-icon-saas danger-gradient">
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value"><?= $rejectedApps ?></div>
        </div>
        <div class="stat-card-footer">
            <span class="badge rounded-pill" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 600; padding: 4px 10px; font-size: 0.75rem;">
                <i class="bi bi-slash-circle me-1"></i> Declined Requests
            </span>
        </div>
    </div>
</div>

<!-- Search & Filter Controls Card -->
<div class="saas-card overflow-hidden mb-4">
    <div class="card-body p-3.5">
        <form action="<?= BASE_URL ?>admin/faculty-applications.php" method="GET" class="d-flex flex-column gap-3">
            <div class="row g-3">
                <div class="col-12 col-md-8">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="search" class="saas-form-control ps-5 py-2 w-100" placeholder="Search by name, email, college, or employee code..." value="<?= htmlspecialchars($searchKeyword) ?>">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <select name="status" class="saas-form-select py-2" onchange="this.form.submit()">
                        <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Statuses</option>
                        <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending Only</option>
                        <option value="approved" <?= $statusFilter === 'approved' ? 'selected' : '' ?>>Approved Only</option>
                        <option value="rejected" <?= $statusFilter === 'rejected' ? 'selected' : '' ?>>Rejected Only</option>
                    </select>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-4">
                    <select name="department" class="saas-form-select py-2" onchange="this.form.submit()">
                        <option value="all">All Departments</option>
                        <?php foreach ($departmentsList as $d): ?>
                            <option value="<?= htmlspecialchars($d) ?>" <?= $deptFilter === $d ? 'selected' : '' ?>><?= htmlspecialchars($d) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="sort" class="saas-form-select py-2" onchange="this.form.submit()">
                        <option value="newest" <?= $sortOrder === 'DESC' ? 'selected' : '' ?>>Newest First</option>
                        <option value="oldest" <?= $sortOrder === 'ASC' ? 'selected' : '' ?>>Oldest First</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Applications Data Table -->
<div class="saas-card overflow-hidden">
    <div class="card-body p-0">
        <?php if (empty($applications)): ?>
            <div class="saas-empty-state py-5">
                <div class="saas-empty-icon mb-3"><i class="bi bi-inbox"></i></div>
                <h6 class="fw-bold mb-1" style="color: var(--text-heading);">No applications matching the criteria</h6>
                <p class="text-muted small mb-0">Try clearing filters or adjusting your search keyword.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="saas-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">APPLICANT</th>
                            <th>DEPARTMENT & DESIGNATION</th>
                            <th>SUBMITTED ON</th>
                            <th>STATUS</th>
                            <th>COLLEGE & EMP CODE</th>
                            <th class="text-end pe-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <?php 
                                $status = strtolower($app['approval_status']); 
                                $statusBadge = match($status) {
                                    'approved' => '<span class="badge rounded-pill" style="background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.35); font-weight: 600; padding: 5px 14px; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;"><i class="bi bi-check-circle"></i> Approved</span>',
                                    'rejected' => '<span class="badge rounded-pill" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.35); font-weight: 600; padding: 5px 14px; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;"><i class="bi bi-x-circle"></i> Rejected</span>',
                                    default    => '<span class="badge rounded-pill" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); font-weight: 600; padding: 5px 14px; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;"><i class="bi bi-clock"></i> Pending</span>'
                                };
                                $fullName = trim($app['first_name'] . ' ' . $app['last_name']);
                                if (empty($fullName)) $fullName = $app['username'];

                                // Initials for Avatar
                                $parts = explode(' ', $fullName);
                                $initials = strtoupper(substr($parts[0] ?? 'A', 0, 1) . substr($parts[1] ?? ($parts[0] ?? 'B'), 0, 1));
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar-initials" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(139, 92, 246, 0.2); color: #c084fc; border: 1px solid rgba(139, 92, 246, 0.35); font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 0.82rem; flex-shrink: 0;"><?= $initials ?></div>
                                        <div>
                                            <strong class="d-block" style="color: var(--text-heading); font-size: 0.9rem;"><?= htmlspecialchars($fullName) ?></strong>
                                            <span class="text-muted small"><?= htmlspecialchars($app['email']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-block fw-semibold" style="color: var(--text-heading);"><?= htmlspecialchars($app['department']) ?></span>
                                    <span class="badge rounded-pill" style="font-size: 11px; padding: 4px 10px; background: rgba(139, 92, 246, 0.15); color: #c084fc; border: 1px solid rgba(139, 92, 246, 0.3);"><?= htmlspecialchars($app['designation']) ?></span>
                                </td>
                                <td class="small text-muted">
                                    <i class="bi bi-calendar-event me-1"></i><?= date('M d, Y h:i A', strtotime($app['created_at'])) ?>
                                </td>
                                <td><?= $statusBadge ?></td>
                                <td>
                                    <span class="d-block fw-semibold text-truncate" style="color: var(--text-heading); max-width: 220px;" title="<?= htmlspecialchars($app['college_name'] ?? 'SkillBridge Institution') ?>"><?= htmlspecialchars($app['college_name'] ?? 'SkillBridge Institution') ?></span>
                                    <code class="text-primary small" style="color: #60a5fa !important;"><?= htmlspecialchars($app['employee_code']) ?></code>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1.5 align-items-center">
                                        <button type="button" class="btn-action-square" 
                                                onclick='openDetailsModal(<?= json_encode($app) ?>)' title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <?php if ($status !== 'approved'): ?>
                                            <form action="<?= BASE_URL ?>admin/faculty-applications.php" method="POST" class="d-inline" onsubmit="return confirm('Approve faculty application for <?= htmlspecialchars(addslashes($fullName)) ?>?')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="faculty_id" value="<?= $app['id'] ?>">
                                                <button type="submit" class="btn-action-square btn-action-success" title="Approve Application">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="btn-action-square disabled opacity-50"><i class="bi bi-dash"></i></span>
                                        <?php endif; ?>

                                        <?php if ($status !== 'rejected'): ?>
                                            <button type="button" class="btn-action-square btn-action-danger" 
                                                    onclick="openRejectModal(<?= $app['id'] ?>, '<?= htmlspecialchars(addslashes($fullName)) ?>')" title="Reject Application">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        <?php else: ?>
                                            <span class="btn-action-square disabled opacity-50"><i class="bi bi-dash"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-lines-fill text-primary me-2"></i>Faculty Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="detailsModalContent">
                <!-- Dynamic Content Loaded via JS -->
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Reason Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Reject Faculty Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>admin/faculty-applications.php" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="reject">
                <input type="hidden" name="faculty_id" id="rejectFacultyId">

                <div class="modal-body p-4">
                    <p class="text-secondary small mb-3">Rejecting application for: <strong class="text-dark" id="rejectFacultyName"></strong></p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Rejection Reason (Optional)</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Provide reason for rejection (will be emailed to the applicant)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDetailsModal(app) {
    const fullName = (app.first_name || '') + ' ' + (app.last_name || '') || app.username;
    const baseUrl = '<?= BASE_URL ?>';
    
    let idCardHtml = app.id_card_file 
        ? `<a href="${baseUrl}uploads/faculty_docs/${app.id_card_file}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill"><i class="bi bi-file-earmark-person me-1"></i> View Faculty ID Card</a>`
        : `<span class="text-muted small">Not Uploaded</span>`;

    let appointmentHtml = app.appointment_letter_file 
        ? `<a href="${baseUrl}uploads/faculty_docs/${app.appointment_letter_file}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill"><i class="bi bi-file-earmark-text me-1"></i> View Appointment Letter</a>`
        : `<span class="text-muted small">Not Uploaded</span>`;

    let rejectionHtml = app.rejection_reason 
        ? `<div class="p-3 bg-danger-subtle border border-danger-subtle rounded-3 mb-3"><strong class="text-danger small d-block">Rejection Reason:</strong><span class="text-dark small">${app.rejection_reason}</span></div>`
        : '';

    const content = `
        ${rejectionHtml}
        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block">Full Name</span>
                    <strong class="text-dark">${fullName}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block">Email Address</span>
                    <strong class="text-dark">${app.email}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block">Mobile Number</span>
                    <strong class="text-dark">${app.mobile_number || 'N/A'}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block">College / Institution</span>
                    <strong class="text-dark">${app.college_name || 'SkillBridge University'}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block">Department</span>
                    <strong class="text-dark">${app.department}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block">Designation</span>
                    <strong class="text-dark">${app.designation}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block">Employee ID</span>
                    <strong class="text-primary">${app.employee_code}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block">Years of Experience</span>
                    <strong class="text-dark">${app.experience_years || 0} Years</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block mb-1">Faculty ID Card Document</span>
                    ${idCardHtml}
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block mb-1">Appointment Letter Document</span>
                    ${appointmentHtml}
                </div>
            </div>
        </div>
    `;

    document.getElementById('detailsModalContent').innerHTML = content;
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
}

function openRejectModal(id, name) {
    document.getElementById('rejectFacultyId').value = id;
    document.getElementById('rejectFacultyName').textContent = name;

    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>