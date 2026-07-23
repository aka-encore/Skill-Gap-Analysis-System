<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

echo "=== TESTING ADMIN FORGOT PASSWORD PRODUCTION SMTP FLOW ===\n\n";

$db = Database::getInstance();

// 1. Fetch or create test Admin account
$adminEmail = 'sudrikyash1@gmail.com';
$adminUser = $db->fetch("SELECT * FROM users WHERE email = ?", [$adminEmail]);

if (!$adminUser) {
    echo "Creating test admin user...\n";
    $db->insert('users', [
        'username' => 'testadmin',
        'email' => $adminEmail,
        'password' => password_hash('AdminPass123!', PASSWORD_DEFAULT),
        'role' => 'admin',
        'full_name' => 'System Admin'
    ]);
    $adminUser = $db->fetch("SELECT * FROM users WHERE email = ?", [$adminEmail]);
}

echo "1. Target Admin Account: ID #{$adminUser['id']} ({$adminUser['email']})\n";

// 2. Generate secure token & expiry
$token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', time() + 1800);

$db->update('users', [
    'reset_token' => $token,
    'reset_token_expiry' => $expires
], 'id = ?', [$adminUser['id']]);

$updatedUser = $db->fetch("SELECT * FROM users WHERE id = ?", [$adminUser['id']]);
if ($updatedUser['reset_token'] === $token && !empty($updatedUser['reset_token_expiry'])) {
    echo "2. Token Storage in DB: PASSED (Token: {$token})\n";
} else {
    echo "2. Token Storage in DB: FAILED\n";
    exit(1);
}

// 3. Dispatch production SMTP email
$resetLink = BASE_URL . "reset-password.php?token=" . $token;
echo "3. Sending SMTP Email to {$adminEmail} via PHPMailer...\n";
$mailRes = send_password_reset_email($adminEmail, $resetLink);

if ($mailRes['success']) {
    echo "   -> SMTP Delivery Result: PASSED ('{$mailRes['message']}')\n";
} else {
    echo "   -> SMTP Delivery Result: FAILED ('{$mailRes['message']}')\n";
    exit(1);
}

// 4. Verify Local Testing Mode is completely absent from HTML output
$_SERVER['REQUEST_METHOD'] = 'GET';
ob_start();
include __DIR__ . '/../forgot-password.php';
$html = ob_get_clean();

$hasLocalTesting = (strpos($html, 'Local Testing Mode') !== false || strpos($html, 'Click Here to Reset Password') !== false);
if (!$hasLocalTesting) {
    echo "4. Local Testing Mode Check: PASSED (No bypass links exposed)\n";
} else {
    echo "4. Local Testing Mode Check: FAILED (Testing links found in HTML!)\n";
    exit(1);
}

// 5. Test Token Validation on reset-password.php
$_GET['token'] = $token;
$_SERVER['REQUEST_METHOD'] = 'GET';
ob_start();
include __DIR__ . '/../reset-password.php';
$resetHtml = ob_get_clean();

if (strpos($resetHtml, 'name="password"') !== false) {
    echo "5. Reset Password Form Validation: PASSED (Valid token accepted)\n";
} else {
    echo "5. Reset Password Form Validation: FAILED\n";
    exit(1);
}

echo "\nALL ADMIN FORGOT PASSWORD TESTS PASSED 100% SUCCESSFULLY!\n";
