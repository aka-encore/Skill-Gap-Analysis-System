<?php
/**
 * SkillBridge - Centralized PHPMailer Configuration & Email Subsystem
 * Single Source of Truth for SMTP Settings, PHPMailer Factory, and Mail Helpers.
 * PHP 8.2+ Compatible
 */

// Load Composer Autoloader & Core Application Settings
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// =========================================================================
// SINGLE SOURCE OF TRUTH FOR SMTP CONFIGURATION
// Configure your Gmail address & 16-character App Password here only.
// =========================================================================
if (!defined('SMTP_HOST'))       define('SMTP_HOST',       'smtp.gmail.com');
if (!defined('SMTP_PORT'))       define('SMTP_PORT',       587); // Use 587 for TLS, 465 for SSL
if (!defined('SMTP_SECURE'))     define('SMTP_SECURE',     PHPMailer::ENCRYPTION_STARTTLS); // STARTTLS or SMTPS
if (!defined('SMTP_USER'))       define('SMTP_USER',       'sudrikyash1@gmail.com');
if (!defined('SMTP_PASS'))       define('SMTP_PASS',       'xlwm lcsx nzlc wdla'); // Gmail App Password
if (!defined('SMTP_FROM_EMAIL')) define('SMTP_FROM_EMAIL', 'sudrikyash1@gmail.com');
if (!defined('SMTP_FROM_NAME'))  define('SMTP_FROM_NAME',  'SkillBridge Team');
if (!defined('SUPPORT_EMAIL'))   define('SUPPORT_EMAIL',   'sudrikyash1@gmail.com');

/**
 * Centralized PHPMailer Instance Factory
 * Creates and returns a fully configured PHPMailer object from project constants.
 * 
 * @param bool $debug Enable verbose SMTP debug output if true
 * @return PHPMailer
 */
function create_phpmailer_instance(bool $debug = false): PHPMailer {
    $mail = new PHPMailer(true);

    // Debugging configuration
    $mail->SMTPDebug = $debug ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;

    // Server configuration
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = str_replace(' ', '', SMTP_PASS); // Strip spaces from App Password
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port       = SMTP_PORT;
    $mail->Timeout    = 15;

    // Default sender
    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);

    return $mail;
}

/**
 * Send Email Verification OTP Email
 * 
 * @param string $toEmail Recipient email address
 * @param string $otp 6-digit OTP verification code
 * @return array Standardized response ['success' => bool, 'message' => string]
 */
