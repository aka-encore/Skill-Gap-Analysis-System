<?php
/**
 * SkillBridge - Terms of Service Page
 * Skill Gap Analysis & Learning Management System
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "Terms of Service - SkillBridge";
include __DIR__ . '/includes/header.php';
?>

<style>
/* ── Terms of Service — Page-Scoped Hero Overrides ── */
.tos-hero {
    background: linear-gradient(135deg, #021024 0%, #073460 40%, #26658C 80%, #14B8A6 100%);
    border-radius: 24px;
    color: #FFFFFF;
    padding: 4rem 3rem;
    box-shadow: 0 20px 60px rgba(2, 16, 36, 0.25), 0 4px 16px rgba(38, 101, 140, 0.15);
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
}
/* Decorative orb top-right */
.tos-hero::before {
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
.tos-hero::after {
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
.tos-hero-inner {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 2.5rem;
}
.tos-hero-icon-wrap {
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
.tos-hero-badge {
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
.tos-hero h1 {
    font-size: clamp(1.9rem, 4vw, 2.75rem);
    font-weight: 800;
    color: #FFFFFF;
    line-height: 1.15;
    margin-bottom: 0.65rem;
    letter-spacing: -0.02em;
    text-shadow: 0 2px 12px rgba(0, 0, 0, 0.25);
}
.tos-hero-subtitle {
    font-size: 1.05rem;
    color: rgba(255, 255, 255, 0.75);
    line-height: 1.6;
    margin-bottom: 1.2rem;
    max-width: 560px;
}
.tos-hero-meta {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex-wrap: wrap;
    font-size: 0.83rem;
    color: rgba(255, 255, 255, 0.55);
}
.tos-hero-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}
.tos-hero-divider {
    width: 1px;
    height: 14px;
    background: rgba(255, 255, 255, 0.25);
}
/* Dark theme hero stays consistent */
[data-theme="dark"] .tos-hero {
    background: linear-gradient(135deg, #0D0D1A 0%, #1A1040 40%, #2D1A6B 80%, #3B82A0 100%);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 4px 16px rgba(139, 92, 246, 0.12);
}
[data-theme="dark"] .tos-hero::before {
    background: radial-gradient(circle, rgba(139, 92, 246, 0.20) 0%, transparent 68%);
}
[data-theme="dark"] .tos-hero::after {
    background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
}
[data-theme="dark"] .tos-hero-icon-wrap {
    background: rgba(139, 92, 246, 0.15);
    border-color: rgba(139, 92, 246, 0.30);
    color: #C4B5FD;
}
[data-theme="dark"] .tos-hero-badge {
    background: rgba(139, 92, 246, 0.15);
    border-color: rgba(139, 92, 246, 0.30);
    color: #C4B5FD;
}
@media (max-width: 640px) {
    .tos-hero { padding: 2.5rem 1.5rem; }
    .tos-hero-inner { flex-direction: column; align-items: flex-start; gap: 1.25rem; }
    .tos-hero-icon-wrap { width: 60px; height: 60px; font-size: 1.5rem; border-radius: 16px; }
}
</style>

<div class="dash-content pb-5">
  <!-- HERO BANNER -->
  <div class="tos-hero">
    <div class="tos-hero-inner">
      <!-- Icon orb -->
      <div class="tos-hero-icon-wrap">
        <i class="bi bi-file-earmark-text"></i>
      </div>
      <!-- Text content -->
      <div>
        <div class="tos-hero-badge">
          <i class="bi bi-shield-check"></i> User Agreement &amp; Terms
        </div>
        <h1>Terms of Service</h1>
        <p class="tos-hero-subtitle">
          Rules, guidelines, user responsibilities, and terms governing your use of SkillBridge Skill Gap Analysis &amp; LMS.
        </p>
        <div class="tos-hero-meta">
          <span><i class="bi bi-calendar3"></i> Last Updated: July 29, 2026</span>
          <span class="tos-hero-divider"></span>
          <span><i class="bi bi-clock"></i> 6 min read</span>
        </div>
      </div>
    </div>
  </div>

  <!-- MAIN CONTENT CONTAINER -->
  <div class="row g-4">
    <!-- TERMS SECTIONS -->
    <div class="col-12">
      <!-- 1. Acceptance of Terms -->
      <section id="acceptance" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-check-circle"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">1. Acceptance of Terms</h4>
            <span class="text-muted small">Binding Agreement</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-3">
          By registering, accessing, or using the <strong>SkillBridge</strong> platform, you confirm that you have read, understood, and agree to be bound by these Terms of Service (“Terms”) and our <a href="<?= BASE_URL ?>privacy-policy.php" class="text-primary text-decoration-none font-semibold">Privacy Policy</a>.
        </p>
        <p class="text-secondary leading-relaxed mb-0">
          If you do not agree with any part of these Terms, you must immediately discontinue your use of SkillBridge services. These terms apply to all registered students, faculty members, academic administrators, and guests.
        </p>
      </section>

      <!-- 2. User Responsibilities -->
      <section id="responsibilities" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-person-badge"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">2. User Responsibilities</h4>
            <span class="text-muted small">Account Conduct & Credentials</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-3">
          As a user of SkillBridge, you agree to fulfill the following account responsibilities:
        </p>
        <ul class="text-secondary mb-0 ps-3">
          <li class="mb-2"><strong>Accurate Information:</strong> Provide true, accurate, and current information during account registration and profile maintenance.</li>
          <li class="mb-2"><strong>Credential Security:</strong> Safeguard your password and login credentials. You are solely responsible for any activity conducted under your account.</li>
          <li class="mb-2"><strong>Academic Integrity:</strong> Submit genuine responses during assessments and evaluations without unauthorized assistance or automated scripts.</li>
        </ul>
      </section>

      <!-- 3. Acceptable Use -->
      <section id="acceptable-use" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-shield-x"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">3. Acceptable Use Policy</h4>
            <span class="text-muted small">Prohibited Actions & System Rules</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-3">
          When using SkillBridge, you must refrain from engaging in any of the following prohibited behaviors:
        </p>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
              <h6 class="fw-bold text-danger mb-1"><i class="bi bi-x-circle me-1"></i> Unauthorized Access</h6>
              <p class="text-secondary small mb-0">Attempting to bypass authentication, probe vulnerabilities, or escalate user privileges.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
              <h6 class="fw-bold text-danger mb-1"><i class="bi bi-bug me-1"></i> System Disruption</h6>
              <p class="text-secondary small mb-0">Injecting malicious code, SQL injection scripts, or automated bots into assessment forms.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- 4. Intellectual Property -->
      <section id="ip-rights" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-lightbulb"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">4. Intellectual Property</h4>
            <span class="text-muted small">Ownership & Course Materials</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-0">
          All platform software, algorithms, question banks, learning roadmap graphics, logos, and UI designs are the exclusive property of <strong>SkillBridge</strong> and its licensors. Users receive a limited, non-exclusive, non-transferable license to access learning content solely for educational purposes.
        </p>
      </section>

      <!-- 5. Privacy -->
      <section id="privacy" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-shield-lock"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">5. Privacy & Data Handling</h4>
            <span class="text-muted small">Data Usage Alignment</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-0">
          Your personal data and skill assessment scores are collected and processed in accordance with our <a href="<?= BASE_URL ?>privacy-policy.php" class="text-primary text-decoration-none fw-semibold">Privacy Policy</a>. We do not sell or monetize personal student data to commercial third parties.
        </p>
      </section>

      <!-- 6. Limitation of Liability -->
      <section id="liability" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-exclamation-triangle"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">6. Limitation of Liability</h4>
            <span class="text-muted small">Platform Availability & Warranty</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-0">
          SkillBridge is provided on an "as is" and "as available" basis. While we strive for 99.9% uptime, we do not warrant that service will be uninterrupted or error-free. SkillBridge is not liable for indirect, incidental, or consequential damages arising from system maintenance, connectivity issues, or lost assessment data.
        </p>
      </section>

      <!-- 7. Account Suspension -->
      <section id="suspension" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-slash-circle"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">7. Account Suspension & Termination</h4>
            <span class="text-muted small">Policy Enforcement</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-0">
          SkillBridge reserves the right to suspend or terminate user access without prior notice if a user breaches these Terms, engages in academic dishonesty during official assessments, or attempts to disrupt system security.
        </p>
      </section>

      <!-- 8. Modifications -->
      <section id="modifications" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-pencil-square"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">8. Modifications to Terms</h4>
            <span class="text-muted small">Updates & Amendments</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-0">
          We reserve the right to revise or update these Terms at any time. Material updates will be communicated through system notifications or platform announcements. Continued use of SkillBridge after modifications constitutes acceptance of the revised Terms.
        </p>
      </section>

      <!-- 9. Contact Information -->
      <section id="contact" class="legal-card-section">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="legal-section-icon">
            <i class="bi bi-envelope"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-0">9. Contact Information</h4>
            <span class="text-muted small">Questions & Clarifications</span>
          </div>
        </div>
        <p class="text-secondary leading-relaxed mb-3">
          If you have any questions regarding these Terms of Service or need assistance with your account, please contact our support team:
        </p>
        <div class="p-3 bg-light rounded-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 border border-light-subtle">
          <div>
            <strong class="text-dark d-block mb-1">SkillBridge Legal & Administrative Support</strong>
            <span class="text-muted small"><i class="bi bi-envelope me-1"></i> <a href="mailto:skill.profile.project1@gmail.com" class="text-decoration-none text-primary">skill.profile.project1@gmail.com</a> &bull; <i class="bi bi-building me-1"></i> IT Department, ZCOER, Pune</span>
          </div>
          <a href="mailto:skill.bridge.project1@gmail.com" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold flex-shrink-0">
            <i class="bi bi-life-ring me-1"></i> Help Center
          </a>
        </div>
      </section>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
