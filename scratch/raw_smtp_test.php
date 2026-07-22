<?php
header('Content-Type: text/plain');

echo "==========================================================" . PHP_EOL;
echo "  INDEPENDENT RAW SOCKET SMTP AUTHENTICATION TEST         " . PHP_EOL;
echo "==========================================================" . PHP_EOL;

$user = 'skill.profile.project1@gmail.com';
$pass = 'ltfqpmdtxvmryyed';

echo "Username: $user" . PHP_EOL;
echo "Password (Clean): $pass" . PHP_EOL;
echo "Base64 User: " . base64_encode($user) . PHP_EOL;
echo "Base64 Pass: " . base64_encode($pass) . PHP_EOL;
echo PHP_EOL;

// 1. Raw Socket Test over STARTTLS Port 587
echo "--- RAW SOCKET TEST 1: smtp.gmail.com:587 (STARTTLS) ---" . PHP_EOL;
test_raw_smtp('smtp.gmail.com', 587, false, $user, $pass);

echo PHP_EOL;

// 2. Raw Socket Test over SSL Port 465
echo "--- RAW SOCKET TEST 2: ssl://smtp.gmail.com:465 (SSL) ---" . PHP_EOL;
test_raw_smtp('ssl://smtp.gmail.com', 465, true, $user, $pass);

function test_raw_smtp($host, $port, $isSsl, $user, $pass) {
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ]);
    
    $fp = @stream_socket_client("$host:$port", $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $context);
    if (!$fp) {
        echo "  [ERROR] Connection failed: $errstr ($errno)" . PHP_EOL;
        return;
    }
    
    $response = fgets($fp, 512);
    echo "  S: $response";
    
    // EHLO
    fputs($fp, "EHLO localhost\r\n");
    echo "  C: EHLO localhost" . PHP_EOL;
    read_reply($fp);
    
    if (!$isSsl) {
        // STARTTLS
        fputs($fp, "STARTTLS\r\n");
        echo "  C: STARTTLS" . PHP_EOL;
        $reply = fgets($fp, 512);
        echo "  S: $reply";
        
        // Crypto handshake
        $crypto_res = stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT);
        if (!$crypto_res) {
            echo "  [ERROR] TLS Handshake failed!" . PHP_EOL;
            fclose($fp);
            return;
        }
        echo "  [TLS Handshake Successful!]" . PHP_EOL;
        
        // EHLO again after TLS
        fputs($fp, "EHLO localhost\r\n");
        echo "  C: EHLO localhost" . PHP_EOL;
        read_reply($fp);
    }
    
    // AUTH LOGIN
    fputs($fp, "AUTH LOGIN\r\n");
    echo "  C: AUTH LOGIN" . PHP_EOL;
    $reply = fgets($fp, 512);
    echo "  S: $reply";
    
    // Send Username
    $b64User = base64_encode($user);
    fputs($fp, "$b64User\r\n");
    echo "  C: [Base64 Username Sent: $b64User]" . PHP_EOL;
    $reply = fgets($fp, 512);
    echo "  S: $reply";
    
    // Send Password
    $b64Pass = base64_encode($pass);
    fputs($fp, "$b64Pass\r\n");
    echo "  C: [Base64 Password Sent: $b64Pass]" . PHP_EOL;
    $reply = fgets($fp, 512);
    echo "  S: $reply";
    
    if (str_contains($reply, '235')) {
        echo "  ==> RAW SMTP RESULT: 235 AUTHENTICATION SUCCESSFUL!" . PHP_EOL;
    } else {
        echo "  ==> RAW SMTP RESULT: AUTHENTICATION REJECTED BY GMAIL SERVER!" . PHP_EOL;
    }
    
    fputs($fp, "QUIT\r\n");
    fclose($fp);
}

function read_reply($fp) {
    while ($line = fgets($fp, 512)) {
        echo "  S: $line";
        if (substr($line, 3, 1) === ' ') break;
    }
}
