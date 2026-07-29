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
check_suspended_status();

$facultyId = $_SESSION['profile_id'];
$db = Database::getInstance();

$assessmentId = (int)($_GET['assessment_id'] ?? $_POST['assessment_id'] ?? 0);

// Fetch ALL assessments for dropdown (Shared Academic Repository)
$assessmentsList = $db->fetchAll(
    "SELECT a.*, f.first_name as creator_first, f.last_name as creator_last, s.name as skill_name, s.category as skill_category
     FROM assessments a 
     LEFT JOIN faculty f ON a.created_by_faculty_id = f.id 
     LEFT JOIN skills s ON a.skill_id = s.id
     ORDER BY a.title ASC"
);

$skillsList = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");

// Default to first assessment if not specified
if ($assessmentId === 0 && !empty($assessmentsList)) {
    $assessmentId = $assessmentsList[0]['id'];
}

$currentAssessment = null;
$isAssessmentOwner = false;
if ($assessmentId > 0) {
    $currentAssessment = $db->fetch(
        "SELECT a.*, s.name as skill_name, s.category as skill_category, f.first_name as creator_first, f.last_name as creator_last 
         FROM assessments a 
         JOIN skills s ON a.skill_id = s.id 
         LEFT JOIN faculty f ON a.created_by_faculty_id = f.id 
         WHERE a.id = ?", 
        [$assessmentId]
    );
    if ($currentAssessment) {
        $isAssessmentOwner = ((int)$currentAssessment['created_by_faculty_id'] === (int)$facultyId);
    }
}

$error = '';
$success = '';

// Question Actions: Add / Edit / Delete (Ownership Enforced per-question assessment)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type'])) {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } else {
        // Re-resolve ownership from the posted assessment_id (supports cross-assessment actions)
        $postedAssessmentId = (int)($_POST['assessment_id'] ?? $assessmentId);
        $postedAssessment = $db->fetch(
            "SELECT * FROM assessments WHERE id = ?",
            [$postedAssessmentId]
        );
        $canActOnPosted = ($postedAssessment && (int)$postedAssessment['created_by_faculty_id'] === (int)$facultyId);

        if (!$canActOnPosted) {
            $error = 'Unauthorized: You can only modify questions for assessments that you created.';
        } else {
        $action = $_POST['action_type'];
        $qId = (int)($_POST['question_id'] ?? 0);

        if ($action === 'delete') {
            $db->delete('assessment_questions', 'id = ? AND assessment_id = ?', [$qId, $postedAssessmentId]);
            $success = 'Question deleted successfully.';
        } elseif (in_array($action, ['create', 'update'])) {
            $questionText = trim($_POST['question_text'] ?? '');
            $optA = trim($_POST['option_a'] ?? '');
            $optB = trim($_POST['option_b'] ?? '');
            $optC = trim($_POST['option_c'] ?? '');
            $optD = trim($_POST['option_d'] ?? '');
            $correctOpt = strtoupper(trim($_POST['correct_option'] ?? 'A'));
            $marks = 1; // 1 mark per question to remain consistent with Student Assessments
            $category = trim($_POST['category'] ?? 'Core Concepts');

            // Validate that the active assessment conforms to the category/skill/difficulty rules
            if ($assessmentId <= 0 || !$currentAssessment) {
                $error = 'Assessment is required and must be valid.';
            } else {
                $skillCategory = trim($currentAssessment['skill_category'] ?? '');
                $skillId = (int)($currentAssessment['skill_id'] ?? 0);
                $diffLevel = trim($currentAssessment['difficulty_level'] ?? '');

                if (empty($skillCategory)) {
                    $error = 'Category is required.';
                } elseif ($skillId <= 0) {
                    $error = 'Skill is required.';
                } elseif (empty($diffLevel)) {
                    $error = 'Difficulty Level is required.';
                }
            }

            if (empty($error)) {
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
        } // end canActOnPosted
    }
}

$questions = $db->fetchAll(
    "SELECT q.*,
            a.title              AS assessment_title,
            a.difficulty_level   AS assessment_difficulty,
            a.created_by_faculty_id,
            a.skill_id,
            s.name               AS skill_name,
            s.category           AS skill_category
     FROM assessment_questions q
     JOIN assessments a ON q.assessment_id = a.id
     JOIN skills s ON a.skill_id = s.id
     ORDER BY q.id ASC"
);

$categoriesList = $db->fetchAll("SELECT DISTINCT category FROM skills ORDER BY category ASC");

