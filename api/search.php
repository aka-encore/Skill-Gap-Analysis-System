<?php
/**
 * SkillBridge - Dynamic Search API Endpoint
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../includes/auth.php';

$query = trim($_GET['q'] ?? '');
$type = trim($_GET['type'] ?? 'all');

if (empty($query)) {
    echo json_encode([]);
    exit;
}

$userRole = $_SESSION['user_role'] ?? 'student';
$db = Database::getInstance();
$results = [];

if ($type === 'all' || $type === 'skills') {
    $skills = $db->fetchAll("SELECT id, name, category, description FROM skills WHERE name LIKE ? LIMIT 5", ["%$query%"]);
    $skillUrl = match($userRole) {
        'admin'   => BASE_URL . 'admin/skills.php',
        'faculty' => BASE_URL . 'faculty/skill-gap.php',
        default   => BASE_URL . 'student/skill-gap.php'
    };
    foreach ($skills as $s) {
        $results[] = ['type' => 'skill', 'title' => $s['name'], 'subtitle' => $s['category'], 'url' => $skillUrl];
    }
}

if ($type === 'all' || $type === 'courses') {
    $courses = $db->fetchAll("SELECT id, course_code, title FROM courses WHERE title LIKE ? OR course_code LIKE ? LIMIT 5", ["%$query%", "%$query%"]);
    $courseUrl = match($userRole) {
        'admin'   => BASE_URL . 'admin/courses.php',
        default   => BASE_URL . 'student/recommendations.php#recommended-courses'
    };
    foreach ($courses as $c) {
        $results[] = ['type' => 'course', 'title' => $c['course_code'] . ' - ' . $c['title'], 'subtitle' => 'Course Catalog', 'url' => $courseUrl];
    }
}

echo json_encode($results);
