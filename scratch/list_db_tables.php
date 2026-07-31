<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

try {
    $db = Database::getInstance();
    $tables = $db->fetchAll("SHOW TABLES");
    echo "Actual Tables in Database:\n";
    foreach ($tables as $t) {
        echo "- " . array_values($t)[0] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
