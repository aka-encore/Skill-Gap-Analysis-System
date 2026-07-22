<?php
/**
 * SkillBridge - Standalone SMTP Diagnostic Tool
 * Live test page for SMTP Connection, Configuration, Authentication & Email Dispatch.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$pageTitle = "SMTP Diagnostics – SkillBridge";

// Helper to mask secret strings
function mask_secret_str(string $str): string {
    $len = strlen($str);
    if ($len <= 4) return '****';
    return substr($str, 0, 2) . str_repeat('*', max(0, $len - 4)) . substr($str, -2);
}

$testResult = null;
$debugLogOutput = '';
$testEmail = $_POST['test_email'] ?? SMTP_USER;
$verboseDebug = isset($_POST['verbose_debug']);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['run_test'])) {
    $mail = new PHPMailer(true);
    
    // Capture debug output
    ob_start();
    try {
        $mail->SMTPDebug = $verboseDebug ? SMTP::DEBUG_SERVER : SMTP::DEBUG_CONNECTION;
        $mail->Debugoutput = function($str, $level) {
            echo "[" . date('H:i:s') . " DBG-$level] " . htmlspecialchars($str) . "\n";
        };

        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = str_replace(' ', '', SMTP_PASS);
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;
        $mail->Timeout    = 15;

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($testEmail);

        $mail->isHTML(true);
        $mail->Subject = 'SkillBridge SMTP Diagnostic Test Email';
        $mail->Body    = '
        <div style="font-family: Arial, sans-serif; padding: 20px; background-color: #f8fafc; border-radius: 10px;">
            <h2 style="color: #26658C;">SkillBridge SMTP Diagnostic Success!</h2>
            <p>This is a test email dispatched from <strong>' . htmlspecialchars(APP_NAME) . '</strong>.</p>
            <p><strong>Timestamp:</strong> ' . date('Y-m-d H:i:s T') . '</p>
            <p><strong>Configured SMTP Host:</strong> ' . htmlspecialchars(SMTP_HOST) . ':' . SMTP_PORT . '</p>
            <p><strong>Authenticated User:</strong> ' . htmlspecialchars(SMTP_USER) . '</p>
            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 15px 0;">
            <p style="font-size: 12px; color: #64748b;">If you received this message, your SkillBridge SMTP Subsystem is 100% operational.</p>
        </div>';

        $mail->send();
        $testResult = [
            'success' => true,
            'message' => "SMTP Authentication & Email Dispatch SUCCESSFUL! Test email sent to {$testEmail}."
        ];
    } catch (\Throwable $e) {
        $testResult = [
            'success' => false,
            'message' => "SMTP Error: " . $mail->ErrorInfo . " (Exception: " . $e->getMessage() . ")"
        ];
    }
    $debugLogOutput = ob_get_clean();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #0b1329; color: #e2e8f0; padding-bottom: 50px; }
        .diagnostic-card { background: #131e3a; border: 1px solid #1e2d5a; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .config-badge { background: #1a294d; border: 1px solid #2a3f75; color: #38bdf8; font-family: monospace; font-size: 0.9rem; padding: 4px 10px; border-radius: 6px; }
        .debug-box { background: #050b14; border: 1px solid #1e293b; color: #38bdf8; font-family: 'Courier New', monospace; font-size: 0.85rem; max-height: 400px; overflow-y: auto; white-space: pre-wrap; padding: 15px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="container py-5" style="max-width: 900px;">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary">
        <div>
            <h2 class="fw-bold text-white mb-0" style="font-family: 'Outfit', sans-serif;"><i class="fa-solid fa-paper-plane text-info me-2"></i>SkillBridge SMTP Diagnostic Tool</h2>
            <p class="text-muted small mb-0">Single Source of Truth Configuration Verification & SMTP Tester</p>
        </div>
        <a href="<?= BASE_URL ?>smtp_health.php" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-heart-pulse me-1"></i> Health Check Page</a>
    </div>

    <!-- Loaded Configuration Section -->
    <div class="diagnostic-card p-4 mb-4">
        <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-sliders text-warning me-2"></i>Loaded SMTP Configuration</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <span class="text-muted small d-block">SMTP Host</span>
                <span class="config-badge"><?= htmlspecialchars(SMTP_HOST) ?></span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small d-block">Port</span>
                <span class="config-badge"><?= SMTP_PORT ?></span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small d-block">Security</span>
                <span class="config-badge"><?= htmlspecialchars(SMTP_SECURE) ?></span>
            </div>
            <div class="col-md-6">
                <span class="text-muted small d-block">SMTP Username</span>
                <span class="config-badge"><?= htmlspecialchars(SMTP_USER) ?></span>
            </div>
            <div class="col-md-6">
                <span class="text-muted small d-block">App Password (Masked)</span>
                <span class="config-badge text-warning"><?= htmlspecialchars(mask_secret_str(SMTP_PASS)) ?> (<?= strlen(str_replace(' ', '', SMTP_PASS)) ?> chars)</span>
            </div>
            <div class="col-md-6">
                <span class="text-muted small d-block">From Address</span>
                <span class="config-badge"><?= htmlspecialchars(SMTP_FROM_EMAIL) ?></span>
            </div>
            <div class="col-md-6">
                <span class="text-muted small d-block">From Name</span>
                <span class="config-badge"><?= htmlspecialchars(SMTP_FROM_NAME) ?></span>
            </div>
        </div>
    </div>

    <!-- Test Trigger Form -->
    <div class="diagnostic-card p-4 mb-4">
        <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-vial text-success me-2"></i>Run SMTP Authentication & Mail Test</h5>
        <form method="POST">
            <div class="mb-3">
                <label for="test_email" class="form-label text-light small fw-medium">Recipient Email Address</label>
                <input type="email" class="form-control bg-dark text-white border-secondary" id="test_email" name="test_email" value="<?= htmlspecialchars($testEmail) ?>" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="verbose_debug" id="verbose_debug" <?= $verboseDebug ? 'checked' : '' ?>>
                <label class="form-check-label text-light small" for="verbose_debug">
                    Enable Full Level-3 SMTP Debug Output (SMTP::DEBUG_SERVER)
                </label>
            </div>
            <button type="submit" name="run_test" class="btn btn-primary px-4 fw-semibold"><i class="fa-solid fa-play me-2"></i>Run SMTP Test</button>
        </form>
    </div>

    <!-- Result Box -->
    <?php if ($testResult !== null): ?>
        <div class="alert <?= $testResult['success'] ? 'alert-success' : 'alert-danger' ?> shadow-sm mb-4">
            <h5 class="alert-heading fw-bold mb-1">
                <i class="fa-solid <?= $testResult['success'] ? 'fa-circle-check' : 'fa-triangle-exclamation' ?> me-2"></i>
                <?= $testResult['success'] ? 'SMTP Dispatch Successful' : 'SMTP Dispatch Failed' ?>
            </h5>
            <p class="mb-0 small"><?= htmlspecialchars($testResult['message']) ?></p>
        </div>
    <?php endif; ?>

    <!-- Debug Log Output -->
    <?php if (!empty($debugLogOutput)): ?>
        <div class="diagnostic-card p-4">
            <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-terminal text-info me-2"></i>Live SMTP Debug Log</h5>
            <div class="debug-box"><?= $debugLogOutput ?></div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
