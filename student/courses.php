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
check_suspended_status();

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
                'progress_percentage' => 0,
                'status'              => 'in_progress',
                'completed_lessons'   => json_encode([]),
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
    $lessonId = (int)($_POST['lesson_id'] ?? 0);
    
    if ($courseId > 0 && $lessonId > 0) {
        $existing = $db->fetch("SELECT * FROM student_progress WHERE student_id = ? AND course_id = ?", [$studentId, $courseId]);
        if ($existing) {
            $completedLessons = $existing['completed_lessons'] ? json_decode($existing['completed_lessons'], true) : [];
            if (!in_array($lessonId, $completedLessons)) {
                $completedLessons[] = $lessonId;
            }
            
            // Recalculate progress based on lessons in database
            $totalLessons = (int)$db->fetch("SELECT COUNT(*) as cnt FROM lessons WHERE course_id = ?", [$courseId])['cnt'];
            $newProgress = $totalLessons > 0 ? (int)min(100, round((count($completedLessons) / $totalLessons) * 100)) : 0;
            
            // Check if final quiz exists for the course's skills
            $hasQuiz = $db->fetch(
                "SELECT COUNT(*) as cnt 
                 FROM assessments a 
                 JOIN course_skills cs ON a.skill_id = cs.skill_id 
                 WHERE cs.course_id = ? AND a.status = 'active'",
                [$courseId]
            )['cnt'] > 0;
            
            $quizPassed = false;
            if ($hasQuiz) {
                $passedQuizCount = $db->fetch(
                    "SELECT COUNT(*) as cnt 
                     FROM assessment_results 
                     WHERE student_id = ? AND status = 'pass' AND assessment_id IN (
                         SELECT a.id 
                         FROM assessments a 
                         JOIN course_skills cs ON a.skill_id = cs.skill_id 
                         WHERE cs.course_id = ? AND a.status = 'active'
                     )",
                    [$studentId, $courseId]
                )['cnt'];
                $quizPassed = ($passedQuizCount > 0);
            } else {
                $quizPassed = true;
            }
            
            $status = ($newProgress >= 100 && $quizPassed) ? 'completed' : 'in_progress';
            
            $db->update('student_progress', [
                'progress_percentage' => $newProgress,
                'status'              => $status,
                'completed_lessons'   => json_encode($completedLessons),
                'last_updated'        => date('Y-m-d H:i:s')
            ], 'id = ?', [$existing['id']]);
            
            if ($status === 'completed' && $existing['status'] !== 'completed') {
                log_activity($userId, 'COURSE_COMPLETED', "Completed course ID #{$courseId}");
            }
            
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'progress' => $newProgress, 
                    'status' => $status, 
                    'completed_lessons' => $completedLessons
                ]);
                exit;
            }
            set_flash_message('success', 'Lesson marked completed!');
        } else {
            // Student is not enrolled, automatically enroll them first
            $db->insert('student_progress', [
                'student_id'          => $studentId,
                'course_id'           => $courseId,
                'progress_percentage' => 0,
                'status'              => 'in_progress',
                'completed_lessons'   => json_encode([$lessonId]),
                'last_updated'        => date('Y-m-d H:i:s')
            ]);
            
            // Recalculate
            $totalLessons = (int)$db->fetch("SELECT COUNT(*) as cnt FROM lessons WHERE course_id = ?", [$courseId])['cnt'];
            $newProgress = $totalLessons > 0 ? (int)min(100, round((1 / $totalLessons) * 100)) : 0;
            
            $db->update('student_progress', [
                'progress_percentage' => $newProgress
            ], 'student_id = ? AND course_id = ?', [$studentId, $courseId]);
            
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'progress' => $newProgress, 
                    'status' => 'in_progress', 
                    'completed_lessons' => [$lessonId]
                ]);
                exit;
            }
        }
    }
    redirect(BASE_URL . 'student/courses.php?tab=enrolled');
}

// 5. Fetch Total Database Catalog Count & Courses (100% Dynamic from MySQL)
$totalCatalogCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM courses")['cnt'] ?? 0);

