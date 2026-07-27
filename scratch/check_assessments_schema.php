<?php
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();
echo "=== DESCRIBE assessments ===\n";
foreach ($db->fetchAll("DESCRIBE assessments") as $row) {
    echo "Field: {$row['Field']} | Type: {$row['Type']} | Key: {$row['Key']}\n";
}
echo "\n=== DESCRIBE assessment_results ===\n";
foreach ($db->fetchAll("DESCRIBE assessment_results") as $row) {
    echo "Field: {$row['Field']} | Type: {$row['Type']} | Key: {$row['Key']}\n";
}
