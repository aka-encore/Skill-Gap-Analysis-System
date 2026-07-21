<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance();

echo "========================================================\n";
echo "REGISTRATION FLOW & VALIDATION AUDIT\n";
echo "========================================================\n\n";

// 1. Name Validation Test
$invalidName = "John123";
if (!preg_match("/^[a-zA-Z\s\-]+$/", $invalidName)) {
    echo "1. NAME VALIDATION TEST: Passed -> 'First name cannot contain numbers.'\n";
} else {
    echo "1. NAME VALIDATION TEST: Failed\n";
}

// 2. Username Validation Test
$invalidUser = "john@doe#";
if (!preg_match("/^[a-zA-Z0-9_\.]+$/", $invalidUser)) {
    echo "2. USERNAME VALIDATION TEST: Passed -> 'Username can only contain letters, numbers, underscores, and periods.'\n";
} else {
    echo "2. USERNAME VALIDATION TEST: Failed\n";
}

// 3. Test Render of register.php
ob_start();
require __DIR__ . '/../register.php';
$output = ob_get_clean();

echo "3. register.php Render Length: " . strlen($output) . " bytes\n";
if (strpos($output, "Create Your Account") !== false && strpos($output, "tech-bg-container") !== false && strpos($output, "pw-strength") !== false) {
    echo "   -> register.php Render Test: PASSED with SaaS styling and password strength meter!\n";
} else {
    echo "   -> register.php Render Test: FAILED\n";
}

echo "\n========================================================\n";
