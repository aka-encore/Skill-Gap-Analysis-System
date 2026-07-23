<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/mail.php';

echo "Testing SMTP email delivery via send_password_reset_email...\n";

$testEmail = 'sudrikyash1@gmail.com';
$testLink = BASE_URL . 'reset-password.php?token=test_token_1234567890abcdef';

$res = send_password_reset_email($testEmail, $testLink);
echo "Result: " . json_encode($res) . "\n";