function send_otp_email(string $toEmail, string $otp): array {
    try {
        $mail = create_phpmailer_instance();
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Verify your SkillBridge Account';

        $htmlBody = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Verify your SkillBridge Account</title>
        </head>
        <body style="font-family: \'Outfit\', \'Poppins\', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 24px; color: #1e293b;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin: 0 auto;">
                <!-- Header -->
                <tr>
                    <td style="background: linear-gradient(135deg, #021024 0%, #052659 50%, #26658C 100%); padding: 36px 30px; text-align: center;">
                        <div style="display: inline-block; background: rgba(255,255,255,0.15); padding: 10px; border-radius: 12px; margin-bottom: 12px;">
                            <span style="font-size: 28px; color: #ffffff;">🧠</span>
                        </div>
                        <h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 700; letter-spacing: -0.5px;">SkillBridge</h1>
                        <p style="color: #c1d3fe; margin: 6px 0 0 0; font-size: 13px; font-weight: 500;">Skill Gap Analysis & Learning Management System</p>
                    </td>
                </tr>

                <!-- Main Content -->
                <tr>
                    <td style="padding: 40px 32px; background-color: #ffffff;">
                        <p style="font-size: 16px; margin-top: 0; color: #0f172a; font-weight: 600;">Welcome to SkillBridge!</p>
                        <p style="font-size: 15px; color: #475569; line-height: 1.6; margin-bottom: 24px;">Your verification code is:</p>
                        
                        <!-- OTP Display Box -->
                        <div style="text-align: center; margin: 28px 0;">
                            <div style="display: inline-block; background: #f8fafc; border: 2px dashed #26658C; padding: 16px 36px; border-radius: 12px; letter-spacing: 8px; font-size: 32px; font-weight: 800; color: #021024; font-family: \'Courier New\', monospace;">
                                ' . htmlspecialchars($otp) . '
                            </div>
                        </div>

                        <p style="font-size: 14px; color: #64748b; line-height: 1.5; margin-bottom: 12px;">This OTP is valid for 10 minutes.</p>
                        <p style="font-size: 14px; color: #64748b; line-height: 1.5; margin-bottom: 32px;">If you did not create this account, please ignore this email.</p>

                        <div style="border-top: 1px solid #f1f5f9; padding-top: 24px;">
                            <p style="font-size: 14px; margin: 0; color: #334155; font-weight: 500;">Regards,<br><strong style="color: #021024;">SkillBridge Team</strong></p>
                        </div>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                        <p style="margin: 0;">This email was sent automatically by SkillBridge LMS.</p>
                        <p style="margin: 4px 0 0 0;">&copy; ' . date('Y') . ' SkillBridge. All rights reserved.</p>
                    </td>
                </tr>
            </table>
        </body>
        </html>';

        $plainTextBody = "Welcome to SkillBridge!\n\nYour verification code is:\n" . $otp . "\n\nThis OTP is valid for 10 minutes.\n\nIf you did not create this account, please ignore this email.\n\nRegards,\nSkillBridge Team";

        $mail->Body    = $htmlBody;
        $mail->AltBody = $plainTextBody;

        $mail->send();
        return ['success' => true, 'message' => 'Verification code sent successfully.'];
    } catch (Exception $e) {
        error_log("PHPMailer send_otp_email Exception: " . $e->getMessage() . " | ErrorInfo: " . ($mail->ErrorInfo ?? 'N/A'));
        return [
            'success' => false,
            'message' => 'Mailer Error: ' . ($mail->ErrorInfo ?? $e->getMessage())
        ];
    }
}

/**
 * Send Password Reset Email using PHPMailer
 * 
 * @param string $toEmail Recipient email address
 * @param string $resetLink Full dynamic URL to reset-password.php with token
 * @return array Standardized response ['success' => bool, 'message' => string]
 */
function send_password_reset_email(string $toEmail, string $resetLink): array {
    try {
        $mail = create_phpmailer_instance();
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'SkillBridge Password Reset';

        $htmlBody = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>SkillBridge Password Reset</title>
        </head>
        <body style="font-family: \'Outfit\', \'Poppins\', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 24px; color: #1e293b;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin: 0 auto;">
                <!-- Header -->
                <tr>
                    <td style="background: linear-gradient(135deg, #021024 0%, #052659 50%, #26658C 100%); padding: 36px 30px; text-align: center;">
                        <div style="display: inline-block; background: rgba(255,255,255,0.15); padding: 10px; border-radius: 12px; margin-bottom: 12px;">
                            <span style="font-size: 28px; color: #ffffff;">🧠</span>
                        </div>
                        <h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 700; letter-spacing: -0.5px;">SkillBridge</h1>
                        <p style="color: #c1d3fe; margin: 6px 0 0 0; font-size: 13px; font-weight: 500;">Skill Gap Analysis & Learning Management System</p>
                    </td>
                </tr>

                <!-- Main Content -->
                <tr>
                    <td style="padding: 40px 32px; background-color: #ffffff;">
                        <p style="font-size: 16px; margin-top: 0; color: #0f172a; font-weight: 600;">Hello,</p>
                        <p style="font-size: 15px; color: #475569; line-height: 1.6; margin-bottom: 16px;">A password reset request was received for your SkillBridge account.</p>
                        <p style="font-size: 15px; color: #475569; line-height: 1.6; margin-bottom: 28px;">Click the button below to reset your password.</p>
                        
                        <!-- CTA Button -->
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 32px;">
                            <tr>
                                <td align="center">
                                    <a href="' . htmlspecialchars($resetLink) . '" target="_blank" style="background: linear-gradient(135deg, #26658C 0%, #021024 100%); color: #ffffff; padding: 14px 34px; text-decoration: none; border-radius: 10px; font-weight: 600; font-size: 15px; display: inline-block; box-shadow: 0 4px 14px rgba(38, 101, 140, 0.35); letter-spacing: 0.2px;">Reset Password</a>
                                </td>
                            </tr>
                        </table>

                        <p style="font-size: 14px; color: #64748b; line-height: 1.5; margin-bottom: 12px;">This link will expire in 30 minutes.</p>
                        <p style="font-size: 14px; color: #64748b; line-height: 1.5; margin-bottom: 32px;">If you did not request this, simply ignore this email.</p>

                        <div style="border-top: 1px solid #f1f5f9; padding-top: 24px;">
                            <p style="font-size: 14px; margin: 0; color: #334155; font-weight: 500;">Regards,<br><strong style="color: #021024;">SkillBridge Team</strong></p>
                        </div>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                        <p style="margin: 0;">This email was sent automatically by SkillBridge LMS.</p>
                        <p style="margin: 4px 0 0 0;">&copy; ' . date('Y') . ' SkillBridge. All rights reserved.</p>
                    </td>
                </tr>
            </table>
        </body>
        </html>';

        $plainTextBody = "Hello,\n\nA password reset request was received for your SkillBridge account.\n\nClick the link below to reset your password:\n" . $resetLink . "\n\nThis link will expire in 30 minutes.\n\nIf you did not request this, simply ignore this email.\n\nRegards,\nSkillBridge Team";

        $mail->Body    = $htmlBody;
        $mail->AltBody = $plainTextBody;

        $mail->send();
        return ['success' => true, 'message' => 'Password reset email sent successfully.'];
    } catch (Exception $e) {
        error_log("PHPMailer send_password_reset_email Exception: " . $e->getMessage() . " | ErrorInfo: " . ($mail->ErrorInfo ?? 'N/A'));
        return [
            'success' => false,
            'message' => 'Mailer Error: ' . ($mail->ErrorInfo ?? $e->getMessage())
        ];
    }
}

