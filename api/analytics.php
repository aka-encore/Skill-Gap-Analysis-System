<?php
/**
 * SkillBridge - Dynamic Chart.js Analytics API Endpoint
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$type = $_GET['chart'] ?? 'pass_fail';

if ($type === 'pass_fail') {
    $pass = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE status = 'pass'")['cnt'] ?? 0);
    $fail = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE status = 'fail'")['cnt'] ?? 0);
    echo json_encode(['labels' => ['Passed', 'Needs Improvement'], 'data' => [$pass, $fail]]);
    exit;
}

echo json_encode(['error' => 'Invalid analytics request']);
