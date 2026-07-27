<?php
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();
$courses = $db->fetchAll(
    "SELECT c.id, c.title, cs.skill_id, s.name as skill_name 
     FROM courses c
     LEFT JOIN course_skills cs ON c.id = cs.course_id
     LEFT JOIN skills s ON cs.skill_id = s.id
     ORDER BY c.id ASC"
);
echo "=== Course to Skill Mappings ===\n";
foreach ($courses as $c) {
    echo "Course ID: {$c['id']} | Title: {$c['title']} | Skill ID: {$c['skill_id']} | Skill Name: {$c['skill_name']}\n";
}
