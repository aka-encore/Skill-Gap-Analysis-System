<?php
/**
 * SkillBridge - Enterprise Faculty Assessment Inspector & Performance Analytics Dashboard
 * Premium SaaS dashboard with deep notification integration, row highlighting, and attempt detail drawer/modal.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');
check_suspended_status();

$facultyId = $_SESSION['profile_id'];
$db = Database::getInstance();

// Auto-sync assessments table
sync_assessments_table($db);

// Check if deep linked from a notification
$targetResultId = (int)($_GET['result_id'] ?? 0);
$targetStudentId = (int)($_GET['student_id'] ?? 0);

// Fetch all students for quick filtering dropdown
$students = $db->fetchAll("SELECT * FROM students ORDER BY first_name ASC");

// Fetch ALL assessment attempts across all students with complete proctoring summaries & skill metadata
$allAttempts = $db->fetchAll(
    "SELECT ar.*, 
            st.id as student_id, st.student_code, st.first_name, st.last_name, st.department, st.current_semester, u.email as student_email,
            a.title as assessment_title, a.difficulty_level,
            s.name as skill_name, s.category as skill_category,
            ps.risk_level, ps.total_violations, ps.phone_violations, ps.face_missing_violations, ps.multiple_face_violations, ps.tab_switch_violations, ps.focus_loss_violations, ps.camera_disconnect_violations
     FROM assessment_results ar
     JOIN students st ON ar.student_id = st.id
     JOIN users u ON st.user_id = u.id
     JOIN assessments a ON ar.assessment_id = a.id
     JOIN skills s ON a.skill_id = s.id
     LEFT JOIN assessment_proctoring_summaries ps ON ar.id = ps.result_id
     ORDER BY ar.completed_at DESC"
);

// Aggregate Dashboard KPI Metrics
$totalAttempts = count($allAttempts);
$passedAttempts = 0;
$failedAttempts = 0;
$totalPercentageSum = 0;
$flaggedViolationsCount = 0;
$categoriesList = [];
$skillsList = [];

foreach ($allAttempts as $att) {
    if ($att['status'] === 'pass') {
        $passedAttempts++;
    } else {
        $failedAttempts++;
    }
    $totalPercentageSum += (float)$att['score_percentage'];
    
    if ((int)($att['total_violations'] ?? 0) > 0 || in_array($att['risk_level'] ?? '', ['High Risk', 'Medium Risk'])) {
        $flaggedViolationsCount++;
    }

    if (!empty($att['skill_category'])) {
        $categoriesList[$att['skill_category']] = true;
    }
    if (!empty($att['skill_name'])) {
        $skillsList[$att['skill_name']] = true;
    }
}

$avgPercentage = $totalAttempts > 0 ? round($totalPercentageSum / $totalAttempts, 1) : 0;

$pageTitle = "Assessment Inspector & Performance Analytics - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<style>
/* SkillBridge Responsive Theme System for Assessment Inspector */
.inspector-header-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border);
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.inspector-kpi-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    height: 100%;
}
.inspector-kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.12);
    border-color: var(--primary);
}
.inspector-kpi-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