/**
 * Send Platform Feedback Notification Email to Support
 */
function send_feedback_email(string $userRole, string $userName, string $userEmail, string $category, int $rating, string $message): array {
    try {
        $mail = create_phpmailer_instance();
        $mail->addAddress(SUPPORT_EMAIL, 'SkillBridge Support');
        if (!empty($userEmail)) {
            $mail->addReplyTo($userEmail, $userName);
        }

        $mail->isHTML(true);
        $mail->Subject = "[SkillBridge Feedback] New Feedback from " . $userName . " (" . ucfirst($userRole) . ")";
        
        $stars = str_repeat('⭐', $rating);
        $submissionTime = date('Y-m-d H:i:s T');
        
        $mail->Body = "
            <div style='font-family:Arial,sans-serif; line-height:1.6; color:#333; max-width:600px; margin:0 auto; border:1px solid #e0e0e0; border-radius:10px; padding:20px;'>
                <h2 style='color:#26658C; margin-top:0;'>New Platform Feedback Received</h2>
                <p><strong>From:</strong> " . htmlspecialchars($userName) . " (" . htmlspecialchars($userEmail) . ") &bull; " . ucfirst($userRole) . "</p>
                <p><strong>Category:</strong> " . htmlspecialchars($category) . "</p>
                <p><strong>Rating:</strong> {$stars} ({$rating}/5 Stars)</p>
                <p><strong>Submission Time:</strong> {$submissionTime}</p>
                <hr style='border:none; border-top:1px solid #eee; margin:15px 0;' />
                <p><strong>Feedback Message:</strong></p>
                <blockquote style='background:#f9f9f9; border-left:4px solid #26658C; margin:0; padding:10px 15px;'>
                    " . nl2br(htmlspecialchars($message)) . "
                </blockquote>
                <hr style='border:none; border-top:1px solid #eee; margin:15px 0;' />
                <p style='font-size:12px; color:#888;'>Sent automatically to " . SUPPORT_EMAIL . "</p>
            </div>
        ";
        $mail->AltBody = "New Feedback from {$userName} ({$userEmail})\nCategory: {$category}\nRating: {$rating}/5\nSubmission Time: {$submissionTime}\n\nMessage:\n{$message}";

        $mail->send();
        return ['success' => true, 'message' => 'Feedback email sent successfully.'];
    } catch (Exception $e) {
        error_log("PHPMailer send_feedback_email Exception: " . $e->getMessage() . " | ErrorInfo: " . ($mail->ErrorInfo ?? 'N/A'));
        return [
            'success' => false,
            'message' => 'Mailer Error: ' . ($mail->ErrorInfo ?? $e->getMessage())
        ];
    }
}

