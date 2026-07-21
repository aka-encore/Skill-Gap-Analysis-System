<?php
/**
 * SkillBridge - Dynamic Sidebar Navigation Component
 * Supports Student, Faculty, & Admin with compact icon-only collapsed state & tooltips.
 */
require_once __DIR__ . '/auth.php';

$role = $_SESSION['user_role'] ?? 'student';
$currentScript = basename($_SERVER['PHP_SELF']);
$currentDir = basename(dirname($_SERVER['PHP_SELF']));

if (!function_exists('isActive')) {
    function isActive($page, $dir = ''): string {
        global $currentScript, $currentDir;
        if ($dir && $currentDir !== $dir) return '';
        return $currentScript === $page ? 'active' : '';
    }
}

$sidebarUserName = $_SESSION['full_name'] ?? $_SESSION['user_name'] ?? $_SESSION['username'] ?? 'User';
if (empty(trim($sidebarUserName))) {
    $sidebarUserName = $_SESSION['username'] ?? 'User';
}

$sidebarAvatar = $_SESSION['avatar'] ?? 'default-avatar.png';
$sidebarAvatarUrl = BASE_URL . 'uploads/avatars/' . $sidebarAvatar;
if (!file_exists(__DIR__ . '/../uploads/avatars/' . $sidebarAvatar) || empty($sidebarAvatar)) {
    $sidebarAvatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($sidebarUserName) . '&background=26658C&color=ffffff&bold=true';
}

