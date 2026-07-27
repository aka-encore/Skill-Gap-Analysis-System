<?php
/**
 * SkillBridge - Full Faculty & Admin Module Diagnostic
 * Tests every layer: DB, session simulation, queries, PHP logic
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

// ─── Simulate Faculty login context (f_turing = faculty.id=1) ─────────────
$testFacultyId = 1; // Alan Turing
$testAdminId   = 1;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║         SKILLBRIDGE FACULTY & ADMIN DIAGNOSTIC REPORT        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// ──────────────────────────────────────────────────────────────────────────
// SECTION 1: DB SCHEMA VERIFICATION
// ──────────────────────────────────────────────────────────────────────────
echo "═══ [1] DATABASE TABLES ═══\n";
$tables = $db->fetchAll("SHOW TABLES");
foreach ($tables as $t) {
    $name = array_values($t)[0];
    $cnt  = $db->fetch("SELECT COUNT(*) as c FROM `$name`")['c'];
    echo "  TABLE: $name  →  $cnt rows\n";
}

// ──────────────────────────────────────────────────────────────────────────
// SECTION 2: FACULTY DASHBOARD QUERIES
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [2] FACULTY DASHBOARD (faculty_id=$testFacultyId) ═══\n";

$faculty = $db->fetch("SELECT f.*, u.email FROM faculty f JOIN users u ON f.user_id = u.id WHERE f.id = ?", [$testFacultyId]);
echo "  Faculty profile: " . ($faculty ? "OK ({$faculty['first_name']} {$faculty['last_name']})" : "MISSING!") . "\n";

$totalStudents = (int)($db->fetch("SELECT COUNT(*) as cnt FROM students")['cnt'] ?? 0);
echo "  totalStudents: $totalStudents\n";

$myAssessmentsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessments")['cnt'] ?? 0);
echo "  totalAssessmentsCount (Shared Repo): $myAssessmentsCount\n";

$totalSubmissions = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results")['cnt'] ?? 0);
echo "  totalSubmissions: $totalSubmissions\n";

$classAvgRow = $db->fetch("SELECT AVG(score_percentage) as avg_score FROM assessment_results");
$classAvgScore = round((float)($classAvgRow['avg_score'] ?? 0), 1);
echo "  classAvgScore: $classAvgScore%\n";

$recentSubmissions = $db->fetchAll(
    "SELECT ar.*, a.title as assessment_title, st.first_name, st.last_name, st.student_code, s.name as skill_name
     FROM assessment_results ar
     JOIN assessments a ON ar.assessment_id = a.id
     JOIN students st ON ar.student_id = st.id
     JOIN skills s ON a.skill_id = s.id
     ORDER BY ar.completed_at DESC LIMIT 5"
);
echo "  recentSubmissions count: " . count($recentSubmissions) . "\n";

$assessmentPerf = $db->fetchAll(
    "SELECT a.title, AVG(ar.score_percentage) as avg_score
     FROM assessments a
     LEFT JOIN assessment_results ar ON a.id = ar.assessment_id
     GROUP BY a.id, a.title ORDER BY a.created_at DESC LIMIT 6"
);
echo "  chartData assessments (for bar chart): " . count($assessmentPerf) . "\n";

// ──────────────────────────────────────────────────────────────────────────
// SECTION 3: FACULTY ASSESSMENTS PAGE
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [3] FACULTY ASSESSMENTS PAGE (Shared Repo) ═══\n";

$assessments = $db->fetchAll(
    "SELECT a.*, s.name as skill_name, f.first_name as creator_first, f.last_name as creator_last,
            (SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = a.id) as question_count,
            (SELECT COUNT(*) FROM assessment_results WHERE assessment_id = a.id) as submission_count
     FROM assessments a
     JOIN skills s ON a.skill_id = s.id
     LEFT JOIN faculty f ON a.created_by_faculty_id = f.id
     ORDER BY a.created_at DESC"
);
echo "  Assessments for faculty $testFacultyId: " . count($assessments) . "\n";
foreach ($assessments as $a) {
    echo "    → [{$a['id']}] {$a['title']} | skill={$a['skill_name']} | Qs={$a['question_count']} | subs={$a['submission_count']} | status={$a['status']}\n";
}

// ──────────────────────────────────────────────────────────────────────────
// SECTION 4: QUESTION BANK
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [4] QUESTION BANK ═══\n";

$assessmentsList = $db->fetchAll(
    "SELECT a.*, f.first_name as creator_first, f.last_name as creator_last 
     FROM assessments a 
     LEFT JOIN faculty f ON a.created_by_faculty_id = f.id 
     ORDER BY a.title ASC"
);
echo "  Assessments in dropdown for faculty $testFacultyId: " . count($assessmentsList) . "\n";

if (!empty($assessmentsList)) {
    $firstAssId = $assessmentsList[0]['id'];
    $questions = $db->fetchAll(
        "SELECT * FROM assessment_questions WHERE assessment_id = ? ORDER BY id ASC",
        [$firstAssId]
    );
    echo "  Questions for assessment [{$firstAssId}] '{$assessmentsList[0]['title']}': " . count($questions) . "\n";
}

// ──────────────────────────────────────────────────────────────────────────
// SECTION 5: FACULTY SKILL GAP ANALYTICS
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [5] FACULTY SKILL GAP ANALYTICS ═══\n";

$classSkills = $db->fetchAll(
    "SELECT s.name as skill_name, s.category,
            AVG(ar.score_percentage) as class_avg_score,
            COUNT(DISTINCT ar.student_id) as total_students_tested,
            SUM(CASE WHEN ar.score_percentage < 60 THEN 1 ELSE 0 END) as weak_students_count
     FROM skills s
     JOIN assessments a ON s.id = a.skill_id
     JOIN assessment_results ar ON a.id = ar.assessment_id
     GROUP BY s.id, s.name, s.category
     ORDER BY class_avg_score ASC"
);
echo "  Skill gap rows: " . count($classSkills) . "\n";
foreach ($classSkills as $cs) {
    echo "    → {$cs['skill_name']} | avg={$cs['class_avg_score']} | tested={$cs['total_students_tested']}\n";
}

// ──────────────────────────────────────────────────────────────────────────
// SECTION 6: ADMIN DASHBOARD
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [6] ADMIN DASHBOARD ═══\n";

echo "  totalStudents: " . $db->fetch("SELECT COUNT(*) as c FROM students")['c'] . "\n";
echo "  totalFaculty: " . $db->fetch("SELECT COUNT(*) as c FROM faculty")['c'] . "\n";
echo "  totalCourses: " . $db->fetch("SELECT COUNT(*) as c FROM courses")['c'] . "\n";
echo "  totalSkills: " . $db->fetch("SELECT COUNT(*) as c FROM skills")['c'] . "\n";
echo "  totalAssessments: " . $db->fetch("SELECT COUNT(*) as c FROM assessments")['c'] . "\n";
echo "  totalNotifications: " . $db->fetch("SELECT COUNT(*) as c FROM notifications")['c'] . "\n";

$recentLogs = $db->fetchAll(
    "SELECT l.*, u.username, u.role 
     FROM activity_logs l 
     LEFT JOIN users u ON l.user_id = u.id 
     ORDER BY l.created_at DESC LIMIT 6"
);
echo "  Recent activity logs: " . count($recentLogs) . "\n";

$dbSizeRow = $db->fetch(
    "SELECT SUM(data_length + index_length) / 1024 / 1024 as db_size_mb 
     FROM information_schema.tables 
     WHERE table_schema = ?",
    [DB_NAME]
);
echo "  DB size: " . round((float)($dbSizeRow['db_size_mb'] ?? 0), 2) . " MB\n";

// ──────────────────────────────────────────────────────────────────────────
// SECTION 7: ADMIN ANALYTICS
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [7] ADMIN ANALYTICS ═══\n";

$passCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE status = 'pass'")['cnt'] ?? 0);
$failCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE status = 'fail'")['cnt'] ?? 0);
echo "  passCount: $passCount | failCount: $failCount\n";

$deptStats = $db->fetchAll(
    "SELECT s.department, AVG(ar.score_percentage) as avg_score, COUNT(ar.id) as total_tests
     FROM students s
     JOIN assessment_results ar ON s.id = ar.student_id
     GROUP BY s.department"
);
echo "  Department stats: " . count($deptStats) . " departments\n";
foreach ($deptStats as $d) {
    echo "    → {$d['department']} | avg=" . round((float)$d['avg_score'],1) . "% | tests={$d['total_tests']}\n";
}

// ──────────────────────────────────────────────────────────────────────────
// SECTION 8: ADMIN ASSESSMENTS
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [8] ADMIN ASSESSMENTS ═══\n";

$adminAssessments = $db->fetchAll(
    "SELECT a.*, s.name as skill_name, f.first_name, f.last_name,
            (SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = a.id) as q_count,
            (SELECT COUNT(*) FROM assessment_results WHERE assessment_id = a.id) as sub_count
     FROM assessments a
     JOIN skills s ON a.skill_id = s.id
     JOIN faculty f ON a.created_by_faculty_id = f.id
     ORDER BY a.created_at DESC"
);
echo "  Admin sees assessments: " . count($adminAssessments) . "\n";

// ──────────────────────────────────────────────────────────────────────────
// SECTION 9: ADMIN COURSES
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [9] ADMIN COURSES ═══\n";
$courses = $db->fetchAll("SELECT id, title, status FROM courses ORDER BY id ASC LIMIT 10");
echo "  Courses (first 10): " . count($courses) . "\n";
foreach ($courses as $c) {
    echo "    → [{$c['id']}] {$c['title']} | status={$c['status']}\n";
}

// ──────────────────────────────────────────────────────────────────────────
// SECTION 10: ADMIN SKILLS
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [10] ADMIN SKILLS ═══\n";
$skills = $db->fetchAll("SELECT id, name, category FROM skills ORDER BY id ASC LIMIT 10");
echo "  Skills (first 10): " . count($skills) . "\n";
foreach ($skills as $s) {
    echo "    → [{$s['id']}] {$s['name']} | category={$s['category']}\n";
}

// ──────────────────────────────────────────────────────────────────────────
// SECTION 11: ADMIN REPORTS TABLE
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [11] ADMIN REPORTS ═══\n";
try {
    $reports = $db->fetchAll("SELECT id, title, report_type FROM reports LIMIT 5");
    echo "  Reports count: " . count($reports) . "\n";
    if (empty($reports)) echo "  (no reports generated yet — this is OK)\n";
} catch (Exception $e) {
    echo "  ERROR: " . $e->getMessage() . "\n";
}

// ──────────────────────────────────────────────────────────────────────────
// SECTION 12: PJAX INIT REGISTRATION CHECK
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [12] PJAX INITIALIZER NAMES CHECK ═══\n";

$pjaxInitMap = [
    'faculty/dashboard.php'    => 'initFacultyDashboard',
    'faculty/skill-gap.php'    => 'initFacultySkillGap',
    'admin/dashboard.php'      => 'initAdminDashboard',
    'admin/analytics.php'      => 'initAdminAnalytics',
    'student/dashboard.php'    => 'initDashboard',
    'student/skill-gap.php'    => 'initSkillGap',
];

$baseDir = __DIR__ . '/../';
foreach ($pjaxInitMap as $file => $fnName) {
    $path = $baseDir . $file;
    if (!file_exists($path)) {
        echo "  MISSING FILE: $file\n";
        continue;
    }
    $content = file_get_contents($path);
    $hasInit  = str_contains($content, "window.$fnName");
    $hasSrc   = str_contains($content, 'charts-config.js');
    echo "  $file → window.$fnName: " . ($hasInit ? "✓ REGISTERED" : "✗ MISSING!") 
         . " | inline-charts-src: " . ($hasSrc ? "⚠ STILL PRESENT" : "✓ removed") . "\n";
}

// ──────────────────────────────────────────────────────────────────────────
// SECTION 13: SESSION HANDLING CHECK
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [13] SESSION/AUTH MECHANISM CHECK ═══\n";
$authContent = file_get_contents(__DIR__ . '/../includes/auth.php');
$setsProfileId = str_contains($authContent, "profile_id");
echo "  auth.php sets \$_SESSION['profile_id']: " . ($setsProfileId ? "✓" : "✗ MISSING!") . "\n";

$dbContent = file_get_contents(__DIR__ . '/../config/database.php');
$hasFetch    = str_contains($dbContent, 'function fetch');
$hasFetchAll = str_contains($dbContent, 'function fetchAll');
echo "  database.php has fetch(): " . ($hasFetch ? "✓" : "✗") . "\n";
echo "  database.php has fetchAll(): " . ($hasFetchAll ? "✓" : "✗") . "\n";

// ──────────────────────────────────────────────────────────────────────────
// SECTION 14: JS PJAX TRIGGER MAP CHECK
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ [14] JS PJAX TRIGGER MAP ═══\n";
$appJs = file_get_contents(__DIR__ . '/../assets/js/app.js');
$checks = [
    'initFacultyDashboard in runPageSpecificInitializer' => str_contains($appJs, 'initFacultyDashboard'),
    'initAdminDashboard in runPageSpecificInitializer'   => str_contains($appJs, 'initAdminDashboard'),
    'initFacultySkillGap in runPageSpecificInitializer'  => str_contains($appJs, 'initFacultySkillGap'),
    'initAdminAnalytics in runPageSpecificInitializer'   => str_contains($appJs, 'initAdminAnalytics'),
    'executePageScripts skips src scripts'               => str_contains($appJs, 'oldScript.src && oldScript.src.length'),
    'assessments.php in PJAX exclude list'               => str_contains($appJs, "'assessments.php'"),
];
foreach ($checks as $check => $result) {
    echo "  " . ($result ? "✓" : "✗") . " $check\n";
}

// ──────────────────────────────────────────────────────────────────────────
// FINAL SUMMARY
// ──────────────────────────────────────────────────────────────────────────
echo "\n═══ DIAGNOSTIC COMPLETE ═══\n";
echo "Run: php scratch/full_diagnostic.php\n";
