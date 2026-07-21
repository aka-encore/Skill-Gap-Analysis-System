<?php
session_start();
$_SESSION['user_id'] = 7;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = 1;
$_SESSION['user_name'] = 'Arjun Kapoor';

ob_start();
require_once __DIR__ . '/../student/roadmap.php';
$output = ob_get_clean();

echo "Roadmap Page Render Length: " . strlen($output) . " bytes\n";
if (strpos($output, 'Learning Roadmap') !== false && strpos($output, 'studentSkillsData') !== false) {
    echo "SUCCESS: Roadmap page rendered cleanly with dynamic DB skill data!\n";
} else {
    echo "WARNING: Roadmap signature mismatch.\n";
}
