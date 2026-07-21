<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$skills = $db->fetchAll("SELECT * FROM skills ORDER BY category, name");
echo "Total Skills in DB: " . count($skills) . "\n";
foreach ($skills as $s) {
    echo "- ID: {$s['id']} | Name: {$s['name']} | Category: {$s['category']}\n";
}
