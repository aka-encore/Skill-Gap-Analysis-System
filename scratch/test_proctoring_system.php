<?php
/**
 * Verification Script - Test Proctoring Database Insertion & Logic
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

try {
    echo "Running Proctoring System Verification...\n";

    // 1. Fetch a student ID
    $student = $db->fetch("SELECT id FROM students LIMIT 1");
    if (!$student) {
        throw new Exception("No students found in database. Please register a student first.");
    }
    $studentId = $student['id'];
    echo "Using Student ID: $studentId\n";

    // 2. Fetch an active assessment ID
    $assessment = $db->fetch("SELECT id, passing_marks, title FROM assessments WHERE status = 'active' LIMIT 1");
    if (!$assessment) {
        throw new Exception("No active assessments found in database.");
    }
    $assessmentId = $assessment['id'];
    echo "Using Assessment: '{$assessment['title']}' (ID: $assessmentId)\n";

    // 3. Create dummy assessment result
    $db->beginTransaction();
    $resultId = $db->insert('assessment_results', [
        'student_id' => $studentId,
        'assessment_id' => $assessmentId,
        'total_questions' => 25,
        'correct_answers' => 15,
        'score_obtained' => 60,
        'score_percentage' => 60.00,
        'status' => 'pass',
        'time_taken_seconds' => 300,
        'completed_at' => date('Y-m-d H:i:s')
    ]);
    echo "Created Assessment Result ID: $resultId\n";

    // 4. Simulate proctoring session counts
    $proctorCounts = [
        'total' => 3,
        'phone' => 1,
        'face_missing' => 1,
        'multiple_face' => 0,
        'tab_switch' => 1,
        'focus_loss' => 0,
        'camera_disconnect' => 0
    ];

    // Determine Risk Level (replicate backend logic)
    $maxViolations = 3;
    $riskLevel = 'Low Risk';
    if ($proctorCounts['total'] >= $maxViolations || $proctorCounts['phone'] > 0 || $proctorCounts['multiple_face'] > 0 || $proctorCounts['tab_switch'] >= 2) {
        $riskLevel = 'High Risk';
    } elseif ($proctorCounts['total'] >= 2 || $proctorCounts['tab_switch'] == 1 || $proctorCounts['focus_loss'] >= 2 || $proctorCounts['face_missing'] >= 2) {
        $riskLevel = 'Medium Risk';
    }

    echo "Calculated Integrity Risk: $riskLevel\n";

    // 5. Insert proctoring summary
    $db->insert('assessment_proctoring_summaries', [
        'result_id' => $resultId,
        'total_violations' => $proctorCounts['total'],
        'phone_violations' => $proctorCounts['phone'],
        'face_missing_violations' => $proctorCounts['face_missing'],
        'multiple_face_violations' => $proctorCounts['multiple_face'],
        'tab_switch_violations' => $proctorCounts['tab_switch'],
        'focus_loss_violations' => $proctorCounts['focus_loss'],
        'camera_disconnect_violations' => $proctorCounts['camera_disconnect'],
        'risk_level' => $riskLevel
    ]);
    echo "Saved Proctoring Summary in database.\n";

    // 6. Insert timeline logs
    $logs = [
        ['event_type' => 'Assessment Started', 'description' => 'Student authorized webcam and loaded proctoring environment.'],
        ['event_type' => 'Camera Enabled', 'description' => 'Webcam stream validated and active.'],
        ['event_type' => 'Tab Switch', 'description' => 'Student switched tabs or minimized the browser window.'],
        ['event_type' => 'Mobile Phone Detected', 'description' => 'Mobile phone detected in webcam view.'],
        ['event_type' => 'Face Missing', 'description' => 'No face visible in webcam frame.'],
        ['event_type' => 'Assessment Submitted', 'description' => 'Student submitted the assessment manually.']
    ];

    foreach ($logs as $log) {
        $db->insert('assessment_proctoring_logs', [
            'result_id' => $resultId,
            'event_type' => $log['event_type'],
            'description' => $log['description'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    echo "Saved " . count($logs) . " proctor timeline logs.\n";

    $db->commit();
    echo "Transaction committed successfully!\n";

    // 7. Verify we can select them back
    $verifySummary = $db->fetch("SELECT * FROM assessment_proctoring_summaries WHERE result_id = ?", [$resultId]);
    $verifyLogs = $db->fetchAll("SELECT * FROM assessment_proctoring_logs WHERE result_id = ?", [$resultId]);

    echo "--- Verification Data ---\n";
    echo "Risk Level: " . $verifySummary['risk_level'] . " (Expected: $riskLevel)\n";
    echo "Total Violations: " . $verifySummary['total_violations'] . " (Expected: 3)\n";
    echo "Logs Count: " . count($verifyLogs) . " (Expected: 6)\n";
    
    if ($verifySummary['risk_level'] === $riskLevel && count($verifyLogs) === 6) {
        echo "Verification SUCCESS!\n";
    } else {
        echo "Verification FAILED (mismatched database counts)!\n";
    }

} catch (Exception $e) {
    if (isset($db) && $db->beginTransaction()) {
        $db->rollBack();
    }
    echo "Verification Error: " . $e->getMessage() . "\n";
}
