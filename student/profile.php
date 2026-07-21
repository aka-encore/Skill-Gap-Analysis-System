<?php
/**
 * SkillBridge - Student Profile & Learning Analytics Center
 * Fully dynamic PDO database-driven profile management with strict validations.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$userId    = $_SESSION['user_id'];
$db        = Database::getInstance();

// Fetch current authenticated student & user record first
$student = $db->fetch(
    "SELECT s.*, u.username, u.email, u.role, u.created_at as user_created 
     FROM students s 
     JOIN users u ON s.user_id = u.id 
     WHERE s.id = ?",
    [$studentId]
);

// 1. Handle Profile Info & Avatar Upload Submit with strict validations
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['update_profile_action'])) {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $dept      = trim($_POST['department'] ?? '');
    $bio       = trim($_POST['bio'] ?? '');
    $location  = trim($_POST['city_location'] ?? 'Mumbai, India');

    // Name validations
    if (empty($firstName) || empty($lastName)) {
        set_flash_message('danger', 'First name and last name are required.');
        redirect(BASE_URL . 'student/profile.php');
    } elseif (!preg_match("/^[a-zA-Z\s\-]+$/", $firstName)) {
        set_flash_message('danger', 'First name cannot contain numbers.');
        redirect(BASE_URL . 'student/profile.php');
    } elseif (!preg_match("/^[a-zA-Z\s\-]+$/", $lastName)) {
        set_flash_message('danger', 'Last name cannot contain numbers.');
        redirect(BASE_URL . 'student/profile.php');
    }

    // Username validations
    if (empty($username)) {
        set_flash_message('danger', 'Username cannot be empty.');
        redirect(BASE_URL . 'student/profile.php');
    } elseif (!preg_match("/^[a-zA-Z0-9_\.]+$/", $username)) {
        set_flash_message('danger', 'Username can only contain letters, numbers, underscores, and periods.');
        redirect(BASE_URL . 'student/profile.php');
    }

    // Check username uniqueness
    $existingUser = $db->fetch("SELECT id FROM users WHERE username = ? AND id != ?", [$username, $userId]);
    if ($existingUser) {
        set_flash_message('danger', 'This username is already taken.');
        redirect(BASE_URL . 'student/profile.php');
    }

    // Phone validations
    if (!empty($phone) && !preg_match("/^\+?[0-9]{7,15}$/", $phone)) {
        set_flash_message('danger', 'Phone number must contain digits only.');
        redirect(BASE_URL . 'student/profile.php');
    }

    // Handle Avatar File Upload
    $avatarName = $student['avatar'] ?? 'default-avatar.png';
    $avatarUploaded = false;

    if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['avatar_file']['tmp_name'];
        $origName = $_FILES['avatar_file']['name'];
        $size = $_FILES['avatar_file']['size'];
        $mime = mime_content_type($tmp);

        if ($size > MAX_FILE_SIZE) {
            set_flash_message('danger', 'Profile picture size must be less than 5MB.');
            redirect(BASE_URL . 'student/profile.php');
        } elseif (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
            set_flash_message('danger', 'Invalid file format. Please upload JPG, PNG, or WebP image.');
            redirect(BASE_URL . 'student/profile.php');
        } else {
            $ext = pathinfo($origName, PATHINFO_EXTENSION);
            $newFilename = 'avatar_user_' . $userId . '_' . time() . '.' . strtolower($ext);
            $dest = AVATAR_UPLOAD_DIR . $newFilename;

            if (!file_exists(AVATAR_UPLOAD_DIR)) {
                @mkdir(AVATAR_UPLOAD_DIR, 0777, true);
            }

            if (move_uploaded_file($tmp, $dest)) {
                $avatarName = $newFilename;
                $_SESSION['avatar'] = $newFilename;
                $avatarUploaded = true;
            }
        }
    }

    // Compare with current database values to detect real changes
    $currFirst = trim($student['first_name'] ?? '');
    $currLast  = trim($student['last_name'] ?? '');
    $currUser  = trim($student['username'] ?? '');
    $currPhone = trim($student['phone'] ?? '');
    $currDept  = trim($student['department'] ?? '');
    $currBio   = trim($student['bio'] ?? '');
    $currLoc   = trim($student['city_location'] ?? '');

    $hasStudentChanged = ($firstName !== $currFirst || $lastName !== $currLast || $phone !== $currPhone || $dept !== $currDept || $bio !== $currBio || $location !== $currLoc || $avatarUploaded);
    $hasUserChanged = ($username !== $currUser);

    if (!$hasStudentChanged && !$hasUserChanged) {
        set_flash_message('info', 'No changes were made to your profile.');
    } else {
        if ($hasStudentChanged) {
            $db->update('students', [
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'phone'         => $phone,
                'department'    => $dept,
                'bio'           => $bio,
                'city_location' => $location,
                'avatar'        => $avatarName
            ], 'id = ?', [$studentId]);

            $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        }

        if ($hasUserChanged) {
            $db->update('users', ['username' => $username], 'id = ?', [$userId]);
            $_SESSION['username'] = $username;
        }

        set_flash_message('success', 'Profile updated successfully.');
    }

    redirect(BASE_URL . 'student/profile.php');
}

// Re-fetch student record for display rendering
$student = $db->fetch(
    "SELECT s.*, u.username, u.email, u.role, u.created_at as user_created 
     FROM students s 
     JOIN users u ON s.user_id = u.id 
     WHERE s.id = ?",
    [$studentId]
);
$studentName = htmlspecialchars(($student['first_name'] ?? 'Student') . ' ' . ($student['last_name'] ?? ''));

// 2. Dynamic Database Metrics Calculations
$attemptsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE student_id = ?", [$studentId])['cnt'] ?? 0);
$avgScoreRow   = $db->fetch("SELECT ROUND(AVG(score_percentage), 1) as av, ROUND(MAX(score_percentage), 1) as mx FROM assessment_results WHERE student_id = ?", [$studentId]);
$avgScore      = (float)($avgScoreRow['av'] ?? 0.0);
$highestScore  = (float)($avgScoreRow['mx'] ?? 0.0);

// Calculate Overall Skill Score & Skills Mastered across all active skills
$skillsList = $db->fetchAll("SELECT id, name FROM skills ORDER BY name ASC");
$totalSkillsCount = count($skillsList);
$masteredSkillsCount = 0;
$totalWeightedSum = 0;
$topSkillAnalysis = [];

foreach ($skillsList as $sk) {
    $weighted = calculate_weighted_skill_percentage($studentId, (int)$sk['id']);
    $pct = (float)$weighted['overall_percentage'];
    $totalWeightedSum += $pct;

    if ($pct >= 60.0) {
        $masteredSkillsCount++;
    }

    $topSkillAnalysis[] = [
        'name' => $sk['name'],
        'percentage' => round($pct, 1)
    ];
}

usort($topSkillAnalysis, fn($a, $b) => $b['percentage'] <=> $a['percentage']);
$top5Skills = array_slice($topSkillAnalysis, 0, 5);

$overallSkillScore = $totalSkillsCount > 0 ? round($totalWeightedSum / $totalSkillsCount, 1) : 0;

// Dynamic Streak Calculation
$actRows = $db->fetchAll(
    "SELECT DISTINCT DATE(completed_at) as act_date FROM assessment_results WHERE student_id = ? 
     UNION 
     SELECT DISTINCT DATE(last_updated) as act_date FROM student_progress WHERE student_id = ? 
     ORDER BY act_date DESC",
    [$studentId, $studentId]
);

$activeDates = array_column($actRows, 'act_date');
$learningStreak = 0;

if (!empty($activeDates)) {
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));

    if (in_array($today, $activeDates) || in_array($yesterday, $activeDates)) {
        $checkDate = in_array($today, $activeDates) ? $today : $yesterday;
        while (in_array($checkDate, $activeDates)) {
            $learningStreak++;
            $checkDate = date('Y-m-d', strtotime($checkDate . ' -1 day'));
        }
    }
}

// Dynamic Cohort Leaderboard Rank Calculation
$cohortRank = 1;
$rankRows = $db->fetchAll(
    "SELECT student_id, ROUND(AVG(score_percentage), 1) as avg_p 
     FROM assessment_results 
     GROUP BY student_id 
     ORDER BY avg_p DESC"
);
foreach ($rankRows as $rIdx => $rRow) {
    if ((int)$rRow['student_id'] === (int)$studentId) {
        $cohortRank = $rIdx + 1;
        break;
    }
}

// Calculate Profile Completion Percentage
$compScore = 0;
if (!empty($student['first_name'])) $compScore += 15;
if (!empty($student['username'])) $compScore += 15;
if (!empty($student['email'])) $compScore += 15;
if (!empty($student['phone'])) $compScore += 15;
if (!empty($student['department'])) $compScore += 15;
if (!empty($student['bio'])) $compScore += 15;
if (!empty($student['avatar']) && $student['avatar'] !== 'default-avatar.png') $compScore += 10;
$completionPct = min(100, $compScore);

// Fetch Recent Activity Stream
$recentAttempts = $db->fetchAll(
    "SELECT ar.*, a.title as assessment_title 
     FROM assessment_results ar 
     JOIN assessments a ON ar.assessment_id = a.id 
     WHERE ar.student_id = ? 
     ORDER BY ar.completed_at DESC LIMIT 5",
    [$studentId]
);

$recentFeedback = $db->fetchAll(
    "SELECT * FROM feedback WHERE user_id = ? ORDER BY created_at DESC LIMIT 3",
    [$userId]
);

$pageTitle = "My Profile - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="dash-content">
  <!-- PROFILE HEADER BANNER -->
  <div class="saas-card p-4 p-md-5 mb-4 position-relative overflow-hidden">
    <div class="row align-items-start g-4">
      <div class="col-auto position-relative">
        <?php 
          $avatarPath = BASE_URL . 'assets/images/default-avatar.png';
          if (!empty($student['avatar']) && file_exists(AVATAR_UPLOAD_DIR . $student['avatar'])) {
              $avatarPath = BASE_URL . 'uploads/avatars/' . htmlspecialchars($student['avatar']);
          }
        ?>
        <div class="rounded-circle overflow-hidden shadow-sm border border-3 border-primary" style="width: 110px; height: 110px; background: var(--bg-muted);">
          <img src="<?= $avatarPath ?>" alt="<?= $studentName ?>" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <button type="button" class="btn btn-primary rounded-circle position-absolute bottom-0 end-0 p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#editProfileModal" title="Upload Photo">
          <i class="fa-solid fa-camera" style="font-size: 0.85rem;"></i>
        </button>
      </div>

      <div class="col">
        <!-- FIRST ROW: FULL NAME -> BRANCH BADGE -> JOINED DATE (LEFT/CENTER) & ACTION BUTTONS (RIGHT) -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-2">
          <div class="d-flex align-items-center gap-3 flex-wrap">
            <h2 class="fw-bold mb-0" style="color: var(--text-heading);"><?= $studentName ?></h2>
            <span class="badge saas-badge-primary">
              <i class="fa-solid fa-user-graduate me-1"></i> <?= !empty($student['department']) ? htmlspecialchars($student['department']) : '<span style="color: var(--text-muted);">Not Provided</span>' ?>
            </span>
            <div class="small" style="color: var(--text-muted);">
              <i class="fa-solid fa-calendar-days text-primary me-1"></i> Joined <?= date('M Y', strtotime($student['user_created'] ?? 'now')) ?>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-primary rounded-pill px-3 py-1.5 small fw-semibold" data-bs-toggle="modal" data-bs-target="#editProfileModal">
              <i class="fa-solid fa-user-pen me-1"></i> Edit Profile
            </button>
            <a href="<?= BASE_URL ?>student/settings.php" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 small fw-semibold">
              <i class="fa-solid fa-gear me-1"></i> Settings
            </a>
          </div>
        </div>

        <!-- SECOND ROW: USERNAME -> STUDENT ID -> EMAIL -> PHONE NUMBER -> LOCATION -->
        <div class="d-flex flex-wrap gap-3 small mb-3" style="color: var(--text-secondary);">
          <div><i class="fa-solid fa-user text-primary me-1"></i> @<?= !empty($student['username']) ? htmlspecialchars($student['username']) : 'Not Provided' ?></div>
          <div><i class="fa-solid fa-id-card text-primary me-1"></i> <?= !empty($student['student_code']) ? htmlspecialchars($student['student_code']) : 'Not Provided' ?></div>
          <div><i class="fa-solid fa-envelope text-primary me-1"></i> <?= !empty($student['email']) ? htmlspecialchars($student['email']) : 'Not Provided' ?></div>
          <div><i class="fa-solid fa-phone text-primary me-1"></i> <?= !empty($student['phone']) ? htmlspecialchars($student['phone']) : '<span style="color: var(--text-muted);">Not Provided</span>' ?></div>
          <div><i class="fa-solid fa-location-dot text-primary me-1"></i> <?= !empty($student['city_location']) ? htmlspecialchars($student['city_location']) : '<span style="color: var(--text-muted);">Not Provided</span>' ?></div>
        </div>

        <!-- THIRD ROW: BIO PARAGRAPH -->
        <p class="small mb-3 leading-relaxed max-w-700" style="color: var(--text-secondary);">
          <?= !empty($student['bio']) ? htmlspecialchars($student['bio']) : '<span class="fst-italic opacity-75">No bio provided yet. Click "Edit Profile" to add a bio.</span>' ?>
        </p>

        <!-- FOURTH ROW: PROFILE COMPLETION STATUS BAR -->
        <div class="max-w-500">
          <div class="d-flex justify-content-between small fw-semibold mb-1" style="color: var(--text-secondary);">
            <span>Profile Completion Status</span>
            <span class="text-primary fw-bold"><?= $completionPct ?>%</span>
          </div>
          <div class="progress rounded-pill" style="height: 6px; background: var(--bg-muted);">
            <div class="progress-bar rounded-pill bg-primary" style="width: <?= $completionPct ?>%;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 6 STATS CARDS GRID -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
      <div class="saas-card p-3 text-center h-100">
        <div class="fs-3 text-primary mb-1"><i class="fa-solid fa-star"></i></div>
        <div class="fw-bold fs-4" style="color: var(--text-heading);"><?= $overallSkillScore ?>%</div>
        <div class="small font-semibold" style="color: var(--text-muted);">Overall Skill</div>
      </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
      <div class="saas-card p-3 text-center h-100">
        <div class="fs-3 text-warning mb-1"><i class="fa-solid fa-trophy"></i></div>
        <div class="fw-bold fs-4" style="color: var(--text-heading);">#<?= $cohortRank ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Cohort Rank</div>
      </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
      <div class="saas-card p-3 text-center h-100">
        <div class="fs-3 text-success mb-1"><i class="fa-solid fa-award"></i></div>
        <div class="fw-bold fs-4" style="color: var(--text-heading);"><?= $masteredSkillsCount ?> / <?= $totalSkillsCount ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Skills Mastered</div>
      </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
      <div class="saas-card p-3 text-center h-100">
        <div class="fs-3 text-info mb-1"><i class="fa-solid fa-clipboard-check"></i></div>
        <div class="fw-bold fs-4" style="color: var(--text-heading);"><?= $attemptsCount ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Attempts Logged</div>
      </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
      <div class="saas-card p-3 text-center h-100">
        <div class="fs-3 text-primary mb-1"><i class="fa-solid fa-chart-line"></i></div>
        <div class="fw-bold fs-4" style="color: var(--text-heading);"><?= $avgScore ?>%</div>
        <div class="small font-semibold" style="color: var(--text-muted);">Average Score</div>
      </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
      <div class="saas-card p-3 text-center h-100">
        <div class="fs-3 text-danger mb-1"><i class="fa-solid fa-fire"></i></div>
        <div class="fw-bold fs-4" style="color: var(--text-heading);"><?= $learningStreak ?> Days</div>
        <div class="small font-semibold" style="color: var(--text-muted);">Learning Streak</div>
      </div>
    </div>
  </div>

  <!-- 2-COLUMN MAIN GRID -->
  <div class="row g-4 mb-4">
    <!-- LEFT COLUMN: SKILL ANALYSIS & COMPANY ELIGIBILITY -->
    <div class="col-lg-6">
      <!-- SKILL ANALYSIS BARS -->
      <div class="saas-card p-4 mb-4">
        <h5 class="fw-bold mb-3" style="color: var(--text-heading);"><i class="fa-solid fa-chart-pie text-primary me-2"></i>Top Technical Skill Analysis</h5>
        <?php if (empty($top5Skills)): ?>
          <p class="small mb-0" style="color: var(--text-muted);">No skill assessments completed yet.</p>
        <?php else: ?>
          <div class="d-flex flex-column gap-3">
            <?php foreach ($top5Skills as $sk): ?>
              <div>
                <div class="d-flex justify-content-between small fw-semibold mb-1">
                  <span style="color: var(--text-heading);"><?= htmlspecialchars($sk['name']) ?></span>
                  <span class="text-primary fw-bold"><?= $sk['percentage'] ?>%</span>
                </div>
                <div class="progress rounded-pill" style="height: 8px; background: var(--bg-muted);">
                  <div class="progress-bar rounded-pill bg-primary" style="width: <?= $sk['percentage'] ?>%;"></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- COMPANY ELIGIBILITY READY CARDS -->
      <div class="saas-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0" style="color: var(--text-heading);"><i class="fa-solid fa-building text-success me-2"></i>Career & Placement Eligibility</h5>
          <span class="badge saas-badge-success">
            Based on <?= $overallSkillScore ?>% Overall Score
          </span>
        </div>

        <div class="d-flex flex-column gap-2 mb-3">
          <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" style="background: var(--bg-alt); border: 1px solid var(--border);">
            <div>
              <div class="fw-bold small" style="color: var(--text-heading);">TCS Ninja / Digital (Min. 60%)</div>
              <div style="font-size: 11px; color: var(--text-muted);">Core programming & SQL query proficiency</div>
            </div>
            <?= $overallSkillScore >= 60 ? '<span class="badge saas-badge-success"><i class="fa-solid fa-check"></i> Eligible</span>' : '<span class="badge saas-badge-info">Need Improvement</span>' ?>
          </div>

          <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" style="background: var(--bg-alt); border: 1px solid var(--border);">
            <div>
              <div class="fw-bold small" style="color: var(--text-heading);">Infosys Specialist Programmer (Min. 65%)</div>
              <div style="font-size: 11px; color: var(--text-muted);">Web architecture & algorithmic design</div>
            </div>
            <?= $overallSkillScore >= 65 ? '<span class="badge saas-badge-success"><i class="fa-solid fa-check"></i> Eligible</span>' : '<span class="badge saas-badge-info">Need Improvement</span>' ?>
          </div>

          <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" style="background: var(--bg-alt); border: 1px solid var(--border);">
            <div>
              <div class="fw-bold small" style="color: var(--text-heading);">Accenture Advanced Software Analyst (Min. 70%)</div>
              <div style="font-size: 11px; color: var(--text-muted);">Full stack development & cloud concepts</div>
            </div>
            <?= $overallSkillScore >= 70 ? '<span class="badge saas-badge-success"><i class="fa-solid fa-check"></i> Eligible</span>' : '<span class="badge saas-badge-info">Need Improvement</span>' ?>
          </div>

          <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" style="background: var(--bg-alt); border: 1px solid var(--border);">
            <div>
              <div class="fw-bold small" style="color: var(--text-heading);">Amazon SDE I (Min. 80%)</div>
              <div style="font-size: 11px; color: var(--text-muted);">Advanced data structures & system design</div>
            </div>
            <?= $overallSkillScore >= 80 ? '<span class="badge saas-badge-success"><i class="fa-solid fa-check"></i> Eligible</span>' : '<span class="badge saas-badge-info">Need Improvement</span>' ?>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN: RECENT ACTIVITY TIMELINE -->
    <div class="col-lg-6">
      <div class="saas-card p-4 h-100">
        <h5 class="fw-bold mb-3" style="color: var(--text-heading);"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Recent Activity Stream</h5>

        <?php if (empty($recentAttempts) && empty($recentFeedback)): ?>
          <div class="text-center py-5 text-muted">
            <div class="fs-1 opacity-25 mb-2"><i class="fa-solid fa-calendar-xmark"></i></div>
            <p class="small mb-0" style="color: var(--text-muted);">No recent activity recorded yet.</p>
          </div>
        <?php else: ?>
          <div class="d-flex flex-column gap-3">
            <?php foreach ($recentAttempts as $att): ?>
              <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" style="background: var(--bg-alt); border: 1px solid var(--border);">
                <div>
                  <div class="fw-bold small" style="color: var(--text-heading);">
                    Completed Assessment: <?= htmlspecialchars($att['assessment_title']) ?>
                  </div>
                  <div style="font-size: 11px; color: var(--text-muted);">
                    Score: <?= (float)$att['score_percentage'] ?>% · <?= date('M d, Y · h:i A', strtotime($att['completed_at'])) ?>
                  </div>
                </div>
                <span class="badge <?= (float)$att['score_percentage'] >= 60 ? 'saas-badge-success' : 'saas-badge-danger' ?>">
                  <?= (float)$att['score_percentage'] >= 60 ? 'Passed' : 'Failed' ?>
                </span>
              </div>
            <?php endforeach; ?>

            <?php foreach ($recentFeedback as $fb): ?>
              <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" style="background: var(--bg-alt); border: 1px solid var(--border);">
                <div>
                  <div class="fw-bold small" style="color: var(--text-heading);">
                    Submitted Feedback: <?= htmlspecialchars($fb['category']) ?>
                  </div>
                  <div style="font-size: 11px; color: var(--text-muted);">
                    Rating: <?= (int)$fb['rating'] ?>/5 · <?= date('M d, Y', strtotime($fb['created_at'])) ?>
                  </div>
                </div>
                <span class="badge saas-badge-info">Feedback</span>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- EDIT PROFILE & UPLOAD AVATAR MODAL -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold" style="color: var(--text-heading);"><i class="fa-solid fa-user-pen text-primary me-2"></i>Edit Student Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>student/profile.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_profile_action" value="1">
        
        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">UPLOAD PROFILE PICTURE</label>
            <input type="file" name="avatar_file" class="form-control rounded-3" accept="image/jpeg,image/png,image/webp">
            <div class="text-muted" style="font-size: 11px; margin-top: 4px;">Accepted formats: JPG, PNG, WebP (Max size: 5MB)</div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">USERNAME <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control rounded-3" value="<?= htmlspecialchars($student['username'] ?? '') ?>" required>
            <div class="text-muted" style="font-size: 11px; margin-top: 2px;">Allowed: letters, numbers, underscores, and periods.</div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label small fw-semibold text-muted">FIRST NAME <span class="text-danger">*</span></label>
              <input type="text" name="first_name" class="form-control rounded-3" value="<?= htmlspecialchars($student['first_name'] ?? '') ?>" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold text-muted">LAST NAME <span class="text-danger">*</span></label>
              <input type="text" name="last_name" class="form-control rounded-3" value="<?= htmlspecialchars($student['last_name'] ?? '') ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">DEPARTMENT</label>
            <select name="department" class="form-select rounded-3">
              <option value="Computer Science" <?= ($student['department'] ?? '') === 'Computer Science' ? 'selected' : '' ?>>Computer Science</option>
              <option value="Information Technology" <?= ($student['department'] ?? '') === 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
              <option value="Software Engineering" <?= ($student['department'] ?? '') === 'Software Engineering' ? 'selected' : '' ?>>Software Engineering</option>
              <option value="Data Science" <?= ($student['department'] ?? '') === 'Data Science' ? 'selected' : '' ?>>Data Science</option>
            </select>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label small fw-semibold text-muted">PHONE NUMBER</label>
              <input type="text" name="phone" class="form-control rounded-3" value="<?= htmlspecialchars($student['phone'] ?? '') ?>" placeholder="e.g. 9876543210">
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold text-muted">LOCATION</label>
              <input type="text" name="city_location" class="form-control rounded-3" value="<?= htmlspecialchars($student['city_location'] ?? 'Mumbai, India') ?>">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">BIO / SUMMARY</label>
            <textarea name="bio" rows="3" class="form-control rounded-3" placeholder="Tell us about your learning goals..."><?= htmlspecialchars($student['bio'] ?? '') ?></textarea>
          </div>
        </div>

        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-light rounded-pill px-3 small" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary rounded-pill px-4 small fw-semibold">Save Profile Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
