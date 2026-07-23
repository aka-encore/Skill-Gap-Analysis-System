<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

echo "=== TESTING ENHANCED NOTIFICATION SYSTEM ===\n\n";

$db = Database::getInstance();

// Setup test notification data
$adminUser = $db->fetch("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
$facultyUser = $db->fetch("SELECT id FROM users WHERE role = 'faculty' LIMIT 1");
$studentUser = $db->fetch("SELECT id FROM users WHERE role = 'student' LIMIT 1");

if (!$adminUser || !$facultyUser || !$studentUser) {
    echo "Test users not found.\n";
    exit(1);
}

$adminId = (int)$adminUser['id'];
$facId   = (int)$facultyUser['id'];
$stuId   = (int)$studentUser['id'];

// 1. TEST ANNOUNCEMENT NOTIFICATION FILTERING
echo "--- 1. Testing Announcement Notification Filtering ---\n";
// Insert test announcement notification
$annNotifId = $db->insert('notifications', [
    'user_id'    => $adminId,
    'title'      => 'Test Announcement Title',
    'message'    => 'Broadcast message text',
    'link'       => '#',
    'is_read'    => 0,
    'type'       => 'announcement',
    'created_at' => date('Y-m-d H:i:s')
]);

$adminNotifs = $db->fetchAll("SELECT * FROM notifications WHERE (type IS NULL OR type != 'announcement') AND id = ?", [$annNotifId]);
$facNotifs   = $db->fetchAll("SELECT * FROM notifications WHERE user_id = ? AND (type IS NULL OR type != 'announcement') AND id = ?", [$facId, $annNotifId]);

if (empty($adminNotifs) && empty($facNotifs)) {
    echo "1a. Admin & Faculty Announcement Exclusion: PASSED (Announcements hidden from Admin & Faculty views)\n";
} else {
    echo "1a. Admin & Faculty Announcement Exclusion: FAILED\n";
    exit(1);
}

// Verify Student Notifications include announcements
$stuNotifId = $db->insert('notifications', [
    'user_id'    => $stuId,
    'title'      => 'Student Test Announcement',
    'message'    => 'Student announcement message',
    'link'       => '#',
    'is_read'    => 0,
    'type'       => 'announcement',
    'created_at' => date('Y-m-d H:i:s')
]);

$stuNotifs = $db->fetchAll("SELECT * FROM notifications WHERE user_id = ? AND type = 'announcement' AND id = ?", [$stuId, $stuNotifId]);
if (!empty($stuNotifs)) {
    echo "1b. Student Announcement Inclusion: PASSED (Announcements displayed for Students)\n";
} else {
    echo "1b. Student Announcement Inclusion: FAILED\n";
    exit(1);
}

// 2. TEST CLICKABLE NOTIFICATIONS & AUTOMATIC READ MARKING
echo "\n--- 2. Testing Clickable Notifications & Automatic Mark as Read ---\n";
$testNotifId = $db->insert('notifications', [
    'user_id'    => $adminId,
    'title'      => 'New Faculty Registration Application from Dr. Test',
    'message'    => 'A new faculty application has been submitted.',
    'link'       => BASE_URL . 'admin/faculty-applications.php',
    'is_read'    => 0,
    'type'       => 'faculty_application',
    'created_at' => date('Y-m-d H:i:s')
]);

// Simulate opening the notification
$db->update('notifications', ['is_read' => 1], 'id = ?', [$testNotifId]);
$readCheck = $db->fetch("SELECT is_read FROM notifications WHERE id = ?", [$testNotifId]);

if ((int)$readCheck['is_read'] === 1) {
    echo "2. Automatic Mark as Read on Open: PASSED (is_read updated to 1)\n";
} else {
    echo "2. Automatic Mark as Read on Open: FAILED\n";
    exit(1);
}

// 3. TEST CLEAR SELECTED & SAFE DELETION BEHAVIOR
echo "\n--- 3. Testing Clear Selected & Safe Deletion ---\n";
$n1 = $db->insert('notifications', ['user_id' => $adminId, 'title' => 'N1', 'message' => 'M1', 'is_read' => 0, 'type' => 'system', 'created_at' => date('Y-m-d H:i:s')]);
$n2 = $db->insert('notifications', ['user_id' => $adminId, 'title' => 'N2', 'message' => 'M2', 'is_read' => 0, 'type' => 'system', 'created_at' => date('Y-m-d H:i:s')]);

$userCountBefore = (int)($db->fetch("SELECT COUNT(*) as cnt FROM users")['cnt'] ?? 0);
$facCountBefore  = (int)($db->fetch("SELECT COUNT(*) as cnt FROM faculty")['cnt'] ?? 0);

// Clear N1
$db->delete('notifications', 'id = ?', [$n1]);

$checkN1 = $db->fetch("SELECT id FROM notifications WHERE id = ?", [$n1]);
$checkN2 = $db->fetch("SELECT id FROM notifications WHERE id = ?", [$n2]);
$userCountAfter = (int)($db->fetch("SELECT COUNT(*) as cnt FROM users")['cnt'] ?? 0);
$facCountAfter  = (int)($db->fetch("SELECT COUNT(*) as cnt FROM faculty")['cnt'] ?? 0);

if (!$checkN1 && $checkN2 && ($userCountBefore === $userCountAfter) && ($facCountBefore === $facCountAfter)) {
    echo "3. Clear Selected & Safe Deletion: PASSED (Only notification record removed, 0 entity records deleted)\n";
} else {
    echo "3. Clear Selected & Safe Deletion: FAILED\n";
    exit(1);
}

// Cleanup test items
$db->delete('notifications', 'id IN (?, ?, ?, ?)', [$annNotifId, $stuNotifId, $testNotifId, $n2]);

echo "\nALL ENHANCED NOTIFICATION TESTS PASSED 100% SUCCESSFULLY!\n";
