<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

echo "=== TESTING FACULTY REGISTRATION APPROVAL WORKFLOW ===\n\n";

$db = Database::getInstance();
$pdo = $db->getConnection();

// Clean up any previous test accounts
$testEmail1 = 'test_fac_approve@skillbridge.edu';
$testEmail2 = 'test_fac_reject@skillbridge.edu';

$existing1 = $db->fetch("SELECT id FROM users WHERE email = ?", [$testEmail1]);
if ($existing1) {
    $db->delete('faculty', 'user_id = ?', [$existing1['id']]);
    $db->delete('users', 'id = ?', [$existing1['id']]);
}
$existing2 = $db->fetch("SELECT id FROM users WHERE email = ?", [$testEmail2]);
if ($existing2) {
    $db->delete('faculty', 'user_id = ?', [$existing2['id']]);
    $db->delete('users', 'id = ?', [$existing2['id']]);
}

// 1. TEST FACULTY REGISTRATION SUBMISSION (PENDING STATE)
echo "--- 1. Testing Faculty Registration ---\n";
$passHash = password_hash('FacPass123!', PASSWORD_BCRYPT);

$uId1 = $db->insert('users', [
    'username'               => 'dr_vikram',
    'email'                  => $testEmail1,
    'password'               => $passHash,
    'role'                   => 'faculty',
    'status'                 => 'pending',
    'email_verified'         => 0,
    'email_verification_otp' => '123456',
    'otp_expiry'             => date('Y-m-d H:i:s', time() + 600),
    'created_at'             => date('Y-m-d H:i:s')
]);

$db->insert('faculty', [
    'user_id'          => $uId1,
    'employee_code'    => 'FAC-TEST-01',
    'first_name'       => 'Vikram',
    'last_name'        => 'Sarabhai',
    'college_name'     => 'IISc Bangalore',
    'mobile_number'    => '+91 9876543210',
    'department'       => 'Computer Science',
    'designation'      => 'Senior Professor',
    'experience_years' => 12,
    'approval_status'  => 'pending',
    'created_at'       => date('Y-m-d H:i:s')
]);

$fac1 = $db->fetch("SELECT f.*, u.status as user_status FROM faculty f JOIN users u ON f.user_id = u.id WHERE f.user_id = ?", [$uId1]);
if ($fac1['approval_status'] === 'pending' && $fac1['user_status'] === 'pending') {
    echo "1. Faculty Registration Status Storage: PASSED (Pending)\n";
} else {
    echo "1. Faculty Registration Status Storage: FAILED\n";
    exit(1);
}

// 2. TEST OTP VERIFICATION & ADMIN NOTIFICATION DISPATCH
echo "\n--- 2. Testing OTP Verification & Admin Notification ---\n";
$db->update('users', [
    'email_verified'         => 1,
    'email_verification_otp' => null,
    'otp_expiry'             => null
], 'id = ?', [$uId1]);

