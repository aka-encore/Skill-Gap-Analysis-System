<?php
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

try {
    // Add bio column if not exists
    $cols = array_column($db->fetchAll("DESCRIBE students"), 'Field');
    if (!in_array('bio', $cols)) {
        $db->query("ALTER TABLE students ADD COLUMN bio VARCHAR(255) NULL AFTER avatar");
        echo "Added 'bio' column to students table.\n";
    }
    if (!in_array('city_location', $cols)) {
        $db->query("ALTER TABLE students ADD COLUMN city_location VARCHAR(100) NULL DEFAULT 'Mumbai, India' AFTER bio");
        echo "Added 'city_location' column to students table.\n";
    }
    echo "SUCCESS: Student profile schema is fully ready!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
