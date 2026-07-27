
<?php
/**
 * SkillBridge - System Activity Logs Audit Viewer
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('admin');

$db = Database::getInstance();

$search = trim($_GET['search'] ?? '');
$sql = "SELECT l.*, u.username, u.role 
        FROM activity_logs l 
        LEFT JOIN users u ON l.user_id = u.id 
        WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (u.username LIKE ? OR l.action LIKE ? OR l.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY l.created_at DESC LIMIT 100";
$logs = $db->fetchAll($sql, $params);

$pageTitle = "Activity Audit Logs - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-journal-text text-info me-2"></i>System Activity Audit Logs</h3>
        <p class="text-muted small mb-0">Real-time audit trail of user logins, assessment submissions, and administrative events</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="<?= BASE_URL ?>admin/activity-logs.php" method="GET" class="row g-2 align-items-center">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by username, action, or description..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary bg-gradient-primary border-0 w-100 rounded-3">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="audit-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="audit-table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">LOG ID</th>
                        <th>USER</th>
                        <th>ROLE</th>
                        <th>ACTION</th>
                        <th>DESCRIPTION</th>
                        <th>IP ADDRESS</th>
                        <th class="pe-4 text-end">TIMESTAMP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No activity logs recorded.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td class="ps-4 font-monospace opacity-75 small">#<?= $l['id'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($l['username'] ?? 'Guest / System') ?></td>
                                <td><span class="badge <?= get_role_badge_class($l['role'] ?? 'SYSTEM') ?>"><?= strtoupper($l['role'] ?? 'SYSTEM') ?></span></td>
                                <td><span class="badge <?= get_action_badge_class($l['action']) ?>"><?= htmlspecialchars($l['action']) ?></span></td>
                                <td><?= htmlspecialchars($l['description']) ?></td>
                                <td class="font-monospace opacity-75"><?= htmlspecialchars($l['ip_address']) ?></td>
                                <td class="pe-4 text-end opacity-75"><?= format_date($l['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
