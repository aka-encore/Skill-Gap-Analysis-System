<?php
header('Content-Type: text/plain');

$user = 'skill.profile.project1@gmail.com';
$pass_raw = 'amsd bvua sebb vsni';
$pass_clean = str_replace(' ', '', $pass_raw);

echo "User: '$user'" . PHP_EOL;
echo "User Base64: " . base64_encode($user) . PHP_EOL;
echo "Pass Raw: '$pass_raw'" . PHP_EOL;
echo "Pass Clean: '$pass_clean' (Length: " . strlen($pass_clean) . ")" . PHP_EOL;
echo "Pass Clean Base64: " . base64_encode($pass_clean) . PHP_EOL;
