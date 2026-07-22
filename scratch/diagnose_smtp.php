<?php
ob_implicit_flush(true);
while (ob_get_level()) ob_end_flush();

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "STARTING DIAGNOSTIC SCRIPT\n";

$host = 'smtp.gmail.com';
$user = 'skill.profile.project1@gmail.com';
$pass_raw = 'oowq qnye vivy xkrc';
$pass_clean = 'oowqqnyevivyxkrc';

echo "1. Resolving hostname...\n";
$ip = gethostbyname($host);
echo "Resolved IP for $host: $ip\n";

echo "2. Testing fsockopen to $host:587...\n";
$t1 = microtime(true);
$fp = @fsockopen($host, 587, $errno, $errstr, 5);
$t2 = microtime(true);
echo "fsockopen result: " . ($fp ? "SUCCESS" : "FAILED ($errno: $errstr)") . " in " . round($t2-$t1, 2) . "s\n";
if ($fp) {
    echo "Banner: " . trim(fgets($fp, 512)) . "\n";
    fclose($fp);
}

echo "3. Testing fsockopen to direct IP $ip:587...\n";
$fp2 = @fsockopen($ip, 587, $errno, $errstr, 5);
echo "fsockopen IP result: " . ($fp2 ? "SUCCESS" : "FAILED ($errno: $errstr)") . "\n";
if ($fp2) {
    echo "Banner: " . trim(fgets($fp2, 512)) . "\n";
    fclose($fp2);
}

echo "4. Testing fsockopen to ssl://$host:465...\n";
$fp3 = @fsockopen("ssl://$host", 465, $errno, $errstr, 5);
echo "fsockopen SSL 465 result: " . ($fp3 ? "SUCCESS" : "FAILED ($errno: $errstr)") . "\n";
if ($fp3) {
    echo "Banner: " . trim(fgets($fp3, 512)) . "\n";
    fclose($fp3);
}

echo "5. Testing PHPMailer with clean password on $host:587...\n";
$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function($str, $level) {
        echo "  [DBG] $str\n";
    };
    $mail->isSMTP();
    $mail->Host = $host;
    $mail->SMTPAuth = true;
    $mail->Username = $user;
    $mail->Password = $pass_clean;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->Timeout = 10;

    $mail->setFrom($user, 'Test');
    $mail->addAddress($user);
    $mail->Subject = 'Test Subject';
    $mail->Body = 'Test Body';

    $mail->send();
    echo "PHPMailer send: SUCCESS\n";
} catch (Exception $e) {
    echo "PHPMailer send FAILED: " . $mail->ErrorInfo . "\n";
    echo "Exception: " . $e->getMessage() . "\n";
}

echo "DIAGNOSTIC SCRIPT COMPLETED\n";
