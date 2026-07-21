<?php
/**
 * SkillBridge - Faculty Guidance & Support Center
 * Tailored documentation, assessment management guides, and faculty FAQs.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');

$facultyId = $_SESSION['profile_id'];
$db = Database::getInstance();

$faculty = $db->fetch("SELECT * FROM faculty WHERE id = ?", [$facultyId]);
$facultyName = htmlspecialchars($faculty['first_name'] ?? 'Faculty Member');

$pageTitle = "Faculty Support Center - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="dash-content">
  <!-- HERO SEARCH BANNER -->
  <div class="saas-hero-banner mb-4">
    <div class="position-relative z-1 max-w-700">
      <span class="badge rounded-pill px-3 mb-2 small fw-semibold" style="background: rgba(255,255,255,0.18); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
        <i class="fa-solid fa-life-ring me-1"></i> Faculty Knowledge Portal
      </span>
      <h2 class="fw-bold display-6 mb-2">Welcome to Faculty Support, Prof. <?= $facultyName ?></h2>
      <p class="mb-4" style="color: rgba(255,255,255,0.75);">Search documentation on assessment management, question bank setup, student monitoring, and analytics.</p>
      
      <div class="position-relative">
        <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
        <input type="text" id="facultyHelpSearchInput" class="form-control form-control-lg rounded-pill ps-5 pe-4 border-0 shadow" 
               style="background: rgba(255,255,255,0.95); color: #1F2937;"
               placeholder="Search faculty topics (e.g. create assessment, question bank, student analytics)..." onkeyup="filterFacultyTopics()">
      </div>
    </div>
  </div>

  <!-- FACULTY HELP CATEGORIES GRID -->
  <div class="row g-4 mb-5" id="facultyCategoriesGrid">
    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="saas-card p-4 h-100">
        <div class="stat-icon primary mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
          <i class="fa-solid fa-gauge-high"></i>
        </div>
        <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Faculty Dashboard</h5>
        <p class="text-muted small mb-3">Monitor department cohorts, active quizzes, and overall student performance overview.</p>
        <a href="#faq-section" onclick="filterFacultyFAQ('dashboard')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon success mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
          <i class="fa-solid fa-clipboard-check"></i>
        </div>
        <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Assessment Management</h5>
        <p class="text-muted small mb-3">Create new skill assessments, configure difficulty tiers, time limits, and passing criteria.</p>
        <a href="#faq-section" onclick="filterFacultyFAQ('assessments')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon warning mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
          <i class="fa-solid fa-question-circle"></i>
        </div>
        <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Question Bank</h5>
        <p class="text-muted small mb-3">Add multiple-choice questions, set correct options, and tag questions to specific technical skills.</p>
        <a href="#faq-section" onclick="filterFacultyFAQ('questions')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon accent mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
          <i class="fa-solid fa-users"></i>
        </div>
        <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Student Monitoring</h5>
        <p class="text-muted small mb-3">Review individual student attempt histories, test scores, and learning progress.</p>
        <a href="#faq-section" onclick="filterFacultyFAQ('students')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon info mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center; background: rgba(6, 182, 212, 0.15); color: #0891B2;">
          <i class="fa-solid fa-chart-pie"></i>
        </div>
        <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Skill Analytics</h5>
        <p class="text-muted small mb-3">Analyze department-wide skill gap distributions to tailor course curriculum.</p>
        <a href="#faq-section" onclick="filterFacultyFAQ('analytics')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 help-card-item">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift transition-all">
        <div class="stat-icon danger mb-3 fs-3" style="width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center; background: rgba(239, 68, 68, 0.15); color: #EF4444;">
          <i class="fa-solid fa-file-invoice"></i>
        </div>
        <h5 class="fw-bold mb-2" style="color: var(--text-heading);">Performance Reports</h5>
        <p class="text-muted small mb-3">Generate cohort summaries and track student skill mastery over time.</p>
        <a href="#faq-section" onclick="filterFacultyFAQ('reports')" class="text-primary fw-semibold small text-decoration-none">
          Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- FREQUENTLY ASKED QUESTIONS SECTION -->
  <div class="saas-card p-4 p-md-5 mb-5" id="faq-section">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-heading);">Faculty Frequently Asked Questions</h3>
        <p class="small mb-0" style="color: var(--text-muted);">Answers to common faculty management, assessment creation, and grading workflows.</p>
      </div>
      <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-primary active rounded-pill px-3" onclick="filterFacultyFAQ('all')">All Faculty FAQs</button>
      </div>
    </div>

    <div class="accordion accordion-flush" id="facultyFaqAccordion">
      <!-- FAQ 1 -->
      <div class="accordion-item border-bottom py-2 faculty-faq-item" data-category="assessments">
        <h2 class="accordion-header" id="fHeadingOne">
          <button class="accordion-button collapsed fw-semibold bg-transparent" style="color: var(--text-heading);" type="button" data-bs-toggle="collapse" data-bs-target="#fCollapseOne">
            <i class="fa-solid fa-plus-circle text-primary me-2"></i> How do I create a new assessment for my students?
          </button>
        </h2>
        <div id="fCollapseOne" class="accordion-collapse collapse" data-bs-parent="#facultyFaqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Navigate to <strong>Assessments</strong> in the sidebar and click <strong>Create Assessment</strong>. Specify the title, target skill, difficulty level (Beginner to Expert), passing percentage, and total time limit.
          </div>
        </div>
      </div>

      <!-- FAQ 2 -->
      <div class="accordion-item border-bottom py-2 faculty-faq-item" data-category="questions">
        <h2 class="accordion-header" id="fHeadingTwo">
          <button class="accordion-button collapsed fw-semibold bg-transparent" style="color: var(--text-heading);" type="button" data-bs-toggle="collapse" data-bs-target="#fCollapseTwo">
            <i class="fa-solid fa-list-check text-success me-2"></i> How does the Question Bank assign questions to assessments?
          </button>
        </h2>
        <div id="fCollapseTwo" class="accordion-collapse collapse" data-bs-parent="#facultyFaqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Questions added to the <strong>Question Bank</strong> are tagged by skill and difficulty level. When a student starts an assessment, the system dynamically pulls 25 questions matching that assessment's specific skill and difficulty level.
          </div>
        </div>
      </div>

      <!-- FAQ 3 -->
      <div class="accordion-item border-bottom py-2 faculty-faq-item" data-category="analytics">
        <h2 class="accordion-header" id="fHeadingThree">
          <button class="accordion-button collapsed fw-semibold bg-transparent" style="color: var(--text-heading);" type="button" data-bs-toggle="collapse" data-bs-target="#fCollapseThree">
            <i class="fa-solid fa-chart-line text-warning me-2"></i> How can I view department-wide skill gap analytics?
          </button>
        </h2>
        <div id="fCollapseThree" class="accordion-collapse collapse" data-bs-parent="#facultyFaqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Open <strong>Skill Analytics</strong> in your sidebar to view aggregated proficiency distributions across all enrolled students in your department.
          </div>
        </div>
      </div>

      <!-- FAQ 4 -->
      <div class="accordion-item border-bottom py-2 faculty-faq-item" data-category="students">
        <h2 class="accordion-header" id="fHeadingFour">
          <button class="accordion-button collapsed fw-semibold bg-transparent" style="color: var(--text-heading);" type="button" data-bs-toggle="collapse" data-bs-target="#fCollapseFour">
            <i class="fa-solid fa-user-check text-info me-2"></i> How do I inspect an individual student's score history?
          </button>
        </h2>
        <div id="fCollapseFour" class="accordion-collapse collapse" data-bs-parent="#facultyFaqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Go to <strong>Students</strong> in the navigation sidebar. Click on any student's name to view their complete assessment attempts, passed levels, and skill gap report.
          </div>
        </div>
      </div>

      <!-- FAQ 5 -->
      <div class="accordion-item py-2 faculty-faq-item" data-category="dashboard">
        <h2 class="accordion-header" id="fHeadingFive">
          <button class="accordion-button collapsed fw-semibold bg-transparent" style="color: var(--text-heading);" type="button" data-bs-toggle="collapse" data-bs-target="#fCollapseFive">
            <i class="fa-solid fa-shield-halved text-secondary me-2"></i> Can I edit or delete an assessment after it has been published?
          </button>
        </h2>
        <div id="fCollapseFive" class="accordion-collapse collapse" data-bs-parent="#facultyFaqAccordion">
          <div class="accordion-body text-muted small leading-relaxed">
            Yes. On the <strong>Assessments</strong> list, click the <em>Edit</em> icon to update assessment titles, time limits, or active status.
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CONTACT SUPPORT CTA BANNER -->
  <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-light text-center">
    <div class="max-w-600 mx-auto">
      <div class="fs-1 text-primary mb-3"><i class="fa-solid fa-headset"></i></div>
      <h4 class="fw-bold text-dark mb-2">Faculty Support & Systems Helpdesk</h4>
      <p class="text-muted small mb-4">Contact our IT administration team for assistance with assessment permissions, question uploads, or student record updates.</p>
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="mailto:faculty-support@skillbridge.edu" class="btn btn-primary rounded-pill px-4 py-2 small fw-semibold">
          <i class="fa-solid fa-envelope me-1"></i> Contact IT Support
        </a>
        <a href="<?= BASE_URL ?>faculty/dashboard.php" class="btn btn-outline-secondary rounded-pill px-4 py-2 small fw-semibold">
          <i class="fa-solid fa-gauge-high me-1"></i> Faculty Dashboard
        </a>
      </div>
    </div>
  </div>
</div>

<script>
function filterFacultyTopics() {
    const q = document.getElementById('facultyHelpSearchInput').value.toLowerCase();
    const faqItems = document.querySelectorAll('.faculty-faq-item');
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

function filterFacultyFAQ(cat) {
    const faqItems = document.querySelectorAll('.faculty-faq-item');
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