/* Modern Controls & Filters Toolbar */
.inspector-control-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border);
    border-radius: 18px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    padding: 20px;
}
.inspector-filter-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.inspector-filter-control {
    height: 44px !important;
    background-color: var(--bg-alt, #f8fafc) !important;
    border: 1px solid var(--border) !important;
    color: var(--text-body) !important;
    border-radius: 12px !important;
    font-size: 0.85rem !important;
    padding: 8px 14px !important;
    transition: all 0.2s ease !important;
    width: 100% !important;
}
.inspector-filter-control:focus {
    background-color: var(--card-bg, #ffffff) !important;
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
}

/* Modern Sticky Table */
.inspector-table-container {
    border-radius: 20px;
    border: 1px solid var(--border);
    background: var(--card-bg, #ffffff);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    overflow: hidden;
}
.inspector-table {
    margin-bottom: 0;
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.inspector-table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background: var(--bg-alt, #f8fafc);
    color: var(--text-muted);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
}
.inspector-table tbody tr {
    transition: background 0.2s ease, transform 0.15s ease;
}
.inspector-table tbody tr:hover {
    background: var(--primary-light, rgba(37, 99, 235, 0.04)) !important;
}
.inspector-table td {
    padding: 14px 16px;
    vertical-align: middle;
    font-size: 0.875rem;
    color: var(--text-body);
    border-bottom: 1px solid var(--border);
}

/* Notification Target Highlight Animation */
@keyframes notifHighlightPulse {
    0% {
        background-color: rgba(255, 193, 7, 0.35);
        box-shadow: inset 4px 0 0 #ffc107, 0 0 15px rgba(255, 193, 7, 0.4);
    }
    50% {
        background-color: rgba(37, 99, 235, 0.2);
        box-shadow: inset 4px 0 0 var(--primary), 0 0 20px rgba(37, 99, 235, 0.3);
    }
    100% {
        background-color: transparent;
        box-shadow: none;
    }
}
.target-notif-highlight {
    animation: notifHighlightPulse 4s ease-out forwards;
}

/* Action Icon Button */
.btn-action-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--card-bg, #ffffff);
    color: var(--text-muted);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
}
.btn-action-icon:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
    transform: scale(1.08);
}
</style>

<div class="dash-content">
  <!-- BREADCRUMBS NAVIGATION -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0 small fw-semibold">
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>faculty/dashboard.php" class="text-decoration-none text-muted"><i class="fa-solid fa-house me-1"></i> Faculty</a></li>
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>faculty/assessments.php" class="text-decoration-none text-muted">Assessment Dashboard</a></li>
      <li class="breadcrumb-item active text-primary" aria-current="page">Assessment Inspector</li>
    </ol>
  </nav>

  <!-- HEADER -->
  <div class="card inspector-header-card p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
      <div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-semibold small mb-2">
          <i class="fa-solid fa-shield-halved me-1"></i> Performance Diagnostics & Audit Center
        </span>
        <h3 class="fw-bold mb-1" style="color: var(--text-heading);">Faculty Assessment Inspector</h3>
        <p class="text-muted small mb-0">Inspect evaluation records, score breakdowns, attempt durations, and proctoring integrity evidence.</p>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <a href="<?= BASE_URL ?>faculty/skill-gap.php" class="btn btn-outline-primary rounded-pill px-3.5 py-2 small fw-semibold shadow-xs">
          <i class="bi bi-bar-chart-line me-1"></i> Score Analytics
        </a>
        <a href="<?= BASE_URL ?>faculty/assessments.php" class="btn btn-primary rounded-pill px-4 py-2 small fw-semibold shadow-xs">
          <i class="bi bi-journal-text me-1"></i> Assessment List
        </a>
      </div>
    </div>
  </div>

  <!-- KPI CARDS GRID -->
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-5 g-3 mb-4">
    <!-- Card 1: Total Evaluated Attempts -->
    <div class="col">
      <div class="inspector-kpi-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-muted small fw-semibold">Total Attempts</span>
          <div class="inspector-kpi-icon bg-primary-subtle text-primary">
            <i class="fa-solid fa-list-check"></i>
          </div>
        </div>
        <h3 class="fw-bold mb-0" style="color: var(--text-heading);"><?= $totalAttempts ?></h3>
        <div class="text-muted mt-1" style="font-size: 11px;">
          <i class="fa-solid fa-rotate me-1 text-primary"></i> Evaluated submissions
        </div>
      </div>
    </div>

    <!-- Card 2: Passed Attempts -->
    <div class="col">
      <div class="inspector-kpi-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-muted small fw-semibold">Passed Attempts</span>
          <div class="inspector-kpi-icon bg-success-subtle text-success">
            <i class="fa-solid fa-circle-check"></i>
          </div>
        </div>
        <h3 class="fw-bold text-success mb-0"><?= $passedAttempts ?></h3>
        <div class="text-muted mt-1" style="font-size: 11px;">
          <i class="fa-solid fa-arrow-up me-1 text-success"></i> Score &ge; Pass mark
        </div>
      </div>
    </div>

    <!-- Card 3: Failed Attempts -->
    <div class="col">
      <div class="inspector-kpi-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-muted small fw-semibold">Failed Attempts</span>
          <div class="inspector-kpi-icon bg-danger-subtle text-danger">
            <i class="fa-solid fa-circle-xmark"></i>
          </div>
        </div>
        <h3 class="fw-bold text-danger mb-0"><?= $failedAttempts ?></h3>
        <div class="text-muted mt-1" style="font-size: 11px;">
          <i class="fa-solid fa-circle-exclamation me-1 text-danger"></i> Needs remediation
        </div>
      </div>
    </div>

    <!-- Card 4: Overall Average Score -->
    <div class="col">
      <div class="inspector-kpi-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-muted small fw-semibold">Overall Mean</span>
          <div class="inspector-kpi-icon bg-info-subtle text-info">
            <i class="fa-solid fa-chart-line"></i>
          </div>
        </div>
        <h3 class="fw-bold mb-0" style="color: var(--text-heading);"><?= $avgPercentage ?>%</h3>
        <div class="text-muted mt-1" style="font-size: 11px;">
          <i class="fa-solid fa-bullseye me-1 text-info"></i> Class score average
        </div>
      </div>
    </div>

    <!-- Card 5: Flagged Proctoring Violations -->
    <div class="col">
      <div class="inspector-kpi-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-muted small fw-semibold">Flagged Risks</span>
          <div class="inspector-kpi-icon bg-warning-subtle text-warning">
            <i class="fa-solid fa-shield-cat"></i>
          </div>
        </div>
        <h3 class="fw-bold text-warning mb-0"><?= $flaggedViolationsCount ?></h3>
        <div class="text-muted mt-1" style="font-size: 11px;">
          <i class="fa-solid fa-triangle-exclamation me-1 text-warning"></i> AI integrity alerts
        </div>
      </div>
    </div>
  </div>

  <!-- CONTROL TOOLBAR: SEARCH & MULTI-FILTERS -->
  <div class="card inspector-control-card mb-4">
    <div class="row g-3">
      <!-- ROW 1: SEARCH, STUDENT, CATEGORY, SKILL -->
      <div class="col-12 col-sm-6 col-lg-3">
        <label class="inspector-filter-label" for="inspectorSearchInput">
          <i class="fa-solid fa-magnifying-glass text-primary"></i> SEARCH
        </label>
        <input type="text" id="inspectorSearchInput" class="form-control inspector-filter-control" placeholder="Search name, roll no, title..." oninput="filterInspectorTable()">
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <label class="inspector-filter-label" for="filterStudent">
          <i class="fa-solid fa-user text-primary"></i> STUDENT
        </label>
        <select id="filterStudent" class="form-select inspector-filter-control" onchange="filterInspectorTable()">
          <option value="all" selected>All Students</option>
          <?php foreach ($students as $st): ?>
            <option value="<?= $st['id'] ?>" <?= $targetStudentId == $st['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($st['first_name'] . ' ' . $st['last_name']) ?> (<?= htmlspecialchars($st['student_code']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <label class="inspector-filter-label" for="filterCategory">
          <i class="fa-solid fa-layer-group text-primary"></i> CATEGORY
        </label>
        <select id="filterCategory" class="form-select inspector-filter-control" onchange="filterInspectorTable()">
          <option value="all" selected>All Categories</option>
          <?php foreach (array_keys($categoriesList) as $catName): ?>
            <option value="<?= htmlspecialchars(strtolower($catName)) ?>"><?= htmlspecialchars($catName) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <label class="inspector-filter-label" for="filterSkill">
          <i class="fa-solid fa-code text-primary"></i> SKILL
        </label>
        <select id="filterSkill" class="form-select inspector-filter-control" onchange="filterInspectorTable()">
          <option value="all" selected>All Skills</option>
          <?php foreach (array_keys($skillsList) as $skName): ?>
            <option value="<?= htmlspecialchars(strtolower($skName)) ?>"><?= htmlspecialchars($skName) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- ROW 2: RESULT STATUS & SORT BY -->
      <div class="col-12 col-sm-6 col-lg-3">
        <label class="inspector-filter-label" for="filterStatus">
          <i class="fa-solid fa-flag text-primary"></i> RESULT
        </label>
        <select id="filterStatus" class="form-select inspector-filter-control" onchange="filterInspectorTable()">
          <option value="all" selected>All Results</option>
          <option value="pass">Pass Only</option>
          <option value="fail">Fail Only</option>
        </select>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <label class="inspector-filter-label" for="sortAttempts">
          <i class="fa-solid fa-arrow-down-wide-short text-primary"></i> SORT BY
        </label>
        <select id="sortAttempts" class="form-select inspector-filter-control" onchange="filterInspectorTable()">
          <option value="newest" selected>Newest First</option>
          <option value="oldest">Oldest First</option>
          <option value="score_desc">Highest Score</option>
          <option value="score_asc">Lowest Score</option>
          <option value="risk_desc">Highest Risk</option>
        </select>
      </div>
    </div>
  </div>

  <!-- TABLE CONTAINER CARD -->
  <div class="inspector-table-container mb-4">
    <div class="border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: var(--card-bg, #ffffff);">
      <h5 class="fw-bold mb-0" style="color: var(--text-heading);"><i class="fa-solid fa-table-list text-primary me-2"></i>Evaluated Attempt Logs</h5>
      <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-semibold" id="inspectorCountBadge">Matches: <?= $totalAttempts ?></span>
    </div>

    <div class="table-responsive">
      <table class="table inspector-table align-middle" id="inspectorTable">
        <thead>
          <tr>
            <th class="ps-4">#</th>
            <th>Student</th>
            <th>Roll Number</th>
            <th>Assessment</th>
            <th>Category</th>
            <th>Skill</th>
            <th>Difficulty</th>
            <th>Score</th>
            <th>Percentage</th>
            <th>Result</th>
            <th>Duration</th>
            <th>Proctoring Status</th>
            <th>Date</th>
            <th class="pe-4 text-end">Actions</th>
          </tr>
        </thead>
        <tbody id="inspectorTbody">
          <?php foreach ($allAttempts as $idx => $att): 
              $studentFullName = trim($att['first_name'] . ' ' . $att['last_name']);
              $scorePct = (float)$att['score_percentage'];
              $isPass = ($att['status'] === 'pass');
              $timestamp = strtotime($att['completed_at']);
              $formattedTime = date('d M Y • h:i A', $timestamp);
              
              // Duration formatting
              $seconds = (int)($att['time_taken_seconds'] ?? 0);
              $mins = floor($seconds / 60);
              $remSecs = $seconds % 60;
              $durationStr = $mins . 'm ' . $remSecs . 's';

              $diffRaw = strtolower(trim($att['difficulty_level'] ?? 'beginner'));
              $diffDisplay = ucfirst($diffRaw);

              $riskLevel = $att['risk_level'] ?? 'Low Risk';
              $riskBadge = 'bg-success-subtle text-success border border-success-subtle';
              if ($riskLevel === 'High Risk') {
                  $riskBadge = 'bg-danger-subtle text-danger border border-danger-subtle';
              } elseif ($riskLevel === 'Medium Risk') {
                  $riskBadge = 'bg-warning-subtle text-warning border border-warning-subtle';
              }
              $riskScore = match($riskLevel) { 'High Risk' => 3, 'Medium Risk' => 2, default => 1 };
              $isTargetRow = ($targetResultId > 0 && (int)$att['id'] === $targetResultId);
          ?>
            <tr class="attempt-row <?= $isTargetRow ? 'target-notif-highlight' : '' ?>"
                id="attempt-row-<?= $att['id'] ?>"
                data-result-id="<?= $att['id'] ?>"
                data-student-id="<?= $att['student_id'] ?>"
                data-student-name="<?= htmlspecialchars(strtolower($studentFullName)) ?>"
                data-student-code="<?= htmlspecialchars(strtolower($att['student_code'])) ?>"
                data-student-email="<?= htmlspecialchars($att['student_email']) ?>"
                data-student-dept="<?= htmlspecialchars($att['department']) ?>"
                data-student-sem="<?= htmlspecialchars($att['current_semester']) ?>"
                data-assessment-title="<?= htmlspecialchars(strtolower($att['assessment_title'])) ?>"
                data-category="<?= htmlspecialchars(strtolower($att['skill_category'])) ?>"
                data-skill="<?= htmlspecialchars(strtolower($att['skill_name'])) ?>"
                data-difficulty="<?= htmlspecialchars(strtolower($diffRaw)) ?>"
                data-status="<?= htmlspecialchars(strtolower($att['status'])) ?>"
                data-score="<?= $scorePct ?>"
                data-score-obtained="<?= $att['score_obtained'] ?>"
                data-total-questions="<?= $att['total_questions'] ?>"
                data-correct-answers="<?= $att['correct_answers'] ?>"
                data-wrong-answers="<?= (int)$att['total_questions'] - (int)$att['correct_answers'] ?>"
                data-duration="<?= htmlspecialchars($durationStr) ?>"
                data-risk-level="<?= htmlspecialchars($riskLevel) ?>"
                data-risk-score="<?= $riskScore ?>"
                data-total-violations="<?= (int)($att['total_violations'] ?? 0) ?>"
                data-formatted-date="<?= htmlspecialchars($formattedTime) ?>"
                data-timestamp="<?= $timestamp ?>">
              <td class="ps-4 text-muted fw-bold"><?= $idx + 1 ?></td>
              <td>
                <div class="fw-bold" style="color: var(--text-heading);"><?= htmlspecialchars($studentFullName) ?></div>
                <div class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($att['student_email']) ?></div>
              </td>
              <td>
                <span class="badge bg-light text-dark border fw-semibold"><?= htmlspecialchars($att['student_code']) ?></span>
              </td>
              <td>
                <div class="fw-semibold" style="color: var(--text-heading);"><?= htmlspecialchars($att['assessment_title']) ?></div>
              </td>
              <td>
                <span class="badge bg-secondary-subtle text-secondary border rounded-pill"><?= htmlspecialchars($att['skill_category']) ?></span>
              </td>
              <td>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold"><?= htmlspecialchars($att['skill_name']) ?></span>
              </td>
              <td>
                <span class="badge bg-light text-secondary border rounded-pill text-uppercase" style="font-size: 10px;"><?= htmlspecialchars($diffDisplay) ?></span>
              </td>
              <td class="fw-bold" style="color: var(--text-heading);">
                <?= $att['score_obtained'] ?> / <?= $att['total_questions'] ?>
              </td>
              <td>
                <span class="badge <?= $scorePct >= 75 ? 'bg-success-subtle text-success border-success-subtle' : ($scorePct >= 60 ? 'bg-info-subtle text-info border-info-subtle' : 'bg-danger-subtle text-danger border-danger-subtle') ?> border rounded-pill fw-semibold">
                  <?= number_format($scorePct, 1) ?>%
                </span>
              </td>
              <td>
                <?php if ($isPass): ?>
                  <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> PASS</span>
                <?php else: ?>
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill fw-semibold"><i class="fa-solid fa-circle-xmark me-1"></i> FAIL</span>
                <?php endif; ?>
              </td>
              <td class="text-muted small">
                <?= $durationStr ?>
              </td>
              <td>
                <?php if (!empty($att['risk_level'])): ?>
                  <span class="badge <?= $riskBadge ?> rounded-pill px-3 py-1 fw-semibold">
                    <i class="fa-solid fa-shield-halved me-1"></i> <?= htmlspecialchars($att['risk_level']) ?>
                  </span>
                <?php else: ?>
                  <span class="text-muted small">Standard Session</span>
                <?php endif; ?>
              </td>
              <td class="text-muted small">
                <?= date('d M Y', $timestamp) ?>
              </td>
              <td class="pe-4 text-end">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <button class="btn-action-icon" title="View Detailed Attempt Drawer" onclick="openAttemptDetailModal(<?= $att['id'] ?>)">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                  <a href="<?= BASE_URL ?>faculty/proctoring-report.php?result_id=<?= $att['id'] ?>" class="btn-action-icon" title="View Proctoring Evidence Report">
                    <i class="fa-solid fa-shield-halved"></i>
                  </a>
                  <a href="<?= BASE_URL ?>faculty/skill-gap.php" class="btn-action-icon" title="View Performance Analytics">
                    <i class="fa-solid fa-chart-pie"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- EMPTY STATE -->
  <div id="inspectorEmptyState" class="card border-0 shadow-sm rounded-4 p-5 text-center my-4 bg-white" style="<?= empty($allAttempts) ? 'display: block;' : 'display: none;' ?>">
    <div class="empty-icon-ring mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2.2rem; display: flex; align-items: center; justify-content: center; background: var(--bg-alt, #f8fafc); border-radius: 50%;">
      <i class="fa-solid fa-file-circle-xmark text-muted"></i>
    </div>
    <h4 class="fw-bold text-dark mb-2">No assessment attempts available.</h4>
    <p class="text-muted small mb-4 mx-auto" style="max-width: 420px;">Assessment attempt records and proctoring diagnostics will appear here once students complete assessments.</p>
    <div>
      <a href="<?= BASE_URL ?>faculty/assessments.php" class="btn btn-primary rounded-pill px-4 py-2 small fw-semibold">
        <i class="bi bi-journal-text me-1"></i> Assessment List
      </a>
    </div>
  </div>

  <!-- PAGINATION CONTROLS -->
  <div id="inspectorPaginationContainer" class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 pt-2">
    <span class="text-muted small" id="inspectorPaginationInfo">Showing 1-10 of 10 attempts</span>
    <div class="d-flex align-items-center gap-1" id="inspectorPaginationButtons">
      <!-- Dynamic page buttons -->
    </div>
  </div>
</div>

<!-- STUDENT ATTEMPT DETAIL MODAL DRAWER -->
<div class="modal fade" id="attemptDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: var(--card-bg, #ffffff);">
      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white p-3.5 border-0">
        <div class="d-flex align-items-center gap-2">
          <div class="bg-white text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i class="fa-solid fa-file-invoice"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="modalStudentName">Student Attempt Details</h5>
            <span class="text-white-50 small" id="modalAssessmentTitle">Assessment Title</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4" style="background: var(--bg-alt, #f8fafc);">
        <!-- Student Info Strip -->
        <div class="card border-0 shadow-sm rounded-3 p-3 mb-3 bg-white">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>
              <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1 mb-1" id="modalStudentCode">CODE</span>
              <h5 class="fw-bold text-dark mb-1" id="modalStudentFullName">Student Name</h5>
              <div class="text-muted small" id="modalStudentMeta">Department &bull; Semester &bull; Email</div>
            </div>
            <div class="text-md-end">
              <span class="badge rounded-pill px-3 py-1.5 fw-semibold fs-6" id="modalResultBadge">PASS</span>
              <div class="text-muted small mt-1" id="modalCompletionDate">Date</div>
            </div>
          </div>
        </div>

        <!-- Metric Grid -->
        <div class="row g-3 mb-3">
          <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white text-center">
              <div class="text-muted small fw-semibold">SCORE OBTAINED</div>
              <div class="fs-4 fw-bold text-dark mt-1" id="modalScoreObtained">0 / 25</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white text-center">
              <div class="text-muted small fw-semibold">PERCENTAGE</div>
              <div class="fs-4 fw-bold text-primary mt-1" id="modalScorePct">0.0%</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white text-center">
              <div class="text-muted small fw-semibold">CORRECT / WRONG</div>
              <div class="fs-4 fw-bold text-success mt-1" id="modalCorrectWrong">0 / 0</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white text-center">
              <div class="text-muted small fw-semibold">DURATION TAKEN</div>
              <div class="fs-4 fw-bold text-dark mt-1" id="modalDuration">0m 0s</div>
            </div>
          </div>
        </div>

        <!-- Assessment & Skill Breakdown -->
        <div class="card border-0 shadow-sm rounded-3 p-3 mb-3 bg-white">
          <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-layer-group text-primary me-2"></i>Skill & Assessment Context</h6>
          <div class="row g-2 text-dark small">
            <div class="col-6"><strong>Category:</strong> <span id="modalCategory">Category</span></div>
            <div class="col-6"><strong>Skill:</strong> <span id="modalSkill">Skill</span></div>
            <div class="col-6"><strong>Difficulty:</strong> <span id="modalDifficulty">Beginner</span></div>
            <div class="col-6"><strong>Certificate Status:</strong> <span id="modalCertStatus" class="badge bg-success-subtle text-success border">Eligible</span></div>
          </div>
        </div>

        <!-- Proctoring Diagnostic Summary -->
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-shield-halved text-warning me-2"></i>Proctoring Diagnostic Summary</h6>
            <span class="badge rounded-pill px-3 py-1 fw-semibold" id="modalProctorRiskBadge">Low Risk</span>
          </div>
          <p class="text-muted small mb-3" id="modalProctorDesc">Proctoring evidence logs and session violation diagnostics recorded during this attempt.</p>
          <div class="d-flex justify-content-end">
            <a href="#" id="modalProctorLink" class="btn btn-outline-warning btn-sm rounded-pill px-4 fw-semibold">
              <i class="fa-solid fa-external-link me-1"></i> View Full Proctoring Evidence Log
            </a>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer bg-white border-top p-3 justify-content-between">
        <a href="<?= BASE_URL ?>faculty/skill-gap.php" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
          <i class="fa-solid fa-chart-line me-1"></i> View Score Analytics
        </a>
        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
let currentPage = 1;
const itemsPerPage = 10;
const targetResultId = <?= $targetResultId ?>;

function filterInspectorTable() {
    const searchVal = document.getElementById('inspectorSearchInput').value.toLowerCase().trim();
    const studentVal = document.getElementById('filterStudent').value;
    const categoryVal = document.getElementById('filterCategory').value;
    const skillVal = document.getElementById('filterSkill').value;
    const statusVal = document.getElementById('filterStatus').value;
    const sortVal = document.getElementById('sortAttempts').value;

    const rows = Array.from(document.querySelectorAll('.attempt-row'));

    // 1. Filter
    let visibleRows = rows.filter(row => {
        const resultId = parseInt(row.dataset.resultId);
        const studentId = row.dataset.studentId;
        const studentName = row.dataset.studentName;
        const studentCode = row.dataset.studentCode;
        const title = row.dataset.assessmentTitle;
        const category = row.dataset.category;
        const skill = row.dataset.skill;
        const status = row.dataset.status;

        // If targetResultId is set from notification, ensure target row is always shown!
        if (targetResultId > 0 && resultId === targetResultId) {
            return true;
        }

        // Student match
        if (studentVal !== 'all' && studentId !== studentVal) return false;

        // Category match
        if (categoryVal !== 'all' && category !== categoryVal) return false;

        // Skill match
        if (skillVal !== 'all' && skill !== skillVal) return false;

        // Status match
        if (statusVal !== 'all' && status !== statusVal) return false;

        // Search text match
        if (searchVal.length > 0) {
            const matchesSearch = studentName.includes(searchVal) || studentCode.includes(searchVal) || title.includes(searchVal) || skill.includes(searchVal) || category.includes(searchVal);
            if (!matchesSearch) return false;
        }

        return true;
    });

    // 2. Sort
    visibleRows.sort((a, b) => {
        const timeA = parseInt(a.dataset.timestamp);
        const timeB = parseInt(b.dataset.timestamp);
        const scoreA = parseFloat(a.dataset.score);
        const scoreB = parseFloat(b.dataset.score);
        const riskA = parseInt(a.dataset.riskScore);
        const riskB = parseInt(b.dataset.riskScore);

        if (sortVal === 'newest') return timeB - timeA;
        if (sortVal === 'oldest') return timeA - timeB;
        if (sortVal === 'score_desc') return scoreB - scoreA || timeB - timeA;
        if (sortVal === 'score_asc') return scoreA - scoreB || timeB - timeA;
        if (sortVal === 'risk_desc') return riskB - riskA || timeB - timeA;
        return timeB - timeA;
    });

    // If targetResultId is present, ensure page containing targetResultId is active
    if (targetResultId > 0) {
        const targetIdx = visibleRows.findIndex(r => parseInt(r.dataset.resultId) === targetResultId);
        if (targetIdx !== -1) {
            currentPage = Math.floor(targetIdx / itemsPerPage) + 1;
        }
    }

    // 3. Hide all rows first
    rows.forEach(r => r.style.display = 'none');

    // 4. Pagination math
    const totalVisible = visibleRows.length;
    const totalPages = Math.ceil(totalVisible / itemsPerPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;

    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = startIdx + itemsPerPage;

    const pageRows = visibleRows.slice(startIdx, endIdx);

    const tbody = document.getElementById('inspectorTbody');
    pageRows.forEach(r => {
        r.style.display = 'table-row';
        tbody.appendChild(r);
    });

    // 5. Empty State & Pagination UI updates
    const emptyState = document.getElementById('inspectorEmptyState');
    const tableCard = document.querySelector('#inspectorTable').closest('.inspector-table-container');
    const pagContainer = document.getElementById('inspectorPaginationContainer');
    const countBadge = document.getElementById('inspectorCountBadge');

    if (countBadge) countBadge.textContent = `Matches: ${totalVisible}`;

    if (totalVisible === 0) {
        if (emptyState) emptyState.style.display = 'block';
        if (tableCard) tableCard.style.display = 'none';
        if (pagContainer) pagContainer.style.display = 'none';
    } else {
        if (emptyState) emptyState.style.display = 'none';
        if (tableCard) tableCard.style.display = 'block';
        if (pagContainer) pagContainer.style.display = 'flex';
        renderPaginationControls(totalVisible, totalPages, startIdx, Math.min(endIdx, totalVisible));
    }
}

function renderPaginationControls(totalItems, totalPages, start, end) {
    const info = document.getElementById('inspectorPaginationInfo');
    if (info) info.textContent = `Showing ${start + 1}-${end} of ${totalItems} attempts`;

    const btnContainer = document.getElementById('inspectorPaginationButtons');
    if (!btnContainer) return;
    btnContainer.innerHTML = '';

    if (totalPages <= 1) return;

    // Prev Button
    const prevBtn = document.createElement('button');
    prevBtn.className = 'btn-action-icon';
    prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; filterInspectorTable(); } };
    btnContainer.appendChild(prevBtn);

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `btn-action-icon ${i === currentPage ? 'bg-primary text-white border-primary' : ''}`;
        pageBtn.textContent = i;
        pageBtn.onclick = () => { currentPage = i; filterInspectorTable(); };
        btnContainer.appendChild(pageBtn);
    }

    // Next Button
    const nextBtn = document.createElement('button');
    nextBtn.className = 'btn-action-icon';
    nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; filterInspectorTable(); } };
    btnContainer.appendChild(nextBtn);
}

