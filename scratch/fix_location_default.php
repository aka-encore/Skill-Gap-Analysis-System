<?php
/**
 * SkillBridge - Migration: Fix city_location column default (remove 'Mumbai, India').
 * Run ONCE via browser: http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/scratch/fix_location_default.php
 * Delete this file after running.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = Database::getConnection();

    // 1. Remove the DEFAULT 'Mumbai, India' from the column definition
    $pdo->exec("ALTER TABLE `students` MODIFY COLUMN `city_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL");
    echo "students.city_location DEFAULT changed to NULL.\n";

    // 2. Clean up existing student records set to default 'Mumbai, India' or empty/whitespace
    $affected = $pdo->exec("UPDATE `students` SET `city_location` = NULL WHERE `city_location` = 'Mumbai, India' OR TRIM(IFNULL(`city_location`, '')) = ''");
    echo "Cleaned up $affected existing student location records to NULL.\n";

    echo "Migration complete.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
