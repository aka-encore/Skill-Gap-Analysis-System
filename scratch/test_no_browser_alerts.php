<?php
session_start();
ob_start();
require __DIR__ . '/../register.php';
$output = ob_get_clean();

echo "========================================================\n";
echo "ZERO BROWSER ALERT AUDIT FOR REGISTER.PHP\n";
echo "========================================================\n\n";

// 1. Check for browser alert()
if (!preg_match("/\balert\s*\(/", $output)) {
    echo "1. Browser alert() check: PASSED (0 occurrences of browser alert() found)\n";
} else {
    echo "1. Browser alert() check: FAILED (Found browser alert() call!)\n";
}

// 2. Check for Toast Notifications
if (strpos($output, "toastNotificationContainer") !== false && strpos($output, "showToastNotification") !== false) {
    echo "2. In-Page Toast Notification Helper: FOUND & INTEGRATED\n";
} else {
    echo "2. Toast Notification Helper: NOT FOUND\n";
}

// 3. Check for Inline Field Errors
if (strpos($output, "showFieldError") !== false && strpos($output, "clearFieldErrors") !== false) {
    echo "3. Inline Field Error Highlighting & Auto-Clear: FOUND & INTEGRATED\n";
} else {
    echo "3. Inline Field Error Highlighting: NOT FOUND\n";
}

echo "\n========================================================\n";
