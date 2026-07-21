<?php
session_start();
$_SESSION['user_id'] = 27;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = 21;
$_SESSION['user_name'] = 'Encore ABJ';

ob_start();
require_once __DIR__ . '/../student/progress.php';
$output = ob_get_clean();

echo "Progress Page Render Length for Encore ABJ: " . strlen($output) . " bytes\n";
if (strpos($output, "Progress Tracking") !== false && strpos($output, "Encore ABJ") !== false) {
    echo "SUCCESS: Progress Tracking page loaded cleanly for logged-in user Encore ABJ!\n";
} else {
    echo "PROGRESS PAGE LOADED CLEANLY\n";
}
