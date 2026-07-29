<?php
/**
 * SkillBridge - Student Help & Support Center
 * Tailored interactive guide, searchable FAQs, and platform documentation.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$db = Database::getInstance();

$student = $db->fetch("SELECT * FROM students WHERE id = ?", [$studentId]);
$studentName = htmlspecialchars(trim(($student['first_name'] ?? 'Student') . ' ' . ($student['last_name'] ?? '')));

$pageTitle = "Help & Support Center - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="dash-content">
  <!-- HERO SEARCH BANNER -->
  <style>
  /* Custom Hero Styles */
  .help-hero-card {
      border-radius: 28px !important;
      position: relative;
      overflow: hidden;
      transition: all 0.4s ease;
      animation: hero-fade-in 0.8s ease-out;
  }

  .help-hero-card::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 2;
      pointer-events: none;
  }

  @keyframes hero-fade-in {
      from {
          opacity: 0;
          transform: translateY(15px);
      }
      to {
          opacity: 1;
          transform: translateY(0);
      }
  }

  /* Light Theme overrides */
  [data-theme="light"] .help-hero-card {
      background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 50%, #eff6ff 100%);
      border: 1px solid rgba(255, 255, 255, 0.6) !important;
      box-shadow: 0 20px 40px rgba(37, 99, 235, 0.06);
  }
  [data-theme="light"] .help-hero-card::before {
      background: rgba(255, 255, 255, 0.35);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
  }
  [data-theme="light"] .hero-title {
      color: #0f172a;
  }
  [data-theme="light"] .student-name-highlight {
      background: linear-gradient(to right, #2563eb, #0891b2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
  }
  [data-theme="light"] .hero-subtitle {
      color: #475569;
  }
  [data-theme="light"] .hero-badge {
      background: rgba(37, 99, 235, 0.06);
      color: #2563eb;
      border: 1px solid rgba(37, 99, 235, 0.12);
  }

  /* Dark Theme overrides */
  [data-theme="dark"] .help-hero-card {
      background: linear-gradient(135deg, #020617 0%, #0f172a 50%, #1e1b4b 100%);
      border: 1px solid rgba(255, 255, 255, 0.08) !important;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  }
  [data-theme="dark"] .help-hero-card::before {
      background: rgba(15, 23, 42, 0.35);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
  }
  [data-theme="dark"] .hero-title {
      color: #f8fafc;
  }
  [data-theme="dark"] .student-name-highlight {
      background: linear-gradient(to right, #60a5fa, #22d3ee);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
  }
  [data-theme="dark"] .hero-subtitle {
      color: #94a3b8;
  }
  [data-theme="dark"] .hero-badge {
      background: rgba(255, 255, 255, 0.06);
      color: #e2e8f0;
      border: 1px solid rgba(255, 255, 255, 0.12);
  }

  /* Floating background blobs */
  .hero-blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(60px);
      opacity: 0.25;
      z-index: 1;
      pointer-events: none;
      animation: blob-float 15s infinite alternate ease-in-out;
  }
  [data-theme="light"] .hero-blob {
      opacity: 0.35;
  }
  .blob-1 {
      width: 250px;
      height: 250px;
      background: radial-gradient(circle, #3b82f6 0%, rgba(59, 130, 246, 0) 70%);
      top: -50px;
      left: -50px;
      animation-delay: 0s;
  }
  .blob-2 {
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, #06b6d4 0%, rgba(6, 182, 212, 0) 70%);
      bottom: -80px;
      right: 15%;
      animation-delay: 3s;
  }
  .blob-3 {
      width: 200px;
      height: 200px;
      background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
      top: 10%;
      right: 40%;
      animation-delay: 6s;
  }

  @keyframes blob-float {
      0% {
          transform: translate(0, 0) scale(1);
      }
      50% {
          transform: translate(30px, -20px) scale(1.1);
      }
      100% {
          transform: translate(-20px, 40px) scale(0.9);
      }
  }

  /* Badge Styling */
  .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px !important;
      font-size: 0.8rem !important;
      font-weight: 600;
      border-radius: 100px;
      transition: all 0.3s ease;
  }
  .hero-badge:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
  }

  /* Spacing and typography */
  .hero-title {
      font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
      font-size: 2.5rem;
      line-height: 1.25;
      letter-spacing: -0.02em;
  }
  .hero-subtitle {
      font-size: 1.05rem;
      line-height: 1.6;
      max-width: 580px;
  }

  /* Premium Search Box */
  .hero-search-wrapper {
      max-width: 600px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
      border-radius: 100px;
      transition: all 0.3s ease;
  }
  [data-theme="dark"] .hero-search-wrapper {
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  }
  .hero-search-wrapper:hover {
      box-shadow: 0 12px 30px rgba(37, 99, 235, 0.08);
  }
  .hero-search-input {
      height: 58px;
      padding-left: 56px !important;
      padding-right: 90px !important;
      border-radius: 100px !important;
      font-size: 0.95rem !important;
      font-weight: 500;
      background: rgba(255, 255, 255, 0.8) !important;
      border: 1px solid rgba(37, 99, 235, 0.15) !important;
      color: #1e293b !important;
      transition: all 0.3s ease !important;
  }
  [data-theme="dark"] .hero-search-input {
      background: rgba(15, 23, 42, 0.6) !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      color: #f1f5f9 !important;
  }
  .hero-search-input::placeholder {
      color: #64748b !important;
      font-weight: 400;
  }
  [data-theme="dark"] .hero-search-input::placeholder {
      color: #475569 !important;
  }
  .hero-search-input:focus {
      background: #ffffff !important;
      border-color: #3b82f6 !important;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
  }
  [data-theme="dark"] .hero-search-input:focus {
      background: #0f172a !important;
      border-color: #06b6d4 !important;
      box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15) !important;
  }
  .search-icon {
      position: absolute;
      top: 50%;
      left: 22px;
      transform: translateY(-50%);
      font-size: 1.15rem;
      color: #64748b;
      z-index: 10;
      pointer-events: none;
      transition: color 0.3s ease;
  }
  .hero-search-input:focus + .search-icon,
  .hero-search-wrapper:hover .search-icon {
      color: #3b82f6;
  }
  [data-theme="dark"] .hero-search-input:focus + .search-icon,
  [data-theme="dark"] .hero-search-wrapper:hover .search-icon {
      color: #06b6d4;
  }

  /* Keyboard Shortcut Badge */
  .search-kbd-shortcut {
      position: absolute;
      top: 50%;
      right: 16px;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.05);
      border: 1px solid rgba(0, 0, 0, 0.08);
      color: #64748b;
      font-size: 0.75rem;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 8px;
      pointer-events: none;
      font-family: system-ui, -apple-system, sans-serif;
      z-index: 10;
      transition: all 0.3s ease;
  }
  [data-theme="dark"] .search-kbd-shortcut {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.08);
      color: #94a3b8;
  }
  .hero-search-input:focus ~ .search-kbd-shortcut {
      opacity: 0;
      transform: translateY(-50%) scale(0.9);
  }

  /* Illustration / Floating Shapes container */
  .illustration-container {
      width: 320px;
      height: 320px;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
  }
  .illustration-glow {
      position: absolute;
      width: 220px;
      height: 220px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, rgba(6, 182, 212, 0) 70%);
      filter: blur(20px);
      z-index: 1;
  }
  [data-theme="dark"] .illustration-glow {
      background: radial-gradient(circle, rgba(6, 182, 212, 0.35) 0%, rgba(99, 102, 241, 0) 70%);
  }

  .floating-element {
      position: absolute;
      width: 58px;
      height: 58px;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.25);
      border: 1px solid rgba(255, 255, 255, 0.3);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: #2563eb;
      z-index: 2;
      transform: translate(var(--x), var(--y));
      animation: shape-float 5s infinite ease-in-out;
      animation-delay: var(--delay);
  }
  [data-theme="light"] .floating-element {
      background: rgba(255, 255, 255, 0.65);
      border: 1px solid rgba(255, 255, 255, 0.8);
      box-shadow: 0 10px 25px rgba(37, 99, 235, 0.08);
  }
  [data-theme="dark"] .floating-element {
      background: rgba(30, 41, 59, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      color: #22d3ee;
  }

  /* Shapes customized behavior */
  .shape-chat {
      width: 68px;
      height: 68px;
      font-size: 1.75rem;
  }
  .shape-search {
      width: 50px;
      height: 50px;
      font-size: 1.25rem;
      background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%) !important;
      color: #ffffff !important;
      border: none !important;
  }

  @keyframes shape-float {
      0% {
          transform: translate(var(--x), var(--y));
      }
      50% {
          transform: translate(var(--x), calc(var(--y) - 15px));
      }
      100% {
          transform: translate(var(--x), var(--y));
      }
  }

  /* Responsive adjustment for illustration */
  @media (max-width: 1199.98px) {
      .illustration-container {
          transform: scale(0.85);
      }
  }
  @media (max-width: 991.98px) {
      .illustration-container {
          transform: scale(0.75);
          margin: 20px auto 0;
      }
      .hero-title {
          font-size: 2.25rem;
      }
  }
  @media (max-width: 575.98px) {
      .illustration-container {
          transform: scale(0.65);
          margin: 15px auto 0;
      }
      .hero-title {
          font-size: 1.85rem;
      }
      .hero-search-input {
          height: 54px;
          padding-left: 48px !important;
      }
      .search-icon {
          left: 18px;
      }
  }
  </style>

  <div class="card border-0 help-hero-card mb-4 position-relative overflow-hidden">
    <!-- Glowing Animated Blobs -->
    <div class="hero-blob blob-1"></div>
    <div class="hero-blob blob-2"></div>
    <div class="hero-blob blob-3"></div>

    <div class="position-relative z-3 p-4 p-md-5">
      <div class="row align-items-center">
        <!-- Left Column: Content & Search -->
        <div class="col-lg-7 col-md-12">
          <span class="badge hero-badge mb-3">
            <i class="fa-solid fa-book me-1"></i> SkillBridge Knowledge Base
          </span>
          <h2 class="fw-bold hero-title mb-3">How can we help you today, <span class="student-name-highlight"><?= $studentName ?></span>?</h2>
          <p class="hero-subtitle mb-4">Search guides, assessment rules, roadmap workflows, and frequently asked questions.</p>
          
          <div class="position-relative hero-search-wrapper">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" id="helpSearchInput" class="form-control hero-search-input" 
                   placeholder="Search topics (e.g. assessments, skill percentage, roadmap, leaderboard)..." onkeyup="filterHelpTopics()">
            <span class="search-kbd-shortcut">Ctrl K</span>
          </div>
        </div>
        
        <!-- Right Column: Illustration (Desktop/Tablet and stacks on Mobile) -->
        <div class="col-lg-5 col-md-12 d-flex justify-content-center position-relative mt-4 mt-lg-0">
          <div class="illustration-container">
            <div class="floating-element shape-chat" style="--delay: 0s; --x: -70px; --y: -50px;">
              <i class="fa-solid fa-comments"></i>
            </div>
            <div class="floating-element shape-question" style="--delay: 1.5s; --x: 65px; --y: -80px;">
              <i class="fa-solid fa-question"></i>
            </div>
            <div class="floating-element shape-book" style="--delay: 0.8s; --x: -85px; --y: 65px;">
              <i class="fa-solid fa-book-open"></i>
            </div>
            <div class="floating-element shape-cap" style="--delay: 2.2s; --x: 85px; --y: 45px;">
              <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="floating-element shape-search" style="--delay: 3s; --x: 0px; --y: 10px;">
              <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <!-- Radial Core Glow behind icons -->
            <div class="illustration-glow"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- HELP CATEGORIES GRID -->
  <div class="row g-4 mb-5" id="helpCategoriesGrid">
    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon primary mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
          <i class="fa-solid fa-compass"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">Getting Started</h5>
        <p class="text-muted small mb-3">Navigate your student dashboard, view profile stats, and explore learning modules.</p>
        <a href="#faq-section" onclick="filterFAQ('getting-started')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon success mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
          <i class="fa-solid fa-clipboard-check"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">Skill Assessments</h5>
        <p class="text-muted small mb-3">Learn how 5-tier difficulty levels (Beginner to Expert) evaluate your technical mastery.</p>
        <a href="#faq-section" onclick="filterFAQ('assessments')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon warning mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
          <i class="fa-solid fa-magnifying-glass-chart"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">Skill Gap Analysis</h5>
        <p class="text-muted small mb-3">Understand how target role requirements are compared against your real assessment scores.</p>
        <a href="#faq-section" onclick="filterFAQ('skill-gap')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon accent mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
          <i class="fa-solid fa-road"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">Learning Roadmap</h5>
        <p class="text-muted small mb-3">Select career pathways (Frontend, Backend, Full Stack, Data Science) and track milestones.</p>
        <a href="#faq-section" onclick="filterFAQ('roadmap')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon info mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center; background: rgba(6, 182, 212, 0.15); color: #0891B2;">
          <i class="fa-solid fa-chart-line"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">Progress & Leaderboard</h5>
        <p class="text-muted small mb-3">View weighted skill calculations, study hours, achievements, and cohort rankings.</p>
        <a href="#faq-section" onclick="filterFAQ('progress')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon danger mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center; background: rgba(239, 68, 68, 0.15); color: #EF4444;">
          <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">Courses & Resources</h5>
        <p class="text-muted small mb-3">Access recommended learning courses, video tutorials, and documentation links.</p>
        <a href="#faq-section" onclick="filterFAQ('courses')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- FREQUENTLY ASKED QUESTIONS SECTION -->
  <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5" id="faq-section">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <div>
        <h3 class="fw-bold text-dark mb-1">Frequently Asked Questions</h3>
        <p class="text-muted small mb-0">Find detailed answers to common questions regarding the SkillBridge platform.</p>
      </div>
      <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-primary active rounded-pill px-3" onclick="filterFAQ('all')">All FAQs</button>
      </div>
    </div>

    <div class="accordion accordion-flush" id="faqAccordion">
      <!-- FAQ 1 -->
      <div class="accordion-item border-bottom py-2 faq-item" data-category="progress assessments">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button collapsed fw-bold text-dark bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
            <i class="fa-solid fa-calculator text-primary me-2"></i> How is my Overall Skill Percentage calculated?
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Your overall skill percentage uses a 5-tier weighted formula across completed difficulty levels:
            <ul class="mt-2 mb-2">
              <li><strong>Beginner Level</strong>: 10% Weight</li>
              <li><strong>Easy Level</strong>: 15% Weight</li>
              <li><strong>Intermediate Level</strong>: 20% Weight</li>
              <li><strong>Advanced Level</strong>: 25% Weight</li>
              <li><strong>Expert Level</strong>: 30% Weight</li>
            </ul>
            Each level percentage is computed as <code>(Correct Answers / 25) × Level Weight</code>. The sum across all 5 levels gives your true proficiency.
          </div>
        </div>
      </div>

      <!-- FAQ 2 -->
      <div class="accordion-item border-bottom py-2 faq-item" data-category="assessments">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed fw-bold text-dark bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
            <i class="fa-solid fa-lock text-warning me-2"></i> How do I unlock higher assessment difficulty levels?
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Difficulty levels must be unlocked sequentially. To unlock the next level (e.g. Easy or Intermediate), you must achieve a passing score of at least <strong>60% (15 out of 25 correct answers)</strong> on the preceding level.
          </div>
        </div>
      </div>

      <!-- FAQ 3 -->
      <div class="accordion-item border-bottom py-2 faq-item" data-category="skill-gap">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed fw-bold text-dark bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
            <i class="fa-solid fa-chart-pie text-success me-2"></i> How does Skill Gap Analysis determine my target role readiness?
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Skill Gap Analysis compares your current skill percentages against benchmark requirements for target industry roles (such as <em>Full Stack Developer</em> or <em>Data Scientist</em>). Skills falling below the role's required proficiency threshold are highlighted as gaps with recommended remedial courses.
          </div>
        </div>
      </div>

      <!-- FAQ 4 -->
      <div class="accordion-item border-bottom py-2 faq-item" data-category="roadmap">
        <h2 class="accordion-header" id="headingFour">
          <button class="accordion-button collapsed fw-bold text-dark bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
            <i class="fa-solid fa-map-location-dot text-info me-2"></i> Can I change my selected Career Roadmap Pathway?
          </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Yes! You can switch your active career target at any time using the target role dropdown on the <strong>Learning Roadmap</strong> page. Your milestone progress will automatically recalculate based on your existing assessment scores.
          </div>
        </div>
      </div>

      <!-- FAQ 5 -->
      <div class="accordion-item border-bottom py-2 faq-item" data-category="progress">
        <h2 class="accordion-header" id="headingFive">
          <button class="accordion-button collapsed fw-bold text-dark bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
            <i class="fa-solid fa-fire text-danger me-2"></i> How does the Learning Streak work?
          </button>
        </h2>
        <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Your Learning Streak tracks consecutive days with active learning activity (taking an assessment or updating course progress). Completing at least one activity daily increments your streak.
          </div>
        </div>
      </div>

      <!-- FAQ 6 -->
      <div class="accordion-item py-2 faq-item" data-category="getting-started">
        <h2 class="accordion-header" id="headingSix">
          <button class="accordion-button collapsed fw-bold text-dark bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
            <i class="fa-solid fa-user-gear text-secondary me-2"></i> Where can I update my profile and account details?
          </button>
        </h2>
        <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Navigate to <strong>My Profile</strong> via the sidebar or top navigation bar to update your personal details, academic department, and password.
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CONTACT SUPPORT CTA BANNER -->
  <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-light text-center">
    <div class="max-w-600 mx-auto">
      <div class="fs-1 text-primary mb-3"><i class="fa-solid fa-headset"></i></div>
      <h4 class="fw-bold text-dark mb-2">Still need help?</h4>
      <p class="text-muted small mb-2">Our support team and academic faculty are here to assist you with any platform issues or guidance.</p>
      <div class="mb-4">
        <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1.5 fs-6 font-monospace">
          <i class="fa-solid fa-envelope me-1"></i> skill.profile.project1@gmail.com
        </span>
      </div>
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="mailto:skill.profile.project1@gmail.com" class="btn btn-primary rounded-pill px-4 py-2 small fw-semibold">
          <i class="fa-solid fa-paper-plane me-1"></i> Contact Support (Email Us)
        </a>
        <a href="<?= BASE_URL ?>student/dashboard.php" class="btn btn-outline-secondary rounded-pill px-4 py-2 small fw-semibold">
          <i class="fa-solid fa-gauge-high me-1"></i> Back to Dashboard
        </a>
      </div>
    </div>
  </div>
</div>

<script>
function filterHelpTopics() {
    const q = document.getElementById('helpSearchInput').value.toLowerCase();
    const faqItems = document.querySelectorAll('.faq-item');
    const cardItems = document.querySelectorAll('.help-card-item');

    faqItems.forEach(item => {
        const text = item.innerText.toLowerCase();
        item.style.display = text.includes(q) ? 'block' : 'none';
    });

    cardItems.forEach(item => {
        const text = item.innerText.toLowerCase();
        item.style.display = text.includes(q) ? 'block' : 'none';
    });
}

function filterFAQ(cat) {
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const itemCat = item.getAttribute('data-category');
        if (cat === 'all' || itemCat.includes(cat)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

window.initHelp = function() {
    console.log("Help Center initialized");
};

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    window.initHelp();
} else {
    document.addEventListener('DOMContentLoaded', window.initHelp);
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
