<?php
/**
 * SkillBridge - Admin Notification Management Module
 * Premium communication hub with live search, sorting, filtering, read management, and pagination.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('admin');

$db = Database::getInstance();
$adminUserId = $_SESSION['user_id'] ?? 0;

// Fetch all notifications for admin ordered newest to oldest
$notifications = $db->fetchAll(
    "SELECT * FROM notifications WHERE user_id = ? OR user_id IS NULL ORDER BY created_at DESC, id DESC",
    [$adminUserId]
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

$pageTitle = "Admin Notification Management - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<style>
/* Notification Center Premium Styles */
.notif-hub-card {
    border: 1px solid var(--border);
    background: var(--card-bg, #ffffff);
    border-radius: 16px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.notif-hub-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
    border-color: var(--primary);
}
.notif-hub-card.unread {
    border-left: 4px solid var(--primary);
    background: var(--primary-light, rgba(37, 99, 235, 0.03));
}
[data-theme="dark"] .notif-hub-card.unread {
    background: rgba(37, 99, 235, 0.1);
}

.notif-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.notif-icon-assessment          { background: #D1E7DD; color: #0F5132; }
.notif-icon-announcement        { background: #CFF4FC; color: #055160; }
.notif-icon-faculty_application { background: #E0CFFC; color: #3D0A91; }
.notif-icon-student              { background: #CFE2FF; color: #084298; }
.notif-icon-faculty              { background: #E2D9F3; color: #491217; }
.notif-icon-achievement         { background: #FFF3CD; color: #664D03; }
.notif-icon-feedback            { background: #D2F4EA; color: #0A3622; }
.notif-icon-certificate         { background: #E0CFFC; color: #3D0A91; }
.notif-icon-course              { background: #CFE2FF; color: #084298; }
.notif-icon-system              { background: #E2E3E5; color: #41464B; }
.notif-icon-reminder            { background: #F8D7DA; color: #842029; }
.notif-icon-general             { background: #E0F2FE; color: #0369A1; }

.notif-filter-btn {
    border: 1px solid var(--border);
    background: var(--bg-alt, #f8fafc);
    color: var(--text-secondary);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.825rem;
    font-weight: 600;
    transition: all 0.2s ease;
    cursor: pointer;
    white-space: nowrap;
}
.notif-filter-btn:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
}
.notif-filter-btn.active {
    background: var(--primary);
    color: #ffffff;
    border-color: var(--primary);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

.btn-notif-action {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 1px solid var(--border);
    background: var(--card-bg, #ffffff);
    color: var(--text-muted);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    cursor: pointer;
}
.btn-notif-action:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
    transform: scale(1.08);
}
.btn-notif-action.delete:hover {
    background: #FEE2E2;
    color: #DC2626;
    border-color: #DC2626;
}

.notif-pagination-btn {
    min-width: 36px;
    height: 36px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--card-bg, #ffffff);
    color: var(--text-body);
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    cursor: pointer;
}
.notif-pagination-btn:hover {
    background: var(--primary-light);
    color: var(--primary);
}
.notif-pagination-btn.active {
    background: var(--primary);
    color: #ffffff;
    border-color: var(--primary);
}
</style>

<div class="dash-content">
  <!-- Header & Summary Badges -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-bell text-primary me-2"></i>Admin Notification Management</h3>
      <p class="text-muted small mb-0">System-wide event center for faculty applications, student registrations, assessment alerts, and platform logs.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3 py-2 fw-semibold small" id="badgeAll">
        All: <span id="cntAll"><?= $countAll ?></span>
      </span>
      <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-semibold small" id="badgeUnread">
        <i class="fa-solid fa-envelope me-1"></i> Unread: <span id="cntUnread"><?= $countUnread ?></span>
      </span>
      <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold small" id="badgeRead">
        <i class="fa-solid fa-envelope-open me-1"></i> Read: <span id="cntRead"><?= $countRead ?></span>
      </span>
    </div>
  </div>

  <!-- CONTROL TOOLBAR: SEARCH, SORT, FILTER TABS & BATCH ACTIONS -->
  <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
    <!-- Top Row: Search & Sort Dropdown -->
    <div class="row g-3 align-items-center mb-3">
      <div class="col-md-7 col-lg-8">
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 rounded-start-pill text-muted ps-3">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input type="text" id="notifSearchInput" class="form-control bg-light border-start-0 rounded-end-pill py-2 text-dark small" placeholder="Search by title, description, or notification type..." oninput="filterAndSortNotifs()">
        </div>
      </div>
      <div class="col-md-5 col-lg-4 d-flex justify-content-md-end gap-2">
        <select id="notifSortSelect" class="form-select rounded-pill py-2 small fw-semibold text-muted" style="border-color: var(--border);" onchange="filterAndSortNotifs()">
          <option value="newest" selected>Sort: Newest First</option>
          <option value="oldest">Sort: Oldest First</option>
          <option value="unread_first">Sort: Unread First</option>
          <option value="read_first">Sort: Read First</option>
          <option value="priority">Sort: Priority (High → Low)</option>
          <option value="type_asc">Sort: Type (A → Z)</option>
        </select>
      </div>
    </div>

    <!-- Bottom Row: Filter Tabs & Batch Buttons -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pt-2 border-top">
      <div class="d-flex align-items-center gap-2 overflow-auto pb-2 pb-lg-0" id="filterTabsContainer" style="scrollbar-width: thin;">
        <button class="notif-filter-btn active" data-filter="all" onclick="setNotifFilter('all', this)">All</button>
        <button class="notif-filter-btn" data-filter="unread" onclick="setNotifFilter('unread', this)">Unread</button>
        <button class="notif-filter-btn" data-filter="read" onclick="setNotifFilter('read', this)">Read</button>
        <button class="notif-filter-btn" data-filter="faculty_application" onclick="setNotifFilter('faculty_application', this)">Faculty Apps</button>
        <button class="notif-filter-btn" data-filter="assessment" onclick="setNotifFilter('assessment', this)">Assessments</button>
        <button class="notif-filter-btn" data-filter="announcement" onclick="setNotifFilter('announcement', this)">Announcements</button>
        <button class="notif-filter-btn" data-filter="student" onclick="setNotifFilter('student', this)">Students</button>
        <button class="notif-filter-btn" data-filter="faculty" onclick="setNotifFilter('faculty', this)">Faculty</button>
        <button class="notif-filter-btn" data-filter="course" onclick="setNotifFilter('course', this)">Courses</button>
        <button class="notif-filter-btn" data-filter="system" onclick="setNotifFilter('system', this)">System</button>
      </div>
      <div class="d-flex align-items-center gap-2 flex-shrink-0">
        <button class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-semibold small" onclick="markAllNotifsReadAjax()">
          <i class="fa-solid fa-check-double me-1"></i> Mark All Read
        </button>
        <button class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1.5 fw-semibold small" onclick="clearAllNotifsAjax()">
          <i class="fa-solid fa-trash me-1"></i> Clear All
        </button>
      </div>
    </div>
  </div>

  <!-- NOTIFICATIONS LIST CONTAINER -->
  <div id="notifsListContainer" class="d-flex flex-column gap-3">
    <?php foreach ($notifications as $n): 
        $isRead = (int)$n['is_read'] === 1;
        $type = strtolower($n['type'] ?? 'general');
        if ($type === 'recommendation') $type = 'course';
        
        $title = $n['title'] ?? 'Notification';
        $message = $n['message'] ?? '';
        $createdAt = $n['created_at'] ?? date('Y-m-d H:i:s');
        $timestamp = strtotime($createdAt);
        $timeAgo = format_time_ago($createdAt);

        // Detect priority
        $priority = strtolower($n['priority'] ?? 'normal');
        if (str_contains(strtolower($title), 'urgent') || str_contains(strtolower($title), 'high') || str_contains(strtolower($title), 'critical') || str_contains(strtolower($title), 'pending approval')) {
            $priority = 'high';
        } elseif (str_contains(strtolower($title), 'important') || str_contains(strtolower($title), 'remind') || str_contains(strtolower($title), 'application')) {
            $priority = 'medium';
        }
        $priorityScore = match($priority) { 'high' => 3, 'medium' => 2, default => 1 };

        // Icon & styling maps
        $iconClass = match($type) {
            'assessment'          => 'fa-solid fa-file-pen',
            'announcement'        => 'fa-solid fa-bullhorn',
            'faculty_application' => 'fa-solid fa-id-badge',
            'student'             => 'fa-solid fa-user-graduate',
            'faculty'             => 'fa-solid fa-user-tie',
            'achievement'         => 'fa-solid fa-trophy',
            'feedback'            => 'fa-solid fa-comment-dots',
            'certificate'         => 'fa-solid fa-certificate',
            'course'              => 'fa-solid fa-graduation-cap',
            'profile'             => 'fa-solid fa-user-gear',
            'system'              => 'fa-solid fa-server',
            'reminder'            => 'fa-solid fa-clock',
            default               => 'fa-solid fa-bell'
        };
        $iconBoxClass = 'notif-icon-' . (in_array($type, ['assessment','announcement','faculty_application','student','faculty','achievement','feedback','certificate','course','profile','system','reminder']) ? $type : 'general');
        
        $isAnnouncement = ($type === 'announcement');
        $annId = (int)($n['announcement_id'] ?? 0);
        $redirectUrl = BASE_URL . "api/notifications_action.php?action=open&id={$n['id']}";
    ?>
      <div class="notif-hub-card <?= $isRead ? '' : 'unread' ?> p-3.5"
           id="notif-card-<?= $n['id'] ?>"
           data-id="<?= $n['id'] ?>"
           data-read="<?= $isRead ? '1' : '0' ?>"
           data-type="<?= htmlspecialchars($type) ?>"
           data-title="<?= htmlspecialchars(strtolower($title)) ?>"
           data-message="<?= htmlspecialchars(strtolower($message)) ?>"
           data-timestamp="<?= $timestamp ?>"
           data-priority="<?= $priority ?>"
           data-priority-score="<?= $priorityScore ?>"
           style="cursor: pointer;"
           onclick="handleCardClick(<?= $n['id'] ?>, '<?= addslashes($redirectUrl) ?>', <?= $isAnnouncement ? 'true' : 'false' ?>, <?= $annId ?>, event)">
        <div class="d-flex align-items-start gap-3">
          <!-- Icon Box -->
          <div class="notif-icon-box <?= $iconBoxClass ?>">
            <i class="<?= $iconClass ?>"></i>
          </div>

          <!-- Body Content -->
          <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <h6 class="fw-bold text-dark mb-0 text-break" style="font-size: 0.95rem;"><?= htmlspecialchars($title) ?></h6>
                <!-- Read/Unread Badge -->
                <?php if (!$isRead): ?>
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold status-badge" style="font-size: 10px;">Unread</span>
                <?php else: ?>
                  <span class="badge bg-light text-muted border rounded-pill fw-normal status-badge" style="font-size: 10px;">Read</span>
                <?php endif; ?>

                <!-- Priority Badge -->
                <?php if ($priority === 'high'): ?>
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill fw-semibold" style="font-size: 10px;"><i class="fa-solid fa-triangle-exclamation me-1"></i> High Priority</span>
                <?php elseif ($priority === 'medium'): ?>
                  <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill fw-semibold" style="font-size: 10px;"><i class="fa-solid fa-circle-exclamation me-1"></i> Medium</span>
                <?php endif; ?>
              </div>

              <!-- Time Ago -->
              <span class="text-muted small text-nowrap" style="font-size: 12px;">
                <i class="fa-regular fa-clock me-1"></i> <?= $timeAgo ?>
              </span>
            </div>

            <!-- Description Message -->
            <p class="text-muted small mb-2 text-break" style="line-height: 1.45;"><?= htmlspecialchars($message) ?></p>

            <!-- Meta Tags -->
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-light text-secondary border rounded-2 text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.5px;"><?= str_replace('_', ' ', ucfirst($type)) ?></span>
              <span class="text-muted" style="font-size: 11px;"><?= date('M d, Y • h:i A', $timestamp) ?></span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex align-items-center gap-1.5 flex-shrink-0 ms-2" onclick="event.stopPropagation();">
            <button class="btn-notif-action toggle-read-btn" 
                    title="<?= $isRead ? 'Mark as Unread' : 'Mark as Read' ?>" 
                    onclick="toggleNotifReadStatus(<?= $n['id'] ?>, event)">
              <i class="fa-solid <?= $isRead ? 'fa-envelope' : 'fa-envelope-open' ?>"></i>
            </button>
            <button class="btn-notif-action delete" 
                    title="Delete Notification" 
                    onclick="deleteSingleNotifAjax(<?= $n['id'] ?>, event)">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- EMPTY STATE -->
  <div id="notifEmptyState" class="card border-0 shadow-sm rounded-4 p-5 text-center my-4 bg-white" style="display: none;">
    <div class="empty-icon-ring mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2.2rem; display: flex; align-items: center; justify-content: center; background: var(--bg-alt, #f8fafc); border-radius: 50%;">
      <i class="fa-solid fa-bell text-muted"></i>
    </div>
    <h4 class="fw-bold text-dark mb-2">No Notifications Yet</h4>
    <p class="text-muted small mb-4 mx-auto" style="max-width: 420px;">You'll see updates and important activities here.</p>
    <div>
      <a href="<?= BASE_URL ?>admin/dashboard.php" class="btn btn-primary rounded-pill px-4 py-2 small fw-semibold">
        <i class="fa-solid fa-gauge-high me-1"></i> Back to Dashboard
      </a>
    </div>
  </div>

  <!-- PAGINATION CONTROLS -->
  <div id="notifPaginationContainer" class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 pt-2">
    <span class="text-muted small" id="paginationInfo">Showing 1-10 of 10 notifications</span>
    <div class="d-flex align-items-center gap-1" id="paginationButtons">
      <!-- Buttons dynamically populated -->
    </div>
  </div>
</div>

<script>
const BASE_URL = '<?= BASE_URL ?>';
let activeFilter = 'all';
let currentPage = 1;
const itemsPerPage = 10;

function setNotifFilter(filter, btn) {
    activeFilter = filter;
    document.querySelectorAll('.notif-filter-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    currentPage = 1;
    filterAndSortNotifs();
}

function filterAndSortNotifs() {
    const searchVal = document.getElementById('notifSearchInput').value.toLowerCase().trim();
    const sortVal = document.getElementById('notifSortSelect').value;
    const cards = Array.from(document.querySelectorAll('.notif-hub-card'));

    // 1. Filter
    let visibleCards = cards.filter(card => {
        const read = card.dataset.read;
        const type = card.dataset.type;
        const title = card.dataset.title;
        const message = card.dataset.message;

        // Filter tab match
        let matchesTab = false;
        if (activeFilter === 'all') matchesTab = true;
        else if (activeFilter === 'unread' && read === '0') matchesTab = true;
        else if (activeFilter === 'read' && read === '1') matchesTab = true;
        else if (activeFilter === type) matchesTab = true;

        // Search text match
        let matchesSearch = true;
        if (searchVal.length > 0) {
            matchesSearch = title.includes(searchVal) || message.includes(searchVal) || type.includes(searchVal);
        }

        return matchesTab && matchesSearch;
    });

    // 2. Sort
    visibleCards.sort((a, b) => {
        const timeA = parseInt(a.dataset.timestamp);
        const timeB = parseInt(b.dataset.timestamp);
        const readA = parseInt(a.dataset.read);
        const readB = parseInt(b.dataset.read);
        const prioA = parseInt(a.dataset.priorityScore);
        const priob = parseInt(b.dataset.priorityScore);
        const typeA = a.dataset.type;
        const typeB = b.dataset.type;

        if (sortVal === 'newest') return timeB - timeA;
        if (sortVal === 'oldest') return timeA - timeB;
        if (sortVal === 'unread_first') return readA - readB || timeB - timeA;
        if (sortVal === 'read_first') return readB - readA || timeB - timeA;
        if (sortVal === 'priority') return priob - prioA || timeB - timeA;
        if (sortVal === 'type_asc') return typeA.localeCompare(typeB) || timeB - timeA;
        return timeB - timeA;
    });

    // 3. Hide all cards first
    cards.forEach(c => c.style.display = 'none');

    // 4. Pagination math
    const totalVisible = visibleCards.length;
    const totalPages = Math.ceil(totalVisible / itemsPerPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;

    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = startIdx + itemsPerPage;

    const pageCards = visibleCards.slice(startIdx, endIdx);

    // Re-append sorted visible cards to container
    const container = document.getElementById('notifsListContainer');
    pageCards.forEach(c => {
        c.style.display = 'block';
        container.appendChild(c);
    });

    // 5. Render Empty State
    const emptyState = document.getElementById('notifEmptyState');
    const pagContainer = document.getElementById('notifPaginationContainer');
    if (totalVisible === 0) {
        emptyState.style.display = 'block';
        pagContainer.style.display = 'none';
    } else {
        emptyState.style.display = 'none';
        pagContainer.style.display = 'flex';
        renderPaginationControls(totalVisible, totalPages, startIdx, Math.min(endIdx, totalVisible));
    }

    updateCountBadges();
}

function renderPaginationControls(totalItems, totalPages, start, end) {
    document.getElementById('paginationInfo').textContent = `Showing ${start + 1}-${end} of ${totalItems} notifications`;
    const btnContainer = document.getElementById('paginationButtons');
    btnContainer.innerHTML = '';

    if (totalPages <= 1) return;

    // Prev Button
    const prevBtn = document.createElement('button');
    prevBtn.className = 'notif-pagination-btn';
    prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; filterAndSortNotifs(); } };
    btnContainer.appendChild(prevBtn);

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `notif-pagination-btn ${i === currentPage ? 'active' : ''}`;
        pageBtn.textContent = i;
        pageBtn.onclick = () => { currentPage = i; filterAndSortNotifs(); };
        btnContainer.appendChild(pageBtn);
    }

    // Next Button
    const nextBtn = document.createElement('button');
    nextBtn.className = 'notif-pagination-btn';
    nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; filterAndSortNotifs(); } };
    btnContainer.appendChild(nextBtn);
}

function updateCountBadges() {
    const cards = document.querySelectorAll('.notif-hub-card');
    let total = cards.length, unread = 0, read = 0;
    cards.forEach(c => c.dataset.read === '1' ? read++ : unread++);

    const setTxt = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    setTxt('cntAll', total);
    setTxt('cntUnread', unread);
    setTxt('cntRead', read);

    const navBadge = document.getElementById('notifBadge');
    if (navBadge) {
        navBadge.textContent = unread;
        navBadge.style.display = unread > 0 ? 'inline-block' : 'none';
    }
}

function handleCardClick(id, redirectUrl, isAnnouncement, annId, event) {
    if (isAnnouncement && annId > 0 && typeof window.openAnnouncementModal === 'function') {
        window.openAnnouncementModal(annId);
        fetch(BASE_URL + 'api/notifications_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=mark_read&id=' + id
        }).then(() => {
            markCardDOMRead(id, true);
        });
    } else {
        window.location.href = redirectUrl;
    }
}

function markCardDOMRead(id, isRead) {
    const card = document.getElementById('notif-card-' + id);
    if (!card) return;
    card.dataset.read = isRead ? '1' : '0';
    if (isRead) {
        card.classList.remove('unread');
    } else {
        card.classList.add('unread');
    }

    const badge = card.querySelector('.status-badge');
    if (badge) {
        if (isRead) {
            badge.className = 'badge bg-light text-muted border rounded-pill fw-normal status-badge';
            badge.textContent = 'Read';
        } else {
            badge.className = 'badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold status-badge';
            badge.textContent = 'Unread';
        }
    }

    const toggleBtn = card.querySelector('.toggle-read-btn');
    if (toggleBtn) {
        toggleBtn.title = isRead ? 'Mark as Unread' : 'Mark as Read';
        toggleBtn.querySelector('i').className = isRead ? 'fa-solid fa-envelope' : 'fa-solid fa-envelope-open';
    }
}

function toggleNotifReadStatus(id, event) {
    event.stopPropagation();
    const card = document.getElementById('notif-card-' + id);
    if (!card) return;
    const currentlyRead = card.dataset.read === '1';
    const nextAction = currentlyRead ? 'mark_unread' : 'mark_read';

    fetch(BASE_URL + 'api/notifications_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=${nextAction}&id=${id}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            markCardDOMRead(id, !currentlyRead);
            filterAndSortNotifs();
        }
    });
}

function deleteSingleNotifAjax(id, event) {
    event.stopPropagation();
    fetch(BASE_URL + 'api/notifications_action.php', {
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
                    filterAndSortNotifs();
                }, 300);
            }
        }
    });
}

function markAllNotifsReadAjax() {
    fetch(BASE_URL + 'api/notifications_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=mark_all_read'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notif-hub-card').forEach(c => {
                markCardDOMRead(c.dataset.id, true);
            });
            filterAndSortNotifs();
        }
    });
}

function clearAllNotifsAjax() {
    if (!confirm('Are you sure you want to clear all notifications?')) return;
    fetch(BASE_URL + 'api/notifications_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=clear_all'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notif-hub-card').forEach(c => c.remove());
            filterAndSortNotifs();
        }
    });
}

// Initial Run
document.addEventListener('DOMContentLoaded', () => {
    filterAndSortNotifs();
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
