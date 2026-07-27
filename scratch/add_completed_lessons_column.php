<?php
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();

// Check if column exists
$columns = $db->fetchAll("DESCRIBE student_progress");
$columnExists = false;
foreach ($columns as $c) {
    if ($c['Field'] === 'completed_lessons') {
        $columnExists = true;
        break;
    }
}

if (!$columnExists) {
    $db->query("ALTER TABLE student_progress ADD COLUMN completed_lessons TEXT DEFAULT NULL");
    echo "Successfully added 'completed_lessons' column to 'student_progress' table.\n";
} else {
    echo "Column 'completed_lessons' already exists in 'student_progress' table.\n";
}
