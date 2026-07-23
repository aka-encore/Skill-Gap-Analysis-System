<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

echo "=== MIGRATING ANNOUNCEMENTS & NOTIFICATIONS DATABASE SCHEMA ===\n\n";

// 1. Create announcements table if not exists
$sqlAnnouncements = "
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by_user_id` int(11) NOT NULL,
  `created_by_name` varchar(150) NOT NULL,
  `created_by_role` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `audience` varchar(50) NOT NULL DEFAULT 'all',
  `priority` varchar(20) NOT NULL DEFAULT 'normal',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `link` varchar(255) DEFAULT '#',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_announcements_created_by` (`created_by_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

$pdo->exec($sqlAnnouncements);
echo "1. Table `announcements` created/verified.\n";

// 2. Add announcement_id, created_by_user_id, created_by_role to notifications if not exists
$columns = array_column($db->fetchAll("SHOW COLUMNS FROM notifications"), 'Field');

if (!in_array('announcement_id', $columns)) {
    $pdo->exec("ALTER TABLE `notifications` ADD COLUMN `announcement_id` int(11) NULL AFTER `type`");
    echo "2a. Added column `announcement_id` to `notifications`.\n";
} else {
    echo "2a. Column `announcement_id` already exists in `notifications`.\n";
}

if (!in_array('created_by_user_id', $columns)) {
    $pdo->exec("ALTER TABLE `notifications` ADD COLUMN `created_by_user_id` int(11) NULL AFTER `announcement_id`");
    echo "2b. Added column `created_by_user_id` to `notifications`.\n";
} else {
    echo "2b. Column `created_by_user_id` already exists in `notifications`.\n";
}

if (!in_array('created_by_role', $columns)) {
    $pdo->exec("ALTER TABLE `notifications` ADD COLUMN `created_by_role` varchar(50) NULL AFTER `created_by_user_id`");
    echo "2c. Added column `created_by_role` to `notifications`.\n";
} else {
    echo "2c. Column `created_by_role` already exists in `notifications`.\n";
}

// 3. Migrate existing announcement notifications into announcements table if announcements table is empty
$annCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM announcements")['cnt'] ?? 0);
if ($annCount === 0) {
    $oldAnnouncements = $db->fetchAll("
        SELECT title, message, link, created_at, MIN(user_id) as min_user 
        FROM notifications 
        WHERE type = 'announcement' 
        GROUP BY title, message, link, created_at
    ");

    $adminUser = $db->fetch("SELECT id, username, role FROM users WHERE role = 'admin' LIMIT 1") 
              ?? ['id' => 1, 'username' => 'System Admin', 'role' => 'admin'];

    foreach ($oldAnnouncements as $old) {
        $annId = $db->insert('announcements', [
            'created_by_user_id' => $adminUser['id'],
            'created_by_name'    => $adminUser['username'] ?? 'System Admin',
            'created_by_role'    => $adminUser['role'] ?? 'admin',
            'title'              => $old['title'],
            'message'            => $old['message'],
            'audience'           => 'all',
            'priority'           => 'normal',
            'status'             => 'active',
            'link'               => $old['link'] ?? '#',
            'created_at'         => $old['created_at'] ?? date('Y-m-d H:i:s')
        ]);

        // Link existing notification rows to this announcement ID
        $pdo->prepare("UPDATE notifications SET announcement_id = ? WHERE title = ? AND message = ? AND type = 'announcement'")
            ->execute([$annId, $old['title'], $old['message']]);
    }
    echo "3. Migrated " . count($oldAnnouncements) . " existing announcements into `announcements` table.\n";
} else {
    echo "3. Table `announcements` already has {$annCount} records.\n";
}

echo "\nDATABASE SCHEMA MIGRATION COMPLETED SUCCESSFULLY!\n";
