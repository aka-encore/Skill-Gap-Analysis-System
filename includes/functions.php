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
 * Return the URL of the role-specific default avatar SVG.
 * Falls back to student avatar for unknown roles.
 */
function get_default_avatar_url(string $role = 'student'): string {
    $map = [
        'student' => 'default-avatar-student.svg',
        'faculty' => 'default-avatar-faculty.svg',
        'admin'   => 'default-avatar-admin.svg',
    ];
    $file = $map[strtolower($role)] ?? 'default-avatar-student.svg';
    return BASE_URL . 'assets/images/' . $file;
}

/**
 * Resolve the avatar URL for a given avatar filename and role.
 * Returns the uploaded image URL when the file exists, otherwise the
 * role-specific default SVG.
 */
function resolve_avatar_url(string $avatarFile, string $role = 'student'): string {
    $isDefault = (empty($avatarFile) || $avatarFile === 'default-avatar.png');
    if (!$isDefault) {
        $diskPath = __DIR__ . '/../uploads/avatars/' . $avatarFile;
        if (file_exists($diskPath)) {
            return BASE_URL . 'uploads/avatars/' . $avatarFile;
        }
    }
    return get_default_avatar_url($role);
}

/**
 * Validate a college / institution name.
 * Allows letters, spaces, periods, hyphens, apostrophes, ampersands, parentheses.
 * Rejects digits and unsupported special characters.
 */