function openAttemptDetailModal(resultId) {
    const row = document.getElementById('attempt-row-' + resultId);
    if (!row) return;

    const data = row.dataset;
    document.getElementById('modalStudentCode').textContent = data.studentCode.toUpperCase();
    document.getElementById('modalStudentFullName').textContent = row.querySelector('td:nth-child(2) .fw-bold').textContent;
    document.getElementById('modalStudentMeta').textContent = `${data.studentDept} • Semester ${data.studentSem} • ${data.studentEmail}`;
    document.getElementById('modalAssessmentTitle').textContent = row.querySelector('td:nth-child(4) .fw-semibold').textContent;

    const isPass = data.status === 'pass';
    const badgeEl = document.getElementById('modalResultBadge');
    badgeEl.textContent = isPass ? 'PASS' : 'FAIL';
    badgeEl.className = `badge rounded-pill px-3 py-1.5 fw-semibold fs-6 ${isPass ? 'bg-success text-white' : 'bg-danger text-white'}`;

    document.getElementById('modalCompletionDate').textContent = data.formattedDate;
    document.getElementById('modalScoreObtained').textContent = `${data.scoreObtained} / ${data.totalQuestions}`;
    document.getElementById('modalScorePct').textContent = `${parseFloat(data.score).toFixed(1)}%`;
    document.getElementById('modalCorrectWrong').textContent = `${data.correctAnswers} / ${data.wrongAnswers}`;
    document.getElementById('modalDuration').textContent = data.duration;

    document.getElementById('modalCategory').textContent = data.category.toUpperCase();
    document.getElementById('modalSkill').textContent = data.skill.toUpperCase();
    document.getElementById('modalDifficulty').textContent = data.difficulty.toUpperCase();

    const certEl = document.getElementById('modalCertStatus');
    certEl.textContent = isPass ? 'Eligible for Certificate' : 'Ineligible (Score < Pass Mark)';
    certEl.className = `badge ${isPass ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle'}`;

    const riskBadge = document.getElementById('modalProctorRiskBadge');
    const riskLevel = data.riskLevel;
    riskBadge.textContent = riskLevel;
    if (riskLevel === 'High Risk') riskBadge.className = 'badge bg-danger text-white px-3 py-1 rounded-pill';
    else if (riskLevel === 'Medium Risk') riskBadge.className = 'badge bg-warning text-dark px-3 py-1 rounded-pill';
    else riskBadge.className = 'badge bg-success text-white px-3 py-1 rounded-pill';

    const violCount = parseInt(data.totalViolations);
    document.getElementById('modalProctorDesc').textContent = violCount > 0 ? `Flagged ${violCount} total integrity violation events during assessment.` : 'Clean session. Zero integrity violations detected.';
    document.getElementById('modalProctorLink').href = `${BASE_URL}faculty/proctoring-report.php?result_id=${resultId}`;

    const modal = new bootstrap.Modal(document.getElementById('attemptDetailModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', () => {
    filterInspectorTable();

    // Auto-scroll & highlight if arriving from notification deep-link
    if (targetResultId > 0) {
        setTimeout(() => {
            const targetRow = document.getElementById('attempt-row-' + targetResultId);
            if (targetRow) {
                targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                targetRow.classList.add('target-notif-highlight');
                setTimeout(() => {
                    targetRow.classList.remove('target-notif-highlight');
                }, 4000);
            }
        }, 400);
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
