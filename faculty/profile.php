<?php
/**
 * SkillBridge - Faculty Profile Center
 * Fully dynamic PDO database-driven faculty profile management.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');

$facultyId = $_SESSION['profile_id'];
$userId    = $_SESSION['user_id'];
$db        = Database::getInstance();

// 1. Handle Profile Info & Avatar Upload Submit
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['update_profile_action'])) {
    $firstName   = trim($_POST['first_name'] ?? '');
    $lastName    = trim($_POST['last_name'] ?? '');
    $dept        = trim($_POST['department'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $mobile      = trim($_POST['mobile_number'] ?? '');
    $experience  = isset($_POST['experience_years']) ? (int)$_POST['experience_years'] : 0;

    $facultyRow = $db->fetch("SELECT * FROM faculty WHERE id = ?", [$facultyId]);
    $avatarName = $facultyRow['avatar'] ?? 'default-avatar.png';

    // Name validations
    if (empty($firstName) || empty($lastName)) {
        set_flash_message('danger', 'First name and last name are required.');
        redirect(BASE_URL . 'faculty/profile.php');
    } elseif (!preg_match("/^[a-zA-Z\s\-]+$/", $firstName)) {
        set_flash_message('danger', 'First name cannot contain numbers.');
        redirect(BASE_URL . 'faculty/profile.php');
    } elseif (!preg_match("/^[a-zA-Z\s\-]+$/", $lastName)) {
        set_flash_message('danger', 'Last name cannot contain numbers.');
        redirect(BASE_URL . 'faculty/profile.php');
    }

    // Phone / Mobile validations
    $currPhone = trim($facultyRow['mobile_number'] ?? '');
    if ($mobile !== $currPhone && !empty($mobile)) {
        if (!preg_match('/^[0-9]{10}$/', $mobile)) {
            set_flash_message('danger', 'Mobile number must contain exactly 10 digits.');
            redirect(BASE_URL . 'faculty/profile.php');
        }
    }

    // Handle Avatar Upload & Removal
    if (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] === '1') {
        if (!empty($facultyRow['avatar']) && $facultyRow['avatar'] !== 'default-avatar.png') {
            $oldFile = AVATAR_UPLOAD_DIR . $facultyRow['avatar'];
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }
        $avatarName = 'default-avatar.png';
        $_SESSION['avatar'] = 'default-avatar.png';
    } elseif (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['avatar_file']['tmp_name'];
        $origName = $_FILES['avatar_file']['name'];
        $size = $_FILES['avatar_file']['size'];
        $mime = mime_content_type($tmp);

        if ($size <= MAX_FILE_SIZE && in_array($mime, ALLOWED_IMAGE_TYPES)) {
            $ext = pathinfo($origName, PATHINFO_EXTENSION);
            $newFilename = 'avatar_user_' . $userId . '_' . time() . '.' . strtolower($ext);
            $dest = AVATAR_UPLOAD_DIR . $newFilename;

            if (!file_exists(AVATAR_UPLOAD_DIR)) {
                @mkdir(AVATAR_UPLOAD_DIR, 0777, true);
            }

            if (move_uploaded_file($tmp, $dest)) {
                // Clean up previous custom avatar
                if (!empty($facultyRow['avatar']) && $facultyRow['avatar'] !== 'default-avatar.png') {
                    $oldFile = AVATAR_UPLOAD_DIR . $facultyRow['avatar'];
                    if (file_exists($oldFile)) {
                        @unlink($oldFile);
                    }
                }
                $avatarName = $newFilename;
                $_SESSION['avatar'] = $newFilename;
            }
        }
    }

    $db->update('faculty', [
        'first_name'       => $firstName,
        'last_name'        => $lastName,
        'department'       => $dept,
        'designation'      => $designation,
        'avatar'           => $avatarName,
        'mobile_number'    => $mobile,
        'experience_years' => $experience
    ], 'id = ?', [$facultyId]);

    $_SESSION['user_name'] = 'Prof. ' . $firstName . ' ' . $lastName;
    set_flash_message('success', 'Faculty profile updated successfully.');
    redirect(BASE_URL . 'faculty/profile.php');
}

// 2. Fetch authenticated faculty & user record
$faculty = $db->fetch(
    "SELECT f.*, u.username, u.email, u.role, u.created_at as user_created 
     FROM faculty f 
     JOIN users u ON f.user_id = u.id 
     WHERE f.id = ?",
    [$facultyId]
);

$facultyName = htmlspecialchars(($faculty['first_name'] ?? 'Faculty') . ' ' . ($faculty['last_name'] ?? 'Member'));

// 3. Dynamic Faculty Metrics Calculations
$createdAssessments = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessments WHERE created_by_faculty_id = ?", [$facultyId])['cnt'] ?? 0);
$totalQuestionsAdded = (int)($db->fetch("SELECT COUNT(*) as cnt FROM questions q JOIN question_banks qb ON q.question_bank_id = qb.id WHERE qb.created_by_faculty_id = ?", [$facultyId])['cnt'] ?? 0);
$deptStudentsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM students WHERE department = ?", [$faculty['department'] ?? 'Computer Science'])['cnt'] ?? 0);
$deptAttemptsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results ar JOIN students s ON ar.student_id = s.id WHERE s.department = ?", [$faculty['department'] ?? 'Computer Science'])['cnt'] ?? 0);

$pageTitle = "Faculty Profile - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<style>
/* ── Faculty Profile Redesign CSS ── */
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
      <h4 class="fw-bold mb-0 text-primary" style="font-size: 1.25rem;"><i class="fa-solid fa-circle-user text-primary me-2"></i>My Profile Dashboard</h4>
      <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary rounded-pill px-3.5 py-2 small fw-semibold shadow-sm hover-lift" data-bs-toggle="modal" data-bs-target="#editFacultyModal">
          <i class="fa-solid fa-user-pen me-1.5"></i>Edit Profile
        </button>
        <a href="<?= BASE_URL ?>faculty/settings.php" class="btn btn-outline-secondary rounded-pill px-3.5 py-2 small fw-semibold shadow-sm hover-lift">
          <i class="fa-solid fa-gear me-1.5"></i>Settings
        </a>
      </div>
    </div>

    <div class="row g-4 align-items-stretch">
      <!-- LEFT COLUMN -->
      <div class="col-lg-4 col-md-5 col-12 border-lg-end pe-lg-4 d-flex flex-column justify-content-between">
        <div>
          <!-- Large Profile Picture -->
          <div class="profile-avatar-container mb-3">
            <?php 
              $avatarPath = resolve_avatar_url($faculty['avatar'] ?? '', 'faculty');
            ?>
            <div class="rounded-circle overflow-hidden border border-3 border-primary shadow-sm" style="width: 100%; height: 100%;">
              <img src="<?= $avatarPath ?>" alt="<?= $facultyName ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <button type="button" class="btn btn-primary rounded-circle position-absolute bottom-0 end-0 p-0 d-flex align-items-center justify-content-center hover-lift shadow" style="width: 38px; height: 38px; border: 3px solid var(--bg-card);" data-bs-toggle="modal" data-bs-target="#editFacultyModal" title="Upload Photo">
              <i class="fa-solid fa-camera" style="font-size: 0.85rem;"></i>
            </button>
          </div>

          <!-- Faculty Name & Username -->
          <div class="text-center text-md-start mb-3">
            <h2 class="fw-bold mb-1" style="color: var(--text-heading); font-size: 1.5rem;">Prof. <?= $facultyName ?></h2>
            <div class="small" style="color: var(--text-muted); font-weight: 500;">
              <span class="badge saas-badge-primary mb-2">
                <i class="fa-solid fa-graduation-cap me-1"></i><?= htmlspecialchars($faculty['designation'] ?? 'Assistant Professor') ?>
              </span>
              <div class="text-muted"><i class="fa-solid fa-at text-primary me-1 text-lowercase"></i><?= htmlspecialchars($faculty['username'] ?? '') ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN -->
      <div class="col-lg-8 col-md-7 col-12 ps-lg-4 mt-md-0 mt-4">
        <div class="h-100 d-flex flex-column justify-content-between">
          <div>
            <h5 class="fw-bold mb-3 text-primary" style="font-size: 1.05rem;">
              <i class="fa-solid fa-address-card text-primary me-2"></i>Profile Information
            </h5>
            
            <div class="row g-3">
              <!-- Employee Code -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-id-card"></i>
                  </div>
                  <div>
                    <div class="info-item-label">Employee ID</div>
                    <div class="info-item-value"><?= !empty($faculty['employee_code']) ? htmlspecialchars($faculty['employee_code']) : 'Not Provided' ?></div>
                  </div>
                </div>
              </div>

              <!-- College Name -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-school"></i>
                  </div>
                  <div>
                    <div class="info-item-label">College Name</div>
                    <div class="info-item-value"><?= !empty($faculty['college_name']) ? htmlspecialchars($faculty['college_name']) : 'Not Provided' ?></div>
                  </div>
                </div>
              </div>

              <!-- Department -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-building-columns"></i>
                  </div>
                  <div>
                    <div class="info-item-label">Department</div>
                    <div class="info-item-value"><?= !empty($faculty['department']) ? htmlspecialchars($faculty['department']) : 'Not Provided' ?></div>
                  </div>
                </div>
              </div>

              <!-- Designation -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                  </div>
                  <div>
                    <div class="info-item-label">Designation</div>
                    <div class="info-item-value"><?= !empty($faculty['designation']) ? htmlspecialchars($faculty['designation']) : 'Not Provided' ?></div>
                  </div>
                </div>
              </div>

              <!-- Experience Years -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-briefcase"></i>
                  </div>
                  <div>
                    <div class="info-item-label">Experience</div>
                    <div class="info-item-value"><?= isset($faculty['experience_years']) ? htmlspecialchars($faculty['experience_years']) . ' Years' : 'Not Provided' ?></div>
                  </div>
                </div>
              </div>

              <!-- Joined Date -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                  </div>
                  <div>
                    <div class="info-item-label">Joined Date</div>
                    <div class="info-item-value"><?= !empty($faculty['user_created']) ? date('M d, Y', strtotime($faculty['user_created'])) : 'Not Provided' ?></div>
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
                    <div class="info-item-value text-truncate" title="<?= htmlspecialchars($faculty['email'] ?? '') ?>"><?= !empty($faculty['email']) ? htmlspecialchars($faculty['email']) : 'Not Provided' ?></div>
                  </div>
                </div>
              </div>

              <!-- Mobile Number -->
              <div class="col-md-6 col-12">
                <div class="info-item-card">
                  <div class="info-item-icon">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                  </div>
                  <div>
                    <div class="info-item-label">Mobile Number</div>
                    <div class="info-item-value"><?= !empty($faculty['mobile_number']) ? htmlspecialchars($faculty['mobile_number']) : 'Not Provided' ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- METRICS CARDS GRID -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="saas-card p-3 text-center h-100">
        <div class="fs-3 text-primary mb-1"><i class="fa-solid fa-clipboard-check"></i></div>
        <div class="fw-bold fs-4" style="color: var(--text-heading);"><?= $createdAssessments ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Assessments Created</div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="saas-card p-3 text-center h-100">
        <div class="fs-3 text-warning mb-1"><i class="fa-solid fa-circle-question"></i></div>
        <div class="fw-bold fs-4" style="color: var(--text-heading);"><?= $totalQuestionsAdded ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Question Bank Entries</div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="saas-card p-3 text-center h-100">
        <div class="fs-3 text-success mb-1"><i class="fa-solid fa-users"></i></div>
        <div class="fw-bold fs-4" style="color: var(--text-heading);"><?= $deptStudentsCount ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Department Students</div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="saas-card p-3 text-center h-100">
        <div class="fs-3 text-info mb-1"><i class="fa-solid fa-chart-line"></i></div>
        <div class="fw-bold fs-4" style="color: var(--text-heading);"><?= $deptAttemptsCount ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Student Attempts</div>
      </div>
    </div>
  </div>


