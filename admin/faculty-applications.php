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
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-user-clock text-primary me-2"></i>Faculty Registration Applications</h3>
        <p class="text-muted small mb-0">Review, approve, and manage faculty registration requests across institutions</p>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3 small border-0 mb-4 shadow-xs"><?= $error ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2 px-3 small border-0 mb-4 shadow-xs"><?= $success ?></div>
<?php endif; ?>

<!-- Summary Metrics Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3">
            <span class="text-muted small d-block mb-1 fw-semibold">Total Applications</span>
            <h3 class="fw-bold text-dark mb-0"><?= $totalApps ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-warning border-4">
            <span class="text-muted small d-block mb-1 fw-semibold">Pending Review</span>
            <h3 class="fw-bold text-warning mb-0"><?= $pendingApps ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-success border-4">
            <span class="text-muted small d-block mb-1 fw-semibold">Approved</span>
            <h3 class="fw-bold text-success mb-0"><?= $approvedApps ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-danger border-4">
            <span class="text-muted small d-block mb-1 fw-semibold">Rejected</span>
            <h3 class="fw-bold text-danger mb-0"><?= $rejectedApps ?></h3>
        </div>
    </div>
</div>

<!-- Search & Filter Controls -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3.5">
        <form action="<?= BASE_URL ?>admin/faculty-applications.php" method="GET" class="row g-3 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, college..." value="<?= htmlspecialchars($searchKeyword) ?>">
                </div>
            </div>
            <div class="col-6 col-md-2.5">
                <select name="status" class="form-select">
                    <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Statuses</option>
                    <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending Only</option>
                    <option value="approved" <?= $statusFilter === 'approved' ? 'selected' : '' ?>>Approved Only</option>
                    <option value="rejected" <?= $statusFilter === 'rejected' ? 'selected' : '' ?>>Rejected Only</option>
                </select>
            </div>
            <div class="col-6 col-md-2.5">
                <select name="department" class="form-select">
                    <option value="all">All Departments</option>
                    <?php foreach ($departmentsList as $d): ?>
                        <option value="<?= htmlspecialchars($d) ?>" <?= $deptFilter === $d ? 'selected' : '' ?>><?= htmlspecialchars($d) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="sort" class="form-select">
                    <option value="newest" <?= $sortOrder === 'DESC' ? 'selected' : '' ?>>Newest First</option>
                    <option value="oldest" <?= $sortOrder === 'ASC' ? 'selected' : '' ?>>Oldest First</option>
                </select>
            </div>
            <div class="col-6 col-md-1 text-end">
                <button type="submit" class="btn btn-primary w-100 rounded-3">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Applications Data Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <?php if (empty($applications)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                <p class="text-secondary mb-0">No faculty applications matching the criteria.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Faculty Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>College Name</th>
                            <th>Department & Title</th>
                            <th>Employee ID</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <?php 
                                $status = strtolower($app['approval_status']); 
                                $statusBadge = match($status) {
                                    'approved' => '<span class="badge bg-success-subtle text-success rounded-pill px-3 py-1"><i class="bi bi-check-circle me-1"></i> Approved</span>',
                                    'rejected' => '<span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1"><i class="bi bi-x-circle me-1"></i> Rejected</span>',
                                    default    => '<span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1"><i class="bi bi-clock-history me-1"></i> Pending Review</span>'
                                };
                                $fullName = trim($app['first_name'] . ' ' . $app['last_name']);
                                if (empty($fullName)) $fullName = $app['username'];
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <strong class="text-dark d-block"><?= htmlspecialchars($fullName) ?></strong>
                                    <span class="text-muted small">@<?= htmlspecialchars($app['username']) ?></span>
                                </td>
                                <td class="text-secondary"><?= htmlspecialchars($app['email']) ?></td>
                                <td class="text-secondary"><?= htmlspecialchars($app['mobile_number'] ?? 'N/A') ?></td>
                                <td class="fw-semibold text-dark"><?= htmlspecialchars($app['college_name'] ?? 'SkillBridge University') ?></td>
                                <td>
                                    <span class="d-block fw-semibold text-dark"><?= htmlspecialchars($app['department']) ?></span>
                                    <span class="text-muted small"><?= htmlspecialchars($app['designation']) ?></span>
                                </td>
                                <td><code class="text-primary"><?= htmlspecialchars($app['employee_code']) ?></code></td>
                                <td class="text-muted small"><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                                <td><?= $statusBadge ?></td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button type="button" class="btn btn-outline-secondary btn-xs rounded-pill px-2.5 py-1" 
                                                onclick='openDetailsModal(<?= json_encode($app) ?>)'>
                                            <i class="bi bi-eye me-1"></i> Details
                                        </button>

                                        <?php if ($status !== 'approved'): ?>
                                            <form action="<?= BASE_URL ?>admin/faculty-applications.php" method="POST" class="d-inline" onsubmit="return confirm('Approve faculty application for <?= htmlspecialchars(addslashes($fullName)) ?>?')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="faculty_id" value="<?= $app['id'] ?>">
                                                <button type="submit" class="btn btn-success btn-xs rounded-pill px-2.5 py-1">
                                                    <i class="bi bi-check-lg me-1"></i> Approve
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($status !== 'rejected'): ?>
                                            <button type="button" class="btn btn-danger btn-xs rounded-pill px-2.5 py-1" 
                                                    onclick="openRejectModal(<?= $app['id'] ?>, '<?= htmlspecialchars(addslashes($fullName)) ?>')">
                                                <i class="bi bi-x-lg me-1"></i> Reject
                                            </button>
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
