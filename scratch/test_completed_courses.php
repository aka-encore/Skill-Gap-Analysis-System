<?php
session_start();

$_SESSION['user_id'] = 7;
$_SESSION['profile_id'] = 1;
$_SESSION['user_role'] = 'student';
$_SESSION['user_name'] = 'Test Student';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/Skill-Gap-Analysis-System/');
}

ob_start();
include __DIR__ . '/../student/courses.php';
$html = ob_get_clean();

echo "=== TESTING COMPLETED COURSES BUTTON & TAB FUNCTIONALITY ===\n\n";

$hasAllBtn = (strpos($html, 'id="tab-all"') !== false);
$hasEnrolledBtn = (strpos($html, 'id="tab-enrolled"') !== false);
$hasCompletedBtn = (strpos($html, 'id="tab-completed"') !== false);
$hasCompletedJSVar = (strpos($html, 'const COMPLETED_COURSES =') !== false);
$hasCompletedTabJS = (strpos($html, "switchCourseTab('completed')") !== false);

echo "All Courses Button:       " . ($hasAllBtn ? "PASSED" : "FAILED") . "\n";
echo "Enrolled Courses Button:  " . ($hasEnrolledBtn ? "PASSED" : "FAILED") . "\n";
echo "Completed Courses Button: " . ($hasCompletedBtn ? "PASSED" : "FAILED") . "\n";
echo "Completed Courses JS Var: " . ($hasCompletedJSVar ? "PASSED" : "FAILED") . "\n";
echo "Completed Tab JS Logic:   " . ($hasCompletedTabJS ? "PASSED" : "FAILED") . "\n";

if ($hasAllBtn && $hasEnrolledBtn && $hasCompletedBtn && $hasCompletedJSVar && $hasCompletedTabJS) {
    echo "\nSUCCESS: Completed Courses button and tab functionality passed all checks!\n";
} else {
    echo "\nFAILURE: Missing required components.\n";
    exit(1);
}
