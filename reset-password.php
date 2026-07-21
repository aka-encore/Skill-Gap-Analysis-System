<?php
/**
 * SkillBridge - Reset Password Token Handler
 * Premium SaaS UI matching the Login page.
 * Preserves 100% of existing token validation & password hashing logic.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/validators.php';

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$error = '';
$validToken = false;

$db = Database::getInstance();
$reset = null;

if (!empty($token)) {
    $reset = $db->fetch("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()", [$token]);
    if ($reset) {
        $validToken = true;
    } else {
        $error = 'Invalid or expired password reset token.';
    }
} else {
    $error = 'No reset token provided.';
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && $validToken) {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } else {
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($password) || strlen($password) < 6) {
            $error = 'Password must be at least 6 characters long.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $db->update('users', ['password' => $hash], 'email = ?', [$reset['email']]);
            $db->delete('password_resets', 'email = ?', [$reset['email']]);

            set_flash_message('success', 'Your password has been reset successfully. You may now log in.');
            redirect(BASE_URL . 'login.php');
        }
    }
}

$pageTitle = "Set New Password – SkillBridge";
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

  <!-- Floating Technology Background Icons (Parallax Animation) -->
  <div class="tech-bg-container" aria-hidden="true">
    <div class="tech-icon-wrap" style="top: 6%; left: 4%; animation-duration: 18s;">
      <i class="tech-icon fa-brands fa-html5" style="--brand-color: #E34F26; font-size: 3.2rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 22%; left: 12%; animation-duration: 24s; animation-delay: -3s;">
      <i class="tech-icon fa-brands fa-python" style="--brand-color: #3776AB; font-size: 3.5rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 8%; left: 22%; animation-duration: 20s; animation-delay: -7s;">
      <i class="tech-icon fa-brands fa-react" style="--brand-color: #61DAFB; font-size: 3.4rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 35%; left: 5%; animation-duration: 22s; animation-delay: -2s;">
      <i class="tech-icon fa-brands fa-node-js" style="--brand-color: #339933; font-size: 2.9rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 48%; left: 14%; animation-duration: 19s; animation-delay: -11s;">
      <i class="tech-icon fa-brands fa-docker" style="--brand-color: #2496ED; font-size: 3.1rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 64%; left: 6%; animation-duration: 23s; animation-delay: -4s;">
      <i class="tech-icon fa-brands fa-square-js" style="--brand-color: #F7DF1E; font-size: 3.2rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 60%; left: 24%; animation-duration: 20s; animation-delay: -6s;">
      <i class="tech-icon fa-solid fa-brain" style="--brand-color: #8E75FF; font-size: 3.4rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 5%; left: 68%; animation-duration: 21s; animation-delay: -2s;">
      <i class="tech-icon fa-brands fa-css3-alt" style="--brand-color: #1572B6; font-size: 3.3rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 18%; left: 82%; animation-duration: 25s; animation-delay: -9s;">
      <i class="tech-icon fa-brands fa-java" style="--brand-color: #ED8B00; font-size: 3.1rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 28%; left: 88%; animation-duration: 20s; animation-delay: -6s;">
      <i class="tech-icon fa-brands fa-php" style="--brand-color: #777BB4; font-size: 3.0rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 62%; left: 75%; animation-duration: 22s; animation-delay: -8s;">
      <i class="tech-icon fa-brands fa-laravel" style="--brand-color: #FF2D20; font-size: 3.1rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 76%; left: 86%; animation-duration: 18s; animation-delay: -3s;">
      <i class="tech-icon fa-brands fa-aws" style="--brand-color: #FF9900; font-size: 3.3rem;"></i>
    </div>
    <div class="tech-icon-wrap" style="top: 92%; left: 48%; animation-duration: 21s; animation-delay: -2s;">
      <i class="tech-icon fa-solid fa-database" style="--brand-color: #4479A1; font-size: 2.8rem;"></i>
    </div>
  </div>

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
        <h3 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Set New Password</h3>
        <p class="text-muted small mb-0">Choose a new password for your SkillBridge account.</p>
      </div>

      <!-- Error Alerts -->
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2.5 px-3 small rounded-3 border-0 mb-4 d-flex align-items-center gap-2 shadow-xs">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <div><?= htmlspecialchars($error) ?></div>
        </div>
      <?php endif; ?>

      <?php if ($validToken): ?>
        <!-- Reset Password Form -->
        <form action="<?= BASE_URL ?>reset-password.php" method="POST" autocomplete="off" onsubmit="handleFormSubmit(this)">
          <?= csrf_field() ?>
          <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

          <!-- New Password Field -->
          <div class="mb-3.5">
            <label class="form-label small fw-semibold text-dark mb-1.5">New Password</label>
            <div class="saas-input-group">
              <i class="fa-solid fa-lock saas-input-icon"></i>
              <input type="password" name="password" id="pwInput1" class="form-control" placeholder="Min. 6 characters" required autofocus style="padding-right: 44px;">
              <button type="button" class="btn p-0 text-muted border-0 position-absolute end-0 me-3" onclick="togglePw('pwInput1', this)" style="z-index: 5;" title="Toggle password visibility">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- Confirm New Password Field -->
          <div class="mb-4">
            <label class="form-label small fw-semibold text-dark mb-1.5">Confirm New Password</label>
            <div class="saas-input-group">
              <i class="fa-solid fa-shield-halved saas-input-icon"></i>
              <input type="password" name="confirm_password" id="pwInput2" class="form-control" placeholder="Re-enter password" required style="padding-right: 44px;">
              <button type="button" class="btn p-0 text-muted border-0 position-absolute end-0 me-3" onclick="togglePw('pwInput2', this)" style="z-index: 5;" title="Toggle password visibility">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- Submit Button with Loading State -->
          <button type="submit" id="submitBtn" class="btn btn-saas-primary w-100 py-2.5 mb-3">
            <i class="fa-solid fa-floppy-disk me-1.5"></i> Update Password & Sign In
          </button>
        </form>
      <?php else: ?>
        <div class="text-center py-3">
          <a href="<?= BASE_URL ?>forgot-password.php" class="btn btn-saas-primary rounded-pill px-4 py-2 small fw-semibold">
            <i class="fa-solid fa-rotate-left me-1"></i> Request New Reset Token
          </a>
        </div>
      <?php endif; ?>

      <!-- Back to Login Link -->
      <div class="text-center mt-3 pt-3 border-top">
        <a href="<?= BASE_URL ?>login.php" class="small text-primary fw-semibold text-decoration-none">
          <i class="fa-solid fa-arrow-left me-1"></i> Back to Sign In
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
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Updating Password...';
      }
      return true;
    }

    // Parallax Effect for Background Tech Icons
    let mouseX = 0, mouseY = 0;
    let currentX = 0, currentY = 0;

    document.addEventListener('mousemove', (e) => {
      mouseX = (e.clientX - window.innerWidth / 2) * 0.025;
      mouseY = (e.clientY - window.innerHeight / 2) * 0.025;
    });

    function animateParallax() {
      currentX += (mouseX - currentX) * 0.05;
      currentY += (mouseY - currentY) * 0.05;
      const bg = document.querySelector('.tech-bg-container');
      if (bg) {
        bg.style.transform = `translate3d(${currentX}px, ${currentY}px, 0)`;
      }
      requestAnimationFrame(animateParallax);
    }
    requestAnimationFrame(animateParallax);
  </script>
</body>
</html>
