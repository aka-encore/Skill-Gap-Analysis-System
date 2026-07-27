<?php
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();
$courses = $db->fetchAll("SELECT * FROM courses WHERE status = 'active'");
foreach ($courses as $c) {
    echo "Course: {$c['title']} (ID: {$c['id']})\n";
    $skills = $db->fetchAll("SELECT skill_id FROM course_skills WHERE course_id = ?", [$c['id']]);
    foreach ($skills as $s) {
        $assessments = $db->fetchAll("SELECT id, title, passing_marks, total_marks FROM assessments WHERE skill_id = ? AND status = 'active'", [$s['skill_id']]);
        foreach ($assessments as $a) {
            echo "  -> Assessment: {$a['title']} (ID: {$a['id']}) | Passing: {$a['passing_marks']}/{$a['total_marks']}\n";
        }
    }
}
