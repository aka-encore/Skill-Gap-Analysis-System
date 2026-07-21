<?php
/**
 * SkillBridge - Admin Profile & Settings Manager
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('admin');

$userId = $_SESSION['user_id'];
$adminId = $_SESSION['profile_id'];
$db = Database::getInstance();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type'])) {
    if (!verify_csrf_token()) {
        $error = 'Invalid CSRF token.';
    } else {
        $action = $_POST['action_type'];

        if ($action === 'update_profile') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $department = trim($_POST['department'] ?? 'IT & Operations');

            if (empty($firstName) || empty($lastName)) {
                $error = 'First Name and Last Name are required.';
            } else {
                $avatarFileName = $_SESSION['avatar'] ?? 'default-avatar.png';

                if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
                    $fileTmp = $_FILES['avatar_file']['tmp_name'];
                    $fileName = $_FILES['avatar_file']['name'];
                    $fileSize = $_FILES['avatar_file']['size'];
                    $fileType = mime_content_type($fileTmp);

                    if ($fileSize > MAX_FILE_SIZE) {
                        $error = 'Avatar image file size must be less than 5MB.';
                    } elseif (!in_array($fileType, ALLOWED_IMAGE_TYPES)) {
                        $error = 'Invalid image type. Only JPG, PNG, and WebP are allowed.';
                    } else {
                        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                        $newAvatarName = 'avatar_user_' . $userId . '_' . time() . '.' . $ext;
                        if (move_uploaded_file($fileTmp, AVATAR_UPLOAD_DIR . $newAvatarName)) {
                            $avatarFileName = $newAvatarName;
                            $_SESSION['avatar'] = $newAvatarName;
                        }
                    }
                }

                if (empty($error)) {
                    $db->update('admins', [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'department' => $department,
                        'avatar' => $avatarFileName
                    ], 'id = ?', [$adminId]);

                    $_SESSION['full_name'] = "$firstName $lastName";
                    $success = 'Administrator profile updated successfully.';
                }
            }
        } elseif ($action === 'change_password') {
            $currentPass = $_POST['current_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';
            $confirmPass = $_POST['confirm_password'] ?? '';

            $user = $db->fetch("SELECT password FROM users WHERE id = ?", [$userId]);
            if (!password_verify($currentPass, $user['password'])) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($newPass) < 6) {
                $error = 'New password must be at least 6 characters long.';
            } elseif ($newPass !== $confirmPass) {
                $error = 'Passwords do not match.';
            } else {
                $hash = password_hash($newPass, PASSWORD_BCRYPT);
                $db->update('users', ['password' => $hash], 'id = ?', [$userId]);
                $success = 'Password updated successfully.';
            }
        }
    }
}

$profile = get_user_profile_data($userId, 'admin');
$avatarUrl = BASE_URL . 'uploads/avatars/' . ($profile['avatar'] ?? 'default-avatar.png');

$pageTitle = "Admin Profile - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-person-gear text-primary me-2"></i>Administrator Profile</h3>
        <p class="text-muted small mb-0">Update account credentials and security preferences</p>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0">Personal Profile</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>admin/profile.php" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action_type" value="update_profile">

                    <div class="d-flex align-items-center gap-4 mb-4">
                        <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="Avatar" class="rounded-circle object-fit-cover border shadow-xs" width="80" height="80">
                        <div>
                            <label class="form-label fw-semibold small text-secondary">Profile Avatar</label>
                            <input type="file" name="avatar_file" class="form-control form-control-sm" accept="image/*">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-secondary">Department</label>
                        <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($profile['department'] ?? 'IT & Operations') ?>">
                    </div>

                    <button type="submit" class="btn btn-primary bg-gradient-primary border-0 rounded-pill px-4">
                        Save Profile Changes
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4" id="change-password">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0">Change Admin Password</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>admin/profile.php#change-password" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action_type" value="change_password">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-semibold">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4">
            <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="Avatar" class="rounded-circle mx-auto mb-3 object-fit-cover border shadow-sm" width="100" height="100">
            <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? '')) ?></h5>
            <span class="badge bg-dark border rounded-pill px-3 py-1 text-uppercase mx-auto mb-3" style="width:fit-content;">Administrator</span>
            <hr>
            <div class="text-start small text-muted d-flex flex-column gap-2">
                <div><strong>Username:</strong> <?= htmlspecialchars($profile['username'] ?? '') ?></div>
                <div><strong>Email:</strong> <?= htmlspecialchars($profile['email'] ?? '') ?></div>
                <div><strong>Role:</strong> System Administrator</div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