$sidebarSubTitle = ucfirst($role);
if (!empty($_SESSION['department'])) {
    $sidebarSubTitle .= ' • ' . $_SESSION['department'];
}
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <button class="mobile-menu-toggle sidebar-toggle-btn d-md-none" id="sidebarCloseToggle" onclick="toggleSidebar()"
      style="display:flex;align-items:center;justify-content:center;width:40px;height:40px;background:#F4F9FF;border:1px solid var(--border);border-radius:var(--radius-md);color:var(--text-secondary);cursor:pointer"
      title="Close Sidebar">
      <i class="fa-solid fa-bars"></i>
    </button>
    <a href="<?= BASE_URL ?>" class="d-flex align-items-center gap-2 text-decoration-none" title="SkillBridge Dashboard">
      <div class="nav-brand-icon"><i class="fa-solid fa-brain"></i></div>
      <span class="fw-bold fs-4 text-dark font-heading">Skill<span class="gradient-text">Bridge</span></span>
    </a>
  </div>

  <nav class="sidebar-nav">
    <?php if ($role === 'student'): ?>
      <div class="sidebar-section-label">Main</div>
      <a href="<?= BASE_URL ?>student/dashboard.php" class="sidebar-nav-item <?= isActive('dashboard.php', 'student') ?>" title="Dashboard">
        <div class="nav-icon"><i class="fa-solid fa-gauge-high"></i></div>
        <span>Dashboard</span>
      </a>
      <a href="<?= BASE_URL ?>student/profile.php" class="sidebar-nav-item <?= isActive('profile.php', 'student') ?>" title="My Profile">
        <div class="nav-icon"><i class="fa-solid fa-user-circle"></i></div>
        <span>My Profile</span>
      </a>
      <a href="<?= BASE_URL ?>student/assessments.php" class="sidebar-nav-item <?= isActive('assessments.php', 'student') ?>" title="Assessments">
        <div class="nav-icon"><i class="fa-solid fa-clipboard-check"></i></div>
        <span>Assessments</span>
        <span class="nav-badge">3</span>
      </a>
      <a href="<?= BASE_URL ?>student/notification.php" class="sidebar-nav-item <?= isActive('notification.php', 'student') ?>" title="Notifications">
        <div class="nav-icon"><i class="fa-solid fa-bell"></i></div>
        <span>Notifications</span>
        <span class="nav-badge">4</span>
      </a>

      <div class="sidebar-section-label">Learning</div>
      <a href="<?= BASE_URL ?>student/skill-gap.php" class="sidebar-nav-item <?= isActive('skill-gap.php', 'student') ?>" title="Skill Gap Analysis">
        <div class="nav-icon"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
        <span>Skill Gap</span>
      </a>
      <a href="<?= BASE_URL ?>student/recommendations.php" class="sidebar-nav-item <?= isActive('recommendations.php', 'student') ?>" title="Courses & Recommendations">
        <div class="nav-icon"><i class="fa-solid fa-graduation-cap"></i></div>
        <span>Courses</span>
      </a>
      <a href="<?= BASE_URL ?>student/roadmap.php" class="sidebar-nav-item <?= isActive('roadmap.php', 'student') ?>" title="Skill Roadmap">
        <div class="nav-icon"><i class="fa-solid fa-road"></i></div>
        <span>Roadmap</span>
      </a>
      <a href="<?= BASE_URL ?>student/progress.php" class="sidebar-nav-item <?= isActive('progress.php', 'student') ?>" title="Learning Progress">
        <div class="nav-icon"><i class="fa-solid fa-chart-line"></i></div>
        <span>Progress</span>
      </a>

      <div class="sidebar-section-label">Other</div>
      <a href="<?= BASE_URL ?>student/feedback.php" class="sidebar-nav-item <?= isActive('feedback.php', 'student') ?>" title="Feedback">
        <div class="nav-icon"><i class="fa-solid fa-comments"></i></div>
        <span>Feedback</span>
      </a>
      <a href="<?= BASE_URL ?>about.php" class="sidebar-nav-item <?= isActive('about.php') ?>" title="About Us">
        <div class="nav-icon"><i class="fa-solid fa-circle-info"></i></div>
        <span>About Us</span>
      </a>
      <a href="<?= BASE_URL ?>student/help.php" class="sidebar-nav-item <?= isActive('help.php', 'student') ?>" title="Help & Support">
        <div class="nav-icon"><i class="fa-solid fa-life-ring"></i></div>
        <span>Help</span>
      </a>
      <a href="<?= BASE_URL ?>student/settings.php" class="sidebar-nav-item <?= isActive('settings.php', 'student') ?>" title="Settings">
        <div class="nav-icon"><i class="fa-solid fa-gear"></i></div>
        <span>Settings</span>
      </a>

    <?php elseif ($role === 'faculty'): ?>
      <div class="sidebar-section-label">Main</div>
      <a href="<?= BASE_URL ?>faculty/dashboard.php" class="sidebar-nav-item <?= isActive('dashboard.php', 'faculty') ?>" title="Dashboard">
        <div class="nav-icon"><i class="fa-solid fa-gauge-high"></i></div>
        <span>Dashboard</span>
      </a>
      <a href="<?= BASE_URL ?>faculty/assessments.php" class="sidebar-nav-item <?= isActive('assessments.php', 'faculty') ?>" title="Manage Assessments">
        <div class="nav-icon"><i class="fa-solid fa-clipboard-check"></i></div>
        <span>Assessments</span>
      </a>
      <a href="<?= BASE_URL ?>faculty/question-bank.php" class="sidebar-nav-item <?= isActive('question-bank.php', 'faculty') ?>" title="Question Bank">
        <div class="nav-icon"><i class="fa-solid fa-question-circle"></i></div>
        <span>Question Bank</span>
      </a>
      <a href="<?= BASE_URL ?>faculty/students.php" class="sidebar-nav-item <?= isActive('students.php', 'faculty') ?>" title="Student Analytics">
        <div class="nav-icon"><i class="fa-solid fa-users"></i></div>
        <span>Students</span>
      </a>
      <a href="<?= BASE_URL ?>faculty/skill-gap.php" class="sidebar-nav-item <?= isActive('skill-gap.php', 'faculty') ?>" title="Skill Analytics">
        <div class="nav-icon"><i class="fa-solid fa-chart-pie"></i></div>
        <span>Skill Analytics</span>
      </a>
      <a href="<?= BASE_URL ?>faculty/feedback.php" class="sidebar-nav-item <?= isActive('feedback.php', 'faculty') ?>" title="Feedback">
        <div class="nav-icon"><i class="fa-solid fa-comments"></i></div>
        <span>Feedback</span>
      </a>
      <a href="<?= BASE_URL ?>faculty/help.php" class="sidebar-nav-item <?= isActive('help.php', 'faculty') ?>" title="Faculty Help & Support">
        <div class="nav-icon"><i class="fa-solid fa-life-ring"></i></div>
        <span>Help</span>
      </a>

    <?php elseif ($role === 'admin'): ?>
      <div class="sidebar-section-label">Main</div>
      <a href="<?= BASE_URL ?>admin/dashboard.php" class="sidebar-nav-item <?= isActive('dashboard.php', 'admin') ?>" title="Admin Dashboard">
        <div class="nav-icon"><i class="fa-solid fa-gauge-high"></i></div>
        <span>Dashboard</span>
      </a>
      <a href="<?= BASE_URL ?>admin/students.php" class="sidebar-nav-item <?= isActive('students.php', 'admin') ?>" title="Manage Students">
        <div class="nav-icon"><i class="fa-solid fa-user-graduate"></i></div>
        <span>Students</span>
      </a>
      <a href="<?= BASE_URL ?>admin/faculty.php" class="sidebar-nav-item <?= isActive('faculty.php', 'admin') ?>" title="Manage Faculty">
        <div class="nav-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
        <span>Faculty</span>
      </a>
      <a href="<?= BASE_URL ?>admin/courses.php" class="sidebar-nav-item <?= isActive('courses.php', 'admin') ?>" title="Manage Courses">
        <div class="nav-icon"><i class="fa-solid fa-book"></i></div>
        <span>Courses</span>
      </a>
      <a href="<?= BASE_URL ?>admin/skills.php" class="sidebar-nav-item <?= isActive('skills.php', 'admin') ?>" title="Manage Skills">
        <div class="nav-icon"><i class="fa-solid fa-lightbulb"></i></div>
        <span>Skills</span>
      </a>
      <a href="<?= BASE_URL ?>admin/assessments.php" class="sidebar-nav-item <?= isActive('assessments.php', 'admin') ?>" title="Assessments Overview">
        <div class="nav-icon"><i class="fa-solid fa-clipboard-list"></i></div>
        <span>Assessments</span>
      </a>
      <a href="<?= BASE_URL ?>admin/analytics.php" class="sidebar-nav-item <?= isActive('analytics.php', 'admin') ?>" title="System Analytics">
        <div class="nav-icon"><i class="fa-solid fa-chart-line"></i></div>
        <span>Analytics</span>
      </a>
      <a href="<?= BASE_URL ?>admin/settings.php" class="sidebar-nav-item <?= isActive('settings.php', 'admin') ?>" title="System Settings">
        <div class="nav-icon"><i class="fa-solid fa-gear"></i></div>
        <span>Settings</span>
      </a>
    <?php endif; ?>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <a href="<?= BASE_URL ?><?= $role ?>/profile.php" class="sidebar-profile-link d-flex align-items-center gap-2 text-decoration-none" title="<?= htmlspecialchars($sidebarUserName) ?> (<?= ucfirst($role) ?>)">
        <img src="<?= htmlspecialchars($sidebarAvatarUrl) ?>" alt="Avatar" class="rounded-circle object-fit-cover" width="36" height="36" style="flex-shrink:0;">
        <div class="sidebar-user-info overflow-hidden">
          <div class="sidebar-user-name text-truncate fw-semibold" style="max-width: 140px; color: #0F172A; font-size: 0.88rem;"><?= htmlspecialchars($sidebarUserName) ?></div>
          <div class="sidebar-user-role text-truncate small text-muted" style="max-width: 140px; font-size: 0.75rem;"><?= htmlspecialchars($sidebarSubTitle) ?></div>
        </div>
      </a>
      <a href="<?= BASE_URL ?>logout.php" style="margin-left:auto;color:#64748B;font-size:1rem" title="Logout" class="p-1">
        <i class="fa-solid fa-right-from-bracket"></i>
      </a>
    </div>
  </div>
</aside>
