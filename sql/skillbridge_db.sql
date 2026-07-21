-- SkillBridge - Skill Gap Analysis and Learning Management System
-- SQL Database Schema and Complete Seed Data
-- PHP 8.x + MySQL / MariaDB (XAMPP Ready)

DROP DATABASE IF EXISTS `skillbridge_db`;
CREATE DATABASE IF NOT EXISTS `skillbridge_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `skillbridge_db`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('student', 'faculty', 'admin') NOT NULL DEFAULT 'student',
  `remember_token` VARCHAR(255) NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `students`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL UNIQUE,
  `student_code` VARCHAR(20) NOT NULL UNIQUE,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `avatar` VARCHAR(255) DEFAULT 'default-avatar.png',
  `bio` VARCHAR(255) NULL,
  `city_location` VARCHAR(100) NULL DEFAULT 'Mumbai, India',
  `phone` VARCHAR(20) NULL,
  `department` VARCHAR(100) NOT NULL DEFAULT 'Computer Science',
  `current_semester` INT NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `faculty`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `faculty` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL UNIQUE,
  `employee_code` VARCHAR(20) NOT NULL UNIQUE,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `avatar` VARCHAR(255) DEFAULT 'default-avatar.png',
  `department` VARCHAR(100) NOT NULL DEFAULT 'Computer Science',
  `designation` VARCHAR(100) NOT NULL DEFAULT 'Assistant Professor',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_faculty_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `admins`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL UNIQUE,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `avatar` VARCHAR(255) DEFAULT 'default-avatar.png',
  `department` VARCHAR(100) DEFAULT 'System Administration',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_admins_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `skills`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `skills` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `category` VARCHAR(50) NOT NULL DEFAULT 'Technical',
  `description` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `courses`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `courses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_code` VARCHAR(20) NOT NULL UNIQUE,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL,
  `duration_hours` INT NOT NULL DEFAULT 10,
  `difficulty_level` ENUM('beginner', 'intermediate', 'advanced') NOT NULL DEFAULT 'beginner',
  `provider_url` VARCHAR(255) NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `course_skills`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `course_skills` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_id` INT NOT NULL,
  `skill_id` INT NOT NULL,
  `skill_level_gained` INT NOT NULL DEFAULT 3,
  CONSTRAINT `fk_cs_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cs_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE,
  CONSTRAINT `unique_course_skill` UNIQUE (`course_id`, `skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `assessments`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `assessments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL,
  `skill_id` INT NOT NULL,
  `created_by_faculty_id` INT NOT NULL,
  `duration_minutes` INT NOT NULL DEFAULT 30,
  `passing_marks` INT NOT NULL DEFAULT 6,
  `total_marks` INT NOT NULL DEFAULT 10,
  `difficulty_level` ENUM('beginner', 'easy', 'intermediate', 'advanced', 'expert') NOT NULL DEFAULT 'intermediate',
  `status` ENUM('draft', 'active', 'archived') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_assessment_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_assessment_faculty` FOREIGN KEY (`created_by_faculty_id`) REFERENCES `faculty` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `assessment_questions`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `assessment_questions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `assessment_id` INT NOT NULL,
  `question_text` TEXT NOT NULL,
  `option_a` TEXT NOT NULL,
  `option_b` TEXT NOT NULL,
  `option_c` TEXT NOT NULL,
  `option_d` TEXT NOT NULL,
  `correct_option` ENUM('A', 'B', 'C', 'D') NOT NULL,
  `marks` INT NOT NULL DEFAULT 1,
  `category` VARCHAR(50) DEFAULT 'Core Concepts',
  CONSTRAINT `fk_question_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `assessment_results`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `assessment_results` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `assessment_id` INT NOT NULL,
  `total_questions` INT NOT NULL DEFAULT 10,
  `correct_answers` INT NOT NULL DEFAULT 0,
  `score_obtained` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `score_percentage` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('pass', 'fail') NOT NULL DEFAULT 'fail',
  `time_taken_seconds` INT NOT NULL DEFAULT 0,
  `completed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_result_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_result_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `student_answers`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `student_answers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `result_id` INT NOT NULL,
  `question_id` INT NOT NULL,
  `selected_option` ENUM('A', 'B', 'C', 'D') NULL,
  `is_correct` TINYINT(1) NOT NULL DEFAULT 0,
  `marks_obtained` INT NOT NULL DEFAULT 0,
  CONSTRAINT `fk_answer_result` FOREIGN KEY (`result_id`) REFERENCES `assessment_results` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `assessment_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `student_progress`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `student_progress` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `progress_percentage` INT NOT NULL DEFAULT 0,
  `status` ENUM('not_started', 'in_progress', 'completed') NOT NULL DEFAULT 'not_started',
  `last_updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_progress_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_progress_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `unique_student_course` UNIQUE (`student_id`, `course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `recommendations`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `recommendations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `skill_id` INT NOT NULL,
  `reason` TEXT NULL,
  `priority_level` ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
  `is_dismissed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_rec_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rec_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rec_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `notifications`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `link` VARCHAR(255) DEFAULT '#',
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `type` VARCHAR(50) DEFAULT 'system',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `reports`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reports` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `report_type` VARCHAR(50) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `generated_by_user_id` INT NOT NULL,
  `file_path` VARCHAR(255) NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_report_user` FOREIGN KEY (`generated_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `activity_logs`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `action` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `ip_address` VARCHAR(45) DEFAULT '127.0.0.1',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `user_sessions`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `session_token` VARCHAR(255) NOT NULL UNIQUE,
  `last_activity` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_agent` TEXT NULL,
  `ip_address` VARCHAR(45) DEFAULT '127.0.0.1',
  CONSTRAINT `fk_session_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `password_resets`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(191) NOT NULL,
  `token` VARCHAR(255) NOT NULL UNIQUE,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `system_settings`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `system_settings` (
  `setting_key` VARCHAR(100) PRIMARY KEY,
  `setting_value` TEXT NOT NULL,
  `setting_group` VARCHAR(50) DEFAULT 'general',
  `description` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- SEED DATA INSERTION
-- Documented Test Credentials:
-- Admin: admin@skillbridge.edu / Admin@123
-- Faculty: faculty1@skillbridge.edu / Faculty@123
-- Student: student1@skillbridge.edu / Student@123
-- ========================================================

-- Insert Users (1 Admin, 5 Faculty, 20 Students)
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@skillbridge.edu', '$2y$10$.F.iCd9GpDLiL9.hR3L84OmLS8UZC/xnUc4/VbsZPl5pMMSGMBB4O', 'admin'),

(2, 'f_turing', 'faculty1@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty'),
(3, 'f_hopper', 'faculty2@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty'),
(4, 'f_knuth', 'faculty3@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty'),
(5, 'f_lovelace', 'faculty4@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty'),
(6, 'f_torvalds', 'faculty5@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty'),

(7, 's_john', 'student1@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(8, 's_emily', 'student2@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(9, 's_michael', 'student3@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(10, 's_sophia', 'student4@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(11, 's_daniel', 'student5@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(12, 's_olivia', 'student6@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(13, 's_david', 'student7@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(14, 's_emma', 'student8@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(15, 's_james', 'student9@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(16, 's_ava', 'student10@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(17, 's_alex', 'student11@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(18, 's_mia', 'student12@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(19, 's_ethan', 'student13@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(20, 's_isabella', 'student14@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(21, 's_william', 'student15@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(22, 's_charlotte', 'student16@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(23, 's_benjamin', 'student17@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(24, 's_amelia', 'student18@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(25, 's_lucas', 'student19@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student'),
(26, 's_harper', 'student20@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student');

-- Insert Admins
INSERT INTO `admins` (`id`, `user_id`, `first_name`, `last_name`, `department`) VALUES
(1, 1, 'System', 'Administrator', 'IT & Operations');

-- Insert Faculty
INSERT INTO `faculty` (`id`, `user_id`, `employee_code`, `first_name`, `last_name`, `department`, `designation`) VALUES
(1, 2, 'FAC-001', 'Alan', 'Turing', 'Computer Science', 'Professor & HOD'),
(2, 3, 'FAC-002', 'Grace', 'Hopper', 'Software Engineering', 'Associate Professor'),
(3, 4, 'FAC-003', 'Donald', 'Knuth', 'Computer Science', 'Senior Professor'),
(4, 5, 'FAC-004', 'Ada', 'Lovelace', 'Information Technology', 'Assistant Professor'),
(5, 6, 'FAC-005', 'Linus', 'Torvalds', 'Systems Engineering', 'Associate Professor');

-- Insert Students
INSERT INTO `students` (`id`, `user_id`, `student_code`, `first_name`, `last_name`, `phone`, `department`, `current_semester`) VALUES
(1, 7, 'STU-1001', 'John', 'Doe', '555-0101', 'Computer Science', 5),
(2, 8, 'STU-1002', 'Emily', 'Smith', '555-0102', 'Information Technology', 5),
(3, 9, 'STU-1003', 'Michael', 'Brown', '555-0103', 'Software Engineering', 6),
(4, 10, 'STU-1004', 'Sophia', 'Johnson', '555-0104', 'Computer Science', 4),
(5, 11, 'STU-1005', 'Daniel', 'Williams', '555-0105', 'Data Science', 6),
(6, 12, 'STU-1006', 'Olivia', 'Jones', '555-0106', 'Software Engineering', 3),
(7, 13, 'STU-1007', 'David', 'Miller', '555-0107', 'Computer Science', 5),
(8, 14, 'STU-1008', 'Emma', 'Davis', '555-0108', 'Information Technology', 4),
(9, 15, 'STU-1009', 'James', 'Wilson', '555-0109', 'Systems Engineering', 6),
(10, 16, 'STU-1010', 'Ava', 'Taylor', '555-0110', 'Computer Science', 3),
(11, 17, 'STU-1011', 'Alex', 'Anderson', '555-0111', 'Data Science', 5),
(12, 18, 'STU-1012', 'Mia', 'Thomas', '555-0112', 'Software Engineering', 4),
(13, 19, 'STU-1013', 'Ethan', 'Jackson', '555-0113', 'Computer Science', 6),
(14, 20, 'STU-1014', 'Isabella', 'White', '555-0114', 'Information Technology', 3),
(15, 21, 'STU-1015', 'William', 'Harris', '555-0115', 'Systems Engineering', 5),
(16, 22, 'STU-1016', 'Charlotte', 'Martin', '555-0116', 'Computer Science', 4),
(17, 23, 'STU-1017', 'Benjamin', 'Thompson', '555-0117', 'Software Engineering', 6),
(18, 24, 'STU-1018', 'Amelia', 'Garcia', '555-0118', 'Data Science', 3),
(19, 25, 'STU-1019', 'Lucas', 'Martinez', '555-0119', 'Computer Science', 5),
(20, 26, 'STU-1020', 'Harper', 'Robinson', '555-0120', 'Information Technology', 4);

-- Insert 20 Skills
INSERT INTO `skills` (`id`, `name`, `category`, `description`) VALUES
(1, 'PHP 8 Web Development', 'Backend', 'Object-Oriented PHP, PDO, MVC architecture, and backend logic.'),
(2, 'MySQL Database Design', 'Database', 'Relational database schema normalization, SQL queries, index optimization.'),
(3, 'JavaScript ES6+', 'Frontend', 'Asynchronous JS, Promises, Fetch API, DOM manipulation, ES6 syntax.'),
(4, 'HTML5 & Responsive CSS3', 'Frontend', 'Semantic HTML5 markup, Flexbox, CSS Grid, media queries, and accessibility.'),
(5, 'Bootstrap 5 Framework', 'Frontend', 'Bootstrap grid system, utility classes, dynamic components, and dark themes.'),
(6, 'Web Application Security', 'Security', 'OWASP Top 10 mitigation, XSS prevention, CSRF tokens, SQL injection defense.'),
(7, 'RESTful API Architecture', 'Backend', 'API design principles, JSON data formats, HTTP headers, authentication tokens.'),
(8, 'Data Structures & Algorithms', 'Computer Science', 'Arrays, linked lists, trees, graphs, sorting, searching, and complexity analysis.'),
(9, 'Object-Oriented Programming', 'Software Design', 'Inheritance, polymorphism, encapsulation, abstraction, and design patterns.'),
(10, 'Version Control with Git', 'DevOps', 'Git workflows, branching, merging, pull requests, and remote repositories.'),
(11, 'UI/UX Interface Design', 'Design', 'User research, wireframing, color theory, typography, and micro-interactions.'),
(12, 'Python Programming', 'Programming', 'Python language syntax, data analysis libraries, script automation.'),
(13, 'Docker & Containerization', 'DevOps', 'Dockerfile creation, container orchestration, microservices deployment.'),
(14, 'React Frontend Development', 'Frontend', 'Component architecture, state hooks, virtual DOM, and single page applications.'),
(15, 'Cloud Computing (AWS/Azure)', 'Infrastructure', 'Cloud infrastructure services, virtual private clouds, storage buckets, IAM.'),
(16, 'Software Testing & QA', 'Quality Assurance', 'Unit testing, integration testing, test-driven development (TDD), automation.'),
(17, 'Node.js & Express Architecture', 'Backend', 'Event-driven asynchronous I/O backend development, middleware, npm.'),
(18, 'Linux System Administration', 'Systems', 'Shell scripting, file permissions, SSH keys, cron jobs, server management.'),
(19, 'Agile & Scrum Methodology', 'Management', 'Sprint planning, user stories, backlog grooming, daily standups, retrospectives.'),
(20, 'Cybersecurity Fundamentals', 'Security', 'Network security protocols, encryption algorithms, penetration testing basics.');

-- Insert 20 Courses
INSERT INTO `courses` (`id`, `course_code`, `title`, `description`, `duration_hours`, `difficulty_level`, `provider_url`, `status`) VALUES
(1, 'CS-101', 'Mastering Pure PHP 8 Development', 'Learn complete PHP 8 programming from fundamentals to advanced PDO database integration.', 25, 'intermediate', 'https://course.skillbridge.edu/php8-mastery', 'active'),
(2, 'CS-102', 'Relational Database Masterclass: MySQL', 'Comprehensive database design, complex JOINs, indexing strategies, and normalization.', 20, 'intermediate', 'https://course.skillbridge.edu/mysql-mastery', 'active'),
(3, 'CS-103', 'Modern JavaScript ES6+ Mastery', 'Deep dive into asynchronous JavaScript, Promises, DOM handling, and modern ES6 syntax.', 18, 'beginner', 'https://course.skillbridge.edu/js-es6', 'active'),
(4, 'CS-104', 'Responsive Design with Bootstrap 5', 'Build modern, responsive, component-rich web applications using Bootstrap 5 framework.', 15, 'beginner', 'https://course.skillbridge.edu/bootstrap5', 'active'),
(5, 'CS-105', 'Web Security Essentials & OWASP', 'Learn practical defenses against SQL Injection, XSS, CSRF, and broken session management.', 22, 'advanced', 'https://course.skillbridge.edu/web-security', 'active'),
(6, 'CS-106', 'RESTful API Engineering in PHP', 'Build lightweight, secure, JSON-based REST APIs using PHP and PDO prepared statements.', 16, 'intermediate', 'https://course.skillbridge.edu/php-rest-api', 'active'),
(7, 'CS-107', 'Data Structures & Algorithms in Practice', 'Master essential algorithms and data structures with step-by-step code implementations.', 30, 'intermediate', 'https://course.skillbridge.edu/dsa-practice', 'active'),
(8, 'CS-108', 'Object-Oriented Software Architecture', 'Apply solid OOP principles and design patterns to create maintainable enterprise code.', 24, 'advanced', 'https://course.skillbridge.edu/oop-architecture', 'active'),
(9, 'CS-109', 'Git & GitHub Collaboration Workflow', 'Master version control, interactive rebasing, merge conflict resolution, and branching.', 12, 'beginner', 'https://course.skillbridge.edu/git-mastery', 'active'),
(10, 'CS-110', 'UI/UX Fundamentals for Web Engineers', 'Design intuitive user experiences with high-contrast layouts, typography, and accessibility.', 15, 'beginner', 'https://course.skillbridge.edu/ui-ux-design', 'active'),
(11, 'CS-111', 'Python for Software Automation', 'Write efficient Python scripts for data processing, web scraping, and task automation.', 20, 'beginner', 'https://course.skillbridge.edu/python-automation', 'active'),
(12, 'CS-112', 'Docker Container Essentials', 'Containerize full-stack web applications with multi-container Docker Compose setups.', 18, 'intermediate', 'https://course.skillbridge.edu/docker-essentials', 'active'),
(13, 'CS-113', 'React Frontend Foundations', 'Build dynamic single-page web applications using React hooks and state management.', 25, 'intermediate', 'https://course.skillbridge.edu/react-foundations', 'active'),
(14, 'CS-114', 'Cloud Infrastructure Fundamentals', 'Deploy scalable web applications to AWS Cloud services with secure networking.', 28, 'advanced', 'https://course.skillbridge.edu/aws-cloud', 'active'),
(15, 'CS-115', 'Automated Software Testing & TDD', 'Write unit tests, integration tests, and implement Test-Driven Development workflows.', 20, 'intermediate', 'https://course.skillbridge.edu/qa-testing', 'active'),
(16, 'CS-116', 'Asynchronous Node.js & Express', 'Build high-concurrency event-driven backends with Node.js, Express, and MongoDB.', 22, 'intermediate', 'https://course.skillbridge.edu/nodejs-express', 'active'),
(17, 'CS-117', 'Linux Command Line Administration', 'Master bash commands, shell scripts, system services, and Linux server security.', 18, 'beginner', 'https://course.skillbridge.edu/linux-admin', 'active'),
(18, 'CS-118', 'Agile Product Delivery & Scrum', 'Understand Agile principles, sprint execution, user story mapping, and team velocity.', 12, 'beginner', 'https://course.skillbridge.edu/agile-scrum', 'active'),
(19, 'CS-119', 'Practical Cyber Security Defenses', 'Ethical hacking fundamentals, network packet analysis, and security hardening.', 30, 'advanced', 'https://course.skillbridge.edu/cybersecurity-defenses', 'active'),
(20, 'CS-120', 'Full Stack Web Architecture Capstone', 'Synthesize frontend, backend, database, and security concepts into a unified capstone.', 35, 'advanced', 'https://course.skillbridge.edu/fullstack-capstone', 'active');

-- Link Courses with Skills
INSERT INTO `course_skills` (`course_id`, `skill_id`, `skill_level_gained`) VALUES
(1, 1, 4), (2, 2, 4), (3, 3, 3), (4, 4, 3), (4, 5, 4),
(5, 6, 5), (6, 7, 4), (7, 8, 4), (8, 9, 5), (9, 10, 3),
(10, 11, 3), (11, 12, 4), (12, 13, 4), (13, 14, 4), (14, 15, 5),
(15, 16, 4), (16, 17, 4), (17, 18, 3), (18, 19, 3), (19, 20, 5);

-- Insert 10 Assessments
INSERT INTO `assessments` (`id`, `title`, `description`, `skill_id`, `created_by_faculty_id`, `duration_minutes`, `passing_marks`, `total_marks`, `difficulty_level`, `status`) VALUES
(1, 'PHP 8 Core Concepts & PDO Mastery', 'Evaluates knowledge of OOP in PHP, PDO database queries, session handling, and backend logic.', 1, 1, 20, 6, 10, 'intermediate', 'active'),
(2, 'MySQL Relational Schema & SQL Querying', 'Tests database normalization, multi-table JOINs, subqueries, and indexing principles.', 2, 2, 20, 6, 10, 'intermediate', 'active'),
(3, 'JavaScript ES6 Asynchronous Programming', 'Focuses on Promises, async/await, DOM events, and modern JavaScript syntax.', 3, 3, 15, 6, 10, 'beginner', 'active'),
(4, 'HTML5 Semantic Markup & CSS3 Layouts', 'Tests semantic HTML elements, Flexbox layout, responsive media queries, and specificity.', 4, 4, 15, 6, 10, 'beginner', 'active'),
(5, 'Bootstrap 5 Responsive Grid & UI Components', 'Covers Bootstrap grid system, dynamic utility classes, navigation, and modal components.', 5, 1, 15, 6, 10, 'beginner', 'active'),
(6, 'Web Security & OWASP Top 10 Defenses', 'Assesses practical mitigation of SQL Injection, XSS attacks, CSRF, and safe auth sessions.', 6, 5, 20, 7, 10, 'advanced', 'active'),
(7, 'RESTful API Design & HTTP Header Standards', 'Tests HTTP request methods, JSON response formatting, status codes, and API security.', 7, 2, 20, 6, 10, 'intermediate', 'active'),
(8, 'Data Structures: Arrays, Lists & Trees', 'Evaluates algorithmic efficiency, Big-O notation, tree traversals, and search logic.', 8, 3, 25, 7, 10, 'advanced', 'active'),
(9, 'Object-Oriented Design & Design Patterns', 'Covers OOP principles (SOLID), Singleton patterns, Factory pattern, and class encapsulation.', 9, 4, 20, 6, 10, 'intermediate', 'active'),
(10, 'Git Version Control & Merge Workflows', 'Evaluates Git commands, branch management, merge conflict resolution, and git log history.', 10, 5, 15, 6, 10, 'beginner', 'active');

-- Insert 100 Questions (10 questions per assessment)
INSERT INTO `assessment_questions` (`id`, `assessment_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `marks`, `category`) VALUES
-- Assessment 1: PHP 8
(1, 1, 'Which PHP 8 feature allows initializing class properties directly inside constructor parameters?', 'Named Arguments', 'Constructor Property Promotion', 'Match Expressions', 'Nullsafe Operator', 'B', 1, 'PHP 8 Syntax'),
(2, 1, 'What is the recommended approach to prevent SQL Injection in pure PHP database queries?', 'Using addslashes()', 'Using PDO prepared statements with bound parameters', 'Using htmlspecialchars()', 'Escaping single quotes manually', 'B', 1, 'Security & Database'),
(3, 1, 'In PHP, which superglobal array stores data passed through HTTP POST requests?', '$_GET', '$_REQUEST', '$_POST', '$_SERVER', 'C', 1, 'Superglobals'),
(4, 1, 'What does the PDO::FETCH_ASSOC fetch mode return?', 'An array indexed by column number', 'An object with property names matching column names', 'An array indexed by column name', 'A string of JSON data', 'C', 1, 'PDO'),
(5, 1, 'Which function in PHP destroys all data registered to a session?', 'session_unset()', 'session_destroy()', 'session_reset()', 'unset($_SESSION)', 'B', 1, 'Session Management'),
(6, 1, 'What will the expression `null ?? "default"` evaluate to in PHP?', 'null', 'default', 'false', 'Syntax Error', 'B', 1, 'Operators'),
(7, 1, 'Which PHP 8 function checks if a string contains a specific substring?', 'strpos()', 'strstr()', 'str_contains()', 'substr_count()', 'C', 1, 'String Functions'),
(8, 1, 'What access modifier makes a property accessible only within the class where it is declared?', 'public', 'protected', 'private', 'static', 'C', 1, 'OOP Principles'),
(9, 1, 'How do you define a constant in a PHP class?', 'const MY_CONST = 100;', 'define("MY_CONST", 100);', 'var MY_CONST = 100;', 'static MY_CONST = 100;', 'A', 1, 'OOP Principles'),
(10, 1, 'Which header function call correctly redirects the browser to index.php?', 'header("Location: index.php");', 'header("Redirect: index.php");', 'header("Url: index.php");', 'header("Goto: index.php");', 'A', 1, 'HTTP Headers'),

-- Assessment 2: MySQL
(11, 2, 'Which SQL keyword is used to eliminate duplicate rows from query results?', 'UNIQUE', 'DISTINCT', 'DIFFERENT', 'GROUP BY', 'B', 1, 'SQL Syntax'),
(12, 2, 'In MySQL, what type of JOIN returns all records when there is a match in either left or right table?', 'INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'FULL OUTER JOIN', 'D', 1, 'SQL Joins'),
(13, 2, 'Which normal form ensures that no non-prime attribute is dependent on a subset of any candidate key (no partial dependencies)?', '1NF', '2NF', '3NF', 'BCNF', 'B', 1, 'Normalization'),
(14, 2, 'What constraint ensures that a column cannot have NULL values?', 'UNIQUE', 'CHECK', 'NOT NULL', 'DEFAULT', 'C', 1, 'Constraints'),
(15, 2, 'Which aggregate function counts the number of non-null values in a column?', 'SUM()', 'COUNT()', 'AVG()', 'TOTAL()', 'B', 1, 'Aggregate Functions'),
(16, 2, 'What is the default storage engine for MySQL 8.0?', 'MyISAM', 'InnoDB', 'Memory', 'CSV', 'B', 1, 'Database Engines'),
(17, 2, 'Which SQL clause is used to filter records after an aggregate function (GROUP BY)?', 'WHERE', 'HAVING', 'FILTER', 'ORDER BY', 'B', 1, 'SQL Syntax'),
(18, 2, 'What type of index speeds up searching columns with high data cardinality?', 'B-Tree Index', 'Full-Text Index', 'Spatial Index', 'Hash Index', 'A', 1, 'Indexing'),
(19, 2, 'Which command is used to add a new column to an existing table in MySQL?', 'UPDATE TABLE', 'MODIFY TABLE', 'ALTER TABLE', 'CHANGE TABLE', 'C', 1, 'DDL Commands'),
(20, 2, 'What does a FOREIGN KEY constraint enforce?', 'Entity Integrity', 'Referential Integrity', 'Domain Integrity', 'User-Defined Integrity', 'B', 1, 'Constraints'),

-- Assessment 3: JavaScript ES6
(21, 3, 'Which keyword creates a block-scoped variable in ES6 that cannot be reassigned?', 'var', 'let', 'const', 'static', 'C', 1, 'ES6 Syntax'),
(22, 3, 'What does the `fetch()` API return in modern JavaScript?', 'A JSON string', 'A Promise resolving to a Response object', 'An XML document', 'A callback function', 'B', 1, 'Asynchronous JS'),
(23, 3, 'How do you extract values from an array or object in ES6 using concise syntax?', 'Spread operator', 'Destructuring assignment', 'Template literals', 'Array mapping', 'B', 1, 'ES6 Features'),
(24, 3, 'What is the primary benefit of Arrow Functions regarding the `this` keyword?', 'They bind dynamic `this`', 'They inherit lexical `this` from surrounding scope', 'They create their own `this` context', 'They reset `this` to window', 'B', 1, 'Functions'),
(25, 3, 'Which array method creates a new array filled with elements that pass a test condition?', 'map()', 'filter()', 'reduce()', 'forEach()', 'B', 1, 'Array Methods'),
(26, 3, 'What method converts a JavaScript object into a JSON formatted string?', 'JSON.parse()', 'JSON.stringify()', 'JSON.encode()', 'JSON.format()', 'B', 1, 'JSON'),
(27, 3, 'Which keyword is used inside an async function to pause execution until a Promise settles?', 'defer', 'yield', 'await', 'hold', 'C', 1, 'Asynchronous JS'),
(28, 3, 'What will `typeof NaN` evaluate to in JavaScript?', 'number', 'nan', 'undefined', 'object', 'A', 1, 'Data Types'),
(29, 3, 'How do you pass a variable into a Template Literal string in ES6?', '{$var}', '${var}', '{{var}}', '%var%', 'B', 1, 'Template Strings'),
(30, 3, 'Which method attaches an event handler to an HTML element without overwriting existing handlers?', 'addEventListener()', 'attachEvent()', 'on()', 'bindEvent()', 'A', 1, 'DOM Manipulation'),

-- Assessment 4: HTML5 & CSS3
(31, 4, 'Which semantic HTML5 tag should be used for the primary introductory content or nav links?', '<section>', '<header>', '<aside>', '<article>', 'B', 1, 'HTML5 Semantics'),
(32, 4, 'In CSS Flexbox, which property aligns items along the main axis?', 'align-items', 'justify-content', 'align-content', 'flex-direction', 'B', 1, 'CSS Flexbox'),
(33, 4, 'What CSS box-sizing value ensures padding and border are included in the element total width and height?', 'content-box', 'border-box', 'padding-box', 'inherit', 'B', 1, 'CSS Box Model'),
(34, 4, 'Which HTML5 input type provides built-in email format validation in forms?', '<input type="text">', '<input type="email">', '<input type="mail">', '<input type="validate">', 'B', 1, 'HTML5 Forms'),
(35, 4, 'What CSS pseudo-class targets an element when a user hovers over it with a pointer?', ':active', ':focus', ':hover', ':visited', 'C', 1, 'CSS Selectors'),
(36, 4, 'Which media query feature checks the width of the user viewport?', 'min-device-width', 'min-width', 'resolution', 'orientation', 'B', 1, 'Responsive CSS'),
(37, 4, 'What is the correct HTML element for playing audio files natively?', '<sound>', '<audio>', '<music>', '<media>', 'B', 1, 'HTML5 Media'),
(38, 4, 'Which CSS property controls the stacking order of elements positioned relative or absolute?', 'display', 'float', 'z-index', 'opacity', 'C', 1, 'CSS Layout'),
(39, 4, 'What attribute provides alternative text for an image if it fails to load?', 'title', 'alt', 'caption', 'desc', 'B', 1, 'Accessibility'),
(40, 4, 'In CSS Grid, which property defines the track sizes of rows?', 'grid-template-columns', 'grid-template-rows', 'grid-gap', 'grid-auto-flow', 'B', 1, 'CSS Grid'),

-- Assessment 5: Bootstrap 5
(41, 5, 'How many responsive grid columns are in standard Bootstrap 5?', '10', '12', '16', '24', 'B', 1, 'Bootstrap Grid'),
(42, 5, 'Which Bootstrap class creates a flexbox container that spans the full width of the viewport?', 'container', 'container-fluid', 'container-full', 'row-fluid', 'B', 1, 'Bootstrap Layout'),
(43, 5, 'Which class adds a modern card container with borders and padding in Bootstrap 5?', '.box', '.card', '.panel', '.well', 'B', 1, 'Bootstrap Components'),
(44, 5, 'What is the Bootstrap 5 class to color a button blue (primary brand color)?', 'btn-blue', 'btn-info', 'btn-primary', 'btn-accent', 'C', 1, 'Bootstrap Components'),
(45, 5, 'Which utility class turns text bold in Bootstrap 5?', '.font-bold', '.fw-bold', '.text-bold', '.weight-bold', 'B', 1, 'Utilities'),
(46, 5, 'What class aligns text to the center in Bootstrap 5?', '.text-center', '.align-center', '.center-text', '.justify-center', 'A', 1, 'Utilities'),
(47, 5, 'Which breakpoint prefix corresponds to extra-large screens (≥1200px) in Bootstrap 5?', 'md', 'lg', 'xl', 'xxl', 'C', 1, 'Breakpoints'),
(48, 5, 'Which component displays a dismissible contextual feedback message?', 'Modal', 'Toast', 'Alert', 'Badge', 'C', 1, 'Components'),
(49, 5, 'What attribute triggers a Bootstrap 5 modal window via button click?', 'data-toggle="modal"', 'data-bs-toggle="modal"', 'data-target="modal"', 'bs-modal="open"', 'B', 1, 'JS Plugins'),
(50, 5, 'Which utility class adds margin to the bottom of an element (spacing scale 3)?', 'm-3', 'mb-3', 'my-3', 'pb-3', 'B', 1, 'Spacing Utilities'),

-- Assessment 6: Web Security
(51, 6, 'What attack occurs when malicious scripts are injected into trusted web applications?', 'CSRF', 'XSS (Cross-Site Scripting)', 'SQL Injection', 'Man-in-the-Middle', 'B', 1, 'OWASP Vulnerabilities'),
(52, 6, 'What defense mechanism prevents Cross-Site Request Forgery (CSRF) attacks?', 'Using HTTPS', 'CSRF synchronizer tokens in forms', 'Hashing passwords with bcrypt', 'Validating email addresses', 'B', 1, 'CSRF Defense'),
(53, 6, 'What is the safest way to store user passwords in a database?', 'Plaintext', 'MD5 hash', 'SHA-1 hash', 'Bcrypt/Argon2 password hash with salt', 'D', 1, 'Password Security'),
(54, 6, 'Which HTTP header prevents an application from being embedded in an iframe (Clickjacking protection)?', 'X-Content-Type-Options', 'X-Frame-Options', 'Strict-Transport-Security', 'Content-Security-Policy', 'B', 1, 'HTTP Headers'),
(55, 6, 'What parameter flag prevents JavaScript from accessing a session cookie via document.cookie?', 'Secure', 'HttpOnly', 'SameSite', 'Domain', 'B', 1, 'Session Security'),
(56, 6, 'Which technique eliminates SQL Injection risks entirely?', 'Escaping strings manually', 'Using PDO prepared statements with parameter binding', 'Stripping HTML tags', 'Using GET instead of POST', 'B', 1, 'SQL Defense'),
(57, 6, 'What does the SameSite=Strict cookie attribute prevent?', 'Cross-Origin Resource Sharing', 'Cross-Site Request Forgery', 'Buffer Overflow', 'DNS Spoofing', 'B', 1, 'Cookie Security'),
(58, 6, 'Which PHP function sanitizes HTML characters to prevent XSS vulnerabilities when echoing text?', 'urlencode()', 'htmlspecialchars()', 'addslashes()', 'strip_tags()', 'B', 1, 'XSS Defense'),
(59, 6, 'What HTTP status code indicates an Unauthorized access request?', '400', '401', '403', '404', 'B', 1, 'HTTP Protocol'),
(60, 6, 'What mechanism controls which cross-origin requests are permitted by the browser?', 'CORS (Cross-Origin Resource Sharing)', 'CSRF', 'SSRF', 'XSS', 'A', 1, 'Browser Security'),

-- Assessment 7: RESTful APIs
(61, 7, 'Which HTTP method is idempotent and intended for updating an existing resource completely?', 'POST', 'PUT', 'GET', 'DELETE', 'B', 1, 'HTTP Methods'),
(62, 7, 'What standard content-type header is set when sending JSON data in REST APIs?', 'text/html', 'application/json', 'multipart/form-data', 'application/x-www-form-urlencoded', 'B', 1, 'API Headers'),
(63, 7, 'Which HTTP status code indicates successful resource creation?', '200 OK', '201 Created', '204 No Content', '302 Found', 'B', 1, 'Status Codes'),
(64, 7, 'What is the primary characteristic of a RESTful API concerning client state?', 'Stateful', 'Stateless', 'Session-bound', 'Database-locked', 'B', 1, 'REST Principles'),
(65, 7, 'Which HTTP method is used to retrieve data from a server without side effects?', 'POST', 'GET', 'PATCH', 'DELETE', 'B', 1, 'HTTP Methods'),
(66, 7, 'What format is most commonly used for RESTful API payloads today?', 'XML', 'JSON', 'YAML', 'CSV', 'B', 1, 'Data Formats'),
(67, 7, 'Which status code represents "Internal Server Error"?', '400', '404', '500', '503', 'C', 1, 'Status Codes'),
(68, 7, 'What header sends bearer tokens for API authentication?', 'Cookie', 'Authorization', 'User-Agent', 'Accept', 'B', 1, 'API Auth'),
(69, 7, 'What does HATEOAS stand for in advanced REST architecture?', 'Hypermedia As The Engine Of Application State', 'Hypertext And Text Editing Operating System', 'High Availability Transfer Engine System', 'Hosted Application Technology Architecture', 'A', 1, 'REST Architecture'),
(70, 7, 'Which HTTP method is used for partial updates to a resource?', 'PUT', 'PATCH', 'POST', 'UPDATE', 'B', 1, 'HTTP Methods'),

-- Assessment 8: Data Structures
(71, 8, 'What is the time complexity of searching an element in a balanced Binary Search Tree (BST)?', 'O(1)', 'O(n)', 'O(log n)', 'O(n²)', 'C', 1, 'Tree Complexity'),
(72, 8, 'Which data structure operates on a Last-In, First-Out (LIFO) principle?', 'Queue', 'Stack', 'Array', 'Linked List', 'B', 1, 'Stacks'),
(73, 8, 'In a queue, from which end are items enqueued?', 'Front', 'Rear', 'Middle', 'Top', 'B', 1, 'Queues'),
(74, 8, 'What is the worst-case time complexity of QuickSort?', 'O(n log n)', 'O(n)', 'O(n²)', 'O(1)', 'C', 1, 'Sorting'),
(75, 8, 'Which tree traversal visits the root node first, followed by left and right subtrees?', 'In-order', 'Pre-order', 'Post-order', 'Level-order', 'B', 1, 'Trees'),
(76, 8, 'What data structure uses key-value pairs for O(1) average lookup time?', 'Linked List', 'Hash Table', 'Binary Tree', 'Heap', 'B', 1, 'Hash Tables'),
(77, 8, 'Which memory allocation issue occurs when dynamically allocated memory is no longer reachable?', 'Buffer overflow', 'Memory leak', 'Stack overflow', 'Segmentation fault', 'B', 1, 'Memory Management'),
(78, 8, 'What algorithm finds the shortest path between nodes in a weighted graph?', 'Dijkstra Algorithm', 'Binary Search', 'Bubble Sort', 'Kruskal Algorithm', 'A', 1, 'Graph Algorithms'),
(79, 8, 'Which queue type allows insertion and deletion from both ends?', 'Priority Queue', 'Deque (Double-ended Queue)', 'Circular Queue', 'FIFO Queue', 'B', 1, 'Queues'),
(80, 8, 'What is the Space Complexity of an array storing N elements?', 'O(1)', 'O(log N)', 'O(N)', 'O(N²)', 'C', 1, 'Big-O'),

-- Assessment 9: Object-Oriented Principles
(81, 9, 'What does the "S" in SOLID design principles stand for?', 'Single Responsibility Principle', 'Substitute Responsibility Principle', 'Software Engineering Principle', 'Static Inheritance Principle', 'A', 1, 'SOLID'),
(82, 9, 'Which mechanism allows a child class to provide a specific implementation of a parent class method?', 'Method Overloading', 'Method Overriding', 'Method Shadowing', 'Method Encapsulation', 'B', 1, 'OOP Principles'),
(83, 9, 'What design pattern ensures a class has only one instance and provides a global point of access?', 'Factory Pattern', 'Observer Pattern', 'Singleton Pattern', 'Strategy Pattern', 'C', 1, 'Design Patterns'),
(84, 9, 'Hiding internal object details and exposing only necessary interfaces is called what?', 'Polymorphism', 'Encapsulation', 'Inheritance', 'Abstraction', 'B', 1, 'OOP Principles'),
(85, 9, 'Which keyword is used in PHP to inherit a parent class?', 'implements', 'extends', 'inherits', 'uses', 'B', 1, 'PHP OOP'),
(86, 9, 'Can an abstract class be instantiated directly with `new`?', 'Yes, always', 'No, abstract classes cannot be instantiated', 'Only if it has no parameters', 'Only in PHP 8', 'B', 1, 'OOP Principles'),
(87, 9, 'Which design pattern defines a one-to-many dependency between objects so that when one changes state, all dependents are notified?', 'Factory', 'Singleton', 'Observer', 'Adapter', 'C', 1, 'Design Patterns'),
(88, 9, 'What is an interface in OOP?', 'A class with concrete properties', 'A contract defining method signatures without implementation', 'A database table mapping', 'A dynamic array wrapper', 'B', 1, 'OOP Concepts'),
(89, 9, 'What is Polymorphism?', 'Ability of different objects to respond to the same method call in unique ways', 'Ability to create multiple threads', 'Grouping variables into a single file', 'Writing recursive code', 'A', 1, 'OOP Principles'),
(90, 9, 'Which SOLID principle states that soft units should be open for extension but closed for modification?', 'Single Responsibility', 'Open/Closed Principle', 'Liskov Substitution', 'Interface Segregation', 'B', 1, 'SOLID'),

-- Assessment 10: Git Version Control
(91, 10, 'Which command creates a local copy of a remote Git repository?', 'git fetch', 'git clone', 'git copy', 'git download', 'B', 1, 'Git Basics'),
(92, 10, 'Which command stages all modified and new files for commit in Git?', 'git stage -all', 'git add .', 'git commit -a', 'git push', 'B', 1, 'Git Workflow'),
(93, 10, 'What does `git pull` do under the hood?', 'Executes `git fetch` followed by `git merge`', 'Executes `git push`', 'Executes `git checkout`', 'Executes `git reset`', 'A', 1, 'Git Operations'),
(94, 10, 'Which command creates and switches to a new branch simultaneously?', 'git branch -new <name>', 'git checkout -b <name>', 'git switch -create <name>', 'git make-branch <name>', 'B', 1, 'Git Branching'),
(95, 10, 'How do you check the commit history of a repository?', 'git status', 'git log', 'git show-history', 'git list', 'B', 1, 'Git Basics'),
(96, 10, 'What file specifies intentionally untracked files that Git should ignore?', '.gitconfig', '.gitignore', '.gitkeep', '.gitmanifest', 'B', 1, 'Git Configuration'),
(97, 10, 'Which command temporarily saves uncommitted changes so you can work on something else?', 'git stash', 'git save', 'git pause', 'git store', 'A', 1, 'Git Stash'),
(98, 10, 'What happens when two branches have modified the same line in a file and you attempt to merge?', 'Git automatically picks the newest line', 'A merge conflict occurs requiring manual resolution', 'The operation is silently aborted', 'The repository gets corrupted', 'B', 1, 'Merge Conflicts'),
(99, 10, 'Which command uploads local branch commits to a remote repository?', 'git send', 'git push', 'git upload', 'git sync', 'B', 1, 'Remote Git'),
(100, 10, 'What command shows the working directory and staging area status?', 'git diff', 'git status', 'git check', 'git inspect', 'B', 1, 'Git Basics');

-- Sample Assessment Results (for Students 1 through 10)
INSERT INTO `assessment_results` (`id`, `student_id`, `assessment_id`, `total_questions`, `correct_answers`, `score_obtained`, `score_percentage`, `status`, `time_taken_seconds`, `completed_at`) VALUES
(1, 1, 1, 10, 9, 9.00, 90.00, 'pass', 650, NOW() - INTERVAL 5 DAY),
(2, 1, 2, 10, 8, 8.00, 80.00, 'pass', 710, NOW() - INTERVAL 4 DAY),
(3, 1, 6, 10, 5, 5.00, 50.00, 'fail', 890, NOW() - INTERVAL 2 DAY),

(4, 2, 3, 10, 9, 9.00, 90.00, 'pass', 500, NOW() - INTERVAL 6 DAY),
(5, 2, 4, 10, 7, 7.00, 70.00, 'pass', 420, NOW() - INTERVAL 3 DAY),
(6, 2, 6, 10, 4, 4.00, 40.00, 'fail', 950, NOW() - INTERVAL 1 DAY),

(7, 3, 1, 10, 8, 8.00, 80.00, 'pass', 600, NOW() - INTERVAL 7 DAY),
(8, 3, 2, 10, 9, 9.00, 90.00, 'pass', 580, NOW() - INTERVAL 4 DAY),
(9, 3, 8, 10, 5, 5.00, 50.00, 'fail', 1100, NOW() - INTERVAL 1 DAY),

(10, 4, 4, 10, 10, 10.00, 100.00, 'pass', 350, NOW() - INTERVAL 5 DAY),
(11, 4, 5, 10, 9, 9.00, 90.00, 'pass', 390, NOW() - INTERVAL 3 DAY),

(12, 5, 1, 10, 5, 5.00, 50.00, 'fail', 800, NOW() - INTERVAL 2 DAY),
(13, 5, 7, 10, 8, 8.00, 80.00, 'pass', 720, NOW() - INTERVAL 1 DAY);

-- Sample Student Progress in Courses
INSERT INTO `student_progress` (`student_id`, `course_id`, `progress_percentage`, `status`) VALUES
(1, 1, 100, 'completed'),
(1, 2, 80, 'in_progress'),
(1, 5, 20, 'in_progress'),
(2, 3, 100, 'completed'),
(2, 4, 75, 'in_progress'),
(2, 5, 10, 'in_progress'),
(3, 1, 90, 'in_progress'),
(3, 2, 100, 'completed'),
(3, 7, 30, 'in_progress'),
(4, 4, 100, 'completed'),
(4, 5, 90, 'in_progress'),
(5, 1, 40, 'in_progress'),
(5, 6, 85, 'in_progress');

-- Sample Recommendations
INSERT INTO `recommendations` (`student_id`, `course_id`, `skill_id`, `reason`, `priority_level`) VALUES
(1, 5, 6, 'Assessment score in Web Security & OWASP Top 10 was 50.00%. Targeted learning recommended to bridge security gap.', 'high'),
(2, 5, 6, 'Assessment score in Web Security & OWASP Top 10 was 40.00%. Highly recommended to build defensive web coding skills.', 'high'),
(3, 7, 8, 'Assessment score in Data Structures was 50.00%. Recommended to strengthen computer science fundamentals.', 'high'),
(5, 1, 1, 'Assessment score in PHP 8 Core Concepts was 50.00%. Recommended to complete core PHP course modules.', 'high'),
(4, 10, 11, 'Recommended based on high score in HTML5/CSS3 to expand frontend UI/UX design knowledge.', 'medium');

-- Sample System Notifications
INSERT INTO `notifications` (`user_id`, `title`, `message`, `link`, `is_read`, `type`) VALUES
(7, 'Assessment Result Available', 'Your score for "PHP 8 Core Concepts & PDO Mastery" is 90.00% (Passed).', '/student/assessment-result.php?id=1', 1, 'assessment'),
(7, 'Course Recommendation', 'You have a high-priority course recommendation: Web Security Essentials & OWASP.', '/student/recommendations.php', 0, 'recommendation'),
(8, 'Assessment Assigned', 'Faculty Grace Hopper assigned a new assessment: JavaScript ES6 Asynchronous Programming.', '/student/take-assessment.php?id=3', 1, 'assessment'),
(8, 'Course Recommendation', 'New course recommendation based on your recent Web Security assessment gap.', '/student/recommendations.php', 0, 'recommendation'),
(9, 'Assessment Result Available', 'Your score for "Data Structures: Arrays, Lists & Trees" is 50.00% (Failed).', '/student/assessment-result.php?id=9', 0, 'assessment'),
(1, 'System Backup Reminder', 'Weekly database backup and audit scheduled today.', '/admin/backup.php', 0, 'system'),
(2, 'Class Assessment Submitted', 'Student John Doe has completed PHP 8 Core Concepts & PDO Mastery.', '/faculty/evaluate.php?assessment_id=1', 1, 'assessment');

-- Sample Reports Log
INSERT INTO `reports` (`report_type`, `title`, `generated_by_user_id`, `file_path`) VALUES
('Skill Gap', 'Departmental Skill Gap Summary - Q3', 1, 'reports/skill_gap_q3.pdf'),
('Student Performance', 'CS-101 PHP 8 Class Evaluation Report', 2, 'reports/cs101_eval.pdf'),
('Course Completion', 'Annual SkillBridge Institutional Learning Metrics', 1, 'reports/annual_metrics.pdf');

-- Sample Activity Logs
INSERT INTO `activity_logs` (`user_id`, `action`, `description`, `ip_address`) VALUES
(1, 'LOGIN', 'Admin logged into system control panel.', '127.0.0.1'),
(7, 'ASSESSMENT_SUBMITTED', 'Student John Doe completed PHP 8 Core Concepts with score 90.00%.', '127.0.0.1'),
(2, 'ASSESSMENT_CREATED', 'Faculty Alan Turing created assessment: PHP 8 Core Concepts & PDO Mastery.', '127.0.0.1'),
(8, 'ASSESSMENT_SUBMITTED', 'Student Emily Smith completed Web Security assessment with score 40.00%.', '127.0.0.1'),
(1, 'SYSTEM_SETTING_UPDATE', 'Updated system site name and notification mail parameters.', '127.0.0.1');

-- Sample System Settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
('site_name', 'SkillBridge LMS', 'general', 'Name of the learning management platform'),
('institution_name', 'Global Institute of Technology', 'general', 'Educational institution name'),
('admin_email', 'admin@skillbridge.edu', 'general', 'System administrator contact email'),
('pass_mark_threshold', '60', 'assessment', 'Default passing percentage threshold for assessments'),
('session_timeout', '3600', 'security', 'Session expiration timeout in seconds'),
('enable_auto_recommendations', '1', 'analytics', 'Automatically trigger AI/Rule recommendations on skill gaps');

-- --------------------------------------------------------
-- Table structure for table `feedback`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `feedback` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `user_role` ENUM('student', 'faculty', 'admin') NOT NULL DEFAULT 'student',
    `category` VARCHAR(100) NOT NULL,
    `rating` INT NOT NULL DEFAULT 5,
    `message` TEXT NOT NULL,
    `status` ENUM('pending', 'reviewed', 'resolved') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_user_id` (`user_id`),
    KEY `idx_status` (`status`),
    KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Data for table `feedback`
INSERT INTO `feedback` (`user_id`, `user_role`, `category`, `rating`, `message`, `status`) VALUES
(27, 'student', 'Skill Assessments', 5, 'The 5-tier difficulty structure helps me prepare step-by-step for technical interviews.', 'reviewed'),
(27, 'student', 'Personalized Roadmap', 4, 'Great feature! Adding more video tutorials for DevOps milestones would make it even better.', 'pending');
