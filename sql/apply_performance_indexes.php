<?php
/**
 * SkillBridge - Performance & Schema Synchronization Migration Runner
 * Automatically defines missing tables, adds missing columns, and applies query performance indexes.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

try {
    $db = Database::getInstance();
    $conn = Database::getConnection();
    echo "Connected successfully to Database: " . DB_NAME . "\n";

    // 1. Create question_banks Table if not exists
    $qbCheck = $conn->query("SHOW TABLES LIKE 'question_banks'")->fetch();
    if (!$qbCheck) {
        echo "Creating table 'question_banks'...\n";
        $conn->exec("
            CREATE TABLE `question_banks` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(150) NOT NULL,
              `category` varchar(100) NOT NULL,
              `skill` varchar(100) NOT NULL,
              `difficulty` varchar(50) NOT NULL,
              `status` enum('draft','published') NOT NULL DEFAULT 'draft',
              `created_by_faculty_id` int(11) NOT NULL,
              `created_at` datetime DEFAULT current_timestamp(),
              `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`),
              UNIQUE KEY `unique_qbank_combo` (`category`,`skill`,`difficulty`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "Table 'question_banks' created successfully.\n";
    } else {
        echo "Table 'question_banks' already exists.\n";
    }

    // 2. Create questions Table if not exists
    $qCheck = $conn->query("SHOW TABLES LIKE 'questions'")->fetch();
    if (!$qCheck) {
        echo "Creating table 'questions'...\n";
        $conn->exec("
            CREATE TABLE `questions` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `question_bank_id` int(11) NOT NULL,
              `question_text` text NOT NULL,
              `option_a` text NOT NULL,
              `option_b` text NOT NULL,
              `option_c` text NOT NULL,
              `option_d` text NOT NULL,
              `correct_option` enum('A','B','C','D') NOT NULL,
              `marks` int(11) NOT NULL DEFAULT 1,
              PRIMARY KEY (`id`),
              KEY `fk_question_qbank` (`question_bank_id`),
              CONSTRAINT `fk_questions_qbank_id` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "Table 'questions' created successfully.\n";
    } else {
        echo "Table 'questions' already exists.\n";
    }

    // 3. Add question_bank_id to assessments Table if not exists
    $colCheck = $conn->query("SHOW COLUMNS FROM assessments LIKE 'question_bank_id'")->fetch();
    if (!$colCheck) {
        echo "Adding column 'question_bank_id' to assessments...\n";
        $conn->exec("
            ALTER TABLE `assessments` 
            ADD COLUMN `question_bank_id` int(11) DEFAULT NULL,
            ADD KEY `fk_assessment_qbank` (`question_bank_id`),
            ADD CONSTRAINT `fk_assessments_qbank_id` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE SET NULL;
        ");
        echo "Column 'question_bank_id' added to assessments.\n";
    } else {
        echo "Column 'question_bank_id' already exists in assessments.\n";
    }

    // 4. Apply Performance Indexes Helper Function
    function ensure_index($conn, $table, $indexName, $indexSql) {
        $idxCheck = $conn->query("SHOW INDEX FROM `$table` WHERE Key_name = '$indexName'")->fetch();
        if (!$idxCheck) {
            echo "Applying index '$indexName' to '$table'...\n";
            $conn->exec($indexSql);
            echo "Index '$indexName' applied successfully.\n";
        } else {
            echo "Index '$indexName' already exists on '$table'.\n";
        }
    }

    // Index 4.1: notifications(user_id, is_read)
    ensure_index($conn, 'notifications', 'idx_user_read', 
        "ALTER TABLE `notifications` ADD INDEX `idx_user_read` (`user_id`, `is_read`);"
    );

    // Index 4.2: assessment_results(student_id, completed_at)
    ensure_index($conn, 'assessment_results', 'idx_student_completed', 
        "ALTER TABLE `assessment_results` ADD INDEX `idx_student_completed` (`student_id`, `completed_at`);"
    );

    // Index 4.3: assessment_results(student_id, assessment_id, completed_at)
    ensure_index($conn, 'assessment_results', 'idx_student_assessment_completed', 
        "ALTER TABLE `assessment_results` ADD INDEX `idx_student_assessment_completed` (`student_id`, `assessment_id`, `completed_at`);"
    );

    // Index 4.4: activity_logs(created_at)
    ensure_index($conn, 'activity_logs', 'idx_created_at', 
        "ALTER TABLE `activity_logs` ADD INDEX `idx_created_at` (`created_at`);"
    );

    // Index 4.5: activity_logs(user_id, created_at)
    ensure_index($conn, 'activity_logs', 'idx_user_created', 
        "ALTER TABLE `activity_logs` ADD INDEX `idx_user_created` (`user_id`, `created_at`);"
    );

    // Index 4.6: student_progress(student_id, status)
    ensure_index($conn, 'student_progress', 'idx_student_status', 
        "ALTER TABLE `student_progress` ADD INDEX `idx_student_status` (`student_id`, `status`);"
    );

    echo "\nDatabase Performance & Schema Synchronization Completed successfully!\n";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage() . "\n";
    exit(1);
}
