<?php
header('Content-Type: text/plain');
require_once __DIR__ . '/../config/mail.php';

echo "=== TESTING REGISTRATION OTP EMAIL FLOW ===" . PHP_EOL;
$otpRes = send_otp_email('skill.bridge.project1@gmail.com', '654321');
echo "OTP Email Result: " . print_r($otpRes, true) . PHP_EOL;

echo PHP_EOL . "=== TESTING FORGOT PASSWORD RESET EMAIL FLOW ===" . PHP_EOL;
$resetLink = 'http://localhost/Skill%20Gap%20Analysis/Skill-Gap-Analysis-System/reset-password.php?token=test1234567890abcdef';
$resetRes = send_password_reset_email('skill.bridge.project1@gmail.com', $resetLink);
echo "Password Reset Email Result: " . print_r($resetRes, true) . PHP_EOL;
