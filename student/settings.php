<?php
/**
 * SkillBridge - Student Account & Preferences Settings
 * Project-tailored settings page: Security, Notifications, Appearance, System Info.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$userId = $_SESSION['user_id'];
$db = Database::getInstance();

// Fetch authenticated student & user record first
$student = $db->fetch(
    "SELECT s.*, u.username, u.email, u.role, u.password as user_pw, u.created_at as user_created 
     FROM students s 
     JOIN users u ON s.user_id = u.id 
     WHERE s.id = ?", 
    [$studentId]
);

// Initialize default notification preferences in session if not set
if (!isset($_SESSION['student_notif_prefs'])) {
    $_SESSION['student_notif_prefs'] = [
        'notif_assessment' => 1,
        'notif_roadmap'    => 1
    ];
}

// 1. Handle Password Change Form Submit with strict security & difference checks
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['change_password'])) {
    $currPassword = $_POST['current_password'] ?? '';
    $newPassword  = $_POST['new_password'] ?? '';
    $confPassword = $_POST['confirm_password'] ?? '';

    if (!password_verify($currPassword, $student['user_pw'])) {
        set_flash_message('danger', 'Current password is incorrect.');
    } elseif ($newPassword === $currPassword || password_verify($newPassword, $student['user_pw'])) {
        set_flash_message('danger', 'Please enter a new password different from your current password.');
    } elseif ($newPassword !== $confPassword) {
        set_flash_message('danger', 'New password and confirmation password do not match.');
    } elseif (strlen($newPassword) < 6) {
        set_flash_message('danger', 'New password must be at least 6 characters long.');
    } else {
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $db->update('users', ['password' => $newHash], 'id = ?', [$userId]);
        set_flash_message('success', 'Password updated successfully.');
    }
    redirect(BASE_URL . 'student/settings.php');
}

// 2. Handle Notification Settings Form Submit with strict change verification
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['update_preferences'])) {
    $newAss = isset($_POST['notif_assessment']) ? 1 : 0;
    $newRoad = isset($_POST['notif_roadmap']) ? 1 : 0;

    $currAss = $_SESSION['student_notif_prefs']['notif_assessment'] ?? 1;
    $currRoad = $_SESSION['student_notif_prefs']['notif_roadmap'] ?? 1;

    if ($newAss === $currAss && $newRoad === $currRoad) {
        set_flash_message('info', 'No changes were made to your notification settings.');
    } else {
        $_SESSION['student_notif_prefs'] = [
            'notif_assessment' => $newAss,
            'notif_roadmap'    => $newRoad
        ];
        set_flash_message('success', 'Notification settings updated successfully.');
    }
    redirect(BASE_URL . 'student/settings.php');
}

$studentName = htmlspecialchars(($student['first_name'] ?? 'Student') . ' ' . ($student['last_name'] ?? ''));

$pageTitle = "Account Security & Platform Settings - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="dash-content">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
      <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-gear text-primary me-2"></i>Account & Platform Settings</h2>
      <p class="text-muted small mb-0">Manage your password, security, notifications, and platform preferences.</p>
    </div>
    <a href="<?= BASE_URL ?>student/profile.php" class="btn btn-outline-primary rounded-pill px-3 py-1.5 small fw-semibold">
      <i class="fa-solid fa-user-circle me-1"></i> View & Edit Profile
    </a>
  </div>

  <div class="row g-4">
    <!-- LEFT COLUMN: SETTINGS PANELS -->
    <div class="col-lg-8">
      <!-- 1. CHANGE PASSWORD & SECURITY -->
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-lock text-warning me-2"></i> Security & Password</h5>
        <p class="text-muted small mb-4">Update your account password to keep your learning records secure.</p>

        <form action="<?= BASE_URL ?>student/settings.php" method="POST" class="d-flex flex-column gap-3" id="changePwForm">
          <input type="hidden" name="change_password" value="1">
          
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
              <input type="password" name="new_password" id="newPassword" class="form-control rounded-start-3" placeholder="Enter new password (min. 6 chars)" oninput="checkPwStrength(this.value)" required>
              <button type="button" class="btn btn-outline-secondary rounded-end-3" onclick="togglePwVisibility('newPassword', this)">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
            <!-- Strength Indicator -->
            <div class="mt-2">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted" style="font-size: 11px;">Password Strength</span>
                <span id="pwStrengthText" class="fw-bold" style="font-size: 11px;"></span>
              </div>
              <div class="progress" style="height: 4px; background: #E2E8F0;">
                <div id="pwStrengthBar" class="progress-bar transition-all" style="width: 0%;"></div>
              </div>
            </div>
          </div>

          <div>
            <label class="form-label small fw-semibold text-muted">CONFIRM NEW PASSWORD</label>
            <div class="input-group">
              <input type="password" name="confirm_password" id="confirmPassword" class="form-control rounded-start-3" placeholder="Confirm new password" required>
              <button type="button" class="btn btn-outline-secondary rounded-end-3" onclick="togglePwVisibility('confirmPassword', this)">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>

          <div class="pt-2">
            <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-semibold small">
              <i class="fa-solid fa-key me-1"></i> Update Password
            </button>
          </div>
        </form>
      </div>

      <!-- 2. NOTIFICATION SETTINGS -->
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-bell text-success me-2"></i> Notification Settings</h5>
        <p class="text-muted small mb-4">Manage your platform alert preferences for quizzes and roadmap milestones.</p>

        <form action="<?= BASE_URL ?>student/settings.php" method="POST" class="d-flex flex-column gap-3">
          <input type="hidden" name="update_preferences" value="1">
          
          <div class="p-3 bg-light rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="notif_assessment" id="notifAssessment" value="1" <?= ($_SESSION['student_notif_prefs']['notif_assessment'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="notifAssessment">Assessment & Quiz Alerts</label>
            </div>
            <div class="text-muted" style="font-size: 11px;">Receive notifications when new assessments are assigned by faculty.</div>
          </div>

          <div class="p-3 bg-light rounded-3 border">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="notif_roadmap" id="notifRoadmap" value="1" <?= ($_SESSION['student_notif_prefs']['notif_roadmap'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-input-label fw-bold text-dark small" for="notifRoadmap">Roadmap Milestone Reminders</label>
            </div>
            <div class="text-muted" style="font-size: 11px;">Get weekly reminders for pending career roadmap milestones.</div>
          </div>

          <div class="pt-2">
            <button type="submit" class="btn btn-success text-white rounded-pill px-4 fw-semibold small">
              <i class="fa-solid fa-floppy-disk me-1"></i> Save Notification Settings
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- RIGHT COLUMN: APPEARANCE & READ-ONLY SYSTEM INFO -->
    <div class="col-lg-4">
      <!-- APPEARANCE THEME SELECTOR -->
      <div class="saas-card p-4 mb-4">
        <h5 class="fw-bold mb-1" style="color: var(--text-heading);"><i class="bi bi-palette me-2 text-primary"></i>Appearance</h5>
        <p class="small mb-3" style="color: var(--text-muted);">Choose your preferred display mode across all pages.</p>

        <div class="d-flex flex-column gap-2" id="appearanceOptionsList">
          <label class="appearance-theme-option d-flex align-items-center gap-3 p-3 rounded-3" style="border: 1px solid var(--border); background: var(--bg-alt); cursor: pointer; transition: all 0.2s ease;" data-val="light">
            <i class="bi bi-sun-fill text-warning fs-5"></i>
            <span class="small fw-semibold" style="color: var(--text-body);">Light</span>
          </label>
          <label class="appearance-theme-option d-flex align-items-center gap-3 p-3 rounded-3" style="border: 1px solid var(--border); background: var(--bg-alt); cursor: pointer; transition: all 0.2s ease;" data-val="dark">
            <i class="bi bi-moon-stars-fill text-primary fs-5"></i>
            <span class="small fw-semibold" style="color: var(--text-body);">Dark</span>
          </label>
          <label class="appearance-theme-option d-flex align-items-center gap-3 p-3 rounded-3" style="border: 1px solid var(--border); background: var(--bg-alt); cursor: pointer; transition: all 0.2s ease;" data-val="system">
            <i class="bi bi-circle-half text-secondary fs-5"></i>
            <span class="small fw-semibold" style="color: var(--text-body);">System (Auto)</span>
          </label>
        </div>
      </div>

      <!-- ℹ️ READ-ONLY SYSTEM INFORMATION -->
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i> System Information</h5>
        
        <div class="d-flex flex-column gap-2 small">
          <div class="d-flex justify-content-between py-1.5 border-bottom">
            <span class="text-muted">Application</span>
            <span class="fw-bold text-dark">SkillBridge LMS</span>
          </div>
          <div class="d-flex justify-content-between py-1.5 border-bottom">
            <span class="text-muted">Platform Version</span>
            <span class="badge bg-primary-subtle text-primary border fw-semibold">v1.0.4 Enterprise</span>
          </div>
          <div class="d-flex justify-content-between py-1.5 border-bottom">
            <span class="text-muted">Account Role</span>
            <span class="badge bg-success-subtle text-success border text-uppercase fw-semibold"><?= htmlspecialchars($student['role'] ?? 'student') ?></span>
          </div>
          <div class="d-flex justify-content-between py-1.5 border-bottom">
            <span class="text-muted">Student Code</span>
            <span class="fw-bold text-dark"><?= htmlspecialchars($student['student_code'] ?? 'STU-1000') ?></span>
          </div>
          <div class="d-flex justify-content-between py-1.5 border-bottom">
            <span class="text-muted">Username</span>
            <span class="fw-bold text-dark"><?= htmlspecialchars($student['username'] ?? '') ?></span>
          </div>
          <div class="d-flex justify-content-between py-1.5">
            <span class="text-muted">Email Address</span>
            <span class="fw-semibold text-dark"><?= htmlspecialchars($student['email'] ?? '') ?></span>
          </div>
        </div>
      </div>

      <!-- DEDICATED LOGOUT CARD -->
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-danger-subtle border-danger-subtle">
        <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-right-from-bracket me-2"></i> Log Out Session</h6>
        <p class="text-muted small mb-3">Sign out of your SkillBridge session on this browser.</p>
        <a href="<?= BASE_URL ?>logout.php" class="btn btn-danger rounded-pill w-100 py-2 small fw-semibold">
          Log Out Account
        </a>
      </div>
    </div>
  </div>
</div>

<script>
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

function checkPwStrength(val) {
    const bar = document.getElementById('pwStrengthBar');
    const text = document.getElementById('pwStrengthText');
    if (!bar || !text) return;

    if (!val || val.length === 0) {
        bar.style.width = '0%';
        text.textContent = '';
        return;
    }

    let score = 0;
    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

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

// Appearance Theme — synced with SkillBridgeTheme engine
(function() {
    var saved = localStorage.getItem('skillbridge_theme') || 'system';
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
