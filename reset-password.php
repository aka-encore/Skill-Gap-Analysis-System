<?php
/**
 * SkillBridge - Reset Password Token Handler
 * Premium SaaS UI matching the Login page.
 * Validates token, hashes new password, and updates database using PDO prepared statements.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/validators.php';

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$errorMessage = '';
$successMessage = '';
$validToken = false;
$user = null;

$db = Database::getInstance();

// Part 6 Requirement: Validate token exists, matches database, and has not expired
if (!empty($token)) {
    // Query users table for matching token and non-expired timestamp
    $user = $db->fetch("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry >= NOW()", [$token]);
    if ($user) {
        $validToken = true;
    } else {
        // Part 6 Requirement: Display exact message if token is invalid or expired
        $errorMessage = 'This reset link is invalid or has expired.';
    }
} else {
    $errorMessage = 'This reset link is invalid or has expired.';
}

// Handle Password Reset Form Submission
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && $validToken) {
    if (!verify_csrf_token()) {
        $errorMessage = 'Invalid security token. Please try again.';
    } else {
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Part 7 Requirement: Validation (Required fields, Password confirmation, Password strength)
        if (empty($password)) {
            $errorMessage = 'New password is required.';
        } elseif (strlen($password) < 6) {
            $errorMessage = 'Password must be at least 6 characters long.';
        } elseif ($password !== $confirmPassword) {
            $errorMessage = 'Password confirmation does not match.';
        } else {
            // Part 8 Requirement: Hash password using password_hash()
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Part 8 Requirement: Update password & clear reset_token and reset_token_expiry using prepared statements
            $db->update('users', [
                'password' => $hashedPassword,
                'reset_token' => null,
                'reset_token_expiry' => null
            ], 'id = ?', [$user['id']]);

            log_activity($user['id'], 'PASSWORD_RESET_SUCCESS', "Password reset successfully completed for user {$user['username']}.");

            // Part 8 Requirement: Display "Password reset successfully."
            $successMessage = 'Password reset successfully. Redirecting to login page in 3 seconds...';

            // Set flash message for login page
            set_flash_message('success', 'Password reset successfully. You can now log in with your new password.');
            
            // Invalidate validToken state so form hides upon success
            $validToken = false;
        }
    }
}

$pageTitle = "Reset Password – SkillBridge";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (!empty($successMessage)): ?>
      <!-- Auto redirect after 3 seconds -->
      <meta http-equiv="refresh" content="3;url=<?= BASE_URL ?>login.php">
    <?php endif; ?>
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="login-page-body">

  <!-- Ambient Background Orbs -->
  <div class="auth-ambient-glow-1"></div>
  <div class="auth-ambient-glow-2"></div>

  <!-- Main Centered SaaS Card Wrapper -->
  <div class="auth-center-wrapper">
    <div class="auth-card-modern">
      
      <!-- Welcome Header -->
      <div class="text-center mb-4">
        <a href="<?= BASE_URL ?>" class="d-inline-flex align-items-center gap-2.5 text-decoration-none mb-3">
          <div class="rounded-3 p-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="width:46px; height:46px; background: linear-gradient(135deg, #26658C, #021024);">
            <i class="fa-solid fa-brain fs-4"></i>
          </div>
          <span class="fw-bold fs-3 text-dark" style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">SkillBridge</span>
        </a>
        <h3 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Reset Password</h3>
        <p class="text-muted small mb-0">Create a new password for your account.</p>
      </div>

      <!-- Messages & Flash Alerts -->
      <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger py-2.5 px-3 small rounded-3 border-0 mb-4 d-flex align-items-center gap-2 shadow-xs">
          <i class="fa-solid fa-triangle-exclamation fs-6"></i>
          <div><?= htmlspecialchars($errorMessage) ?></div>
        </div>
      <?php endif; ?>

      <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success py-3 px-3 small rounded-3 border-0 mb-4 shadow-xs text-center">
          <i class="fa-solid fa-circle-check fs-3 text-success mb-2 d-block"></i>
          <div class="fw-bold fs-6 mb-1 text-dark">Password reset successfully.</div>
          <div class="text-muted small mb-3">Redirecting to login in <span id="countdown">3</span> seconds...</div>
          <a href="<?= BASE_URL ?>login.php" class="btn btn-saas-primary btn-sm px-4 rounded-pill">
            Go to Login Now <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        </div>
      <?php endif; ?>

      <?php if ($validToken): ?>
        <!-- Password Reset Form -->
        <form action="<?= BASE_URL ?>reset-password.php" method="POST" autocomplete="off" onsubmit="handleFormSubmit(this)">
          <?= csrf_field() ?>
          <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

          <!-- New Password Field -->
          <div class="mb-3.5">
            <label class="form-label small fw-semibold text-dark mb-1.5">New Password</label>
            <div class="saas-input-group">
              <i class="fa-solid fa-lock saas-input-icon"></i>
              <input type="password" name="password" id="pwInput1" class="form-control" placeholder="Minimum 6 characters" required autofocus style="padding-right: 44px;">
              <button type="button" class="btn p-0 text-muted border-0 position-absolute end-0 me-3" onclick="togglePw('pwInput1', this)" style="z-index: 5;" title="Toggle password visibility">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- Confirm Password Field -->
          <div class="mb-4">
            <label class="form-label small fw-semibold text-dark mb-1.5">Confirm Password</label>
            <div class="saas-input-group">
              <i class="fa-solid fa-shield-halved saas-input-icon"></i>
              <input type="password" name="confirm_password" id="pwInput2" class="form-control" placeholder="Re-enter your new password" required style="padding-right: 44px;">
              <button type="button" class="btn p-0 text-muted border-0 position-absolute end-0 me-3" onclick="togglePw('pwInput2', this)" style="z-index: 5;" title="Toggle password visibility">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" id="submitBtn" class="btn btn-saas-primary w-100 py-2.5 mb-3">
            <i class="fa-solid fa-check-circle me-1.5"></i> Reset Password
          </button>
        </form>
      <?php elseif (empty($successMessage)): ?>
        <div class="text-center py-3">
          <p class="text-muted small mb-3">Please request a new password reset link to continue.</p>
          <a href="<?= BASE_URL ?>forgot-password.php" class="btn btn-saas-primary rounded-pill px-4 py-2 small fw-semibold">
            <i class="fa-solid fa-rotate-left me-1.5"></i> Request New Reset Link
          </a>
        </div>
      <?php endif; ?>

      <!-- Back to Login Link -->
      <div class="text-center mt-3 pt-3 border-top">
        <a href="<?= BASE_URL ?>login.php" class="small text-primary fw-semibold text-decoration-none">
          <i class="fa-solid fa-arrow-left me-1"></i> Back to Login
        </a>
      </div>
    </div>
  </div>

  <script>
    // Toggle Password Visibility
    function togglePw(inputId, btn) {
      const pwInput = document.getElementById(inputId);
      const icon = btn.querySelector('i');
      if (pwInput.type === 'password') {
        pwInput.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
      } else {
        pwInput.type = 'password';
        icon.className = 'fa-solid fa-eye';
      }
    }

    // Form Submit Loading State
    function handleFormSubmit(form) {
      const btn = document.getElementById('submitBtn');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Resetting Password...';
      }
      return true;
    }

    <?php if (!empty($successMessage)): ?>
      // 3-second Countdown JS Timer to redirect to login.php
      let seconds = 3;
      const timerElement = document.getElementById('countdown');
      const interval = setInterval(() => {
        seconds--;
        if (timerElement) timerElement.textContent = seconds;
        if (seconds <= 0) {
          clearInterval(interval);
          window.location.href = '<?= BASE_URL ?>login.php';
        }
      }, 1000);
    <?php endif; ?>

  </script>
</body>
</html>
