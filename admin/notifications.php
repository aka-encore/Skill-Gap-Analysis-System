<?php
/**
 * SkillBridge - System Broadcast Announcement & Notifications Engine
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
        $error = 'Invalid security token.';
    } else {
        $targetRole = $_POST['target_role'] ?? 'all';
        $title = trim($_POST['title'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $link = trim($_POST['link'] ?? '#');

        if (empty($title) || empty($message)) {
            $error = 'Announcement Title and Message body are required.';
        } else {
            $sql = "SELECT id FROM users";
            $params = [];
            if ($targetRole !== 'all') {
                $sql .= " WHERE role = ?";
                $params[] = $targetRole;
            }

            $users = $db->fetchAll($sql, $params);
            $count = 0;

            foreach ($users as $u) {
                $db->insert('notifications', [
                    'user_id' => $u['id'],
                    'title' => $title,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => 0,
                    'type' => 'announcement',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $count++;
            }

            $success = "Announcement broadcasted successfully to {$count} user accounts!";
        }
    }
}

$pageTitle = "Announcements - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-megaphone text-danger me-2"></i>Institutional Announcements Broadcast</h3>
        <p class="text-muted small mb-0">Dispatch notifications to students, faculty, or all system users</p>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2 px-3 small border-0 mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 max-w-3xl mx-auto">
    <div class="card-body p-4 p-md-5">
        <form action="<?= BASE_URL ?>admin/notifications.php" method="POST">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Target Audience *</label>
                <select name="target_role" class="form-select">
                    <option value="all">All Registered Users (Students + Faculty + Admins)</option>
                    <option value="student">Students Only</option>
                    <option value="faculty">Faculty Only</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Announcement Title *</label>
                <input type="text" name="title" class="form-control" placeholder="e.g., Scheduled Maintenance Window" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Announcement Message *</label>
                <textarea name="message" class="form-control" rows="4" required placeholder="Type broadcast message details..."></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Action Link (Optional)</label>
                <input type="text" name="link" class="form-control" value="#" placeholder="e.g. /student/assessments.php">
            </div>

            <button type="submit" class="btn btn-danger bg-gradient-danger border-0 rounded-pill px-4 py-2 fw-semibold">
                Broadcast Announcement Now <i class="bi bi-send ms-1"></i>
            </button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
