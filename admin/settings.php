<?php
/**
 * SkillBridge - Editable System Configuration Settings
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('admin');

$db = Database::getInstance();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid CSRF token.';
    } else {
        $settings = $_POST['settings'] ?? [];
        foreach ($settings as $key => $val) {
            $db->update('system_settings', ['setting_value' => trim($val)], 'setting_key = ?', [$key]);
        }
        log_activity($_SESSION['user_id'], 'SYSTEM_SETTING_UPDATE', 'Updated system settings');
        $success = 'System settings updated successfully.';
    }
}

$rawSettings = $db->fetchAll("SELECT * FROM system_settings ORDER BY setting_group ASC");
$settingsMap = [];
foreach ($rawSettings as $s) {
    $settingsMap[$s['setting_key']] = $s;
}

$pageTitle = "System Settings - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-sliders text-dark me-2"></i>System Settings & Configuration</h3>
        <p class="text-muted small mb-0">Customize application parameters, passing thresholds, and session options</p>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 max-w-4xl mx-auto">
    <div class="card-body p-4 p-md-5">
        <form action="<?= BASE_URL ?>admin/settings.php" method="POST">
            <?= csrf_field() ?>

            <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary">General Configuration</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Application Platform Name</label>
                    <input type="text" name="settings[site_name]" class="form-control" value="<?= htmlspecialchars($settingsMap['site_name']['setting_value'] ?? 'SkillBridge LMS') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Educational Institution Name</label>
                    <input type="text" name="settings[institution_name]" class="form-control" value="<?= htmlspecialchars($settingsMap['institution_name']['setting_value'] ?? 'Global Institute of Technology') ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold small text-secondary">Admin Contact Email</label>
                    <input type="email" name="settings[admin_email]" class="form-control" value="<?= htmlspecialchars($settingsMap['admin_email']['setting_value'] ?? 'admin@skillbridge.edu') ?>">
                </div>
            </div>

            <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary">Assessment & Algorithm Rules</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Default Passing Score Threshold (%)</label>
                    <input type="number" name="settings[pass_mark_threshold]" class="form-control" value="<?= htmlspecialchars($settingsMap['pass_mark_threshold']['setting_value'] ?? '60') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Automated Skill Recommendations</label>
                    <select name="settings[enable_auto_recommendations]" class="form-select">
                        <option value="1" <?= ($settingsMap['enable_auto_recommendations']['setting_value'] ?? '1') == '1' ? 'selected' : '' ?>>Enabled (Trigger on weak score)</option>
                        <option value="0" <?= ($settingsMap['enable_auto_recommendations']['setting_value'] ?? '1') == '0' ? 'selected' : '' ?>>Disabled</option>
                    </select>
                </div>
            </div>

            <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary">Security & Session Parameters</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Session Expiration Timeout (Seconds)</label>
                    <input type="number" name="settings[session_timeout]" class="form-control" value="<?= htmlspecialchars($settingsMap['session_timeout']['setting_value'] ?? '3600') ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary bg-gradient-primary border-0 rounded-pill px-4 py-2 fw-semibold">
                Save System Settings
            </button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
