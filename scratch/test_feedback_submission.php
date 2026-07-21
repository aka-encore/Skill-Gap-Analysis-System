<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance();
$studentId = 21; // Encore ABJ
$userId = 27;

$_SESSION['user_id'] = $userId;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = $studentId;

echo "========================================================\n";
echo "TESTING FEEDBACK SUBMISSION FOR ENCORE ABJ (user_id = $userId)\n";
echo "========================================================\n\n";

// Insert test feedback entry
$category = "Skill Assessments";
$rating = 5;
$message = "Automated test feedback entry: The 5-tier assessment system is highly effective.";

$db->query(
    "INSERT INTO feedback (user_id, user_role, category, rating, message, status) VALUES (?, 'student', ?, ?, ?, 'pending')",
    [$userId, $category, $rating, $message]
);

$lastId = $db->fetch("SELECT LAST_INSERT_ID() as id")['id'] ?? 0;
echo "Inserted feedback record with ID: $lastId\n";

// Fetch back the record
$fetched = $db->fetch("SELECT * FROM feedback WHERE id = ?", [$lastId]);
if ($fetched && $fetched['category'] === $category && (int)$fetched['rating'] === 5) {
    echo "SUCCESS: Feedback record correctly inserted and verified in database!\n";
} else {
    echo "ERROR: Feedback verification failed.\n";
}
echo "========================================================\n";
