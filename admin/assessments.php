<?php
/**
 * SkillBridge - System-wide Assessment Oversight for Admin
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('admin');

$db = Database::getInstance();

$assessments = $db->fetchAll(
    "SELECT a.*, s.name as skill_name, f.first_name, f.last_name,
            (SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = a.id) as q_count,
            (SELECT COUNT(*) FROM assessment_results WHERE assessment_id = a.id) as sub_count
     FROM assessments a
     JOIN skills s ON a.skill_id = s.id
     JOIN faculty f ON a.created_by_faculty_id = f.id
     ORDER BY a.created_at DESC"
);

$pageTitle = "All System Assessments - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-file-earmark-check text-primary me-2"></i>Institutional Assessments Oversight</h3>
        <p class="text-muted small mb-0">System-wide inventory of all faculty assessments and question counts</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Created By Faculty</th>
                        <th>Associated Skill</th>
                        <th>Duration</th>
                        <th>Questions</th>
                        <th>Submissions</th>
                        <th class="pe-4 text-end">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assessments as $a): ?>
                        <tr>
                            <td class="ps-4 fw-semibold text-dark"><?= htmlspecialchars($a['title']) ?></td>
                            <td class="small text-muted">Prof. <?= htmlspecialchars($a['first_name'] . ' ' . $a['last_name']) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($a['skill_name']) ?></span></td>
                            <td class="small text-muted"><?= $a['duration_minutes'] ?> Mins</td>
                            <td><span class="badge bg-info-subtle text-info border"><?= $a['q_count'] ?> Questions</span></td>
                            <td><span class="badge bg-secondary-subtle text-dark border"><?= $a['sub_count'] ?></span></td>
                            <td class="pe-4 text-end">
                                <span class="badge bg-<?= $a['status'] === 'active' ? 'success' : 'warning' ?>"><?= strtoupper($a['status']) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
