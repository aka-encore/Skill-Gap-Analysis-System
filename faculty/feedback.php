<?php
/**
 * SkillBridge - Faculty Feedback Management Portal
 * Allows faculty members to view and action student feedback submitted for their department.
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
$facultyDept = $faculty['department'] ?? '';
$facultyName = htmlspecialchars(($faculty['first_name'] ?? 'Faculty') . ' ' . ($faculty['last_name'] ?? 'Member'));

// Handle feedback action submits
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $feedbackId = (int)($_POST['feedback_id'] ?? 0);

    if ($feedbackId > 0) {
        // Security check: verify feedback belongs to a student of the faculty's department
        $checkFb = $db->fetch(
            "SELECT f.*, s.department 
             FROM feedback f 
             JOIN students s ON f.student_id = s.id 
             WHERE f.id = ?",
            [$feedbackId]
        );

        if ($checkFb && $checkFb['department'] === $facultyDept) {
            if ($action === 'mark_read') {
                $db->query("UPDATE feedback SET read_status = 'read', status = 'Read' WHERE id = ?", [$feedbackId]);
                set_flash_message('success', 'Feedback marked as read.');
            } elseif ($action === 'mark_resolved') {
                $db->query("UPDATE feedback SET status = 'Resolved', read_status = 'read' WHERE id = ?", [$feedbackId]);
                set_flash_message('success', 'Feedback marked as resolved.');
            }
            redirect(BASE_URL . 'faculty/feedback.php');
        } else {
            set_flash_message('danger', 'Unauthorized action.');
        }
    }
}

// Fetch query filters
$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$ratingFilter = trim($_GET['rating'] ?? '');
$sort = trim($_GET['sort'] ?? 'newest');

$sql = "SELECT 
            f.*, 
            s.first_name, 
            s.last_name, 
            u.username, 
            s.department
        FROM feedback f
        JOIN students s ON f.student_id = s.id
        JOIN users u ON s.user_id = u.id
        WHERE f.recipient_type = 'faculty' AND s.department = ?";

$params = [$facultyDept];

if (!empty($search)) {
    $sql .= " AND (u.username LIKE ? OR s.first_name LIKE ? OR s.last_name LIKE ? OR f.subject LIKE ? OR f.message LIKE ?)";
    $searchWild = '%' . $search . '%';
    $params = array_merge($params, [$searchWild, $searchWild, $searchWild, $searchWild, $searchWild]);
}

if (!empty($statusFilter)) {
    $sql .= " AND f.status = ?";
    $params[] = $statusFilter;
}

if (!empty($ratingFilter)) {
    $sql .= " AND f.rating = ?";
    $params[] = (int)$ratingFilter;
}

if ($sort === 'oldest') {
    $sql .= " ORDER BY f.created_at ASC";
} elseif ($sort === 'rating_desc') {
    $sql .= " ORDER BY f.rating DESC, f.created_at DESC";
} elseif ($sort === 'rating_asc') {
    $sql .= " ORDER BY f.rating ASC, f.created_at DESC";
} else {
    $sql .= " ORDER BY f.created_at DESC"; // Default: newest
}

$feedbacks = $db->fetchAll($sql, $params);

$pageTitle = "Student Feedback - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<div class="dash-content">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
      <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-comments text-teal me-2" style="color: #14B8A6;"></i>Student Feedback Portal</h2>
      <p class="text-muted small mb-0">Manage feedback submissions from students enrolled in the <strong><?= htmlspecialchars($facultyDept) ?></strong> department.</p>
    </div>
  </div>

  <!-- FILTERS & SEARCH ROW -->
  <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
    <form action="<?= BASE_URL ?>faculty/feedback.php" method="GET" class="row g-3 align-items-center">
      <!-- Search Input -->
      <div class="col-12 col-md-4">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
          <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search by student, message..." value="<?= htmlspecialchars($search) ?>">
        </div>
      </div>
      <!-- Status Filter -->
      <div class="col-6 col-md-2">
        <select name="status" class="form-select form-select-sm bg-light">
          <option value="">All Statuses</option>
          <option value="New" <?= $statusFilter === 'New' ? 'selected' : '' ?>>New</option>
          <option value="Read" <?= $statusFilter === 'Read' ? 'selected' : '' ?>>Read</option>
          <option value="Resolved" <?= $statusFilter === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
        </select>
      </div>
      <!-- Rating Filter -->
      <div class="col-6 col-md-2">
        <select name="rating" class="form-select form-select-sm bg-light">
          <option value="">All Ratings</option>
          <?php for($r = 5; $r >= 1; $r--): ?>
            <option value="<?= $r ?>" <?= $ratingFilter === (string)$r ? 'selected' : '' ?>><?= $r ?> Stars</option>
          <?php endfor; ?>
        </select>
      </div>
      <!-- Sorting -->
      <div class="col-6 col-md-2">
        <select name="sort" class="form-select form-select-sm bg-light">
          <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
          <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
          <option value="rating_desc" <?= $sort === 'rating_desc' ? 'selected' : '' ?>>Highest Rated</option>
          <option value="rating_asc" <?= $sort === 'rating_asc' ? 'selected' : '' ?>>Lowest Rated</option>
        </select>
      </div>
      <!-- Actions -->
      <div class="col-6 col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-teal text-white btn-sm w-100 fw-semibold" style="background: #14B8A6; border: none;">Filter</button>
        <a href="<?= BASE_URL ?>faculty/feedback.php" class="btn btn-outline-secondary btn-sm w-100 fw-semibold">Clear</a>
      </div>
    </form>
  </div>

  <!-- FEEDBACK ITEMS -->
  <div class="card border-0 shadow-sm rounded-4 p-0 bg-white overflow-hidden">
    <?php if (empty($feedbacks)): ?>
      <div class="text-center py-5 px-3">
        <span style="font-size: 3rem;" class="mb-2 d-block">💬</span>
        <h5 class="fw-bold mb-1" style="color: var(--text-heading);">No Feedback Submissions Found</h5>
        <p class="text-muted small mb-0">There are no matching feedback entries submitted by students in your department.</p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table align-middle table-hover mb-0 small">
          <thead>
            <tr class="table-light">
              <th style="width: 20%;" class="ps-4">Student</th>
              <th style="width: 15%;">Rating</th>
              <th style="width: 40%;">Subject & Detailed Feedback</th>
              <th style="width: 15%;">Submitted</th>
              <th style="width: 10%;" class="pe-4 text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($feedbacks as $fb): 
                $studentName = htmlspecialchars($fb['first_name'] . ' ' . $fb['last_name']);
                $initials = strtoupper(substr($fb['first_name'], 0, 1) . substr($fb['last_name'], 0, 1));
                
                // Badges
                $statusVal = $fb['status'];
                $statusBadge = 'bg-secondary';
                if ($statusVal === 'New') {
                    $statusBadge = 'bg-primary text-white';
                } elseif ($statusVal === 'Read') {
                    $statusBadge = 'bg-info text-dark';
                } elseif ($statusVal === 'Resolved') {
                    $statusBadge = 'bg-success text-white';
                }
            ?>
              <tr>
                <td class="ps-4">
                  <div class="d-flex align-items-center gap-2.5">
                    <div class="avatar-placeholder rounded-circle bg-teal text-white d-flex align-items-center justify-content-center fw-bold small" style="width: 32px; height: 32px; font-size: 10px; background: #14B8A6;">
                      <?= $initials ?>
                    </div>
                    <div>
                      <div class="fw-semibold text-dark"><?= $studentName ?></div>
                      <div class="text-muted" style="font-size: 10px;">@<?= htmlspecialchars($fb['username']) ?></div>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="text-warning">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                      <i class="fa-<?= $i <= $fb['rating'] ? 'solid' : 'regular' ?> fa-star" style="font-size: 11px;"></i>
                    <?php endfor; ?>
                  </div>
                  <div class="text-muted mt-0.5" style="font-size: 10px;"><?= htmlspecialchars($fb['category']) ?></div>
                </td>
                <td>
                  <div class="fw-semibold text-dark"><?= htmlspecialchars($fb['subject'] ?: 'No Subject') ?></div>
                  <div class="text-secondary mt-0.5" style="font-size: 11.5px; line-height: 1.4; word-break: break-word;"><?= nl2br(htmlspecialchars($fb['message'])) ?></div>
                  <div class="mt-1">
                    <span class="badge <?= $statusBadge ?> rounded-pill px-2.5 py-0.5" style="font-size: 9px; font-weight: 600;"><?= $statusVal ?></span>
                    <?php if ($fb['read_status'] === 'unread'): ?>
                      <span class="badge bg-danger text-white rounded-pill px-2.5 py-0.5" style="font-size: 9px; font-weight: 600;">Unread</span>
                    <?php endif; ?>
                  </div>
                </td>
                <td class="text-muted">
                  <?= date('M d, Y', strtotime($fb['created_at'])) ?>
                  <div style="font-size: 10px;" class="text-muted mt-0.5"><?= date('h:i A', strtotime($fb['created_at'])) ?></div>
                </td>
                <td class="pe-4 text-end">
                  <div class="d-flex justify-content-end gap-1.5">
                    <?php if ($fb['status'] === 'New'): ?>
                      <form action="<?= BASE_URL ?>faculty/feedback.php" method="POST" class="d-inline">
                        <input type="hidden" name="feedback_id" value="<?= $fb['id'] ?>">
                        <input type="hidden" name="action" value="mark_read">
                        <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill py-1 px-2.5 fw-semibold" style="font-size: 10px;">Read</button>
                      </form>
                    <?php endif; ?>
                    <?php if ($fb['status'] !== 'Resolved'): ?>
                      <form action="<?= BASE_URL ?>faculty/feedback.php" method="POST" class="d-inline">
                        <input type="hidden" name="feedback_id" value="<?= $fb['id'] ?>">
                        <input type="hidden" name="action" value="mark_resolved">
                        <button type="submit" class="btn btn-outline-success btn-sm rounded-pill py-1 px-2.5 fw-semibold" style="font-size: 10px;">Resolve</button>
                      </form>
                    <?php else: ?>
                      <span class="text-success small fw-semibold"><i class="fa-solid fa-circle-check me-1"></i>Resolved</span>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
window.initFacultyFeedback = function() {
    console.log("Faculty feedback page loaded.");
};
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
