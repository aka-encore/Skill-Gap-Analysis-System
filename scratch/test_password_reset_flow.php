<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

echo "========================================================\n";
echo "PASSWORD RESET FLOW & RENDERING AUDIT\n";
echo "========================================================\n\n";

// Test Token Generation
$email = "admin@skillbridge.edu";
$user = $db->fetch("SELECT * FROM users WHERE email = ?", [$email]);

if ($user) {
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600);
    
    $db->delete('password_resets', 'email = ?', [$email]);
    $db->insert('password_resets', [
        'email' => $email,
        'token' => $token,
        'expires_at' => $expires,
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
    echo "1. Token Generation for {$email}: PASSED (Token: " . substr($token, 0, 16) . "...)\n";

    // Test Reset Page Rendering with Token
    $_GET['token'] = $token;
    ob_start();
    require __DIR__ . '/../reset-password.php';
    $output = ob_get_clean();

    echo "2. reset-password.php Render Length: " . strlen($output) . " bytes\n";
    if (strpos($output, "Set New Password") !== false && strpos($output, "tech-bg-container") !== false && strpos($output, "btn-saas-primary") !== false) {
        echo "   -> reset-password.php Render Test: PASSED with SaaS styling!\n";
    } else {
        echo "   -> reset-password.php Render Test: FAILED\n";
    }
} else {
    echo "1. Admin user not found.\n";
}

echo "\n========================================================\n";
