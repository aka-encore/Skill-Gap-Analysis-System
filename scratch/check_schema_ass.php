<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();
echo "=== ASSESSMENTS COLS ===\n";
foreach ($db->fetchAll("DESCRIBE assessments") as $c) {
    echo $c['Field'] . " (" . $c['Type'] . ")\n";
}
