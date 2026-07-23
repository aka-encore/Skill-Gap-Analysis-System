<?php
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();
$tables = array_column($db->fetchAll("SHOW TABLES"), 'Tables_in_skillbridge_db');
echo "Tables in DB:\n" . implode("\n", $tables) . "\n";
