<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

echo "--- STUDENTS ---\n";
$students = $db->fetchAll("SELECT s.*, u.email FROM students s JOIN users u ON s.user_id = u.id");
print_r($students);

echo "--- ASSESSMENT RESULTS ---\n";
$results = $db->fetchAll("SELECT ar.*, a.title, s.name as skill_name FROM assessment_results ar JOIN assessments a ON ar.assessment_id = a.id JOIN skills s ON a.skill_id = s.id LIMIT 10");
print_r($results);

echo "--- SKILLS & SCORES FOR STUDENT 1 ---\n";
$skills = $db->fetchAll("SELECT s.id, s.name, MAX(ar.score_percentage) as max_score FROM skills s LEFT JOIN assessments a ON s.id = a.skill_id LEFT JOIN assessment_results ar ON a.id = ar.assessment_id AND ar.student_id = 1 GROUP BY s.id, s.name");
print_r($skills);

echo "--- COURSES & PROGRESS ---\n";
$progress = $db->fetchAll("SELECT sp.*, c.title FROM student_progress sp JOIN courses c ON sp.course_id = c.id LIMIT 10");
print_r($progress);

echo "--- RECOMMENDATIONS ---\n";
$recs = $db->fetchAll("SELECT r.*, c.title as course_title, s.name as skill_name FROM recommendations r JOIN courses c ON r.course_id = c.id JOIN skills s ON r.skill_id = s.id LIMIT 10");
print_r($recs);