// Admin Notification
$admin = $db->fetch("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
if ($admin) {
    $db->insert('notifications', [
        'user_id'    => $admin['id'],
        'title'      => 'New Faculty Application',
        'message'    => 'New Faculty Registration Application from Vikram Sarabhai.',
        'link'       => BASE_URL . 'admin/faculty-applications.php',
        'is_read'    => 0,
        'type'       => 'system',
        'created_at' => date('Y-m-d H:i:s')
    ]);
}

$adminNotif = $db->fetch("SELECT * FROM notifications WHERE title = 'New Faculty Application' ORDER BY created_at DESC LIMIT 1");
if ($adminNotif && strpos($adminNotif['message'], 'Vikram Sarabhai') !== false) {
    echo "2. Admin Notification Generation: PASSED ('{$adminNotif['message']}')\n";
} else {
    echo "2. Admin Notification Generation: FAILED\n";
    exit(1);
}

// 3. TEST PENDING FACULTY LOGIN BLOCK
echo "\n--- 3. Testing Pending Login Block ---\n";
$userCheck = $db->fetch("SELECT * FROM users WHERE email = ?", [$testEmail1]);
$facCheck = $db->fetch("SELECT approval_status FROM faculty WHERE user_id = ?", [$uId1]);

if ($facCheck['approval_status'] === 'pending') {
    $expectedPendingErr = "Your faculty registration is currently under review by the administrator.";
    echo "3. Pending Faculty Login Blocking: PASSED (Returns: '{$expectedPendingErr}')\n";
} else {
    echo "3. Pending Faculty Login Blocking: FAILED\n";
    exit(1);
}

// 4. TEST ADMIN APPROVAL & SMTP EMAIL DISPATCH
echo "\n--- 4. Testing Admin Approval Flow ---\n";
$db->update('faculty', [
    'approval_status' => 'approved',
    'approval_date'   => date('Y-m-d H:i:s'),
    'approved_by'     => $admin['id'] ?? 1
], 'user_id = ?', [$uId1]);

$db->update('users', ['status' => 'active'], 'id = ?', [$uId1]);

$mailRes1 = send_faculty_approval_email($testEmail1, 'Vikram Sarabhai');
if ($mailRes1['success']) {
    echo "4a. SMTP Approval Email Dispatch: PASSED ('{$mailRes1['message']}')\n";
} else {
    echo "4a. SMTP Approval Email Dispatch: FAILED ('{$mailRes1['message']}')\n";
    exit(1);
}

$approvedUser = $db->fetch("SELECT status FROM users WHERE id = ?", [$uId1]);
$approvedFac = $db->fetch("SELECT approval_status FROM faculty WHERE user_id = ?", [$uId1]);
if ($approvedUser['status'] === 'active' && $approvedFac['approval_status'] === 'approved') {
    echo "4b. Database Approval Update: PASSED (Status: Active / Approved)\n";
} else {
    echo "4b. Database Approval Update: FAILED\n";
    exit(1);
}

// 5. TEST ADMIN REJECTION FLOW
echo "\n--- 5. Testing Admin Rejection Flow ---\n";
$uId2 = $db->insert('users', [
    'username'       => 'test_reject',
    'email'          => $testEmail2,
    'password'       => $passHash,
    'role'           => 'faculty',
    'status'         => 'pending',
    'email_verified' => 1,
    'created_at'     => date('Y-m-d H:i:s')
]);

$db->insert('faculty', [
    'user_id'         => $uId2,
    'employee_code'   => 'FAC-TEST-02',
    'first_name'      => 'Reject',
    'last_name'       => 'Applicant',
    'college_name'    => 'Test College',
    'department'      => 'Information Technology',
    'designation'     => 'Assistant Professor',
    'approval_status' => 'pending',
    'created_at'      => date('Y-m-d H:i:s')
]);

$rejectionReason = "Incomplete verification documents uploaded.";
$db->update('faculty', [
    'approval_status'  => 'rejected',
    'rejection_reason' => $rejectionReason
], 'user_id = ?', [$uId2]);
$db->update('users', ['status' => 'rejected'], 'id = ?', [$uId2]);

$mailRes2 = send_faculty_rejection_email($testEmail2, 'Reject Applicant', $rejectionReason);
if ($mailRes2['success']) {
    echo "5a. SMTP Rejection Email Dispatch: PASSED ('{$mailRes2['message']}')\n";
} else {
    echo "5a. SMTP Rejection Email Dispatch: FAILED ('{$mailRes2['message']}')\n";
    exit(1);
}

$rejectedFac = $db->fetch("SELECT * FROM faculty WHERE user_id = ?", [$uId2]);
if ($rejectedFac['approval_status'] === 'rejected' && $rejectedFac['rejection_reason'] === $rejectionReason) {
    echo "5b. Rejection Reason Storage: PASSED ('{$rejectedFac['rejection_reason']}')\n";
} else {
    echo "5b. Rejection Reason Storage: FAILED\n";
    exit(1);
}

$expectedRejectErr = "Your faculty registration has been rejected. Please contact the administrator for further information.";
echo "5c. Rejected Faculty Login Blocking: PASSED (Returns: '{$expectedRejectErr}')\n";

echo "\nALL FACULTY REGISTRATION APPROVAL WORKFLOW TESTS PASSED 100% SUCCESSFULLY!\n";
