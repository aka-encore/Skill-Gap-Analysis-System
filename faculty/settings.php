<?php
/**
 * SkillBridge - Faculty Account & Preferences Settings
 * Project-tailored settings page with 7 core sections: Profile, Account, Security,
 * Appearance, Notifications, Privacy, and Preferences.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('faculty');
check_suspended_status();

$facultyId = $_SESSION['profile_id'];
$userId    = $_SESSION['user_id'];
$db        = Database::getInstance();

// Fetch current authenticated faculty & user record first
$faculty = $db->fetch(
    "SELECT f.*, u.username, u.email, u.role, u.password as user_pw, u.created_at as user_created, u.status as user_status
     FROM faculty f 
     JOIN users u ON f.user_id = u.id 
     WHERE f.id = ?",
    [$facultyId]
);

if (!$faculty) {
    set_flash_message('danger', 'Faculty profile record not found.');
    redirect(BASE_URL . 'login.php');
}

// 1. Process Post Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        set_flash_message('danger', 'Invalid security token.');
        redirect(BASE_URL . 'faculty/settings.php');
    }

    $action = $_POST['action'] ?? '';

    // A. Profile Settings Update
    if ($action === 'update_profile') {
        $firstName      = trim($_POST['first_name'] ?? '');
        $lastName       = trim($_POST['last_name'] ?? '');
        $displayName    = trim($_POST['display_name'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $mobileNumber   = trim($_POST['mobile_number'] ?? '');
        $department     = trim($_POST['department'] ?? '');
        $designation    = trim($_POST['designation'] ?? '');
        $bio            = trim($_POST['bio'] ?? '');
        $officeLocation = trim($_POST['office_location'] ?? '');

        // Validation: First & Last Name
        if (empty($firstName) || empty($lastName)) {
            set_flash_message('danger', 'First name and last name are required.');
            redirect(BASE_URL . 'faculty/settings.php?tab=profile');
        } elseif (!preg_match("/^[a-zA-Z\s\-]+$/", $firstName)) {
            set_flash_message('danger', 'First name cannot contain numbers or special characters.');
            redirect(BASE_URL . 'faculty/settings.php?tab=profile');
        } elseif (!preg_match("/^[a-zA-Z\s\-]+$/", $lastName)) {
            set_flash_message('danger', 'Last name cannot contain numbers or special characters.');
            redirect(BASE_URL . 'faculty/settings.php?tab=profile');
        }

        // Validation: Email
        if (empty($email)) {
            set_flash_message('danger', 'Email address is required.');
            redirect(BASE_URL . 'faculty/settings.php?tab=profile');
        } elseif (!validate_email($email)) {
            set_flash_message('danger', 'Please enter a valid email address.');
            redirect(BASE_URL . 'faculty/settings.php?tab=profile');
        } else {
            // Check email uniqueness
            $existingEmail = $db->fetch("SELECT id FROM users WHERE email = ? AND id != ?", [$email, $userId]);
            if ($existingEmail) {
                set_flash_message('danger', 'This email address is already in use by another account.');
                redirect(BASE_URL . 'faculty/settings.php?tab=profile');
            }
        }

        // Validation: Mobile Number (10 digits)
        if (!empty($mobileNumber)) {
            if (!preg_match('/^[0-9]{10}$/', $mobileNumber)) {
                set_flash_message('danger', 'Mobile number must contain exactly 10 digits.');
                redirect(BASE_URL . 'faculty/settings.php?tab=profile');
            }
        }

        // Handle Profile Picture upload & Removal
        $avatarName = $faculty['avatar'] ?? 'default-avatar.png';
        $avatarUploaded = false;

        if (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] === '1') {
            if (!empty($faculty['avatar']) && $faculty['avatar'] !== 'default-avatar.png') {
                $oldFile = AVATAR_UPLOAD_DIR . $faculty['avatar'];
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }
            $avatarName = 'default-avatar.png';
            $_SESSION['avatar'] = 'default-avatar.png';
            $avatarUploaded = true;
        } elseif (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['avatar_file']['tmp_name'];
            $origName = $_FILES['avatar_file']['name'];
            $size = $_FILES['avatar_file']['size'];
            $mime = mime_content_type($tmp);

            if ($size > MAX_FILE_SIZE) {
                set_flash_message('danger', 'Profile picture size must be less than 5MB.');
                redirect(BASE_URL . 'faculty/settings.php?tab=profile');
            } elseif (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
                set_flash_message('danger', 'Invalid file format. Please upload a JPG, PNG, or WebP image.');
                redirect(BASE_URL . 'faculty/settings.php?tab=profile');
            } else {
                $ext = pathinfo($origName, PATHINFO_EXTENSION);
                $newFilename = 'avatar_user_' . $userId . '_' . time() . '.' . strtolower($ext);
                $dest = AVATAR_UPLOAD_DIR . $newFilename;

                if (!file_exists(AVATAR_UPLOAD_DIR)) {
                    @mkdir(AVATAR_UPLOAD_DIR, 0777, true);
                }

                if (move_uploaded_file($tmp, $dest)) {
                    // Clean up previous custom avatar
                    if (!empty($faculty['avatar']) && $faculty['avatar'] !== 'default-avatar.png') {
                        $oldFile = AVATAR_UPLOAD_DIR . $faculty['avatar'];
                        if (file_exists($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                    $avatarName = $newFilename;
                    $_SESSION['avatar'] = $newFilename;
                    $avatarUploaded = true;
                }
            }
        }

        // Detect real changes
        $hasChanged = (
            $firstName !== ($faculty['first_name'] ?? '') ||
            $lastName !== ($faculty['last_name'] ?? '') ||
            $displayName !== ($faculty['display_name'] ?? '') ||
            $email !== ($faculty['email'] ?? '') ||
            $mobileNumber !== ($faculty['mobile_number'] ?? '') ||
            $department !== ($faculty['department'] ?? '') ||
            $designation !== ($faculty['designation'] ?? '') ||
            $bio !== ($faculty['bio'] ?? '') ||
            $officeLocation !== ($faculty['office_location'] ?? '') ||
            $avatarUploaded
        );

        if (!$hasChanged) {
            set_flash_message('info', 'No changes were made to your profile settings.');
        } else {
            // Update users table email
            $db->update('users', ['email' => $email], 'id = ?', [$userId]);

            // Update faculty table
            $db->update('faculty', [
                'first_name'      => $firstName,
                'last_name'       => $lastName,
                'display_name'    => $displayName,
                'mobile_number'   => $mobileNumber,
                'department'      => $department,
                'designation'     => $designation,
                'bio'             => $bio,
                'office_location' => $officeLocation,
                'avatar'          => $avatarName
            ], 'id = ?', [$facultyId]);

            // Update sessions
            $_SESSION['user_email'] = $email;
            $_SESSION['full_name']  = trim($firstName . ' ' . $lastName);
            $_SESSION['user_name']  = 'Prof. ' . $firstName . ' ' . $lastName;

            set_flash_message('success', 'Profile settings updated successfully.');
        }
        redirect(BASE_URL . 'faculty/settings.php?tab=profile');
    }

    // B. Account Settings Update
    if ($action === 'update_account') {
        $username = trim($_POST['username'] ?? '');

        if (empty($username)) {
            set_flash_message('danger', 'Username cannot be empty.');
            redirect(BASE_URL . 'faculty/settings.php?tab=account');
        } elseif (!preg_match("/^[a-zA-Z0-9_\.]+$/", $username)) {
            set_flash_message('danger', 'Username can only contain letters, numbers, underscores, and periods.');
            redirect(BASE_URL . 'faculty/settings.php?tab=account');
        }

        // Check uniqueness
        $existingUser = $db->fetch("SELECT id FROM users WHERE username = ? AND id != ?", [$username, $userId]);
        if ($existingUser) {
            set_flash_message('danger', 'This username is already taken.');
            redirect(BASE_URL . 'faculty/settings.php?tab=account');
        }

        if ($username === ($faculty['username'] ?? '')) {
            set_flash_message('info', 'No changes were made to your account settings.');
        } else {
            $db->update('users', ['username' => $username], 'id = ?', [$userId]);
            $_SESSION['username'] = $username;
            set_flash_message('success', 'Account username updated successfully.');
        }
        redirect(BASE_URL . 'faculty/settings.php?tab=account');
    }

    // C. Security settings (Password Update)
    if ($action === 'update_security') {
        $currPassword = $_POST['current_password'] ?? '';
        $newPassword  = $_POST['new_password'] ?? '';
        $confPassword = $_POST['confirm_password'] ?? '';

        if (!password_verify($currPassword, $faculty['user_pw'])) {
            set_flash_message('danger', 'Current password is incorrect.');
        } elseif ($newPassword === $currPassword || password_verify($newPassword, $faculty['user_pw'])) {
            set_flash_message('danger', 'New password must be different from current password.');
        } elseif ($newPassword !== $confPassword) {
            set_flash_message('danger', 'New password and confirmation password do not match.');
        } elseif (strlen($newPassword) < 6) {
            set_flash_message('danger', 'New password must be at least 6 characters long.');
        } elseif (!preg_match('/[A-Z]/', $newPassword)) {
            set_flash_message('danger', 'New password must contain at least one uppercase letter.');
        } elseif (!preg_match('/[a-z]/', $newPassword)) {
            set_flash_message('danger', 'New password must contain at least one lowercase letter.');
        } elseif (!preg_match('/[0-9]/', $newPassword)) {
            set_flash_message('danger', 'New password must contain at least one number.');
        } elseif (!preg_match('/[^A-Za-z0-9]/', $newPassword)) {
            set_flash_message('danger', 'New password must contain at least one special character.');
        } else {
            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $db->update('users', ['password' => $newHash], 'id = ?', [$userId]);
            set_flash_message('success', 'Security password updated successfully.');
        }
        redirect(BASE_URL . 'faculty/settings.php?tab=security');
    }

    // D. Notification settings update
    if ($action === 'update_notifications') {
        $notifAssessment = isset($_POST['notif_assessment']) ? 1 : 0;
        $notifSubmission = isset($_POST['notif_submission']) ? 1 : 0;
        $notifSystem     = isset($_POST['notif_system']) ? 1 : 0;
        $notifEmail      = isset($_POST['notif_email']) ? 1 : 0;
        $notifBrowser    = isset($_POST['notif_browser']) ? 1 : 0;

        $hasChanged = (
            $notifAssessment !== (int)($faculty['notif_assessment'] ?? 1) ||
            $notifSubmission !== (int)($faculty['notif_submission'] ?? 1) ||
            $notifSystem     !== (int)($faculty['notif_system'] ?? 1) ||
            $notifEmail      !== (int)($faculty['notif_email'] ?? 1) ||
            $notifBrowser    !== (int)($faculty['notif_browser'] ?? 1)
        );

        if (!$hasChanged) {
            set_flash_message('info', 'No changes were made to notification preferences.');
        } else {
            $db->update('faculty', [
                'notif_assessment' => $notifAssessment,
                'notif_submission' => $notifSubmission,
                'notif_system'     => $notifSystem,
                'notif_email'      => $notifEmail,
                'notif_browser'    => $notifBrowser
            ], 'id = ?', [$facultyId]);

            set_flash_message('success', 'Notification preferences updated successfully.');
        }
        redirect(BASE_URL . 'faculty/settings.php?tab=notifications');
    }

    // E. Privacy settings update
    if ($action === 'update_privacy') {
        $privVisibility = isset($_POST['priv_profile_visibility']) ? 1 : 0;
        $privShowEmail  = isset($_POST['priv_show_email']) ? 1 : 0;
        $privShowMobile = isset($_POST['priv_show_mobile']) ? 1 : 0;
        $privShowDept   = isset($_POST['priv_show_department']) ? 1 : 0;
        $privShowDesig  = isset($_POST['priv_show_designation']) ? 1 : 0;

        $hasChanged = (
            $privVisibility !== (int)($faculty['priv_profile_visibility'] ?? 1) ||
            $privShowEmail  !== (int)($faculty['priv_show_email'] ?? 1) ||
            $privShowMobile !== (int)($faculty['priv_show_mobile'] ?? 1) ||
            $privShowDept   !== (int)($faculty['priv_show_department'] ?? 1) ||
            $privShowDesig  !== (int)($faculty['priv_show_designation'] ?? 1)
        );

        if (!$hasChanged) {
            set_flash_message('info', 'No changes were made to privacy settings.');
        } else {
            $db->update('faculty', [
                'priv_profile_visibility' => $privVisibility,
                'priv_show_email'          => $privShowEmail,
                'priv_show_mobile'         => $privShowMobile,
                'priv_show_department'     => $privShowDept,
                'priv_show_designation'    => $privShowDesig
            ], 'id = ?', [$facultyId]);

            set_flash_message('success', 'Privacy settings updated successfully.');
        }
        redirect(BASE_URL . 'faculty/settings.php?tab=privacy');
    }

    // F. Preferences update
    if ($action === 'update_preferences') {
        $prefDashboard = trim($_POST['pref_dashboard'] ?? 'faculty/dashboard.php');
        $prefView      = trim($_POST['pref_assessment_view'] ?? 'grid');
        $prefLanguage  = trim($_POST['pref_language'] ?? 'en');
        $prefTimezone  = trim($_POST['pref_timezone'] ?? 'Asia/Kolkata');

        $hasChanged = (
            $prefDashboard !== ($faculty['pref_dashboard'] ?? 'faculty/dashboard.php') ||
            $prefView      !== ($faculty['pref_assessment_view'] ?? 'grid') ||
            $prefLanguage  !== ($faculty['pref_language'] ?? 'en') ||
            $prefTimezone  !== ($faculty['pref_timezone'] ?? 'Asia/Kolkata')
        );

        if (!$hasChanged) {
            set_flash_message('info', 'No changes were made to platform preferences.');
        } else {
            $db->update('faculty', [
                'pref_dashboard'       => $prefDashboard,
                'pref_assessment_view' => $prefView,
                'pref_language'        => $prefLanguage,
                'pref_timezone'        => $prefTimezone
            ], 'id = ?', [$facultyId]);

            set_flash_message('success', 'Platform preferences updated successfully.');
        }
        redirect(BASE_URL . 'faculty/settings.php?tab=preferences');
    }
}

// Re-fetch faculty record for display rendering
$faculty = $db->fetch(
    "SELECT f.*, u.username, u.email, u.role, u.created_at as user_created, u.status as user_status
     FROM faculty f 
     JOIN users u ON f.user_id = u.id 
     WHERE f.id = ?",
    [$facultyId]
);

$facultyName = htmlspecialchars(($faculty['first_name'] ?? 'Faculty') . ' ' . ($faculty['last_name'] ?? ''));

// Fetch Last Login from activity logs (excluding current session if applicable)
$lastLoginRow = $db->fetch(
    "SELECT created_at FROM activity_logs WHERE user_id = ? AND action = 'LOGIN' ORDER BY created_at DESC LIMIT 1 OFFSET 1",
    [$userId]
);
$lastLogin = $lastLoginRow ? format_date($lastLoginRow['created_at']) : 'First Session / Today';

$pageTitle = "Faculty Portal Settings - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<style>
/* Settings Navigation and Premium Styles */
.nav-link-custom {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 12px 16px;
    border: none;
    background: transparent;
    border-radius: 12px;
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.9rem;
    text-align: left;
    transition: all 0.25s ease;
    margin-bottom: 4px;
}
.nav-link-custom:hover {
    background: var(--bg-alt);
    color: var(--primary);
    transform: translateX(4px);
}
.nav-link-custom.active {
    background: var(--primary-light);
    color: var(--primary);
    border-left: 4px solid var(--primary);
    border-radius: 0 12px 12px 0;
}
[data-theme="dark"] .nav-link-custom.active {
    background: rgba(38, 101, 140, 0.2);
}
.settings-tab-content {
    display: none;
}
.settings-tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.avatar-wrapper-settings {
    position: relative;
    width: 110px;
    height: 110px;
}
.avatar-wrapper-settings img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>

