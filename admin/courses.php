<?php
/**
 * SkillBridge - Admin Course Catalog CRUD Management
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
        $courseId = (int)($_POST['course_id'] ?? 0);

        if ($action === 'delete') {
            $db->delete('courses', 'id = ?', [$courseId]);
            $success = 'Course deleted from catalog.';
        } elseif (in_array($action, ['create', 'update'])) {
            $code = trim($_POST['course_code'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $duration = (int)($_POST['duration_hours'] ?? 10);
            $diff = trim($_POST['difficulty_level'] ?? 'beginner');
            $url = trim($_POST['provider_url'] ?? '');
            $status = trim($_POST['status'] ?? 'active');
            $skillId = (int)($_POST['skill_id'] ?? 0);

            if (empty($code) || empty($title)) {
                $error = 'Course Code and Title are required.';
            } else {
                if ($action === 'create') {
                    $cId = $db->insert('courses', [
                        'course_code' => $code,
                        'title' => $title,
                        'description' => $desc,
                        'duration_hours' => $duration,
                        'difficulty_level' => $diff,
                        'provider_url' => $url,
                        'status' => $status,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    if ($skillId > 0) {
                        $db->insert('course_skills', [
                            'course_id' => $cId,
                            'skill_id' => $skillId,
                            'skill_level_gained' => 4
                        ]);
                    }
                    $success = "Course '{$title}' created successfully.";
                } else {
                    $db->update('courses', [
                        'course_code' => $code,
                        'title' => $title,
                        'description' => $desc,
                        'duration_hours' => $duration,
                        'difficulty_level' => $diff,
                        'provider_url' => $url,
                        'status' => $status
                    ], 'id = ?', [$courseId]);

                    if ($skillId > 0) {
                        $db->delete('course_skills', 'course_id = ?', [$courseId]);
                        $db->insert('course_skills', [
                            'course_id' => $courseId,
                            'skill_id' => $skillId,
                            'skill_level_gained' => 4
                        ]);
                    }
                    $success = "Course '{$title}' updated.";
                }
            }
        }
    }
}

$courses = $db->fetchAll(
    "SELECT c.*, 
            (SELECT s.name FROM course_skills cs JOIN skills s ON cs.skill_id = s.id WHERE cs.course_id = c.id LIMIT 1) as linked_skill_name,
            (SELECT cs.skill_id FROM course_skills cs WHERE cs.course_id = c.id LIMIT 1) as linked_skill_id
     FROM courses c 
     ORDER BY c.created_at DESC"
);

$skills = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");

$pageTitle = "Manage Courses - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-book text-success me-2"></i>Course Catalog Management</h3>
        <p class="text-muted small mb-0">Create, edit, and link educational courses with technical skills</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#courseModal" onclick="resetCourseForm()">
        <i class="bi bi-plus-circle me-1"></i> Create Course
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
            <input type="text" class="saas-form-control ps-5 py-2 w-100" placeholder="Search courses..." data-search-table="adminCoursesTable">
        </div>
        <span class="badge saas-badge-success">Total Courses: <?= count($courses) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="saas-table align-middle mb-0" id="adminCoursesTable">
                <thead>
                    <tr>
                        <th class="ps-4">Code</th>
                        <th>Course Title</th>
                        <th>Linked Skill</th>
                        <th>Duration</th>
                        <th>Difficulty</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($courses)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="saas-empty-state">
                                    <div class="saas-empty-icon"><i class="bi bi-book"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">No courses found in catalog</h6>
                                    <p class="text-muted small mb-0">Click "Create Course" to add an educational resource.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $c): ?>
                            <tr>
                                <td class="ps-4"><span class="badge saas-badge-primary"><?= htmlspecialchars($c['course_code']) ?></span></td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($c['title']) ?></div>
                                    <div class="small text-muted text-truncate" style="max-width: 260px;"><?= htmlspecialchars($c['description'] ?? '') ?></div>
                                </td>
                                <td><span class="badge saas-badge-info"><?= htmlspecialchars($c['linked_skill_name'] ?? 'Unlinked') ?></span></td>
                                <td class="small text-muted"><i class="bi bi-clock me-1"></i><?= $c['duration_hours'] ?> Hrs</td>
                                <td><span class="badge saas-badge-warning text-uppercase"><?= $c['difficulty_level'] ?></span></td>
                                <td><span class="badge <?= $c['status'] === 'active' ? 'saas-badge-success' : 'saas-badge-info' ?>"><?= strtoupper($c['status']) ?></span></td>
                                <td class="pe-4 text-end">
                                    <button class="saas-btn-action me-1" onclick='editCourse(<?= json_encode($c) ?>)' title="Edit Course">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="<?= BASE_URL ?>admin/courses.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this course?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action_type" value="delete">
                                        <input type="hidden" name="course_id" value="<?= $c['id'] ?>">
                                        <button type="submit" class="saas-btn-action danger" title="Delete Course">
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

<!-- Modal Create / Edit Course -->
<div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold" id="cModalTitle">Create Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>admin/courses.php" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action_type" id="cActionType" value="create">
                <input type="hidden" name="course_id" id="cId" value="0">

                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">Course Code *</label>
                            <input type="text" name="course_code" id="cCode" class="form-control" required placeholder="e.g. CS-101">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small text-secondary">Course Title *</label>
                            <input type="text" name="title" id="cTitle" class="form-control" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Associated Skill</label>
                            <select name="skill_id" id="cSkillId" class="form-select">
                                <option value="0">-- Select Skill --</option>
                                <?php foreach ($skills as $sk): ?>
                                    <option value="<?= $sk['id'] ?>"><?= htmlspecialchars($sk['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-secondary">Duration (Hours)</label>
                            <input type="number" name="duration_hours" id="cDuration" class="form-control" min="1" value="15" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-secondary">Difficulty Level</label>
                            <select name="difficulty_level" id="cDiff" class="form-select">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Description</label>
                        <textarea name="description" id="cDesc" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small text-secondary">Course URL / Material Link</label>
                            <input type="url" name="provider_url" id="cUrl" class="form-control" placeholder="https://">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">Status</label>
                            <select name="status" id="cStatus" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success bg-gradient-success border-0 rounded-pill px-4" id="cSubmitBtn">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetCourseForm() {
    document.getElementById('cModalTitle').textContent = 'Create Course';
    document.getElementById('cActionType').value = 'create';
    document.getElementById('cId').value = '0';
    document.getElementById('cCode').value = '';
    document.getElementById('cTitle').value = '';
    document.getElementById('cDesc').value = '';
    document.getElementById('cDuration').value = '15';
    document.getElementById('cDiff').value = 'beginner';
    document.getElementById('cUrl').value = '';
    document.getElementById('cStatus').value = 'active';
    document.getElementById('cSkillId').value = '0';
    document.getElementById('cSubmitBtn').textContent = 'Save Course';
}

function editCourse(c) {
    document.getElementById('cModalTitle').textContent = 'Edit Course';
    document.getElementById('cActionType').value = 'update';
    document.getElementById('cId').value = c.id;
    document.getElementById('cCode').value = c.course_code;
    document.getElementById('cTitle').value = c.title;
    document.getElementById('cDesc').value = c.description || '';
    document.getElementById('cDuration').value = c.duration_hours;
    document.getElementById('cDiff').value = c.difficulty_level;
    document.getElementById('cUrl').value = c.provider_url || '';
    document.getElementById('cStatus').value = c.status;
    document.getElementById('cSkillId').value = c.linked_skill_id || '0';
    document.getElementById('cSubmitBtn').textContent = 'Update Course';

    const modal = new bootstrap.Modal(document.getElementById('courseModal'));
    modal.show();
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
