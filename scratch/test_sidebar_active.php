<?php
session_start();

// Mock Session & Database for test execution
$_SESSION['user_id'] = 7;
$_SESSION['profile_id'] = 1;
$_SESSION['user_name'] = 'Test Student';
$_SESSION['user_role'] = 'student';

// Define BASE_URL if not defined
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Skill-Gap-Analysis-System/');
}

$pagesToTest = [
    ['role' => 'student', 'script' => '/Skill-Gap-Analysis-System/student/courses.php', 'expectedTitle' => 'Courses & Recommendations'],
    ['role' => 'student', 'script' => '/Skill-Gap-Analysis-System/student/dashboard.php', 'expectedTitle' => 'Dashboard'],
    ['role' => 'student', 'script' => '/Skill-Gap-Analysis-System/student/assessments.php', 'expectedTitle' => 'Assessments'],
    ['role' => 'student', 'script' => '/Skill-Gap-Analysis-System/student/notification.php', 'expectedTitle' => 'Notifications'],
    ['role' => 'student', 'script' => '/Skill-Gap-Analysis-System/student/progress.php', 'expectedTitle' => 'Learning Progress'],
    ['role' => 'student', 'script' => '/Skill-Gap-Analysis-System/student/profile.php', 'expectedTitle' => 'My Profile'],
    ['role' => 'admin',   'script' => '/Skill-Gap-Analysis-System/admin/courses.php', 'expectedTitle' => 'Manage Courses'],
    ['role' => 'faculty', 'script' => '/Skill-Gap-Analysis-System/faculty/assessments.php', 'expectedTitle' => 'Manage Assessments'],
];

echo "=== TESTING SIDEBAR ACTIVE STATE RESOLUTION ===\n\n";
$allPassed = true;

foreach ($pagesToTest as $test) {
    $_SESSION['user_role'] = $test['role'];
    $_SERVER['SCRIPT_NAME'] = $test['script'];
    $_SERVER['PHP_SELF'] = $test['script'];

    ob_start();
    include __DIR__ . '/../includes/sidebar.php';
    $html = ob_get_clean();

    // Check how many items have 'active' class
    preg_match_all('/class="sidebar-nav-item[^"]*active[^"]*"[^>]*title="([^"]+)"/', $html, $matches);
    $activeItems = $matches[1] ?? [];

    // Check if '1' bug class is present
    $hasBugClass = (strpos($html, 'class="sidebar-nav-item 1"') !== false);

    echo "Role: " . str_pad($test['role'], 8) . " | Path: " . str_pad($test['script'], 50);
    if (count($activeItems) === 1 && $activeItems[0] === $test['expectedTitle'] && !$hasBugClass) {
        echo " -> PASSED (Active: '{$activeItems[0]}')\n";
    } else {
        echo " -> FAILED (Active: " . json_encode($activeItems) . ", HasBugClass: " . ($hasBugClass ? 'YES' : 'NO') . ")\n";
        $allPassed = false;
    }
}

if ($allPassed) {
    echo "\nALL TESTS PASSED SUCCESSFULLY! The active state bug is 100% resolved.\n";
} else {
    echo "\nSOME TESTS FAILED. Please review output.\n";
    exit(1);
}
