<?php
/**
 * SkillBridge - Logout Session Handler
 * Destroys all active session & cookie data and redirects user to index.php landing page.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

// Perform complete session & cookie cleanup
logout_user();

// Redirect user to index.php landing page with success query parameter
redirect(BASE_URL . 'index.php?logout=success');
