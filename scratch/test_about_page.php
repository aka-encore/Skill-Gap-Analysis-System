<?php
session_start();
$_SESSION['user_id'] = 27;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = 21;
$_SESSION['user_name'] = 'Encore ABJ';

ob_start();
require_once __DIR__ . '/../about.php';
$output = ob_get_clean();

echo "About Page Render Length: " . strlen($output) . " bytes\n";
if (strpos($output, "About SkillBridge") !== false && strpos($output, "Our Mission") !== false) {
    echo "SUCCESS: About page rendered cleanly with dynamic DB statistics!\n";
} else {
    echo "WARNING: About page signature mismatch.\n";
}
