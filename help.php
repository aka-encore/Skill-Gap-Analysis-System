<?php
/**
 * SkillBridge - Root Help Center Dispatcher
 * Seamlessly routes logged in students/faculty to their respective Help modules.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    $role = $_SESSION['user_role'] ?? 'student';
    if ($role === 'faculty') {
        header("Location: " . BASE_URL . "faculty/help.php");
        exit;
    } else {
        header("Location: " . BASE_URL . "student/help.php");
        exit;
    }
} else {
    header("Location: " . BASE_URL . "login.php?redirect=student/help.php");
    exit;
}
