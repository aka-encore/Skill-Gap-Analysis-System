<?php
echo "Testing PHP Syntax Check..." . PHP_EOL;

$files = [
    __DIR__ . '/../config/mail.php',
    __DIR__ . '/../register.php',
    __DIR__ . '/../verify-email.php',
    __DIR__ . '/../forgot-password.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "File not found: $file" . PHP_EOL;
        continue;
    }
    $code = file_get_contents($file);
    // Basic PHP tokens check
    try {
        token_get_all($code, TOKEN_PARSE);
        echo "[OK] " . basename($file) . " passed PHP token parsing." . PHP_EOL;
    } catch (Throwable $t) {
        echo "[ERROR] " . basename($file) . " syntax error: " . $t->getMessage() . PHP_EOL;
    }
}
