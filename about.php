<?php
/**
 * SkillBridge - About Us Page
 * AI-Based Skill Gap Analysis & Learning Management System
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$db = Database::getInstance();

// 1. Fetch Dynamic System Statistics from MySQL PDO
$totalStudents    = (int)($db->fetch("SELECT COUNT(*) as cnt FROM students")['cnt'] ?? 0);
$totalFaculty     = (int)($db->fetch("SELECT COUNT(*) as cnt FROM faculty")['cnt'] ?? 0);
$totalSkills      = (int)($db->fetch("SELECT COUNT(*) as cnt FROM skills")['cnt'] ?? 0);
$totalAssessments = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessments")['cnt'] ?? 0);
$totalCourses     = (int)($db->fetch("SELECT COUNT(*) as cnt FROM courses")['cnt'] ?? 0);

$pageTitle = "About SkillBridge - AI-Based Skill Gap Analysis Platform";
include __DIR__ . '/includes/header.php';
?>

<style>
  .timeline-step-badge {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #26658C;
    color: white;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .tech-badge {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    padding: 10px 18px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #021024;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
  }
  .tech-badge:hover {
    background: #26658C;
    color: white;
    transform: translateY(-2px);
  }
</style>

<div class="dash-content">
  <!-- 1. REDESIGNED CLEAN HERO SECTION -->
  <div class="saas-card p-4 p-md-5 mb-5 text-center">
    <div class="max-w-800 mx-auto">
      <span class="badge saas-badge-primary mb-3">
        <i class="fa-solid fa-circle-info me-1"></i> About SkillBridge
      </span>
      <h1 class="fw-bold display-5 mb-3" style="color: var(--text-heading);">Helping Students <span class="text-primary">Build Better Careers</span></h1>
      <p class="fs-5 mb-4 leading-relaxed" style="color: var(--text-secondary);">
        Empowering students with AI-driven skill analysis, personalized learning, and data-driven career development.
      </p>
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="<?= BASE_URL ?>student/assessments.php" class="btn btn-primary rounded-pill px-4 py-2.5 fs-6 fw-semibold">
          Take an Assessment <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
        <a href="<?= BASE_URL ?>student/roadmap.php" class="btn btn-outline-primary rounded-pill px-4 py-2.5 fs-6 fw-semibold">
          Explore Roadmaps <i class="fa-solid fa-road ms-1"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- 2. DYNAMIC SYSTEM STATISTICS COUNTERS -->
  <div class="row g-3 mb-5">
    <div class="col-6 col-md-4 col-lg-2.4 text-center">
      <div class="saas-card p-3 h-100">
        <div class="fs-2 fw-bold text-primary gradient-text mb-1"><?= number_format($totalStudents) ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Active Students</div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2.4 text-center">
      <div class="saas-card p-3 h-100">
        <div class="fs-2 fw-bold text-success mb-1"><?= number_format($totalFaculty) ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Faculty Members</div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2.4 text-center">
      <div class="saas-card p-3 h-100">
        <div class="fs-2 fw-bold text-warning mb-1"><?= number_format($totalSkills) ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Technical Skills</div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2.4 text-center">
      <div class="saas-card p-3 h-100">
        <div class="fs-2 fw-bold text-info mb-1"><?= number_format($totalAssessments) ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Quizzes & Tests</div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2.4 text-center">
      <div class="saas-card p-3 h-100">
        <div class="fs-2 fw-bold text-danger mb-1"><?= number_format($totalCourses) ?></div>
        <div class="small font-semibold" style="color: var(--text-muted);">Enrolled Courses</div>
      </div>
    </div>
  </div>

  <!-- 3. OUR MISSION -->
  <div class="saas-card p-4 p-md-5 mb-5">
    <div class="max-w-800 mx-auto text-center">
      <span class="badge saas-badge-primary mb-2">
        <i class="fa-solid fa-bullseye me-1"></i> Core Objective
      </span>
      <h3 class="fw-bold mb-3" style="color: var(--text-heading);">Our Mission</h3>
      <p class="fs-5 leading-relaxed mb-0" style="color: var(--text-secondary);">
        SkillBridge aims to bridge the gap between students' current skills and industry expectations by providing AI-powered skill assessments, personalized learning roadmaps, and continuous progress tracking to help learners become career-ready.
      </p>
    </div>
  </div>

  <!-- 4. WHAT SKILLBRIDGE OFFERS -->
  <div class="mb-5">
    <div class="text-center mb-4">
      <h3 class="fw-bold mb-1" style="color: var(--text-heading);">What SkillBridge Offers</h3>
      <p class="small" style="color: var(--text-muted);">Comprehensive AI-driven tools designed for modern technical education.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="saas-card p-4 h-100">
          <div class="stat-icon primary mb-3 fs-4" style="width: 44px; height: 44px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-brain"></i>
          </div>
          <h5 class="fw-bold mb-2" style="color: var(--text-heading);">AI-Based Skill Gap Analysis</h5>
          <p class="small mb-0" style="color: var(--text-secondary);">Automated comparison of student proficiency against target role benchmarks.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="saas-card p-4 h-100">
          <div class="stat-icon success mb-3 fs-4" style="width: 44px; height: 44px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-clipboard-check"></i>
          </div>
          <h5 class="fw-bold mb-2" style="color: var(--text-heading);">5-Tier Skill Assessments</h5>
          <p class="small mb-0" style="color: var(--text-secondary);">25-question quizzes across Beginner, Easy, Intermediate, Advanced, and Expert tiers.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="saas-card p-4 h-100">
          <div class="stat-icon warning mb-3 fs-4" style="width: 44px; height: 44px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-road"></i>
          </div>
          <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Personalized Roadmaps</h5>
          <p class="small mb-0" style="color: var(--text-secondary);">Tailored milestone paths for Frontend, Backend, Full Stack, Data Science, and DevOps.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="saas-card p-4 h-100">
          <div class="stat-icon accent mb-3 fs-4" style="width: 44px; height: 44px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-chart-line"></i>
          </div>
          <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Progress & Leaderboard</h5>
          <p class="small mb-0" style="color: var(--text-secondary);">Real-time study hours, score analytics, achievements, and cohort rankings.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- 5. HOW SKILLBRIDGE WORKS (TIMELINE) -->
  <div class="saas-card p-4 p-md-5 mb-5">
    <div class="text-center mb-5">
      <h3 class="fw-bold mb-1" style="color: var(--text-heading);">How SkillBridge Works</h3>
      <p class="small" style="color: var(--text-muted);">8 simple steps to bridge your skill gap and become industry-ready.</p>
    </div>

    <div class="row g-4">
      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
          <div class="timeline-step-badge">1</div>
          <div>
            <div class="fw-bold small" style="color: var(--text-heading);">Student Login</div>
            <div style="font-size: 11px; color: var(--text-muted);">Secure SSO access</div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
          <div class="timeline-step-badge">2</div>
          <div>
            <div class="fw-bold small" style="color: var(--text-heading);">Take Assessment</div>
            <div style="font-size: 11px; color: var(--text-muted);">Select skill & level</div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
          <div class="timeline-step-badge">3</div>
          <div>
            <div class="fw-bold small" style="color: var(--text-heading);">AI Analyzes Score</div>
            <div style="font-size: 11px; color: var(--text-muted);">Weighted calculation</div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
          <div class="timeline-step-badge">4</div>
          <div>
            <div class="fw-bold small" style="color: var(--text-heading);">Skill Gap Identified</div>
            <div style="font-size: 11px; color: var(--text-muted);">Target role benchmark</div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
          <div class="timeline-step-badge">5</div>
          <div>
            <div class="fw-bold small" style="color: var(--text-heading);">Roadmap Generated</div>
            <div style="font-size: 11px; color: var(--text-muted);">Phase milestones</div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
          <div class="timeline-step-badge">6</div>
          <div>
            <div class="fw-bold small" style="color: var(--text-heading);">Learn Skills</div>
            <div style="font-size: 11px; color: var(--text-muted);">Recommended courses</div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--bg-alt); border: 1px solid var(--border);">
          <div class="timeline-step-badge">7</div>
          <div>
            <div class="fw-bold small" style="color: var(--text-heading);">Track Progress</div>
            <div style="font-size: 11px; color: var(--text-muted);">Analytics & streak</div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3);">
          <div class="timeline-step-badge" style="background: #10B981;">8</div>
          <div>
            <div class="fw-bold text-success small">Placement Ready!</div>
            <div style="font-size: 11px; color: var(--text-muted);">Career achievement</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 6. USER ROLES -->
  <div class="mb-5">
    <div class="text-center mb-4">
      <h3 class="fw-bold mb-1" style="color: var(--text-heading);">Role-Based Ecosystem</h3>
      <p class="small" style="color: var(--text-muted);">Designed for students, faculty advisors, and institutional administrators.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="saas-card p-4 h-100">
          <div class="fs-1 text-primary mb-3"><i class="fa-solid fa-user-graduate"></i></div>
          <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Students</h5>
          <ul class="small ps-3 mb-0 leading-relaxed" style="color: var(--text-secondary);">
            <li>Take 5-tier skill assessments</li>
            <li>View personalized skill gap matrices</li>
            <li>Follow interactive career roadmaps</li>
            <li>Track study hours & earn badges</li>
          </ul>
        </div>
      </div>

      <div class="col-md-4">
        <div class="saas-card p-4 h-100">
          <div class="fs-1 text-success mb-3"><i class="fa-solid fa-chalkboard-user"></i></div>
          <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Faculty</h5>
          <ul class="small ps-3 mb-0 leading-relaxed" style="color: var(--text-secondary);">
            <li>Monitor student cohort progress</li>
            <li>Create & edit skill assessments</li>
            <li>Manage Question Bank entries</li>
            <li>Analyze department skill gap reports</li>
          </ul>
        </div>
      </div>

      <div class="col-md-4">
        <div class="saas-card p-4 h-100">
          <div class="fs-1 text-warning mb-3"><i class="fa-solid fa-user-shield"></i></div>
          <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Administrators</h5>
          <ul class="small ps-3 mb-0 leading-relaxed" style="color: var(--text-secondary);">
            <li>Manage student & faculty accounts</li>
            <li>Configure skill categories & courses</li>
            <li>Monitor platform-wide analytics</li>
            <li>Ensure system security & uptime</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- 7. TECHNOLOGIES USED -->
  <div class="saas-card p-4 p-md-5 mb-4 text-center">
    <h4 class="fw-bold mb-2" style="color: var(--text-heading);">Powered By Modern Web Technologies</h4>
    <p class="small mb-4" style="color: var(--text-muted);">Built on a secure, high-performance PHP & MySQL architecture.</p>

    <div class="d-flex justify-content-center flex-wrap gap-2">
      <div class="tech-badge"><i class="fa-brands fa-php text-primary fs-5"></i> PHP 8.2</div>
      <div class="tech-badge"><i class="fa-solid fa-database text-warning fs-5"></i> MySQL & PDO</div>
      <div class="tech-badge"><i class="fa-brands fa-html5 text-danger fs-5"></i> HTML5</div>
      <div class="tech-badge"><i class="fa-brands fa-css3-alt text-info fs-5"></i> CSS3</div>
      <div class="tech-badge"><i class="fa-brands fa-js text-warning fs-5"></i> JavaScript ES6+</div>
      <div class="tech-badge"><i class="fa-brands fa-bootstrap text-primary fs-5"></i> Bootstrap 5</div>
      <div class="tech-badge"><i class="fa-solid fa-chart-line text-success fs-5"></i> Chart.js 4.4</div>
    </div>
  </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
