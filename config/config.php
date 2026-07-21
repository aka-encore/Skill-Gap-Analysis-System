<?php
/**
 * SkillBridge - System Configuration Settings
 * PHP 8.x Pure PHP Implementation
 */

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// Set default timezone
date_default_timezone_set('Asia/Kolkata');

// Application Constants
define('APP_NAME', 'SkillBridge');
define('APP_SUBTITLE', 'Skill Gap Analysis & LMS');
define('APP_VERSION', '1.0.0');

// Database Configuration Parameters (XAMPP Defaults)
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'skillbridge_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// File Upload Settings
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('AVATAR_UPLOAD_DIR', UPLOAD_DIR . 'avatars/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB limit
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Auto-detect Base URL dynamically
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

// Find relative path to project root
$project_root = rtrim($script_name, '/');
// Strip subdirectories like /student, /faculty, /admin, /api, /reports
$subdirs = ['/student', '/faculty', '/admin', '/api', '/reports', '/config', '/includes'];
foreach ($subdirs as $subdir) {
    if (str_ends_with($project_root, $subdir)) {
        $project_root = substr($project_root, 0, -strlen($subdir));
    }
}
define('BASE_URL', $protocol . $host . $project_root . '/');

// Error Reporting (Turn on for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure upload directories exist
if (!file_exists(AVATAR_UPLOAD_DIR)) {
    @mkdir(AVATAR_UPLOAD_DIR, 0777, true);
}
