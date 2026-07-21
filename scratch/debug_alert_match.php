<?php
session_start();
ob_start();
require __DIR__ . '/../register.php';
$output = ob_get_clean();

$lines = explode("\n", $output);
foreach ($lines as $idx => $line) {
    if (preg_match("/\balert\s*\(/", $line)) {
        echo "Match on line " . ($idx + 1) . ": " . trim($line) . "\n";
    }
}
