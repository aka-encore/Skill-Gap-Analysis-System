<?php
/**
 * SkillBridge - Faculty Feedback Module
 * Database-driven feedback submission system with interactive 5-star rating for Faculty.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');

$facultyId = $_SESSION['profile_id'];
$userId    = $_SESSION['user_id'];
$db        = Database::getInstance();

// Fetch logged-in faculty details
$faculty = $db->fetch(
    "SELECT f.*, u.username, u.email, u.role 
     FROM faculty f 
     JOIN users u ON f.user_id = u.id 
     WHERE f.id = ?",
    [$facultyId]
);
$facultyName = htmlspecialchars(($faculty['first_name'] ?? 'Faculty') . ' ' . ($faculty['last_name'] ?? 'Member'));

// Handle Feedback Form Submit
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['submit_feedback'])) {
    $category = trim($_POST['category'] ?? '');
    $rating   = (int)($_POST['rating'] ?? 5);
    $message  = trim($_POST['message'] ?? '');

    if (empty($category)) {
        set_flash_message('danger', 'Please select a feedback category.');
    } elseif ($rating < 1 || $rating > 5) {
        set_flash_message('danger', 'Please select a valid rating between 1 and 5 stars.');
    } elseif (empty($message)) {
        set_flash_message('danger', 'Please write your feedback message before submitting.');
    } else {
        $db->query(
            "INSERT INTO feedback (user_id, user_role, category, rating, message, status) 
             VALUES (?, 'faculty', ?, ?, ?, 'pending')",
            [$userId, $category, $rating, $message]
        );

        // Build mailto URL for user's default email client
        $subject = "SkillBridge Feedback - " . $category;
        $stars = str_repeat('★', $rating);
        $dateTime = date('Y-m-d H:i:s');
        $userEmailAddr = $faculty['email'] ?? '';

        $emailBody = "SkillBridge Feedback\n\n"
                   . "Name:\n" . $facultyName . "\n\n"
                   . "Email:\n" . $userEmailAddr . "\n\n"
                   . "Role:\nFaculty\n\n"
                   . "Category:\n" . $category . "\n\n"
                   . "Rating:\n" . $stars . " (" . $rating . "/5 Stars)\n\n"
                   . "Feedback:\n" . $message . "\n\n"
                   . "Submitted via SkillBridge\n"
                   . "Date:\n" . $dateTime;

        $mailtoUrl = "mailto:skill.profile.project1@gmail.com?subject=" . rawurlencode($subject) . "&body=" . rawurlencode($emailBody);

        $_SESSION['open_mailto'] = $mailtoUrl;
        set_flash_message('success', 'Thank you! Your feedback has been saved to database and your mail client opened.');
        redirect(BASE_URL . 'faculty/feedback.php');
    }
}

$pageTitle = "Faculty Feedback & Support - SkillBridge";
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
      <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-comments text-primary me-2"></i>Faculty Feedback Portal</h2>
      <p class="text-muted small mb-0">Provide feedback regarding assessment creation, student monitoring, and system tools.</p>
    </div>
  </div>

  <div class="max-w-4xl mx-auto">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
      <!-- AUTO-FILLED USER INFORMATION BADGE -->
      <div class="p-3 bg-light rounded-3 border mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
          <div class="avatar-placeholder rounded-circle bg-teal text-white d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px; font-size: 0.9rem; background: #14B8A6;">
            <?= strtoupper(substr($faculty['first_name'] ?? 'F', 0, 1) . substr($faculty['last_name'] ?? 'M', 0, 1)) ?>
          </div>
          <div>
            <div class="fw-bold text-dark small">Prof. <?= $facultyName ?></div>
            <div class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($faculty['email'] ?? '') ?> · <?= htmlspecialchars($faculty['department'] ?? 'CS') ?></div>
          </div>
        </div>
        <span class="badge bg-teal-subtle text-teal border rounded-pill px-3 py-1 small fw-semibold" style="color: #0D9488;">Faculty Session</span>
      </div>

      <form action="<?= BASE_URL ?>faculty/feedback.php" method="POST">
        <input type="hidden" name="submit_feedback" value="1">
        <input type="hidden" name="rating" id="ratingInput" value="5">

        <!-- CATEGORY SELECTION -->
        <div class="mb-4">
          <label class="form-label small fw-semibold text-muted">FEEDBACK CATEGORY <span class="text-danger">*</span></label>
          <select name="category" class="form-select rounded-3" required>
            <option value="" disabled selected>-- Select a category --</option>
            <option value="General Feedback">General Feedback</option>
            <option value="Assessment Management">Assessment Creation & Grading</option>
            <option value="Question Bank">Question Bank Management</option>
            <option value="Student Monitoring">Student Monitoring & Cohort Performance</option>
            <option value="Skill Gap Analysis">Department Skill Analytics</option>
            <option value="Faculty Dashboard">Faculty Dashboard UI</option>
            <option value="Notifications">Notifications & System Alerts</option>
            <option value="Bug Report">Bug Report</option>
            <option value="Feature Request">Feature Request</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <!-- RATING CONTROL -->
        <div class="mb-4">
          <label class="form-label small fw-semibold text-muted d-block">YOUR RATING <span class="text-danger">*</span></label>
          <div class="star-rating" id="starRating">
            <i class="fa-solid fa-star active" data-value="1"></i>
            <i class="fa-solid fa-star active" data-value="2"></i>
            <i class="fa-solid fa-star active" data-value="3"></i>
            <i class="fa-solid fa-star active" data-value="4"></i>
            <i class="fa-solid fa-star active" data-value="5"></i>
          </div>
          <span class="text-muted ms-2 small fw-semibold" id="ratingLabel">5 - Excellent</span>
        </div>

        <!-- MESSAGE TEXTAREA -->
        <div class="mb-4">
          <label class="form-label small fw-semibold text-muted">DETAILED COMMENTS <span class="text-danger">*</span></label>
          <textarea name="message" rows="5" class="form-control rounded-3" placeholder="Write your detailed feedback or feature request here..." required></textarea>
        </div>

        <button type="submit" class="btn btn-teal text-white rounded-pill px-4 py-2.5 fw-semibold small" style="background: #14B8A6; border: none;">
          <i class="fa-solid fa-paper-plane me-1"></i> Submit Faculty Feedback
        </button>
      </form>
    </div>
  </div>
</div>

<script>
// Interactive 5-Star Rating Logic
document.addEventListener('DOMContentLoaded', function () {
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
        star.addEventListener('click', function () {
            const val = parseInt(this.getAttribute('data-value'));
            ratingInput.value = val;
            ratingLabel.textContent = labels[val];

            stars.forEach(s => {
                const sVal = parseInt(s.getAttribute('data-value'));
                if (sVal <= val) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
    });
});
</script>

<?php if (isset($_SESSION['open_mailto'])): 
  $mailtoToOpen = $_SESSION['open_mailto'];
  unset($_SESSION['open_mailto']);
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.location.href = <?= json_encode($mailtoToOpen) ?>;
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
