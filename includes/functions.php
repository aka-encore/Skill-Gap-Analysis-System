<?php
/**
 * SkillBridge - System Helper Functions & Skill Gap Algorithms
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

/**
 * Sanitize text input for display
 */
function sanitize_input($data): string {
    if (is_array($data)) {
        return '';
    }
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

/**
 * Perform safe HTTP redirect
 */
function redirect(string $url): void {
    if (!headers_sent()) {
        header("Location: " . $url, true, 303);
        exit;
    } else {
        echo "<script>window.location.href='" . htmlspecialchars($url) . "';</script>";
        exit;
    }
}

/**
 * Set session flash message
 */
function set_flash_message(string $type, string $message): void {
    $_SESSION['flash_message'] = [
        'type' => $type, // 'success', 'danger', 'warning', 'info'
        'message' => $message
    ];
}

/**
 * Retrieve and clear session flash message
 */
function get_flash_message(): ?array {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $msg;
    }
    return null;
}

/**
 * Record activity log in database
 */
function log_activity(?int $userId, string $action, string $description): void {
    try {
        $db = Database::getInstance();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $db->insert('activity_logs', [
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $ip,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    } catch (Exception $e) {
        // Silently fail log write to avoid breaking user flow
    }
}

/**
 * Calculate Skill Gap metrics based on score percentage
 */
function calculate_skill_gap(float $scorePercentage, int $targetLevel = 4): array {
    $achievedLevel = (int)ceil($scorePercentage / 20.0);
    if ($achievedLevel < 1) $achievedLevel = 1;
    if ($achievedLevel > 5) $achievedLevel = 5;

    $gapPercentage = max(0.0, 100.0 - $scorePercentage);
    $isWeak = $scorePercentage < 60.0;
    $isStrong = $scorePercentage >= 75.0;

    $skillLevelName = match ($achievedLevel) {
        1 => 'Novice (Level 1)',
        2 => 'Beginner (Level 2)',
        3 => 'Competent (Level 3)',
        4 => 'Proficient (Level 4)',
        5 => 'Expert (Level 5)',
    };

    $badgeClass = match (true) {
        $scorePercentage >= 80 => 'bg-success',
        $scorePercentage >= 60 => 'bg-info',
        $scorePercentage >= 40 => 'bg-warning text-dark',
        default => 'bg-danger',
    };

    return [
        'score_percentage' => round($scorePercentage, 2),
        'achieved_level' => $achievedLevel,
        'target_level' => $targetLevel,
        'gap_percentage' => round($gapPercentage, 2),
        'skill_level_name' => $skillLevelName,
        'is_weak' => $isWeak,
        'is_strong' => $isStrong,
        'badge_class' => $badgeClass
    ];
}

/**
 * Trigger automated recommendation and notifications if a student exhibits a skill gap
 */
function generate_recommendations_for_result(int $studentId, int $assessmentId, float $scorePercentage): void {
    $db = Database::getInstance();

    // Get assessment details & skill
    $assessment = $db->fetch("SELECT a.*, s.name as skill_name FROM assessments a JOIN skills s ON a.skill_id = s.id WHERE a.id = ?", [$assessmentId]);
    if (!$assessment) return;

    $student = $db->fetch("SELECT s.*, u.id as user_id FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?", [$studentId]);
    if (!$student) return;

    $skillId = $assessment['skill_id'];
    $gapMetrics = calculate_skill_gap($scorePercentage);

    // Notify the Faculty member who created this assessment (Requirement 15)
    if (!empty($assessment['created_by_faculty_id'])) {
        $faculty = $db->fetch(
            "SELECT f.*, u.id as user_id FROM faculty f JOIN users u ON f.user_id = u.id WHERE f.id = ?",
            [$assessment['created_by_faculty_id']]
        );
        if ($faculty && !empty($faculty['user_id'])) {
            $studentName = trim(($student['first_name'] ?? 'Student') . ' ' . ($student['last_name'] ?? ''));
            $completionTime = date('d M Y, h:i A');
            $db->insert('notifications', [
                'user_id'    => $faculty['user_id'],
                'title'      => 'Student Quiz Submission: ' . $assessment['title'],
                'message'    => "Student {$studentName} completed assessment '{$assessment['title']}' with a score of " . number_format($scorePercentage, 1) . "% on {$completionTime}.",
                'link'       => BASE_URL . 'faculty/evaluate.php?student_id=' . $studentId,
                'is_read'    => 0,
                'type'       => 'assessment',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    // If weak skill, automatically match suitable courses
    if ($gapMetrics['is_weak']) {
        $courses = $db->fetchAll(
            "SELECT c.* FROM courses c 
             JOIN course_skills cs ON c.id = cs.course_id 
             WHERE cs.skill_id = ? AND c.status = 'active'", 
            [$skillId]
        );

        foreach ($courses as $course) {
            // Check if recommendation already exists
            $existing = $db->fetch(
                "SELECT id FROM recommendations WHERE student_id = ? AND course_id = ? AND is_dismissed = 0",
                [$studentId, $course['id']]
            );

            if (!$existing) {
                $priority = $scorePercentage < 40 ? 'high' : 'medium';
                $reason = "Your recent assessment in {$assessment['skill_name']} was " . number_format($scorePercentage, 2) . "%. Recommended to bridge your " . number_format($gapMetrics['gap_percentage'], 1) . "% skill gap.";
                
                $db->insert('recommendations', [
                    'student_id' => $studentId,
                    'course_id' => $course['id'],
                    'skill_id' => $skillId,
                    'reason' => $reason,
                    'priority_level' => $priority,
                    'is_dismissed' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // Create notification for course recommendation
                $db->insert('notifications', [
                    'user_id' => $student['user_id'],
                    'title' => 'New Course Recommendation',
                    'message' => "We recommended course '{$course['title']}' to help improve your {$assessment['skill_name']} skill.",
                    'link' => BASE_URL . 'student/recommendations.php',
                    'is_read' => 0,
                    'type' => 'recommendation',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}

/**
 * Fetch unread notification count
 */
function get_unread_notifications_count(int $userId): int {
    $db = Database::getInstance();
    $row = $db->fetch("SELECT COUNT(*) as cnt FROM notifications WHERE user_id = ? AND is_read = 0", [$userId]);
    return (int)($row['cnt'] ?? 0);
}

/**
 * Fetch recent user notifications
 */
function get_user_notifications(int $userId, int $limit = 5): array {
    $db = Database::getInstance();
    return $db->fetchAll("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit", [$userId]);
}

/**
 * Fetch detailed profile record according to user role
 */
function get_user_profile_data(int $userId, string $role): ?array {
    $db = Database::getInstance();
    if ($role === 'student') {
        return $db->fetch("SELECT u.username, u.email, u.role, u.created_at as user_created, s.* FROM users u JOIN students s ON u.id = s.user_id WHERE u.id = ?", [$userId]);
    } elseif ($role === 'faculty') {
        return $db->fetch("SELECT u.username, u.email, u.role, u.created_at as user_created, f.* FROM users u JOIN faculty f ON u.id = f.user_id WHERE u.id = ?", [$userId]);
    } elseif ($role === 'admin') {
        return $db->fetch("SELECT u.username, u.email, u.role, u.created_at as user_created, a.* FROM users u JOIN admins a ON u.id = a.user_id WHERE u.id = ?", [$userId]);
    }
    return null;
}

/**
 * Format datetime nicely
 */
function format_date(?string $datetime, string $format = 'M d, Y h:i A'): string {
    if (!$datetime) return 'N/A';
    return date($format, strtotime($datetime));
}

/**
 * Calculate Weighted Skill Percentage & Status across 5 Difficulty Levels
 * Weights: Beginner (10%), Easy (15%), Intermediate (20%), Advanced (25%), Expert (30%)
 * Each level contains 25 questions. Uses highest score per level on retakes.
 */
function calculate_weighted_skill_percentage(int $studentId, int $skillId): array {
    $db = Database::getInstance();

    $weights = [
        'beginner'     => 10,
        'easy'         => 15,
        'intermediate' => 20,
        'advanced'     => 25,
        'expert'       => 30
    ];

    // Fetch highest score_percentage for each difficulty level completed for this skill
    $levelScores = $db->fetchAll(
        "SELECT a.difficulty_level, MAX(ar.score_percentage) as best_percentage
         FROM assessment_results ar
         JOIN assessments a ON ar.assessment_id = a.id
         WHERE ar.student_id = ? AND a.skill_id = ?
         GROUP BY a.difficulty_level",
        [$studentId, $skillId]
    );

    $scoresByLevel = [];
    foreach ($levelScores as $ls) {
        $lvl = strtolower(trim($ls['difficulty_level']));
        $scoresByLevel[$lvl] = (float)$ls['best_percentage'];
    }

    $totalSkillPercentage = 0.0;
    $attemptedLevelsCount = 0;
    $levelBreakdown = [];
    $isExpertCompleted = isset($scoresByLevel['expert']);

    foreach ($weights as $lvl => $weight) {
        if (isset($scoresByLevel[$lvl])) {
            $attemptedLevelsCount++;
            $scorePct = $scoresByLevel[$lvl];
            // Contribution = (score_pct / 100) * weight
            $contribution = ($scorePct / 100.0) * $weight;
            $totalSkillPercentage += $contribution;
            $levelBreakdown[$lvl] = [
                'attempted'    => true,
                'score_pct'    => round($scorePct, 2),
                'weight'       => $weight,
                'contribution' => round($contribution, 2)
            ];
        } else {
            $levelBreakdown[$lvl] = [
                'attempted'    => false,
                'score_pct'    => 0.0,
                'weight'       => $weight,
                'contribution' => 0.0
            ];
        }
    }

    $status = $isExpertCompleted ? 'Completed' : 'In Progress';

    return [
        'overall_percentage'  => round($totalSkillPercentage, 1),
        'status'              => $status,
        'attempted_levels'    => $attemptedLevelsCount,
        'is_expert_completed' => $isExpertCompleted,
        'breakdown'           => $levelBreakdown
    ];
}

/**
 * Calculate Student's Overall Average Weighted Skill Percentage across all active skills
 */
function calculate_overall_student_skill_percentage(int $studentId): float {
    $db = Database::getInstance();
    $skills = $db->fetchAll("SELECT id FROM skills");
    if (empty($skills)) return 0.0;

    $totalPercentageSum = 0.0;
    $skillsTestedCount = 0;

    foreach ($skills as $s) {
        $weighted = calculate_weighted_skill_percentage($studentId, (int)$s['id']);
        if ($weighted['attempted_levels'] > 0) {
            $totalPercentageSum += $weighted['overall_percentage'];
            $skillsTestedCount++;
        }
    }

    if ($skillsTestedCount === 0) return 0.0;
    return round($totalPercentageSum / $skillsTestedCount, 1);
}