/**
 * Send Faculty Registration Approval Email via SMTP
 */
function send_faculty_approval_email(string $toEmail, string $facultyName): array {
    try {
        $mail = create_phpmailer_instance();
        $mail->addAddress($toEmail, $facultyName);
        $mail->isHTML(true);
        $mail->Subject = 'Faculty Registration Approved';

        $body = "
        <div style='font-family:Arial,sans-serif; line-height:1.6; color:#333; max-width:600px; margin:0 auto; border:1px solid #e0e0e0; border-radius:10px; padding:25px;'>
            <h2 style='color:#28a745; margin-top:0;'>Faculty Registration Approved</h2>
            <p>Dear " . htmlspecialchars($facultyName) . ",</p>
            <p><strong>Congratulations!</strong></p>
            <p>Your SkillBridge Faculty account has been approved by the administrator.</p>
            <p>You may now log in using your registered credentials.</p>
            <p style='margin-top:20px;'><a href='" . BASE_URL . "login.php' style='display:inline-block; padding:10px 20px; background:#26658C; color:#ffffff; text-decoration:none; border-radius:5px; font-weight:bold;'>Sign In to Faculty Portal</a></p>
            <hr style='border:none; border-top:1px solid #eee; margin:20px 0;' />
            <p style='font-size:12px; color:#888;'>Regards,<br/>SkillBridge Administration Team</p>
        </div>";

        $mail->Body    = $body;
        $mail->AltBody = "Dear {$facultyName},\n\nCongratulations!\n\nYour SkillBridge Faculty account has been approved by the administrator.\n\nYou may now log in using your registered credentials.\n\nSign In: " . BASE_URL . "login.php\n\nRegards,\nSkillBridge Team";

        $mail->send();
        return ['success' => true, 'message' => 'Faculty approval email sent successfully.'];
    } catch (Exception $e) {
        error_log("send_faculty_approval_email Exception: " . $e->getMessage());
        return ['success' => false, 'message' => 'Mailer Error: ' . ($mail->ErrorInfo ?? $e->getMessage())];
    }
}

/**
 * Send Faculty Registration Rejection Email via SMTP
 */
function send_faculty_rejection_email(string $toEmail, string $facultyName, string $reason = ''): array {
    try {
        $mail = create_phpmailer_instance();
        $mail->addAddress($toEmail, $facultyName);
        $mail->isHTML(true);
        $mail->Subject = 'Faculty Registration Update';

        $reasonHtml = !empty($reason) ? "<p><strong>Reason:</strong><br/>" . nl2br(htmlspecialchars($reason)) . "</p>" : "";
        $reasonText = !empty($reason) ? "\nReason:\n" . $reason . "\n" : "";

        $body = "
        <div style='font-family:Arial,sans-serif; line-height:1.6; color:#333; max-width:600px; margin:0 auto; border:1px solid #e0e0e0; border-radius:10px; padding:25px;'>
            <h2 style='color:#dc3545; margin-top:0;'>Faculty Registration Update</h2>
            <p>Dear " . htmlspecialchars($facultyName) . ",</p>
            <p>Your Faculty registration application was not approved.</p>
            {$reasonHtml}
            <p>Please contact the administrator for further assistance.</p>
            <hr style='border:none; border-top:1px solid #eee; margin:20px 0;' />
            <p style='font-size:12px; color:#888;'>Regards,<br/>SkillBridge Administration Team</p>
        </div>";

        $mail->Body    = $body;
        $mail->AltBody = "Dear {$facultyName},\n\nYour Faculty registration application was not approved.{$reasonText}\nPlease contact the administrator for further assistance.\n\nRegards,\nSkillBridge Team";

        $mail->send();
        return ['success' => true, 'message' => 'Faculty rejection email sent successfully.'];
    } catch (Exception $e) {
        error_log("send_faculty_rejection_email Exception: " . $e->getMessage());
        return ['success' => false, 'message' => 'Mailer Error: ' . ($mail->ErrorInfo ?? $e->getMessage())];
    }
}
