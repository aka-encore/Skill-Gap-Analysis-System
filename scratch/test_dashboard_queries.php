<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$studentId = 1;

// 1. Student
$student = $db->fetch("SELECT s.*, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?", [$studentId]);
echo "Student Name: " . $student['first_name'] . " " . $student['last_name'] . "\n";

// 2. Scores
$avgScoreRow = $db->fetch("SELECT AVG(score_percentage) as avg_score FROM assessment_results WHERE student_id = ?", [$studentId]);
$avgScore = round((float)($avgScoreRow['avg_score'] ?? 0), 1);
echo "Avg Score: $avgScore\n";

// 3. Completed assessments
$completedAssessments = (int)($db->fetch("SELECT COUNT(DISTINCT assessment_id) as cnt FROM assessment_results WHERE student_id = ?", [$studentId])['cnt'] ?? 0);
$completedThisMonth = (int)($db->fetch("SELECT COUNT(DISTINCT assessment_id) as cnt FROM assessment_results WHERE student_id = ? AND completed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)", [$studentId])['cnt'] ?? 0);
echo "Completed: $completedAssessments (This month: $completedThisMonth)\n";

// 4. Courses Completed
$coursesCompleted = (int)($db->fetch("SELECT COUNT(*) as cnt FROM student_progress WHERE student_id = ? AND status = 'completed'", [$studentId])['cnt'] ?? 0);
$coursesCompletedThisWeek = (int)($db->fetch("SELECT COUNT(*) as cnt FROM student_progress WHERE student_id = ? AND status = 'completed' AND last_updated >= DATE_SUB(NOW(), INTERVAL 7 DAY)", [$studentId])['cnt'] ?? 0);
echo "Courses Completed: $coursesCompleted (This week: $coursesCompletedThisWeek)\n";

// 5. Current Level
$currentLevel = ($avgScore >= 75) ? 'Advanced' : (($avgScore >= 40) ? 'Intermediate' : 'Beginner');
echo "Current Level: $currentLevel\n";

// 6. Streak calculation
$dates = $db->fetchAll(
    "SELECT DISTINCT DATE(completed_at) as act_date FROM assessment_results WHERE student_id = ?
     UNION
     SELECT DISTINCT DATE(last_updated) as act_date FROM student_progress WHERE student_id = ?
     ORDER BY act_date DESC",
    [$studentId, $studentId]
);
print_r($dates);

// 7. Current Skills
$skills = $db->fetchAll(
    "SELECT s.id, s.name as skill_name, COALESCE(MAX(ar.score_percentage), 0) as score
     FROM skills s
     LEFT JOIN assessments a ON s.id = a.skill_id
     LEFT JOIN assessment_results ar ON a.id = ar.assessment_id AND ar.student_id = ?
     GROUP BY s.id, s.name
     ORDER BY score DESC LIMIT 6",
    [$studentId]
);
print_r($skills);

// 8. Recommendations
$recommendations = $db->fetchAll(
    "SELECT r.*, c.title as course_title, c.course_code, c.duration_hours, c.difficulty_level, s.name as skill_name,
            COALESCE(sp.progress_percentage, 0) as progress_percentage,
            COALESCE(sp.status, 'not_started') as progress_status
     FROM recommendations r
     JOIN courses c ON r.course_id = c.id
     JOIN skills s ON r.skill_id = s.id
     LEFT JOIN student_progress sp ON sp.course_id = c.id AND sp.student_id = r.student_id
     WHERE r.student_id = ? AND r.is_dismissed = 0
     ORDER BY r.priority_level DESC, r.created_at DESC LIMIT 3",
    [$studentId]
);
print_r($recommendations);
