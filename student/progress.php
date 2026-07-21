<?php
/**
 * SkillBridge - Dynamic Student Progress Tracking & Leaderboard Analytics
 * 100% Database-driven via PDO SQL aggregation queries.
 * Single source of truth for Dashboard streak & real assessment metrics.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$userId = $_SESSION['user_id'];
$db = Database::getInstance();

// Handle Course Progress Update Form Submit
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['update_progress'])) {
    $progressId = (int)$_POST['progress_id'];
    $newVal = min(100, max(0, (int)$_POST['progress_percentage']));
    $status = ($newVal === 100) ? 'completed' : 'in_progress';

    $db->update('student_progress', [
        'progress_percentage' => $newVal,
        'status' => $status,
        'last_updated' => date('Y-m-d H:i:s')
    ], 'id = ? AND student_id = ?', [$progressId, $studentId]);

    set_flash_message('success', 'Course progress updated successfully.');
    redirect(BASE_URL . 'student/progress.php');
}

// 1. Fetch student info
$student = $db->fetch(
    "SELECT s.*, u.username, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?",
    [$studentId]
);
$studentName = htmlspecialchars($student['first_name'] . ' ' . $student['last_name']);
$studentDept = htmlspecialchars($student['department'] ?? 'Computer Science');

// 2. STREAK SYNCHRONIZATION WITH DASHBOARD (Single Source of Truth)
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
$streakDays = max(1, $currentStreak);

// 3. Overall Weighted Skill Percentage & Skill Competencies
$skillsRaw = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");
$studentSkillsList = [];
$totalScoreSum = 0;
$skillCount = count($skillsRaw);
$completedSkillsCount = 0;

foreach ($skillsRaw as $s) {
    $weighted = calculate_weighted_skill_percentage($studentId, (int)$s['id']);
    $score = (float)$weighted['overall_percentage'];
    
    if ($score >= 60) {
        $completedSkillsCount++;
    }

    $studentSkillsList[] = [
        'id' => (int)$s['id'],
        'name' => $s['name'],
        'category' => $s['category'],
        'score' => $score,
        'status' => $weighted['status'],
        'attempted_levels' => $weighted['attempted_levels']
    ];
    $totalScoreSum += $score;
}

$overallSkillScore = $skillCount > 0 ? round($totalScoreSum / $skillCount, 1) : 0.0;

// Sort skills for Progress display (highest score first)
usort($studentSkillsList, function($a, $b) {
    return $b['score'] <=> $a['score'];
});

// 4. Detailed Real Assessment Analytics Metrics
$assessmentsAttempted = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE student_id = ?", [$studentId])['cnt'] ?? 0);
$distinctAssessmentsCompleted = (int)($db->fetch("SELECT COUNT(DISTINCT assessment_id) as cnt FROM assessment_results WHERE student_id = ?", [$studentId])['cnt'] ?? 0);
$completedAssessmentsCount = $assessmentsAttempted;

$maxScore = round((float)($db->fetch("SELECT MAX(score_percentage) as mx FROM assessment_results WHERE student_id = ?", [$studentId])['mx'] ?? 0), 1);
$minScore = round((float)($db->fetch("SELECT MIN(score_percentage) as mn FROM assessment_results WHERE student_id = ?", [$studentId])['mn'] ?? 0), 1);
$avgAssessmentScore = round((float)($db->fetch("SELECT AVG(score_percentage) as av FROM assessment_results WHERE student_id = ?", [$studentId])['av'] ?? 0), 1);

// 5. Enrolled courses progress & Total Real Logged Learning Hours
$progressRecords = $db->fetchAll(
    "SELECT sp.*, c.course_code, c.title as course_title, c.description, c.duration_hours, c.difficulty_level, c.provider_url
     FROM student_progress sp
     JOIN courses c ON sp.course_id = c.id
     WHERE sp.student_id = ?
     ORDER BY sp.last_updated DESC",
    [$studentId]
);
$coursesDoneCount = 0;
$courseHoursSum = 0;
foreach ($progressRecords as $pr) {
    if ($pr['status'] === 'completed' || $pr['progress_percentage'] == 100) {
        $coursesDoneCount++;
        $courseHoursSum += (float)($pr['duration_hours'] ?? 0);
    }
}

// Compute Real Learning Hours strictly from DB records
$totalAssessmentSeconds = (int)($db->fetch("SELECT SUM(time_taken_seconds) as st FROM assessment_results WHERE student_id = ?", [$studentId])['st'] ?? 0);
$learningHours = round(($totalAssessmentSeconds / 3600) + $courseHoursSum, 1);

// 6. REAL ASSESSMENT HISTORY AGGREGATION QUERIES FOR CHART.JS GRAPH

// --- A. MONTHLY AGGREGATION (Last 7 Calendar Months) ---
$monthsMap = [];
for ($i = 6; $i >= 0; $i--) {
    $mCode = date('Y-m', strtotime("-$i month"));
    $mLabel = date('M', strtotime("-$i month")); // Clean single label format (e.g. Jan, Feb, Mar)
    $monthsMap[$mCode] = [
        'label' => $mLabel,
        'avg_score' => null,
        'hours' => 0.0,
        'count' => 0
    ];
}

$monthlyAggData = $db->fetchAll(
    "SELECT DATE_FORMAT(completed_at, '%Y-%m') as m_code,
            COUNT(*) as test_count,
            ROUND(AVG(score_percentage), 1) as avg_score,
            ROUND(SUM(time_taken_seconds) / 3600, 1) as total_hours
     FROM assessment_results 
     WHERE student_id = ? AND completed_at >= DATE_SUB(NOW(), INTERVAL 7 MONTH)
     GROUP BY m_code
     ORDER BY m_code ASC",
    [$studentId]
);

foreach ($monthlyAggData as $row) {
    if (isset($monthsMap[$row['m_code']])) {
        $monthsMap[$row['m_code']]['count'] = (int)$row['test_count'];
        $monthsMap[$row['m_code']]['avg_score'] = (float)$row['avg_score'];
        $monthsMap[$row['m_code']]['hours'] = (float)$row['total_hours'];
    }
}

$dbMonthlyLabels = array_column($monthsMap, 'label');
$dbMonthlyScores = array_column($monthsMap, 'avg_score');
$dbMonthlyHours  = array_column($monthsMap, 'hours');

// --- B. WEEKLY AGGREGATION (Last 7 Days) ---
$daysMap = [];
for ($i = 6; $i >= 0; $i--) {
    $dCode = date('Y-m-d', strtotime("-$i day"));
    $dLabel = date('D', strtotime("-$i day")); // Clean single label format (e.g. Mon, Tue, Wed)
    $daysMap[$dCode] = [
        'label' => $dLabel,
        'avg_score' => null,
        'hours' => 0.0,
        'count' => 0
    ];
}

$weeklyAggData = $db->fetchAll(
    "SELECT DATE(completed_at) as d_code,
            COUNT(*) as test_count,
            ROUND(AVG(score_percentage), 1) as avg_score,
            ROUND(SUM(time_taken_seconds) / 3600, 1) as total_hours
     FROM assessment_results 
     WHERE student_id = ? AND completed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
     GROUP BY d_code
     ORDER BY d_code ASC",
    [$studentId]
);

foreach ($weeklyAggData as $row) {
    if (isset($daysMap[$row['d_code']])) {
        $daysMap[$row['d_code']]['count'] = (int)$row['test_count'];
        $daysMap[$row['d_code']]['avg_score'] = (float)$row['avg_score'];
        $daysMap[$row['d_code']]['hours'] = (float)$row['total_hours'];
    }
}

$dbWeeklyLabels = array_column($daysMap, 'label');
$dbWeeklyScores = array_column($daysMap, 'avg_score');
$dbWeeklyHours  = array_column($daysMap, 'hours');

// 7. Leaderboard Calculation across all students (STRICTLY FROM DATABASE)
$allStudents = $db->fetchAll(
    "SELECT s.id, s.first_name, s.last_name, s.department, u.username 
     FROM students s 
     JOIN users u ON s.user_id = u.id"
);

$leaderboard = [];
foreach ($allStudents as $st) {
    $stId = (int)$st['id'];
    $stScoreSum = 0;
    foreach ($skillsRaw as $s) {
        $w = calculate_weighted_skill_percentage($stId, (int)$s['id']);
        $stScoreSum += (float)$w['overall_percentage'];
    }
    $stOverallScore = $skillCount > 0 ? round($stScoreSum / $skillCount, 1) : 0.0;
    
    if ($stId == $studentId) {
        $stOverallScore = $overallSkillScore;
    }

    $leaderboard[] = [
        'id' => $stId,
        'name' => htmlspecialchars($st['first_name'] . ' ' . $st['last_name']),
        'department' => htmlspecialchars($st['department'] ?? 'Computer Science'),
        'score' => $stOverallScore,
        'is_current' => ($stId == $studentId)
    ];
}

usort($leaderboard, function($a, $b) {
    return $b['score'] <=> $a['score'];
});

$studentRank = 1;
foreach ($leaderboard as $idx => &$lbItem) {
    $lbItem['rank'] = $idx + 1;
    if ($lbItem['is_current']) {
        $studentRank = $idx + 1;
    }
}
unset($lbItem);

// 8. Badges dynamic status determination
$badges = [
    [
        'id' => 'first_step',
        'title' => 'First Step',
        'desc' => 'Completed your first skill assessment',
        'icon' => 'fa-solid fa-flag-checkered',
        'grad' => 'cyan-grad',
        'unlocked' => ($assessmentsAttempted >= 1)
    ],
    [
        'id' => 'skill_explorer',
        'title' => 'Skill Explorer',
        'desc' => 'Evaluated competencies across 3+ technical skills',
        'icon' => 'fa-solid fa-compass',
        'grad' => 'indigo-grad',
        'unlocked' => ($skillCount >= 3)
    ],
    [
        'id' => 'streak_master',
        'title' => 'Streak Master',
        'desc' => 'Maintained a 7-day continuous study streak',
        'icon' => 'fa-solid fa-fire',
        'grad' => 'orange-grad',
        'unlocked' => ($streakDays >= 7)
    ],
    [
        'id' => 'quiz_ace',
        'title' => 'Quiz Ace',
        'desc' => 'Scored 80%+ on any difficulty level assessment',
        'icon' => 'fa-solid fa-award',
        'grad' => 'pink-grad',
        'unlocked' => ($maxScore >= 80)
    ],
    [
        'id' => 'course_finisher',
        'title' => 'Course Finisher',
        'desc' => 'Successfully completed an enrolled course module',
        'icon' => 'fa-solid fa-graduation-cap',
        'grad' => 'amber-grad',
        'unlocked' => ($coursesDoneCount >= 1)
    ],
    [
        'id' => 'top_cohort',
        'title' => 'Top 10 Cohort',
        'desc' => 'Ranked in top 10 on institutional leaderboard',
        'icon' => 'fa-solid fa-trophy',
        'grad' => 'emerald-grad',
        'unlocked' => ($studentRank <= 10)
    ]
];

$pageTitle = "Progress Tracking - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="dash-content">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
      <h2 class="fw-bold text-dark mb-1">Progress Tracking</h2>
      <p class="text-muted small mb-0">Your complete learning journey — skills, time, achievements, and rankings.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <!-- Synchronized Dashboard Streak -->
      <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-semibold" title="Synchronized with Student Dashboard">
        <i class="fa-solid fa-fire me-1"></i> <?= $streakDays ?> Day Streak
      </span>
      <select class="form-select form-select-sm rounded-pill border fw-semibold" style="width: auto;" onchange="toggleView(this.value)">
        <option value="month">This Month</option>
        <option value="week">This Week</option>
      </select>
    </div>
  </div>

  <!-- 1. KPI STATS GRID (CLICKABLE MODALS) -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm rounded-4 p-3 card-stat" onclick="openInteractiveModal('skill-score')">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon primary"><i class="fa-solid fa-star"></i></div>
          <div>
            <div class="stat-value gradient-text"><?= number_format($overallSkillScore, 1) ?>%</div>
            <div class="stat-label">Overall Skill Score</div>
            <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> DB Evaluated</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm rounded-4 p-3 card-stat" onclick="openInteractiveModal('learning-hours')">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon success"><i class="fa-solid fa-clock"></i></div>
          <div>
            <div class="stat-value text-success"><?= $learningHours ?>h</div>
            <div class="stat-label">Learning Hours</div>
            <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> Dynamic Log</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm rounded-4 p-3 card-stat" onclick="openInteractiveModal('courses-done')">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon warning"><i class="fa-solid fa-book-open"></i></div>
          <div>
            <div class="stat-value text-warning"><?= $coursesDoneCount ?></div>
            <div class="stat-label">Courses Completed</div>
            <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> <?= count($progressRecords) ?> Enrolled</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm rounded-4 p-3 card-stat" onclick="openInteractiveModal('leaderboard-rank')">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon accent"><i class="fa-solid fa-medal"></i></div>
          <div>
            <div class="stat-value text-info">#<?= $studentRank ?></div>
            <div class="stat-label">Leaderboard Rank</div>
            <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> Cohort Position</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. MONTHLY/WEEKLY PROGRESS CHART & SKILLS SIDE-BY-SIDE GRID -->
  <div class="row g-4 mb-4">
    <!-- Left: Learning & Assessment Analytics Interactive Canvas Chart -->
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100" id="monthly-progress-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-chart-line text-primary me-2"></i>Learning & Assessment Analytics</h5>
          <div class="btn-group btn-group-sm">
            <button class="btn btn-primary btn-sm rounded-start-pill" id="viewM" onclick="toggleView('month')">Monthly</button>
            <button class="btn btn-outline-secondary btn-sm rounded-end-pill" id="viewW" onclick="toggleView('week')">Weekly</button>
          </div>
        </div>

        <!-- Chart Container with Canvas & Professional Empty State -->
        <div class="chart-container-wrapper" id="chartContainerWrapper" style="position:relative; height:220px; width:100%;">
          <canvas id="progressChartCanvas"></canvas>
          <div id="noDataMessage" class="d-none text-center py-5">
            <i class="fa-solid fa-chart-line fs-2 text-muted mb-2"></i>
            <p class="text-muted small mb-0">No assessment data available yet. Complete an assessment to view your learning analytics.</p>
          </div>
        </div>

        <!-- 100% Database-Driven Performance Summary Container -->
        <div class="perf-summary-box mt-3" id="perfSummaryContainer">
          <div class="fw-bold text-dark small mb-2"><i class="fa-solid fa-lightbulb text-warning me-1"></i> Assessment History Metrics</div>
          <div class="row g-2">
            <div class="col-6 col-sm-3 text-center p-2 bg-white rounded border">
              <div class="fw-bold text-primary small"><?= number_format($avgAssessmentScore, 1) ?>%</div>
              <div class="text-muted" style="font-size: 10px;">AVERAGE SCORE</div>
            </div>
            <div class="col-6 col-sm-3 text-center p-2 bg-white rounded border">
              <div class="fw-bold text-success small"><?= $maxScore ?>%</div>
              <div class="text-muted" style="font-size: 10px;">HIGHEST SCORE</div>
            </div>
            <div class="col-6 col-sm-3 text-center p-2 bg-white rounded border">
              <div class="fw-bold text-warning small"><?= $assessmentsAttempted ?></div>
              <div class="text-muted" style="font-size: 10px;">ATTEMPTS LOGGED</div>
            </div>
            <div class="col-6 col-sm-3 text-center p-2 bg-white rounded border">
              <div class="fw-bold text-info small"><?= $completedSkillsCount ?> / <?= $skillCount ?></div>
              <div class="text-muted" style="font-size: 10px;">SKILLS MASTERED</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: Skills Summary Progress Bars -->
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100" id="skills-progress-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-layer-group text-info me-2"></i>Skill Competencies</h5>
          <span class="badge bg-primary-subtle text-primary border rounded-pill px-2.5 py-1" style="font-size: 11px;">Real-Time DB</span>
        </div>

        <div class="d-flex flex-column gap-3">
          <?php foreach (array_slice($studentSkillsList, 0, 5) as $sk): 
            $barColor = match(true) {
              $sk['score'] >= 75 => '#10B981',
              $sk['score'] >= 40 => '#F59E0B',
              default => '#EF4444'
            };
          ?>
            <div class="progress-row p-2 rounded hover-bg-light border border-transparent" onclick="openInteractiveModal('skill_<?= $sk['id'] ?>')">
              <div class="d-flex justify-content-between small fw-semibold mb-1">
                <span class="text-dark"><?= htmlspecialchars($sk['name']) ?></span>
                <span style="color: <?= $barColor ?>;"><?= number_format($sk['score'], 1) ?>%</span>
              </div>
              <div class="progress rounded-pill" style="height: 8px; background: #F1F5F9;">
                <div class="progress-bar rounded-pill" style="width: <?= max(5, $sk['score']) ?>%; background-color: <?= $barColor ?>;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <a href="<?= BASE_URL ?>student/skill-gap.php" class="btn btn-outline-primary rounded-pill w-100 py-2 mt-auto fw-semibold small">
          View Complete Skill Gap Matrix <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- 3. LEADERBOARD & BADGES (SIDE BY SIDE) -->
  <div class="row g-4 mb-4">
    <!-- Institutional Leaderboard -->
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-trophy text-warning me-2"></i>Student Leaderboard</h5>
          <span class="badge bg-warning-subtle text-warning border rounded-pill px-3 py-1">Top Ranks</span>
        </div>

        <div class="leaderboard-controls mb-3">
          <div class="search-wrapper mb-2">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="leaderboardSearch" class="leaderboard-search-input" placeholder="Search student name or department..." onkeyup="filterLeaderboard()">
          </div>
          <div class="d-flex gap-2">
            <select id="leaderboardDeptFilter" class="leaderboard-select flex-grow-1" onchange="filterLeaderboard()">
              <option value="all">All Departments</option>
              <option value="Computer Science">Computer Science</option>
              <option value="Information Technology">Information Technology</option>
            </select>
          </div>
        </div>

        <div class="leaderboard-list" id="leaderboardContainer">
          <?php foreach ($leaderboard as $lb): 
            $topClass = match($lb['rank']) {
              1 => 'top-1',
              2 => 'top-2',
              3 => 'top-3',
              default => ''
            };
            $userClass = $lb['is_current'] ? 'logged-in-user' : '';
          ?>
            <div class="leaderboard-item <?= $topClass ?> <?= $userClass ?>" data-name="<?= strtolower($lb['name']) ?>" data-dept="<?= htmlspecialchars($lb['department']) ?>">
              <div class="lb-rank">
                <?php if ($lb['rank'] == 1): ?><i class="fa-solid fa-crown text-warning"></i>
                <?php else: ?>#<?= $lb['rank'] ?>
                <?php endif; ?>
              </div>
              <div class="avatar avatar-xs rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 11px;">
                <?= strtoupper(substr($lb['name'], 0, 2)) ?>
              </div>
              <div class="lb-user-details">
                <div class="lb-name"><?= $lb['name'] ?> <?= $lb['is_current'] ? '<span class="badge bg-primary text-white" style="font-size: 9px;">YOU</span>' : '' ?></div>
                <div class="lb-dept"><?= $lb['department'] ?></div>
              </div>
              <div class="lb-score"><?= number_format($lb['score'], 1) ?>%</div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Achievements & Badges Grid -->
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 badges-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-ribbon text-danger me-2"></i>Achievements & Badges</h5>
          <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-1"><?= count(array_filter($badges, fn($b) => $b['unlocked'])) ?> / <?= count($badges) ?> Unlocked</span>
        </div>

        <div class="badges-grid">
          <?php foreach ($badges as $bg): ?>
            <div class="badge-item <?= $bg['unlocked'] ? '' : 'locked' ?>" onclick="openBadgeModal('<?= $bg['id'] ?>', '<?= $bg['title'] ?>', '<?= $bg['desc'] ?>', <?= $bg['unlocked'] ? 'true' : 'false' ?>)">
              <div class="badge-icon-wrapper <?= $bg['unlocked'] ? $bg['grad'] : 'locked-grad' ?>">
                <i class="<?= $bg['icon'] ?>"></i>
              </div>
              <div class="badge-title"><?= $bg['title'] ?></div>
              <?php if (!$bg['unlocked']): ?>
                <div class="badge-lock-overlay"><i class="fa-solid fa-lock"></i></div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- 4. ENROLLED COURSES PROGRESS TRACKER TABLE -->
  <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-graduation-cap text-primary me-2"></i>Enrolled Course Modules Progress</h5>
      <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1"><?= count($progressRecords) ?> Enrolled</span>
    </div>

    <?php if (empty($progressRecords)): ?>
      <div class="text-center py-4">
        <i class="fa-solid fa-book-open text-muted fs-1 mb-2"></i>
        <p class="text-muted small mb-0">No enrolled course progress found. Browse <a href="<?= BASE_URL ?>student/recommendations.php">recommended courses</a>.</p>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($progressRecords as $p): ?>
          <div class="col-md-6">
            <div class="p-3 bg-light rounded-3 border">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-white text-dark border fw-bold"><?= htmlspecialchars($p['course_code']) ?></span>
                <span class="badge bg-<?= $p['status'] === 'completed' ? 'success' : 'info' ?> rounded-pill px-3 py-1">
                  <?= strtoupper(str_replace('_', ' ', $p['status'])) ?>
                </span>
              </div>
              <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($p['course_title']) ?></h6>
              <div class="d-flex justify-content-between small text-muted mb-2">
                <span>Completion Status</span>
                <span class="fw-bold text-primary"><?= $p['progress_percentage'] ?>%</span>
              </div>
              <div class="progress rounded-pill mb-3" style="height: 8px;">
                <div class="progress-bar bg-<?= $p['status'] === 'completed' ? 'success' : 'primary' ?> rounded-pill" style="width: <?= $p['progress_percentage'] ?>%;"></div>
              </div>

              <form action="<?= BASE_URL ?>student/progress.php" method="POST" class="row g-2 align-items-center">
                <input type="hidden" name="progress_id" value="<?= $p['id'] ?>">
                <input type="hidden" name="update_progress" value="1">
                <div class="col-8">
                  <input type="range" name="progress_percentage" class="form-range" min="0" max="100" step="5" value="<?= $p['progress_percentage'] ?>" oninput="this.nextElementSibling.value = this.value + '%'">
                  <output class="small text-muted ms-2 d-none"><?= $p['progress_percentage'] ?>%</output>
                </div>
                <div class="col-4">
                  <button type="submit" class="btn btn-outline-primary btn-sm w-100 rounded-pill py-1 small">Update %</button>
                </div>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

<!-- ══════════ INTERACTIVE MODAL DIALOG ══════════ -->
<div id="interactiveModal" class="modal-overlay" onclick="handleModalOverlayClick(event)">
  <div class="modal-content interactive-modal-content">
    <button class="modal-close-x" onclick="closeInteractiveModal()"><i class="fa-solid fa-xmark"></i></button>
    <div id="modalDynamicBody"></div>
  </div>
</div>

<script>
// Real Aggregated Datasets fetched 100% from SQL Database
const dbMonthlyLabels = <?php echo json_encode($dbMonthlyLabels); ?>;
const dbMonthlyScores = <?php echo json_encode($dbMonthlyScores); ?>;
const dbMonthlyHours  = <?php echo json_encode($dbMonthlyHours); ?>;

const dbWeeklyLabels  = <?php echo json_encode($dbWeeklyLabels); ?>;
const dbWeeklyScores  = <?php echo json_encode($dbWeeklyScores); ?>;
const dbWeeklyHours   = <?php echo json_encode($dbWeeklyHours); ?>;

const overallSkillScore = <?php echo json_encode($overallSkillScore); ?>;
const studentRank       = <?php echo json_encode($studentRank); ?>;
const completedTests    = <?php echo json_encode($completedAssessmentsCount); ?>;
const learningHours     = <?php echo json_encode($learningHours); ?>;
const maxScore          = <?php echo json_encode($maxScore); ?>;
const minScore          = <?php echo json_encode($minScore); ?>;
const avgAssessmentScore= <?php echo json_encode($avgAssessmentScore); ?>;

let currentView = 'month';
let progressChartInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    renderChartJS(currentView);
});

function toggleView(view) {
    currentView = view;
    document.getElementById('viewM').className = view === 'month' ? 'btn btn-primary btn-sm rounded-start-pill' : 'btn btn-outline-secondary btn-sm rounded-start-pill';
    document.getElementById('viewW').className = view === 'week' ? 'btn btn-primary btn-sm rounded-end-pill' : 'btn btn-outline-secondary btn-sm rounded-end-pill';
    renderChartJS(view);
}

function renderChartJS(view) {
    const canvas = document.getElementById('progressChartCanvas');
    const noDataMsg = document.getElementById('noDataMessage');
    if (!canvas) return;

    // Separate clean label arrays
    const labels   = view === 'month' ? dbMonthlyLabels : dbWeeklyLabels;
    const lineData = view === 'month' ? dbMonthlyScores : dbWeeklyScores;
    const barData  = view === 'month' ? dbMonthlyHours  : dbWeeklyHours;

    const hasTestRecord = completedTests > 0 || lineData.some(v => v !== null && v > 0);

    if (!hasTestRecord) {
        canvas.style.display = 'none';
        if (noDataMsg) noDataMsg.classList.remove('d-none');
        return;
    } else {
        canvas.style.display = 'block';
        if (noDataMsg) noDataMsg.classList.add('d-none');
    }

    // Completely destroy previous chart instance before re-creating
    if (progressChartInstance) {
        progressChartInstance.destroy();
    }

    const ctx = canvas.getContext('2d');
    
    // Gradients
    const lineGradient = ctx.createLinearGradient(0, 0, 0, 200);
    lineGradient.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
    lineGradient.addColorStop(1, 'rgba(16, 185, 129, 0.05)');

    const barGradient = ctx.createLinearGradient(0, 0, 0, 200);
    barGradient.addColorStop(0, 'rgba(38, 101, 140, 0.85)');
    barGradient.addColorStop(1, 'rgba(38, 101, 140, 0.25)');

    const chartDatasets = [
        {
            type: 'line',
            label: 'Average Score (%)',
            data: lineData,
            borderColor: '#10B981',
            borderWidth: 3,
            backgroundColor: lineGradient,
            fill: true,
            tension: 0.3,
            spanGaps: true,
            pointBackgroundColor: '#10B981',
            pointBorderColor: '#FFFFFF',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
            yAxisID: 'yScore'
        }
    ];

    // Only include learning hours bar dataset if student has logged time
    const hasLoggedHours = barData.some(v => v > 0);
    if (hasLoggedHours) {
        chartDatasets.push({
            type: 'bar',
            label: 'Logged Hours',
            data: barData,
            backgroundColor: barGradient,
            borderColor: '#26658C',
            borderWidth: 1,
            borderRadius: 6,
            barPercentage: 0.45,
            yAxisID: 'yHours'
        });
    }

    progressChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: chartDatasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 12,
                        font: { size: 11, family: 'Inter, sans-serif' },
                        color: '#64748B'
                    }
                },
                tooltip: {
                    backgroundColor: '#021024',
                    titleColor: '#FFF',
                    bodyColor: '#E2E8F0',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) {
                                label += context.parsed.y + (context.dataset.yAxisID === 'yScore' ? '%' : 'h');
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748B', font: { size: 11, family: 'Inter, sans-serif' } }
                },
                yScore: {
                    type: 'linear',
                    position: 'left',
                    min: 0,
                    max: 100,
                    grid: { color: '#F1F5F9' },
                    ticks: {
                        color: '#10B981',
                        callback: function(val) { return val + '%'; },
                        font: { size: 10 }
                    }
                },
                yHours: {
                    type: 'linear',
                    position: 'right',
                    display: hasLoggedHours,
                    min: 0,
                    grid: { display: false },
                    ticks: {
                        color: '#26658C',
                        callback: function(val) { return val + 'h'; },
                        font: { size: 10 }
                    }
                }
            }
        }
    });
}

function filterLeaderboard() {
    const q = document.getElementById('leaderboardSearch').value.toLowerCase();
    const dept = document.getElementById('leaderboardDeptFilter').value;
    const items = document.querySelectorAll('#leaderboardContainer .leaderboard-item');

    items.forEach(item => {
        const name = item.getAttribute('data-name');
        const itemDept = item.getAttribute('data-dept');
        const matchName = name.includes(q);
        const matchDept = dept === 'all' || itemDept === dept;

        item.style.display = (matchName && matchDept) ? 'grid' : 'none';
    });
}

function openBadgeModal(id, title, desc, unlocked) {
    const body = document.getElementById('modalDynamicBody');
    body.innerHTML = `
        <div class="text-center p-3">
            <div class="fs-1 mb-2 ${unlocked ? 'text-warning' : 'text-muted'}"><i class="fa-solid ${unlocked ? 'fa-award' : 'fa-lock'}"></i></div>
            <h4 class="fw-bold text-dark mb-1">${title}</h4>
            <p class="text-muted small mb-3">${desc}</p>
            <span class="badge ${unlocked ? 'bg-success text-white' : 'bg-secondary text-white'} rounded-pill px-3 py-1.5">
                ${unlocked ? 'UNLOCKED ACHIEVED ✓' : 'LOCKED — COMPLETE REQUIREMENTS TO UNLOCK'}
            </span>
        </div>
    `;
    document.getElementById('interactiveModal').classList.add('active');
}

function openInteractiveModal(type) {
    const body = document.getElementById('modalDynamicBody');
    if (type === 'skill-score') {
        body.innerHTML = `
            <div class="p-2">
                <h4 class="fw-bold text-dark mb-2"><i class="fa-solid fa-star text-primary me-2"></i>Overall Skill Score</h4>
                <p class="text-muted small">Calculated proficiency level: <strong>${overallSkillScore}%</strong>.</p>
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <div class="d-flex justify-content-between small fw-bold mb-1">
                        <span>Highest Score</span>
                        <span class="text-success">${maxScore}%</span>
                    </div>
                    <div class="d-flex justify-content-between small fw-bold mb-1">
                        <span>Average Assessment Score</span>
                        <span class="text-primary">${avgAssessmentScore}%</span>
                    </div>
                    <div class="progress rounded-pill mb-2" style="height:10px;">
                        <div class="progress-bar bg-primary rounded-pill" style="width: ${overallSkillScore}%"></div>
                    </div>
                </div>
            </div>
        `;
    } else if (type === 'learning-hours') {
        body.innerHTML = `
            <div class="p-2">
                <h4 class="fw-bold text-dark mb-2"><i class="fa-solid fa-clock text-success me-2"></i>Study & Practice Log</h4>
                <p class="text-muted small">You have logged <strong>${learningHours} hours</strong> of total active learning and assessment time from DB records.</p>
            </div>
        `;
    } else if (type === 'leaderboard-rank') {
        body.innerHTML = `
            <div class="p-2">
                <h4 class="fw-bold text-dark mb-2"><i class="fa-solid fa-medal text-info me-2"></i>Institutional Cohort Rank</h4>
                <p class="text-muted small">You are currently ranked <strong>#${studentRank}</strong> among all active students.</p>
            </div>
        `;
    } else {
        body.innerHTML = `
            <div class="p-2">
                <h4 class="fw-bold text-dark mb-2"><i class="fa-solid fa-circle-info text-primary me-2"></i>Progress Analytics</h4>
                <p class="text-muted small">Performance summary loaded dynamically from MySQL database records.</p>
            </div>
        `;
    }
    document.getElementById('interactiveModal').classList.add('active');
}

function closeInteractiveModal() {
    document.getElementById('interactiveModal').classList.remove('active');
}

function handleModalOverlayClick(e) {
    if (e.target.id === 'interactiveModal') closeInteractiveModal();
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
