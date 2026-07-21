<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance();
$studentId = 21; // Encore ABJ

echo "========================================================\n";
echo "DATABASE AUDIT FOR ENCORE ABJ (student_id = $studentId)\n";
echo "========================================================\n\n";

// 1. Fetch all attempts from assessment_results
$attempts = $db->fetchAll(
    "SELECT id, assessment_id, score_obtained, total_questions, score_percentage, status, completed_at 
     FROM assessment_results 
     WHERE student_id = ? 
     ORDER BY id ASC",
    [$studentId]
);

echo "1. ATTEMPTS LOGGED (from `assessment_results`):\n";
echo "--------------------------------------------------------\n";
$sumScore = 0;
$maxScore = 0;
$count = count($attempts);

foreach ($attempts as $idx => $att) {
    $num = $idx + 1;
    $score = (float)$att['score_percentage'];
    $sumScore += $score;
    if ($score > $maxScore) {
        $maxScore = $score;
    }
    echo "   Attempt #$num (Result ID {$att['id']}):\n";
    echo "      - Assessment ID: {$att['assessment_id']}\n";
    echo "      - Score: {$att['score_obtained']} / {$att['total_questions']} ({$score}%)\n";
    echo "      - Status: {$att['status']}\n";
    echo "      - Completed At: {$att['completed_at']}\n\n";
}

$avgScore = $count > 0 ? ($sumScore / $count) : 0;

echo "--------------------------------------------------------\n";
echo "   TOTAL ATTEMPTS COUNT  = $count\n";
echo "   HIGHEST SCORE         = $maxScore%\n";
echo "   SUM OF SCORES         = $sumScore%\n";
echo "   AVERAGE SCORE         = ($sumScore / $count) = " . number_format($avgScore, 1) . "%\n";
echo "--------------------------------------------------------\n\n";

// 2. Fetch total skills count & mastered skills count
$totalSkills = (int)($db->fetch("SELECT COUNT(*) as cnt FROM skills")['cnt'] ?? 0);
$skillsRaw = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");
$masteredSkills = 0;

echo "2. SKILLS MASTERED AUDIT (Threshold: >= 60% Overall Weighted Score):\n";
echo "--------------------------------------------------------\n";
foreach ($skillsRaw as $s) {
    $weighted = calculate_weighted_skill_percentage($studentId, (int)$s['id']);
    $pct = (float)$weighted['overall_percentage'];
    if ($pct >= 60.0) {
        $masteredSkills++;
        echo "   [MASTERED] Skill '{$s['name']}': {$pct}%\n";
    }
}

echo "   TOTAL SKILLS IN DB    = $totalSkills\n";
echo "   MASTERED SKILLS COUNT = $masteredSkills / $totalSkills\n";
echo "========================================================\n";
