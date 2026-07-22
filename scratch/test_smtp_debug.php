<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "=== TESTING SMTP CONNECTION ===" . PHP_EOL;
echo "SMTP_HOST: " . SMTP_HOST . PHP_EOL;
echo "SMTP_PORT: " . SMTP_PORT . PHP_EOL;
echo "SMTP_USER: " . SMTP_USER . PHP_EOL;
echo "SMTP_PASS raw: " . SMTP_PASS . PHP_EOL;
echo "SMTP_PASS stripped: " . str_replace(' ', '', SMTP_PASS) . PHP_EOL;

echo PHP_EOL . "--- Test 1: Using raw SMTP_PASS ('oowq qnye vivy xkrc') with STARTTLS (Port 587) ---" . PHP_EOL;
test_mail(SMTP_PASS, PHPMailer::ENCRYPTION_STARTTLS, 587);

echo PHP_EOL . "--- Test 2: Using stripped SMTP_PASS ('oowqqnyevivyxkrc') with STARTTLS (Port 587) ---" . PHP_EOL;
test_mail(str_replace(' ', '', SMTP_PASS), PHPMailer::ENCRYPTION_STARTTLS, 587);

echo PHP_EOL . "--- Test 3: Using stripped SMTP_PASS with SMTPS (Port 465) ---" . PHP_EOL;
test_mail(str_replace(' ', '', SMTP_PASS), PHPMailer::ENCRYPTION_SMTPS, 465);

function test_mail($pass, $secure, $port) {
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = function($str, $level) {
            echo "  [SMTP DEBUG $level] $str" . PHP_EOL;
        };
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = $pass;
        $mail->SMTPSecure = $secure;
        $mail->Port       = $port;
        $mail->Timeout    = 10;

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress(SMTP_USER);

        $mail->isHTML(false);
        $mail->Subject = 'SMTP Test';
        $mail->Body    = 'This is a test email.';

        $mail->send();
        echo "RESULT: SUCCESS!" . PHP_EOL;
    } catch (Exception $e) {
        echo "RESULT: FAILED! Error: " . $mail->ErrorInfo . PHP_EOL;
        echo "Exception message: " . $e->getMessage() . PHP_EOL;
    }
}
