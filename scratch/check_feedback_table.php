<?php
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$result = $db->fetch("SHOW TABLES LIKE 'feedback'");

if ($result) {
    echo "TABLE_EXISTS\n";
    $cols = $db->fetchAll("DESCRIBE feedback");
    print_r($cols);
} else {
    echo "TABLE_DOES_NOT_EXIST\n";
}
