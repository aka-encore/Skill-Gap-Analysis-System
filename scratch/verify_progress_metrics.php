<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance();
$studentId = 1;

echo "=== DATABASE METRICS VERIFICATION FOR STUDENT ID = $studentId ===\n\n";

// 1. Total Skills Count in Database
$totalSkillsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM skills")['cnt'] ?? 0);
echo "1. Total Skills Count (from `skills` table): $totalSkillsCount\n";

// 2. Attempts Logged (from `assessment_results` table)
$attemptsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE student_id = ?", [$studentId])['cnt'] ?? 0);
echo "2. Attempts Logged (from `assessment_results` table): $attemptsCount\n";

// 3. Highest Score (%)
$maxScore = (float)($db->fetch("SELECT MAX(score_percentage) as mx FROM assessment_results WHERE student_id = ?", [$studentId])['mx'] ?? 0);
echo "3. Highest Score (%) (from `assessment_results` table): $maxScore%\n";

// 4. Average Score (%)
$avgScore = (float)($db->fetch("SELECT AVG(score_percentage) as av FROM assessment_results WHERE student_id = ?", [$studentId])['av'] ?? 0);
$overallWeightedScore = calculate_overall_student_skill_percentage($studentId);
echo "4. Average Score (%) (Simple SQL AVG): " . round($avgScore, 1) . "%\n";
echo "   Average Score (%) (Weighted Skill Calculation): " . round($overallWeightedScore, 1) . "%\n";

// 5. Skills Mastered (Dynamic check across all skills)
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
echo "\nDetailed Skills Breakdown:\n";
print_r($scoresList);

echo "\nAll Assessment Results for Student $studentId:\n";
$results = $db->fetchAll("SELECT id, assessment_id, score_obtained, score_percentage, status, completed_at FROM assessment_results WHERE student_id = ?", [$studentId]);
print_r($results);
