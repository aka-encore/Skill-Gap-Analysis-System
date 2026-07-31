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
$avatar = $_SESSION['avatar'] ?? '';
$avatarUrl = resolve_avatar_url($avatar, $userRole);
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
                            <?php foreach ($notifications as $n): 
                                $isAnnouncement = (($n['type'] ?? '') === 'announcement');
                                $notifUrl = $isAnnouncement ? htmlspecialchars($n['link'] ?? '#') : (BASE_URL . 'api/notifications_action.php?action=open&id=' . $n['id']);
                            ?>
                                <a href="<?= $notifUrl ?>" class="dropdown-item p-3 border-bottom notification-item <?= $n['is_read'] ? 'read' : 'unread bg-primary-subtle bg-opacity-10' ?>">
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

<?php
// ── Global Search: Step 1. Build Complete Master Searchable Page List First ──
$allSearchablePages = [
    // --- ADMIN PAGES ---
    [
        'title'    => 'Dashboard',
        'desc'     => 'System administrator control center & metrics',
        'url'      => BASE_URL . 'admin/dashboard.php',
        'icon'     => 'fa-gauge-high',
        'category' => 'Navigation & Modules',
        'keywords' => 'dashboard admin main control overview metrics',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Students',
        'desc'     => 'Manage student accounts, profiles & data',
        'url'      => BASE_URL . 'admin/students.php',
        'icon'     => 'fa-user-graduate',
        'category' => 'User Management',
        'keywords' => 'students student management users accounts profiles',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Faculty',
        'desc'     => 'Manage faculty accounts & assignments',
        'url'      => BASE_URL . 'admin/faculty.php',
        'icon'     => 'fa-chalkboard-user',
        'category' => 'User Management',
        'keywords' => 'faculty teachers instructors management accounts',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Faculty Applications',
        'desc'     => 'Review & approve pending faculty registrations',
        'url'      => BASE_URL . 'admin/faculty-applications.php',
        'icon'     => 'fa-user-clock',
        'category' => 'User Management',
        'keywords' => 'faculty applications approval registration pending requests',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Notifications',
        'desc'     => 'System alerts, broadcast logs & activity updates',
        'url'      => BASE_URL . 'admin/notifications.php',
        'icon'     => 'fa-bell',
        'category' => 'Communication',
        'keywords' => 'notifications alerts system updates broadcast activity',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Announcements',
        'desc'     => 'Create & manage global system announcements',
        'url'      => BASE_URL . 'admin/announcements.php',
        'icon'     => 'fa-bullhorn',
        'category' => 'Communication',
        'keywords' => 'announcements news broadcasts global notices management',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Courses',
        'desc'     => 'Manage courses, subjects & curriculum catalog',
        'url'      => BASE_URL . 'admin/courses.php',
        'icon'     => 'fa-book',
        'category' => 'Curriculum & Content',
        'keywords' => 'courses course catalog subjects curriculum management',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Skills',
        'desc'     => 'Define skill tags, categories & competencies',
        'url'      => BASE_URL . 'admin/skills.php',
        'icon'     => 'fa-lightbulb',
        'category' => 'Curriculum & Content',
        'keywords' => 'skills skill tags categories competencies management',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Assessments',
        'desc'     => 'Overview of all skill tests & quiz configurations',
        'url'      => BASE_URL . 'admin/assessments.php',
        'icon'     => 'fa-clipboard-list',
        'category' => 'Assessments',
        'keywords' => 'assessments tests quizzes overview configurations',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Analytics',
        'desc'     => 'Institutional skill analytics & cohort performance',
        'url'      => BASE_URL . 'admin/analytics.php',
        'icon'     => 'fa-chart-line',
        'category' => 'Reports & System',
        'keywords' => 'analytics metrics institutional performance cohort',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Reports',
        'desc'     => 'Generate system reports, PDF & CSV exports',
        'url'      => BASE_URL . 'admin/reports.php',
        'icon'     => 'fa-file-earmark-pdf',
        'category' => 'Reports & System',
        'keywords' => 'reports pdf csv export system reports analytics',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Settings',
        'desc'     => 'System configuration & admin preferences',
        'url'      => BASE_URL . 'admin/settings.php',
        'icon'     => 'fa-gear',
        'category' => 'Reports & System',
        'keywords' => 'settings configuration preferences admin settings',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Profile',
        'desc'     => 'Administrator profile & account details',
        'url'      => BASE_URL . 'admin/profile.php',
        'icon'     => 'fa-user-circle',
        'category' => 'Account',
        'keywords' => 'profile account details administrator admin profile',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Activity Logs',
        'desc'     => 'Audit logs & system action history',
        'url'      => BASE_URL . 'admin/activity-logs.php',
        'icon'     => 'fa-clock-history',
        'category' => 'Reports & System',
        'keywords' => 'activity logs audit history user actions',
        'roles'    => ['admin']
    ],
    [
        'title'    => 'Database Backup',
        'desc'     => 'Export & backup database snapshots',
        'url'      => BASE_URL . 'admin/backup.php',
        'icon'     => 'fa-database',
        'category' => 'Reports & System',
        'keywords' => 'database backup export db snapshots',
        'roles'    => ['admin']
    ],

    // --- FACULTY PAGES ---
    [
        'title'    => 'Dashboard',
        'desc'     => 'Faculty overview, class metrics & quiz stats',
        'url'      => BASE_URL . 'faculty/dashboard.php',
        'icon'     => 'fa-gauge-high',
        'category' => 'Navigation & Modules',
        'keywords' => 'dashboard faculty main overview class metrics',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Students',
        'desc'     => 'View department students, progress & test scores',
        'url'      => BASE_URL . 'faculty/students.php',
        'icon'     => 'fa-users',
        'category' => 'Student Management',
        'keywords' => 'students class enrollees progress test scores',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Courses',
        'desc'     => 'Course assessments, syllabus & skill mapping',
        'url'      => BASE_URL . 'faculty/assessments.php',
        'icon'     => 'fa-book-open',
        'category' => 'Curriculum & Quizzes',
        'keywords' => 'courses syllabus subjects curriculum mapping',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Lessons',
        'desc'     => 'Question bank & quiz lesson materials',
        'url'      => BASE_URL . 'faculty/question-bank.php',
        'icon'     => 'fa-layer-group',
        'category' => 'Curriculum & Quizzes',
        'keywords' => 'lessons study materials questions topic modules',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Assessments',
        'desc'     => 'Create, edit & manage student skill quizzes',
        'url'      => BASE_URL . 'faculty/assessments.php',
        'icon'     => 'fa-clipboard-check',
        'category' => 'Curriculum & Quizzes',
        'keywords' => 'assessments tests quizzes create manage edit',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Create Assessment',
        'desc'     => 'Draft new quiz assessments & questions',
        'url'      => BASE_URL . 'faculty/assessment-create.php',
        'icon'     => 'fa-plus-circle',
        'category' => 'Curriculum & Quizzes',
        'keywords' => 'create assessment add quiz new test',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Evaluate Submissions',
        'desc'     => 'Review & evaluate student quiz attempts',
        'url'      => BASE_URL . 'faculty/evaluate.php',
        'icon'     => 'fa-check-double',
        'category' => 'Curriculum & Quizzes',
        'keywords' => 'evaluate submissions review quiz attempts grading',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Question Bank',
        'desc'     => 'Manage question repository by topic & skill',
        'url'      => BASE_URL . 'faculty/question-bank.php',
        'icon'     => 'fa-question-circle',
        'category' => 'Curriculum & Quizzes',
        'keywords' => 'question bank repository mcq questions',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Skill Analytics',
        'desc'     => 'Department skill gap breakdown & cohort analytics',
        'url'      => BASE_URL . 'faculty/skill-gap.php',
        'icon'     => 'fa-chart-pie',
        'category' => 'Analytics',
        'keywords' => 'skill analytics department gap breakdown cohort',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Notifications',
        'desc'     => 'Faculty alerts, system notifications & updates',
        'url'      => BASE_URL . 'faculty/notifications.php',
        'icon'     => 'fa-bell',
        'category' => 'Communication',
        'keywords' => 'notifications alerts class updates faculty notifications',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Announcements',
        'desc'     => 'Post class announcements & student notices',
        'url'      => BASE_URL . 'faculty/announcements.php',
        'icon'     => 'fa-bullhorn',
        'category' => 'Communication',
        'keywords' => 'announcements class notices post announcements',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Feedback',
        'desc'     => 'Submit feedback & system improvement suggestions',
        'url'      => BASE_URL . 'faculty/feedback.php',
        'icon'     => 'fa-comments',
        'category' => 'Communication',
        'keywords' => 'feedback suggestions comments support',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Profile',
        'desc'     => 'Faculty profile, bio & contact information',
        'url'      => BASE_URL . 'faculty/profile.php',
        'icon'     => 'fa-user-circle',
        'category' => 'Account',
        'keywords' => 'profile account details faculty profile bio',
        'roles'    => ['faculty']
    ],
    [
        'title'    => 'Settings',
        'desc'     => 'Account preferences & security settings',
        'url'      => BASE_URL . 'faculty/profile.php#settings',
        'icon'     => 'fa-gear',
        'category' => 'Account',
        'keywords' => 'settings preferences account faculty settings',
        'roles'    => ['faculty']
    ],

    // --- STUDENT PAGES ---
    [
        'title'    => 'Dashboard',
        'desc'     => 'Student overview, skill score, streak & activity',
        'url'      => BASE_URL . 'student/dashboard.php',
        'icon'     => 'fa-gauge-high',
        'category' => 'Navigation & Modules',
        'keywords' => 'dashboard student main overview score streak',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Profile',
        'desc'     => 'Personal profile, email, department & avatar',
        'url'      => BASE_URL . 'student/profile.php',
        'icon'     => 'fa-user-circle',
        'category' => 'Account',
        'keywords' => 'profile my profile account details student profile',
        'roles'    => ['student']
    ],
    [
        'title'    => 'My Profile',
        'desc'     => 'Personal profile, email, department & avatar',
        'url'      => BASE_URL . 'student/profile.php',
        'icon'     => 'fa-user-circle',
        'category' => 'Account',
        'keywords' => 'my profile account details student profile',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Courses',
        'desc'     => 'Course catalog & recommended learning',
        'url'      => BASE_URL . 'student/courses.php',
        'icon'     => 'fa-graduation-cap',
        'category' => 'Learning & Skill Development',
        'keywords' => 'courses course catalog recommended learning',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Enrolled Courses',
        'desc'     => 'View active courses currently enrolled',
        'url'      => BASE_URL . 'student/courses.php#enrolled-courses',
        'icon'     => 'fa-book-open',
        'category' => 'Learning & Skill Development',
        'keywords' => 'enrolled courses active learning enrolled',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Completed Courses',
        'desc'     => 'Courses successfully completed & badges',
        'url'      => BASE_URL . 'student/courses.php#completed-courses',
        'icon'     => 'fa-circle-check',
        'category' => 'Learning & Skill Development',
        'keywords' => 'completed courses finished passed badges',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Lessons',
        'desc'     => 'Interactive lessons, topics & study materials',
        'url'      => BASE_URL . 'student/courses.php',
        'icon'     => 'fa-chalkboard-user',
        'category' => 'Learning & Skill Development',
        'keywords' => 'lessons topics study materials course lessons',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Assessments',
        'desc'     => 'Take skill tests, pending & completed quizzes',
        'url'      => BASE_URL . 'student/assessments.php',
        'icon'     => 'fa-clipboard-check',
        'category' => 'Assessments',
        'keywords' => 'assessments tests quizzes take tests pending',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Completed Assessments',
        'desc'     => 'Score history, passed quizzes & breakdown',
        'url'      => BASE_URL . 'student/assessments.php#completed-assessments',
        'icon'     => 'fa-history',
        'category' => 'Assessments',
        'keywords' => 'completed assessments test history scores passed',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Progress',
        'desc'     => 'Skill growth progress & cohort leaderboard',
        'url'      => BASE_URL . 'student/progress.php',
        'icon'     => 'fa-chart-line',
        'category' => 'Analytics & Pathways',
        'keywords' => 'progress learning progress leaderboard growth',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Skill Gap Analysis',
        'desc'     => 'Radar breakdown, target skill gaps & priorities',
        'url'      => BASE_URL . 'student/skill-gap.php',
        'icon'     => 'fa-magnifying-glass-chart',
        'category' => 'Analytics & Pathways',
        'keywords' => 'skill gap analysis radar priorities targets',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Roadmap',
        'desc'     => 'Step-by-step career pathway & skill milestones',
        'url'      => BASE_URL . 'student/roadmap.php',
        'icon'     => 'fa-road',
        'category' => 'Analytics & Pathways',
        'keywords' => 'roadmap career pathway milestones learning plan',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Skill Roadmap',
        'desc'     => 'Step-by-step career pathway & skill milestones',
        'url'      => BASE_URL . 'student/roadmap.php',
        'icon'     => 'fa-road',
        'category' => 'Analytics & Pathways',
        'keywords' => 'skill roadmap pathway milestones career',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Notifications',
        'desc'     => 'Personal alerts & system notifications',
        'url'      => BASE_URL . 'student/notification.php',
        'icon'     => 'fa-bell',
        'category' => 'Communication',
        'keywords' => 'notifications alerts personal updates',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Feedback',
        'desc'     => 'Submit feedback & platform suggestions',
        'url'      => BASE_URL . 'student/feedback.php',
        'icon'     => 'fa-comments',
        'category' => 'Communication',
        'keywords' => 'feedback suggestions comments support',
        'roles'    => ['student']
    ],
    [
        'title'    => 'Settings',
        'desc'     => 'Change password & notification preferences',
        'url'      => BASE_URL . 'student/settings.php',
        'icon'     => 'fa-gear',
        'category' => 'Account',
        'keywords' => 'settings password preferences student settings',
        'roles'    => ['student']
    ],

    // --- SHARED SYSTEM PAGES (ALL ROLES) ---
    [
        'title'    => 'Help & Support',
        'desc'     => 'Searchable FAQs, guides & platform help',
        'url'      => BASE_URL . (($_SESSION['user_role'] ?? 'student') === 'faculty' ? 'faculty/help.php' : (($_SESSION['user_role'] ?? 'student') === 'admin' ? 'help.php' : 'student/help.php')),
        'icon'     => 'fa-life-ring',
        'category' => 'Support',
        'keywords' => 'help support documentation guides faqs',
        'roles'    => ['admin', 'faculty', 'student']
    ],
    [
        'title'    => 'About Us',
        'desc'     => 'Platform mission & technology details',
        'url'      => BASE_URL . 'about.php',
        'icon'     => 'fa-circle-info',
        'category' => 'Information',
        'keywords' => 'about us mission info platform details',
        'roles'    => ['admin', 'faculty', 'student']
    ],
    [
        'title'    => 'Privacy Policy',
        'desc'     => 'Data security & privacy rights',
        'url'      => BASE_URL . 'privacy-policy.php',
        'icon'     => 'fa-shield-lock',
        'category' => 'Information',
        'keywords' => 'privacy policy data security protection rights',
        'roles'    => ['admin', 'faculty', 'student']
    ],
    [
        'title'    => 'Terms of Service',
        'desc'     => 'Platform terms & acceptable use',
        'url'      => BASE_URL . 'terms-of-service.php',
        'icon'     => 'fa-file-text',
        'category' => 'Information',
        'keywords' => 'terms of service rules acceptable use legal',
        'roles'    => ['admin', 'faculty', 'student']
    ],
    [
        'title'    => 'Logout',
        'desc'     => 'Sign out of your SkillBridge account',
        'url'      => BASE_URL . 'logout.php',
        'icon'     => 'fa-box-arrow-right',
        'category' => 'Account',
        'keywords' => 'logout sign out exit logoff',
        'roles'    => ['admin', 'faculty', 'student']
    ]
];

