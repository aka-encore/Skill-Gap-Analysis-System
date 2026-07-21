<?php
/**
 * SkillBridge - Legacy History Redirect to Notification Center
 */
require_once __DIR__ . '/../config/config.php';
header("Location: " . BASE_URL . "student/notification.php");
exit;
