<?php
/**
 * SkillBridge - Faculty Notification Center
 * Modern navigation-first communication hub with instant search, sorting, filtering, and read management.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');
check_suspended_status();

$userId = $_SESSION['user_id'];
$facultyId = $_SESSION['profile_id'];
$db = Database::getInstance();

// Fetch all notifications for faculty ordered newest to oldest
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

$pageTitle = "Faculty Notification Center - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<style>
/* Notification Center Premium Styles */
.notif-hub-card {
    border: 1px solid var(--border);
    background: var(--card-bg, #ffffff);
    border-radius: 18px;
    padding: 20px !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
}
.notif-hub-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px -6px rgba(37, 99, 235, 0.12);
    border-color: var(--primary);
}
.notif-hub-card.unread {
    border-left: 4px solid var(--primary);
    background: var(--primary-light, rgba(37, 99, 235, 0.02));
}
[data-theme="dark"] .notif-hub-card.unread {
    background: rgba(37, 99, 235, 0.08);
}

.notif-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}
.notif-icon-assessment   { background: linear-gradient(135deg, #d1e7dd, #a3cfbb); color: #0f5132; }
.notif-icon-announcement { background: linear-gradient(135deg, #cff4fc, #9eeaf9); color: #055160; }
.notif-icon-student      { background: linear-gradient(135deg, #cfe2ff, #b6d4fe); color: #084298; }
.notif-icon-achievement  { background: linear-gradient(135deg, #fff3cd, #ffe69c); color: #664d03; }
.notif-icon-feedback     { background: linear-gradient(135deg, #e0cffc, #c5a3ff); color: #3d0a91; }
.notif-icon-certificate  { background: linear-gradient(135deg, #e2d9f3, #d2c4ec); color: #491217; }
.notif-icon-course       { background: linear-gradient(135deg, #d2f4ea, #a6edd8); color: #0a3622; }
.notif-icon-system       { background: linear-gradient(135deg, #e2e3e5, #d3d6d8); color: #41464b; }
.notif-icon-general      { background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1; }

.notif-filter-btn {
    border: 1px solid var(--border);
    background: var(--bg-alt, #f8fafc);
    color: var(--text-muted);
    padding: 8px 18px;
    border-radius: 25px;
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
    width: 36px;
    height: 36px;
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

.notif-filter-btn .badge {
    transition: all 0.2s ease;
    font-size: 0.75rem;
    font-weight: 700;
}
.notif-filter-btn.active .badge {
    background-color: rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
    border-color: transparent !important;
}

/* Custom Search Bar with Icon and Animation */
.search-wrapper {
    position: relative;
    width: 100%;
}
.search-wrapper i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    pointer-events: none;
    font-size: 0.95rem;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.search-wrapper input {
    width: 100%;
    height: 48px;
    padding-left: 44px;
    padding-right: 16px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: var(--bg-alt, #f8fafc);
    color: var(--text-body);
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.search-wrapper input::placeholder {
    color: var(--text-muted, #64748b);
    font-weight: 400;
}
.search-wrapper input:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--card-bg, #ffffff);
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    padding-left: 48px;
}
.search-wrapper input:focus + i {
    color: var(--primary-hover, #1d4ed8);
    transform: translateY(-50%) scale(1.1);
}
</style>

<div class="dash-content">
  <!-- HEADER & BATCH ACTIONS -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h3 class="fw-bold mb-1" style="color: var(--text-heading);"><i class="fa-solid fa-bell text-primary me-2"></i>Faculty Notification Center</h3>
      <p class="text-muted small mb-0">Track student assessment submissions, announcements, and portal updates.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <button class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-semibold small" onclick="markAllNotifsReadAjax()">
        <i class="fa-solid fa-check-double me-1"></i> Mark All Read
      </button>
      <button class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1.5 fw-semibold small" onclick="clearAllNotifsAjax()">
        <i class="fa-solid fa-trash me-1"></i> Clear All
      </button>
    </div>
  </div>

  <!-- CONTROL TOOLBAR: SEARCH & FILTER PILLS -->
  <div class="card border-0 shadow-sm rounded-4 p-3.5 mb-4" style="background: var(--card-bg, #ffffff); border: 1px solid var(--border) !important;">
    <!-- Search Notifications Heading -->
    <div class="small fw-bold text-muted mb-2"><i class="fa-solid fa-magnifying-glass text-primary me-1"></i> Search Notifications</div>
    
    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3 mb-3">
      <!-- Search Input: Spans to fill space -->
      <div class="flex-grow-1">
        <div class="search-wrapper">
          <input type="text" id="notifSearchInput" placeholder="Search student name, title, assessment, skill..." oninput="filterAndSortNotifs()">
          <i class="fa-solid fa-magnifying-glass"></i>
        </div>
      </div>

      <!-- Segmented Status Control: All, Unread, Read -->
      <div class="d-flex align-items-center gap-2 flex-shrink-0">
        <button class="notif-filter-btn active" data-filter="all" onclick="setNotifFilter('all', this)">
          All <span class="badge bg-light text-muted border ms-1" id="cntAll"><?= $countAll ?></span>
        </button>
        <button class="notif-filter-btn" data-filter="unread" onclick="setNotifFilter('unread', this)">
          Unread <span class="badge bg-light text-muted border ms-1" id="cntUnread"><?= $countUnread ?></span>
        </button>
        <button class="notif-filter-btn" data-filter="read" onclick="setNotifFilter('read', this)">
          Read <span class="badge bg-light text-muted border ms-1" id="cntRead"><?= $countRead ?></span>
        </button>
      </div>
    </div>

    <!-- Bottom Row: Filter Chips -->
    <div class="d-flex align-items-center gap-2 overflow-auto pt-2 border-top" id="filterTabsContainer" style="scrollbar-width: thin;">
      <button class="notif-filter-btn" data-filter="assessment" onclick="setNotifFilter('assessment', this)">Assessment</button>
      <button class="notif-filter-btn" data-filter="announcement" onclick="setNotifFilter('announcement', this)">Announcement</button>
      <button class="notif-filter-btn" data-filter="feedback" onclick="setNotifFilter('feedback', this)">Feedback</button>
      <button class="notif-filter-btn" data-filter="student" onclick="setNotifFilter('student', this)">Student Activity</button>
      <button class="notif-filter-btn" data-filter="system" onclick="setNotifFilter('system', this)">System</button>
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

        // Advanced regex parser to extract structured detail fields for custom premium assessment completion layout
        $isAssessmentNotif = (str_contains(strtolower($type), 'assessment') || str_contains(strtolower($title), 'assessment completed'));
        $parsedStudent = '';
        $parsedScore = '';
        $parsedPercentage = '';
        $parsedAssessmentName = '';
        $parsedSkill = '';
        $parsedDifficulty = '';

        if ($isAssessmentNotif) {
            if (preg_match('/^(.*?)\s+has\s+successfully\s+completed/i', $message, $m)) {
                $parsedStudent = trim($m[1]);
            }
            if (preg_match('/completed\s+the\s+["\'](.*?)["\']/i', $message, $m)) {
                $parsedAssessmentName = trim($m[1]);
            }
            if (preg_match('/Skill:\s*([^|]+)/i', $message, $m)) {
                $parsedSkill = trim($m[1]);
            }
            if (preg_match('/Difficulty:\s*([^|]+)/i', $message, $m)) {
                $parsedDifficulty = trim($m[1]);
            }
            if (preg_match('/Score:\s*([^\s]+)\s*\((.*?)\)/i', $message, $m)) {
                $parsedScore = trim($m[1]);
                $parsedPercentage = trim($m[2]);
            }
        }

        // Icon maps
        $iconClass = match($type) {
            'assessment'   => 'fa-solid fa-clipboard-check',
            'announcement' => 'fa-solid fa-bullhorn',
            'student'      => 'fa-solid fa-user-graduate',
            'achievement'  => 'fa-solid fa-award',
            'feedback'     => 'fa-solid fa-comment-dots',
            'certificate'  => 'fa-solid fa-certificate',
            'course'       => 'fa-solid fa-graduation-cap',
            'profile'      => 'fa-solid fa-user',
            'system'       => 'fa-solid fa-gear',
            default        => 'fa-solid fa-bell'
        };
        $iconBoxClass = 'notif-icon-' . (in_array($type, ['assessment','announcement','student','achievement','feedback','certificate','course','profile','system']) ? $type : 'general');
        
        $redirectUrl = BASE_URL . "api/notifications_action.php?action=open&id={$n['id']}";
    ?>
      <div class="notif-hub-card <?= $isRead ? '' : 'unread' ?> p-3.5"
           id="notif-card-<?= $n['id'] ?>"
           data-id="<?= $n['id'] ?>"
           data-read="<?= $isRead ? '1' : '0' ?>"
           data-type="<?= htmlspecialchars($type) ?>"
           data-title="<?= htmlspecialchars(strtolower($title)) ?>"
           data-message="<?= htmlspecialchars(strtolower($message)) ?>"
           data-student="<?= htmlspecialchars(strtolower($parsedStudent)) ?>"
           data-assessment-name="<?= htmlspecialchars(strtolower($parsedAssessmentName)) ?>"
           data-skill="<?= htmlspecialchars(strtolower($parsedSkill)) ?>"
           data-timestamp="<?= $timestamp ?>"
           onclick="handleCardClick('<?= addslashes($redirectUrl) ?>', event)">
        <div class="d-flex align-items-start gap-3">
          <!-- Icon Box -->
          <div class="notif-icon-box <?= $iconBoxClass ?>">
            <i class="<?= $iconClass ?>"></i>
          </div>

          <!-- Body Content -->
          <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <h6 class="fw-bold mb-0 text-break text-uppercase" style="font-size: 0.75rem; color: var(--text-muted); letter-spacing: 0.5px;">
                  <?php if ($isAssessmentNotif): ?>
                    📝 Assessment Completed
                  <?php else: ?>
                    <?= htmlspecialchars($title) ?>
                  <?php endif; ?>
                </h6>
                <!-- Unread status dot -->
                <?php if (!$isRead): ?>
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold status-badge" style="font-size: 10px;">Unread</span>
                  <span class="unread-dot bg-primary rounded-circle d-inline-block" style="width: 8px; height: 8px; margin-left: 4px;"></span>
                <?php else: ?>
                  <span class="badge bg-light text-muted border rounded-pill fw-normal status-badge" style="font-size: 10px;">Read</span>
                <?php endif; ?>
              </div>

              <!-- Timestamp -->
              <span class="text-muted small text-nowrap" style="font-size: 12px;">
                <i class="fa-regular fa-clock me-1 text-primary"></i> <?= $timeAgo ?>
              </span>
            </div>

            <!-- Redesigned subtitle hierarchy and parsed tags -->
            <?php if ($isAssessmentNotif && !empty($parsedStudent) && !empty($parsedAssessmentName)): ?>
              <h4 class="fw-bold text-dark mt-1.5 mb-1" style="font-size: 1.15rem; color: var(--text-heading); font-family: inherit; letter-spacing: -0.3px;"><?= htmlspecialchars($parsedAssessmentName) ?></h4>
              
              <div class="d-flex flex-wrap gap-2 my-2.5">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 11px;">
                  <i class="fa-solid fa-user me-1"></i> Student: <?= htmlspecialchars($parsedStudent) ?>
                </span>
                <?php if (!empty($parsedSkill)): ?>
                  <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 11px;">
                    <i class="fa-solid fa-code me-1"></i> Skill: <?= htmlspecialchars($parsedSkill) ?>
                  </span>
                <?php endif; ?>
                <?php if (!empty($parsedDifficulty)): ?>
                  <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 11px;">
                    <i class="fa-solid fa-layer-group me-1"></i> Difficulty: <?= htmlspecialchars($parsedDifficulty) ?>
                  </span>
                <?php endif; ?>
                <?php if (!empty($parsedScore)): ?>
                  <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 11px;">
                    <i class="fa-solid fa-chart-line me-1"></i> Score: <?= htmlspecialchars($parsedScore) ?> (<?= htmlspecialchars($parsedPercentage) ?>)
                  </span>
                <?php endif; ?>
                <span class="badge bg-light text-secondary border rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 11px;">
                  <i class="fa-regular fa-calendar me-1"></i> Completed: <?= date('d M Y • h:i A', $timestamp) ?>
                </span>
              </div>
            <?php else: ?>
              <p class="text-muted small mb-2 text-break mt-1.5" style="line-height: 1.45;"><?= htmlspecialchars($message) ?></p>
              
              <div class="d-flex flex-wrap gap-2 my-2.5">
                <span class="badge bg-light text-secondary border rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 11px;">
                  <i class="fa-solid fa-tag me-1"></i> Type: <?= ucfirst($type) ?>
                </span>
                <span class="badge bg-light text-secondary border rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 11px;">
                  <i class="fa-regular fa-calendar me-1"></i> Date: <?= date('d M Y • h:i A', $timestamp) ?>
                </span>
              </div>
            <?php endif; ?>
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
  <div id="notifEmptyState" class="card border-0 shadow-sm rounded-4 p-5 text-center my-4 bg-white" style="display: none; background: var(--card-bg, #ffffff); border: 1px solid var(--border) !important;">
    <div class="empty-icon-ring mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2.2rem; display: flex; align-items: center; justify-content: center; background: var(--bg-alt, #f8fafc); border-radius: 50%;">
      <i class="fa-solid fa-bell-slash text-muted"></i>
    </div>
    <h4 class="fw-bold mb-1" style="color: var(--text-heading);">No Notifications</h4>
    <p class="text-muted small mb-4 mx-auto" style="max-width: 420px;">You're all caught up!</p>
    <div>
      <a href="<?= BASE_URL ?>faculty/dashboard.php" class="btn btn-primary rounded-pill px-4 py-2 small fw-semibold">
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
    const cards = Array.from(document.querySelectorAll('.notif-hub-card'));

    // 1. Filter
    let visibleCards = cards.filter(card => {
        const read = card.dataset.read;
        const type = card.dataset.type;
        const title = card.dataset.title;
        const message = card.dataset.message;
        const student = card.dataset.student || '';
        const assess = card.dataset.assessmentName || '';
        const skill = card.dataset.skill || '';

        // Filter tab match
        let matchesTab = false;
        if (activeFilter === 'all') matchesTab = true;
        else if (activeFilter === 'unread' && read === '0') matchesTab = true;
        else if (activeFilter === 'read' && read === '1') matchesTab = true;
        else if (activeFilter === type) matchesTab = true;

        // Search text match across multiple keys
        let matchesSearch = true;
        if (searchVal.length > 0) {
            matchesSearch = student.includes(searchVal) || 
                            assess.includes(searchVal) || 
                            skill.includes(searchVal) || 
                            title.includes(searchVal) || 
                            message.includes(searchVal);
        }

        return matchesTab && matchesSearch;
    });

    // 2. Sort: Always Newest First
    visibleCards.sort((a, b) => {
        const timeA = parseInt(a.dataset.timestamp);
        const timeB = parseInt(b.dataset.timestamp);
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

// Navigation-first card handler (Zero popup, modal, or alert dialog behavior)
function handleCardClick(redirectUrl, event) {
    window.location.href = redirectUrl;
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