function validate_college_name(string $name): bool {
    if (empty($name)) {
        return false;
    }
    // Must not contain digits; only allowed chars: letters, space . - ' & ( )
    return (bool) preg_match("/^[a-zA-Z\s\.\-\'\&\(\)]+$/u", $name);
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
/**
 * Automatically generate real-time in-app notifications for all active faculty members
 * belonging to the student's department when a student completes an assessment.
 */
function notify_faculty_of_assessment_completion(int $studentId, int $assessmentId, ?int $resultId, float $scorePercentage): void {
    $db = Database::getInstance();

    // 1. Fetch Assessment & Skill Details
    $assessment = $db->fetch(
        "SELECT a.*, s.name as skill_name 
         FROM assessments a 
         JOIN skills s ON a.skill_id = s.id 
         WHERE a.id = ?", 
        [$assessmentId]
    );
    if (!$assessment) return;

    // 2. Fetch Student Details
    $student = $db->fetch(
        "SELECT s.*, u.id as user_id 
         FROM students s 
         JOIN users u ON s.user_id = u.id 
         WHERE s.id = ?", 
        [$studentId]
    );
    if (!$student) return;

    // 3. Fetch Assessment Result Details if resultId not passed
    if (!$resultId || $resultId <= 0) {
        $latestRes = $db->fetch(
            "SELECT * FROM assessment_results WHERE student_id = ? AND assessment_id = ? ORDER BY completed_at DESC LIMIT 1",
            [$studentId, $assessmentId]
        );
        $resultId = (int)($latestRes['id'] ?? 0);
        $resultRow = $latestRes;
    } else {
        $resultRow = $db->fetch("SELECT * FROM assessment_results WHERE id = ?", [$resultId]);
    }

    $studentName    = trim(($student['first_name'] ?? 'Student') . ' ' . ($student['last_name'] ?? ''));
    $studentDept    = trim($student['department'] ?? '');
    $skillName      = $assessment['skill_name'] ?? 'Skill';
    $diffRaw        = strtolower(trim($assessment['difficulty_level'] ?? 'beginner'));
    $diffDisplay    = match($diffRaw) {
        'beginner' => 'Beginner (Level 1)',
        'intermediate' => 'Intermediate (Level 2)',
        'advanced' => 'Advanced (Level 3)',
        'expert' => 'Expert (Level 4)',
        'master' => 'Master (Level 5)',
        default => ucfirst($diffRaw)
    };

    $scoreObtained  = (int)($resultRow['score_obtained'] ?? $resultRow['correct_answers'] ?? 0);
    $totalQuestions = (int)($resultRow['total_questions'] ?? 25);
    $formattedScorePct = number_format($scorePercentage, 1);
    $completionTime = date('d M Y • h:i A');

    // 4. Query all active faculty members belonging to the SAME department
    $facultyRecipients = [];
    if (!empty($studentDept)) {
        $facultyRecipients = $db->fetchAll(
            "SELECT f.id as faculty_id, f.user_id 
             FROM faculty f 
             JOIN users u ON f.user_id = u.id 
             WHERE LOWER(TRIM(f.department)) = LOWER(TRIM(?)) 
               AND u.status = 'active'",
            [$studentDept]
        );
    }

    // Fallback: If no faculty members found in department, notify all active faculty members so no submission is missed
    if (empty($facultyRecipients)) {
        $facultyRecipients = $db->fetchAll(
            "SELECT f.id as faculty_id, f.user_id 
             FROM faculty f 
             JOIN users u ON f.user_id = u.id 
             WHERE u.status = 'active'"
        );
    }

    if (empty($facultyRecipients)) return;

    $title   = "Assessment Completed: {$assessment['title']}";
    $message = "{$studentName} has successfully completed the \"{$assessment['title']}\". Skill: {$skillName} | Difficulty: {$diffDisplay} | Score: {$scoreObtained}/{$totalQuestions} ({$formattedScorePct}%) | Completed: {$completionTime}";
    $link    = BASE_URL . 'faculty/evaluate.php?student_id=' . $studentId . ($resultId ? '&result_id=' . $resultId : '');

    // 5. Insert notification record for each matching active faculty member
    foreach ($facultyRecipients as $fac) {
        $facUserId = (int)$fac['user_id'];
        
        // Strict duplicate check to avoid double notification for same result
        $existing = false;
        if ($resultId > 0) {
            $existing = $db->fetch(
                "SELECT id FROM notifications 
                 WHERE user_id = ? 
                   AND type = 'assessment' 
                   AND link LIKE ?",
                [$facUserId, "%result_id={$resultId}%"]
            );
        }

        if (!$existing) {
            $db->insert('notifications', [
                'user_id'    => $facUserId,
                'title'      => $title,
                'message'    => $message,
                'link'       => $link,
                'is_read'    => 0,
                'type'       => 'assessment',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}

/**
 * Trigger automated recommendation and notifications if a student exhibits a skill gap
 */
function generate_recommendations_for_result(int $studentId, int $assessmentId, float $scorePercentage, ?int $resultId = null): void {
    $db = Database::getInstance();

    // Get assessment details & skill
    $assessment = $db->fetch("SELECT a.*, s.name as skill_name FROM assessments a JOIN skills s ON a.skill_id = s.id WHERE a.id = ?", [$assessmentId]);
    if (!$assessment) return;

    $student = $db->fetch("SELECT s.*, u.id as user_id FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?", [$studentId]);
    if (!$student) return;

    $skillId = $assessment['skill_id'];
    $gapMetrics = calculate_skill_gap($scorePercentage);

    // Notify all active department faculty members of this assessment completion
    notify_faculty_of_assessment_completion($studentId, $assessmentId, $resultId, $scorePercentage);

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
    return $db->fetchAll("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC, id DESC LIMIT $limit", [$userId]);
}

/**
 * Centralized routing/mapping for page-related notifications based on user role
 */
/**
 * Helper to extract name, student code, faculty code, or other keywords from a notification message or title
 */
function extract_notification_keyword(array $notif): string {
    $msg = $notif['message'] ?? '';
    $title = $notif['title'] ?? '';
    
    // 1. Check for student/faculty codes (e.g. STU-1058, FAC-1002)
    if (preg_match('/(STU-\d+|FAC-\d+)/i', $msg, $matches)) {
        return $matches[1];
    }
    if (preg_match('/(STU-\d+|FAC-\d+)/i', $title, $matches)) {
        return $matches[1];
    }
    
    // 2. Check for double quotes (e.g. "HTML - Beginner")
    if (preg_match('/"([^"]+)"/', $msg, $matches)) {
        return $matches[1];
    }
    if (preg_match('/"([^"]+)"/', $title, $matches)) {
        return $matches[1];
    }

    // 3. Extract name after "Student" or "Faculty"
    if (preg_match('/(?:student|faculty)\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)+)/', $msg, $matches)) {
        return $matches[1];
    }
    
    // 4. Extract first capitalized name sequence in message
    if (preg_match('/([A-Z][a-z]+(?:\s+[A-Z][a-z]+)+)/', $msg, $matches)) {
        return $matches[1];
    }
    
    return '';
}

function get_notification_redirect_url(array $notif, string $userRole): string {
    // If it's an announcement notification, respect the existing announcement behavior
    if (($notif['type'] ?? '') === 'announcement') {
        $annId = (int)($notif['announcement_id'] ?? 0);
        $link = trim($notif['link'] ?? '');
        // If it points to an external custom link, follow it, otherwise route to announcements view page
        if (!empty($link) && $link !== '#' && !str_contains($link, 'announcements.php') && !str_contains($link, 'notification.php')) {
            return $link;
        }
        return match($userRole) {
            'admin'   => BASE_URL . 'admin/announcements.php' . ($annId > 0 ? '?open_announcement_id=' . $annId : ''),
            'faculty' => BASE_URL . 'faculty/announcements.php' . ($annId > 0 ? '?open_announcement_id=' . $annId : ''),
            default   => BASE_URL . 'student/notification.php' . ($annId > 0 ? '?open_announcement_id=' . $annId : '')
        };
    }

    $type = strtolower($notif['type'] ?? '');
    $title = strtolower($notif['title'] ?? '');
    
    // Default fallback based on role
    $fallbackUrl = match($userRole) {
        'admin'   => BASE_URL . 'admin/dashboard.php',
        'faculty' => BASE_URL . 'faculty/dashboard.php',
        default   => BASE_URL . 'student/dashboard.php'
    };

    $link = trim($notif['link'] ?? '');
    if (!empty($link) && $link !== '#') {
        // Enforce strict role-based prefix check to prevent unauthorized redirection
        if ($userRole === 'admin') {
            if (str_contains($link, '/faculty/') || str_contains($link, '/student/')) {
                return $fallbackUrl;
            }
        } elseif ($userRole === 'faculty') {
            if (str_contains($link, '/admin/') || str_contains($link, '/student/')) {
                return $fallbackUrl;
            }
        } else {
            if (str_contains($link, '/admin/') || str_contains($link, '/faculty/')) {
                return $fallbackUrl;
            }
        }
        return $link;
    }

    // Role-restricted centralized navigation mapping based on notification type/title
    if ($userRole === 'admin') {
        $keyword = urlencode(extract_notification_keyword($notif));
        $query = !empty($keyword) ? "?search=" . $keyword : "";

        return match (true) {
            str_contains($title, 'faculty application') || str_contains($title, 'faculty registration application') || $type === 'faculty_application' => BASE_URL . 'admin/faculty-applications.php' . $query,
            str_contains($title, 'suspended') || $type === 'student_suspended' => BASE_URL . 'admin/students.php' . (!empty($keyword) ? "?status=suspended&search=" . $keyword : "?status=suspended"),
            str_contains($title, 'student registration') || str_contains($title, 'new student') || str_contains($title, 'student joined') => BASE_URL . 'admin/students.php' . $query,
            str_contains($title, 'student') || $type === 'student' => BASE_URL . 'admin/students.php' . $query,
            str_contains($title, 'faculty') || $type === 'faculty' => BASE_URL . 'admin/faculty.php' . $query,
            str_contains($title, 'course') || $type === 'course' => BASE_URL . 'admin/courses.php' . $query,
            str_contains($title, 'assessment completed') || str_contains($title, 'quiz submission') || $type === 'assessment' => BASE_URL . 'admin/proctoring-reports.php' . $query,
            str_contains($title, 'feedback') || $type === 'feedback' => BASE_URL . 'admin/feedback.php' . $query,
            str_contains($title, 'announcement') || $type === 'announcement' => BASE_URL . 'admin/announcements.php' . $query,
            str_contains($title, 'question bank') || $type === 'question_bank' => BASE_URL . 'admin/assessments.php' . $query,
            str_contains($title, 'certificate') || $type === 'certificate' => BASE_URL . 'admin/certificates.php' . $query,
            str_contains($title, 'skill') || $type === 'skill' => BASE_URL . 'admin/skills.php' . $query,
            str_contains($title, 'report') || $type === 'report' => BASE_URL . 'admin/reports.php' . $query,
            str_contains($title, 'analytics') || $type === 'analytics' => BASE_URL . 'admin/analytics.php' . $query,
            str_contains($title, 'settings') || str_contains($title, 'system') || $type === 'settings' || $type === 'system' => BASE_URL . 'admin/settings.php' . $query,
            str_contains($title, 'profile') || $type === 'profile' => BASE_URL . 'admin/profile.php' . $query,
            default => BASE_URL . 'admin/dashboard.php'
        };
    } elseif ($userRole === 'faculty') {
        return match (true) {
            str_contains($title, 'submission') || str_contains($title, 'quiz') || $type === 'assessment' => BASE_URL . 'faculty/evaluate.php',
            str_contains($title, 'student') || $type === 'student' => BASE_URL . 'faculty/students.php',
            str_contains($title, 'course') || $type === 'course' => BASE_URL . 'faculty/assessments.php',
            str_contains($title, 'lesson') || $type === 'lesson' || $type === 'question' => BASE_URL . 'faculty/question-bank.php',
            str_contains($title, 'settings') || $type === 'settings' => BASE_URL . 'faculty/profile.php#settings',
            str_contains($title, 'profile') || $type === 'profile' => BASE_URL . 'faculty/profile.php',
            default => BASE_URL . 'faculty/dashboard.php'
        };
    } else { // student
        return match (true) {
            str_contains($title, 'recommendation') || $type === 'recommendation' => BASE_URL . 'student/recommendations.php',
            str_contains($title, 'assessment') || str_contains($title, 'quiz') || $type === 'assessment' => BASE_URL . 'student/assessments.php',
            str_contains($title, 'course') || $type === 'course' => BASE_URL . 'student/courses.php',
            str_contains($title, 'settings') || $type === 'settings' => BASE_URL . 'student/settings.php',
            str_contains($title, 'profile') || $type === 'profile' => BASE_URL . 'student/profile.php',
            str_contains($title, 'progress') || $type === 'progress' => BASE_URL . 'student/progress.php',
            str_contains($title, 'roadmap') || $type === 'roadmap' => BASE_URL . 'student/roadmap.php',
            default => BASE_URL . 'student/dashboard.php'
        };
    }
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
 * Format relative time string (e.g., "5 mins ago", "2 hours ago", "Yesterday", "30 Jul 2026")
 */
function format_time_ago(?string $datetime): string {
    if (!$datetime) return 'N/A';
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $mins = (int)floor($diff / 60);
        return $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = (int)floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 172800) {
        return 'Yesterday, ' . date('h:i A', $time);
    } elseif ($diff < 604800) {
        $days = (int)floor($diff / 86400);
        return $days . ' days ago';
    } else {
        return date('d M Y, h:i A', $time);
    }
}

/**
 * Get CSS badge class for audit trail activity actions
 */
function get_action_badge_class(string $action): string {
    $act = strtoupper($action);
    if ($act === 'LOGIN') return 'badge-action-login';
    if ($act === 'LOGOUT') return 'badge-action-logout';
    if (str_contains($act, 'ANNOUNCEMENT')) return 'badge-action-announcement';
    if (str_contains($act, 'ASSESSMENT')) return 'badge-action-assessment';
    if (str_contains($act, 'COURSE')) return 'badge-action-announcement';
    return 'badge-action-default';
}

/**
 * Get CSS badge class for audit trail user roles
 */
function get_role_badge_class(?string $role): string {
    $r = strtoupper($role ?? 'SYSTEM');
    if ($r === 'ADMIN') return 'badge-role-admin';
    if ($r === 'FACULTY') return 'badge-role-faculty';
    if ($r === 'STUDENT') return 'badge-role-student';
    return 'badge-role-system';
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
         WHERE ar.student_id = ? AND a.skill_id = ? AND a.status = 'active'
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

/**
 * Calculate Student's Overall Career Match Percentage dynamically using database metrics.
 */
function calculate_student_career_match(int $studentId): int {
    $db = Database::getInstance();

    // 1. Check if the student has completed any assessments
    $hasAssessments = (int)($db->fetch(
        "SELECT COUNT(*) as cnt FROM assessment_results WHERE student_id = ? AND assessment_id IN (SELECT id FROM assessments WHERE status = 'active')",
        [$studentId]
    )['cnt'] ?? 0) > 0;

    // 2. Check if the student has any learning progress
    $hasProgress = (int)($db->fetch(
        "SELECT COUNT(*) as cnt FROM student_progress WHERE student_id = ? AND progress_percentage > 0",
        [$studentId]
    )['cnt'] ?? 0) > 0;

    // For a brand-new student with no assessments completed and no progress, return 0 (0% Career Match)
    if (!$hasAssessments && !$hasProgress) {
        return 0;
    }

    // 3. Calculate Overall Skill Proficiency (average weighted skill score across all active skills)
    $skills = $db->fetchAll("SELECT id FROM skills");
    $totalScoreSum = 0.0;
    $skillCount = count($skills);
    foreach ($skills as $s) {
        $weighted = calculate_weighted_skill_percentage($studentId, (int)$s['id']);
        $totalScoreSum += (float)$weighted['overall_percentage'];
    }
    $skillProficiency = $skillCount > 0 ? ($totalScoreSum / $skillCount) : 0.0;

    // 4. Calculate Learning/Course Progress (average progress percentage across all enrolled courses)
    $progressRow = $db->fetch("SELECT AVG(progress_percentage) as avg_prog FROM student_progress WHERE student_id = ?", [$studentId]);
    $courseProgress = (float)($progressRow['avg_prog'] ?? 0.0);

    // 5. Calculate Roadmap Progress (milestone completion percentage for their target pathway)
    $student = $db->fetch("SELECT department FROM students WHERE id = ?", [$studentId]);
    $studentDept = $student['department'] ?? '';

    $pathSkills = [
        'frontend' => [4, 5, 3, 10, 14, 11],
        'backend' => [1, 2, 7, 6],
        'fullstack' => [4, 1, 14, 13],
        'uiux' => [11, 98],
        'datascientist' => [12, 8],
        'devops' => [13, 15, 10, 18],
        'cybersecurity' => [20, 6],
        'mobile' => [88, 89]
    ];

    $roleKey = 'fullstack';
    if (stripos($studentDept, 'front') !== false) $roleKey = 'frontend';
    elseif (stripos($studentDept, 'back') !== false) $roleKey = 'backend';
    elseif (stripos($studentDept, 'data') !== false) $roleKey = 'datascientist';
    elseif (stripos($studentDept, 'sec') !== false) $roleKey = 'cybersecurity';
    elseif (stripos($studentDept, 'devops') !== false) $roleKey = 'devops';
    elseif (stripos($studentDept, 'ui') !== false) $roleKey = 'uiux';
    elseif (stripos($studentDept, 'mobile') !== false) $roleKey = 'mobile';

    $targetSkills = $pathSkills[$roleKey] ?? $pathSkills['fullstack'];
    $roadmapProgress = 0.0;

    if (!empty($targetSkills)) {
        $placeholders = implode(',', array_fill(0, count($targetSkills), '?'));
        $courses = $db->fetchAll(
            "SELECT cs.skill_id, sp.progress_percentage, sp.status 
             FROM courses c
             JOIN course_skills cs ON c.id = cs.course_id
             LEFT JOIN student_progress sp ON c.id = sp.course_id AND sp.student_id = ?
             WHERE c.status = 'active' AND cs.skill_id IN ($placeholders)",
            array_merge([$studentId], $targetSkills)
        );

        $completedSkills = [];
        foreach ($courses as $c) {
            $sId = (int)$c['skill_id'];
            $prog = (int)($c['progress_percentage'] ?? 0);
            $status = $c['status'] ?? '';
            if ($status === 'completed' || $prog >= 100) {
                $completedSkills[$sId] = true;
            }
        }

        $completedCount = 0;
        foreach ($targetSkills as $sId) {
            if (isset($completedSkills[$sId])) {
                $completedCount++;
            }
        }
        $roadmapProgress = ($completedCount / count($targetSkills)) * 100.0;
    }

    // Dynamic Career Match formula:
    // 50% Skill Proficiency + 20% Course Progress + 30% Roadmap Progress
    $careerMatch = ($skillProficiency * 0.50) + ($courseProgress * 0.20) + ($roadmapProgress * 0.30);

    // Limit/constrain to a maximum of 100% and round to nearest integer
    return (int)max(0, min(100, round($careerMatch)));
}

/**
 * Create a new announcement and automatically dispatch notifications to the target audience (EXCLUDING creator).
 */
function create_announcement(int $creatorId, string $title, string $message, string $audience = 'all', string $priority = 'normal', string $link = '#'): array {
    $db = Database::getInstance();

    // Fetch creator details
    $creator = $db->fetch("SELECT id, username, role FROM users WHERE id = ?", [$creatorId]);
    if (!$creator) {
        return ['success' => false, 'message' => 'Creator user record not found.'];
    }

    $creatorName = $creator['username'] ?? 'User #' . $creatorId;
    $creatorRole = strtolower($creator['role'] ?? 'user');

    // Auto-resolve department for faculty
    $department = null;
    if ($creatorRole === 'faculty') {
        $department = $db->fetch("SELECT department FROM faculty WHERE user_id = ?", [$creatorId])['department'] ?? null;
    }

    // 1. Insert announcement record
    $announcementId = $db->insert('announcements', [
        'created_by_user_id' => $creatorId,
        'created_by_name'    => $creatorName,
        'created_by_role'    => $creatorRole,
        'title'              => $title,
        'message'            => $message,
        'audience'           => $audience,
        'priority'           => $priority,
        'status'             => 'active',
        'link'               => $link,
        'department'         => $department,
        'created_at'         => date('Y-m-d H:i:s')
    ]);

    if (!$announcementId) {
        return ['success' => false, 'message' => 'Failed to create announcement database record.'];
    }

    // 2. Fetch audience recipients excluding creator (DO NOT notify creator)
    $sql = "SELECT id, role FROM users WHERE id != ?";
    $params = [$creatorId];

    if ($audience === 'student') {
        if ($creatorRole === 'faculty' && !empty($department)) {
            $sql = "SELECT u.id, u.role FROM users u 
                    JOIN students s ON u.id = s.user_id
                    WHERE u.id != ? AND u.role = 'student' AND s.department = ?";
            $params = [$creatorId, $department];
        } else {
            $sql .= " AND role = 'student'";
        }
    } elseif ($audience === 'faculty') {
        if ($creatorRole === 'faculty' && !empty($department)) {
            $sql = "SELECT u.id, u.role FROM users u 
                    JOIN faculty f ON u.id = f.user_id
                    WHERE u.id != ? AND u.role = 'faculty' AND f.department = ?";
            $params = [$creatorId, $department];
        } else {
            $sql .= " AND role = 'faculty'";
        }
    } elseif ($audience === 'all') {
        if ($creatorRole === 'faculty' && !empty($department)) {
            $sql = "SELECT u.id, u.role FROM users u 
                    LEFT JOIN students s ON u.id = s.user_id AND u.role = 'student'
                    LEFT JOIN faculty f ON u.id = f.user_id AND u.role = 'faculty'
                    WHERE u.id != ? AND (s.department = ? OR f.department = ?)";
            $params = [$creatorId, $department, $department];
        }
    } elseif ($audience === 'admin') {
        $sql .= " AND role = 'admin'";
    }

    $recipients = $db->fetchAll($sql, $params);
    $notifCount = 0;

    $notifTitle = 'New Announcement';
    $notifMsg = "{$creatorName} published a new announcement: {$title}";

    foreach ($recipients as $r) {
        $recipientRole = strtolower($r['role']);
        $notifLink = match($recipientRole) {
            'admin'   => BASE_URL . 'admin/announcements.php',
            'faculty' => BASE_URL . 'faculty/announcements.php',
            default   => BASE_URL . 'student/notification.php'
        };

        if ($link !== '#' && !empty($link)) {
            $notifLink = $link;
        }

        $db->insert('notifications', [
            'user_id'            => $r['id'],
            'title'              => $notifTitle,
            'message'            => $notifMsg,
            'link'               => $notifLink,
            'is_read'            => 0,
            'type'               => 'announcement',
            'announcement_id'    => $announcementId,
            'created_by_user_id' => $creatorId,
            'created_by_role'    => $creatorRole,
            'created_at'         => date('Y-m-d H:i:s')
        ]);
        $notifCount++;
    }

    log_activity($creatorId, 'ANNOUNCEMENT_CREATED', "Created announcement #{$announcementId}: '{$title}' sent to {$notifCount} recipients.");

    return [
        'success'         => true,
        'message'         => "Announcement broadcasted successfully to {$notifCount} user accounts!",
        'announcement_id' => $announcementId,
        'recipient_count' => $notifCount
    ];
}

/**
 * Edit/Update an announcement with strict role-based ownership validation.
 */
function update_announcement(int $announcementId, int $currentUserId, string $currentUserRole, string $title, string $message, string $audience = 'all', string $priority = 'normal', string $link = '#'): array {
    $db = Database::getInstance();
    $ann = $db->fetch("SELECT * FROM announcements WHERE id = ?", [$announcementId]);

    if (!$ann) {
        return ['success' => false, 'message' => 'Announcement not found.'];
    }

    // Role-based authorization check:
    // Admin can edit any announcement; Faculty can edit ONLY their own.
    if ($currentUserRole !== 'admin' && (int)$ann['created_by_user_id'] !== $currentUserId) {
        return ['success' => false, 'message' => 'Access Denied: You can only edit announcements created by yourself.'];
    }

    $department = $ann['department'];
    if ($currentUserRole === 'faculty' && empty($department)) {
        $department = $db->fetch("SELECT department FROM faculty WHERE user_id = ?", [$currentUserId])['department'] ?? null;
    }

    $db->update('announcements', [
        'title'      => $title,
        'message'    => $message,
        'audience'   => $audience,
        'priority'   => $priority,
        'link'       => $link,
        'department' => $department,
        'updated_at' => date('Y-m-d H:i:s')
    ], 'id = ?', [$announcementId]);

    // Update corresponding notification titles and messages
    $creatorName = $ann['created_by_name'];
    $notifMsg = "{$creatorName} published a new announcement: {$title}";

    $db->update('notifications', [
        'message' => $notifMsg
    ], 'announcement_id = ?', [$announcementId]);

    log_activity($currentUserId, 'ANNOUNCEMENT_UPDATED', "Updated announcement #{$announcementId}: '{$title}'.");

    return ['success' => true, 'message' => 'Announcement updated successfully.'];
}

/**
 * Delete an announcement with strict role-based ownership validation.
 */
function delete_announcement(int $announcementId, int $currentUserId, string $currentUserRole): array {
    $db = Database::getInstance();
    $ann = $db->fetch("SELECT * FROM announcements WHERE id = ?", [$announcementId]);

    if (!$ann) {
        return ['success' => false, 'message' => 'Announcement not found.'];
    }

    // Role-based authorization check:
    // Admin can delete any announcement; Faculty can delete ONLY their own.
    if ($currentUserRole !== 'admin' && (int)$ann['created_by_user_id'] !== $currentUserId) {
        return ['success' => false, 'message' => 'Access Denied: You can only delete announcements created by yourself.'];
    }

    // Delete related notification records first
    $db->delete('notifications', 'announcement_id = ?', [$announcementId]);
    // Delete announcement record
    $db->delete('announcements', 'id = ?', [$announcementId]);

    log_activity($currentUserId, 'ANNOUNCEMENT_DELETED', "Deleted announcement #{$announcementId}.");

    return ['success' => true, 'message' => 'Announcement deleted successfully.'];
}

/**
 * Retrieve a system setting value dynamically from system_settings table.
 *
 * @param string $key
 * @param mixed $default
 * @return string
 */
function get_system_setting(string $key, $default = ''): string {
    try {
        $db = Database::getInstance();
        $row = $db->fetch("SELECT setting_value FROM system_settings WHERE setting_key = ?", [$key]);
        return $row ? (string)$row['setting_value'] : (string)$default;
    } catch (Exception $e) {
        return (string)$default;
    }
}

/**
 * Ensure all 150 Category + Skill + Difficulty Level question banks exist in the database.
 */
function ensure_all_question_banks_exist(Database $db) {
    $categoriesStructure = [
        'Frontend Development' => ['HTML', 'CSS', 'JavaScript', 'Bootstrap', 'Tailwind CSS', 'React', 'Angular', 'Vue.js', 'jQuery', 'TypeScript'],
        'Backend Development' => ['C', 'C++', 'Java', 'Python', 'PHP', 'C#', 'Node.js', 'SQL', 'MySQL', 'MongoDB'],
        'Full Stack Development' => ['MERN Stack', 'MEAN Stack', 'Laravel', 'Django', 'Express.js', 'Next.js', 'ASP.NET', 'Spring Boot', 'Flask', 'REST API']
    ];
    
    $diffLevels = ['beginner', 'intermediate', 'advanced', 'professional', 'expert'];
    
    $existingList = $db->fetchAll("SELECT category, skill, difficulty FROM question_banks");
    $existingMap = [];
    foreach ($existingList as $eb) {
        $key = strtolower(trim($eb['category'] ?? '')) . '||' . strtolower(trim($eb['skill'] ?? '')) . '||' . strtolower(trim($eb['difficulty'] ?? ''));
        $existingMap[$key] = true;
    }
    
    $facultyRow = $db->fetch("SELECT id FROM faculty LIMIT 1");
    $facultyId = $facultyRow ? (int)$facultyRow['id'] : 1;
    
    foreach ($categoriesStructure as $cat => $skills) {
        foreach ($skills as $sk) {
            foreach ($diffLevels as $diff) {
                $key = strtolower(trim($cat)) . '||' . strtolower(trim($sk)) . '||' . strtolower(trim($diff));
                if (!isset($existingMap[$key])) {
                    $db->insert('question_banks', [
                        'title' => $sk . ' ' . ucfirst($diff) . ' Bank',
                        'category' => $cat,
                        'skill' => $sk,
                        'difficulty' => $diff,
                        'status' => 'draft',
                        'created_by_faculty_id' => $facultyId,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
    }
}

/**
 * Automatically synchronize the assessments table based on valid, published question banks.
 */
function sync_assessments_table(Database $db, bool $force = false) {
    if (!$force) {
        $lastSync = get_system_setting('last_assessment_sync_time', '0');
        if (time() - (int)$lastSync < 900) {
            return;
        }
    }

    // Ensure all 150 question pools are seeded/initialized in the DB
    ensure_all_question_banks_exist($db);

    // 1. Get all published question banks with their question counts
    $publishedBanks = $db->fetchAll(
        "SELECT qb.*, 
                (SELECT COUNT(*) FROM questions WHERE question_bank_id = qb.id) as q_count 
         FROM question_banks qb"
    );
    
    // Get all skill mappings
    $skillsList = $db->fetchAll("SELECT id, name, category FROM skills");
    $skillNameToId = [];
    foreach ($skillsList as $sk) {
        $key = strtolower(trim($sk['category'])) . '||' . strtolower(trim($sk['name']));
        $skillNameToId[$key] = (int)$sk['id'];
    }
    
    $validQbIds = [];
    
    foreach ($publishedBanks as $qb) {
        $qbId = (int)$qb['id'];
        $qCount = (int)$qb['q_count'];
        $isPublished = (strtolower($qb['status']) === 'published');
        $hasRequiredQuestions = ($qCount >= 25);
        $skillNameLower = strtolower(trim($qb['skill']));
        $categoryLower = strtolower(trim($qb['category']));
        $key = $categoryLower . '||' . $skillNameLower;
        $skillId = $skillNameToId[$key] ?? null;
        
        if ($skillId && $isPublished && $hasRequiredQuestions) {
            $validQbIds[] = $qbId;
            
            // Determine difficulty label
            $diffLabel = strtolower(trim($qb['difficulty']));
            if (!in_array($diffLabel, ['beginner', 'intermediate', 'advanced', 'professional', 'expert'])) {
                $diffLabel = 'beginner';
            }
            
            // Format title: "HTML - Beginner", "C++ - Advanced", etc.
            $title = $qb['skill'] . ' - ' . ucfirst($diffLabel);
            $duration = max(15, $qCount * 1);
            $totalMarks = $qCount;
            $passThreshold = (float)get_system_setting('pass_mark_threshold', 60);
            $passingMarks = (int)round($totalMarks * ($passThreshold / 100.0));
            
            // Check if assessment already exists for this question bank
            $existing = $db->fetch("SELECT * FROM assessments WHERE question_bank_id = ?", [$qbId]);
            
            if ($existing) {
                // Update properties
                $db->update('assessments', [
                    'title' => $title,
                    'skill_id' => $skillId,
                    'duration_minutes' => $duration,
                    'total_marks' => $totalMarks,
                    'passing_marks' => $passingMarks,
                    'difficulty_level' => $diffLabel,
                    'status' => 'active'
                ], 'id = ?', [$existing['id']]);
            } else {
                // Insert new assessment
                $db->insert('assessments', [
                    'title' => $title,
                    'description' => $qb['skill'] . ' ' . ucfirst($diffLabel) . ' level assessment.',
                    'skill_id' => $skillId,
                    'created_by_faculty_id' => (int)($qb['created_by_faculty_id'] ?? 1),
                    'duration_minutes' => $duration,
                    'passing_marks' => $passingMarks,
                    'total_marks' => $totalMarks,
                    'difficulty_level' => $diffLabel,
                    'status' => 'active',
                    'question_bank_id' => $qbId
                ]);
            }
        } else {
            // If it exists in assessments but is no longer valid, set its status to draft
            $existing = $db->fetch("SELECT * FROM assessments WHERE question_bank_id = ?", [$qbId]);
            if ($existing) {
                $db->update('assessments', ['status' => 'draft'], 'id = ?', [$existing['id']]);
            }
        }
    }
    
    // Disable any assessments whose question bank is completely missing/deleted/draft
    if (!empty($validQbIds)) {
        $inClause = implode(',', $validQbIds);
        $db->query("UPDATE assessments SET status = 'draft' WHERE question_bank_id NOT IN ($inClause) OR question_bank_id IS NULL");
    } else {
        $db->query("UPDATE assessments SET status = 'draft'");
    }

    // Save last sync timestamp
    $db->query("INSERT INTO system_settings (setting_key, setting_value) VALUES ('last_assessment_sync_time', ?) 
                ON DUPLICATE KEY UPDATE setting_value = ?", [time(), time()]);
}

/**
 * Invalidate the assessment sync cache by setting the sync time to 0.
 */
function invalidate_assessment_sync_cache(Database $db): void {
    $db->query("INSERT INTO system_settings (setting_key, setting_value) VALUES ('last_assessment_sync_time', '0') 
                ON DUPLICATE KEY UPDATE setting_value = '0'");
}
