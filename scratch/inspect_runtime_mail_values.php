<?php
header('Content-Type: text/plain');
require_once __DIR__ . '/../config/mail.php';

echo "==========================================================" . PHP_EOL;
echo "  RUNTIME SMTP CREDENTIAL & ENCODING INSPECTOR           " . PHP_EOL;
echo "==========================================================" . PHP_EOL;

$pass_raw = SMTP_PASS;
$pass_clean = str_replace(' ', '', $pass_raw);

function mask_secret($str) {
    $len = strlen($str);
    if ($len <= 4) return '****';
    return substr($str, 0, 2) . str_repeat('*', $len - 4) . substr($str, -2);
}

echo "1. SMTP_USER: " . SMTP_USER . PHP_EOL;
echo "2. SMTP_PASS constant: " . mask_secret($pass_raw) . " (Length: " . strlen($pass_raw) . ")" . PHP_EOL;
echo "3. Sanitized Pass:     " . mask_secret($pass_clean) . " (Length: " . strlen($pass_clean) . ")" . PHP_EOL;
echo "4. Hex bytes of raw pass:   " . bin2hex($pass_raw) . PHP_EOL;
echo "5. Hex bytes of clean pass: " . bin2hex($pass_clean) . PHP_EOL;

echo PHP_EOL . "--- Byte-by-Byte Inspection of Sanitized Password ---" . PHP_EOL;
for ($i = 0; $i < strlen($pass_clean); $i++) {
    $char = $pass_clean[$i];
    $ord = ord($char);
    echo sprintf("  Position %2d: Character '%s' (ASCII %3d, Hex 0x%02X)", $i + 1, $char, $ord, $ord) . PHP_EOL;
}

echo PHP_EOL . "--- File Loading Verification ---" . PHP_EOL;
$included = get_included_files();
echo "Included files at runtime:" . PHP_EOL;
foreach ($included as $f) {
    if (str_contains($f, 'Skill-Gap-Analysis-System')) {
        echo "  - " . str_replace(realpath(__DIR__ . '/..'), '', $f) . PHP_EOL;
    }
}
