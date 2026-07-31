<?php
/**
 * SkillBridge - Standardized Question Bank Manager for Faculty
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

// Auto-sync assessments table with valid published question banks
sync_assessments_table($db);

$qbId = (int)($_GET['qb_id'] ?? $_POST['qb_id'] ?? 0);
$error = '';
$success = '';

// Handle actions: Create, Duplicate, Delete Bank, Toggle Publish, Add/Edit/Delete Questions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } else {
        $action = $_POST['action'];

        // 1. BANK LEVEL ACTIONS
        if ($action === 'toggle_publish') {
            $targetQbId = (int)($_POST['target_qb_id'] ?? 0);
            $qb = $db->fetch("SELECT * FROM question_banks WHERE id = ?", [$targetQbId]);
            if ($qb) {
                $qCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM questions WHERE question_bank_id = ?", [$targetQbId])['cnt'] ?? 0);
                if ($qCount < 25 && $qb['status'] !== 'published') {
                    $error = 'Cannot publish Question Bank: A minimum of 25 questions is required to publish.';
                } else {
                    $newStatus = $qb['status'] === 'published' ? 'draft' : 'published';
                    $db->update('question_banks', [
                        'status' => $newStatus,
                        'updated_at' => date('Y-m-d H:i:s')
                    ], 'id = ?', [$targetQbId]);
                    log_activity($_SESSION['user_id'], 'QBANK_STATUS_UPDATED', "Toggled status of Question Bank ID: {$targetQbId} to {$newStatus}");
                    $success = 'Question Bank status updated to ' . ucfirst($newStatus) . '.';
                }
                $qbId = $targetQbId;
            } else {
                $error = 'Unauthorized or Question Bank not found.';
            }
        }

        // 2. QUESTION LEVEL ACTIONS
        elseif ($action === 'add_question' || $action === 'edit_question') {
            $targetQbId = (int)($_POST['target_qb_id'] ?? 0);
            $qb = $db->fetch("SELECT * FROM question_banks WHERE id = ?", [$targetQbId]);
            if (!$qb) {
                $error = 'Unauthorized or Question Bank not found.';
            } else {
                $qText = trim($_POST['question_text'] ?? '');
                $optA = trim($_POST['option_a'] ?? '');
                $optB = trim($_POST['option_b'] ?? '');
                $optC = trim($_POST['option_c'] ?? '');
                $optD = trim($_POST['option_d'] ?? '');
                $correctOpt = strtoupper(trim($_POST['correct_option'] ?? 'A'));

                if (empty($qText) || empty($optA) || empty($optB) || empty($optC) || empty($optD)) {
                    $error = 'All question fields are mandatory.';
                } else {
                    if ($action === 'add_question') {
                        $db->insert('questions', [
                            'question_bank_id' => $targetQbId,
                            'question_text' => $qText,
                            'option_a' => $optA,
                            'option_b' => $optB,
                            'option_c' => $optC,
                            'option_d' => $optD,
                            'correct_option' => $correctOpt,
                            'marks' => 1
                        ]);
                        $success = 'Question added successfully.';
                    } else {
                        $qId = (int)($_POST['question_id'] ?? 0);
                        $db->update('questions', [
                            'question_text' => $qText,
                            'option_a' => $optA,
                            'option_b' => $optB,
                            'option_c' => $optC,
                            'option_d' => $optD,
                            'correct_option' => $correctOpt
                        ], 'id = ? AND question_bank_id = ?', [$qId, $targetQbId]);
                        $success = 'Question updated successfully.';
                    }
                    
                    // Update the Question Bank's updated_at timestamp
                    $db->update('question_banks', ['updated_at' => date('Y-m-d H:i:s')], 'id = ?', [$targetQbId]);
                    $qbId = $targetQbId;
                }
            }
        } elseif ($action === 'delete_question') {
            $targetQbId = (int)($_POST['target_qb_id'] ?? 0);
            $qId = (int)($_POST['question_id'] ?? 0);
            $qb = $db->fetch("SELECT * FROM question_banks WHERE id = ?", [$targetQbId]);
            if ($qb) {
                $db->delete('questions', 'id = ? AND question_bank_id = ?', [$qId, $targetQbId]);
                $db->update('question_banks', ['updated_at' => date('Y-m-d H:i:s')], 'id = ?', [$targetQbId]);
                
                // If question count falls below 25, automatically revert bank to draft
                $qCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM questions WHERE question_bank_id = ?", [$targetQbId])['cnt'] ?? 0);
                if ($qCount < 25) {
                    $db->update('question_banks', ['status' => 'draft'], 'id = ?', [$targetQbId]);
                }
                
                $success = 'Question deleted successfully.';
                $qbId = $targetQbId;
            } else {
                $error = 'Unauthorized or Question Bank not found.';
            }
        }
        
        if (!empty($success)) {
            invalidate_assessment_sync_cache($db);
        }
    }
}

// Fetch focused Question Bank details if ID is selected
$currentQb = null;
$questionsList = [];
if ($qbId > 0) {
    $currentQb = $db->fetch("SELECT * FROM question_banks WHERE id = ?", [$qbId]);
    if ($currentQb) {
        $questionsList = $db->fetchAll("SELECT * FROM questions WHERE question_bank_id = ? ORDER BY id ASC", [$qbId]);
    }
}

// Fetch ALL question banks in DB
$qBanks = $db->fetchAll(
    "SELECT qb.*, f.first_name, f.last_name,
            (SELECT COUNT(*) FROM questions WHERE question_bank_id = qb.id) as q_count
     FROM question_banks qb
     JOIN faculty f ON qb.created_by_faculty_id = f.id
     ORDER BY qb.created_at DESC"
);

// Group question banks for hierarchical view
$groupedBanks = [];
foreach ($qBanks as $qb) {
    $groupedBanks[$qb['category']][$qb['skill']][$qb['difficulty']] = $qb;
}

$skillsList = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");

$pageTitle = "Question Bank Manager - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.staggered-fade-in {
    animation: fadeInUp 0.4s ease-out forwards;
    opacity: 0;
}
.staggered-fade-in:nth-child(1) { animation-delay: 0.05s; }
.staggered-fade-in:nth-child(2) { animation-delay: 0.1s; }
.staggered-fade-in:nth-child(3) { animation-delay: 0.15s; }
.staggered-fade-in:nth-child(4) { animation-delay: 0.2s; }
.staggered-fade-in:nth-child(5) { animation-delay: 0.25s; }

.card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
}
.breadcrumb-item + .breadcrumb-item::before {
    content: ">" !important;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--text-secondary);
}
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-folder2-open text-primary me-2"></i>Question Bank Repository</h3>
        <p class="text-muted small mb-0">Manage assessment question pools organized by categories, skills, and difficulties</p>
    </div>
    <?php if ($currentQb): ?>
    <a href="<?= BASE_URL ?>faculty/question-bank.php" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 small fw-semibold">
        <i class="bi bi-arrow-left me-1"></i> Back to Repository
    </a>
    <?php endif; ?>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if ($currentQb): ?>
    <!-- BREADCRUMB HEADER -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light py-2.5 px-4 rounded-pill border shadow-sm">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>faculty/question-bank.php" class="text-decoration-none fw-semibold text-secondary"><i class="bi bi-folder2-open me-1"></i>Repository</a></li>
            <li class="breadcrumb-item text-secondary fw-semibold"><?= htmlspecialchars($currentQb['category']) ?></li>
            <li class="breadcrumb-item text-secondary fw-semibold"><?= htmlspecialchars($currentQb['skill']) ?></li>
            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page"><?= ucfirst($currentQb['difficulty']) ?> (Level <?= $currentQb['difficulty'] === 'beginner' ? '1' : ($currentQb['difficulty'] === 'intermediate' ? '2' : ($currentQb['difficulty'] === 'advanced' ? '3' : ($currentQb['difficulty'] === 'professional' ? '4' : '5'))) ?>)</li>
        </ol>
    </nav>

    <!-- QUESTION BANK DASHBOARD SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Questions -->
        <div class="col-md col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border: 1px solid var(--border) !important; min-height: 100px;">
                <div class="text-muted text-xs uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;"><i class="bi bi-question-circle text-primary me-1"></i>Total Questions</div>
                <div class="fs-3 fw-bold text-primary mt-1"><?= count($questionsList) ?></div>
            </div>
        </div>
        <!-- Card 2: Minimum Required -->
        <div class="col-md col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border: 1px solid var(--border) !important; min-height: 100px;">
                <div class="text-muted text-xs uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;"><i class="bi bi-shield-check text-secondary me-1"></i>Minimum Required</div>
                <div class="fs-3 fw-bold text-secondary mt-1">25</div>
            </div>
        </div>
        <!-- Card 3: Status -->
        <div class="col-md col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border: 1px solid var(--border) !important; min-height: 100px;">
                <div class="text-muted text-xs uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;"><i class="bi bi-activity text-warning me-1"></i>Status</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <div>
                        <?php if (count($questionsList) >= 25): ?>
                            <?php if ($currentQb['status'] === 'published'): ?>
                                <span class="badge bg-success text-white rounded-pill px-2.5 py-1 text-xs fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Published</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 text-xs fw-bold"><i class="bi bi-exclamation-circle-fill me-1"></i>Draft</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 text-xs fw-bold" title="Need at least 25 questions to publish"><i class="bi bi-x-circle-fill me-1"></i>Incomplete</span>
                        <?php endif; ?>
                    </div>
                    <form action="<?= BASE_URL ?>faculty/question-bank.php" method="POST" class="d-inline ms-2">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="toggle_publish">
                        <input type="hidden" name="target_qb_id" value="<?= $currentQb['id'] ?>">
                        <button type="submit" class="btn btn-xs rounded-pill px-2.5 py-1 <?= $currentQb['status'] === 'published' ? 'btn-outline-warning' : 'btn-success text-white' ?>" <?= (count($questionsList) < 25 && $currentQb['status'] !== 'published') ? 'disabled title="Minimum 25 questions required"' : '' ?> style="font-size: 11px; font-weight: 600;">
                            <?= $currentQb['status'] === 'published' ? 'Unpublish' : 'Publish' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Card 4: Last Updated -->
        <div class="col-md col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border: 1px solid var(--border) !important; min-height: 100px;">
                <div class="text-muted text-xs uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;"><i class="bi bi-clock-history text-info me-1"></i>Last Updated</div>
                <div class="fs-5 fw-semibold text-dark mt-2"><?= !empty($currentQb['updated_at']) ? date('d M Y', strtotime($currentQb['updated_at'])) : 'Never' ?></div>
            </div>
        </div>
        <!-- Card 5: Pool Ready -->
        <div class="col-md col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border: 1px solid var(--border) !important; min-height: 100px;">
                <div class="text-muted text-xs uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;"><i class="bi bi-check-circle text-success me-1"></i>Pool Ready</div>
                <div class="mt-2">
                    <?php if (count($questionsList) >= 25): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-bold px-3 py-1 text-xs"><i class="bi bi-check2-all me-1"></i>Yes</span>
                    <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill font-bold px-3 py-1 text-xs"><i class="bi bi-exclamation-triangle me-1"></i>No</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white" style="border: 1px solid var(--border) !important;">
        <div class="row g-3 align-items-center">
            <!-- Search Questions -->
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted" style="border: 1px solid var(--border);"><i class="bi bi-search"></i></span>
                    <input type="text" id="questionSearchInput" class="form-control bg-light border-start-0 ps-0" placeholder="Search questions content..." onkeyup="filterQuestionsTable()" style="border: 1px solid var(--border); font-size: 0.9rem;">
                </div>
            </div>
            
            <!-- Controls -->
            <div class="col-md-8 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                <button class="btn btn-primary rounded-pill px-4 py-2 fw-semibold small shadow-sm" onclick="openAddQuestionModal()">
                    <i class="bi bi-plus-lg me-1"></i> Add Question
                </button>
                <a href="<?= BASE_URL ?>faculty/question-import.php?qb_id=<?= $currentQb['id'] ?>" class="btn btn-success text-white rounded-pill px-4 py-2 fw-semibold small shadow-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i> Import Questions (CSV)
                </a>
                
                <button class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold small" onclick="exportQuestionsToCSV()">
                    <i class="bi bi-download me-1"></i> Export Questions
                </button>
            </div>
        </div>
    </div>

    <!-- QUESTION MANAGEMENT TABLE -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white" style="border: 1px solid var(--border) !important;">
        <div class="table-responsive table-responsive-card">
            <table class="table table-hover align-middle mb-0" id="questionsTable">
                <thead class="table-light text-secondary uppercase fw-bold" style="font-size: 0.72rem; border-bottom: 1px solid var(--border);">
                    <tr>
                        <th class="ps-4" style="width: 80px;">No.</th>
                        <th>Question Preview</th>
                        <th style="width: 160px;">Correct Option</th>
                        <th style="width: 140px;">Status</th>
                        <th class="text-end pe-4" style="width: 250px;">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-dark small">
                    <?php if (empty($questionsList)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="saas-empty-state py-5 text-center">
                                    <div class="saas-empty-icon mb-3 text-secondary" style="font-size: 3rem;"><i class="bi bi-file-earmark-plus"></i></div>
                                    <h5 class="fw-bold mb-1 text-dark">📄 No Questions Added Yet</h5>
                                    <p class="text-muted small mb-3">This Question Bank is currently empty.</p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-primary rounded-pill px-4 py-2 fw-semibold small shadow-sm" onclick="openAddQuestionModal()">
                                            <i class="bi bi-plus-lg me-1"></i> Add Question
                                        </button>
                                        <a href="<?= BASE_URL ?>faculty/question-import.php?qb_id=<?= $currentQb['id'] ?>" class="btn btn-success text-white rounded-pill px-4 py-2 fw-semibold small shadow-sm">
                                            <i class="bi bi-file-earmark-excel me-1"></i> Import Questions (CSV)
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($questionsList as $index => $q): ?>
                            <tr class="question-row" data-text="<?= htmlspecialchars(strtolower($q['question_text'])) ?>">
                                <td class="ps-4 fw-bold text-secondary" data-label="No.">#<?= $index + 1 ?></td>
                                <td data-label="Preview">
                                    <div class="fw-bold text-dark text-truncate mb-1" style="max-width: 550px;" title="<?= htmlspecialchars($q['question_text']) ?>">
                                        <?= htmlspecialchars($q['question_text']) ?>
                                    </div>
                                    <div class="row g-2 text-secondary text-xs mt-1" style="font-size: 0.75rem; max-width: 600px;">
                                        <div class="col-6"><strong>A:</strong> <?= htmlspecialchars($q['option_a']) ?></div>
                                        <div class="col-6"><strong>B:</strong> <?= htmlspecialchars($q['option_b']) ?></div>
                                        <div class="col-6"><strong>C:</strong> <?= htmlspecialchars($q['option_c']) ?></div>
                                        <div class="col-6"><strong>D:</strong> <?= htmlspecialchars($q['option_d']) ?></div>
                                    </div>
                                </td>
                                <td data-label="Correct Option">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded px-2.5 py-1 font-bold">
                                        Option <?= htmlspecialchars($q['correct_option']) ?>
                                    </span>
                                </td>
                                <td data-label="Status">
                                    <span class="badge bg-light text-secondary border rounded px-2.5 py-1">Active</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 text-xs fw-semibold" onclick="previewQuestion(<?= htmlspecialchars(json_encode($q)) ?>)">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning rounded-pill px-3 py-1 text-xs fw-semibold" onclick="openEditQuestionModal(<?= htmlspecialchars(json_encode($q)) ?>)">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form action="<?= BASE_URL ?>faculty/question-bank.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="delete_question">
                                            <input type="hidden" name="target_qb_id" value="<?= $currentQb['id'] ?>">
                                            <input type="hidden" name="question_id" value="<?= $q['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 text-xs fw-semibold">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <!-- HIERARCHICAL REPOSITORY VIEW -->
    <?php
    $categoriesStructure = [
        'Frontend Development' => ['HTML', 'CSS', 'JavaScript', 'Bootstrap', 'Tailwind CSS', 'React', 'Angular', 'Vue.js', 'jQuery', 'TypeScript'],
        'Backend Development' => ['C', 'C++', 'Java', 'Python', 'PHP', 'C#', 'Node.js', 'SQL', 'MySQL', 'MongoDB'],
        'Full Stack Development' => ['MERN Stack', 'MEAN Stack', 'Laravel', 'Django', 'Express.js', 'Next.js', 'ASP.NET', 'Spring Boot', 'Flask', 'REST API']
    ];
    ?>
    
    <div class="accordion saas-accordion" id="categoryAccordion">
        <?php $catIdx = 0; foreach ($categoriesStructure as $categoryName => $skillsListForCat): $catIdx++; ?>
            <div class="accordion-item border-0 mb-4 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid var(--border) !important; background: var(--bg-card);">
                <h2 class="accordion-header" id="headingCat<?= $catIdx ?>">
                    <button class="accordion-button fw-bold fs-5 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCat<?= $catIdx ?>" aria-expanded="true" aria-controls="collapseCat<?= $catIdx ?>" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
                        <i class="bi bi-folder2-open me-2"></i> <?= htmlspecialchars($categoryName) ?>
                    </button>
                </h2>
                <div id="collapseCat<?= $catIdx ?>" class="accordion-collapse collapse show" aria-labelledby="headingCat<?= $catIdx ?>">
                    <div class="accordion-body p-4">
                        
                        <!-- Per-Category Skill Selector -->
                        <div class="mb-4 pb-3 border-bottom d-flex flex-column flex-md-row align-items-md-center gap-3">
                            <div style="min-width: 150px;">
                                <label class="form-label small fw-bold text-secondary uppercase mb-0">Select Skill:</label>
                            </div>
                            <div class="flex-grow-1">
                                <select class="saas-form-select py-2 w-100 select-skill-trigger" data-category-idx="<?= $catIdx ?>" onchange="toggleCategorySkills(this, <?= $catIdx ?>)">
                                    <?php 
                                    $firstSkill = reset($skillsListForCat);
                                    foreach ($skillsListForCat as $skillOpt): 
                                    ?>
                                        <option value="<?= htmlspecialchars($skillOpt) ?>" <?= ($skillOpt === $firstSkill) ? 'selected' : '' ?>><?= htmlspecialchars($skillOpt) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Professional Empty State / Placeholder -->
                        <div id="placeholderCat<?= $catIdx ?>" class="saas-empty-state py-5 text-center bg-light rounded-3 border border-dashed" style="display: none;">
                            <div class="saas-empty-icon mb-2 text-secondary" style="font-size: 2.5rem;"><i class="bi bi-info-circle"></i></div>
                            <h6 class="fw-bold mb-1 text-dark">Select a skill to view its Question Pools.</h6>
                            <p class="text-muted small mb-0">Choose a specific topic from the selector above to manage question pools.</p>
                        </div>

                        <!-- Skill Question Banks Wrappers -->
                        <?php 
                        $firstSkill = reset($skillsListForCat);
                        foreach ($skillsListForCat as $skillName): 
                            $skillSlug = preg_replace('/[^a-z0-9]+/', '-', strtolower($skillName));
                            $isDefault = ($skillName === $firstSkill);
                        ?>
                            <div class="skill-banks-wrapper" id="banks-cat-<?= $catIdx ?>-<?= $skillSlug ?>" style="<?= $isDefault ? 'display: block; opacity: 1; max-height: 1000px;' : 'display: none; opacity: 0;' ?>">
                                <h5 class="fw-bold mb-3 text-indigo d-flex align-items-center">
                                    <i class="bi bi-code-square me-2"></i>
                                    <?= htmlspecialchars($skillName) ?> Question Pools
                                </h5>
                                
                                <div class="row row-cols-1 row-cols-md-5 g-3">
                                    <?php 
                                    $diffLevels = [
                                        'beginner' => 'Beginner (Level 1)',
                                        'intermediate' => 'Intermediate (Level 2)',
                                        'advanced' => 'Advanced (Level 3)',
                                        'professional' => 'Professional (Level 4)',
                                        'expert' => 'Expert (Level 5)'
                                    ];
                                    foreach ($diffLevels as $diffKey => $diffLabel): 
                                        $qb = $groupedBanks[$categoryName][$skillName][$diffKey] ?? null;
                                        $qCount = $qb ? (int)$qb['q_count'] : 0;
                                        $progressPct = min(100, round(($qCount / 25) * 100));
                                        $realPct = round(($qCount / 25) * 100);
                                        $progressColor = $qCount >= 25 ? 'bg-success' : ($qCount > 0 ? 'bg-warning' : 'bg-danger');
                                    ?>
                                        <div class="col staggered-fade-in">
                                            <div class="card h-100 border p-3 rounded-4 shadow-sm transition-all bg-white" style="min-height: 240px; border: 1px solid var(--border) !important;">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <?php if ($qCount >= 25): ?>
                                                        <?php if ($qb && $qb['status'] === 'published'): ?>
                                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1 text-xs fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 6px; vertical-align: middle;"></i>Published</span>
                                                        <?php else: ?>
                                                            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 text-xs fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 6px; vertical-align: middle;"></i>Draft</span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 text-xs fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 6px; vertical-align: middle;"></i>Incomplete</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="small fw-bold text-dark text-truncate mb-1" style="font-size: 0.95rem;"><?= $diffLabel ?></div>
                                                
                                                <!-- Pool Stats -->
                                                <div class="text-secondary mb-2" style="font-size: 0.8rem;">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span>Pool size:</span>
                                                        <strong><?= $qCount ?> / 25 Questions</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span>Min Required:</span>
                                                        <strong>25</strong>
                                                    </div>
                                                    <?php if ($qb && !empty($qb['updated_at'])): ?>
                                                        <div class="mt-2 text-muted text-xs">
                                                            Last Updated: <?= date('d M Y', strtotime($qb['updated_at'])) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Progress Indicator -->
                                                <div class="mb-3">
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar <?= $progressColor ?>" role="progressbar" style="width: <?= $progressPct ?>%" aria-valuenow="<?= $progressPct ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <div class="d-flex justify-content-between text-muted mt-1" style="font-size: 0.72rem;">
                                                        <span>Progress</span>
                                                        <span class="fw-bold text-dark"><?= $realPct ?>%</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Buttons -->
                                                <div class="mt-auto">
                                                    <?php if ($qb): ?>
                                                        <a href="<?= BASE_URL ?>faculty/question-bank.php?qb_id=<?= $qb['id'] ?>" class="btn btn-sm btn-primary rounded-pill w-100 py-1.5 font-semibold text-xs mt-1">
                                                            <i class="bi bi-gear-fill me-1"></i> Manage Question Bank
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- MODALS -->

<!-- 1. Question Add/Edit Modal -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="<?= BASE_URL ?>faculty/question-bank.php" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action" id="qModalAction" value="add_question">
                <input type="hidden" name="target_qb_id" value="<?= $qbId ?>">
                <input type="hidden" name="question_id" id="qModalId" value="0">
                <div class="modal-header border-bottom py-3">
                    <h5 class="fw-bold mb-0 text-dark" id="qModalTitle">Add Question to Pool</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark mb-1">Question Content / Text</label>
                        <textarea name="question_text" id="qModalText" class="saas-form-control w-100" rows="3" placeholder="Enter standard technical question details..." required></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark mb-1">Option A</label>
                            <input type="text" name="option_a" id="qModalOptA" class="saas-form-control w-100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark mb-1">Option B</label>
                            <input type="text" name="option_b" id="qModalOptB" class="saas-form-control w-100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark mb-1">Option C</label>
                            <input type="text" name="option_c" id="qModalOptC" class="saas-form-control w-100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark mb-1">Option D</label>
                            <input type="text" name="option_d" id="qModalOptD" class="saas-form-control w-100" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label fw-bold text-dark mb-1">Correct Option</label>
                        <select name="correct_option" id="qModalCorrect" class="saas-form-select w-100" required>
                            <option value="A">Option A</option>
                            <option value="B">Option B</option>
                            <option value="C">Option C</option>
                            <option value="D">Option D</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 justify-content-end">
                    <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Save Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. Question Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="fw-bold mb-0 text-dark">Question Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="fw-semibold text-dark mb-3" id="prevText" style="font-size: 1.05rem;"></div>
                <div class="d-flex flex-column gap-2 mb-3">
                    <div class="p-2.5 rounded border bg-light text-dark" id="prevOptA"></div>
                    <div class="p-2.5 rounded border bg-light text-dark" id="prevOptB"></div>
                    <div class="p-2.5 rounded border bg-light text-dark" id="prevOptC"></div>
                    <div class="p-2.5 rounded border bg-light text-dark" id="prevOptD"></div>
                </div>
                <div class="alert alert-success border-0 py-2.5 px-3 rounded-3 mb-0 small">
                    <i class="bi bi-check-circle-fill me-1"></i> Correct Option: <strong id="prevCorrect"></strong>
                </div>
            </div>
            <div class="modal-footer border-top p-3 justify-content-end">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCategorySkills(selectElem, catIdx) {
    const selectedSkill = selectElem.value;
    const placeholder = document.getElementById('placeholderCat' + catIdx);
    
    // Normalize skill name to match wrapper ID format
    const idFriendlySkillName = selectedSkill.toLowerCase().replace(/[^a-z0-9]+/g, '-');
    const targetId = 'banks-cat-' + catIdx + '-' + idFriendlySkillName;
    const targetWrapper = selectedSkill ? document.getElementById(targetId) : null;

    // Find all wrappers inside this accordion-body
    const wrappers = selectElem.closest('.accordion-body').querySelectorAll('.skill-banks-wrapper');
    
    wrappers.forEach(w => {
        if (w !== targetWrapper) {
            w.style.opacity = '0';
            w.style.maxHeight = '0';
            w.style.display = 'none';
        }
    });
    
    if (!selectedSkill) {
        if (placeholder) {
            placeholder.style.display = 'block';
            placeholder.style.opacity = '1';
        }
        localStorage.removeItem('selected_qb_skill_cat_' + catIdx);
    } else {
        if (placeholder) {
            placeholder.style.display = 'none';
        }
        
        if (targetWrapper) {
            targetWrapper.style.display = 'block';
            // Force browser layout reflow to register style changes before starting transition
            targetWrapper.offsetHeight;
            targetWrapper.style.transition = 'opacity 0.3s ease-in-out, max-height 0.4s ease-in-out';
            targetWrapper.style.maxHeight = '1000px';
            targetWrapper.style.opacity = '1';
            
            // Persist the state in localStorage per category
            localStorage.setItem('selected_qb_skill_cat_' + catIdx, selectedSkill);
        }
    }
}

// Restore saved skill selection on page load
document.addEventListener('DOMContentLoaded', () => {
    for (let catIdx = 1; catIdx <= 3; catIdx++) {
        const savedSkill = localStorage.getItem('selected_qb_skill_cat_' + catIdx);
        if (savedSkill) {
            const selectElem = document.querySelector(`.select-skill-trigger[data-category-idx="${catIdx}"]`);
            if (selectElem) {
                selectElem.value = savedSkill;
                toggleCategorySkills(selectElem, catIdx);
            }
        }
    }
});

function openAddQuestionFromTree(qbId) {
    document.querySelector('#questionModal [name="target_qb_id"]').value = qbId;
    
    document.getElementById('qModalAction').value = 'add_question';
    document.getElementById('qModalId').value = '0';
    document.getElementById('qModalTitle').textContent = 'Add Question to Pool';
    document.getElementById('qModalText').value = '';
    document.getElementById('qModalOptA').value = '';
    document.getElementById('qModalOptB').value = '';
    document.getElementById('qModalOptC').value = '';
    document.getElementById('qModalOptD').value = '';
    document.getElementById('qModalCorrect').value = 'A';
    
    const myModal = new bootstrap.Modal(document.getElementById('questionModal'));
    myModal.show();
}

function openAddQuestionModal() {
    document.getElementById('qModalAction').value = 'add_question';
    document.getElementById('qModalId').value = '0';
    document.getElementById('qModalTitle').textContent = 'Add Question to Pool';
    document.getElementById('qModalText').value = '';
    document.getElementById('qModalOptA').value = '';
    document.getElementById('qModalOptB').value = '';
    document.getElementById('qModalOptC').value = '';
    document.getElementById('qModalOptD').value = '';
    document.getElementById('qModalCorrect').value = 'A';
    
    const myModal = new bootstrap.Modal(document.getElementById('questionModal'));
    myModal.show();
}

function openEditQuestionModal(q) {
    document.getElementById('qModalAction').value = 'edit_question';
    document.getElementById('qModalId').value = q.id;
    document.getElementById('qModalTitle').textContent = 'Edit Question';
    document.getElementById('qModalText').value = q.question_text;
    document.getElementById('qModalOptA').value = q.option_a;
    document.getElementById('qModalOptB').value = q.option_b;
    document.getElementById('qModalOptC').value = q.option_c;
    document.getElementById('qModalOptD').value = q.option_d;
    document.getElementById('qModalCorrect').value = q.correct_option;
    
    const myModal = new bootstrap.Modal(document.getElementById('questionModal'));
    myModal.show();
}

function previewQuestion(q) {
    document.getElementById('prevText').textContent = q.question_text;
    document.getElementById('prevOptA').textContent = 'A) ' + q.option_a;
    document.getElementById('prevOptB').textContent = 'B) ' + q.option_b;
    document.getElementById('prevOptC').textContent = 'C) ' + q.option_c;
    document.getElementById('prevOptD').textContent = 'D) ' + q.option_d;
    document.getElementById('prevCorrect').textContent = q.correct_option;
    
    const myModal = new bootstrap.Modal(document.getElementById('previewModal'));
    myModal.show();
}

function filterQuestionsTable() {
    const query = document.getElementById('questionSearchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.question-row');
    rows.forEach(row => {
        const text = row.getAttribute('data-text');
        if (text.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function exportQuestionsToCSV() {
    const questions = <?php echo json_encode($questionsList); ?>;
    if (!questions || questions.length === 0) {
        alert('No questions in the pool to export.');
        return;
    }
    
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Question Text,Option A,Option B,Option C,Option D,Correct Option\n";
    
    questions.forEach(q => {
        let text = '"' + q.question_text.replace(/"/g, '""') + '"';
        let optA = '"' + q.option_a.replace(/"/g, '""') + '"';
        let optB = '"' + q.option_b.replace(/"/g, '""') + '"';
        let optC = '"' + q.option_c.replace(/"/g, '""') + '"';
        let optD = '"' + q.option_d.replace(/"/g, '""') + '"';
        let correct = '"' + q.correct_option + '"';
        csvContent += `${text},${optA},${optB},${optC},${optD},${correct}\n`;
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `Export_Pool_Questions.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
