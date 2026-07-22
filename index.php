<?php
/**
 * SkillBridge - System Landing Page / Redirect Router
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Redirect authenticated users to their role dashboard
if (is_logged_in()) {
    $role = $_SESSION['user_role'] ?? 'student';
    match ($role) {
        'student' => redirect(BASE_URL . 'student/dashboard.php'),
        'faculty' => redirect(BASE_URL . 'faculty/dashboard.php'),
        'admin'   => redirect(BASE_URL . 'admin/dashboard.php'),
        default   => redirect(BASE_URL . 'login.php')
    };
}

$db = Database::getInstance();

// Live Dynamic Database Statistics
$totalStudents = (int)($db->fetch("SELECT COUNT(*) as cnt FROM students")['cnt'] ?? 0);

$totalResults = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results")['cnt'] ?? 0);
$passedResults = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE status = ?", ['pass'])['cnt'] ?? 0);
$successRate = $totalResults > 0 ? round(($passedResults / $totalResults) * 100) : 100;

$totalAssessments = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessments")['cnt'] ?? 0);
$totalCourses = (int)($db->fetch("SELECT COUNT(*) as cnt FROM courses")['cnt'] ?? 0);

$pageTitle = "SkillBridge – Skill Gap Analysis & Learning Management System";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="Identify your skill gaps, get personalized learning roadmaps, and accelerate your career with SkillBridge skill analysis." />
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
  
  <!-- FontAwesome 6.5, Devicon Official Logos & Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/devicon.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet" />

  <style>
    /* CSS Tokens & Custom Styling Merged from Original Design */
    :root {
      --space-4: 1rem;
      --space-6: 1.5rem;
      --space-20: 5rem;
      --transition-fast: 0.2s ease;
      --transition-base: 0.3s ease;

      --bg-primary: #FFFFFF;
      --bg-secondary: #F4F9FF;
      --bg-card: #FFFFFF;
      --bg-card-hover: #F4F9FF;
      --primary: #26658C;
      --primary-light: #5483B3;
      --primary-dark: #021024;
      --accent: #14B8A6;
      --accent-light: #CCFBF1;
      --success: #22C55E;
      --warning: #F59E0B;
      --danger: #EF4444;
      --info: #14B8A6;
      --text-primary: #021024;
      --text-secondary: #1F2937;
      --text-muted: #6B7280;
      --border: #E5E7EB;
      --border-hover: #14B8A6;
      --glass: rgba(255, 255, 255, 0.85);
      --glass-strong: rgba(255, 255, 255, 0.98);
      --card-border: #DCE8F5;
    }

    /* Premium Dark Mode Override */
    body.dark-mode {
      --bg-primary: #0D1117;
      --bg-secondary: #111827;
      --bg-card: #111827;
      --bg-card-hover: #1F2937;
      --primary: #2E6CB7;
      --primary-light: #5289D0;
      --primary-dark: #021024;
      --accent: #F2C14E;
      --accent-light: rgba(242, 193, 78, 0.15);
      --success: #22C55E;
      --warning: #F2C14E;
      --danger: #EF4444;
      --info: #2E6CB7;
      --text-primary: #DDE2E8;
      --text-secondary: #DDE2E8;
      --text-muted: #9CA3AF;
      --border: rgba(221, 226, 232, 0.1);
      --border-hover: #F2C14E;
      --glass: rgba(17, 24, 39, 0.85);
      --glass-strong: rgba(17, 24, 39, 0.98);
      --card-border: rgba(221, 226, 232, 0.1);
    }

    body {
      font-family: 'Poppins', 'Inter', sans-serif;
      background-color: var(--bg-primary) !important;
      color: var(--text-secondary) !important;
      transition: background-color var(--transition-base), color var(--transition-base);
    }

    h1, h2, h3, h4, h5, h6 {
      color: var(--text-primary) !important;
      font-family: 'Outfit', 'Poppins', sans-serif;
    }

    /* Gradient Text */
    .gradient-text {
      background: linear-gradient(135deg, var(--primary), var(--accent)) !important;
      -webkit-background-clip: text !important;
      -webkit-text-fill-color: transparent !important;
      background-clip: text !important;
    }

    /* Navbar */
    .landing-navbar {
      position: sticky;
      top: 0;
      z-index: 1050;
      background: var(--glass) !important;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border);
      transition: all var(--transition-base);
    }
    .landing-navbar.scrolled {
      background: var(--glass-strong) !important;
      box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }
    .nav-brand-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: #FFFFFF;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }

    /* Theme Toggle */
    .theme-toggle-btn {
      background: transparent;
      border: 1px solid var(--border);
      color: var(--text-secondary);
      border-radius: 50%;
      width: 38px;
      height: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      transition: all var(--transition-fast);
    }
    .theme-toggle-btn:hover {
      color: var(--accent);
      border-color: var(--accent);
      background: var(--accent-light);
    }

    /* Hero Section */
    .hero-section {
      padding: 6.5rem 0 5rem;
      background: linear-gradient(180deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
      position: relative;
      overflow: hidden;
    }
    .hero-bg-blobs {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      pointer-events: none;
      z-index: 0;
    }
    .bg-blob-1 {
      position: absolute;
      width: 450px;
      height: 450px;
      top: -120px;
      right: -60px;
      background: rgba(20, 184, 166, 0.09);
      filter: blur(100px);
      border-radius: 50%;
    }
    .bg-blob-2 {
      position: absolute;
      width: 400px;
      height: 400px;
      bottom: -60px;
      left: -60px;
      background: rgba(38, 101, 140, 0.09);
      filter: blur(100px);
      border-radius: 50%;
    }

    .hero-eyebrow {
      color: var(--primary) !important;
      font-weight: 700;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 1rem;
      display: inline-flex;
      align-items: center;
    }
    body.dark-mode .hero-eyebrow,
    body.dark-mode .section-tag {
      color: var(--accent) !important;
    }

    .hero-title {
      font-size: 3.5rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      line-height: 1.18;
      margin-bottom: 1.5rem;
    }
    @media (max-width: 768px) {
      .hero-title {
        font-size: 2.5rem;
      }
    }

    .hero-subtitle {
      font-size: 1.2rem;
      line-height: 1.7;
      color: var(--text-muted);
      margin-bottom: 2.5rem;
      max-width: 660px;
    }

    /* 2026 SaaS Buttons System */
    .btn-saas-primary {
      background: linear-gradient(135deg, #26658C 0%, #14B8A6 100%) !important;
      color: #FFFFFF !important;
      border: none !important;
      border-radius: 14px !important;
      padding: 0.85rem 2rem !important;
      font-size: 0.95rem !important;
      font-weight: 600 !important;
      letter-spacing: 0.2px !important;
      min-width: 210px !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 0.5rem !important;
      box-shadow: 0 8px 20px -4px rgba(20, 184, 166, 0.35), 0 3px 8px rgba(38, 101, 140, 0.15) !important;
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
      text-decoration: none !important;
    }
    body.dark-mode .btn-saas-primary {
      background: linear-gradient(135deg, #2E6CB7 0%, #F2C14E 100%) !important;
      color: #0D1117 !important;
      box-shadow: 0 8px 20px -4px rgba(242, 193, 78, 0.35), 0 3px 8px rgba(46, 108, 183, 0.2) !important;
    }
    .btn-saas-primary:hover {
      transform: translateY(-2px) scale(1.01) !important;
      box-shadow: 0 12px 28px -4px rgba(20, 184, 166, 0.5), 0 4px 12px rgba(38, 101, 140, 0.25) !important;
      color: #FFFFFF !important;
    }
    body.dark-mode .btn-saas-primary:hover {
      color: #0D1117 !important;
      box-shadow: 0 12px 28px -4px rgba(242, 193, 78, 0.55) !important;
    }
    .btn-saas-primary:active {
      transform: translateY(0px) scale(0.99) !important;
    }

    .btn-saas-secondary {
      background: rgba(255, 255, 255, 0.85) !important;
      color: var(--text-primary) !important;
      border: 1px solid var(--card-border) !important;
      border-radius: 14px !important;
      padding: 0.85rem 2rem !important;
      font-size: 0.95rem !important;
      font-weight: 600 !important;
      letter-spacing: 0.2px !important;
      min-width: 210px !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 0.5rem !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
      backdrop-filter: blur(12px) !important;
      -webkit-backdrop-filter: blur(12px) !important;
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
      text-decoration: none !important;
    }
    body.dark-mode .btn-saas-secondary {
      background: rgba(17, 24, 39, 0.75) !important;
      color: var(--text-primary) !important;
      border-color: rgba(221, 226, 232, 0.15) !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
    }
    .btn-saas-secondary:hover {
      background: var(--bg-card-hover) !important;
      border-color: var(--primary-light) !important;
      color: var(--primary) !important;
      transform: translateY(-2px) scale(1.01) !important;
      box-shadow: 0 8px 20px -4px rgba(38, 101, 140, 0.12) !important;
    }
    body.dark-mode .btn-saas-secondary:hover {
      border-color: var(--accent) !important;
      color: var(--accent) !important;
      box-shadow: 0 8px 20px -4px rgba(242, 193, 78, 0.18) !important;
    }
    .btn-saas-secondary:active {
      transform: translateY(0px) scale(0.99) !important;
    }

    /* Avatars Social Proof */
    .hero-social-proof {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-top: 2rem;
    }
    .hero-avatars {
      display: flex;
    }
    .hero-avatar-ph {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 2px solid var(--bg-primary);
      color: #fff;
      font-size: 0.75rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-left: -10px;
    }
    .hero-avatars .hero-avatar-ph:first-child { margin-left: 0; }
    .hero-proof-text {
      margin-bottom: 0;
      font-size: 0.9rem;
      color: var(--text-muted);
    }

    /* Feature & Step Cards */
    .feature-card, .step-card, .review-card {
      background: var(--bg-card) !important;
      border: 1px solid var(--card-border) !important;
      border-radius: 1.25rem;
      padding: 2rem;
      height: 100%;
      box-shadow: 0 4px 20px rgba(2, 16, 36, 0.04);
      transition: all var(--transition-base);
    }
    .feature-card:hover, .step-card:hover, .review-card:hover {
      border-color: var(--primary-light) !important;
      box-shadow: 0 10px 30px rgba(38, 101, 140, 0.12) !important;
      transform: translateY(-5px);
    }
    body.dark-mode .feature-card:hover,
    body.dark-mode .step-card:hover,
    body.dark-mode .review-card:hover {
      border-color: var(--accent) !important;
      box-shadow: 0 10px 30px rgba(242, 193, 78, 0.15) !important;
    }

    .feature-icon-wrap {
      width: 54px;
      height: 54px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1.25rem;
    }

    /* How Section */
    .how-section {
      background: var(--bg-secondary) !important;
    }
    .step-num {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: #FFFFFF;
      font-weight: 800;
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.25rem;
    }

    /* Stats Banner */
    .stats-section {
      background: var(--bg-secondary) !important;
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 3.5rem 0;
    }
    .stat-big-num {
      font-size: 2.75rem;
      font-weight: 800;
      display: block;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .stat-big-label {
      font-size: 0.95rem;
      color: var(--text-muted);
      font-weight: 600;
    }

    /* Top Right Navbar Buttons */
    .nav-btn-signin {
      color: var(--text-primary) !important;
      background: transparent !important;
      border: 1px solid var(--border) !important;
      border-radius: 10px !important;
      padding: 0.45rem 1.25rem !important;
      font-size: 0.9rem !important;
      font-weight: 600 !important;
      transition: all 0.25s ease !important;
      text-decoration: none !important;
    }
    .nav-btn-signin:hover {
      background: var(--bg-card-hover) !important;
      border-color: var(--primary) !important;
      color: var(--primary) !important;
      transform: translateY(-1px) !important;
    }

    .nav-btn-getstarted {
      background: linear-gradient(135deg, #26658C 0%, #14B8A6 100%) !important;
      color: #FFFFFF !important;
      border: none !important;
      border-radius: 10px !important;
      padding: 0.45rem 1.35rem !important;
      font-size: 0.9rem !important;
      font-weight: 600 !important;
      box-shadow: 0 4px 14px rgba(20, 184, 166, 0.3) !important;
      transition: all 0.25s ease !important;
      text-decoration: none !important;
      display: inline-flex !important;
      align-items: center !important;
    }
    body.dark-mode .nav-btn-getstarted {
      background: linear-gradient(135deg, #2E6CB7 0%, #F2C14E 100%) !important;
      color: #0D1117 !important;
      box-shadow: 0 4px 14px rgba(242, 193, 78, 0.3) !important;
    }
    .nav-btn-getstarted:hover {
      transform: translateY(-2px) scale(1.02) !important;
      box-shadow: 0 6px 18px rgba(20, 184, 166, 0.45) !important;
      color: #FFFFFF !important;
    }
    body.dark-mode .nav-btn-getstarted:hover {
      color: #0D1117 !important;
      box-shadow: 0 6px 18px rgba(242, 193, 78, 0.5) !important;
    }

    /* Footer */
    .footer {
      background: #021024 !important;
      color: #E2E8F0 !important;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding: 4.5rem 0 2.5rem;
    }
    body.dark-mode .footer {
      background: #0D1117 !important;
      color: #CBD5E1 !important;
      border-top: 1px solid rgba(221, 226, 232, 0.12);
    }
    .footer h5 {
      color: #FFFFFF !important;
      font-size: 1.05rem;
      font-weight: 700;
      margin-bottom: 1.25rem;
      letter-spacing: 0.3px;
    }
    body.dark-mode .footer h5 {
      color: #F8FAFC !important;
    }
    .footer-desc {
      color: #94A3B8 !important;
      font-size: 0.92rem;
      line-height: 1.65;
    }
    body.dark-mode .footer-desc {
      color: #CBD5E1 !important;
    }
    .footer-link {
      color: #CBD5E1 !important;
      text-decoration: none;
      display: inline-block;
      margin-bottom: 0.6rem;
      font-size: 0.92rem;
      font-weight: 400;
      transition: color var(--transition-fast), transform var(--transition-fast);
    }
    body.dark-mode .footer-link {
      color: #94A3B8 !important;
    }
    .footer-link:hover {
      color: var(--accent) !important;
      transform: translateX(3px);
    }
    .social-btn {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.08);
      color: #14B8A6;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: all var(--transition-fast);
      margin-right: 0.5rem;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .social-btn:hover {
      background: var(--accent);
      color: #021024;
      border-color: var(--accent);
      transform: translateY(-2px);
    }
    .footer-bottom-text {
      color: #94A3B8 !important;
      font-size: 0.88rem;
    }
    .footer-bottom-link {
      color: #94A3B8 !important;
      text-decoration: none;
      transition: color var(--transition-fast);
    }
    .footer-bottom-link:hover {
      color: var(--accent) !important;
    }

    /* Mobile Drawer */
    .mobile-drawer {
      position: fixed;
      top: 0;
      right: -300px;
      width: 280px;
      height: 100vh;
      background: var(--glass-strong);
      backdrop-filter: blur(25px);
      z-index: 1090;
      padding: 2rem 1.5rem;
      transition: right 0.3s ease;
      box-shadow: -5px 0 25px rgba(0,0,0,0.15);
    }
    .mobile-drawer.open {
      right: 0;
    }
  </style>
</head>
<body>

<!-- ══════════ NAVBAR ══════════ -->
<nav class="landing-navbar py-3" id="mainNav">
  <div class="container d-flex align-items-center justify-content-between">
    <!-- Brand Logo -->
    <a href="<?= BASE_URL ?>" class="d-flex align-items-center gap-2 text-decoration-none">
      <div class="nav-brand-icon"><i class="fa-solid fa-brain"></i></div>
      <span class="fw-bold fs-4 text-dark font-heading">Skill<span class="gradient-text">Bridge</span></span>
    </a>

    <!-- Desktop Nav Links -->
    <ul class="nav d-none d-md-flex align-items-center gap-4 mb-0">
      <li class="nav-item"><a href="#features" class="nav-link text-secondary fw-semibold p-0">Features</a></li>
      <li class="nav-item"><a href="#how" class="nav-link text-secondary fw-semibold p-0">How It Works</a></li>
      <li class="nav-item"><a href="#stats" class="nav-link text-secondary fw-semibold p-0">Stats</a></li>
    </ul>

    <!-- Right Actions -->
    <div class="d-flex align-items-center gap-2">
      <!-- Light / Dark Theme Toggle Button -->
      <button id="themeToggle" class="theme-toggle-btn" aria-label="Toggle Theme">
        <i class="fa-solid fa-moon"></i>
      </button>

      <a href="<?= BASE_URL ?>login.php" class="nav-btn-signin d-none d-sm-inline-block">Sign In</a>
      <a href="<?= BASE_URL ?>register.php" class="nav-btn-getstarted">
        <i class="fa-solid fa-rocket me-1"></i> Get Started
      </a>

      <!-- Mobile Hamburger -->
      <button class="btn btn-light btn-sm border-0 d-md-none me-0" id="hamburger" aria-label="Menu">
        <i class="fa-solid fa-bars fs-5"></i>
      </button>
    </div>
  </div>
</nav>

<!-- Mobile Navigation Drawer -->
<div class="mobile-drawer" id="mobileNav">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <span class="fw-bold fs-5">Menu</span>
    <button class="btn btn-sm btn-light border-0 rounded-circle" id="closeNav"><i class="fa-solid fa-xmark"></i></button>
  </div>
  <div class="d-flex flex-column gap-3">
    <a href="#features" class="text-decoration-none text-dark fw-semibold" onclick="closeMobileNav()">Features</a>
    <a href="#how" class="text-decoration-none text-dark fw-semibold" onclick="closeMobileNav()">How It Works</a>
    <a href="#stats" class="text-decoration-none text-dark fw-semibold" onclick="closeMobileNav()">Stats</a>
    <hr>
    <a href="<?= BASE_URL ?>login.php" class="btn btn-outline-primary rounded-pill w-100">Sign In</a>
    <a href="<?= BASE_URL ?>register.php" class="btn btn-primary bg-gradient-primary border-0 rounded-pill w-100"><i class="fa-solid fa-rocket me-1"></i> Get Started</a>
  </div>
</div>

<!-- ══════════ HERO SECTION ══════════ -->
<section class="hero-section" id="hero">
  <div class="hero-bg-blobs">
    <div class="bg-blob-1"></div>
    <div class="bg-blob-2"></div>
  </div>

  <div class="container position-relative" style="z-index: 2;">
    <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
      <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 max-w-2xl mx-auto mb-4 p-3" role="alert" style="background: rgba(16, 185, 129, 0.12); color: #065F46; border: 1px solid rgba(16, 185, 129, 0.2) !important;">
        <i class="fa-solid fa-circle-check fs-5 text-success me-1"></i>
        <div class="fw-semibold small">
          You have been logged out successfully.
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="text-center max-w-3xl mx-auto">
      <h1 class="hero-title">
        Bridge Your <span class="gradient-text">Skill Gap</span><br />and Build Your Career
      </h1>

      <p class="hero-subtitle mx-auto">
        Analyze your skills, discover your potential, and unlock personalized learning paths that prepare you for academic success and future careers.
      </p>

      <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
        <a href="<?= BASE_URL ?>register.php" class="btn-saas-primary">
          <i class="fa-solid fa-rocket me-2"></i> Start Free Analysis
        </a>
        <a href="<?= BASE_URL ?>login.php" class="btn-saas-secondary">
          <i class="fa-solid fa-sign-in-alt me-2"></i> Access Portal Log In
        </a>
      </div>

      <!-- Social Proof -->
      <div class="hero-social-proof justify-content-center">
        <div class="hero-avatars">
          <div class="hero-avatar-ph" style="background:linear-gradient(135deg,#6366F1,#8B5CF6)">AK</div>
          <div class="hero-avatar-ph" style="background:linear-gradient(135deg,#10B981,#3B82F6)">MS</div>
          <div class="hero-avatar-ph" style="background:linear-gradient(135deg,#F59E0B,#EF4444)">RJ</div>
          <div class="hero-avatar-ph" style="background:linear-gradient(135deg,#EC4899,#8B5CF6)">PR</div>
        </div>
        <p class="hero-proof-text">Joined by <strong><?= number_format($totalStudents) ?>+</strong> active students & faculty</p>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ FEATURES SECTION ══════════ -->
<section class="py-5" id="features">
  <div class="container py-4">
    <div class="text-center max-w-2xl mx-auto mb-5">
      <span class="hero-eyebrow justify-content-center">What We Offer</span>
      <h2 class="fw-bold display-5 mb-3">Everything You Need to <span class="gradient-text">Close the Gap</span></h2>
      <p class="text-muted">A comprehensive platform that evaluates your technical skills, identifies deficiencies, and delivers a personalized roadmap to your dream career.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="feature-icon-wrap" style="background:rgba(38, 101, 140, 0.12); color:#26658C;">
            <i class="fa-solid fa-brain"></i>
          </div>
          <h4 class="fw-bold mb-2">Skill Gap Analysis</h4>
          <p class="text-muted small mb-0">Advanced algorithms evaluate your current scores against target industry benchmark standards with precision.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="feature-icon-wrap" style="background:rgba(20, 184, 166, 0.12); color:#14B8A6;">
            <i class="fa-solid fa-map-location-dot"></i>
          </div>
          <h4 class="fw-bold mb-2">Personalized Roadmaps</h4>
          <p class="text-muted small mb-0">Get a step-by-step learning roadmap tailored specifically to your weak skills and academic goals.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="feature-icon-wrap" style="background:rgba(5, 38, 89, 0.12); color:#052659;">
            <i class="fa-solid fa-chart-line"></i>
          </div>
          <h4 class="fw-bold mb-2">Real-Time Analytics</h4>
          <p class="text-muted small mb-0">Track your learning progress with interactive Radar and Bar charts powered by real-time database metrics.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="feature-icon-wrap" style="background:rgba(84, 131, 179, 0.12); color:#5483B3;">
            <i class="fa-solid fa-graduation-cap"></i>
          </div>
          <h4 class="fw-bold mb-2">Curated Courses</h4>
          <p class="text-muted small mb-0">Access hand-picked courses linked directly to technical skills, recommended automatically based on your gaps.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="feature-icon-wrap" style="background:rgba(13, 148, 136, 0.12); color:#0D9488;">
            <i class="fa-solid fa-trophy"></i>
          </div>
          <h4 class="fw-bold mb-2">Achievement System</h4>
          <p class="text-muted small mb-0">Earn proficiency badges, track assessment completion rates, and maintain progress momentum.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="feature-icon-wrap" style="background:rgba(6, 182, 212, 0.12); color:#06B6D4;">
            <i class="fa-solid fa-users"></i>
          </div>
          <h4 class="fw-bold mb-2">Faculty & Admin Tools</h4>
          <p class="text-muted small mb-0">Powerful dashboards for faculty to build question banks, evaluate class performance, and assign recommendations.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ HOW IT WORKS SECTION ══════════ -->
<section class="py-5 how-section" id="how">
  <div class="container py-4">
    <div class="text-center max-w-2xl mx-auto mb-5">
      <span class="hero-eyebrow justify-content-center">Simple Process</span>
      <h2 class="fw-bold display-5 mb-3">How <span class="gradient-text">SkillBridge</span> Works</h2>
      <p class="text-muted">Four simple steps to transform your technical trajectory with data-driven skill intelligence.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="step-card">
          <div class="step-num">1</div>
          <h5 class="fw-bold mb-2">Create Your Profile</h5>
          <p class="text-muted small mb-0">Sign up as a Student or Faculty member and set up your academic profile details.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="step-card">
          <div class="step-num">2</div>
          <h5 class="fw-bold mb-2">Take Assessments</h5>
          <p class="text-muted small mb-0">Complete online assessments with live countdown timers and auto-grading.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="step-card">
          <div class="step-num">3</div>
          <h5 class="fw-bold mb-2">Analyze Your Gaps</h5>
          <p class="text-muted small mb-0">View visual radar analytics comparing your current skill level against benchmark targets.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="step-card">
          <div class="step-num">4</div>
          <h5 class="fw-bold mb-2">Follow Your Roadmap</h5>
          <p class="text-muted small mb-0">Execute your personalized course recommendations and track overall completion progress.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ STATS SECTION ══════════ -->
<section class="stats-section" id="stats">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-6 col-md-3">
        <span class="stat-big-num"><?= number_format($totalStudents) ?>+</span>
        <span class="stat-big-label">Active Students</span>
      </div>
      <div class="col-6 col-md-3">
        <span class="stat-big-num"><?= $successRate ?>%</span>
        <span class="stat-big-label">Career Success Rate</span>
      </div>
      <div class="col-6 col-md-3">
        <span class="stat-big-num"><?= number_format($totalAssessments) ?>+</span>
        <span class="stat-big-label">Skill Assessments</span>
      </div>
      <div class="col-6 col-md-3">
        <span class="stat-big-num"><?= number_format($totalCourses) ?>+</span>
        <span class="stat-big-label">Curated Courses</span>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ FOOTER ══════════ -->
<footer class="footer">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-lg-4">
        <a href="<?= BASE_URL ?>" class="d-flex align-items-center gap-2 text-decoration-none mb-3">
          <div class="nav-brand-icon"><i class="fa-solid fa-brain"></i></div>
          <span class="fw-bold fs-4 text-white font-heading">Skill<span class="gradient-text">Bridge</span></span>
        </a>
        <p class="footer-desc mb-3">The most advanced skill gap analysis and LMS platform for students, faculty, and educational institutions.</p>
        <div>
          <a href="javascript:void(0)" class="social-btn"><i class="fa-brands fa-twitter"></i></a>
          <a href="javascript:void(0)" class="social-btn"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="javascript:void(0)" class="social-btn"><i class="fa-brands fa-github"></i></a>
        </div>
      </div>

      <div class="col-6 col-lg-3">
        <h5>Student Modules</h5>
        <a href="<?= BASE_URL ?>student/dashboard.php" class="footer-link">Student Dashboard</a>
        <a href="<?= BASE_URL ?>student/assessments.php" class="footer-link">Online Assessments</a>
        <a href="<?= BASE_URL ?>student/skill-gap.php" class="footer-link">Skill Gap Analysis</a>
        <a href="<?= BASE_URL ?>student/recommendations.php" class="footer-link">Course Recommendations</a>
        <a href="<?= BASE_URL ?>student/progress.php" class="footer-link">Learning Progress Tracker</a>
      </div>

      <div class="col-6 col-lg-3">
        <h5>Faculty & Admin</h5>
        <a href="<?= BASE_URL ?>faculty/dashboard.php" class="footer-link">Faculty Portal</a>
        <a href="<?= BASE_URL ?>faculty/question-bank.php" class="footer-link">Question Bank Builder</a>
        <a href="<?= BASE_URL ?>admin/dashboard.php" class="footer-link">Admin Control Panel</a>
        <a href="<?= BASE_URL ?>admin/reports.php" class="footer-link">Institutional Reports</a>
      </div>

      <div class="col-lg-2">
        <h5>Quick Links</h5>
        <a href="<?= BASE_URL ?>login.php" class="footer-link">Sign In</a>
        <a href="<?= BASE_URL ?>register.php" class="footer-link">Register Account</a>
        <a href="<?= BASE_URL ?>forgot-password.php" class="footer-link">Reset Password</a>
      </div>
    </div>

    <div class="pt-3 border-top border-secondary border-opacity-25 d-flex flex-column flex-md-row justify-content-between align-items-center footer-bottom-text">
      <span>&copy; 2026 <strong>SkillBridge</strong> – Skill Gap Analysis & LMS. All rights reserved.</span>
      <div class="d-flex gap-3 mt-2 mt-md-0">
        <a href="<?= BASE_URL ?>privacy-policy.php" class="footer-bottom-link">Privacy Policy</a>
        <a href="<?= BASE_URL ?>terms-of-service.php" class="footer-bottom-link">Terms & Conditions</a>
        <a href="mailto:skill.profile.project1@gmail.com" class="footer-bottom-link">Support</a>
      </div>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Navbar scroll effect
  const nav = document.getElementById('mainNav');
  window.addEventListener('scroll', () => {
    if (nav) nav.classList.toggle('scrolled', window.scrollY > 40);
  });

  // Mobile Drawer toggle
  const hamburger = document.getElementById('hamburger');
  const mobileNav = document.getElementById('mobileNav');
  const closeNav  = document.getElementById('closeNav');

  if (hamburger && mobileNav) {
    hamburger.addEventListener('click', () => mobileNav.classList.add('open'));
  }
  if (closeNav && mobileNav) {
    closeNav.addEventListener('click', () => mobileNav.classList.remove('open'));
  }
  function closeMobileNav() { 
    if (mobileNav) mobileNav.classList.remove('open'); 
  }

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      if (targetId && targetId !== '#') {
        const target = document.querySelector(targetId);
        if (target) { 
          e.preventDefault(); 
          target.scrollIntoView({ behavior: 'smooth' }); 
        }
      }
    });
  });

  // Light / Dark Theme toggle logic with localStorage
  const themeToggleBtn = document.getElementById('themeToggle');
  const savedTheme = localStorage.getItem('skillbridge_theme');

  if (savedTheme === 'dark') {
    document.body.classList.add('dark-mode');
    if (themeToggleBtn) themeToggleBtn.innerHTML = '<i class="fa-solid fa-sun"></i>';
  }

  if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', function() {
      document.body.classList.toggle('dark-mode');
      const isDark = document.body.classList.contains('dark-mode');
      localStorage.setItem('skillbridge_theme', isDark ? 'dark' : 'light');
      this.innerHTML = isDark ? '<i class="fa-solid fa-sun"></i>' : '<i class="fa-solid fa-moon"></i>';
    });
  }
</script>

</body>
</html>
