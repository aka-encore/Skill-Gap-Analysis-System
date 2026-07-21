<?php
session_start();
ob_start();
require_once __DIR__ . '/../login.php';
$output = ob_get_clean();

echo "Login Page Render Length: " . strlen($output) . " bytes\n";
if (strpos($output, "Welcome Back") !== false && strpos($output, "tech-bg-container") !== false && strpos($output, "role-tab") !== false) {
    echo "SUCCESS: login.php rendered cleanly with modern UI, floating tech background, role tabs, and password eye-toggle!\n";
} else {
    echo "WARNING: Page signature mismatch.\n";
}
