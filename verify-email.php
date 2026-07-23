<?php
/**
 * SkillBridge - Email Verification via OTP Page
 * Premium SaaS UI matching Login and Registration pages.
 * Verifies 6-digit OTP, activates user account, and handles resend rate limiting.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/mail.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/validators.php';

if (is_logged_in()) {
    $role = $_SESSION['user_role'] ?? 'student';
    match ($role) {
        'student' => redirect(BASE_URL . 'student/dashboard.php'),
        'faculty' => redirect(BASE_URL . 'faculty/dashboard.php'),
        'admin'   => redirect(BASE_URL . 'admin/dashboard.php'),
        default   => null
    };
}

$email = trim($_GET['email'] ?? $_POST['email'] ?? '');
$errorMessage = '';
$successMessage = '';
$infoMessage = '';

$db = Database::getInstance();
$user = null;

if (!empty($email)) {
    $user = $db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
}

// Redirect if no valid email specified or user does not exist
if (!$user) {
    set_flash_message('warning', 'Invalid verification session. Please log in or create a new account.');
    redirect(BASE_URL . 'login.php');
}

// If already verified, notify and redirect to login
if ((int)($user['email_verified'] ?? 0) === 1) {
    set_flash_message('success', 'Your email is already verified. Please sign in to your account.');
    redirect(BASE_URL . 'login.php');
}

// Handle Form Submissions
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!verify_csrf_token()) {
        $errorMessage = 'Invalid security token. Please try again.';
    } else {
        $action = $_POST['action'] ?? 'verify';

        if ($action === 'verify') {
            $submittedOtp = trim($_POST['otp'] ?? '');

            if (empty($submittedOtp)) {
                $errorMessage = 'Please enter the 6-digit verification code.';
            } elseif (!preg_match('/^[0-9]{6}$/', $submittedOtp)) {
                $errorMessage = 'Invalid OTP format. Code must be 6 numeric digits.';
            } else {
                // Check OTP match
                if ($user['email_verification_otp'] !== $submittedOtp) {
                    // Requirement: Display "Invalid OTP."
                    $errorMessage = 'Invalid OTP.';
                } elseif (!empty($user['otp_expiry']) && strtotime($user['otp_expiry']) < time()) {
                    // Requirement: Display "OTP has expired."
                    $errorMessage = 'OTP has expired.';
                } else {
                    // OTP is valid & active! Update user: email_verified = 1, clear OTP and expiry
                    $db->update('users', [
                        'email_verified'         => 1,
                        'email_verification_otp' => null,
                        'otp_expiry'             => null
                    ], 'id = ?', [$user['id']]);

                    log_activity($user['id'], 'EMAIL_VERIFIED', "User {$user['username']} verified email successfully via OTP.");

                    if ($user['role'] === 'faculty') {
                        // Ensure status is set to pending
                        $db->update('users', ['status' => 'pending'], 'id = ?', [$user['id']]);
                        $db->update('faculty', ['approval_status' => 'pending'], 'user_id = ?', [$user['id']]);

                        // Fetch Faculty Details for Admin Notification
                        $fac = $db->fetch("SELECT first_name, last_name FROM faculty WHERE user_id = ?", [$user['id']]);
                        $fullName = trim(($fac['first_name'] ?? '') . ' ' . ($fac['last_name'] ?? ''));
                        if (empty($fullName)) $fullName = $user['username'];

                        // Dispatch Admin Notification for new Faculty Application
                        $admins = $db->fetchAll("SELECT id FROM users WHERE role = 'admin'");
                        foreach ($admins as $a) {
                            $db->insert('notifications', [
                                'user_id'    => $a['id'],
                                'title'      => 'New Faculty Application',
                                'message'    => "New Faculty Registration Application from {$fullName}.",
                                'link'       => BASE_URL . 'admin/faculty-applications.php',
                                'is_read'    => 0,
                                'type'       => 'system',
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }

                        $pendingMsg = 'Your registration application has been submitted successfully. Your account is currently under review by the administrator. You will receive an email once your application has been approved or rejected.';
                        set_flash_message('info', $pendingMsg);
                        redirect(BASE_URL . 'login.php');
                    } else {
                        $successMessage = 'Email verified successfully.';
                        set_flash_message('success', 'Email verified successfully. You may now log in to your account.');
                    }
                }
            }
        } elseif ($action === 'resend') {
            // Rate Limit: Maximum one resend every 60 seconds
            $sessionKey = 'last_otp_resend_' . md5($email);
            $lastResend = $_SESSION[$sessionKey] ?? 0;
            $timeRemaining = 60 - (time() - $lastResend);

            if ($timeRemaining > 0) {
                $errorMessage = "Please wait {$timeRemaining} seconds before requesting another verification code.";
            } else {
                // Generate a new 6-digit OTP (10 minutes validity)
                $newOtp = (string)random_int(100000, 999999);
                $newExpiry = date('Y-m-d H:i:s', time() + 600);

                // Update database
                $db->update('users', [
                    'email_verification_otp' => $newOtp,
                    'otp_expiry'             => $newExpiry
                ], 'id = ?', [$user['id']]);

                // Update user record reference
                $user['email_verification_otp'] = $newOtp;
                $user['otp_expiry'] = $newExpiry;

                // Record resend timestamp
                $_SESSION[$sessionKey] = time();

                // Send new OTP email
                $resendResult = send_otp_email($email, $newOtp);

                if (!empty($resendResult['success'])) {
                    $infoMessage = 'A new 6-digit verification code has been sent to your email address.';
                    log_activity($user['id'], 'RESEND_OTP', "Resent email verification OTP for {$email}.");
                } else {
                    $errorMessage = 'Failed to send verification code: ' . htmlspecialchars($resendResult['message'] ?? 'SMTP Error');
                }
            }
        }
    }
}

$pageTitle = "Verify Email – SkillBridge";
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
    <style>
      .otp-input-box {
        letter-spacing: 12px;
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        font-family: 'Courier New', monospace;
        padding-left: 20px;
        background: #F8FAFC;
        border: 2px solid #CBD5E1;
        border-radius: 12px;
        transition: all 0.2s ease;
      }
      .otp-input-box:focus {
        border-color: #26658C;
        box-shadow: 0 0 0 4px rgba(38, 101, 140, 0.15);
        background: #FFFFFF;
      }
    </style>
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
        <h3 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Verify Your Email</h3>
        <p class="text-muted small mb-0">We have sent a 6-digit verification code to:<br><strong class="text-dark"><?= htmlspecialchars($email) ?></strong></p>
      </div>

      <!-- Messages & Flash Alerts -->
      <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger py-2.5 px-3 small rounded-3 border-0 mb-4 d-flex align-items-center gap-2 shadow-xs">
          <i class="fa-solid fa-circle-exclamation fs-6"></i>
          <div><?= htmlspecialchars($errorMessage) ?></div>
        </div>
      <?php endif; ?>

      <?php if (!empty($infoMessage)): ?>
        <div class="alert alert-info py-2.5 px-3 small rounded-3 border-0 mb-4 d-flex align-items-center gap-2 shadow-xs">
          <i class="fa-solid fa-circle-info fs-6"></i>
          <div><?= htmlspecialchars($infoMessage) ?></div>
        </div>
      <?php endif; ?>

      <?php $flash = get_flash_message(); if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> py-2.5 px-3 small rounded-3 border-0 mb-4 shadow-xs">
          <?= htmlspecialchars($flash['message']) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success py-3 px-3 small rounded-3 border-0 mb-4 shadow-xs text-center">
          <i class="fa-solid fa-circle-check fs-3 text-success mb-2 d-block"></i>
          <div class="fw-bold fs-6 mb-1 text-dark">Email verified successfully.</div>
          <div class="text-muted small mb-3">Redirecting to login in <span id="countdown">3</span> seconds...</div>
          <a href="<?= BASE_URL ?>login.php" class="btn btn-saas-primary btn-sm px-4 rounded-pill">
            Sign In Now <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        </div>
      <?php else: ?>
        <!-- OTP Verification Form -->
        <form action="<?= BASE_URL ?>verify-email.php" method="POST" autocomplete="off" onsubmit="handleVerifySubmit(this)">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="verify">
          <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

          <!-- 6-digit OTP Field -->
          <div class="mb-4">
            <label class="form-label small fw-semibold text-dark mb-2 text-center d-block">Enter Verification Code</label>
            <input type="text" name="otp" id="otpInput" class="form-control otp-input-box" placeholder="••••••" maxlength="6" pattern="[0-9]{6}" required autofocus autocomplete="one-time-code">
            <div class="form-text text-center text-muted small mt-2">Code is valid for 10 minutes.</div>
          </div>

          <!-- Buttons: Verify OTP & Resend OTP -->
          <div class="d-grid gap-2">
            <button type="submit" id="verifyBtn" class="btn btn-saas-primary py-2.5">
              <i class="fa-solid fa-shield-check me-1.5"></i> Verify OTP
            </button>
          </div>
        </form>

        <!-- Separate Form for Resend OTP -->
        <form action="<?= BASE_URL ?>verify-email.php" method="POST" class="mt-3 text-center">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="resend">
          <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
          <button type="submit" class="btn btn-link text-decoration-none small text-primary fw-semibold p-0 border-0" id="resendBtn">
            <i class="fa-solid fa-rotate-right me-1"></i> Resend OTP
          </button>
        </form>
      <?php endif; ?>

      <!-- Back to Login Link -->
      <div class="text-center mt-4 pt-3 border-top">
        <a href="<?= BASE_URL ?>login.php" class="small text-muted fw-semibold text-decoration-none">
          <i class="fa-solid fa-arrow-left me-1"></i> Back to Sign In
        </a>
      </div>
    </div>
  </div>

  <script>
    // Automatically filter input to numeric digits only
    const otpInp = document.getElementById('otpInput');
    if (otpInp) {
      otpInp.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
      });
    }

    // Form Submit Loading State
    function handleVerifySubmit(form) {
      const btn = document.getElementById('verifyBtn');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Verifying...';
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
