<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

echo "=== ASSESSMENTS TABLE ===\n";
$assessments = $db->fetchAll("SELECT id, title, skill_id, created_by_faculty_id, status, created_at FROM assessments ORDER BY created_at DESC LIMIT 20");
print_r($assessments);

echo "\n=== COLUMN CHECK: does 'created_by_faculty_id' exist? ===\n";
try {
    $cols = $db->fetchAll("DESCRIBE assessments");
    foreach ($cols as $c) {
        echo $c['Field'] . ' | ' . $c['Type'] . ' | ' . $c['Key'] . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== FACULTY TABLE ===\n";
$faculty = $db->fetchAll("SELECT id, first_name, last_name, user_id FROM faculty LIMIT 10");
print_r($faculty);

echo "\n=== ASSESSMENT_RESULTS TABLE (sample) ===\n";
$results = $db->fetchAll("SELECT ar.id, ar.assessment_id, ar.student_id, ar.score_percentage, a.created_by_faculty_id FROM assessment_results ar JOIN assessments a ON ar.assessment_id = a.id LIMIT 10");
print_r($results);

echo "\n=== ASSESSMENT_QUESTIONS TABLE (sample) ===\n";
$qs = $db->fetchAll("SELECT assessment_id, COUNT(*) as cnt FROM assessment_questions GROUP BY assessment_id");
print_r($qs);
