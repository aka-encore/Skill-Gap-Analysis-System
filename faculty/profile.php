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

    $facultyRow = $db->fetch("SELECT * FROM faculty WHERE id = ?", [$facultyId]);
    $avatarName = $facultyRow['avatar'] ?? 'default-avatar.png';

    // Handle Avatar Upload
    if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
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
                $avatarName = $newFilename;
                $_SESSION['avatar'] = $newFilename;
            }
        }
    }

    if (!empty($firstName) && !empty($lastName)) {
        $db->update('faculty', [
            'first_name'  => $firstName,
            'last_name'   => $lastName,
            'department'  => $dept,
            'designation' => $designation,
            'avatar'      => $avatarName
        ], 'id = ?', [$facultyId]);

        $_SESSION['user_name'] = 'Prof. ' . $firstName . ' ' . $lastName;
        set_flash_message('success', 'Faculty profile updated successfully.');
    }
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
$totalQuestionsAdded = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_questions aq JOIN assessments a ON aq.assessment_id = a.id WHERE a.created_by_faculty_id = ?", [$facultyId])['cnt'] ?? 0);
$deptStudentsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM students WHERE department = ?", [$faculty['department'] ?? 'Computer Science'])['cnt'] ?? 0);
$deptAttemptsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results ar JOIN students s ON ar.student_id = s.id WHERE s.department = ?", [$faculty['department'] ?? 'Computer Science'])['cnt'] ?? 0);

$pageTitle = "Faculty Profile - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="dash-content">
  <!-- FACULTY HEADER BANNER -->
  <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white position-relative">
    <div class="row align-items-center g-4">
      <div class="col-auto position-relative">
        <?php 
          $avatarPath = BASE_URL . 'assets/images/default-avatar.png';
          if (!empty($faculty['avatar']) && file_exists(AVATAR_UPLOAD_DIR . $faculty['avatar'])) {
              $avatarPath = BASE_URL . 'uploads/avatars/' . htmlspecialchars($faculty['avatar']);
          }
        ?>
        <div class="rounded-circle overflow-hidden shadow-sm border border-3 border-teal" style="width: 110px; height: 110px; background: #021024;">
          <img src="<?= $avatarPath ?>" alt="<?= $facultyName ?>" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <button type="button" class="btn btn-teal text-white rounded-circle position-absolute bottom-0 end-0 p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #14B8A6; border: none;" data-bs-toggle="modal" data-bs-target="#editFacultyModal" title="Upload Photo">
          <i class="fa-solid fa-camera" style="font-size: 0.85rem;"></i>
        </button>
      </div>

      <div class="col">
        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
          <h2 class="fw-bold text-dark mb-0">Prof. <?= $facultyName ?></h2>
          <span class="badge bg-teal-subtle border rounded-pill px-3 py-1 small fw-semibold" style="color: #0D9488;">
            <?= htmlspecialchars($faculty['designation'] ?? 'Assistant Professor') ?>
          </span>
        </div>

        <div class="d-flex flex-wrap gap-3 text-muted small mb-2">
          <div><i class="fa-solid fa-id-badge text-teal me-1" style="color: #14B8A6;"></i> <?= htmlspecialchars($faculty['employee_code'] ?? 'EMP-1001') ?></div>
          <div><i class="fa-solid fa-envelope text-teal me-1" style="color: #14B8A6;"></i> <?= htmlspecialchars($faculty['email'] ?? '') ?></div>
          <div><i class="fa-solid fa-building-columns text-teal me-1" style="color: #14B8A6;"></i> <?= htmlspecialchars($faculty['department'] ?? 'Computer Science') ?></div>
          <div><i class="fa-solid fa-calendar-days text-teal me-1" style="color: #14B8A6;"></i> Joined <?= date('M Y', strtotime($faculty['user_created'] ?? 'now')) ?></div>
        </div>
      </div>

      <div class="col-12 col-md-auto d-flex flex-column gap-2">
        <button type="button" class="btn btn-teal text-white rounded-pill px-4 py-2 small fw-semibold" style="background: #14B8A6; border: none;" data-bs-toggle="modal" data-bs-target="#editFacultyModal">
          <i class="fa-solid fa-user-pen me-1"></i> Edit Faculty Details
        </button>
      </div>
    </div>
  </div>

  <!-- METRICS CARDS GRID -->
  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100">
        <div class="fs-2 text-primary mb-1"><i class="fa-solid fa-clipboard-check"></i></div>
        <div class="fw-bold fs-3 text-dark"><?= $createdAssessments ?></div>
        <div class="text-muted small font-semibold">Assessments Created</div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100">
        <div class="fs-2 text-warning mb-1"><i class="fa-solid fa-question-circle"></i></div>
        <div class="fw-bold fs-3 text-dark"><?= $totalQuestionsAdded ?></div>
        <div class="text-muted small font-semibold">Question Bank Entries</div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100">
        <div class="fs-2 text-success mb-1"><i class="fa-solid fa-users"></i></div>
        <div class="fw-bold fs-3 text-dark"><?= $deptStudentsCount ?></div>
        <div class="text-muted small font-semibold">Department Students</div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100">
        <div class="fs-2 text-info mb-1"><i class="fa-solid fa-chart-line"></i></div>
        <div class="fw-bold fs-3 text-dark"><?= $deptAttemptsCount ?></div>
        <div class="text-muted small font-semibold">Student Attempts</div>
      </div>
    </div>
  </div>
</div>

<!-- EDIT FACULTY MODAL -->
<div class="modal fade" id="editFacultyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-user-pen me-2" style="color: #14B8A6;"></i>Edit Faculty Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>faculty/profile.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_profile_action" value="1">
        
        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">UPLOAD PROFILE PICTURE</label>
            <input type="file" name="avatar_file" class="form-control rounded-3" accept="image/jpeg,image/png,image/webp">
          </div>

          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label small fw-semibold text-muted">FIRST NAME</label>
              <input type="text" name="first_name" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['first_name'] ?? '') ?>" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold text-muted">LAST NAME</label>
              <input type="text" name="last_name" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['last_name'] ?? '') ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">DESIGNATION</label>
            <input type="text" name="designation" class="form-control rounded-3" value="<?= htmlspecialchars($faculty['designation'] ?? 'Assistant Professor') ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">DEPARTMENT</label>
            <select name="department" class="form-select rounded-3">
              <option value="Computer Science" <?= ($faculty['department'] ?? '') === 'Computer Science' ? 'selected' : '' ?>>Computer Science</option>
              <option value="Information Technology" <?= ($faculty['department'] ?? '') === 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
              <option value="Software Engineering" <?= ($faculty['department'] ?? '') === 'Software Engineering' ? 'selected' : '' ?>>Software Engineering</option>
            </select>
          </div>
        </div>

        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-light rounded-pill px-3 small" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-teal text-white rounded-pill px-4 small fw-semibold" style="background: #14B8A6; border: none;">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
