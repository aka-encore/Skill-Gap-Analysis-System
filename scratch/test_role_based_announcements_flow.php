<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

echo "=== TESTING ROLE-BASED ANNOUNCEMENTS & AUTOMATIC NOTIFICATIONS ===\n\n";

$db = Database::getInstance();

// Setup test users if needed
$admin = $db->fetch("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
$faculty1 = $db->fetch("SELECT * FROM users WHERE role = 'faculty' LIMIT 1");
$faculty2 = $db->fetch("SELECT * FROM users WHERE role = 'faculty' AND id != ? LIMIT 1", [$faculty1['id'] ?? 0]);

if (!$admin || !$faculty1) {
    echo "Required admin/faculty users not found in DB.\n";
    exit(1);
}

$adminId = (int)$admin['id'];
$faculty1Id = (int)$faculty1['id'];
$faculty2Id = $faculty2 ? (int)$faculty2['id'] : 0;

echo "Test Users: Admin ID #{$adminId}, Faculty1 ID #{$faculty1Id}" . ($faculty2Id ? ", Faculty2 ID #{$faculty2Id}" : "") . "\n\n";

// TEST 1: Admin Creates Announcement -> Verify Automatic Notification Dispatch Excluding Creator
echo "--- TEST 1: Admin Creates Announcement ---\n";
$annTitle = "Server Maintenance Test " . time();
$annMsg = "The system will be down for 15 minutes tonight.";

$res1 = create_announcement($adminId, $annTitle, $annMsg, 'all', 'high', '#');
if ($res1['success']) {
    echo "1a. Announcement Creation: PASSED (ID #{$res1['announcement_id']})\n";
} else {
    echo "1a. Announcement Creation: FAILED ('{$res1['message']}')\n";
    exit(1);
}

$annId1 = $res1['announcement_id'];
$annRecord1 = $db->fetch("SELECT * FROM announcements WHERE id = ?", [$annId1]);
if ($annRecord1['created_by_user_id'] == $adminId && $annRecord1['created_by_role'] == 'admin') {
    echo "1b. Ownership Storage: PASSED (Created By User ID: {$annRecord1['created_by_user_id']}, Role: {$annRecord1['created_by_role']})\n";
} else {
    echo "1b. Ownership Storage: FAILED\n";
    exit(1);
}

// Verify Creator (Admin #1) received NO notification for own announcement
$creatorNotif = $db->fetch("SELECT * FROM notifications WHERE announcement_id = ? AND user_id = ?", [$annId1, $adminId]);
if (!$creatorNotif) {
    echo "1c. Exclude Creator Notification Rule: PASSED (Admin #{$adminId} was NOT notified for own announcement)\n";
} else {
    echo "1c. Exclude Creator Notification Rule: FAILED (Creator received notification!)\n";
    exit(1);
}

// Verify non-creator users received notification
$otherNotifs = $db->fetchAll("SELECT * FROM notifications WHERE announcement_id = ? AND user_id != ?", [$annId1, $adminId]);
if (!empty($otherNotifs)) {
    echo "1d. Audience Notification Dispatch: PASSED (" . count($otherNotifs) . " recipients notified)\n";
    $sampleNotif = $otherNotifs[0];
    echo "   Sample Message: '{$sampleNotif['message']}'\n";
} else {
    echo "1d. Audience Notification Dispatch: FAILED\n";
    exit(1);
}

// TEST 2: Faculty Creates Announcement -> Verify Exclude Creator Rule & Audience Handling
echo "\n--- TEST 2: Faculty Creates Announcement ---\n";
$facTitle = "Faculty Exam Review " . time();
$facMsg = "Exam review slides are posted on the portal.";

$res2 = create_announcement($faculty1Id, $facTitle, $facMsg, 'student', 'normal', '#');
if ($res2['success']) {
    echo "2a. Faculty Announcement Creation: PASSED (ID #{$res2['announcement_id']})\n";
} else {
    echo "2a. Faculty Announcement Creation: FAILED ('{$res2['message']}')\n";
    exit(1);
}

$annId2 = $res2['announcement_id'];
$facCreatorNotif = $db->fetch("SELECT * FROM notifications WHERE announcement_id = ? AND user_id = ?", [$annId2, $faculty1Id]);
if (!$facCreatorNotif) {
    echo "2b. Exclude Faculty Creator Rule: PASSED (Faculty #{$faculty1Id} was NOT notified for own announcement)\n";
} else {
    echo "2b. Exclude Faculty Creator Rule: FAILED\n";
    exit(1);
}

// TEST 3: Role-Based Ownership & Edit/Delete Permission Validation
echo "\n--- TEST 3: Ownership & Permission Checks on Edit & Delete ---\n";

// 3a. Faculty tries to edit Admin's announcement -> Expect DENIED
$editAttemptByFacOnAdmin = update_announcement($annId1, $faculty1Id, 'faculty', 'Hacked Title', 'Hacked Msg', 'all', 'high');
if (!$editAttemptByFacOnAdmin['success'] && strpos($editAttemptByFacOnAdmin['message'], 'Access Denied') !== false) {
    echo "3a. Faculty Edit Admin Announcement Protection: PASSED ('{$editAttemptByFacOnAdmin['message']}')\n";
} else {
    echo "3a. Faculty Edit Admin Announcement Protection: FAILED!\n";
    exit(1);
}

// 3b. Faculty tries to delete Admin's announcement -> Expect DENIED
$deleteAttemptByFacOnAdmin = delete_announcement($annId1, $faculty1Id, 'faculty');
if (!$deleteAttemptByFacOnAdmin['success'] && strpos($deleteAttemptByFacOnAdmin['message'], 'Access Denied') !== false) {
    echo "3b. Faculty Delete Admin Announcement Protection: PASSED ('{$deleteAttemptByFacOnAdmin['message']}')\n";
} else {
    echo "3b. Faculty Delete Admin Announcement Protection: FAILED!\n";
    exit(1);
}

// 3c. Faculty edits own announcement -> Expect ALLOWED
$editOwnFac = update_announcement($annId2, $faculty1Id, 'faculty', $facTitle . " (Updated)", $facMsg, 'student', 'normal');
if ($editOwnFac['success']) {
    echo "3c. Faculty Edit Own Announcement: PASSED ('{$editOwnFac['message']}')\n";
} else {
    echo "3c. Faculty Edit Own Announcement: FAILED\n";
    exit(1);
}

// 3d. Admin edits Faculty's announcement -> Expect ALLOWED (Admin can edit any)
$adminEditFac = update_announcement($annId2, $adminId, 'admin', $facTitle . " (Admin Moderated)", $facMsg, 'student', 'normal');
if ($adminEditFac['success']) {
    echo "3d. Admin Edit Faculty Announcement: PASSED ('{$adminEditFac['message']}')\n";
} else {
    echo "3d. Admin Edit Faculty Announcement: FAILED\n";
    exit(1);
}

// 3e. Admin deletes test announcement -> Expect ALLOWED
$adminDel = delete_announcement($annId1, $adminId, 'admin');
$facDel = delete_announcement($annId2, $faculty1Id, 'faculty');
if ($adminDel['success'] && $facDel['success']) {
    echo "3e. Announcement Deletion: PASSED\n";
} else {
    echo "3e. Announcement Deletion: FAILED\n";
    exit(1);
}

echo "\nALL ROLE-BASED ANNOUNCEMENT & AUTOMATIC NOTIFICATION TESTS PASSED 100% SUCCESSFULLY!\n";
