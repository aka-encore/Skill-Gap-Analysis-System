<?php
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
echo "================ STUDENTS TABLE COLUMNS ================\n";
$studentCols = $db->fetchAll("DESCRIBE students");
foreach ($studentCols as $col) {
    echo "  - {$col['Field']} ({$col['Type']})\n";
}

echo "\n================ USERS TABLE COLUMNS ================\n";
$userCols = $db->fetchAll("DESCRIBE users");
foreach ($userCols as $col) {
    echo "  - {$col['Field']} ({$col['Type']})\n";
}
