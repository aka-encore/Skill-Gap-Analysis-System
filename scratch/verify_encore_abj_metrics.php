<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance();

// Query student record for user_id = 27 (Encore ABJ)
$student = $db->fetch("SELECT s.*, u.username, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE u.id = 27 OR s.id = 21");
if (!$student) {
    die("Student Encore ABJ not found!\n");
}

$studentId = (int)$student['id'];
$studentName = $student['first_name'] . ' ' . $student['last_name'];
$studentDept = $student['department'] ?? 'Computer Science';

echo "=== DATABASE METRICS FOR AUTHENTICATED USER: $studentName (student_id = $studentId, user_id = {$student['user_id']}) ===\n\n";

// 1. Total Skills Count
$totalSkillsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM skills")['cnt'] ?? 0);
echo "1. Total Skills Count (skills table): $totalSkillsCount\n";

// 2. Attempts Logged
$attemptsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE student_id = ?", [$studentId])['cnt'] ?? 0);
echo "2. Attempts Logged (assessment_results table): $attemptsCount\n";

// 3. Highest Score (%)
$maxScore = (float)($db->fetch("SELECT MAX(score_percentage) as mx FROM assessment_results WHERE student_id = ?", [$studentId])['mx'] ?? 0);
echo "3. Highest Score (%) (assessment_results table): " . round($maxScore, 1) . "%\n";

// 4. Average Score (%)
$avgScore = (float)($db->fetch("SELECT AVG(score_percentage) as av FROM assessment_results WHERE student_id = ?", [$studentId])['av'] ?? 0);
$overallWeightedScore = calculate_overall_student_skill_percentage($studentId);
echo "4. Average Score (%) (Simple SQL AVG): " . round($avgScore, 1) . "%\n";
echo "   Average Score (%) (Weighted Skill Calculation): " . round($overallWeightedScore, 1) . "%\n";

// 5. Skills Mastered
$skillsRaw = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");
$masteredCount = 0;
$scoresList = [];

foreach ($skillsRaw as $s) {
    $weighted = calculate_weighted_skill_percentage($studentId, (int)$s['id']);
    $pct = (float)$weighted['overall_percentage'];
    $scoresList[$s['name']] = [
        'score' => $pct,
        'status' => $weighted['status']
    ];
    if ($pct >= 60.0) {
        $masteredCount++;
    }
}

echo "5. Skills Mastered (Score >= 60%): $masteredCount / $totalSkillsCount\n";

// 6. Streak calculation
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
echo "6. Learning Streak (Synchronized): $streakDays Day(s)\n";

// 7. Enrolled courses
$progressRecords = $db->fetchAll(
    "SELECT sp.*, c.course_code, c.title as course_title, c.duration_hours
     FROM student_progress sp
     JOIN courses c ON sp.course_id = c.id
     WHERE sp.student_id = ?",
    [$studentId]
);
echo "7. Enrolled Courses Count: " . count($progressRecords) . "\n";

echo "\nAll Assessment Results for $studentName (ID $studentId):\n";
$results = $db->fetchAll("SELECT id, assessment_id, score_obtained, score_percentage, status, completed_at FROM assessment_results WHERE student_id = ?", [$studentId]);
print_r($results);
