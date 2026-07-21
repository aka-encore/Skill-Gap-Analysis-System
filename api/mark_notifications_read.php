<?php
/**
 * SkillBridge - AJAX API Endpoint to Mark All Notifications as Read
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'] ?? 0;
$db = Database::getInstance();

try {
    $db->query("UPDATE notifications SET is_read = 1 WHERE user_id = ?", [$userId]);
    echo json_encode([
        'success' => true,
        'unread_count' => 0,
        'message' => 'All notifications marked as read.'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