$allCourses = $db->fetchAll(
    "SELECT c.*, 
            COALESCE(sp.progress_percentage, 0) as progress_percentage,
            sp.status as enrollment_status,
            sp.completed_lessons,
            r.id as recommendation_id,
            r.priority_level as rec_priority,
            s.name as recommended_skill,
            (SELECT COUNT(*) FROM assessments a WHERE a.skill_id IN (SELECT skill_id FROM course_skills WHERE course_id = c.id) AND a.status = 'active') > 0 as has_quiz,
            (SELECT COUNT(*) FROM assessment_results ar WHERE ar.student_id = ? AND ar.status = 'pass' AND ar.assessment_id IN (SELECT a.id FROM assessments a WHERE a.skill_id IN (SELECT skill_id FROM course_skills WHERE course_id = c.id) AND a.status = 'active')) > 0 as quiz_passed
     FROM courses c
     LEFT JOIN student_progress sp ON c.id = sp.course_id AND sp.student_id = ?
     LEFT JOIN recommendations r ON c.id = r.course_id AND r.student_id = ? AND r.is_dismissed = 0
     LEFT JOIN skills s ON r.skill_id = s.id
     ORDER BY c.id DESC",
    [$studentId, $studentId, $studentId]
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
    "SELECT c.*, sp.progress_percentage, sp.status as enrollment_status, sp.completed_lessons, sp.last_updated,
            (SELECT COUNT(*) FROM assessments a WHERE a.skill_id IN (SELECT skill_id FROM course_skills WHERE course_id = c.id) AND a.status = 'active') > 0 as has_quiz,
            (SELECT COUNT(*) FROM assessment_results ar WHERE ar.student_id = ? AND ar.status = 'pass' AND ar.assessment_id IN (SELECT a.id FROM assessments a WHERE a.skill_id IN (SELECT skill_id FROM course_skills WHERE course_id = c.id) AND a.status = 'active')) > 0 as quiz_passed
     FROM student_progress sp
     JOIN courses c ON sp.course_id = c.id
     WHERE sp.student_id = ?
     ORDER BY sp.last_updated DESC",
    [$studentId, $studentId]
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
    background: var(--bg-card, #FFFFFF);
    border: 1px solid var(--border, #E2E8F0);
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  }
  /* High contrast text rules for Course Cards (Light Theme / Default) */
  :root:not([data-theme="dark"]) .course-card-premium,
  [data-theme="light"] .course-card-premium {
    background: #FFFFFF;
  }
  :root:not([data-theme="dark"]) .course-card-premium .course-card-description,
  [data-theme="light"] .course-card-premium .course-card-description {
    color: #374151 !important;
    line-height: 1.45;
  }
  :root:not([data-theme="dark"]) .course-card-premium .course-card-instructor,
  :root:not([data-theme="dark"]) .course-card-premium .course-card-instructor span,
  :root:not([data-theme="dark"]) .course-card-premium .course-card-instructor i,
  [data-theme="light"] .course-card-premium .course-card-instructor,
  [data-theme="light"] .course-card-premium .course-card-instructor span,
  [data-theme="light"] .course-card-premium .course-card-instructor i {
    color: #4B5563 !important;
    font-weight: 500;
  }
  :root:not([data-theme="dark"]) .course-card-premium .course-card-duration,
  :root:not([data-theme="dark"]) .course-card-premium .course-card-duration i,
  :root:not([data-theme="dark"]) .course-card-premium .course-card-meta,
  :root:not([data-theme="dark"]) .course-card-premium .course-card-meta *,
  :root:not([data-theme="dark"]) .course-card-premium .badge.bg-light,
  [data-theme="light"] .course-card-premium .course-card-duration,
  [data-theme="light"] .course-card-premium .course-card-duration i,
  [data-theme="light"] .course-card-premium .course-card-meta,
  [data-theme="light"] .course-card-premium .course-card-meta *,
  [data-theme="light"] .course-card-premium .badge.bg-light {
    color: #6B7280 !important;
    font-weight: 500;
  }
  :root:not([data-theme="dark"]) .course-card-premium .text-muted,
  [data-theme="light"] .course-card-premium .text-muted {
    color: #4B5563 !important;
  }
  /* Dark Theme adaptation for Course Cards (Courses Page Only) */
  [data-theme="dark"] .course-card-premium {
    background: var(--bg-card, #23202E) !important;
    border-color: var(--border, #383347) !important;
  }
  [data-theme="dark"] .course-card-premium .text-dark,
  [data-theme="dark"] .course-card-premium h5 {
    color: var(--text-heading, #FFFFFF) !important;
  }
  [data-theme="dark"] .course-card-premium .course-card-description {
    color: var(--text-body, #F5F5F0) !important;
    line-height: 1.45;
  }
  [data-theme="dark"] .course-card-premium .course-card-instructor,
  [data-theme="dark"] .course-card-premium .course-card-instructor span,
  [data-theme="dark"] .course-card-premium .course-card-instructor i {
    color: var(--text-secondary, #E6E4DD) !important;
    font-weight: 500;
  }
  [data-theme="dark"] .course-card-premium .course-card-duration,
  [data-theme="dark"] .course-card-premium .course-card-duration i,
  [data-theme="dark"] .course-card-premium .course-card-meta,
  [data-theme="dark"] .course-card-premium .course-card-meta * {
    color: var(--text-placeholder, #C5C2B8) !important;
    font-weight: 500;
  }
  [data-theme="dark"] .course-card-premium .badge.bg-light {
    background-color: var(--bg-muted, #2D293B) !important;
    color: var(--text-secondary, #E6E4DD) !important;
    border-color: var(--border, #383347) !important;
  }
  [data-theme="dark"] .course-card-premium .text-muted {
    color: var(--text-secondary, #E6E4DD) !important;
  }
  [data-theme="dark"] .course-card-premium .border-top {
    border-color: var(--border, #383347) !important;
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
  /* Filter Toolbar Premium Styles */
  .courses-filter-toolbar {
    background: var(--bg-card, #FFFFFF);
    border: 1px solid var(--border, #E2E8F0);
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 1.5rem;
  }
  [data-theme="dark"] .courses-filter-toolbar {
    background: var(--bg-card, #23202E);
    border-color: var(--border, #383347);
  }
  .filter-toolbar-search {
    position: relative;
    flex: 1 1 220px;
  }
  .filter-toolbar-search .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-placeholder, #94A3B8);
    font-size: 0.82rem;
    pointer-events: none;
  }
  .filter-toolbar-search input {
    padding-left: 36px;
    border-radius: 10px;
    border: 1.5px solid var(--border, #E2E8F0);
    background: var(--bg-input, #F8FAFC);
    color: var(--text-heading, #021024);
    height: 38px;
    font-size: 0.87rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    width: 100%;
  }
  .filter-toolbar-search input:focus {
    outline: none;
    border-color: var(--primary, #26658C);
    box-shadow: 0 0 0 3px rgba(38,101,140,0.12);
    background: var(--bg-card, #FFFFFF);
  }
  .filter-toolbar-search input::placeholder {
    color: var(--text-placeholder, #94A3B8);
  }
  [data-theme="dark"] .filter-toolbar-search input {
    background: var(--bg-muted, #2D293B);
    border-color: var(--border, #383347);
    color: var(--text-heading, #FFFFFF);
  }
  [data-theme="dark"] .filter-toolbar-search input:focus {
    background: var(--bg-card, #23202E);
    border-color: var(--primary, #26658C);
  }
  .filter-select {
    height: 38px;
    border-radius: 10px;
    border: 1.5px solid var(--border, #E2E8F0);
    background: var(--bg-input, #F8FAFC);
    color: var(--text-heading, #021024);
    font-size: 0.87rem;
    font-weight: 500;
    padding: 0 32px 0 12px;
    min-width: 140px;
    cursor: pointer;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%2394A3B8' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
  }
  .filter-select:focus {
    outline: none;
    border-color: var(--primary, #26658C);
    box-shadow: 0 0 0 3px rgba(38,101,140,0.12);
  }
  .filter-select:hover {
    border-color: var(--primary, #26658C);
  }
  [data-theme="dark"] .filter-select {
    background-color: var(--bg-muted, #2D293B);
    border-color: var(--border, #383347);
    color: var(--text-heading, #FFFFFF);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%23E6E4DD' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
  }
  [data-theme="dark"] .filter-select:focus {
    background-color: var(--bg-card, #23202E);
    border-color: var(--primary, #26658C);
  }
  .filter-divider {
    width: 1px;
    height: 28px;
    background: var(--border, #E2E8F0);
    flex-shrink: 0;
  }
  [data-theme="dark"] .filter-divider {
    background: var(--border, #383347);
  }
  .btn-reset-filters {
    height: 38px;
    border-radius: 10px;
    border: 1.5px solid var(--border, #E2E8F0);
    background: transparent;
    color: var(--text-secondary, #6B7280);
    font-size: 0.82rem;
    font-weight: 500;
    padding: 0 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: all 0.2s ease;
  }
  .btn-reset-filters:hover {
    border-color: var(--primary, #26658C);
    color: var(--primary, #26658C);
    background: rgba(38,101,140,0.05);
  }
  [data-theme="dark"] .btn-reset-filters {
    border-color: var(--border, #383347);
    color: var(--text-secondary, #C5C2B8);
  }
  [data-theme="dark"] .btn-reset-filters:hover {
    border-color: var(--primary, #26658C);
    color: var(--primary, #26658C);
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
  /* Premium Lesson Navigator Styles */
  .lesson-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border-radius: 12px;
    background-color: var(--bg-card, #FFFFFF);
    border: 1px solid var(--border, #E2E8F0);
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 0.85rem;
  }
  .lesson-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,0.05));
    border-color: var(--primary, #26658C);
  }
  .lesson-item.active {
    background-color: var(--primary-light, rgba(38, 101, 140, 0.1));
    border: 2px solid var(--primary, #26658C);
    box-shadow: var(--shadow-md, 0 4px 16px rgba(15,23,42,0.05));
  }
  .lesson-item.locked {
    opacity: 0.55;
    cursor: not-allowed;
    background-color: var(--bg-muted, #F1F5F9);
  }
  .lesson-item.locked:hover {
    transform: none;
    box-shadow: none;
    border-color: var(--border, #E2E8F0);
  }
  .lesson-status-icon {
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 50%;
  }
  .lesson-status-icon.completed {
    color: #198754;
    background-color: #d1e7dd;
  }
  .lesson-status-icon.current {
    color: var(--primary, #26658C);
    background-color: var(--primary-light, rgba(38, 101, 140, 0.15));
  }
  .lesson-status-icon.locked {
    color: var(--text-secondary, #6B7280);
    background-color: var(--bg-muted, #F1F5F9);
  }
  .lesson-status-icon.not-started {
    color: var(--primary, #26658C);
    background-color: var(--bg-tag, #F1F5F9);
  }
  .lesson-title-text {
    font-weight: 550;
    color: var(--text-heading, #021024);
  }
  .lesson-item.active .lesson-title-text {
    color: var(--primary, #26658C);
    font-weight: 700;
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

  <!-- Course Tabs -->
  <div class="d-flex gap-2 flex-wrap mb-3" id="courseTabsList">
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

  <!-- Premium SaaS Filter Toolbar -->
  <div class="courses-filter-toolbar">
    <div class="d-flex flex-wrap align-items-center gap-2">

      <!-- Search -->
      <div class="filter-toolbar-search">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="courseSearchInput" placeholder="Search courses or instructors..." oninput="applyCourseFilters()" autocomplete="off" />
      </div>

      <div class="filter-divider d-none d-md-block"></div>

      <!-- Learning Track -->
      <select class="filter-select" id="trackFilterSelect" onchange="applyCourseFilters()">
        <option value="All Tracks">All Tracks</option>
        <?php foreach ($dbTracks as $trk): ?>
          <option value="<?= htmlspecialchars(strtolower($trk)) ?>"><?= htmlspecialchars(ucfirst($trk)) ?></option>
        <?php endforeach; ?>
      </select>

      <!-- Difficulty Level -->
      <select class="filter-select" id="levelFilterSelect" onchange="applyCourseFilters()">
        <option value="All Levels">All Levels</option>
        <?php foreach ($dbLevels as $lvl): ?>
          <option value="<?= htmlspecialchars(strtolower($lvl)) ?>"><?= htmlspecialchars(ucfirst($lvl)) ?></option>
        <?php endforeach; ?>
      </select>

      <div class="filter-divider d-none d-md-block"></div>

      <!-- Sort By -->
      <select class="filter-select" id="sortFilterSelect" onchange="applyCourseFilters()" style="min-width:155px;">
        <option value="recommended">Recommended</option>
        <option value="newest">Newest</option>
        <option value="popular">Most Popular</option>
        <option value="rating">Highest Rated</option>
        <option value="az">A &ndash; Z</option>
        <option value="duration">Duration</option>
      </select>

      <!-- Reset -->
      <button type="button" class="btn-reset-filters ms-auto" onclick="resetAllFilters()" title="Clear all filters">
        <i class="fa-solid fa-rotate-left"></i> Reset
      </button>

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
      <div class="small text-secondary">
        Instructor: <strong id="checkoutInstructor" class="text-dark">Instructor</strong>
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
          <div id="videoContainer" class="ratio ratio-16x9 bg-black rounded-3 overflow-hidden mb-3" style="display: none;">
            <iframe id="lessonVideoIframe" src="" title="Lesson Video" allowfullscreen></iframe>
          </div>
          <div>
            <h6 id="activeLessonTitle" class="fw-bold text-white mb-1">Select a database lesson</h6>
            <div id="activeLessonContent" class="small text-white-50 mb-3" style="font-size: 0.78rem; overflow-y: auto; max-height: 180px;">Lesson details loaded from database.</div>
          </div>
        </div>
        
        <!-- Requirements Summary Widget -->
        <div class="mt-3 p-3 rounded bg-white text-dark shadow-sm border" id="playerProgressSummaryPanel">
            <h6 class="fw-bold mb-2 text-primary d-flex align-items-center gap-1" style="font-size: 0.9rem;">
                <i class="fa-solid fa-graduation-cap"></i> Course Requirements Status
            </h6>
            
            <div class="row g-2">
                <div class="col-6">
                    <div class="small text-muted mb-0.5">Lessons Completed:</div>
                    <strong id="summaryLessonsProgress" class="fs-6 text-dark">0/0</strong>
                </div>
                <div class="col-6">
                    <div class="small text-muted mb-0.5">Lessons Remaining:</div>
                    <strong id="summaryLessonsRemaining" class="fs-6 text-dark">0</strong>
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center small mb-3 border-top pt-2 mt-2">
                <span>Final Skill Quiz:</span>
                <strong id="summaryQuizStatus" class="text-danger">Required</strong>
            </div>
            
            <div class="text-center pt-2 border-top" id="summaryCompleteBadgeContainer">
                <!-- Completed status card injected dynamically -->
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

window.initCourses = function() {
  const urlParams = new URLSearchParams(window.location.search);
  const tabParam = urlParams.get('tab');
  if (tabParam === 'completed') {
    switchCourseTab('completed');
  } else if (tabParam === 'enrolled') {
    switchCourseTab('enrolled');
  } else {
    renderCoursesGrid();
  }

  // Auto-launch course player if course_id parameter is present
  const courseIdParam = urlParams.get('course_id');
  if (courseIdParam) {
    const course = ALL_COURSES.find(c => c.id == courseIdParam) 
                || ENROLLED_COURSES.find(c => c.id == courseIdParam) 
                || COMPLETED_COURSES.find(c => c.id == courseIdParam);
    if (course) {
      const progress = parseInt(course.progress_percentage || 0);
      const instructor = course.instructor || 'Expert Instructor';
      openCoursePlayerModal(course.id, course.title, instructor, progress);
    }
  }
};

if (document.readyState === 'complete' || document.readyState === 'interactive') {
  window.initCourses();
} else {
  document.addEventListener('DOMContentLoaded', window.initCourses);
}

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
      const matchTrack = (c.track_category || '').toLowerCase().includes(search);
      const matchCode  = (c.course_code || '').toLowerCase().includes(search);
      if (!matchTitle && !matchInst && !matchDesc && !matchTrack && !matchCode) return false;
    }

    // Learning Track
    if (track !== 'all tracks' && (c.track_category || '').toLowerCase() !== track) {
      return false;
    }

    // Level
    if (level !== 'all levels' && (c.difficulty_level || '').toLowerCase() !== level) {
      return false;
    }

    return true;
  });

  // Sorting
  filtered.sort((a, b) => {
    if (sort === 'rating')   return parseFloat(b.rating || 0) - parseFloat(a.rating || 0);
    if (sort === 'duration') return parseInt(b.duration_hours || 0) - parseInt(a.duration_hours || 0);
    if (sort === 'az')       return (a.title || '').localeCompare(b.title || '');
    if (sort === 'popular')  return parseInt(b.enrolled_count || b.id || 0) - parseInt(a.enrolled_count || a.id || 0);
    if (sort === 'newest')   return b.id - a.id;
    // recommended: completed tab recent-first, else newest
    if (currentTab === 'completed') {
      const dateA = a.last_updated ? new Date(a.last_updated).getTime() : 0;
      const dateB = b.last_updated ? new Date(b.last_updated).getTime() : 0;
      if (dateA !== dateB) return dateB - dateA;
    }
    return b.id - a.id;
  });

  // Showing indicator
  const showingEl = document.getElementById('filteredShowingText');
  const isFilterActive = search || track !== 'all tracks' || level !== 'all levels';
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
    const rating = parseFloat(c.rating || 4.8).toFixed(1);
    const instructor = c.instructor || 'Expert Instructor';
    const progress = parseInt(c.progress_percentage || 0);
    const isRecommended = Boolean(c.recommendation_id);

    return `
      <div class="col-12 col-md-6 col-lg-4">
        <div class="course-card-premium h-100">
          <div class="course-thumb-header">
            <div class="d-flex justify-content-between align-items-center w-100">
              <span class="badge bg-white-subtle text-white border border-white-subtle rounded-pill small">${escapeHtml(c.course_code || 'CS')}</span>
              ${isRecommended ? `<span class="badge bg-warning text-dark fw-bold rounded-pill small"><i class="fa-solid fa-star me-1"></i>Recommended</span>` : '<span></span>'}
            </div>
            <div></div>
          </div>
          
          <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
            <div>
              <div class="d-flex justify-content-between align-items-center small mb-2">
                <span class="text-warning fw-bold"><i class="fa-solid fa-star me-1"></i>${rating}</span>
                <span class="course-card-duration course-card-meta"><i class="fa-regular fa-clock me-1"></i>${c.duration_hours || 10}h</span>
                <span class="badge bg-light border text-capitalize course-card-meta">${escapeHtml(c.difficulty_level || 'beginner')}</span>
              </div>
              
              <h5 class="fw-bold text-dark fs-6 mb-1 text-truncate" title="${escapeHtml(c.title)}">${escapeHtml(c.title)}</h5>
              <p class="course-card-description mb-2 text-truncate-2" style="font-size:0.8rem; height:38px;">${escapeHtml(c.description || 'No description available.')}</p>

              <div class="d-flex align-items-center course-card-instructor small mb-3">
                <span class="text-truncate"><i class="fa-solid fa-user-tie me-1"></i>${escapeHtml(instructor)}</span>
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
                  <button type="button" class="btn btn-primary btn-sm rounded-pill flex-fill fw-semibold" onclick="openCheckoutModal(${c.id}, '${escapeJs(c.title)}', '${escapeJs(instructor)}')">
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
  document.getElementById('sortFilterSelect').value = 'recommended';
  applyCourseFilters();
}

// 4. Modal Controls
let activeCheckoutCourseId = 0;
function openCheckoutModal(id, title, instructor) {
  activeCheckoutCourseId = id;
  document.getElementById('checkoutCourseIdInput').value = id;
  document.getElementById('checkoutCourseTitle').textContent = title;
  document.getElementById('checkoutInstructor').textContent = instructor;

  document.getElementById('checkoutModal').classList.add('active');
}

function closeCheckoutModal() {
  document.getElementById('checkoutModal').classList.remove('active');
}

function handleCheckoutBackdropClick(e) {
  if (e.target.id === 'checkoutModal') closeCheckoutModal();
}

// 5. Course Player Modal & Automatic Progress System
let activePlayerCourseId = 0;
let activePlayerProgress = 0;
let activePlayerLessonId = 0;
let ytPlayer = null;
let ytInterval = null;
let readingTimer = null;
let secondsRead = 0;
const REQUIRED_READING_SECONDS = 10; // For verification: 10s minimum

// Load YT script if not loaded
if (!window.YT) {
  var tag = document.createElement('script');
  tag.src = "https://www.youtube.com/iframe_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

function showLessonLockedAlert() {
  alert("This lesson is locked. Please complete the preceding lessons first!");
}

function openCoursePlayerModal(id, title, instructor, progress) {
  activePlayerCourseId = id;
  activePlayerProgress = progress;
  activePlayerLessonId = 0;

  // Clear trackers
  if (ytInterval) clearInterval(ytInterval);
  if (readingTimer) clearInterval(readingTimer);

  document.getElementById('playerCourseTitle').textContent = title;
  document.getElementById('playerMetaText').textContent = `Instructor: ${instructor}`;
  document.getElementById('playerProgressPercent').textContent = `${progress}%`;
  document.getElementById('playerProgressBar').style.width = `${progress}%`;

  const course = ALL_COURSES.find(c => c.id == id) || ENROLLED_COURSES.find(c => c.id == id) || COMPLETED_COURSES.find(c => c.id == id);
  const lessons = (course && course.lessons && course.lessons.length > 0) ? course.lessons : [];
  const completedList = course && course.completed_lessons 
    ? (typeof course.completed_lessons === 'string' ? JSON.parse(course.completed_lessons) : course.completed_lessons) 
    : [];

  // Find first uncompleted lesson
  let activeIdx = 0;
  for (let i = 0; i < lessons.length; i++) {
    const isDone = completedList.includes(parseInt(lessons[i].id));
    if (!isDone) {
      activeIdx = i;
      break;
    }
  }

  // Update Syllabus count in requirements panel
  const totalLessons = lessons.length;
  const completedCount = completedList.length;
  const remainingCount = Math.max(0, totalLessons - completedCount);
  
  document.getElementById('summaryLessonsProgress').textContent = `${completedCount}/${totalLessons}`;
  document.getElementById('summaryLessonsRemaining').textContent = `${remainingCount}`;

  // Update Quiz Status
  const quizStatusText = document.getElementById('summaryQuizStatus');
  if (course && course.has_quiz == 1) {
      if (course.quiz_passed == 1) {
          quizStatusText.innerHTML = '<span class="text-success fw-bold"><i class="fa-solid fa-circle-check"></i> Passed</span>';
      } else {
          quizStatusText.innerHTML = `<span class="text-danger fw-bold"><i class="fa-solid fa-circle-xmark"></i> Required <a href="${BASE_URL}student/assessments.php" class="btn btn-sm btn-danger py-0.5 px-2 rounded-pill ms-2 text-white text-decoration-none small" style="font-size:0.75rem;">Take Quiz</a></span>`;
      }
  } else {
      quizStatusText.innerHTML = '<span class="text-success fw-bold"><i class="fa-solid fa-circle-check"></i> Not Required</span>';
  }

  // Update Complete Badge
  const badgeContainer = document.getElementById('summaryCompleteBadgeContainer');
  const isCompleted = (progress >= 100) && (!course || course.has_quiz == 0 || course.quiz_passed == 1);
  if (isCompleted) {
      badgeContainer.innerHTML = `
          <div class="badge bg-success-subtle text-success border border-success-subtle p-2 w-100 rounded-3 text-center">
              <h6 class="fw-bold mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-award"></i> Course Completed!</h6>
              <span class="small" style="font-size:0.7rem;">Dynamic Progress 100% & Quiz Passed</span>
          </div>
      `;
  } else {
      badgeContainer.innerHTML = `
          <div class="badge bg-warning-subtle text-warning border border-warning-subtle p-2 w-100 rounded-3 text-center">
              <span class="small" style="font-size:0.75rem;"><i class="fa-solid fa-spinner fa-spin me-1"></i> Requirements Pending</span>
          </div>
      `;
  }

  const modulesContainer = document.getElementById('playerModulesContainer');
  if (!lessons || lessons.length === 0) {
    modulesContainer.innerHTML = '<div class="small text-muted p-2">No database lessons assigned to this course.</div>';
    document.getElementById('activeLessonTitle').textContent = 'No Lesson Selected';
    document.getElementById('activeLessonContent').textContent = 'No database lessons available.';
    document.getElementById('videoContainer').style.display = 'none';
  } else {
    modulesContainer.innerHTML = lessons.map((l, idx) => {
      const isDone = completedList.includes(parseInt(l.id));
      const isPriorDone = idx === 0 || completedList.includes(parseInt(lessons[idx - 1].id));
      const isUnlocked = isPriorDone;
      const isActive = activePlayerLessonId == l.id || (activePlayerLessonId === 0 && idx === activeIdx);
      
      let statusClass = 'not-started';
      let iconHtml = '<i class="fa-regular fa-circle"></i>';
      let lockClass = '';
      let clickAttr = `onclick="selectDatabaseLesson(${idx}, ${cEscapeJs(l.title)}, ${cEscapeJs(l.description || '')}, ${cEscapeJs(l.video_url || '')}, ${l.id})"`;

      if (!isUnlocked) {
          statusClass = 'locked';
          iconHtml = '<i class="fa-solid fa-lock"></i>';
          lockClass = 'locked';
          clickAttr = `onclick="showLessonLockedAlert()"`;
      } else if (isDone) {
          statusClass = 'completed';
          iconHtml = '<i class="fa-solid fa-circle-check text-success"></i>';
      } else if (isActive) {
          statusClass = 'current';
          iconHtml = '<i class="fa-solid fa-circle-play text-primary"></i>';
      }

      return `
        <div class="lesson-item ${isActive ? 'active' : ''} ${lockClass}" ${clickAttr}>
          <div class="d-flex align-items-center gap-2 text-truncate">
            <span class="lesson-status-icon ${statusClass}">${iconHtml}</span>
            <span class="lesson-title-text text-truncate" style="max-width: 200px;">
              <span class="text-muted me-1">#${idx + 1}</span> ${escapeHtml(l.title)}
            </span>
          </div>
          <span class="badge bg-light text-dark ms-1">${l.duration_minutes || 15}m</span>
        </div>
      `;
    }).join('');

    if (activePlayerLessonId === 0 && lessons[activeIdx]) {
      const defaultLesson = lessons[activeIdx];
      selectDatabaseLesson(activeIdx, defaultLesson.title, defaultLesson.description, defaultLesson.video_url, defaultLesson.id);
    }
  }

  document.getElementById('coursePlayerModal').classList.add('active');
}

function selectDatabaseLesson(idx, title, desc, videoUrl, lessonId) {
  // Clear any existing trackers
  if (ytInterval) clearInterval(ytInterval);
  if (readingTimer) clearInterval(readingTimer);

  activePlayerLessonId = lessonId;

  document.getElementById('activeLessonTitle').textContent = title;
  document.getElementById('activeLessonContent').textContent = desc || 'Database lesson content and learning guide.';
  
  const videoContainer = document.getElementById('videoContainer');
  
  if (videoUrl) {
    // Video Lesson completion tracking
    videoContainer.style.display = 'block';
    
    // Recreate the iframe element to completely purge any old YouTube player event listeners
    videoContainer.innerHTML = `<iframe id="lessonVideoIframe" src="" title="Lesson Video" allowfullscreen></iframe>`;
    
    // Inject video URL with API enabled
    let separator = videoUrl.indexOf('?') !== -1 ? '&' : '?';
    let videoApiUrl = videoUrl + separator + "enablejsapi=1";
    document.getElementById('lessonVideoIframe').src = videoApiUrl;
    
    // Initialize YouTube state listener
    initYTIframePlayer('lessonVideoIframe', lessonId);
  } else {
    // Reading Lesson completion tracking
    videoContainer.style.display = 'none';
    videoContainer.innerHTML = `<iframe id="lessonVideoIframe" src="" title="Lesson Video" allowfullscreen></iframe>`;
    
    startReadingLessonTracker(lessonId);
  }

  const course = ALL_COURSES.find(c => c.id == activePlayerCourseId) || ENROLLED_COURSES.find(c => c.id == activePlayerCourseId) || COMPLETED_COURSES.find(c => c.id == activePlayerCourseId);
  const lessons = (course && course.lessons && course.lessons.length > 0) ? course.lessons : [];
  const completedList = course && course.completed_lessons 
    ? (typeof course.completed_lessons === 'string' ? JSON.parse(course.completed_lessons) : course.completed_lessons) 
    : [];

  document.querySelectorAll('#playerModulesContainer .lesson-item').forEach((item, i) => {
    if (i === idx) {
      item.classList.add('active');
      const iconSpan = item.querySelector('.lesson-status-icon');
      if (iconSpan) {
        iconSpan.className = 'lesson-status-icon current';
        iconSpan.innerHTML = '<i class="fa-solid fa-circle-play text-primary"></i>';
      }
    } else {
      item.classList.remove('active');
      const otherLesson = lessons[i];
      const isDone = completedList.includes(parseInt(otherLesson.id));
      const isUnlocked = i === 0 || completedList.includes(parseInt(lessons[i - 1].id));
      const iconSpan = item.querySelector('.lesson-status-icon');
      if (iconSpan) {
        if (!isUnlocked) {
          iconSpan.className = 'lesson-status-icon locked';
          iconSpan.innerHTML = '<i class="fa-solid fa-lock"></i>';
        } else if (isDone) {
          iconSpan.className = 'lesson-status-icon completed';
          iconSpan.innerHTML = '<i class="fa-solid fa-circle-check text-success"></i>';
        } else {
          iconSpan.className = 'lesson-status-icon not-started';
          iconSpan.innerHTML = '<i class="fa-regular fa-circle"></i>';
        }
      }
    }
  });
}

function initYTIframePlayer(iframeId, lessonId) {
  if (ytInterval) clearInterval(ytInterval);
  if (ytPlayer) {
    try {
      if (typeof ytPlayer.destroy === 'function') {
        ytPlayer.destroy();
      }
    } catch (e) {}
    ytPlayer = null;
  }

  if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
    setTimeout(() => initYTIframePlayer(iframeId, lessonId), 500);
    return;
  }

  ytPlayer = new YT.Player(iframeId, {
    events: {
      'onStateChange': (event) => {
        if (event.data === YT.PlayerState.PLAYING) {
          if (ytInterval) clearInterval(ytInterval);
          ytInterval = setInterval(() => {
            if (ytPlayer && typeof ytPlayer.getCurrentTime === 'function' && typeof ytPlayer.getDuration === 'function') {
              const curr = ytPlayer.getCurrentTime();
              const dur = ytPlayer.getDuration();
              if (dur > 0 && (curr / dur) >= 0.90) {
                clearInterval(ytInterval);
                triggerAutomaticLessonCompletion(lessonId);
              }
            }
          }, 1000);
        } else {
          if (ytInterval) clearInterval(ytInterval);
        }
      }
    }
  });
}

function startReadingLessonTracker(lessonId) {
  secondsRead = 0;
  const contentContainer = document.getElementById('activeLessonContent');
  if (!contentContainer) return;

  let scrolledToBottom = false;

  function checkScroll() {
    const scrollHeight = contentContainer.scrollHeight;
    const clientHeight = contentContainer.clientHeight;
    const scrollTop = contentContainer.scrollTop;
    if (scrollHeight - clientHeight - scrollTop <= 25) {
      scrolledToBottom = true;
    }
  }

  contentContainer.style.maxHeight = '180px';
  contentContainer.style.overflowY = 'auto';
  contentContainer.removeEventListener('scroll', checkScroll);
  contentContainer.addEventListener('scroll', checkScroll);

  readingTimer = setInterval(() => {
    secondsRead++;
    // If text is short and doesn't scroll, scrollHeight <= clientHeight
    if (contentContainer.scrollHeight <= contentContainer.clientHeight) {
      scrolledToBottom = true;
    }

    if (secondsRead >= REQUIRED_READING_SECONDS && scrolledToBottom) {
      clearInterval(readingTimer);
      contentContainer.removeEventListener('scroll', checkScroll);
      triggerAutomaticLessonCompletion(lessonId);
    }
  }, 1000);
}

function triggerAutomaticLessonCompletion(lessonId) {
  const course = ALL_COURSES.find(c => c.id == activePlayerCourseId) 
              || ENROLLED_COURSES.find(c => c.id == activePlayerCourseId)
              || COMPLETED_COURSES.find(c => c.id == activePlayerCourseId);
  if (!course) return;

  let completedList = course.completed_lessons 
    ? (typeof course.completed_lessons === 'string' ? JSON.parse(course.completed_lessons) : course.completed_lessons) 
    : [];

  if (completedList.includes(parseInt(lessonId))) return; // Already completed

  const formData = new FormData();
  formData.append('action', 'update_progress');
  formData.append('course_id', activePlayerCourseId);
  formData.append('lesson_id', lessonId);

  fetch('<?= BASE_URL ?>student/courses.php', {
    method: 'POST',
    body: formData,
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      // Sync local records
      course.progress_percentage = data.progress;
      course.completed_lessons = data.completed_lessons;
      course.enrollment_status = data.status;

      const lessons = course.lessons || [];
      const curIdx = lessons.findIndex(l => l.id == lessonId);

      // Re-trigger player modal view updates
      openCoursePlayerModal(course.id, course.title, course.instructor, data.progress);
      applyCourseFilters();

      if (curIdx !== -1 && curIdx < lessons.length - 1) {
          const nextLesson = lessons[curIdx + 1];
          setTimeout(() => {
              if (confirm(`Lesson completed successfully! Move to next lesson: "${nextLesson.title}"?`)) {
                  selectDatabaseLesson(curIdx + 1, nextLesson.title, nextLesson.description, nextLesson.video_url, nextLesson.id);
              }
          }, 300);
      }
    }
  })
  .catch(err => console.log('Lesson progress sync error:', err));
}

function closeCoursePlayerModal() {
  if (ytInterval) clearInterval(ytInterval);
  if (readingTimer) clearInterval(readingTimer);
  document.getElementById('coursePlayerModal').classList.remove('active');
}

function handlePlayerBackdropClick(e) {
  if (e.target.id === 'coursePlayerModal') closeCoursePlayerModal();
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
