<?php
session_start();

// Test Student Help Page
$_SESSION['user_id'] = 27;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = 21;
$_SESSION['user_name'] = 'Encore ABJ';

ob_start();
require_once __DIR__ . '/../student/help.php';
$studentOut = ob_get_clean();

echo "Student Help Page Length: " . strlen($studentOut) . " bytes\n";
if (strpos($studentOut, "Help & Support Center") !== false) {
    echo "SUCCESS: Student Help page rendered cleanly!\n";
} else {
    echo "WARNING: Student Help signature mismatch.\n";
}

// Test Faculty Help Page
$_SESSION['user_id'] = 2;
$_SESSION['user_role'] = 'faculty';
$_SESSION['profile_id'] = 1;
$_SESSION['user_name'] = 'Prof. Sarah Connor';

ob_start();
require_once __DIR__ . '/../faculty/help.php';
$facultyOut = ob_get_clean();

echo "Faculty Help Page Length: " . strlen($facultyOut) . " bytes\n";
if (strpos($facultyOut, "Faculty Support Center") !== false) {
    echo "SUCCESS: Faculty Help page rendered cleanly!\n";
} else {
    echo "WARNING: Faculty Help signature mismatch.\n";
}
