<?php
/**
 * SkillBridge - Student Dashboard
 * Fully Dynamic - Connected 100% to MySQL Database (Zero Hardcoded Demo Values)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = (int)$_SESSION['profile_id'];
$db = Database::getInstance();

// 1. Fetch Logged-in Student Information
$student = $db->fetch(
    "SELECT s.*, u.email 
     FROM students s 
     JOIN users u ON s.user_id = u.id 
     WHERE s.id = ?", 
    [$studentId]
);

if (!$student) {
    die("Student record not found.");
}

// Time-aware greeting
$hour = (int)date('H');
if ($hour < 12) {
    $greetingTitle = "Good Morning, " . htmlspecialchars($student['first_name']) . "! ☀️";
} elseif ($hour < 17) {
    $greetingTitle = "Good Afternoon, " . htmlspecialchars($student['first_name']) . "! ☀️";
} else {
    $greetingTitle = "Good Evening, " . htmlspecialchars($student['first_name']) . "! 🌙";
}

// 2. Fetch Overall Skill Score & Weekly Change
$avgScore = calculate_overall_student_skill_percentage($studentId);

// Cohort Rank calculation
$totalCohortStudents = (int)($db->fetch("SELECT COUNT(DISTINCT student_id) as cnt FROM assessment_results")['cnt'] ?? 1);
$lowerRankCount = (int)($db->fetch(
    "SELECT COUNT(*) as cnt FROM (
        SELECT student_id, AVG(score_percentage) as avg_s 
        FROM assessment_results 
        GROUP BY student_id
    ) t WHERE t.avg_s < ?", 
    [$avgScore]
)['cnt'] ?? 0);
$percentileRank = max(5, min(99, round((($totalCohortStudents - $lowerRankCount) / max(1, $totalCohortStudents)) * 100)));

// 3. Completed Assessments & Monthly Increase
$totalAssessments = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessments WHERE status = 'active'")['cnt'] ?? 0);
$completedAssessments = (int)($db->fetch("SELECT COUNT(DISTINCT assessment_id) as cnt FROM assessment_results WHERE student_id = ?", [$studentId])['cnt'] ?? 0);
$completedThisMonth = (int)($db->fetch(
    "SELECT COUNT(DISTINCT assessment_id) as cnt FROM assessment_results WHERE student_id = ? AND completed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)", 
    [$studentId]
)['cnt'] ?? 0);

// 4. Courses Completed & Weekly Progress
$coursesCompleted = (int)($db->fetch(
    "SELECT COUNT(*) as cnt FROM student_progress WHERE student_id = ? AND status = 'completed'", 
    [$studentId]
)['cnt'] ?? 0);
$coursesCompletedThisWeek = (int)($db->fetch(
    "SELECT COUNT(*) as cnt FROM student_progress WHERE student_id = ? AND status = 'completed' AND last_updated >= DATE_SUB(NOW(), INTERVAL 7 DAY)", 
    [$studentId]
)['cnt'] ?? 0);

// Overall Learning Progress Across Enrolled Courses
$progressRow = $db->fetch("SELECT AVG(progress_percentage) as avg_prog FROM student_progress WHERE student_id = ?", [$studentId]);
$overallProgress = round((float)($progressRow['avg_prog'] ?? 0), 1);

// 5. Current Level Determination
$currentLevel = match (true) {
    $avgScore >= 85 => 'Level 5 (Expert)',
    $avgScore >= 70 => 'Level 4 (Advanced)',
    $avgScore >= 50 => 'Level 3 (Intermediate)',
    $avgScore >= 30 => 'Level 2 (Basic)',
    default => 'Level 1 (Beginner)',
};
$userLevel = $currentLevel;

// 6. Dynamic Learning Streak Calculation
$activityDates = $db->fetchAll(
    "SELECT DISTINCT DATE(completed_at) as act_date FROM assessment_results WHERE student_id = ?
     UNION
     SELECT DISTINCT DATE(last_updated) as act_date FROM student_progress WHERE student_id = ?
     ORDER BY act_date DESC",
    [$studentId, $studentId]
);

$activeDayStrings = array_column($activityDates, 'act_date');
$todayStr = date('Y-m-d');
$yesterdayStr = date('Y-m-d', strtotime('-1 day'));

$currentStreak = 0;
$startCheck = in_array($todayStr, $activeDayStrings) ? $todayStr : (in_array($yesterdayStr, $activeDayStrings) ? $yesterdayStr : null);

if ($startCheck) {
    $curTs = strtotime($startCheck);
    while (in_array(date('Y-m-d', $curTs), $activeDayStrings)) {
        $currentStreak++;
        $curTs = strtotime('-1 day', $curTs);
    }
}

// If zero activity recorded yet, default to 1 active day upon profile creation
$currentStreak = max(1, $currentStreak);

$longestStreak = $currentStreak;
$tempStreak = 0;
$prevTs = null;
foreach ($activeDayStrings as $dStr) {
    $ts = strtotime($dStr);
    if ($prevTs === null) {
        $tempStreak = 1;
    } else {
        if (($prevTs - $ts) == 86400) {
            $tempStreak++;
        } else {
            $tempStreak = 1;
        }
    }
    if ($tempStreak > $longestStreak) {
        $longestStreak = $tempStreak;
    }
    $prevTs = $ts;
}

// 7. Fetch Dynamic Skills with Real Database Scores
$skillResults = $db->fetchAll(
    "SELECT s.id, s.name as skill_name, COALESCE(MAX(ar.score_percentage), 0) as score
     FROM skills s
     LEFT JOIN assessments a ON s.id = a.skill_id
     LEFT JOIN assessment_results ar ON a.id = ar.assessment_id AND ar.student_id = ?
     GROUP BY s.id, s.name
     ORDER BY score DESC, s.name ASC
     LIMIT 6",
    [$studentId]
);

// 8. Fetch Recommended Courses Linked to DB Progress
$recommendations = $db->fetchAll(
    "SELECT r.*, c.title as course_title, c.course_code, c.duration_hours, c.difficulty_level, s.name as skill_name,
            COALESCE(sp.progress_percentage, 0) as progress_percentage,
            COALESCE(sp.status, 'not_started') as progress_status
     FROM recommendations r
     JOIN courses c ON r.course_id = c.id
     JOIN skills s ON r.skill_id = s.id
     LEFT JOIN student_progress sp ON sp.course_id = c.id AND sp.student_id = r.student_id
     WHERE r.student_id = ? AND r.is_dismissed = 0
     ORDER BY r.priority_level DESC, r.created_at DESC LIMIT 3",
    [$studentId]
);

// Fallback: If no recommendations generated yet, fetch general courses
if (empty($recommendations)) {
    $recommendations = $db->fetchAll(
        "SELECT c.id as course_id, c.title as course_title, c.course_code, c.duration_hours, c.difficulty_level, 
                'General Recommendation' as reason, 'medium' as priority_level, 'Core Competency' as skill_name,
                COALESCE(sp.progress_percentage, 0) as progress_percentage,
                COALESCE(sp.status, 'not_started') as progress_status
         FROM courses c
         LEFT JOIN student_progress sp ON sp.course_id = c.id AND sp.student_id = ?
         ORDER BY c.id ASC LIMIT 3",
        [$studentId]
    );
}

// 9. Fetch Real Recent Activity Feed
$recentActivity = $db->fetchAll(
    "SELECT ar.completed_at as activity_time, 
            'assessment' as activity_type, 
            a.title as item_title, 
            s.name as skill_name,
            ar.score_percentage, 
            ar.status
     FROM assessment_results ar
     JOIN assessments a ON ar.assessment_id = a.id
     JOIN skills s ON a.skill_id = s.id
     WHERE ar.student_id = ?
     ORDER BY ar.completed_at DESC LIMIT 4",
    [$studentId]
);

// 10. Achievements Dynamic Generation
$achievements = [];
if ($completedAssessments > 0) {
    $achievements[] = [
        'icon'  => '🏆',
        'bg'    => 'linear-gradient(135deg,#F59E0B,#EF4444)',
        'title' => 'First Assessment',
        'desc'  => 'Completed ' . $completedAssessments . ' skill test' . ($completedAssessments > 1 ? 's' : '')
    ];
}
if ($currentStreak >= 1) {
    $achievements[] = [
        'icon'  => '🔥',
        'bg'    => 'linear-gradient(135deg,#10B981,#3B82F6)',
        'title' => $currentStreak . '-Day Streak',
        'desc'  => 'Learning consistency champion'
    ];
}
if ($avgScore >= 70) {
    $achievements[] = [
        'icon'  => '⭐',
        'bg'    => 'linear-gradient(135deg,#6366F1,#8B5CF6)',
        'title' => 'Top ' . $percentileRank . '% Cohort Rank',
        'desc'  => 'Maintained ' . $avgScore . '% overall score average'
    ];
}
if (empty($achievements)) {
    $achievements[] = [
        'icon'  => '🚀',
        'bg'    => 'linear-gradient(135deg,#26658C,#14B8A6)',
        'title' => 'Skill Journey Ready',
        'desc'  => 'Enrolled in ' . htmlspecialchars($student['department']) . ' department'
    ];
}

$pageTitle = "Student Dashboard – SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<style>
  /* ── Student Dashboard UI Styles ── */
  .dash-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }
  .dash-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-heading);
    margin-bottom: 0.25rem;
  }
  .dash-header p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0;
  }

  .streak-badge {
    background: var(--warning-light);
    border: 1px solid var(--warning);
    color: var(--warning-text);
    padding: 0.45rem 1.15rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .streak-badge:hover {
    background: var(--warning);
    color: #FFFFFF;
    transform: translateY(-1px);
  }

  /* Stats Cards Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.75rem;
  }
  .card-stat {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 1.25rem;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: var(--shadow-card);
    transition: all 0.25s ease;
  }
  .card-stat:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-card-hover);
  }
  .stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
  }
  .stat-icon.primary { background: var(--primary-light); color: var(--primary); }
  .stat-icon.success { background: var(--success-light); color: var(--success-text); }
  .stat-icon.warning { background: var(--warning-light); color: var(--warning-text); }
  .stat-icon.accent  { background: var(--accent-light); color: var(--accent); }

  .stat-value {
    font-size: 1.75rem;
    font-weight: 800;
    line-height: 1.2;
    color: var(--text-heading);
  }
  .stat-value.gradient-text {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .stat-label {
    font-size: 0.825rem;
    color: var(--text-secondary);
    font-weight: 500;
    margin-top: 2px;
  }
  .stat-change {
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 3px;
  }
  .stat-change.up { color: var(--success-text); }

  /* Dashboard Grid Layouts */
  .dashboard-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
  }

  .dashboard-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
  }
  @media (max-width: 768px) {
    .dashboard-grid-3 { grid-template-columns: 1fr; }
  }

  /* Current Skills Group */
  .skill-bar-group {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    padding-top: 0.5rem;
  }
  .skill-bar-item {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
  }
  .skill-bar-meta {
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-heading);
  }
  .skill-bar-track {
    width: 100%;
    height: 8px;
    background: var(--bg-muted);
    border-radius: 50px;
    overflow: hidden;
  }
  .skill-bar-fill {
    height: 100%;
    border-radius: 50px;
    transition: width 1s ease-in-out;
  }

  /* Achievements Item */
  .achievement {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.65rem;
    background: var(--bg-alt);
    border-radius: 12px;
    border: 1px solid var(--border);
  }
  .ach-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
  }
  .ach-name {
    color: var(--text-heading);
    font-weight: 600;
    font-size: 0.875rem;
  }
  .ach-desc {
    color: var(--text-secondary);
    font-size: 0.775rem;
  }

  /* Activity Feed */
  .activity-feed {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  .activity-item {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
  }
  .activity-icon {
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .activity-text {
    color: var(--text-heading);
    font-size: 0.875rem;
  }
  .activity-time {
    color: var(--text-muted);
    font-size: 0.75rem;
    margin-top: 2px;
  }

  /* Modals */
  .dashboard-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(8px);
    z-index: 1100;
    align-items: center;
    justify-content: center;
  }
  .dashboard-modal.active {
    display: flex;
  }
  .modal-content-card {
    width: 90%;
    max-width: 480px;
    animation: modalSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    background: var(--bg-card) !important;
    border: 1px solid var(--border) !important;
    color: var(--text-body);
  }
  @keyframes modalSlideIn {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
  }
  .modal-close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: transparent;
    border: none;
    font-size: 1.25rem;
    color: var(--text-muted);
    cursor: pointer;
  }

  /* Calendar Grid */
  .calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
    text-align: center;
    margin-top: 1rem;
  }
  .calendar-day-header {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-muted);
  }
  .calendar-day-cell {
    aspect-ratio: 1;
    border-radius: 8px;
    background: var(--bg-muted);
    border: 1px solid var(--border);
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
  }
  .calendar-day-cell.active {
    background: linear-gradient(135deg, #F59E0B, #D97706);
    color: #FFFFFF;
    font-weight: 700;
    border: none;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
  }
</style>

<!-- Header Row -->
<div class="dash-header flex-between">
  <div>
    <h1><?= $greetingTitle ?></h1>
    <p>Here's your learning overview for today — keep the streak alive!</p>
  </div>
  <div class="d-flex align-items-center gap-3">
    <div class="streak-badge" style="cursor:pointer;" onclick="openStreakModal()">
      <i class="fa-solid fa-fire"></i> <?= $currentStreak ?> Day Streak
    </div>
    <a href="<?= BASE_URL ?>student/assessments.php" class="btn btn-primary btn-sm rounded-pill px-3 py-2 fw-semibold">
      <i class="fa-solid fa-rocket me-1"></i> Take Assessment
    </a>
  </div>
</div>

<!-- Stats Cards Grid (Dynamic Database Metrics) -->
<div class="stats-grid-saas animate-slideUp">
  <!-- Card 1: Overall Skill Score -->
  <div class="saas-stat-card primary-card" style="cursor:pointer;" onclick="openSkillScoreModal()">
    <div class="stat-card-header">
      <span class="stat-card-title">Overall Skill Score</span>
      <div class="stat-icon-saas primary-gradient">
        <i class="fa-solid fa-star"></i>
      </div>
    </div>
    <div class="stat-card-body">
      <div class="stat-card-value gradient-value"><?= $avgScore ?></div>
    </div>
    <div class="stat-card-footer">
      <span class="stat-card-trend trend-primary">
        <i class="fa-solid fa-arrow-trend-up"></i> Top <?= $percentileRank ?>% cohort
      </span>
    </div>
  </div>

  <!-- Card 2: Completed Assessments -->
  <div class="saas-stat-card success-card" style="cursor:pointer;" onclick="window.location.href='<?= BASE_URL ?>student/history.php'">
    <div class="stat-card-header">
      <span class="stat-card-title">Completed Assessments</span>
      <div class="stat-icon-saas success-gradient">
        <i class="fa-solid fa-clipboard-check"></i>
      </div>
    </div>
    <div class="stat-card-body">
      <div class="stat-card-value"><?= $completedAssessments ?></div>
    </div>
    <div class="stat-card-footer">
      <span class="stat-card-trend trend-success">
        <i class="fa-solid fa-arrow-trend-up"></i> <?= $completedThisMonth ?> this month
      </span>
    </div>
  </div>

  <!-- Card 3: Courses Completed -->
  <div class="saas-stat-card warning-card" style="cursor:pointer;" onclick="window.location.href='<?= BASE_URL ?>student/recommendations.php'">
    <div class="stat-card-header">
      <span class="stat-card-title">Courses Completed</span>
      <div class="stat-icon-saas warning-gradient">
        <i class="fa-solid fa-book-open"></i>
      </div>
    </div>
    <div class="stat-card-body">
      <div class="stat-card-value"><?= $coursesCompleted ?></div>
    </div>
    <div class="stat-card-footer">
      <span class="stat-card-trend trend-warning">
        <i class="fa-solid fa-arrow-trend-up"></i> <?= $coursesCompletedThisWeek ?> this week
      </span>
    </div>
  </div>

  <!-- Card 4: Current Level -->
  <div class="saas-stat-card accent-card">
    <div class="stat-card-header">
      <span class="stat-card-title">Current Level</span>
      <div class="stat-icon-saas accent-gradient">
        <i class="fa-solid fa-layer-group"></i>
      </div>
    </div>
    <div class="stat-card-body">
      <?php 
        $levelParts = explode(' (', $currentLevel);
        $levelNum = $levelParts[0] ?? $currentLevel;
        $levelName = isset($levelParts[1]) ? rtrim($levelParts[1], ')') : '';
      ?>
      <div class="stat-card-value-level">
        <span class="level-main"><?= htmlspecialchars($levelNum) ?></span>
        <?php if (!empty($levelName)): ?>
          <span class="level-tag"><?= htmlspecialchars($levelName) ?></span>
        <?php endif; ?>
      </div>
    </div>
    <div class="stat-card-footer">
      <span class="stat-card-trend trend-accent">
        <i class="fa-solid fa-chart-line"></i> <?= $overallProgress ?>% overall progress
      </span>
    </div>
  </div>
</div>

<!-- Main Section: Current Skills -->
<div class="dashboard-grid">
  <div class="saas-card animate-slideUp p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold fs-5 mb-0" style="color: var(--text-heading);"><i class="fa-solid fa-chart-bar text-primary me-2"></i>Current Skills</h3>
      <a href="<?= BASE_URL ?>student/skill-gap.php" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">Full Analysis →</a>
    </div>

    <div class="skill-bar-group">
      <?php 
        $colorGradients = [
          'linear-gradient(90deg,#6366F1,#818CF8)',
          'linear-gradient(90deg,#8B5CF6,#A78BFA)',
          'linear-gradient(90deg,#F59E0B,#FCD34D)',
          'linear-gradient(90deg,#10B981,#34D399)',
          'linear-gradient(90deg,#EF4444,#F87171)',
          'linear-gradient(90deg,#3B82F6,#60A5FA)'
        ];
        $colorIdx = 0;
        foreach ($skillResults as $s): 
          $scoreVal = round((float)($s['score'] ?? 0));
          $fillGradient = $colorGradients[$colorIdx % count($colorGradients)];
          $colorIdx++;
      ?>
        <div class="skill-bar-item">
          <div class="skill-bar-meta">
            <span class="skill-name"><?= htmlspecialchars($s['skill_name']) ?></span>
            <span class="skill-score"><?= $scoreVal ?>%</span>
          </div>
          <div class="skill-bar-track">
            <div class="skill-bar-fill" style="width:<?= max(4, $scoreVal) ?>%;background:<?= $fillGradient ?>"></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Recommended Courses Section -->
<div class="saas-card animate-slideUp p-4 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold fs-5 mb-0" style="color: var(--text-heading);"><i class="fa-solid fa-graduation-cap text-warning me-2"></i>Recommended Courses</h3>
    <a href="<?= BASE_URL ?>student/recommendations.php" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">View All →</a>
  </div>
  <div class="d-flex flex-column gap-3">
    <?php foreach ($recommendations as $rec): ?>
      <div class="course-item d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
        <div class="fs-2">🎓</div>
        <div class="flex-grow-1">
          <div class="fw-semibold fs-6" style="color: var(--text-heading);"><?= htmlspecialchars($rec['course_title']) ?></div>
          <div class="small" style="color: var(--text-secondary);">Code: <?= htmlspecialchars($rec['course_code']) ?> &bull; <?= $rec['duration_hours'] ?>h &bull; Skill: <?= htmlspecialchars($rec['skill_name']) ?></div>
          <div class="skill-bar-track mt-2">
            <div class="skill-bar-fill" style="width:<?= max(5, round($rec['progress_percentage'])) ?>%;background:#26658C"></div>
          </div>
        </div>
        <a href="<?= BASE_URL ?>student/progress.php" class="badge saas-badge-primary rounded-pill px-3 py-2 text-decoration-none">
          <?= round($rec['progress_percentage']) ?>%
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Bottom Grid: Achievements + Activity Feed -->
<div class="dashboard-grid-3">
  <!-- Achievements Card (Dynamic) -->
  <div class="saas-card animate-slideUp p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold fs-5 mb-0" style="color: var(--text-heading);"><i class="fa-solid fa-trophy text-warning me-2"></i>Achievements</h3>
      <a href="<?= BASE_URL ?>student/profile.php" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">All →</a>
    </div>
    <div class="d-flex flex-column gap-3">
      <?php foreach ($achievements as $ach): ?>
        <div class="achievement">
          <div class="ach-icon" style="background:<?= $ach['bg'] ?>"><?= $ach['icon'] ?></div>
          <div>
            <div class="ach-name"><?= htmlspecialchars($ach['title']) ?></div>
            <div class="ach-desc"><?= htmlspecialchars($ach['desc']) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Recent Activity Card (Dynamic Database Activity) -->
  <div class="saas-card animate-slideUp p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold fs-5 mb-0" style="color: var(--text-heading);"><i class="fa-solid fa-clock-history text-info me-2"></i>Recent Activity</h3>
    </div>
    <div class="activity-feed">
      <?php if (!empty($recentActivity)): ?>
        <?php foreach ($recentActivity as $act): ?>
          <div class="activity-item">
            <div class="activity-icon" style="background:rgba(16,185,129,0.12);color:#10B981">
              <i class="fa-solid fa-check"></i>
            </div>
            <div>
              <div class="activity-text">Completed <span class="fw-semibold text-primary"><?= htmlspecialchars($act['item_title']) ?></span> assessment</div>
              <div class="activity-time"><?= format_date($act['activity_time']) ?> &bull; Score: <strong><?= number_format($act['score_percentage'], 1) ?>%</strong></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="activity-item">
          <div class="activity-icon" style="background:rgba(38,101,140,0.12);color:#26658C">
            <i class="fa-solid fa-info-circle"></i>
          </div>
          <div>
            <div class="activity-text">Account registered in <span class="fw-semibold text-primary"><?= htmlspecialchars($student['department']) ?></span> department</div>
            <div class="activity-time">Active Account</div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Overall Skill Score Modal -->
<div id="skillScoreModal" class="dashboard-modal" onclick="closeModalOnOuterClick(event, 'skillScoreModal')">
  <div class="modal-content-card saas-card p-4 text-center">
    <button class="modal-close-btn" onclick="closeDashboardModal('skillScoreModal')"><i class="fa-solid fa-xmark"></i></button>
    <h4 class="fw-bold mb-3" style="color: var(--text-heading);">Overall Skill Score</h4>
    <div style="position:relative;width:150px;height:150px;margin:0 auto 16px;border-radius:50%;background:conic-gradient(var(--primary) <?= ($avgScore * 3.6) ?>deg, var(--bg-muted) 0);display:flex;align-items:center;justify-content:center;box-shadow:0 0 30px rgba(38,101,140,0.15)">
      <div style="position:absolute;inset:12px;background:var(--bg-card);color:var(--text-heading);border-radius:50%;display:flex;flex-direction:column;align-items:center;justify-content:center">
        <span class="fs-2 fw-bold text-primary"><?= $avgScore ?></span>
        <span class="small text-muted">/ 100</span>
      </div>
    </div>
    <p class="small text-muted mb-3">You are ranked in the top <strong class="text-primary"><?= $percentileRank ?>%</strong> of your student cohort.</p>
    <div class="d-flex justify-content-center gap-3 pt-3 border-top" style="border-color: var(--border) !important;">
      <div>
        <div class="fw-bold text-success"><?= $completedAssessments ?> Tests</div>
        <div class="small text-muted">Completed</div>
      </div>
      <div class="border-end" style="border-color: var(--border) !important;"></div>
      <div>
        <div class="fw-bold text-warning"><?= $coursesCompleted ?> Courses</div>
        <div class="small text-muted">Finished</div>
      </div>
    </div>
  </div>
</div>

<!-- Streak Details Modal -->
<div id="streakModal" class="dashboard-modal" onclick="closeModalOnOuterClick(event, 'streakModal')">
  <div class="modal-content-card saas-card p-4 text-center">
    <button class="modal-close-btn" onclick="closeDashboardModal('streakModal')"><i class="fa-solid fa-xmark"></i></button>
    <i class="fa-solid fa-fire text-warning display-4 mb-2"></i>
    <h3 class="fw-bold mb-1" style="color: var(--text-heading);"><?= $currentStreak ?> Day Streak!</h3>
    <p class="small text-muted mb-4">Your daily learning consistency is tracked live in the database.</p>

    <div class="row g-2 mb-3">
      <div class="col-6">
        <div class="p-3 rounded-3 text-center" style="background: var(--bg-alt); border: 1px solid var(--border);">
          <span class="small text-muted d-block">Current Streak</span>
          <strong class="fs-4 text-warning"><?= $currentStreak ?> Days</strong>
        </div>
      </div>
      <div class="col-6">
        <div class="p-3 rounded-3 text-center" style="background: var(--bg-alt); border: 1px solid var(--border);">
          <span class="small text-muted d-block">Longest Streak</span>
          <strong class="fs-4 text-success"><?= $longestStreak ?> Days</strong>
        </div>
      </div>
    </div>

    <h6 class="fw-bold text-start mb-2" style="color: var(--text-heading);">Streak Activity Grid</h6>
    <div class="calendar-grid">
      <div class="calendar-day-header">M</div>
      <div class="calendar-day-header">T</div>
      <div class="calendar-day-header">W</div>
      <div class="calendar-day-header">T</div>
      <div class="calendar-day-header">F</div>
      <div class="calendar-day-header">S</div>
      <div class="calendar-day-header">S</div>

      <?php 
        for ($i = 1; $i <= 14; $i++): 
          $isActiveDay = ($i <= $currentStreak);
      ?>
        <div class="calendar-day-cell <?= $isActiveDay ? 'active' : '' ?>"><?= $i ?></div>
      <?php endfor; ?>
    </div>

  </div>
</div>

<script>
  function openStreakModal() {
    document.getElementById('streakModal').classList.add('active');
  }

  function openSkillScoreModal() {
    document.getElementById('skillScoreModal').classList.add('active');
  }

  function closeDashboardModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
  }

  function closeModalOnOuterClick(event, modalId) {
    if (event.target.id === modalId) {
      closeDashboardModal(modalId);
    }
  }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
