<?php
/**
 * SkillBridge - Forgot Password Handler
 * Premium SaaS UI matching the Login page.
 * Uses Composer PHPMailer & PDO Prepared Statements.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/mail.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/validators.php';

$errorMessage = '';
$successMessage = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!verify_csrf_token()) {
        $errorMessage = 'Invalid security token. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email)) {
            $errorMessage = 'Please enter your email address.';
        } elseif (!validate_email($email)) {
            $errorMessage = 'Please enter a valid email address.';
        } else {
            $db = Database::getInstance();
            // Check if email exists in users table (covers students, faculty, and admin)
            $user = $db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
            
            if ($user) {
                // Generate 32-byte secure token
                $token = bin2hex(random_bytes(32));
                // 30 minutes expiry
                $expires = date('Y-m-d H:i:s', time() + 1800);
                
                // Save reset_token and reset_token_expiry using prepared statement
                $db->update('users', [
                    'reset_token' => $token,
                    'reset_token_expiry' => $expires
                ], 'id = ?', [$user['id']]);

                // Construct dynamic reset URL using BASE_URL configuration
                $resetLink = BASE_URL . "reset-password.php?token=" . $token;

                // Send reset email via PHPMailer SMTP
                $mailResult = send_password_reset_email($email, $resetLink);

                if ($mailResult['success']) {
                    $successMessage = 'A password reset link has been sent to your email address.';
                    log_activity($user['id'], 'FORGOT_PASSWORD_REQUEST', "Password reset requested for {$email}.");
                } else {
                    // SMTP delivery failed
                    $errorMessage = 'Failed to send password reset email via SMTP. Please try again later.';
                    error_log("Forgot password SMTP failure for {$email}: " . ($mailResult['message'] ?? 'Unknown error'));
                }
            } else {
                // Security best practice: Prevent user enumeration
                $successMessage = 'If an account with that email address exists, a password reset link has been sent.';
            }
        }
    }
}

$pageTitle = "Forgot Password – SkillBridge";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <h3 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Forgot Password</h3>
        <p class="text-muted small mb-0">Enter your registered email address and we'll send you a password reset link.</p>
      </div>

      <!-- Messages & Flash Alerts -->
      <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger py-2.5 px-3 small rounded-3 border-0 mb-4 d-flex align-items-center gap-2 shadow-xs">
          <i class="fa-solid fa-triangle-exclamation fs-6"></i>
          <div><?= htmlspecialchars($errorMessage) ?></div>
        </div>
      <?php endif; ?>

      <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success py-2.5 px-3 small rounded-3 border-0 mb-4 shadow-xs">
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fa-solid fa-circle-check fs-6 text-success"></i>
            <div class="fw-semibold">Reset Link Sent</div>
          </div>
          <div><?= htmlspecialchars($successMessage) ?></div>
        </div>
      <?php endif; ?>

      <!-- Forgot Password Form -->
      <form action="<?= BASE_URL ?>forgot-password.php" method="POST" autocomplete="off" onsubmit="handleFormSubmit(this)">
        <?= csrf_field() ?>
        
        <div class="mb-4">
          <label class="form-label small fw-semibold text-dark mb-1.5">Email Address</label>
          <div class="saas-input-group">
            <i class="fa-solid fa-envelope saas-input-icon"></i>
            <input type="email" name="email" class="form-control" placeholder="you@example.com" required autofocus value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
          </div>
        </div>

        <!-- Send Reset Link Button -->
        <button type="submit" id="submitBtn" class="btn btn-saas-primary w-100 py-2.5 mb-3">
          <i class="fa-solid fa-paper-plane me-1.5"></i> Send Reset Link
        </button>
      </form>

      <!-- Back to Login Link -->
      <div class="text-center mt-3 pt-3 border-top">
        <a href="<?= BASE_URL ?>login.php" class="small text-primary fw-semibold text-decoration-none">
          <i class="fa-solid fa-arrow-left me-1"></i> Back to Login
        </a>
      </div>
    </div>
  </div>

  <script>
    // Form Submit Loading State
    function handleFormSubmit(form) {
      const btn = document.getElementById('submitBtn');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Sending Reset Link...';
      }
      return true;
    }

  </script>
</body>
</html>
