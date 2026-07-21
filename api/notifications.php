<?php
/**
 * SkillBridge - Notifications AJAX API Endpoint
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'error' => 'Unauthenticated']);
    exit;
}

$userId = $_SESSION['user_id'];
$db = Database::getInstance();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'mark_all_read') {
    $db->update('notifications', ['is_read' => 1], 'user_id = ?', [$userId]);
    echo json_encode(['success' => true]);
    exit;
} elseif ($action === 'delete') {
    $notifId = (int)($_POST['id'] ?? 0);
    $db->delete('notifications', 'id = ? AND user_id = ?', [$notifId, $userId]);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid action']);
