<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$skills = $db->fetchAll("SELECT * FROM skills");
echo "Total Skills: " . count($skills) . "\n";
foreach ($skills as $s) {
    echo "- ID: {$s['id']} | Name: {$s['name']} | Category: {$s['category']}\n";
}

$assessments = $db->fetchAll("SELECT a.*, s.name as skill_name FROM assessments a JOIN skills s ON a.skill_id = s.id");
echo "\nTotal Assessments: " . count($assessments) . "\n";
foreach ($assessments as $a) {
    echo "- Assessment ID: {$a['id']} | Title: {$a['title']} | Skill: {$a['skill_name']} | Level: {$a['difficulty_level']}\n";
}
