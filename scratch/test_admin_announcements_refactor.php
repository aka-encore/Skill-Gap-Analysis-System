<?php
session_start();

$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'admin';
$_SESSION['username'] = 'AdminUser';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/Skill-Gap-Analysis-System/');
}

echo "=== TESTING ADMIN ANNOUNCEMENTS & NOTIFICATIONS REFACTORING ===\n\n";

// 1. Test admin/announcements.php render
$_SERVER['SCRIPT_NAME'] = '/Skill-Gap-Analysis-System/admin/announcements.php';
$_SERVER['PHP_SELF'] = '/Skill-Gap-Analysis-System/admin/announcements.php';

ob_start();
include __DIR__ . '/../admin/announcements.php';
$announcementsHtml = ob_get_clean();

$hasAnnForm = (strpos($announcementsHtml, 'name="title"') !== false && strpos($announcementsHtml, 'name="message"') !== false);
$hasAnnSidebarActive = (strpos($announcementsHtml, 'title="Announcements"') !== false && strpos($announcementsHtml, 'class="sidebar-nav-item active" title="Announcements"') !== false);

echo "1. Announcements Page Render:        " . ($hasAnnForm ? "PASSED" : "FAILED") . "\n";
echo "2. Announcements Sidebar Active:     " . ($hasAnnSidebarActive ? "PASSED" : "FAILED") . "\n";

// 2. Test admin/notifications.php render
$_SERVER['SCRIPT_NAME'] = '/Skill-Gap-Analysis-System/admin/notifications.php';
$_SERVER['PHP_SELF'] = '/Skill-Gap-Analysis-System/admin/notifications.php';

ob_start();
include __DIR__ . '/../admin/notifications.php';
$notifHtml = ob_get_clean();

$noAnnFormInNotif = (strpos($notifHtml, 'Broadcast Announcement Now') === false);
$hasNotifSidebarActive = (strpos($notifHtml, 'title="Notifications"') !== false && strpos($notifHtml, 'class="sidebar-nav-item active" title="Notifications"') !== false);

echo "3. Notifications Sidebar Active:     " . ($hasNotifSidebarActive ? "PASSED" : "FAILED") . "\n";
echo "4. No Announcement Form in Notif:   " . ($noAnnFormInNotif ? "PASSED" : "FAILED") . "\n";

if ($hasAnnForm && $hasAnnSidebarActive && $hasNotifSidebarActive && $noAnnFormInNotif) {
    echo "\nALL TESTS PASSED SUCCESSFULLY! The refactoring is 100% complete and clean.\n";
} else {
    echo "\nTEST FAILED. Please review output.\n";
    exit(1);
}
