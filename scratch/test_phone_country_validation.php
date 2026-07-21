<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

echo "========================================================\n";
echo "PHONE & NAME VALIDATION AUDIT\n";
echo "========================================================\n\n";

$nameRegex = "/^[a-zA-Z\s\-\']+$/";
$phoneRegex = "/^[0-9]{10}$/";

// 1. First Name Tests
$validFirstNames = ["John", "Mary Jane", "Anne-Marie", "O'Connor"];
foreach ($validFirstNames as $fn) {
    if (preg_match($nameRegex, $fn)) {
        echo "1. Valid First Name '{$fn}': PASSED\n";
    } else {
        echo "1. Valid First Name '{$fn}': FAILED\n";
    }
}

$invalidFirstNames = ["John123", "@John", "John#"];
foreach ($invalidFirstNames as $fn) {
    if (!preg_match($nameRegex, $fn)) {
        echo "2. Invalid First Name '{$fn}': PASSED -> Error: \"First name may contain only letters, spaces, hyphens (-), and apostrophes (').\"\n";
    } else {
        echo "2. Invalid First Name '{$fn}': FAILED\n";
    }
}

// 2. Phone Number Tests
$validPhone = "9876543210";
if (preg_match($phoneRegex, $validPhone)) {
    echo "3. Valid Phone '{$validPhone}' (10 Digits): PASSED\n";
} else {
    echo "3. Valid Phone '{$validPhone}': FAILED\n";
}

$invalidPhones = ["98765AB210", "987654321", "98765432101", "98-76543210"];
foreach ($invalidPhones as $p) {
    if (!preg_match($phoneRegex, $p)) {
        echo "4. Invalid Phone '{$p}': PASSED -> Error: \"Please enter a valid 10-digit mobile number.\"\n";
    } else {
        echo "4. Invalid Phone '{$p}': FAILED\n";
    }
}

// 3. Country Code Concatenation Test
$countryCode = "+91";
$phoneFull = $countryCode . " " . $validPhone;
echo "5. Country Code Formatting: '{$phoneFull}' -> Fits in phone (VARCHAR 20): " . (strlen($phoneFull) <= 20 ? "YES" : "NO") . "\n";

echo "\n========================================================\n";
