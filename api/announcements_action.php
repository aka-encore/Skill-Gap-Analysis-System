<?php
/**
 * SkillBridge - AJAX API Endpoint for Announcement Actions (Get details, Mark Read)
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
$userRole = $_SESSION['user_role'] ?? 'student';
$db = Database::getInstance();

$action = $_POST['action'] ?? $_GET['action'] ?? 'get';
$annId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

if ($annId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid announcement ID']);
    exit;
}

try {
    // 1. Fetch announcement
    $ann = $db->fetch("SELECT * FROM announcements WHERE id = ?", [$annId]);
    if (!$ann) {
        echo json_encode(['success' => false, 'message' => 'Announcement not found']);
        exit;
    }

    // 2. Enforce Role-Based Visibility
    $authorized = false;
    $studentDept = null;
    $facultyDept = null;

    if ($userRole === 'admin') {
        $authorized = true;
    } elseif ($userRole === 'student') {
        // Fetch student department
        $student = $db->fetch("SELECT department FROM students WHERE user_id = ?", [$userId]);
        $studentDept = $student['department'] ?? '';
        
        // Visibility rule: audience in ('all', 'student') AND (department is NULL or department = student's department)
        if (in_array($ann['audience'], ['all', 'student'])) {
            if (empty($ann['department']) || $ann['department'] === $studentDept) {
                $authorized = true;
            }
        }
    } elseif ($userRole === 'faculty') {
        // Fetch faculty department
        $faculty = $db->fetch("SELECT department FROM faculty WHERE user_id = ?", [$userId]);
        $facultyDept = $faculty['department'] ?? '';

        // Visibility rule: creator is self OR audience in ('all', 'faculty') AND (department is NULL or department = faculty's department)
        if ((int)$ann['created_by_user_id'] === $userId) {
            $authorized = true;
        } elseif (in_array($ann['audience'], ['all', 'faculty'])) {
            if (empty($ann['department']) || $ann['department'] === $facultyDept) {
                $authorized = true;
            }
        }
    }

    if (!$authorized) {
        echo json_encode(['success' => false, 'message' => 'Access Denied: You do not have permission to view this announcement.']);
        exit;
    }

    // 3. Handle Actions
    if ($action === 'get') {
        // Mark as Read individually
        $db->query(
            "INSERT IGNORE INTO announcement_reads (announcement_id, user_id) VALUES (?, ?)",
            [$annId, $userId]
        );

        echo json_encode([
            'success' => true,
            'current_user_id' => $userId,
            'current_user_role' => $userRole,
            'announcement' => [
                'id' => $ann['id'],
                'title' => $ann['title'],
                'message' => $ann['message'],
                'created_by_name' => $ann['created_by_name'],
                'created_by_role' => $ann['created_by_role'],
                'audience' => $ann['audience'],
                'priority' => $ann['priority'],
                'department' => $ann['department'],
                'link' => $ann['link'],
                'created_at' => $ann['created_at'],
                'formatted_date' => date('M d, Y h:i A', strtotime($ann['created_at']))
            ]
        ]);
        exit;
    } elseif ($action === 'mark_read') {
        $db->query(
            "INSERT IGNORE INTO announcement_reads (announcement_id, user_id) VALUES (?, ?)",
            [$annId, $userId]
        );
        echo json_encode(['success' => true, 'message' => 'Announcement marked as read.']);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid action']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Internal server error: ' . $e->getMessage()]);
}
