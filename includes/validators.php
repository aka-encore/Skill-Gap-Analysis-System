<?php
/**
 * SkillBridge - CSRF Security & Form Validation Module
 */

require_once __DIR__ . '/../config/config.php';

/**
 * Generate CSRF Token HTML field
 */
function csrf_field(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

/**
 * Verify CSRF Token from POST request
 */
function verify_csrf_token(): bool {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
    }
    return true;
}

/**
 * Validate required fields in input array
 */
function validate_required(array $input, array $fields): array {
    $errors = [];
    foreach ($fields as $field => $label) {
        if (!isset($input[$field]) || trim((string)$input[$field]) === '') {
            $errors[$field] = "{$label} is required.";
        }
    }
    return $errors;
}

/**
 * Validate email address format
 */
function validate_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
