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

<div class="dash-content pb-5">
  <!-- HERO BANNER -->
  <div class="legal-hero-card mb-4">
    <div class="position-relative z-1 max-w-800">
      <span class="badge bg-white-subtle text-white border border-white-subtle rounded-pill px-3 py-1.5 mb-3 small fw-semibold">
        <i class="bi bi-file-earmark-text me-1"></i> User Agreement & Terms
      </span>
      <h1 class="fw-bold display-5 mb-2">Terms of Service</h1>
      <p class="text-white-50 fs-5 mb-3">
        Rules, guidelines, user responsibilities, and terms governing your use of SkillBridge Skill Gap Analysis & LMS.
      </p>
      <div class="d-flex align-items-center gap-3 text-white-50 small">
        <span><i class="bi bi-calendar3 me-1"></i> Last Updated: January 15, 2026</span>
        <span>&bull;</span>
        <span><i class="bi bi-clock me-1"></i> 6 min read</span>
      </div>
    </div>
  </div>

  <!-- MAIN CONTENT CONTAINER -->
  <div class="row g-4">
    <!-- STICKY TABLE OF CONTENTS -->
    <div class="col-lg-3 d-none d-lg-block">
      <div class="card border-0 shadow-sm rounded-4 p-3 legal-nav-sticky bg-white">
        <h6 class="fw-bold text-dark mb-3 px-2">On This Page</h6>
        <nav class="nav flex-column gap-1">
          <a href="#acceptance" class="legal-toc-link active"><i class="bi bi-check-circle me-2"></i> 1. Acceptance of Terms</a>
          <a href="#responsibilities" class="legal-toc-link"><i class="bi bi-person-badge me-2"></i> 2. User Responsibilities</a>
          <a href="#acceptable-use" class="legal-toc-link"><i class="bi bi-shield-x me-2"></i> 3. Acceptable Use</a>
          <a href="#ip-rights" class="legal-toc-link"><i class="bi bi-lightbulb me-2"></i> 4. Intellectual Property</a>
          <a href="#privacy" class="legal-toc-link"><i class="bi bi-shield-lock me-2"></i> 5. Privacy Policy</a>
          <a href="#liability" class="legal-toc-link"><i class="bi bi-exclamation-triangle me-2"></i> 6. Limitation of Liability</a>
          <a href="#suspension" class="legal-toc-link"><i class="bi bi-slash-circle me-2"></i> 7. Account Suspension</a>
          <a href="#modifications" class="legal-toc-link"><i class="bi bi-pencil-square me-2"></i> 8. Modifications</a>
          <a href="#contact" class="legal-toc-link"><i class="bi bi-envelope me-2"></i> 9. Contact Info</a>
        </nav>
      </div>
    </div>

    <!-- TERMS SECTIONS -->
    <div class="col-lg-9">
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
            <span class="text-muted small"><i class="bi bi-envelope me-1"></i> <a href="mailto:skill.profile.project1@gmail.com" class="text-decoration-none text-primary">skill.profile.project1@gmail.com</a> &bull; <i class="bi bi-building me-1"></i> SkillBridge Governance Office</span>
          </div>
          <a href="<?= BASE_URL ?>student/help.php" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold flex-shrink-0">
            <i class="bi bi-life-ring me-1"></i> Help Center
          </a>
        </div>
      </section>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
