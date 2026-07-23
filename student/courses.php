<?php
/**
 * SkillBridge - Modern SaaS Courses Module
 * Simplified, Clean, & Premium UI/UX preserving 100% Database-Driven Logic
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$userId    = $_SESSION['user_id'];
$db        = Database::getInstance();

// 1. AJAX Endpoint for fetching normalized lessons from MySQL database
if (isset($_GET['action']) && $_GET['action'] === 'get_lessons') {
    $courseId = (int)($_GET['course_id'] ?? 0);
    $lessons = $db->fetchAll("SELECT * FROM lessons WHERE course_id = ? ORDER BY sort_order ASC", [$courseId]);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'lessons' => $lessons]);
    exit;
}

// 2. Dismiss Recommendation Handler
if (isset($_GET['dismiss_id'])) {
    $dismissId = (int)$_GET['dismiss_id'];
    $db->update('recommendations', ['is_dismissed' => 1], 'id = ? AND student_id = ?', [$dismissId, $studentId]);
    set_flash_message('success', 'Recommendation dismissed successfully.');
    redirect(BASE_URL . 'student/courses.php');
}

// 3. Enroll Course Handler
if (isset($_REQUEST['enroll_course_id']) || (isset($_POST['action']) && $_POST['action'] === 'enroll')) {
    $courseId = (int)($_REQUEST['enroll_course_id'] ?? $_POST['course_id'] ?? 0);
    if ($courseId > 0) {
        $existing = $db->fetch("SELECT id FROM student_progress WHERE student_id = ? AND course_id = ?", [$studentId, $courseId]);
        if (!$existing) {
            $db->insert('student_progress', [
                'student_id'          => $studentId,
                'course_id'           => $courseId,
                'progress_percentage' => 10,
                'status'              => 'in_progress',
                'last_updated'        => date('Y-m-d H:i:s')
            ]);
            $courseInfo = $db->fetch("SELECT title FROM courses WHERE id = ?", [$courseId]);
            log_activity($userId, 'ENROLL_COURSE', "Enrolled in course: " . ($courseInfo['title'] ?? "ID #{$courseId}"));
            
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Enrolled successfully!']);
                exit;
            }
            set_flash_message('success', 'Enrolled in course successfully!');
        } else {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Already enrolled in this course.']);
                exit;
            }
            set_flash_message('info', 'You are already enrolled in this course.');
        }
    }
    redirect(BASE_URL . 'student/courses.php?tab=enrolled');
}

// 4. Mark Lesson Complete / Progress Update Handler
if (isset($_POST['action']) && $_POST['action'] === 'update_progress') {
    $courseId = (int)($_POST['course_id'] ?? 0);
    $newProgress = min(100, max(0, (int)($_POST['progress'] ?? 0)));
    if ($courseId > 0) {
        $status = ($newProgress >= 100) ? 'completed' : 'in_progress';
        $existing = $db->fetch("SELECT id FROM student_progress WHERE student_id = ? AND course_id = ?", [$studentId, $courseId]);
        if ($existing) {
            $db->update('student_progress', [
                'progress_percentage' => $newProgress,
                'status'              => $status,
                'last_updated'        => date('Y-m-d H:i:s')
            ], 'id = ?', [$existing['id']]);
        } else {
            $db->insert('student_progress', [
                'student_id'          => $studentId,
                'course_id'           => $courseId,
                'progress_percentage' => $newProgress,
                'status'              => $status,
                'last_updated'        => date('Y-m-d H:i:s')
            ]);
        }
        
        if ($newProgress >= 100) {
            log_activity($userId, 'COURSE_COMPLETED', "Completed course ID #{$courseId}");
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'progress' => $newProgress, 'status' => $status]);
            exit;
        }
        set_flash_message('success', 'Lesson progress saved!');
    }
    redirect(BASE_URL . 'student/courses.php?tab=enrolled');
}

// 5. Fetch Total Database Catalog Count & Courses (100% Dynamic from MySQL)
$totalCatalogCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM courses")['cnt'] ?? 0);

$allCourses = $db->fetchAll(
    "SELECT c.*, 
            COALESCE(sp.progress_percentage, 0) as progress_percentage,
            sp.status as enrollment_status,
            r.id as recommendation_id,
            r.priority_level as rec_priority,
            s.name as recommended_skill
     FROM courses c
     LEFT JOIN student_progress sp ON c.id = sp.course_id AND sp.student_id = ?
     LEFT JOIN recommendations r ON c.id = r.course_id AND r.student_id = ? AND r.is_dismissed = 0
     LEFT JOIN skills s ON r.skill_id = s.id
     ORDER BY c.id DESC",
    [$studentId, $studentId]
);

// 6. Fetch Database Lessons for all courses
$dbLessonsRaw = $db->fetchAll("SELECT * FROM lessons ORDER BY course_id ASC, sort_order ASC");
$lessonsByCourse = [];
foreach ($dbLessonsRaw as $l) {
    $lessonsByCourse[$l['course_id']][] = $l;
}

foreach ($allCourses as &$courseRef) {
    $courseRef['lessons'] = $lessonsByCourse[$courseRef['id']] ?? [];
}
unset($courseRef);

// 7. Fetch Database Enrolled & Completed Courses
$allUserProgress = $db->fetchAll(
    "SELECT c.*, sp.progress_percentage, sp.status as enrollment_status, sp.last_updated
     FROM student_progress sp
     JOIN courses c ON sp.course_id = c.id
     WHERE sp.student_id = ?
     ORDER BY sp.last_updated DESC",
    [$studentId]
);

$enrolledCourses = [];
$completedCourses = [];

foreach ($allUserProgress as &$progressRef) {
    $progressRef['lessons'] = $lessonsByCourse[$progressRef['id']] ?? [];
    $pPct = (int)($progressRef['progress_percentage'] ?? 0);
    $st = $progressRef['enrollment_status'] ?? '';

    if ($pPct >= 100 || $st === 'completed') {
        $completedCourses[] = $progressRef;
    } else {
        $enrolledCourses[] = $progressRef;
    }
}
unset($progressRef);

// 8. DYNAMIC METADATA EXTRACTION FROM MYSQL FOR FILTERS
$dbTracksRaw = $db->fetchAll("SELECT DISTINCT track_category FROM courses WHERE track_category IS NOT NULL AND track_category != ''");
$dbTracks = array_values(array_filter(array_column($dbTracksRaw, 'track_category')));

$dbPlatformsRaw = $db->fetchAll("SELECT DISTINCT platform FROM courses WHERE platform IS NOT NULL AND platform != ''");
$dbPlatforms = array_values(array_filter(array_column($dbPlatformsRaw, 'platform')));

$dbLevelsRaw = $db->fetchAll("SELECT DISTINCT difficulty_level FROM courses WHERE difficulty_level IS NOT NULL");
$dbLevels = array_values(array_filter(array_column($dbLevelsRaw, 'difficulty_level')));

$pageTitle = "Courses - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<style>
  /* Premium SaaS LMS Styling */
  .course-card-premium {
    background: #FFFFFF;
    border: 1px solid var(--border, #E2E8F0);
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .course-card-premium:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.06);
    border-color: #26658C;
  }
  .course-thumb-header {
    height: 100px;
    background: linear-gradient(135deg, #26658C, #021024);
    padding: 14px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .platform-badge {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(4px);
    color: white;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    align-self: flex-start;
  }
  .price-tag-badge {
    background: #10B981;
    color: white;
    font-size: 0.8rem;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 6px;
    align-self: flex-end;
  }
  .price-tag-badge.free {
    background: #3B82F6;
  }
  .modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    z-index: 1050;
    align-items: center;
    justify-content: center;
  }
  .modal-backdrop.active {
    display: flex;
  }
  .modal-container {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 24px;
    position: relative;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    animation: modalSlideUp 0.25s ease-out;
  }
  @keyframes modalSlideUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .modal-close-btn {
    position: absolute;
    top: 16px;
    right: 16px;
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #64748B;
    cursor: pointer;
  }
  .modal-close-btn:hover {
    color: #0F172A;
  }
  .lesson-item {
    padding: 10px 12px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.82rem;
  }
  .lesson-item:hover, .lesson-item.active {
    border-color: #26658C;
    background: #F4F9FF;
  }
</style>

<div class="dash-content pb-5">
  
  <!-- 1. SIMPLIFIED CLEAN HEADER (Requirement 3) -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
      <h1 class="fw-bold fs-3 text-dark mb-0">Courses</h1>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill small fw-semibold">
        <i class="fa-solid fa-graduation-cap me-1"></i> <span id="totalCatalogCountText"><?= $totalCatalogCount ?></span> Courses Available
      </span>
      <span id="filteredShowingText" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill small fw-semibold" style="display:none;"></span>
    </div>
  </div>

  <!-- 2. SIMPLIFIED FILTER BAR & TABS (Requirements 1, 2, 4) -->
  <div class="saas-card p-3 mb-4">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
      
      <!-- Clean Tabs: All Courses, Enrolled Courses, Completed Courses -->
      <div class="d-flex gap-2 flex-wrap" id="courseTabsList">
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold" onclick="switchCourseTab('all')" id="tab-all">
          All Courses
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold" onclick="switchCourseTab('enrolled')" id="tab-enrolled">
          Enrolled Courses <span class="badge bg-secondary rounded-pill ms-1" id="enrolledBadgeCount"><?= count($enrolledCourses) ?></span>
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold" onclick="switchCourseTab('completed')" id="tab-completed">
          Completed Courses <span class="badge bg-secondary rounded-pill ms-1" id="completedBadgeCount"><?= count($completedCourses) ?></span>
        </button>
      </div>

      <!-- Single Horizontal Row Filter Bar (Requirement 4 & 2) -->
      <div class="d-flex flex-wrap gap-2 align-items-center">
        <!-- Search Input -->
        <div class="position-relative" style="min-width: 170px;">
          <i class="fa-solid fa-search position-absolute top-50 start-0 translate-middle-y ms-2.5 text-muted small"></i>
          <input type="text" id="courseSearchInput" class="form-control form-control-sm rounded-pill ps-4" placeholder="Search courses, instructors..." oninput="applyCourseFilters()" />
        </div>

        <!-- Single Learning Track Dropdown (Requirement 2) -->
        <select class="form-select form-select-sm rounded-pill" id="trackFilterSelect" onchange="applyCourseFilters()" style="width: auto;">
          <option value="All Tracks">Learning Track: All</option>
          <?php foreach ($dbTracks as $trk): ?>
            <option value="<?= htmlspecialchars(strtolower($trk)) ?>"><?= htmlspecialchars(ucfirst($trk)) ?></option>
          <?php endforeach; ?>
        </select>

        <!-- Level Filter -->
        <select class="form-select form-select-sm rounded-pill" id="levelFilterSelect" onchange="applyCourseFilters()" style="width: auto;">
          <option value="All Levels">Level: All</option>
          <?php foreach ($dbLevels as $lvl): ?>
            <option value="<?= htmlspecialchars(strtolower($lvl)) ?>"><?= htmlspecialchars(ucfirst($lvl)) ?></option>
          <?php endforeach; ?>
        </select>

        <!-- Platform Filter -->
        <select class="form-select form-select-sm rounded-pill" id="platformFilterSelect" onchange="applyCourseFilters()" style="width: auto;">
          <option value="All Platforms">Platform: All</option>
          <?php foreach ($dbPlatforms as $plat): ?>
            <option value="<?= htmlspecialchars($plat) ?>"><?= htmlspecialchars($plat) ?></option>
          <?php endforeach; ?>
        </select>

        <!-- Price Filter -->
        <select class="form-select form-select-sm rounded-pill" id="priceFilterSelect" onchange="applyCourseFilters()" style="width: auto;">
          <option value="all">Price: All</option>
          <option value="free">Free Only</option>
          <option value="paid">Paid Only</option>
        </select>

        <!-- Sort Filter -->
        <select class="form-select form-select-sm rounded-pill" id="sortFilterSelect" onchange="applyCourseFilters()" style="width: auto;">
          <option value="recommended">Sort: Recommended</option>
          <option value="rating">Sort: Top Rating</option>
          <option value="price_low">Sort: Price (Low to High)</option>
          <option value="price_high">Sort: Price (High to Low)</option>
          <option value="duration">Sort: Duration</option>
        </select>
      </div>

    </div>
  </div>

  <!-- 3. DYNAMIC COURSES GRID CONTAINER -->
  <div id="coursesGridContainer" class="row g-4">
    <!-- Injected dynamically via JS from MySQL database records -->
  </div>

</div>

<!-- ══════════ COURSE ENROLLMENT CONFIRMATION MODAL ══════════ -->
<div id="checkoutModal" class="modal-backdrop" onclick="handleCheckoutBackdropClick(event)">
  <div class="modal-container" style="max-width:460px; width:90%;">
    <button class="modal-close-btn" onclick="closeCheckoutModal()" title="Close (Esc)">
      <i class="fa-solid fa-xmark"></i>
    </button>
    
    <div class="d-flex align-items-center gap-3 mb-3">
      <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width:40px; height:40px; font-size:1.1rem; flex-shrink:0;">
        <i class="fa-solid fa-graduation-cap"></i>
      </div>
      <div>
        <h5 class="fw-bold text-dark mb-0">Course Enrollment</h5>
        <span class="badge bg-success-subtle text-success small fw-semibold">Instant Academic Access</span>
      </div>
    </div>

    <div class="p-3 bg-light rounded-3 border mb-3">
      <div class="text-muted small text-uppercase fw-semibold mb-1">Selected Course</div>
      <div id="checkoutCourseTitle" class="fw-bold text-dark fs-6 mb-1">Course Title</div>
      <div class="d-flex justify-content-between align-items-center small text-secondary">
        <span>Instructor: <strong id="checkoutInstructor" class="text-dark">Instructor</strong></span>
        <span>Platform: <strong id="checkoutPlatform" class="text-dark">Udemy</strong></span>
      </div>
      <div class="border-top mt-2 pt-2 d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark">Access:</span>
        <span id="checkoutPriceTag" class="fs-5 fw-bold text-success">FREE</span>
      </div>
    </div>

    <form id="checkoutForm" action="<?= BASE_URL ?>student/courses.php" method="POST">
      <input type="hidden" name="action" value="enroll" />
      <input type="hidden" name="course_id" id="checkoutCourseIdInput" value="0" />
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary btn-sm flex-fill rounded-pill" onclick="closeCheckoutModal()">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm flex-fill rounded-pill fw-bold" id="confirmPaymentBtn">
          <i class="fa-solid fa-circle-check me-1"></i> Confirm Enrollment
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ══════════ INTERACTIVE COURSE LEARNING PLAYER MODAL ══════════ -->
<div id="coursePlayerModal" class="modal-backdrop" onclick="handlePlayerBackdropClick(event)">
  <div class="modal-container" style="max-width:860px; width:94%;">
    <button class="modal-close-btn" onclick="closeCoursePlayerModal()" title="Close (Esc)">
      <i class="fa-solid fa-xmark"></i>
    </button>
    
    <!-- Player Header -->
    <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom">
      <div>
        <span id="playerTopicBadge" class="badge bg-primary mb-1">Database Lesson</span>
        <h4 id="playerCourseTitle" class="fw-bold text-dark mb-0">Course Title</h4>
        <div class="small text-muted" id="playerMetaText">Instructor · 15.0 Hours</div>
      </div>
      <div class="text-end">
        <div class="small text-muted mb-1">Overall Progress</div>
        <div class="fs-4 fw-bold text-primary" id="playerProgressPercent">0%</div>
      </div>
    </div>

    <!-- Progress Track -->
    <div class="progress mb-3" style="height: 8px;">
      <div id="playerProgressBar" class="progress-bar bg-gradient-primary" role="progressbar" style="width: 0%;"></div>
    </div>

    <!-- Main Player Grid -->
    <div class="row g-3">
      <!-- Video / Content Display Area -->
      <div class="col-md-7">
        <div class="p-3 bg-dark text-white rounded-3 d-flex flex-column justify-content-between" style="min-height: 320px;">
          <div id="videoContainer" class="ratio ratio-16x9 bg-black rounded-3 overflow-hidden mb-3">
            <iframe id="lessonVideoIframe" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Lesson Video" allowfullscreen></iframe>
          </div>
          <div>
            <h6 id="activeLessonTitle" class="fw-bold text-white mb-1">Select a database lesson</h6>
            <p id="activeLessonContent" class="small text-white-50 mb-3" style="font-size: 0.78rem;">Lesson details loaded from database.</p>
            <button type="button" class="btn btn-success btn-sm w-100 fw-bold" id="markLessonCompleteBtn" onclick="markActiveLessonComplete()">
              <i class="fa-solid fa-circle-check me-1"></i> Mark Lesson Completed
            </button>
          </div>
        </div>
      </div>

      <!-- Syllabus Accordion List -->
      <div class="col-md-5">
        <div class="p-3 bg-light rounded-3 border overflow-auto" style="max-height: 360px;">
          <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-list-check text-primary me-1"></i> Course Lessons</h6>
          <div id="playerModulesContainer" class="d-flex flex-column gap-2">
            <!-- Injected dynamically from MySQL database -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- CLIENT-SIDE JS ENGINE FOR FILTERS, ENROLLMENT & PLAYER -->
<script>
const TOTAL_CATALOG_COUNT = <?= (int)$totalCatalogCount ?>;
const ALL_COURSES = <?= json_encode($allCourses) ?>;
const ENROLLED_COURSES = <?= json_encode($enrolledCourses) ?>;
const COMPLETED_COURSES = <?= json_encode($completedCourses) ?>;

let currentTab = 'all';

document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  const tabParam = urlParams.get('tab');
  if (tabParam === 'completed') {
    switchCourseTab('completed');
  } else if (tabParam === 'enrolled') {
    switchCourseTab('enrolled');
  } else {
    renderCoursesGrid();
  }
});

