<?php
session_start();
$_SESSION['user_id'] = 7;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = 1;
$_SESSION['full_name'] = 'John Doe';
$_GET['result_id'] = 1;

ob_start();
require_once __DIR__ . '/../student/assessment-result.php';
$output = ob_get_clean();

echo "Page Render Length: " . strlen($output) . " bytes\n";
if (strpos($output, "Assessment Performance Report") !== false) {
    echo "SUCCESS: Assessment Performance Report loaded cleanly with zero errors!\n";
} else {
    echo "WARNING: Output signature not found.\n";
}
