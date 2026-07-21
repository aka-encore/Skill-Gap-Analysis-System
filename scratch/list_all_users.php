<?php
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();
$users = $db->fetchAll("SELECT id, username, email, role FROM users LIMIT 10");
foreach ($users as $u) {
    echo "ID: {$u['id']} | Username: {$u['username']} | Email: {$u['email']} | Role: {$u['role']}\n";
}
