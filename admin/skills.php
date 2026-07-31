<?php
/**
 * SkillBridge - Admin Technical Skills Registry Overview (Read-Only)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('admin');

$db = Database::getInstance();

$skills = $db->fetchAll(
    "SELECT s.*, 
            (SELECT COUNT(*) FROM assessments WHERE skill_id = s.id) as assessment_count,
            (SELECT COUNT(*) FROM course_skills WHERE skill_id = s.id) as course_count
     FROM skills s 
     ORDER BY s.category ASC, s.name ASC"
);

$pageTitle = "Standardized Skills Registry - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-gear-wide-connected text-warning me-2"></i>Standardized Skills Registry</h3>
        <p class="text-muted small mb-0">System-wide inventory of all active standardized Frontend, Backend, and Full Stack technical skills</p>
    </div>
</div>

<div class="saas-card overflow-hidden">
    <div class="saas-card-header flex-wrap gap-2">
        <div class="position-relative" style="min-width: 250px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" class="saas-form-control ps-5 py-2 w-100" placeholder="Search skills..." data-search-table="adminSkillsTable">
        </div>
        <span class="badge saas-badge-warning ms-auto">Total Standardized Skills: <?= count($skills) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="saas-table align-middle mb-0" id="adminSkillsTable">
                <thead>
                    <tr>
                        <th class="ps-4">Skill Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Linked Assessments</th>
                        <th class="pe-4">Linked Courses</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($skills)): ?>
                        <tr>
                            <td colspan="5">
                                <div class="saas-empty-state py-5">
                                    <div class="saas-empty-icon mb-3"><i class="bi bi-gear-wide-connected"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">No skills found in catalog</h6>
                                    <p class="text-muted small mb-0">Please run the standardized seeder script to populate the official skill list.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($skills as $sk): ?>
                            <tr>
                                <td class="ps-4 fw-semibold text-dark"><?= htmlspecialchars($sk['name']) ?></td>
                                <td><span class="badge saas-badge-primary"><?= htmlspecialchars($sk['category']) ?></span></td>
                                <td><span class="small text-muted text-truncate" style="max-width: 320px; display: inline-block;"><?= htmlspecialchars($sk['description'] ?? 'No description.') ?></span></td>
                                <td><span class="badge saas-badge-info"><?= $sk['assessment_count'] ?> Assessments</span></td>
                                <td class="pe-4"><span class="badge saas-badge-success"><?= $sk['course_count'] ?> Courses</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
