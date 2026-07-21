<?php
session_start();
$_SESSION['user_id'] = 27;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = 21;
$_SESSION['user_name'] = 'Encore ABJ';

ob_start();
require_once __DIR__ . '/../student/settings.php';
$output = ob_get_clean();

echo "Settings Page Render Length: " . strlen($output) . " bytes\n";
if (strpos($output, "Account & Platform Settings") !== false && strpos($output, "Encore ABJ") !== false) {
    echo "SUCCESS: Settings page rendered cleanly with dynamic student session details!\n";
} else {
    echo "WARNING: Settings page signature mismatch.\n";
}
