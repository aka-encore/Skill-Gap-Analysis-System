<?php
/**
 * SkillBridge - Top Navigation Bar Component
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$userId = $_SESSION['user_id'] ?? 0;
$unreadCount = get_unread_notifications_count($userId);
$notifications = get_user_notifications($userId, 5);
$userRole = $_SESSION['user_role'] ?? 'student';
$fullName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'User';
$avatar = $_SESSION['avatar'] ?? 'default-avatar.png';
$avatarUrl = BASE_URL . 'uploads/avatars/' . $avatar;
if (!file_exists(__DIR__ . '/../uploads/avatars/' . $avatar) || empty($avatar)) {
    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=6366f1&color=ffffff&bold=true';
}
?>
<nav class="navbar navbar-expand navbar-saas sticky-top px-3 px-md-4 py-2">
    <div class="container-fluid p-0">
        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle-btn me-3" id="sidebarToggle" type="button" aria-label="Toggle Sidebar" title="Toggle Navigation">
            <i class="bi bi-list fs-5"></i>
        </button>

        <!-- Brand / Context Role Badge -->
        <div class="d-flex align-items-center">
            <span class="role-badge-saas text-uppercase">
                <i class="bi bi-shield-check me-1"></i> <?= htmlspecialchars($userRole) ?>
            </span>
        </div>

        <!-- Quick Search Bar -->
        <div class="header-search-wrapper d-none d-md-block ms-3 position-relative">
            <i class="bi bi-search header-search-icon"></i>
            <input type="text" class="form-control header-search-input" id="globalHeaderSearch" placeholder="Search skills, assessments..." autocomplete="off">
            <span class="header-search-kbd">Ctrl K</span>
            <div class="header-search-results" id="globalSearchResults"></div>
        </div>

        <!-- Right Controls -->
        <div class="ms-auto d-flex align-items-center gap-2">
            <!-- Theme Toggle Button -->
            <button class="btn-header-action theme-toggle-btn" type="button" id="navbarThemeToggle" title="Toggle Theme" aria-label="Toggle Theme">
                <i class="bi bi-sun-fill fs-6"></i>
            </button>

            <!-- Notification Dropdown -->
            <div class="dropdown">
                <button class="btn-header-action" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                    <i class="bi bi-bell fs-5"></i>
                    <?php if ($unreadCount > 0): ?>
                        <span class="notif-pulse-badge shadow-xs" id="notifBadge"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-end dropdown-saas-menu mt-2" aria-labelledby="notifDropdown" style="width: 320px;">
                    <div class="dropdown-header d-flex justify-content-between align-items-center p-3 bg-light rounded-top-4 border-bottom">
                        <span class="fw-bold text-dark mb-0">Notifications</span>
                        <?php if ($unreadCount > 0): ?>
                            <button class="btn btn-link btn-sm text-primary p-0 text-decoration-none small fw-semibold" id="markAllReadBtn" onclick="markAllNotificationsRead()">Mark all read</button>
                        <?php endif; ?>
                    </div>
                    <div class="notification-list overflow-y-auto" style="max-height: 300px;">
                        <?php if (empty($notifications)): ?>
                            <div class="p-4 text-center text-muted small">
                                <i class="bi bi-bell-slash fs-3 d-block mb-2 text-secondary"></i>
                                No notifications yet
                            </div>
                        <?php else: ?>
                            <?php foreach ($notifications as $n): ?>
                                <a href="<?= htmlspecialchars($n['link'] ?? '#') ?>" class="dropdown-item p-3 border-bottom notification-item <?= $n['is_read'] ? 'read' : 'unread bg-primary-subtle bg-opacity-10' ?>">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="notif-icon rounded-circle bg-primary text-white p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px; height:28px; font-size:12px;">
                                            <i class="bi bi-bell"></i>
                                        </div>
                                        <div class="w-100 overflow-hidden">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong class="text-dark small text-truncate" style="max-width: 170px;"><?= htmlspecialchars($n['title']) ?></strong>
                                                <span class="text-muted" style="font-size: 10px;"><?= date('M d', strtotime($n['created_at'])) ?></span>
                                            </div>
                                            <p class="text-secondary small mb-0 text-truncate"><?= htmlspecialchars($n['message']) ?></p>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="p-2 text-center border-top bg-light rounded-bottom-4">
                        <?php
                            $notifLink = match($userRole) {
                                'admin'   => BASE_URL . 'admin/notifications.php',
                                'faculty' => BASE_URL . 'faculty/notifications.php',
                                default   => BASE_URL . 'student/notification.php'
                            };
                        ?>
                        <a href="<?= $notifLink ?>" class="small text-primary fw-semibold text-decoration-none">View All Notifications</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown ms-2">
                <button class="btn profile-pill-trigger d-flex align-items-center gap-2" 
                        type="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="Avatar" class="rounded-circle object-fit-cover" width="32" height="32">
                    <span class="d-none d-md-inline-block fw-semibold text-dark small me-1"><?= htmlspecialchars($fullName) ?></span>
                    <i class="bi bi-chevron-down text-muted small me-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdown-saas-menu mt-2" aria-labelledby="userProfileDropdown" style="min-width: 220px;">
                    <li class="px-3 py-2 border-bottom">
                        <div class="fw-bold text-dark"><?= htmlspecialchars($fullName) ?></div>
                        <div class="small text-muted text-truncate" style="max-width: 180px;"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></div>
                    </li>
                    <?php
                        $profileUrl = match($userRole) {
                            'student' => BASE_URL . 'student/profile.php',
                            'faculty' => BASE_URL . 'faculty/profile.php',
                            'admin'   => BASE_URL . 'admin/profile.php',
                            default   => '#'
                        };
                        $settingsUrl = match($userRole) {
                            'student' => BASE_URL . 'student/settings.php',
                            'admin'   => BASE_URL . 'admin/settings.php',
                            default   => null
                        };
                    ?>
                    <li><a class="dropdown-item py-2" href="<?= $profileUrl ?>"><i class="bi bi-person-circle me-2 text-primary"></i> View Profile</a></li>
                    <?php if ($settingsUrl): ?>
                        <li><a class="dropdown-item py-2" href="<?= $settingsUrl ?>"><i class="bi bi-gear me-2 text-secondary"></i> Settings</a></li>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 text-danger fw-semibold" href="<?= BASE_URL ?>logout.php"><i class="bi bi-box-arrow-right me-2"></i> Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
function markAllNotificationsRead() {
    fetch('<?= BASE_URL ?>api/mark_notifications_read.php', {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById('notifBadge');
            if (badge) badge.style.display = 'none';

            const btn = document.getElementById('markAllReadBtn');
            if (btn) btn.style.display = 'none';

            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread', 'bg-primary-subtle', 'bg-opacity-10');
                item.classList.add('read');
            });

            const countHeader = document.getElementById('unreadCountNum');
            if (countHeader) countHeader.textContent = '0';
        }
    });
}

// ── Global Dashboard & Sidebar Search Engine ──
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('globalHeaderSearch');
    const resultsContainer = document.getElementById('globalSearchResults');
    if (!searchInput || !resultsContainer) return;

    const baseUrl = '<?= BASE_URL ?>';

    const userRole = '<?= $_SESSION['user_role'] ?? 'student' ?>';
    const roleDir = (userRole === 'admin') ? 'admin' : ((userRole === 'faculty') ? 'faculty' : 'student');

    // 1. Full Navigation & Sidebar Modules Index
    const modulesIndex = [
        { title: 'Dashboard', desc: 'Overview, skill scores, streak & activity', url: baseUrl + roleDir + '/dashboard.php', icon: 'fa-gauge-high', category: 'Sidebar Modules' },
        { title: 'My Profile', desc: 'Personal details, email, department & password', url: baseUrl + roleDir + '/profile.php#profile-information', icon: 'fa-user-circle', category: 'Sidebar Modules' },
        { title: 'Profile', desc: 'Personal details, email, department & password', url: baseUrl + roleDir + '/profile.php#profile-information', icon: 'fa-user-circle', category: 'Sidebar Modules' },
        { title: 'Assessments', desc: 'Take skill tests, view active & completed quizzes', url: baseUrl + (userRole === 'student' ? 'student/assessments.php' : (userRole === 'faculty' ? 'faculty/assessments.php' : 'admin/assessments.php')), icon: 'fa-clipboard-check', category: 'Sidebar Modules' },
        { title: 'Completed Assessments', desc: 'View score history and passed quizzes', url: baseUrl + 'student/assessments.php#completed-assessments', icon: 'fa-clipboard-check', category: 'Sidebar Modules' },
        { title: 'Pending Assessments', desc: 'Active quizzes and skill wizard', url: baseUrl + 'student/assessments.php#pending-assessments', icon: 'fa-clipboard-list', category: 'Sidebar Modules' },
        { title: 'Assessment History', desc: 'Historical quiz attempts and scores', url: baseUrl + 'student/assessments.php#completed-assessments', icon: 'fa-history', category: 'Sidebar Modules' },
        { title: 'Notifications', desc: 'Alerts, announcements & activity updates', url: baseUrl + (userRole === 'admin' ? 'admin/notifications.php' : 'student/notification.php#notifications-section'), icon: 'fa-bell', category: 'Sidebar Modules' },
        { title: 'Skill Gap Analysis', desc: 'Radar charts, target skill gaps & priorities', url: baseUrl + (userRole === 'faculty' ? 'faculty/skill-gap.php' : 'student/skill-gap.php'), icon: 'fa-magnifying-glass-chart', category: 'Sidebar Modules' },
        { title: 'Courses & Recommendations', desc: 'Tailored learning courses & progress tracking', url: baseUrl + (userRole === 'admin' ? 'admin/courses.php' : 'student/recommendations.php#recommended-courses'), icon: 'fa-graduation-cap', category: 'Sidebar Modules' },
        { title: 'Courses', desc: 'Tailored learning courses catalog', url: baseUrl + (userRole === 'admin' ? 'admin/courses.php' : 'student/recommendations.php#recommended-courses'), icon: 'fa-book-open', category: 'Sidebar Modules' },
        { title: 'Skill Roadmap', desc: 'Step-by-step career skill pathway', url: baseUrl + 'student/roadmap.php', icon: 'fa-road', category: 'Sidebar Modules' },
        { title: 'Learning Progress', desc: 'Completed courses, analytics & leaderboard', url: baseUrl + 'student/progress.php#skill-progress', icon: 'fa-chart-line', category: 'Sidebar Modules' },
        { title: 'Skill Progress', desc: 'Real-time skill levels & progress tracking', url: baseUrl + 'student/progress.php#skill-progress', icon: 'fa-chart-line', category: 'Sidebar Modules' },
        { title: 'Achievement', desc: 'View badges, awards, and completed goals', url: baseUrl + 'student/dashboard.php#achievements-section', icon: 'fa-trophy', category: 'Sidebar Modules' },
        { title: 'Feedback', desc: 'Submit system feedback & feature requests', url: baseUrl + (userRole === 'faculty' ? 'faculty/feedback.php' : 'student/feedback.php#feedback-section'), icon: 'fa-comments', category: 'Sidebar Modules' },
        { title: 'Reports', desc: 'Institutional analytics & PDF/CSV exports', url: baseUrl + 'admin/reports.php', icon: 'fa-file-earmark-pdf', category: 'Sidebar Modules' },
        { title: 'Faculty Dashboard', desc: 'Faculty management overview and metrics', url: baseUrl + 'faculty/dashboard.php', icon: 'fa-chalkboard-user', category: 'Sidebar Modules' },
        { title: 'Admin Dashboard', desc: 'System administrator control center', url: baseUrl + 'admin/dashboard.php', icon: 'fa-shield-lock', category: 'Sidebar Modules' },
        { title: 'About Us', desc: 'Platform mission, technology stack & details', url: baseUrl + 'about.php', icon: 'fa-circle-info', category: 'Sidebar Modules' },
        { title: 'Help & Support', desc: 'Searchable FAQs, guides & documentation', url: baseUrl + (userRole === 'faculty' ? 'faculty/help.php' : (userRole === 'admin' ? 'help.php' : 'student/help.php')), icon: 'fa-life-ring', category: 'Sidebar Modules' },
        { title: 'Settings', desc: 'Account preferences & notification settings', url: baseUrl + (userRole === 'admin' ? 'admin/settings.php' : 'student/settings.php#change-password'), icon: 'fa-gear', category: 'Sidebar Modules' },
        { title: 'Privacy Policy', desc: 'Data security, protection & user rights', url: baseUrl + 'privacy-policy.php', icon: 'fa-shield-lock', category: 'Sidebar Modules' },
        { title: 'Terms of Service', desc: 'Platform terms, rules & acceptable use', url: baseUrl + 'terms-of-service.php', icon: 'fa-file-text', category: 'Sidebar Modules' }
    ];

    // 2. Curated Feature Search Items
    function getPageDynamicItems() {
        return [];
    }

    // 3. Search Engine Filter & UI Rendering
    function performSearch(query) {
        const term = query.trim().toLowerCase();
        if (!term) {
            resultsContainer.classList.remove('active');
            resultsContainer.innerHTML = '';
            return;
        }

        const dynamicItems = getPageDynamicItems();
        const allItems = [...modulesIndex, ...dynamicItems];
        const matches = [];
        const seen = new Set();

        allItems.forEach(item => {
            const titleMatch = item.title.toLowerCase().includes(term);
            const descMatch = item.desc ? item.desc.toLowerCase().includes(term) : false;
            if ((titleMatch || descMatch) && !seen.has(item.title.toLowerCase())) {
                seen.add(item.title.toLowerCase());
                matches.push(item);
            }
        });

        if (matches.length === 0) {
            resultsContainer.innerHTML = '<div class="search-no-results"><i class="bi bi-search me-2"></i>No matching results found.</div>';
        } else {
            let html = '';
            let currentCat = '';
            matches.slice(0, 8).forEach((item, index) => {
                if (item.category !== currentCat) {
                    currentCat = item.category;
                    html += `<div class="search-category-header">${currentCat}</div>`;
                }
                const iconClass = item.icon.startsWith('fa-') ? item.icon : ('fa-solid ' + item.icon);
                html += `
                    <a href="${item.url}" class="search-result-item" data-index="${index}">
                        <div class="search-result-icon"><i class="${iconClass}"></i></div>
                        <div class="overflow-hidden">
                            <div class="text-truncate fw-semibold">${item.title}</div>
                            <div class="search-result-meta text-truncate">${item.desc}</div>
                        </div>
                    </a>
                `;
            });
            resultsContainer.innerHTML = html;

            resultsContainer.querySelectorAll('.search-result-item').forEach((el, idx) => {
                const matchItem = matches[idx];
                el.addEventListener('click', function(e) {
                    if (matchItem && matchItem.action) {
                        e.preventDefault();
                        matchItem.action();
                        resultsContainer.classList.remove('active');
                        searchInput.value = '';
                    }
                });
            });
        }

        resultsContainer.classList.add('active');
    }

    searchInput.addEventListener('input', function() {
        performSearch(this.value);
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length > 0) {
            performSearch(this.value);
        }
    });

    // 4. Shortcut Ctrl+K / Cmd+K
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        } else if (e.key === 'Escape') {
            resultsContainer.classList.remove('active');
        }
    });

    // 5. Click Outside Handler
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.classList.remove('active');
        }
    });
});
</script>
