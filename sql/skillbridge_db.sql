-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2026 at 05:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skillbridge_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT '127.0.0.1',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 1, 'LOGIN', 'Admin logged into system control panel.', '127.0.0.1', '2026-07-20 20:01:07'),
(2, 7, 'ASSESSMENT_SUBMITTED', 'Student John Doe completed PHP 8 Core Concepts with score 90.00%.', '127.0.0.1', '2026-07-20 20:01:07'),
(3, 2, 'ASSESSMENT_CREATED', 'Faculty Alan Turing created assessment: PHP 8 Core Concepts & PDO Mastery.', '127.0.0.1', '2026-07-20 20:01:07'),
(4, 8, 'ASSESSMENT_SUBMITTED', 'Student Emily Smith completed Web Security assessment with score 40.00%.', '127.0.0.1', '2026-07-20 20:01:07'),
(5, 1, 'SYSTEM_SETTING_UPDATE', 'Updated system site name and notification mail parameters.', '127.0.0.1', '2026-07-20 20:01:07'),
(7, NULL, 'REGISTER', 'New student registered: encore.exe (STU-1027)', '::1', '2026-07-20 20:54:58'),
(8, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-20 20:55:58'),
(9, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 10.0%', '::1', '2026-07-20 20:59:10'),
(10, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 10.0%', '::1', '2026-07-20 20:59:30'),
(11, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 20.0%', '::1', '2026-07-20 20:59:50'),
(12, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 20.0%', '::1', '2026-07-20 21:00:10'),
(13, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 20.0%', '::1', '2026-07-20 21:00:30'),
(14, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 40.0%', '::1', '2026-07-20 21:00:50'),
(15, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 60.0%', '::1', '2026-07-20 21:01:10'),
(16, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 70.0%', '::1', '2026-07-20 21:01:30'),
(17, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 100.0%', '::1', '2026-07-20 21:01:50'),
(18, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 100.0%', '::1', '2026-07-20 21:01:52'),
(19, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 20.0%', '::1', '2026-07-20 21:31:13'),
(20, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 30.0%', '::1', '2026-07-20 21:31:33'),
(21, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 50.0%', '::1', '2026-07-20 21:31:53'),
(22, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 70.0%', '::1', '2026-07-20 21:32:13'),
(23, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 80.0%', '::1', '2026-07-20 21:32:17'),
(24, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 50.0%', '::1', '2026-07-20 21:36:34'),
(25, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts with score 70.0%', '::1', '2026-07-20 21:36:42'),
(26, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 24.0%', '::1', '2026-07-20 21:45:24'),
(27, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 00:00:54'),
(28, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 00:02:14'),
(29, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 00:19:27'),
(30, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 00:20:05'),
(31, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 01:21:50'),
(32, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 02:07:16'),
(33, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 02:57:30'),
(34, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-21 02:59:37'),
(35, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-21 03:03:03'),
(36, 2, 'LOGIN', 'User f_turing logged in successfully as faculty.', '::1', '2026-07-21 03:04:09'),
(37, 2, 'LOGOUT', 'User f_turing logged out.', '::1', '2026-07-21 03:50:06'),
(38, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 03:50:23'),
(39, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 03:56:06'),
(40, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-21 03:56:45'),
(41, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-21 03:58:46'),
(42, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-21 03:59:37'),
(43, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 09:22:34'),
(44, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 09:42:42'),
(45, NULL, 'REGISTER', 'New student registered: kishor1 (STU-1028)', '::1', '2026-07-21 09:46:11'),
(46, NULL, 'LOGIN', 'User kishor1 logged in successfully as student.', '::1', '2026-07-21 09:46:34'),
(47, NULL, 'LOGOUT', 'User kishor1 logged out.', '::1', '2026-07-21 09:47:30'),
(48, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-21 09:49:02'),
(49, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-21 09:53:14'),
(50, NULL, 'REGISTER', 'New student registered: Messi (STU-1029)', '::1', '2026-07-21 09:56:09'),
(51, NULL, 'LOGIN', 'User Messi logged in successfully as student.', '::1', '2026-07-21 09:57:10'),
(52, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-21 09:59:26'),
(53, NULL, 'LOGOUT', 'User Messi logged out.', '::1', '2026-07-21 10:09:04'),
(54, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 10:10:33'),
(55, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 4.0%', '::1', '2026-07-21 10:11:47'),
(56, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 4.0%', '::1', '2026-07-21 10:17:04'),
(57, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 10:18:15'),
(58, NULL, 'REGISTER', 'New student registered: shrey (STU-1030)', '::1', '2026-07-21 10:19:34'),
(59, NULL, 'LOGIN', 'User shrey logged in successfully as student.', '::1', '2026-07-21 10:19:54'),
(60, NULL, 'LOGOUT', 'User shrey logged out.', '::1', '2026-07-21 10:20:05'),
(61, NULL, 'LOGIN', 'User shrey logged in successfully as student.', '::1', '2026-07-21 10:20:40'),
(62, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 20.0%', '::1', '2026-07-21 10:30:10'),
(63, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 48.0%', '::1', '2026-07-21 10:33:27'),
(64, NULL, 'LOGOUT', 'User shrey logged out.', '::1', '2026-07-21 10:49:56'),
(65, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 10:50:09'),
(66, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 10:56:58'),
(67, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 10:59:15'),
(68, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 10:59:33'),
(69, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 11:05:10'),
(70, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 11:06:16'),
(71, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-21 11:08:00'),
(72, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-21 11:08:35'),
(73, 2, 'LOGIN', 'User f_turing logged in successfully as faculty.', '::1', '2026-07-21 11:09:40'),
(74, 2, 'LOGOUT', 'User f_turing logged out.', '::1', '2026-07-21 11:09:59'),
(75, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 11:18:24'),
(76, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 11:23:22'),
(77, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-21 11:26:37'),
(78, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-21 11:31:49'),
(79, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-21 11:39:35'),
(82, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 00:32:39'),
(83, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-22 00:33:31'),
(84, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-22 00:34:06'),
(85, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-22 00:34:21'),
(86, 7, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for student1@skillbridge.edu.', '::1', '2026-07-22 00:35:35'),
(87, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 00:37:45'),
(88, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 00:37:57'),
(89, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-22 00:38:37'),
(90, 36, 'REGISTER', 'New student registered: encore.exe (STU-1036)', '::1', '2026-07-22 01:03:55'),
(91, 36, 'EMAIL_VERIFIED', 'User encore.exe verified email successfully via OTP.', '::1', '2026-07-22 01:04:43'),
(92, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 01:05:04'),
(93, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 01:41:57'),
(94, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 01:43:28'),
(95, 36, 'ENROLL_COURSE', 'Enrolled in course: Full Stack Web Architecture Capstone', '::1', '2026-07-22 02:25:34'),
(96, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 02:46:24'),
(97, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 02:46:56'),
(98, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 02:49:07'),
(99, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 02:50:32'),
(100, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 02:51:09'),
(101, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 02:51:23'),
(102, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 02:54:16'),
(103, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:02:20'),
(104, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:02:29'),
(105, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:03:28'),
(106, 7, 'LOGOUT', 'User student_test logged out.', '127.0.0.1', '2026-07-22 03:06:41'),
(107, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:07:11'),
(108, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:07:35'),
(109, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:08:10'),
(110, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:08:32'),
(111, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:10:45'),
(112, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 03:11:34'),
(113, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-22 03:12:17'),
(114, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-22 03:12:37'),
(115, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-22 03:16:35'),
(116, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:17:02'),
(117, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:34:02'),
(118, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:38:37'),
(119, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:38:55'),
(120, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:39:10'),
(121, 7, 'LOGIN', 'User s_john logged in successfully as student.', '127.0.0.1', '2026-07-22 03:43:14'),
(122, 7, 'LOGIN', 'User s_john logged in successfully as student.', '127.0.0.1', '2026-07-22 03:43:14'),
(123, 7, 'LOGOUT', 'User s_john logged out.', '127.0.0.1', '2026-07-22 03:43:14'),
(124, 1, 'LOGIN', 'User admin logged in successfully as admin.', '127.0.0.1', '2026-07-22 03:43:14'),
(125, 1, 'LOGOUT', 'User admin logged out.', '127.0.0.1', '2026-07-22 03:43:14'),
(126, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:44:35'),
(127, 36, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:45:32'),
(128, 36, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:54:22'),
(129, 36, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-22 03:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `department` varchar(100) DEFAULT 'System Administration',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `first_name`, `last_name`, `avatar`, `department`, `created_at`) VALUES
(1, 1, 'System', 'Administrator', 'default-avatar.png', 'IT & Operations', '2026-07-20 20:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `skill_id` int(11) NOT NULL,
  `created_by_faculty_id` int(11) NOT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 30,
  `passing_marks` int(11) NOT NULL DEFAULT 6,
  `total_marks` int(11) NOT NULL DEFAULT 10,
  `difficulty_level` enum('beginner','easy','intermediate','advanced','expert') NOT NULL DEFAULT 'intermediate',
  `status` enum('draft','active','archived') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`id`, `title`, `description`, `skill_id`, `created_by_faculty_id`, `duration_minutes`, `passing_marks`, `total_marks`, `difficulty_level`, `status`, `created_at`) VALUES
(1, 'PHP 8 Core Concepts & PDO Mastery', 'Evaluates knowledge of OOP in PHP, PDO database queries, session handling, and backend logic.', 1, 1, 20, 20, 25, 'intermediate', 'active', '2026-07-20 20:01:07'),
(2, 'MySQL Relational Schema & SQL Querying', 'Tests database normalization, multi-table JOINs, subqueries, and indexing principles.', 2, 2, 20, 20, 25, 'intermediate', 'active', '2026-07-20 20:01:07'),
(3, 'JavaScript ES6 Asynchronous Programming', 'Focuses on Promises, async/await, DOM events, and modern JavaScript syntax.', 3, 3, 15, 20, 25, 'beginner', 'active', '2026-07-20 20:01:07'),
(4, 'HTML5 Semantic Markup & CSS3 Layouts', 'Tests semantic HTML elements, Flexbox layout, responsive media queries, and specificity.', 4, 4, 15, 20, 25, 'beginner', 'active', '2026-07-20 20:01:07'),
(5, 'Bootstrap 5 Responsive Grid & UI Components', 'Covers Bootstrap grid system, dynamic utility classes, navigation, and modal components.', 5, 1, 15, 20, 25, 'beginner', 'active', '2026-07-20 20:01:07'),
(6, 'Web Security & OWASP Top 10 Defenses', 'Assesses practical mitigation of SQL Injection, XSS attacks, CSRF, and safe auth sessions.', 6, 5, 20, 20, 25, 'advanced', 'active', '2026-07-20 20:01:07'),
(7, 'RESTful API Design & HTTP Header Standards', 'Tests HTTP request methods, JSON response formatting, status codes, and API security.', 7, 2, 20, 20, 25, 'intermediate', 'active', '2026-07-20 20:01:07'),
(8, 'Data Structures: Arrays, Lists & Trees', 'Evaluates algorithmic efficiency, Big-O notation, tree traversals, and search logic.', 8, 3, 25, 20, 25, 'advanced', 'active', '2026-07-20 20:01:07'),
(9, 'Object-Oriented Design & Design Patterns', 'Covers OOP principles (SOLID), Singleton patterns, Factory pattern, and class encapsulation.', 9, 4, 20, 20, 25, 'intermediate', 'active', '2026-07-20 20:01:07'),
(10, 'Git Version Control & Merge Workflows', 'Evaluates Git commands, branch management, merge conflict resolution, and git log history.', 10, 5, 15, 20, 25, 'beginner', 'active', '2026-07-20 20:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `assessment_questions`
--

CREATE TABLE `assessment_questions` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `correct_option` enum('A','B','C','D') NOT NULL,
  `marks` int(11) NOT NULL DEFAULT 1,
  `category` varchar(50) DEFAULT 'Core Concepts'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_questions`
--

INSERT INTO `assessment_questions` (`id`, `assessment_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `marks`, `category`) VALUES
(1, 1, 'Which PHP 8 feature allows initializing class properties directly inside constructor parameters?', 'Named Arguments', 'Constructor Property Promotion', 'Match Expressions', 'Nullsafe Operator', 'B', 1, 'PHP 8 Syntax'),
(2, 1, 'What is the recommended approach to prevent SQL Injection in pure PHP database queries?', 'Using addslashes()', 'Using PDO prepared statements with bound parameters', 'Using htmlspecialchars()', 'Escaping single quotes manually', 'B', 1, 'Security & Database'),
(3, 1, 'In PHP, which superglobal array stores data passed through HTTP POST requests?', '$_GET', '$_REQUEST', '$_POST', '$_SERVER', 'C', 1, 'Superglobals'),
(4, 1, 'What does the PDO::FETCH_ASSOC fetch mode return?', 'An array indexed by column number', 'An object with property names matching column names', 'An array indexed by column name', 'A string of JSON data', 'C', 1, 'PDO'),
(5, 1, 'Which function in PHP destroys all data registered to a session?', 'session_unset()', 'session_destroy()', 'session_reset()', 'unset($_SESSION)', 'B', 1, 'Session Management'),
(6, 1, 'What will the expression `null ?? \"default\"` evaluate to in PHP?', 'null', 'default', 'false', 'Syntax Error', 'B', 1, 'Operators'),
(7, 1, 'Which PHP 8 function checks if a string contains a specific substring?', 'strpos()', 'strstr()', 'str_contains()', 'substr_count()', 'C', 1, 'String Functions'),
(8, 1, 'What access modifier makes a property accessible only within the class where it is declared?', 'public', 'protected', 'private', 'static', 'C', 1, 'OOP Principles'),
(9, 1, 'How do you define a constant in a PHP class?', 'const MY_CONST = 100;', 'define(\"MY_CONST\", 100);', 'var MY_CONST = 100;', 'static MY_CONST = 100;', 'A', 1, 'OOP Principles'),
(10, 1, 'Which header function call correctly redirects the browser to index.php?', 'header(\"Location: index.php\");', 'header(\"Redirect: index.php\");', 'header(\"Url: index.php\");', 'header(\"Goto: index.php\");', 'A', 1, 'HTTP Headers'),
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
(31, 4, 'Which semantic HTML5 tag should be used for the primary introductory content or nav links?', '<section>', '<header>', '<aside>', '<article>', 'B', 1, 'HTML5 Semantics'),
(32, 4, 'In CSS Flexbox, which property aligns items along the main axis?', 'align-items', 'justify-content', 'align-content', 'flex-direction', 'B', 1, 'CSS Flexbox'),
(33, 4, 'What CSS box-sizing value ensures padding and border are included in the element total width and height?', 'content-box', 'border-box', 'padding-box', 'inherit', 'B', 1, 'CSS Box Model'),
(34, 4, 'Which HTML5 input type provides built-in email format validation in forms?', '<input type=\"text\">', '<input type=\"email\">', '<input type=\"mail\">', '<input type=\"validate\">', 'B', 1, 'HTML5 Forms'),
(35, 4, 'What CSS pseudo-class targets an element when a user hovers over it with a pointer?', ':active', ':focus', ':hover', ':visited', 'C', 1, 'CSS Selectors'),
(36, 4, 'Which media query feature checks the width of the user viewport?', 'min-device-width', 'min-width', 'resolution', 'orientation', 'B', 1, 'Responsive CSS'),
(37, 4, 'What is the correct HTML element for playing audio files natively?', '<sound>', '<audio>', '<music>', '<media>', 'B', 1, 'HTML5 Media'),
(38, 4, 'Which CSS property controls the stacking order of elements positioned relative or absolute?', 'display', 'float', 'z-index', 'opacity', 'C', 1, 'CSS Layout'),
(39, 4, 'What attribute provides alternative text for an image if it fails to load?', 'title', 'alt', 'caption', 'desc', 'B', 1, 'Accessibility'),
(40, 4, 'In CSS Grid, which property defines the track sizes of rows?', 'grid-template-columns', 'grid-template-rows', 'grid-gap', 'grid-auto-flow', 'B', 1, 'CSS Grid'),
(41, 5, 'How many responsive grid columns are in standard Bootstrap 5?', '10', '12', '16', '24', 'B', 1, 'Bootstrap Grid'),
(42, 5, 'Which Bootstrap class creates a flexbox container that spans the full width of the viewport?', 'container', 'container-fluid', 'container-full', 'row-fluid', 'B', 1, 'Bootstrap Layout'),
(43, 5, 'Which class adds a modern card container with borders and padding in Bootstrap 5?', '.box', '.card', '.panel', '.well', 'B', 1, 'Bootstrap Components'),
(44, 5, 'What is the Bootstrap 5 class to color a button blue (primary brand color)?', 'btn-blue', 'btn-info', 'btn-primary', 'btn-accent', 'C', 1, 'Bootstrap Components'),
(45, 5, 'Which utility class turns text bold in Bootstrap 5?', '.font-bold', '.fw-bold', '.text-bold', '.weight-bold', 'B', 1, 'Utilities'),
(46, 5, 'What class aligns text to the center in Bootstrap 5?', '.text-center', '.align-center', '.center-text', '.justify-center', 'A', 1, 'Utilities'),
(47, 5, 'Which breakpoint prefix corresponds to extra-large screens (≥1200px) in Bootstrap 5?', 'md', 'lg', 'xl', 'xxl', 'C', 1, 'Breakpoints'),
(48, 5, 'Which component displays a dismissible contextual feedback message?', 'Modal', 'Toast', 'Alert', 'Badge', 'C', 1, 'Components'),
(49, 5, 'What attribute triggers a Bootstrap 5 modal window via button click?', 'data-toggle=\"modal\"', 'data-bs-toggle=\"modal\"', 'data-target=\"modal\"', 'bs-modal=\"open\"', 'B', 1, 'JS Plugins'),
(50, 5, 'Which utility class adds margin to the bottom of an element (spacing scale 3)?', 'm-3', 'mb-3', 'my-3', 'pb-3', 'B', 1, 'Spacing Utilities'),
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
(61, 7, 'Which HTTP method is idempotent and intended for updating an existing resource completely?', 'POST', 'PUT', 'GET', 'DELETE', 'B', 1, 'HTTP Methods'),
(62, 7, 'What standard content-type header is set when sending JSON data in REST APIs?', 'text/html', 'application/json', 'multipart/form-data', 'application/x-www-form-urlencoded', 'B', 1, 'API Headers'),
(63, 7, 'Which HTTP status code indicates successful resource creation?', '200 OK', '201 Created', '204 No Content', '302 Found', 'B', 1, 'Status Codes'),
(64, 7, 'What is the primary characteristic of a RESTful API concerning client state?', 'Stateful', 'Stateless', 'Session-bound', 'Database-locked', 'B', 1, 'REST Principles'),
(65, 7, 'Which HTTP method is used to retrieve data from a server without side effects?', 'POST', 'GET', 'PATCH', 'DELETE', 'B', 1, 'HTTP Methods'),
(66, 7, 'What format is most commonly used for RESTful API payloads today?', 'XML', 'JSON', 'YAML', 'CSV', 'B', 1, 'Data Formats'),
(67, 7, 'Which status code represents \"Internal Server Error\"?', '400', '404', '500', '503', 'C', 1, 'Status Codes'),
(68, 7, 'What header sends bearer tokens for API authentication?', 'Cookie', 'Authorization', 'User-Agent', 'Accept', 'B', 1, 'API Auth'),
(69, 7, 'What does HATEOAS stand for in advanced REST architecture?', 'Hypermedia As The Engine Of Application State', 'Hypertext And Text Editing Operating System', 'High Availability Transfer Engine System', 'Hosted Application Technology Architecture', 'A', 1, 'REST Architecture'),
(70, 7, 'Which HTTP method is used for partial updates to a resource?', 'PUT', 'PATCH', 'POST', 'UPDATE', 'B', 1, 'HTTP Methods'),
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
(81, 9, 'What does the \"S\" in SOLID design principles stand for?', 'Single Responsibility Principle', 'Substitute Responsibility Principle', 'Software Engineering Principle', 'Static Inheritance Principle', 'A', 1, 'SOLID'),
(82, 9, 'Which mechanism allows a child class to provide a specific implementation of a parent class method?', 'Method Overloading', 'Method Overriding', 'Method Shadowing', 'Method Encapsulation', 'B', 1, 'OOP Principles'),
(83, 9, 'What design pattern ensures a class has only one instance and provides a global point of access?', 'Factory Pattern', 'Observer Pattern', 'Singleton Pattern', 'Strategy Pattern', 'C', 1, 'Design Patterns'),
(84, 9, 'Hiding internal object details and exposing only necessary interfaces is called what?', 'Polymorphism', 'Encapsulation', 'Inheritance', 'Abstraction', 'B', 1, 'OOP Principles'),
(85, 9, 'Which keyword is used in PHP to inherit a parent class?', 'implements', 'extends', 'inherits', 'uses', 'B', 1, 'PHP OOP'),
(86, 9, 'Can an abstract class be instantiated directly with `new`?', 'Yes, always', 'No, abstract classes cannot be instantiated', 'Only if it has no parameters', 'Only in PHP 8', 'B', 1, 'OOP Principles'),
(87, 9, 'Which design pattern defines a one-to-many dependency between objects so that when one changes state, all dependents are notified?', 'Factory', 'Singleton', 'Observer', 'Adapter', 'C', 1, 'Design Patterns'),
(88, 9, 'What is an interface in OOP?', 'A class with concrete properties', 'A contract defining method signatures without implementation', 'A database table mapping', 'A dynamic array wrapper', 'B', 1, 'OOP Concepts'),
(89, 9, 'What is Polymorphism?', 'Ability of different objects to respond to the same method call in unique ways', 'Ability to create multiple threads', 'Grouping variables into a single file', 'Writing recursive code', 'A', 1, 'OOP Principles'),
(90, 9, 'Which SOLID principle states that soft units should be open for extension but closed for modification?', 'Single Responsibility', 'Open/Closed Principle', 'Liskov Substitution', 'Interface Segregation', 'B', 1, 'SOLID'),
(91, 10, 'Which command creates a local copy of a remote Git repository?', 'git fetch', 'git clone', 'git copy', 'git download', 'B', 1, 'Git Basics'),
(92, 10, 'Which command stages all modified and new files for commit in Git?', 'git stage -all', 'git add .', 'git commit -a', 'git push', 'B', 1, 'Git Workflow'),
(93, 10, 'What does `git pull` do under the hood?', 'Executes `git fetch` followed by `git merge`', 'Executes `git push`', 'Executes `git checkout`', 'Executes `git reset`', 'A', 1, 'Git Operations'),
(94, 10, 'Which command creates and switches to a new branch simultaneously?', 'git branch -new <name>', 'git checkout -b <name>', 'git switch -create <name>', 'git make-branch <name>', 'B', 1, 'Git Branching'),
(95, 10, 'How do you check the commit history of a repository?', 'git status', 'git log', 'git show-history', 'git list', 'B', 1, 'Git Basics'),
(96, 10, 'What file specifies intentionally untracked files that Git should ignore?', '.gitconfig', '.gitignore', '.gitkeep', '.gitmanifest', 'B', 1, 'Git Configuration'),
(97, 10, 'Which command temporarily saves uncommitted changes so you can work on something else?', 'git stash', 'git save', 'git pause', 'git store', 'A', 1, 'Git Stash'),
(98, 10, 'What happens when two branches have modified the same line in a file and you attempt to merge?', 'Git automatically picks the newest line', 'A merge conflict occurs requiring manual resolution', 'The operation is silently aborted', 'The repository gets corrupted', 'B', 1, 'Merge Conflicts'),
(99, 10, 'Which command uploads local branch commits to a remote repository?', 'git send', 'git push', 'git upload', 'git sync', 'B', 1, 'Remote Git'),
(100, 10, 'What command shows the working directory and staging area status?', 'git diff', 'git status', 'git check', 'git inspect', 'B', 1, 'Git Basics'),
(101, 1, 'Question 11: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(102, 1, 'Question 12: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(103, 1, 'Question 13: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(104, 1, 'Question 14: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(105, 1, 'Question 15: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(106, 1, 'Question 16: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(107, 1, 'Question 17: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(108, 1, 'Question 18: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(109, 1, 'Question 19: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(110, 1, 'Question 20: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(111, 1, 'Question 21: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(112, 1, 'Question 22: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(113, 1, 'Question 23: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(114, 1, 'Question 24: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(115, 1, 'Question 25: In PHP 8 Core Concepts & PDO Mastery, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(116, 2, 'Question 11: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(117, 2, 'Question 12: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(118, 2, 'Question 13: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(119, 2, 'Question 14: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(120, 2, 'Question 15: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(121, 2, 'Question 16: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(122, 2, 'Question 17: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(123, 2, 'Question 18: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(124, 2, 'Question 19: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(125, 2, 'Question 20: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(126, 2, 'Question 21: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(127, 2, 'Question 22: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(128, 2, 'Question 23: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(129, 2, 'Question 24: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(130, 2, 'Question 25: In MySQL Relational Schema & SQL Querying, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(131, 3, 'Question 11: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(132, 3, 'Question 12: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(133, 3, 'Question 13: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(134, 3, 'Question 14: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(135, 3, 'Question 15: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(136, 3, 'Question 16: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(137, 3, 'Question 17: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(138, 3, 'Question 18: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(139, 3, 'Question 19: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(140, 3, 'Question 20: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(141, 3, 'Question 21: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(142, 3, 'Question 22: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(143, 3, 'Question 23: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(144, 3, 'Question 24: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(145, 3, 'Question 25: In JavaScript ES6 Asynchronous Programming, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(146, 4, 'Question 11: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(147, 4, 'Question 12: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(148, 4, 'Question 13: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(149, 4, 'Question 14: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(150, 4, 'Question 15: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(151, 4, 'Question 16: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(152, 4, 'Question 17: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(153, 4, 'Question 18: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(154, 4, 'Question 19: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(155, 4, 'Question 20: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(156, 4, 'Question 21: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(157, 4, 'Question 22: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(158, 4, 'Question 23: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(159, 4, 'Question 24: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(160, 4, 'Question 25: In HTML5 Semantic Markup & CSS3 Layouts, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(161, 5, 'Question 11: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(162, 5, 'Question 12: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(163, 5, 'Question 13: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(164, 5, 'Question 14: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(165, 5, 'Question 15: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(166, 5, 'Question 16: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(167, 5, 'Question 17: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(168, 5, 'Question 18: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(169, 5, 'Question 19: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(170, 5, 'Question 20: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(171, 5, 'Question 21: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(172, 5, 'Question 22: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture');
INSERT INTO `assessment_questions` (`id`, `assessment_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `marks`, `category`) VALUES
(173, 5, 'Question 23: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(174, 5, 'Question 24: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(175, 5, 'Question 25: In Bootstrap 5 Responsive Grid & UI Components, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(176, 6, 'Question 11: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(177, 6, 'Question 12: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(178, 6, 'Question 13: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(179, 6, 'Question 14: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(180, 6, 'Question 15: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(181, 6, 'Question 16: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(182, 6, 'Question 17: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(183, 6, 'Question 18: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(184, 6, 'Question 19: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(185, 6, 'Question 20: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(186, 6, 'Question 21: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(187, 6, 'Question 22: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(188, 6, 'Question 23: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(189, 6, 'Question 24: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(190, 6, 'Question 25: In Web Security & OWASP Top 10 Defenses, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(191, 7, 'Question 11: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(192, 7, 'Question 12: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(193, 7, 'Question 13: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(194, 7, 'Question 14: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(195, 7, 'Question 15: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(196, 7, 'Question 16: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(197, 7, 'Question 17: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(198, 7, 'Question 18: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(199, 7, 'Question 19: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(200, 7, 'Question 20: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(201, 7, 'Question 21: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(202, 7, 'Question 22: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(203, 7, 'Question 23: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(204, 7, 'Question 24: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(205, 7, 'Question 25: In RESTful API Design & HTTP Header Standards, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(206, 8, 'Question 11: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(207, 8, 'Question 12: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(208, 8, 'Question 13: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(209, 8, 'Question 14: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(210, 8, 'Question 15: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(211, 8, 'Question 16: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(212, 8, 'Question 17: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(213, 8, 'Question 18: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(214, 8, 'Question 19: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(215, 8, 'Question 20: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(216, 8, 'Question 21: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(217, 8, 'Question 22: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(218, 8, 'Question 23: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(219, 8, 'Question 24: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(220, 8, 'Question 25: In Data Structures: Arrays, Lists & Trees, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(221, 9, 'Question 11: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(222, 9, 'Question 12: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(223, 9, 'Question 13: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(224, 9, 'Question 14: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(225, 9, 'Question 15: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(226, 9, 'Question 16: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(227, 9, 'Question 17: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(228, 9, 'Question 18: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(229, 9, 'Question 19: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(230, 9, 'Question 20: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(231, 9, 'Question 21: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(232, 9, 'Question 22: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(233, 9, 'Question 23: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(234, 9, 'Question 24: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(235, 9, 'Question 25: In Object-Oriented Design & Design Patterns, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(236, 10, 'Question 11: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(237, 10, 'Question 12: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(238, 10, 'Question 13: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(239, 10, 'Question 14: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(240, 10, 'Question 15: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(241, 10, 'Question 16: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(242, 10, 'Question 17: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(243, 10, 'Question 18: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(244, 10, 'Question 19: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(245, 10, 'Question 20: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(246, 10, 'Question 21: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(247, 10, 'Question 22: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(248, 10, 'Question 23: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(249, 10, 'Question 24: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture'),
(250, 10, 'Question 25: In Git Version Control & Merge Workflows, which concept best describes the optimal architectural pattern for system scaling and memory optimization?', 'Option A: Modular decoupled architecture with caching layers', 'Option B: Synchronous single-threaded blocking execution', 'Option C: Global state mutation without scoping controls', 'Option D: Unindexed linear table scans on foreign keys', 'A', 1, 'Core Architecture');

-- --------------------------------------------------------

--
-- Table structure for table `assessment_results`
--

CREATE TABLE `assessment_results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL DEFAULT 10,
  `correct_answers` int(11) NOT NULL DEFAULT 0,
  `score_obtained` decimal(5,2) NOT NULL DEFAULT 0.00,
  `score_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` enum('pass','fail') NOT NULL DEFAULT 'fail',
  `time_taken_seconds` int(11) NOT NULL DEFAULT 0,
  `completed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_results`
--

INSERT INTO `assessment_results` (`id`, `student_id`, `assessment_id`, `total_questions`, `correct_answers`, `score_obtained`, `score_percentage`, `status`, `time_taken_seconds`, `completed_at`) VALUES
(1, 1, 1, 10, 9, 9.00, 90.00, 'pass', 650, '2026-07-15 20:01:07'),
(2, 1, 2, 10, 8, 8.00, 80.00, 'pass', 710, '2026-07-16 20:01:07'),
(3, 1, 6, 10, 5, 5.00, 50.00, 'fail', 890, '2026-07-18 20:01:07'),
(4, 2, 3, 10, 9, 9.00, 90.00, 'pass', 500, '2026-07-14 20:01:07'),
(5, 2, 4, 10, 7, 7.00, 70.00, 'pass', 420, '2026-07-17 20:01:07'),
(6, 2, 6, 10, 4, 4.00, 40.00, 'fail', 950, '2026-07-19 20:01:07'),
(7, 3, 1, 10, 8, 8.00, 80.00, 'pass', 600, '2026-07-13 20:01:07'),
(8, 3, 2, 10, 9, 9.00, 90.00, 'pass', 580, '2026-07-16 20:01:07'),
(9, 3, 8, 10, 5, 5.00, 50.00, 'fail', 1100, '2026-07-19 20:01:07'),
(10, 4, 4, 10, 10, 10.00, 100.00, 'pass', 350, '2026-07-15 20:01:07'),
(11, 4, 5, 10, 9, 9.00, 90.00, 'pass', 390, '2026-07-17 20:01:07'),
(12, 5, 1, 10, 5, 5.00, 50.00, 'fail', 800, '2026-07-18 20:01:07'),
(13, 5, 7, 10, 8, 8.00, 80.00, 'pass', 720, '2026-07-19 20:01:07'),
(37, 30, 1, 25, 0, 0.00, 0.00, 'fail', 0, '2026-07-22 03:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_hours` int(11) NOT NULL DEFAULT 10,
  `difficulty_level` enum('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  `provider_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `platform` varchar(50) NOT NULL DEFAULT 'Udemy',
  `price` decimal(10,2) NOT NULL DEFAULT 499.00,
  `rating` decimal(3,2) NOT NULL DEFAULT 4.80,
  `instructor` varchar(100) NOT NULL DEFAULT 'SkillBridge Instructor',
  `track_category` varchar(50) NOT NULL DEFAULT 'frontend',
  `topic_pill` varchar(50) NOT NULL DEFAULT 'General',
  `lessons_json` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `title`, `description`, `duration_hours`, `difficulty_level`, `provider_url`, `status`, `created_at`, `platform`, `price`, `rating`, `instructor`, `track_category`, `topic_pill`, `lessons_json`) VALUES
(1, 'CS-101', 'Mastering Pure PHP 8 Development', 'Learn complete PHP 8 programming from fundamentals to advanced PDO database integration.', 25, 'intermediate', 'https://course.skillbridge.edu/php8-mastery', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.85, 'Dr. Robert Martin', 'backend', 'PHP', NULL),
(2, 'CS-102', 'Relational Database Masterclass: MySQL', 'Comprehensive database design, complex JOINs, indexing strategies, and normalization.', 20, 'intermediate', 'https://course.skillbridge.edu/mysql-mastery', 'active', '2026-07-20 20:01:07', 'Coursera', 0.00, 4.90, 'Prof. Elena Rostova', 'backend', 'SQL', NULL),
(3, 'CS-103', 'Modern JavaScript ES6+ Mastery', 'Deep dive into asynchronous JavaScript, Promises, DOM handling, and modern ES6 syntax.', 18, 'beginner', 'https://course.skillbridge.edu/js-es6', 'active', '2026-07-20 20:01:07', 'Udemy', 599.00, 4.80, 'Jonas Schmedtmann', 'frontend', 'JavaScript', NULL),
(4, 'CS-104', 'Responsive Design with Bootstrap 5', 'Build modern, responsive, component-rich web applications using Bootstrap 5 framework.', 15, 'beginner', 'https://course.skillbridge.edu/bootstrap5', 'active', '2026-07-20 20:01:07', 'Laracasts', 399.00, 4.75, 'Jeffrey Way', 'frontend', 'HTML/CSS', NULL),
(5, 'CS-105', 'Web Security Essentials & OWASP', 'Learn practical defenses against SQL Injection, XSS, CSRF, and broken session management.', 22, 'advanced', 'https://course.skillbridge.edu/web-security', 'active', '2026-07-20 20:01:07', 'edX', 0.00, 4.95, 'MIT OpenCourseWare', 'backend', 'Security', NULL),
(6, 'CS-106', 'RESTful API Engineering in PHP', 'Build lightweight, secure, JSON-based REST APIs using PHP and PDO prepared statements.', 16, 'intermediate', 'https://course.skillbridge.edu/php-rest-api', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(7, 'CS-107', 'Data Structures & Algorithms in Practice', 'Master essential algorithms and data structures with step-by-step code implementations.', 30, 'intermediate', 'https://course.skillbridge.edu/dsa-practice', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(8, 'CS-108', 'Object-Oriented Software Architecture', 'Apply solid OOP principles and design patterns to create maintainable enterprise code.', 24, 'advanced', 'https://course.skillbridge.edu/oop-architecture', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(9, 'CS-109', 'Git & GitHub Collaboration Workflow', 'Master version control, interactive rebasing, merge conflict resolution, and branching.', 12, 'beginner', 'https://course.skillbridge.edu/git-mastery', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(10, 'CS-110', 'UI/UX Fundamentals for Web Engineers', 'Design intuitive user experiences with high-contrast layouts, typography, and accessibility.', 15, 'beginner', 'https://course.skillbridge.edu/ui-ux-design', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(11, 'CS-111', 'Python for Software Automation', 'Write efficient Python scripts for data processing, web scraping, and task automation.', 20, 'beginner', 'https://course.skillbridge.edu/python-automation', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(12, 'CS-112', 'Docker Container Essentials', 'Containerize full-stack web applications with multi-container Docker Compose setups.', 18, 'intermediate', 'https://course.skillbridge.edu/docker-essentials', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(13, 'CS-113', 'React Frontend Foundations', 'Build dynamic single-page web applications using React hooks and state management.', 25, 'intermediate', 'https://course.skillbridge.edu/react-foundations', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(14, 'CS-114', 'Cloud Infrastructure Fundamentals', 'Deploy scalable web applications to AWS Cloud services with secure networking.', 28, 'advanced', 'https://course.skillbridge.edu/aws-cloud', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(15, 'CS-115', 'Automated Software Testing & TDD', 'Write unit tests, integration tests, and implement Test-Driven Development workflows.', 20, 'intermediate', 'https://course.skillbridge.edu/qa-testing', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(16, 'CS-116', 'Asynchronous Node.js & Express', 'Build high-concurrency event-driven backends with Node.js, Express, and MongoDB.', 22, 'intermediate', 'https://course.skillbridge.edu/nodejs-express', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(17, 'CS-117', 'Linux Command Line Administration', 'Master bash commands, shell scripts, system services, and Linux server security.', 18, 'beginner', 'https://course.skillbridge.edu/linux-admin', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(18, 'CS-118', 'Agile Product Delivery & Scrum', 'Understand Agile principles, sprint execution, user story mapping, and team velocity.', 12, 'beginner', 'https://course.skillbridge.edu/agile-scrum', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(19, 'CS-119', 'Practical Cyber Security Defenses', 'Ethical hacking fundamentals, network packet analysis, and security hardening.', 30, 'advanced', 'https://course.skillbridge.edu/cybersecurity-defenses', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL),
(20, 'CS-120', 'Full Stack Web Architecture Capstone', 'Synthesize frontend, backend, database, and security concepts into a unified capstone.', 35, 'advanced', 'https://course.skillbridge.edu/fullstack-capstone', 'active', '2026-07-20 20:01:07', 'Udemy', 499.00, 4.80, 'SkillBridge Instructor', 'frontend', 'General', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_skills`
--

CREATE TABLE `course_skills` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `skill_level_gained` int(11) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_skills`
--

INSERT INTO `course_skills` (`id`, `course_id`, `skill_id`, `skill_level_gained`) VALUES
(1, 1, 1, 4),
(2, 2, 2, 4),
(3, 3, 3, 3),
(4, 4, 4, 3),
(5, 4, 5, 4),
(6, 5, 6, 5),
(7, 6, 7, 4),
(8, 7, 8, 4),
(9, 8, 9, 5),
(10, 9, 10, 3),
(11, 10, 11, 3),
(12, 11, 12, 4),
(13, 12, 13, 4),
(14, 13, 14, 4),
(15, 14, 15, 5),
(16, 15, 16, 4),
(17, 16, 17, 4),
(18, 17, 18, 3),
(19, 18, 19, 3),
(20, 19, 20, 5);

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `employee_code` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `department` varchar(100) NOT NULL DEFAULT 'Computer Science',
  `designation` varchar(100) NOT NULL DEFAULT 'Assistant Professor',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `user_id`, `employee_code`, `first_name`, `last_name`, `avatar`, `department`, `designation`, `created_at`) VALUES
(1, 2, 'FAC-001', 'Alan', 'Turing', 'default-avatar.png', 'Computer Science', 'Professor & HOD', '2026-07-20 20:01:07'),
(2, 3, 'FAC-002', 'Grace', 'Hopper', 'default-avatar.png', 'Software Engineering', 'Associate Professor', '2026-07-20 20:01:07'),
(3, 4, 'FAC-003', 'Donald', 'Knuth', 'default-avatar.png', 'Computer Science', 'Senior Professor', '2026-07-20 20:01:07'),
(4, 5, 'FAC-004', 'Ada', 'Lovelace', 'default-avatar.png', 'Information Technology', 'Assistant Professor', '2026-07-20 20:01:07'),
(5, 6, 'FAC-005', 'Linus', 'Torvalds', 'default-avatar.png', 'Systems Engineering', 'Associate Professor', '2026-07-20 20:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_role` enum('student','faculty','admin') NOT NULL DEFAULT 'student',
  `category` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `message` text NOT NULL,
  `status` enum('pending','reviewed','resolved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `user_role`, `category`, `rating`, `message`, `status`, `created_at`) VALUES
(1, 27, 'student', 'Skill Assessments', 5, 'Automated test feedback entry: The 5-tier assessment system is highly effective.', 'pending', '2026-07-20 19:14:24'),
(2, 29, 'student', 'Skill Assessments', 5, 'tttttttt', 'pending', '2026-07-21 04:38:02'),
(3, 27, 'student', 'General Feedback', 5, 'hi it very nice', 'pending', '2026-07-21 05:24:03'),
(4, 36, 'student', 'General Feedback', 5, 'Great job Developers ....!', 'pending', '2026-07-21 21:39:15'),
(5, 36, 'student', 'General Feedback', 5, 'hii', 'pending', '2026-07-21 22:04:23');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `module_name` varchar(100) NOT NULL DEFAULT 'Module 1: Core Fundamentals',
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 15,
  `sort_order` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `module_name`, `title`, `description`, `video_url`, `duration_minutes`, `sort_order`, `created_at`) VALUES
(1, 1, 'Module 1: Introduction & Fundamentals', '1. Introduction to Mastering Pure PHP 8 Development', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(2, 1, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(3, 1, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(4, 2, 'Module 1: Introduction & Fundamentals', '1. Introduction to Relational Database Masterclass: MySQL', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(5, 2, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(6, 2, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(7, 3, 'Module 1: Introduction & Fundamentals', '1. Introduction to Modern JavaScript ES6+ Mastery', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(8, 3, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(9, 3, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(10, 4, 'Module 1: Introduction & Fundamentals', '1. Introduction to Responsive Design with Bootstrap 5', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(11, 4, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(12, 4, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(13, 5, 'Module 1: Introduction & Fundamentals', '1. Introduction to Web Security Essentials & OWASP', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(14, 5, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(15, 5, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(16, 6, 'Module 1: Introduction & Fundamentals', '1. Introduction to RESTful API Engineering in PHP', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(17, 6, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(18, 6, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(19, 7, 'Module 1: Introduction & Fundamentals', '1. Introduction to Data Structures & Algorithms in Practice', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(20, 7, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(21, 7, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(22, 8, 'Module 1: Introduction & Fundamentals', '1. Introduction to Object-Oriented Software Architecture', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(23, 8, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(24, 8, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(25, 9, 'Module 1: Introduction & Fundamentals', '1. Introduction to Git & GitHub Collaboration Workflow', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(26, 9, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(27, 9, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(28, 10, 'Module 1: Introduction & Fundamentals', '1. Introduction to UI/UX Fundamentals for Web Engineers', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(29, 10, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(30, 10, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(31, 11, 'Module 1: Introduction & Fundamentals', '1. Introduction to Python for Software Automation', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(32, 11, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(33, 11, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(34, 12, 'Module 1: Introduction & Fundamentals', '1. Introduction to Docker Container Essentials', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(35, 12, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(36, 12, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(37, 13, 'Module 1: Introduction & Fundamentals', '1. Introduction to React Frontend Foundations', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(38, 13, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(39, 13, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(40, 14, 'Module 1: Introduction & Fundamentals', '1. Introduction to Cloud Infrastructure Fundamentals', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(41, 14, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(42, 14, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(43, 15, 'Module 1: Introduction & Fundamentals', '1. Introduction to Automated Software Testing & TDD', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(44, 15, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(45, 15, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(46, 16, 'Module 1: Introduction & Fundamentals', '1. Introduction to Asynchronous Node.js & Express', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(47, 16, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(48, 16, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(49, 17, 'Module 1: Introduction & Fundamentals', '1. Introduction to Linux Command Line Administration', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(50, 17, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(51, 17, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(52, 18, 'Module 1: Introduction & Fundamentals', '1. Introduction to Agile Product Delivery & Scrum', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(53, 18, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(54, 18, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(55, 19, 'Module 1: Introduction & Fundamentals', '1. Introduction to Practical Cyber Security Defenses', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(56, 19, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28'),
(57, 19, 'Module 3: Advanced Optimization & Project', '3. Production Deployment & Best Practices', 'Comprehensive testing, security hardening, performance optimization, and final deployment.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 30, 3, '2026-07-22 02:33:28'),
(58, 20, 'Module 1: Introduction & Fundamentals', '1. Introduction to Full Stack Web Architecture Capstone', 'Overview of core principles, development environment setup, and prerequisites.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15, 1, '2026-07-22 02:33:28'),
(59, 20, 'Module 2: Practical Concepts & Implementation', '2. Building Core Components', 'Hands-on implementation of primary architecture, functions, and workflows.', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25, 2, '2026-07-22 02:33:28');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT '#',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(50) DEFAULT 'system',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `link`, `is_read`, `type`, `created_at`) VALUES
(1, 7, 'Assessment Result Available', 'Your score for \"PHP 8 Core Concepts & PDO Mastery\" is 90.00% (Passed).', '/student/assessment-result.php?id=1', 1, 'assessment', '2026-07-20 20:01:07'),
(2, 7, 'Course Recommendation', 'You have a high-priority course recommendation: Web Security Essentials & OWASP.', '/student/recommendations.php', 1, 'recommendation', '2026-07-20 20:01:07'),
(3, 8, 'Assessment Assigned', 'Faculty Grace Hopper assigned a new assessment: JavaScript ES6 Asynchronous Programming.', '/student/take-assessment.php?id=3', 1, 'assessment', '2026-07-20 20:01:07'),
(4, 8, 'Course Recommendation', 'New course recommendation based on your recent Web Security assessment gap.', '/student/recommendations.php', 0, 'recommendation', '2026-07-20 20:01:07'),
(5, 9, 'Assessment Result Available', 'Your score for \"Data Structures: Arrays, Lists & Trees\" is 50.00% (Failed).', '/student/assessment-result.php?id=9', 0, 'assessment', '2026-07-20 20:01:07'),
(6, 1, 'System Backup Reminder', 'Weekly database backup and audit scheduled today.', '/admin/backup.php', 0, 'system', '2026-07-20 20:01:07'),
(7, 2, 'Class Assessment Submitted', 'Student John Doe has completed PHP 8 Core Concepts & PDO Mastery.', '/faculty/evaluate.php?assessment_id=1', 1, 'assessment', '2026-07-20 20:01:07'),
(36, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student John Doe completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 85.0% on 22 Jul 2026, 01:53 AM.', 'http://localhostC:/Users/yashs/.gemini/antigravity-ide/brain/cbc92975-2724-482c-b2ef-ecac81861c28/scratch/faculty/evaluate.php?student_id=1', 0, 'assessment', '2026-07-22 01:53:39'),
(37, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Encore Abj completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 0.0% on 22 Jul 2026, 03:54 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=30', 0, 'assessment', '2026-07-22 03:54:52'),
(38, 36, 'New Course Recommendation', 'We recommended course \'Mastering Pure PHP 8 Development\' to help improve your PHP 8 Web Development skill.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/recommendations.php', 0, 'recommendation', '2026-07-22 03:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(191) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'admin@skillbridge.edu', '31e0b14ea47f2ddf3c5614b558bd13d9f5086b48828f577af19af74fd8d9f23a', '2026-07-21 02:40:44', '2026-07-21 01:40:44'),
(2, 'sudrikyash1@gmail.com', '48bfc38bb422161287aa022ea22c70e31181fb5f60f4c337e92ae34bb6ecdf2b', '2026-07-21 11:09:29', '2026-07-21 10:09:29');

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE `recommendations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `priority_level` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `is_dismissed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recommendations`
--

INSERT INTO `recommendations` (`id`, `student_id`, `course_id`, `skill_id`, `reason`, `priority_level`, `is_dismissed`, `created_at`) VALUES
(1, 1, 5, 6, 'Assessment score in Web Security & OWASP Top 10 was 50.00%. Targeted learning recommended to bridge security gap.', 'high', 0, '2026-07-20 20:01:07'),
(2, 2, 5, 6, 'Assessment score in Web Security & OWASP Top 10 was 40.00%. Highly recommended to build defensive web coding skills.', 'high', 0, '2026-07-20 20:01:07'),
(3, 3, 7, 8, 'Assessment score in Data Structures was 50.00%. Recommended to strengthen computer science fundamentals.', 'high', 0, '2026-07-20 20:01:07'),
(4, 5, 1, 1, 'Assessment score in PHP 8 Core Concepts was 50.00%. Recommended to complete core PHP course modules.', 'high', 0, '2026-07-20 20:01:07'),
(5, 4, 10, 11, 'Recommended based on high score in HTML5/CSS3 to expand frontend UI/UX design knowledge.', 'medium', 0, '2026-07-20 20:01:07'),
(11, 30, 1, 1, 'Your recent assessment in PHP 8 Web Development was 0.00%. Recommended to bridge your 100.0% skill gap.', 'high', 0, '2026-07-22 03:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `report_type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `generated_by_user_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `report_type`, `title`, `generated_by_user_id`, `file_path`, `created_at`) VALUES
(1, 'Skill Gap', 'Departmental Skill Gap Summary - Q3', 1, 'reports/skill_gap_q3.pdf', '2026-07-20 20:01:07'),
(2, 'Student Performance', 'CS-101 PHP 8 Class Evaluation Report', 2, 'reports/cs101_eval.pdf', '2026-07-20 20:01:07'),
(3, 'Course Completion', 'Annual SkillBridge Institutional Learning Metrics', 1, 'reports/annual_metrics.pdf', '2026-07-20 20:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'Technical',
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `category`, `description`, `created_at`) VALUES
(1, 'PHP 8 Web Development', 'Backend', 'Object-Oriented PHP, PDO, MVC architecture, and backend logic.', '2026-07-20 20:01:07'),
(2, 'MySQL Database Design', 'Database', 'Relational database schema normalization, SQL queries, index optimization.', '2026-07-20 20:01:07'),
(3, 'JavaScript ES6+', 'Frontend', 'Asynchronous JS, Promises, Fetch API, DOM manipulation, ES6 syntax.', '2026-07-20 20:01:07'),
(4, 'HTML5 & Responsive CSS3', 'Frontend', 'Semantic HTML5 markup, Flexbox, CSS Grid, media queries, and accessibility.', '2026-07-20 20:01:07'),
(5, 'Bootstrap 5 Framework', 'Frontend', 'Bootstrap grid system, utility classes, dynamic components, and dark themes.', '2026-07-20 20:01:07'),
(6, 'Web Application Security', 'Security', 'OWASP Top 10 mitigation, XSS prevention, CSRF tokens, SQL injection defense.', '2026-07-20 20:01:07'),
(7, 'RESTful API Architecture', 'Backend', 'API design principles, JSON data formats, HTTP headers, authentication tokens.', '2026-07-20 20:01:07'),
(8, 'Data Structures & Algorithms', 'Computer Science', 'Arrays, linked lists, trees, graphs, sorting, searching, and complexity analysis.', '2026-07-20 20:01:07'),
(9, 'Object-Oriented Programming', 'Software Design', 'Inheritance, polymorphism, encapsulation, abstraction, and design patterns.', '2026-07-20 20:01:07'),
(10, 'Version Control with Git', 'DevOps', 'Git workflows, branching, merging, pull requests, and remote repositories.', '2026-07-20 20:01:07'),
(11, 'UI/UX Interface Design', 'Design', 'User research, wireframing, color theory, typography, and micro-interactions.', '2026-07-20 20:01:07'),
(12, 'Python Programming', 'Programming', 'Python language syntax, data analysis libraries, script automation.', '2026-07-20 20:01:07'),
(13, 'Docker & Containerization', 'DevOps', 'Dockerfile creation, container orchestration, microservices deployment.', '2026-07-20 20:01:07'),
(14, 'React Frontend Development', 'Frontend', 'Component architecture, state hooks, virtual DOM, and single page applications.', '2026-07-20 20:01:07'),
(15, 'Cloud Computing (AWS/Azure)', 'Infrastructure', 'Cloud infrastructure services, virtual private clouds, storage buckets, IAM.', '2026-07-20 20:01:07'),
(16, 'Software Testing & QA', 'Quality Assurance', 'Unit testing, integration testing, test-driven development (TDD), automation.', '2026-07-20 20:01:07'),
(17, 'Node.js & Express Architecture', 'Backend', 'Event-driven asynchronous I/O backend development, middleware, npm.', '2026-07-20 20:01:07'),
(18, 'Linux System Administration', 'Systems', 'Shell scripting, file permissions, SSH keys, cron jobs, server management.', '2026-07-20 20:01:07'),
(19, 'Agile & Scrum Methodology', 'Management', 'Sprint planning, user stories, backlog grooming, daily standups, retrospectives.', '2026-07-20 20:01:07'),
(20, 'Cybersecurity Fundamentals', 'Security', 'Network security protocols, encryption algorithms, penetration testing basics.', '2026-07-20 20:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `student_code` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `bio` varchar(255) DEFAULT NULL,
  `city_location` varchar(100) DEFAULT 'Mumbai, India',
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(100) NOT NULL DEFAULT 'Computer Science',
  `current_semester` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_code`, `first_name`, `last_name`, `avatar`, `bio`, `city_location`, `phone`, `department`, `current_semester`, `created_at`) VALUES
(1, 7, 'STU-1001', 'John', 'Doe', 'default-avatar.png', NULL, 'Mumbai, India', '555-0101', 'Computer Science', 5, '2026-07-20 20:01:07'),
(2, 8, 'STU-1002', 'Emily', 'Smith', 'default-avatar.png', NULL, 'Mumbai, India', '555-0102', 'Information Technology', 5, '2026-07-20 20:01:07'),
(3, 9, 'STU-1003', 'Michael', 'Brown', 'default-avatar.png', NULL, 'Mumbai, India', '555-0103', 'Software Engineering', 6, '2026-07-20 20:01:07'),
(4, 10, 'STU-1004', 'Sophia', 'Johnson', 'default-avatar.png', NULL, 'Mumbai, India', '555-0104', 'Computer Science', 4, '2026-07-20 20:01:07'),
(5, 11, 'STU-1005', 'Daniel', 'Williams', 'default-avatar.png', NULL, 'Mumbai, India', '555-0105', 'Data Science', 6, '2026-07-20 20:01:07'),
(6, 12, 'STU-1006', 'Olivia', 'Jones', 'default-avatar.png', NULL, 'Mumbai, India', '555-0106', 'Software Engineering', 3, '2026-07-20 20:01:07'),
(7, 13, 'STU-1007', 'David', 'Miller', 'default-avatar.png', NULL, 'Mumbai, India', '555-0107', 'Computer Science', 5, '2026-07-20 20:01:07'),
(8, 14, 'STU-1008', 'Emma', 'Davis', 'default-avatar.png', NULL, 'Mumbai, India', '555-0108', 'Information Technology', 4, '2026-07-20 20:01:07'),
(9, 15, 'STU-1009', 'James', 'Wilson', 'default-avatar.png', NULL, 'Mumbai, India', '555-0109', 'Systems Engineering', 6, '2026-07-20 20:01:07'),
(10, 16, 'STU-1010', 'Ava', 'Taylor', 'default-avatar.png', NULL, 'Mumbai, India', '555-0110', 'Computer Science', 3, '2026-07-20 20:01:07'),
(11, 17, 'STU-1011', 'Alex', 'Anderson', 'default-avatar.png', NULL, 'Mumbai, India', '555-0111', 'Data Science', 5, '2026-07-20 20:01:07'),
(12, 18, 'STU-1012', 'Mia', 'Thomas', 'default-avatar.png', NULL, 'Mumbai, India', '555-0112', 'Software Engineering', 4, '2026-07-20 20:01:07'),
(13, 19, 'STU-1013', 'Ethan', 'Jackson', 'default-avatar.png', NULL, 'Mumbai, India', '555-0113', 'Computer Science', 6, '2026-07-20 20:01:07'),
(14, 20, 'STU-1014', 'Isabella', 'White', 'default-avatar.png', NULL, 'Mumbai, India', '555-0114', 'Information Technology', 3, '2026-07-20 20:01:07'),
(15, 21, 'STU-1015', 'William', 'Harris', 'default-avatar.png', NULL, 'Mumbai, India', '555-0115', 'Systems Engineering', 5, '2026-07-20 20:01:07'),
(16, 22, 'STU-1016', 'Charlotte', 'Martin', 'default-avatar.png', NULL, 'Mumbai, India', '555-0116', 'Computer Science', 4, '2026-07-20 20:01:07'),
(17, 23, 'STU-1017', 'Benjamin', 'Thompson', 'default-avatar.png', NULL, 'Mumbai, India', '555-0117', 'Software Engineering', 6, '2026-07-20 20:01:07'),
(18, 24, 'STU-1018', 'Amelia', 'Garcia', 'default-avatar.png', NULL, 'Mumbai, India', '555-0118', 'Data Science', 3, '2026-07-20 20:01:07'),
(19, 25, 'STU-1019', 'Lucas', 'Martinez', 'default-avatar.png', NULL, 'Mumbai, India', '555-0119', 'Computer Science', 5, '2026-07-20 20:01:07'),
(20, 26, 'STU-1020', 'Harper', 'Robinson', 'default-avatar.png', NULL, 'Mumbai, India', '555-0120', 'Information Technology', 4, '2026-07-20 20:01:07'),
(30, 36, 'STU-1036', 'Encore', 'Abj', 'avatar_user_36_1784664668.jpg', 'hii', 'Pune, India', '+91 7558272740', 'Computer Science', 1, '2026-07-22 01:03:55');

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `id` int(11) NOT NULL,
  `result_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_option` enum('A','B','C','D') DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `marks_obtained` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`id`, `result_id`, `question_id`, `selected_option`, `is_correct`, `marks_obtained`) VALUES
(101, 1, 1, 'B', 1, 1),
(102, 1, 2, 'B', 1, 1),
(103, 1, 3, 'C', 1, 1),
(104, 1, 4, 'C', 1, 1),
(105, 1, 5, 'B', 1, 1),
(106, 1, 6, 'B', 1, 1),
(107, 1, 7, 'C', 1, 1),
(108, 1, 8, 'C', 1, 1),
(109, 1, 9, 'A', 1, 1),
(110, 1, 10, 'A', 1, 1),
(111, 2, 11, 'B', 1, 1),
(112, 2, 12, 'D', 1, 1),
(113, 2, 13, 'B', 1, 1),
(114, 2, 14, 'C', 1, 1),
(115, 2, 15, 'B', 1, 1),
(116, 2, 16, 'B', 1, 1),
(117, 2, 17, 'B', 1, 1),
(118, 2, 18, 'A', 1, 1),
(119, 2, 19, 'C', 1, 1),
(120, 2, 20, 'B', 1, 1),
(121, 3, 51, 'B', 1, 1),
(122, 3, 52, 'B', 1, 1),
(123, 3, 53, 'D', 1, 1),
(124, 3, 54, 'B', 1, 1),
(125, 3, 55, 'B', 1, 1),
(126, 3, 56, 'B', 1, 1),
(127, 3, 57, 'B', 1, 1),
(128, 3, 58, 'B', 1, 1),
(129, 3, 59, 'B', 1, 1),
(130, 3, 60, 'A', 1, 1),
(351, 37, 1, NULL, 0, 0),
(352, 37, 2, NULL, 0, 0),
(353, 37, 3, NULL, 0, 0),
(354, 37, 4, NULL, 0, 0),
(355, 37, 5, NULL, 0, 0),
(356, 37, 6, NULL, 0, 0),
(357, 37, 7, NULL, 0, 0),
(358, 37, 8, NULL, 0, 0),
(359, 37, 9, NULL, 0, 0),
(360, 37, 10, NULL, 0, 0),
(361, 37, 101, NULL, 0, 0),
(362, 37, 102, NULL, 0, 0),
(363, 37, 103, NULL, 0, 0),
(364, 37, 104, NULL, 0, 0),
(365, 37, 105, NULL, 0, 0),
(366, 37, 106, NULL, 0, 0),
(367, 37, 107, NULL, 0, 0),
(368, 37, 108, NULL, 0, 0),
(369, 37, 109, NULL, 0, 0),
(370, 37, 110, NULL, 0, 0),
(371, 37, 111, NULL, 0, 0),
(372, 37, 112, NULL, 0, 0),
(373, 37, 113, NULL, 0, 0),
(374, 37, 114, NULL, 0, 0),
(375, 37, 115, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_progress`
--

CREATE TABLE `student_progress` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `progress_percentage` int(11) NOT NULL DEFAULT 0,
  `status` enum('not_started','in_progress','completed') NOT NULL DEFAULT 'not_started',
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_progress`
--

INSERT INTO `student_progress` (`id`, `student_id`, `course_id`, `progress_percentage`, `status`, `last_updated`) VALUES
(2, 1, 2, 80, 'in_progress', '2026-07-20 20:01:07'),
(3, 1, 5, 20, 'in_progress', '2026-07-20 20:01:07'),
(4, 2, 3, 100, 'completed', '2026-07-20 20:01:07'),
(5, 2, 4, 75, 'in_progress', '2026-07-20 20:01:07'),
(6, 2, 5, 10, 'in_progress', '2026-07-20 20:01:07'),
(7, 3, 1, 90, 'in_progress', '2026-07-20 20:01:07'),
(8, 3, 2, 100, 'completed', '2026-07-20 20:01:07'),
(9, 3, 7, 30, 'in_progress', '2026-07-20 20:01:07'),
(10, 4, 4, 100, 'completed', '2026-07-20 20:01:07'),
(11, 4, 5, 90, 'in_progress', '2026-07-20 20:01:07'),
(12, 5, 1, 40, 'in_progress', '2026-07-20 20:01:07'),
(13, 5, 6, 85, 'in_progress', '2026-07-20 20:01:07'),
(15, 30, 20, 10, 'in_progress', '2026-07-22 02:25:34'),
(17, 1, 1, 100, 'completed', '2026-07-22 02:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
('admin_email', 'admin@skillbridge.edu', 'general', 'System administrator contact email'),
('enable_auto_recommendations', '1', 'analytics', 'Automatically trigger AI/Rule recommendations on skill gaps'),
('institution_name', 'Global Institute of Technology', 'general', 'Educational institution name'),
('pass_mark_threshold', '60', 'assessment', 'Default passing percentage threshold for assessments'),
('session_timeout', '3600', 'security', 'Session expiration timeout in seconds'),
('site_name', 'SkillBridge LMS', 'general', 'Name of the learning management platform');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','faculty','admin') NOT NULL DEFAULT 'student',
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 1,
  `email_verification_otp` varchar(10) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `remember_token`, `reset_token`, `reset_token_expiry`, `email_verified`, `email_verification_otp`, `otp_expiry`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'sudrikyash1@gmail.com', '$2y$10$1WZxZiSjh.uF.FNVrB32Ae4TkHSDS8pmoFv44FlrCAK9RgbyQLW4u', 'admin', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-22 03:12:17'),
(2, 'f_turing', 'faculty1@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(3, 'f_hopper', 'faculty2@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(4, 'f_knuth', 'faculty3@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(5, 'f_lovelace', 'faculty4@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(6, 'f_torvalds', 'faculty5@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(7, 's_john', 'student1@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, '6a2ad5f8e7458727fa3580a35b70b987faba3e39f3aefd4df2caacb28d9ad9b7', '2026-07-22 01:05:30', 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-22 03:43:14'),
(8, 's_emily', 'student2@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(9, 's_michael', 'student3@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(10, 's_sophia', 'student4@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(11, 's_daniel', 'student5@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(12, 's_olivia', 'student6@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(13, 's_david', 'student7@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(14, 's_emma', 'student8@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(15, 's_james', 'student9@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(16, 's_ava', 'student10@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(17, 's_alex', 'student11@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(18, 's_mia', 'student12@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(19, 's_ethan', 'student13@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(20, 's_isabella', 'student14@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(21, 's_william', 'student15@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(22, 's_charlotte', 'student16@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(23, 's_benjamin', 'student17@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(24, 's_amelia', 'student18@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(25, 's_lucas', 'student19@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(26, 's_harper', 'student20@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(36, 'encore.exe', 'marathaedits96@gmail.com', '$2y$10$yLSa7GCcjioj49VoLrizp.WOEOhCjS9gM6iMhiAbZdrxnTM260Mku', 'student', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-22 01:03:55', '2026-07-22 03:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `last_activity` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_agent` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT '127.0.0.1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_user` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assessment_skill` (`skill_id`),
  ADD KEY `fk_assessment_faculty` (`created_by_faculty_id`);

--
-- Indexes for table `assessment_questions`
--
ALTER TABLE `assessment_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_question_assessment` (`assessment_id`);

--
-- Indexes for table `assessment_results`
--
ALTER TABLE `assessment_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_result_student` (`student_id`),
  ADD KEY `fk_result_assessment` (`assessment_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_code` (`course_code`);

--
-- Indexes for table `course_skills`
--
ALTER TABLE `course_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_course_skill` (`course_id`,`skill_id`),
  ADD KEY `fk_cs_skill` (`skill_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `employee_code` (`employee_code`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lessons_course` (`course_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notification_user` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rec_student` (`student_id`),
  ADD KEY `fk_rec_course` (`course_id`),
  ADD KEY `fk_rec_skill` (`skill_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_report_user` (`generated_by_user_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `student_code` (`student_code`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_answer_result` (`result_id`),
  ADD KEY `fk_answer_question` (`question_id`);

--
-- Indexes for table `student_progress`
--
ALTER TABLE `student_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_course` (`student_id`,`course_id`),
  ADD KEY `fk_progress_course` (`course_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `fk_session_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `assessment_questions`
--
ALTER TABLE `assessment_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `assessment_results`
--
ALTER TABLE `assessment_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `course_skills`
--
ALTER TABLE `course_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=376;

--
-- AUTO_INCREMENT for table `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fk_admins_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assessments`
--
ALTER TABLE `assessments`
  ADD CONSTRAINT `fk_assessment_faculty` FOREIGN KEY (`created_by_faculty_id`) REFERENCES `faculty` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_assessment_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assessment_questions`
--
ALTER TABLE `assessment_questions`
  ADD CONSTRAINT `fk_question_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assessment_results`
--
ALTER TABLE `assessment_results`
  ADD CONSTRAINT `fk_result_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_result_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_skills`
--
ALTER TABLE `course_skills`
  ADD CONSTRAINT `fk_cs_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cs_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `fk_faculty_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `fk_lessons_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD CONSTRAINT `fk_rec_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rec_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rec_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_report_user` FOREIGN KEY (`generated_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `assessment_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_answer_result` FOREIGN KEY (`result_id`) REFERENCES `assessment_results` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_progress`
--
ALTER TABLE `student_progress`
  ADD CONSTRAINT `fk_progress_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_progress_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `fk_session_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
