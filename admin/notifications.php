<?php
/**
 * SkillBridge - Dedicated Admin Notification Management Module
 * Professional LMS/ERP Notification Center with Filtering, Navigation, and Batch Controls.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('admin');

$db = Database::getInstance();
$adminUserId = $_SESSION['user_id'] ?? 0;
$error = '';
$success = '';

// 1. Handle Clickable Notification Open Action (Mark Read & Redirect)
if (($_GET['action'] ?? '') === 'open') {
    $notifId = (int)($_GET['id'] ?? 0);
    if ($notifId > 0) {
        $notif = $db->fetch("SELECT * FROM notifications WHERE id = ?", [$notifId]);
        if ($notif) {
            // Mark as Read
            $db->update('notifications', ['is_read' => 1], 'id = ?', [$notifId]);

            // Resolve target URL
            $targetUrl = BASE_URL . 'admin/dashboard.php';
            $link = trim($notif['link'] ?? '');
            $title = strtolower($notif['title'] ?? '');
            $type = strtolower($notif['type'] ?? '');

            if (!empty($link) && $link !== '#') {
                $targetUrl = $link;
            } elseif (str_contains($title, 'faculty application') || str_contains($title, 'faculty registration application') || $type === 'faculty_application') {
                $targetUrl = BASE_URL . 'admin/faculty-applications.php';
            } elseif (str_contains($title, 'student') || $type === 'student') {
                $targetUrl = BASE_URL . 'admin/students.php';
            } elseif (str_contains($title, 'faculty') || $type === 'faculty') {
                $targetUrl = BASE_URL . 'admin/faculty.php';
            } elseif (str_contains($title, 'course') || $type === 'course') {
                $targetUrl = BASE_URL . 'admin/courses.php';
            } elseif (str_contains($title, 'assessment') || $type === 'assessment') {
                $targetUrl = BASE_URL . 'admin/assessments.php';
            } elseif (str_contains($title, 'skill') || $type === 'skill') {
                $targetUrl = BASE_URL . 'admin/skills.php';
            }

            redirect($targetUrl);
        }
    }
    redirect(BASE_URL . 'admin/notifications.php');
}

// 2. Handle Form POST Actions (Clear Selected, Clear All, Mark All Read)
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } else {
        $postAction = $_POST['action'] ?? '';

        if ($postAction === 'clear_selected') {
            $selectedIds = $_POST['selected_ids'] ?? [];
            if (!empty($selectedIds) && is_array($selectedIds)) {
                $cleanIds = array_map('intval', $selectedIds);
                $placeholders = implode(',', array_fill(0, count($cleanIds), '?'));
                
                // STRICT SAFETY: Delete ONLY notification entries from notifications table
                $db->delete('notifications', "id IN ({$placeholders})", $cleanIds);
                $success = count($cleanIds) . " notification(s) cleared successfully.";
            } else {
                $error = "Please select at least one notification to clear.";
            }
        } elseif ($postAction === 'clear_all') {
            // STRICT SAFETY: Delete ONLY notification entries (excluding announcements if present)
            $db->delete('notifications', "(type IS NULL OR type != 'announcement')");
            $success = "All system notifications cleared successfully.";
        } elseif ($postAction === 'mark_all_read') {
            $pdo = $db->getConnection();
            $pdo->exec("UPDATE notifications SET is_read = 1 WHERE (type IS NULL OR type != 'announcement')");
            $success = "All notifications marked as read.";
        }
    }
}

// 3. Category & Filter Controls
$categoryFilter = strtolower(trim($_GET['category'] ?? 'all'));
$statusFilter = strtolower(trim($_GET['status'] ?? 'all'));

$whereClauses = ["(n.type IS NULL OR n.type != 'announcement')"];
$queryParams = [];

if ($categoryFilter !== 'all' && !empty($categoryFilter)) {
    $whereClauses[] = "LOWER(n.type) = ?";
    $queryParams[] = $categoryFilter;
}

if ($statusFilter === 'unread') {
    $whereClauses[] = "n.is_read = 0";
} elseif ($statusFilter === 'read') {
    $whereClauses[] = "n.is_read = 1";
}

$whereSql = "WHERE " . implode(" AND ", $whereClauses);

// Query filtered notifications for admin overview (Newest -> Oldest, tiebreak by ID DESC)
$allNotifications = $db->fetchAll("
    SELECT n.*, u.username, u.email, u.role 
    FROM notifications n
    LEFT JOIN users u ON n.user_id = u.id
    {$whereSql}
    ORDER BY n.created_at DESC, n.id DESC
    LIMIT 50
", $queryParams);

// Calculate Unread Count for Admin (excluding announcements)
$unreadCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM notifications WHERE is_read = 0 AND (type IS NULL OR type != 'announcement')")['cnt'] ?? 0);
$totalCount  = (int)($db->fetch("SELECT COUNT(*) as cnt FROM notifications WHERE (type IS NULL OR type != 'announcement')")['cnt'] ?? 0);

$pageTitle = "Notifications – Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-bell text-primary me-2"></i>System Notifications</h3>
        <p class="text-muted small mb-0">Manage system notifications, user alerts, and platform activity logs</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-semibold">
            <i class="bi bi-bell-fill me-1"></i> <?= $unreadCount ?> Unread
        </span>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3 small border-0 mb-4 shadow-xs"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2 px-3 small border-0 mb-4 shadow-xs"><?= $success ?></div>
<?php endif; ?>

<!-- Category Filter Tabs -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="nav nav-pills flex-nowrap overflow-x-auto pb-1 pb-md-0" style="gap: 6px;">
                <a href="<?= BASE_URL ?>admin/notifications.php?category=all&status=<?= $statusFilter ?>" class="nav-link text-nowrap rounded-pill px-3 py-1.5 small <?= $categoryFilter === 'all' ? 'active' : '' ?>">All</a>
                <a href="<?= BASE_URL ?>admin/notifications.php?category=faculty_application&status=<?= $statusFilter ?>" class="nav-link text-nowrap rounded-pill px-3 py-1.5 small <?= $categoryFilter === 'faculty_application' ? 'active' : '' ?>">Faculty Applications</a>
                <a href="<?= BASE_URL ?>admin/notifications.php?category=student&status=<?= $statusFilter ?>" class="nav-link text-nowrap rounded-pill px-3 py-1.5 small <?= $categoryFilter === 'student' ? 'active' : '' ?>">Students</a>
                <a href="<?= BASE_URL ?>admin/notifications.php?category=faculty&status=<?= $statusFilter ?>" class="nav-link text-nowrap rounded-pill px-3 py-1.5 small <?= $categoryFilter === 'faculty' ? 'active' : '' ?>">Faculty</a>
                <a href="<?= BASE_URL ?>admin/notifications.php?category=course&status=<?= $statusFilter ?>" class="nav-link text-nowrap rounded-pill px-3 py-1.5 small <?= $categoryFilter === 'course' ? 'active' : '' ?>">Courses</a>
                <a href="<?= BASE_URL ?>admin/notifications.php?category=assessment&status=<?= $statusFilter ?>" class="nav-link text-nowrap rounded-pill px-3 py-1.5 small <?= $categoryFilter === 'assessment' ? 'active' : '' ?>">Assessments</a>
                <a href="<?= BASE_URL ?>admin/notifications.php?category=skill&status=<?= $statusFilter ?>" class="nav-link text-nowrap rounded-pill px-3 py-1.5 small <?= $categoryFilter === 'skill' ? 'active' : '' ?>">Skills</a>
                <a href="<?= BASE_URL ?>admin/notifications.php?category=system&status=<?= $statusFilter ?>" class="nav-link text-nowrap rounded-pill px-3 py-1.5 small <?= $categoryFilter === 'system' ? 'active' : '' ?>">System</a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <select onchange="window.location.href='<?= BASE_URL ?>admin/notifications.php?category=<?= $categoryFilter ?>&status=' + this.value" class="form-select form-select-sm rounded-pill" style="width: 130px;">
                    <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Status</option>
                    <option value="unread" <?= $statusFilter === 'unread' ? 'selected' : '' ?>>Unread Only</option>
                    <option value="read" <?= $statusFilter === 'read' ? 'selected' : '' ?>>Read Only</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Main Notifications Form Wrapper for Batch Actions -->
<form action="<?= BASE_URL ?>admin/notifications.php" method="POST" id="notifBatchForm">
    <?= csrf_field() ?>

    <div class="card border-0 shadow-sm rounded-4 max-w-5xl mx-auto my-4">
        <div class="card-header bg-white border-0 pt-4 px-4 px-md-5 pb-2 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <div class="notif-checkbox-wrapper">
                    <input type="checkbox" id="selectAllCheckbox" class="form-check-input" onclick="toggleSelectAll(this)">
                </div>
                <label for="selectAllCheckbox" class="form-check-label small fw-semibold text-dark mb-0 cursor-pointer">Select All</label>
                <span class="text-muted small ms-1">(<?= count($allNotifications) ?> displayed)</span>
            </div>

            <!-- Clear & Action Buttons -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="submit" name="action" value="mark_all_read" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-check-double me-1"></i> Mark All Read
                </button>
                <button type="submit" name="action" value="clear_selected" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirmClearSelected()">
                    <i class="fa-solid fa-trash-can me-1"></i> Clear Selected
                </button>
                <button type="submit" name="action" value="clear_all" class="btn btn-danger bg-gradient-danger border-0 btn-sm rounded-pill px-3" onclick="return confirmClearAll()">
                    <i class="fa-solid fa-dumpster me-1"></i> Clear All
                </button>
            </div>
        </div>

        <div class="card-body p-4 p-md-5">
            <?php if (empty($allNotifications)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fa-regular fa-bell-slash text-muted" style="font-size: 3.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">No Notifications Found</h4>
                    <p class="text-muted mb-3 mx-auto" style="max-width: 460px;">There are no system notifications matching your current filters.</p>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-2">
                    <?php foreach ($allNotifications as $n): ?>
                        <?php 
                            $isRead = (int)$n['is_read'] === 1;
                            $type = strtolower($n['type'] ?? 'system');

                            $iconClass = match($type) {
                                'faculty_application' => 'fa-solid fa-user-clock text-warning',
                                'student'             => 'fa-solid fa-user-graduate text-info',
                                'faculty'             => 'fa-solid fa-chalkboard-user text-primary',
                                'course'              => 'fa-solid fa-book text-success',
                                'assessment'          => 'fa-solid fa-clipboard-check text-info',
                                'skill'               => 'fa-solid fa-lightbulb text-warning',
                                'security'            => 'fa-solid fa-shield-halved text-danger',
                                default               => 'fa-solid fa-bell text-secondary'
                            };

                            // Dynamic Clickable Link
                            $clickUrl = BASE_URL . "admin/notifications.php?action=open&id={$n['id']}";
                        ?>
                        <div class="p-3.5 rounded-4 border d-flex align-items-center gap-3 notification-card-item <?= $isRead ? 'notif-read bg-white border-secondary-subtle' : 'notif-unread bg-primary-subtle border-primary-subtle' ?>" 
                             style="<?= $isRead ? '' : 'border-left: 4px solid #0d6efd !important;' ?>">
                            
                            <!-- Checkbox for Batch Selection (Stop Propagation on click) -->
                            <div class="notif-checkbox-wrapper" onclick="event.stopPropagation();">
                                <input type="checkbox" name="selected_ids[]" value="<?= $n['id'] ?>" class="form-check-input notif-checkbox">
                            </div>

                            <!-- Icon -->
                            <div class="rounded-circle bg-white shadow-xs p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:42px; height:42px;">
                                <i class="<?= $iconClass ?> fs-5"></i>
                            </div>

                            <!-- Content (Clickable) -->
                            <div class="flex-grow-1 overflow-hidden" onclick="window.location.href='<?= $clickUrl ?>'">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <strong class="text-dark small <?= $isRead ? 'fw-semibold' : 'fw-bold' ?> text-truncate" style="max-width: 320px;">
                                            <?= htmlspecialchars($n['title']) ?>
                                        </strong>
                                        <?php if (!$isRead): ?>
                                            <span class="badge bg-primary rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">Unread</span>
                                        <?php endif; ?>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0.5 text-capitalize" style="font-size: 0.68rem;">
                                            <?= htmlspecialchars(str_replace('_', ' ', $type)) ?>
                                        </span>
                                    </div>
                                    <span class="text-muted small" style="font-size: 0.75rem;"><?= date('M d, Y • h:i A', strtotime($n['created_at'])) ?></span>
                                </div>
                                <p class="text-secondary small mb-0 text-truncate" style="font-size: 0.84rem;"><?= htmlspecialchars($n['message']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
function toggleSelectAll(master) {
    document.querySelectorAll('.notif-checkbox').forEach(cb => cb.checked = master.checked);
}

function confirmClearSelected() {
    const checkedCount = document.querySelectorAll('.notif-checkbox:checked').length;
    if (checkedCount === 0) {
        alert("Please select at least one notification to clear.");
        return false;
    }
    return confirm("Are you sure you want to clear the selected notification(s)?");
}

function confirmClearAll() {
    return confirm("Are you sure you want to clear all notifications?");
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