// 1. Tab Switching
function switchCourseTab(tab) {
  currentTab = tab;
  const btnAll = document.getElementById('tab-all');
  const btnEnrolled = document.getElementById('tab-enrolled');
  const btnCompleted = document.getElementById('tab-completed');

  [btnAll, btnEnrolled, btnCompleted].forEach(btn => {
    if (btn) btn.className = 'btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold';
  });

  if (tab === 'enrolled') {
    if (btnEnrolled) btnEnrolled.className = 'btn btn-primary btn-sm rounded-pill px-3 fw-semibold';
  } else if (tab === 'completed') {
    if (btnCompleted) btnCompleted.className = 'btn btn-primary btn-sm rounded-pill px-3 fw-semibold';
  } else {
    if (btnAll) btnAll.className = 'btn btn-primary btn-sm rounded-pill px-3 fw-semibold';
  }
  applyCourseFilters();
}

// 2. Single Filter Function
function applyCourseFilters() {
  const search = document.getElementById('courseSearchInput').value.toLowerCase().trim();
  const track  = document.getElementById('trackFilterSelect').value.toLowerCase();
  const level  = document.getElementById('levelFilterSelect').value.toLowerCase();
  const platform = document.getElementById('platformFilterSelect').value.toLowerCase();
  const price = document.getElementById('priceFilterSelect').value;
  const sort = document.getElementById('sortFilterSelect').value;

  let dataset = ALL_COURSES;
  if (currentTab === 'enrolled') {
    dataset = ENROLLED_COURSES;
  } else if (currentTab === 'completed') {
    dataset = COMPLETED_COURSES;
  }

  let filtered = dataset.filter(c => {
    // Search
    if (search) {
      const matchTitle = (c.title || '').toLowerCase().includes(search);
      const matchInst  = (c.instructor || '').toLowerCase().includes(search);
      const matchDesc  = (c.description || '').toLowerCase().includes(search);
      const matchPlat  = (c.platform || '').toLowerCase().includes(search);
      const matchTrack = (c.track_category || '').toLowerCase().includes(search);
      const matchCode  = (c.course_code || '').toLowerCase().includes(search);
      if (!matchTitle && !matchInst && !matchDesc && !matchPlat && !matchTrack && !matchCode) return false;
    }

    // Learning Track (Requirement 2: Single Track Select)
    if (track !== 'all tracks' && (c.track_category || '').toLowerCase() !== track) {
      return false;
    }

    // Level
    if (level !== 'all levels' && (c.difficulty_level || '').toLowerCase() !== level) {
      return false;
    }

    // Platform
    if (platform !== 'all platforms' && (c.platform || '').toLowerCase() !== platform) {
      return false;
    }

    // Price
    if (price === 'free' && parseFloat(c.price || 0) > 0) return false;
    if (price === 'paid' && parseFloat(c.price || 0) === 0) return false;

    return true;
  });

  // Sorting
  filtered.sort((a, b) => {
    if (sort === 'rating') return parseFloat(b.rating || 0) - parseFloat(a.rating || 0);
    if (sort === 'price_low') return parseFloat(a.price || 0) - parseFloat(b.price || 0);
    if (sort === 'price_high') return parseFloat(b.price || 0) - parseFloat(a.price || 0);
    if (sort === 'duration') return parseInt(b.duration_hours || 0) - parseInt(a.duration_hours || 0);
    return b.id - a.id;
  });

  // Showing indicator
  const showingEl = document.getElementById('filteredShowingText');
  const isFilterActive = search || track !== 'all tracks' || level !== 'all levels' || platform !== 'all platforms' || price !== 'all';
  if (showingEl) {
    if (isFilterActive || currentTab === 'enrolled' || currentTab === 'completed') {
      showingEl.style.display = 'inline-block';
      const labelText = currentTab === 'completed' ? 'Completed' : (currentTab === 'enrolled' ? 'Enrolled' : 'Catalog');
      showingEl.textContent = `Showing ${filtered.length} ${labelText} Courses`;
    } else {
      showingEl.style.display = 'none';
    }
  }

  renderCoursesGrid(filtered);
}

