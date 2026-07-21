<?php
/**
 * SkillBridge - Logout Session Handler
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

logout_user();
set_flash_message('info', 'You have been logged out successfully.');
redirect(BASE_URL . 'login.php');