// ── Global Search: Step 2. Detect Logged-In User Role ──
$detectedUserRole = strtolower(trim($_SESSION['user_role'] ?? $_SESSION['role'] ?? 'student'));

// ── Global Search: Step 3. Filter Page List According to Role (Post-Generation) ──
$authorizedPages = array_values(array_filter($allSearchablePages, function($page) use ($detectedUserRole) {
    return in_array($detectedUserRole, $page['roles'], true);
}));

$totalBeforeFilter = count($allSearchablePages);
$totalAfterFilter  = count($authorizedPages);
?>

<script>
function markAllNotificationsRead() {
    fetch('<?= BASE_URL ?>api/mark_notifications_read.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'csrf_token=<?= $_SESSION['csrf_token'] ?? "" ?>'
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
    const userRole = '<?= $detectedUserRole ?>';
    const totalBefore = <?= $totalBeforeFilter ?>;
    const totalAfter = <?= $totalAfterFilter ?>;
    const modulesIndex = <?= json_encode($authorizedPages) ?>;
    let selectedIndex = -1;

    // Inject search highlight and animations stylesheet
    const searchStyle = document.createElement('style');
    searchStyle.innerHTML = `
        .search-fade-out {
            opacity: 0.15 !important;
            transition: opacity 0.25s ease-in-out;
        }
        .search-target-pulse {
            animation: searchPulse 2.5s ease-in-out;
        }
        @keyframes searchPulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(38, 101, 140, 0.6); }
            30% { transform: scale(1.02); box-shadow: 0 0 20px 8px rgba(38, 101, 140, 0.4); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(38, 101, 140, 0); }
        }
        .search-text-highlight {
            background-color: #fef08a !important;
            color: #1e293b !important;
            border-radius: 2px !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
        }
    `;
    document.head.appendChild(searchStyle);

    function applyTextHighlight(element, query) {
        if (!element) return;
        removeTextHighlight(element);
        
        const term = query.trim().toLowerCase();
        if (!term) return;
        
        const walk = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, null, false);
        let node;
        const matches = [];
        while (node = walk.nextNode()) {
            const parentTagName = node.parentNode.tagName.toUpperCase();
            if (parentTagName === 'SCRIPT' || parentTagName === 'STYLE' || parentTagName === 'NOSCRIPT' || parentTagName === 'I' || node.parentNode.classList.contains('header-search-results')) {
                continue;
            }
            
            const val = node.nodeValue;
            const idx = val.toLowerCase().indexOf(term);
            if (idx >= 0) {
                matches.push({ node: node, value: val });
            }
        }
        
        matches.forEach(m => {
            const node = m.node;
            const val = m.value;
            const frag = document.createDocumentFragment();
            let lastIdx = 0;
            
            let idx = val.toLowerCase().indexOf(term);
            while (idx >= 0) {
                if (idx > lastIdx) {
                    frag.appendChild(document.createTextNode(val.substring(lastIdx, idx)));
                }
                
                const span = document.createElement('span');
                span.className = 'search-text-highlight';
                span.appendChild(document.createTextNode(val.substring(idx, idx + term.length)));
                frag.appendChild(span);
                
                lastIdx = idx + term.length;
                idx = val.toLowerCase().indexOf(term, lastIdx);
            }
            
            if (lastIdx < val.length) {
                frag.appendChild(document.createTextNode(val.substring(lastIdx)));
            }
            
            if (node.parentNode) {
                node.parentNode.replaceChild(frag, node);
            }
        });
    }

    function removeTextHighlight(element) {
        if (!element) return;
        element.querySelectorAll('.search-text-highlight').forEach(span => {
            const parent = span.parentNode;
            if (parent) {
                parent.replaceChild(document.createTextNode(span.textContent), span);
                parent.normalize();
            }
        });
    }

    function filterCurrentPage(term) {
        const q = term.trim().toLowerCase();
        
        const selectors = [
            '.kpi-card-premium',
            '.saas-stat-card',
            '.milestone-card',
            '.clickable-skill-item',
            '.course-card',
            '.saas-card',
            '.card',
            '.stat-card',
            'tbody tr',
            '.list-group-item',
            '.notification-item',
            '.activity-item',
            '.feedback-item',
            '.profile-info-row',
            '.q-bank-item',
            '.question-card'
        ];
        
        let targets = [];
        selectors.forEach(sel => {
            document.querySelectorAll(sel).forEach(el => {
                if (!el.closest('#globalSearchResults') && !targets.includes(el)) {
                    targets.push(el);
                }
            });
        });
        
        if (!q) {
            targets.forEach(el => {
                el.style.display = '';
                el.classList.remove('search-fade-out');
                removeTextHighlight(el);
            });
            
            document.querySelectorAll('section, .section-wrapper, .row, .col-md-6, .col-lg-4, .col-xl-3, .col-md-4, .col-md-3, .col-lg-3').forEach(parent => {
                parent.style.display = '';
            });
            
            const banner = document.getElementById('pageSearchEmptyState');
            if (banner) banner.remove();
            return;
        }
        
        let matchCount = 0;
        targets.forEach(el => {
            const text = el.innerText.toLowerCase();
            const isMatch = text.includes(q);
            
            if (isMatch) {
                el.style.display = '';
                el.classList.remove('search-fade-out');
                matchCount++;
                applyTextHighlight(el, q);
            } else {
                el.style.display = 'none';
                el.classList.add('search-fade-out');
                removeTextHighlight(el);
            }
        });
        
        // Hide parent layout cols if empty
        document.querySelectorAll('.col, .col-md-6, .col-lg-4, .col-xl-3, .col-12, .col-md-4, .col-md-3, .col-lg-3').forEach(col => {
            const innerTargets = Array.from(col.querySelectorAll(selectors.join(','))).filter(t => !t.closest('#globalSearchResults'));
            if (innerTargets.length > 0) {
                const visibleCount = innerTargets.filter(t => t.style.display !== 'none').length;
                if (visibleCount === 0) {
                    col.style.display = 'none';
                } else {
                    col.style.display = '';
                }
            }
        });

        // Hide parent sections/rows if empty
        document.querySelectorAll('section, .section-wrapper, .row, .mb-4').forEach(parent => {
            if (parent.classList.contains('container-fluid') || parent.classList.contains('wrapper') || parent.id === 'previewDifficultyStars') return;
            const innerTargets = Array.from(parent.querySelectorAll(selectors.join(','))).filter(t => !t.closest('#globalSearchResults'));
            if (innerTargets.length > 0) {
                const visibleCount = innerTargets.filter(t => t.style.display !== 'none').length;
                if (visibleCount === 0) {
                    parent.style.display = 'none';
                } else {
                    parent.style.display = '';
                }
            }
        });
        
        let banner = document.getElementById('pageSearchEmptyState');
        if (matchCount === 0) {
            if (!banner) {
                banner = document.createElement('div');
                banner.id = 'pageSearchEmptyState';
                banner.className = 'card border-0 shadow-sm rounded-4 p-5 text-center my-4 bg-white';
                banner.innerHTML = `
                    <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-search text-warning fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">No matches found on this page</h4>
                    <p class="text-muted small mb-0">Try typing another keyword, or explore global results in the search dropdown above.</p>
                `;
                const container = document.querySelector('main, .container-fluid, .content-wrapper, .content') || document.body;
                container.appendChild(banner);
            }
        } else {
            if (banner) banner.remove();
        }
    }

    function getPageMatches(query) {
        const q = query.trim().toLowerCase();
        if (!q) return [];

        const matches = [];
        const selectors = [
            { sel: '.kpi-card-premium', type: 'KPI Card' },
            { sel: '.saas-stat-card', type: 'Statistics Card' },
            { sel: '.milestone-card', type: 'Roadmap Milestone' },
            { sel: '.clickable-skill-item', type: 'Skill Gap Item' },
            { sel: '.course-card', type: 'Course Card' },
            { sel: '.saas-card', type: 'Section Card' },
            { sel: '.card', type: 'Page Card' },
            { sel: '.stat-card', type: 'Statistics Card' },
            { sel: 'tbody tr', type: 'Table Row' },
            { sel: '.list-group-item', type: 'List Item' },
            { sel: '.notification-item', type: 'Notification' },
            { sel: '.activity-item', type: 'Activity Item' },
            { sel: '.feedback-item', type: 'Feedback' },
            { sel: '.profile-info-row', type: 'Profile Details' },
            { sel: '.question-card', type: 'Question Card' }
        ];

        let matchIdx = 0;
        selectors.forEach(item => {
            document.querySelectorAll(item.sel).forEach((el) => {
                if (el.closest('#globalSearchResults')) return;
                
                const text = el.innerText.trim();
                const lowerText = text.toLowerCase();
                
                if (lowerText.includes(q)) {
                    let heading = el.querySelector('h1, h2, h3, h4, h5, h6, .fw-bold, .fw-semibold, td:first-child');
                    let headingText = heading ? heading.innerText.trim() : '';
                    if (!headingText || headingText.length > 50) {
                        headingText = text.split('\n')[0].substring(0, 40) + '...';
                    }
                    
                    let snippet = text.replace(/\n/g, ' ').substring(0, 65) + '...';

                    let targetId = el.getAttribute('id');
                    if (!targetId) {
                        targetId = 'search-match-target-' + matchIdx++;
                        el.setAttribute('id', targetId);
                    }

                    matches.push({
                        title: headingText,
                        desc: item.type + ' • ' + snippet,
                        url: '#' + targetId,
                        icon: 'fa-eye',
                        category: 'Matches on Current Page'
                    });
                }
            });
        });

        return matches;
    }

    function renderResults(matches) {
        if (matches.length === 0) {
            resultsContainer.innerHTML = '<div class="search-no-results"><i class="bi bi-search me-2"></i>No matching results found.</div>';
        } else {
            let html = '';
            let currentCat = '';
            matches.slice(0, 15).forEach((item, index) => {
                if (item.category !== currentCat) {
                    currentCat = item.category;
                    html += `<div class="search-category-header">${currentCat}</div>`;
                }
                const rawIcon = item.icon || 'fa-magnifying-glass';
                const iconClass = rawIcon.startsWith('fa-') ? rawIcon : ('fa-solid ' + rawIcon);
                const descText = item.desc || item.subtitle || '';
                html += `
                    <a href="${item.url}" class="search-result-item" data-index="${index}">
                        <div class="search-result-icon"><i class="${iconClass}"></i></div>
                        <div class="overflow-hidden">
                            <div class="text-truncate fw-semibold">${item.title}</div>
                            <div class="search-result-meta text-truncate">${descText}</div>
                        </div>
                    </a>
                `;
            });
            resultsContainer.innerHTML = html;
        }
        resultsContainer.classList.add('active');
    }

    function performSearch(query) {
        const term = query.trim().toLowerCase();
        selectedIndex = -1;

        // Step A: Client-side DOM filtering on current page
        filterCurrentPage(term);

        if (!term) {
            resultsContainer.classList.remove('active');
            resultsContainer.innerHTML = '';
            return;
        }

        const matches = [];
        const seen = new Set();

        // Step B: Collect matches on current page
        const pageMatches = getPageMatches(term);
        pageMatches.forEach(item => {
            const uniqueKey = item.title.toLowerCase() + '_' + item.url;
            if (!seen.has(uniqueKey)) {
                seen.add(uniqueKey);
                matches.push(item);
            }
        });

        // Step C: Search authorized menu links
        modulesIndex.forEach(item => {
            const titleMatch   = item.title.toLowerCase().includes(term);
            const descMatch    = item.desc ? item.desc.toLowerCase().includes(term) : false;
            const keywordMatch = item.keywords ? item.keywords.toLowerCase().includes(term) : false;
            const uniqueKey    = item.title.toLowerCase() + '_' + item.url;
            if ((titleMatch || descMatch || keywordMatch) && !seen.has(uniqueKey)) {
                seen.add(uniqueKey);
                matches.push(item);
            }
        });

        renderResults(matches);

        // Fetch dynamic backend database results
        fetch(`${baseUrl}api/search.php?q=${encodeURIComponent(term)}`)
            .then(res => res.ok ? res.json() : [])
            .then(apiResults => {
                if (Array.isArray(apiResults) && apiResults.length > 0) {
                    apiResults.forEach(item => {
                        const uniqueKey = item.title.toLowerCase() + '_' + item.url;
                        if (!seen.has(uniqueKey)) {
                            seen.add(uniqueKey);
                            matches.push(item);
                        }
                    });
                    renderResults(matches);
                }
            })
            .catch(() => {});
    }

    searchInput.addEventListener('input', function() {
        performSearch(this.value);
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length > 0) {
            performSearch(this.value);
        }
    });

    // Results click hijack for smooth scroll anchor matching
    resultsContainer.addEventListener('click', function(e) {
        const item = e.target.closest('.search-result-item');
        if (item) {
            const href = item.getAttribute('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                const targetEl = document.querySelector(href);
                if (targetEl) {
                    resultsContainer.classList.remove('active');
                    searchInput.value = '';
                    filterCurrentPage(''); // Restore visible elements
                    
                    targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    targetEl.classList.remove('search-target-pulse');
                    void targetEl.offsetWidth; // trigger reflow
                    targetEl.classList.add('search-target-pulse');
                    
                    targetEl.style.outline = '2px solid var(--bs-primary, #26658c)';
                    targetEl.style.boxShadow = '0 0 20px rgba(38, 101, 140, 0.4)';
                    setTimeout(() => {
                        targetEl.style.outline = '';
                        targetEl.style.boxShadow = '';
                        targetEl.classList.remove('search-target-pulse');
                    }, 2500);
                }
            }
        }
    });

    // Keyboard navigation (Arrow keys, Enter, Escape, Ctrl+K)
    searchInput.addEventListener('keydown', function(e) {
        const items = resultsContainer.querySelectorAll('.search-result-item');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (items.length > 0) {
                selectedIndex = (selectedIndex + 1) % items.length;
                updateKeyboardHighlight(items);
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (items.length > 0) {
                selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                updateKeyboardHighlight(items);
            }
        } else if (e.key === 'Enter') {
            if (selectedIndex >= 0 && items[selectedIndex]) {
                e.preventDefault();
                items[selectedIndex].click();
            }
        }
    });

    function updateKeyboardHighlight(items) {
        items.forEach((item, idx) => {
            if (idx === selectedIndex) {
                item.style.backgroundColor = 'var(--bg-hover, rgba(99, 102, 241, 0.08))';
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.style.backgroundColor = '';
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        } else if (e.key === 'Escape') {
            resultsContainer.classList.remove('active');
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.classList.remove('active');
        }
    });
});
</script>
