<?php
/**
 * SkillBridge - AI Proctoring Live Event Logging API
 * Logs events to session in real-time to avoid client-side database tampering.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';

// Ensure user is logged in as student
if (!is_logged_in() || $_SESSION['user_role'] !== 'student') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventType = trim($_POST['event_type'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($eventType)) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing event_type parameter']);
        exit;
    }

    // Initialize session stores if not exists
    if (!isset($_SESSION['proctor_logs'])) {
        $_SESSION['proctor_logs'] = [];
    }
    if (!isset($_SESSION['proctor_counts'])) {
        $_SESSION['proctor_counts'] = [
            'total' => 0,
            'phone' => 0,
            'face_missing' => 0,
            'multiple_face' => 0,
            'tab_switch' => 0,
            'focus_loss' => 0,
            'camera_disconnect' => 0
        ];
    }

    // Append log entry
    $_SESSION['proctor_logs'][] = [
        'event_type' => $eventType,
        'description' => $description,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Determine if this event is counted as a violation
    $isViolation = false;
    
    switch ($eventType) {
        case 'Tab Switch':
        case 'Window Minimized':
        case 'Full-screen Exit':
            $_SESSION['proctor_counts']['tab_switch']++;
            $isViolation = true;
            break;
        case 'Window Focus Lost':
            $_SESSION['proctor_counts']['focus_loss']++;
            $isViolation = true;
            break;
        case 'Mobile Phone Detected':
            $_SESSION['proctor_counts']['phone']++;
            $isViolation = true;
            break;
        case 'Face Missing':
            $_SESSION['proctor_counts']['face_missing']++;
            $isViolation = true;
            break;
        case 'Multiple Faces Detected':
            $_SESSION['proctor_counts']['multiple_face']++;
            $isViolation = true;
            break;
        case 'Camera Disabled':
            $_SESSION['proctor_counts']['camera_disconnect']++;
            $isViolation = true;
            break;
    }

    if ($isViolation) {
        $_SESSION['proctor_counts']['total']++;
    }

    echo json_encode([
        'status' => 'success',
        'is_violation' => $isViolation,
        'current_violations' => $_SESSION['proctor_counts']['total'],
        'counts' => $_SESSION['proctor_counts']
    ]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}
