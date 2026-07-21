<?php
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$sql = "CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_role ENUM('student', 'faculty', 'admin') NOT NULL DEFAULT 'student',
    category VARCHAR(100) NOT NULL,
    rating INT NOT NULL DEFAULT 5,
    message TEXT NOT NULL,
    status ENUM('pending', 'reviewed', 'resolved') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_user_id (user_id),
    KEY idx_status (status),
    KEY idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

try {
    $db->query($sql);
    echo "SUCCESS: feedback table created successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
