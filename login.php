<?php
/**
 * SkillBridge - Multi-Role Login Handler
 * Premium SaaS UI (Linear / Clerk / Vercel style) with ambient glow & interactive role selection.
 * Preserves 100% of existing PDO database authentication logic & session management.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
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

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid CSRF security token. Please try again.';
    } else {
        $loginInput   = trim($_POST['login_input'] ?? '');
        $password     = $_POST['password'] ?? '';
        $remember     = isset($_POST['remember_me']);
        $selectedRole = strtolower(trim($_POST['selected_role'] ?? 'student'));

        if (!in_array($selectedRole, ['student', 'faculty', 'admin'])) {
            $selectedRole = 'student';
        }

        if (empty($loginInput) || empty($password)) {
            $error = 'Please enter both your email/username and password.';
        } else {
            $db = Database::getInstance();
            $user = $db->fetch("SELECT * FROM users WHERE email = ? OR username = ?", [$loginInput, $loginInput]);

            if ($user && password_verify($password, $user['password'])) {
                // Strict Role-Based Account Validation
                if ($user['role'] !== $selectedRole) {
                    $error = 'Invalid email/username, password, or role.';
                } elseif ((int)($user['email_verified'] ?? 1) === 0) {
                    $verifyUrl = BASE_URL . 'verify-email.php?email=' . urlencode($user['email']);
                    $error = 'Please verify your email before logging in. <a href="' . htmlspecialchars($verifyUrl) . '" class="fw-bold text-decoration-underline text-danger ms-1">Verify Email</a>';
                } elseif ($user['role'] === 'faculty') {
                    $fac = $db->fetch("SELECT approval_status FROM faculty WHERE user_id = ?", [$user['id']]);
                    $appStatus = strtolower($fac['approval_status'] ?? 'pending');
                    $userStatus = strtolower($user['status'] ?? '');

                    if ($appStatus === 'pending' || $userStatus === 'pending') {
                        $error = 'Your faculty registration is currently under review by the administrator.';
                    } elseif ($appStatus === 'rejected' || $userStatus === 'rejected') {
                        $error = 'Your faculty registration has been rejected. Please contact the administrator for further information.';
                    } else {
                        login_user($user, $remember);
                        set_flash_message('success', "Welcome back, " . htmlspecialchars($_SESSION['full_name'] ?? $user['username']) . "!");
                        redirect(BASE_URL . 'faculty/dashboard.php');
                    }
                } else {
                    login_user($user, $remember && ($user['role'] !== 'admin'));
                    set_flash_message('success', "Welcome back, " . htmlspecialchars($_SESSION['full_name'] ?? $user['username']) . "!");
                    
                    match ($user['role']) {
                        'student' => redirect(BASE_URL . 'student/dashboard.php'),
                        'admin'   => redirect(BASE_URL . 'admin/dashboard.php'),
                        default   => redirect(BASE_URL . 'index.php')
                    };
                }
            } else {
                $error = 'Invalid email/username, password, or role.';
            }
        }
    }
}

$pageTitle = "Sign In – SkillBridge";
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
        <h3 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Welcome Back</h3>
        <p class="text-muted small mb-0">Select your role and sign in to continue your learning journey.</p>
      </div>

      <!-- Segmented Control Role Selector -->
      <?php $activeRole = $_POST['selected_role'] ?? 'student'; ?>
      <div class="role-selector" id="roleSelector">
        <button type="button" class="role-tab <?= $activeRole === 'student' ? 'active' : '' ?>" data-role="student" onclick="selectRole('student', this)">
          <span>👨‍🎓</span>
          <span>Student</span>
        </button>
        <button type="button" class="role-tab <?= $activeRole === 'faculty' ? 'active' : '' ?>" data-role="faculty" onclick="selectRole('faculty', this)">
          <span>👨‍🏫</span>
          <span>Faculty</span>
        </button>
        <button type="button" class="role-tab <?= $activeRole === 'admin' ? 'active' : '' ?>" data-role="admin" onclick="selectRole('admin', this)">
          <span>👨‍💼</span>
          <span>Admin</span>
        </button>
      </div>

      <!-- System Flash Alerts & Server Errors -->
      <div id="alertContainer">
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger py-2.5 px-3 small rounded-3 border-0 mb-4 d-flex align-items-center gap-2 shadow-xs">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div><?= $error ?></div>
          </div>
        <?php endif; ?>

        <?php $flash = get_flash_message(); if ($flash): ?>
          <div class="alert alert-<?= $flash['type'] ?> py-2.5 px-3 small rounded-3 border-0 mb-4 shadow-xs">
            <?= htmlspecialchars($flash['message']) ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Authenticated Login Form -->
      <form action="<?= BASE_URL ?>login.php" method="POST" autocomplete="off" id="loginForm" onsubmit="handleLoginSubmit(this)">
        <?= csrf_field() ?>
        <input type="hidden" name="selected_role" id="selectedRoleInput" value="<?= htmlspecialchars($activeRole) ?>">
        
        <!-- Email or Username Field -->
        <div class="mb-3.5">
          <label class="form-label small fw-semibold text-dark mb-1.5">Email Address or Username</label>
          <div class="saas-input-group">
            <i class="fa-solid fa-envelope saas-input-icon"></i>
            <input type="text" name="login_input" class="form-control" placeholder="you@example.com or username" required autofocus value="<?= htmlspecialchars($_POST['login_input'] ?? '') ?>">
          </div>
        </div>

        <!-- Password Field with Visibility Eye Toggle -->
        <div class="mb-3.5">
          <div class="d-flex justify-content-between align-items-center mb-1.5">
            <label class="form-label small fw-semibold text-dark mb-0">Password</label>
            <a href="<?= BASE_URL ?>forgot-password.php" class="small text-primary text-decoration-none fw-semibold">Forgot Password?</a>
          </div>
          <div class="saas-input-group">
            <i class="fa-solid fa-lock saas-input-icon"></i>
            <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Enter your password" required style="padding-right: 44px;">
            <button type="button" class="btn p-0 text-muted border-0 position-absolute end-0 me-3" id="togglePw" onclick="togglePasswordVisibility()" style="z-index: 5;" title="Toggle password visibility">
              <i class="fa-solid fa-eye"></i>
            </button>
          </div>
        </div>

        <!-- Remember Me Checkbox -->
        <div class="d-flex align-items-center justify-content-between mb-4" id="rememberMeWrapper">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember_me" id="rememberMe">
            <label class="form-check-input-label small text-muted" for="rememberMe">Remember me for 30 days</label>
          </div>
        </div>

        <!-- Submit Button with Loading State -->
        <button type="submit" id="submitBtn" class="btn btn-saas-primary w-100 py-2.5">
          <i class="fa-solid fa-right-to-bracket me-1.5"></i> Sign In to SkillBridge
        </button>
      </form>

      <!-- Footer Link -->
      <div class="text-center mt-4 pt-3 border-top">
        <p class="small text-muted mb-0">Don't have an account? <a href="<?= BASE_URL ?>register.php" class="text-primary fw-semibold text-decoration-none">Create Account</a></p>
      </div>
    </div>
  </div>

  <script>
    // Toggle Password Visibility
    function togglePasswordVisibility() {
      const pwInput = document.getElementById('passwordInput');
      const toggleBtn = document.getElementById('togglePw');
      const icon = toggleBtn.querySelector('i');

      if (pwInput.type === 'password') {
        pwInput.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
      } else {
        pwInput.type = 'password';
        icon.className = 'fa-solid fa-eye';
      }
    }

    // Role Tab Selector UI - Clears Validation Errors & Handles Admin Remember-Me Visibility
    function selectRole(role, btnElement) {
      document.querySelectorAll('.role-tab').forEach(tab => tab.classList.remove('active'));
      btnElement.classList.add('active');

      const roleInput = document.getElementById('selectedRoleInput');
      if (roleInput) {
        roleInput.value = role;
      }

      // Hide Remember Me checkbox for Admin
      const rememberWrapper = document.getElementById('rememberMeWrapper');
      if (rememberWrapper) {
        rememberWrapper.style.display = (role === 'admin') ? 'none' : 'flex';
      }

      // 1. Immediately hide and clear error/success alerts
      const alertContainer = document.getElementById('alertContainer');
      if (alertContainer) {
        alertContainer.innerHTML = '';
      }
      document.querySelectorAll('.alert').forEach(alert => {
        alert.style.display = 'none';
      });

      // 2. Remove validation state highlights from form fields
      document.querySelectorAll('.form-control').forEach(input => {
        input.classList.remove('is-invalid', 'is-valid', 'border-danger');
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      const activeTab = document.querySelector('.role-tab.active');
      if (activeTab) {
        const role = activeTab.getAttribute('data-role');
        const rememberWrapper = document.getElementById('rememberMeWrapper');
        if (rememberWrapper && role === 'admin') {
          rememberWrapper.style.display = 'none';
        }
      }
    });

    // Form Submit Loading State & Anti-Double-Submit
    function handleLoginSubmit(form) {
      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Authenticating...';
      return true;
    }
  </script>
</body>
</html>
