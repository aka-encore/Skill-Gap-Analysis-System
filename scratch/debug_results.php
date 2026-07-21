<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

$results = $db->fetchAll("SELECT * FROM assessment_results ORDER BY id DESC LIMIT 20");
echo "Total Rows in assessment_results: " . count($results) . "\n";
foreach ($results as $r) {
    echo "ID: {$r['id']} | StudentID: {$r['student_id']} | AssessmentID: {$r['assessment_id']} | TotalQ: {$r['total_questions']} | Correct: {$r['correct_answers']} | Score%: {$r['score_percentage']} | Status: {$r['status']} | Date: {$r['completed_at']}\n";
}

$answersCount = $db->fetch("SELECT COUNT(*) as cnt FROM student_answers");
echo "\nTotal Rows in student_answers: " . ($answersCount['cnt'] ?? 0) . "\n";
