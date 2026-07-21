<?php
/**
 * SkillBridge - Forgot Password Handler
 * Premium SaaS UI matching the Login page.
 * Preserves 100% of existing backend PHP token generation & validation logic.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/validators.php';

$message = '';
$resetLink = '';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!verify_csrf_token()) {
        $message = 'Invalid security token.';
    } else {
        $email = trim($_POST['email'] ?? '');
        if (empty($email) || !validate_email($email)) {
            $message = 'Please enter a valid email address.';
        } else {
            $db = Database::getInstance();
            $user = $db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiration
                
                $db->delete('password_resets', 'email = ?', [$email]);
                $db->insert('password_resets', [
                    'email' => $email,
                    'token' => $token,
                    'expires_at' => $expires,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $resetLink = BASE_URL . "reset-password.php?token=" . $token;
                $message = "Password reset token generated! In production, an email is sent. For local testing, click the reset link below.";
            } else {
                // Generic security response
                $message = "If an account with that email exists, password reset instructions have been generated.";
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
        <h3 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Reset Password</h3>
        <p class="text-muted small mb-0">Enter your email address to receive password recovery instructions.</p>
      </div>

      <!-- Messages & Flash Alerts -->
      <?php if (!empty($message)): ?>
        <div class="alert alert-info py-2.5 px-3 small rounded-3 border-0 mb-4 shadow-xs">
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fa-solid fa-circle-info"></i>
            <div class="fw-semibold">Recovery Notice</div>
          </div>
          <div><?= htmlspecialchars($message) ?></div>
          <?php if (!empty($resetLink)): ?>
            <div class="mt-3 pt-2 border-top">
              <a href="<?= $resetLink ?>" class="btn btn-dark btn-sm rounded-pill w-100 py-1.5 fw-semibold">
                <i class="fa-solid fa-key me-1"></i> Click Here to Reset Password
              </a>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- Recovery Form -->
      <form action="<?= BASE_URL ?>forgot-password.php" method="POST" autocomplete="off" onsubmit="handleFormSubmit(this)">
        <?= csrf_field() ?>
        
        <div class="mb-4">
          <label class="form-label small fw-semibold text-dark mb-1.5">Account Email Address</label>
          <div class="saas-input-group">
            <i class="fa-solid fa-envelope saas-input-icon"></i>
            <input type="email" name="email" class="form-control" placeholder="you@example.com" required autofocus>
          </div>
        </div>

        <!-- Submit Button with Loading State -->
        <button type="submit" id="submitBtn" class="btn btn-saas-primary w-100 py-2.5 mb-3">
          <i class="fa-solid fa-paper-plane me-1.5"></i> Send Recovery Instructions
        </button>
      </form>

      <!-- Back to Login Link -->
      <div class="text-center mt-3 pt-3 border-top">
        <a href="<?= BASE_URL ?>login.php" class="small text-primary fw-semibold text-decoration-none">
          <i class="fa-solid fa-arrow-left me-1"></i> Back to Sign In
        </a>
      </div>
    </div>
  </div>

  <script>
    // Form Submit Loading State
    function handleFormSubmit(form) {
      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Processing Request...';
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
