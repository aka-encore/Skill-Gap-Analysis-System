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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF security token.']);
        exit;
    }
}

$userId = $_SESSION['user_id'] ?? 0;
$db = Database::getInstance();

try {
    $stmt = $db->query("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0", [$userId]);
    $affected = $stmt->rowCount();

    if ($affected === 0) {
        echo json_encode([
            'success' => true,
            'already_read' => true,
            'unread_count' => 0,
            'message' => 'All notifications are already marked as read.'
        ]);
        exit;
    }

    log_activity($userId, 'NOTIFICATION_MARK_ALL_READ', 'Marked all notifications as read.');

    echo json_encode([
        'success' => true,
        'already_read' => false,
        'unread_count' => 0,
        'message' => 'All notifications marked as read.'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
