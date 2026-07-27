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

$pageTitle = "Institutional Assessments Oversight - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(139, 92, 246, 0.15); border: 1px solid rgba(139, 92, 246, 0.3); color: #a78bfa; font-size: 1.5rem;">
        <i class="bi bi-clipboard-check"></i>
    </div>
    <div>
        <h3 class="fw-bold mb-0" style="color: var(--text-heading);">Institutional Assessments Oversight</h3>
        <p class="text-muted small mb-0">System-wide inventory of all faculty assessments and question counts</p>
    </div>
</div>

<div class="saas-card overflow-hidden">
    <div class="saas-card-header flex-wrap gap-2">
        <div class="position-relative" style="min-width: 280px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" class="saas-form-control ps-5 py-2 w-100" placeholder="Search skills, assessments..." data-search-table="adminAssessTable">
        </div>
        <div class="d-flex align-items-center gap-2 ms-auto">
            <span class="saas-badge saas-badge-primary"><i class="bi bi-layers me-1"></i> Total: <?= count($assessments) ?></span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="saas-table align-middle mb-0" id="adminAssessTable">
                <thead>
                    <tr>
                        <th class="ps-4">TITLE</th>
                        <th>CREATED BY FACULTY</th>
                        <th>ASSOCIATED SKILL</th>
                        <th>DURATION</th>
                        <th>QUESTIONS</th>
                        <th>SUBMISSIONS</th>
                        <th class="pe-4 text-end">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assessments)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="saas-empty-state py-5">
                                    <div class="saas-empty-icon mb-3"><i class="bi bi-journal-x"></i></div>
                                    <h6 class="fw-bold mb-1" style="color: var(--text-heading);">No assessments found</h6>
                                    <p class="text-muted small mb-0">There are no faculty assessments created in the system yet.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assessments as $a): 
                            $status = strtolower($a['status'] ?? 'active');
                            $statusBadge = match($status) {
                                'active'   => '<span class="badge rounded-pill" style="background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.35); font-weight: 700; letter-spacing: 0.05em; padding: 5px 14px; font-size: 0.72rem;">ACTIVE</span>',
                                'draft'    => '<span class="badge rounded-pill" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); font-weight: 700; letter-spacing: 0.05em; padding: 5px 14px; font-size: 0.72rem;">DRAFT</span>',
                                'archived' => '<span class="badge rounded-pill" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.35); font-weight: 700; letter-spacing: 0.05em; padding: 5px 14px; font-size: 0.72rem;">ARCHIVED</span>',
                                default    => '<span class="badge rounded-pill" style="background: rgba(99, 102, 241, 0.15); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.35); font-weight: 700; letter-spacing: 0.05em; padding: 5px 14px; font-size: 0.72rem;">' . strtoupper(htmlspecialchars($status)) . '</span>'
                            };
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <strong class="d-block" style="color: var(--text-heading); font-size: 0.92rem;"><?= htmlspecialchars($a['title']) ?></strong>
                                </td>
                                <td>
                                    <span class="text-secondary small font-medium">Prof. <?= htmlspecialchars($a['first_name'] . ' ' . $a['last_name']) ?></span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill" style="background: rgba(139, 92, 246, 0.15); color: #c084fc; border: 1px solid rgba(139, 92, 246, 0.3); font-weight: 600; padding: 6px 14px; font-size: 0.78rem;">
                                        <?= htmlspecialchars($a['skill_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="small text-muted font-medium"><?= (int)$a['duration_minutes'] ?> Mins</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); font-weight: 600; padding: 6px 14px; font-size: 0.78rem;">
                                        <?= (int)$a['q_count'] ?> Questions
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill" style="background: rgba(148, 163, 184, 0.12); color: var(--text-body); border: 1px solid rgba(148, 163, 184, 0.25); padding: 5px 14px; font-size: 0.78rem; font-weight: 700;">
                                        <?= (int)$a['sub_count'] ?>
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <?= $statusBadge ?>
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
