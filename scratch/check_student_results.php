<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

$results = $db->fetchAll(
    "SELECT ar.*, a.title as assessment_title, s.name as skill_name, s.category as skill_category
     FROM assessment_results ar
     JOIN assessments a ON ar.assessment_id = a.id
     JOIN skills s ON a.skill_id = s.id
     WHERE ar.student_id = 1
     ORDER BY ar.completed_at DESC"
);

echo "Total Assessment Results for Student 1: " . count($results) . "\n";
foreach ($results as $r) {
    echo "- Result ID: {$r['id']} | Assessment ID: {$r['assessment_id']} | Title: {$r['assessment_title']} | Score: {$r['score_percentage']}% | Date: {$r['completed_at']}\n";
    $answers = $db->fetchAll("SELECT * FROM student_answers WHERE result_id = ?", [$r['id']]);
    echo "  -> Answers count for this result_id ({$r['id']}): " . count($answers) . "\n";
}
