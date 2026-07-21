<?php
/**
 * SkillBridge - Question Bank Manager for Faculty
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('faculty');

$facultyId = $_SESSION['profile_id'];
$db = Database::getInstance();

$assessmentId = (int)($_GET['assessment_id'] ?? $_POST['assessment_id'] ?? 0);

$assessmentsList = $db->fetchAll("SELECT * FROM assessments WHERE created_by_faculty_id = ? ORDER BY title ASC", [$facultyId]);

// Default to first assessment if not specified
if ($assessmentId === 0 && !empty($assessmentsList)) {
    $assessmentId = $assessmentsList[0]['id'];
}

$currentAssessment = null;
if ($assessmentId > 0) {
    $currentAssessment = $db->fetch("SELECT a.*, s.name as skill_name FROM assessments a JOIN skills s ON a.skill_id = s.id WHERE a.id = ?", [$assessmentId]);
}

$error = '';
$success = '';

// Question Actions: Add / Edit / Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type'])) {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } else {
        $action = $_POST['action_type'];
        $qId = (int)($_POST['question_id'] ?? 0);

        if ($action === 'delete') {
            $db->delete('assessment_questions', 'id = ? AND assessment_id = ?', [$qId, $assessmentId]);
            $success = 'Question deleted successfully.';
        } elseif (in_array($action, ['create', 'update'])) {
            $questionText = trim($_POST['question_text'] ?? '');
            $optA = trim($_POST['option_a'] ?? '');
            $optB = trim($_POST['option_b'] ?? '');
            $optC = trim($_POST['option_c'] ?? '');
            $optD = trim($_POST['option_d'] ?? '');
            $correctOpt = strtoupper(trim($_POST['correct_option'] ?? 'A'));
            $marks = (int)($_POST['marks'] ?? 1);
            $category = trim($_POST['category'] ?? 'Core Concepts');

            if (empty($questionText) || empty($optA) || empty($optB) || empty($optC) || empty($optD)) {
                $error = 'Question text and all four options (A, B, C, D) are required.';
            } else {
                if ($action === 'create') {
                    $db->insert('assessment_questions', [
                        'assessment_id' => $assessmentId,
                        'question_text' => $questionText,
                        'option_a' => $optA,
                        'option_b' => $optB,
                        'option_c' => $optC,
                        'option_d' => $optD,
                        'correct_option' => $correctOpt,
                        'marks' => $marks,
                        'category' => $category
                    ]);
                    $success = 'Question added to bank.';
                } else {
                    $db->update('assessment_questions', [
                        'question_text' => $questionText,
                        'option_a' => $optA,
                        'option_b' => $optB,
                        'option_c' => $optC,
                        'option_d' => $optD,
                        'correct_option' => $correctOpt,
                        'marks' => $marks,
                        'category' => $category
                    ], 'id = ? AND assessment_id = ?', [$qId, $assessmentId]);
                    $success = 'Question updated.';
                }
            }
        }
    }
}

$questions = [];
if ($assessmentId > 0) {
    $questions = $db->fetchAll("SELECT * FROM assessment_questions WHERE assessment_id = ? ORDER BY id ASC", [$assessmentId]);
}

$pageTitle = "Question Bank - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-question-circle text-primary me-2"></i>Question Bank Builder</h3>
        <p class="text-muted small mb-0">Create multiple choice questions and specify correct answers</p>
    </div>
    <?php if ($currentAssessment): ?>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#questionModal" onclick="resetQuestionForm()">
            <i class="bi bi-plus-circle me-1"></i> Add Question
        </button>
    <?php endif; ?>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<!-- Assessment Selector Dropdown -->
<div class="saas-card mb-4">
    <div class="card-body p-3">
        <form action="<?= BASE_URL ?>faculty/question-bank.php" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9">
                <label class="form-label small text-muted mb-1 fw-semibold">Active Assessment Context</label>
                <select name="assessment_id" class="saas-form-select w-100" onchange="this.form.submit()">
                    <?php if (empty($assessmentsList)): ?>
                        <option value="">-- No Assessments Created Yet --</option>
                    <?php else: ?>
                        <?php foreach ($assessmentsList as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= $assessmentId == $a['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <a href="<?= BASE_URL ?>faculty/assessment-create.php" class="btn btn-outline-primary btn-sm w-100 rounded-pill py-2">Create New Test</a>
            </div>
        </form>
    </div>
</div>

<?php if (!$currentAssessment): ?>
    <div class="saas-card py-5">
        <div class="saas-empty-state">
            <div class="saas-empty-icon"><i class="bi bi-journal-x"></i></div>
            <h5 class="fw-bold text-dark mb-1">No Assessment Selected</h5>
            <p class="text-muted small mb-0">Please select an assessment or create a new one to add questions.</p>
        </div>
    </div>
<?php else: ?>
    <!-- Questions List Table -->
    <div class="saas-card overflow-hidden">
        <div class="saas-card-header flex-wrap gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-0"><?= htmlspecialchars($currentAssessment['title']) ?></h5>
                <span class="small text-muted">Skill: <?= htmlspecialchars($currentAssessment['skill_name']) ?> &bull; Questions: <?= count($questions) ?></span>
            </div>
            <div class="position-relative" style="min-width: 220px;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" class="saas-form-control ps-5 py-1.5 w-100" placeholder="Search questions..." data-search-table="questionsTable">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="saas-table align-middle mb-0" id="questionsTable">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">#</th>
                            <th>Question Prompt</th>
                            <th>Options Breakdown</th>
                            <th>Correct Option</th>
                            <th>Marks</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($questions)): ?>
                            <tr>
                                <td colspan="6">
                                    <div class="saas-empty-state">
                                        <div class="saas-empty-icon"><i class="bi bi-patch-question"></i></div>
                                        <h6 class="fw-bold text-dark mb-1">No questions added yet</h6>
                                        <p class="text-muted small mb-3">Click "Add Question" button to start building question items.</p>
                                        <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#questionModal" onclick="resetQuestionForm()">Add Question</button>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($questions as $idx => $q): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted"><?= $idx + 1 ?></td>
                                    <td>
                                        <strong class="text-dark d-block mb-1"><?= htmlspecialchars($q['question_text']) ?></strong>
                                        <span class="badge bg-light text-dark border" style="font-size: 10px;"><?= htmlspecialchars($q['category']) ?></span>
                                    </td>
                                    <td class="small">
                                        <div class="text-muted">A: <?= htmlspecialchars($q['option_a']) ?></div>
                                        <div class="text-muted">B: <?= htmlspecialchars($q['option_b']) ?></div>
                                        <div class="text-muted">C: <?= htmlspecialchars($q['option_c']) ?></div>
                                        <div class="text-muted">D: <?= htmlspecialchars($q['option_d']) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success px-2 py-1 fs-6">Option <?= $q['correct_option'] ?></span>
                                    </td>
                                    <td><span class="fw-bold text-dark"><?= $q['marks'] ?></span></td>
                                    <td class="pe-4 text-end">
                                        <button class="btn btn-outline-warning btn-sm rounded-circle me-1" onclick='editQuestion(<?= json_encode($q) ?>)'>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="<?= BASE_URL ?>faculty/question-bank.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this question?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="assessment_id" value="<?= $assessmentId ?>">
                                            <input type="hidden" name="action_type" value="delete">
                                            <input type="hidden" name="question_id" value="<?= $q['id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle">
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
<?php endif; ?>

<!-- Question Add/Edit Modal -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>faculty/question-bank.php" method="POST" id="qForm">
                <?= csrf_field() ?>
                <input type="hidden" name="assessment_id" value="<?= $assessmentId ?>">
                <input type="hidden" name="action_type" id="qActionType" value="create">
                <input type="hidden" name="question_id" id="qId" value="0">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Question Prompt *</label>
                        <textarea name="question_text" id="qText" class="form-control" rows="3" required placeholder="Type the question text here..."></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Option A *</label>
                            <input type="text" name="option_a" id="optA" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Option B *</label>
                            <input type="text" name="option_b" id="optB" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Option C *</label>
                            <input type="text" name="option_c" id="optC" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Option D *</label>
                            <input type="text" name="option_d" id="optD" class="form-control" required>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">Correct Option *</label>
                            <select name="correct_option" id="correctOpt" class="form-select" required>
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">Marks</label>
                            <input type="number" name="marks" id="qMarks" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">Topic Category</label>
                            <input type="text" name="category" id="qCategory" class="form-control" value="Core Concepts">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary bg-gradient-primary border-0 rounded-pill px-4" id="modalSubmitBtn">Save Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetQuestionForm() {
    document.getElementById('modalTitle').textContent = 'Add Question';
    document.getElementById('qActionType').value = 'create';
    document.getElementById('qId').value = '0';
    document.getElementById('qText').value = '';
    document.getElementById('optA').value = '';
    document.getElementById('optB').value = '';
    document.getElementById('optC').value = '';
    document.getElementById('optD').value = '';
    document.getElementById('correctOpt').value = 'A';
    document.getElementById('qMarks').value = '1';
    document.getElementById('qCategory').value = 'Core Concepts';
    document.getElementById('modalSubmitBtn').textContent = 'Save Question';
}

function editQuestion(q) {
    document.getElementById('modalTitle').textContent = 'Edit Question';
    document.getElementById('qActionType').value = 'update';
    document.getElementById('qId').value = q.id;
    document.getElementById('qText').value = q.question_text;
    document.getElementById('optA').value = q.option_a;
    document.getElementById('optB').value = q.option_b;
    document.getElementById('optC').value = q.option_c;
    document.getElementById('optD').value = q.option_d;
    document.getElementById('correctOpt').value = q.correct_option;
    document.getElementById('qMarks').value = q.marks;
    document.getElementById('qCategory').value = q.category || 'Core Concepts';
    document.getElementById('modalSubmitBtn').textContent = 'Update Question';

    const modal = new bootstrap.Modal(document.getElementById('questionModal'));
    modal.show();
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