$pageTitle = "Question Bank - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>
<style>
  .questions-filter-toolbar {
    background: var(--bg-card, #FFFFFF);
    border: 1px solid var(--border, #E2E8F0);
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 1.5rem;
  }
  [data-theme="dark"] .questions-filter-toolbar {
    background: var(--bg-card, #23202E);
    border-color: var(--border, #383347);
  }
  .filter-toolbar-search {
    position: relative;
    flex: 1 1 200px;
  }
  .filter-toolbar-search .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-placeholder, #94A3B8);
    font-size: 0.82rem;
    pointer-events: none;
  }
  .filter-toolbar-search input {
    padding-left: 36px;
    border-radius: 10px;
    border: 1.5px solid var(--border, #E2E8F0);
    background: var(--bg-input, #F8FAFC);
    color: var(--text-heading, #021024);
    height: 38px;
    font-size: 0.87rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    width: 100%;
  }
  .filter-toolbar-search input:focus {
    outline: none;
    border-color: var(--primary, #26658C);
    box-shadow: 0 0 0 3px rgba(38,101,140,0.12);
    background: var(--bg-card, #FFFFFF);
  }
  [data-theme="dark"] .filter-toolbar-search input {
    background: var(--bg-muted, #2D293B);
    border-color: var(--border, #383347);
    color: var(--text-heading, #FFFFFF);
  }
  [data-theme="dark"] .filter-toolbar-search input:focus {
    background: var(--bg-card, #23202E);
  }
  .filter-select {
    height: 38px;
    border-radius: 10px;
    border: 1.5px solid var(--border, #E2E8F0);
    background: var(--bg-input, #F8FAFC);
    color: var(--text-heading, #021024);
    font-size: 0.87rem;
    font-weight: 500;
    padding: 0 32px 0 12px;
    min-width: 130px;
    cursor: pointer;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%2394A3B8' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
  }
  .filter-select:focus {
    outline: none;
    border-color: var(--primary, #26658C);
    box-shadow: 0 0 0 3px rgba(38,101,140,0.12);
  }
  .filter-select:hover {
    border-color: var(--primary, #26658C);
  }
  [data-theme="dark"] .filter-select {
    background-color: var(--bg-muted, #2D293B);
    border-color: var(--border, #383347);
    color: var(--text-heading, #FFFFFF);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%23E6E4DD' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
  }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-question-circle text-primary me-2"></i>Question Bank Builder</h3>
        <p class="text-muted small mb-0">Shared Question Repository — Browse all questions; edit items for your created assessments</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>faculty/questions-import-export.php" class="btn btn-outline-primary rounded-pill px-4 shadow-sm fw-semibold">
            <i class="bi bi-cloud-arrow-up me-1"></i> Bulk Import/Export
        </a>
        <?php if ($currentAssessment && $isAssessmentOwner): ?>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#questionModal" onclick="resetQuestionForm()">
                <i class="bi bi-plus-circle me-1"></i> Add Question
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<!-- Questions Filter Toolbar -->
<div class="questions-filter-toolbar">
    <div class="d-flex flex-wrap align-items-center gap-2">

        <!-- Search -->
        <div class="filter-toolbar-search flex-grow-1" style="min-width: 200px;">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="questionSearchInput" placeholder="Search questions..." oninput="applyQuestionFilters()" autocomplete="off">
        </div>

        <!-- Category -->
        <select class="filter-select" id="categoryFilterSelect" onchange="applyQuestionFilters()">
            <option value="all">All Categories</option>
            <option value="frontend development">Frontend Development</option>
            <option value="backend development">Backend Development</option>
            <option value="full stack development">Full Stack Development</option>
        </select>

        <!-- Skill -->
        <select class="filter-select" id="skillFilterSelect" onchange="applyQuestionFilters()">
            <option value="all">All Skills</option>
            <?php foreach ($skillsList as $sk): ?>
                <option value="<?= $sk['id'] ?>"><?= htmlspecialchars($sk['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Difficulty -->
        <select class="filter-select" id="difficultyFilterSelect" onchange="applyQuestionFilters()">
            <option value="all">All Difficulties</option>
            <option value="beginner">Beginner (Level 1)</option>
            <option value="easy">Elementary (Level 2)</option>
            <option value="intermediate">Intermediate (Level 3)</option>
            <option value="advanced">Advanced (Level 4)</option>
            <option value="expert">Expert (Level 5)</option>
        </select>

    </div>
</div>


<?php if (empty($questions)): ?>
    <div class="saas-card py-5">
        <div class="saas-empty-state">
            <div class="saas-empty-icon"><i class="bi bi-journal-x"></i></div>
            <h5 class="fw-bold text-dark mb-1">No Questions Yet</h5>
            <p class="text-muted small mb-0">Create your first assessment and add questions to build the bank.</p>
        </div>
    </div>
<?php else: ?>

    <!-- Questions List Table -->
    <div class="saas-card overflow-hidden">
        <div class="saas-card-header flex-wrap gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-table me-2 text-primary"></i>Shared Question Repository</h5>
                <span class="small text-muted">Total in Bank: <?= count($questions) ?> questions &nbsp;|&nbsp; <span id="qbResultCount" class="fw-semibold text-muted"><?= count($questions) ?> questions</span> shown</span>
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
                                        <?php if ($isAssessmentOwner): ?>
                                            <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#questionModal" onclick="resetQuestionForm()">Add Question</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($questions as $idx => $q):
                                $qOwner = ((int)$q['created_by_faculty_id'] === (int)$facultyId);
                            ?>
                                <tr class="question-row"
                                    data-category="<?= htmlspecialchars(strtolower($q['skill_category'])) ?>"
                                    data-skill-id="<?= (int)$q['skill_id'] ?>"
                                    data-difficulty="<?= htmlspecialchars($q['assessment_difficulty']) ?>"
                                    data-assessment-id="<?= (int)$q['assessment_id'] ?>"
                                    data-text="<?= htmlspecialchars(strtolower($q['question_text'] . ' ' . $q['option_a'] . ' ' . $q['option_b'] . ' ' . $q['option_c'] . ' ' . $q['option_d'] . ' ' . $q['category'] . ' ' . $q['assessment_title'])) ?>">
                                    <td class="ps-4 fw-bold text-muted row-index"><?= $idx + 1 ?></td>
                                    <td>
                                        <strong class="text-dark d-block mb-1"><?= htmlspecialchars($q['question_text']) ?></strong>
                                        <div class="d-flex flex-wrap gap-1 align-items-center mt-1">
                                            <span class="badge bg-light text-dark border" style="font-size: 10px;"><?= htmlspecialchars($q['category']) ?></span>
                                            <span class="badge bg-secondary-subtle text-secondary border" style="font-size: 10px;"><i class="bi bi-journal-text me-1"></i><?= htmlspecialchars($q['assessment_title']) ?></span>
                                        </div>
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
                                        <?php if ($qOwner): ?>
                                            <button class="btn btn-outline-warning btn-sm rounded-circle me-1" title="Edit Question" onclick='editQuestion(<?= json_encode($q) ?>)'>
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="<?= BASE_URL ?>faculty/question-bank.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this question?')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="assessment_id" value="<?= (int)$q['assessment_id'] ?>">
                                                <input type="hidden" name="action_type" value="delete">
                                                <input type="hidden" name="question_id" value="<?= $q['id'] ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" title="Delete Question">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border py-1.5 px-2.5">
                                                <i class="bi bi-lock me-1 text-warning"></i> Read-Only
                                            </span>
                                        <?php endif; ?>
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
                            <label class="form-label fw-semibold small text-secondary">Marks (Read-only)</label>
                            <input type="number" name="marks" id="qMarks" class="form-control bg-light text-muted" value="1" readonly required>
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

/* =========================================================
   Question Bank — Client-Side Filter Engine
   ========================================================= */
function applyQuestionFilters() {
    const searchVal = (document.getElementById('questionSearchInput')?.value  || '').toLowerCase().trim();
    const catVal    = (document.getElementById('categoryFilterSelect')?.value  || 'all').toLowerCase();
    const skillVal  = (document.getElementById('skillFilterSelect')?.value     || 'all');
    const diffVal   = (document.getElementById('difficultyFilterSelect')?.value || 'all').toLowerCase();

    const tbody = document.querySelector('table tbody');
    if (!tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr.question-row'));

    // ---- Filter visibility ----
    let visibleRows = [];
    rows.forEach(row => {
        const rowCat   = (row.dataset.category   || '').toLowerCase();
        const rowSkill = (row.dataset.skillId    || '');
        const rowDiff  = (row.dataset.difficulty || '').toLowerCase();
        const rowText  = (row.dataset.text       || '').toLowerCase();

        const matchSearch = !searchVal || rowText.includes(searchVal);
        const matchCat    = catVal   === 'all' || rowCat   === catVal;
        const matchSkill  = skillVal === 'all' || rowSkill === skillVal;
        const matchDiff   = diffVal  === 'all' || rowDiff  === diffVal;

        const visible = matchSearch && matchCat && matchSkill && matchDiff;
        row.style.display = visible ? '' : 'none';
        if (visible) visibleRows.push(row);
    });

    // ---- Re-number visible rows & update counter ----
    visibleRows.forEach((row, idx) => {
        const indexCell = row.querySelector('.row-index');
        if (indexCell) indexCell.textContent = idx + 1;
    });

    // Update result counter badge if present
    const counter = document.getElementById('qbResultCount');
    if (counter) {
        const total = rows.length;
        const shown = visibleRows.length;
        counter.textContent = shown === total ? `${total} questions` : `${shown} of ${total} questions`;
        counter.classList.toggle('text-warning', shown < total);
        counter.classList.toggle('text-muted',   shown === total);
    }
}

// Auto-run on page load to handle pre-selected assessment filter
document.addEventListener('DOMContentLoaded', function () {
    applyQuestionFilters();
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
