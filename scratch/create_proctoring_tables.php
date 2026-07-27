<?php
/**
 * Migration Script - Setup Proctoring Tables and Settings
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$pdo = Database::getConnection();

try {
    echo "Starting migration...\n";

    // 1. Create assessment_proctoring_summaries table
    $sqlSummaries = "
    CREATE TABLE IF NOT EXISTS `assessment_proctoring_summaries` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `result_id` INT NOT NULL UNIQUE,
      `total_violations` INT DEFAULT 0,
      `phone_violations` INT DEFAULT 0,
      `face_missing_violations` INT DEFAULT 0,
      `multiple_face_violations` INT DEFAULT 0,
      `tab_switch_violations` INT DEFAULT 0,
      `focus_loss_violations` INT DEFAULT 0,
      `camera_disconnect_violations` INT DEFAULT 0,
      `risk_level` VARCHAR(20) DEFAULT 'Low Risk',
      `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`result_id`) REFERENCES `assessment_results` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sqlSummaries);
    echo "Table 'assessment_proctoring_summaries' checked/created.\n";

    // 2. Create assessment_proctoring_logs table
    $sqlLogs = "
    CREATE TABLE IF NOT EXISTS `assessment_proctoring_logs` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `result_id` INT NOT NULL,
      `event_type` VARCHAR(50) NOT NULL,
      `description` TEXT NOT NULL,
      `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`result_id`) REFERENCES `assessment_results` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sqlLogs);
    echo "Table 'assessment_proctoring_logs' checked/created.\n";

    // 3. Add dynamic proctoring violation configuration setting if not exists
    $checkSetting = $db->fetch("SELECT * FROM system_settings WHERE setting_key = 'proctoring_max_violations'");
    if (!$checkSetting) {
        $db->insert('system_settings', [
            'setting_key' => 'proctoring_max_violations',
            'setting_value' => '3',
            'setting_group' => 'security',
            'description' => 'Maximum allowed proctoring violations before automatic submission'
        ]);
        echo "Setting 'proctoring_max_violations' inserted with default value 3.\n";
    } else {
        echo "Setting 'proctoring_max_violations' already exists.\n";
    }

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
