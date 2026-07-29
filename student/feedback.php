<?php
/**
 * SkillBridge - Student Feedback Module
 * Database-driven feedback submission system with interactive 5-star rating.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$userId    = $_SESSION['user_id'];
$db        = Database::getInstance();

// Fetch logged-in student user details
$student = $db->fetch(
    "SELECT s.*, u.username, u.email, u.role 
     FROM students s 
     JOIN users u ON s.user_id = u.id 
     WHERE s.id = ?",
    [$studentId]
);
$studentName = htmlspecialchars(($student['first_name'] ?? 'Student') . ' ' . ($student['last_name'] ?? ''));

// Handle Feedback Form Submit
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['submit_feedback'])) {
    $category = trim($_POST['category'] ?? '');
    $rating   = isset($_POST['rating']) && $_POST['rating'] !== '' ? (int)$_POST['rating'] : 0;
    $message  = trim($_POST['message'] ?? '');

    if (empty($category)) {
        set_flash_message('danger', 'Please select a feedback category.');
    } elseif ($rating < 1 || $rating > 5) {
        set_flash_message('danger', 'Please select a rating before submitting your feedback.');
    } elseif (empty($message)) {
        set_flash_message('danger', 'Please write your feedback message before submitting.');
    } else {
        $db->query(
            "INSERT INTO feedback (user_id, user_role, category, rating, message, status) 
             VALUES (?, 'student', ?, ?, ?, 'pending')",
            [$userId, $category, $rating, $message]
        );
        $userEmailAddr = $student['email'] ?? '';
        require_once __DIR__ . '/../config/mail.php';
        $feedbackMailRes = send_feedback_email('student', $studentName, $userEmailAddr, $category, $rating, $message);

        if ($feedbackMailRes['success']) {
            set_flash_message('success', 'Thank you! Your feedback has been sent successfully.');
        } else {
            set_flash_message('warning', 'Feedback saved to database, but email notification failed: ' . $feedbackMailRes['message']);
        }
        redirect(BASE_URL . 'student/feedback.php');
    }
}

$pageTitle = "Share Your Feedback - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<style>
  .star-rating {
    display: inline-flex;
    gap: 8px;
    font-size: 1.6rem;
    color: #CBD5E1;
    cursor: pointer;
  }
  .star-rating i {
    transition: color 0.2s ease, transform 0.15s ease;
  }
  .star-rating i.active,
  .star-rating i:hover {
    color: #F59E0B;
    transform: scale(1.15);
  }
</style>

<div class="dash-content">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 max-w-4xl mx-auto">
    <div>
      <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-comments text-primary me-2"></i>Share Your Feedback</h2>
      <p class="text-muted small mb-0">Help us improve your learning experience on the SkillBridge platform.</p>
    </div>
  </div>

  <div class="max-w-4xl mx-auto">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white" id="feedback-section">
      <!-- AUTO-FILLED USER INFORMATION BADGE -->
      <div class="p-3 bg-light rounded-3 border mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
          <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px; font-size: 0.9rem;">
            <?= strtoupper(substr($student['first_name'] ?? 'S', 0, 1) . substr($student['last_name'] ?? 'U', 0, 1)) ?>
          </div>
          <div>
            <div class="fw-bold text-dark small"><?= $studentName ?></div>
            <div class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($student['email'] ?? '') ?> · <?= htmlspecialchars($student['department'] ?? 'CS') ?></div>
          </div>
        </div>
        <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1 small fw-semibold">Auto-Authenticated</span>
      </div>

      <form action="<?= BASE_URL ?>student/feedback.php" method="POST">
        <input type="hidden" name="submit_feedback" value="1">
        <input type="hidden" name="rating" id="ratingInput" value="">

        <!-- CATEGORY SELECTION -->
        <div class="mb-4">
          <label class="form-label small fw-semibold text-muted">FEEDBACK CATEGORY <span class="text-danger">*</span></label>
          <select name="category" class="form-select rounded-3" required>
            <option value="" disabled selected>-- Select a category --</option>
            <option value="General Feedback">General Feedback</option>
            <option value="Skill Assessments">Skill Assessments & Quizzes</option>
            <option value="Skill Gap Analysis">Skill Gap Analysis & Recommendations</option>
            <option value="Personalized Roadmap">Personalized Career Roadmap</option>
            <option value="Progress Tracking">Progress Tracking & Leaderboard</option>
            <option value="Dashboard">Dashboard UI & Navigation</option>
            <option value="Notifications">Notifications & Alerts</option>
            <option value="User Interface">User Interface & Theme</option>
            <option value="Bug Report">Bug Report</option>
            <option value="Feature Request">Feature Request</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <!-- RATING CONTROL -->
        <div class="mb-4">
          <label class="form-label small fw-semibold text-muted d-block">YOUR RATING <span class="text-danger">*</span></label>
          <div class="star-rating mb-1" id="starRating">
            <i class="fa-solid fa-star" data-value="1"></i>
            <i class="fa-solid fa-star" data-value="2"></i>
            <i class="fa-solid fa-star" data-value="3"></i>
            <i class="fa-solid fa-star" data-value="4"></i>
            <i class="fa-solid fa-star" data-value="5"></i>
          </div>
          <span class="text-muted ms-1 small fw-semibold" id="ratingLabel">Please select a rating</span>
          
          <div id="ratingErrorAlert" class="alert alert-danger d-none rounded-3 py-2 px-3 mt-2 mb-0 small">
              <i class="fa-solid fa-circle-exclamation me-1"></i> Please select a rating before submitting your feedback.
          </div>
        </div>

        <!-- MESSAGE TEXTAREA -->
        <div class="mb-4">
          <label class="form-label small fw-semibold text-muted">DETAILED COMMENTS <span class="text-danger">*</span></label>
          <textarea name="message" rows="5" class="form-control rounded-3" placeholder="Write your detailed feedback, ideas, or bug details here..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2.5 fw-semibold small">
          <i class="fa-solid fa-paper-plane me-1"></i> Submit Feedback
        </button>
      </form>
    </div>
  </div>
</div>

<script>
window.initFeedback = function() {
    const stars = document.querySelectorAll('#starRating i');
    const ratingInput = document.getElementById('ratingInput');
    const ratingLabel = document.getElementById('ratingLabel');

    const labels = {
        1: '1 - Poor',
        2: '2 - Fair',
        3: '3 - Good',
        4: '4 - Very Good',
        5: '5 - Excellent'
    };

    stars.forEach(star => {
        const newStar = star.cloneNode(true);
        star.parentNode.replaceChild(newStar, star);
        
        newStar.addEventListener('click', function () {
            const val = parseInt(this.getAttribute('data-value'));
            if (ratingInput) ratingInput.value = val;
            if (ratingLabel) ratingLabel.textContent = labels[val];
            
            const errorAlert = document.getElementById('ratingErrorAlert');
            if (errorAlert) errorAlert.classList.add('d-none');

            document.querySelectorAll('#starRating i').forEach(s => {
                const sVal = parseInt(s.getAttribute('data-value'));
                if (sVal <= val) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
    });

    // Render pre-selected value if present
    if (ratingInput && ratingInput.value) {
        const initialVal = parseInt(ratingInput.value);
        if (initialVal >= 1 && initialVal <= 5) {
            if (ratingLabel) ratingLabel.textContent = labels[initialVal];
            document.querySelectorAll('#starRating i').forEach(s => {
                const sVal = parseInt(s.getAttribute('data-value'));
                if (sVal <= initialVal) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        }
    }

    // Form submit validation handler
    const form = document.querySelector('#feedback-section form') || document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (ratingInput && (!ratingInput.value || ratingInput.value === '')) {
                e.preventDefault();
                const errorAlert = document.getElementById('ratingErrorAlert');
                if (errorAlert) {
                    errorAlert.classList.remove('d-none');
                    errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }
        });
    }
};

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    window.initFeedback();
} else {
    document.addEventListener('DOMContentLoaded', window.initFeedback);
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
