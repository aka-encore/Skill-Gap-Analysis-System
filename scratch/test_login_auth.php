<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance();

echo "========================================================\n";
echo "LOGIN AUTHENTICATION BACKEND AUDIT\n";
echo "========================================================\n\n";

// Test Student User (Encore ABJ)
$studentUser = $db->fetch("SELECT * FROM users WHERE username = 'encore' OR email = 'encore@gmail.com'");
if ($studentUser) {
    echo "1. Student User Found: ID={$studentUser['id']}, Username={$studentUser['username']}, Role={$studentUser['role']}\n";
    if (password_verify('student123', $studentUser['password'])) {
        echo "   -> Password Hash Verification: PASSED (password_verify matched)\n";
    } else {
        echo "   -> Password Hash Verification: FAILED\n";
    }
} else {
    echo "1. Student User: NOT FOUND\n";
}

// Test Admin User
$adminUser = $db->fetch("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
if ($adminUser) {
    echo "2. Admin User Found: ID={$adminUser['id']}, Username={$adminUser['username']}, Role={$adminUser['role']}\n";
    if (password_verify('admin123', $adminUser['password'])) {
        echo "   -> Password Hash Verification: PASSED (password_verify matched)\n";
    }
}

echo "\n========================================================\n";
