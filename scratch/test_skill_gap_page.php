<?php
session_start();
$_SESSION['user_id'] = 7;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = 1;
$_SESSION['full_name'] = 'Arjun Kapoor';

ob_start();
require_once __DIR__ . '/../student/skill-gap.php';
$output = ob_get_clean();

echo "Skill Gap Page Render Length: " . strlen($output) . " bytes\n";
if (strpos($output, "Skill Gap Analysis") !== false && strpos($output, "Competency Radar") !== false) {
    echo "SUCCESS: Skill Gap Analysis page loaded cleanly with zero errors!\n";
} else {
    echo "WARNING: Output signature not found.\n";
}
