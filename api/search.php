<?php
/**
 * SkillBridge - Dynamic Search API Endpoint
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$query = trim($_GET['q'] ?? '');
$type = trim($_GET['type'] ?? 'all');

if (empty($query)) {
    echo json_encode([]);
    exit;
}

$db = Database::getInstance();
$results = [];

if ($type === 'all' || $type === 'skills') {
    $skills = $db->fetchAll("SELECT id, name, category, description FROM skills WHERE name LIKE ? LIMIT 5", ["%$query%"]);
    foreach ($skills as $s) {
        $results[] = ['type' => 'skill', 'title' => $s['name'], 'subtitle' => $s['category'], 'url' => BASE_URL . 'student/skill-gap.php'];
    }
}

if ($type === 'all' || $type === 'courses') {
    $courses = $db->fetchAll("SELECT id, course_code, title FROM courses WHERE title LIKE ? OR course_code LIKE ? LIMIT 5", ["%$query%", "%$query%"]);
    foreach ($courses as $c) {
        $results[] = ['type' => 'course', 'title' => $c['course_code'] . ' - ' . $c['title'], 'subtitle' => 'Course Catalog', 'url' => BASE_URL . 'student/recommendations.php'];
    }
}

echo json_encode($results);
