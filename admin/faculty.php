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

        if ($action === 'delete') {
            $fc = $db->fetch("SELECT user_id FROM faculty WHERE id = ?", [$facId]);
            if ($fc) {
                $db->delete('users', 'id = ?', [$fc['user_id']]);
                $success = 'Faculty member account deleted.';
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

$facultyList = $db->fetchAll(
    "SELECT f.*, u.username, u.email 
     FROM faculty f 
     JOIN users u ON f.user_id = u.id 
     ORDER BY f.employee_code ASC"
);

$pageTitle = "Manage Faculty - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-person-badge text-info me-2"></i>Faculty Accounts Management</h3>
        <p class="text-muted small mb-0">Create, edit, and oversee faculty credentials</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#createFacultyModal">
        <i class="bi bi-person-plus me-1"></i> Add Faculty Account
    </button>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="saas-card overflow-hidden">
    <div class="saas-card-header flex-wrap gap-2">
        <div class="position-relative" style="min-width: 250px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" class="saas-form-control ps-5 py-2 w-100" placeholder="Search faculty..." data-search-table="adminFacultyTable">
        </div>
        <span class="badge saas-badge-info">Total Members: <?= count($facultyList) ?></span>
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
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($facultyList)): ?>
                        <tr>
                            <td colspan="6">
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
                                <td class="pe-4 text-end">
                                    <form action="<?= BASE_URL ?>admin/faculty.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this faculty account?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action_type" value="delete">
                                        <input type="hidden" name="faculty_id" value="<?= $fc['id'] ?>">
                                        <button type="submit" class="saas-btn-action danger" title="Delete Faculty Account">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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