// 3. Simplified Course Cards Rendering (Requirement 5 & 7)
function renderCoursesGrid(courses = ALL_COURSES) {
  const container = document.getElementById('coursesGridContainer');
  if (!courses || courses.length === 0) {
    container.innerHTML = `
      <div class="col-12 text-center py-5">
        <i class="fa-solid fa-folder-open display-4 text-muted mb-3"></i>
        <h5 class="fw-bold text-dark">No courses found</h5>
        <p class="text-muted small">Try adjusting your search terms or filter selections.</p>
        <button class="btn btn-outline-primary btn-sm rounded-pill mt-2" onclick="resetAllFilters()">Reset Filters</button>
      </div>
    `;
    return;
  }

  container.innerHTML = courses.map(c => {
    const isEnrolled = parseInt(c.progress_percentage || 0) > 0 || c.enrollment_status === 'in_progress' || c.enrollment_status === 'completed';
    const isFree = parseFloat(c.price || 0) === 0;
    const priceText = isFree ? 'FREE' : `₹${parseFloat(c.price).toFixed(0)}`;
    const rating = parseFloat(c.rating || 4.8).toFixed(1);
    const platform = c.platform || 'SkillBridge';
    const instructor = c.instructor || 'Expert Instructor';
    const progress = parseInt(c.progress_percentage || 0);
    const isRecommended = Boolean(c.recommendation_id);

    return `
      <div class="col-12 col-md-6 col-lg-4">
        <div class="course-card-premium h-100">
          <div class="course-thumb-header">
            <div class="d-flex justify-content-between align-items-center w-100">
              <span class="platform-badge"><i class="fa-solid fa-graduation-cap me-1"></i>${escapeHtml(platform)}</span>
              <span class="price-tag-badge ${isFree ? 'free' : ''}">${priceText}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center text-white">
              <span class="badge bg-white-subtle text-white border border-white-subtle rounded-pill small">${escapeHtml(c.course_code || 'CS')}</span>
              ${isRecommended ? `<span class="badge bg-warning text-dark fw-bold rounded-pill small"><i class="fa-solid fa-star me-1"></i>Recommended</span>` : ''}
            </div>
          </div>
          
          <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
            <div>
              <div class="d-flex justify-content-between align-items-center small mb-2">
                <span class="text-warning fw-bold"><i class="fa-solid fa-star me-1"></i>${rating}</span>
                <span class="text-muted"><i class="fa-regular fa-clock me-1"></i>${c.duration_hours || 10}h</span>
                <span class="badge bg-light text-dark border text-capitalize">${escapeHtml(c.difficulty_level || 'beginner')}</span>
              </div>
              
              <h5 class="fw-bold text-dark fs-6 mb-1 text-truncate" title="${escapeHtml(c.title)}">${escapeHtml(c.title)}</h5>
              <p class="text-muted small mb-2 text-truncate-2" style="font-size:0.8rem; height:38px;">${escapeHtml(c.description || 'No description available.')}</p>

              <div class="d-flex align-items-center justify-content-between text-muted small mb-3">
                <span class="text-truncate" style="max-width: 170px;"><i class="fa-solid fa-user-tie me-1"></i>${escapeHtml(instructor)}</span>
              </div>
            </div>

            <div class="pt-2 border-top">
              ${isEnrolled ? `
                <div class="mb-2">
                  <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Progress</span>
                    <span class="fw-bold text-primary">${progress}%</span>
                  </div>
                  <div class="progress" style="height:6px;">
                    <div class="progress-bar bg-primary" style="width:${progress}%"></div>
                  </div>
                </div>
                <div class="d-flex gap-2">
                  <button type="button" class="btn btn-success btn-sm rounded-pill flex-fill fw-semibold" onclick="openCoursePlayerModal(${c.id}, '${escapeJs(c.title)}', '${escapeJs(instructor)}', ${progress})">
                    <i class="fa-solid fa-circle-play me-1"></i> Continue Learning
                  </button>
                  <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="openCoursePlayerModal(${c.id}, '${escapeJs(c.title)}', '${escapeJs(instructor)}', ${progress})" title="View Details">
                    <i class="fa-solid fa-info-circle"></i>
                  </button>
                </div>
              ` : `
                <div class="d-flex gap-2">
                  <button type="button" class="btn btn-primary btn-sm rounded-pill flex-fill fw-semibold" onclick="openCheckoutModal(${c.id}, '${escapeJs(c.title)}', '${escapeJs(instructor)}', '${escapeJs(platform)}', '${priceText}')">
                    <i class="fa-solid fa-plus-circle me-1"></i> Enroll
                  </button>
                  <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="openCoursePlayerModal(${c.id}, '${escapeJs(c.title)}', '${escapeJs(instructor)}', ${progress})" title="View Details">
                    Details
                  </button>
                </div>
              `}
            </div>
          </div>
        </div>
      </div>
    `;
  }).join('');
}

