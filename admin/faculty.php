<?php
/**
 * SkillBridge - Admin Faculty Account Management CRUD
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('admin');

$db = Database::getInstance();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type'])) {
    if (!verify_csrf_token()) {
        $error = 'Invalid CSRF token.';
    } else {
        $action = $_POST['action_type'];
        $facId = (int)($_POST['faculty_id'] ?? 0);

        if ($action === 'suspend') {
            $fc = $db->fetch("SELECT user_id, first_name, last_name FROM faculty WHERE id = ?", [$facId]);
            if ($fc) {
                $db->update('users', ['status' => 'suspended'], 'id = ?', [$fc['user_id']]);
                $adminName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin';
                log_activity($_SESSION['user_id'], 'SUSPEND_ACCOUNT', "Admin {$adminName} suspended Faculty account for {$fc['first_name']} {$fc['last_name']} (User ID: {$fc['user_id']}).");
                $success = 'Faculty account has been suspended.';
            }
        } elseif ($action === 'unsuspend') {
            $fc = $db->fetch("SELECT user_id, first_name, last_name FROM faculty WHERE id = ?", [$facId]);
            if ($fc) {
                $db->update('users', ['status' => 'active'], 'id = ?', [$fc['user_id']]);
                $adminName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin';
                log_activity($_SESSION['user_id'], 'UNSUSPEND_ACCOUNT', "Admin {$adminName} reactivated Faculty account for {$fc['first_name']} {$fc['last_name']} (User ID: {$fc['user_id']}).");
                $success = 'Faculty account has been reactivated.';
            }
        } elseif ($action === 'create') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $dept = trim($_POST['department'] ?? 'Computer Science');
            $designation = trim($_POST['designation'] ?? 'Assistant Professor');
            $password = $_POST['password'] ?? 'Password123!';

            if (empty($firstName) || empty($username) || empty($email)) {
                $error = 'First Name, Username, and Email are required.';
            } else {
                $exists = $db->fetch("SELECT id FROM users WHERE username = ? OR email = ?", [$username, $email]);
                if ($exists) {
                    $error = 'Username or Email is already registered.';
                } else {
                    $db->beginTransaction();
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $uId = $db->insert('users', [
                        'username' => $username,
                        'email' => $email,
                        'password' => $hash,
                        'role' => 'faculty',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $code = 'FAC-' . (100 + $uId);
                    $db->insert('faculty', [
                        'user_id' => $uId,
                        'employee_code' => $code,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'department' => $dept,
                        'designation' => $designation,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    $db->commit();
                    $success = "Faculty member Dr./Prof. {$firstName} {$lastName} added ({$code}).";
                }
            }
        }
    }
}

$filter = $_GET['status'] ?? 'all';
if (!in_array($filter, ['all', 'active', 'suspended'])) {
    $filter = 'all';
}

$query = "SELECT f.*, u.username, u.email, u.status as user_status 
          FROM faculty f 
          JOIN users u ON f.user_id = u.id";

if ($filter === 'active') {
    $query .= " WHERE u.status = 'active'";
} elseif ($filter === 'suspended') {
    $query .= " WHERE u.status = 'suspended'";
}

$query .= " ORDER BY f.employee_code ASC";
$facultyList = $db->fetchAll($query);

$pageTitle = "Manage Faculty - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-person-badge text-info me-2"></i>Faculty Accounts Management</h3>
        <p class="text-muted small mb-0">Create, edit, and oversee faculty credentials</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>admin/faculty-import.php" class="btn btn-outline-primary rounded-pill px-3.5 py-2 shadow-sm small fw-semibold">
            <i class="bi bi-cloud-arrow-up me-1"></i> Import Faculty
        </a>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#createFacultyModal">
            <i class="bi bi-person-plus me-1"></i> Add Faculty Account
        </button>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="saas-card overflow-hidden">
    <div class="saas-card-header flex-wrap gap-3">
        <div class="position-relative" style="min-width: 250px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" class="saas-form-control ps-5 py-2 w-100" placeholder="Search faculty..." data-search-table="adminFacultyTable">
        </div>
        <div class="d-flex align-items-center gap-1 bg-light p-1 rounded-pill border">
            <a href="?status=all" class="btn btn-xs rounded-pill px-3 py-1 fw-semibold small text-decoration-none <?= $filter === 'all' ? 'btn-info bg-gradient-info text-dark border-0' : 'text-secondary' ?>" style="font-size: 11px;">All</a>
            <a href="?status=active" class="btn btn-xs rounded-pill px-3 py-1 fw-semibold small text-decoration-none <?= $filter === 'active' ? 'btn-info bg-gradient-info text-dark border-0' : 'text-secondary' ?>" style="font-size: 11px;">Active</a>
            <a href="?status=suspended" class="btn btn-xs rounded-pill px-3 py-1 fw-semibold small text-decoration-none <?= $filter === 'suspended' ? 'btn-info bg-gradient-info text-dark border-0' : 'text-secondary' ?>" style="font-size: 11px;">Suspended</a>
        </div>
        <span class="badge saas-badge-info ms-auto">Total Members: <?= count($facultyList) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="saas-table align-middle mb-0" id="adminFacultyTable">
                <thead>
                    <tr>
                        <th class="ps-4">Code</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($facultyList)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="saas-empty-state">
                                    <div class="saas-empty-icon"><i class="bi bi-person-badge"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">No faculty members found</h6>
                                    <p class="text-muted small mb-0">Click "Add Faculty Account" to register faculty members.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($facultyList as $fc): ?>
                            <tr>
                                <td class="ps-4"><span class="badge saas-badge-primary"><?= htmlspecialchars($fc['employee_code']) ?></span></td>
                                <td class="fw-semibold text-dark"><?= htmlspecialchars($fc['first_name'] . ' ' . $fc['last_name']) ?></td>
                                <td><span class="badge saas-badge-info"><?= htmlspecialchars($fc['designation']) ?></span></td>
                                <td><?= htmlspecialchars($fc['department']) ?></td>
                                <td><span class="small text-muted"><?= htmlspecialchars($fc['email']) ?></span></td>
                                <td>
                                    <?php if (($fc['user_status'] ?? 'active') === 'suspended'): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill"><i class="bi bi-x-circle me-1"></i>Suspended</span>
                                    <?php else: ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-check-circle me-1"></i>Active</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <?php if (($fc['user_status'] ?? 'active') === 'suspended'): ?>
                                        <form action="<?= BASE_URL ?>admin/faculty.php" method="POST" class="d-inline" onsubmit="return confirm('Reactivate this faculty account?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action_type" value="unsuspend">
                                            <input type="hidden" name="faculty_id" value="<?= $fc['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3.5 py-1 fw-bold" style="font-size: 11px;">
                                                <i class="bi bi-unlock-fill me-1"></i> Reactivate
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3.5 py-1 fw-bold" data-bs-toggle="modal" data-bs-target="#suspendModal<?= $fc['id'] ?>" style="font-size: 11px;">
                                            <i class="bi bi-lock me-1"></i> Suspend
                                        </button>
                                        
                                        <!-- Suspend Confirmation Modal -->
                                        <div class="modal fade text-start" id="suspendModal<?= $fc['id'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-4">
                                                    <div class="modal-header border-bottom-0 pb-0">
                                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-lock text-danger me-2"></i>Suspend Account</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?= BASE_URL ?>admin/faculty.php" method="POST">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="action_type" value="suspend">
                                                        <input type="hidden" name="faculty_id" value="<?= $fc['id'] ?>">
                                                        <div class="modal-body py-3">
                                                            <p class="text-secondary small mb-0">
                                                                Are you sure you want to suspend the account of <strong>Dr./Prof. <?= htmlspecialchars($fc['first_name'] . ' ' . $fc['last_name']) ?></strong>?
                                                                <br><br>
                                                                This user will no longer be able to access learning features until their account is reactivated.
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-3.5 py-1.5 small fw-semibold" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger rounded-pill px-4 py-1.5 small fw-semibold">Suspend Account</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Faculty Modal -->
<div class="modal fade" id="createFacultyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold">Add Faculty Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>admin/faculty.php" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action_type" value="create">

                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">First Name *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Username *</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Department</label>
                            <input type="text" name="department" class="form-control" value="Computer Science">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Designation</label>
                            <select name="designation" class="form-select">
                                <option value="Assistant Professor">Assistant Professor</option>
                                <option value="Associate Professor">Associate Professor</option>
                                <option value="Professor & HOD">Professor & HOD</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Defaults to Password123! if blank">
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info rounded-pill px-4 text-dark fw-semibold">Create Faculty</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
