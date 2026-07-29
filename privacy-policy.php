<?php
/**
 * SkillBridge - Privacy Policy Page
 * Skill Gap Analysis & Learning Management System
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "Privacy Policy - SkillBridge";
include __DIR__ . '/includes/header.php';
?>

<style>
/* ── Privacy Policy — Page-Scoped Hero Overrides ── */
.pp-hero {
    background: linear-gradient(135deg, #021024 0%, #063352 40%, #26658C 80%, #14B8A6 100%);
    border-radius: 24px;
    color: #FFFFFF;
    padding: 4rem 3rem;
    box-shadow: 0 20px 60px rgba(2, 16, 36, 0.25), 0 4px 16px rgba(38, 101, 140, 0.15);
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
}
/* Decorative orb top-right */
.pp-hero::before {
    content: '';
    position: absolute;
    top: -80px;
    right: -60px;
    width: 340px;
    height: 340px;
    background: radial-gradient(circle, rgba(20, 184, 166, 0.22) 0%, transparent 68%);
    border-radius: 50%;
    pointer-events: none;
}
/* Decorative orb bottom-left */
.pp-hero::after {
    content: '';
    position: absolute;
    bottom: -60px;
    left: -40px;
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(84, 131, 179, 0.20) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.pp-hero-inner {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 2.5rem;
}
.pp-hero-icon-wrap {
    flex-shrink: 0;
    width: 80px;
    height: 80px;
    border-radius: 22px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.20);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #CCFBF1;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
}
.pp-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 50px;
    padding: 5px 14px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #CCFBF1;
    letter-spacing: 0.02em;
    margin-bottom: 1rem;
}
.pp-hero h1 {
    font-size: clamp(1.9rem, 4vw, 2.75rem);
    font-weight: 800;
    color: #FFFFFF;
    line-height: 1.15;
    margin-bottom: 0.65rem;
    letter-spacing: -0.02em;
    text-shadow: 0 2px 12px rgba(0, 0, 0, 0.25);
}
.pp-hero-subtitle {
    font-size: 1.05rem;
    color: rgba(255, 255, 255, 0.75);
    line-height: 1.6;
    margin-bottom: 1.2rem;
    max-width: 580px;
}
.pp-hero-meta {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex-wrap: wrap;
    font-size: 0.83rem;
    color: rgba(255, 255, 255, 0.55);
}
.pp-hero-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}
.pp-hero-divider {
    width: 1px;
    height: 14px;
    background: rgba(255, 255, 255, 0.25);
}
/* Dark theme overrides */
[data-theme="dark"] .pp-hero {
    background: linear-gradient(135deg, #0D0D1A 0%, #1A1040 40%, #2D1A6B 80%, #3B82A0 100%);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 4px 16px rgba(139, 92, 246, 0.12);
}
[data-theme="dark"] .pp-hero::before {
    background: radial-gradient(circle, rgba(139, 92, 246, 0.20) 0%, transparent 68%);
}
[data-theme="dark"] .pp-hero::after {
    background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
}
[data-theme="dark"] .pp-hero-icon-wrap {
    background: rgba(139, 92, 246, 0.15);
    border-color: rgba(139, 92, 246, 0.30);
    color: #C4B5FD;
}
[data-theme="dark"] .pp-hero-badge {
    background: rgba(139, 92, 246, 0.15);
    border-color: rgba(139, 92, 246, 0.30);
    color: #C4B5FD;
}
@media (max-width: 640px) {
    .pp-hero { padding: 2.5rem 1.5rem; }
    .pp-hero-inner { flex-direction: column; align-items: flex-start; gap: 1.25rem; }
    .pp-hero-icon-wrap { width: 60px; height: 60px; font-size: 1.5rem; border-radius: 16px; }
}
</style>

<div class="dash-content pb-5">
  <!-- HERO BANNER -->
  <div class="pp-hero">
    <div class="pp-hero-inner">
      <!-- Icon orb -->
      <div class="pp-hero-icon-wrap">
        <i class="bi bi-shield-lock"></i>
      </div>
      <!-- Text content -->
      <div>
        <div class="pp-hero-badge">
          <i class="bi bi-shield-check"></i> Legal &amp; Data Trust
        </div>
        <h1>Privacy Policy</h1>
        <p class="pp-hero-subtitle">
          Learn how SkillBridge handles, protects, and respects your personal and skill evaluation data across our Skill Gap Analysis &amp; LMS platform.
        </p>
        <div class="pp-hero-meta">
          <span><i class="bi bi-calendar3"></i> Last Updated: July 29, 2026</span>
          <span class="pp-hero-divider"></span>
          <span><i class="bi bi-clock"></i> 5 min read</span>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- RIGHT CONTENT AREA -->
    <div class="col-12">
      <!-- 1. Introduction -->
      <section id="intro" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon bg-primary-subtle text-primary"><i class="bi bi-info-circle fs-4"></i></div>
          <div>
            <h4 class="fw-bold text-dark mb-0">1. Introduction</h4>
            <span class="text-muted small">Overview & Governance</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-3">
          Welcome to <strong>SkillBridge</strong> (“we,” “our,” or “us”). SkillBridge is a Skill Gap Analysis & Learning Management System designed to evaluate technical competencies, generate custom learning roadmaps, and connect academic curriculum with industry requirements.
        </p>
        <p class="text-secondary leading-relaxed mb-0">
          This Privacy Policy explains how we collect, process, store, and safeguard your information when you access our system through any device. By registering an account or using SkillBridge services, you agree to the collection and use of information in accordance with this policy.
        </p>
      </section>

      <!-- 2. Information We Collect -->
      <section id="info-collect" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-folder-check"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">2. Information We Collect</h4>
            <span class="text-muted small">Personal & Assessment Data</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-3">
          We gather information necessary to provide tailored skill assessments, analytics, and learning progress metrics:
        </p>
        <ul class="text-secondary mb-0 ps-3 space-y-2">
          <li class="mb-2"><strong>Account Details:</strong> Full name, university email address, student ID/roll number, department, section, and phone number provided during registration or profile updates.</li>
          <li class="mb-2"><strong>Academic & Skill Evaluation Data:</strong> Assessment submissions, quiz scores, timing logs, skill proficiency levels, progress percentages, and generated learning roadmap metrics.</li>
          <li class="mb-2"><strong>Technical Log Data:</strong> IP addresses, browser types, device information, session identifiers, access timestamps, and page interactions captured automatically for security auditing.</li>
        </ul>
      </section>

      <!-- 3. How Information Is Used -->
      <section id="how-used" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-cpu"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">3. How Information Is Used</h4>
            <span class="text-muted small">Purpose & Analytics</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-3">
          Your information is used strictly to enhance learning outcomes and system operations:
        </p>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
              <h6 class="fw-bold text-primary mb-1"><i class="bi bi-graph-up-arrow me-1"></i> Skill Gap Analysis</h6>
              <p class="text-secondary small mb-0">Generating automated skill radar reports, course recommendations, and targeted learning pathways.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
              <h6 class="fw-bold text-primary mb-1"><i class="bi bi-mortarboard me-1"></i> Faculty Reporting</h6>
              <p class="text-secondary small mb-0">Providing aggregated classroom performance metrics and skill gaps to authorized academic staff.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- 4. Data Security -->
      <section id="security" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-lock"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">4. Data Security</h4>
            <span class="text-muted small">Protection & Encryption</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-3">
          We deploy robust administrative, physical, and technical controls to guard your personal information against unauthorized access, loss, or alteration:
        </p>
        <ul class="text-secondary mb-0 ps-3">
          <li class="mb-2"><strong>Encryption:</strong> Password hashes are secured using industry-standard BCRYPT hashing algorithms.</li>
          <li class="mb-2"><strong>PDO Prepared Statements:</strong> All database queries utilize parameterized PDO statements to block SQL injection risks.</li>
          <li class="mb-2"><strong>Session Management:</strong> Secure HTTP-only cookies and automatic session expiration safeguard user authentication states.</li>
        </ul>
      </section>

      <!-- 5. Cookies -->
      <section id="cookies" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-cookie"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">5. Cookies & Local Storage</h4>
            <span class="text-muted small">Session Handling</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-0">
          SkillBridge uses essential PHP session cookies (`PHPSESSID`) strictly required for authenticating your user session, preserving active quiz states, and maintaining your dark/light UI theme preference. We do NOT use invasive cross-site tracking or advertising cookies.
        </p>
      </section>

      <!-- 6. User Rights -->
      <section id="rights" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-person-check"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">6. User Rights</h4>
            <span class="text-muted small">Access, Edit & Control</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-3">
          As a registered user of SkillBridge, you hold full rights to manage your personal details:
        </p>
        <ul class="text-secondary mb-0 ps-3">
          <li class="mb-2"><strong>Access & Correction:</strong> Review and edit your profile details at any time from the <em>View Profile</em> or <em>Settings</em> page.</li>
          <li class="mb-2"><strong>Data Export:</strong> View and print your skill gap radar reports and assessment history directly from your dashboard.</li>
          <li class="mb-2"><strong>Account Deactivation:</strong> Request account closure or data erasure by contacting your academic administrator.</li>
        </ul>
      </section>

      <!-- 7. Third-Party Services -->
      <section id="third-party" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-diagram-3"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">7. Third-Party Services</h4>
            <span class="text-muted small">Integrations & External Libraries</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-0">
          SkillBridge utilizes trusted content delivery networks (Bootstrap CDN, FontAwesome, Google Fonts, and Chart.js) to render optimized user interfaces and analytical charts. These services operate under their respective privacy policies.
        </p>
      </section>

      <!-- 8. Contact Information -->
      <section id="contact" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-envelope"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">8. Contact Information</h4>
            <span class="text-muted small">Inquiries & Support</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-3">
          If you have any questions, concerns, or requests regarding this Privacy Policy or how your data is handled, please reach out to our team:
        </p>
        <div class="p-3 bg-light rounded-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 border border-light-subtle">
          <div>
            <strong class="text-dark d-block mb-1">SkillBridge Data Protection & Support</strong>
            <span class="text-muted small"><i class="bi bi-envelope me-1"></i> <a href="mailto:skill.profile.project1@gmail.com" class="text-decoration-none text-primary">skill.profile.project1@gmail.com</a> &bull; <i class="bi bi-building me-1"></i> IT Department, ZCOER, Pune</span>
          </div>
          <a href="mailto:skill.bridge.project1@gmail.com" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold flex-shrink-0">
            <i class="bi bi-life-ring me-1"></i> Help Center
          </a>
        </div>
      </section>

      <!-- 9. Updates to This Policy -->
      <section id="updates" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-arrow-repeat"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">9. Updates to This Policy</h4>
            <span class="text-muted small">Revisions & Notifications</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-0">
          We may update this Privacy Policy periodically to reflect system enhancements or legal compliance. Any changes will be posted on this page with an updated "Last Updated" timestamp. We encourage users to review this page regularly.
        </p>
      </section>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
