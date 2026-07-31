-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2026 at 07:08 AM
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
(132, NULL, 'REGISTER', 'New student registered: babudon2 (STU-1037)', '::1', '2026-07-22 10:27:19'),
(133, NULL, 'RESEND_OTP', 'Resent email verification OTP for warriorbabu402@gmail.com.', '::1', '2026-07-22 10:28:11'),
(134, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-22 10:29:43'),
(135, NULL, 'REGISTER', 'New student registered: sumedh2 (STU-1038)', '::1', '2026-07-22 10:31:26'),
(136, NULL, 'RESEND_OTP', 'Resent email verification OTP for khalikarsumedh07@gmail.com.', '::1', '2026-07-22 10:33:18'),
(137, NULL, 'RESEND_OTP', 'Resent email verification OTP for khalikarsumedh07@gmail.com.', '::1', '2026-07-22 10:35:54'),
(138, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-22 10:36:20'),
(139, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-22 10:36:38'),
(140, NULL, 'REGISTER', 'New student registered: sumedh (STU-1039)', '::1', '2026-07-22 10:38:56'),
(141, NULL, 'REGISTER', 'New student registered: vaibhav1 (STU-1040)', '::1', '2026-07-22 11:07:05'),
(142, NULL, 'REGISTER', 'New student registered: pavan (STU-1041)', '::1', '2026-07-22 11:42:41'),
(143, NULL, 'REGISTER', 'New student registered: rona (STU-1042)', '::1', '2026-07-22 11:44:45'),
(144, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-22 11:57:18'),
(145, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-22 12:11:24'),
(146, NULL, 'REGISTER', 'New student registered: heroic (STU-1043)', '::1', '2026-07-22 12:13:32'),
(147, NULL, 'REGISTER', 'New student registered: nsr (STU-1044)', '::1', '2026-07-22 13:12:45'),
(148, NULL, 'REGISTER', 'New student registered: nikhil (STU-1045)', '::1', '2026-07-22 13:16:46'),
(149, NULL, 'EMAIL_VERIFIED', 'User nikhil verified email successfully via OTP.', '::1', '2026-07-22 13:17:35'),
(150, NULL, 'LOGIN', 'User nikhil logged in successfully as student.', '::1', '2026-07-22 13:17:52'),
(151, NULL, 'LOGOUT', 'User nikhil logged out.', '::1', '2026-07-22 13:18:33'),
(152, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for bettercallsaul9848@gmail.com.', '::1', '2026-07-22 13:19:35'),
(153, NULL, 'REGISTER', 'New student registered: JR. (STU-1046)', '::1', '2026-07-22 13:31:32'),
(154, NULL, 'REGISTER', 'New student registered: sumedh2 (STU-1047)', '::1', '2026-07-22 13:37:48'),
(155, NULL, 'EMAIL_VERIFIED', 'User sumedh2 verified email successfully via OTP.', '::1', '2026-07-22 13:38:19'),
(156, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for khalikarsumedh07@gmail.com.', '::1', '2026-07-22 13:38:48'),
(157, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for khalikarsumedh07@gmail.com.', '::1', '2026-07-22 13:39:24'),
(158, NULL, 'LOGIN', 'User sumedh2 logged in successfully as student.', '::1', '2026-07-22 13:40:14'),
(159, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 12.0%', '::1', '2026-07-22 13:44:53'),
(160, NULL, 'LOGOUT', 'User sumedh2 logged out.', '::1', '2026-07-22 13:56:57'),
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
(172, NULL, 'REGISTER', 'New student registered: praju (STU-1051)', '::1', '2026-07-22 14:32:28'),
(173, NULL, 'EMAIL_VERIFIED', 'User praju verified email successfully via OTP.', '::1', '2026-07-22 14:32:58'),
(174, NULL, 'LOGIN', 'User praju logged in successfully as student.', '::1', '2026-07-22 14:33:47'),
(175, NULL, 'LOGOUT', 'User praju logged out.', '::1', '2026-07-22 14:52:54'),
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
(204, NULL, 'REGISTER', 'New student registered: pavan (STU-1056)', '::1', '2026-07-23 09:44:09'),
(205, NULL, 'EMAIL_VERIFIED', 'User pavan verified email successfully via OTP.', '::1', '2026-07-23 09:46:08'),
(206, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for pavanthote7777@gmail.com.', '::1', '2026-07-23 10:14:51'),
(207, NULL, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user pavan.', '::1', '2026-07-23 10:17:42'),
(208, NULL, 'LOGIN', 'User pavan logged in successfully as student.', '::1', '2026-07-23 10:18:21'),
(209, NULL, 'LOGOUT', 'User pavan logged out.', '::1', '2026-07-23 10:51:52'),
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
(237, NULL, 'REGISTER', 'New student registered: vaishnavi (STU-1059)', '::1', '2026-07-23 13:31:40'),
(238, NULL, 'EMAIL_VERIFIED', 'User vaishnavi verified email successfully via OTP.', '::1', '2026-07-23 13:32:20'),
(239, NULL, 'LOGIN', 'User vaishnavi logged in successfully as student.', '::1', '2026-07-23 13:32:46'),
(240, NULL, 'LOGOUT', 'User vaishnavi logged out.', '::1', '2026-07-23 13:32:59'),
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
(285, NULL, 'LOGIN', 'User sumedh2 logged in successfully as student.', '::1', '2026-07-24 10:36:18'),
(286, NULL, 'ENROLL_COURSE', 'Enrolled in course: Full Stack Web Architecture Capstone', '::1', '2026-07-24 10:55:35'),
(287, NULL, 'LOGOUT', 'User sumedh2 logged out.', '::1', '2026-07-24 10:59:30'),
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
(364, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-27 09:53:26'),
(365, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-27 10:27:00'),
(366, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 16.0%', '::1', '2026-07-27 10:44:19'),
(367, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-27 11:32:18'),
(368, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-27 11:32:49'),
(369, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-27 12:58:35'),
(370, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-27 13:04:00'),
(371, 58, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for marathaedits96@gmail.com.', '::1', '2026-07-27 13:04:24'),
(372, 58, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user encore.exe.', '::1', '2026-07-27 13:06:04'),
(373, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-27 13:06:31'),
(374, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-27 13:21:38'),
(375, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-27 13:33:31'),
(376, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-27 13:37:50'),
(377, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-27 14:34:55'),
(378, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-27 14:35:42'),
(379, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-27 14:39:05'),
(380, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-27 14:44:03'),
(381, 1, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for sudrikyash1@gmail.com.', '::1', '2026-07-27 14:44:26'),
(382, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-27 14:45:30'),
(383, 63, 'ASSESSMENT_CREATED', 'Created assessment rwe (ID: 12)', '::1', '2026-07-27 14:46:23'),
(384, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-27 14:49:03'),
(385, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 10:31:26'),
(386, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-28 13:07:07'),
(387, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 13:07:21'),
(388, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-28 13:17:19'),
(389, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-28 13:17:32'),
(390, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-28 13:22:33'),
(391, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-28 13:22:54'),
(392, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-28 13:27:39'),
(393, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 13:28:40'),
(394, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 0.0%', '::1', '2026-07-28 13:30:19'),
(395, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-28 14:10:27'),
(396, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 14:10:54'),
(397, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-28 14:19:38'),
(398, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-28 14:19:57'),
(399, 63, 'ANNOUNCEMENT_CREATED', 'Created announcement #8: \'Testing\' sent to 30 recipients.', '::1', '2026-07-28 14:20:39'),
(400, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-28 14:24:34'),
(401, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 14:24:49'),
(402, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-28 14:26:20'),
(403, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-28 14:26:30'),
(404, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-28 14:37:37'),
(405, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-28 14:41:59'),
(406, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 18:38:20'),
(407, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-28 18:38:32'),
(408, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 18:38:53'),
(409, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-28 18:39:53'),
(410, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 18:40:15'),
(411, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-28 18:45:50'),
(412, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 18:54:00'),
(413, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-28 18:55:34'),
(414, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 19:01:12'),
(415, 9, 'NOTIFICATION_MARK_ALL_READ', 'Marked all notifications as read.', '127.0.0.1', '2026-07-28 19:30:49'),
(416, 9, 'NOTIFICATION_CLEAR_ALL', 'Cleared all notifications (9 items).', '127.0.0.1', '2026-07-28 19:30:49'),
(417, 58, 'NOTIFICATION_CLEAR_ALL', 'Cleared all notifications (0 items).', '::1', '2026-07-28 19:31:55'),
(418, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-28 21:59:27'),
(419, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 10:05:19'),
(420, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 10:07:32'),
(421, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-29 10:56:18'),
(422, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-29 11:13:12'),
(423, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 11:13:30'),
(424, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 11:21:08'),
(425, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-29 12:01:14'),
(426, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 13:11:58'),
(427, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 13:43:15'),
(428, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 13:44:54'),
(429, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 13:48:16'),
(430, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-29 13:49:20'),
(431, 1, 'SUSPEND_ACCOUNT', 'Admin Skill Bridge Team suspended Student account for Pavan Thote (User ID: 56).', '::1', '2026-07-29 13:49:51'),
(432, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-29 13:49:55'),
(433, NULL, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for pavanthote7777@gmail.com.', '::1', '2026-07-29 13:50:48'),
(434, NULL, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user pavan.', '::1', '2026-07-29 13:54:36'),
(435, NULL, 'LOGIN', 'User pavan logged in successfully as student.', '::1', '2026-07-29 13:54:48'),
(436, NULL, 'NOTIFICATION_MARK_ALL_READ', 'Marked all notifications as read.', '::1', '2026-07-29 13:57:09'),
(437, NULL, 'LOGOUT', 'User pavan logged out.', '::1', '2026-07-29 13:57:19'),
(438, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 13:57:30'),
(439, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 13:59:00'),
(440, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-29 13:59:24'),
(441, 1, 'SYSTEM_SETTING_UPDATE', 'Updated system settings', '::1', '2026-07-29 13:59:38'),
(442, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-29 13:59:41'),
(443, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 13:59:53'),
(444, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 14:00:55'),
(445, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-29 14:01:12'),
(446, 1, 'SYSTEM_SETTING_UPDATE', 'Updated system settings', '::1', '2026-07-29 14:01:28'),
(447, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-29 14:01:39'),
(448, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 14:01:50'),
(449, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 14:02:11'),
(450, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-29 14:02:38'),
(451, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-29 14:03:57'),
(452, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 14:04:08'),
(453, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 14:06:02'),
(454, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-29 14:06:15'),
(455, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-29 14:09:27'),
(456, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-29 14:09:52'),
(457, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-29 14:19:15'),
(458, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-29 14:20:03'),
(459, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 17:27:54'),
(460, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 17:31:04'),
(461, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 18:16:38'),
(462, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 18:19:49'),
(463, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 18:55:36'),
(464, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 18:59:17'),
(465, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 19:17:07'),
(466, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 19:18:06'),
(467, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-29 19:18:18'),
(468, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-29 19:19:16'),
(469, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-29 19:19:33'),
(470, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-29 19:24:46'),
(471, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 19:24:59'),
(472, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 19:31:56'),
(473, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-29 19:32:08'),
(474, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-29 19:37:16'),
(475, NULL, 'REGISTER', 'New student registered: sb (STU-1066)', '::1', '2026-07-29 19:39:15'),
(476, NULL, 'EMAIL_VERIFIED', 'User sb verified email successfully via OTP.', '::1', '2026-07-29 19:39:55'),
(477, NULL, 'LOGIN', 'User sb logged in successfully as student.', '::1', '2026-07-29 19:40:07'),
(478, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 21:13:49'),
(479, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 21:17:51'),
(480, NULL, 'REGISTER', 'New student registered: sb (STU-1067)', '::1', '2026-07-29 21:18:35'),
(481, NULL, 'EMAIL_VERIFIED', 'User sb verified email successfully via OTP.', '::1', '2026-07-29 21:19:30'),
(482, NULL, 'LOGIN', 'User sb logged in successfully as student.', '::1', '2026-07-29 21:19:39'),
(483, NULL, 'LOGOUT', 'User sb logged out.', '::1', '2026-07-29 21:20:39'),
(484, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-29 21:20:59'),
(485, 63, 'ASSESSMENT_CREATED', 'Created assessment testing (ID: 13)', '::1', '2026-07-29 21:25:57'),
(486, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-29 21:26:10'),
(487, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 21:26:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(488, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-29 21:27:12'),
(489, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment JavaScript ES6 Asynchronous Programming (25 MCQs) with score 0.0%', '::1', '2026-07-29 21:27:51'),
(490, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 21:28:27'),
(491, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-29 21:28:49'),
(492, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-29 21:31:06'),
(493, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 21:31:16'),
(494, 58, 'ENROLL_COURSE', 'Enrolled in course: Agile Product Delivery & Scrum', '::1', '2026-07-29 21:32:10'),
(495, 58, 'ENROLL_COURSE', 'Enrolled in course: Linux Command Line Administration', '::1', '2026-07-29 22:15:29'),
(496, 58, 'ENROLL_COURSE', 'Enrolled in course: Relational Database Masterclass: MySQL', '::1', '2026-07-29 22:29:52'),
(497, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-29 23:32:20'),
(498, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-29 23:32:46'),
(499, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-30 09:31:36'),
(500, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-30 10:14:34'),
(501, NULL, 'REGISTER', 'New student registered: Arnav_16 (STU-1068)', '::1', '2026-07-30 10:17:16'),
(502, NULL, 'REGISTER', 'New student registered: Arnav_17 (STU-1069)', '::1', '2026-07-30 10:18:39'),
(503, NULL, 'EMAIL_VERIFIED', 'User Arnav_17 verified email successfully via OTP.', '::1', '2026-07-30 10:19:17'),
(504, NULL, 'LOGIN', 'User Arnav_17 logged in successfully as student.', '::1', '2026-07-30 10:19:44'),
(505, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-30 10:34:13'),
(506, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 0.0%', '::1', '2026-07-30 10:35:02'),
(507, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 20.0%', '::1', '2026-07-30 10:41:26'),
(508, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML5 Semantic Markup & CSS3 Layouts (25 MCQs) with score 4.0%', '::1', '2026-07-30 10:52:46'),
(509, NULL, 'NOTIFICATION_MARK_READ', 'Marked notification #339 as read.', '::1', '2026-07-30 10:53:46'),
(510, NULL, 'NOTIFICATION_MARK_ALL_READ', 'Marked all notifications as read.', '::1', '2026-07-30 10:53:52'),
(511, NULL, 'NOTIFICATION_DELETE', 'Deleted notification #339.', '::1', '2026-07-30 10:54:45'),
(512, NULL, 'NOTIFICATION_DELETE', 'Deleted notification #335.', '::1', '2026-07-30 10:55:46'),
(513, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 4.0%', '::1', '2026-07-30 11:06:05'),
(514, NULL, 'ASSESSMENT_SUBMITTED', 'Completed assessment PHP 8 Core Concepts & PDO Mastery (25 MCQs) with score 12.0%', '::1', '2026-07-30 11:10:34'),
(515, NULL, 'ENROLL_COURSE', 'Enrolled in course: Full Stack Web Architecture Capstone', '::1', '2026-07-30 11:13:38'),
(516, NULL, 'COURSE_COMPLETED', 'Completed course ID #20', '::1', '2026-07-30 11:14:34'),
(517, NULL, 'ENROLL_COURSE', 'Enrolled in course: Practical Cyber Security Defenses', '::1', '2026-07-30 11:16:43'),
(518, NULL, 'ENROLL_COURSE', 'Enrolled in course: Responsive Design with Bootstrap 5', '::1', '2026-07-30 11:24:25'),
(519, NULL, 'COURSE_COMPLETED', 'Completed course ID #19', '::1', '2026-07-30 11:25:13'),
(520, NULL, 'LOGOUT', 'User Arnav_17 logged out.', '::1', '2026-07-30 11:40:23'),
(521, 63, 'FORGOT_PASSWORD_REQUEST', 'Password reset requested for heroicff2727@gmail.com.', '::1', '2026-07-30 11:44:29'),
(522, 63, 'PASSWORD_RESET_SUCCESS', 'Password reset successfully completed for user khansir.', '::1', '2026-07-30 11:45:05'),
(523, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 11:46:33'),
(524, 63, 'ASSESSMENT_CREATED', 'Created assessment setup (ID: 14)', '::1', '2026-07-30 11:58:23'),
(525, 63, 'BULK_EXPORT_QUESTIONS', 'Exported 0 question records.', '::1', '2026-07-30 12:02:10'),
(526, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-30 12:03:21'),
(527, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-30 13:10:13'),
(528, NULL, 'LOGIN', 'User Arnav_17 logged in successfully as student.', '::1', '2026-07-30 13:30:08'),
(529, NULL, 'LOGOUT', 'User Arnav_17 logged out.', '::1', '2026-07-30 13:30:11'),
(530, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 13:30:27'),
(531, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-30 13:32:04'),
(532, NULL, 'LOGIN', 'User Arnav_17 logged in successfully as student.', '::1', '2026-07-30 13:32:14'),
(533, NULL, 'LOGOUT', 'User Arnav_17 logged out.', '::1', '2026-07-30 13:33:52'),
(534, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 13:34:48'),
(535, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-30 13:41:35'),
(536, NULL, 'LOGIN', 'User Arnav_17 logged in successfully as student.', '::1', '2026-07-30 13:41:49'),
(537, NULL, 'NOTIFICATION_MARK_READ', 'Marked notification #342 as read.', '::1', '2026-07-30 13:42:03'),
(538, NULL, 'NOTIFICATION_OPEN', 'Opened notification #342.', '::1', '2026-07-30 13:42:04'),
(539, NULL, 'LOGOUT', 'User Arnav_17 logged out.', '::1', '2026-07-30 13:42:19'),
(540, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 13:42:31'),
(541, 63, 'ANNOUNCEMENT_CREATED', 'Created announcement #9: \'world war 3\' sent to 33 recipients.', '::1', '2026-07-30 13:50:15'),
(542, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-30 13:50:29'),
(543, NULL, 'LOGIN', 'User Arnav_17 logged in successfully as student.', '::1', '2026-07-30 13:50:41'),
(544, NULL, 'NOTIFICATION_MARK_READ', 'Marked notification #375 as read.', '::1', '2026-07-30 13:51:17'),
(545, NULL, 'LOGOUT', 'User Arnav_17 logged out.', '::1', '2026-07-30 13:51:21'),
(546, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 13:51:40'),
(547, 63, 'ANNOUNCEMENT_UPDATED', 'Updated announcement #9: \'world war 3\'.', '::1', '2026-07-30 13:51:58'),
(548, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-30 13:52:01'),
(549, NULL, 'LOGIN', 'User Arnav_17 logged in successfully as student.', '::1', '2026-07-30 13:52:27'),
(550, NULL, 'LOGOUT', 'User Arnav_17 logged out.', '::1', '2026-07-30 13:52:38'),
(551, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 13:52:50'),
(552, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-30 14:01:28'),
(553, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-30 14:01:39'),
(554, 1, 'DATABASE_BACKUP', 'Exported database dump skillbridge_backup_2026_07_30_140308.sql', '::1', '2026-07-30 14:03:08'),
(555, 1, 'SUSPEND_ACCOUNT', 'Admin Skill Bridge Team suspended Student account for Michael Brown (User ID: 9).', '::1', '2026-07-30 14:04:59'),
(556, 1, 'UNSUSPEND_ACCOUNT', 'Admin Skill Bridge Team reactivated Student account for Michael Brown (User ID: 9).', '::1', '2026-07-30 14:05:04'),
(557, 1, 'ANNOUNCEMENT_CREATED', 'Created announcement #10: \'helloa\' sent to 8 recipients.', '::1', '2026-07-30 14:08:56'),
(558, 1, 'SYSTEM_SETTING_UPDATE', 'Updated system settings', '::1', '2026-07-30 14:14:40'),
(559, 1, 'SYSTEM_SETTING_UPDATE', 'Updated system settings', '::1', '2026-07-30 14:14:47'),
(560, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-30 14:14:51'),
(561, NULL, 'LOGIN', 'User Arnav_17 logged in successfully as student.', '::1', '2026-07-30 14:15:12'),
(562, NULL, 'LOGOUT', 'User Arnav_17 logged out.', '::1', '2026-07-30 14:15:31'),
(563, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 14:15:56'),
(564, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-30 14:16:34'),
(565, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-30 14:17:31'),
(566, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-30 14:18:57'),
(567, NULL, 'LOGIN', 'User Arnav_17 logged in successfully as student.', '::1', '2026-07-30 14:19:17'),
(568, NULL, 'NOTIFICATION_OPEN', 'Opened notification #342.', '::1', '2026-07-30 14:19:44'),
(569, NULL, 'NOTIFICATION_OPEN', 'Opened notification #342.', '::1', '2026-07-30 14:19:48'),
(570, NULL, 'LOGOUT', 'User Arnav_17 logged out.', '::1', '2026-07-30 14:20:52'),
(571, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 14:22:31'),
(572, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-30 14:40:36'),
(573, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-30 14:42:44'),
(574, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-30 14:44:24'),
(575, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-30 14:52:19'),
(576, 1, 'LOGOUT', 'User admin logged out.', '::1', '2026-07-30 14:53:53'),
(577, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-30 16:25:05'),
(578, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-30 16:26:39'),
(579, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-30 16:27:04'),
(580, 58, 'ENROLL_COURSE', 'Enrolled in course: testing era', '::1', '2026-07-30 16:27:27'),
(581, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-30 16:28:21'),
(582, NULL, 'LOGIN', 'User sb logged in successfully as student.', '::1', '2026-07-30 16:28:31'),
(583, NULL, 'NOTIFICATION_MARK_ALL_READ', 'Marked all notifications as read.', '::1', '2026-07-30 16:49:23'),
(584, NULL, 'NOTIFICATION_CLEAR_ALL', 'Cleared all notifications (1 items).', '::1', '2026-07-30 16:49:31'),
(585, NULL, 'LOGOUT', 'User sb logged out.', '::1', '2026-07-30 17:20:09'),
(586, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 17:20:30'),
(587, 63, 'ANNOUNCEMENT_DELETED', 'Deleted announcement #9.', '::1', '2026-07-30 17:21:32'),
(588, 63, 'QBANK_CREATED', 'Created Question Bank React Intermediate Bank (ID: 5)', '::1', '2026-07-30 18:41:33'),
(589, 63, 'QBANK_CREATED', 'Created Question Bank React Advanced Bank (ID: 6)', '::1', '2026-07-30 18:49:27'),
(590, 63, 'QBANK_CREATED', 'Created Question Bank CSS Beginner Bank (ID: 34)', '::1', '2026-07-30 19:03:30'),
(591, 63, 'QBANK_CREATED', 'Created Question Bank Angular Beginner Bank (ID: 35)', '::1', '2026-07-30 19:08:05'),
(592, 63, 'QBANK_CREATED', 'Created Question Bank HTML Beginner Bank (ID: 31)', '::1', '2026-07-30 19:36:39'),
(593, 63, 'QBANK_DELETED', 'Deleted Question Bank: HTML Beginner Bank (ID: 31)', '::1', '2026-07-30 19:36:48'),
(594, 63, 'QBANK_CREATED', 'Created Question Bank HTML Expert Bank (ID: 32)', '::1', '2026-07-30 19:37:46'),
(595, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-30 20:53:08'),
(596, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-30 20:53:49'),
(597, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 20:54:11'),
(598, 63, 'BULK_IMPORT_STUDENTS', 'Bulk imported 2 student accounts', '::1', '2026-07-30 21:38:51'),
(599, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-30 21:40:07'),
(600, 71, 'LOGIN', 'User sb logged in successfully as student.', '::1', '2026-07-30 21:40:40'),
(601, 71, 'LOGOUT', 'User sb logged out.', '::1', '2026-07-30 21:44:46'),
(602, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 21:45:11'),
(603, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-30 21:59:41'),
(604, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-30 22:00:00'),
(605, 1, 'ANNOUNCEMENT_DELETED', 'Deleted announcement #10.', '::1', '2026-07-30 22:06:36'),
(606, 1, 'ANNOUNCEMENT_DELETED', 'Deleted announcement #5.', '::1', '2026-07-30 22:06:42'),
(607, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-30 23:50:05'),
(608, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-31 00:06:26'),
(609, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-31 00:06:38'),
(610, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment TypeScript - Beginner (25 MCQs) with score 8.0%', '::1', '2026-07-31 00:07:45'),
(611, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-31 00:07:53'),
(612, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-31 00:08:05'),
(613, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-31 00:13:29'),
(614, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-31 00:13:39'),
(615, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment TypeScript - Beginner (25 MCQs) with score 8.0%', '::1', '2026-07-31 00:14:27'),
(616, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-31 00:14:35'),
(617, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-31 00:14:46'),
(618, 63, 'NOTIFICATION_OPEN', 'Opened notification #390.', '::1', '2026-07-31 00:14:51'),
(619, 63, 'NOTIFICATION_OPEN', 'Opened notification #390.', '::1', '2026-07-31 00:14:51'),
(620, 63, 'NOTIFICATION_OPEN', 'Opened notification #390.', '::1', '2026-07-31 00:14:56'),
(621, 63, 'NOTIFICATION_OPEN', 'Opened notification #390.', '::1', '2026-07-31 00:15:26'),
(622, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-31 00:34:35'),
(623, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-31 00:34:49'),
(624, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment HTML - Beginner (25 MCQs) with score 8.0%', '::1', '2026-07-31 00:35:55'),
(625, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-31 00:36:01'),
(626, 63, 'LOGIN', 'User khansir logged in successfully as faculty.', '::1', '2026-07-31 00:36:15'),
(627, 63, 'NOTIFICATION_OPEN', 'Opened notification #393.', '::1', '2026-07-31 00:36:33'),
(628, 63, 'NOTIFICATION_OPEN', 'Opened notification #393.', '::1', '2026-07-31 00:40:46'),
(629, 63, 'NOTIFICATION_OPEN', 'Opened notification #390.', '::1', '2026-07-31 00:44:15'),
(630, 63, 'NOTIFICATION_OPEN', 'Opened notification #390.', '::1', '2026-07-31 00:48:10'),
(631, 63, 'NOTIFICATION_OPEN', 'Opened notification #393.', '::1', '2026-07-31 00:48:32'),
(632, 63, 'NOTIFICATION_OPEN', 'Opened notification #390.', '::1', '2026-07-31 00:48:40'),
(633, 63, 'NOTIFICATION_OPEN', 'Opened notification #393.', '::1', '2026-07-31 00:54:25'),
(634, 63, 'LOGOUT', 'User khansir logged out.', '::1', '2026-07-31 01:37:57'),
(635, 1, 'LOGIN', 'User admin logged in successfully as admin.', '::1', '2026-07-31 01:38:12'),
(636, 1, 'NOTIFICATION_MARK_ALL_READ', 'Marked all notifications as read.', '::1', '2026-07-31 01:41:47'),
(637, 1, 'NOTIFICATION_MARK_UNREAD', 'Marked notification #80 as unread.', '::1', '2026-07-31 01:41:49'),
(638, 1, 'NOTIFICATION_MARK_READ', 'Marked notification #80 as read.', '::1', '2026-07-31 01:41:52'),
(639, 1, 'NOTIFICATION_MARK_READ', 'Marked notification #80 as read.', '::1', '2026-07-31 01:41:53'),
(640, 58, 'LOGIN', 'User encore.exe logged in successfully as student.', '::1', '2026-07-31 09:35:13'),
(641, 58, 'ASSESSMENT_SUBMITTED', 'Completed assessment TypeScript - Beginner (25 MCQs) with score 4.0%', '::1', '2026-07-31 09:37:38'),
(642, 58, 'NOTIFICATION_OPEN', 'Opened notification #394.', '::1', '2026-07-31 10:11:46'),
(643, 58, 'LOGOUT', 'User encore.exe logged out.', '::1', '2026-07-31 10:33:30'),
(644, 2, 'LOGIN', 'User f_turing logged in successfully as faculty.', '::1', '2026-07-31 10:34:10');

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
  `department` varchar(100) DEFAULT NULL,
  `priority` varchar(20) NOT NULL DEFAULT 'normal',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `link` varchar(255) DEFAULT '#',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `created_by_user_id`, `created_by_name`, `created_by_role`, `title`, `message`, `audience`, `department`, `priority`, `status`, `link`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', 'admin', 'Testing Features', 'Ignore the announcement', 'all', NULL, 'normal', 'active', '#', '2026-07-22 22:39:51', '2026-07-23 14:36:55'),
(2, 1, 'admin', 'admin', 'Testing From Admin Section', 'Ignore this.', 'all', NULL, 'normal', 'active', '#', '2026-07-23 14:24:42', '2026-07-23 14:36:55'),
(6, 63, 'khansir', 'faculty', 'Testing from faculty', 'ignore', 'student', 'Information Technology', 'normal', 'active', '#', '2026-07-24 09:59:02', '2026-07-30 17:29:33'),
(7, 63, 'khansir', 'faculty', 'testing', 'ignore..', 'student', 'Information Technology', 'normal', 'active', '#', '2026-07-26 18:55:25', '2026-07-30 17:29:33'),
(8, 63, 'khansir', 'faculty', 'Testing', '...', 'student', 'Information Technology', 'normal', 'active', '#', '2026-07-28 14:20:39', '2026-07-30 17:29:33');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_reads`
--

CREATE TABLE `announcement_reads` (
  `id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `read_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcement_reads`
--

INSERT INTO `announcement_reads` (`id`, `announcement_id`, `user_id`, `read_at`) VALUES
(2, 10, 63, '2026-07-30 17:43:51'),
(3, 2, 63, '2026-07-30 17:43:54'),
(5, 8, 1, '2026-07-31 01:41:25'),
(6, 7, 1, '2026-07-31 01:41:29'),
(7, 2, 1, '2026-07-31 01:41:53');

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
  `difficulty_level` enum('beginner','intermediate','advanced','professional','expert') NOT NULL DEFAULT 'beginner',
  `status` enum('draft','active','archived') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `question_bank_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`id`, `title`, `description`, `skill_id`, `created_by_faculty_id`, `duration_minutes`, `passing_marks`, `total_marks`, `difficulty_level`, `status`, `created_at`, `question_bank_id`) VALUES
(1, 'HTML - Beginner', 'Standard assessment evaluation for React Core Concepts Bank.', 1, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 18:02:51', 1),
(2, 'CSS - Beginner', 'Standard assessment evaluation for MySQL Query Optimization Bank.', 2, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 18:02:51', 2),
(3, 'JavaScript - Beginner', 'Standard assessment evaluation for Next.js Routing & SSR Bank.', 3, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 18:02:51', 3),
(6, 'Bootstrap - Beginner', 'Bootstrap Beginner level assessment.', 4, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 4),
(7, 'Tailwind CSS - Beginner', 'Tailwind CSS Beginner level assessment.', 5, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 5),
(8, 'React - Beginner', 'React Beginner level assessment.', 6, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 6),
(9, 'Angular - Beginner', 'Angular Beginner level assessment.', 7, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 7),
(10, 'Vue.js - Beginner', 'Vue.js Beginner level assessment.', 8, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 8),
(11, 'jQuery - Beginner', 'jQuery Beginner level assessment.', 9, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 9),
(12, 'TypeScript - Beginner', 'TypeScript Beginner level assessment.', 10, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 10),
(13, 'C - Beginner', 'C Beginner level assessment.', 11, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 11),
(14, 'C++ - Beginner', 'C++ Beginner level assessment.', 12, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 12),
(15, 'Java - Beginner', 'Java Beginner level assessment.', 13, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 13),
(16, 'Python - Beginner', 'Python Beginner level assessment.', 14, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 14),
(17, 'PHP - Beginner', 'PHP Beginner level assessment.', 15, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 15),
(18, 'C# - Beginner', 'C# Beginner level assessment.', 16, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 16),
(19, 'Node.js - Beginner', 'Node.js Beginner level assessment.', 17, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 17),
(20, 'SQL - Beginner', 'SQL Beginner level assessment.', 18, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 18),
(21, 'MySQL - Beginner', 'MySQL Beginner level assessment.', 19, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 19),
(22, 'MongoDB - Beginner', 'MongoDB Beginner level assessment.', 20, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 20),
(23, 'MERN Stack - Beginner', 'MERN Stack Beginner level assessment.', 21, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 21),
(24, 'MEAN Stack - Beginner', 'MEAN Stack Beginner level assessment.', 22, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 22),
(25, 'Laravel - Beginner', 'Laravel Beginner level assessment.', 23, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 23),
(26, 'Django - Beginner', 'Django Beginner level assessment.', 24, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 24),
(27, 'Express.js - Beginner', 'Express.js Beginner level assessment.', 25, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 25),
(28, 'Next.js - Beginner', 'Next.js Beginner level assessment.', 26, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 26),
(29, 'ASP.NET - Beginner', 'ASP.NET Beginner level assessment.', 27, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 27),
(30, 'Spring Boot - Beginner', 'Spring Boot Beginner level assessment.', 28, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 28),
(31, 'Flask - Beginner', 'Flask Beginner level assessment.', 29, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 29),
(32, 'REST API - Beginner', 'REST API Beginner level assessment.', 30, 1, 25, 10, 25, 'beginner', 'active', '2026-07-30 19:19:14', 30);

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
(87, 64, 'Auto Submission', 'Assessment submitted automatically due to exceeding maximum warnings limit.', '2026-07-27 09:52:10'),
(88, 65, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-27 10:26:32'),
(89, 65, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-27 10:26:32'),
(90, 65, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-27 10:27:00'),
(91, 66, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-27 10:41:34'),
(92, 66, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-27 10:41:34'),
(93, 66, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-27 10:44:19'),
(94, 67, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-27 14:37:34'),
(95, 67, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-27 14:37:34'),
(96, 67, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-27 14:38:20'),
(97, 67, 'Full-screen Exit', 'Student exited full-screen mode.', '2026-07-27 14:38:24'),
(98, 67, 'Full-screen Exit', 'Student exited full-screen mode.', '2026-07-27 14:38:45'),
(99, 67, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-27 14:38:49'),
(100, 67, 'Tab Switch', 'Student switched tabs or minimized the browser window.', '2026-07-27 14:38:57'),
(101, 67, 'Auto Submission', 'Assessment submitted automatically due to exceeding maximum warnings limit.', '2026-07-27 14:39:05'),
(102, 68, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-28 13:29:05'),
(103, 68, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-28 13:29:05'),
(104, 68, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-28 13:29:58'),
(105, 68, 'Face Re-calibrated', 'One face presence restored.', '2026-07-28 13:30:01'),
(106, 68, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-28 13:30:19'),
(107, 69, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-29 21:26:53'),
(108, 69, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-29 21:26:53'),
(109, 69, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-29 21:27:12'),
(110, 70, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-29 21:27:36'),
(111, 70, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-29 21:27:36'),
(112, 70, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-29 21:27:51'),
(113, 71, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-30 10:33:51'),
(114, 71, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-30 10:33:51'),
(115, 71, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-30 10:33:54'),
(116, 71, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-30 10:33:56'),
(117, 71, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-30 10:33:58'),
(118, 71, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-30 10:34:01'),
(119, 71, 'Auto Submission', 'Assessment submitted automatically due to exceeding maximum warnings limit.', '2026-07-30 10:34:13'),
(120, 72, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-30 10:34:40'),
(121, 72, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-30 10:34:40'),
(122, 72, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-30 10:34:46'),
(123, 72, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-30 10:34:50'),
(124, 72, 'Tab Switch', 'Student switched tabs or minimized the browser window.', '2026-07-30 10:34:57'),
(125, 72, 'Auto Submission', 'Assessment submitted automatically due to exceeding maximum warnings limit.', '2026-07-30 10:35:02'),
(126, 73, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-30 10:36:57'),
(127, 73, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-30 10:36:57'),
(128, 73, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-30 10:37:42'),
(129, 73, 'Face Re-calibrated', 'One face presence restored.', '2026-07-30 10:38:11'),
(130, 73, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-30 10:41:26'),
(131, 74, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-30 10:51:21'),
(132, 74, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-30 10:51:21'),
(133, 74, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-30 10:52:46'),
(134, 75, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-30 11:05:55'),
(135, 75, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-30 11:05:55'),
(136, 75, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-30 11:06:05'),
(137, 76, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-30 11:07:47'),
(138, 76, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-30 11:07:47'),
(139, 76, 'Window Focus Lost', 'Student focused away from the assessment window.', '2026-07-30 11:09:29'),
(140, 76, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-30 11:09:59'),
(141, 76, 'Face Re-calibrated', 'One face presence restored.', '2026-07-30 11:10:05'),
(142, 76, 'Multiple Faces Detected', 'Multiple faces visible in webcam frame.', '2026-07-30 11:10:26'),
(143, 76, 'Auto Submission', 'Assessment submitted automatically due to exceeding maximum warnings limit.', '2026-07-30 11:10:34'),
(144, 1, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-31 00:07:15'),
(145, 1, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-31 00:07:15'),
(146, 1, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-31 00:07:45'),
(147, 2, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-31 00:13:57'),
(148, 2, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-31 00:13:57'),
(149, 2, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-31 00:14:27'),
(150, 3, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-31 00:35:06'),
(151, 3, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-31 00:35:06'),
(152, 3, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-31 00:35:55'),
(153, 4, 'Assessment Started', 'Student authorized webcam and loaded proctoring environment.', '2026-07-31 09:37:19'),
(154, 4, 'Camera Enabled', 'Webcam stream validated and active.', '2026-07-31 09:37:19'),
(155, 4, 'Assessment Submitted', 'Student submitted the assessment manually.', '2026-07-31 09:37:38');

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
(17, 64, 4, 0, 1, 2, 0, 1, 0, 'High Risk', '2026-07-27 09:52:10'),
(18, 65, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-27 10:27:00'),
(19, 66, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-27 10:44:19'),
(20, 67, 5, 0, 0, 0, 3, 2, 0, 'High Risk', '2026-07-27 14:39:05'),
(21, 68, 1, 0, 0, 1, 0, 0, 0, 'High Risk', '2026-07-28 13:30:19'),
(22, 69, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-29 21:27:12'),
(23, 70, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-29 21:27:51'),
(24, 71, 4, 0, 0, 0, 0, 4, 0, 'High Risk', '2026-07-30 10:34:13'),
(25, 72, 3, 0, 0, 0, 1, 2, 0, 'High Risk', '2026-07-30 10:35:02'),
(26, 73, 1, 0, 0, 1, 0, 0, 0, 'High Risk', '2026-07-30 10:41:26'),
(27, 74, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-30 10:52:46'),
(28, 75, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-30 11:06:05'),
(29, 76, 3, 0, 0, 2, 0, 1, 0, 'High Risk', '2026-07-30 11:10:34'),
(30, 1, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-31 00:07:45'),
(31, 2, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-31 00:14:27'),
(32, 3, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-31 00:35:55'),
(33, 4, 0, 0, 0, 0, 0, 0, 0, 'Low Risk', '2026-07-31 09:37:38');

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
(1, 50, 12, 25, 2, 2.00, 8.00, 'fail', 29, '2026-07-31 00:07:45'),
(2, 50, 12, 25, 2, 2.00, 8.00, 'fail', 29, '2026-07-31 00:14:27'),
(3, 50, 1, 25, 2, 2.00, 8.00, 'fail', 49, '2026-07-31 00:35:55'),
(4, 50, 12, 25, 1, 1.00, 4.00, 'fail', 19, '2026-07-31 09:37:38');

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
(1, 1, 15, 3),
(2, 2, 19, 3),
(3, 3, 3, 3),
(4, 4, 4, 3),
(5, 5, 15, 3),
(6, 6, 30, 3),
(7, 7, 12, 3),
(8, 8, 13, 3),
(9, 9, 1, 3),
(10, 10, 2, 3),
(11, 11, 14, 3),
(12, 12, 25, 3),
(13, 13, 6, 3),
(14, 14, 26, 3),
(15, 15, 25, 3),
(16, 16, 17, 3),
(17, 17, 11, 3),
(18, 18, 21, 3),
(19, 19, 27, 3),
(20, 20, 21, 3);

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
  `display_name` varchar(100) DEFAULT NULL,
  `college_name` varchar(200) NOT NULL DEFAULT 'SkillBridge University',
  `mobile_number` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `department` varchar(100) NOT NULL DEFAULT 'Computer Science',
  `designation` varchar(100) NOT NULL DEFAULT 'Assistant Professor',
  `bio` text DEFAULT NULL,
  `office_location` varchar(100) DEFAULT NULL,
  `experience_years` int(11) DEFAULT 0,
  `id_card_file` varchar(255) DEFAULT NULL,
  `appointment_letter_file` varchar(255) DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approval_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `notif_assessment` tinyint(1) NOT NULL DEFAULT 1,
  `notif_submission` tinyint(1) NOT NULL DEFAULT 1,
  `notif_system` tinyint(1) NOT NULL DEFAULT 1,
  `notif_email` tinyint(1) NOT NULL DEFAULT 1,
  `notif_browser` tinyint(1) NOT NULL DEFAULT 1,
  `priv_profile_visibility` tinyint(1) NOT NULL DEFAULT 1,
  `priv_show_email` tinyint(1) NOT NULL DEFAULT 1,
  `priv_show_mobile` tinyint(1) NOT NULL DEFAULT 1,
  `priv_show_department` tinyint(1) NOT NULL DEFAULT 1,
  `priv_show_designation` tinyint(1) NOT NULL DEFAULT 1,
  `pref_dashboard` varchar(100) NOT NULL DEFAULT 'faculty/dashboard.php',
  `pref_assessment_view` varchar(50) NOT NULL DEFAULT 'grid',
  `pref_language` varchar(50) NOT NULL DEFAULT 'en',
  `pref_timezone` varchar(100) NOT NULL DEFAULT 'Asia/Kolkata'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `user_id`, `employee_code`, `first_name`, `last_name`, `display_name`, `college_name`, `mobile_number`, `avatar`, `department`, `designation`, `bio`, `office_location`, `experience_years`, `id_card_file`, `appointment_letter_file`, `approval_status`, `approval_date`, `approved_by`, `rejection_reason`, `created_at`, `notif_assessment`, `notif_submission`, `notif_system`, `notif_email`, `notif_browser`, `priv_profile_visibility`, `priv_show_email`, `priv_show_mobile`, `priv_show_department`, `priv_show_designation`, `pref_dashboard`, `pref_assessment_view`, `pref_language`, `pref_timezone`) VALUES
(1, 2, 'FAC-001', 'Alan', 'Turing', NULL, 'SkillBridge University', NULL, 'default-avatar.png', 'Computer Science', 'Professor & HOD', NULL, NULL, 0, NULL, NULL, 'approved', NULL, NULL, NULL, '2026-07-20 20:01:07', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'faculty/dashboard.php', 'grid', 'en', 'Asia/Kolkata'),
(2, 3, 'FAC-002', 'Grace', 'Hopper', NULL, 'SkillBridge University', NULL, 'default-avatar.png', 'Software Engineering', 'Associate Professor', NULL, NULL, 0, NULL, NULL, 'approved', NULL, NULL, NULL, '2026-07-20 20:01:07', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'faculty/dashboard.php', 'grid', 'en', 'Asia/Kolkata'),
(3, 4, 'FAC-003', 'Donald', 'Knuth', NULL, 'SkillBridge University', NULL, 'default-avatar.png', 'Computer Science', 'Senior Professor', NULL, NULL, 0, NULL, NULL, 'approved', NULL, NULL, NULL, '2026-07-20 20:01:07', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'faculty/dashboard.php', 'grid', 'en', 'Asia/Kolkata'),
(4, 5, 'FAC-004', 'Ada', 'Lovelace', NULL, 'SkillBridge University', NULL, 'default-avatar.png', 'Information Technology', 'Assistant Professor', NULL, NULL, 0, NULL, NULL, 'approved', NULL, NULL, NULL, '2026-07-20 20:01:07', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'faculty/dashboard.php', 'grid', 'en', 'Asia/Kolkata'),
(5, 6, 'FAC-005', 'Linus', 'Torvalds', NULL, 'SkillBridge University', NULL, 'default-avatar.png', 'Systems Engineering', 'Associate Professor', NULL, NULL, 0, NULL, NULL, 'approved', NULL, NULL, NULL, '2026-07-20 20:01:07', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'faculty/dashboard.php', 'grid', 'en', 'Asia/Kolkata'),
(9, 61, 'FAC-TEST-01', 'Vikram', 'Sarabhai', NULL, 'IISc Bangalore', '+91 9876543210', 'default-avatar.png', 'Computer Science', 'Senior Professor', NULL, NULL, 12, NULL, NULL, 'approved', '2026-07-23 15:03:06', 1, NULL, '2026-07-23 15:03:06', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'faculty/dashboard.php', 'grid', 'en', 'Asia/Kolkata'),
(10, 62, 'FAC-TEST-02', 'Reject', 'Applicant', NULL, 'Test College', NULL, 'default-avatar.png', 'Information Technology', 'Assistant Professor', NULL, NULL, 0, NULL, NULL, 'rejected', NULL, NULL, 'Incomplete verification documents uploaded.', '2026-07-23 15:03:15', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'faculty/dashboard.php', 'grid', 'en', 'Asia/Kolkata'),
(11, 63, 'FAC-1063', 'Khan', 'Sir', NULL, 'Khan Global Studies', '', 'default-avatar.png', 'Information Technology', 'HOD', NULL, NULL, 0, NULL, NULL, 'approved', '2026-07-23 19:16:04', 1, NULL, '2026-07-23 19:03:55', 1, 1, 0, 1, 1, 1, 1, 1, 0, 0, 'faculty/students.php', 'grid', 'fr', 'Asia/Kolkata');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `recipient_type` varchar(50) NOT NULL DEFAULT 'admin',
  `user_role` enum('student','faculty','admin') NOT NULL DEFAULT 'student',
  `category` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'New',
  `read_status` varchar(50) NOT NULL DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `student_id`, `recipient_type`, `user_role`, `category`, `rating`, `subject`, `message`, `status`, `read_status`, `created_at`) VALUES
(1, 27, NULL, 'admin', 'student', 'Skill Assessments', 5, NULL, 'Automated test feedback entry: The 5-tier assessment system is highly effective.', 'New', 'unread', '2026-07-20 19:14:24'),
(2, 29, NULL, 'admin', 'student', 'Skill Assessments', 5, NULL, 'tttttttt', 'New', 'unread', '2026-07-21 04:38:02'),
(3, 27, NULL, 'admin', 'student', 'General Feedback', 5, NULL, 'hi it very nice', 'New', 'unread', '2026-07-21 05:24:03'),
(4, 36, NULL, 'admin', 'student', 'General Feedback', 5, NULL, 'Great job Developers ....!', 'New', 'unread', '2026-07-21 21:39:15'),
(5, 36, NULL, 'admin', 'student', 'General Feedback', 5, NULL, 'hii', 'New', 'unread', '2026-07-21 22:04:23'),
(6, 50, NULL, 'admin', 'student', 'General Feedback', 5, NULL, 'good', 'New', 'unread', '2026-07-22 08:59:27'),
(7, 58, 50, 'admin', 'student', 'General Feedback', 5, NULL, 'Testing From Student section', 'New', 'unread', '2026-07-23 17:14:47'),
(8, 63, NULL, 'admin', 'faculty', 'General Feedback', 5, NULL, 'hii', 'New', 'unread', '2026-07-26 13:25:53'),
(9, 58, 50, 'admin', 'student', 'Skill Gap Analysis', 5, NULL, 'xyz', 'New', 'unread', '2026-07-27 09:12:38'),
(10, 58, 50, 'admin', 'student', 'Skill Gap Analysis', 5, NULL, 'xyz', 'New', 'unread', '2026-07-27 09:12:45'),
(11, 58, 50, 'admin', 'student', 'Skill Assessments', 1, NULL, 'tesing', 'New', 'unread', '2026-07-29 18:42:54'),
(12, 58, 50, 'admin', 'student', 'General Feedback', 5, NULL, 'hello', 'New', 'unread', '2026-07-29 18:53:40'),
(13, 58, 50, 'admin', 'student', 'General Feedback', 5, NULL, 'hii', 'New', 'unread', '2026-07-29 19:00:54'),
(14, 69, 57, 'admin', 'student', 'Personalized Roadmap', 2, NULL, 'hello world', 'New', 'unread', '2026-07-30 06:02:17'),
(15, 69, 57, 'admin', 'student', 'Personalized Roadmap', 2, NULL, 'hello world', 'New', 'unread', '2026-07-30 06:02:23'),
(16, 69, 57, 'admin', 'student', 'Personalized Roadmap', 2, NULL, 'hello world', 'New', 'unread', '2026-07-30 06:02:29'),
(17, 69, 57, 'admin', 'student', 'Personalized Roadmap', 2, NULL, 'hello world', 'New', 'unread', '2026-07-30 06:02:36'),
(18, 69, 57, 'admin', 'student', 'Personalized Roadmap', 2, NULL, 'hello world', 'New', 'unread', '2026-07-30 06:02:42'),
(19, 63, NULL, 'admin', 'faculty', 'General Feedback', 5, NULL, 'ghtg', 'New', 'unread', '2026-07-30 08:58:09'),
(20, 63, NULL, 'admin', 'faculty', 'General Feedback', 5, NULL, 'ghtg', 'New', 'unread', '2026-07-30 08:58:15'),
(21, 63, NULL, 'admin', 'faculty', 'General Feedback', 5, NULL, 'ghtg', 'New', 'unread', '2026-07-30 08:58:21'),
(22, 63, NULL, 'admin', 'faculty', 'Bug Report', 5, NULL, 'gfghf', 'New', 'unread', '2026-07-30 08:59:08'),
(23, 63, NULL, 'admin', 'faculty', 'Feature Request', 5, NULL, 'hgggfss', 'New', 'unread', '2026-07-30 09:03:18'),
(26, 67, 55, 'admin', 'student', 'General Feedback', 5, '', 'testing', 'Email Sent', 'read', '2026-07-30 11:49:09'),
(27, 67, 55, 'faculty', 'student', 'General Feedback', 1, 'nothing', 'Hello', 'Resolved', 'read', '2026-07-30 11:50:03');

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
  `duration_seconds` int(11) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `module_name`, `title`, `description`, `video_url`, `duration_minutes`, `duration_seconds`, `sort_order`, `created_at`) VALUES
(1, 1, 'Module 1: Introduction & Fundamentals', '1. PHP 8 Introduction & Environment Setup', 'Install PHP 8, configure your development environment, and write your first PHP script.', 'https://www.youtube.com/embed/OK_JCtrrv-c', 277, 16599, 1, '2026-07-22 02:33:28'),
(2, 1, 'Module 2: Practical Concepts & Implementation', '2. PHP Functions, Arrays & OOP Basics', 'Master PHP functions, arrays, loops, and an introduction to object-oriented programming concepts.', 'https://www.youtube.com/embed/pWG7ajC_OVo', 7, 425, 2, '2026-07-22 02:33:28'),
(3, 1, 'Module 3: Advanced Optimization & Project', '3. PHP & MySQL ??? Building a Full Web App', 'Build a complete PHP 8 CRUD application connected to MySQL with sessions, forms, and validation.', 'https://www.youtube.com/embed/3DMMPA3uxBo', 30, 1800, 3, '2026-07-22 02:33:28'),
(4, 2, 'Module 1: Introduction & Fundamentals', '1. SQL & MySQL Fundamentals ??? Queries & Tables', 'Learn SQL syntax, how to create databases, tables, and run basic SELECT queries in MySQL.', 'https://www.youtube.com/embed/7S_tz1z_5bA', 190, 11419, 1, '2026-07-22 02:33:28'),
(5, 2, 'Module 2: Practical Concepts & Implementation', '2. Joins, Subqueries & Aggregate Functions', 'Master INNER JOIN, LEFT JOIN, GROUP BY, HAVING, and complex subqueries in MySQL.', 'https://www.youtube.com/embed/p3qvj9hO_Bo', 56, 3383, 2, '2026-07-22 02:33:28'),
(6, 2, 'Module 3: Advanced Optimization & Project', '3. Database Design, Indexes & Stored Procedures', 'Design normalized relational schemas, use indexes for performance, and write stored procedures and triggers.', 'https://www.youtube.com/embed/ER8oKX5myE0', 120, 7171, 3, '2026-07-22 02:33:28'),
(7, 3, 'Module 1: Introduction & Fundamentals', '1. Modern JavaScript ES6 ??? Let, Const, Arrow Functions & Destructuring', 'Understand ES6+ fundamentals: let/const, template literals, arrow functions, and destructuring.', 'https://www.youtube.com/embed/NCwa_xi0Uuc', 50, 3005, 1, '2026-07-22 02:33:28'),
(8, 3, 'Module 2: Practical Concepts & Implementation', '2. Promises, Async/Await & the Fetch API', 'Handle asynchronous JavaScript with Promises, async/await, and fetch data from REST APIs.', 'https://www.youtube.com/embed/DHvZLI7Db8E', 12, 691, 2, '2026-07-22 02:33:28'),
(9, 3, 'Module 3: Advanced Optimization & Project', '3. JavaScript Modules, Classes & Modern Tooling', 'Work with ES6 modules, classes, iterators, generators, and modern bundling with Webpack/Vite.', 'https://www.youtube.com/embed/lI1ae4REbFM', 741, 44483, 3, '2026-07-22 02:33:28'),
(10, 4, 'Module 1: Introduction & Fundamentals', '1. Bootstrap 5 ??? Grid System & Utility Classes', 'Set up Bootstrap 5, learn the 12-column grid system, breakpoints, and core utility classes.', 'https://www.youtube.com/embed/4sosXZsdy-s', 79, 4728, 1, '2026-07-22 02:33:28'),
(11, 4, 'Module 2: Practical Concepts & Implementation', '2. Bootstrap 5 Components ??? Navbar, Cards & Forms', 'Build responsive navbars, cards, modals, forms, and interactive components with Bootstrap 5.', 'https://www.youtube.com/embed/rQryOSyfXmI', 87, 5249, 2, '2026-07-22 02:33:28'),
(12, 4, 'Module 3: Advanced Optimization & Project', '3. Bootstrap 5 ??? Building a Complete Responsive Website', 'Apply Bootstrap 5 to build a full responsive landing page with custom CSS overrides.', 'https://www.youtube.com/embed/Jyvffr3aCp0', 71, 4274, 3, '2026-07-22 02:33:28'),
(13, 5, 'Module 1: Introduction & Fundamentals', '1. OWASP Top 10 ??? Understanding Web Vulnerabilities', 'Explore the OWASP Top 10 most critical web application security risks and how attackers exploit them.', 'https://www.youtube.com/embed/t0IT914i3TU', 15, 900, 1, '2026-07-22 02:33:28'),
(14, 5, 'Module 2: Practical Concepts & Implementation', '2. SQL Injection, XSS & CSRF Attacks & Defenses', 'Deep dive into SQL Injection, Cross-Site Scripting (XSS), and CSRF with hands-on defense techniques.', 'https://www.youtube.com/embed/WtHnT73NaaQ', 128, 7702, 2, '2026-07-22 02:33:28'),
(15, 5, 'Module 3: Advanced Optimization & Project', '3. HTTPS, Authentication Security & Security Headers', 'Implement HTTPS, secure password hashing, JWT authentication, and critical HTTP security headers.', 'https://www.youtube.com/embed/F5KJVuii0Yw', 89, 5329, 3, '2026-07-22 02:33:28'),
(16, 6, 'Module 1: Introduction & Fundamentals', '1. REST API Fundamentals & HTTP Methods in PHP', 'Understand REST principles, HTTP verbs (GET, POST, PUT, DELETE), and build your first PHP API endpoint.', 'https://www.youtube.com/embed/OEWXbpUMODk', 32, 1937, 1, '2026-07-22 02:33:28'),
(17, 6, 'Module 2: Practical Concepts & Implementation', '2. PHP REST API ??? CRUD Operations & JSON Responses', 'Implement full CRUD operations in a PHP REST API with proper JSON responses and status codes.', 'https://www.youtube.com/embed/eyvRc9XSqMw', 25, 1500, 2, '2026-07-22 02:33:28'),
(18, 6, 'Module 3: Advanced Optimization & Project', '3. PHP REST API ??? Authentication, JWT & Security', 'Secure your PHP REST API with JWT tokens, API keys, rate limiting, and CORS configuration.', 'https://www.youtube.com/embed/T-Pum2TraX4', 18, 1097, 3, '2026-07-22 02:33:28'),
(19, 7, 'Module 1: Introduction & Fundamentals', '1. Arrays, Linked Lists & Big O Notation', 'Master arrays, linked lists, stacks, queues, and understand time/space complexity with Big O notation.', 'https://www.youtube.com/embed/BBpAmxU_NQo', 79, 4722, 1, '2026-07-22 02:33:28'),
(20, 7, 'Module 2: Practical Concepts & Implementation', '2. Trees, Graphs & Sorting Algorithms', 'Explore binary trees, BSTs, graphs, BFS/DFS traversal, and implement sorting algorithms from scratch.', 'https://www.youtube.com/embed/pkYVOmU3MgA', 751, 45050, 2, '2026-07-22 02:33:28'),
(21, 7, 'Module 3: Advanced Optimization & Project', '3. Dynamic Programming & Algorithm Design Patterns', 'Solve complex problems using dynamic programming, memoization, greedy algorithms, and divide-and-conquer.', 'https://www.youtube.com/embed/oBt53YbR9Kk', 310, 18602, 3, '2026-07-22 02:33:28'),
(22, 8, 'Module 1: Introduction & Fundamentals', '1. OOP Principles ??? Classes, Encapsulation & Inheritance', 'Master the four OOP pillars: encapsulation, abstraction, inheritance, and polymorphism with real examples.', 'https://www.youtube.com/embed/pTB0EiLXUC8', 8, 454, 1, '2026-07-22 02:33:28'),
(23, 8, 'Module 2: Practical Concepts & Implementation', '2. SOLID Principles & Clean Architecture', 'Apply SOLID design principles to write maintainable, extensible, and testable object-oriented code.', 'https://www.youtube.com/embed/_jDNAkmINF0', 25, 1500, 2, '2026-07-22 02:33:28'),
(24, 8, 'Module 3: Advanced Optimization & Project', '3. Design Patterns ??? Creational, Structural & Behavioral', 'Implement the most important GoF design patterns including Singleton, Factory, Observer, and Strategy.', 'https://www.youtube.com/embed/tv-_1er1mWI', 11, 664, 3, '2026-07-22 02:33:28'),
(25, 9, 'Module 1: Introduction & Fundamentals', '1. Git Fundamentals ??? Init, Commit, Branch & Merge', 'Install Git, initialize repositories, make commits, create branches, and perform merges.', 'https://www.youtube.com/embed/RGOj5yH7evk', 69, 4110, 1, '2026-07-22 02:33:28'),
(26, 9, 'Module 2: Practical Concepts & Implementation', '2. GitHub ??? Remote Repos, Pull Requests & Code Reviews', 'Push to GitHub, work with remote repositories, fork projects, and collaborate via Pull Requests.', 'https://www.youtube.com/embed/SWYqp7iY_Tc', 33, 1962, 2, '2026-07-22 02:33:28'),
(27, 9, 'Module 3: Advanced Optimization & Project', '3. Git Workflows ??? Rebasing, Cherry-pick & CI/CD Integration', 'Master advanced Git workflows: rebase, cherry-pick, Git Flow, and integrate with GitHub Actions CI/CD.', 'https://www.youtube.com/embed/Uszj_k0DGsg', 41, 2442, 3, '2026-07-22 02:33:28'),
(28, 10, 'Module 1: Introduction & Fundamentals', '1. UX Design Principles ??? Research, Wireframing & Prototyping', 'Learn UX research methods, create wireframes, and build interactive prototypes using Figma.', 'https://www.youtube.com/embed/c9Wg6RyOxjU', 15, 900, 1, '2026-07-22 02:33:28'),
(29, 10, 'Module 2: Practical Concepts & Implementation', '2. Figma Masterclass ??? Components, Auto Layout & Design Systems', 'Build reusable Figma components, master Auto Layout, and create a consistent design system.', 'https://www.youtube.com/embed/FTFaQWZBqQ8', 24, 1463, 2, '2026-07-22 02:33:28'),
(30, 10, 'Module 3: Advanced Optimization & Project', '3. UI Design ??? Color Theory, Typography & Accessibility', 'Apply color theory, typography best practices, and WCAG accessibility guidelines to UI design.', 'https://www.youtube.com/embed/yNDgFK2Jj1E', 27, 1647, 3, '2026-07-22 02:33:28'),
(31, 11, 'Module 1: Introduction & Fundamentals', '1. Python Fundamentals ??? Variables, Loops & Functions', 'Learn Python syntax, data types, control flow, functions, and working with modules.', 'https://www.youtube.com/embed/rfscVS0vtbw', 267, 16012, 1, '2026-07-22 02:33:28'),
(32, 11, 'Module 2: Practical Concepts & Implementation', '2. Python ??? File Automation, OS Module & Scripting', 'Automate file system tasks, work with the os module, write scripts, and use regular expressions.', 'https://www.youtube.com/embed/s3lrgez5pls', 25, 1500, 2, '2026-07-22 02:33:28'),
(33, 11, 'Module 3: Advanced Optimization & Project', '3. Python ??? Web Scraping, APIs & Task Automation', 'Use Requests, BeautifulSoup, and schedule libraries to build powerful automation pipelines.', 'https://www.youtube.com/embed/ycdptosWgFc', 30, 1800, 3, '2026-07-22 02:33:28'),
(34, 12, 'Module 1: Introduction & Fundamentals', '1. Docker Introduction ??? Containers, Images & Dockerfile', 'Understand containerization, install Docker, build images with Dockerfile, and run containers.', 'https://www.youtube.com/embed/pg19Z8LL06w', 68, 4059, 1, '2026-07-22 02:33:28'),
(35, 12, 'Module 2: Practical Concepts & Implementation', '2. Docker Compose ??? Multi-Container Applications', 'Define and run multi-container applications with Docker Compose, volumes, and networking.', 'https://www.youtube.com/embed/DM65_JyGxCo', 16, 985, 2, '2026-07-22 02:33:28'),
(36, 12, 'Module 3: Advanced Optimization & Project', '3. Docker in Production ??? Registry, CI/CD & Best Practices', 'Push images to Docker Hub, integrate with CI/CD pipelines, and apply production-grade best practices.', 'https://www.youtube.com/embed/3c-iBn73dDE', 166, 9975, 3, '2026-07-22 02:33:28'),
(37, 13, 'Module 1: Introduction & Fundamentals', '1. React Fundamentals ??? Components, Props & JSX', 'Set up a React project with Vite, create components, pass props, and understand JSX syntax.', 'https://www.youtube.com/embed/RVFAyFWO4go', 529, 31746, 1, '2026-07-22 02:33:28'),
(38, 13, 'Module 2: Practical Concepts & Implementation', '2. React Hooks ??? useState, useEffect & Custom Hooks', 'Master React Hooks: useState for state management, useEffect for side effects, and build custom hooks.', 'https://www.youtube.com/embed/O6P86uwfdR0', 16, 945, 2, '2026-07-22 02:33:28'),
(39, 13, 'Module 3: Advanced Optimization & Project', '3. React Router, Context API & Building a Full App', 'Implement React Router v6, global state with Context API, and build a complete React application.', 'https://www.youtube.com/embed/w7ejDZ8SWv8', 109, 6527, 3, '2026-07-22 02:33:28'),
(40, 14, 'Module 1: Introduction & Fundamentals', '1. Cloud Computing & AWS Core Services Overview', 'Understand cloud computing models (IaaS, PaaS, SaaS), AWS global infrastructure, and core services.', 'https://www.youtube.com/embed/NhDYbskXRgc', 858, 51472, 1, '2026-07-22 02:33:28'),
(41, 14, 'Module 2: Practical Concepts & Implementation', '2. AWS EC2, S3, RDS & IAM Hands-On', 'Launch EC2 instances, store files in S3, configure RDS databases, and set up IAM roles and policies.', 'https://www.youtube.com/embed/ulprqHHWlng', 25, 1500, 2, '2026-07-22 02:33:28'),
(42, 14, 'Module 3: Advanced Optimization & Project', '3. AWS Deployment ??? Elastic Beanstalk, Lambda & CloudFormation', 'Deploy apps with Elastic Beanstalk, run serverless functions with Lambda, and use CloudFormation IaC.', 'https://www.youtube.com/embed/SOTamWNgDKc', 806, 48360, 3, '2026-07-22 02:33:28'),
(43, 15, 'Module 1: Introduction & Fundamentals', '1. Software Testing Fundamentals ??? Unit, Integration & E2E', 'Understand different testing types, the testing pyramid, and write your first unit tests.', 'https://www.youtube.com/embed/r9HdJ8P6GQI', 40, 2385, 1, '2026-07-22 02:33:28'),
(44, 15, 'Module 2: Practical Concepts & Implementation', '2. Test-Driven Development (TDD) ??? Red, Green, Refactor', 'Practice the TDD cycle: write failing tests first, make them pass, then refactor with confidence.', 'https://www.youtube.com/embed/Jv2uxzhPFl4', 13, 774, 2, '2026-07-22 02:33:28'),
(45, 15, 'Module 3: Advanced Optimization & Project', '3. PHPUnit & Jest ??? Testing in CI/CD Pipelines', 'Write tests with PHPUnit for PHP and Jest for JavaScript, then integrate testing into CI/CD pipelines.', 'https://www.youtube.com/embed/ajiAl5UNsZQ', 30, 1800, 3, '2026-07-22 02:33:28'),
(46, 16, 'Module 1: Introduction & Fundamentals', '1. Node.js Fundamentals ??? Event Loop, Modules & npm', 'Understand the Node.js event loop, CommonJS modules, npm ecosystem, and working with the file system.', 'https://www.youtube.com/embed/fBNz5xF-Kx4', 90, 5407, 1, '2026-07-22 02:33:28'),
(47, 16, 'Module 2: Practical Concepts & Implementation', '2. Express.js ??? Routing, Middleware & REST APIs', 'Build a REST API with Express.js, use middleware, handle routing, and work with request/response objects.', 'https://www.youtube.com/embed/L72fhGm1tfE', 74, 4441, 2, '2026-07-22 02:33:28'),
(48, 16, 'Module 3: Advanced Optimization & Project', '3. Node.js ??? Authentication, MongoDB & Deployment', 'Add JWT authentication, connect to MongoDB with Mongoose, and deploy your Node.js app to production.', 'https://www.youtube.com/embed/ENrzD9HAZK4', 16, 980, 3, '2026-07-22 02:33:28'),
(49, 17, 'Module 1: Introduction & Fundamentals', '1. Linux Command Line Basics ??? Navigation, Files & Permissions', 'Navigate the Linux file system, manage files and directories, and understand user permissions and chmod.', 'https://www.youtube.com/embed/ZtqBQ68cfJc', 300, 18016, 1, '2026-07-22 02:33:28'),
(50, 17, 'Module 2: Practical Concepts & Implementation', '2. Linux Shell Scripting ??? Bash Automation & Cron Jobs', 'Write Bash shell scripts, use variables, loops, and conditionals to automate repetitive system tasks.', 'https://www.youtube.com/embed/tK9Oc6AEnR4', 48, 2877, 2, '2026-07-22 02:33:28'),
(51, 17, 'Module 3: Advanced Optimization & Project', '3. Linux System Administration ??? Processes, Networking & Services', 'Manage Linux processes, configure networking, work with systemd services, and monitor system health.', 'https://www.youtube.com/embed/wBp0Rb-ZJak', 444, 26633, 3, '2026-07-22 02:33:28'),
(52, 18, 'Module 1: Introduction & Fundamentals', '1. Agile Fundamentals ??? Manifesto, Principles & Mindset', 'Understand the Agile Manifesto, its 12 principles, and how Agile thinking transforms software delivery.', 'https://www.youtube.com/embed/8eVXTyIZ1Hs', 6, 382, 1, '2026-07-22 02:33:28'),
(53, 18, 'Module 2: Practical Concepts & Implementation', '2. Scrum Framework ??? Roles, Events & Artifacts', 'Learn Scrum roles (Product Owner, Scrum Master, Dev Team), ceremonies, and artifacts like the Sprint Backlog.', 'https://www.youtube.com/embed/2Vt7Ik8Ublw', 5, 271, 2, '2026-07-22 02:33:28'),
(54, 18, 'Module 3: Advanced Optimization & Project', '3. Agile Estimation, Kanban & Scaling Frameworks', 'Use story points, Kanban boards, velocity tracking, and explore scaling frameworks like SAFe and LeSS.', 'https://www.youtube.com/embed/iVaFVa7HYj4', 6, 332, 3, '2026-07-22 02:33:28'),
(55, 19, 'Module 1: Introduction & Fundamentals', '1. Ethical Hacking & Penetration Testing Fundamentals', 'Learn ethical hacking methodology, set up Kali Linux, and understand the penetration testing lifecycle.', 'https://www.youtube.com/embed/3Kq1MIfTWCE', 891, 53473, 1, '2026-07-22 02:33:28'),
(56, 19, 'Module 2: Practical Concepts & Implementation', '2. Network Security ??? Firewalls, VPNs & Intrusion Detection', 'Configure firewalls, understand VPN protocols, analyze network traffic with Wireshark, and set up IDS.', 'https://www.youtube.com/embed/qiQR5rTSshw', 565, 33888, 2, '2026-07-22 02:33:28'),
(57, 19, 'Module 3: Advanced Optimization & Project', '3. Incident Response, Cryptography & Security Operations', 'Build a security operations workflow, implement cryptography, and establish incident response procedures.', 'https://www.youtube.com/embed/AQDCe585Lnc', 5, 280, 3, '2026-07-22 02:33:28'),
(58, 20, 'Module 1: Introduction & Fundamentals', '1. Full Stack Architecture ??? Planning, Tech Stack & Project Setup', 'Design a full stack web application, choose the right tech stack, and set up the complete project structure.', 'https://www.youtube.com/embed/7CqJlxBYj-M', 107, 6422, 1, '2026-07-22 02:33:28'),
(59, 20, 'Module 2: Practical Concepts & Implementation', '2. Full Stack Development ??? Backend API, Database & Frontend Integration', 'Build the backend API, connect to the database, and integrate the frontend with the REST API.', 'https://www.youtube.com/embed/ngc9gnGgUdA', 76, 4540, 2, '2026-07-22 02:33:28');

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
(47, 3, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(48, 4, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(49, 5, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(50, 6, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(51, 2, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
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
(76, 12, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(77, 10, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(78, 21, 'Testing Features', 'Ignore the announcement', '#', 0, 'announcement', 1, NULL, NULL, '2026-07-22 22:39:51'),
(80, 1, 'Testing From Admin Section', 'Ignore this.', '#', 1, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(83, 3, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(84, 4, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(85, 5, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(86, 6, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(87, 2, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
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
(109, 12, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(110, 10, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
(111, 21, 'Testing From Admin Section', 'Ignore this.', '#', 0, 'announcement', 2, NULL, NULL, '2026-07-23 14:24:42'),
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
(292, 4, 'Student Quiz Submission: JavaScript ES6 Asynchronous Programming', 'Student Encore Abj completed assessment \'JavaScript ES6 Asynchronous Programming\' with a score of 0.0% on 27 Jul 2026, 09:30 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 1, 'assessment', NULL, NULL, NULL, '2026-07-27 09:30:02'),
(293, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Encore Abj completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 0.0% on 27 Jul 2026, 09:31 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 1, 'assessment', NULL, NULL, NULL, '2026-07-27 09:31:07'),
(294, 5, 'Student Quiz Submission: HTML5 Semantic Markup & CSS3 Layouts', 'Student Encore Abj completed assessment \'HTML5 Semantic Markup & CSS3 Layouts\' with a score of 4.0% on 27 Jul 2026, 09:52 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 1, 'assessment', NULL, NULL, NULL, '2026-07-27 09:52:10'),
(295, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Encore Abj completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 0.0% on 27 Jul 2026, 10:27 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 1, 'assessment', NULL, NULL, NULL, '2026-07-27 10:27:00'),
(296, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Encore Abj completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 16.0% on 27 Jul 2026, 10:44 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 1, 'assessment', NULL, NULL, NULL, '2026-07-27 10:44:19'),
(297, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Encore Abj completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 0.0% on 27 Jul 2026, 02:39 PM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 1, 'assessment', NULL, NULL, NULL, '2026-07-27 14:39:05'),
(299, 5, 'Student Quiz Submission: HTML5 Semantic Markup & CSS3 Layouts', 'Student Encore Abj completed assessment \'HTML5 Semantic Markup & CSS3 Layouts\' with a score of 0.0% on 28 Jul 2026, 01:30 PM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 1, 'assessment', NULL, NULL, NULL, '2026-07-28 13:30:19'),
(301, 10, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(302, 11, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(303, 12, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(304, 13, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(305, 14, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(306, 15, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(307, 16, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(308, 17, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(309, 18, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(310, 19, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(311, 20, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(312, 21, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(313, 22, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(314, 23, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(315, 24, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(316, 25, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(317, 26, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(328, 64, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(329, 65, 'New Announcement', 'khansir published a new announcement: Testing', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/notification.php', 0, 'announcement', 8, 63, 'faculty', '2026-07-28 14:20:39'),
(332, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Encore Abj completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 0.0% on 29 Jul 2026, 09:27 PM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 1, 'assessment', NULL, NULL, NULL, '2026-07-29 21:27:12'),
(333, 4, 'Student Quiz Submission: JavaScript ES6 Asynchronous Programming', 'Student Encore Abj completed assessment \'JavaScript ES6 Asynchronous Programming\' with a score of 0.0% on 29 Jul 2026, 09:27 PM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 1, 'assessment', NULL, NULL, NULL, '2026-07-29 21:27:51'),
(334, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Arnav Macharekar completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 0.0% on 30 Jul 2026, 10:34 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=57', 1, 'assessment', NULL, NULL, NULL, '2026-07-30 10:34:13'),
(336, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Arnav Macharekar completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 0.0% on 30 Jul 2026, 10:35 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=57', 1, 'assessment', NULL, NULL, NULL, '2026-07-30 10:35:02'),
(337, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Arnav Macharekar completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 20.0% on 30 Jul 2026, 10:41 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=57', 1, 'assessment', NULL, NULL, NULL, '2026-07-30 10:41:26'),
(338, 5, 'Student Quiz Submission: HTML5 Semantic Markup & CSS3 Layouts', 'Student Arnav Macharekar completed assessment \'HTML5 Semantic Markup & CSS3 Layouts\' with a score of 4.0% on 30 Jul 2026, 10:52 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=57', 1, 'assessment', NULL, NULL, NULL, '2026-07-30 10:52:46'),
(340, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Arnav Macharekar completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 4.0% on 30 Jul 2026, 11:06 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=57', 1, 'assessment', NULL, NULL, NULL, '2026-07-30 11:06:05'),
(341, 2, 'Student Quiz Submission: PHP 8 Core Concepts & PDO Mastery', 'Student Arnav Macharekar completed assessment \'PHP 8 Core Concepts & PDO Mastery\' with a score of 12.0% on 30 Jul 2026, 11:10 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=57', 1, 'assessment', NULL, NULL, NULL, '2026-07-30 11:10:34'),
(385, 5, 'New Student Feedback', 'New feedback submitted by student Skill Bridge in department Information Technology.', '#', 1, 'feedback', NULL, 67, 'student', '2026-07-30 17:20:03'),
(387, 2, 'Student Quiz Submission: TypeScript - Beginner', 'Student Encore Abj completed assessment \'TypeScript - Beginner\' with a score of 8.0% on 31 Jul 2026, 12:07 AM.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50', 0, 'assessment', NULL, NULL, NULL, '2026-07-31 00:07:45'),
(389, 5, 'Assessment Completed: TypeScript - Beginner', 'Encore Abj has successfully completed the \"TypeScript - Beginner\". Skill: TypeScript | Difficulty: Beginner (Level 1) | Score: 2/25 (8.0%) | Completed: 31 Jul 2026 • 12:14 AM', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50&result_id=2', 0, 'assessment', NULL, NULL, NULL, '2026-07-31 00:14:27'),
(390, 63, 'Assessment Completed: TypeScript - Beginner', 'Encore Abj has successfully completed the \"TypeScript - Beginner\". Skill: TypeScript | Difficulty: Beginner (Level 1) | Score: 2/25 (8.0%) | Completed: 31 Jul 2026 • 12:14 AM', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50&result_id=2', 1, 'assessment', NULL, NULL, NULL, '2026-07-31 00:14:27'),
(392, 5, 'Assessment Completed: HTML - Beginner', 'Encore Abj has successfully completed the \"HTML - Beginner\". Skill: HTML | Difficulty: Beginner (Level 1) | Score: 2/25 (8.0%) | Completed: 31 Jul 2026 • 12:35 AM', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50&result_id=3', 0, 'assessment', NULL, NULL, NULL, '2026-07-31 00:35:55'),
(393, 63, 'Assessment Completed: HTML - Beginner', 'Encore Abj has successfully completed the \"HTML - Beginner\". Skill: HTML | Difficulty: Beginner (Level 1) | Score: 2/25 (8.0%) | Completed: 31 Jul 2026 • 12:35 AM', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50&result_id=3', 1, 'assessment', NULL, NULL, NULL, '2026-07-31 00:35:55'),
(394, 58, 'New Course Recommendation', 'We recommended course \'Git & GitHub Collaboration Workflow\' to help improve your HTML skill.', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/student/recommendations.php', 1, 'recommendation', NULL, NULL, NULL, '2026-07-31 00:35:55'),
(395, 5, 'Assessment Completed: TypeScript - Beginner', 'Encore Abj has successfully completed the \"TypeScript - Beginner\". Skill: TypeScript | Difficulty: Beginner (Level 1) | Score: 1/25 (4.0%) | Completed: 31 Jul 2026 • 09:37 AM', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50&result_id=4', 0, 'assessment', NULL, NULL, NULL, '2026-07-31 09:37:38'),
(396, 63, 'Assessment Completed: TypeScript - Beginner', 'Encore Abj has successfully completed the \"TypeScript - Beginner\". Skill: TypeScript | Difficulty: Beginner (Level 1) | Score: 1/25 (4.0%) | Completed: 31 Jul 2026 • 09:37 AM', 'http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/faculty/evaluate.php?student_id=50&result_id=4', 0, 'assessment', NULL, NULL, NULL, '2026-07-31 09:37:38');

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
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question_bank_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `correct_option` enum('A','B','C','D') NOT NULL,
  `marks` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_bank_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `marks`, `created_at`) VALUES
(1, 1, 'What does HTML stand for?', 'Hyper Text Markup Language', 'Hyperlinks and Text Markup Language', 'Home Tool Markup Language', 'Hyper Tool Markup Language', 'A', 1, '2026-07-30 19:13:05'),
(2, 1, 'Which tag is used to define the largest heading in HTML?', '<h6>', '<heading>', '<h1>', '<head>', 'C', 1, '2026-07-30 19:13:05'),
(3, 1, 'What is the correct HTML element for inserting a line break?', '<break>', '<br>', '<lb>', '<next>', 'B', 1, '2026-07-30 19:13:05'),
(4, 1, 'Which HTML attribute is used to define inline styles?', 'styles', 'style', 'class', 'font', 'B', 1, '2026-07-30 19:13:05'),
(5, 1, 'What is the correct HTML element to define important text?', '<important>', '<i>', '<strong>', '<b>', 'C', 1, '2026-07-30 19:13:05'),
(6, 1, 'Which character is used to indicate an end tag in HTML?', '<', '*', '^', '/', 'D', 1, '2026-07-30 19:13:05'),
(7, 1, 'How do you create a hyperlink in HTML?', '<a href=\'url\'>Link</a>', '<a url=\'url\'>Link</a>', '<a>url</a>', '<link href=\'url\'>Link</link>', 'A', 1, '2026-07-30 19:13:05'),
(8, 1, 'Which tag is used to create a numbered list in HTML?', '<ul>', '<list>', '<ol>', '<dl>', 'C', 1, '2026-07-30 19:13:05'),
(9, 1, 'Which tag is used to create a bulleted list in HTML?', '<ul>', '<list>', '<ol>', '<dl>', 'A', 1, '2026-07-30 19:13:05'),
(10, 1, 'What is the correct HTML for making a checkbox?', '<input type=\'checkbox\'>', '<checkbox>', '<input type=\'check\'>', '<check>', 'A', 1, '2026-07-30 19:13:05'),
(11, 1, 'What is the correct HTML for making a text input field?', '<input type=\'textfield\'>', '<input type=\'text\'>', '<textinput>', '<textfield>', 'B', 1, '2026-07-30 19:13:05'),
(12, 1, 'Which HTML element is used to display an image?', '<img>', '<image>', '<picture>', '<src>', 'A', 1, '2026-07-30 19:13:05'),
(13, 1, 'Which attribute specifies an alternate text for an image if it cannot be displayed?', 'title', 'src', 'alt', 'longdesc', 'C', 1, '2026-07-30 19:13:05'),
(14, 1, 'Which HTML element defines the title of a document displayed in the browser tab?', '<head>', '<title>', '<meta>', '<header>', 'B', 1, '2026-07-30 19:13:05'),
(15, 1, 'Which tag is used to create a table row?', '<td>', '<th>', '<table>', '<tr>', 'D', 1, '2026-07-30 19:13:05'),
(16, 1, 'Which HTML5 tag defines a navigation section?', '<nav>', '<navigation>', '<menu>', '<nav-link>', 'A', 1, '2026-07-30 19:13:05'),
(17, 1, 'Which HTML5 tag defines a footer section?', '<bottom>', '<footer>', '<foot>', '<section-footer>', 'B', 1, '2026-07-30 19:13:05'),
(18, 1, 'What is the correct HTML element for a dropdown list?', '<select>', '<input type=\'dropdown\'>', '<list>', '<dropdown>', 'A', 1, '2026-07-30 19:13:05'),
(19, 1, 'Which tag is used to define a multiline text input area?', '<input type=\'textarea\'>', '<textarea>', '<text>', '<input type=\'textbox\'>', 'B', 1, '2026-07-30 19:13:05'),
(20, 1, 'What is the correct HTML for inserting a background image?', '<body style=\'background-image:url(background.gif)\'>', '<body bg=\'background.gif\'>', '<background img=\'background.gif\'>', '<img src=\'background.gif\' bg>', 'A', 1, '2026-07-30 19:13:05'),
(21, 1, 'Which HTML element is used to define semantic header content?', '<header>', '<head>', '<heading>', '<section-header>', 'A', 1, '2026-07-30 19:13:05'),
(22, 1, 'Which HTML attribute specifies that an input field must be filled out before submitting?', 'validate', 'placeholder', 'required', 'mandatory', 'C', 1, '2026-07-30 19:13:05'),
(23, 1, 'What is the default value of the type attribute for a `<button>` element inside a form?', 'button', 'reset', 'submit', 'input', 'C', 1, '2026-07-30 19:13:05'),
(24, 1, 'Which tag is used to define a table header cell?', '<tr>', '<td>', '<th>', '<thead-cell>', 'C', 1, '2026-07-30 19:13:05'),
(25, 1, 'Which HTML element is used to group inline-elements for styling?', '<div>', '<span>', '<section>', '<style>', 'B', 1, '2026-07-30 19:13:05'),
(26, 2, 'What does CSS stand for?', 'Colorful Style Sheets', 'Creative Style Sheets', 'Cascading Style Sheets', 'Computer Style Sheets', 'C', 1, '2026-07-30 19:13:05'),
(27, 2, 'Where in an HTML document is the correct place to refer to an external style sheet?', 'In the <body> section', 'At the end of the document', 'In the <head> section', 'In the <title> section', 'C', 1, '2026-07-30 19:13:05'),
(28, 2, 'Which HTML tag is used to define an internal style sheet?', '<script>', '<css>', '<style>', '<link>', 'C', 1, '2026-07-30 19:13:05'),
(29, 2, 'Which HTML attribute is used to define inline styles?', 'styles', 'style', 'class', 'font', 'B', 1, '2026-07-30 19:13:05'),
(30, 2, 'Which CSS property is used to change the background color of an element?', 'color', 'bgcolor', 'background-color', 'color-bg', 'C', 1, '2026-07-30 19:13:05'),
(31, 2, 'Which CSS property is used to change the text color of an element?', 'text-color', 'fgcolor', 'color', 'font-color', 'C', 1, '2026-07-30 19:13:05'),
(32, 2, 'Which CSS property controls the text size?', 'font-style', 'font-size', 'text-size', 'text-style', 'B', 1, '2026-07-30 19:13:05'),
(33, 2, 'What is the correct CSS syntax for making all the <p> elements bold?', 'p {text-size:bold;}', 'p {font-weight:bold;}', 'p {font-style:bold;}', 'p {font-size:bold;}', 'B', 1, '2026-07-30 19:13:05'),
(34, 2, 'How do you display hyperlinks without an underline in CSS?', 'a {decoration:no-underline;}', 'a {text-decoration:none;}', 'a {underline:none;}', 'a {text-decoration:no-underline;}', 'B', 1, '2026-07-30 19:13:05'),
(35, 2, 'Which property is used to change the font of an element?', 'font-family', 'font-style', 'font-type', 'font-name', 'A', 1, '2026-07-30 19:13:05'),
(36, 2, 'How do you make the text bold in CSS?', 'font:bold;', 'font-weight:bold;', 'font-style:bold;', 'text-style:bold;', 'B', 1, '2026-07-30 19:13:05'),
(37, 2, 'Which CSS property controls the border size and style?', 'border', 'border-width', 'border-style', 'border-color', 'A', 1, '2026-07-30 19:13:05'),
(38, 2, 'Which CSS property is used to set space inside the border of an element?', 'margin', 'padding', 'spacing', 'border-spacing', 'B', 1, '2026-07-30 19:13:05'),
(39, 2, 'Which CSS property is used to set space outside the border of an element?', 'margin', 'padding', 'spacing', 'border-spacing', 'A', 1, '2026-07-30 19:13:05'),
(40, 2, 'How do you select an element with id \'demo\' in CSS?', '.demo', '#demo', 'demo', '*demo', 'B', 1, '2026-07-30 19:13:05'),
(41, 2, 'How do you select elements with class name \'test\' in CSS?', '#test', 'test', '.test', '*test', 'C', 1, '2026-07-30 19:13:05'),
(42, 2, 'What is the default value of the position property in CSS?', 'relative', 'fixed', 'absolute', 'static', 'D', 1, '2026-07-30 19:13:05'),
(43, 2, 'Which CSS property is used to align text to the center?', 'text-align:center', 'align:center', 'text-center', 'align-text:center', 'A', 1, '2026-07-30 19:13:05'),
(44, 2, 'Which property is used to create space between the HTML list bullet and the item text?', 'margin', 'padding', 'padding-left', 'spacing', 'C', 1, '2026-07-30 19:13:05'),
(45, 2, 'Which CSS property specifies the stack order of an element?', 'stack-index', 'z-index', 'x-index', 'order', 'B', 1, '2026-07-30 19:13:05'),
(46, 2, 'How do you group multiple selectors in CSS to apply the same style?', 'Separate them with a plus sign', 'Separate them with a comma', 'Separate them with a space', 'Separate them with a semicolon', 'B', 1, '2026-07-30 19:13:05'),
(47, 2, 'Which CSS property is used to make a grid layout?', 'display:flex', 'display:block', 'display:grid', 'layout:grid', 'C', 1, '2026-07-30 19:13:05'),
(48, 2, 'Which CSS property makes an element a flexbox container?', 'display:inline', 'display:flex', 'flex:container', 'display:block-flex', 'B', 1, '2026-07-30 19:13:05'),
(49, 2, 'Which property is used to change the left margin of an element?', 'margin-left', 'padding-left', 'left-margin', 'indent', 'A', 1, '2026-07-30 19:13:05'),
(50, 2, 'What CSS selector matches all elements on a page?', '#all', '.all', '*', 'body', 'C', 1, '2026-07-30 19:13:05'),
(51, 3, 'Which HTML tag is used to embed JavaScript code?', '<script>', '<javascript>', '<js>', '<codeing>', 'A', 1, '2026-07-30 19:13:05'),
(52, 3, 'What is the correct syntax for writing \'Hello World\' to the browser console?', 'print(\'Hello World\')', 'console.log(\'Hello World\')', 'log.console(\'Hello World\')', 'browser.write(\'Hello World\')', 'B', 1, '2026-07-30 19:13:05'),
(53, 3, 'How do you declare a variable in JavaScript?', 'var myVar;', 'let myVar;', 'const myVar = 10;', 'All of the above', 'D', 1, '2026-07-30 19:13:05'),
(54, 3, 'Which operator is used to assign a value to a variable in JavaScript?', '*', '=', '==', '===', 'B', 1, '2026-07-30 19:13:05'),
(55, 3, 'What is the correct syntax to create a function in JavaScript?', 'function myFunction()', 'def myFunction()', 'function:myFunction()', 'create myFunction()', 'A', 1, '2026-07-30 19:13:05'),
(56, 3, 'How do you call a function named \'myFunction\'?', 'call myFunction()', 'myFunction()', 'run myFunction()', 'execute myFunction()', 'B', 1, '2026-07-30 19:13:05'),
(57, 3, 'How do you write an IF statement in JavaScript?', 'if i = 5 then', 'if (i == 5)', 'if i == 5 then', 'if i = 5', 'B', 1, '2026-07-30 19:13:05'),
(58, 3, 'Which operator represents strict equality (matches both value and type)?', '==', '=', '===', '!=', 'C', 1, '2026-07-30 19:13:05'),
(59, 3, 'How does a FOR loop start in JavaScript?', 'for (i = 0; i <= 5; i++)', 'for (i = 0; i <= 5)', 'for (i <= 5; i++)', 'for i = 1 to 5', 'A', 1, '2026-07-30 19:13:05'),
(60, 3, 'What is the correct way to write a JavaScript array?', 'var colors = 1:(\'red\'), 2:(\'green\')', 'var colors = [\'red\', \'green\', \'blue\']', 'var colors = (1:\'red\', 2:\'green\')', 'var colors = \'red\', \'green\', \'blue\'', 'B', 1, '2026-07-30 19:13:05'),
(61, 3, 'How do you write a comment in JavaScript?', '\' This is a comment', '// This is a comment', '<!-- This is a comment -->', '/* This is a comment', 'B', 1, '2026-07-30 19:13:05'),
(62, 3, 'Which built-in method returns the length of a string?', 'length()', 'size()', 'length', 'count', 'C', 1, '2026-07-30 19:13:05'),
(63, 3, 'How do you round the number 7.25 to the nearest integer in JavaScript?', 'Math.round(7.25)', 'Math.rnd(7.25)', 'round(7.25)', 'Math.floor(7.25)', 'A', 1, '2026-07-30 19:13:05'),
(64, 3, 'Which event occurs when a user clicks on an HTML element?', 'onmouseover', 'onchange', 'onclick', 'onmouseclick', 'C', 1, '2026-07-30 19:13:05'),
(65, 3, 'How do you select an HTML element by its ID in JavaScript?', 'document.getElementByClass(\'demo\')', 'document.getElementById(\'demo\')', 'document.select(\'demo\')', 'document.findId(\'demo\')', 'B', 1, '2026-07-30 19:13:05'),
(66, 3, 'What is the data type of the value `true` in JavaScript?', 'String', 'Number', 'Boolean', 'Null', 'C', 1, '2026-07-30 19:13:05'),
(67, 3, 'Which method adds a new element to the end of an array?', 'push()', 'pop()', 'shift()', 'unshift()', 'A', 1, '2026-07-30 19:13:05'),
(68, 3, 'Which method removes the last element from an array?', 'push()', 'pop()', 'shift()', 'unshift()', 'B', 1, '2026-07-30 19:13:05'),
(69, 3, 'What will `typeof \'Hello\'` return in JavaScript?', 'object', 'string', 'String', 'undefined', 'B', 1, '2026-07-30 19:13:05'),
(70, 3, 'Which operator represents logical AND?', '&&', '||', '!', '&', 'A', 1, '2026-07-30 19:13:05'),
(71, 3, 'Which operator represents logical OR?', '&&', '||', '!', '|', 'B', 1, '2026-07-30 19:13:05'),
(72, 3, 'How do you check if a variable is NOT a number (NaN) in JavaScript?', 'isNaN(x)', 'isNotNumber(x)', 'checkNaN(x)', 'typeof x !== \'number\'', 'A', 1, '2026-07-30 19:13:05'),
(73, 3, 'What is the default value of an uninitialized variable?', 'null', 'NaN', 'undefined', '0', 'C', 1, '2026-07-30 19:13:05'),
(74, 3, 'How do you create an object in JavaScript?', 'var obj = {};', 'var obj = [];', 'var obj = ()', 'var obj = new Object;', 'A', 1, '2026-07-30 19:13:05'),
(75, 3, 'Which method parses a JSON string into a JavaScript object?', 'JSON.stringify()', 'JSON.parse()', 'JSON.objectify()', 'JSON.convert()', 'B', 1, '2026-07-30 19:13:05'),
(76, 4, 'What is Bootstrap?', 'A database management system', 'A front-end CSS framework for responsive design', 'A JavaScript runtime environment', 'A server-side programming language', 'B', 1, '2026-07-30 19:13:05'),
(77, 4, 'Which Bootstrap class provides a full-width container spanning the entire viewport width?', '.container', '.container-fluid', '.container-full', '.container-viewport', 'B', 1, '2026-07-30 19:13:05'),
(78, 4, 'The Bootstrap grid system is based on how many columns?', '6 columns', '10 columns', '12 columns', '24 columns', 'C', 1, '2026-07-30 19:13:05'),
(79, 4, 'Which class is used to create a layout row in Bootstrap?', '.row-container', '.grid-row', '.row', '.col-row', 'C', 1, '2026-07-30 19:13:05'),
(80, 4, 'Which class prefix is used to target medium devices (tablet/desktop >= 768px) in Bootstrap grid?', '.col-sm-', '.col-md-', '.col-lg-', '.col-xl-', 'B', 1, '2026-07-30 19:13:05'),
(81, 4, 'Which Bootstrap class is used to style a standard button?', '.button', '.btn', '.btn-control', '.btn-style', 'B', 1, '2026-07-30 19:13:05'),
(82, 4, 'Which Bootstrap class is used to create a primary blue button?', '.btn-blue', '.btn-main', '.btn-primary', '.btn-info', 'C', 1, '2026-07-30 19:13:05'),
(83, 4, 'Which Bootstrap class makes an image responsive (fluid size)?', '.img-responsive', '.img-fluid', '.img-fit', '.img-fluid-responsive', 'B', 1, '2026-07-30 19:13:05'),
(84, 4, 'Which Bootstrap utility class centers text?', '.text-center', '.align-center', '.center-text', '.text-align-center', 'A', 1, '2026-07-30 19:13:05'),
(85, 4, 'Which class creates a rounded badge in Bootstrap?', '.badge', '.tag', '.label', '.badge-rounded', 'A', 1, '2026-07-30 19:13:05'),
(86, 4, 'Which utility class sets a top margin of size 3 in Bootstrap?', '.m-3', '.mt-3', '.margin-top-3', '.pt-3', 'B', 1, '2026-07-30 19:13:05'),
(87, 4, 'Which utility class sets left and right padding of size 4 in Bootstrap?', '.px-4', '.py-4', '.p-xy-4', '.pl-4', 'A', 1, '2026-07-30 19:13:05'),
(88, 4, 'Which Bootstrap class is used to create a navigation bar?', '.nav', '.navbar', '.navigation', '.nav-bar', 'B', 1, '2026-07-30 19:13:05'),
(89, 4, 'Which class makes a table striping background rows in Bootstrap?', '.table-striped', '.table-rows', '.table-bg', '.table-stripes', 'A', 1, '2026-07-30 19:13:05'),
(90, 4, 'Which class creates a border around a table in Bootstrap?', '.table-border', '.table-bordered', '.table-outline', '.table-grid', 'B', 1, '2026-07-30 19:13:05'),
(91, 4, 'Which Bootstrap class hides an element on all viewports?', '.hide', '.d-none', '.invisible', '.display-none', 'B', 1, '2026-07-30 19:13:05'),
(92, 4, 'Which class is used to create a card component in Bootstrap?', '.card', '.panel', '.box', '.card-container', 'A', 1, '2026-07-30 19:13:05'),
(93, 4, 'What class is used to style a card header inside a card?', '.card-top', '.card-title', '.card-header', '.card-head', 'C', 1, '2026-07-30 19:13:05'),
(94, 4, 'Which Bootstrap class is used to style form input fields?', '.form-control', '.form-input', '.input-control', '.form-field', 'A', 1, '2026-07-30 19:13:05'),
(95, 4, 'Which Bootstrap class creates a flex container?', '.flex-container', '.d-flex', '.display-flex', '.flexbox', 'B', 1, '2026-07-30 19:13:05'),
(96, 4, 'Which alert class creates a green success notification box?', '.alert-green', '.alert-success', '.alert-info', '.alert-confirm', 'B', 1, '2026-07-30 19:13:05'),
(97, 4, 'Which Bootstrap class is used to build dropdown menus?', '.dropdown', '.select-menu', '.drop-menu', '.dropdown-list', 'A', 1, '2026-07-30 19:13:05'),
(98, 4, 'Which utility class sets background color to dark in Bootstrap?', '.bg-dark', '.bg-black', '.dark-mode', '.color-dark', 'A', 1, '2026-07-30 19:13:05'),
(99, 4, 'Which utility class changes text color to white in Bootstrap?', '.text-white', '.text-light', '.color-white', '.fg-white', 'A', 1, '2026-07-30 19:13:05'),
(100, 4, 'Which Bootstrap class is used to create responsive page layout columns?', '.col', '.grid-col', '.column', '.grid-cell', 'A', 1, '2026-07-30 19:13:05'),
(101, 5, 'What is Tailwind CSS?', 'A pre-designed template library', 'A utility-first CSS framework for rapid UI styling', 'A component framework similar to Bootstrap', 'A JavaScript library for responsive grid layouts', 'B', 1, '2026-07-30 19:13:05'),
(102, 5, 'How do you apply a background color of red with 500 intensity in Tailwind CSS?', 'bg-red', 'bg-red-500', 'background-red-500', 'color-red-500', 'B', 1, '2026-07-30 19:13:05'),
(103, 5, 'Which Tailwind utility class sets text alignment to center?', 'text-center', 'align-center', 'text-align-center', 'font-center', 'A', 1, '2026-07-30 19:13:05'),
(104, 5, 'Which Tailwind class makes text bold?', 'font-bold', 'text-bold', 'font-weight-bold', 'bold', 'A', 1, '2026-07-30 19:13:05'),
(105, 5, 'How do you add padding of size 4 on all sides of an element in Tailwind?', 'p-4', 'padding-4', 'pad-all-4', 'px-4-py-4', 'A', 1, '2026-07-30 19:13:05'),
(106, 5, 'Which utility class sets a top margin of size 6 in Tailwind?', 'margin-top-6', 'mt-6', 'm-t-6', 'pt-6', 'B', 1, '2026-07-30 19:13:05'),
(107, 5, 'Which class sets horizontal padding (left and right) of size 8 in Tailwind?', 'py-8', 'pl-8-pr-8', 'px-8', 'p-x-8', 'C', 1, '2026-07-30 19:13:05'),
(108, 5, 'How do you set a custom width of 50% on an element in Tailwind?', 'w-1/2', 'w-half', 'width-50', 'w-50%', 'A', 1, '2026-07-30 19:13:05'),
(109, 5, 'How do you set an element\'s CSS display to flex in Tailwind?', 'display-flex', 'flexbox', 'flex', 'd-flex', 'C', 1, '2026-07-30 19:13:05'),
(110, 5, 'Which Tailwind utility class sets the text color to gray with 800 intensity?', 'text-gray', 'color-gray-800', 'text-gray-800', 'fg-gray-800', 'C', 1, '2026-07-30 19:13:05'),
(111, 5, 'Which utility class defines a border around an element in Tailwind?', 'border', 'border-1', 'outline-border', 'show-border', 'A', 1, '2026-07-30 19:13:05'),
(112, 5, 'How do you set border radius to fully rounded (pill shape) in Tailwind?', 'rounded-circle', 'rounded-full', 'border-round-full', 'rounded-pill', 'B', 1, '2026-07-30 19:13:05'),
(113, 5, 'Which utility class sets height of an element to 100% of the viewport height?', 'h-screen', 'h-full', 'h-viewport', 'height-screen', 'A', 1, '2026-07-30 19:13:05'),
(114, 5, 'Which Tailwind class makes an element static or block-level hidden?', 'hidden', 'd-none', 'hide', 'invisible', 'A', 1, '2026-07-30 19:13:05'),
(115, 5, 'Which utility class adds shadow to an element in Tailwind?', 'box-shadow', 'shadow', 'drop-shadow', 'shadow-sm', 'B', 1, '2026-07-30 19:13:05'),
(116, 5, 'Which Tailwind responsive prefix targets screens wider than 1024px?', 'md:', 'lg:', 'xl:', 'desktop:', 'B', 1, '2026-07-30 19:13:05'),
(117, 5, 'How do you specify horizontal spacing between inline child items using flexbox in Tailwind?', 'space-x-4', 'gap-x-4', 'spacing-x-4', 'items-space-4', 'A', 1, '2026-07-30 19:13:05'),
(118, 5, 'Which utility class centers flex items vertically?', 'items-center', 'justify-center', 'content-center', 'align-center', 'A', 1, '2026-07-30 19:13:05'),
(119, 5, 'Which utility class centers flex items horizontally inside a column layout?', 'justify-center', 'items-center', 'content-center', 'align-center', 'A', 1, '2026-07-30 19:13:05'),
(120, 5, 'Which Tailwind utility class sets cursor to pointer when hovering?', 'cursor-hand', 'cursor-pointer', 'hover-pointer', 'pointer', 'B', 1, '2026-07-30 19:13:05'),
(121, 5, 'How do you change background color to blue-500 on hover in Tailwind CSS?', 'hover:bg-blue-500', 'bg-blue-500:hover', 'hover-bg-blue-500', 'onhover:bg-blue-500', 'A', 1, '2026-07-30 19:13:05'),
(122, 5, 'Which utility sets text size to extra large in Tailwind?', 'text-xl', 'text-lg', 'font-xl', 'text-size-xl', 'A', 1, '2026-07-30 19:13:05'),
(123, 5, 'Which utility sets text style to italic in Tailwind?', 'font-italic', 'italic', 'text-italic', 'style-italic', 'B', 1, '2026-07-30 19:13:05'),
(124, 5, 'Which utility class sets font family to monospaced in Tailwind?', 'font-mono', 'font-sans', 'font-serif', 'monospace', 'A', 1, '2026-07-30 19:13:05'),
(125, 5, 'Which utility sets element visibility to hidden without changing document layout flow?', 'invisible', 'hidden', 'opacity-0', 'hide', 'A', 1, '2026-07-30 19:13:05'),
(126, 6, 'What is React?', 'A server-side programming language', 'A JavaScript library for building user interfaces', 'A relational database management tool', 'An execution runtime environment', 'B', 1, '2026-07-30 19:13:05'),
(127, 6, 'What is JSX in React?', 'A JavaScript extension that allows writing HTML-like code inside JS', 'A new relational database querying language', 'A styling template engine for CSS inline rules', 'A deployment pipeline framework', 'A', 1, '2026-07-30 19:13:05'),
(128, 6, 'How do you pass data from a parent component to a child component in React?', 'Using state variables', 'Using props attributes', 'Using action callbacks', 'Using local storage', 'B', 1, '2026-07-30 19:13:05'),
(129, 6, 'Which React Hook is used to manage local state in functional components?', 'useEffect', 'useContext', 'useReducer', 'useState', 'D', 1, '2026-07-30 19:13:05'),
(130, 6, 'Which Hook is used to perform side effects in functional React components?', 'useState', 'useContext', 'useEffect', 'useRef', 'C', 1, '2026-07-30 19:13:05'),
(131, 6, 'In React, how do you handle browser click events on a button?', 'onclick={handler}', 'onClick={handler}', 'onClick=handler()', 'on-click={handler}', 'B', 1, '2026-07-30 19:13:05'),
(132, 6, 'What is the purpose of the key prop when rendering list elements in React?', 'To styled individual list elements differently', 'To help React identify which items have changed, been added, or removed', 'To encrypt list data from being read in the DOM', 'To bind list item indexes to form state', 'B', 1, '2026-07-30 19:13:05'),
(133, 6, 'How many HTML elements can a standard React component return?', 'Exactly one root element', 'Unlimited elements without wrapper constraints', 'Maximum 3 elements', 'Only block-level elements', 'A', 1, '2026-07-30 19:13:05'),
(134, 6, 'What is the correct syntax for referencing a state variable named \'count\' initialized using useState Hook?', 'const [count, setCount] = useState(0);', 'const count = useState(0);', 'const {count} = useState(0);', 'const countState = useState[count](0);', 'A', 1, '2026-07-30 19:13:05'),
(135, 6, 'What is the Virtual DOM in React?', 'A virtual emulator browser interface', 'A lightweight, in-memory representation of the real DOM', 'An encryption layer for secure state variables', 'A browser cache for CSS stylesheets', 'B', 1, '2026-07-30 19:13:05'),
(136, 6, 'How do you write a comment inside a JSX block?', '// Comment', '/* Comment */', '{/* Comment */}', '<!-- Comment -->', 'C', 1, '2026-07-30 19:13:05'),
(137, 6, 'What is a React Fragment?', 'A modular component file type', 'A container element to group children without adding extra node layers to DOM', 'A debugging tool for state changes', 'A CSS styling template library', 'B', 1, '2026-07-30 19:13:05'),
(138, 6, 'How do you initialize a default state value in React functional components?', 'Pass it as an argument to useState()', 'Set it in class constructors', 'Pass it as a prop from layout', 'Declare it globally using var', 'A', 1, '2026-07-30 19:13:05'),
(139, 6, 'Which method is used to update state initialized by `const [name, setName] = useState(\'\')`?', 'name = \'New Name\'', 'setName(\'New Name\')', 'updateState(\'name\', \'New Name\')', 'name.update(\'New Name\')', 'B', 1, '2026-07-30 19:13:05'),
(140, 6, 'What happens when a React component\'s state or props change?', 'The component is destroyed', 'The browser window reloads', 'The component re-renders to reflect changes', 'Nothing happens until manually refreshed', 'C', 1, '2026-07-30 19:13:05'),
(141, 6, 'Which hook allows functional components to access context variables?', 'useContext', 'useState', 'useEffect', 'useReducer', 'A', 1, '2026-07-30 19:13:05'),
(142, 6, 'What does the dependency array in `useEffect(callback, deps)` do?', 'Declares internal helper functions', 'Specifies when the effect callback should re-run based on dependency updates', 'Restricts component render loops', 'Loads external CSS imports', 'B', 1, '2026-07-30 19:13:05'),
(143, 6, 'How do you render a component conditionally in React?', 'Using standard if-else statement outside JSX', 'Using ternary operator inside JSX', 'Using logical AND (&&) operator inside JSX', 'All of the above', 'D', 1, '2026-07-30 19:13:05'),
(144, 6, 'What are controlled components in React?', 'Components managed by external CSS layout grid', 'Form inputs whose value is controlled by React state', 'Components restricted to Admin roles', 'Components optimized for virtual memory caching', 'B', 1, '2026-07-30 19:13:05'),
(145, 6, 'Which command is commonly used to create a new React application?', 'npm create-react-app app-name', 'npx create-react-app app-name', 'react-cli create app-name', 'node create-react app-name', 'B', 1, '2026-07-30 19:13:05'),
(146, 6, 'What does it mean if the useEffect dependency array is empty `[]`?', 'The effect runs on every render', 'The effect runs only once after the component mounts', 'The effect is disabled completely', 'The component will throw a syntax error', 'B', 1, '2026-07-30 19:13:05'),
(147, 6, 'How do you import a component named \'Header\' from a file \'./Header.js\'?', 'require(\'./Header\')', 'import Header from \'./Header\'', 'load \'./Header\'', 'import {Header} from \'./Header\'', 'B', 1, '2026-07-30 19:13:05'),
(148, 6, 'What is the default port for local development servers started by Create React App?', '8080', '3000', '5000', '80', 'B', 1, '2026-07-30 19:13:05'),
(149, 6, 'How do you apply a CSS class named \'primary\' to an element in JSX?', 'class=\'primary\'', 'className=\'primary\'', 'style-class=\'primary\'', 'class-name=\'primary\'', 'B', 1, '2026-07-30 19:13:05'),
(150, 6, 'Which React Hook returns a mutable ref object that persists across renders?', 'useState', 'useRef', 'useEffect', 'useMemo', 'B', 1, '2026-07-30 19:13:05'),
(151, 7, 'What is Angular?', 'A styling template compiler', 'A component-based framework for building single-page client applications', 'A database engine library', 'A server runtime tool', 'B', 1, '2026-07-30 19:13:05'),
(152, 7, 'Which programming language is primary for Angular development?', 'Python', 'JavaScript', 'TypeScript', 'PHP', 'C', 1, '2026-07-30 19:13:05'),
(153, 7, 'Which command line tool is used to manage Angular projects?', 'Angular-CLI (ng)', 'npm-angular', 'ng-cli', 'ang', 'A', 1, '2026-07-30 19:13:05'),
(154, 7, 'How do you generate a new component in Angular using CLI?', 'ng create component my-comp', 'ng generate component my-comp', 'ng add component my-comp', 'ng new component my-comp', 'B', 1, '2026-07-30 19:13:05'),
(155, 7, 'What is the structural decorator used to define an Angular component class?', '@Component', '@Directive', '@NgModule', '@Injectable', 'A', 1, '2026-07-30 19:13:05'),
(156, 7, 'Which file defines the template layout structure for an Angular component by default?', '.ts file', '.html file', '.css file', '.spec.ts file', 'B', 1, '2026-07-30 19:13:05'),
(157, 7, 'What is interpolation syntax in Angular templates to bind expressions?', '{{ expression }}', '{ expression }', '[[ expression ]]', '(( expression ))', 'A', 1, '2026-07-30 19:13:05'),
(158, 7, 'Which directive is used for conditionally inserting or removing HTML elements?', '*ngFor', '*ngIf', 'ngStyle', 'ngSwitch', 'B', 1, '2026-07-30 19:13:05'),
(159, 7, 'Which directive is used to render list elements from an array in Angular templates?', '*ngFor', '*ngIf', 'ngList', 'ngRepeat', 'A', 1, '2026-07-30 19:13:05'),
(160, 7, 'What type of binding is defined by parentheses `(click)=\"handler()\"` in Angular?', 'Property binding', 'Event binding', 'Two-way data binding', 'Attribute binding', 'B', 1, '2026-07-30 19:13:05'),
(161, 7, 'What type of binding is defined by square brackets `[src]=\"imageUrl\"` in Angular?', 'Property binding', 'Event binding', 'Two-way data binding', 'Interpolation', 'A', 1, '2026-07-30 19:13:05'),
(162, 7, 'Which directive is used for two-way data binding in Angular forms?', 'ngModel', '[ngModel]', '[(ngModel)]', '[(model)]', 'C', 1, '2026-07-30 19:13:05'),
(163, 7, 'Which decorator is used to define an Angular module?', '@NgModule', '@Component', '@Injectable', '@Module', 'A', 1, '2026-07-30 19:13:05'),
(164, 7, 'Which decorator makes an Angular class eligible for dependency injection as a service?', '@Component', '@Injectable', '@Service', '@Inject', 'B', 1, '2026-07-30 19:13:05'),
(165, 7, 'What is the main configuration file for an Angular application CLI setup?', 'angular.json', 'package.json', 'tsconfig.json', 'webpack.config.js', 'A', 1, '2026-07-30 19:13:05'),
(166, 7, 'Which lifecycle hook is called after Angular initializes component data-bound input properties?', 'ngOnInit', 'ngOnDestroy', 'ngAfterViewInit', 'ngOnChanges', 'A', 1, '2026-07-30 19:13:05'),
(167, 7, 'How do you define a route path configuration in Angular router modules?', '{ path: \'home\', component: HomeComponent }', '{ url: \'home\', load: HomeComponent }', '{ route: \'home\', component: HomeComponent }', '{ link: \'home\', load: HomeComponent }', 'A', 1, '2026-07-30 19:13:05'),
(168, 7, 'Which directive acts as a placeholder that Angular dynamically fills based on active route state?', '<router-outlet></router-outlet>', '<router-view></router-view>', '<ng-view></ng-view>', '<outlet></outlet>', 'A', 1, '2026-07-30 19:13:05'),
(169, 7, 'What is an Angular Pipe?', 'A middleware routing filter', 'A template utility used to transform input data for display', 'A structural component helper', 'A data binding method', 'B', 1, '2026-07-30 19:13:05'),
(170, 7, 'Which pipe transforms text to all uppercase in Angular templates?', 'uppercase', 'upper', 'toUpper', 'caps', 'A', 1, '2026-07-30 19:13:05'),
(171, 7, 'What command is used to run a local development server for Angular?', 'ng start', 'ng build', 'ng serve', 'ng run', 'C', 1, '2026-07-30 19:13:05'),
(172, 7, 'How do you define input properties passed from parent to child components in Angular?', 'Using @Output decorator', 'Using @Input decorator', 'Using props declaration', 'Using @Inject decorator', 'B', 1, '2026-07-30 19:13:05'),
(173, 7, 'How do you emit custom event events from child components to parent components in Angular?', 'Using @Input decorator', 'Using @Output decorator and EventEmitter', 'Using actions dispatchers', 'Using context providers', 'B', 1, '2026-07-30 19:13:05'),
(174, 7, 'Which module must be imported to use two-way data bindings in Angular components?', 'CommonModule', 'BrowserModule', 'FormsModule', 'HttpClientModule', 'C', 1, '2026-07-30 19:13:05'),
(175, 7, 'What is RxJS in the context of Angular?', 'A template rendering library', 'A reactive programming library for asynchronous stream handling using Observables', 'A database wrapper module', 'A CSS preprocessor utility', 'B', 1, '2026-07-30 19:13:05'),
(176, 8, 'What is Vue.js?', 'A database management engine', 'A progressive JavaScript framework for building user interfaces', 'A server runtime system', 'A CSS layout utility tool', 'B', 1, '2026-07-30 19:13:05'),
(177, 8, 'What is the root element syntax used to mount a Vue application instance?', 'createApp(App).mount(\'#app\')', 'initApp(App).mount(\'#app\')', 'new Vue(\'#app\')', 'App.start(\'#app\')', 'A', 1, '2026-07-30 19:13:05'),
(178, 8, 'What is the template interpolation syntax in Vue.js?', '{{ expression }}', '{ expression }', '[[ expression ]]', '(( expression ))', 'A', 1, '2026-07-30 19:13:05'),
(179, 8, 'Which directive is used to conditionally render an HTML element in Vue.js?', 'v-show-if', 'v-if', 'v-cond', 'v-render', 'B', 1, '2026-07-30 19:13:05'),
(180, 8, 'Which directive is used to render items from an array in Vue.js?', 'v-repeat', 'v-loop', 'v-for', 'v-list', 'C', 1, '2026-07-30 19:13:05'),
(181, 8, 'Which directive binds element attributes to data properties in Vue.js (e.g. href or src)?', 'v-bind', 'v-on', 'v-model', 'v-text', 'A', 1, '2026-07-30 19:13:05'),
(182, 8, 'Which directive is used to listen to DOM events (like click) in Vue.js?', 'v-bind', 'v-on', 'v-model', 'v-event', 'B', 1, '2026-07-30 19:13:05'),
(183, 8, 'Which directive is used for two-way data binding in form inputs in Vue.js?', 'v-bind', 'v-on', 'v-model', 'v-sync', 'C', 1, '2026-07-30 19:13:05'),
(184, 8, 'What is the shorthand syntax for the `v-bind` directive?', '@', ':', '#', '$', 'B', 1, '2026-07-30 19:13:05'),
(185, 8, 'What is the shorthand syntax for the `v-on` directive?', '@', ':', '#', '$', 'A', 1, '2026-07-30 19:13:05'),
(186, 8, 'In Vue, which component property option defines reactive state variables in Options API?', 'methods', 'props', 'data', 'computed', 'C', 1, '2026-07-30 19:13:05'),
(187, 8, 'Which component property defines template methods in Vue\'s Options API?', 'computed', 'methods', 'data', 'watch', 'B', 1, '2026-07-30 19:13:05'),
(188, 8, 'What are computed properties in Vue.js?', 'Methods that run on every click event', 'Cached reactive getters that recalculate only when their dependencies change', 'Asynchronous fetch functions', 'Global state variables', 'B', 1, '2026-07-30 19:13:05'),
(189, 8, 'Which lifecycle hook is called immediately after a Vue instance is initialized and reactive properties are configured?', 'mounted', 'created', 'updated', 'beforeDestroy', 'B', 1, '2026-07-30 19:13:05'),
(190, 8, 'Which lifecycle hook executes after the template has been mounted to the DOM in Vue?', 'created', 'mounted', 'beforeUpdate', 'rendered', 'B', 1, '2026-07-30 19:13:05'),
(191, 8, 'How do you register components locally inside another Vue component?', 'In the modules option', 'In the components option', 'In the elements list', 'Import it globally', 'B', 1, '2026-07-30 19:13:05'),
(192, 8, 'How does a parent component pass attributes to a child component in Vue?', 'Using state hooks', 'Using props declarations', 'Using actions dispatchers', 'Using direct reference', 'B', 1, '2026-07-30 19:13:05'),
(193, 8, 'How does a child component emit custom event signals to parent components in Vue?', '$emit() method', '$send() method', '$trigger() method', '$dispatch() method', 'A', 1, '2026-07-30 19:13:05'),
(194, 8, 'What does the directive `v-show` do to an element?', 'Removes it from DOM', 'Toggles its CSS display property (block/none) keeping it in the DOM', 'Changes its opacity to 0', 'Hides it from reader nodes', 'B', 1, '2026-07-30 19:13:05'),
(195, 8, 'Which property is used to perform custom watch checks on reactive variables in Vue Options API?', 'computed', 'watch', 'methods', 'state', 'B', 1, '2026-07-30 19:13:05'),
(196, 8, 'What CLI tool is officially used to quickly scaffold Vue applications?', 'Vue CLI (vue)', 'npm-vue', 'ng-vue', 'vue-scaffold', 'A', 1, '2026-07-30 19:13:05'),
(197, 8, 'What is the root component file type used in Vue single-file component architecture?', '.js file', '.html file', '.vue file', '.component file', 'C', 1, '2026-07-30 19:13:05'),
(198, 8, 'What is the purpose of Vuex or Pinia in Vue applications?', 'CSS styling preprocessors', 'Centralized state management libraries', 'Routing middleware tools', 'Database connectors', 'B', 1, '2026-07-30 19:13:05'),
(199, 8, 'How do you render raw HTML string text in Vue.js templates safely?', 'Using {{ HTML }}', 'Using v-html directive', 'Using v-text directive', 'Using raw-html element', 'B', 1, '2026-07-30 19:13:05'),
(200, 8, 'What is the Composition API introduced in Vue 3?', 'A CSS grid layout utility', 'A set of APIs to author components using imported function hooks instead of Options API', 'An asset compiling bundler', 'A route guard middleware', 'B', 1, '2026-07-30 19:13:05'),
(201, 9, 'What is jQuery?', 'A JavaScript framework for single-page applications', 'A lightweight, \'write less, do more\' JavaScript library for DOM manipulation', 'A backend PHP wrapper database engine', 'A CSS style compiler', 'B', 1, '2026-07-30 19:13:05'),
(202, 9, 'Which character is the default shorthand alias for jQuery in code?', '&', '$', '@', '#', 'B', 1, '2026-07-30 19:13:05'),
(203, 9, 'What is the correct jQuery syntax to select all `<p>` elements?', '$(\'p\')', '$(\'#p\')', '$(\'.p\')', '$(\'paragraph\')', 'A', 1, '2026-07-30 19:13:05'),
(204, 9, 'How do you select an HTML element with id \'demo\' in jQuery?', '$(\'.demo\')', '$(\'demo\')', '$(\'#demo\')', '$(\'*demo\')', 'C', 1, '2026-07-30 19:13:05'),
(205, 9, 'How do you select all elements with class \'test\' in jQuery?', '$(\'.test\')', '$(\'#test\')', '$(\'test\')', '$(\'.class-test\')', 'A', 1, '2026-07-30 19:13:05'),
(206, 9, 'What is the standard jQuery code to ensure DOM content is fully loaded before executing scripts?', '$(document).ready(function())', '$(window).load(function())', '$(dom).onload(function())', 'jQuery.start(function())', 'A', 1, '2026-07-30 19:13:05'),
(207, 9, 'Which jQuery method is used to hide an HTML element?', 'hide()', 'invisible()', 'displayNone()', 'remove()', 'A', 1, '2026-07-30 19:13:05'),
(208, 9, 'Which jQuery method is used to show a hidden element?', 'display()', 'show()', 'visible()', 'append()', 'B', 1, '2026-07-30 19:13:05'),
(209, 9, 'How do you change the text content of an HTML element using jQuery?', '$(\'selector\').text(\'New Text\')', '$(\'selector\').value(\'New Text\')', '$(\'selector\').htmlText(\'New Text\')', '$(\'selector\').write(\'New Text\')', 'A', 1, '2026-07-30 19:13:05'),
(210, 9, 'How do you get or set the value of a form input field in jQuery?', 'val()', 'value()', 'text()', 'attr(\'value\')', 'A', 1, '2026-07-30 19:13:05'),
(211, 9, 'Which jQuery method changes CSS style properties directly?', 'css()', 'style()', 'theme()', 'setClass()', 'A', 1, '2026-07-30 19:13:05'),
(212, 9, 'What is the correct jQuery syntax to set background color to red for all paragraphs?', '$(\'p\').css(\'background-color\', \'red\')', '$(\'p\').style(\'bg-color\', \'red\')', '$(\'p\').background(\'red\')', '$(\'p\').css-bg(\'red\')', 'A', 1, '2026-07-30 19:13:05'),
(213, 9, 'Which jQuery method adds a CSS class name to matched elements?', 'addClass()', 'setClass()', 'appendClass()', 'toggleCSS()', 'A', 1, '2026-07-30 19:13:05'),
(214, 9, 'Which jQuery method removes a class name from elements?', 'removeClass()', 'deleteClass()', 'clearClass()', 'toggleCSS()', 'B', 1, '2026-07-30 19:13:05'),
(215, 9, 'Which jQuery method toggles between adding and removing a class name?', 'toggleClass()', 'switchClass()', 'triggerClass()', 'alterClass()', 'A', 1, '2026-07-30 19:13:05'),
(216, 9, 'Which jQuery event handler method attaches a handler for mouse click events?', 'click()', 'onClick()', 'onclick()', 'mouseclick()', 'A', 1, '2026-07-30 19:13:05'),
(217, 9, 'How do you perform an asynchronous HTTP GET request in jQuery?', '$.get()', '$.ajaxGet()', '$.httpGet()', '$.fetch()', 'A', 1, '2026-07-30 19:13:05'),
(218, 9, 'How do you perform a POST Ajax request in jQuery?', '$.post()', '$.ajaxPost()', '$.httpPost()', '$.send()', 'A', 1, '2026-07-30 19:13:05'),
(219, 9, 'Which jQuery method returns the parent element of the selected element?', 'parent()', 'parents()', 'ancestor()', 'root()', 'A', 1, '2026-07-30 19:13:05'),
(220, 9, 'Which jQuery method returns all direct child elements of matched elements?', 'children()', 'childs()', 'descendants()', 'find()', 'A', 1, '2026-07-30 19:13:05'),
(221, 9, 'Which method is used to append content to the inside end of matched HTML elements in jQuery?', 'append()', 'prepend()', 'after()', 'insert()', 'A', 1, '2026-07-30 19:13:05'),
(222, 9, 'Which method is used to insert content before the matched HTML elements (external sibling)?', 'before()', 'prepend()', 'after()', 'insert()', 'A', 1, '2026-07-30 19:13:05'),
(223, 9, 'Which jQuery method is used to remove an element and all its child elements from the DOM?', 'remove()', 'empty()', 'clear()', 'delete()', 'A', 1, '2026-07-30 19:13:05'),
(224, 9, 'Which jQuery method only removes child content from matched elements without destroying the parent elements?', 'empty()', 'remove()', 'clear()', 'clean()', 'A', 1, '2026-07-30 19:13:05'),
(225, 9, 'Which jQuery animation method slides an element open (displays it with height slide)?', 'slideDown()', 'slideUp()', 'fadeIn()', 'slideToggle()', 'A', 1, '2026-07-30 19:13:05'),
(226, 10, 'What is TypeScript?', 'A direct replacement database browser runtime', 'A strongly typed programming language that compiles to plain JavaScript', 'A CSS parser framework', 'An execution compiler for python', 'B', 1, '2026-07-30 19:13:05'),
(227, 10, 'Which command line tool compiles TypeScript (.ts) files into JavaScript?', 'tsc', 'ts-compile', 'tsrun', 'npmcompile', 'A', 1, '2026-07-30 19:13:05'),
(228, 10, 'How do you define a typed variable \'username\' as a string in TypeScript?', 'let username = String;', 'let username: string;', 'var username String;', 'let username string = \'\';', 'B', 1, '2026-07-30 19:13:05'),
(229, 10, 'What is the default type assigned to variables in TypeScript if no type is declared and type inference cannot be applied?', 'null', 'undefined', 'any', 'void', 'C', 1, '2026-07-30 19:13:05'),
(230, 10, 'Which data type represents the absence of a return value in a function declaration?', 'null', 'undefined', 'void', 'never', 'C', 1, '2026-07-30 19:13:05'),
(231, 10, 'How do you make an object property optional in a TypeScript interface?', 'Use a colon suffix `:`', 'Use a question mark suffix `?`', 'Use an asterisk suffix `*`', 'Declare it as null', 'B', 1, '2026-07-30 19:13:05'),
(232, 10, 'What keyword is used to define a custom type structure holding class contracts in TypeScript?', 'contract', 'interface', 'protocol', 'struct', 'B', 1, '2026-07-30 19:13:05'),
(233, 10, 'What type refers to a variable containing array elements of numbers?', 'let list: number[];', 'let list: ArrayNumber;', 'let list: numbers[];', 'let list: number;', 'A', 1, '2026-07-30 19:13:05'),
(234, 10, 'How do you define a tuple type representing a string and a number in TypeScript?', 'let x: [string, number];', 'let x: (string, number);', 'let x: string, number;', 'let x: {string, number};', 'A', 1, '2026-07-30 19:13:05'),
(235, 10, 'What TypeScript utility type represents a constant collection of named integer values?', 'interface', 'enum', 'type', 'const', 'B', 1, '2026-07-30 19:13:05'),
(236, 10, 'How do you compile a typescript file named \'app.ts\'?', 'tsc app.ts', 'compile app.ts', 'node app.ts', 'ts-node app.ts', 'A', 1, '2026-07-30 19:13:05'),
(237, 10, 'What is the configuration file name used to define compiler options in a TypeScript project?', 'tsconfig.json', 'tsconfig.xml', 'typescript.json', 'package.json', 'A', 1, '2026-07-30 19:13:05'),
(238, 10, 'Which keyword defines class inheritance in TypeScript?', 'extends', 'implements', 'inherits', 'prototype', 'A', 1, '2026-07-30 19:13:05'),
(239, 10, 'What is union type syntax in TypeScript (allowing a variable to be string OR number)?', 'string | number', 'string & number', 'string or number', 'string || number', 'A', 1, '2026-07-30 19:13:05'),
(240, 10, 'Which type assertions syntax allows casting a variable \'someValue\' to string in JSX-safe format?', '(someValue as string)', '<string>someValue', 'someValue(string)', 'cast<string>(someValue)', 'A', 1, '2026-07-30 19:13:05'),
(241, 10, 'What is type inference in TypeScript?', 'The manual declaration of types in text editor', 'The compiler automatically determining the type based on initialization values', 'The compilation of JS to TS', 'A runtime error logging system', 'B', 1, '2026-07-30 19:13:05'),
(242, 10, 'Which access modifier makes a class property accessible only within the declaring class?', 'public', 'protected', 'private', 'readonly', 'C', 1, '2026-07-30 19:13:05'),
(243, 10, 'Which access modifier makes a property accessible in the declaring class and its subclasses?', 'public', 'protected', 'private', 'readonly', 'B', 1, '2026-07-30 19:13:05'),
(244, 10, 'What keyword creates a read-only variable assignment that cannot be mutated?', 'readonly', 'const', 'static', 'final', 'A', 1, '2026-07-30 19:13:05'),
(245, 10, 'What does compile-time checking refer to in TypeScript?', 'The browser checking code during website render', 'The compiler checking types and syntax errors before generating JavaScript code', 'The database validating queries', 'The server compiling pages', 'B', 1, '2026-07-30 19:13:05'),
(246, 10, 'What type represents a function parameter that has a default value if omitted?', 'any', 'Optional parameter or Default parameter', 'void', 'never', 'B', 1, '2026-07-30 19:13:05'),
(247, 10, 'How do you define a type alias in TypeScript?', 'type Name = string;', 'alias Name = string;', 'interface Name = string;', 'const Name: type = string;', 'A', 1, '2026-07-30 19:13:05'),
(248, 10, 'What is intersection type syntax in TypeScript (combining multiple types)?', 'type C = A | B', 'type C = A & B', 'type C = A + B', 'type C = A and B', 'B', 1, '2026-07-30 19:13:05'),
(249, 10, 'Which keyword allows a class to implement a specific interface in TypeScript?', 'extends', 'implements', 'conforms', 'requires', 'B', 1, '2026-07-30 19:13:05'),
(250, 10, 'What is the \'never\' type in TypeScript?', 'A type representing variables that default to null', 'A type representing values that will never occur (e.g. function throwing error always)', 'A type alias for void', 'An unassigned tuple', 'B', 1, '2026-07-30 19:13:05'),
(251, 11, 'Who developed the C programming language?', 'Dennis Ritchie', 'James Gosling', 'Bjarne Stroustrup', 'Guido van Rossum', 'A', 1, '2026-07-30 19:13:05'),
(252, 11, 'Which format specifier is used to print an integer in C?', '%f', '%c', '%d', '%s', 'C', 1, '2026-07-30 19:13:05'),
(253, 11, 'What is the correct way to declare a pointer to an integer in C?', 'int p;', 'int &p;', 'int *p;', 'pointer p;', 'C', 1, '2026-07-30 19:13:05'),
(254, 11, 'Which function is used to allocate memory dynamically in C?', 'calloc()', 'malloc()', 'realloc()', 'All of the above', 'D', 1, '2026-07-30 19:13:05'),
(255, 11, 'Which operator returns the size of a variable or data type in bytes in C?', 'sizeof', 'len', 'bytes', 'size', 'A', 1, '2026-07-30 19:13:05'),
(256, 11, 'What is the index of the first element in a C array?', '1', '-1', '0', '10', 'C', 1, '2026-07-30 19:13:05'),
(257, 11, 'Which character terminates a standard string in C?', '\\n', '\\0', '\\t', '\\s', 'B', 1, '2026-07-30 19:13:05'),
(258, 11, 'Which function is used to free dynamically allocated memory in C?', 'delete()', 'free()', 'clear()', 'release()', 'B', 1, '2026-07-30 19:13:05'),
(259, 11, 'Which header file must be included to use standard input/output functions like printf?', '<conio.h>', '<stdlib.h>', '<stdio.h>', '<math.h>', 'C', 1, '2026-07-30 19:13:05'),
(260, 11, 'What is the correct syntax to define a structure in C?', 'struct { int x; } s;', 'structure structName { };', 'struct structName { int x; };', 'class structName { };', 'C', 1, '2026-07-30 19:13:05'),
(261, 11, 'Which operator is used to access structure members using a structure variable?', '.', '->', '*', '&', 'A', 1, '2026-07-30 19:13:05'),
(262, 11, 'Which operator is used to access structure members using a structure pointer?', '.', '->', '*', '&', 'B', 1, '2026-07-30 19:13:05'),
(263, 11, 'What is the binary representation of bitwise AND operator in C?', '&', '&&', '|', '||', 'A', 1, '2026-07-30 19:13:05'),
(264, 11, 'What value represents false in C language?', 'Any negative number', '0', '1', 'Null character only', 'B', 1, '2026-07-30 19:13:05'),
(265, 11, 'Which statement is used to exit a loop early in C?', 'continue', 'break', 'exit', 'return', 'B', 1, '2026-07-30 19:13:05'),
(266, 11, 'Which keyword is used to prevent modification of a variable\'s value in C?', 'static', 'volatile', 'const', 'final', 'C', 1, '2026-07-30 19:13:05'),
(267, 11, 'What is the output of `printf(\"%d\", 5 / 2);` in C?', '2.5', '2', '3', 'Error', 'B', 1, '2026-07-30 19:13:05'),
(268, 11, 'How do you read a character input in C?', 'putchar()', 'getchar()', 'scanf(\"%s\")', 'gets()', 'B', 1, '2026-07-30 19:13:05'),
(269, 11, 'What is a recursive function in C?', 'A function that has no return type', 'A function that calls itself', 'A function executed inside main', 'A thread function', 'B', 1, '2026-07-30 19:13:05'),
(270, 11, 'Which type of loop is guaranteed to execute at least once in C?', 'for', 'while', 'do-while', 'None', 'C', 1, '2026-07-30 19:13:05'),
(271, 11, 'What is the output of increment operator `i++` compared to `++i`?', 'i++ increments before expression evaluation', '++i increments after expression evaluation', 'i++ returns current value and then increments', 'They are identical in all contexts', 'C', 1, '2026-07-30 19:13:05'),
(272, 11, 'Which of the following is NOT a valid variable name in C?', 'my_var', 'myVar', '2myVar', '_myVar', 'C', 1, '2026-07-30 19:13:05'),
(273, 11, 'What does the `#include` preprocessor directive do?', 'It prints text to console', 'It copy-pastes the content of the specified header file into the source code', 'It compiles the project', 'It declares global variables', 'B', 1, '2026-07-30 19:13:05'),
(274, 11, 'Which operator returns the address of a variable in C?', '*', '&&', '&', '->', 'C', 1, '2026-07-30 19:13:05'),
(275, 11, 'What is the size of a standard integer variable `int` in C on most modern 32/64-bit systems?', '1 byte', '2 bytes', '4 bytes', '8 bytes', 'C', 1, '2026-07-30 19:13:05'),
(276, 12, 'Who developed C++?', 'Dennis Ritchie', 'Bjarne Stroustrup', 'James Gosling', 'Guido van Rossum', 'B', 1, '2026-07-30 19:13:05'),
(277, 12, 'Which stream object is used for output in C++?', 'cin', 'cout', 'cerr', 'stdout', 'B', 1, '2026-07-30 19:13:05'),
(278, 12, 'Which stream object is used for input in C++?', 'cin', 'cout', 'cin-stream', 'stdin', 'A', 1, '2026-07-30 19:13:05'),
(279, 12, 'Which operator is the insertion operator used with `std::cout`?', '<<', '>>', '<-', '++', 'A', 1, '2026-07-30 19:13:05');
INSERT INTO `questions` (`id`, `question_bank_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `marks`, `created_at`) VALUES
(280, 12, 'Which operator is the extraction operator used with `std::cin`?', '<<', '>>', '->', '--', 'B', 1, '2026-07-30 19:13:05'),
(281, 12, 'What is the extension of C++ source files by convention?', '.c', '.cpp', '.cp', '.cxx', 'B', 1, '2026-07-30 19:13:05'),
(282, 12, 'What is a class in C++?', 'A template function', 'A user-defined data type that acts as a blueprint for objects', 'A built-in array keyword', 'A compile parameter', 'B', 1, '2026-07-30 19:13:05'),
(283, 12, 'By default, what access specifier is applied to members of a C++ class?', 'public', 'private', 'protected', 'friend', 'B', 1, '2026-07-30 19:13:05'),
(284, 12, 'By default, what access specifier is applied to members of a C++ struct?', 'public', 'private', 'protected', 'friend', 'A', 1, '2026-07-30 19:13:05'),
(285, 12, 'Which keyword is used to instantiate an object of a class dynamically on the heap?', 'malloc', 'new', 'create', 'alloc', 'B', 1, '2026-07-30 19:13:05'),
(286, 12, 'Which keyword deallocates memory allocated dynamically by the `new` operator?', 'free', 'delete', 'remove', 'clear', 'B', 1, '2026-07-30 19:13:05'),
(287, 12, 'What is a constructor in C++?', 'A function called to compile code', 'A special member function called automatically during object instantiation', 'A method to delete objects', 'An interface decorator', 'B', 1, '2026-07-30 19:13:05'),
(288, 12, 'What is a destructor in C++?', 'A method called to compile source code', 'A special member function called when an object is destroyed', 'An operator to allocate memory', 'A loop counter', 'B', 1, '2026-07-30 19:13:05'),
(289, 12, 'Which keyword defines inheritance in C++ class declaration?', 'extends', 'implements', 'Colon symbol `:`', 'inherits', 'C', 1, '2026-07-30 19:13:05'),
(290, 12, 'What is a reference variable in C++?', 'A pointer holding memory address', 'An alias (alternative name) for an existing variable', 'A copy of a variable value', 'A template placeholder', 'B', 1, '2026-07-30 19:13:05'),
(291, 12, 'Which keyword is used to import namespaces in C++?', 'using namespace', 'import', 'include', 'use', 'A', 1, '2026-07-30 19:13:05'),
(292, 12, 'Which C++ function represents the mandatory entry point of execution?', 'start()', 'main()', 'init()', 'run()', 'B', 1, '2026-07-30 19:13:05'),
(293, 12, 'What does polymorphism mean in C++?', 'Code that executes without compiler errors', 'The ability to present the same interface for differing underlying data types', 'Multiple files in one project', 'Templates of variables', 'B', 1, '2026-07-30 19:13:05'),
(294, 12, 'Which keyword makes a member function eligible for dynamic binding (polymorphism)?', 'static', 'virtual', 'dynamic', 'override', 'B', 1, '2026-07-30 19:13:05'),
(295, 12, 'Which operator is the scope resolution operator in C++?', '.', '->', '::', '?:', 'C', 1, '2026-07-30 19:13:05'),
(296, 12, 'What does `std::endl` do in C++ stream outputs?', 'Clears the stream buffer without break', 'Inserts a newline character and flushes the output stream buffer', 'Ends execution of main', 'Closes stream files', 'B', 1, '2026-07-30 19:13:05'),
(297, 12, 'Which keyword is used to write generic code (templates) in C++?', 'class', 'template', 'generic', 'struct', 'B', 1, '2026-07-30 19:13:05'),
(298, 12, 'What is an abstract class in C++?', 'A class without any member variables', 'A class containing at least one pure virtual function', 'A class derived from multiple classes', 'An inline struct', 'B', 1, '2026-07-30 19:13:05'),
(299, 12, 'How do you write a single-line comment in C++?', '/* comment */', '# comment', '// comment', '\' comment', 'C', 1, '2026-07-30 19:13:05'),
(300, 12, 'Which header file is used for C++ standard string class?', '<string.h>', '<string>', '<cstring>', '<str>', 'B', 1, '2026-07-30 19:13:05'),
(301, 13, 'What is the main execution entry method signature in Java?', 'void main(String[] args)', 'public static void main(String[] args)', 'static public void main(args)', 'public void main()', 'B', 1, '2026-07-30 19:13:05'),
(302, 13, 'Which platform compiles Java source code into bytecode?', 'JVM', 'JDK (javac)', 'JRE', 'OS', 'B', 1, '2026-07-30 19:13:05'),
(303, 13, 'What platform executes compiled Java bytecode?', 'JDK', 'JRE / JVM', 'javac', 'Compiler', 'B', 1, '2026-07-30 19:13:05'),
(304, 13, 'Which keyword is used to inherit a class in Java?', 'implements', 'extends', 'inherits', 'extends-class', 'B', 1, '2026-07-30 19:13:05'),
(305, 13, 'Which keyword is used to implement an interface in Java?', 'extends', 'implements', 'inherits', 'interface', 'B', 1, '2026-07-30 19:13:05'),
(306, 13, 'What is the default value of a boolean variable declared as a class member in Java?', 'true', 'false', 'null', '0', 'B', 1, '2026-07-30 19:13:05'),
(307, 13, 'Which package is imported automatically into every Java program by default?', 'java.io.*', 'java.util.*', 'java.lang.*', 'java.net.*', 'C', 1, '2026-07-30 19:13:05'),
(308, 13, 'Which keyword defines a read-only variable that cannot be reassigned in Java?', 'static', 'const', 'final', 'readonly', 'C', 1, '2026-07-30 19:13:05'),
(309, 13, 'What class is the root ancestor of all classes in Java?', 'String', 'System', 'Object', 'Class', 'C', 1, '2026-07-30 19:13:05'),
(310, 13, 'How do you declare a numeric array of size 5 in Java?', 'int arr = new int[5];', 'int[] arr = new int[5];', 'int arr[] = int[5];', 'array arr = new array(5);', 'B', 1, '2026-07-30 19:13:05'),
(311, 13, 'Which keyword is used to handle run-time errors (exceptions) in Java?', 'catch-throw', 'try-catch', 'throw-exception', 'error-handle', 'B', 1, '2026-07-30 19:13:05'),
(312, 13, 'What is garbage collection in Java?', 'The compilation of dead code', 'The automatic reclamation of heap memory by destroying unreferenced objects', 'The memory dump of JRE', 'The cleaning of file directories', 'B', 1, '2026-07-30 19:13:05'),
(313, 13, 'Which operator compares two object references to check if they point to the exact same object in memory?', 'equals()', '==', '===', 'compare', 'B', 1, '2026-07-30 19:13:05'),
(314, 13, 'Which method should be used to compare the actual character contents of two String objects?', '==', 'equals()', 'compare()', 'strcmp()', 'B', 1, '2026-07-30 19:13:05'),
(315, 13, 'What is the size of an integer primitive variable `int` in Java?', '1 byte', '2 bytes', '4 bytes', '8 bytes', 'C', 1, '2026-07-30 19:13:05'),
(316, 13, 'Which keyword is used to access parent class members or invoke parent constructors?', 'super', 'this', 'parent', 'base', 'A', 1, '2026-07-30 19:13:05'),
(317, 13, 'Which keyword refers to the current object instance inside a constructor or method?', 'super', 'this', 'self', 'instance', 'B', 1, '2026-07-30 19:13:05'),
(318, 13, 'What modifier makes a class member accessible only within its own package and subclass classes?', 'public', 'protected', 'private', 'default', 'B', 1, '2026-07-30 19:13:05'),
(319, 13, 'What is the return type of a class constructor in Java?', 'void', 'int', 'None', 'Object', 'C', 1, '2026-07-30 19:13:05'),
(320, 13, 'What happens if a Java class is declared abstract?', 'It can be instantiated normally', 'It cannot be instantiated and must be subclassed', 'It contains only static methods', 'It is loaded into local files', 'B', 1, '2026-07-30 19:13:05'),
(321, 13, 'Which Java Collection represents a dynamic resizing array?', 'HashMap', 'ArrayList', 'HashSet', 'LinkedList', 'B', 1, '2026-07-30 19:13:05'),
(322, 13, 'Which Collection represents key-value pair mapping?', 'ArrayList', 'HashSet', 'HashMap', 'Stack', 'C', 1, '2026-07-30 19:13:05'),
(323, 13, 'What exception is thrown when attempting to access a member of a null object reference?', 'NullPointerException', 'ArrayIndexOutOfBoundsException', 'ArithmeticException', 'IOException', 'A', 1, '2026-07-30 19:13:05'),
(324, 13, 'How do you print a line of text to console in Java?', 'System.out.print()', 'System.out.println()', 'console.log()', 'print()', 'B', 1, '2026-07-30 19:13:05'),
(325, 13, 'Which keyword prevents a method from being overridden by subclasses?', 'static', 'final', 'private', 'abstract', 'B', 1, '2026-07-30 19:13:05'),
(326, 14, 'What is Python?', 'A markup compilation engine', 'An interpreted, high-level, general-purpose programming language', 'A relational database dialect', 'A web styling compiler', 'B', 1, '2026-07-30 19:13:05'),
(327, 14, 'How do you define a function in Python?', 'function myFunction()', 'def myFunction():', 'define myFunction():', 'func myFunction()', 'B', 1, '2026-07-30 19:13:05'),
(328, 14, 'What is used to define blocks of code (like loops or functions) in Python?', 'Curly braces `{}`', 'Parentheses `()`', 'Indentation (whitespace)', 'Semicolons `;`', 'C', 1, '2026-07-30 19:13:05'),
(329, 14, 'How do you write a comment in Python?', '// This is a comment', '/* This is a comment */', '# This is a comment', '<!-- This is a comment -->', 'C', 1, '2026-07-30 19:13:05'),
(330, 14, 'What is the output of `print(type([])` in Python?', '<class \'list\'>', '<class \'tuple\'>', '<class \'dict\'>', '<class \'set\'>', 'A', 1, '2026-07-30 19:13:05'),
(331, 14, 'Which built-in function returns the number of items in a list or characters in a string?', 'size()', 'length()', 'len()', 'count()', 'C', 1, '2026-07-30 19:13:05'),
(332, 14, 'What is the correct syntax to create a list in Python?', 'my_list = [1, 2, 3]', 'my_list = (1, 2, 3)', 'my_list = {1, 2, 3}', 'my_list = 1, 2, 3', 'A', 1, '2026-07-30 19:13:05'),
(333, 14, 'What is the difference between a List and a Tuple in Python?', 'Lists are immutable; Tuples are mutable', 'Lists are mutable; Tuples are immutable', 'Lists hold strings only; Tuples hold numbers', 'They are identical in Python', 'B', 1, '2026-07-30 19:13:05'),
(334, 14, 'How do you create a dictionary in Python?', 'd = []', 'd = ()', 'd = {}', 'd = set()', 'C', 1, '2026-07-30 19:13:05'),
(335, 14, 'What is the correct output of `print(2 ** 3)` in Python?', '6', '8', '9', '5', 'B', 1, '2026-07-30 19:13:05'),
(336, 14, 'How do you start a FOR loop in Python to repeat code 5 times?', 'for i in range(5):', 'for (i=0; i<5; i++):', 'for i in 1 to 5:', 'for i = 1 to 5:', 'A', 1, '2026-07-30 19:13:05'),
(337, 14, 'Which keyword is used to check if a key exists inside a Python dictionary?', 'has', 'exists', 'in', 'contains', 'C', 1, '2026-07-30 19:13:05'),
(338, 14, 'How do you add an element to the end of a list in Python?', 'append()', 'add()', 'push()', 'insert()', 'A', 1, '2026-07-30 19:13:05'),
(339, 14, 'What is the correct way to handle exceptions in Python?', 'try-except', 'try-catch', 'throw-catch', 'try-handle', 'A', 1, '2026-07-30 19:13:05'),
(340, 14, 'Which function opens a file in Python?', 'open()', 'file()', 'read_file()', 'load()', 'A', 1, '2026-07-30 19:13:05'),
(341, 14, 'What is the default return value of a function that does not contain a return statement?', '0', 'False', 'None', 'void', 'C', 1, '2026-07-30 19:13:05'),
(342, 14, 'How do you slice the first three elements from a list named `my_list`?', 'my_list[3:]', 'my_list[:3]', 'my_list[0, 1, 2]', 'my_list[1:3]', 'B', 1, '2026-07-30 19:13:05'),
(343, 14, 'What does Python\'s `is` operator evaluate?', 'Value equality', 'Identity equality (checking if references point to the exact same object)', 'String comparison', 'Logical checks', 'B', 1, '2026-07-30 19:13:05'),
(344, 14, 'Which keyword imports modules in Python?', 'include', 'import', 'require', 'load', 'B', 1, '2026-07-30 19:13:05'),
(345, 14, 'What is a list comprehension in Python?', 'A method to explain list objects', 'A concise way to construct lists from existing iterables', 'A syntax error check', 'A list sorting routine', 'B', 1, '2026-07-30 19:13:05'),
(346, 14, 'How do you remove all whitespace from the beginning and end of a string in Python?', 'strip()', 'trim()', 'clear()', 'replace(\' \', \'\')', 'A', 1, '2026-07-30 19:13:05'),
(347, 14, 'Which keyword is used to create a class in Python?', 'class', 'def', 'struct', 'object', 'A', 1, '2026-07-30 19:13:05'),
(348, 14, 'What is the initialization method equivalent to a constructor inside a Python class?', '__init__()', '__new__()', 'constructor()', 'init()', 'A', 1, '2026-07-30 19:13:05'),
(349, 14, 'How do you convert a string \'123\' into an integer in Python?', 'int(\'123\')', 'str(\'123\')', 'convert(\'123\')', 'parseInt(\'123\')', 'A', 1, '2026-07-30 19:13:05'),
(350, 14, 'Which data structure in Python stores unique, unordered elements?', 'List', 'Tuple', 'Set', 'Dictionary', 'C', 1, '2026-07-30 19:13:05'),
(351, 15, 'What does PHP stand for?', 'Personal Home Page', 'PHP: Hypertext Preprocessor', 'Private Hypertext Preprocessor', 'Program Hypertext Preprocessor', 'B', 1, '2026-07-30 19:13:05'),
(352, 15, 'What character must all variables start with in PHP?', '&', '#', '$', '@', 'C', 1, '2026-07-30 19:13:05'),
(353, 15, 'Which statement is used to output text in PHP?', 'print()', 'echo', 'Both A and B', 'console.log()', 'C', 1, '2026-07-30 19:13:05'),
(354, 15, 'How do you write a single-line comment in PHP?', '// This is a comment', '# This is a comment', 'Both A and B', '/* This is a comment */', 'C', 1, '2026-07-30 19:13:05'),
(355, 15, 'Which operator is used to concatenate two strings in PHP?', '+', '.', '&&', '&', 'B', 1, '2026-07-30 19:13:05'),
(356, 15, 'What are the standard PHP script wrapping tags?', '<?php ... ?>', '<script> ... </script>', '<% ... %>', '<? ... ?>', 'A', 1, '2026-07-30 19:13:05'),
(357, 15, 'Which array represents associative key-value mappings in PHP?', '$arr = [1, 2, 3]', '$arr = [\'key\' => \'value\']', '$arr = (1 => 2)', '$arr = \'key\' = \'value\'', 'B', 1, '2026-07-30 19:13:05'),
(358, 15, 'Which PHP superglobal array collects form data sent via HTTP POST requests?', '$_GET', '$_POST', '$_REQUEST', '$_SESSION', 'B', 1, '2026-07-30 19:13:05'),
(359, 15, 'How do you start a session in PHP?', 'session_start()', 'start_session()', 'session()', '$_SESSION[\'start\'] = true', 'A', 1, '2026-07-30 19:13:05'),
(360, 15, 'What is the default execution port of built-in PHP development server when started on localhost?', '3000', '80', '8000', '8080', 'C', 1, '2026-07-30 19:13:05'),
(361, 15, 'Which function returns the number of elements in an array in PHP?', 'count()', 'sizeof()', 'Both A and B', 'len()', 'C', 1, '2026-07-30 19:13:05'),
(362, 15, 'Which built-in PHP function checks if a variable has been declared and is not null?', 'is_null()', 'isset()', 'empty()', 'defined()', 'B', 1, '2026-07-30 19:13:05'),
(363, 15, 'What is the difference between single quotes (\'\') and double quotes (\"\") for strings in PHP?', 'Single quotes evaluate variable variables; double quotes do not', 'Double quotes interpolate variable values; single quotes treat them as literal text', 'They are identical in all behaviors', 'Single quotes are only for HTML', 'B', 1, '2026-07-30 19:13:05'),
(364, 15, 'Which PHP function redirects a client\'s browser to another URL?', 'redirect()', 'header(\'Location: url\')', 'go(\'url\')', 'window.location = url', 'B', 1, '2026-07-30 19:13:05'),
(365, 15, 'How do you declare a class in PHP?', 'class MyClass {}', 'MyClass class {}', 'class: MyClass {}', 'struct MyClass {}', 'A', 1, '2026-07-30 19:13:05'),
(366, 15, 'Which keyword references a class constructor in PHP?', '__init()', '__construct()', 'constructor()', 'MyClass()', 'B', 1, '2026-07-30 19:13:05'),
(367, 15, 'Which keyword is used to access properties or methods from the current class instance in PHP?', 'this', '$this', '$self', 'self::', 'B', 1, '2026-07-30 19:13:05'),
(368, 15, 'Which keyword is used to access static methods or constants of a class in PHP?', '$this', 'self::', 'parent::', 'static::', 'B', 1, '2026-07-30 19:13:05'),
(369, 15, 'How do you define a constant value in PHP?', 'define(\'NAME\', value)', 'const NAME = value', 'Both A and B', 'constant NAME = value', 'C', 1, '2026-07-30 19:13:05'),
(370, 15, 'Which PHP array function checks if a key exists in an array?', 'array_key_exists()', 'in_array()', 'key_exists()', 'Both A and C', 'D', 1, '2026-07-30 19:13:05'),
(371, 15, 'Which PHP function converts an array into a JSON string?', 'json_encode()', 'json_decode()', 'json_stringify()', 'json_convert()', 'A', 1, '2026-07-30 19:13:05'),
(372, 15, 'Which PHP function parses a JSON string into a PHP associative array or object?', 'json_encode()', 'json_decode()', 'json_parse()', 'json_convert()', 'B', 1, '2026-07-30 19:13:05'),
(373, 15, 'Which operator represents strict equality (matches type and value) in PHP?', '==', '===', '=', '!==', 'B', 1, '2026-07-30 19:13:05'),
(374, 15, 'What database extension is standard in modern PHP 7/8 for connecting to databases securely?', 'mysql_connect', 'PDO', 'mysqli', 'Both B and C', 'D', 1, '2026-07-30 19:13:05'),
(375, 15, 'What is Composer in PHP?', 'A testing framework', 'A dependency manager package utility for PHP libraries', 'A page router', 'A compiler', 'B', 1, '2026-07-30 19:13:05'),
(376, 16, 'Which company developed the C# programming language?', 'Sun Microsystems', 'Oracle', 'Microsoft', 'Google', 'C', 1, '2026-07-30 19:13:05'),
(377, 16, 'What is the entry point method signature for a C# console application?', 'void Main()', 'static void Main(string[] args)', 'public void Main(args)', 'Console.Start()', 'B', 1, '2026-07-30 19:13:05'),
(378, 16, 'Which namespace holds standard Console input/output classes in C#?', 'System.IO', 'System.Console', 'System', 'Microsoft.Console', 'C', 1, '2026-07-30 19:13:05'),
(379, 16, 'How do you print a line of text to console in C#?', 'Console.print()', 'Console.WriteLine()', 'System.out.println()', 'print()', 'B', 1, '2026-07-30 19:13:05'),
(380, 16, 'What is the file extension of C# source code files?', '.c', '.cs', '.cpp', '.csharp', 'B', 1, '2026-07-30 19:13:05'),
(381, 16, 'Which keyword is used to import namespaces in C#?', 'import', 'using', 'include', 'require', 'B', 1, '2026-07-30 19:13:05'),
(382, 16, 'What is the difference between a class and a struct in C#?', 'Classes are value types; structs are reference types', 'Classes are reference types; structs are value types', 'Classes do not support inheritance; structs do', 'They are identical in C#', 'B', 1, '2026-07-30 19:13:05'),
(383, 16, 'How do you define properties with auto-implemented getters and setters in C#?', 'public int Age { get; set; }', 'public int Age(get, set);', 'public int Age;', 'int Age { get; set; }', 'A', 1, '2026-07-30 19:13:05'),
(384, 16, 'Which keyword defines inheritance in C# class structures?', 'extends', 'implements', 'Colon symbol `:`', 'inherits', 'C', 1, '2026-07-30 19:13:05'),
(385, 16, 'Does C# support multiple inheritance of classes?', 'Yes, natively', 'No, classes can only inherit from one base class', 'Yes, using structs', 'Only in console applications', 'B', 1, '2026-07-30 19:13:05'),
(386, 16, 'Which keyword defines a variable whose value is determined at compile-time and cannot be altered?', 'readonly', 'const', 'final', 'static', 'B', 1, '2026-07-30 19:13:05'),
(387, 16, 'Which keyword defines a class member whose value can be assigned at declaration or in a constructor only?', 'readonly', 'const', 'final', 'static', 'A', 1, '2026-07-30 19:13:05'),
(388, 16, 'What represents a null reference or absence of an object assignment in C#?', 'nil', 'null', 'None', 'undefined', 'B', 1, '2026-07-30 19:13:05'),
(389, 16, 'What is garbage collection in C#?', 'A compiler checking syntax', 'The automatic management of memory, reclaiming unused heap objects', 'A tool to compile DLL files', 'The folder cleaning script', 'B', 1, '2026-07-30 19:13:05'),
(390, 16, 'Which data type represents string values in C#?', 'String', 'string', 'Both A and B', 'char[]', 'C', 1, '2026-07-30 19:13:05'),
(391, 16, 'Which C# collection represents a dynamically resizing list?', 'ArrayList', 'List<T>', 'Array', 'HashSet', 'B', 1, '2026-07-30 19:13:05'),
(392, 16, 'How do you handle exceptions in C#?', 'try-except', 'try-catch', 'throw-catch', 'handle', 'B', 1, '2026-07-30 19:13:05'),
(393, 16, 'What access modifier makes members accessible from any code in the same assembly or another assembly referencing it?', 'private', 'internal', 'protected', 'public', 'D', 1, '2026-07-30 19:13:05'),
(394, 16, 'What access modifier restricts member access to code in the same assembly only?', 'private', 'internal', 'protected', 'public', 'B', 1, '2026-07-30 19:13:05'),
(395, 16, 'Which keyword is used to declare that a method can be overridden by subclass classes?', 'override', 'virtual', 'abstract', 'new', 'B', 1, '2026-07-30 19:13:05'),
(396, 16, 'Which keyword in subclasses overrides a base class virtual method?', 'override', 'virtual', 'abstract', 'extend', 'A', 1, '2026-07-30 19:13:05'),
(397, 16, 'How do you check if an object reference is of a certain type in C#?', 'is operator', 'as operator', 'typeof()', 'instanceof', 'A', 1, '2026-07-30 19:13:05'),
(398, 16, 'What keyword attempts to cast an object to a specific type, returning null if casting fails?', 'is', 'as', 'cast', 'convert', 'B', 1, '2026-07-30 19:13:05'),
(399, 16, 'Which operator handles null-coalescing in C# (e.g. `x ?? y`)?', '?', '??', '?:', '&&', 'B', 1, '2026-07-30 19:13:05'),
(400, 16, 'What is the default value of a boolean variable in C#?', 'true', 'false', 'null', '0', 'B', 1, '2026-07-30 19:13:05'),
(401, 17, 'What is Node.js?', 'A front-end JavaScript library', 'An open-source, cross-platform JavaScript runtime environment', 'A database wrapper dialect', 'A web browser extension', 'B', 1, '2026-07-30 19:13:05'),
(402, 17, 'Which JavaScript engine powers Node.js?', 'SpiderMonkey', 'Chakra', 'V8', 'JavaScriptCore', 'C', 1, '2026-07-30 19:13:05'),
(403, 17, 'What is the default package manager for Node.js projects?', 'composer', 'pip', 'npm', 'yarn', 'C', 1, '2026-07-30 19:13:05'),
(404, 17, 'Which CLI command initializes a new Node.js project and creates a package.json file?', 'node init', 'npm init', 'npm install', 'node start', 'B', 1, '2026-07-30 19:13:05'),
(405, 17, 'Which core Node.js module handles file system operations?', 'path', 'fs', 'http', 'os', 'B', 1, '2026-07-30 19:13:05'),
(406, 17, 'How do you import a module named \'fs\' in CommonJS syntax in Node.js?', 'import fs from \'fs\'', 'require(\'fs\')', 'const fs = require(\'fs\');', 'load(\'fs\')', 'C', 1, '2026-07-30 19:13:05'),
(407, 17, 'How do you export a function \'myFunc\' from a module file in CommonJS?', 'export myFunc;', 'module.exports = myFunc;', 'export default myFunc;', 'exports.myFunc = myFunc;', 'B', 1, '2026-07-30 19:13:05'),
(408, 17, 'Which core module is used to create an HTTP server in Node.js?', 'net', 'http', 'fs', 'url', 'B', 1, '2026-07-30 19:13:05'),
(409, 17, 'What is the Event Loop in Node.js?', 'A loop that counts event handlers', 'A mechanism that executes asynchronous callbacks in a single-threaded runtime', 'A file caching loader', 'An HTTP server thread', 'B', 1, '2026-07-30 19:13:05'),
(410, 17, 'Which command executes a JavaScript file named \'index.js\' in Node.js?', 'run index.js', 'npm index.js', 'node index.js', 'execute index.js', 'C', 1, '2026-07-30 19:13:05'),
(411, 17, 'What does npm stand for?', 'Node Package Manager', 'Node Project Manager', 'New Project Manager', 'Net Package Manager', 'A', 1, '2026-07-30 19:13:05'),
(412, 17, 'What is the role of `node_modules` directory in a Node.js project?', 'Stores database records', 'Stores all downloaded external package dependencies', 'Stores user upload documents', 'Stores compiled HTML files', 'B', 1, '2026-07-30 19:13:05'),
(413, 17, 'Which function runs code asynchronously after a specified number of milliseconds?', 'setTimeout()', 'setInterval()', 'wait()', 'sleep()', 'A', 1, '2026-07-30 19:13:05'),
(414, 17, 'Which Node.js object represents global context variables (similar to window object in browsers)?', 'window', 'document', 'global', 'root', 'C', 1, '2026-07-30 19:13:05'),
(415, 17, 'How do you read environment variables from a Node.js script?', 'process.env.VARIABLE_NAME', 'global.env.VARIABLE_NAME', 'env.VARIABLE_NAME', 'node.env.VARIABLE_NAME', 'A', 1, '2026-07-30 19:13:05'),
(416, 17, 'Which method of core \'path\' module joins multiple file path segments into a normalized path?', 'path.join()', 'path.concat()', 'path.merge()', 'path.resolve()', 'A', 1, '2026-07-30 19:13:05'),
(417, 17, 'What is the file name containing specific package version locks in Node.js projects?', 'package.json', 'package-lock.json', 'npm-shrinkwrap.json', 'dependencies.lock', 'B', 1, '2026-07-30 19:13:05'),
(418, 17, 'Which core Node.js module provides utilities for operating system information?', 'os', 'sys', 'system', 'host', 'A', 1, '2026-07-30 19:13:05'),
(419, 17, 'What is a callback function in Node.js?', 'A function passed as an argument to be executed after an asynchronous operation completes', 'A function returned from a loop', 'A static method', 'A routing rule', 'A', 1, '2026-07-30 19:13:05'),
(420, 17, 'How do you install an external package named \'lodash\' and save it to package.json dependencies?', 'npm add lodash', 'npm install lodash', 'node install lodash', 'npm get lodash', 'B', 1, '2026-07-30 19:13:05'),
(421, 17, 'Which global variable exposes the directory path of the current module file in Node.js?', '__filename', '__dirname', 'process.cwd()', 'module.path', 'B', 1, '2026-07-30 19:13:05'),
(422, 17, 'Which global variable exposes the absolute file path of the current module file in Node.js?', '__filename', '__dirname', 'process.file', 'module.file', 'A', 1, '2026-07-30 19:13:05'),
(423, 17, 'What is the purpose of the \'process\' object in Node.js?', 'Compiles CSS styling', 'Exposes current execution process state and control', 'Connects to MongoDB databases', 'Manages user login sessions', 'B', 1, '2026-07-30 19:13:05'),
(424, 17, 'How do you terminate a running Node.js process programmatically?', 'process.kill()', 'process.exit()', 'node.stop()', 'global.exit()', 'B', 1, '2026-07-30 19:13:05'),
(425, 17, 'Which method checks if an object is an instance of a Buffer in Node.js?', 'Buffer.isBuffer()', 'Buffer.check()', 'Buffer.isObject()', 'typeof Buffer', 'A', 1, '2026-07-30 19:13:05'),
(426, 18, 'What does SQL stand for?', 'Strong Query Language', 'Structured Query Language', 'Structured Question Language', 'System Query Language', 'B', 1, '2026-07-30 19:13:05'),
(427, 18, 'Which SQL statement is used to retrieve data from a database?', 'SELECT', 'GET', 'RETRIEVE', 'EXTRACT', 'A', 1, '2026-07-30 19:13:05'),
(428, 18, 'Which SQL clause is used to filter records based on a condition?', 'HAVING', 'WHERE', 'GROUP BY', 'ORDER BY', 'B', 1, '2026-07-30 19:13:05'),
(429, 18, 'Which SQL keyword is used to sort the result-set in ascending or descending order?', 'SORT BY', 'ORDER BY', 'GROUP BY', 'ARRANGE', 'B', 1, '2026-07-30 19:13:05'),
(430, 18, 'Which SQL statement is used to insert new records into a table?', 'INSERT INTO', 'ADD RECORD', 'NEW ROW', 'INSERT ROW', 'A', 1, '2026-07-30 19:13:05'),
(431, 18, 'Which SQL statement is used to update existing records in a table?', 'UPDATE', 'SAVE', 'MODIFY', 'ALTER', 'A', 1, '2026-07-30 19:13:05'),
(432, 18, 'Which SQL statement is used to delete records from a table?', 'DELETE', 'REMOVE', 'DROP', 'TRUNCATE', 'A', 1, '2026-07-30 19:13:05'),
(433, 18, 'What does the aggregate function `COUNT()` return?', 'The sum of column values', 'The total number of rows matching the query', 'The average value', 'The maximum value', 'B', 1, '2026-07-30 19:13:05'),
(434, 18, 'Which SQL constraint uniquely identifies each record in a database table?', 'UNIQUE KEY', 'FOREIGN KEY', 'PRIMARY KEY', 'NOT NULL', 'C', 1, '2026-07-30 19:13:05'),
(435, 18, 'Which SQL constraint links a column in one table to the primary key of another table?', 'PRIMARY KEY', 'FOREIGN KEY', 'UNIQUE KEY', 'CHECK', 'B', 1, '2026-07-30 19:13:05'),
(436, 18, 'Which JOIN returns all records from the left table and the matched records from the right table?', 'INNER JOIN', 'RIGHT JOIN', 'LEFT JOIN', 'FULL OUTER JOIN', 'C', 1, '2026-07-30 19:13:05'),
(437, 18, 'Which JOIN returns only the records that have matching values in both tables?', 'INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'FULL JOIN', 'A', 1, '2026-07-30 19:13:05'),
(438, 18, 'How do you select all columns from a table named \'Customers\' in SQL?', 'SELECT all FROM Customers', 'SELECT * FROM Customers', 'SELECT [all] FROM Customers', 'SELECT Customers.*', 'B', 1, '2026-07-30 19:13:05'),
(439, 18, 'Which SQL clause is used to group rows that have the same values into summary rows?', 'ORDER BY', 'GROUP BY', 'HAVING', 'WHERE', 'B', 1, '2026-07-30 19:13:05'),
(440, 18, 'Which SQL clause is used to filter group summary rows returned by a GROUP BY clause?', 'WHERE', 'HAVING', 'FILTER', 'SORT', 'B', 1, '2026-07-30 19:13:05'),
(441, 18, 'Which SQL keyword is used to return only distinct (different) values?', 'UNIQUE', 'DISTINCT', 'DIFFERENT', 'ONLY', 'B', 1, '2026-07-30 19:13:05'),
(442, 18, 'Which operator searches for a specified pattern in a column using wildcards?', 'IN', 'LIKE', 'BETWEEN', 'MATCH', 'B', 1, '2026-07-30 19:13:05'),
(443, 18, 'What wildcard character represents zero, one, or multiple characters in SQL LIKE pattern matches?', 'Question mark `?`', 'Percent sign `%`', 'Asterisk `*`', 'Underscore `_`', 'B', 1, '2026-07-30 19:13:05'),
(444, 18, 'What wildcard character represents a single character in SQL LIKE pattern matches?', 'Question mark `?`', 'Percent sign `%`', 'Asterisk `*`', 'Underscore `_`', 'D', 1, '2026-07-30 19:13:05'),
(445, 18, 'Which SQL statement deletes a table structure and all its data permanently?', 'DELETE TABLE', 'DROP TABLE', 'REMOVE TABLE', 'TRUNCATE TABLE', 'B', 1, '2026-07-30 19:13:05'),
(446, 18, 'Which SQL statement deletes all data inside a table without deleting the table structure itself?', 'DROP TABLE', 'TRUNCATE TABLE', 'DELETE TABLE', 'REMOVE TABLE', 'B', 1, '2026-07-30 19:13:05'),
(447, 18, 'Which SQL keyword is used to modify the structure of an existing table (like adding a column)?', 'MODIFY TABLE', 'ALTER TABLE', 'UPDATE TABLE', 'CHANGE TABLE', 'B', 1, '2026-07-30 19:13:05'),
(448, 18, 'Which operator filters values within a specified range (inclusive)?', 'RANGE', 'BETWEEN', 'IN', 'LIKE', 'B', 1, '2026-07-30 19:13:05'),
(449, 18, 'Which operator specifies multiple possible values for a column condition (shortcut for multiple ORs)?', 'BETWEEN', 'IN', 'LIKE', 'ANY', 'B', 1, '2026-07-30 19:13:05'),
(450, 18, 'What is the default sorting order of the `ORDER BY` clause?', 'DESC', 'ASC', 'Random', 'Index order', 'B', 1, '2026-07-30 19:13:05'),
(451, 19, 'What is MySQL?', 'A frontend CSS framework', 'An open-source relational database management system (RDBMS)', 'A NoSQL database', 'A server programming compiler', 'B', 1, '2026-07-30 19:13:05'),
(452, 19, 'What is the default port number for MySQL connections?', '8080', '1521', '3306', '5432', 'C', 1, '2026-07-30 19:13:05'),
(453, 19, 'Which keyword is used to auto-increment a primary key column value in MySQL?', 'AUTOINCREMENT', 'SERIAL', 'AUTO_INCREMENT', 'IDENTITY', 'C', 1, '2026-07-30 19:13:05'),
(454, 19, 'Which MySQL storage engine is the default in modern versions (5.5+) supporting transactions and foreign keys?', 'MyISAM', 'InnoDB', 'Memory', 'Archive', 'B', 1, '2026-07-30 19:13:05'),
(455, 19, 'Which MySQL command displays all existing databases on the server?', 'SHOW DATABASES;', 'LIST DATABASES;', 'DISPLAY DATABASES;', 'GET DATABASES;', 'A', 1, '2026-07-30 19:13:05'),
(456, 19, 'Which MySQL command displays all tables within the active database?', 'LIST TABLES;', 'SHOW TABLES;', 'DISPLAY TABLES;', 'GET TABLES;', 'B', 1, '2026-07-30 19:13:05'),
(457, 19, 'Which command tells MySQL to select a specific database to query?', 'SELECT database_name;', 'USE database_name;', 'CONNECT database_name;', 'DATABASE database_name;', 'B', 1, '2026-07-30 19:13:05'),
(458, 19, 'Which keyword restricts the number of rows returned by a query in MySQL?', 'TOP', 'ROWNUM', 'LIMIT', 'FETCH FIRST', 'C', 1, '2026-07-30 19:13:05'),
(459, 19, 'How do you specify the offset (starting row index) in MySQL LIMIT queries?', 'LIMIT offset, row_count', 'LIMIT row_count OFFSET offset', 'Both A and B', 'LIMIT offset', 'C', 1, '2026-07-30 19:13:05'),
(460, 19, 'Which command displays the column structure of a table named \'users\' in MySQL?', 'DESCRIBE users;', 'EXPLAIN users;', 'SHOW COLUMNS FROM users;', 'All of the above', 'D', 1, '2026-07-30 19:13:05'),
(461, 19, 'Which function returns the ID generated by the last AUTO_INCREMENT insert query in the current MySQL session?', 'LAST_INSERT_ID()', 'LAST_ID()', 'GET_LAST_ID()', 'MAX(id)', 'A', 1, '2026-07-30 19:13:05'),
(462, 19, 'What is the MySQL command line client execution syntax to log in as user \'root\' prompting for password?', 'mysql -u root -p', 'mysql login root', 'connect root', 'mysql -p root', 'A', 1, '2026-07-30 19:13:05'),
(463, 19, 'Which function returns the current date and time in MySQL?', 'NOW()', 'CURRENT_TIME()', 'TODAY()', 'DATE()', 'A', 1, '2026-07-30 19:13:05'),
(464, 19, 'Which function string-concatenates multiple arguments in MySQL?', 'CONCAT()', 'CONCAT_WS()', 'Both A and B', 'JOIN()', 'C', 1, '2026-07-30 19:13:05'),
(465, 19, 'Which MySQL storage engine is optimized for high-speed read-heavy operations but lacks transaction support?', 'InnoDB', 'MyISAM', 'Memory', 'CSV', 'B', 1, '2026-07-30 19:13:05'),
(466, 19, 'What type of relational database join is represented by comma-separated table listings without join conditions in MySQL (e.g. `SELECT * FROM A, B`)?', 'INNER JOIN', 'CROSS JOIN (Cartesian product)', 'LEFT JOIN', 'UNION', 'B', 1, '2026-07-30 19:13:05'),
(467, 19, 'Which MySQL function returns the current database name selected?', 'DATABASE()', 'CURRENT_DB()', 'DB_NAME()', 'ACTIVE()', 'A', 1, '2026-07-30 19:13:05'),
(468, 19, 'How do you escape special characters (like quotes) inside string literals in MySQL queries?', 'Precede them with backslash `\\`', 'Precede them with slash `/`', 'Double quotes', 'Precede them with hash `#`', 'A', 1, '2026-07-30 19:13:05'),
(469, 19, 'What is the index size limit check comment symbol in MySQL configuration files?', '--', '#', 'Both A and B', '/* */', 'C', 1, '2026-07-30 19:13:05'),
(470, 19, 'Which command adds a new index to speed up select queries on a column in MySQL?', 'CREATE INDEX index_name ON table (column);', 'ADD INDEX ON table (column);', 'INDEX CREATE index_name;', 'ALTER TABLE table ADD INDEX (column);', 'A', 1, '2026-07-30 19:13:05'),
(471, 19, 'What type of database indexing is standard for primary keys in MySQL InnoDB tables?', 'Clustered Index', 'Non-clustered Index', 'Hash Index', 'Spatial Index', 'A', 1, '2026-07-30 19:13:05'),
(472, 19, 'Which command deletes a database named \'test_db\' permanently in MySQL?', 'DELETE DATABASE test_db;', 'DROP DATABASE test_db;', 'REMOVE DATABASE test_db;', 'CLEAR DATABASE test_db;', 'B', 1, '2026-07-30 19:13:05'),
(473, 19, 'Which function extracts the year from a date value in MySQL?', 'YEAR(date)', 'EXTRACT(YEAR FROM date)', 'Both A and B', 'DATE_YEAR(date)', 'C', 1, '2026-07-30 19:13:05'),
(474, 19, 'How do you specify string comparisons matching case-insensitively in MySQL where columns have binary collation?', 'Use LIKE binary', 'Use COLLATE helper', 'Standard LIKE is case-insensitive by default in standard collations', 'Standard comparisons are always case-sensitive', 'C', 1, '2026-07-30 19:13:05'),
(475, 19, 'Which MySQL command updates a user password credential?', 'ALTER USER \'user\'@\'host\' IDENTIFIED BY \'new_password\';', 'UPDATE PASSWORD \'new_pass\';', 'SET USER PASSWORD = \'new_pass\';', 'CHANGE PASSWORD FOR \'user\';', 'A', 1, '2026-07-30 19:13:05'),
(476, 20, 'What is MongoDB?', 'A relational database management system', 'A document-oriented NoSQL database system', 'A CSS styling template engine', 'A routing middleware package', 'B', 1, '2026-07-30 19:13:05'),
(477, 20, 'What format does MongoDB use to store data documents internally?', 'XML', 'JSON', 'BSON (Binary JSON)', 'CSV', 'C', 1, '2026-07-30 19:13:05'),
(478, 20, 'In MongoDB, what is the equivalent concept of a relational database \'table\'?', 'Document', 'Collection', 'Database', 'Index', 'B', 1, '2026-07-30 19:13:05'),
(479, 20, 'In MongoDB, what is the equivalent concept of a relational database \'row\'?', 'Document', 'Collection', 'Field', 'Row-cell', 'A', 1, '2026-07-30 19:13:05'),
(480, 20, 'Which field acts as the mandatory primary key unique identifier in every MongoDB document?', '_id', 'id', 'primary_key', 'document_id', 'A', 1, '2026-07-30 19:13:05'),
(481, 20, 'Which database operation method inserts a single document into a collection?', 'insert()', 'insertOne()', 'add()', 'push()', 'B', 1, '2026-07-30 19:13:05'),
(482, 20, 'Which method is used to retrieve documents matching a query filter from a collection in MongoDB?', 'find()', 'get()', 'select()', 'retrieve()', 'A', 1, '2026-07-30 19:13:05'),
(483, 20, 'Which MongoDB shell command displays all collections in the active database?', 'show tables', 'show collections', 'db.list()', 'Both A and B', 'D', 1, '2026-07-30 19:13:05'),
(484, 20, 'Which shell command displays all accessible databases on the server?', 'show dbs', 'show databases', 'Both A and B', 'db.list()', 'C', 1, '2026-07-30 19:13:05'),
(485, 20, 'Which command selects a specific database workspace named \'test_db\' in MongoDB shell?', 'use test_db', 'db.connect(\'test_db\')', 'select test_db', 'database test_db', 'A', 1, '2026-07-30 19:13:05'),
(486, 20, 'Which update operator replaces the value of a field in MongoDB documents?', '$replace', '$set', '$update', '$push', 'B', 1, '2026-07-30 19:13:05'),
(487, 20, 'Which update operator appends a value to an array field in MongoDB documents?', '$set', '$push', '$addToSet', '$add', 'B', 1, '2026-07-30 19:13:05'),
(488, 20, 'Which update operator appends values to arrays only if they do not already exist (ensuring uniqueness)?', '$push', '$addToSet', '$set', '$add', 'B', 1, '2026-07-30 19:13:05'),
(489, 20, 'Which operator checks if a field value matches any elements inside a specified array list in MongoDB queries?', '$in', '$match', '$or', '$eq', 'A', 1, '2026-07-30 19:13:05'),
(490, 20, 'Which MongoDB method deletes a single document matching a query filter from a collection?', 'delete()', 'deleteOne()', 'remove()', 'destroy()', 'B', 1, '2026-07-30 19:13:05'),
(491, 20, 'Which method deletes multiple documents matching a query filter in MongoDB?', 'deleteMany()', 'delete()', 'remove()', 'clear()', 'A', 1, '2026-07-30 19:13:05'),
(492, 20, 'Which MongoDB method updates a single document matching filters?', 'updateOne()', 'update()', 'modify()', 'set()', 'A', 1, '2026-07-30 19:13:05'),
(493, 20, 'What does MongoDB\'s `db.collection.find().limit(5)` do?', 'Filters fields returning 5 attributes', 'Restricts the cursor results to a maximum of 5 documents', 'Sets search timeouts', 'Deletes 5 documents', 'B', 1, '2026-07-30 19:13:05'),
(494, 20, 'How do you sort query results in ascending order based on field \'age\' in MongoDB?', 'find().sort({ age: 1 })', 'find().sort({ age: -1 })', 'find().order(\'age\')', 'find().sort(\'age\')', 'A', 1, '2026-07-30 19:13:05'),
(495, 20, 'How do you count the number of documents in a collection in modern MongoDB?', 'count()', 'countDocuments()', 'find().length()', 'db.size()', 'B', 1, '2026-07-30 19:13:05'),
(496, 20, 'What MongoDB shell helper variable references the active database context object?', 'database', 'db', 'mongo', 'conn', 'B', 1, '2026-07-30 19:13:05'),
(497, 20, 'Which comparison operator represents \'greater than\' in MongoDB queries?', '$gt', '$gte', '$lt', '$ne', 'A', 1, '2026-07-30 19:13:05'),
(498, 20, 'Which comparison operator represents \'less than or equal to\' in MongoDB queries?', '$lt', '$lte', '$gt', '$gte', 'B', 1, '2026-07-30 19:13:05'),
(499, 20, 'Which operator performs logical OR checks combining multiple query objects in MongoDB?', '$or', '$and', '$not', '$nor', 'A', 1, '2026-07-30 19:13:05'),
(500, 20, 'What is the default port number for MongoDB server processes?', '3306', '5432', '27017', '28017', 'C', 1, '2026-07-30 19:13:05'),
(501, 21, 'What does the MERN stack stand for?', 'MongoDB, Express, React, Node.js', 'MySQL, Express, React, Node.js', 'MongoDB, Ember, React, Node.js', 'MongoDB, Express, Redux, Node.js', 'A', 1, '2026-07-30 19:13:05'),
(502, 21, 'Which technology in the MERN stack is used to build the front-end user interface?', 'MongoDB', 'Express.js', 'React.js', 'Node.js', 'C', 1, '2026-07-30 19:13:05'),
(503, 21, 'Which technology in the MERN stack provides the database system?', 'MongoDB', 'Express.js', 'React.js', 'Node.js', 'A', 1, '2026-07-30 19:13:05'),
(504, 21, 'Which technology in the MERN stack serves as the back-end application framework?', 'MongoDB', 'Express.js', 'React.js', 'Node.js', 'B', 1, '2026-07-30 19:13:05'),
(505, 21, 'Which technology in the MERN stack is the server runtime environment?', 'MongoDB', 'Express.js', 'React.js', 'Node.js', 'D', 1, '2026-07-30 19:13:05'),
(506, 21, 'How is data transferred between React and the Node/Express server in MERN stack?', 'Using SQL databases directly', 'Via HTTP REST API JSON payloads', 'Using binary files', 'Through CSS stylesheet bindings', 'B', 1, '2026-07-30 19:13:05'),
(507, 21, 'What is Mongoose in a MERN stack application?', 'A frontend state manager', 'An Object Data Modeling (ODM) library for MongoDB and Node.js', 'A web design styling framework', 'An server-side router compiler', 'B', 1, '2026-07-30 19:13:05'),
(508, 21, 'In MERN stack, on which side does React code execute?', 'Client-side (web browser)', 'Server-side (Node.js runtime)', 'Database-side (MongoDB query compiler)', 'Compiler-side', 'A', 1, '2026-07-30 19:13:05'),
(509, 21, 'In MERN stack, on which side does Node.js code execute?', 'Client-side (web browser)', 'Server-side', 'Database-side', 'CSS-side', 'B', 1, '2026-07-30 19:13:05'),
(510, 21, 'What package manager installs modules for both front-end and back-end in MERN stack?', 'composer', 'npm', 'pip', 'maven', 'B', 1, '2026-07-30 19:13:05'),
(511, 21, 'Which port does Express server commonly run on during local MERN development by default convention?', '3000', '5000 (or 8000/8080)', '27017', '80', 'B', 1, '2026-07-30 19:13:05'),
(512, 21, 'Which port does MongoDB daemon run on by default in MERN setups?', '3306', '5432', '27017', '3000', 'C', 1, '2026-07-30 19:13:05'),
(513, 21, 'How do you enable Cross-Origin Resource Sharing (CORS) in Express backends to allow React requests?', 'Using the cors middleware package', 'Using session cookies', 'Using path routing rules', 'Disabling firewall rules', 'A', 1, '2026-07-30 19:13:05'),
(514, 21, 'What format represents records stored in MongoDB in MERN?', 'XML documents', 'BSON documents', 'CSV rows', 'TXT lines', 'B', 1, '2026-07-30 19:13:05'),
(515, 21, 'Which utility tool is commonly used in development to automatically restart the Node.js server when file changes are saved?', 'npm', 'nodemon', 'webpack', 'pm2', 'B', 1, '2026-07-30 19:13:05'),
(516, 21, 'Which object maps schema designs inside MongoDB using Mongoose?', 'Schema', 'Model', 'Collection', 'Table', 'A', 1, '2026-07-30 19:13:05'),
(517, 21, 'Which method compiles a Mongoose schema into a queryable object wrapper linked to a collection?', 'mongoose.compile()', 'mongoose.model()', 'mongoose.schema()', 'new Schema()', 'B', 1, '2026-07-30 19:13:05'),
(518, 21, 'How do you parse incoming request JSON payloads in Express.js?', 'app.use(express.json())', 'app.parseJSON()', 'req.parseBody()', 'bodyParser.string()', 'A', 1, '2026-07-30 19:13:05'),
(519, 21, 'What is the main configuration file where project dependency lists are defined in MERN backend folders?', 'package.json', 'tsconfig.json', 'webpack.config.js', 'angular.json', 'A', 1, '2026-07-30 19:13:05'),
(520, 21, 'Which JS command starts a React development server initialized with Create React App?', 'npm start', 'npm run dev', 'node start', 'npm run build', 'A', 1, '2026-07-30 19:13:05'),
(521, 21, 'Which JS command starts a Vite-based React development server?', 'npm start', 'npm run dev', 'node start', 'npm run build', 'B', 1, '2026-07-30 19:13:05'),
(522, 21, 'How do you handle API calls asynchronously in React components?', 'Using fetch() or axios with async/await inside useEffect Hook', 'Using thread objects', 'Using direct SQL statements', 'Using iframe elements', 'A', 1, '2026-07-30 19:13:05'),
(523, 21, 'What represents a document-level update operation in Mongoose?', 'Model.updateOne()', 'Model.update()', 'Model.modify()', 'Model.change()', 'A', 1, '2026-07-30 19:13:05'),
(524, 21, 'How do you define route parameter patterns like dynamic user IDs in Express?', 'app.get(\'/users/:id\', ...)', 'app.get(\'/users/{id}\', ...)', 'app.get(\'/users/id\', ...)', 'app.get(\'/users/?id\', ...)', 'A', 1, '2026-07-30 19:13:05'),
(525, 21, 'What is the value of `req.params.id` if a user requests `/users/123` with route `/users/:id`?', 'id', '123', 'users', 'null', 'B', 1, '2026-07-30 19:13:05'),
(526, 22, 'What does the MEAN stack stand for?', 'MongoDB, Express, Angular, Node.js', 'MySQL, Express, Angular, Node.js', 'MongoDB, Ember, Angular, Node.js', 'MongoDB, Express, Apache, Node.js', 'A', 1, '2026-07-30 19:13:05'),
(527, 22, 'Which technology in the MEAN stack provides the front-end user interface framework?', 'MongoDB', 'Express.js', 'Angular', 'Node.js', 'C', 1, '2026-07-30 19:13:05'),
(528, 22, 'What is the main language used to author code in Angular client folders in the MEAN stack?', 'Python', 'JavaScript', 'TypeScript', 'PHP', 'C', 1, '2026-07-30 19:13:05'),
(529, 22, 'Which tool is used to compile, run, and test Angular files in a MEAN project?', 'Angular CLI (ng)', 'npm-angular', 'ng-cli', 'ang', 'A', 1, '2026-07-30 19:13:05'),
(530, 22, 'In MEAN stack, how does the Angular client consume data from Node/Express server routes?', 'Using raw SQL statements', 'Via HttpClient REST requests receiving JSON objects', 'Using text files', 'Using websocket stylesheets', 'B', 1, '2026-07-30 19:13:05'),
(531, 22, 'Which decorator defines metadata declarations for a component in Angular?', '@Component', '@Directive', '@NgModule', '@Injectable', 'A', 1, '2026-07-30 19:13:05'),
(532, 22, 'Which decorator declares dependencies eligible for constructor injections in Angular services?', '@Injectable', '@Component', '@NgModule', '@Inject', 'A', 1, '2026-07-30 19:13:05'),
(533, 22, 'Which directive loops over array lists to render items in Angular templates?', '*ngFor', '*ngIf', 'ngList', 'ngRepeat', 'A', 1, '2026-07-30 19:13:05'),
(534, 22, 'Which directive renders elements conditionally in Angular layouts?', '*ngFor', '*ngIf', 'ngStyle', 'ngSwitch', 'B', 1, '2026-07-30 19:13:05'),
(535, 22, 'What is the standard configuration file for Angular workspace options in MEAN?', 'angular.json', 'package.json', 'tsconfig.json', 'webpack.config.js', 'A', 1, '2026-07-30 19:13:05'),
(536, 22, 'Which technology provides back-end API routing in the MEAN stack?', 'MongoDB', 'Express.js', 'Angular', 'Node.js', 'B', 1, '2026-07-30 19:13:05'),
(537, 22, 'Which technology acts as the database layer in the MEAN stack?', 'MongoDB', 'Express.js', 'Angular', 'Node.js', 'A', 1, '2026-07-30 19:13:05'),
(538, 22, 'Which technology is the underlying server engine runtime in the MEAN stack?', 'MongoDB', 'Express.js', 'Angular', 'Node.js', 'D', 1, '2026-07-30 19:13:05'),
(539, 22, 'What type of data binding syncs inputs to class variables using the `[(ngModel)]` syntax in Angular?', 'Property binding', 'Event binding', 'Two-way data binding', 'Interpolation', 'C', 1, '2026-07-30 19:13:05'),
(540, 22, 'Which Angular module must be imported to use forms and input bindings (ngModel)?', 'CommonModule', 'BrowserModule', 'FormsModule', 'HttpClientModule', 'C', 1, '2026-07-30 19:13:05'),
(541, 22, 'Which service is standard for querying backend API data inside Angular components?', 'HttpClient', 'HttpServer', 'FetchService', 'AjaxService', 'A', 1, '2026-07-30 19:13:05'),
(542, 22, 'What object type represents asynchronous data streams returned by HttpClient in Angular?', 'Promise', 'Observable', 'async/await', 'Generator', 'B', 1, '2026-07-30 19:13:05'),
(543, 22, 'How do you subscribe to an Observable to trigger HTTP requests in Angular classes?', 'using .then() method', 'using .subscribe() method', 'using .await() method', 'using .catch() method', 'B', 1, '2026-07-30 19:13:05'),
(544, 22, 'Which command compiles and starts a local Angular development server?', 'ng serve', 'ng start', 'ng build', 'ng serve --prod', 'A', 1, '2026-07-30 19:13:05'),
(545, 22, 'Which command builds production-ready optimized assets for Angular deployment?', 'ng serve', 'ng build', 'ng compile', 'ng run', 'B', 1, '2026-07-30 19:13:05'),
(546, 22, 'What Mongoose ODM library is used in MEAN stack backends to define database models?', 'Mongoose', 'Sequelize', 'Prisma', 'Hibernate', 'A', 1, '2026-07-30 19:13:05'),
(547, 22, 'How do you register route parameters in Angular client routing grids?', '{ path: \'profile/:id\', component: ProfileComponent }', '{ route: \'profile/:id\', load: ProfileComponent }', '{ path: \'profile\', load: ProfileComponent }', '{ url: \'profile/:id\', view: ProfileComponent }', 'A', 1, '2026-07-30 19:13:05'),
(548, 22, 'What is the root class decorator that declares component dependencies, imports, and exports for modularity in Angular?', '@NgModule', '@Component', '@Injectable', '@Module', 'A', 1, '2026-07-30 19:13:05'),
(549, 22, 'Which tag is the mount point placeholder in the main index.html file where Angular bootstrap boots the root component?', '<app-root></app-root>', '<ng-app></ng-app>', '<app-mount></app-mount>', '<root></root>', 'A', 1, '2026-07-30 19:13:05'),
(550, 22, 'Which lifecycle hook is called after Angular initializes component bindings in component classes?', 'ngOnInit', 'ngOnDestroy', 'ngAfterViewInit', 'ngOnChanges', 'A', 1, '2026-07-30 19:13:05'),
(551, 23, 'What is Laravel?', 'A Python framework for data analysis', 'An open-source PHP web framework utilizing MVC patterns', 'A front-end CSS compiler', 'A relational database dialect', 'B', 1, '2026-07-30 19:13:05');
INSERT INTO `questions` (`id`, `question_bank_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `marks`, `created_at`) VALUES
(552, 23, 'Which design pattern does Laravel follow?', 'Singleton pattern', 'Observer pattern', 'MVC (Model-View-Controller)', 'Builder pattern', 'C', 1, '2026-07-30 19:13:05'),
(553, 23, 'What is the name of Laravel\'s command-line interface tool?', 'Composer', 'Artisan', 'Laravel-CLI', 'PHPUnit', 'B', 1, '2026-07-30 19:13:05'),
(554, 23, 'Which command is used to start Laravel\'s built-in local development server?', 'php artisan serve', 'php artisan start', 'php -S localhost:8000', 'laravel serve', 'A', 1, '2026-07-30 19:13:05'),
(555, 23, 'What database tool is used to define, edit, and track database schema structures in Laravel?', 'Migrations', 'Artisan', 'Eloquent ORM', 'Blade templates', 'A', 1, '2026-07-30 19:13:05'),
(556, 23, 'What is the name of Laravel\'s built-in Object-Relational Mapper (ORM)?', 'Doctrine', 'Eloquent', 'Hibernate', 'Prisma', 'B', 1, '2026-07-30 19:13:05'),
(557, 23, 'What template engine does Laravel use for views?', 'Twig', 'Smarty', 'Blade', 'Pug', 'C', 1, '2026-07-30 19:13:05'),
(558, 23, 'In which directory are Laravel web routes defined by default?', 'app/Http/', 'config/', 'routes/web.php', 'resources/views/', 'C', 1, '2026-07-30 19:13:05'),
(559, 23, 'How do you define a route matching a GET request to path \'/home\' in Laravel?', 'Route::get(\'/home\', [HomeController::class, \'index\'])', 'Route::post(\'/home\', ...)', 'Route::match(\'/home\', ...)', 'Route::path(\'/home\', ...)', 'A', 1, '2026-07-30 19:13:05'),
(560, 23, 'What is Composer in Laravel projects?', 'An asset bundler', 'A PHP dependency package manager tool', 'A database engine', 'A routing compiler', 'B', 1, '2026-07-30 19:13:05'),
(561, 23, 'How do you create a new controller class in Laravel using Artisan CLI?', 'php artisan make:controller MyController', 'php artisan create:controller MyController', 'php artisan add:controller MyController', 'php artisan controller MyController', 'A', 1, '2026-07-30 19:13:05'),
(562, 23, 'How do you create a new database model and migration file simultaneously using Artisan?', 'php artisan make:model MyModel -m', 'php artisan create:model MyModel', 'php artisan make:model MyModel --migration', 'Both A and C', 'D', 1, '2026-07-30 19:13:05'),
(563, 23, 'Which environment configuration file stores database credentials in Laravel?', 'config/database.php', '.env', 'composer.json', 'laravel.config', 'B', 1, '2026-07-30 19:13:05'),
(564, 23, 'What is the base model class that Eloquent models must inherit in Laravel?', 'Eloquent\\Model', 'Illuminate\\Database\\Eloquent\\Model', 'App\\Models\\Model', 'Database\\Model', 'B', 1, '2026-07-30 19:13:05'),
(565, 23, 'Which command executes pending database migrations in Laravel?', 'php artisan migrate', 'php artisan db:migrate', 'php artisan run:migrations', 'php artisan schema:update', 'A', 1, '2026-07-30 19:13:05'),
(566, 23, 'Which method retrieves all rows from a database table using Eloquent?', 'Model::all()', 'Model::get()', 'Model::select()', 'Model::find()', 'A', 1, '2026-07-30 19:13:05'),
(567, 23, 'How do you retrieve a single record by its primary key using Eloquent?', 'Model::find(id)', 'Model::get(id)', 'Model::all(id)', 'Model::first(id)', 'A', 1, '2026-07-30 19:13:05'),
(568, 23, 'What syntax is used to output escaped variables inside Blade templates?', '{{ $variable }}', '{!! $variable !!}', '{ $variable }', '[ $variable ]', 'A', 1, '2026-07-30 19:13:05'),
(569, 23, 'What syntax is used to output unescaped raw HTML variables inside Blade templates?', '{{ $variable }}', '{!! $variable !!}', '{ $variable }', '[ $variable ]', 'B', 1, '2026-07-30 19:13:05'),
(570, 23, 'Which command clears Laravel\'s application cache files?', 'php artisan cache:clear', 'php artisan clear:cache', 'php artisan config:cache', 'php artisan route:clear', 'A', 1, '2026-07-30 19:13:05'),
(571, 23, 'What directory holds CSS, JS, and image assets in Laravel projects?', 'public/', 'resources/', 'storage/', 'assets/', 'A', 1, '2026-07-30 19:13:05'),
(572, 23, 'How do you define a dynamic route parameter like a user ID in Laravel routes?', 'Route::get(\'/user/{id}\', ...)', 'Route::get(\'/user/:id\', ...)', 'Route::get(\'/user/id\', ...)', 'Route::get(\'/user/&id\', ...)', 'A', 1, '2026-07-30 19:13:05'),
(573, 23, 'What mechanism is used to filter incoming HTTP requests (like checking auth) before they reach controllers in Laravel?', 'Controllers', 'Middleware', 'Migrations', 'Service Providers', 'B', 1, '2026-07-30 19:13:05'),
(574, 23, 'How do you validate incoming form inputs inside Laravel controllers?', 'Using $request->validate([...])', 'Using Validate::form()', 'Using input->check()', 'Manually in loops', 'A', 1, '2026-07-30 19:13:05'),
(575, 23, 'What is Eloquent\'s relationship method to define a one-to-many relationship?', 'hasOne()', 'belongsTo()', 'hasMany()', 'belongsToMany()', 'C', 1, '2026-07-30 19:13:05'),
(576, 24, 'What is Django?', 'A front-end CSS preprocessor', 'A high-level Python web framework that encourages rapid design and clean, pragmatic development', 'A Java build automation utility', 'A NoSQL database interface', 'B', 1, '2026-07-30 19:13:05'),
(577, 24, 'What architectural pattern does Django follow?', 'MVC (Model-View-Controller)', 'MVT (Model-View-Template)', 'MVVM', 'Singleton', 'B', 1, '2026-07-30 19:13:05'),
(578, 24, 'What is the main command-line script file used to manage Django applications?', 'manage.py', 'django-admin.py', 'wsgi.py', 'urls.py', 'A', 1, '2026-07-30 19:13:05'),
(579, 24, 'Which command starts Django\'s local development server?', 'python manage.py runserver', 'python manage.py start', 'django-admin startserver', 'python manage.py serve', 'A', 1, '2026-07-30 19:13:05'),
(580, 24, 'Which command creates new database migration files based on model changes in Django?', 'python manage.py migrate', 'python manage.py makemigrations', 'python manage.py migrations', 'python manage.py db:update', 'B', 1, '2026-07-30 19:13:05'),
(581, 24, 'Which command applies prepared database migrations in Django?', 'python manage.py migrate', 'python manage.py makemigrations', 'python manage.py db:migrate', 'python manage.py run', 'A', 1, '2026-07-30 19:13:05'),
(582, 24, 'How do you create a new Django app inside an active project folder?', 'python manage.py startapp app_name', 'python manage.py createapp app_name', 'django-admin newapp app_name', 'python manage.py addapp app_name', 'A', 1, '2026-07-30 19:13:05'),
(583, 24, 'In which Django app file do you define database models?', 'views.py', 'models.py', 'admin.py', 'urls.py', 'B', 1, '2026-07-30 19:13:05'),
(584, 24, 'In which file do you configure URL routing paths in a Django app?', 'views.py', 'urls.py', 'settings.py', 'models.py', 'B', 1, '2026-07-30 19:13:05'),
(585, 24, 'What class acts as the base ancestor for all Django models?', 'models.Model', 'models.Base', 'models.Object', 'models.Class', 'A', 1, '2026-07-30 19:13:05'),
(586, 24, 'Which built-in Django feature provides a pre-built web interface to manage application data records?', 'Django Forms', 'Django Admin Portal', 'Django Templates', 'Django Middleware', 'B', 1, '2026-07-30 19:13:05'),
(587, 24, 'How do you define a view function that renders a template in Django views?', 'Using the render() function helper', 'Using response.write()', 'Using template.load()', 'Using html.display()', 'A', 1, '2026-07-30 19:13:05'),
(588, 24, 'What is the main configuration settings file in Django projects?', 'settings.py', 'config.py', 'urls.py', 'wsgi.py', 'A', 1, '2026-07-30 19:13:05'),
(589, 24, 'What database does Django configure by default in new project settings?', 'PostgreSQL', 'MySQL', 'SQLite', 'MongoDB', 'C', 1, '2026-07-30 19:13:05'),
(590, 24, 'How do you retrieve all records from a database table using Django\'s Object-Relational Mapper (ORM)?', 'Model.objects.all()', 'Model.objects.get()', 'Model.objects.filter()', 'Model.all()', 'A', 1, '2026-07-30 19:13:05'),
(591, 24, 'How do you retrieve a single record matching a unique criteria using Django\'s ORM?', 'Model.objects.all()', 'Model.objects.get()', 'Model.objects.filter()', 'Model.find()', 'B', 1, '2026-07-30 19:13:05'),
(592, 24, 'How do you filter records matching criteria using Django\'s ORM?', 'Model.objects.filter(field=value)', 'Model.objects.get(field=value)', 'Model.objects.search()', 'Model.select()', 'A', 1, '2026-07-30 19:13:05'),
(593, 24, 'What syntax displays variables inside Django templates?', '{{ variable }}', '{% variable %}', '{ variable }', '[ variable ]', 'A', 1, '2026-07-30 19:13:05'),
(594, 24, 'What syntax is used to write template tag structures (like loops or conditionals) in Django templates?', '{{ tag }}', '{% tag %}', '{ tag }', '[ tag ]', 'B', 1, '2026-07-30 19:13:05'),
(595, 24, 'Which command creates a superuser account capable of logging into the Django admin interface?', 'python manage.py createsuperuser', 'python manage.py admin', 'python manage.py makeadmin', 'python manage.py user', 'A', 1, '2026-07-30 19:13:05'),
(596, 24, 'What directory path stores static CSS, Javascript, and image assets in Django?', 'assets/', 'static/', 'public/', 'resources/', 'B', 1, '2026-07-30 19:13:05'),
(597, 24, 'How do you define route parameters inside Django `path()` configurations?', 'path(\'user/<int:id>/\', ...)', 'path(\'user/:id/\', ...)', 'path(\'user/{id}/\', ...)', 'path(\'user/id/\', ...)', 'A', 1, '2026-07-30 19:13:05'),
(598, 24, 'What is the purpose of WSGI or ASGI in Django projects?', 'A database connector', 'Interfaces that allow web servers to communicate with Django web applications', 'A testing framework', 'An asset compiling pipeline', 'B', 1, '2026-07-30 19:13:05'),
(599, 24, 'Which Django model field represents a foreign key relationship?', 'models.ForeignKey', 'models.OneToMany', 'models.ReferenceField', 'models.RelationField', 'A', 1, '2026-07-30 19:13:05'),
(600, 24, 'What is Django\'s built-in defense mechanism against Cross-Site Request Forgery (CSRF) in forms?', 'csrf_token tag', 'captcha', 'session authentication', 'firewall', 'A', 1, '2026-07-30 19:13:05'),
(601, 25, 'What is Express.js?', 'A front-end CSS styling framework', 'A minimal, flexible Node.js web application framework providing robust routing and middleware utilities', 'A database engine layer', 'A testing compiler', 'B', 1, '2026-07-30 19:13:05'),
(602, 25, 'How do you initialize an Express application instance in JavaScript?', 'const app = express();', 'const app = new Express();', 'const app = express.init();', 'const app = express.start();', 'A', 1, '2026-07-30 19:13:05'),
(603, 25, 'What function handles routing for HTTP GET requests in Express?', 'app.get()', 'app.post()', 'app.route()', 'app.listen()', 'A', 1, '2026-07-30 19:13:05'),
(604, 25, 'What function starts the server and listens for incoming requests on a specified port?', 'app.get()', 'app.listen()', 'app.start()', 'app.connect()', 'B', 1, '2026-07-30 19:13:05'),
(605, 25, 'In an Express route handler `(req, res) => { }`, what does the `req` object represent?', 'The database connection request object', 'The HTTP request object (containing headers, parameters, body)', 'The HTTP response object', 'The routing engine controller', 'B', 1, '2026-07-30 19:13:05'),
(606, 25, 'In an Express route handler `(req, res) => { }`, what does the `res` object represent?', 'The server configuration object', 'The HTTP response object (used to send data back to the client)', 'The database results object', 'The router resolver', 'B', 1, '2026-07-30 19:13:05'),
(607, 25, 'Which method of the `res` object is used to send JSON data back to client requests?', 'res.send()', 'res.json()', 'res.write()', 'res.print()', 'B', 1, '2026-07-30 19:13:05'),
(608, 25, 'Which method is used to send HTTP status codes along with response data in Express?', 'res.status(code).send(data)', 'res.code(code).send(data)', 'res.statusCode = code', 'Both A and C', 'D', 1, '2026-07-30 19:13:05'),
(609, 25, 'What is middleware in Express.js?', 'A database querying mechanism', 'Functions that have access to request and response objects, executing sequentially in request pipelines', 'A browser layout grid', 'A package installer', 'B', 1, '2026-07-30 19:13:05'),
(610, 25, 'Which method binds middleware globally to the Express application instance?', 'app.use()', 'app.bind()', 'app.add()', 'app.middleware()', 'A', 1, '2026-07-30 19:13:05'),
(611, 25, 'Which method terminates the request-response cycle and sends text strings back in Express?', 'res.send()', 'res.end()', 'res.write()', 'Both A and B', 'D', 1, '2026-07-30 19:13:05'),
(612, 25, 'How do you read route path parameters like a dynamic user ID in Express (e.g. route is `/users/:id`)?', 'req.body.id', 'req.params.id', 'req.query.id', 'req.path.id', 'B', 1, '2026-07-30 19:13:05'),
(613, 25, 'How do you read query string parameters (like `?search=term`) in Express?', 'req.body.search', 'req.query.search', 'req.params.search', 'req.headers.search', 'B', 1, '2026-07-30 19:13:05'),
(614, 25, 'How do you read form data or JSON payloads submitted in POST requests in Express?', 'req.params', 'req.query', 'req.body', 'req.headers', 'C', 1, '2026-07-30 19:13:05'),
(615, 25, 'Which middleware package is standard to parse cookies in Express?', 'cookie-parser', 'express-session', 'cors', 'body-parser', 'A', 1, '2026-07-30 19:13:05'),
(616, 25, 'Which middleware package handles session management and session variables?', 'cookie-parser', 'express-session', 'cors', 'passport', 'B', 1, '2026-07-30 19:13:05'),
(617, 25, 'How do you serve static files (like CSS or images) in Express?', 'Using built-in `express.static()` middleware', 'Using fs module manually', 'Using path routing rules', 'Through templates only', 'A', 1, '2026-07-30 19:13:05'),
(618, 25, 'How do you redirect client requests to another URL in Express?', 'res.redirect()', 'res.header(\'Location\')', 'res.go()', 'res.send(\'redirect\')', 'A', 1, '2026-07-30 19:13:05'),
(619, 25, 'What is the third parameter commonly passed to Express middleware signatures `(req, res, next) => { }`?', 'next', 'callback', 'done', 'process', 'A', 1, '2026-07-30 19:13:05'),
(620, 25, 'Which middleware allows enabling Cross-Origin Resource Sharing (CORS) in Express applications?', 'cors', 'body-parser', 'cookie-parser', 'express-cors', 'A', 1, '2026-07-30 19:13:05'),
(621, 25, 'Which router class is used to create modular, mountable route handlers in Express?', 'express.Router()', 'new Router()', 'express.RouterClass()', 'express.Route()', 'A', 1, '2026-07-30 19:13:05'),
(622, 25, 'How do you define a route matching a POST request in Express?', 'app.post()', 'app.get()', 'app.put()', 'app.delete()', 'A', 1, '2026-07-30 19:13:05'),
(623, 25, 'How do you define a route matching a PUT request in Express?', 'app.post()', 'app.get()', 'app.put()', 'app.delete()', 'C', 1, '2026-07-30 19:13:05'),
(624, 25, 'How do you define a route matching a DELETE request in Express?', 'app.post()', 'app.get()', 'app.put()', 'app.delete()', 'D', 1, '2026-07-30 19:13:05'),
(625, 25, 'What happens if you do not call `next()` or send a response inside an Express middleware?', 'The request terminates immediately', 'The request hangs and eventually times out', 'The next route executes automatically', 'An error is thrown', 'B', 1, '2026-07-30 19:13:05'),
(626, 26, 'What is Next.js?', 'A database interface layer', 'A production-ready React framework for server-side rendering and static site generation', 'A CSS styling framework', 'An execution compiler for python', 'B', 1, '2026-07-30 19:13:05'),
(627, 26, 'What is Server-Side Rendering (SSR) in Next.js?', 'Pre-rendering pages on the client browser runtime', 'Pre-rendering pages on the server for each request', 'Pre-rendering pages at build-time only', 'Database querying during page load', 'B', 1, '2026-07-30 19:13:05'),
(628, 26, 'What is Static Site Generation (SSG) in Next.js?', 'Rendering pages dynamically on the server at each request', 'Pre-rendering pages at build-time once', 'Loading pages in iframe elements', 'None of the above', 'B', 1, '2026-07-30 19:13:05'),
(629, 26, 'How does Next.js handle page routing by default in the classic \'pages\' directory architecture?', 'Using a configuration file routes.js', 'File-system routing (files in pages folder act as paths)', 'Using React Router components manually', 'Via Express middleware integration', 'B', 1, '2026-07-30 19:13:05'),
(630, 26, 'In Next.js Pages Router, what function fetches page data on the server side for every request (SSR)?', 'getStaticProps', 'getServerSideProps', 'getStaticPaths', 'getInitialState', 'B', 1, '2026-07-30 19:13:05'),
(631, 26, 'In Next.js Pages Router, what function fetches page data during the build process (SSG)?', 'getStaticProps', 'getServerSideProps', 'getStaticPaths', 'getInitialProps', 'A', 1, '2026-07-30 19:13:05'),
(632, 26, 'Which component is used to link navigation paths in Next.js?', '<Link>', '<a>', '<NavLink>', '<RouteLink>', 'A', 1, '2026-07-30 19:13:05'),
(633, 26, 'Which directory serves as the root path for static assets (like images) in Next.js?', 'assets/', 'static/', 'public/', 'resources/', 'C', 1, '2026-07-30 19:13:05'),
(634, 26, 'What is the configuration file name for Next.js options?', 'next.config.js', 'next.json', 'package.json', 'tsconfig.json', 'A', 1, '2026-07-30 19:13:05'),
(635, 26, 'Which command compiles and runs the Next.js local development server?', 'npm run dev', 'npm start', 'npm build', 'node start', 'A', 1, '2026-07-30 19:13:05'),
(636, 26, 'Which command builds the Next.js application for production deployment?', 'npm run dev', 'npm run build', 'npm run production', 'npm run start', 'B', 1, '2026-07-30 19:13:05'),
(637, 26, 'Which command starts the Next.js production server after running builds?', 'npm run dev', 'npm run build', 'npm run start', 'node start', 'C', 1, '2026-07-30 19:13:05'),
(638, 26, 'How do you create dynamic routes in Next.js (e.g. path is `/posts/123`) in Pages Router?', 'Create file `posts/[id].js`', 'Create file `posts/id.js`', 'Create file `posts/:id.js`', 'Create file `posts/[...id].js`', 'A', 1, '2026-07-30 19:13:05'),
(639, 26, 'Which hook retrieves query parameters from paths in Next.js components?', 'useRouter', 'useParams', 'useQuery', 'useLocation', 'A', 1, '2026-07-30 19:13:05'),
(640, 26, 'What is the App Router introduced in Next.js 13?', 'A new routing architecture based on files in the `app` directory supporting React Server Components', 'An external routing library', 'An Express.js middleware bundle', 'A stylesheet preprocessor', 'A', 1, '2026-07-30 19:13:05'),
(641, 26, 'By default, what type of components are files inside the Next.js App Router directory?', 'Client Components', 'Server Components', 'Static Components', 'Dynamic Components', 'B', 1, '2026-07-30 19:13:05'),
(642, 26, 'How do you declare a component as a Client Component in Next.js App Router?', 'Add \'use client\' directive at the top of the file', 'Add \'use client\' inside HTML tags', 'Name the file with client prefix', 'Import it differently', 'A', 1, '2026-07-30 19:13:05'),
(643, 26, 'Can Server Components use client-side Hooks like `useState` or `useEffect` in Next.js App Router?', 'Yes, natively', 'No, Hooks require Client Components (\'use client\')', 'Only in development mode', 'Only in layout.js files', 'B', 1, '2026-07-30 19:13:05'),
(644, 26, 'Which component is standard to display responsive images with layout optimizations in Next.js?', '<img>', '<Image>', '<ResponsiveImage>', '<Pic>', 'B', 1, '2026-07-30 19:13:05'),
(645, 26, 'Which file defines a common UI layout wrapper sharing header/footers across pages in Next.js App Router?', 'page.js', 'layout.js', 'template.js', 'global.js', 'B', 1, '2026-07-30 19:13:05'),
(646, 26, 'Which file represents the unique page UI route in Next.js App Router?', 'page.js', 'layout.js', 'route.js', 'index.js', 'A', 1, '2026-07-30 19:13:05'),
(647, 26, 'Which file is used to write custom API endpoints (REST routes) in Next.js App Router?', 'api.js', 'route.js', 'page.js', 'endpoint.js', 'B', 1, '2026-07-30 19:13:05'),
(648, 26, 'What is Incremental Static Regeneration (ISR) in Next.js?', 'Rebuilding the entire website on every user login', 'Rebuilding individual static pages in the background on the fly as requests arrive, without rebuilding the whole site', 'Caching CSS stylesheets on CDN', 'A database query optimizer', 'B', 1, '2026-07-30 19:13:05'),
(649, 26, 'How do you configure ISR in Next.js getStaticProps?', 'Pass a `revalidate` property in the returned object', 'Set static = true', 'Pass timeout in seconds to fetch()', 'Set cache = true', 'A', 1, '2026-07-30 19:13:05'),
(650, 26, 'Which metadata file is configured in App Router to define page headers (HTML head titles)?', 'metadata object inside layout.js or page.js', 'next.config.js', 'package.json', '_document.js', 'A', 1, '2026-07-30 19:13:05'),
(651, 27, 'What is ASP.NET?', 'A web framework for C# and .NET', 'A database management engine', 'A CSS template pipeline', 'A JavaScript library', 'A', 1, '2026-07-30 19:13:05'),
(652, 27, 'What is ASP.NET Core?', 'A legacy version of ASP.NET strictly for Windows', 'A cross-platform, open-source, high-performance re-design of ASP.NET', 'A browser compilation engine', 'A NoSQL database interface', 'B', 1, '2026-07-30 19:13:05'),
(653, 27, 'What is the main execution file entry script class in ASP.NET Core applications?', 'Program.cs', 'Startup.cs', 'App.config', 'Web.config', 'A', 1, '2026-07-30 19:13:05'),
(654, 27, 'Which design pattern is standard for structuring web pages in ASP.NET?', 'MVC (Model-View-Controller)', 'MVVM', 'Singleton', 'Prototype', 'A', 1, '2026-07-30 19:13:05'),
(655, 27, 'What is Razor in ASP.NET?', 'A C# compiler tool', 'A markup syntax that lets you write C# inside HTML views', 'A database wrapper ORM', 'A web server engine', 'B', 1, '2026-07-30 19:13:05'),
(656, 27, 'Which C# ORM is standard for connecting ASP.NET Core to database engines?', 'Dapper', 'Entity Framework Core (EF Core)', 'Hibernate', 'NHibernate', 'B', 1, '2026-07-30 19:13:05'),
(657, 27, 'What is Kestrel in ASP.NET Core?', 'A database optimization tool', 'A lightweight, cross-platform web server engine packaged with .NET', 'A routing compiler', 'An authentication provider', 'B', 1, '2026-07-30 19:13:05'),
(658, 27, 'In ASP.NET Core, where do you register service dependencies for Dependency Injection (DI)?', 'Program.cs (using builder.Services)', 'App.config', 'Controller constructors', 'Views layouts', 'A', 1, '2026-07-30 19:13:05'),
(659, 27, 'How do you register a temporary transient service dependency in .NET DI?', 'builder.Services.AddTransient<IService, Service>();', 'builder.Services.AddSingleton<IService, Service>();', 'builder.Services.AddScoped<IService, Service>();', 'builder.Services.AddService<IService, Service>();', 'A', 1, '2026-07-30 19:13:05'),
(660, 27, 'How do you register a scoped service dependency (instantiated once per client request) in .NET DI?', 'builder.Services.AddTransient<IService, Service>();', 'builder.Services.AddSingleton<IService, Service>();', 'builder.Services.AddScoped<IService, Service>();', 'builder.Services.AddService<IService, Service>();', 'C', 1, '2026-07-30 19:13:05'),
(661, 27, 'How do you register a singleton service dependency (instantiated once and shared globally) in .NET DI?', 'builder.Services.AddTransient<IService, Service>();', 'builder.Services.AddSingleton<IService, Service>();', 'builder.Services.AddScoped<IService, Service>();', 'builder.Services.AddService<IService, Service>();', 'B', 1, '2026-07-30 19:13:05'),
(662, 27, 'Which base class must ASP.NET Core MVC controllers inherit?', 'Controller', 'ControllerBase', 'ApiController', 'BaseController', 'A', 1, '2026-07-30 19:13:05'),
(663, 27, 'Which base class must ASP.NET Core Web API controllers inherit?', 'Controller', 'ControllerBase', 'ApiController', 'BaseController', 'B', 1, '2026-07-30 19:13:05'),
(664, 27, 'Which attribute makes a controller class act as a RESTful HTTP API in ASP.NET Core?', '[ApiController]', '[Route]', '[HttpGet]', '[Api]', 'A', 1, '2026-07-30 19:13:05'),
(665, 27, 'How do you define route paths on controller methods in ASP.NET Core Web API?', '[Route(\"api/[controller]\")]', '[HttpGet(\"path\")]', '[HttpPost(\"path\")]', 'All of the above', 'D', 1, '2026-07-30 19:13:05'),
(666, 27, 'Where are static files (like CSS/JS) located by default in ASP.NET Core project folders?', 'wwwroot/', 'Assets/', 'Static/', 'Public/', 'A', 1, '2026-07-30 19:13:05'),
(667, 27, 'Which method activates serving static files inside `Program.cs`?', 'app.UseStaticFiles();', 'app.MapStaticFiles();', 'app.UseAssets();', 'app.StartStatic();', 'A', 1, '2026-07-30 19:13:05'),
(668, 27, 'What environment configuration file stores connection strings in ASP.NET Core?', 'appsettings.json', 'web.config', 'settings.json', '.env', 'A', 1, '2026-07-30 19:13:05'),
(669, 27, 'What command-line tool executes pending database migrations using Entity Framework CLI?', 'dotnet ef database update', 'dotnet ef migrations add', 'dotnet db update', 'dotnet migrate', 'A', 1, '2026-07-30 19:13:05'),
(670, 27, 'Which MVC folder path holds shared layouts (like _Layout.cshtml)?', 'Views/Shared/', 'Views/Home/', 'Controllers/', 'wwwroot/', 'A', 1, '2026-07-30 19:13:05'),
(671, 27, 'What directory stores Razor Page files in an ASP.NET Core Razor Pages application?', 'Pages/', 'Views/', 'Controllers/', 'wwwroot/', 'A', 1, '2026-07-30 19:13:05'),
(672, 27, 'Which HTTP status code helper does `Ok()` return in Web API controllers?', '200', '201', '400', '404', 'A', 1, '2026-07-30 19:13:05'),
(673, 27, 'Which HTTP status code helper does `NotFound()` return in Web API controllers?', '200', '201', '400', '404', 'D', 1, '2026-07-30 19:13:05'),
(674, 27, 'Which HTTP status code helper does `BadRequest()` return in Web API controllers?', '200', '201', '400', '404', 'C', 1, '2026-07-30 19:13:05'),
(675, 27, 'What mechanism intercepts and processes HTTP requests entering ASP.NET Core applications?', 'Controllers', 'Middleware', 'Filters', 'Views', 'B', 1, '2026-07-30 19:13:05'),
(676, 28, 'What is Spring Boot?', 'A front-end CSS compiler', 'A Java-based framework to simplify bootstrapping and developing production-ready Spring applications', 'A database server engine', 'A web browser client', 'B', 1, '2026-07-30 19:13:05'),
(677, 28, 'Which annotation is the entry point decorator starting a Spring Boot application?', '@SpringBootApplication', '@Configuration', '@EnableAutoConfiguration', '@ComponentScan', 'A', 1, '2026-07-30 19:13:05'),
(678, 28, 'What is the default embedded servlet container (web server) in Spring Boot web projects?', 'Tomcat', 'Jetty', 'Undertow', 'Kestrel', 'A', 1, '2026-07-30 19:13:05'),
(679, 28, 'Which build automation tool configures dependencies inside a \'pom.xml\' file in Spring Boot?', 'Gradle', 'Maven', 'Ant', 'NPM', 'B', 1, '2026-07-30 19:13:05'),
(680, 28, 'Which build automation tool configures dependencies inside a \'build.gradle\' file in Spring Boot?', 'Gradle', 'Maven', 'Ant', 'NPM', 'A', 1, '2026-07-30 19:13:05'),
(681, 28, 'What are Spring Boot \'Starters\'?', 'Pre-packaged dependency descriptors to quickly bootstrap features', 'Initialization method hooks', 'Startup templates', 'Database seeders', 'A', 1, '2026-07-30 19:13:05'),
(682, 28, 'Which annotation registers a class as a RESTful web controller in Spring Boot?', '@RestController', '@Controller', '@Component', '@Service', 'A', 1, '2026-07-30 19:13:05'),
(683, 28, 'Which annotation maps incoming HTTP GET requests to handler methods in Spring Boot controllers?', '@GetMapping', '@PostMapping', '@RequestMapping', '@PutMapping', 'A', 1, '2026-07-30 19:13:05'),
(684, 28, 'Which annotation maps incoming HTTP POST requests to handler methods?', '@GetMapping', '@PostMapping', '@RequestMapping', '@PutMapping', 'B', 1, '2026-07-30 19:13:05'),
(685, 28, 'What configuration file stores environment-specific properties (database host, port) in Spring Boot?', 'application.properties or application.yml', 'pom.xml', 'settings.xml', 'web.xml', 'A', 1, '2026-07-30 19:13:05'),
(686, 28, 'Which Spring Boot dependency manages database connections using Hibernate?', 'Spring Boot Starter Data JPA', 'Spring Boot Starter Web', 'Spring Boot Starter JDBC', 'Spring Boot Starter Security', 'A', 1, '2026-07-30 19:13:05'),
(687, 28, 'Which interface manages standard database CRUD operations in Spring Data JPA?', 'CrudRepository', 'JpaRepository', 'Both A and B', 'DatabaseRepository', 'C', 1, '2026-07-30 19:13:05'),
(688, 28, 'Which annotation injects class dependencies automatically (Dependency Injection) in Spring Boot?', '@Inject', '@Autowired', '@Resource', '@Component', 'B', 1, '2026-07-30 19:13:05'),
(689, 28, 'Which annotation registers a class as a Spring Bean holding business logic?', '@Service', '@Repository', '@Controller', '@Bean', 'A', 1, '2026-07-30 19:13:05'),
(690, 28, 'Which annotation registers a class as a database access repository bean?', '@Service', '@Repository', '@Controller', '@Bean', 'B', 1, '2026-07-30 19:13:05'),
(691, 28, 'Which annotation maps dynamic path variables in URL paths to method parameters?', '@PathVariable', '@RequestParam', '@RequestBody', '@Header', 'A', 1, '2026-07-30 19:13:05'),
(692, 28, 'Which annotation extracts query string parameters (like `?name=val`) in Spring Boot?', '@PathVariable', '@RequestParam', '@RequestBody', '@Header', 'B', 1, '2026-07-30 19:13:05'),
(693, 28, 'Which annotation deserializes incoming JSON request payloads to Java objects?', '@PathVariable', '@RequestParam', '@RequestBody', '@Header', 'C', 1, '2026-07-30 19:13:05'),
(694, 28, 'What is the default execution port of Spring Boot web servers?', '8080', '3000', '8000', '80', 'A', 1, '2026-07-30 19:13:05'),
(695, 28, 'Which tool provides endpoints to inspect application health, metrics, and configurations in Spring Boot?', 'Spring Boot DevTools', 'Spring Boot Actuator', 'Spring Boot Starter Test', 'Spring Boot CLI', 'B', 1, '2026-07-30 19:13:05'),
(696, 28, 'Which module enables hot-swapping class edits and live-reload during local development in Spring Boot?', 'Spring Boot DevTools', 'Spring Boot Actuator', 'Spring Boot Starter Test', 'Lombok', 'A', 1, '2026-07-30 19:13:05'),
(697, 28, 'Which annotation creates class getter and setter methods automatically at compile-time (often used with Java model classes)?', '@Getter', '@Setter', '@Data (Lombok)', 'All of the above', 'D', 1, '2026-07-30 19:13:05'),
(698, 28, 'What is Spring IoC (Inversion of Control) Container?', 'A database connection pool', 'The core context that manages, instantiates, and configures application beans', 'A routing engine', 'A security filter', 'B', 1, '2026-07-30 19:13:05'),
(699, 28, 'How do you customize the context path or port of a Spring Boot server?', 'Modify server.port and server.servlet.context-path in application.properties', 'Modify pom.xml configuration', 'Add class arguments inside Main', 'Configure firewall rules', 'A', 1, '2026-07-30 19:13:05'),
(700, 28, 'Which annotation is used on configuration classes to define Spring Beans programmatically?', '@Configuration', '@Component', '@Controller', '@Service', 'A', 1, '2026-07-30 19:13:05'),
(701, 29, 'What is Flask?', 'A heavy Java framework', 'A lightweight Python WSGI micro web framework', 'A JavaScript library', 'A relational database utility', 'B', 1, '2026-07-30 19:13:05'),
(702, 29, 'Who created the Flask framework?', 'Guido van Rossum', 'Armin Ronacher (Pocoo)', 'Taylor Otwell', 'Evan You', 'B', 1, '2026-07-30 19:13:05'),
(703, 29, 'How do you initialize a Flask application instance in Python?', 'app = Flask(__name__)', 'app = new Flask()', 'app = Flask.init()', 'app = Flask.start()', 'A', 1, '2026-07-30 19:13:05'),
(704, 29, 'Which decorator maps URL paths to view functions in Flask?', '@app.route()', '@app.path()', '@app.get()', '@app.link()', 'A', 1, '2026-07-30 19:13:05'),
(705, 29, 'What is the default port number of Flask\'s local development server?', '5000', '8000', '3000', '8080', 'A', 1, '2026-07-30 19:13:05'),
(706, 29, 'Which template engine does Flask use to render HTML files?', 'Twig', 'Blade', 'Jinja2', 'Mustache', 'C', 1, '2026-07-30 19:13:05'),
(707, 29, 'Which function renders an external HTML file, passing variables to template contexts in Flask?', 'render()', 'render_template()', 'display()', 'load_html()', 'B', 1, '2026-07-30 19:13:05'),
(708, 29, 'In which directory must HTML templates be located by default in Flask projects?', 'views/', 'templates/', 'static/', 'public/', 'B', 1, '2026-07-30 19:13:05'),
(709, 29, 'In which directory are static CSS, JavaScript, and image files stored in Flask?', 'assets/', 'static/', 'public/', 'resources/', 'B', 1, '2026-07-30 19:13:05'),
(710, 29, 'How do you read query string parameters (like `?search=term`) in Flask?', 'request.args.get(\'search\')', 'request.form.get(\'search\')', 'request.query.get(\'search\')', 'request.params[\'search\']', 'A', 1, '2026-07-30 19:13:05'),
(711, 29, 'How do you read form data submitted in HTTP POST requests in Flask?', 'request.args.get(\'field\')', 'request.form.get(\'field\')', 'request.body.get(\'field\')', 'request.json.get(\'field\')', 'B', 1, '2026-07-30 19:13:05'),
(712, 29, 'How do you read JSON payloads submitted to Flask APIs?', 'request.form.get(\'field\')', 'request.json.get(\'field\')', 'request.args.get(\'field\')', 'request.data.field', 'B', 1, '2026-07-30 19:13:05'),
(713, 29, 'Which object must be imported from \'flask\' to access request parameters, forms, and JSON payloads?', 'req', 'request', 'http_request', 'payload', 'B', 1, '2026-07-30 19:13:05'),
(714, 29, 'How do you return JSON responses from Flask views directly in modern Flask?', 'jsonify(data)', 'json.dumps(data)', 'Both A and B', 'json_response(data)', 'C', 1, '2026-07-30 19:13:05'),
(715, 29, 'How do you specify route paths that accept only specific HTTP methods like POST in Flask?', '@app.route(\'/path\', methods=[\'POST\'])', '@app.post(\'/path\')', 'Both A and B', '@app.route(\'/path\', type=\'POST\')', 'C', 1, '2026-07-30 19:13:05'),
(716, 29, 'How do you redirect a user to another page or view function in Flask?', 'redirect(url_for(\'function_name\'))', 'header(\'Location\')', 'go_to(\'url\')', 'res.redirect()', 'A', 1, '2026-07-30 19:13:05'),
(717, 29, 'What happens if you run a Flask application in debug mode (`debug=True`)?', 'The server prints no logs', 'The server enables interactive debugger and auto-restarts on code modifications', 'The database is cleared', 'The site executes slower on production', 'B', 1, '2026-07-30 19:13:05'),
(718, 29, 'How do you define a route containing dynamic parameters like user IDs in Flask?', '@app.route(\'/user/<id>\')', '@app.route(\'/user/:id\')', '@app.route(\'/user/{id}\')', '@app.route(\'/user/id\')', 'A', 1, '2026-07-30 19:13:05'),
(719, 29, 'What syntax displays variables inside Jinja2 HTML templates?', '{{ variable }}', '{% tag %}', '{ variable }', '[ variable ]', 'A', 1, '2026-07-30 19:13:05'),
(720, 29, 'What syntax is used to write loops or conditional tags in Jinja2 templates?', '{{ tag }}', '{% tag %}', '{ tag }', '[ tag ]', 'B', 1, '2026-07-30 19:13:05'),
(721, 29, 'Which Flask function returns a URL pathway mapped to a specific view function name?', 'url_for()', 'get_url()', 'route_url()', 'path()', 'A', 1, '2026-07-30 19:13:05'),
(722, 29, 'How do you define HTTP status codes returned from Flask view function responses?', 'Return a tuple `(\'text\', status_code)`', 'Set request.status = code', 'Call set_code(code)', 'Use headers only', 'A', 1, '2026-07-30 19:13:05'),
(723, 29, 'Which package is standard to map database tables using SQL in Flask applications?', 'Flask-SQLAlchemy', 'Flask-Pymongo', 'Flask-SQLite', 'Flask-Db', 'A', 1, '2026-07-30 19:13:05'),
(724, 29, 'How do you handle cookie creation in Flask responses?', 'Call response.set_cookie() on response objects', 'Set request.cookies = value', 'Use session variables', 'Send headers manually', 'A', 1, '2026-07-30 19:13:05'),
(725, 29, 'What context-local object is used to store data across requests for a single browser session in Flask?', 'request', 'session', 'global', 'g', 'B', 1, '2026-07-30 19:13:05'),
(726, 30, 'What does REST stand for?', 'Representational State Transfer', 'Request State Transfer', 'Relational System Transfer', 'Remote State Transfer', 'A', 1, '2026-07-30 19:13:05'),
(727, 30, 'What is the primary data transfer format used in modern REST APIs?', 'XML', 'JSON', 'HTML', 'Plain text', 'B', 1, '2026-07-30 19:13:05'),
(728, 30, 'Which HTTP method is used to retrieve data from a server in REST APIs?', 'GET', 'POST', 'PUT', 'DELETE', 'A', 1, '2026-07-30 19:13:05'),
(729, 30, 'Which HTTP method is used to create new resources on the server in REST APIs?', 'GET', 'POST', 'PUT', 'DELETE', 'B', 1, '2026-07-30 19:13:05'),
(730, 30, 'Which HTTP method is used to update existing resources (full replacement) in REST APIs?', 'GET', 'POST', 'PUT', 'DELETE', 'C', 1, '2026-07-30 19:13:05'),
(731, 30, 'Which HTTP method is used to remove resources from the server in REST APIs?', 'GET', 'POST', 'PUT', 'DELETE', 'D', 1, '2026-07-30 19:13:05'),
(732, 30, 'What does it mean for an HTTP method to be \'idempotent\'?', 'It compiles without errors', 'Multiple identical requests return the same result and have the same side effects as a single request', 'It executes asynchronously', 'It requires authentication', 'B', 1, '2026-07-30 19:13:05'),
(733, 30, 'Which HTTP method is used for partial updates to resources in REST APIs?', 'PUT', 'POST', 'PATCH', 'OPTIONS', 'C', 1, '2026-07-30 19:13:05'),
(734, 30, 'What HTTP status code range represents successful requests in REST APIs?', '1xx', '2xx', '3xx', '4xx', 'B', 1, '2026-07-30 19:13:05'),
(735, 30, 'What HTTP status code represents standard successful GET queries?', '200 OK', '201 Created', '204 No Content', '400 Bad Request', 'A', 1, '2026-07-30 19:13:05'),
(736, 30, 'What HTTP status code is standard when new resources are successfully created?', '200 OK', '201 Created', '204 No Content', '400 Bad Request', 'B', 1, '2026-07-30 19:13:05'),
(737, 30, 'What HTTP status code is returned when requests complete successfully but return no content payload (like DELETE)?', '200 OK', '201 Created', '204 No Content', '400 Bad Request', 'C', 1, '2026-07-30 19:13:05'),
(738, 30, 'What HTTP status code range represents client-side errors in REST APIs?', '3xx', '4xx', '5xx', '2xx', 'B', 1, '2026-07-30 19:13:05'),
(739, 30, 'What HTTP status code represents client validation failures or invalid request parameters?', '400 Bad Request', '401 Unauthorized', '403 Forbidden', '404 Not Found', 'A', 1, '2026-07-30 19:13:05'),
(740, 30, 'What HTTP status code is returned when client requests lack valid authentication credentials?', '400 Bad Request', '401 Unauthorized', '403 Forbidden', '404 Not Found', 'B', 1, '2026-07-30 19:13:05'),
(741, 30, 'What HTTP status code indicates authenticated users lack permissions to access resource routes?', '400 Bad Request', '401 Unauthorized', '403 Forbidden', '404 Not Found', 'C', 1, '2026-07-30 19:13:05'),
(742, 30, 'What HTTP status code indicates requested resources do not exist on the server?', '400 Bad Request', '401 Summary', '404 Not Found', '500 Server Error', 'C', 1, '2026-07-30 19:13:05'),
(743, 30, 'What HTTP status code range represents server-side execution failures in REST APIs?', '3xx', '4xx', '5xx', '1xx', 'C', 1, '2026-07-30 19:13:05'),
(744, 30, 'What is the default HTTP status code for generic server exceptions in REST APIs?', '500 Internal Server Error', '502 Bad Gateway', '503 Service Unavailable', '404 Not Found', 'A', 1, '2026-07-30 19:13:05'),
(745, 30, 'What does it mean that REST APIs are \'stateless\'?', 'They do not use databases', 'The server stores no client session context; each request must contain all necessary information to process it', 'They do not return status codes', 'They only run on static HTML files', 'B', 1, '2026-07-30 19:13:05'),
(746, 30, 'Which HTTP header specifies the media type of resource payloads (like application/json) sent in requests or responses?', 'Authorization', 'Content-Type', 'Accept', 'User-Agent', 'B', 1, '2026-07-30 19:13:05'),
(747, 30, 'Which HTTP header is commonly used to send client authentication tokens (like Bearer tokens) in REST APIs?', 'Content-Type', 'Accept', 'Authorization', 'Host', 'C', 1, '2026-07-30 19:13:05'),
(748, 30, 'What is path parameters usage in REST API endpoints design (e.g. `/users/123`)?', 'Filtering lists of resources', 'Identifying specific resources by unique keys', 'Sorting query outputs', 'Authenticating users', 'B', 1, '2026-07-30 19:13:05'),
(749, 30, 'What is query parameters usage in REST API endpoints design (e.g. `/users?role=admin`)?', 'Identifying unique resource records', 'Filtering, sorting, pagination, or searching collections of resources', 'Deleting resources', 'Creating new databases', 'B', 1, '2026-07-30 19:13:05'),
(750, 30, 'Which HTTP method fetches supported HTTP methods of route endpoints in REST APIs?', 'GET', 'OPTIONS', 'HEAD', 'TRACE', 'B', 1, '2026-07-30 19:13:05');

-- --------------------------------------------------------

--
-- Table structure for table `question_banks`
--

CREATE TABLE `question_banks` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL,
  `skill` varchar(50) NOT NULL,
  `difficulty` enum('beginner','intermediate','advanced','professional','expert') NOT NULL DEFAULT 'beginner',
  `status` enum('draft','published') NOT NULL DEFAULT 'published',
  `created_by_faculty_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_banks`
--

INSERT INTO `question_banks` (`id`, `title`, `category`, `skill`, `difficulty`, `status`, `created_by_faculty_id`, `created_at`, `updated_at`) VALUES
(1, 'HTML Beginner Bank', 'Frontend Development', 'HTML', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(2, 'CSS Beginner Bank', 'Frontend Development', 'CSS', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(3, 'JavaScript Beginner Bank', 'Frontend Development', 'JavaScript', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(4, 'Bootstrap Beginner Bank', 'Frontend Development', 'Bootstrap', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(5, 'Tailwind CSS Beginner Bank', 'Frontend Development', 'Tailwind CSS', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(6, 'React Beginner Bank', 'Frontend Development', 'React', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(7, 'Angular Beginner Bank', 'Frontend Development', 'Angular', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(8, 'Vue.js Beginner Bank', 'Frontend Development', 'Vue.js', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(9, 'jQuery Beginner Bank', 'Frontend Development', 'jQuery', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(10, 'TypeScript Beginner Bank', 'Frontend Development', 'TypeScript', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(11, 'C Beginner Bank', 'Backend Development', 'C', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(12, 'C++ Beginner Bank', 'Backend Development', 'C++', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(13, 'Java Beginner Bank', 'Backend Development', 'Java', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(14, 'Python Beginner Bank', 'Backend Development', 'Python', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(15, 'PHP Beginner Bank', 'Backend Development', 'PHP', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(16, 'C# Beginner Bank', 'Backend Development', 'C#', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(17, 'Node.js Beginner Bank', 'Backend Development', 'Node.js', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(18, 'SQL Beginner Bank', 'Backend Development', 'SQL', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(19, 'MySQL Beginner Bank', 'Backend Development', 'MySQL', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(20, 'MongoDB Beginner Bank', 'Backend Development', 'MongoDB', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(21, 'MERN Stack Beginner Bank', 'Full Stack Development', 'MERN Stack', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(22, 'MEAN Stack Beginner Bank', 'Full Stack Development', 'MEAN Stack', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(23, 'Laravel Beginner Bank', 'Full Stack Development', 'Laravel', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(24, 'Django Beginner Bank', 'Full Stack Development', 'Django', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(25, 'Express.js Beginner Bank', 'Full Stack Development', 'Express.js', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(26, 'Next.js Beginner Bank', 'Full Stack Development', 'Next.js', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(27, 'ASP.NET Beginner Bank', 'Full Stack Development', 'ASP.NET', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(28, 'Spring Boot Beginner Bank', 'Full Stack Development', 'Spring Boot', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(29, 'Flask Beginner Bank', 'Full Stack Development', 'Flask', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(30, 'REST API Beginner Bank', 'Full Stack Development', 'REST API', 'beginner', 'published', 1, '2026-07-30 19:13:05', '2026-07-30 19:13:05'),
(32, 'HTML Expert Bank', 'Frontend Development', 'HTML', 'expert', 'draft', 11, '2026-07-30 19:37:46', '2026-07-30 19:37:46'),
(33, 'HTML Intermediate Bank', 'Frontend Development', 'HTML', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(34, 'HTML Advanced Bank', 'Frontend Development', 'HTML', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(35, 'HTML Professional Bank', 'Frontend Development', 'HTML', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(36, 'CSS Intermediate Bank', 'Frontend Development', 'CSS', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(37, 'CSS Advanced Bank', 'Frontend Development', 'CSS', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(38, 'CSS Professional Bank', 'Frontend Development', 'CSS', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(39, 'CSS Expert Bank', 'Frontend Development', 'CSS', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(40, 'JavaScript Intermediate Bank', 'Frontend Development', 'JavaScript', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(41, 'JavaScript Advanced Bank', 'Frontend Development', 'JavaScript', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(42, 'JavaScript Professional Bank', 'Frontend Development', 'JavaScript', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(43, 'JavaScript Expert Bank', 'Frontend Development', 'JavaScript', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(44, 'Bootstrap Intermediate Bank', 'Frontend Development', 'Bootstrap', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(45, 'Bootstrap Advanced Bank', 'Frontend Development', 'Bootstrap', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(46, 'Bootstrap Professional Bank', 'Frontend Development', 'Bootstrap', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(47, 'Bootstrap Expert Bank', 'Frontend Development', 'Bootstrap', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(48, 'Tailwind CSS Intermediate Bank', 'Frontend Development', 'Tailwind CSS', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(49, 'Tailwind CSS Advanced Bank', 'Frontend Development', 'Tailwind CSS', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(50, 'Tailwind CSS Professional Bank', 'Frontend Development', 'Tailwind CSS', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(51, 'Tailwind CSS Expert Bank', 'Frontend Development', 'Tailwind CSS', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(52, 'React Intermediate Bank', 'Frontend Development', 'React', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(53, 'React Advanced Bank', 'Frontend Development', 'React', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(54, 'React Professional Bank', 'Frontend Development', 'React', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(55, 'React Expert Bank', 'Frontend Development', 'React', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(56, 'Angular Intermediate Bank', 'Frontend Development', 'Angular', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(57, 'Angular Advanced Bank', 'Frontend Development', 'Angular', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(58, 'Angular Professional Bank', 'Frontend Development', 'Angular', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(59, 'Angular Expert Bank', 'Frontend Development', 'Angular', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(60, 'Vue.js Intermediate Bank', 'Frontend Development', 'Vue.js', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(61, 'Vue.js Advanced Bank', 'Frontend Development', 'Vue.js', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(62, 'Vue.js Professional Bank', 'Frontend Development', 'Vue.js', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(63, 'Vue.js Expert Bank', 'Frontend Development', 'Vue.js', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(64, 'jQuery Intermediate Bank', 'Frontend Development', 'jQuery', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(65, 'jQuery Advanced Bank', 'Frontend Development', 'jQuery', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(66, 'jQuery Professional Bank', 'Frontend Development', 'jQuery', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(67, 'jQuery Expert Bank', 'Frontend Development', 'jQuery', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(68, 'TypeScript Intermediate Bank', 'Frontend Development', 'TypeScript', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(69, 'TypeScript Advanced Bank', 'Frontend Development', 'TypeScript', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(70, 'TypeScript Professional Bank', 'Frontend Development', 'TypeScript', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(71, 'TypeScript Expert Bank', 'Frontend Development', 'TypeScript', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(72, 'C Intermediate Bank', 'Backend Development', 'C', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(73, 'C Advanced Bank', 'Backend Development', 'C', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(74, 'C Professional Bank', 'Backend Development', 'C', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(75, 'C Expert Bank', 'Backend Development', 'C', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(76, 'C++ Intermediate Bank', 'Backend Development', 'C++', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(77, 'C++ Advanced Bank', 'Backend Development', 'C++', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(78, 'C++ Professional Bank', 'Backend Development', 'C++', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(79, 'C++ Expert Bank', 'Backend Development', 'C++', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(80, 'Java Intermediate Bank', 'Backend Development', 'Java', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(81, 'Java Advanced Bank', 'Backend Development', 'Java', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(82, 'Java Professional Bank', 'Backend Development', 'Java', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(83, 'Java Expert Bank', 'Backend Development', 'Java', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(84, 'Python Intermediate Bank', 'Backend Development', 'Python', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(85, 'Python Advanced Bank', 'Backend Development', 'Python', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(86, 'Python Professional Bank', 'Backend Development', 'Python', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(87, 'Python Expert Bank', 'Backend Development', 'Python', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(88, 'PHP Intermediate Bank', 'Backend Development', 'PHP', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(89, 'PHP Advanced Bank', 'Backend Development', 'PHP', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(90, 'PHP Professional Bank', 'Backend Development', 'PHP', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(91, 'PHP Expert Bank', 'Backend Development', 'PHP', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(92, 'C# Intermediate Bank', 'Backend Development', 'C#', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(93, 'C# Advanced Bank', 'Backend Development', 'C#', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(94, 'C# Professional Bank', 'Backend Development', 'C#', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(95, 'C# Expert Bank', 'Backend Development', 'C#', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(96, 'Node.js Intermediate Bank', 'Backend Development', 'Node.js', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(97, 'Node.js Advanced Bank', 'Backend Development', 'Node.js', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(98, 'Node.js Professional Bank', 'Backend Development', 'Node.js', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(99, 'Node.js Expert Bank', 'Backend Development', 'Node.js', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(100, 'SQL Intermediate Bank', 'Backend Development', 'SQL', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(101, 'SQL Advanced Bank', 'Backend Development', 'SQL', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(102, 'SQL Professional Bank', 'Backend Development', 'SQL', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(103, 'SQL Expert Bank', 'Backend Development', 'SQL', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(104, 'MySQL Intermediate Bank', 'Backend Development', 'MySQL', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(105, 'MySQL Advanced Bank', 'Backend Development', 'MySQL', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(106, 'MySQL Professional Bank', 'Backend Development', 'MySQL', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(107, 'MySQL Expert Bank', 'Backend Development', 'MySQL', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(108, 'MongoDB Intermediate Bank', 'Backend Development', 'MongoDB', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(109, 'MongoDB Advanced Bank', 'Backend Development', 'MongoDB', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(110, 'MongoDB Professional Bank', 'Backend Development', 'MongoDB', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(111, 'MongoDB Expert Bank', 'Backend Development', 'MongoDB', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(112, 'MERN Stack Intermediate Bank', 'Full Stack Development', 'MERN Stack', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(113, 'MERN Stack Advanced Bank', 'Full Stack Development', 'MERN Stack', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(114, 'MERN Stack Professional Bank', 'Full Stack Development', 'MERN Stack', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(115, 'MERN Stack Expert Bank', 'Full Stack Development', 'MERN Stack', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(116, 'MEAN Stack Intermediate Bank', 'Full Stack Development', 'MEAN Stack', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(117, 'MEAN Stack Advanced Bank', 'Full Stack Development', 'MEAN Stack', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(118, 'MEAN Stack Professional Bank', 'Full Stack Development', 'MEAN Stack', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(119, 'MEAN Stack Expert Bank', 'Full Stack Development', 'MEAN Stack', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(120, 'Laravel Intermediate Bank', 'Full Stack Development', 'Laravel', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(121, 'Laravel Advanced Bank', 'Full Stack Development', 'Laravel', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(122, 'Laravel Professional Bank', 'Full Stack Development', 'Laravel', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(123, 'Laravel Expert Bank', 'Full Stack Development', 'Laravel', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(124, 'Django Intermediate Bank', 'Full Stack Development', 'Django', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(125, 'Django Advanced Bank', 'Full Stack Development', 'Django', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(126, 'Django Professional Bank', 'Full Stack Development', 'Django', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(127, 'Django Expert Bank', 'Full Stack Development', 'Django', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(128, 'Express.js Intermediate Bank', 'Full Stack Development', 'Express.js', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(129, 'Express.js Advanced Bank', 'Full Stack Development', 'Express.js', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(130, 'Express.js Professional Bank', 'Full Stack Development', 'Express.js', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(131, 'Express.js Expert Bank', 'Full Stack Development', 'Express.js', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(132, 'Next.js Intermediate Bank', 'Full Stack Development', 'Next.js', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(133, 'Next.js Advanced Bank', 'Full Stack Development', 'Next.js', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(134, 'Next.js Professional Bank', 'Full Stack Development', 'Next.js', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(135, 'Next.js Expert Bank', 'Full Stack Development', 'Next.js', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(136, 'ASP.NET Intermediate Bank', 'Full Stack Development', 'ASP.NET', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(137, 'ASP.NET Advanced Bank', 'Full Stack Development', 'ASP.NET', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(138, 'ASP.NET Professional Bank', 'Full Stack Development', 'ASP.NET', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(139, 'ASP.NET Expert Bank', 'Full Stack Development', 'ASP.NET', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(140, 'Spring Boot Intermediate Bank', 'Full Stack Development', 'Spring Boot', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(141, 'Spring Boot Advanced Bank', 'Full Stack Development', 'Spring Boot', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(142, 'Spring Boot Professional Bank', 'Full Stack Development', 'Spring Boot', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(143, 'Spring Boot Expert Bank', 'Full Stack Development', 'Spring Boot', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(144, 'Flask Intermediate Bank', 'Full Stack Development', 'Flask', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(145, 'Flask Advanced Bank', 'Full Stack Development', 'Flask', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(146, 'Flask Professional Bank', 'Full Stack Development', 'Flask', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(147, 'Flask Expert Bank', 'Full Stack Development', 'Flask', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(148, 'REST API Intermediate Bank', 'Full Stack Development', 'REST API', 'intermediate', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(149, 'REST API Advanced Bank', 'Full Stack Development', 'REST API', 'advanced', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(150, 'REST API Professional Bank', 'Full Stack Development', 'REST API', 'professional', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06'),
(151, 'REST API Expert Bank', 'Full Stack Development', 'REST API', 'expert', 'draft', 1, '2026-07-30 19:41:06', '2026-07-30 19:41:06');

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
(1, 50, 9, 1, 'Your recent assessment in HTML was 8.00%. Recommended to bridge your 92.0% skill gap.', 'high', 0, '2026-07-31 00:35:55');

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
(1, 'HTML', 'Frontend Development', 'Standardized assessment skill for HTML in Frontend Development.', '2026-07-30 18:02:51'),
(2, 'CSS', 'Frontend Development', 'Standardized assessment skill for CSS in Frontend Development.', '2026-07-30 18:02:51'),
(3, 'JavaScript', 'Frontend Development', 'Standardized assessment skill for JavaScript in Frontend Development.', '2026-07-30 18:02:51'),
(4, 'Bootstrap', 'Frontend Development', 'Standardized assessment skill for Bootstrap in Frontend Development.', '2026-07-30 18:02:51'),
(5, 'Tailwind CSS', 'Frontend Development', 'Standardized assessment skill for Tailwind CSS in Frontend Development.', '2026-07-30 18:02:51'),
(6, 'React', 'Frontend Development', 'Standardized assessment skill for React in Frontend Development.', '2026-07-30 18:02:51'),
(7, 'Angular', 'Frontend Development', 'Standardized assessment skill for Angular in Frontend Development.', '2026-07-30 18:02:51'),
(8, 'Vue.js', 'Frontend Development', 'Standardized assessment skill for Vue.js in Frontend Development.', '2026-07-30 18:02:51'),
(9, 'jQuery', 'Frontend Development', 'Standardized assessment skill for jQuery in Frontend Development.', '2026-07-30 18:02:51'),
(10, 'TypeScript', 'Frontend Development', 'Standardized assessment skill for TypeScript in Frontend Development.', '2026-07-30 18:02:51'),
(11, 'C', 'Backend Development', 'Standardized assessment skill for C in Backend Development.', '2026-07-30 18:02:51'),
(12, 'C++', 'Backend Development', 'Standardized assessment skill for C++ in Backend Development.', '2026-07-30 18:02:51'),
(13, 'Java', 'Backend Development', 'Standardized assessment skill for Java in Backend Development.', '2026-07-30 18:02:51'),
(14, 'Python', 'Backend Development', 'Standardized assessment skill for Python in Backend Development.', '2026-07-30 18:02:51'),
(15, 'PHP', 'Backend Development', 'Standardized assessment skill for PHP in Backend Development.', '2026-07-30 18:02:51'),
(16, 'C#', 'Backend Development', 'Standardized assessment skill for C# in Backend Development.', '2026-07-30 18:02:51'),
(17, 'Node.js', 'Backend Development', 'Standardized assessment skill for Node.js in Backend Development.', '2026-07-30 18:02:51'),
(18, 'SQL', 'Backend Development', 'Standardized assessment skill for SQL in Backend Development.', '2026-07-30 18:02:51'),
(19, 'MySQL', 'Backend Development', 'Standardized assessment skill for MySQL in Backend Development.', '2026-07-30 18:02:51'),
(20, 'MongoDB', 'Backend Development', 'Standardized assessment skill for MongoDB in Backend Development.', '2026-07-30 18:02:51'),
(21, 'MERN Stack', 'Full Stack Development', 'Standardized assessment skill for MERN Stack in Full Stack Development.', '2026-07-30 18:02:51'),
(22, 'MEAN Stack', 'Full Stack Development', 'Standardized assessment skill for MEAN Stack in Full Stack Development.', '2026-07-30 18:02:51'),
(23, 'Laravel', 'Full Stack Development', 'Standardized assessment skill for Laravel in Full Stack Development.', '2026-07-30 18:02:51'),
(24, 'Django', 'Full Stack Development', 'Standardized assessment skill for Django in Full Stack Development.', '2026-07-30 18:02:51'),
(25, 'Express.js', 'Full Stack Development', 'Standardized assessment skill for Express.js in Full Stack Development.', '2026-07-30 18:02:51'),
(26, 'Next.js', 'Full Stack Development', 'Standardized assessment skill for Next.js in Full Stack Development.', '2026-07-30 18:02:51'),
(27, 'ASP.NET', 'Full Stack Development', 'Standardized assessment skill for ASP.NET in Full Stack Development.', '2026-07-30 18:02:51'),
(28, 'Spring Boot', 'Full Stack Development', 'Standardized assessment skill for Spring Boot in Full Stack Development.', '2026-07-30 18:02:51'),
(29, 'Flask', 'Full Stack Development', 'Standardized assessment skill for Flask in Full Stack Development.', '2026-07-30 18:02:51'),
(30, 'REST API', 'Full Stack Development', 'Standardized assessment skill for REST API in Full Stack Development.', '2026-07-30 18:02:51');

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
(3, 9, 'STU-1003', 'Michael', 'Brown', NULL, 'default-avatar.png', NULL, NULL, '555-0103', 'Software Engineering', 6, '2026-07-20 20:01:07'),
(4, 10, 'STU-1004', 'Sophia', 'Johnson', NULL, 'default-avatar.png', NULL, NULL, '555-0104', 'Computer Science', 4, '2026-07-20 20:01:07'),
(5, 11, 'STU-1005', 'Daniel', 'Williams', NULL, 'default-avatar.png', NULL, NULL, '555-0105', 'Data Science', 6, '2026-07-20 20:01:07'),
(6, 12, 'STU-1006', 'Olivia', 'Jones', NULL, 'default-avatar.png', NULL, NULL, '555-0106', 'Software Engineering', 3, '2026-07-20 20:01:07'),
(7, 13, 'STU-1007', 'David', 'Miller', NULL, 'default-avatar.png', NULL, NULL, '555-0107', 'Computer Science', 5, '2026-07-20 20:01:07'),
(8, 14, 'STU-1008', 'Emma', 'Davis', NULL, 'default-avatar.png', NULL, NULL, '555-0108', 'Information Technology', 4, '2026-07-20 20:01:07'),
(9, 15, 'STU-1009', 'James', 'Wilson', NULL, 'default-avatar.png', NULL, NULL, '555-0109', 'Systems Engineering', 6, '2026-07-20 20:01:07'),
(10, 16, 'STU-1010', 'Ava', 'Taylor', NULL, 'default-avatar.png', NULL, NULL, '555-0110', 'Computer Science', 3, '2026-07-20 20:01:07'),
(11, 17, 'STU-1011', 'Alex', 'Anderson', NULL, 'default-avatar.png', NULL, NULL, '555-0111', 'Data Science', 5, '2026-07-20 20:01:07'),
(12, 18, 'STU-1012', 'Mia', 'Thomas', NULL, 'default-avatar.png', NULL, NULL, '555-0112', 'Software Engineering', 4, '2026-07-20 20:01:07'),
(13, 19, 'STU-1013', 'Ethan', 'Jackson', NULL, 'default-avatar.png', NULL, NULL, '555-0113', 'Computer Science', 6, '2026-07-20 20:01:07'),
(14, 20, 'STU-1014', 'Isabella', 'White', NULL, 'default-avatar.png', NULL, NULL, '555-0114', 'Information Technology', 3, '2026-07-20 20:01:07'),
(15, 21, 'STU-1015', 'William', 'Harris', NULL, 'default-avatar.png', NULL, NULL, '555-0115', 'Systems Engineering', 5, '2026-07-20 20:01:07'),
(16, 22, 'STU-1016', 'Charlotte', 'Martin', NULL, 'default-avatar.png', NULL, NULL, '555-0116', 'Computer Science', 4, '2026-07-20 20:01:07'),
(17, 23, 'STU-1017', 'Benjamin', 'Thompson', NULL, 'default-avatar.png', NULL, NULL, '555-0117', 'Software Engineering', 6, '2026-07-20 20:01:07'),
(18, 24, 'STU-1018', 'Amelia', 'Garcia', NULL, 'default-avatar.png', NULL, NULL, '555-0118', 'Data Science', 3, '2026-07-20 20:01:07'),
(19, 25, 'STU-1019', 'Lucas', 'Martinez', NULL, 'default-avatar.png', NULL, NULL, '555-0119', 'Computer Science', 5, '2026-07-20 20:01:07'),
(20, 26, 'STU-1020', 'Harper', 'Robinson', NULL, 'default-avatar.png', NULL, NULL, '555-0120', 'Information Technology', 4, '2026-07-20 20:01:07'),
(50, 58, 'STU-1058', 'Encore', 'Abj', 'ZCOER', 'default-avatar.png', 'No Half Measures....!', 'Pune, India', '7558272740', 'Information Technology', 1, '2026-07-23 11:39:30'),
(52, 64, 'STU-1064', 'TestStudentOne', 'User', 'SkillBridge University', 'default-avatar.png', NULL, NULL, '9876543210', 'Computer Science', 3, '2026-07-26 21:26:37'),
(53, 65, 'STU-1065', 'TestStudentTwo', 'User', 'SkillBridge University', 'default-avatar.png', NULL, NULL, '9876543211', 'Information Technology', 5, '2026-07-26 21:26:37'),
(58, 70, 'STU-1070', 'Pavan', 'thote', 'ZCOER', 'default-avatar.png', NULL, NULL, '8087741794', 'Information Technology', 3, '2026-07-30 21:38:51'),
(59, 71, 'STU-1071', 'Skill', 'B', 'ZCOER', 'default-avatar.png', NULL, NULL, '123456789', 'Information Technology', 3, '2026-07-30 21:38:51');

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
(1, 1, 241, 'B', 1, 1),
(2, 1, 249, NULL, 0, 0),
(3, 1, 229, 'B', 0, 0),
(4, 1, 248, NULL, 0, 0),
(5, 1, 231, NULL, 0, 0),
(6, 1, 244, NULL, 0, 0),
(7, 1, 245, NULL, 0, 0),
(8, 1, 226, NULL, 0, 0),
(9, 1, 232, NULL, 0, 0),
(10, 1, 228, NULL, 0, 0),
(11, 1, 233, NULL, 0, 0),
(12, 1, 236, NULL, 0, 0),
(13, 1, 238, NULL, 0, 0),
(14, 1, 227, NULL, 0, 0),
(15, 1, 230, NULL, 0, 0),
(16, 1, 240, NULL, 0, 0),
(17, 1, 235, NULL, 0, 0),
(18, 1, 243, NULL, 0, 0),
(19, 1, 239, NULL, 0, 0),
(20, 1, 237, NULL, 0, 0),
(21, 1, 242, NULL, 0, 0),
(22, 1, 234, 'D', 0, 0),
(23, 1, 250, 'B', 1, 1),
(24, 1, 247, NULL, 0, 0),
(25, 1, 246, 'D', 0, 0),
(26, 2, 244, 'B', 0, 0),
(27, 2, 228, 'B', 1, 1),
(28, 2, 235, 'B', 1, 1),
(29, 2, 226, 'D', 0, 0),
(30, 2, 246, NULL, 0, 0),
(31, 2, 242, NULL, 0, 0),
(32, 2, 247, NULL, 0, 0),
(33, 2, 237, NULL, 0, 0),
(34, 2, 230, NULL, 0, 0),
(35, 2, 248, NULL, 0, 0),
(36, 2, 229, NULL, 0, 0),
(37, 2, 243, NULL, 0, 0),
(38, 2, 234, NULL, 0, 0),
(39, 2, 239, NULL, 0, 0),
(40, 2, 241, NULL, 0, 0),
(41, 2, 232, NULL, 0, 0),
(42, 2, 240, NULL, 0, 0),
(43, 2, 231, NULL, 0, 0),
(44, 2, 227, NULL, 0, 0),
(45, 2, 250, NULL, 0, 0),
(46, 2, 245, NULL, 0, 0),
(47, 2, 238, NULL, 0, 0),
(48, 2, 233, NULL, 0, 0),
(49, 2, 236, NULL, 0, 0),
(50, 2, 249, NULL, 0, 0),
(51, 3, 21, 'B', 0, 0),
(52, 3, 11, 'B', 1, 1),
(53, 3, 8, 'B', 0, 0),
(54, 3, 22, 'B', 0, 0),
(55, 3, 12, 'D', 0, 0),
(56, 3, 16, 'B', 0, 0),
(57, 3, 9, NULL, 0, 0),
(58, 3, 5, NULL, 0, 0),
(59, 3, 20, NULL, 0, 0),
(60, 3, 6, NULL, 0, 0),
(61, 3, 24, NULL, 0, 0),
(62, 3, 13, NULL, 0, 0),
(63, 3, 2, NULL, 0, 0),
(64, 3, 18, 'B', 0, 0),
(65, 3, 15, 'D', 1, 1),
(66, 3, 10, 'B', 0, 0),
(67, 3, 3, NULL, 0, 0),
(68, 3, 1, NULL, 0, 0),
(69, 3, 4, NULL, 0, 0),
(70, 3, 25, NULL, 0, 0),
(71, 3, 14, NULL, 0, 0),
(72, 3, 19, NULL, 0, 0),
(73, 3, 23, NULL, 0, 0),
(74, 3, 17, NULL, 0, 0),
(75, 3, 7, NULL, 0, 0),
(76, 4, 242, 'B', 0, 0),
(77, 4, 248, 'B', 1, 1),
(78, 4, 240, NULL, 0, 0),
(79, 4, 231, NULL, 0, 0),
(80, 4, 247, NULL, 0, 0),
(81, 4, 239, NULL, 0, 0),
(82, 4, 236, NULL, 0, 0),
(83, 4, 244, NULL, 0, 0),
(84, 4, 233, NULL, 0, 0),
(85, 4, 243, NULL, 0, 0),
(86, 4, 246, NULL, 0, 0),
(87, 4, 235, NULL, 0, 0),
(88, 4, 241, NULL, 0, 0),
(89, 4, 237, NULL, 0, 0),
(90, 4, 234, NULL, 0, 0),
(91, 4, 226, NULL, 0, 0),
(92, 4, 229, NULL, 0, 0),
(93, 4, 238, NULL, 0, 0),
(94, 4, 245, NULL, 0, 0),
(95, 4, 228, NULL, 0, 0),
(96, 4, 249, NULL, 0, 0),
(97, 4, 250, NULL, 0, 0),
(98, 4, 232, NULL, 0, 0),
(99, 4, 230, NULL, 0, 0),
(100, 4, 227, NULL, 0, 0);

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
(21, 50, 16, 33, 'in_progress', '2026-07-29 21:32:25', '[46]'),
(22, 50, 4, 10, 'in_progress', '2026-07-26 12:59:57', NULL),
(23, 50, 1, 33, 'in_progress', '2026-07-26 13:16:37', '[1]'),
(24, 50, 19, 85, 'in_progress', '2026-07-26 18:21:22', '[55,56]'),
(25, 50, 18, 33, 'in_progress', '2026-07-29 22:30:04', '[52]'),
(26, 50, 17, 0, 'in_progress', '2026-07-29 22:15:29', '[]'),
(27, 50, 2, 0, 'in_progress', '2026-07-29 22:29:52', '[]');

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
('last_assessment_sync_time', '1785474137', 'general', NULL),
('pass_mark_threshold', '40', 'assessment', 'Default passing percentage threshold for assessments'),
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

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`, `remember_token`, `reset_token`, `reset_token_expiry`, `email_verified`, `email_verification_otp`, `otp_expiry`, `created_at`, `updated_at`, `theme`) VALUES
(1, 'admin', 'sudrikyash1@gmail.com', '$2y$10$Dm3bPMd.zSXW2Jka8IujHexhNE1tatg82gNNcN5IYkbS7SbTTaUZG', 'admin', 'active', NULL, '8cff4d3f4870e66508c459240dea212c0baf6ae66fbca7bd547e6da02777a37d', '2026-07-27 15:14:19', 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-30 22:00:39', 'light'),
(2, 'f_turing', 'faculty1@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-31 10:34:17', 'light'),
(3, 'f_hopper', 'faculty2@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(4, 'f_knuth', 'faculty3@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(5, 'f_lovelace', 'faculty4@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(6, 'f_torvalds', 'faculty5@skillbridge.edu', '$2y$10$41.rpNoFnuBqBQU0yXPmm.ZjpGPXcT0sET25C48qJv6Mo/tHsi74q', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(9, 's_michael', 'student3@skillbridge.edu', '$2y$10$tvfRTgnhMObrLPzROY8S6ORxYznGOlUpTfFxaOdLHBsgaQASCUlWy', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-30 14:05:04', 'system'),
(10, 's_sophia', 'student4@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(11, 's_daniel', 'student5@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(12, 's_olivia', 'student6@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(13, 's_david', 'student7@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(14, 's_emma', 'student8@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(15, 's_james', 'student9@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(16, 's_ava', 'student10@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(17, 's_alex', 'student11@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(18, 's_mia', 'student12@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(19, 's_ethan', 'student13@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(20, 's_isabella', 'student14@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(21, 's_william', 'student15@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(22, 's_charlotte', 'student16@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(23, 's_benjamin', 'student17@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(24, 's_amelia', 'student18@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(25, 's_lucas', 'student19@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(26, 's_harper', 'student20@skillbridge.edu', '$2y$10$wcAqojau3uCIVwrAonlQcejo77iQ3AwutVa/vU7E.tJ2hQncjOfsm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-20 20:01:07', '2026-07-20 20:01:07', 'system'),
(58, 'encore.exe', 'marathaedits96@gmail.com', '$2y$10$iNINbERYrHdAPtXk2Lqu0ez8UGVKsUfm11V0X./n3B5ffVfhZuOhq', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-23 11:39:30', '2026-07-29 18:59:17', 'light'),
(61, 'dr_vikram', 'test_fac_approve@skillbridge.edu', '$2y$10$rTkys9QqJw/CE884zfmk8uWiBsjaO1SJ5UaokjiBAU.RJryFCr1OK', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-23 15:03:06', '2026-07-23 15:03:06', 'system'),
(62, 'test_reject', 'test_fac_reject@skillbridge.edu', '$2y$10$rTkys9QqJw/CE884zfmk8uWiBsjaO1SJ5UaokjiBAU.RJryFCr1OK', 'faculty', 'rejected', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-23 15:03:15', '2026-07-23 15:03:15', 'system'),
(63, 'khansir', 'heroicff2727@gmail.com', '$2y$10$BLXfNe1QpU/7MiEjqO8Y/OuoxqFRMAyFjWj8fsVJgKIhCQHTMOQsC', 'faculty', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-23 19:03:55', '2026-07-30 11:50:54', 'light'),
(64, 'test_stu_one_116', 'test.student.one570@skillbridge.edu', '$2y$10$6fGlxuIOIBoes1fYMt1lueSKJqtu1ifudL6cOyTUvLPPrqK7QNYb.', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-26 21:26:37', '2026-07-26 21:26:37', 'system'),
(65, 'test_stu_two_479', 'test.student.two880@skillbridge.edu', '$2y$10$FL.P04etjjzR6Ven50UdkupqCgTlnoNr0dLIpxJsz4R0Nb2A9tLSm', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-26 21:26:37', '2026-07-26 21:26:37', 'system'),
(70, 'pavandon', 'pavanthote7777@gmail.com', '$2y$10$CBzW/hdevYPsYU3LQrFK7eTyzoXHyw7yncPY.fh9EwVNIV4E30qIW', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-30 21:38:51', '2026-07-30 21:38:51', 'system'),
(71, 'sb', 'skill.bridge.project1@gmail.com', '$2y$10$hQV0AkfmZOPm1PUc3yn3Zeh85nVg74lGXPfblC1Ml0.F/4g5CjxEa', 'student', 'active', NULL, NULL, NULL, 1, NULL, NULL, '2026-07-30 21:38:51', '2026-07-30 21:40:46', 'light');

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
-- Indexes for table `announcement_reads`
--
ALTER TABLE `announcement_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_announcement_user` (`announcement_id`,`user_id`);

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assessment_skill` (`skill_id`),
  ADD KEY `fk_assessment_faculty` (`created_by_faculty_id`),
  ADD KEY `fk_assessments_qbank` (`question_bank_id`);

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
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_q_qb` (`question_bank_id`);

--
-- Indexes for table `question_banks`
--
ALTER TABLE `question_banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_qb_faculty` (`created_by_faculty_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=645;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `announcement_reads`
--
ALTER TABLE `announcement_reads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `assessment_proctoring_logs`
--
ALTER TABLE `assessment_proctoring_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `assessment_proctoring_summaries`
--
ALTER TABLE `assessment_proctoring_summaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `assessment_questions`
--
ALTER TABLE `assessment_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessment_results`
--
ALTER TABLE `assessment_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `course_skills`
--
ALTER TABLE `course_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=397;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=751;

--
-- AUTO_INCREMENT for table `question_banks`
--
ALTER TABLE `question_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

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
  ADD CONSTRAINT `fk_assessment_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_assessments_qbank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE SET NULL;

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
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_q_qb` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_banks`
--
ALTER TABLE `question_banks`
  ADD CONSTRAINT `fk_qb_faculty` FOREIGN KEY (`created_by_faculty_id`) REFERENCES `faculty` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
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
