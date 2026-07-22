<?php
/**
 * SkillBridge - Subsystem Health Check Tool
 * Verifies PHP Environment, Extensions, Composer Autoload, PHPMailer, OpenSSL, and Configuration Constants.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/mail.php';

$pageTitle = "Subsystem Health Check – SkillBridge";

$checks = [];

// 1. PHP Version
$checks[] = [
    'name' => 'PHP 8.2+ Compatibility',
    'pass' => version_compare(PHP_VERSION, '8.2.0', '>='),
    'detail' => 'Current version: PHP ' . PHP_VERSION
];

// 2. Composer Autoloader
$composerPath = __DIR__ . '/vendor/autoload.php';
$checks[] = [
    'name' => 'Composer Autoloader',
    'pass' => file_exists($composerPath),
    'detail' => file_exists($composerPath) ? 'vendor/autoload.php is present' : 'MISSING vendor/autoload.php'
];

// 3. PHPMailer Class Installation
$phpmailerExists = class_exists('PHPMailer\PHPMailer\PHPMailer');
$checks[] = [
    'name' => 'PHPMailer Library Class',
    'pass' => $phpmailerExists,
    'detail' => $phpmailerExists ? 'PHPMailer\PHPMailer\PHPMailer is loaded' : 'Class not found in autoloader'
];

// 4. OpenSSL Extension & Version
$hasOpenSSL = extension_loaded('openssl');
$checks[] = [
    'name' => 'OpenSSL Extension (TLS/SSL)',
    'pass' => $hasOpenSSL,
    'detail' => $hasOpenSSL ? OPENSSL_VERSION_TEXT : 'OpenSSL extension is DISABLED!'
];

// 5. Required PHP Extensions
$requiredExts = ['pdo', 'pdo_mysql', 'mbstring', 'curl', 'json', 'session'];
$missingExts = [];
foreach ($requiredExts as $ext) {
    if (!extension_loaded($ext)) {
        $missingExts[] = $ext;
    }
}
$checks[] = [
    'name' => 'Required PHP Extensions',
    'pass' => empty($missingExts),
    'detail' => empty($missingExts) ? 'All required extensions loaded (' . implode(', ', $requiredExts) . ')' : 'Missing extensions: ' . implode(', ', $missingExts)
];

// 6. SMTP Configuration Constants Check
$requiredConstants = ['SMTP_HOST', 'SMTP_PORT', 'SMTP_SECURE', 'SMTP_USER', 'SMTP_PASS', 'SMTP_FROM_EMAIL', 'SMTP_FROM_NAME', 'SUPPORT_EMAIL'];
$missingConstants = [];
foreach ($requiredConstants as $const) {
    if (!defined($const)) {
        $missingConstants[] = $const;
    }
}
$checks[] = [
    'name' => 'Single Source Configuration Constants',
    'pass' => empty($missingConstants),
    'detail' => empty($missingConstants) ? 'All 8 SMTP constants defined in config/mail.php' : 'Missing constants: ' . implode(', ', $missingConstants)
];

// 7. Real-Time Socket Reachability Check to SMTP Server
$socketPass = false;
$socketDetail = '';
$t1 = microtime(true);
$fp = @fsockopen(SMTP_HOST, SMTP_PORT, $errno, $errstr, 5);
$t2 = microtime(true);
if ($fp) {
    $socketPass = true;
    $banner = trim(fgets($fp, 512));
    $socketDetail = 'Connected to ' . SMTP_HOST . ':' . SMTP_PORT . ' in ' . round(($t2 - $t1) * 1000, 1) . 'ms. Banner: ' . $banner;
    fclose($fp);
} else {
    $socketDetail = 'Socket Connection Failed to ' . SMTP_HOST . ':' . SMTP_PORT . " - Error {$errno}: {$errstr}";
}
$checks[] = [
    'name' => 'SMTP Server Reachability (' . SMTP_HOST . ':' . SMTP_PORT . ')',
    'pass' => $socketPass,
    'detail' => $socketDetail
];

$allPassed = true;
foreach ($checks as $c) {
    if (!$c['pass']) {
        $allPassed = false;
        break;
    }
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
        .health-card { background: #131e3a; border: 1px solid #1e2d5a; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .status-pill-pass { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
        .status-pill-fail { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
    </style>
</head>
<body>
<div class="container py-5" style="max-width: 900px;">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary">
        <div>
            <h2 class="fw-bold text-white mb-0" style="font-family: 'Outfit', sans-serif;"><i class="fa-solid fa-heart-pulse text-danger me-2"></i>SkillBridge Subsystem Health Check</h2>
            <p class="text-muted small mb-0">Automated Environment, Extension & SMTP Reachability Verification</p>
        </div>
        <a href="<?= BASE_URL ?>smtp_test.php" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-paper-plane me-1"></i> Diagnostic Tool</a>
    </div>

    <!-- Overall Summary Banner -->
    <div class="alert <?= $allPassed ? 'alert-success' : 'alert-warning' ?> p-4 rounded-4 shadow-sm mb-4">
        <h4 class="fw-bold alert-heading mb-1">
            <i class="fa-solid <?= $allPassed ? 'fa-circle-check' : 'fa-triangle-exclamation' ?> me-2"></i>
            <?= $allPassed ? 'System Health: OPTIMAL' : 'System Health: ACTION REQUIRED' ?>
        </h4>
        <p class="mb-0 small">
            <?= $allPassed ? 'All core PHP environment modules, PHPMailer libraries, OpenSSL extensions, and SMTP socket checks passed successfully.' : 'One or more subsystem health checks require attention.' ?>
        </p>
    </div>

    <!-- Individual Check Cards -->
    <div class="health-card p-4">
        <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-list-check text-info me-2"></i>Subsystem Verification Checklist</h5>
        <div class="list-group list-group-flush bg-transparent">
            <?php foreach ($checks as $index => $c): ?>
                <div class="list-group-item bg-transparent text-white border-secondary py-3 d-flex align-items-start justify-content-between">
                    <div>
                        <div class="fw-semibold fs-6 mb-1 text-light"><?= ($index + 1) ?>. <?= htmlspecialchars($c['name']) ?></div>
                        <div class="text-muted small font-monospace"><?= htmlspecialchars($c['detail']) ?></div>
                    </div>
                    <div>
                        <?php if ($c['pass']): ?>
                            <span class="status-pill-pass"><i class="fa-solid fa-check me-1"></i> PASSED</span>
                        <?php else: ?>
                            <span class="status-pill-fail"><i class="fa-solid fa-xmark me-1"></i> FAILED</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>