</div>

<!-- EDIT FACULTY MODAL -->
<div class="modal fade" id="editFacultyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold" style="color: var(--text-heading);"><i class="fa-solid fa-user-pen text-primary me-2"></i>Edit Faculty Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>faculty/profile.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_profile_action" value="1">
        
        <div class="modal-body pt-3">
          <!-- Section: Profile Picture -->
          <div class="p-3 mb-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
            <div class="small fw-bold text-primary mb-2"><i class="fa-solid fa-image me-1"></i> Profile Picture</div>
            <div class="mb-2">
              <input type="file" name="avatar_file" id="faculty_avatar_file" class="form-control rounded-3" accept="image/jpeg,image/png,image/webp">
              <div id="faculty-avatar-file-name" class="text-secondary small mt-1" style="font-size: 11px;">
                <?php if (!empty($faculty['avatar']) && $faculty['avatar'] !== 'default-avatar.png'): ?>
                  <?= htmlspecialchars($faculty['avatar']) ?>
                <?php else: ?>
                  No file selected
                <?php endif; ?>
              </div>
            </div>
            <?php if (!empty($faculty['avatar']) && $faculty['avatar'] !== 'default-avatar.png'): ?>
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_avatar" id="remove_avatar_faculty" value="1">
                <label class="form-check-label text-danger small fw-semibold" for="remove_avatar_faculty">
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
                <input type="text" name="first_name" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['first_name'] ?? '') ?>" required>
              </div>
              <div class="col-6">
                <label class="form-label small fw-semibold text-muted">LAST NAME <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['last_name'] ?? '') ?>" required>
              </div>
            </div>
          </div>

          <!-- Section: Professional Information -->
          <div class="p-3 mb-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
            <div class="small fw-bold text-primary mb-2"><i class="fa-solid fa-graduation-cap me-1"></i> Professional Information</div>
            <div class="mb-3">
              <label class="form-label small fw-semibold text-muted">DESIGNATION <span class="text-danger">*</span></label>
              <input type="text" name="designation" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['designation'] ?? 'Assistant Professor') ?>" required>
            </div>
            <div class="row g-3">
              <div class="col-6">
                <label class="form-label small fw-semibold text-muted">DEPARTMENT</label>
                <select name="department" class="form-select rounded-3">
                  <option value="Computer Science" <?= ($faculty['department'] ?? '') === 'Computer Science' ? 'selected' : '' ?>>Computer Science</option>
                  <option value="Information Technology" <?= ($faculty['department'] ?? '') === 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
                  <option value="Software Engineering" <?= ($faculty['department'] ?? '') === 'Software Engineering' ? 'selected' : '' ?>>Software Engineering</option>
                </select>
              </div>
              <div class="col-6">
                <label class="form-label small fw-semibold text-muted">EXPERIENCE (YEARS)</label>
                <input type="number" name="experience_years" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['experience_years'] ?? '0') ?>" min="0">
              </div>
            </div>
          </div>

          <!-- Section: Contact Information -->
          <div class="p-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
            <div class="small fw-bold text-primary mb-2"><i class="fa-solid fa-mobile-screen-button me-1"></i> Contact Information</div>
            <div>
              <label class="form-label small fw-semibold text-muted">MOBILE NUMBER</label>
              <input type="text" name="mobile_number" id="faculty_phone" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['mobile_number'] ?? '') ?>" placeholder="e.g. 9876543210" maxlength="10" inputmode="numeric" pattern="[0-9]{10}">
              <div class="text-muted" style="font-size: 11px; margin-top: 2px;">Exactly 10 numeric digits are required.</div>
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

