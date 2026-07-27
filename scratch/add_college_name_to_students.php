<?php
/**
 * Migration script to add college_name column to students table.
 */
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

echo "=== MIGRATING STUDENT PROFILE SCHEMA ===\n\n";

try {
    $cols = array_column($db->fetchAll("SHOW COLUMNS FROM students"), 'Field');
    if (!in_array('college_name', $cols)) {
        $pdo->exec("ALTER TABLE `students` ADD COLUMN `college_name` varchar(255) DEFAULT NULL AFTER `last_name`");
        echo "SUCCESS: Added column `college_name` to `students` table.\n";
    } else {
        echo "INFO: Column `college_name` already exists in `students` table.\n";
    }
    echo "\nSCHEMA MIGRATION COMPLETED SUCCESSFULLY!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
