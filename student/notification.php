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
    "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC, id DESC",
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
<div id="notifications-section">
    <?php if (empty($notifications)): ?>
        <div class="notif-empty-state visible card border-0 shadow-sm rounded-4 p-5 text-center my-4 bg-white">
            <div class="empty-icon-ring mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2.2rem; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-bell text-muted"></i></div>
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
            <?php 
                $isAnnouncement = ($type === 'announcement');
                $notifUrl = $isAnnouncement ? '#' : BASE_URL . "api/notifications_action.php?action=open&id={$n['id']}";
            ?>
            <div class="notif-card <?= $isRead ? '' : 'unread' ?> <?= !$isAnnouncement ? 'clickable-notif' : '' ?>" 
                 id="notif-card-<?= $n['id'] ?>" 
                 data-read="<?= $isRead ? '1' : '0' ?>"
                 <?= !$isAnnouncement ? 'onclick="window.location.href=\'' . $notifUrl . '\'"' : '' ?>>
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
                            <span class="notif-card-tag bg-primary-subtle text-primary border border-primary-subtle unread-label">Unread</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="notif-card-actions" onclick="event.stopPropagation();">
                    <?php if (!$isRead): ?>
                        <button class="notif-action-btn text-success mark-read-btn" title="Mark as Read" onclick="markSingleNotifRead(<?= $n['id'] ?>)">
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
const BASE_URL = '<?= BASE_URL ?>';
let currentNotifTab = 'all';

function filterNotifTab(tab) {
    currentNotifTab = tab;
    document.querySelectorAll('.notif-filter-tab').forEach(t => t.classList.remove('active'));
    const tabBtn = document.getElementById('tab-' + tab);
    if (tabBtn) tabBtn.classList.add('active');

    document.querySelectorAll('.notif-card').forEach(card => {
        const isRead = card.getAttribute('data-read') === '1';
        if (tab === 'all') {
            card.style.display = 'flex';
        } else if (tab === 'unread' && !isRead) {
            card.style.display = 'flex';
        } else if (tab === 'read' && isRead) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function refreshCountBadges() {
    const cards = document.querySelectorAll('.notif-card');
    let total = cards.length, unread = 0, read = 0;
    cards.forEach(c => c.getAttribute('data-read') === '1' ? read++ : unread++);

    const el = (id) => document.getElementById(id);
    if (el('count-all'))    el('count-all').textContent    = total;
    if (el('count-unread')) el('count-unread').textContent = unread;
    if (el('count-read'))   el('count-read').textContent   = read;
    if (el('unreadCountNum')) el('unreadCountNum').textContent = unread;

    const navBadge = el('notifBadge');
    if (navBadge) {
        navBadge.textContent = unread;
        navBadge.style.display = unread > 0 ? 'inline-block' : 'none';
    }
}

function renderEmptyState() {
    const section = document.getElementById('notifications-section');
    if (!section) return;
    section.innerHTML = `
        <div class="notif-empty-state visible card border-0 shadow-sm rounded-4 p-5 text-center my-4 bg-white">
            <div class="empty-icon-ring mx-auto mb-3" style="width:80px;height:80px;font-size:2.2rem;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-bell text-muted"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">All caught up!</h4>
            <p class="text-muted small mb-4 mx-auto" style="max-width:400px;">
                You have no notifications right now. Keep learning and we will alert you of score updates, achievements, and course recommendations.
            </p>
            <div>
                <a href="${BASE_URL}student/dashboard.php" class="btn btn-primary bg-gradient-primary border-0 rounded-pill px-4">
                    <i class="fa-solid fa-gauge-high me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>`;
    refreshCountBadges();
}

function markSingleNotifRead(id) {
    fetch(BASE_URL + 'api/notifications_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=mark_read&id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) return;
        const card = document.getElementById('notif-card-' + id);
        if (card) {
            card.classList.remove('unread');
            card.setAttribute('data-read', '1');
            card.querySelectorAll('.unread-label, .mark-read-btn').forEach(el => el.remove());
        }
        refreshCountBadges();
        filterNotifTab(currentNotifTab);
    });
}

function deleteSingleNotif(id) {
    fetch(BASE_URL + 'api/notifications_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=delete&id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) return;
        const card = document.getElementById('notif-card-' + id);
        if (!card) return;
        card.style.transition = 'all 0.3s ease';
        card.style.opacity = '0';
        card.style.transform = 'translateX(30px)';
        setTimeout(() => {
            card.remove();
            filterNotifTab(currentNotifTab);
            if (document.querySelectorAll('.notif-card').length === 0) {
                renderEmptyState();
            } else {
                refreshCountBadges();
            }
        }, 300);
    });
}

function markAllNotificationsReadPage() {
    const unreadCards = document.querySelectorAll('.notif-card[data-read="0"]');
    if (unreadCards.length === 0) {
        alert('All notifications are already marked as read.');
        return;
    }

    fetch(BASE_URL + 'api/mark_notifications_read.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: ''
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) return;
        document.querySelectorAll('.notif-card').forEach(card => {
            card.classList.remove('unread');
            card.setAttribute('data-read', '1');
            card.querySelectorAll('.unread-label, .mark-read-btn').forEach(el => el.remove());
        });
        refreshCountBadges();
        filterNotifTab(currentNotifTab);
    });
}

function clearAllNotificationsPage() {
    if (!confirm('Are you sure you want to clear all notifications? This cannot be undone.')) return;

    fetch(BASE_URL + 'api/notifications_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=clear_all'
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) return;
        renderEmptyState();
    });
}

window.initNotifications = function() {
    filterNotifTab('all');
};

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    window.initNotifications();
} else {
    document.addEventListener('DOMContentLoaded', window.initNotifications);
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
