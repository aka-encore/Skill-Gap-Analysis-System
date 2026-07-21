<?php
require_once __DIR__ . '/../config/database.php';
$db = Database::getInstance();
echo "--- assessment_results schema ---\n";
print_r($db->fetchAll("DESCRIBE assessment_results"));
echo "--- student_progress schema ---\n";
print_r($db->fetchAll("DESCRIBE student_progress"));
