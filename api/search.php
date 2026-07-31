<?php
/**
 * SkillBridge - Dynamic Search API Endpoint (Role-Protected & Enforced)
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

// Enforce login requirement for API access
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$query = trim($_GET['q'] ?? '');
if (empty($query)) {
    echo json_encode([]);
    exit;
}

$userRole = $_SESSION['user_role'] ?? 'student';
$db = Database::getInstance();
$results = [];

// 1. Search Skills (Role-restricted destination URL & permissions)
$skills = $db->fetchAll(
    "SELECT id, name, category, description FROM skills WHERE name LIKE ? OR category LIKE ? OR description LIKE ? LIMIT 5",
    ["%$query%", "%$query%", "%$query%"]
);
$skillUrl = match($userRole) {
    'admin'   => BASE_URL . 'admin/skills.php',
    'faculty' => BASE_URL . 'faculty/skill-gap.php',
    default   => BASE_URL . 'student/skill-gap.php'
};
foreach ($skills as $s) {
    $results[] = [
        'type'     => 'skill',
        'title'    => $s['name'],
        'desc'     => 'Skill Tag • ' . ($s['category'] ?? 'General'),
        'url'      => $skillUrl,
        'icon'     => 'fa-lightbulb',
        'category' => 'Skills & Competencies'
    ];
}

// 2. Search Courses (Role-restricted destination URL & permissions)
$courses = $db->fetchAll(
    "SELECT id, course_code, title, category FROM courses WHERE title LIKE ? OR course_code LIKE ? LIMIT 5",
    ["%$query%", "%$query%"]
);
$courseUrl = match($userRole) {
    'admin'   => BASE_URL . 'admin/courses.php',
    'faculty' => BASE_URL . 'faculty/assessments.php',
    default   => BASE_URL . 'student/courses.php'
};
foreach ($courses as $c) {
    $results[] = [
        'type'     => 'course',
        'title'    => $c['course_code'] . ' - ' . $c['title'],
        'desc'     => 'Course Catalog (' . ($c['category'] ?? 'General') . ')',
        'url'      => $courseUrl,
        'icon'     => 'fa-graduation-cap',
        'category' => 'Courses'
    ];
}

// 3. Search Assessments (Role-restricted destination URL & permissions)
$assessments = $db->fetchAll(
    "SELECT a.id, a.title, s.category 
     FROM assessments a 
     JOIN skills s ON a.skill_id = s.id 
     WHERE a.title LIKE ? OR s.category LIKE ? 
     LIMIT 5",
    ["%$query%", "%$query%"]
);
$assessmentUrl = match($userRole) {
    'admin'   => BASE_URL . 'admin/assessments.php',
    'faculty' => BASE_URL . 'faculty/assessments.php',
    default   => BASE_URL . 'student/assessments.php'
};
foreach ($assessments as $a) {
    $results[] = [
        'type'     => 'assessment',
        'title'    => $a['title'],
        'desc'     => 'Skill Test • ' . ($a['category'] ?? 'General'),
        'url'      => $assessmentUrl,
        'icon'     => 'fa-clipboard-check',
        'category' => 'Assessments'
    ];
}

// 4. Role-Specific Dynamic Entity Search
if ($userRole === 'admin') {
    // Admin can search Student and Faculty user profiles
    $students = $db->fetchAll("SELECT id, first_name, last_name, email FROM students WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? LIMIT 3", ["%$query%", "%$query%", "%$query%"]);
    foreach ($students as $st) {
        $results[] = [
            'type'     => 'student',
            'title'    => trim($st['first_name'] . ' ' . $st['last_name']),
            'desc'     => 'Student Profile • ' . $st['email'],
            'url'      => BASE_URL . 'admin/students.php',
            'icon'     => 'fa-user-graduate',
            'category' => 'User Records'
        ];
    }

    $faculty = $db->fetchAll("SELECT id, first_name, last_name, email FROM faculty WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? LIMIT 3", ["%$query%", "%$query%", "%$query%"]);
    foreach ($faculty as $f) {
        $results[] = [
            'type'     => 'faculty',
            'title'    => trim($f['first_name'] . ' ' . $f['last_name']),
            'desc'     => 'Faculty Profile • ' . $f['email'],
            'url'      => BASE_URL . 'admin/faculty.php',
            'icon'     => 'fa-chalkboard-user',
            'category' => 'User Records'
        ];
    }
} elseif ($userRole === 'faculty') {
    // Faculty can search department students
    $students = $db->fetchAll("SELECT id, first_name, last_name, email FROM students WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? LIMIT 3", ["%$query%", "%$query%", "%$query%"]);
    foreach ($students as $st) {
        $results[] = [
            'type'     => 'student',
            'title'    => trim($st['first_name'] . ' ' . $st['last_name']),
            'desc'     => 'Student Record • ' . $st['email'],
            'url'      => BASE_URL . 'faculty/students.php',
            'icon'     => 'fa-users',
            'category' => 'Students'
        ];
    }
}

echo json_encode($results);
