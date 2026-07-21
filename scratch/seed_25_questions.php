<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

// 1. Update assessments total_marks and total_questions to 25
$db->query("UPDATE assessments SET total_marks = 25, passing_marks = 20");
echo "Updated assessments table default total_marks=25, passing_marks=20.\n";

// 2. Ensure each of the 10 assessments has 25 questions in assessment_questions
$assessments = $db->fetchAll("SELECT id, title FROM assessments");

foreach ($assessments as $a) {
    $assId = $a['id'];
    $existingQuestions = $db->fetchAll("SELECT * FROM assessment_questions WHERE assessment_id = ?", [$assId]);
    $currentCount = count($existingQuestions);

    if ($currentCount < 25) {
        $needed = 25 - $currentCount;
        for ($i = 1; $i <= $needed; $i++) {
            $qNum = $currentCount + $i;
            $db->insert('assessment_questions', [
                'assessment_id' => $assId,
                'question_text' => "Question {$qNum}: In {$a['title']}, which concept best describes the optimal architectural pattern for system scaling and memory optimization?",
                'option_a' => "Option A: Modular decoupled architecture with caching layers",
                'option_b' => "Option B: Synchronous single-threaded blocking execution",
                'option_c' => "Option C: Global state mutation without scoping controls",
                'option_d' => "Option D: Unindexed linear table scans on foreign keys",
                'correct_option' => 'A',
                'marks' => 1,
                'category' => 'Core Architecture'
            ]);
        }
        echo "Added {$needed} questions to Assessment ID {$assId} ('{$a['title']}'). Total is now 25.\n";
    } else {
        echo "Assessment ID {$assId} already has {$currentCount} questions.\n";
    }
}

echo "25-Question database seeding completed successfully!\n";
