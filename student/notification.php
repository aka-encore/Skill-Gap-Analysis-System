<?php
/**
 * SkillBridge - Student Notification Center
 * Fully integrated with database persistence, live tab filtering, AJAX status updates & badge synchronization.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$userId = $_SESSION['user_id'];
$studentId = $_SESSION['profile_id'];
$db = Database::getInstance();

// Fetch all notifications from database ordered newest to oldest
$notifications = $db->fetchAll(
    "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC",
    [$userId]
);

$countAll = count($notifications);
$countUnread = 0;
$countRead = 0;

foreach ($notifications as $n) {
    if ((int)$n['is_read'] === 1) {
        $countRead++;
    } else {
        $countUnread++;
    }
}

$pageTitle = "Notification Center - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<!-- Header Banner -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-bell text-primary me-2"></i>Notification Center</h3>
        <p class="text-muted small mb-0">Stay on top of your learning journey with real-time alerts, course recommendations, and score updates.</p>
    </div>
    <div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-bold" id="unreadBadgeHeader">
            <i class="bi bi-bell-fill me-1"></i> <span id="unreadCountNum"><?= $countUnread ?></span> Unread
        </span>
    </div>
</div>

<!-- Notification Page Filter Bar & Actions -->
<div class="notif-page-header">
    <div class="notif-filter-tabs" role="tablist">
        <button class="notif-filter-tab active" id="tab-all" onclick="filterNotifTab('all')" role="tab">
            All <span class="tab-count" id="count-all"><?= $countAll ?></span>
        </button>
        <button class="notif-filter-tab" id="tab-unread" onclick="filterNotifTab('unread')" role="tab">
            Unread <span class="tab-count" id="count-unread"><?= $countUnread ?></span>
        </button>
        <button class="notif-filter-tab" id="tab-read" onclick="filterNotifTab('read')" role="tab">
            Read <span class="tab-count" id="count-read"><?= $countRead ?></span>
        </button>
    </div>
    <div class="notif-action-btns">
        <button class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="markAllNotificationsReadPage()">
            <i class="fa-solid fa-check-double me-1"></i> Mark All as Read
        </button>
        <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="clearAllNotificationsPage()">
            <i class="fa-solid fa-trash me-1"></i> Clear All
        </button>
    </div>
</div>

<!-- Notifications Container -->
<div id="notifPageListContainer">
    <?php if (empty($notifications)): ?>
        <div class="notif-empty-state visible card border-0 shadow-sm rounded-4 p-5 text-center my-4 bg-white">
            <div class="empty-icon-ring mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2.2rem;">🔔</div>
            <h4 class="fw-bold text-dark mb-2">All caught up!</h4>
            <p class="text-muted small mb-4 mx-auto" style="max-width: 400px;">You have no notifications right now. Keep learning and we will alert you of score updates, achievements, and course recommendations.</p>
            <div>
                <a href="<?= BASE_URL ?>student/dashboard.php" class="btn btn-primary bg-gradient-primary border-0 rounded-pill px-4">
                    <i class="fa-solid fa-gauge-high me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($notifications as $n): 
            $isRead = (int)$n['is_read'] === 1;
            $type = $n['type'] ?? 'assessment';
            
            $iconClass = match($type) {
                'assessment' => 'fa-solid fa-circle-check',
                'recommendation' => 'fa-solid fa-graduation-cap',
                'skill' => 'fa-solid fa-chart-line',
                default => 'fa-solid fa-bell'
            };

            $typeColor = match($type) {
                'assessment' => 'success',
                'recommendation' => 'primary',
                'skill' => 'accent',
                default => 'warning'
            };

            $tagClass = match($type) {
                'assessment' => 'tag-assessment',
                'recommendation' => 'tag-course',
                'skill' => 'tag-skill',
                default => 'tag-reminder'
            };
        ?>
            <div class="notif-card <?= $isRead ? '' : 'unread' ?>" id="notif-card-<?= $n['id'] ?>" data-read="<?= $isRead ? '1' : '0' ?>">
                <div class="notif-card-icon <?= $typeColor ?>">
                    <i class="<?= $iconClass ?>"></i>
                </div>
                <div class="notif-card-body">
                    <div class="notif-card-title"><?= htmlspecialchars($n['title']) ?></div>
                    <div class="notif-card-desc"><?= htmlspecialchars($n['message']) ?></div>
                    <div class="notif-card-meta">
                        <span class="notif-card-time">
                            <i class="fa-regular fa-clock me-1"></i> <?= format_date($n['created_at']) ?>
                        </span>
                        <span class="notif-card-tag <?= $tagClass ?>"><?= ucfirst($type) ?></span>
                        <?php if (!$isRead): ?>
                            <span class="notif-card-tag bg-primary-subtle text-primary border border-primary-subtle">Unread</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="notif-card-actions">
                    <?php if (!$isRead): ?>
                        <button class="notif-action-btn text-success" title="Mark as Read" onclick="markSingleNotifRead(<?= $n['id'] ?>)">
                            <i class="fa-solid fa-check"></i>
                        </button>
                    <?php endif; ?>
                    <button class="notif-action-btn delete" title="Delete" onclick="deleteSingleNotif(<?= $n['id'] ?>)">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
let currentNotifTab = 'all';

function filterNotifTab(tab) {
    currentNotifTab = tab;
    document.querySelectorAll('.notif-filter-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');

    const cards = document.querySelectorAll('.notif-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const isRead = card.getAttribute('data-read') === '1';
        if (tab === 'all') {
            card.style.display = 'flex';
            visibleCount++;
        } else if (tab === 'unread' && !isRead) {
            card.style.display = 'flex';
            visibleCount++;
        } else if (tab === 'read' && isRead) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
}

function markSingleNotifRead(id) {
    fetch('<?= BASE_URL ?>api/notifications_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=mark_read&id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const card = document.getElementById('notif-card-' + id);
            if (card) {
                card.classList.remove('unread');
                card.setAttribute('data-read', '1');
            }
            updateNotifBadgeCounts(data.unread_count);
            filterNotifTab(currentNotifTab);
        }
    });
}

function deleteSingleNotif(id) {
    fetch('<?= BASE_URL ?>api/notifications_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=delete&id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const card = document.getElementById('notif-card-' + id);
            if (card) {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'translateX(30px)';
                setTimeout(() => {
                    card.remove();
                    updateNotifBadgeCounts(data.unread_count);
                    filterNotifTab(currentNotifTab);
                }, 300);
            }
        }
    });
}

function markAllNotificationsReadPage() {
    fetch('<?= BASE_URL ?>api/mark_notifications_read.php', {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notif-card').forEach(card => {
                card.classList.remove('unread');
                card.setAttribute('data-read', '1');
            });
            updateNotifBadgeCounts(0);
            filterNotifTab(currentNotifTab);
        }
    });
}

function clearAllNotificationsPage() {
    if (!confirm('Are you sure you want to clear all notifications?')) return;

    fetch('<?= BASE_URL ?>api/notifications_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=clear_all'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const container = document.getElementById('notifPageListContainer');
            container.innerHTML = `
                <div class="notif-empty-state visible card border-0 shadow-sm rounded-4 p-5 text-center my-4 bg-white">
                    <div class="empty-icon-ring mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2.2rem;">🔔</div>
                    <h4 class="fw-bold text-dark mb-2">All caught up!</h4>
                    <p class="text-muted small mb-4 mx-auto" style="max-width: 400px;">You have no notifications right now.</p>
                    <div>
                        <a href="<?= BASE_URL ?>student/dashboard.php" class="btn btn-primary bg-gradient-primary border-0 rounded-pill px-4">
                            <i class="fa-solid fa-gauge-high me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            `;
            updateNotifBadgeCounts(0);
        }
    });
}

function updateNotifBadgeCounts(unreadCount) {
    const headerCount = document.getElementById('unreadCountNum');
    if (headerCount) headerCount.textContent = unreadCount;

    const navBadge = document.getElementById('notifBadge');
    if (navBadge) {
        if (unreadCount > 0) {
            navBadge.textContent = unreadCount;
            navBadge.style.display = 'inline-block';
        } else {
            navBadge.style.display = 'none';
        }
    }

    const unreadTabCount = document.getElementById('count-unread');
    if (unreadTabCount) unreadTabCount.textContent = unreadCount;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
