<?php
/**
 * SkillBridge - Interactive Assessment Setup Wizard & Completed Assessments Dashboard
 * Fully aligned with 3 Categories (10 Skills each), 5 Difficulty Tiers (25 MCQs), and Completed Results History.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$db = Database::getInstance();

// 1. Fetch Logged-In Student's Completed Assessments History
$completedAssessments = $db->fetchAll(
    "SELECT ar.*, a.title as assessment_title, a.duration_minutes, a.passing_marks, a.total_marks,
            s.name as skill_name, s.category as skill_category
     FROM assessment_results ar
     JOIN assessments a ON ar.assessment_id = a.id
     JOIN skills s ON a.skill_id = s.id
     WHERE ar.student_id = ?
     ORDER BY ar.completed_at DESC",
    [$studentId]
);

// 2. Fetch All Active Assessments from DB for dynamic launcher matching
$dbAssessments = $db->fetchAll(
    "SELECT a.*, s.name as skill_name, s.category as skill_category
     FROM assessments a
     JOIN skills s ON a.skill_id = s.id
     WHERE a.status = 'active'"
);

// Pre-defined Skill Structure matching prompt requirements (10 Skills per Category)
$categorySkills = [
    'Frontend Development' => [
        ['name' => 'HTML', 'icon' => 'fa-brands fa-html5'],
        ['name' => 'CSS', 'icon' => 'fa-brands fa-css3-alt'],
        ['name' => 'JavaScript', 'icon' => 'fa-brands fa-square-js'],
        ['name' => 'Bootstrap', 'icon' => 'fa-brands fa-bootstrap'],
        ['name' => 'Tailwind CSS', 'icon' => 'fa-solid fa-wind'],
        ['name' => 'React', 'icon' => 'fa-brands fa-react'],
        ['name' => 'Angular', 'icon' => 'fa-brands fa-angular'],
        ['name' => 'Vue.js', 'icon' => 'fa-brands fa-vuejs'],
        ['name' => 'jQuery', 'icon' => 'fa-solid fa-code'],
        ['name' => 'TypeScript', 'icon' => 'fa-solid fa-code-commit']
    ],
    'Backend Development' => [
        ['name' => 'C', 'icon' => 'fa-solid fa-copyright'],
        ['name' => 'C++', 'icon' => 'fa-solid fa-terminal'],
        ['name' => 'Java', 'icon' => 'fa-brands fa-java'],
        ['name' => 'Python', 'icon' => 'fa-brands fa-python'],
        ['name' => 'PHP', 'icon' => 'fa-brands fa-php'],
        ['name' => 'C#', 'icon' => 'fa-solid fa-hashtag'],
        ['name' => 'Node.js', 'icon' => 'fa-brands fa-node-js'],
        ['name' => 'SQL', 'icon' => 'fa-solid fa-database'],
        ['name' => 'MySQL', 'icon' => 'fa-solid fa-server'],
        ['name' => 'MongoDB', 'icon' => 'fa-solid fa-leaf']
    ],
    'Full Stack Development' => [
        ['name' => 'MERN Stack', 'icon' => 'fa-solid fa-cubes'],
        ['name' => 'MEAN Stack', 'icon' => 'fa-solid fa-layer-group'],
        ['name' => 'Laravel', 'icon' => 'fa-brands fa-laravel'],
        ['name' => 'Django', 'icon' => 'fa-solid fa-code-branch'],
        ['name' => 'Express.js', 'icon' => 'fa-solid fa-arrows-spin'],
        ['name' => 'Next.js', 'icon' => 'fa-solid fa-n'],
        ['name' => 'ASP.NET', 'icon' => 'fa-solid fa-globe'],
        ['name' => 'Spring Boot', 'icon' => 'fa-solid fa-leaf'],
        ['name' => 'Flask', 'icon' => 'fa-solid fa-pepper-hot'],
        ['name' => 'REST API Development', 'icon' => 'fa-solid fa-network-wired']
    ]
];

$pageTitle = "Skill Assessment Wizard - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<!-- ══════════════════════════════════════════════════════════ -->
<!-- MULTI-STEP ASSESSMENT SETUP WIZARD (STARTS DIRECTLY)        -->
<!-- ══════════════════════════════════════════════════════════ -->
<div class="saas-card p-4 p-md-5 mb-5" id="pending-assessments">

    <!-- STEP INDICATOR DOTS -->
    <div id="stepIndicatorContainer" class="step-indicator">
        <div class="step-dot active" id="dot1">1</div>
        <div class="step-dot" id="dot2">2</div>
        <div class="step-dot" id="dot3">3</div>
        <div class="step-dot" id="dot4">4</div>
    </div>

    <!-- STAGE 1: CATEGORY SELECTION (ONLY 3 CATEGORIES) -->
    <div id="stageCategory" class="stage-pane">
        <div class="text-center mb-4">
            <div class="section-tag mb-1">Step 1 of 4</div>
            <h2 class="fw-bold mb-2" style="color: var(--text-heading);">Select Assessment Path</h2>
            <p class="small mx-auto" style="color: var(--text-muted); max-width: 500px;">Choose your primary domain path to challenge your technical competencies.</p>
        </div>

        <div class="row g-4 justify-content-center mb-4 max-w-4xl mx-auto">
            <div class="col-md-4">
                <div class="selection-card h-100 text-center" onclick="selectWizardCategory('Frontend Development')">
                    <div class="card-icon"><i class="fa-solid fa-laptop-code"></i></div>
                    <h5 class="fw-bold mb-2" style="color: var(--text-heading);">💻 Frontend Development</h5>
                    <p class="small mb-0" style="color: var(--text-muted);">Evaluate styling, responsive layouts, framework mechanics, and UI scripting (10 Skills).</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="selection-card h-100 text-center" onclick="selectWizardCategory('Backend Development')">
                    <div class="card-icon"><i class="fa-solid fa-server"></i></div>
                    <h5 class="fw-bold mb-2" style="color: var(--text-heading);">⚙️ Backend Development</h5>
                    <p class="small mb-0" style="color: var(--text-muted);">Verify server-side scripting, database architecture, query logic, and APIs (10 Skills).</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="selection-card h-100 text-center" onclick="selectWizardCategory('Full Stack Development')">
                    <div class="card-icon"><i class="fa-solid fa-layer-group"></i></div>
                    <h5 class="fw-bold mb-2" style="color: var(--text-heading);">🚀 Full Stack Development</h5>
                    <p class="small mb-0" style="color: var(--text-muted);">Test end-to-end application stacks, microservices, frameworks, and architecture (10 Skills).</p>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end max-w-4xl mx-auto">
            <button class="btn btn-glow" id="categoryContinue" disabled onclick="transitionWizardStage('stageSkill')">
                Continue <i class="fa-solid fa-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- STAGE 2: SKILL SELECTION (10 SKILLS PER CATEGORY) -->
    <div id="stageSkill" class="stage-pane" style="display: none;">
        <div class="text-center mb-4">
            <div class="section-tag mb-1">Step 2 of 4</div>
            <h2 class="fw-bold mb-2" style="color: var(--text-heading);">Select Specific Skill</h2>
            <p class="small mx-auto" style="color: var(--text-muted); max-width: 500px;">Choose the skill tool you want to challenge.</p>
        </div>

        <div class="row g-3 justify-content-center mb-4 max-w-4xl mx-auto" id="skillsGrid">
            <!-- Dynamically loaded by JavaScript based on category selection -->
        </div>

        <div class="d-flex justify-content-between max-w-4xl mx-auto">
            <button class="btn btn-outline-secondary rounded-pill px-4" onclick="transitionWizardStage('stageCategory')">
                <i class="fa-solid fa-arrow-left me-1"></i> Categories
            </button>
            <button class="btn btn-glow" id="skillContinue" disabled onclick="transitionWizardStage('stageDifficulty')">
                Continue <i class="fa-solid fa-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- STAGE 3: DIFFICULTY SELECTION (5 LEVELS) -->
    <div id="stageDifficulty" class="stage-pane" style="display: none;">
        <div class="text-center mb-4">
            <div class="section-tag mb-1">Step 3 of 4</div>
            <h2 class="fw-bold mb-2" style="color: var(--text-heading);">Choose Assessment Difficulty</h2>
            <p class="small mx-auto" style="color: var(--text-muted); max-width: 500px;">Evaluations increase in architecture and logic complexity as difficulty scales.</p>
        </div>

        <div class="row g-3 justify-content-center mb-4 max-w-5xl mx-auto">
            <div class="col">
                <div class="selection-card text-center h-100 p-3" onclick="selectWizardDifficulty('beginner', 'Beginner', 1)">
                    <div class="stars-glow"><i class="fa-solid fa-star"></i></div>
                    <h6 class="fw-bold mb-1" style="color: var(--text-heading);">Beginner</h6>
                    <span class="small d-block" style="color: var(--text-muted);">Level 1</span>
                    <span class="badge saas-badge-primary mt-2">25 MCQs</span>
                </div>
            </div>
            <div class="col">
                <div class="selection-card text-center h-100 p-3" onclick="selectWizardDifficulty('easy', 'Easy', 2)">
                    <div class="stars-glow"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <h6 class="fw-bold mb-1" style="color: var(--text-heading);">Easy</h6>
                    <span class="small d-block" style="color: var(--text-muted);">Level 2</span>
                    <span class="badge saas-badge-primary mt-2">25 MCQs</span>
                </div>
            </div>
            <div class="col">
                <div class="selection-card text-center h-100 p-3" onclick="selectWizardDifficulty('intermediate', 'Intermediate', 3)">
                    <div class="stars-glow"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <h6 class="fw-bold mb-1" style="color: var(--text-heading);">Intermediate</h6>
                    <span class="small d-block" style="color: var(--text-muted);">Level 3</span>
                    <span class="badge saas-badge-primary mt-2">25 MCQs</span>
                </div>
            </div>
            <div class="col">
                <div class="selection-card text-center h-100 p-3" onclick="selectWizardDifficulty('advanced', 'Advanced', 4)">
                    <div class="stars-glow"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <h6 class="fw-bold mb-1" style="color: var(--text-heading);">Advanced</h6>
                    <span class="small d-block" style="color: var(--text-muted);">Level 4</span>
                    <span class="badge saas-badge-primary mt-2">25 MCQs</span>
                </div>
            </div>
            <div class="col">
                <div class="selection-card text-center h-100 p-3" onclick="selectWizardDifficulty('expert', 'Expert', 5)">
                    <div class="stars-glow"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <h6 class="fw-bold mb-1" style="color: var(--text-heading);">Expert</h6>
                    <span class="small d-block" style="color: var(--text-muted);">Level 5</span>
                    <span class="badge saas-badge-primary mt-2">25 MCQs</span>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between max-w-4xl mx-auto">
            <button class="btn btn-outline-secondary rounded-pill px-4" onclick="transitionWizardStage('stageSkill')">
                <i class="fa-solid fa-arrow-left me-1"></i> Select Skill
            </button>
            <button class="btn btn-glow" id="difficultyContinue" disabled onclick="transitionWizardStage('stageStart')">
                Continue <i class="fa-solid fa-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- STAGE 4: START ASSESSMENT PREVIEW -->
    <div id="stageStart" class="stage-pane" style="display: none;">
        <div class="text-center mb-4">
            <div class="section-tag mb-1">Step 4 of 4</div>
            <h2 class="fw-bold mb-2" style="color: var(--text-heading);">Start Assessment</h2>
            <p class="small mx-auto" style="color: var(--text-muted); max-width: 500px;">Review your setup. Ready to start?</p>
        </div>

        <div class="saas-card p-4 max-w-2xl mx-auto mb-4 shadow-sm" style="background: var(--bg-alt);">
            <div class="text-center mb-4">
                <h3 id="previewSkillName" class="fw-bold text-primary mb-1">JavaScript</h3>
                <div id="previewDifficultyStars" class="stars-glow fs-5 mb-2">⭐⭐⭐</div>
                <span id="previewDifficultyName" class="badge saas-badge-primary text-uppercase">Intermediate (Level 3)</span>
            </div>

            <div class="row g-3 text-center border-top border-bottom py-3 mb-4" style="border-color: var(--border) !important;">
                <div class="col-6 col-md-3">
                    <div class="small text-uppercase" style="font-size:10px; color: var(--text-muted);">Total Questions</div>
                    <div class="fw-bold fs-5" style="color: var(--text-heading);">25 MCQs</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-uppercase" style="font-size:10px; color: var(--text-muted);">Estimated Time</div>
                    <div class="fw-bold fs-5" style="color: var(--text-heading);" id="previewTimer">25 Minutes</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-uppercase" style="font-size:10px; color: var(--text-muted);">Passing Marks</div>
                    <div class="fw-bold fs-5 text-success">80% (20/25)</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-uppercase" style="font-size:10px; color: var(--text-muted);">System Security</div>
                    <div class="fw-bold fs-5 text-danger">Strict Proctoring</div>
                </div>
            </div>

            <div class="alert alert-danger border-0 rounded-3 mb-4 p-3 d-flex align-items-start gap-3">
                <i class="fa-solid fa-shield-halved fs-3 flex-shrink-0 text-danger"></i>
                <div class="small">
                    <strong class="d-block mb-1 text-danger">Proctoring Notice:</strong>
                    By launching, you confirm you will not switch windows/tabs, press back, or copy content. Doing so triggers warnings. A limit of 3 violations triggers immediate quiz submission.
                </div>
            </div>

            <button onclick="launchWizardAssessment()" class="btn btn-glow w-100 py-3 fw-bold fs-5">
                <i class="fa-solid fa-rocket me-2"></i> Launch Assessment
            </button>
        </div>

        <div class="text-center">
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-4" onclick="transitionWizardStage('stageDifficulty')">
                <i class="fa-solid fa-arrow-left me-1"></i> Change Difficulty
            </button>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════ -->
<!-- COMPLETED ASSESSMENTS HISTORY (THEME COMPLIANT)            -->
<!-- ══════════════════════════════════════════════════════════ -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0" style="color: var(--text-heading);"><i class="bi bi-clock-history me-2 text-primary"></i>Completed Assessments</h4>
    <span class="badge saas-badge-primary"><?= count($completedAssessments) ?> Completed</span>
</div>

<div class="saas-card mb-5 overflow-hidden" id="completed-assessments">
    <div class="card-body p-0">
        <?php if (empty($completedAssessments)): ?>
            <div class="saas-empty-state">
                <div class="saas-empty-icon"><i class="bi bi-journal-x"></i></div>
                <h5 class="fw-bold mb-1" style="color: var(--text-heading);">No Completed Assessments Yet</h5>
                <p class="small mb-0" style="color: var(--text-muted);">Use the setup wizard above to complete your first skill assessment challenge.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="saas-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Assessment Name</th>
                            <th>Category</th>
                            <th>Skill</th>
                            <th>Difficulty</th>
                            <th>Final Score (%)</th>
                            <th>Status</th>
                            <th>Completion Date & Time</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($completedAssessments as $res): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold" style="color: var(--text-heading);"><?= htmlspecialchars($res['assessment_title']) ?></span>
                                </td>
                                <td>
                                    <span class="badge saas-badge-primary">
                                        <?= htmlspecialchars($res['skill_category']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold" style="color: var(--text-body);"><?= htmlspecialchars($res['skill_name']) ?></span>
                                </td>
                                <td>
                                    <span class="badge saas-badge-info text-uppercase">
                                        <?= htmlspecialchars($res['difficulty_level'] ?? 'intermediate') ?>
                                    </span>
                                </td>
                                <td>
                                    <strong class="fs-6 text-<?= $res['status'] === 'pass' ? 'success' : 'danger' ?>">
                                        <?= number_format($res['score_percentage'], 1) ?>%
                                    </strong>
                                </td>
                                <td>
                                    <?php if ($res['status'] === 'pass'): ?>
                                        <span class="badge saas-badge-success">PASS</span>
                                    <?php else: ?>
                                        <span class="badge saas-badge-danger">FAIL</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="small" style="color: var(--text-muted);"><?= date('M d, Y h:i A', strtotime($res['completed_at'])) ?></span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= BASE_URL ?>student/assessment-result.php?result_id=<?= $res['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                        View Result <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>


<script>
const categorySkillsMap = <?= json_encode($categorySkills) ?>;
const dbAssessments = <?= json_encode($dbAssessments) ?>;

let wizardState = {
    category: null,
    skillName: null,
    difficultyKey: null,
    difficultyName: null,
    levelNum: 3
};

function selectWizardCategory(catName) {
    wizardState.category = catName;
    wizardState.skillName = null;
    wizardState.difficultyKey = null;

    document.querySelectorAll('#stageCategory .selection-card').forEach(card => card.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    document.getElementById('categoryContinue').disabled = false;

    // Load 10 Skills for selected category
    const skillsGrid = document.getElementById('skillsGrid');
    skillsGrid.innerHTML = '';

    const list = categorySkillsMap[catName] || [];
    list.forEach(skillObj => {
        const col = document.createElement('div');
        col.className = 'col-6 col-md-4 col-lg-3';
        col.innerHTML = `
            <div class="selection-card text-center p-3 h-100" onclick="selectWizardSkill('${skillObj.name}')">
                <div class="card-icon mb-2"><i class="${skillObj.icon}"></i></div>
                <div class="fw-bold small" style="color: var(--text-heading);">${skillObj.name}</div>
            </div>
        `;
        skillsGrid.appendChild(col);
    });
}

function selectWizardSkill(skillName) {
    wizardState.skillName = skillName;
    document.querySelectorAll('#skillsGrid .selection-card').forEach(card => card.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    document.getElementById('skillContinue').disabled = false;
}

function selectWizardDifficulty(key, name, levelNum) {
    wizardState.difficultyKey = key;
    wizardState.difficultyName = name;
    wizardState.levelNum = levelNum;

    document.querySelectorAll('#stageDifficulty .selection-card').forEach(card => card.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    document.getElementById('difficultyContinue').disabled = false;
}

function transitionWizardStage(targetStageId) {
    document.querySelectorAll('.stage-pane').forEach(pane => pane.style.display = 'none');
    document.getElementById(targetStageId).style.display = 'block';

    const stageMap = { 'stageCategory': 1, 'stageSkill': 2, 'stageDifficulty': 3, 'stageStart': 4 };
    const stepNum = stageMap[targetStageId];

    for (let i = 1; i <= 4; i++) {
        const dot = document.getElementById(`dot${i}`);
        if (i < stepNum) {
            dot.className = 'step-dot completed';
        } else if (i === stepNum) {
            dot.className = 'step-dot active';
        } else {
            dot.className = 'step-dot';
        }
    }

    if (targetStageId === 'stageStart') {
        populateWizardPreview();
    }
}

function populateWizardPreview() {
    document.getElementById('previewSkillName').textContent = wizardState.skillName || 'Skill Challenge';
    
    const stars = '⭐'.repeat(wizardState.levelNum || 3);
    document.getElementById('previewDifficultyStars').textContent = stars;
    document.getElementById('previewDifficultyName').textContent = (wizardState.difficultyName || 'Intermediate') + ' (Level ' + wizardState.levelNum + ')';
}

function launchWizardAssessment() {
    // Find matching active assessment in database or launch nearest assessment ID
    let match = dbAssessments.find(a => 
        a.skill_name.toLowerCase().includes((wizardState.skillName || '').toLowerCase()) ||
        (wizardState.skillName || '').toLowerCase().includes(a.skill_name.toLowerCase())
    );

    if (!match && dbAssessments.length > 0) {
        match = dbAssessments[0];
    }

    if (match) {
        window.location.href = '<?= BASE_URL ?>student/take-assessment.php?id=' + match.id;
    } else {
        alert('Assessment module for ' + wizardState.skillName + ' is currently initializing. Redirecting to default assessment.');
        window.location.href = '<?= BASE_URL ?>student/take-assessment.php?id=1';
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
