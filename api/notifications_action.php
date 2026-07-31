<?php
/**
 * SkillBridge - AJAX API Endpoint for Notification Actions (Mark Single Read, Delete, Clear All, Open)
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
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$notifId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

try {
    if ($action === 'open' && $notifId > 0) {
        $notif = $db->fetch("SELECT * FROM notifications WHERE id = ? AND user_id = ?", [$notifId, $userId]);
        $userRole = strtolower(trim($_SESSION['user_role'] ?? 'student'));
        if ($notif) {
            $db->query("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?", [$notifId, $userId]);
            log_activity($userId, 'NOTIFICATION_OPEN', "Opened notification #{$notifId}.");
            $targetUrl = get_notification_redirect_url($notif, $userRole);
            header("Location: " . $targetUrl);
            exit;
        }
        // Fallback redirection if not found
        $fallback = match($userRole) {
            'admin'   => BASE_URL . 'admin/dashboard.php',
            'faculty' => BASE_URL . 'faculty/dashboard.php',
            default   => BASE_URL . 'student/dashboard.php'
        };
        header("Location: " . $fallback);
        exit;
    }

    if ($action === 'mark_read' && $notifId > 0) {
        $db->query("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?", [$notifId, $userId]);
        log_activity($userId, 'NOTIFICATION_MARK_READ', "Marked notification #{$notifId} as read.");
    } elseif ($action === 'mark_unread' && $notifId > 0) {
        $db->query("UPDATE notifications SET is_read = 0 WHERE id = ? AND user_id = ?", [$notifId, $userId]);
        log_activity($userId, 'NOTIFICATION_MARK_UNREAD', "Marked notification #{$notifId} as unread.");
    } elseif ($action === 'mark_all_read') {
        $db->query("UPDATE notifications SET is_read = 1 WHERE user_id = ?", [$userId]);
        log_activity($userId, 'NOTIFICATION_MARK_ALL_READ', "Marked all notifications as read.");
    } elseif ($action === 'delete' && $notifId > 0) {
        $db->query("DELETE FROM notifications WHERE id = ? AND user_id = ?", [$notifId, $userId]);
        log_activity($userId, 'NOTIFICATION_DELETE', "Deleted notification #{$notifId}.");
    } elseif ($action === 'clear_all') {
        $deletedCount = $db->delete('notifications', 'user_id = ?', [$userId]);
        log_activity($userId, 'NOTIFICATION_CLEAR_ALL', "Cleared all notifications ({$deletedCount} items).");
    }

    $counts = $db->fetch(
        "SELECT COUNT(*) as total_count, SUM(is_read = 0) as unread_count FROM notifications WHERE user_id = ?", 
        [$userId]
    );
    $totalCount = (int)($counts['total_count'] ?? 0);
    $unreadCount = (int)($counts['unread_count'] ?? 0);
    $readCount = $totalCount - $unreadCount;

    echo json_encode([
        'success'      => true,
        'unread_count' => $unreadCount,
        'total_count'  => $totalCount,
        'read_count'   => $readCount,
        'message'      => $action === 'clear_all' ? 'All notifications cleared successfully.' : 'Action completed.'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
