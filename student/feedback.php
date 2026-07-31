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

// Initialize form persistence variables
$recipientType = '';
$subject = '';
$category = '';
$rating = 0;
$message = '';

// Handle Feedback Form Submit
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['submit_feedback'])) {
    if (!verify_csrf_token()) {
        set_flash_message('danger', 'Invalid CSRF security token. Please try again.');
        redirect(BASE_URL . 'student/feedback.php');
    }
    $recipientType = trim($_POST['recipient_type'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $rating   = isset($_POST['rating']) && $_POST['rating'] !== '' ? (int)$_POST['rating'] : 0;
    $message  = trim($_POST['message'] ?? '');

    if (empty($recipientType)) {
        set_flash_message('danger', 'Please select a feedback recipient.');
    } elseif (empty($category)) {
        set_flash_message('danger', 'Please select a feedback category.');
    } elseif ($rating < 1 || $rating > 5) {
        set_flash_message('danger', 'Please select a rating before submitting your feedback.');
    } elseif (empty($message)) {
        set_flash_message('danger', 'Please write your feedback message before submitting.');
    } else {
        if ($recipientType === 'admin') {
            // Save to database
            $db->query(
                "INSERT INTO feedback (user_id, student_id, user_role, category, recipient_type, rating, subject, message, status, read_status) 
                 VALUES (?, ?, 'student', ?, 'admin', ?, ?, ?, 'Email Sent', 'read')",
                [$userId, $studentId, $category, $rating, $subject, $message]
            );

            // Send via SMTP
            $userEmailAddr = $student['email'] ?? '';
            require_once __DIR__ . '/../config/mail.php';
            $feedbackMailRes = send_feedback_email('student', $studentName, $userEmailAddr, $category, $rating, $message);

            if ($feedbackMailRes['success']) {
                set_flash_message('success', 'Thank you! Your feedback has been sent successfully to the administrator.');
            } else {
                set_flash_message('warning', 'Feedback saved to database, but email notification failed: ' . $feedbackMailRes['message']);
            }
            redirect(BASE_URL . 'student/feedback.php');
        } elseif ($recipientType === 'faculty') {
            // Save to database
            $db->query(
                "INSERT INTO feedback (user_id, student_id, user_role, category, recipient_type, rating, subject, message, status, read_status) 
                 VALUES (?, ?, 'student', ?, 'faculty', ?, ?, ?, 'New', 'unread')",
                [$userId, $studentId, $category, $rating, $subject, $message]
            );

            // Generate in-app notification for all active faculty members in the department
            $facultyList = $db->fetchAll(
                "SELECT f.user_id 
                 FROM faculty f
                 JOIN users u ON f.user_id = u.id
                 WHERE f.department = ? AND f.approval_status = 'approved' AND u.status != 'suspended'",
                [$student['department']]
            );

            foreach ($facultyList as $fac) {
                $db->query(
                    "INSERT INTO notifications (user_id, title, message, link, type, created_by_user_id, created_by_role) 
                     VALUES (?, 'New Student Feedback', ?, '#', 'feedback', ?, 'student')",
                    [
                        $fac['user_id'],
                        "New feedback submitted by student {$studentName} in department {$student['department']}.",
                        $userId
                    ]
                );
            }

            set_flash_message('success', 'Thank you! Your feedback has been submitted successfully to the faculty of your department.');
            redirect(BASE_URL . 'student/feedback.php');
        }
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
        <?= csrf_field() ?>
        <input type="hidden" name="submit_feedback" value="1">
        <input type="hidden" name="rating" id="ratingInput" value="<?= htmlspecialchars($rating ?: '') ?>">

        <!-- RECIPIENT SELECTION -->
        <div class="mb-4">
          <label class="form-label small fw-semibold text-muted">SEND FEEDBACK TO <span class="text-danger">*</span></label>
          <select name="recipient_type" class="form-select rounded-3" required id="recipientSelect">
            <option value="" disabled <?= $recipientType === '' ? 'selected' : '' ?>>-- Select a recipient --</option>
            <option value="admin" <?= $recipientType === 'admin' ? 'selected' : '' ?>>Administrator</option>
            <option value="faculty" <?= $recipientType === 'faculty' ? 'selected' : '' ?>>Faculty</option>
          </select>
        </div>

        <!-- SUBJECT -->
        <div class="mb-4">
          <label class="form-label small fw-semibold text-muted">SUBJECT (OPTIONAL)</label>
          <input type="text" name="subject" class="form-control rounded-3" placeholder="Enter a brief subject for your feedback..." value="<?= htmlspecialchars($subject) ?>">
        </div>

        <!-- CATEGORY SELECTION -->
        <div class="mb-4">
          <label class="form-label small fw-semibold text-muted">FEEDBACK CATEGORY <span class="text-danger">*</span></label>
          <select name="category" class="form-select rounded-3" required>
            <option value="" disabled <?= $category === '' ? 'selected' : '' ?>>-- Select a category --</option>
            <option value="General Feedback" <?= $category === 'General Feedback' ? 'selected' : '' ?>>General Feedback</option>
            <option value="Skill Assessments" <?= $category === 'Skill Assessments' ? 'selected' : '' ?>>Skill Assessments & Quizzes</option>
            <option value="Skill Gap Analysis" <?= $category === 'Skill Gap Analysis' ? 'selected' : '' ?>>Skill Gap Analysis & Recommendations</option>
            <option value="Personalized Roadmap" <?= $category === 'Personalized Roadmap' ? 'selected' : '' ?>>Personalized Career Roadmap</option>
            <option value="Progress Tracking" <?= $category === 'Progress Tracking' ? 'selected' : '' ?>>Progress Tracking & Leaderboard</option>
            <option value="Dashboard" <?= $category === 'Dashboard' ? 'selected' : '' ?>>Dashboard UI & Navigation</option>
            <option value="Notifications" <?= $category === 'Notifications' ? 'selected' : '' ?>>Notifications & Alerts</option>
            <option value="User Interface" <?= $category === 'User Interface' ? 'selected' : '' ?>>User Interface & Theme</option>
            <option value="Bug Report" <?= $category === 'Bug Report' ? 'selected' : '' ?>>Bug Report</option>
            <option value="Feature Request" <?= $category === 'Feature Request' ? 'selected' : '' ?>>Feature Request</option>
            <option value="Other" <?= $category === 'Other' ? 'selected' : '' ?>>Other</option>
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
          <textarea name="message" rows="5" class="form-control rounded-3" placeholder="Write your detailed feedback, ideas, or bug details here..." required><?= htmlspecialchars($message) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2.5 fw-semibold small">
          <i class="fa-solid fa-paper-plane me-1"></i> Submit Feedback
        </button>
      </form>
    </div>

    <!-- FEEDBACK HISTORY CARD -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mt-4" id="feedback-history-section">
      <h3 class="fw-bold fs-5 text-dark mb-3"><i class="fa-solid fa-clock-rotate-left text-secondary me-2"></i>Feedback History</h3>
      <?php
      $history = $db->fetchAll(
          "SELECT recipient_type, rating, created_at, status, subject, message
           FROM feedback 
           WHERE user_id = ? 
           ORDER BY created_at DESC",
          [$userId]
      );
      if (empty($history)):
      ?>
        <p class="text-muted small mb-0">No feedback submitted yet.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table align-middle table-hover small mb-0">
            <thead>
              <tr class="table-light">
                <th>Recipient</th>
                <th>Subject / Comments</th>
                <th>Rating</th>
                <th>Date Submitted</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($history as $h): 
                  $recLabel = $h['recipient_type'] === 'faculty' ? 'Faculty' : 'Administrator';
                  $statusVal = $h['status'];
                  $statusBadge = 'bg-secondary';
                  if ($statusVal === 'Email Sent') {
                      $statusBadge = 'bg-success text-white';
                      $statusText = '✓ Email Sent';
                  } elseif ($statusVal === 'New') {
                      $statusBadge = 'bg-primary text-white';
                      $statusText = 'New';
                  } elseif ($statusVal === 'Read') {
                      $statusBadge = 'bg-info text-dark';
                      $statusText = 'Read';
                  } elseif ($statusVal === 'Resolved') {
                      $statusBadge = 'bg-success text-white';
                      $statusText = 'Resolved';
                  } else {
                      $statusText = $statusVal;
                  }
              ?>
                <tr>
                  <td class="fw-semibold text-dark"><?= $recLabel ?></td>
                  <td>
                    <div class="fw-semibold text-dark"><?= htmlspecialchars($h['subject'] ?: 'No Subject') ?></div>
                    <div class="text-muted" style="font-size: 11px; max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars($h['message'] ?? '') ?></div>
                  </td>
                  <td>
                    <div class="text-warning">
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fa-<?= $i <= $h['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
                      <?php endfor; ?>
                    </div>
                  </td>
                  <td class="text-muted"><?= date('M d, Y h:i A', strtotime($h['created_at'])) ?></td>
                  <td>
                    <span class="badge <?= $statusBadge ?> rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 10px;"><?= $statusText ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
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
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
