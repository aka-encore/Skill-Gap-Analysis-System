-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2026 at 06:44 PM
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
(2, NULL, 'ASSESSMENT_SUBMITTED', 'Student John Doe completed PHP 8 Core Concepts with score 90.00%.', '127.0.0.1', '2026-07-20 20:01:07'),
(3, 2, 'ASSESSMENT_CREATED', 'Faculty Alan Turing created assessment: PHP 8 Core Concepts & PDO Mastery.', '127.0.0.1', '2026-07-20 20:01:07'),
(4, NULL, 'ASSESSMENT_SUBMITTED', 'Student Emily Smith completed Web Security assessment with score 40.00%.', '127.0.0.1', '2026-07-20 20:01:07'),
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
(86, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for student1@skillbridge.edu.', '::1', '2026-07-22 00:35:35'),
(87, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 00:37:45'),
(88, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 00:37:57'),
(89, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-22 00:38:37'),
(90, NULL, 'REGISTER', 'New student registered: encore.exe (STU-1036)', '::1', '2026-07-22 01:03:55'),
(91, NULL, 'EMAIL_VERIFIED', 'User encore.exe verified email successfully via OTP.', '::1', '2026-07-22 01:04:43'),
(92, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 01:05:04'),
(93, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 01:41:57'),
(94, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 01:43:28'),
(95, NULL, 'ENROLL_COURSE', 'Enrolled in course: Full Stack Web Architecture Capstone', '::1', '2026-07-22 02:25:34'),
(96, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 02:46:24'),
(97, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 02:46:56'),
(98, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 02:49:07'),
(99, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 02:50:32'),
(100, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 02:51:09'),
(101, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 02:51:23'),
(102, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 02:54:16'),
(103, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:02:20'),
(104, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:02:29'),
(105, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:03:28'),
(106, NULL, 'LOGOUT', 'User student_test logged out.', '127.0.0.1', '2026-07-22 03:06:41'),
(107, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:07:11'),
(108, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:07:35'),
(109, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:08:10'),
(110, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:08:32'),
(111, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:10:45'),
(112, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 03:11:34'),
(113, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-22 03:12:17'),
(114, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-22 03:12:37'),
(115, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-22 03:16:35'),
(116, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:17:02'),
(117, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:34:02'),
(118, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:38:37'),
(119, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:38:55'),
(120, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:39:10'),
(121, NULL, 'LOGIN', 'User s_john logged in successfully as student.', '127.0.0.1', '2026-07-22 03:43:14'),
(122, NULL, 'LOGIN', 'User s_john logged in successfully as student.', '127.0.0.1', '2026-07-22 03:43:14'),
(123, NULL, 'LOGOUT', 'User s_john logged out.', '127.0.0.1', '2026-07-22 03:43:14'),
(124, 1, 'LOGIN', 'User admin logged in successfully as admin.', '127.0.0.1', '2026-07-22 03:43:14'),
(125, 1, 'LOGOUT', 'User admin logged out.', '127.0.0.1', '2026-07-22 03:43:14'),
(126, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:44:35'),
(127, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 03:45:32'),
(128, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 03:54:22'),
(129, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-22 03:54:52'),
(130, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 10:04:57'),
(131, NULL, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-22 10:25:12'),
(132, 37, 'REGISTER', 'New student registered: babudon2 (STU-1037)', '::1', '2026-07-22 10:27:19'),
(133, 37, 'RESEND_OTP', 'Resent email verification OTP for warriorbabu402@gmail.com.', '::1', '2026-07-22 10:28:11'),
(134, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 10:29:43'),
(135, NULL, 'REGISTER', 'New student registered: sumedh2 (STU-1038)', '::1', '2026-07-22 10:31:26'),
(136, NULL, 'RESEND_OTP', 'Resent email verification OTP for khalikarsumedh07@gmail.com.', '::1', '2026-07-22 10:33:18'),
(137, NULL, 'RESEND_OTP', 'Resent email verification OTP for khalikarsumedh07@gmail.com.', '::1', '2026-07-22 10:35:54'),
(138, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-22 10:36:20'),
(139, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-22 10:36:38'),
(140, NULL, 'REGISTER', 'New student registered: sumedh (STU-1039)', '::1', '2026-07-22 10:38:56'),
(141, NULL, 'REGISTER', 'New student registered: vaibhav1 (STU-1040)', '::1', '2026-07-22 11:07:05'),
(142, NULL, 'REGISTER', 'New student registered: pavan (STU-1041)', '::1', '2026-07-22 11:42:41'),
(143, 42, 'REGISTER', 'New student registered: rona (STU-1042)', '::1', '2026-07-22 11:44:45'),
(144, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-22 11:57:18'),
(145, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-22 12:11:24'),
(146, 43, 'REGISTER', 'New student registered: heroic (STU-1043)', '::1', '2026-07-22 12:13:32'),
(147, 44, 'REGISTER', 'New student registered: nsr (STU-1044)', '::1', '2026-07-22 13:12:45'),
(148, 45, 'REGISTER', 'New student registered: nikhil (STU-1045)', '::1', '2026-07-22 13:16:46'),
(149, 45, 'EMAIL_VERIFIED', 'User nikhil verified email successfully via OTP.', '::1', '2026-07-22 13:17:35'),
(150, 45, 'LOGIN', 'User nikhil logged in successfully as student.', '::1', '2026-07-22 13:17:52'),
(151, 45, 'LOGOUT', 'User nikhil logged out.', '::1', '2026-07-22 13:18:33'),
(152, 45, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for bettercallsaul9848@gmail.com.', '::1', '2026-07-22 13:19:35'),
(153, NULL, 'REGISTER', 'New student registered: JR. (STU-1046)', '::1', '2026-07-22 13:31:32'),
(154, 47, 'REGISTER', 'New student registered: sumedh2 (STU-1047)', '::1', '2026-07-22 13:37:48'),
(155, 47, 'EMAIL_VERIFIED', 'User sumedh2 verified email successfully via OTP.', '::1', '2026-07-22 13:38:19'),
(156, 47, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for khalikarsumedh07@gmail.com.', '::1', '2026-07-22 13:38:48'),
(157, 47, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for khalikarsumedh07@gmail.com.', '::1', '2026-07-22 13:39:24'),
(158, 47, 'LOGIN', 'User sumedh2 logged in successfully as student.', '::1', '2026-07-22 13:40:14'),
(159, 47, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 12.0%', '::1', '2026-07-22 13:44:53'),
(160, 47, 'LOGOUT', 'User sumedh2 logged out.', '::1', '2026-07-22 13:56:57'),
(161, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 14:00:03'),
(162, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-22 14:01:36'),
(163, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-22 14:02:08'),
(164, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-22 14:07:33'),
(165, NULL, 'REGISTER', 'New faculty registered: yashraj1 (EMP-1048)', '::1', '2026-07-22 14:11:21'),
(166, NULL, 'REGISTER', 'New faculty registered: yash (EMP-1049)', '::1', '2026-07-22 14:14:19'),
(167, NULL, 'EMAIL_VERIFIED', 'User yash verified email successfully via OTP.', '::1', '2026-07-22 14:14:50'),
(168, NULL, 'REGISTER', 'New student registered: Pavan123 (STU-1050)', '::1', '2026-07-22 14:25:02'),
(169, NULL, 'EMAIL_VERIFIED', 'User Pavan123 verified email successfully via OTP.', '::1', '2026-07-22 14:25:27'),
(170, NULL, 'LOGIN', 'User Pavan123 logged in successfully as student.', '::1', '2026-07-22 14:27:04'),
(171, NULL, 'LOGOUT', 'User Pavan123 logged out.', '::1', '2026-07-22 14:29:52'),
(172, 51, 'REGISTER', 'New student registered: praju (STU-1051)', '::1', '2026-07-22 14:32:28'),
(173, 51, 'EMAIL_VERIFIED', 'User praju verified email successfully via OTP.', '::1', '2026-07-22 14:32:58'),
(174, 51, 'LOGIN', 'User praju logged in successfully as student.', '::1', '2026-07-22 14:33:47'),
(175, 51, 'LOGOUT', 'User praju logged out.', '::1', '2026-07-22 14:52:54'),
(176, NULL, 'REGISTER', 'New student registered: vaibhav_07 (STU-1052)', '::1', '2026-07-22 14:57:59'),
(177, NULL, 'REGISTER', 'New student registered: vaibhav (STU-1053)', '::1', '2026-07-22 15:02:25'),
(178, NULL, 'RESEND_OTP', 'Resent email verification OTP for vaibhav0305c@gmail.com.', '::1', '2026-07-22 15:03:15'),
(179, NULL, 'RESEND_OTP', 'Resent email verification OTP for vaibhav0305c@gmail.com.', '::1', '2026-07-22 15:06:05'),
(180, NULL, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-22 21:37:23'),
(181, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 52.0%', '::1', '2026-07-22 21:41:02'),
(182, NULL, 'COURSE_COMPLETED', 'Completed course ID #20', '::1', '2026-07-22 21:43:05'),
(183, NULL, 'COURSE_COMPLETED', 'Completed course ID #20', '::1', '2026-07-22 21:43:07'),
(184, NULL, 'COURSE_COMPLETED', 'Completed course ID #20', '::1', '2026-07-22 21:43:07'),
(185, NULL, 'COURSE_COMPLETED', 'Completed course ID #20', '::1', '2026-07-22 21:43:07'),
(186, NULL, 'ENROLL_COURSE', 'Enrolled in course: Practical Cyber Security Defenses', '::1', '2026-07-22 21:44:47'),
(187, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 8.0%', '::1', '2026-07-22 22:08:20'),
(188, NULL, 'LOGOUT', 'User encore.ex logged out.', '::1', '2026-07-22 22:08:57'),
(189, NULL, 'LOGIN', 'User encore.ex logged in successfully as student.', '::1', '2026-07-22 22:09:18'),
(190, NULL, 'LOGOUT', 'User encore.ex logged out.', '::1', '2026-07-22 22:09:55'),
(191, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 22:15:02'),
(192, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-22 22:24:03'),
(193, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 22:29:07'),
(194, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 22:29:39'),
(195, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-22 22:32:22'),
(196, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 22:35:17'),
(197, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-22 22:36:05'),
(198, 1, 'SYSTEM_SETTING_UPDATE', 'Updated system settings', '::1', '2026-07-22 22:41:18'),
(199, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-22 22:51:38'),
(200, NULL, 'REGISTER', 'New student registered: writer (STU-1054)', '::1', '2026-07-22 22:53:12'),
(201, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-23 09:26:11'),
(202, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for pavanthote7777@gmail.com.', '::1', '2026-07-23 09:27:27'),
(203, NULL, 'REGISTER', 'New student registered: pavan (STU-1055)', '::1', '2026-07-23 09:30:05'),
(204, 56, 'REGISTER', 'New student registered: pavan (STU-1056)', '::1', '2026-07-23 09:44:09'),
(205, 56, 'EMAIL_VERIFIED', 'User pavan verified email successfully via OTP.', '::1', '2026-07-23 09:46:08'),
(206, 56, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for pavanthote7777@gmail.com.', '::1', '2026-07-23 10:14:51'),
(207, 56, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user pavan.', '::1', '2026-07-23 10:17:42'),
(208, 56, 'LOGIN', 'User pavan logged in successfully as student.', '::1', '2026-07-23 10:18:21'),
(209, 56, 'LOGOUT', 'User pavan logged out.', '::1', '2026-07-23 10:51:52'),
(210, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-23 10:52:37'),
(211, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-23 10:53:26'),
(212, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-23 10:55:05'),
(213, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-23 11:09:19'),
(214, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-23 11:10:53'),
(215, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-23 11:33:04'),
(216, NULL, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user encore.ex.', '::1', '2026-07-23 11:34:00'),
(217, NULL, 'REGISTER', 'New student registered: encore (STU-1057)', '::1', '2026-07-23 11:36:18'),
(218, NULL, 'EMAIL_VERIFIED', 'User encore verified email successfully via OTP.', '::1', '2026-07-23 11:37:13'),
(219, 58, 'REGISTER', 'New student registered: encore.exe (STU-1058)', '::1', '2026-07-23 11:39:30'),
(220, 58, 'EMAIL_VERIFIED', 'User encore.exe verified email successfully via OTP.', '::1', '2026-07-23 11:39:56'),
(221, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-23 11:40:10'),
(222, 58, 'ENROLL_COURSE', 'Enrolled in course: Full Stack Web Architecture Capstone', '::1', '2026-07-23 11:40:26'),
(223, 58, 'COURSE_COMPLETED', 'Completed course ID #20', '::1', '2026-07-23 11:40:30'),
(224, 58, 'COURSE_COMPLETED', 'Completed course ID #20', '::1', '2026-07-23 11:40:30'),
(225, 58, 'COURSE_COMPLETED', 'Completed course ID #20', '::1', '2026-07-23 11:40:30'),
(226, 58, 'COURSE_COMPLETED', 'Completed course ID #20', '::1', '2026-07-23 11:40:30'),
(227, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-23 13:23:30'),
(228, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-23 13:23:41'),
(229, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-23 13:24:11'),
(230, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-23 13:24:53'),
(231, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-23 13:25:57'),
(232, 1, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user admin.', '::1', '2026-07-23 13:26:23'),
(233, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-23 13:26:37'),
(234, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-23 13:29:11'),
(235, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-23 13:29:31'),
(236, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-23 13:29:40'),
(237, 59, 'REGISTER', 'New student registered: vaishnavi (STU-1059)', '::1', '2026-07-23 13:31:40'),
(238, 59, 'EMAIL_VERIFIED', 'User vaishnavi verified email successfully via OTP.', '::1', '2026-07-23 13:32:20'),
(239, 59, 'LOGIN', 'User vaishnavi logged in successfully as student.', '::1', '2026-07-23 13:32:46'),
(240, 59, 'LOGOUT', 'User vaishnavi logged out.', '::1', '2026-07-23 13:32:59'),
(241, NULL, 'REGISTER', 'New faculty registered: sumeshs (EMP-1060)', '::1', '2026-07-23 13:40:39'),
(242, NULL, 'EMAIL_VERIFIED', 'User sumeshs verified email successfully via OTP.', '::1', '2026-07-23 13:43:06'),
(243, NULL, 'LOGIN', 'User sumeshs logged in successfully as faculty.', '::1', '2026-07-23 13:43:33'),
(244, NULL, 'LOGOUT', 'User sumeshs logged out.', '::1', '2026-07-23 14:14:49'),
(245, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-23 14:15:10'),
(246, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-23 14:24:53'),
(247, NULL, 'LOGIN', 'User sumeshs logged in successfully as faculty.', '::1', '2026-07-23 14:25:13'),
(248, 1, 'ANNOUNCEMENT_CREATED', 'Created announcement #3: \'Server Maintenance Test 1784797837\' sent to 34 recipients.', '127.0.0.1', '2026-07-23 14:40:37'),
(249, 2, 'ANNOUNCEMENT_CREATED', 'Created announcement #4: \'Faculty Exam Review 1784797837\' sent to 28 recipients.', '127.0.0.1', '2026-07-23 14:40:37'),
(250, 2, 'ANNOUNCEMENT_UPDATED', 'Updated announcement #4: \'Faculty Exam Review 1784797837 (Updated)\'.', '127.0.0.1', '2026-07-23 14:40:37'),
(251, 1, 'ANNOUNCEMENT_UPDATED', 'Updated announcement #4: \'Faculty Exam Review 1784797837 (Admin Moderated)\'.', '127.0.0.1', '2026-07-23 14:40:37'),
(252, 1, 'ANNOUNCEMENT_DELETED', 'Deleted announcement #3.', '127.0.0.1', '2026-07-23 14:40:37'),
(253, 2, 'ANNOUNCEMENT_DELETED', 'Deleted announcement #4.', '127.0.0.1', '2026-07-23 14:40:37'),
(254, NULL, 'ANNOUNCEMENT_CREATED', 'Created announcement #5: \'Testing from Faculty Section\' sent to 28 recipients.', '::1', '2026-07-23 14:41:48'),
(255, NULL, 'LOGOUT', 'User sumeshs logged out.', '::1', '2026-07-23 14:41:58'),
(256, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-23 14:42:10'),
(257, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-23 15:04:45'),
(258, 63, 'REGISTER_FACULTY_APPLICATION', 'New faculty application submitted: khansir (FAC-1063) at Khan Global Studies', '::1', '2026-07-23 19:03:55'),
(259, 63, 'EMAIL_VERIFIED', 'User khansir verified email successfully via OTP.', '::1', '2026-07-23 19:06:13'),
(260, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-23 19:07:23'),
(261, 1, 'FACULTY_APPLICATION_APPROVED', 'Approved faculty application #11 (Khan Sir)', '::1', '2026-07-23 19:16:09'),
(262, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-23 19:38:50'),
(263, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-23 19:39:04'),
(264, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-23 21:15:52'),
(265, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-23 21:26:28'),
(266, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-23 21:26:40'),
(267, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-23 21:29:23'),
(268, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-23 21:30:12'),
(269, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-23 21:31:11');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
