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
  <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #021024 0%, #26658C 100%);">
    <div class="position-relative z-1 max-w-700">
      <span class="badge bg-white-subtle text-white border border-white-subtle rounded-pill px-3 py-1.5 mb-2 small fw-semibold">
        <i class="fa-solid fa-life-ring me-1"></i> SkillBridge Knowledge Base
      </span>
      <h2 class="fw-bold display-6 mb-2">How can we help you today, <?= $studentName ?>?</h2>
      <p class="text-white-50 mb-4">Search guides, assessment rules, roadmap workflows, and frequently asked questions.</p>
      
      <div class="position-relative">
        <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
        <input type="text" id="helpSearchInput" class="form-control form-control-lg rounded-pill ps-5 pe-4 bg-white border-0 shadow" 
               placeholder="Search topics (e.g. assessments, skill percentage, roadmap, leaderboard)..." onkeyup="filterHelpTopics()">
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
      <p class="text-muted small mb-4">Our support team and academic faculty are here to assist you with any platform issues or guidance.</p>
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="mailto:support@skillbridge.edu" class="btn btn-primary rounded-pill px-4 py-2 small fw-semibold">
          <i class="fa-solid fa-envelope me-1"></i> Contact Support
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
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
