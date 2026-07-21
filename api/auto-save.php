<?php
/**
 * SkillBridge - Mid-Assessment Periodic Auto-Save API
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'error' => 'Unauthenticated']);
    exit;
}

$assessmentId = (int)($_POST['assessment_id'] ?? 0);
$answers = $_POST['answers'] ?? [];

// Store draft answers in session state during active quiz taking
$_SESSION['quiz_draft_' . $assessmentId] = $answers;

echo json_encode(['success' => true, 'timestamp' => date('H:i:s')]);