function resetAllFilters() {
  document.getElementById('courseSearchInput').value = '';
  document.getElementById('trackFilterSelect').value = 'All Tracks';
  document.getElementById('levelFilterSelect').value = 'All Levels';
  document.getElementById('platformFilterSelect').value = 'All Platforms';
  document.getElementById('priceFilterSelect').value = 'all';
  document.getElementById('sortFilterSelect').value = 'recommended';
  applyCourseFilters();
}

// 4. Modal Controls
let activeCheckoutCourseId = 0;
function openCheckoutModal(id, title, instructor, platform, price) {
  activeCheckoutCourseId = id;
  document.getElementById('checkoutCourseIdInput').value = id;
  document.getElementById('checkoutCourseTitle').textContent = title;
  document.getElementById('checkoutInstructor').textContent = instructor;
  document.getElementById('checkoutPlatform').textContent = platform;
  document.getElementById('checkoutPriceTag').textContent = price;

  document.getElementById('checkoutModal').classList.add('active');
}

function closeCheckoutModal() {
  document.getElementById('checkoutModal').classList.remove('active');
}

function handleCheckoutBackdropClick(e) {
  if (e.target.id === 'checkoutModal') closeCheckoutModal();
}

// 5. Course Player Modal
let activePlayerCourseId = 0;
let activePlayerProgress = 0;

