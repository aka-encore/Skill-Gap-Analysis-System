<?php
/**
 * SkillBridge - System Broadcast Announcement Engine
 * Dedicated Admin Announcements Management Page
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('admin');

$db = Database::getInstance();
$currentUserId = $_SESSION['user_id'] ?? 0;
$currentUserRole = $_SESSION['user_role'] ?? 'admin';
$error = '';
$success = '';

// Handle Form Submissions (Create, Edit, Delete)
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } else {
        $action = $_POST['action'] ?? 'create';
        $announcementId = (int)($_POST['announcement_id'] ?? 0);

        if ($action === 'delete') {
            $res = delete_announcement($announcementId, $currentUserId, $currentUserRole);
            if ($res['success']) {
                $success = $res['message'];
            } else {
                $error = $res['message'];
            }

        } else {
            // Create New Announcement
            $title = trim($_POST['title'] ?? '');
            $message = trim($_POST['message'] ?? '');
            $targetRole = $_POST['target_role'] ?? 'all';
            $priority = $_POST['priority'] ?? 'normal';
            $link = '#';

            if (empty($title) || empty($message)) {
                $error = 'Announcement Title and Message body are required.';
            } else {
                $res = create_announcement($currentUserId, $title, $message, $targetRole, $priority, $link);
                if ($res['success']) {
                    $success = $res['message'];
                } else {
                    $error = $res['message'];
                }
            }
        }
    }
}

// Fetch all announcements with creator information
// Fetch query filters
$search = trim($_GET['search'] ?? '');

$sql = "
    SELECT a.*, u.username as creator_username,
           (SELECT COUNT(*) FROM announcement_reads ar WHERE ar.announcement_id = a.id AND ar.user_id = ?) as is_read
    FROM announcements a 
    LEFT JOIN users u ON a.created_by_user_id = u.id 
    WHERE 1=1
";
$params = [$currentUserId];

if (!empty($search)) {
    $sql .= " AND (a.title LIKE ? OR a.message LIKE ?)";
    $searchWild = '%' . $search . '%';
    $params[] = $searchWild;
    $params[] = $searchWild;
}

$sql .= " ORDER BY a.created_at DESC";
$announcements = $db->fetchAll($sql, $params);

$pageTitle = "Announcements – Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-megaphone text-danger me-2"></i>Institutional Announcements</h3>
        <p class="text-muted small mb-0">Create, edit, and broadcast announcements to students, faculty, or all system users</p>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3 small border-0 mb-4 shadow-xs"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2 px-3 small border-0 mb-4 shadow-xs"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <!-- Broadcast Form Card -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-send-fill text-danger me-2"></i>New Announcement</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>admin/announcements.php" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="create">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Target Audience *</label>
                        <select name="target_role" class="form-select">
                            <option value="all">All Users (Students + Faculty + Admins)</option>
                            <option value="student">Students Only</option>
                            <option value="faculty">Faculty Only</option>
                            <option value="admin">Admins Only</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Priority Level *</label>
                        <select name="priority" class="form-select">
                            <option value="normal">Normal</option>
                            <option value="high">High Priority</option>
                            <option value="urgent">Urgent / Critical</option>
                            <option value="low">Low Priority</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Announcement Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., Scheduled Maintenance Window" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Announcement Message *</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Type announcement details..."></textarea>
                    </div>



                    <button type="submit" class="btn btn-danger bg-gradient-danger border-0 rounded-pill px-4 py-2 fw-semibold w-100 shadow-xs">
                        Broadcast Announcement Now <i class="bi bi-send ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Announcements Feed Card -->
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Announcement Management</h5>
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1.5 small"><?= count($announcements) ?> Items</span>
            </div>
            <div class="card-body p-4">
                <!-- Search -->
                <form action="<?= BASE_URL ?>admin/announcements.php" method="GET" class="mb-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search announcements by title or content..." value="<?= htmlspecialchars($search) ?>">
                        <?php if (!empty($search)): ?>
                            <a href="<?= BASE_URL ?>admin/announcements.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-xmark"></i> Clear</a>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary text-white btn-sm px-3 shadow-xs" style="background: var(--primary); border: none;">Search</button>
                    </div>
                </form>

                <div class="overflow-y-auto" style="max-height: 480px;">
                    <?php if (empty($announcements)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            <p class="small mb-0">No announcements published yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($announcements as $a): ?>
                                <?php 
                                    $isRead = (int)$a['is_read'] > 0;
                                ?>
                                <div class="p-3.5 rounded-4 border transition-all duration-200 clickable-card <?= $isRead ? 'bg-light border-secondary-subtle text-secondary' : 'bg-white border-primary shadow-sm text-dark fw-semibold' ?>" 
                                     data-announcement-id="<?= $a['id'] ?>"
                                     onclick="window.openAnnouncementModal(<?= $a['id'] ?>);"
                                     style="cursor: pointer;">
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-2">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 small fw-semibold">
                                                <i class="bi bi-megaphone-fill me-1"></i> <?= htmlspecialchars(ucfirst($a['priority'])) ?>
                                            </span>
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 small">
                                                Audience: <?= htmlspecialchars(ucfirst($a['audience'])) ?>
                                            </span>
                                            <?php if (!empty($a['department'])): ?>
                                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 small">
                                                    Dept: <?= htmlspecialchars($a['department']) ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!$isRead): ?>
                                                <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 small unread-badge">Unread</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-secondary small" style="font-size: 0.75rem;">
                                            <i class="bi bi-calendar3 me-1"></i><?= date('M d, Y • h:i A', strtotime($a['created_at'])) ?>
                                        </div>
                                    </div>

                                    <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($a['title']) ?></h6>
                                    <p class="text-secondary small mb-2 text-truncate" style="font-size: 0.85rem; line-height: 1.5; max-width: 100%;"><?= htmlspecialchars($a['message']) ?></p>

                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            <i class="bi bi-person-circle me-1"></i> By: <strong class="text-dark"><?= htmlspecialchars($a['created_by_name']) ?></strong> (<?= ucfirst(htmlspecialchars($a['created_by_role'])) ?>)
                                        </div>
                                        <!-- Admin can edit & delete ANY announcement -->
                                        <div class="d-flex gap-1" onclick="event.stopPropagation();">

                                            <form action="<?= BASE_URL ?>admin/announcements.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="announcement_id" value="<?= $a['id'] ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-xs rounded-pill px-2.5 py-1 small fw-semibold">
                                                    <i class="bi bi-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
