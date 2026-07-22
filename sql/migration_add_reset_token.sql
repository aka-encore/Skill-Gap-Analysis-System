-- SkillBridge Database Migration
-- Feature: Password Reset Functionality
-- Description: Adds reset_token and reset_token_expiry columns to users table

ALTER TABLE `users`
ADD COLUMN `reset_token` VARCHAR(255) NULL AFTER `remember_token`,
ADD COLUMN `reset_token_expiry` DATETIME NULL AFTER `reset_token`;
