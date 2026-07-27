<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

echo "=== USERS TABLE (faculty/admin roles) ===\n";
$users = $db->fetchAll("SELECT id, username, email, role FROM users WHERE role IN ('faculty','admin') ORDER BY role, id LIMIT 20");
foreach ($users as $u) {
    echo "[{$u['role']}] id={$u['id']} username={$u['username']} email={$u['email']}\n";
}

echo "\n=== FACULTY PROFILE -> USER MAPPING ===\n";
$fac = $db->fetchAll("SELECT f.id as faculty_id, f.first_name, f.last_name, f.user_id, u.username FROM faculty f JOIN users u ON f.user_id = u.id ORDER BY f.id");
foreach ($fac as $f) {
    echo "faculty.id={$f['faculty_id']} name={$f['first_name']} {$f['last_name']} -> user_id={$f['user_id']} ({$f['username']})\n";
}

echo "\n=== ASSESSMENTS per faculty_id vs faculty ===\n";
$ass = $db->fetchAll("SELECT created_by_faculty_id, COUNT(*) as cnt FROM assessments GROUP BY created_by_faculty_id ORDER BY created_by_faculty_id");
foreach ($ass as $a) {
    echo "created_by_faculty_id={$a['created_by_faculty_id']} -> {$a['cnt']} assessments\n";
}

echo "\n=== ADMINS TABLE ===\n";
$admins = $db->fetchAll("SELECT a.id, a.first_name, a.last_name, a.user_id, u.username FROM admins a JOIN users u ON a.user_id = u.id LIMIT 10");
print_r($admins);

echo "\n=== ADMIN ANALYTICS DATA ===\n";
$analytics = $db->fetch("SELECT COUNT(*) as cnt FROM assessments");
echo "Total assessments: {$analytics['cnt']}\n";

echo "\n=== ADMIN REPORTS DATA ===\n";
$reports = $db->fetchAll("SELECT a.title, f.first_name, f.last_name, 
    (SELECT COUNT(*) FROM assessment_results ar WHERE ar.assessment_id = a.id) as sub_count,
    (SELECT AVG(ar.score_percentage) FROM assessment_results ar WHERE ar.assessment_id = a.id) as avg_score
FROM assessments a 
JOIN faculty f ON a.created_by_faculty_id = f.id 
LIMIT 5");
print_r($reports);

echo "\n=== ACTIVITY LOGS TABLE (check if it exists and has data) ===\n";
try {
    $logs = $db->fetchAll("SELECT id, user_id, action FROM activity_logs ORDER BY created_at DESC LIMIT 5");
    echo "Logs count: " . count($logs) . "\n";
    print_r($logs);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
