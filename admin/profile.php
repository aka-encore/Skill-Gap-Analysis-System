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
            } elseif (!preg_match("/^[a-zA-Z\s\-]+$/", $firstName)) {
                $error = 'First name cannot contain numbers.';
            } elseif (!preg_match("/^[a-zA-Z\s\-]+$/", $lastName)) {
                $error = 'Last name cannot contain numbers.';
            } else {
                $adminRow = $db->fetch("SELECT avatar FROM admins WHERE user_id = ?", [$userId]);
                $currAvatar = $adminRow['avatar'] ?? 'default-avatar.png';
                $avatarFileName = $currAvatar;

                if (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] === '1') {
                    if (!empty($currAvatar) && $currAvatar !== 'default-avatar.png') {
                        $oldFile = AVATAR_UPLOAD_DIR . $currAvatar;
                        if (file_exists($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                    $avatarFileName = 'default-avatar.png';
                    $_SESSION['avatar'] = 'default-avatar.png';
                } elseif (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
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
                            // Clean up previous custom avatar
                            if (!empty($currAvatar) && $currAvatar !== 'default-avatar.png') {
                                $oldFile = AVATAR_UPLOAD_DIR . $currAvatar;
                                if (file_exists($oldFile)) {
                                    @unlink($oldFile);
                                }
                            }
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
$avatarUrl = resolve_avatar_url($profile['avatar'] ?? '', 'admin');

$pageTitle = "Admin Profile - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-person-gear text-primary me-2"></i>Administrator Profile</h3>
        <p class="text-muted small mb-0">Update account credentials and security preferences</p>
    </div>
</div>

<style>
/* ── Admin Profile Redesign CSS ── */
.profile-card-custom {
  border-radius: 20px !important;
  box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important;
  border: 1px solid var(--border) !important;
  background: var(--bg-card) !important;
}

.profile-avatar-container {
  position: relative;
  width: 140px;
  height: 140px;
  margin: 0 auto;
}
@media (min-width: 768px) {
  .profile-avatar-container {
    margin: 0 0 1rem 0;
  }
}

.profile-avatar-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.profile-avatar-container:hover img {
  transform: scale(1.08);
}

.hover-lift {
  transition: transform 0.2s ease, box-shadow 0.2s ease !important;
}

.hover-lift:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 6px 18px rgba(38, 101, 140, 0.15) !important;
}

.info-item-card {
  background: var(--bg-alt);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 0.85rem 1.15rem;
  height: 100%;
  transition: border-color 0.22s ease, background 0.22s ease, transform 0.22s ease;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.info-item-card:hover {
  border-color: var(--primary);
  background: var(--bg-card);
  transform: translateY(-1px);
}

.info-item-icon {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(38, 101, 140, 0.07);
  color: var(--primary);
  font-size: 1.15rem;
  flex-shrink: 0;
}

.info-item-label {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--text-secondary);
  opacity: 0.75;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 2px;
}

.info-item-value {
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--text-heading);
  line-height: 1.25;
}

@media (min-width: 992px) {
  .border-lg-end {
    border-right: 1px solid var(--border) !important;
  }
}
</style>

<div class="dash-content">
  <!-- PROFILE HEADER BANNER -->
  <div class="saas-card p-4 p-md-5 mb-4 position-relative overflow-hidden profile-card-custom" id="profile-information">
    <!-- Header Actions Row (top-right alignment) -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <h4 class="fw-bold mb-0 text-primary" style="font-size: 1.25rem;"><i class="fa-solid fa-person-gear text-primary me-2"></i>Admin Settings Portal</h4>
      <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary rounded-pill px-3.5 py-2 small fw-semibold shadow-sm hover-lift" data-bs-toggle="modal" data-bs-target="#editAdminModal">
          <i class="fa-solid fa-user-pen me-1.5"></i>Edit Profile
        </button>
        <button type="button" class="btn btn-outline-secondary rounded-pill px-3.5 py-2 small fw-semibold shadow-sm hover-lift" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
          <i class="fa-solid fa-key me-1.5"></i>Change Password
        </button>
      </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="row g-4 align-items-stretch">
      <!-- LEFT COLUMN -->
      <div class="col-lg-4 col-md-5 col-12 border-lg-end pe-lg-4 d-flex flex-column justify-content-between">
        <div>
          <!-- Large Profile Picture -->
          <div class="profile-avatar-container mb-3">
            <div class="rounded-circle overflow-hidden border border-3 border-primary shadow-sm" style="width: 100%; height: 100%;">
              <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="Admin Avatar" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <button type="button" class="btn btn-primary rounded-circle position-absolute bottom-0 end-0 p-0 d-flex align-items-center justify-content-center hover-lift shadow" style="width: 38px; height: 38px; border: 3px solid var(--bg-card);" data-bs-toggle="modal" data-bs-target="#editAdminModal" title="Upload Photo">
              <i class="fa-solid fa-camera" style="font-size: 0.85rem;"></i>
            </button>
          </div>

          <!-- Admin Name & Role -->
          <div class="text-center text-md-start mb-3">
            <h2 class="fw-bold mb-1" style="color: var(--text-heading); font-size: 1.5rem;"><?= htmlspecialchars(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? '')) ?></h2>
            <div class="small" style="color: var(--text-muted); font-weight: 500;">
              <span class="badge bg-dark border rounded-pill px-3 py-1 mb-2 text-uppercase text-white">
                <i class="fa-solid fa-shield-halved me-1"></i>Administrator
              </span>
              <div class="text-muted"><i class="fa-solid fa-at text-primary me-1 text-lowercase"></i><?= htmlspecialchars($profile['username'] ?? '') ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN -->
      <div class="col-lg-8 col-md-7 col-12 ps-lg-4 mt-md-0 mt-4">
        <div class="h-100 d-flex flex-column justify-content-between">
          <div>
            <h5 class="fw-bold mb-3 text-primary" style="font-size: 1.05rem;">
              <i class="fa-solid fa-address-card text-primary me-2"></i>Account details
            </h5>
            
            <div class="row g-3">
              <!-- Admin ID -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-fingerprint"></i>
                  </div>
                  <div>
                    <div class="info-item-label">Admin ID</div>
                    <div class="info-item-value">ADM-<?= str_pad($profile['id'], 4, '0', STR_PAD_LEFT) ?></div>
                  </div>
                </div>
              </div>

              <!-- Department -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-building"></i>
                  </div>
                  <div>
                    <div class="info-item-label">Department</div>
                    <div class="info-item-value"><?= !empty($profile['department']) ? htmlspecialchars($profile['department']) : 'IT & Operations' ?></div>
                  </div>
                </div>
              </div>

              <!-- Joined Date -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                  </div>
                  <div>
                    <div class="info-item-label">Joined Date</div>
                    <div class="info-item-value"><?= !empty($profile['user_created']) ? date('M d, Y', strtotime($profile['user_created'])) : 'Not Provided' ?></div>
                  </div>
                </div>
              </div>

              <!-- Email Address -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-envelope"></i>
                  </div>
                  <div class="text-truncate" style="max-width: calc(100% - 50px);">
                    <div class="info-item-label">Email Address</div>
                    <div class="info-item-value text-truncate" title="<?= htmlspecialchars($profile['email'] ?? '') ?>"><?= !empty($profile['email']) ? htmlspecialchars($profile['email']) : 'Not Provided' ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ADMIN UTILITIES AND THEME CONFIG -->
  <div class="row g-4 mb-4">
    <!-- LEFT: System Configuration info -->
    <div class="col-lg-6">
      <div class="saas-card p-4 h-100">
        <h5 class="fw-bold mb-3" style="color: var(--text-heading);"><i class="fa-solid fa-server text-primary me-2"></i>System & Environment Info</h5>
        <div class="row g-3">
          <div class="col-md-6 col-12">
            <div class="info-item-card">
              <div class="info-item-icon"><i class="fa-solid fa-cubes"></i></div>
              <div>
                <div class="info-item-label">Application</div>
                <div class="info-item-value"><?= APP_NAME ?></div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-12">
            <div class="info-item-card">
              <div class="info-item-icon"><i class="fa-solid fa-code-branch"></i></div>
              <div>
                <div class="info-item-label">App Version</div>
                <div class="info-item-value"><?= APP_VERSION ?></div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-12">
            <div class="info-item-card">
              <div class="info-item-icon"><i class="fa-solid fa-clock"></i></div>
              <div>
                <div class="info-item-label">Timezone</div>
                <div class="info-item-value">Asia/Kolkata</div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-12">
            <div class="info-item-card">
              <div class="info-item-icon"><i class="fa-solid fa-circle-check text-success"></i></div>
              <div>
                <div class="info-item-label">Platform Status</div>
                <div class="info-item-value text-success">System Active</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT: Theme Configuration options -->
    <div class="col-lg-6">
      <div class="saas-card p-4 h-100 bg-white">
        <h5 class="fw-bold mb-1" style="color: var(--text-heading);"><i class="fa-solid fa-palette text-primary me-2"></i>Appearance & Theme</h5>
        <p class="small text-muted mb-3">Choose your preferred display mode across all pages.</p>

        <div class="d-flex flex-column gap-2" id="appearanceOptionsList">
          <label class="appearance-theme-option d-flex align-items-center gap-3 p-3 rounded-3" style="border: 1px solid var(--border); background: var(--bg-alt); cursor: pointer; transition: all 0.2s ease;" data-val="light">
            <i class="bi bi-sun-fill text-warning fs-5"></i>
            <span class="small fw-semibold" style="color: var(--text-heading);">Light Mode</span>
          </label>
          <label class="appearance-theme-option d-flex align-items-center gap-3 p-3 rounded-3" style="border: 1px solid var(--border); background: var(--bg-alt); cursor: pointer; transition: all 0.2s ease;" data-val="dark">
            <i class="bi bi-moon-stars-fill text-primary fs-5"></i>
            <span class="small fw-semibold" style="color: var(--text-heading);">Dark Mode</span>
          </label>
          <label class="appearance-theme-option d-flex align-items-center gap-3 p-3 rounded-3" style="border: 1px solid var(--border); background: var(--bg-alt); cursor: pointer; transition: all 0.2s ease;" data-val="system">
            <i class="bi bi-circle-half text-secondary fs-5"></i>
            <span class="small fw-semibold" style="color: var(--text-heading);">System Preference (Auto)</span>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- EDIT ADMIN PROFILE MODAL -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold" style="color: var(--text-heading);"><i class="fa-solid fa-user-pen text-primary me-2"></i>Edit Admin Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>admin/profile.php" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="action_type" value="update_profile">
        
        <div class="modal-body pt-3">
          <!-- Section: Profile Picture -->
          <div class="p-3 mb-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
            <div class="small fw-bold text-primary mb-2"><i class="fa-solid fa-image me-1"></i> Profile Picture</div>
            <div class="mb-2">
              <input type="file" name="avatar_file" id="admin_avatar_file" class="form-control rounded-3" accept="image/jpeg,image/png,image/webp">
              <div id="admin-avatar-file-name" class="text-secondary small mt-1" style="font-size: 11px;">
                <?php if (!empty($profile['avatar']) && $profile['avatar'] !== 'default-avatar.png'): ?>
                  <?= htmlspecialchars($profile['avatar']) ?>
                <?php else: ?>
                  No file selected
                <?php endif; ?>
              </div>
            </div>
            <?php if (!empty($profile['avatar']) && $profile['avatar'] !== 'default-avatar.png'): ?>
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_avatar" id="remove_avatar_val" value="1">
                <label class="form-check-label text-danger small fw-semibold" for="remove_avatar_val">
                  Remove current photo (revert to default)
                </label>
              </div>
            <?php endif; ?>
            <div class="text-muted" style="font-size: 11px; margin-top: 4px;">Accepted formats: JPG, PNG, WebP (Max size: 5MB)</div>
          </div>

          <!-- Section: Personal Information -->
          <div class="p-3 mb-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
            <div class="small fw-bold text-primary mb-2"><i class="fa-solid fa-user me-1"></i> Personal Information</div>
            <div class="row g-3">
              <div class="col-6">
                <label class="form-label small fw-semibold text-muted">FIRST NAME <span class="text-danger">*</span></label>
                <input type="text" name="first_name" class="form-control rounded-3" value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>" required>
              </div>
              <div class="col-6">
                <label class="form-label small fw-semibold text-muted">LAST NAME <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control rounded-3" value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>" required>
              </div>
            </div>
          </div>

          <!-- Section: Professional Information -->
          <div class="p-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
            <div class="small fw-bold text-primary mb-2"><i class="fa-solid fa-building me-1"></i> Professional Information</div>
            <div>
              <label class="form-label small fw-semibold text-muted">DEPARTMENT</label>
              <input type="text" name="department" class="form-control rounded-3" value="<?= htmlspecialchars($profile['department'] ?? 'IT & Operations') ?>">
            </div>
          </div>
        </div>

        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-light rounded-pill px-3.5 small fw-semibold" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary rounded-pill px-4 small fw-semibold hover-lift shadow-sm">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- CHANGE ADMIN PASSWORD MODAL -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold" style="color: var(--text-heading);"><i class="fa-solid fa-key text-primary me-2"></i>Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>admin/profile.php#change-password" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="action_type" value="change_password">
        
        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">CURRENT PASSWORD</label>
            <input type="password" name="current_password" class="form-control rounded-3" required>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">NEW PASSWORD</label>
            <input type="password" name="new_password" class="form-control rounded-3" required>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">CONFIRM NEW PASSWORD</label>
            <input type="password" name="confirm_password" class="form-control rounded-3" required>
          </div>
        </div>

        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-light rounded-pill px-3.5 small fw-semibold" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary rounded-pill px-4 small fw-semibold hover-lift shadow-sm">Update Password</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Appearance Theme — synced with SkillBridgeTheme engine
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

// File Name listener for Admin Avatar upload
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('admin_avatar_file');
    const avatarFileName = document.getElementById('admin-avatar-file-name');
    if (avatarInput && avatarFileName) {
        avatarInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                avatarFileName.textContent = this.files[0].name;
            } else {
                avatarFileName.textContent = <?= json_encode(!empty($profile['avatar']) && $profile['avatar'] !== 'default-avatar.png' ? $profile['avatar'] : 'No file selected') ?>;
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
