<?php
/**
 * SkillBridge - Account Registration Handler (Student & Faculty)
 * Premium SaaS UI with custom toast notifications & inline field error highlights.
 * Preserves 100% of existing PDO database registration logic & session management.
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

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid CSRF security token. Please refresh and try again.';
    } else {
        $role = trim($_POST['role'] ?? 'student');
        if (!in_array($role, ['student', 'faculty'])) {
            $role = 'student';
        }

        $firstName   = trim($_POST['first_name'] ?? '');
        $lastName    = trim($_POST['last_name'] ?? '');
        $username    = trim($_POST['username'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $countryCode = trim($_POST['country_code'] ?? '+91');
        $phoneRaw    = trim($_POST['phone'] ?? '');
        $department  = trim($_POST['department'] ?? 'Computer Science');
        $semester    = (int)($_POST['current_semester'] ?? 1);
        $designation = trim($_POST['designation'] ?? 'Assistant Professor');
        $password    = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // 1. First Name Validations
        if (empty($firstName)) {
            $error = 'First name is required.';
        } elseif (!preg_match("/^[a-zA-Z\s\-\']+$/", $firstName)) {
            $error = "First name may contain only letters, spaces, hyphens (-), and apostrophes (').";
        }
        // 2. Last Name Validations
        elseif (empty($lastName)) {
            $error = 'Last name is required.';
        } elseif (!preg_match("/^[a-zA-Z\s\-\']+$/", $lastName)) {
            $error = "Last name may contain only letters, spaces, hyphens (-), and apostrophes (').";
        }
        // 3. Username Validations
        elseif (empty($username)) {
            $error = 'Username cannot be empty.';
        } elseif (!preg_match("/^[a-zA-Z0-9_\.]+$/", $username)) {
            $error = 'Username can only contain letters, numbers, underscores, and periods.';
        }
        // 4. Email Validations
        elseif (empty($email) || !validate_email($email)) {
            $error = 'Please enter a valid email address.';
        }
        // 5. Mobile Number (10 Digits) Validations
        elseif (!empty($phoneRaw) && !preg_match("/^[0-9]{10}$/", $phoneRaw)) {
            $error = 'Please enter a valid 10-digit mobile number.';
        }
        // 6. Password Validations
        elseif (empty($password) || strlen($password) < 6) {
            $error = 'Password must be at least 6 characters long.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } else {
            $db = Database::getInstance();
            
            // Format full phone string with country code
            $phoneFull = !empty($phoneRaw) ? ($countryCode . ' ' . $phoneRaw) : '';

            // Check uniqueness of username or email
            $existing = $db->fetch("SELECT id FROM users WHERE username = ? OR email = ?", [$username, $email]);
            if ($existing) {
                $error = 'Username or email address is already registered.';
            } else {
                try {
                    $db->beginTransaction();

                    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                    // Generate secure 6-digit OTP (10 minutes validity)
                    $otp = (string)random_int(100000, 999999);
                    $otpExpiry = date('Y-m-d H:i:s', time() + 600);

                    $userId = $db->insert('users', [
                        'username'               => $username,
                        'email'                  => $email,
                        'password'               => $passwordHash,
                        'role'                   => $role,
                        'email_verified'         => 0,
                        'email_verification_otp' => $otp,
                        'otp_expiry'             => $otpExpiry,
                        'created_at'             => date('Y-m-d H:i:s')
                    ]);

                    if ($role === 'faculty') {
                        $empCode = 'EMP-' . (1000 + $userId);
                        $db->insert('faculty', [
                            'user_id'       => $userId,
                            'employee_code' => $empCode,
                            'first_name'    => $firstName,
                            'last_name'     => $lastName,
                            'avatar'        => 'default-avatar.png',
                            'department'    => $department,
                            'designation'   => $designation,
                            'created_at'    => date('Y-m-d H:i:s')
                        ]);
                        log_activity($userId, 'REGISTER', "New faculty registered: {$username} ({$empCode})");
                    } else {
                        $studentCode = 'STU-' . (1000 + $userId);
                        $db->insert('students', [
                            'user_id'          => $userId,
                            'student_code'     => $studentCode,
                            'first_name'       => $firstName,
                            'last_name'        => $lastName,
                            'avatar'           => 'default-avatar.png',
                            'phone'            => $phoneFull,
                            'department'       => $department,
                            'current_semester' => $semester,
                            'created_at'       => date('Y-m-d H:i:s')
                        ]);
                        log_activity($userId, 'REGISTER', "New student registered: {$username} ({$studentCode})");
                    }

                    $db->commit();

                    // Send verification OTP via email
                    send_otp_email($email, $otp);

                    set_flash_message('info', 'Registration successful! A 6-digit verification code has been sent to your email address.');
                    // Redirect to verify-email.php passing email in query param (never OTP)
                    redirect(BASE_URL . 'verify-email.php?email=' . urlencode($email));

                } catch (Exception $e) {
                    $db->rollBack();
                    $error = 'Database error during registration: ' . $e->getMessage();
                }
            }
        }
    }
}

$pageTitle = "Create Account – SkillBridge";
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

  <!-- Floating Toast Notification Container -->
  <div id="toastNotificationContainer" class="toast-notification-container" aria-live="polite"></div>

  <!-- Ambient Background Orbs -->
  <div class="auth-ambient-glow-1"></div>
  <div class="auth-ambient-glow-2"></div>

  <!-- Main Centered SaaS Registration Wrapper -->
  <div class="auth-center-wrapper" style="max-width: 580px;">
    <div class="auth-card-modern">
      
      <!-- Welcome Header -->
      <div class="text-center mb-4">
        <a href="<?= BASE_URL ?>" class="d-inline-flex align-items-center gap-2.5 text-decoration-none mb-3">
          <div class="rounded-3 p-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="width:46px; height:46px; background: linear-gradient(135deg, #26658C, #021024);">
            <i class="fa-solid fa-brain fs-4"></i>
          </div>
          <span class="fw-bold fs-3 text-dark" style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">SkillBridge</span>
        </a>
        <h3 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Create Your Account</h3>
        <p class="text-muted small mb-0">Join SkillBridge to analyze technical skill gaps & personalize roadmaps.</p>
      </div>

      <!-- Segmented Control Role Selector -->
      <div class="role-selector" id="roleSelector">
        <button type="button" class="role-tab active" id="tabStudent" data-role="student" onclick="selectRegRole('student', this)">
          <span>👨‍🎓</span>
          <span>Student</span>
        </button>
        <button type="button" class="role-tab" id="tabFaculty" data-role="faculty" onclick="selectRegRole('faculty', this)">
          <span>👨‍🏫</span>
          <span>Faculty</span>
        </button>
      </div>

      <!-- System Flash Alerts & Server Errors -->
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2.5 px-3 small rounded-3 border-0 mb-4 d-flex align-items-center gap-2 shadow-xs">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <div><?= htmlspecialchars($error) ?></div>
        </div>
      <?php endif; ?>

      <!-- Registration Form -->
      <form action="<?= BASE_URL ?>register.php" method="POST" autocomplete="off" id="regForm" onsubmit="return handleRegSubmit(this)">
        <?= csrf_field() ?>
        <input type="hidden" name="role" id="selectedRoleInput" value="student">
        
        <!-- Name Fields -->
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold text-dark mb-1.5">First Name <span class="text-danger">*</span></label>
            <div class="saas-input-group">
              <i class="fa-solid fa-user saas-input-icon"></i>
              <input type="text" name="first_name" id="firstNameInput" class="form-control" placeholder="John" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold text-dark mb-1.5">Last Name <span class="text-danger">*</span></label>
            <div class="saas-input-group">
              <i class="fa-solid fa-user saas-input-icon"></i>
              <input type="text" name="last_name" id="lastNameInput" class="form-control" placeholder="Doe" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
            </div>
          </div>
        </div>

        <!-- Username & Email -->
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold text-dark mb-1.5">Username <span class="text-danger">*</span></label>
            <div class="saas-input-group">
              <i class="fa-solid fa-at saas-input-icon"></i>
              <input type="text" name="username" id="usernameInput" class="form-control" placeholder="johndoe" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold text-dark mb-1.5">Email Address <span class="text-danger">*</span></label>
            <div class="saas-input-group">
              <i class="fa-solid fa-envelope saas-input-icon"></i>
              <input type="email" name="email" id="emailInput" class="form-control" placeholder="you@university.edu" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
          </div>
        </div>

        <!-- Department & Semester / Designation -->
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold text-dark mb-1.5">Department</label>
            <select name="department" class="form-select rounded-3" style="background:#F8FAFC; border-color:#E2E8F0; padding:0.75rem 1rem; font-size:0.9rem;">
              <option value="Computer Science">Computer Science</option>
              <option value="Information Technology">Information Technology</option>
              <option value="Software Engineering">Software Engineering</option>
              <option value="Data Science">Data Science</option>
              <option value="Systems Engineering">Systems Engineering</option>
            </select>
          </div>
          
          <!-- Student Semester Selection -->
          <div class="col-md-6" id="studentSemesterCol">
            <label class="form-label small fw-semibold text-dark mb-1.5">Current Semester</label>
            <select name="current_semester" class="form-select rounded-3" style="background:#F8FAFC; border-color:#E2E8F0; padding:0.75rem 1rem; font-size:0.9rem;">
              <?php for ($i=1; $i<=8; $i++): ?>
                <option value="<?= $i ?>">Semester <?= $i ?></option>
              <?php endfor; ?>
            </select>
          </div>

          <!-- Faculty Designation Field -->
          <div class="col-md-6" id="facultyDesignationCol" style="display:none;">
            <label class="form-label small fw-semibold text-dark mb-1.5">Designation</label>
            <div class="saas-input-group">
              <i class="fa-solid fa-id-badge saas-input-icon"></i>
              <input type="text" name="designation" class="form-control" placeholder="Assistant Professor" value="Assistant Professor">
            </div>
          </div>
        </div>

        <!-- Mobile Number with Country Code Selector -->
        <div class="mb-3">
          <label class="form-label small fw-semibold text-dark mb-1.5">Mobile Number</label>
          <div class="input-group saas-input-group">
            <select name="country_code" class="form-select text-dark fw-semibold border-end-0" style="max-width: 125px; background: #F8FAFC; border-color: #E2E8F0; border-top-left-radius: 12px; border-bottom-left-radius: 12px; font-size: 0.88rem;">
              <option value="+91" selected>🇮🇳 +91</option>
              <option value="+1">🇺🇸 +1</option>
              <option value="+44">🇬🇧 +44</option>
              <option value="+971">🇦🇪 +971</option>
              <option value="+1">🇨🇦 +1</option>
              <option value="+61">🇦🇺 +61</option>
              <option value="+65">🇸🇬 +65</option>
              <option value="+49">🇩🇪 +49</option>
            </select>
            <input type="text" name="phone" id="phoneInput" class="form-control border-start-0" placeholder="9876543210" maxlength="10" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px;">
          </div>
          <div class="text-muted" style="font-size: 11px; margin-top: 4px;">Enter your 10-digit mobile number without spaces or symbols.</div>
        </div>

        <!-- Password Fields -->
        <div class="row g-3 mb-2">
          <div class="col-md-6">
            <label class="form-label small fw-semibold text-dark mb-1.5">Password <span class="text-danger">*</span></label>
            <div class="saas-input-group">
              <i class="fa-solid fa-lock saas-input-icon"></i>
              <input type="password" name="password" id="regPw1" class="form-control" placeholder="Min. 6 characters" required oninput="checkStrength(this.value)" style="padding-right:44px;">
              <button type="button" class="btn p-0 text-muted border-0 position-absolute end-0 me-3" onclick="togglePw('regPw1', this)" style="z-index: 5;" title="Toggle password visibility">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold text-dark mb-1.5">Confirm Password <span class="text-danger">*</span></label>
            <div class="saas-input-group">
              <i class="fa-solid fa-shield-halved saas-input-icon"></i>
              <input type="password" name="confirm_password" id="regPw2" class="form-control" placeholder="Repeat password" required style="padding-right:44px;">
              <button type="button" class="btn p-0 text-muted border-0 position-absolute end-0 me-3" onclick="togglePw('regPw2', this)" style="z-index: 5;" title="Toggle password visibility">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Password Strength Meter Bar -->
        <div class="mb-4">
          <div class="pw-strength" id="pwStrength">
            <div class="pw-strength-bar" id="pb1"></div>
            <div class="pw-strength-bar" id="pb2"></div>
            <div class="pw-strength-bar" id="pb3"></div>
            <div class="pw-strength-bar" id="pb4"></div>
          </div>
          <div class="pw-strength-label" id="pwLabel">Enter a password</div>
        </div>

        <!-- Terms Checkbox -->
        <div class="mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="agreeTerms" required checked>
            <label class="form-check-input-label small text-muted" for="agreeTerms">
              I agree to the <a href="<?= BASE_URL ?>terms-of-service.php" target="_blank" class="text-primary text-decoration-none">Terms of Service</a> & <a href="<?= BASE_URL ?>privacy-policy.php" target="_blank" class="text-primary text-decoration-none">Privacy Policy</a>
            </label>
          </div>
        </div>

        <!-- Submit Button with Loading State -->
        <button type="submit" id="submitBtn" class="btn btn-saas-primary w-100 py-2.5">
          <i class="fa-solid fa-user-plus me-1.5"></i> <span id="submitBtnText">Create Student Account</span>
        </button>
      </form>

      <!-- Footer Link -->
      <div class="text-center mt-4 pt-3 border-top">
        <p class="small text-muted mb-0">Already have an account? <a href="<?= BASE_URL ?>login.php" class="text-primary fw-semibold text-decoration-none">Sign In</a></p>
      </div>
    </div>
  </div>

  <script>
    // Show Toast Notification
    function showToastNotification(message, type = 'danger') {
      const container = document.getElementById('toastNotificationContainer');
      if (!container) return;

      const toast = document.createElement('div');
      toast.className = `toast-notification-item ${type === 'success' ? 'toast-success' : ''}`;
      
      const icon = type === 'success' ? '<i class="fa-solid fa-circle-check text-success"></i>' : '<i class="fa-solid fa-triangle-exclamation text-danger"></i>';

      toast.innerHTML = `
        <div class="d-flex align-items-center gap-2">
          ${icon}
          <div>${message}</div>
        </div>
        <button type="button" class="btn-close btn-close-sm ms-2" onclick="this.parentElement.remove()"></button>
      `;

      container.appendChild(toast);

      setTimeout(() => {
        if (toast && toast.parentNode) {
          toast.style.opacity = '0';
          toast.style.transform = 'translateY(-10px)';
          toast.style.transition = 'all 0.3s ease';
          setTimeout(() => toast.remove(), 300);
        }
      }, 4000);
    }

    // Inline Field Error Highlighting
    function showFieldError(inputElem, message) {
      if (!inputElem) return;
      inputElem.classList.add('is-invalid');
      
      const parent = inputElem.closest('.mb-3, .mb-3.5, .col-md-6') || inputElem.parentElement;
      let existingErr = parent.querySelector('.field-error-text');
      if (!existingErr) {
        existingErr = document.createElement('div');
        existingErr.className = 'field-error-text';
        parent.appendChild(existingErr);
      }
      existingErr.innerHTML = `<i class="fa-solid fa-circle-exclamation me-1"></i> ${message}`;
    }

    // Clear Field Errors
    function clearFieldErrors() {
      document.querySelectorAll('.form-control').forEach(inp => inp.classList.remove('is-invalid'));
      document.querySelectorAll('.field-error-text').forEach(err => err.remove());
    }

    // Attach input event listeners to clear error styling dynamically
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.form-control').forEach(inp => {
        inp.addEventListener('input', () => {
          inp.classList.remove('is-invalid');
          const parent = inp.closest('.mb-3, .mb-3.5, .col-md-6') || inp.parentElement;
          const errText = parent.querySelector('.field-error-text');
          if (errText) errText.remove();
        });
      });
    });

    // Toggle Password Visibility
    function togglePw(id, btn) {
      const inp = document.getElementById(id);
      const icon = btn.querySelector('i');
      if (inp.type === 'password') { 
        inp.type = 'text'; 
        icon.className = 'fa-solid fa-eye-slash'; 
      } else { 
        inp.type = 'password'; 
        icon.className = 'fa-solid fa-eye'; 
      }
    }

    // Role Tab Selector for Registration
    function selectRegRole(role, btnElement) {
      document.querySelectorAll('.role-tab').forEach(tab => tab.classList.remove('active'));
      btnElement.classList.add('active');

      document.getElementById('selectedRoleInput').value = role;

      const isStudent = role === 'student';
      document.getElementById('studentSemesterCol').style.display = isStudent ? 'block' : 'none';
      document.getElementById('facultyDesignationCol').style.display = isStudent ? 'none' : 'block';

      document.getElementById('submitBtnText').textContent = isStudent ? 'Create Student Account' : 'Create Faculty Account';
      clearFieldErrors();
    }

    // Dynamic Password Strength Meter
    function checkStrength(pw) {
      const bars = [document.getElementById('pb1'), document.getElementById('pb2'), document.getElementById('pb3'), document.getElementById('pb4')];
      const label = document.getElementById('pwLabel');
      if (!bars[0] || !label) return;

      bars.forEach(b => { b.className = 'pw-strength-bar'; });
      if (!pw || pw.length === 0) {
        label.textContent = 'Enter a password';
        label.className = 'pw-strength-label text-muted';
        return;
      }

      let strength = 0;
      if (pw.length >= 6) strength++;
      if (pw.length >= 10) strength++;
      if (/[A-Z]/.test(pw)) strength++;
      if (/[0-9]/.test(pw) && /[^A-Za-z0-9]/.test(pw)) strength++;

      const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
      const colors = ['', 'text-danger', 'text-warning', 'text-accent', 'text-success'];

      for (let i = 0; i < strength; i++) bars[i].classList.add('active-' + (i + 1));
      label.textContent = labels[strength] || 'Weak';
      label.className = 'pw-strength-label ' + (colors[strength] || 'text-danger');
    }

    // Client-side Form Validation & Submit Handler with Toast Notifications
    function handleRegSubmit(form) {
      clearFieldErrors();

      const fnInput = document.getElementById('firstNameInput');
      const lnInput = document.getElementById('lastNameInput');
      const unInput = document.getElementById('usernameInput');
      const emInput = document.getElementById('emailInput');
      const phInput = document.getElementById('phoneInput');
      const pw1Input = document.getElementById('regPw1');
      const pw2Input = document.getElementById('regPw2');

      const firstName = fnInput.value.trim();
      const lastName = lnInput.value.trim();
      const username = unInput.value.trim();
      const email = emInput.value.trim();
      const phone = phInput.value.trim();
      const pw1 = pw1Input.value;
      const pw2 = pw2Input.value;

      const nameRegex = /^[a-zA-Z\s\-\']+$/;
      const userRegex = /^[a-zA-Z0-9_\.]+$/;
      const phoneRegex = /^[0-9]{10}$/;

      if (!nameRegex.test(firstName)) {
        const msg = "First name may contain only letters, spaces, hyphens (-), and apostrophes (').";
        showFieldError(fnInput, msg);
        showToastNotification(msg, 'danger');
        fnInput.focus();
        return false;
      }

      if (!nameRegex.test(lastName)) {
        const msg = "Last name may contain only letters, spaces, hyphens (-), and apostrophes (').";
        showFieldError(lnInput, msg);
        showToastNotification(msg, 'danger');
        lnInput.focus();
        return false;
      }

      if (!userRegex.test(username)) {
        const msg = "Username can only contain letters, numbers, underscores, and periods.";
        showFieldError(unInput, msg);
        showToastNotification(msg, 'danger');
        unInput.focus();
        return false;
      }

      if (phone.length > 0 && !phoneRegex.test(phone)) {
        const msg = "Please enter a valid 10-digit mobile number.";
        showFieldError(phInput, msg);
        showToastNotification(msg, 'danger');
        phInput.focus();
        return false;
      }

      if (pw1 !== pw2) {
        const msg = "Passwords do not match.";
        showFieldError(pw2Input, msg);
        showToastNotification(msg, 'danger');
        pw2Input.focus();
        return false;
      }

      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Creating Account...';
      return true;
    }
  </script>
</body>
</html>