<script>
window.initFacultyProfileEdit = function() {
  const avatarInput = document.getElementById('faculty_avatar_file');
  const avatarFileName = document.getElementById('faculty-avatar-file-name');
  
  if (avatarInput && avatarFileName) {
    avatarInput.addEventListener('change', function() {
      if (this.files && this.files.length > 0) {
        avatarFileName.textContent = this.files[0].name;
      } else {
        avatarFileName.textContent = <?= json_encode(!empty($faculty['avatar']) && $faculty['avatar'] !== 'default-avatar.png' ? $faculty['avatar'] : 'No file selected') ?>;
      }
    });
  }

  const phoneInput = document.getElementById('faculty_phone');
  if (phoneInput) {
    phoneInput.addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
      if (this.value.length > 10) {
        this.value = this.value.slice(0, 10);
      }
      
      if (this.value.length > 0 && this.value.length < 10) {
        this.setCustomValidity('Mobile number must contain exactly 10 digits.');
      } else {
        this.setCustomValidity('');
      }
    });

    const form = phoneInput.closest('form');
    if (form) {
      form.addEventListener('submit', function(e) {
        const val = phoneInput.value.trim();
        if (val.length > 0 && val.length < 10) {
          e.preventDefault();
          phoneInput.setCustomValidity('Mobile number must contain exactly 10 digits.');
          phoneInput.reportValidity();
        }
      });
    }
  }
};
document.addEventListener('DOMContentLoaded', function() {
  if (window.initFacultyProfileEdit) {
    window.initFacultyProfileEdit();
  }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
