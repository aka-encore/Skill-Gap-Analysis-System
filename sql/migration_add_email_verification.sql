-- SkillBridge Database Migration
-- Feature: Email Verification via OTP during Registration
-- Description: Adds email_verified, email_verification_otp, and otp_expiry columns to users table.
-- Existing accounts are automatically updated to email_verified = 1 for 100% backward compatibility.

ALTER TABLE `users`
ADD COLUMN `email_verified` TINYINT(1) NOT NULL DEFAULT 1 AFTER `reset_token_expiry`,
ADD COLUMN `email_verification_otp` VARCHAR(10) NULL AFTER `email_verified`,
ADD COLUMN `otp_expiry` DATETIME NULL AFTER `email_verification_otp`;

UPDATE `users` SET `email_verified` = 1;