function openCoursePlayerModal(id, title, instructor, progress) {
  activePlayerCourseId = id;
  activePlayerProgress = progress;

  document.getElementById('playerCourseTitle').textContent = title;
  document.getElementById('playerMetaText').textContent = `Instructor: ${instructor}`;
  document.getElementById('playerProgressPercent').textContent = `${progress}%`;
  document.getElementById('playerProgressBar').style.width = `${progress}%`;

  const course = ALL_COURSES.find(c => c.id == id) || ENROLLED_COURSES.find(c => c.id == id);
  const lessons = (course && course.lessons && course.lessons.length > 0) ? course.lessons : [];

  const modulesContainer = document.getElementById('playerModulesContainer');
  if (!lessons || lessons.length === 0) {
    modulesContainer.innerHTML = '<div class="small text-muted p-2">No database lessons assigned to this course.</div>';
    document.getElementById('activeLessonTitle').textContent = 'No Lesson Selected';
    document.getElementById('activeLessonContent').textContent = 'No database lessons available.';
  } else {
    modulesContainer.innerHTML = lessons.map((l, idx) => `
      <div class="lesson-item ${idx === 0 ? 'active' : ''}" onclick="selectDatabaseLesson(${idx}, ${cEscapeJs(l.title)}, ${cEscapeJs(l.description || '')}, ${cEscapeJs(l.video_url || '')})">
        <span class="text-truncate" style="max-width:200px;"><i class="fa-solid fa-circle-play text-primary me-2"></i>${escapeHtml(l.title)}</span>
        <span class="badge bg-light text-dark ms-1">${l.duration_minutes || 15}m</span>
      </div>
    `).join('');

    selectDatabaseLesson(0, lessons[0].title, lessons[0].description, lessons[0].video_url);
  }

  document.getElementById('coursePlayerModal').classList.add('active');
}

