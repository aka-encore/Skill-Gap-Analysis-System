<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$studentId = 1;
$skills = Database::getInstance()->fetchAll("SELECT id, name, category FROM skills LIMIT 5");

echo "=== Weighted Skill Percentage Test (Student ID {$studentId}) ===\n\n";

foreach ($skills as $s) {
    $w = calculate_weighted_skill_percentage($studentId, (int)$s['id']);
    echo "Skill: {$s['name']} ({$s['category']})\n";
    echo "Overall Weighted Skill %: {$w['overall_percentage']}%\n";
    echo "Status: {$w['status']}\n";
    echo "Attempted Levels Count: {$w['attempted_levels']} / 5\n";
    echo "Breakdown:\n";
    foreach ($w['breakdown'] as $lvl => $b) {
        $statusStr = $b['attempted'] ? "Attempted (Score: {$b['score_pct']}%, Contrib: {$b['contribution']}%)" : "Unattempted (0%)";
        echo "  - " . ucfirst($lvl) . " [Weight: {$b['weight']}%]: {$statusStr}\n";
    }
    echo "--------------------------------------------------------\n";
}

$overallAvg = calculate_overall_student_skill_percentage($studentId);
echo "\nOverall Average Weighted Skill Score across tested skills: {$overallAvg}%\n";
