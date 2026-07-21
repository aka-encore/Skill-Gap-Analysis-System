<?php
/**
 * SkillBridge - Authentication & Role Authorization Guard
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

/**
 * Check if a user session is active
 */
function is_logged_in(): bool {
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        // Enforce session timeout check (e.g., 1 hour)
        $timeout = 3600;
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
            logout_user();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }
    return check_remember_token();
}

/**
 * Get currently authenticated user data array
 */
function get_logged_in_user(): ?array {
    if (!is_logged_in()) {
        return null;
    }
    $db = Database::getInstance();
    $user = $db->fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
    if (!$user) return null;

    $profile = get_user_profile_data($user['id'], $user['role']);
    return array_merge($user, $profile ?? []);
}

/**
 * Require active authentication session
 */
function require_login(): void {
    if (!is_logged_in()) {
        set_flash_message('warning', 'Please log in to access this page.');
        redirect(BASE_URL . 'login.php');
    }
}

/**
 * Enforce role authorization guard
 * @param array|string $allowedRoles Single role string or array of allowed roles (e.g. ['student', 'faculty'])
 */
function require_role($allowedRoles): void {
    require_login();
    $allowed = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];
    $currentRole = $_SESSION['user_role'] ?? '';

    if (!in_array($currentRole, $allowed)) {
        set_flash_message('danger', 'Unauthorized Access. You do not have permission to view that page.');
        // Redirect to appropriate dashboard
        match ($currentRole) {
            'student' => redirect(BASE_URL . 'student/dashboard.php'),
            'faculty' => redirect(BASE_URL . 'faculty/dashboard.php'),
            'admin'   => redirect(BASE_URL . 'admin/dashboard.php'),
            default   => redirect(BASE_URL . 'login.php')
        };
    }
}

/**
 * Authenticate and log in user
 */
function login_user(array $user, bool $remember = false): void {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['last_activity'] = time();

    // Fetch entity specific profile ID
    $db = Database::getInstance();
    if ($user['role'] === 'student') {
        $st = $db->fetch("SELECT id, first_name, last_name, avatar FROM students WHERE user_id = ?", [$user['id']]);
        $_SESSION['profile_id'] = $st['id'] ?? null;
        $_SESSION['full_name'] = trim(($st['first_name'] ?? '') . ' ' . ($st['last_name'] ?? ''));
        $_SESSION['avatar'] = $st['avatar'] ?? 'default-avatar.png';
    } elseif ($user['role'] === 'faculty') {
        $fc = $db->fetch("SELECT id, first_name, last_name, avatar FROM faculty WHERE user_id = ?", [$user['id']]);
        $_SESSION['profile_id'] = $fc['id'] ?? null;
        $_SESSION['full_name'] = trim(($fc['first_name'] ?? '') . ' ' . ($fc['last_name'] ?? ''));
        $_SESSION['avatar'] = $fc['avatar'] ?? 'default-avatar.png';
    } elseif ($user['role'] === 'admin') {
        $ad = $db->fetch("SELECT id, first_name, last_name, avatar FROM admins WHERE user_id = ?", [$user['id']]);
        $_SESSION['profile_id'] = $ad['id'] ?? null;
        $_SESSION['full_name'] = trim(($ad['first_name'] ?? '') . ' ' . ($ad['last_name'] ?? ''));
        $_SESSION['avatar'] = $ad['avatar'] ?? 'default-avatar.png';
    }

    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $db->update('users', ['remember_token' => $token], 'id = ?', [$user['id']]);
        setcookie('remember_token', $token, time() + (86400 * 30), "/", "", false, true); // 30 days
    }

    log_activity($user['id'], 'LOGIN', "User {$user['username']} logged in successfully as {$user['role']}.");
}

/**
 * Logout user session
 */
function logout_user(): void {
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }
    if (isset($_SESSION['user_id'])) {
        log_activity($_SESSION['user_id'], 'LOGOUT', "User {$_SESSION['username']} logged out.");
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies") && !headers_sent()) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    if (isset($_COOKIE['remember_token']) && !headers_sent()) {
        setcookie('remember_token', '', time() - 3600, "/");
    }
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}

/**
 * Check remember token cookie for persistent session
 */
function check_remember_token(): bool {
    if (isset($_COOKIE['remember_token']) && !empty($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        $db = Database::getInstance();
        $user = $db->fetch("SELECT * FROM users WHERE remember_token = ?", [$token]);
        if ($user) {
            login_user($user, false);
            return true;
        }
    }
    return false;
}
