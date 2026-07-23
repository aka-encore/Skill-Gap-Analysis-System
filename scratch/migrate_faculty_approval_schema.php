<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

echo "=== MIGRATING FACULTY REGISTRATION APPROVAL SCHEMA ===\n\n";

$facultyCols = array_column($db->fetchAll("SHOW COLUMNS FROM faculty"), 'Field');

if (!in_array('college_name', $facultyCols)) {
    $pdo->exec("ALTER TABLE `faculty` ADD COLUMN `college_name` varchar(200) NOT NULL DEFAULT 'SkillBridge University' AFTER `last_name`");
    echo "1. Added column `college_name` to `faculty`.\n";
}

if (!in_array('mobile_number', $facultyCols)) {
    $pdo->exec("ALTER TABLE `faculty` ADD COLUMN `mobile_number` varchar(20) DEFAULT NULL AFTER `college_name`");
    echo "2. Added column `mobile_number` to `faculty`.\n";
}

if (!in_array('experience_years', $facultyCols)) {
    $pdo->exec("ALTER TABLE `faculty` ADD COLUMN `experience_years` int(11) DEFAULT 0 AFTER `designation`");
    echo "3. Added column `experience_years` to `faculty`.\n";
}

if (!in_array('id_card_file', $facultyCols)) {
    $pdo->exec("ALTER TABLE `faculty` ADD COLUMN `id_card_file` varchar(255) DEFAULT NULL AFTER `experience_years`");
    echo "4. Added column `id_card_file` to `faculty`.\n";
}

if (!in_array('appointment_letter_file', $facultyCols)) {
    $pdo->exec("ALTER TABLE `faculty` ADD COLUMN `appointment_letter_file` varchar(255) DEFAULT NULL AFTER `id_card_file`");
    echo "5. Added column `appointment_letter_file` to `faculty`.\n";
}

if (!in_array('approval_status', $facultyCols)) {
    $pdo->exec("ALTER TABLE `faculty` ADD COLUMN `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending' AFTER `appointment_letter_file`");
    echo "6. Added column `approval_status` to `faculty`.\n";
}

if (!in_array('approval_date', $facultyCols)) {
    $pdo->exec("ALTER TABLE `faculty` ADD COLUMN `approval_date` datetime DEFAULT NULL AFTER `approval_status`");
    echo "7. Added column `approval_date` to `faculty`.\n";
}

if (!in_array('approved_by', $facultyCols)) {
    $pdo->exec("ALTER TABLE `faculty` ADD COLUMN `approved_by` int(11) DEFAULT NULL AFTER `approval_date`");
    echo "8. Added column `approved_by` to `faculty`.\n";
}

if (!in_array('rejection_reason', $facultyCols)) {
    $pdo->exec("ALTER TABLE `faculty` ADD COLUMN `rejection_reason` text DEFAULT NULL AFTER `approved_by`");
    echo "9. Added column `rejection_reason` to `faculty`.\n";
}

$userCols = array_column($db->fetchAll("SHOW COLUMNS FROM users"), 'Field');
if (!in_array('status', $userCols)) {
    $pdo->exec("ALTER TABLE `users` ADD COLUMN `status` varchar(20) NOT NULL DEFAULT 'active' AFTER `role`");
    echo "9b. Added column `status` to `users`.\n";
}

// Update existing faculty accounts to 'approved' and users status to 'active'
$pdo->exec("UPDATE `faculty` SET `approval_status` = 'approved' WHERE `id` <= 5");
$pdo->exec("UPDATE `users` u JOIN `faculty` f ON u.id = f.user_id SET u.status = 'active' WHERE f.id <= 5");
echo "10. Existing faculty records updated to `approval_status = 'approved'`.\n";

echo "\nSCHEMA MIGRATION COMPLETED SUCCESSFULLY!\n";
