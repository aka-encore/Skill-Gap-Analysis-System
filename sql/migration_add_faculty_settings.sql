-- SkillBridge Database Migration
-- Feature: Faculty Settings Preference Columns
-- Description: Adds configuration, notification, privacy, and preferences fields to the faculty table.

ALTER TABLE `faculty`
ADD COLUMN `display_name` VARCHAR(100) NULL DEFAULT NULL AFTER `last_name`,
ADD COLUMN `bio` TEXT NULL DEFAULT NULL AFTER `designation`,
ADD COLUMN `office_location` VARCHAR(100) NULL DEFAULT NULL AFTER `bio`,
ADD COLUMN `notif_assessment` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `notif_submission` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `notif_system` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `notif_email` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `notif_browser` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `priv_profile_visibility` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `priv_show_email` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `priv_show_mobile` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `priv_show_department` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `priv_show_designation` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `pref_dashboard` VARCHAR(100) NOT NULL DEFAULT 'faculty/dashboard.php',
ADD COLUMN `pref_assessment_view` VARCHAR(50) NOT NULL DEFAULT 'grid',
ADD COLUMN `pref_language` VARCHAR(50) NOT NULL DEFAULT 'en',
ADD COLUMN `pref_timezone` VARCHAR(100) NOT NULL DEFAULT 'Asia/Kolkata';
