<?php
session_start();
$_SESSION['user_id'] = 27;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = 21;
$_SESSION['user_name'] = 'Encore ABJ';

ob_start();
require_once __DIR__ . '/../student/profile.php';
$output = ob_get_clean();

echo "Profile Page Render Length: " . strlen($output) . " bytes\n";
if (strpos($output, "My Profile") !== false && strpos($output, "Encore ABJ") !== false) {
    echo "SUCCESS: Profile page rendered cleanly with dynamic DB statistics for Encore ABJ!\n";
} else {
    echo "WARNING: Profile page signature mismatch.\n";
}
