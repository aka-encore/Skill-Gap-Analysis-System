-- SkillBridge Database Migration
-- Feature: Standardize Assessments
-- Description: Standardizes all existing assessment configurations to require 25 total marks and 20 passing marks to align with the Student Assessment structure.

UPDATE `assessments`
SET `passing_marks` = 20, `total_marks` = 25;
