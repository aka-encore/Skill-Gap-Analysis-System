<?php
/**
 * SkillBridge - Assessment History Redirect Handler
 */
require_once __DIR__ . '/../config/config.php';
header("Location: " . BASE_URL . "student/assessments.php");
exit;

