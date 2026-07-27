<?php
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();
$hash = password_hash('student123', PASSWORD_DEFAULT);
$db->query("UPDATE users SET password = ?, email_verified = 1 WHERE id = 9", [$hash]);
echo "Password reset successful for s_michael.\n";