function selectDatabaseLesson(idx, title, desc, videoUrl) {
  document.getElementById('activeLessonTitle').textContent = title;
  document.getElementById('activeLessonContent').textContent = desc || 'Database lesson content and learning guide.';
  if (videoUrl) {
    document.getElementById('lessonVideoIframe').src = videoUrl;
  }
  document.querySelectorAll('#playerModulesContainer .lesson-item').forEach((item, i) => {
    if (i === idx) item.classList.add('active');
    else item.classList.remove('active');
  });
}

function closeCoursePlayerModal() {
  document.getElementById('coursePlayerModal').classList.remove('active');
}

function handlePlayerBackdropClick(e) {
  if (e.target.id === 'coursePlayerModal') closeCoursePlayerModal();
}

function markActiveLessonComplete() {
  activePlayerProgress = Math.min(100, activePlayerProgress + 35);
  document.getElementById('playerProgressPercent').textContent = `${activePlayerProgress}%`;
  document.getElementById('playerProgressBar').style.width = `${activePlayerProgress}%`;

  const formData = new FormData();
  formData.append('action', 'update_progress');
  formData.append('course_id', activePlayerCourseId);
  formData.append('progress', activePlayerProgress);

  fetch('<?= BASE_URL ?>student/courses.php', {
    method: 'POST',
    body: formData,
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const course = ALL_COURSES.find(c => c.id == activePlayerCourseId);
      if (course) course.progress_percentage = activePlayerProgress;
      applyCourseFilters();
    }
  })
  .catch(err => console.log('Progress sync error:', err));
}

// Helpers
function escapeHtml(str) {
  return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
function escapeJs(str) {
  return String(str || '').replace(/'/g, "\\'").replace(/"/g, '\\"');
}
function cEscapeJs(str) {
  return JSON.stringify(String(str || ''));
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
