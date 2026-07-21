<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

try {
    // 1. Update difficulty_level ENUM in assessments table to support 5 levels
    $db->query("ALTER TABLE `assessments` MODIFY COLUMN `difficulty_level` ENUM('beginner', 'easy', 'intermediate', 'advanced', 'expert') NOT NULL DEFAULT 'intermediate'");
    echo "Successfully updated assessments.difficulty_level ENUM to 5 levels.\n";
} catch (Exception $e) {
    echo "ENUM update notice: " . $e->getMessage() . "\n";
}

// 2. Skill Mapping for Frontend, Backend, Full Stack
$allSkills = [
    // Frontend (10)
    ['name' => 'HTML', 'category' => 'Frontend Development'],
    ['name' => 'CSS', 'category' => 'Frontend Development'],
    ['name' => 'JavaScript', 'category' => 'Frontend Development'],
    ['name' => 'Bootstrap', 'category' => 'Frontend Development'],
    ['name' => 'Tailwind CSS', 'category' => 'Frontend Development'],
    ['name' => 'React', 'category' => 'Frontend Development'],
    ['name' => 'Angular', 'category' => 'Frontend Development'],
    ['name' => 'Vue.js', 'category' => 'Frontend Development'],
    ['name' => 'jQuery', 'category' => 'Frontend Development'],
    ['name' => 'TypeScript', 'category' => 'Frontend Development'],

    // Backend (10)
    ['name' => 'C', 'category' => 'Backend Development'],
    ['name' => 'C++', 'category' => 'Backend Development'],
    ['name' => 'Java', 'category' => 'Backend Development'],
    ['name' => 'Python', 'category' => 'Backend Development'],
    ['name' => 'PHP', 'category' => 'Backend Development'],
    ['name' => 'C#', 'category' => 'Backend Development'],
    ['name' => 'Node.js', 'category' => 'Backend Development'],
    ['name' => 'SQL', 'category' => 'Backend Development'],
    ['name' => 'MySQL', 'category' => 'Backend Development'],
    ['name' => 'MongoDB', 'category' => 'Backend Development'],

    // Full Stack (10)
    ['name' => 'MERN Stack', 'category' => 'Full Stack Development'],
    ['name' => 'MEAN Stack', 'category' => 'Full Stack Development'],
    ['name' => 'Laravel', 'category' => 'Full Stack Development'],
    ['name' => 'Django', 'category' => 'Full Stack Development'],
    ['name' => 'Express.js', 'category' => 'Full Stack Development'],
    ['name' => 'Next.js', 'category' => 'Full Stack Development'],
    ['name' => 'ASP.NET', 'category' => 'Full Stack Development'],
    ['name' => 'Spring Boot', 'category' => 'Full Stack Development'],
    ['name' => 'Flask', 'category' => 'Full Stack Development'],
    ['name' => 'REST API Development', 'category' => 'Full Stack Development']
];

foreach ($allSkills as $s) {
    $existing = $db->fetch("SELECT id FROM skills WHERE name = ?", [$s['name']]);
    if (!$existing) {
        $db->insert('skills', [
            'name' => $s['name'],
            'category' => $s['category'],
            'description' => "Evaluation module for {$s['name']} in {$s['category']}."
        ]);
        echo "Inserted skill: {$s['name']} ({$s['category']})\n";
    } else {
        $db->query("UPDATE skills SET category = ? WHERE id = ?", [$s['category'], $existing['id']]);
        echo "Updated skill category: {$s['name']} -> {$s['category']}\n";
    }
}

echo "Database seed check completed!\n";
