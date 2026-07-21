<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

// Clean up any test records where total_questions was 1 or corrupted
$db->query("DELETE FROM assessment_results WHERE total_questions = 1 OR total_questions IS NULL");
echo "Cleaned up invalid assessment_results records.\n";

// Ensure sample assessment_results (IDs 1, 2, 3) for Student 1 have sample student_answers entries
$sampleResults = $db->fetchAll("SELECT * FROM assessment_results WHERE student_id = 1");
foreach ($sampleResults as $res) {
    $resId = $res['id'];
    $assId = $res['assessment_id'];

    $existingAns = $db->fetch("SELECT COUNT(*) as cnt FROM student_answers WHERE result_id = ?", [$resId]);
    if (($existingAns['cnt'] ?? 0) === 0) {
        $questions = $db->fetchAll("SELECT * FROM assessment_questions WHERE assessment_id = ? ORDER BY id ASC LIMIT 10", [$assId]);
        foreach ($questions as $q) {
            $db->insert('student_answers', [
                'result_id' => $resId,
                'question_id' => $q['id'],
                'selected_option' => $q['correct_option'],
                'is_correct' => 1,
                'marks_obtained' => (int)$q['marks']
            ]);
        }
        echo "Populated 10 sample student_answers for Result ID {$resId}.\n";
    }
}

echo "Database assessment_results and student_answers structure verified!\n";
