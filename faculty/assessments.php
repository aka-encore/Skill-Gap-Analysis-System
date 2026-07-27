<?php
/**
 * SkillBridge - Faculty Assessment Management List
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');

$facultyId = $_SESSION['profile_id'];
$db = Database::getInstance();

// Action: Toggle Status or Delete (Ownership Guard Enforced)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $aId = (int)($_GET['id'] ?? 0);

    $target = $db->fetch("SELECT * FROM assessments WHERE id = ?", [$aId]);
    if (!$target) {
        set_flash_message('danger', 'Assessment not found.');
        redirect(BASE_URL . 'faculty/assessments.php');
    }

    if ((int)$target['created_by_faculty_id'] !== (int)$facultyId) {
        set_flash_message('danger', 'Unauthorized: You can only edit or delete assessments that you created.');
        redirect(BASE_URL . 'faculty/assessments.php');
    }

    if ($action === 'delete') {
        $db->delete('assessments', 'id = ? AND created_by_faculty_id = ?', [$aId, $facultyId]);
        set_flash_message('success', 'Assessment deleted successfully.');
        redirect(BASE_URL . 'faculty/assessments.php');
    } elseif (in_array($action, ['active', 'draft', 'archived'])) {
        $db->update('assessments', ['status' => $action], 'id = ? AND created_by_faculty_id = ?', [$aId, $facultyId]);
        set_flash_message('success', 'Assessment status updated to ' . strtoupper($action));
        redirect(BASE_URL . 'faculty/assessments.php');
    }
}

// Shared Repository: Fetch ALL assessments
// Shared Repository: Fetch ALL assessments with student attempt statistics
$assessments = $db->fetchAll(
    "SELECT a.*, s.name as skill_name, f.first_name as creator_first, f.last_name as creator_last,
            (SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = a.id) as question_count,
            (SELECT COUNT(*) FROM assessment_results WHERE assessment_id = a.id) as submission_count,
            (SELECT COUNT(DISTINCT student_id) FROM assessment_results WHERE assessment_id = a.id) as student_count,
            (SELECT AVG(score_percentage) FROM assessment_results WHERE assessment_id = a.id) as avg_score,
            (SELECT COUNT(*) FROM assessment_results WHERE assessment_id = a.id AND status = 'passed') as pass_count
     FROM assessments a
     JOIN skills s ON a.skill_id = s.id
     LEFT JOIN faculty f ON a.created_by_faculty_id = f.id
     ORDER BY a.created_at DESC"
);

// Calculate overall Assessment Overview KPIs
$totalAssessmentsCount = count($assessments);
$totalStudentsAttempted = (int)($db->fetch("SELECT COUNT(DISTINCT student_id) as cnt FROM assessment_results")['cnt'] ?? 0);
$overallAvgScore = round((float)($db->fetch("SELECT AVG(score_percentage) as cnt FROM assessment_results")['cnt'] ?? 0), 1);
$totalPassedAttempts = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results WHERE status = 'passed'")['cnt'] ?? 0);
$totalAttemptsCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM assessment_results")['cnt'] ?? 0);
$overallPassRate = $totalAttemptsCount > 0 ? round(($totalPassedAttempts / $totalAttemptsCount) * 100, 1) : 0;

$pageTitle = "Assessment Overview - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-heading);"><i class="bi bi-journal-bookmark text-primary me-2"></i>Assessment Overview</h3>
        <p class="text-muted small mb-0">View and manage faculty assessments, student attempt statistics, and performance metrics</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>faculty/skill-gap.php" class="btn btn-outline-primary rounded-pill px-3 py-2 shadow-sm small fw-semibold">
            <i class="bi bi-bar-chart-line me-1"></i> View Analytics
        </a>
        <a href="<?= BASE_URL ?>faculty/assessment-create.php" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm small fw-semibold">
            <i class="bi bi-plus-circle me-1"></i> Create Assessment
        </a>
    </div>
</div>

<!-- 5 KPI Summary Cards Grid (Faculty Dashboard SaaS Card System) -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-3 mb-4">
    <!-- Card 1: Total Assessments -->
    <div class="col">
        <div class="saas-stat-card primary-card h-100">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Assessments</span>
                <div class="stat-icon-saas primary-gradient">
                    <i class="bi bi-journal-text"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value"><?= $totalAssessmentsCount ?></div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-card-trend trend-primary">
                    <i class="bi bi-journal-check me-1"></i> Institutional Repository
                </span>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Students -->
    <div class="col">
        <div class="saas-stat-card accent-card h-100">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Students</span>
                <div class="stat-icon-saas accent-gradient">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value"><?= $totalStudentsAttempted ?></div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-card-trend trend-accent">
                    <i class="bi bi-person-check me-1"></i> Unique Attempted
                </span>
            </div>
        </div>
    </div>

    <!-- Card 3: Average Score -->
    <div class="col">
        <div class="saas-stat-card success-card h-100">
            <div class="stat-card-header">
                <span class="stat-card-title">Average Score</span>
                <div class="stat-icon-saas success-gradient">
                    <i class="bi bi-bullseye"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value gradient-value"><?= $overallAvgScore ?>%</div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-card-trend trend-success">
                    <i class="bi bi-graph-up-arrow me-1"></i> Overall Average
                </span>
            </div>
        </div>
    </div>

    <!-- Card 4: Pass Rate -->
    <div class="col">
        <div class="saas-stat-card warning-card h-100">
            <div class="stat-card-header">
                <span class="stat-card-title">Pass Rate</span>
                <div class="stat-icon-saas warning-gradient">
                    <i class="bi bi-trophy"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value"><?= $overallPassRate ?>%</div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-card-trend trend-warning">
                    <i class="bi bi-check-circle me-1"></i> Passing Submissions
                </span>
            </div>
        </div>
    </div>

    <!-- Card 5: Total Attempts -->
    <div class="col">
        <div class="saas-stat-card danger-card h-100">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Attempts</span>
                <div class="stat-icon-saas danger-gradient">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value"><?= $totalAttemptsCount ?></div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-card-trend trend-danger">
                    <i class="bi bi-clock-history me-1"></i> Evaluated Attempts
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Table Container Card -->
<div class="saas-card overflow-hidden">
    <div class="saas-card-header flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3 flex-grow-1" style="max-width: 400px;">
            <div class="position-relative w-100">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" class="saas-form-control ps-5 py-2 w-100" placeholder="Search by title, skill, or creator..." data-search-table="facultyAssessTable">
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 ms-auto">
            <span class="saas-badge saas-badge-primary"><i class="bi bi-journal-text me-1"></i> Total: <?= count($assessments) ?></span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="saas-table align-middle mb-0" id="facultyAssessTable">
                <thead>
                    <tr>
                        <th class="ps-4">ASSESSMENT TITLE</th>
                        <th>TYPE / SKILL</th>
                        <th>STUDENTS</th>
                        <th>ATTEMPTS</th>
                        <th>AVG SCORE</th>
                        <th>PASS RATE</th>
                        <th>STATUS</th>
                        <th>LAST UPDATED</th>
                        <th class="pe-4 text-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assessments)): ?>
                        <tr>
                            <td colspan="9">
                                <div class="saas-empty-state py-5">
                                    <div class="saas-empty-icon mb-3"><i class="bi bi-journal-x"></i></div>
                                    <h6 class="fw-bold mb-1" style="color: var(--text-heading);">No assessments available</h6>
                                    <p class="text-muted small mb-3">You haven't created any assessments yet. Get started by creating your first quiz.</p>
                                    <a href="<?= BASE_URL ?>faculty/assessment-create.php" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                                        <i class="bi bi-plus-circle me-1"></i> Create Assessment
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assessments as $a): 
                            $isOwner = ((int)$a['created_by_faculty_id'] === (int)$facultyId);
                            $creatorName = trim(($a['creator_first'] ?? '') . ' ' . ($a['creator_last'] ?? ''));
                            if (empty($creatorName)) $creatorName = 'Faculty';

                            $subs = (int)$a['submission_count'];
                            $avgScore = $subs > 0 ? round((float)($a['avg_score'] ?? 0), 2) : 0;
                            $passRate = $subs > 0 ? round(((int)$a['pass_count'] / $subs) * 100, 2) : 0;

                            $status = strtolower($a['status'] ?? 'active');
                            $statusBadge = match($status) {
                                'active'   => '<span class="saas-badge saas-badge-success"><i class="bi bi-check-circle me-1"></i> Active</span>',
                                'draft'    => '<span class="saas-badge saas-badge-warning"><i class="bi bi-pause-circle me-1"></i> Draft</span>',
                                'archived' => '<span class="saas-badge saas-badge-danger"><i class="bi bi-archive me-1"></i> Archived</span>',
                                default    => '<span class="saas-badge saas-badge-primary"><i class="bi bi-info-circle me-1"></i> ' . ucfirst($status) . '</span>'
                            };
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <strong class="d-block" style="color: var(--text-heading); font-size: 0.92rem;"><?= htmlspecialchars($a['title']) ?></strong>
                                    <small class="d-inline-block mt-0.5">
                                        <?php if ($isOwner): ?>
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-size: 10px;"><i class="bi bi-person-fill me-1"></i>My Assessment</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-muted border border-secondary border-opacity-25" style="font-size: 10px;"><i class="bi bi-shield-lock me-1"></i>Prof. <?= htmlspecialchars($creatorName) ?></span>
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill" style="font-size: 11px; padding: 4px 10px; background: rgba(139, 92, 246, 0.12); color: #8b5cf6; border: 1px solid rgba(139, 92, 246, 0.25);">
                                        <i class="bi bi-tag-fill me-1 opacity-75"></i><?= htmlspecialchars($a['skill_name']) ?>
                                    </span>
                                </td>
                                <td><span class="fw-semibold" style="color: var(--text-heading);"><?= (int)$a['student_count'] ?></span></td>
                                <td><span class="fw-semibold" style="color: var(--text-heading);"><?= $subs ?></span></td>
                                <td>
                                    <span class="fw-bold text-success"><?= number_format($avgScore, 2) ?>%</span>
                                    <small class="text-success ms-1"><i class="bi bi-arrow-up-short"></i></small>
                                </td>
                                <td style="min-width: 140px;">
                                    <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 0.8rem;">
                                        <span class="fw-bold" style="color: var(--text-heading);"><?= number_format($passRate, 2) ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 5px; background: rgba(139, 92, 246, 0.15); border-radius: 10px;">
                                        <div class="progress-bar rounded-pill" role="progressbar" style="width: <?= min(100, max(0, $passRate)) ?>%; background: linear-gradient(90deg, #6366f1, #8b5cf6);" aria-valuenow="<?= $passRate ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td><?= $statusBadge ?></td>
                                <td class="small text-muted">
                                    <i class="bi bi-calendar-event me-1"></i><?= date('M d, Y h:i A', strtotime($a['created_at'])) ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1.5 align-items-center">
                                        <a href="<?= BASE_URL ?>faculty/question-bank.php?assessment_id=<?= $a['id'] ?>" class="btn-action-square" title="View Question Bank">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>faculty/evaluate.php?assessment_id=<?= $a['id'] ?>" class="btn-action-square" title="View Submissions & Analytics">
                                            <i class="bi bi-bar-chart-line"></i>
                                        </a>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn-action-square" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="More Options">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-1">
                                                <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>faculty/question-bank.php?assessment_id=<?= $a['id'] ?>"><i class="bi bi-question-circle me-2 text-primary"></i> Question Bank</a></li>
                                                <?php if ($isOwner): ?>
                                                    <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>faculty/assessment-edit.php?id=<?= $a['id'] ?>"><i class="bi bi-pencil me-2 text-warning"></i> Edit Details</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <?php if ($a['status'] !== 'active'): ?>
                                                        <li><a class="dropdown-item py-2 text-success" href="<?= BASE_URL ?>faculty/assessments.php?action=active&id=<?= $a['id'] ?>"><i class="bi bi-play-circle me-2"></i> Mark Active</a></li>
                                                    <?php endif; ?>
                                                    <?php if ($a['status'] !== 'draft'): ?>
                                                        <li><a class="dropdown-item py-2 text-warning" href="<?= BASE_URL ?>faculty/assessments.php?action=draft&id=<?= $a['id'] ?>"><i class="bi bi-pause-circle me-2"></i> Mark Draft</a></li>
                                                    <?php endif; ?>
                                                    <li><a class="dropdown-item py-2 text-danger" href="<?= BASE_URL ?>faculty/assessments.php?action=delete&id=<?= $a['id'] ?>" onclick="return confirm('Are you sure you want to delete this assessment?')"><i class="bi bi-trash me-2"></i> Delete</a></li>
                                                <?php else: ?>
                                                    <li><span class="dropdown-item-text py-2 text-muted small"><i class="bi bi-lock me-2 text-warning"></i> Read-Only</span></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
