<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

$db = Database::getInstance();
$user = $db->fetch("SELECT * FROM users WHERE role = 'faculty' LIMIT 1");
if ($user) {
    login_user($user);
    header("Location: " . BASE_URL . "faculty/notifications.php");
    exit;
} else {
    echo "No faculty user found in database.";
}
