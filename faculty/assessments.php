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

// Action: Toggle Status or Delete
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $aId = (int)($_GET['id'] ?? 0);

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

$assessments = $db->fetchAll(
    "SELECT a.*, s.name as skill_name, 
            (SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = a.id) as question_count,
            (SELECT COUNT(*) FROM assessment_results WHERE assessment_id = a.id) as submission_count
     FROM assessments a
     JOIN skills s ON a.skill_id = s.id
     WHERE a.created_by_faculty_id = ?
     ORDER BY a.created_at DESC",
    [$facultyId]
);

$pageTitle = "Manage Assessments - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-file-earmark-text text-primary me-2"></i>Assessment Management</h3>
        <p class="text-muted small mb-0">Create, edit, and configure online skill evaluation assessments</p>
    </div>
    <a href="<?= BASE_URL ?>faculty/assessment-create.php" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
        <i class="bi bi-plus-circle me-1"></i> Create New Assessment
    </a>
</div>

<div class="saas-card overflow-hidden">
    <div class="saas-card-header flex-wrap gap-2">
        <div class="position-relative" style="min-width: 250px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" class="saas-form-control ps-5 py-2 w-100" placeholder="Search assessments..." data-search-table="facultyAssessTable">
        </div>
        <span class="badge saas-badge-primary">Total Assessments: <?= count($assessments) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="saas-table align-middle mb-0" id="facultyAssessTable">
                <thead>
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Associated Skill</th>
                        <th>Duration</th>
                        <th>Passing Marks</th>
                        <th>Questions</th>
                        <th>Submissions</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assessments)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="saas-empty-state">
                                    <div class="saas-empty-icon"><i class="bi bi-journal-x"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">No assessments created yet</h6>
                                    <p class="text-muted small mb-3">Click "Create New Assessment" to build your first quiz.</p>
                                    <a href="<?= BASE_URL ?>faculty/assessment-create.php" class="btn btn-sm btn-primary rounded-pill px-3">Create Assessment</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assessments as $a): 
                            $statusBadge = match($a['status']) {
                                'active' => 'saas-badge-success',
                                'draft' => 'saas-badge-warning',
                                'archived' => 'saas-badge-info'
                            };
                        ?>
                            <tr>
                                <td class="ps-4 fw-semibold text-dark"><?= htmlspecialchars($a['title']) ?></td>
                                <td><span class="badge saas-badge-primary"><?= htmlspecialchars($a['skill_name']) ?></span></td>
                                <td class="small text-muted"><i class="bi bi-clock me-1"></i><?= $a['duration_minutes'] ?> Mins</td>
                                <td><strong class="text-dark"><?= $a['passing_marks'] ?> / <?= $a['total_marks'] ?></strong></td>
                                <td>
                                    <a href="<?= BASE_URL ?>faculty/question-bank.php?assessment_id=<?= $a['id'] ?>" class="badge saas-badge-info text-decoration-none">
                                        <?= $a['question_count'] ?> Questions <i class="bi bi-pencil-square ms-1"></i>
                                    </a>
                                </td>
                                <td><span class="badge saas-badge-primary"><?= $a['submission_count'] ?></span></td>
                                <td><span class="badge <?= $statusBadge ?>"><?= strtoupper($a['status']) ?></span></td>
                                <td class="pe-4 text-end">
                                    <div class="dropdown d-inline-block">
                                        <button class="saas-btn-action" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-saas-menu shadow-lg border-0 mt-1">
                                            <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>faculty/question-bank.php?assessment_id=<?= $a['id'] ?>"><i class="bi bi-question-circle me-2 text-primary"></i> Question Bank</a></li>
                                            <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>faculty/assessment-edit.php?id=<?= $a['id'] ?>"><i class="bi bi-pencil me-2 text-warning"></i> Edit Details</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <?php if ($a['status'] !== 'active'): ?>
                                                <li><a class="dropdown-item py-2 text-success" href="<?= BASE_URL ?>faculty/assessments.php?action=active&id=<?= $a['id'] ?>"><i class="bi bi-play-circle me-2"></i> Mark Active</a></li>
                                            <?php endif; ?>
                                            <?php if ($a['status'] !== 'draft'): ?>
                                                <li><a class="dropdown-item py-2 text-warning" href="<?= BASE_URL ?>faculty/assessments.php?action=draft&id=<?= $a['id'] ?>"><i class="bi bi-pause-circle me-2"></i> Mark Draft</a></li>
                                            <?php endif; ?>
                                            <li><a class="dropdown-item py-2 text-danger" href="<?= BASE_URL ?>faculty/assessments.php?action=delete&id=<?= $a['id'] ?>" data-confirm="Are you sure you want to delete this assessment? All associated questions will be removed."><i class="bi bi-trash me-2"></i> Delete</a></li>
                                        </ul>
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
