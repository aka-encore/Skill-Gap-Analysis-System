<?php
session_start();
ob_start();
require_once __DIR__ . '/../login.php';
$output = ob_get_clean();

echo "========================================================\n";
echo "LOGIN ROLE SWITCH CLEARING AUDIT\n";
echo "========================================================\n\n";

if (strpos($output, 'id="alertContainer"') !== false) {
    echo "1. alertContainer div wrapper: FOUND\n";
} else {
    echo "1. alertContainer div wrapper: NOT FOUND\n";
}

if (strpos($output, "alertContainer.innerHTML = ''") !== false && strpos($output, "alert.style.display = 'none'") !== false) {
    echo "2. Alert hiding JS logic in selectRole(): FOUND\n";
} else {
    echo "2. Alert hiding JS logic: NOT FOUND\n";
}

if (strpos($output, "input.classList.remove('is-invalid', 'is-valid', 'border-danger')") !== false) {
    echo "3. Form input validation clearing JS logic: FOUND\n";
} else {
    echo "3. Form input validation clearing JS logic: NOT FOUND\n";
}

echo "\n========================================================\n";
