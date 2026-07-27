<?php
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();
$tables = $db->fetchAll("SHOW TABLES");
foreach ($tables as $t) {
    $tableName = array_values($t)[0];
    echo "Table: $tableName\n";
    if (stripos($tableName, 'course') !== false || stripos($tableName, 'enroll') !== false || stripos($tableName, 'lesson') !== false || stripos($tableName, 'prog') !== false) {
        echo "---------------------------\n";
        $columns = $db->fetchAll("DESCRIBE `$tableName`");
        foreach ($columns as $c) {
            echo "  Field: {$c['Field']} | Type: {$c['Type']} | Null: {$c['Null']} | Key: {$c['Key']}\n";
        }
        echo "---------------------------\n";
    }
}
