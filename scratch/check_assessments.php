<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();
$a = $db->fetchAll('SELECT id, title, created_by_faculty_id, status FROM assessments ORDER BY id ASC');
foreach($a as $r) {
    echo "[{$r['id']}] faculty_id={$r['created_by_faculty_id']} status={$r['status']} -> {$r['title']}\n";
}
echo "\nTotal: " . count($a) . "\n";
