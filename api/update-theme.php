<?php
/**
 * SkillBridge - API to Update User Theme Preference
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$theme = trim($input['theme'] ?? '');

if (!in_array($theme, ['light', 'dark', 'system'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid theme preference']);
    exit;
}

try {
    $db = Database::getInstance();
    $db->update('users', ['theme' => $theme], 'id = ?', [$userId]);
    $_SESSION['user_theme'] = $theme;
    
    // Set cookie as well for FOUC prevention and guest/logout state consistency
    setcookie('skillbridge_theme', $theme, time() + (86400 * 30), "/", "", false, true);
    
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