<div class="dash-content">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
      <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-gear text-primary me-2"></i>Faculty Settings</h2>
      <p class="text-muted small mb-0">Manage your profile information, password, theme preferences, and platform choices.</p>
    </div>
    <a href="<?= BASE_URL ?>faculty/profile.php" class="btn btn-outline-primary rounded-pill px-3 py-1.5 small fw-semibold">
      <i class="fa-solid fa-user-circle me-1"></i> View Profile
    </a>
  </div>

  <div class="row g-4">
    <!-- LEFT COLUMN: SIDE NAVIGATION -->
    <div class="col-lg-3 col-md-4">
      <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
        <div class="d-flex flex-column nav-settings-pills">
          <button class="nav-link-custom active" data-tab="profile">
            <i class="fa-solid fa-circle-user me-2 text-primary"></i>Profile Settings
          </button>
          <button class="nav-link-custom" data-tab="account">
            <i class="fa-solid fa-id-card me-2 text-info"></i>Account Settings
          </button>
          <button class="nav-link-custom" data-tab="security">
            <i class="fa-solid fa-shield-halved me-2 text-warning"></i>Security & Passwords
          </button>
          <button class="nav-link-custom" data-tab="appearance">
            <i class="fa-solid fa-palette me-2 text-success"></i>Appearance (Theme)
          </button>
          <button class="nav-link-custom" data-tab="notifications">
            <i class="fa-solid fa-bell me-2 text-danger"></i>Notifications
          </button>
          <button class="nav-link-custom" data-tab="privacy">
            <i class="fa-solid fa-lock me-2 text-secondary"></i>Privacy Options
          </button>
          <button class="nav-link-custom" data-tab="preferences">
            <i class="fa-solid fa-sliders me-2 text-teal"></i>System Preferences
          </button>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN: DYNAMIC TABS -->
    <div class="col-lg-9 col-md-8">
      <!-- ============================================
           1. PROFILE SETTINGS
           ============================================ -->
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white settings-tab-content" id="tab-profile">
        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-circle-user text-primary me-2"></i>Profile Settings</h5>
        <p class="text-muted small mb-4">Update your personal identification, bio, contact details, and location details.</p>

        <form action="<?= BASE_URL ?>faculty/settings.php?tab=profile" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-3">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="update_profile">

          <!-- Avatar Section -->
          <div class="d-flex align-items-center gap-4 flex-wrap pb-3 border-bottom">
            <?php 
              $avatarPath = resolve_avatar_url($faculty['avatar'] ?? '', 'faculty');
            ?>
            <div class="avatar-wrapper-settings rounded-circle overflow-hidden border border-3 border-primary shadow-xs">
              <img src="<?= $avatarPath ?>" alt="Avatar" id="settingsAvatarPreview">
            </div>
            <div>
              <label class="form-label small fw-semibold text-muted d-block">AVATAR IMAGE</label>
              <input type="file" name="avatar_file" id="avatarFileInput" class="form-control rounded-3" accept="image/jpeg,image/png,image/webp">
              <?php if (!empty($faculty['avatar']) && $faculty['avatar'] !== 'default-avatar.png'): ?>
                <div class="form-check mt-2">
                  <input class="form-check-input" type="checkbox" name="remove_avatar" id="remove_avatar" value="1">
                  <label class="form-check-label text-danger small fw-semibold" for="remove_avatar">
                    Remove current photo (revert to default)
                  </label>
                </div>
              <?php endif; ?>
              <div class="text-muted small mt-1.5" style="font-size: 11px;">Accepted formats: JPG, PNG, WebP (Max size: 5MB)</div>
            </div>
          </div>

          <!-- Name Rows -->
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">FIRST NAME <span class="text-danger">*</span></label>
              <input type="text" name="first_name" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['first_name'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">LAST NAME <span class="text-danger">*</span></label>
              <input type="text" name="last_name" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['last_name'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">DISPLAY NAME</label>
              <input type="text" name="display_name" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['display_name'] ?? '') ?>" placeholder="e.g. Prof. Alan Turing">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">EMPLOYEE ID (Read-only)</label>
              <input type="text" class="form-control rounded-3 bg-light text-muted" value="<?= htmlspecialchars($faculty['employee_code'] ?? 'N/A') ?>" readonly>
            </div>
          </div>

          <!-- Designation & Department -->
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">DESIGNATION <span class="text-danger">*</span></label>
              <input type="text" name="designation" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['designation'] ?? 'Professor') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">DEPARTMENT <span class="text-danger">*</span></label>
              <select name="department" class="form-select rounded-3">
                <option value="Computer Science" <?= ($faculty['department'] ?? '') === 'Computer Science' ? 'selected' : '' ?>>Computer Science</option>
                <option value="Information Technology" <?= ($faculty['department'] ?? '') === 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
                <option value="Software Engineering" <?= ($faculty['department'] ?? '') === 'Software Engineering' ? 'selected' : '' ?>>Software Engineering</option>
                <option value="Data Science" <?= ($faculty['department'] ?? '') === 'Data Science' ? 'selected' : '' ?>>Data Science</option>
              </select>
            </div>
          </div>

          <!-- Contact info -->
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">EMAIL ADDRESS <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['email'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">MOBILE NUMBER (10-digit)</label>
              <input type="text" name="mobile_number" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['mobile_number'] ?? '') ?>" placeholder="e.g. 9876543210" maxlength="10" pattern="[0-9]{10}">
            </div>
          </div>

          <!-- Bio & Office Location -->
          <div class="mb-1">
            <label class="form-label small fw-semibold text-muted">OFFICE LOCATION</label>
            <input type="text" name="office_location" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['office_location'] ?? '') ?>" placeholder="e.g. Block C, Room 402">
          </div>

          <div class="mb-1">
            <label class="form-label small fw-semibold text-muted">BIO / ABOUT ME</label>
            <textarea name="bio" rows="4" class="form-control rounded-3" placeholder="Tell students or colleagues about your research, interests, or background..."><?= htmlspecialchars($faculty['bio'] ?? '') ?></textarea>
          </div>

          <div class="pt-2">
            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold small shadow-xs">
              <i class="fa-solid fa-floppy-disk me-1"></i> Save Profile Details
            </button>
          </div>
        </form>
      </div>

      <!-- ============================================
           2. ACCOUNT SETTINGS
           ============================================ -->
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white settings-tab-content" id="tab-account">
        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-id-card text-info me-2"></i>Account Settings</h5>
        <p class="text-muted small mb-4">View institutional system details and manage your unique platform username.</p>

        <form action="<?= BASE_URL ?>faculty/settings.php?tab=account" method="POST" class="d-flex flex-column gap-3">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="update_account">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">USERNAME <span class="text-danger">*</span></label>
              <input type="text" name="username" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['username'] ?? '') ?>" required>
              <div class="text-muted" style="font-size: 11px; margin-top: 4px;">Allowed: letters, numbers, underscores, and periods.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">FACULTY MEMBER CODE (Read-only)</label>
              <input type="text" class="form-control rounded-3 bg-light text-muted" value="<?= htmlspecialchars($faculty['employee_code'] ?? 'N/A') ?>" readonly>
            </div>
          </div>

          <div class="p-3 bg-light rounded-3 border d-flex flex-column gap-2 small text-secondary mt-2">
            <div class="d-flex justify-content-between pb-2 border-bottom">
              <span>Account Joined Date</span>
              <strong class="text-dark"><?= format_date($faculty['user_created'], 'M d, Y') ?></strong>
            </div>
            <div class="d-flex justify-content-between pb-2 border-bottom">
              <span>Last Login Action</span>
              <strong class="text-dark"><?= $lastLogin ?></strong>
            </div>
            <div class="d-flex justify-content-between">
              <span>Current Status</span>
              <span class="badge bg-success-subtle text-success border text-uppercase px-2.5 rounded-pill"><?= htmlspecialchars($faculty['user_status'] ?? 'active') ?></span>
            </div>
          </div>

          <div class="pt-2">
            <button type="submit" class="btn btn-info text-white rounded-pill px-4 fw-semibold small shadow-xs">
              <i class="fa-solid fa-floppy-disk me-1"></i> Update Account Username
            </button>
          </div>
        </form>
      </div>

      <!-- ============================================
           3. SECURITY (PASSWORD CHANGE)
           ============================================ -->
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white settings-tab-content" id="tab-security">
        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-shield-halved text-warning me-2"></i>Security & Passwords</h5>
        <p class="text-muted small mb-4">Ensure your academic credentials remain protected by updating your password regularly.</p>

        <form action="<?= BASE_URL ?>faculty/settings.php?tab=security" method="POST" class="d-flex flex-column gap-3" id="securityForm">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="update_security">

          <div>
            <label class="form-label small fw-semibold text-muted">CURRENT PASSWORD</label>
            <div class="input-group">
              <input type="password" name="current_password" id="currPassword" class="form-control rounded-start-3" placeholder="Enter current password" required>
              <button type="button" class="btn btn-outline-secondary rounded-end-3" onclick="togglePwVisibility('currPassword', this)">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>

          <div>
            <label class="form-label small fw-semibold text-muted">NEW PASSWORD</label>
            <div class="input-group">
              <input type="password" name="new_password" id="newPassword" class="form-control rounded-start-3" placeholder="Enter secure password" oninput="checkPwStrength(this.value)" required>
              <button type="button" class="btn btn-outline-secondary rounded-end-3" onclick="togglePwVisibility('newPassword', this)">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
            <!-- Strength Indicator -->
            <div class="mt-2">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted" style="font-size: 11px;">Password Strength Requirements:</span>
                <span id="pwStrengthText" class="fw-bold" style="font-size: 11px;"></span>
              </div>
              <div class="progress mb-2" style="height: 4px; background: #E2E8F0;">
                <div id="pwStrengthBar" class="progress-bar transition-all" style="width: 0%;"></div>
              </div>
              <ul class="text-muted small ps-3 mb-0" style="font-size: 11px; list-style-type: disc;">
                <li id="req-len">At least 6 characters long</li>
                <li id="req-upper">At least one uppercase letter (A-Z)</li>
                <li id="req-lower">At least one lowercase letter (a-z)</li>
                <li id="req-num">At least one number (0-9)</li>
                <li id="req-spec">At least one special character (!@#$%^&*)</li>
              </ul>
            </div>
          </div>

          <div>
            <label class="form-label small fw-semibold text-muted">CONFIRM NEW PASSWORD</label>
            <div class="input-group">
              <input type="password" name="confirm_password" id="confirmPassword" class="form-control rounded-start-3" placeholder="Confirm secure password" required>
              <button type="button" class="btn btn-outline-secondary rounded-end-3" onclick="togglePwVisibility('confirmPassword', this)">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>

          <div class="pt-2">
            <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-semibold small shadow-xs">
              <i class="fa-solid fa-key me-1"></i> Update Security Credentials
            </button>
          </div>
        </form>
      </div>

      <!-- ============================================
           4. APPEARANCE
           ============================================ -->
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white settings-tab-content" id="tab-appearance">
        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-palette text-success me-2"></i>Appearance (Theme Settings)</h5>
        <p class="text-muted small mb-4">Choose your preferred display theme. This configuration applies across all SkillBridge pages.</p>

        <div class="d-flex flex-column gap-3">
          <div class="d-flex flex-column gap-2" id="appearanceOptionsList">
            <label class="appearance-theme-option d-flex align-items-center gap-3 p-3 rounded-3" style="border: 1px solid var(--border); background: var(--bg-alt); cursor: pointer; transition: all 0.2s ease;" data-val="light">
              <i class="bi bi-sun-fill text-warning fs-5"></i>
              <div>
                <span class="small fw-semibold text-dark d-block">Light Mode</span>
                <span class="text-muted d-block" style="font-size: 11px;">Standard default bright visual theme</span>
              </div>
            </label>
            <label class="appearance-theme-option d-flex align-items-center gap-3 p-3 rounded-3" style="border: 1px solid var(--border); background: var(--bg-alt); cursor: pointer; transition: all 0.2s ease;" data-val="dark">
              <i class="bi bi-moon-stars-fill text-primary fs-5"></i>
              <div>
                <span class="small fw-semibold text-dark d-block">Dark Mode</span>
                <span class="text-muted d-block" style="font-size: 11px;">Immersive dark layout optimized for nighttime usage</span>
              </div>
            </label>
            <label class="appearance-theme-option d-flex align-items-center gap-3 p-3 rounded-3" style="border: 1px solid var(--border); background: var(--bg-alt); cursor: pointer; transition: all 0.2s ease;" data-val="system">
              <i class="bi bi-circle-half text-secondary fs-5"></i>
              <div>
                <span class="small fw-semibold text-dark d-block">System Preference</span>
                <span class="text-muted d-block" style="font-size: 11px;">Synchronize appearance settings with operating system preference</span>
              </div>
            </label>
          </div>
        </div>
      </div>

      <!-- ============================================
           5. NOTIFICATIONS
           ============================================ -->
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white settings-tab-content" id="tab-notifications">
        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-bell text-danger me-2"></i>Notification Preferences</h5>
        <p class="text-muted small mb-4">Toggle parameters to control what occurrences alert you in real-time.</p>

        <form action="<?= BASE_URL ?>faculty/settings.php?tab=notifications" method="POST" class="d-flex flex-column gap-3">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="update_notifications">

          <div class="p-3 bg-light bg-opacity-50 rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input animate-toggle" type="checkbox" name="notif_assessment" id="notifAssessment" value="1" <?= ($faculty['notif_assessment'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="notifAssessment">Assessment Alerts</label>
            </div>
            <div class="text-muted small" style="font-size: 11px;">Receive real-time dashboard notifications when quiz limits are adjusted or modifications are made.</div>
          </div>

          <div class="p-3 bg-light bg-opacity-50 rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input animate-toggle" type="checkbox" name="notif_submission" id="notifSubmission" value="1" <?= ($faculty['notif_submission'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="notifSubmission">Student Submission Notifications</label>
            </div>
            <div class="text-muted small" style="font-size: 11px;">Get updated when students upload answers or execute a submission in your assessments.</div>
          </div>

          <div class="p-3 bg-light bg-opacity-50 rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input animate-toggle" type="checkbox" name="notif_system" id="notifSystem" value="1" <?= ($faculty['notif_system'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="notifSystem">System Notifications</label>
            </div>
            <div class="text-muted small" style="font-size: 11px;">Notify me regarding server maintenance, global policy adjustments, or generic institutional parameters.</div>
          </div>

          <div class="p-3 bg-light bg-opacity-50 rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input animate-toggle" type="checkbox" name="notif_email" id="notifEmail" value="1" <?= ($faculty['notif_email'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="notifEmail">Email Notifications</label>
            </div>
            <div class="text-muted small" style="font-size: 11px;">Dispatch correspondence logs, submissions counts, and status changes directly to your mail inbox.</div>
          </div>

          <div class="p-3 bg-light bg-opacity-50 rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input animate-toggle" type="checkbox" name="notif_browser" id="notifBrowser" value="1" <?= ($faculty['notif_browser'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="notifBrowser">Browser Push Notifications</label>
            </div>
            <div class="text-muted small" style="font-size: 11px;">Send micro browser push alerts on your desktop when active in another tab layout.</div>
          </div>

          <div class="pt-2">
            <button type="submit" class="btn btn-danger text-white rounded-pill px-4 fw-semibold small shadow-xs">
              <i class="fa-solid fa-floppy-disk me-1"></i> Save Notification Settings
            </button>
          </div>
        </form>
      </div>

      <!-- ============================================
           6. PRIVACY
           ============================================ -->
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white settings-tab-content" id="tab-privacy">
        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-lock text-secondary me-2"></i>Privacy Options</h5>
        <p class="text-muted small mb-4">Decide what profile fields can be checked by active student sessions and outer networks.</p>

        <form action="<?= BASE_URL ?>faculty/settings.php?tab=privacy" method="POST" class="d-flex flex-column gap-3">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="update_privacy">

          <div class="p-3 bg-light bg-opacity-50 rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input animate-toggle" type="checkbox" name="priv_profile_visibility" id="privVisibility" value="1" <?= ($faculty['priv_profile_visibility'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="privVisibility">Profile Search Visibility</label>
            </div>
            <div class="text-muted small" style="font-size: 11px;">Allow students and other institutional branches to discover your profile card.</div>
          </div>

          <div class="p-3 bg-light bg-opacity-50 rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input animate-toggle" type="checkbox" name="priv_show_email" id="privShowEmail" value="1" <?= ($faculty['priv_show_email'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="privShowEmail">Show Email Address on Profile</label>
            </div>
            <div class="text-muted small" style="font-size: 11px;">Display your contact email details globally on the profile pages.</div>
          </div>

          <div class="p-3 bg-light bg-opacity-50 rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input animate-toggle" type="checkbox" name="priv_show_mobile" id="privShowMobile" value="1" <?= ($faculty['priv_show_mobile'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="privShowMobile">Show Mobile Number on Profile</label>
            </div>
            <div class="text-muted small" style="font-size: 11px;">Expose your mobile telephone contact to colleagues or search engines.</div>
          </div>

          <div class="p-3 bg-light bg-opacity-50 rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input animate-toggle" type="checkbox" name="priv_show_department" id="privShowDept" value="1" <?= ($faculty['priv_show_department'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="privShowDept">Show Department Information</label>
            </div>
            <div class="text-muted small" style="font-size: 11px;">List your academic division assignment on public-facing directories.</div>
          </div>

          <div class="p-3 bg-light bg-opacity-50 rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input animate-toggle" type="checkbox" name="priv_show_designation" id="privShowDesig" value="1" <?= ($faculty['priv_show_designation'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="privShowDesig">Show Designation (Title)</label>
            </div>
            <div class="text-muted small" style="font-size: 11px;">Render your academic ranking (e.g. Associate Professor) alongside name fields.</div>
          </div>

          <div class="pt-2">
            <button type="submit" class="btn btn-secondary text-white rounded-pill px-4 fw-semibold small shadow-xs">
              <i class="fa-solid fa-floppy-disk me-1"></i> Save Privacy Options
            </button>
          </div>
        </form>
      </div>

      <!-- ============================================
           7. PREFERENCES
           ============================================ -->
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white settings-tab-content" id="tab-preferences">
        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-sliders text-teal me-2"></i>Platform Preferences</h5>
        <p class="text-muted small mb-4">Adjust visual defaults, views, timezone, and language locales to customize your daily workspace.</p>

        <form action="<?= BASE_URL ?>faculty/settings.php?tab=preferences" method="POST" class="d-flex flex-column gap-3">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="update_preferences">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">DEFAULT DASHBOARD PAGE</label>
              <select name="pref_dashboard" class="form-select rounded-3">
                <option value="faculty/dashboard.php" <?= ($faculty['pref_dashboard'] ?? 'faculty/dashboard.php') === 'faculty/dashboard.php' ? 'selected' : '' ?>>Dashboard Home</option>
                <option value="faculty/assessments.php" <?= ($faculty['pref_dashboard'] ?? '') === 'faculty/assessments.php' ? 'selected' : '' ?>>Assessments Manager</option>
                <option value="faculty/students.php" <?= ($faculty['pref_dashboard'] ?? '') === 'faculty/students.php' ? 'selected' : '' ?>>Student Analytics</option>
                <option value="faculty/question-bank.php" <?= ($faculty['pref_dashboard'] ?? '') === 'faculty/question-bank.php' ? 'selected' : '' ?>>Question Bank</option>
              </select>
              <div class="text-muted" style="font-size: 11px; margin-top: 4px;">Initial page redirected to after signing in.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">DEFAULT ASSESSMENT VIEW</label>
              <select name="pref_assessment_view" class="form-select rounded-3">
                <option value="grid" <?= ($faculty['pref_assessment_view'] ?? 'grid') === 'grid' ? 'selected' : '' ?>>Grid Layout</option>
                <option value="list" <?= ($faculty['pref_assessment_view'] ?? '') === 'list' ? 'selected' : '' ?>>List Layout</option>
              </select>
              <div class="text-muted" style="font-size: 11px; margin-top: 4px;">Display method used by default in assessments screens.</div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">SYSTEM LANGUAGE (locale)</label>
              <select name="pref_language" class="form-select rounded-3">
                <option value="en" <?= ($faculty['pref_language'] ?? 'en') === 'en' ? 'selected' : '' ?>>English (US)</option>
                <option value="es" <?= ($faculty['pref_language'] ?? '') === 'es' ? 'selected' : '' ?>>Spanish (Español)</option>
                <option value="fr" <?= ($faculty['pref_language'] ?? '') === 'fr' ? 'selected' : '' ?>>French (Français)</option>
                <option value="de" <?= ($faculty['pref_language'] ?? '') === 'de' ? 'selected' : '' ?>>German (Deutsch)</option>
              </select>
              <div class="text-muted" style="font-size: 11px; margin-top: 4px;">Localization settings for text labels (future-ready).</div>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold text-muted">TIME ZONE OFFSET</label>
              <select name="pref_timezone" class="form-select rounded-3">
                <option value="Asia/Kolkata" <?= ($faculty['pref_timezone'] ?? 'Asia/Kolkata') === 'Asia/Kolkata' ? 'selected' : '' ?>>Kolkata (GMT+05:30)</option>
                <option value="UTC" <?= ($faculty['pref_timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>Coordinated Universal Time (UTC)</option>
                <option value="America/New_York" <?= ($faculty['pref_timezone'] ?? '') === 'America/New_York' ? 'selected' : '' ?>>New York (EST/EDT)</option>
                <option value="Europe/London" <?= ($faculty['pref_timezone'] ?? '') === 'Europe/London' ? 'selected' : '' ?>>London (GMT/BST)</option>
              </select>
              <div class="text-muted" style="font-size: 11px; margin-top: 4px;">Date-time log offset timezone (future-ready).</div>
            </div>
          </div>

          <div class="pt-2">
            <button type="submit" class="btn btn-teal text-white rounded-pill px-4 fw-semibold small shadow-xs" style="background: #14B8A6; border: none;">
              <i class="fa-solid fa-floppy-disk me-1"></i> Save Preferences
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Toggle Password Input Visibility
function togglePwVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-solid fa-eye';
    }
}

// Password Strength Checker
function checkPwStrength(val) {
    const bar = document.getElementById('pwStrengthBar');
    const text = document.getElementById('pwStrengthText');
    if (!bar || !text) return;

    if (!val || val.length === 0) {
        bar.style.width = '0%';
        text.textContent = '';
        resetPasswordReqs();
        return;
    }

    let score = 0;
    
    // Check validation constraints
    const reqLen = val.length >= 6;
    const reqUpper = /[A-Z]/.test(val);
    const reqLower = /[a-z]/.test(val);
    const reqNum = /[0-9]/.test(val);
    const reqSpec = /[^A-Za-z0-9]/.test(val);

    if (reqLen) { score++; markReqPassed('req-len'); } else { markReqFailed('req-len'); }
    if (reqUpper) { score++; markReqPassed('req-upper'); } else { markReqFailed('req-upper'); }
    if (reqLower) { score++; markReqPassed('req-lower'); } else { markReqFailed('req-lower'); }
    if (reqNum) { score++; markReqPassed('req-num'); } else { markReqFailed('req-num'); }
    if (reqSpec) { score++; markReqPassed('req-spec'); } else { markReqFailed('req-spec'); }

    if (score <= 2) {
        bar.style.width = '33%';
        bar.className = 'progress-bar bg-danger';
        text.textContent = 'Weak';
        text.className = 'fw-bold text-danger';
    } else if (score <= 4) {
        bar.style.width = '66%';
        bar.className = 'progress-bar bg-warning';
        text.textContent = 'Medium';
        text.className = 'fw-bold text-warning';
    } else {
        bar.style.width = '100%';
        bar.className = 'progress-bar bg-success';
        text.textContent = 'Strong';
        text.className = 'fw-bold text-success';
    }
}

function markReqPassed(id) {
    const el = document.getElementById(id);
    if (el) {
        el.className = 'text-success fw-semibold';
        el.style.listStyleType = 'check';
    }
}

function markReqFailed(id) {
    const el = document.getElementById(id);
    if (el) {
        el.className = 'text-danger';
        el.style.listStyleType = 'disc';
    }
}

function resetPasswordReqs() {
    const ids = ['req-len', 'req-upper', 'req-lower', 'req-num', 'req-spec'];
    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.className = 'text-muted';
            el.style.listStyleType = 'disc';
        }
    });
}

// Preview uploaded avatar image before submitting
document.getElementById('avatarFileInput').addEventListener('change', function(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('settingsAvatarPreview');
        output.src = reader.result;
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
});

// Settings Navigation Tabs Logic
(function() {
    const tabButtons = document.querySelectorAll('.nav-link-custom');
    const tabContents = document.querySelectorAll('.settings-tab-content');

    function switchTab(tabId) {
        tabButtons.forEach(btn => {
            if (btn.getAttribute('data-tab') === tabId) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        tabContents.forEach(content => {
            if (content.id === `tab-${tabId}`) {
                content.classList.add('active');
            } else {
                content.classList.remove('active');
            }
        });

        // Update URL query state without forcing a refresh
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.history.replaceState({}, '', url);
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const tabId = btn.getAttribute('data-tab');
            switchTab(tabId);
        });
    });

    // Detect initial tab preference on load
    const urlParams = new URLSearchParams(window.location.search);
    const initialTab = urlParams.get('tab') || 'profile';
    switchTab(initialTab);
})();

// Synced Appearance Theme Controls with SkillBridgeTheme Engine
(function() {
    var saved = (window.SkillBridgeTheme ? window.SkillBridgeTheme.get() : null) || localStorage.getItem('skillbridge_theme') || 'system';
    var options = document.querySelectorAll('.appearance-theme-option');
    
    function highlightSelected(selectedVal) {
        options.forEach(function(l) {
            var isSelected = l.getAttribute('data-val') === selectedVal;
            l.style.borderColor = isSelected ? 'var(--primary)' : 'var(--border)';
            l.style.background = isSelected ? 'var(--primary-light)' : 'var(--bg-alt)';
        });
    }

    highlightSelected(saved);

    options.forEach(function(label) {
        label.addEventListener('click', function() {
            var val = label.getAttribute('data-val');
            highlightSelected(val);
            if (window.SkillBridgeTheme) {
                window.SkillBridgeTheme.set(val);
            } else {
                localStorage.setItem('skillbridge_theme', val);
                var resolved = val === 'system' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : val;
                document.documentElement.setAttribute('data-theme', resolved);
            }
        });
    });
})();
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
