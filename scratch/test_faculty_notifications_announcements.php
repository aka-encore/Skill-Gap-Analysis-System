<?php
session_start();

$_SESSION['user_id'] = 2;
$_SESSION['user_role'] = 'faculty';
$_SESSION['profile_id'] = 1;
$_SESSION['user_name'] = 'Dr. Ramesh Kumar';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/Skill-Gap-Analysis-System/');
}

echo "=== TESTING FACULTY NOTIFICATIONS & ANNOUNCEMENTS MODULE ===\n\n";

// 1. Test faculty/notifications.php render
$_SERVER['SCRIPT_NAME'] = '/Skill-Gap-Analysis-System/faculty/notifications.php';
$_SERVER['PHP_SELF'] = '/Skill-Gap-Analysis-System/faculty/notifications.php';

ob_start();
include __DIR__ . '/../faculty/notifications.php';
$notifHtml = ob_get_clean();

$hasNotifSidebarActive = (strpos($notifHtml, 'title="Notifications"') !== false && strpos($notifHtml, 'class="sidebar-nav-item active" title="Notifications"') !== false);
$hasNotifHeading = (strpos($notifHtml, 'Notifications') !== false);

echo "1. Faculty Notifications Page Render: " . ($hasNotifHeading ? "PASSED" : "FAILED") . "\n";
echo "2. Faculty Notifications Active:     " . ($hasNotifSidebarActive ? "PASSED" : "FAILED") . "\n";

// 2. Test faculty/announcements.php render
$_SERVER['SCRIPT_NAME'] = '/Skill-Gap-Analysis-System/faculty/announcements.php';
$_SERVER['PHP_SELF'] = '/Skill-Gap-Analysis-System/faculty/announcements.php';

ob_start();
include __DIR__ . '/../faculty/announcements.php';
$annHtml = ob_get_clean();

$hasAnnSidebarActive = (strpos($annHtml, 'title="Announcements"') !== false && strpos($annHtml, 'class="sidebar-nav-item active" title="Announcements"') !== false);
$hasReadOnlyBadge = (strpos($annHtml, 'Read-Only View') !== false);
$noBroadcastForm = (strpos($annHtml, 'Broadcast Announcement Now') === false && strpos($annHtml, '<form') === false);

echo "3. Faculty Announcements Active:     " . ($hasAnnSidebarActive ? "PASSED" : "FAILED") . "\n";
echo "4. Read-Only Badge Present:         " . ($hasReadOnlyBadge ? "PASSED" : "FAILED") . "\n";
echo "5. No Broadcast Form (Read-Only):    " . ($noBroadcastForm ? "PASSED" : "FAILED") . "\n";

if ($hasNotifHeading && $hasNotifSidebarActive && $hasAnnSidebarActive && $hasReadOnlyBadge && $noBroadcastForm) {
    echo "\nALL FACULTY NOTIFICATION & ANNOUNCEMENT TESTS PASSED SUCCESSFULLY!\n";
} else {
    echo "\nTEST FAILED. Please review output.\n";
    exit(1);
}
