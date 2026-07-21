<?php
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = 1;

ob_start();
include __DIR__ . '/../student/dashboard.php';
$html = ob_get_clean();

echo "SUCCESS: Output HTML Length = " . strlen($html) . " bytes\n";
