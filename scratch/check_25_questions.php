<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

$assessments = $db->fetchAll("SELECT a.id, a.title, (SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = a.id) as q_count FROM assessments a");

echo "Assessment Question Counts:\n";
foreach ($assessments as $a) {
    echo "- Assessment ID {$a['id']}: '{$a['title']}' -> {$a['q_count']} Questions\n";
}
