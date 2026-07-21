<?php
/**
 * SkillBridge - AJAX API Endpoint for Notification Actions (Mark Single Read, Delete)
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
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$notifId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

try {
    if ($action === 'mark_read' && $notifId > 0) {
        $db->query("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?", [$notifId, $userId]);
    } elseif ($action === 'delete' && $notifId > 0) {
        $db->query("DELETE FROM notifications WHERE id = ? AND user_id = ?", [$notifId, $userId]);
    } elseif ($action === 'clear_all') {
        $db->query("DELETE FROM notifications WHERE user_id = ?", [$userId]);
    }

    $unreadCount = get_unread_notifications_count($userId);
    echo json_encode([
        'success' => true,
        'unread_count' => $unreadCount
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
