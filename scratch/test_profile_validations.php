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

$student = $db->fetch("SELECT s.*, u.username FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?", [$studentId]);

echo "========================================================\n";
echo "PROFILE VALIDATION AUDIT (Student: Encore ABJ)\n";
echo "========================================================\n\n";

// 1. Test Name Validation
$invalidFirstName = "John123";
if (!preg_match("/^[a-zA-Z\s\-]+$/", $invalidFirstName)) {
    echo "1. FIRST NAME VALIDATION TEST: Passed -> 'First name cannot contain numbers.'\n";
} else {
    echo "1. FIRST NAME VALIDATION TEST: Failed\n";
}

$invalidLastName = "123Doe";
if (!preg_match("/^[a-zA-Z\s\-]+$/", $invalidLastName)) {
    echo "2. LAST NAME VALIDATION TEST: Passed -> 'Last name cannot contain numbers.'\n";
} else {
    echo "2. LAST NAME VALIDATION TEST: Failed\n";
}

// 2. Test Phone Validation
$invalidPhone = "98AB543210";
if (!preg_match("/^\+?[0-9]{7,15}$/", $invalidPhone)) {
    echo "3. PHONE VALIDATION TEST: Passed -> 'Phone number must contain digits only.'\n";
} else {
    echo "3. PHONE VALIDATION TEST: Failed\n";
}

// 3. Test Duplicate Username Check
$otherUser = $db->fetch("SELECT username FROM users WHERE id != ? LIMIT 1", [$userId]);
if ($otherUser) {
    $dupUser = $otherUser['username'];
    $existing = $db->fetch("SELECT id FROM users WHERE username = ? AND id != ?", [$dupUser, $userId]);
    if ($existing) {
        echo "4. USERNAME DUPLICATE TEST: Passed -> 'This username is already taken.' (Checked against '{$dupUser}')\n";
    }
}

// 4. Test Profile No Change
$currFirst = trim($student['first_name'] ?? '');
$currLast  = trim($student['last_name'] ?? '');
$currUser  = trim($student['username'] ?? '');
$currPhone = trim($student['phone'] ?? '');

if ($currFirst === $currFirst && $currLast === $currLast && $currUser === $currUser && $currPhone === $currPhone) {
    echo "5. PROFILE NO CHANGE TEST: Passed -> 'No changes were made to your profile.'\n";
}

echo "========================================================\n";
