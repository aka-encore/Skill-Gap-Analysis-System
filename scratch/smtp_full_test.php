<?php
header('Content-Type: text/plain');
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "=====================================================" . PHP_EOL;
echo "  FULL PHPMailer SMTP DEBUG & AUTHENTICATION AUDIT   " . PHP_EOL;
echo "=====================================================" . PHP_EOL;
echo "Current Time: " . date('Y-m-d H:i:s T') . PHP_EOL;
echo "PHP Version: " . PHP_VERSION . PHP_EOL;
echo "OpenSSL Extension: " . (extension_loaded('openssl') ? 'ENABLED (' . OPENSSL_VERSION_TEXT . ')' : 'DISABLED!') . PHP_EOL;
echo PHP_EOL;

echo "--- Loaded Constants ---" . PHP_EOL;
echo "SMTP_HOST: " . SMTP_HOST . PHP_EOL;
echo "SMTP_PORT: " . SMTP_PORT . PHP_EOL;
echo "SMTP_USER: " . SMTP_USER . PHP_EOL;
echo "SMTP_PASS constant: " . SMTP_PASS . PHP_EOL;
echo "Sanitized Pass: " . str_replace(' ', '', SMTP_PASS) . PHP_EOL;
echo "SMTP_FROM_EMAIL: " . SMTP_FROM_EMAIL . PHP_EOL;
echo PHP_EOL;

echo "-----------------------------------------------------" . PHP_EOL;
echo " TEST 1: Port 587 (STARTTLS) with Sanitized Password " . PHP_EOL;
echo "-----------------------------------------------------" . PHP_EOL;
run_smtp_debug(SMTP_HOST, 587, PHPMailer::ENCRYPTION_STARTTLS, SMTP_USER, str_replace(' ', '', SMTP_PASS));

echo PHP_EOL;
echo "-----------------------------------------------------" . PHP_EOL;
echo " TEST 2: Port 465 (SSL/SMTPS) with Sanitized Password " . PHP_EOL;
echo "-----------------------------------------------------" . PHP_EOL;
run_smtp_debug(SMTP_HOST, 465, PHPMailer::ENCRYPTION_SMTPS, SMTP_USER, str_replace(' ', '', SMTP_PASS));

echo PHP_EOL;
echo "-----------------------------------------------------" . PHP_EOL;
echo " TEST 3: Direct Call to send_otp_email() Function    " . PHP_EOL;
echo "-----------------------------------------------------" . PHP_EOL;
$res = send_otp_email(SMTP_USER, '123456');
echo "send_otp_email Result: " . print_r($res, true) . PHP_EOL;


function run_smtp_debug($host, $port, $crypto, $user, $pass) {
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Level 3 / Server debug
        $mail->Debugoutput = function($str, $level) {
            echo "  [SMTP-DBG $level] " . trim($str) . PHP_EOL;
        };
        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $user;
        $mail->Password   = $pass;
        $mail->SMTPSecure = $crypto;
        $mail->Port       = $port;
        $mail->Timeout    = 15;

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($user);

        $mail->isHTML(false);
        $mail->Subject = 'SkillBridge SMTP Diagnostic Test';
        $mail->Body    = 'Testing SMTP Authentication for SkillBridge LMS.';

        $mail->send();
        echo "==> RESULT: SUCCESS! Email sent successfully." . PHP_EOL;
    } catch (Exception $e) {
        echo "==> RESULT: FAILED!" . PHP_EOL;
        echo "    ErrorInfo: " . $mail->ErrorInfo . PHP_EOL;
        echo "    Exception: " . $e->getMessage() . PHP_EOL;
    }
}
