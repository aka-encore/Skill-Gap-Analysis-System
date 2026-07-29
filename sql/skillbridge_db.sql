-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2026 at 06:24 AM
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
(269, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-23 21:31:11'),
(270, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-23 22:24:15'),
(271, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 0.0%', '::1', '2026-07-23 22:28:26'),
(272, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-24 09:32:23'),
(273, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-24 09:37:19'),
(274, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-24 09:38:48'),
(275, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 0.0%', '::1', '2026-07-24 09:41:08'),
(276, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-24 09:50:40'),
(277, 63, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for heroicff2727@gmail.com.', '::1', '2026-07-24 09:51:26'),
(278, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-24 09:55:04'),
(279, 63, 'ANNOUNCEMENT_CREATED', 'Created announcement #6: \'Testing from faculty\' sent to 28 recipients.', '::1', '2026-07-24 09:59:02'),
(280, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-24 10:00:29'),
(281, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-24 10:00:42'),
(282, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-24 10:05:05'),
(283, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-24 10:05:23'),
(284, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-24 10:34:51'),
(285, 47, 'LOGIN', 'User sumedh2 logged in successfully as student.', '::1', '2026-07-24 10:36:18'),
(286, 47, 'ENROLL_COURSE', 'Enrolled in course: Full Stack Web Architecture Capstone', '::1', '2026-07-24 10:55:35'),
(287, 47, 'LOGOUT', 'User sumedh2 logged out.', '::1', '2026-07-24 10:59:30'),
(288, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-24 11:07:31'),
(289, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-24 11:08:10'),
(290, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-24 11:34:42'),
(291, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-24 11:35:02'),
(292, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-24 13:24:32'),
(293, 58, 'ENROLL_COURSE', 'Enrolled in course: Asynchronous Node.js & Express', '::1', '2026-07-24 13:25:05'),
(294, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-24 13:47:34'),
(295, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-24 13:47:51'),
(296, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-24 13:50:46'),
(297, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-24 13:56:39'),
(298, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-24 14:06:54'),
(299, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-24 14:07:18'),
(300, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment JavaScript ES6 Asynchronous Programming (25 MCQs) with score 0.0%', '::1', '2026-07-24 14:29:12'),
(301, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-24 14:40:06'),
(302, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 0.0%', '::1', '2026-07-24 14:41:03'),
(303, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-24 14:41:39'),
(304, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-24 14:41:51'),
(305, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-24 14:43:14'),
(306, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-24 14:43:25'),
(307, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-25 13:34:45'),
(308, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-25 17:07:55'),
(309, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment MySQL Relational Schema & SQL Querying (25 MCQs) with score 100.0%', '::1', '2026-07-25 17:15:35'),
(310, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 8.0%', '::1', '2026-07-25 17:57:25'),
(311, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 0.0%', '::1', '2026-07-25 18:01:41'),
(312, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-25 18:01:49'),
(313, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-25 18:02:09'),
(314, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-25 18:04:07'),
(315, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-25 18:04:25'),
(316, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 0.0%', '::1', '2026-07-25 18:10:03'),
(317, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 0.0%', '::1', '2026-07-25 18:11:58'),
(318, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 0.0%', '::1', '2026-07-25 18:17:35'),
(319, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 0.0%', '::1', '2026-07-25 18:26:14'),
(320, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 4.0%', '::1', '2026-07-25 18:34:24'),
(321, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 0.0%', '::1', '2026-07-25 18:41:20'),
(322, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 4.0%', '::1', '2026-07-25 18:42:32'),
(323, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 40.0%', '::1', '2026-07-25 18:57:40'),
(324, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-25 19:58:06'),
(325, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment JavaScript ES6 Asynchronous Programming (25 MCQs) with score 12.0%', '::1', '2026-07-25 20:26:18'),
(326, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-26 11:41:26'),
(327, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 4.0%', '::1', '2026-07-26 11:52:39'),
(328, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment JavaScript ES6 Asynchronous Programming (25 MCQs) with score 0.0%', '::1', '2026-07-26 12:22:35'),
(329, 58, 'ENROLL_COURSE', 'Enrolled in course: Responsive Design with Bootstrap 5', '::1', '2026-07-26 12:59:57'),
(330, 58, 'ENROLL_COURSE', 'Enrolled in course: Mastering Pure PHP 8 Development', '::1', '2026-07-26 13:00:06'),
(331, 58, 'ENROLL_COURSE', 'Enrolled in course: Practical Cyber Security Defenses', '::1', '2026-07-26 13:20:43'),
(332, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-26 13:48:25'),
(333, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-26 13:48:42'),
(334, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-26 13:57:19'),
(335, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-26 13:57:38'),
(336, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-26 14:01:41'),
(337, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-26 14:01:57'),
(338, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-26 14:10:30'),
(339, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-26 14:10:48'),
(340, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-26 18:18:53'),
(341, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-26 18:37:28'),
(342, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-26 18:37:48'),
(343, 63, 'ASSESSMENT_CREATED', 'Created assessment TEsting (ID: 11)', '::1', '2026-07-26 18:53:19'),
(344, 63, 'ANNOUNCEMENT_CREATED', 'Created announcement #7: \'testing\' sent to 28 recipients.', '::1', '2026-07-26 18:55:25'),
(345, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-26 19:38:48'),
(346, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-26 19:39:15'),
(347, 1, 'BULK_IMPORT_STUDENTS', 'Bulk imported 2 student accounts', '127.0.0.1', '2026-07-26 21:26:37'),
(348, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-26 21:28:51'),
(349, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-26 21:29:08'),
(350, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-26 21:39:37'),
(351, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-27 08:47:17'),
(352, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-27 09:09:02'),
(353, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-27 09:09:20'),
(354, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-27 09:10:49'),
(355, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-27 09:11:02'),
(356, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-27 09:27:49'),
(357, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-27 09:28:07'),
(358, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment JavaScript ES6 Asynchronous Programming (25 MCQs) with score 0.0%', '::1', '2026-07-27 09:30:02'),
(359, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-27 09:31:07'),
(360, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 4.0%', '::1', '2026-07-27 09:52:10'),
(361, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-27 09:52:14'),
(362, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-27 09:52:26'),
(363, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-27 09:53:14'),
(364, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-27 09:53:26');

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
(1, 1, 'Skill Bridge', 'Team', 'default-avatar.png', 'IT & Operations', '2026-07-20 20:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `created_by_user_id` int(11) NOT NULL,
  `created_by_name` varchar(150) NOT NULL,
  `created_by_role` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `audience` varchar(50) NOT NULL DEFAULT 'all',
  `priority` varchar(20) NOT NULL DEFAULT 'normal',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `link` varchar(255) DEFAULT '#',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `created_by_user_id`, `created_by_name`, `created_by_role`, `title`, `message`, `audience`, `priority`, `status`, `link`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', 'admin', 'Testing Features', 'Ignore the announcement', 'all', 'normal', 'active', '#', '2026-07-22 22:39:51', '2026-07-23 14:36:55'),
(2, 1, 'admin', 'admin', 'Testing From Admin Section', 'Ignore this.', 'all', 'normal', 'active', '#', '2026-07-23 14:24:42', '2026-07-23 14:36:55'),
(5, 60, 'sumeshs', 'faculty', 'Testing from Faculty Section', 'ignore', 'student', 'normal', 'active', '#', '2026-07-23 14:41:48', '2026-07-23 14:41:48'),
(6, 63, 'khansir', 'faculty', 'Testing from faculty', 'ignore', 'student', 'normal', 'active', '#', '2026-07-24 09:59:02', '2026-07-24 09:59:02'),
(7, 63, 'khansir', 'faculty', 'testing', 'ignore..', 'student', 'normal', 'active', '#', '2026-07-26 18:55:25', '2026-07-26 18:55:25');

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
-- Table structure for table `assessment_proctoring_logs`
--

CREATE TABLE `assessment_proctoring_logs` (
  `id` int(11) NOT NULL,
  `result_id` int(11) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_proctoring_logs`
--

INSERT INTO `assessment_proctoring_logs` (`id`, `result_id`, `event_type`, `description`, `created_at`) VALUES
(1, 48, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 17:52:59'),
(2, 48, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 17:52:59'),
(3, 48, 'Tab Switch', 'Student switched tabs or minimized the browser window.', '2026-07-25 17:52:59'),
(4, 48, 'Mobile Phone Detected', 'Mobile phone detected in webcam view.', '2026-07-25 17:52:59'),
(5, 48, 'Face Missing', 'No face visible in webcam frame.', '2026-07-25 17:52:59'),
(6, 48, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-25 17:52:59'),
(7, 49, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 17:56:15'),
(8, 49, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 17:56:15'),
(9, 49, 'Camera Disabled', 'Webcam stream disconnected.', '2026-07-25 17:56:51'),
(10, 49, 'Camera Reconnected', 'Webcam stream reconnected.', '2026-07-25 17:56:52'),
(11, 49, 'Camera Disabled', 'Webcam stream disconnected.', '2026-07-25 17:57:03'),
(12, 49, 'Camera Reconnected', 'Webcam stream reconnected.', '2026-07-25 17:57:03'),
(13, 49, 'Camera Disabled', 'Webcam stream disconnected.', '2026-07-25 17:57:21'),
(14, 49, 'Auto Submission', 'Assessment submitted automatically due to exceeding maximum warnings limit.', '2026-07-25 17:57:25'),
(15, 50, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 17:58:26'),
(16, 50, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 17:58:26'),
(17, 50, 'Mobile Phone Detected', 'Mobile phone detected in webcam view.', '2026-07-25 17:59:43'),
(18, 50, 'Mobile Phone Removed', 'Mobile phone removed from camera frame.', '2026-07-25 17:59:44'),
(19, 50, 'Face Missing', 'No face visible in webcam frame.', '2026-07-25 18:00:41'),
(20, 50, 'Face Re-calibrated', 'One face presence restored.', '2026-07-25 18:00:44'),
(21, 50, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-25 18:01:33'),
(22, 50, 'Auto Submission', 'Assessment submitted automatically due to exceeding maximum warnings limit.', '2026-07-25 18:01:41'),
(23, 51, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 18:09:17'),
(24, 51, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 18:09:17'),
(25, 51, 'Full-screen Exit', 'Student exited full-screen mode.', '2026-07-25 18:09:28'),
(26, 51, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-25 18:10:03'),
(27, 52, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 18:11:37'),
(28, 52, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 18:11:37'),
(29, 52, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-25 18:11:58'),
(30, 53, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 18:16:44'),
(31, 53, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 18:16:44'),
(32, 53, 'Full-screen Exit', 'Student exited full-screen mode.', '2026-07-25 18:17:01'),
(33, 53, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-25 18:17:35'),
(34, 54, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 18:25:32'),
(35, 54, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 18:25:32'),
(36, 54, 'Full-screen Exit', 'Student exited full-screen mode.', '2026-07-25 18:25:53'),
(37, 54, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-25 18:26:14'),
(38, 55, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 18:33:24'),
(39, 55, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 18:33:24'),
(40, 55, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-25 18:34:24'),
(41, 56, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 18:40:26'),
(42, 56, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 18:40:26'),
(43, 56, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-25 18:41:20'),
(44, 57, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 18:41:52'),
(45, 57, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 18:41:52'),
(46, 57, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-25 18:42:12'),
(47, 57, 'Full-screen Exit', 'Student exited full-screen mode.', '2026-07-25 18:42:14'),
(48, 57, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-25 18:42:32'),
(49, 58, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 18:56:04'),
(50, 58, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 18:56:04'),
(51, 58, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-25 18:57:40'),
(52, 59, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-25 20:25:03'),
(53, 59, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-25 20:25:03'),
(54, 59, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-25 20:26:18'),
(55, 60, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-26 11:50:31'),
(56, 60, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-26 11:50:31'),
(57, 60, 'Full-screen Exit', 'Student exited full-screen mode.', '2026-07-26 11:51:09'),
(58, 60, 'Face Missing', 'No face visible in webcam frame.', '2026-07-26 11:51:34'),
(59, 60, 'Face Re-calibrated', 'One face presence restored.', '2026-07-26 11:51:39'),
(60, 60, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-26 11:52:39'),
(61, 61, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-26 12:21:52'),
(62, 61, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-26 12:21:52'),
(63, 61, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-26 12:22:35'),
(64, 62, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-27 09:28:32'),
(65, 62, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-27 09:28:32'),
(66, 62, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-27 09:28:47'),
(67, 62, 'Face Re-calibrated', 'One face presence restored.', '2026-07-27 09:28:49'),
(68, 62, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-27 09:29:10'),
(69, 62, 'Face Re-calibrated', 'One face presence restored.', '2026-07-27 09:29:12'),
(70, 62, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-27 09:29:54'),
(71, 62, 'Face Re-calibrated', 'One face presence restored.', '2026-07-27 09:29:57'),
(72, 62, 'Auto Submission', 'Assessment submitted automatically due to exceeding maximum warnings limit.', '2026-07-27 09:30:02'),
(73, 63, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-27 09:30:28'),
(74, 63, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-27 09:30:28'),
(75, 63, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-27 09:31:01'),
(76, 63, 'Face Re-calibrated', 'One face presence restored.', '2026-07-27 09:31:04'),
(77, 63, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-27 09:31:07'),
(78, 64, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-27 09:50:12'),
(79, 64, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-27 09:50:12'),
(80, 64, 'Face Missing', 'No face visible in webcam frame.', '2026-07-27 09:50:44'),
(81, 64, 'Face Re-calibrated', 'One face presence restored.', '2026-07-27 09:50:48'),
(82, 64, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-27 09:51:45'),
(83, 64, 'Face Re-calibrated', 'One face presence restored.', '2026-07-27 09:51:47'),
(84, 64, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-27 09:52:02'),
(85, 64, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-27 09:52:02'),
(86, 64, 'Face Re-calibrated', 'One face presence restored.', '2026-07-27 09:52:06'),
(87, 64, 'Auto Submission', 'Assessment submitted automatically due to exceeding maximum warnings limit.', '2026-07-27 09:52:10');

-- --------------------------------------------------------

--
-- Table structure for table `assessment_proctoring_summaries`
--

CREATE TABLE `assessment_proctoring_summaries` (
  `id` int(11) NOT NULL,
  `result_id` int(11) NOT NULL,
  `total_violations` int(11) DEFAULT 0,
  `phone_violations` int(11) DEFAULT 0,
  `face_missing_violations` int(11) DEFAULT 0,
  `multiple_face_violations` int(11) DEFAULT 0,
  `tab_switch_violations` int(11) DEFAULT 0,
  `focus_loss_violations` int(11) DEFAULT 0,
  `camera_disconnect_violations` int(11) DEFAULT 0,
  `risk_level` varchar(20) DEFAULT 'Low Risk',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_proctoring_summaries`
--

INSERT INTO `assessment_proctoring_summaries` (`id`, `result_id`, `total_violations`, `phone_violations`, `face_missing_violations`, `multiple_face_violations`, `tab_switch_violations`, `focus_loss_violations`, `camera_disconnect_violations`, `risk_level`, `created_at`) VALUES
(1, 48, 3, 1, 1, 0, 1, 0, 0, 'High Risk', '2026-07-25 17:52:59'),
(2, 49, 3, 0, 0, 0, 0, 0, 3, 'High Risk', '2026-07-25 17:57:25'),
(3, 50, 3, 1, 1, 1, 0, 0, 0, 'High Risk', '2026-07-25 18:01:41'),
(4, 51, 1, 0, 0, 0, 1, 0, 0, 'Medium Risk', '2026-07-25 18:10:03'),
(5, 52, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-25 18:11:58'),
(6, 53, 1, 0, 0, 0, 1, 0, 0, 'Medium Risk', '2026-07-25 18:17:35'),
(7, 54, 1, 0, 0, 0, 1, 0, 0, 'Medium Risk', '2026-07-25 18:26:14'),
(8, 55, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-25 18:34:24'),
(9, 56, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-25 18:41:20'),
(10, 57, 2, 0, 0, 0, 1, 1, 0, 'Medium Risk', '2026-07-25 18:42:32'),
(11, 58, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-25 18:57:40'),
(12, 59, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-25 20:26:18'),
(13, 60, 2, 0, 1, 0, 1, 0, 0, 'Medium Risk', '2026-07-26 11:52:39'),
(14, 61, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-26 12:22:35'),
(15, 62, 3, 0, 0, 3, 0, 0, 0, 'High Risk', '2026-07-27 09:30:02'),
(16, 63, 1, 0, 0, 1, 0, 0, 0, 'High Risk', '2026-07-27 09:31:07'),
(17, 64, 4, 0, 1, 2, 0, 1, 0, 'High Risk', '2026-07-27 09:52:10');

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
(7, 3, 1, 10, 8, 8.00, 80.00, 'pass', 600, '2026-07-13 20:01:07'),
(8, 3, 2, 10, 9, 9.00, 90.00, 'pass', 580, '2026-07-16 20:01:07'),
(9, 3, 8, 10, 5, 5.00, 50.00, 'fail', 1100, '2026-07-19 20:01:07'),
(10, 4, 4, 10, 10, 10.00, 100.00, 'pass', 350, '2026-07-15 20:01:07'),
(11, 4, 5, 10, 9, 9.00, 90.00, 'pass', 390, '2026-07-17 20:01:07'),
(12, 5, 1, 10, 5, 5.00, 50.00, 'fail', 800, '2026-07-18 20:01:07'),
(13, 5, 7, 10, 8, 8.00, 80.00, 'pass', 720, '2026-07-19 20:01:07'),
(38, 41, 4, 25, 3, 3.00, 12.00, 'fail', 0, '2026-07-22 13:44:53'),
(41, 50, 4, 25, 0, 0.00, 0.00, 'fail', 0, '2026-07-23 22:28:26'),
(42, 50, 4, 25, 0, 0.00, 0.00, 'fail', 48, '2026-07-24 09:41:08'),
(43, 50, 1, 25, 0, 0.00, 0.00, 'fail', 5, '2026-07-24 11:08:10'),
(44, 50, 3, 25, 0, 0.00, 0.00, 'fail', 20, '2026-07-24 14:29:12'),
(45, 50, 1, 25, 0, 0.00, 0.00, 'fail', 7, '2026-07-24 14:40:06'),
(46, 50, 4, 25, 0, 0.00, 0.00, 'fail', 7, '2026-07-24 14:41:03'),
(47, 50, 2, 25, 25, 25.00, 100.00, 'pass', 209, '2026-07-25 17:15:35'),
(48, 3, 1, 25, 15, 60.00, 60.00, 'pass', 300, '2026-07-25 17:52:59'),
(49, 50, 4, 25, 2, 2.00, 8.00, 'fail', 0, '2026-07-25 17:57:25'),
(50, 50, 4, 25, 0, 0.00, 0.00, 'fail', 0, '2026-07-25 18:01:41'),
(51, 50, 4, 25, 0, 0.00, 0.00, 'fail', 46, '2026-07-25 18:10:03'),
(52, 50, 4, 25, 0, 0.00, 0.00, 'fail', 20, '2026-07-25 18:11:58'),
(53, 50, 4, 25, 0, 0.00, 0.00, 'fail', 50, '2026-07-25 18:17:35'),
(54, 50, 4, 25, 0, 0.00, 0.00, 'fail', 42, '2026-07-25 18:26:14'),
(55, 50, 1, 25, 1, 1.00, 4.00, 'fail', 60, '2026-07-25 18:34:24'),
(56, 50, 4, 25, 0, 0.00, 0.00, 'fail', 53, '2026-07-25 18:41:20'),
(57, 50, 1, 25, 1, 1.00, 4.00, 'fail', 39, '2026-07-25 18:42:32'),
(58, 50, 4, 25, 10, 10.00, 40.00, 'fail', 96, '2026-07-25 18:57:40'),
(59, 50, 3, 25, 3, 3.00, 12.00, 'fail', 74, '2026-07-25 20:26:18'),
(60, 50, 4, 25, 1, 1.00, 4.00, 'fail', 127, '2026-07-26 11:52:39'),
(61, 50, 3, 25, 0, 0.00, 0.00, 'fail', 43, '2026-07-26 12:22:35'),
(62, 50, 3, 25, 0, 0.00, 0.00, 'fail', 0, '2026-07-27 09:30:02'),
(63, 50, 1, 25, 0, 0.00, 0.00, 'fail', 38, '2026-07-27 09:31:07'),
(64, 50, 4, 25, 1, 1.00, 4.00, 'fail', 115, '2026-07-27 09:52:10');

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
  `college_name` varchar(200) NOT NULL DEFAULT 'SkillBridge University',
  `mobile_number` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `department` varchar(100) NOT NULL DEFAULT 'Computer Science',
  `designation` varchar(100) NOT NULL DEFAULT 'Assistant Professor',
  `experience_years` int(11) DEFAULT 0,
  `id_card_file` varchar(255) DEFAULT NULL,
  `appointment_letter_file` varchar(255) DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approval_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `user_id`, `employee_code`, `first_name`, `last_name`, `college_name`, `mobile_number`, `avatar`, `department`, `designation`, `experience_years`, `id_card_file`, `appointment_letter_file`, `approval_status`, `approval_date`, `approved_by`, `rejection_reason`, `created_at`) VALUES
(1, 2, 'FAC-001', 'Alan', 'Turing', 'SkillBridge University', NULL, 'default-avatar.png', 'Computer Science', 'Professor & HOD', 0, NULL, NULL, 'approved', NULL, NULL, NULL, '2026-07-20 20:01:07'),
(2, 3, 'FAC-002', 'Grace', 'Hopper', 'SkillBridge University', NULL, 'default-avatar.png', 'Software Engineering', 'Associate Professor', 0, NULL, NULL, 'approved', NULL, NULL, NULL, '2026-07-20 20:01:07'),
(3, 4, 'FAC-003', 'Donald', 'Knuth', 'SkillBridge University', NULL, 'default-avatar.png', 'Computer Science', 'Senior Professor', 0, NULL, NULL, 'approved', NULL, NULL, NULL, '2026-07-20 20:01:07'),
(4, 5, 'FAC-004', 'Ada', 'Lovelace', 'SkillBridge University', NULL, 'default-avatar.png', 'Information Technology', 'Assistant Professor', 0, NULL, NULL, 'approved', NULL, NULL, NULL, '2026-07-20 20:01:07'),
(5, 6, 'FAC-005', 'Linus', 'Torvalds', 'SkillBridge University', NULL, 'default-avatar.png', 'Systems Engineering', 'Associate Professor', 0, NULL, NULL, 'approved', NULL, NULL, NULL, '2026-07-20 20:01:07'),
(9, 61, 'FAC-TEST-01', 'Vikram', 'Sarabhai', 'IISc Bangalore', '+91 9876543210', 'default-avatar.png', 'Computer Science', 'Senior Professor', 12, NULL, NULL, 'approved', '2026-07-23 15:03:06', 1, NULL, '2026-07-23 15:03:06'),
(10, 62, 'FAC-TEST-02', 'Reject', 'Applicant', 'Test College', NULL, 'default-avatar.png', 'Information Technology', 'Assistant Professor', 0, NULL, NULL, 'rejected', NULL, NULL, 'Incomplete verification documents uploaded.', '2026-07-23 15:03:15'),
(11, 63, 'FAC-1063', 'Khan', 'Sir', 'Khan Global Studies', '', 'avatar_user_63_1785054502.jpg', 'Computer Science', 'Assistant Professor', 0, NULL, NULL, 'approved', '2026-07-23 19:16:04', 1, NULL, '2026-07-23 19:03:55');

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
(5, 36, 'student', 'General Feedback', 5, 'hii', 'pending', '2026-07-21 22:04:23'),
(6, 50, 'student', 'General Feedback', 5, 'good', 'pending', '2026-07-22 08:59:27'),
(7, 58, 'student', 'General Feedback', 5, 'Testing From Student section', 'pending', '2026-07-23 17:14:47'),
(8, 63, 'faculty', 'General Feedback', 5, 'hii', 'pending', '2026-07-26 13:25:53');

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
-- CS-101: Mastering Pure PHP 8 Development
(1, 1, 'Module 1: Introduction & Fundamentals', '1. PHP 8 Introduction & Environment Setup', 'Install PHP 8, configure your development environment, and write your first PHP script.', 'https://www.youtube.com/embed/OK_JCtrrv-c', 15, 1, '2026-07-22 02:33:28'),
(2, 1, 'Module 2: Practical Concepts & Implementation', '2. PHP Functions, Arrays & OOP Basics', 'Master PHP functions, arrays, loops, and an introduction to object-oriented programming concepts.', 'https://www.youtube.com/embed/pWG7ajC_OVo', 25, 2, '2026-07-22 02:33:28'),
(3, 1, 'Module 3: Advanced Optimization & Project', '3. PHP & MySQL — Building a Full Web App', 'Build a complete PHP 8 CRUD application connected to MySQL with sessions, forms, and validation.', 'https://www.youtube.com/embed/3DMMPA3uxBo', 30, 3, '2026-07-22 02:33:28'),
-- CS-102: Relational Database Masterclass
(4, 2, 'Module 1: Introduction & Fundamentals', '1. SQL & MySQL Fundamentals — Queries & Tables', 'Learn SQL syntax, how to create databases, tables, and run basic SELECT queries in MySQL.', 'https://www.youtube.com/embed/7S_tz1z_5bA', 15, 1, '2026-07-22 02:33:28'),
(5, 2, 'Module 2: Practical Concepts & Implementation', '2. Joins, Subqueries & Aggregate Functions', 'Master INNER JOIN, LEFT JOIN, GROUP BY, HAVING, and complex subqueries in MySQL.', 'https://www.youtube.com/embed/p3qvj9hO_Bo', 25, 2, '2026-07-22 02:33:28'),
(6, 2, 'Module 3: Advanced Optimization & Project', '3. Database Design, Indexes & Stored Procedures', 'Design normalized relational schemas, use indexes for performance, and write stored procedures and triggers.', 'https://www.youtube.com/embed/ER8oKX5myE0', 30, 3, '2026-07-22 02:33:28'),
-- CS-103: Modern JavaScript ES6+ Mastery
(7, 3, 'Module 1: Introduction & Fundamentals', '1. Modern JavaScript ES6 — Let, Const, Arrow Functions & Destructuring', 'Understand ES6+ fundamentals: let/const, template literals, arrow functions, and destructuring.', 'https://www.youtube.com/embed/NCwa_xi0Uuc', 15, 1, '2026-07-22 02:33:28'),
(8, 3, 'Module 2: Practical Concepts & Implementation', '2. Promises, Async/Await & the Fetch API', 'Handle asynchronous JavaScript with Promises, async/await, and fetch data from REST APIs.', 'https://www.youtube.com/embed/DHvZLI7Db8E', 25, 2, '2026-07-22 02:33:28'),
(9, 3, 'Module 3: Advanced Optimization & Project', '3. JavaScript Modules, Classes & Modern Tooling', 'Work with ES6 modules, classes, iterators, generators, and modern bundling with Webpack/Vite.', 'https://www.youtube.com/embed/lI1ae4REbFM', 30, 3, '2026-07-22 02:33:28'),
-- CS-104: Responsive Design with Bootstrap 5
(10, 4, 'Module 1: Introduction & Fundamentals', '1. Bootstrap 5 — Grid System & Utility Classes', 'Set up Bootstrap 5, learn the 12-column grid system, breakpoints, and core utility classes.', 'https://www.youtube.com/embed/4sosXZsdy-s', 15, 1, '2026-07-22 02:33:28'),
(11, 4, 'Module 2: Practical Concepts & Implementation', '2. Bootstrap 5 Components — Navbar, Cards & Forms', 'Build responsive navbars, cards, modals, forms, and interactive components with Bootstrap 5.', 'https://www.youtube.com/embed/rQryOSyfXmI', 25, 2, '2026-07-22 02:33:28'),
(12, 4, 'Module 3: Advanced Optimization & Project', '3. Bootstrap 5 — Building a Complete Responsive Website', 'Apply Bootstrap 5 to build a full responsive landing page with custom CSS overrides.', 'https://www.youtube.com/embed/Jyvffr3aCp0', 30, 3, '2026-07-22 02:33:28'),
-- CS-105: Web Security Essentials & OWASP
(13, 5, 'Module 1: Introduction & Fundamentals', '1. OWASP Top 10 — Understanding Web Vulnerabilities', 'Explore the OWASP Top 10 most critical web application security risks and how attackers exploit them.', 'https://www.youtube.com/embed/t0IT914i3TU', 15, 1, '2026-07-22 02:33:28'),
(14, 5, 'Module 2: Practical Concepts & Implementation', '2. SQL Injection, XSS & CSRF Attacks & Defenses', 'Deep dive into SQL Injection, Cross-Site Scripting (XSS), and CSRF with hands-on defense techniques.', 'https://www.youtube.com/embed/WtHnT73NaaQ', 25, 2, '2026-07-22 02:33:28'),
(15, 5, 'Module 3: Advanced Optimization & Project', '3. HTTPS, Authentication Security & Security Headers', 'Implement HTTPS, secure password hashing, JWT authentication, and critical HTTP security headers.', 'https://www.youtube.com/embed/F5KJVuii0Yw', 30, 3, '2026-07-22 02:33:28'),
-- CS-106: RESTful API Engineering in PHP
(16, 6, 'Module 1: Introduction & Fundamentals', '1. REST API Fundamentals & HTTP Methods in PHP', 'Understand REST principles, HTTP verbs (GET, POST, PUT, DELETE), and build your first PHP API endpoint.', 'https://www.youtube.com/embed/OEWXbpUMODk', 15, 1, '2026-07-22 02:33:28'),
(17, 6, 'Module 2: Practical Concepts & Implementation', '2. PHP REST API — CRUD Operations & JSON Responses', 'Implement full CRUD operations in a PHP REST API with proper JSON responses and status codes.', 'https://www.youtube.com/embed/eyvRc9XSqMw', 25, 2, '2026-07-22 02:33:28'),
(18, 6, 'Module 3: Advanced Optimization & Project', '3. PHP REST API — Authentication, JWT & Security', 'Secure your PHP REST API with JWT tokens, API keys, rate limiting, and CORS configuration.', 'https://www.youtube.com/embed/T-Pum2TraX4', 30, 3, '2026-07-22 02:33:28'),
-- CS-107: Data Structures & Algorithms
(19, 7, 'Module 1: Introduction & Fundamentals', '1. Arrays, Linked Lists & Big O Notation', 'Master arrays, linked lists, stacks, queues, and understand time/space complexity with Big O notation.', 'https://www.youtube.com/embed/BBpAmxU_NQo', 15, 1, '2026-07-22 02:33:28'),
(20, 7, 'Module 2: Practical Concepts & Implementation', '2. Trees, Graphs & Sorting Algorithms', 'Explore binary trees, BSTs, graphs, BFS/DFS traversal, and implement sorting algorithms from scratch.', 'https://www.youtube.com/embed/pkYVOmU3MgA', 25, 2, '2026-07-22 02:33:28'),
(21, 7, 'Module 3: Advanced Optimization & Project', '3. Dynamic Programming & Algorithm Design Patterns', 'Solve complex problems using dynamic programming, memoization, greedy algorithms, and divide-and-conquer.', 'https://www.youtube.com/embed/oBt53YbR9Kk', 30, 3, '2026-07-22 02:33:28'),
-- CS-108: Object-Oriented Software Architecture
(22, 8, 'Module 1: Introduction & Fundamentals', '1. OOP Principles — Classes, Encapsulation & Inheritance', 'Master the four OOP pillars: encapsulation, abstraction, inheritance, and polymorphism with real examples.', 'https://www.youtube.com/embed/pTB0EiLXUC8', 15, 1, '2026-07-22 02:33:28'),
(23, 8, 'Module 2: Practical Concepts & Implementation', '2. SOLID Principles & Clean Architecture', 'Apply SOLID design principles to write maintainable, extensible, and testable object-oriented code.', 'https://www.youtube.com/embed/_jDNAkmINF0', 25, 2, '2026-07-22 02:33:28'),
(24, 8, 'Module 3: Advanced Optimization & Project', '3. Design Patterns — Creational, Structural & Behavioral', 'Implement the most important GoF design patterns including Singleton, Factory, Observer, and Strategy.', 'https://www.youtube.com/embed/tv-_1er1mWI', 30, 3, '2026-07-22 02:33:28'),
-- CS-109: Git & GitHub Collaboration Workflow
(25, 9, 'Module 1: Introduction & Fundamentals', '1. Git Fundamentals — Init, Commit, Branch & Merge', 'Install Git, initialize repositories, make commits, create branches, and perform merges.', 'https://www.youtube.com/embed/RGOj5yH7evk', 15, 1, '2026-07-22 02:33:28'),
(26, 9, 'Module 2: Practical Concepts & Implementation', '2. GitHub — Remote Repos, Pull Requests & Code Reviews', 'Push to GitHub, work with remote repositories, fork projects, and collaborate via Pull Requests.', 'https://www.youtube.com/embed/SWYqp7iY_Tc', 25, 2, '2026-07-22 02:33:28'),
(27, 9, 'Module 3: Advanced Optimization & Project', '3. Git Workflows — Rebasing, Cherry-pick & CI/CD Integration', 'Master advanced Git workflows: rebase, cherry-pick, Git Flow, and integrate with GitHub Actions CI/CD.', 'https://www.youtube.com/embed/Uszj_k0DGsg', 30, 3, '2026-07-22 02:33:28'),
-- CS-110: UI/UX Fundamentals
(28, 10, 'Module 1: Introduction & Fundamentals', '1. UX Design Principles — Research, Wireframing & Prototyping', 'Learn UX research methods, create wireframes, and build interactive prototypes using Figma.', 'https://www.youtube.com/embed/c9Wg6RyOxjU', 15, 1, '2026-07-22 02:33:28'),
(29, 10, 'Module 2: Practical Concepts & Implementation', '2. Figma Masterclass — Components, Auto Layout & Design Systems', 'Build reusable Figma components, master Auto Layout, and create a consistent design system.', 'https://www.youtube.com/embed/FTFaQWZBqQ8', 25, 2, '2026-07-22 02:33:28'),
(30, 10, 'Module 3: Advanced Optimization & Project', '3. UI Design — Color Theory, Typography & Accessibility', 'Apply color theory, typography best practices, and WCAG accessibility guidelines to UI design.', 'https://www.youtube.com/embed/yNDgFK2Jj1E', 30, 3, '2026-07-22 02:33:28'),
-- CS-111: Python for Software Automation
(31, 11, 'Module 1: Introduction & Fundamentals', '1. Python Fundamentals — Variables, Loops & Functions', 'Learn Python syntax, data types, control flow, functions, and working with modules.', 'https://www.youtube.com/embed/rfscVS0vtbw', 15, 1, '2026-07-22 02:33:28'),
(32, 11, 'Module 2: Practical Concepts & Implementation', '2. Python — File Automation, OS Module & Scripting', 'Automate file system tasks, work with the os module, write scripts, and use regular expressions.', 'https://www.youtube.com/embed/s3lrgez5pls', 25, 2, '2026-07-22 02:33:28'),
(33, 11, 'Module 3: Advanced Optimization & Project', '3. Python — Web Scraping, APIs & Task Automation', 'Use Requests, BeautifulSoup, and schedule libraries to build powerful automation pipelines.', 'https://www.youtube.com/embed/ycdptosWgFc', 30, 3, '2026-07-22 02:33:28'),
-- CS-112: Docker Container Essentials
(34, 12, 'Module 1: Introduction & Fundamentals', '1. Docker Introduction — Containers, Images & Dockerfile', 'Understand containerization, install Docker, build images with Dockerfile, and run containers.', 'https://www.youtube.com/embed/pg19Z8LL06w', 15, 1, '2026-07-22 02:33:28'),
(35, 12, 'Module 2: Practical Concepts & Implementation', '2. Docker Compose — Multi-Container Applications', 'Define and run multi-container applications with Docker Compose, volumes, and networking.', 'https://www.youtube.com/embed/DM65_JyGxCo', 25, 2, '2026-07-22 02:33:28'),
(36, 12, 'Module 3: Advanced Optimization & Project', '3. Docker in Production — Registry, CI/CD & Best Practices', 'Push images to Docker Hub, integrate with CI/CD pipelines, and apply production-grade best practices.', 'https://www.youtube.com/embed/3c-iBn73dDE', 30, 3, '2026-07-22 02:33:28'),
-- CS-113: React Frontend Foundations
(37, 13, 'Module 1: Introduction & Fundamentals', '1. React Fundamentals — Components, Props & JSX', 'Set up a React project with Vite, create components, pass props, and understand JSX syntax.', 'https://www.youtube.com/embed/RVFAyFWO4go', 15, 1, '2026-07-22 02:33:28'),
(38, 13, 'Module 2: Practical Concepts & Implementation', '2. React Hooks — useState, useEffect & Custom Hooks', 'Master React Hooks: useState for state management, useEffect for side effects, and build custom hooks.', 'https://www.youtube.com/embed/O6P86uwfdR0', 25, 2, '2026-07-22 02:33:28'),
(39, 13, 'Module 3: Advanced Optimization & Project', '3. React Router, Context API & Building a Full App', 'Implement React Router v6, global state with Context API, and build a complete React application.', 'https://www.youtube.com/embed/w7ejDZ8SWv8', 30, 3, '2026-07-22 02:33:28'),
-- CS-114: Cloud Infrastructure Fundamentals
(40, 14, 'Module 1: Introduction & Fundamentals', '1. Cloud Computing & AWS Core Services Overview', 'Understand cloud computing models (IaaS, PaaS, SaaS), AWS global infrastructure, and core services.', 'https://www.youtube.com/embed/NhDYbskXRgc', 15, 1, '2026-07-22 02:33:28'),
(41, 14, 'Module 2: Practical Concepts & Implementation', '2. AWS EC2, S3, RDS & IAM Hands-On', 'Launch EC2 instances, store files in S3, configure RDS databases, and set up IAM roles and policies.', 'https://www.youtube.com/embed/ulprqHHWlng', 25, 2, '2026-07-22 02:33:28'),
(42, 14, 'Module 3: Advanced Optimization & Project', '3. AWS Deployment — Elastic Beanstalk, Lambda & CloudFormation', 'Deploy apps with Elastic Beanstalk, run serverless functions with Lambda, and use CloudFormation IaC.', 'https://www.youtube.com/embed/SOTamWNgDKc', 30, 3, '2026-07-22 02:33:28'),
-- CS-115: Automated Software Testing & TDD
(43, 15, 'Module 1: Introduction & Fundamentals', '1. Software Testing Fundamentals — Unit, Integration & E2E', 'Understand different testing types, the testing pyramid, and write your first unit tests.', 'https://www.youtube.com/embed/r9HdJ8P6GQI', 15, 1, '2026-07-22 02:33:28'),
(44, 15, 'Module 2: Practical Concepts & Implementation', '2. Test-Driven Development (TDD) — Red, Green, Refactor', 'Practice the TDD cycle: write failing tests first, make them pass, then refactor with confidence.', 'https://www.youtube.com/embed/Jv2uxzhPFl4', 25, 2, '2026-07-22 02:33:28'),
(45, 15, 'Module 3: Advanced Optimization & Project', '3. PHPUnit & Jest — Testing in CI/CD Pipelines', 'Write tests with PHPUnit for PHP and Jest for JavaScript, then integrate testing into CI/CD pipelines.', 'https://www.youtube.com/embed/ajiAl5UNsZQ', 30, 3, '2026-07-22 02:33:28'),
-- CS-116: Asynchronous Node.js & Express
(46, 16, 'Module 1: Introduction & Fundamentals', '1. Node.js Fundamentals — Event Loop, Modules & npm', 'Understand the Node.js event loop, CommonJS modules, npm ecosystem, and working with the file system.', 'https://www.youtube.com/embed/fBNz5xF-Kx4', 15, 1, '2026-07-22 02:33:28'),
(47, 16, 'Module 2: Practical Concepts & Implementation', '2. Express.js — Routing, Middleware & REST APIs', 'Build a REST API with Express.js, use middleware, handle routing, and work with request/response objects.', 'https://www.youtube.com/embed/L72fhGm1tfE', 25, 2, '2026-07-22 02:33:28'),
(48, 16, 'Module 3: Advanced Optimization & Project', '3. Node.js — Authentication, MongoDB & Deployment', 'Add JWT authentication, connect to MongoDB with Mongoose, and deploy your Node.js app to production.', 'https://www.youtube.com/embed/ENrzD9HAZK4', 30, 3, '2026-07-22 02:33:28'),
-- CS-117: Linux Command Line Administration
(49, 17, 'Module 1: Introduction & Fundamentals', '1. Linux Command Line Basics — Navigation, Files & Permissions', 'Navigate the Linux file system, manage files and directories, and understand user permissions and chmod.', 'https://www.youtube.com/embed/ZtqBQ68cfJc', 15, 1, '2026-07-22 02:33:28'),
(50, 17, 'Module 2: Practical Concepts & Implementation', '2. Linux Shell Scripting — Bash Automation & Cron Jobs', 'Write Bash shell scripts, use variables, loops, and conditionals to automate repetitive system tasks.', 'https://www.youtube.com/embed/tK9Oc6AEnR4', 25, 2, '2026-07-22 02:33:28'),
(51, 17, 'Module 3: Advanced Optimization & Project', '3. Linux System Administration — Processes, Networking & Services', 'Manage Linux processes, configure networking, work with systemd services, and monitor system health.', 'https://www.youtube.com/embed/wBp0Rb-ZJak', 30, 3, '2026-07-22 02:33:28'),
-- CS-118: Agile Product Delivery & Scrum
(52, 18, 'Module 1: Introduction & Fundamentals', '1. Agile Fundamentals — Manifesto, Principles & Mindset', 'Understand the Agile Manifesto, its 12 principles, and how Agile thinking transforms software delivery.', 'https://www.youtube.com/embed/8eVXTyIZ1Hs', 15, 1, '2026-07-22 02:33:28'),
(53, 18, 'Module 2: Practical Concepts & Implementation', '2. Scrum Framework — Roles, Events & Artifacts', 'Learn Scrum roles (Product Owner, Scrum Master, Dev Team), ceremonies, and artifacts like the Sprint Backlog.', 'https://www.youtube.com/embed/2Vt7Ik8Ublw', 25, 2, '2026-07-22 02:33:28'),
(54, 18, 'Module 3: Advanced Optimization & Project', '3. Agile Estimation, Kanban & Scaling Frameworks', 'Use story points, Kanban boards, velocity tracking, and explore scaling frameworks like SAFe and LeSS.', 'https://www.youtube.com/embed/iVaFVa7HYj4', 30, 3, '2026-07-22 02:33:28'),
-- CS-119: Practical Cyber Security Defenses
(55, 19, 'Module 1: Introduction & Fundamentals', '1. Ethical Hacking & Penetration Testing Fundamentals', 'Learn ethical hacking methodology, set up Kali Linux, and understand the penetration testing lifecycle.', 'https://www.youtube.com/embed/3Kq1MIfTWCE', 15, 1, '2026-07-22 02:33:28'),
(56, 19, 'Module 2: Practical Concepts & Implementation', '2. Network Security — Firewalls, VPNs & Intrusion Detection', 'Configure firewalls, understand VPN protocols, analyze network traffic with Wireshark, and set up IDS.', 'https://www.youtube.com/embed/qiQR5rTSshw', 25, 2, '2026-07-22 02:33:28'),
(57, 19, 'Module 3: Advanced Optimization & Project', '3. Incident Response, Cryptography & Security Operations', 'Build a security operations workflow, implement cryptography, and establish incident response procedures.', 'https://www.youtube.com/embed/AQDCe585Lnc', 30, 3, '2026-07-22 02:33:28'),
-- CS-120: Full Stack Web Architecture Capstone
(58, 20, 'Module 1: Introduction & Fundamentals', '1. Full Stack Architecture — Planning, Tech Stack & Project Setup', 'Design a full stack web application, choose the right tech stack, and set up the complete project structure.', 'https://www.youtube.com/embed/7CqJlxBYj-M', 15, 1, '2026-07-22 02:33:28'),
(59, 20, 'Module 2: Practical Concepts & Implementation', '2. Full Stack Development — Backend API, Database & Frontend Integration', 'Build the backend API, connect to the database, and integrate the frontend with the REST API.', 'https://www.youtube.com/embed/ngc9gnGgUdA', 25, 2, '2026-07-22 02:33:28');

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
  `announcement_id` int(11) DEFAULT NULL,
  `created_by_user_id` int(11) DEFAULT NULL,
  `created_by_role` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `link`, `is_read`, `type`, `announcement_id`, `created_by_user_id`, `created_by_role`, `created_at`) VALUES
(44, 1, 'Testing Features', 'Ignore the announcement', '#', 1, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(45, 37, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(47, 3, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(48, 4, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(49, 5, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(50, 6, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(51, 2, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(52, 43, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(54, 45, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(55, 44, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(58, 51, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(59, 42, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(60, 17, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(61, 24, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(62, 16, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(63, 23, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(64, 22, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(65, 11, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(66, 13, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(68, 14, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(69, 19, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(70, 26, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(71, 20, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(72, 15, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(73, 25, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(74, 18, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(75, 9, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(76, 12, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(77, 10, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(78, 21, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(79, 47, 'Testing Features', 'Ignore the announcement', '#', 1, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(80, 1, 'Testing From Admin Section', 'Ignore this.', '#', 1, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(81, 37, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(83, 3, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(84, 4, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(85, 5, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(86, 6, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(87, 2, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(88, 43, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(89, 45, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(90, 44, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(91, 56, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(92, 51, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(93, 42, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(94, 17, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(95, 24, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(96, 16, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(97, 23, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(98, 22, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(99, 11, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(100, 13, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(101, 14, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(102, 19, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(103, 26, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(104, 20, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(105, 15, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(106, 25, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(107, 18, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(108, 9, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(109, 12, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(110, 10, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(111, 21, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(112, 47, 'Testing From Admin Section', 'Ignore this.', '#', 1, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(114, 59, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(177, 9, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(178, 10, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(179, 11, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(180, 12, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(181, 13, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(182, 14, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(183, 15, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(184, 16, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(185, 17, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(186, 18, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(187, 19, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(188, 20, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(189, 21, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(190, 22, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(191, 23, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(192, 24, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(193, 25, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(194, 26, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(195, 37, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(196, 42, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(197, 43, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(198, 44, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(199, 45, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(200, 47, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 1, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(201, 51, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(202, 56, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(204, 59, 'New Announcement', 'sumeshs published a new announcement: Testing from Faculty Section', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 5, 60, 'faculty', '2026-07-23 14:41:48'),
(215, 9, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(216, 10, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(217, 11, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(218, 12, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(219, 13, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(220, 14, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(221, 15, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(222, 16, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(223, 17, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(224, 18, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(225, 19, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(226, 20, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(227, 21, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(228, 22, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(229, 23, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(230, 24, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(231, 25, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(232, 26, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(233, 37, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(234, 42, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(235, 43, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(236, 44, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(237, 45, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(239, 51, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(240, 56, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(242, 59, 'New Announcement', 'khansir published a new announcement: Testing from faculty', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 6, 63, 'faculty', '2026-07-24 09:59:02'),
(264, 9, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(265, 10, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(266, 11, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(267, 12, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(268, 13, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(269, 14, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(270, 15, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(271, 16, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(272, 17, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(273, 18, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(274, 19, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(275, 20, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(276, 21, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(277, 22, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(278, 23, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(279, 24, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(280, 25, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(281, 26, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(282, 37, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(283, 42, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(284, 43, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(285, 44, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(286, 45, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(287, 47, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(288, 51, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(289, 56, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(291, 59, 'New Announcement', 'khansir published a new announcement: testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 7, 63, 'faculty', '2026-07-26 18:55:25'),
(292, 4, 'Student Quiz Submission: JavaScript ES6 Asynchronous Programming', 'Student Encore Abj completed assessment \'JavaScript ES6 Asynchronous Programming\' with a score of 0.0% on 27 Jul 2026, 09:30 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 0, 'assessment', NULL, NULL, NULL, '2026-07-27 09:30:02'),
(293, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Encore Abj completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 0.0% on 27 Jul 2026, 09:31 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 0, 'assessment', NULL, NULL, NULL, '2026-07-27 09:31:07'),
(294, 5, 'Student Quiz Submission: HTML5 Semantic Markup & CSS3 Layouts', 'Student Encore Abj completed assessment \'HTML5 Semantic Markup & CSS3 Layouts\' with a score of 4.0% on 27 Jul 2026, 09:52 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 0, 'assessment', NULL, NULL, NULL, '2026-07-27 09:52:10');

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
(3, 3, 7, 8, 'Assessment score in Data Structures was 50.00%. Recommended to strengthen computer science fundamentals.', 'high', 0, '2026-07-20 20:01:07'),
(4, 5, 1, 1, 'Assessment score in PHP 8 Core Concepts was 50.00%. Recommended to complete core PHP course modules.', 'high', 0, '2026-07-20 20:01:07'),
(5, 4, 10, 11, 'Recommended based on high score in HTML5/CSS3 to expand frontend UI/UX design knowledge.', 'medium', 0, '2026-07-20 20:01:07'),
(12, 41, 4, 4, 'Your recent assessment in HTML5 & Responsive CSS3 was 12.00%. Recommended to bridge your 88.0% skill gap.', 'high', 0, '2026-07-22 13:44:53'),
(14, 50, 4, 4, 'Your recent assessment in HTML5 & Responsive CSS3 was 0.00%. Recommended to bridge your 100.0% skill gap.', 'high', 0, '2026-07-23 22:28:26'),
(15, 50, 1, 1, 'Your recent assessment in PHP 8 Web Development was 0.00%. Recommended to bridge your 100.0% skill gap.', 'high', 0, '2026-07-24 11:08:10'),
(16, 50, 3, 3, 'Your recent assessment in JavaScript ES6+ was 0.00%. Recommended to bridge your 100.0% skill gap.', 'high', 0, '2026-07-24 14:29:12'),
(17, 50, 3, 9, 'Manually recommended by faculty to reinforce core skill concepts.', 'medium', 0, '2026-07-24 14:43:09');

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
  `college_name` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `bio` varchar(255) DEFAULT NULL,
  `city_location` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(100) NOT NULL DEFAULT 'Computer Science',
  `current_semester` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_code`, `first_name`, `last_name`, `college_name`, `avatar`, `bio`, `city_location`, `phone`, `department`, `current_semester`, `created_at`) VALUES
(3, 9, 'STU-1003', 'Michael', 'Brown', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0103', 'Software Engineering', 6, '2026-07-20 20:01:07'),
(4, 10, 'STU-1004', 'Sophia', 'Johnson', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0104', 'Computer Science', 4, '2026-07-20 20:01:07'),
(5, 11, 'STU-1005', 'Daniel', 'Williams', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0105', 'Data Science', 6, '2026-07-20 20:01:07'),
(6, 12, 'STU-1006', 'Olivia', 'Jones', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0106', 'Software Engineering', 3, '2026-07-20 20:01:07'),
(7, 13, 'STU-1007', 'David', 'Miller', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0107', 'Computer Science', 5, '2026-07-20 20:01:07'),
(8, 14, 'STU-1008', 'Emma', 'Davis', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0108', 'Information Technology', 4, '2026-07-20 20:01:07'),
(9, 15, 'STU-1009', 'James', 'Wilson', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0109', 'Systems Engineering', 6, '2026-07-20 20:01:07'),
(10, 16, 'STU-1010', 'Ava', 'Taylor', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0110', 'Computer Science', 3, '2026-07-20 20:01:07'),
(11, 17, 'STU-1011', 'Alex', 'Anderson', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0111', 'Data Science', 5, '2026-07-20 20:01:07'),
(12, 18, 'STU-1012', 'Mia', 'Thomas', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0112', 'Software Engineering', 4, '2026-07-20 20:01:07'),
(13, 19, 'STU-1013', 'Ethan', 'Jackson', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0113', 'Computer Science', 6, '2026-07-20 20:01:07'),
(14, 20, 'STU-1014', 'Isabella', 'White', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0114', 'Information Technology', 3, '2026-07-20 20:01:07'),
(15, 21, 'STU-1015', 'William', 'Harris', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0115', 'Systems Engineering', 5, '2026-07-20 20:01:07'),
(16, 22, 'STU-1016', 'Charlotte', 'Martin', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0116', 'Computer Science', 4, '2026-07-20 20:01:07'),
(17, 23, 'STU-1017', 'Benjamin', 'Thompson', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0117', 'Software Engineering', 6, '2026-07-20 20:01:07'),
(18, 24, 'STU-1018', 'Amelia', 'Garcia', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0118', 'Data Science', 3, '2026-07-20 20:01:07'),
(19, 25, 'STU-1019', 'Lucas', 'Martinez', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0119', 'Computer Science', 5, '2026-07-20 20:01:07'),
(20, 26, 'STU-1020', 'Harper', 'Robinson', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '555-0120', 'Information Technology', 4, '2026-07-20 20:01:07'),
(31, 37, 'STU-1037', 'babu', 'don', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '+91 9524628562', 'Information Technology', 3, '2026-07-22 10:27:19'),
(36, 42, 'STU-1042', 'Vardhan', 'R', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '+91 7558272740', 'Computer Science', 1, '2026-07-22 11:44:45'),
(37, 43, 'STU-1043', 'Heroic', 'FF', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '', 'Computer Science', 1, '2026-07-22 12:13:32'),
(38, 44, 'STU-1044', 'Nikhil', 'Raut', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '+91 9021987113', 'Computer Science', 1, '2026-07-22 13:12:45'),
(39, 45, 'STU-1045', 'NIkhil', 'R', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '+91 9021987113', 'Computer Science', 1, '2026-07-22 13:16:46'),
(41, 47, 'STU-1047', 'sumedh', 'khalikar', NULL, 'avatar_user_47_1784708265.jpg', '', 'Mumbai, India', '+91 91121118092', 'Information Technology', 3, '2026-07-22 13:37:48'),
(43, 51, 'STU-1051', 'prajakta', 'tiruke', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '+91 7410534350', 'Information Technology', 1, '2026-07-22 14:32:28'),
(48, 56, 'STU-1056', 'Pavan', 'Thote', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '', 'Computer Science', 1, '2026-07-23 09:44:09'),
(50, 58, 'STU-1058', 'Encore', 'Abj', 'ZCOER', 'avatar_user_58_1784968910.jpg', 'No Half Measures....!', 'Mumbai, India', '7558272740', 'Information Technology', 1, '2026-07-23 11:39:30'),
(51, 59, 'STU-1059', 'Vaishnavi', 'Sutar', NULL, 'default-avatar.png', NULL, 'Mumbai, India', '', 'Computer Science', 1, '2026-07-23 13:31:40'),
(52, 64, 'STU-1064', 'TestStudentOne', 'User', 'SkillBridge University', 'default-avatar.png', NULL, 'Mumbai, India', '9876543210', 'Computer Science', 3, '2026-07-26 21:26:37'),
(53, 65, 'STU-1065', 'TestStudentTwo', 'User', 'SkillBridge University', 'default-avatar.png', NULL, 'Mumbai, India', '9876543211', 'Information Technology', 5, '2026-07-26 21:26:37');

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
(376, 38, 31, 'B', 1, 1),
(377, 38, 32, 'B', 1, 1),
(378, 38, 33, 'C', 0, 0),
(379, 38, 34, 'C', 0, 0),
(380, 38, 35, 'C', 1, 1),
(381, 38, 36, 'C', 0, 0),
(382, 38, 37, 'D', 0, 0),
(383, 38, 38, NULL, 0, 0),
(384, 38, 39, NULL, 0, 0),
(385, 38, 40, NULL, 0, 0),
(386, 38, 146, NULL, 0, 0),
(387, 38, 147, NULL, 0, 0),
(388, 38, 148, NULL, 0, 0),
(389, 38, 149, NULL, 0, 0),
(390, 38, 150, NULL, 0, 0),
(391, 38, 151, NULL, 0, 0),
(392, 38, 152, NULL, 0, 0),
(393, 38, 153, NULL, 0, 0),
(394, 38, 154, NULL, 0, 0),
(395, 38, 155, NULL, 0, 0),
(396, 38, 156, NULL, 0, 0),
(397, 38, 157, NULL, 0, 0),
(398, 38, 158, NULL, 0, 0),
(399, 38, 159, NULL, 0, 0),
(400, 38, 160, NULL, 0, 0),
(451, 41, 31, 'A', 0, 0),
(452, 41, 32, NULL, 0, 0),
(453, 41, 33, NULL, 0, 0),
(454, 41, 34, NULL, 0, 0),
(455, 41, 35, NULL, 0, 0),
(456, 41, 36, NULL, 0, 0),
(457, 41, 37, NULL, 0, 0),
(458, 41, 38, NULL, 0, 0),
(459, 41, 39, NULL, 0, 0),
(460, 41, 40, NULL, 0, 0),
(461, 41, 146, NULL, 0, 0),
(462, 41, 147, NULL, 0, 0),
(463, 41, 148, NULL, 0, 0),
(464, 41, 149, NULL, 0, 0),
(465, 41, 150, NULL, 0, 0),
(466, 41, 151, NULL, 0, 0),
(467, 41, 152, NULL, 0, 0),
(468, 41, 153, NULL, 0, 0),
(469, 41, 154, NULL, 0, 0),
(470, 41, 155, NULL, 0, 0),
(471, 41, 156, NULL, 0, 0),
(472, 41, 157, NULL, 0, 0),
(473, 41, 158, NULL, 0, 0),
(474, 41, 159, NULL, 0, 0),
(475, 41, 160, NULL, 0, 0),
(476, 42, 31, NULL, 0, 0),
(477, 42, 32, NULL, 0, 0),
(478, 42, 33, NULL, 0, 0),
(479, 42, 34, NULL, 0, 0),
(480, 42, 35, NULL, 0, 0),
(481, 42, 36, NULL, 0, 0),
(482, 42, 37, NULL, 0, 0),
(483, 42, 38, NULL, 0, 0),
(484, 42, 39, NULL, 0, 0),
(485, 42, 40, NULL, 0, 0),
(486, 42, 146, NULL, 0, 0),
(487, 42, 147, NULL, 0, 0),
(488, 42, 148, NULL, 0, 0),
(489, 42, 149, NULL, 0, 0),
(490, 42, 150, NULL, 0, 0),
(491, 42, 151, NULL, 0, 0),
(492, 42, 152, NULL, 0, 0),
(493, 42, 153, NULL, 0, 0),
(494, 42, 154, NULL, 0, 0),
(495, 42, 155, NULL, 0, 0),
(496, 42, 156, NULL, 0, 0),
(497, 42, 157, NULL, 0, 0),
(498, 42, 158, NULL, 0, 0),
(499, 42, 159, NULL, 0, 0),
(500, 42, 160, NULL, 0, 0),
(501, 43, 1, NULL, 0, 0),
(502, 43, 2, NULL, 0, 0),
(503, 43, 3, NULL, 0, 0),
(504, 43, 4, NULL, 0, 0),
(505, 43, 5, NULL, 0, 0),
(506, 43, 6, NULL, 0, 0),
(507, 43, 7, NULL, 0, 0),
(508, 43, 8, NULL, 0, 0),
(509, 43, 9, NULL, 0, 0),
(510, 43, 10, NULL, 0, 0),
(511, 43, 101, NULL, 0, 0),
(512, 43, 102, NULL, 0, 0),
(513, 43, 103, NULL, 0, 0),
(514, 43, 104, NULL, 0, 0),
(515, 43, 105, NULL, 0, 0),
(516, 43, 106, NULL, 0, 0),
(517, 43, 107, NULL, 0, 0),
(518, 43, 108, NULL, 0, 0),
(519, 43, 109, NULL, 0, 0),
(520, 43, 110, NULL, 0, 0),
(521, 43, 111, NULL, 0, 0),
(522, 43, 112, NULL, 0, 0),
(523, 43, 113, NULL, 0, 0),
(524, 43, 114, NULL, 0, 0),
(525, 43, 115, NULL, 0, 0),
(526, 44, 21, NULL, 0, 0),
(527, 44, 22, NULL, 0, 0),
(528, 44, 23, NULL, 0, 0),
(529, 44, 24, NULL, 0, 0),
(530, 44, 25, NULL, 0, 0),
(531, 44, 26, NULL, 0, 0),
(532, 44, 27, NULL, 0, 0),
(533, 44, 28, NULL, 0, 0),
(534, 44, 29, NULL, 0, 0),
(535, 44, 30, NULL, 0, 0),
(536, 44, 131, NULL, 0, 0),
(537, 44, 132, NULL, 0, 0),
(538, 44, 133, NULL, 0, 0),
(539, 44, 134, NULL, 0, 0),
(540, 44, 135, NULL, 0, 0),
(541, 44, 136, NULL, 0, 0),
(542, 44, 137, NULL, 0, 0),
(543, 44, 138, NULL, 0, 0),
(544, 44, 139, NULL, 0, 0),
(545, 44, 140, NULL, 0, 0),
(546, 44, 141, NULL, 0, 0),
(547, 44, 142, NULL, 0, 0),
(548, 44, 143, NULL, 0, 0),
(549, 44, 144, NULL, 0, 0),
(550, 44, 145, NULL, 0, 0),
(551, 45, 1, NULL, 0, 0),
(552, 45, 2, NULL, 0, 0),
(553, 45, 3, NULL, 0, 0),
(554, 45, 4, NULL, 0, 0),
(555, 45, 5, NULL, 0, 0),
(556, 45, 6, NULL, 0, 0),
(557, 45, 7, NULL, 0, 0),
(558, 45, 8, NULL, 0, 0),
(559, 45, 9, NULL, 0, 0),
(560, 45, 10, NULL, 0, 0),
(561, 45, 101, NULL, 0, 0),
(562, 45, 102, NULL, 0, 0),
(563, 45, 103, NULL, 0, 0),
(564, 45, 104, NULL, 0, 0),
(565, 45, 105, NULL, 0, 0),
(566, 45, 106, NULL, 0, 0),
(567, 45, 107, NULL, 0, 0),
(568, 45, 108, NULL, 0, 0),
(569, 45, 109, NULL, 0, 0),
(570, 45, 110, NULL, 0, 0),
(571, 45, 111, NULL, 0, 0),
(572, 45, 112, NULL, 0, 0),
(573, 45, 113, NULL, 0, 0),
(574, 45, 114, NULL, 0, 0),
(575, 45, 115, NULL, 0, 0),
(576, 46, 31, NULL, 0, 0),
(577, 46, 32, NULL, 0, 0),
(578, 46, 33, NULL, 0, 0),
(579, 46, 34, NULL, 0, 0),
(580, 46, 35, NULL, 0, 0),
(581, 46, 36, NULL, 0, 0),
(582, 46, 37, NULL, 0, 0),
(583, 46, 38, NULL, 0, 0),
(584, 46, 39, NULL, 0, 0),
(585, 46, 40, NULL, 0, 0),
(586, 46, 146, NULL, 0, 0),
(587, 46, 147, NULL, 0, 0),
(588, 46, 148, NULL, 0, 0),
(589, 46, 149, NULL, 0, 0),
(590, 46, 150, NULL, 0, 0),
(591, 46, 151, NULL, 0, 0),
(592, 46, 152, NULL, 0, 0),
(593, 46, 153, NULL, 0, 0),
(594, 46, 154, NULL, 0, 0),
(595, 46, 155, NULL, 0, 0),
(596, 46, 156, NULL, 0, 0),
(597, 46, 157, NULL, 0, 0),
(598, 46, 158, NULL, 0, 0),
(599, 46, 159, NULL, 0, 0),
(600, 46, 160, NULL, 0, 0),
(601, 47, 11, 'B', 1, 1),
(602, 47, 12, 'D', 1, 1),
(603, 47, 13, 'B', 1, 1),
(604, 47, 14, 'C', 1, 1),
(605, 47, 15, 'B', 1, 1),
(606, 47, 16, 'B', 1, 1),
(607, 47, 17, 'B', 1, 1),
(608, 47, 18, 'A', 1, 1),
(609, 47, 19, 'C', 1, 1),
(610, 47, 20, 'B', 1, 1),
(611, 47, 116, 'A', 1, 1),
(612, 47, 117, 'A', 1, 1),
(613, 47, 118, 'A', 1, 1),
(614, 47, 119, 'A', 1, 1),
(615, 47, 120, 'A', 1, 1),
(616, 47, 121, 'A', 1, 1),
(617, 47, 122, 'A', 1, 1),
(618, 47, 123, 'A', 1, 1),
(619, 47, 124, 'A', 1, 1),
(620, 47, 125, 'A', 1, 1),
(621, 47, 126, 'A', 1, 1),
(622, 47, 127, 'A', 1, 1),
(623, 47, 128, 'A', 1, 1),
(624, 47, 129, 'A', 1, 1),
(625, 47, 130, 'A', 1, 1),
(626, 49, 31, 'B', 1, 1),
(627, 49, 32, 'C', 0, 0),
(628, 49, 33, 'C', 0, 0),
(629, 49, 34, 'C', 0, 0),
(630, 49, 35, 'C', 1, 1),
(631, 49, 36, NULL, 0, 0),
(632, 49, 37, NULL, 0, 0),
(633, 49, 38, NULL, 0, 0),
(634, 49, 39, NULL, 0, 0),
(635, 49, 40, NULL, 0, 0),
(636, 49, 146, NULL, 0, 0),
(637, 49, 147, NULL, 0, 0),
(638, 49, 148, NULL, 0, 0),
(639, 49, 149, NULL, 0, 0),
(640, 49, 150, NULL, 0, 0),
(641, 49, 151, NULL, 0, 0),
(642, 49, 152, NULL, 0, 0),
(643, 49, 153, NULL, 0, 0),
(644, 49, 154, NULL, 0, 0),
(645, 49, 155, NULL, 0, 0),
(646, 49, 156, NULL, 0, 0),
(647, 49, 157, NULL, 0, 0),
(648, 49, 158, NULL, 0, 0),
(649, 49, 159, NULL, 0, 0),
(650, 49, 160, NULL, 0, 0),
(651, 50, 31, NULL, 0, 0),
(652, 50, 32, NULL, 0, 0),
(653, 50, 33, 'D', 0, 0),
(654, 50, 34, NULL, 0, 0),
(655, 50, 35, NULL, 0, 0),
(656, 50, 36, NULL, 0, 0),
(657, 50, 37, NULL, 0, 0),
(658, 50, 38, NULL, 0, 0),
(659, 50, 39, NULL, 0, 0),
(660, 50, 40, NULL, 0, 0),
(661, 50, 146, NULL, 0, 0),
(662, 50, 147, NULL, 0, 0),
(663, 50, 148, NULL, 0, 0),
(664, 50, 149, NULL, 0, 0),
(665, 50, 150, NULL, 0, 0),
(666, 50, 151, NULL, 0, 0),
(667, 50, 152, NULL, 0, 0),
(668, 50, 153, NULL, 0, 0),
(669, 50, 154, NULL, 0, 0),
(670, 50, 155, NULL, 0, 0),
(671, 50, 156, NULL, 0, 0),
(672, 50, 157, NULL, 0, 0),
(673, 50, 158, NULL, 0, 0),
(674, 50, 159, NULL, 0, 0),
(675, 50, 160, NULL, 0, 0),
(676, 51, 31, NULL, 0, 0),
(677, 51, 32, NULL, 0, 0),
(678, 51, 33, NULL, 0, 0),
(679, 51, 34, NULL, 0, 0),
(680, 51, 35, NULL, 0, 0),
(681, 51, 36, NULL, 0, 0),
(682, 51, 37, NULL, 0, 0),
(683, 51, 38, NULL, 0, 0),
(684, 51, 39, NULL, 0, 0),
(685, 51, 40, NULL, 0, 0),
(686, 51, 146, NULL, 0, 0),
(687, 51, 147, NULL, 0, 0),
(688, 51, 148, NULL, 0, 0),
(689, 51, 149, NULL, 0, 0),
(690, 51, 150, NULL, 0, 0),
(691, 51, 151, NULL, 0, 0),
(692, 51, 152, NULL, 0, 0),
(693, 51, 153, NULL, 0, 0),
(694, 51, 154, NULL, 0, 0),
(695, 51, 155, NULL, 0, 0),
(696, 51, 156, NULL, 0, 0),
(697, 51, 157, NULL, 0, 0),
(698, 51, 158, NULL, 0, 0),
(699, 51, 159, NULL, 0, 0),
(700, 51, 160, NULL, 0, 0),
(701, 52, 31, NULL, 0, 0),
(702, 52, 32, NULL, 0, 0),
(703, 52, 33, NULL, 0, 0),
(704, 52, 34, NULL, 0, 0),
(705, 52, 35, NULL, 0, 0),
(706, 52, 36, NULL, 0, 0),
(707, 52, 37, NULL, 0, 0),
(708, 52, 38, NULL, 0, 0),
(709, 52, 39, NULL, 0, 0),
(710, 52, 40, NULL, 0, 0),
(711, 52, 146, NULL, 0, 0),
(712, 52, 147, NULL, 0, 0),
(713, 52, 148, NULL, 0, 0),
(714, 52, 149, NULL, 0, 0),
(715, 52, 150, NULL, 0, 0),
(716, 52, 151, NULL, 0, 0),
(717, 52, 152, NULL, 0, 0),
(718, 52, 153, NULL, 0, 0),
(719, 52, 154, NULL, 0, 0),
(720, 52, 155, NULL, 0, 0),
(721, 52, 156, NULL, 0, 0),
(722, 52, 157, NULL, 0, 0),
(723, 52, 158, 'B', 0, 0),
(724, 52, 159, NULL, 0, 0),
(725, 52, 160, NULL, 0, 0),
(726, 53, 31, NULL, 0, 0),
(727, 53, 32, NULL, 0, 0),
(728, 53, 33, NULL, 0, 0),
(729, 53, 34, NULL, 0, 0),
(730, 53, 35, NULL, 0, 0),
(731, 53, 36, NULL, 0, 0),
(732, 53, 37, NULL, 0, 0),
(733, 53, 38, NULL, 0, 0),
(734, 53, 39, NULL, 0, 0),
(735, 53, 40, NULL, 0, 0),
(736, 53, 146, NULL, 0, 0),
(737, 53, 147, NULL, 0, 0),
(738, 53, 148, NULL, 0, 0),
(739, 53, 149, NULL, 0, 0),
(740, 53, 150, NULL, 0, 0),
(741, 53, 151, NULL, 0, 0),
(742, 53, 152, NULL, 0, 0),
(743, 53, 153, NULL, 0, 0),
(744, 53, 154, NULL, 0, 0),
(745, 53, 155, NULL, 0, 0),
(746, 53, 156, NULL, 0, 0),
(747, 53, 157, NULL, 0, 0),
(748, 53, 158, NULL, 0, 0),
(749, 53, 159, NULL, 0, 0),
(750, 53, 160, NULL, 0, 0),
(751, 54, 31, NULL, 0, 0),
(752, 54, 32, NULL, 0, 0),
(753, 54, 33, NULL, 0, 0),
(754, 54, 34, NULL, 0, 0),
(755, 54, 35, NULL, 0, 0),
(756, 54, 36, NULL, 0, 0),
(757, 54, 37, NULL, 0, 0),
(758, 54, 38, NULL, 0, 0),
(759, 54, 39, NULL, 0, 0),
(760, 54, 40, NULL, 0, 0),
(761, 54, 146, NULL, 0, 0),
(762, 54, 147, NULL, 0, 0),
(763, 54, 148, NULL, 0, 0),
(764, 54, 149, NULL, 0, 0),
(765, 54, 150, NULL, 0, 0),
(766, 54, 151, NULL, 0, 0),
(767, 54, 152, NULL, 0, 0),
(768, 54, 153, NULL, 0, 0),
(769, 54, 154, NULL, 0, 0),
(770, 54, 155, NULL, 0, 0),
(771, 54, 156, NULL, 0, 0),
(772, 54, 157, NULL, 0, 0),
(773, 54, 158, NULL, 0, 0),
(774, 54, 159, NULL, 0, 0),
(775, 54, 160, NULL, 0, 0),
(776, 55, 1, 'B', 1, 1),
(777, 55, 2, NULL, 0, 0),
(778, 55, 3, NULL, 0, 0),
(779, 55, 4, NULL, 0, 0),
(780, 55, 5, NULL, 0, 0),
(781, 55, 6, NULL, 0, 0),
(782, 55, 7, NULL, 0, 0),
(783, 55, 8, NULL, 0, 0),
(784, 55, 9, NULL, 0, 0),
(785, 55, 10, NULL, 0, 0),
(786, 55, 101, NULL, 0, 0),
(787, 55, 102, NULL, 0, 0),
(788, 55, 103, NULL, 0, 0),
(789, 55, 104, NULL, 0, 0),
(790, 55, 105, NULL, 0, 0),
(791, 55, 106, NULL, 0, 0),
(792, 55, 107, 'D', 0, 0),
(793, 55, 108, NULL, 0, 0),
(794, 55, 109, NULL, 0, 0),
(795, 55, 110, NULL, 0, 0),
(796, 55, 111, NULL, 0, 0),
(797, 55, 112, NULL, 0, 0),
(798, 55, 113, NULL, 0, 0),
(799, 55, 114, NULL, 0, 0),
(800, 55, 115, NULL, 0, 0),
(801, 56, 31, NULL, 0, 0),
(802, 56, 32, NULL, 0, 0),
(803, 56, 33, NULL, 0, 0),
(804, 56, 34, NULL, 0, 0),
(805, 56, 35, NULL, 0, 0),
(806, 56, 36, NULL, 0, 0),
(807, 56, 37, NULL, 0, 0),
(808, 56, 38, NULL, 0, 0),
(809, 56, 39, NULL, 0, 0),
(810, 56, 40, NULL, 0, 0),
(811, 56, 146, NULL, 0, 0),
(812, 56, 147, NULL, 0, 0),
(813, 56, 148, NULL, 0, 0),
(814, 56, 149, NULL, 0, 0),
(815, 56, 150, NULL, 0, 0),
(816, 56, 151, NULL, 0, 0),
(817, 56, 152, NULL, 0, 0),
(818, 56, 153, NULL, 0, 0),
(819, 56, 154, NULL, 0, 0),
(820, 56, 155, NULL, 0, 0),
(821, 56, 156, NULL, 0, 0),
(822, 56, 157, NULL, 0, 0),
(823, 56, 158, NULL, 0, 0),
(824, 56, 159, NULL, 0, 0),
(825, 56, 160, NULL, 0, 0),
(826, 57, 1, 'B', 1, 1),
(827, 57, 2, NULL, 0, 0),
(828, 57, 3, NULL, 0, 0),
(829, 57, 4, 'B', 0, 0),
(830, 57, 5, NULL, 0, 0),
(831, 57, 6, NULL, 0, 0),
(832, 57, 7, NULL, 0, 0),
(833, 57, 8, NULL, 0, 0),
(834, 57, 9, NULL, 0, 0),
(835, 57, 10, NULL, 0, 0),
(836, 57, 101, NULL, 0, 0),
(837, 57, 102, NULL, 0, 0),
(838, 57, 103, NULL, 0, 0),
(839, 57, 104, NULL, 0, 0),
(840, 57, 105, NULL, 0, 0),
(841, 57, 106, NULL, 0, 0),
(842, 57, 107, NULL, 0, 0),
(843, 57, 108, NULL, 0, 0),
(844, 57, 109, NULL, 0, 0),
(845, 57, 110, NULL, 0, 0),
(846, 57, 111, NULL, 0, 0),
(847, 57, 112, NULL, 0, 0),
(848, 57, 113, NULL, 0, 0),
(849, 57, 114, NULL, 0, 0),
(850, 57, 115, NULL, 0, 0),
(851, 58, 31, 'B', 1, 1),
(852, 58, 32, 'B', 1, 1),
(853, 58, 33, 'C', 0, 0),
(854, 58, 34, 'C', 0, 0),
(855, 58, 35, 'C', 1, 1),
(856, 58, 36, 'B', 1, 1),
(857, 58, 37, 'B', 1, 1),
(858, 58, 38, 'C', 1, 1),
(859, 58, 39, 'B', 1, 1),
(860, 58, 40, 'B', 1, 1),
(861, 58, 146, 'A', 1, 1),
(862, 58, 147, 'A', 1, 1),
(863, 58, 148, 'C', 0, 0),
(864, 58, 149, NULL, 0, 0),
(865, 58, 150, NULL, 0, 0),
(866, 58, 151, NULL, 0, 0),
(867, 58, 152, NULL, 0, 0),
(868, 58, 153, NULL, 0, 0),
(869, 58, 154, NULL, 0, 0),
(870, 58, 155, NULL, 0, 0),
(871, 58, 156, NULL, 0, 0),
(872, 58, 157, NULL, 0, 0),
(873, 58, 158, NULL, 0, 0),
(874, 58, 159, NULL, 0, 0),
(875, 58, 160, NULL, 0, 0),
(876, 59, 21, 'A', 0, 0),
(877, 59, 22, 'D', 0, 0),
(878, 59, 23, 'B', 1, 1),
(879, 59, 24, 'C', 0, 0),
(880, 59, 25, 'B', 1, 1),
(881, 59, 26, 'B', 1, 1),
(882, 59, 27, 'A', 0, 0),
(883, 59, 28, NULL, 0, 0),
(884, 59, 29, NULL, 0, 0),
(885, 59, 30, NULL, 0, 0),
(886, 59, 131, NULL, 0, 0),
(887, 59, 132, NULL, 0, 0),
(888, 59, 133, NULL, 0, 0),
(889, 59, 134, NULL, 0, 0),
(890, 59, 135, NULL, 0, 0),
(891, 59, 136, NULL, 0, 0),
(892, 59, 137, NULL, 0, 0),
(893, 59, 138, NULL, 0, 0),
(894, 59, 139, NULL, 0, 0),
(895, 59, 140, NULL, 0, 0),
(896, 59, 141, NULL, 0, 0),
(897, 59, 142, NULL, 0, 0),
(898, 59, 143, NULL, 0, 0),
(899, 59, 144, NULL, 0, 0),
(900, 59, 145, NULL, 0, 0),
(901, 60, 31, 'B', 1, 1),
(902, 60, 32, NULL, 0, 0),
(903, 60, 33, NULL, 0, 0),
(904, 60, 34, NULL, 0, 0),
(905, 60, 35, NULL, 0, 0),
(906, 60, 36, NULL, 0, 0),
(907, 60, 37, NULL, 0, 0),
(908, 60, 38, NULL, 0, 0),
(909, 60, 39, NULL, 0, 0),
(910, 60, 40, NULL, 0, 0),
(911, 60, 146, NULL, 0, 0),
(912, 60, 147, NULL, 0, 0),
(913, 60, 148, NULL, 0, 0),
(914, 60, 149, NULL, 0, 0),
(915, 60, 150, NULL, 0, 0),
(916, 60, 151, NULL, 0, 0),
(917, 60, 152, NULL, 0, 0),
(918, 60, 153, NULL, 0, 0),
(919, 60, 154, NULL, 0, 0),
(920, 60, 155, NULL, 0, 0),
(921, 60, 156, NULL, 0, 0),
(922, 60, 157, NULL, 0, 0),
(923, 60, 158, NULL, 0, 0),
(924, 60, 159, NULL, 0, 0),
(925, 60, 160, 'B', 0, 0),
(926, 61, 21, NULL, 0, 0),
(927, 61, 22, NULL, 0, 0),
(928, 61, 23, NULL, 0, 0),
(929, 61, 24, NULL, 0, 0),
(930, 61, 25, NULL, 0, 0),
(931, 61, 26, NULL, 0, 0),
(932, 61, 27, NULL, 0, 0),
(933, 61, 28, NULL, 0, 0),
(934, 61, 29, NULL, 0, 0),
(935, 61, 30, NULL, 0, 0),
(936, 61, 131, NULL, 0, 0),
(937, 61, 132, NULL, 0, 0),
(938, 61, 133, NULL, 0, 0),
(939, 61, 134, NULL, 0, 0),
(940, 61, 135, NULL, 0, 0),
(941, 61, 136, NULL, 0, 0),
(942, 61, 137, NULL, 0, 0),
(943, 61, 138, NULL, 0, 0),
(944, 61, 139, NULL, 0, 0),
(945, 61, 140, NULL, 0, 0),
(946, 61, 141, NULL, 0, 0),
(947, 61, 142, NULL, 0, 0),
(948, 61, 143, NULL, 0, 0),
(949, 61, 144, NULL, 0, 0),
(950, 61, 145, NULL, 0, 0),
(951, 62, 21, NULL, 0, 0),
(952, 62, 22, 'D', 0, 0),
(953, 62, 23, NULL, 0, 0),
(954, 62, 24, NULL, 0, 0),
(955, 62, 25, NULL, 0, 0),
(956, 62, 26, NULL, 0, 0),
(957, 62, 27, NULL, 0, 0),
(958, 62, 28, NULL, 0, 0),
(959, 62, 29, NULL, 0, 0),
(960, 62, 30, NULL, 0, 0),
(961, 62, 131, NULL, 0, 0),
(962, 62, 132, NULL, 0, 0),
(963, 62, 133, NULL, 0, 0),
(964, 62, 134, NULL, 0, 0),
(965, 62, 135, NULL, 0, 0),
(966, 62, 136, NULL, 0, 0),
(967, 62, 137, NULL, 0, 0),
(968, 62, 138, NULL, 0, 0),
(969, 62, 139, NULL, 0, 0),
(970, 62, 140, NULL, 0, 0),
(971, 62, 141, NULL, 0, 0),
(972, 62, 142, NULL, 0, 0),
(973, 62, 143, NULL, 0, 0),
(974, 62, 144, NULL, 0, 0),
(975, 62, 145, NULL, 0, 0),
(976, 63, 1, NULL, 0, 0),
(977, 63, 2, NULL, 0, 0),
(978, 63, 3, NULL, 0, 0),
(979, 63, 4, NULL, 0, 0),
(980, 63, 5, NULL, 0, 0),
(981, 63, 6, NULL, 0, 0),
(982, 63, 7, NULL, 0, 0),
(983, 63, 8, NULL, 0, 0),
(984, 63, 9, NULL, 0, 0),
(985, 63, 10, NULL, 0, 0),
(986, 63, 101, NULL, 0, 0),
(987, 63, 102, NULL, 0, 0),
(988, 63, 103, NULL, 0, 0),
(989, 63, 104, NULL, 0, 0),
(990, 63, 105, NULL, 0, 0),
(991, 63, 106, NULL, 0, 0),
(992, 63, 107, NULL, 0, 0),
(993, 63, 108, NULL, 0, 0),
(994, 63, 109, NULL, 0, 0),
(995, 63, 110, NULL, 0, 0),
(996, 63, 111, NULL, 0, 0),
(997, 63, 112, NULL, 0, 0),
(998, 63, 113, NULL, 0, 0),
(999, 63, 114, NULL, 0, 0),
(1000, 63, 115, NULL, 0, 0),
(1001, 64, 31, NULL, 0, 0),
(1002, 64, 32, 'B', 1, 1),
(1003, 64, 33, NULL, 0, 0),
(1004, 64, 34, NULL, 0, 0),
(1005, 64, 35, NULL, 0, 0),
(1006, 64, 36, NULL, 0, 0),
(1007, 64, 37, NULL, 0, 0),
(1008, 64, 38, NULL, 0, 0),
(1009, 64, 39, NULL, 0, 0),
(1010, 64, 40, NULL, 0, 0),
(1011, 64, 146, NULL, 0, 0),
(1012, 64, 147, NULL, 0, 0),
(1013, 64, 148, NULL, 0, 0),
(1014, 64, 149, NULL, 0, 0),
(1015, 64, 150, NULL, 0, 0),
(1016, 64, 151, NULL, 0, 0),
(1017, 64, 152, NULL, 0, 0),
(1018, 64, 153, NULL, 0, 0),
(1019, 64, 154, NULL, 0, 0),
(1020, 64, 155, NULL, 0, 0),
(1021, 64, 156, NULL, 0, 0),
(1022, 64, 157, NULL, 0, 0),
(1023, 64, 158, NULL, 0, 0),
(1024, 64, 159, NULL, 0, 0),
(1025, 64, 160, NULL, 0, 0);

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
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_lessons` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_progress`
--

INSERT INTO `student_progress` (`id`, `student_id`, `course_id`, `progress_percentage`, `status`, `last_updated`, `completed_lessons`) VALUES
(7, 3, 1, 90, 'in_progress', '2026-07-20 20:01:07', NULL),
(8, 3, 2, 100, 'completed', '2026-07-20 20:01:07', NULL),
(9, 3, 7, 30, 'in_progress', '2026-07-20 20:01:07', NULL),
(10, 4, 4, 100, 'completed', '2026-07-20 20:01:07', NULL),
(11, 4, 5, 90, 'in_progress', '2026-07-20 20:01:07', NULL),
(12, 5, 1, 40, 'in_progress', '2026-07-20 20:01:07', NULL),
(13, 5, 6, 85, 'in_progress', '2026-07-20 20:01:07', NULL),
(19, 50, 20, 100, 'completed', '2026-07-23 11:40:30', NULL),
(20, 41, 20, 10, 'in_progress', '2026-07-24 10:55:35', NULL),
(21, 50, 16, 10, 'in_progress', '2026-07-26 12:33:41', NULL),
(22, 50, 4, 10, 'in_progress', '2026-07-26 12:59:57', NULL),
(23, 50, 1, 33, 'in_progress', '2026-07-26 13:16:37', '[1]'),
(24, 50, 19, 85, 'in_progress', '2026-07-26 18:21:22', '[55,56]');

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
('admin_email', 'skill.bridge.project1@gmail.com', 'general', 'System administrator contact email'),
('enable_auto_recommendations', '1', 'analytics', 'Automatically trigger AI/Rule recommendations on skill gaps'),
('institution_name', 'Global Institute of Technology', 'general', 'Educational institution name'),
('pass_mark_threshold', '60', 'assessment', 'Default passing percentage threshold for assessments'),
('proctoring_max_violations', '3', 'security', 'Maximum allowed proctoring violations before automatic submission'),
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
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 1,
  `email_verification_otp` varchar(10) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `theme` varchar(20) NOT NULL DEFAULT 'system'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`, `remember_token`, `reset_token`, `reset_token_expiry`, `email_verified`, `email_verification_otp`, `otp_expiry`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'sudrikyash1@gmail.com', '$2y$10$2O2SpJW0i7pkCLvhc006kuJ1EUKVNQVVzCet3/BOqEVeXOCKboNyu', 'admin', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-23 19:26:09'),
(2, 'f_turing', 'faculty1@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(3, 'f_hopper', 'faculty2@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(4, 'f_knuth', 'faculty3@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(5, 'f_lovelace', 'faculty4@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(6, 'f_torvalds', 'faculty5@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(9, 's_michael', 'student3@skillbridge.edu', '$2y$10$tvfRTgnhMObrLPzROY8S6ORxYznGOlUpTfFxaOdLHBsgaQASCUlWy', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-26 18:28:30'),
(10, 's_sophia', 'student4@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(11, 's_daniel', 'student5@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(12, 's_olivia', 'student6@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(13, 's_david', 'student7@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(14, 's_emma', 'student8@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(15, 's_james', 'student9@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(16, 's_ava', 'student10@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(17, 's_alex', 'student11@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(18, 's_mia', 'student12@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(19, 's_ethan', 'student13@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(20, 's_isabella', 'student14@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(21, 's_william', 'student15@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(22, 's_charlotte', 'student16@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(23, 's_benjamin', 'student17@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(24, 's_amelia', 'student18@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(25, 's_lucas', 'student19@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(26, 's_harper', 'student20@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07'),
(37, 'babudon2', 'warriorbabu402@gmail.com', '$2y$10$y6xKKbevdAudPrAMjsGmU.xKIEtwqRn4baiQt/i1KdROW3BEqatQa', 'student', 'active', NULL, NULL, NULL, 0, '702532', '2026-07-22 10:38:06', '2026-07-22 10:27:19', '2026-07-22 10:28:06'),
(42, 'rona', 'mohitspatil255@gmail.com', '$2y$10$bZXTgKgBS/tXBT3c3RR19.zQZcJczm842SbPxWMde8d3OtD/7TrLi', 'student', 'active', NULL, NULL, NULL, 0, '442498', '2026-07-22 11:55:03', '2026-07-22 11:44:45', '2026-07-22 11:45:03'),
(43, 'heroic', 'ravindramude44@gmail.com', '$2y$10$AnO/mUslQ.0dxcjFIl55q.lBPiEOZyJh31x5Arr0WpEE8qSxSrmzm', 'student', 'active', NULL, NULL, NULL, 0, '647949', '2026-07-22 12:25:09', '2026-07-22 12:13:32', '2026-07-22 12:15:09'),
(44, 'nsr', 'nikhilrout9848@gmail.com', '$2y$10$Q2/GkoKJ8tYBUxHvTDQ9R.NCGn/7pXjH.FtGDeCF77rxOTKcCMchC', 'student', 'active', NULL, NULL, NULL, 0, '362934', '2026-07-22 13:22:45', '2026-07-22 13:12:45', '2026-07-22 13:12:45'),
(45, 'nikhil', 'bettercallsaul9848@gmail.com', '$2y$10$rkzVJ4ALhbUtdQeotAk4mOj2.JzWlYqscxDYAKTRVlIzONr/MVthq', 'student', 'active', NULL, '60e7ac49a72776ae04e9eb4ade23dba6bed35b8033e5b3be335617db2c3fbb65', '2026-07-22 13:49:30', 1, NULL, NULL, '2026-07-22 13:16:46', '2026-07-22 13:19:30'),
(47, 'sumedh2', 'khalikarsumedh07@gmail.com', '$2y$10$xTNtBqzegWTkf2yQO6iDyOVAirNao.RScAzZVOfraJEUr2JI0nO3q', 'student', 'active', NULL, '91ebb622a380d17df0c13a891b9b89cffffb31c31a64aab158e5f9af06dbd359', '2026-07-22 14:09:19', 1, NULL, NULL, '2026-07-22 13:37:48', '2026-07-22 13:39:19'),
(51, 'praju', 'tiruke.prajakta163@gmail.com', '$2y$10$KS2v5luoYZ2of8XB1ZyBDex6TdMVnCTZkDMB4yOVZhfciGNvDw9l6', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-22 14:32:28', '2026-07-22 14:32:58'),
(56, 'pavan', 'pavanthote7777@gmail.com', '$2y$10$vf4BxMw5dzf0GbVfMv4QKOVuCxV.KbO54gMcH6uF/zg6qLDtbkD1C', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-23 09:44:09', '2026-07-23 10:51:52'),
(58, 'encore.exe', 'marathaedits96@gmail.com', '$2y$10$6F4vbUcd1hW907MTYxykSOzuNgsasHt2raJynW095ucSfvwWaNVe2', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-23 11:39:30', '2026-07-23 11:39:56'),
(59, 'vaishnavi', 'vaishnavisutar0808@gmail.com', '$2y$10$ncX6KzdVHMM3TdbtOBWWAe4eJowvSw4/YRfA0kW1SU.Yhpcjt6Hg.', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-23 13:31:40', '2026-07-23 13:32:20'),
(61, 'dr_vikram', 'test_fac_approve@skillbridge.edu', '$2y$10$rTkys9QqJw/CE884zfmk8uWiBsjaO1SJ5UaokjiBAU.RJryFCr1OK', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-23 15:03:06', '2026-07-23 15:03:06'),
(62, 'test_reject', 'test_fac_reject@skillbridge.edu', '$2y$10$rTkys9QqJw/CE884zfmk8uWiBsjaO1SJ5UaokjiBAU.RJryFCr1OK', 'faculty', 'rejected', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-23 15:03:15', '2026-07-23 15:03:15'),
(63, 'khansir', 'heroicff2727@gmail.com', '$2y$10$MXdLJxKTnRS/f/S21aT6MudokGU1ImMIqkiOR8noejiRSz.3ee8xq', 'faculty', 'active', NULL, 'c085985c10c854e454513dae9c7716338667f9dc2caca3bcf46fa195892d360f', '2026-07-24 10:21:15', 1, NULL, NULL, '2026-07-23 19:03:55', '2026-07-24 09:51:15'),
(64, 'test_stu_one_116', 'test.student.one570@skillbridge.edu', '$2y$10$6fGlxuIOIBoes1fYMt1lueSKJqtu1ifudL6cOyTUvLPPrqK7QNYb.', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-26 21:26:37', '2026-07-26 21:26:37'),
(65, 'test_stu_two_479', 'test.student.two880@skillbridge.edu', '$2y$10$FL.P04etjjzR6Ven50UdkupqCgTlnoNr0dLIpxJsz4R0Nb2A9tLSm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-26 21:26:37', '2026-07-26 21:26:37');

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
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_announcements_created_by` (`created_by_user_id`);

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assessment_skill` (`skill_id`),
  ADD KEY `fk_assessment_faculty` (`created_by_faculty_id`);

--
-- Indexes for table `assessment_proctoring_logs`
--
ALTER TABLE `assessment_proctoring_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_id` (`result_id`);

--
-- Indexes for table `assessment_proctoring_summaries`
--
ALTER TABLE `assessment_proctoring_summaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `result_id` (`result_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=365;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `assessment_proctoring_logs`
--
ALTER TABLE `assessment_proctoring_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `assessment_proctoring_summaries`
--
ALTER TABLE `assessment_proctoring_summaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `assessment_questions`
--
ALTER TABLE `assessment_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `assessment_results`
--
ALTER TABLE `assessment_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1026;

--
-- AUTO_INCREMENT for table `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
-- Constraints for table `assessment_proctoring_logs`
--
ALTER TABLE `assessment_proctoring_logs`
  ADD CONSTRAINT `assessment_proctoring_logs_ibfk_1` FOREIGN KEY (`result_id`) REFERENCES `assessment_results` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assessment_proctoring_summaries`
--
ALTER TABLE `assessment_proctoring_summaries`
  ADD CONSTRAINT `assessment_proctoring_summaries_ibfk_1` FOREIGN KEY (`result_id`) REFERENCES `assessment_results` (`id`) ON DELETE CASCADE;

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
