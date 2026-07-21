<?php
/**
 * SkillBridge - Admin Technical Skills Registry CRUD
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('admin');

$db = Database::getInstance();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type'])) {
    if (!verify_csrf_token()) {
        $error = 'Invalid CSRF token.';
    } else {
        $action = $_POST['action_type'];
        $skillId = (int)($_POST['skill_id'] ?? 0);

        if ($action === 'delete') {
            $db->delete('skills', 'id = ?', [$skillId]);
            $success = 'Skill deleted from catalog.';
        } elseif (in_array($action, ['create', 'update'])) {
            $name = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? 'Technical');
            $desc = trim($_POST['description'] ?? '');

            if (empty($name)) {
                $error = 'Skill Name is required.';
            } else {
                if ($action === 'create') {
                    $db->insert('skills', [
                        'name' => $name,
                        'category' => $category,
                        'description' => $desc,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    $success = "Skill '{$name}' created.";
                } else {
                    $db->update('skills', [
                        'name' => $name,
                        'category' => $category,
                        'description' => $desc
                    ], 'id = ?', [$skillId]);
                    $success = "Skill '{$name}' updated.";
                }
            }
        }
    }
}

$skills = $db->fetchAll(
    "SELECT s.*, 
            (SELECT COUNT(*) FROM assessments WHERE skill_id = s.id) as assessment_count,
            (SELECT COUNT(*) FROM course_skills WHERE skill_id = s.id) as course_count
     FROM skills s 
     ORDER BY s.category ASC, s.name ASC"
);

$pageTitle = "Manage Skills - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-gear-wide-connected text-warning me-2"></i>Skills Catalog Management</h3>
        <p class="text-muted small mb-0">Define technical skills, categories, and track associated courses & tests</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#skillModal" onclick="resetSkillForm()">
        <i class="bi bi-plus-circle me-1"></i> Add New Skill
    </button>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="saas-card overflow-hidden">
    <div class="saas-card-header flex-wrap gap-2">
        <div class="position-relative" style="min-width: 250px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" class="saas-form-control ps-5 py-2 w-100" placeholder="Search skills..." data-search-table="adminSkillsTable">
        </div>
        <span class="badge saas-badge-warning">Total Skills: <?= count($skills) ?></span>
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
                        <th>Linked Courses</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($skills)): ?>
                        <tr>
                            <td colspan="6">
                                <div class="saas-empty-state">
                                    <div class="saas-empty-icon"><i class="bi bi-gear-wide-connected"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">No skills found in catalog</h6>
                                    <p class="text-muted small mb-0">Click "Add New Skill" to define technical skills.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($skills as $sk): ?>
                            <tr>
                                <td class="ps-4 fw-semibold text-dark"><?= htmlspecialchars($sk['name']) ?></td>
                                <td><span class="badge saas-badge-primary"><?= htmlspecialchars($sk['category']) ?></span></td>
                                <td><span class="small text-muted text-truncate" style="max-width: 240px; display: inline-block;"><?= htmlspecialchars($sk['description'] ?? 'No description.') ?></span></td>
                                <td><span class="badge saas-badge-info"><?= $sk['assessment_count'] ?> Assessments</span></td>
                                <td><span class="badge saas-badge-success"><?= $sk['course_count'] ?> Courses</span></td>
                                <td class="pe-4 text-end">
                                    <button class="saas-btn-action me-1" onclick='editSkill(<?= json_encode($sk) ?>)' title="Edit Skill">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="<?= BASE_URL ?>admin/skills.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this skill?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action_type" value="delete">
                                        <input type="hidden" name="skill_id" value="<?= $sk['id'] ?>">
                                        <button type="submit" class="saas-btn-action danger" title="Delete Skill">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Skill Form -->
<div class="modal fade" id="skillModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold" id="skModalTitle">Add Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>admin/skills.php" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action_type" id="skActionType" value="create">
                <input type="hidden" name="skill_id" id="skId" value="0">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Skill Name *</label>
                        <input type="text" name="name" id="skName" class="form-control" required placeholder="e.g. PHP 8 Development">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Category</label>
                        <select name="category" id="skCategory" class="form-select">
                            <option value="Backend">Backend</option>
                            <option value="Frontend">Frontend</option>
                            <option value="Database">Database</option>
                            <option value="Security">Security</option>
                            <option value="DevOps">DevOps</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Software Design">Software Design</option>
                            <option value="Technical">Technical</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Description</label>
                        <textarea name="description" id="skDesc" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-semibold" id="skSubmitBtn">Save Skill</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetSkillForm() {
    document.getElementById('skModalTitle').textContent = 'Add Skill';
    document.getElementById('skActionType').value = 'create';
    document.getElementById('skId').value = '0';
    document.getElementById('skName').value = '';
    document.getElementById('skCategory').value = 'Technical';
    document.getElementById('skDesc').value = '';
    document.getElementById('skSubmitBtn').textContent = 'Save Skill';
}

function editSkill(sk) {
    document.getElementById('skModalTitle').textContent = 'Edit Skill';
    document.getElementById('skActionType').value = 'update';
    document.getElementById('skId').value = sk.id;
    document.getElementById('skName').value = sk.name;
    document.getElementById('skCategory').value = sk.category || 'Technical';
    document.getElementById('skDesc').value = sk.description || '';
    document.getElementById('skSubmitBtn').textContent = 'Update Skill';

    const modal = new bootstrap.Modal(document.getElementById('skillModal'));
    modal.show();
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
