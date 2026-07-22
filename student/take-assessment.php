<?php
/**
 * SkillBridge - Live Interactive Assessment Taking Interface (25 Questions Engine)
 * Strictly 1 Result Insert Per Attempt with Server-Side Deduplication Lock & Anti-Cheat Proctoring
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$assessmentId = (int)($_GET['id'] ?? $_POST['assessment_id'] ?? 0);

$db = Database::getInstance();

$assessment = $db->fetch(
    "SELECT a.*, COALESCE(s.name, 'General Technical') as skill_name 
     FROM assessments a 
     LEFT JOIN skills s ON a.skill_id = s.id 
     WHERE a.id = ? AND a.status = 'active'", 
    [$assessmentId]
);

if (!$assessment) {
    set_flash_message('danger', 'Assessment not found or inactive.');
    redirect(BASE_URL . 'student/assessments.php');
}

// Fetch all 25 questions for this assessment
$questions = $db->fetchAll("SELECT * FROM assessment_questions WHERE assessment_id = ? ORDER BY id ASC", [$assessmentId]);

if (empty($questions)) {
    set_flash_message('warning', 'This assessment has no questions configured yet.');
    redirect(BASE_URL . 'student/assessments.php');
}

// ══════════════════════════════════════════════════════════════════════
// HANDLE ASSESSMENT FORM SUBMISSION (ONLY GENERATED AFTER ALL QUESTIONS SUBMITTED)
// ══════════════════════════════════════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_assessment'])) {

    // Server-Side Deduplication Lock: Check if submitted within the last 15 seconds to prevent duplicate inserts
    $recentResult = $db->fetch(
        "SELECT id FROM assessment_results 
         WHERE student_id = ? AND assessment_id = ? AND completed_at >= DATE_SUB(NOW(), INTERVAL 15 SECOND) 
         ORDER BY id DESC LIMIT 1",
        [$studentId, $assessmentId]
    );

    if ($recentResult) {
        redirect(BASE_URL . 'student/assessment-result.php?result_id=' . $recentResult['id']);
        exit;
    }

    $timeTaken = (int)($_POST['time_taken_seconds'] ?? 0);
    $answers = $_POST['answers'] ?? []; // format [question_id => selected_option]

    $correctCount = 0;
    $totalQuestions = count($questions); // Exactly 25 questions
    $marksObtained = 0;
    $totalPossibleMarks = 0;

    foreach ($questions as $q) {
        $qId = $q['id'];
        $correctOption = strtoupper(trim($q['correct_option']));
        $selectedOption = isset($answers[$qId]) ? strtoupper(trim($answers[$qId])) : null;
        $qMarks = (int)$q['marks'];
        $totalPossibleMarks += $qMarks;

        $isCorrect = ($selectedOption === $correctOption) ? 1 : 0;
        if ($isCorrect) {
            $correctCount++;
            $marksObtained += $qMarks;
        }
    }

    $scorePercentage = $totalPossibleMarks > 0 ? ($marksObtained / $totalPossibleMarks) * 100.0 : 0;
    $status = ($marksObtained >= $assessment['passing_marks']) ? 'pass' : 'fail';

    try {
        $db->beginTransaction();

        // 1 Assessment Attempt = Exactly 1 Result Record
        $resultId = $db->insert('assessment_results', [
            'student_id' => $studentId,
            'assessment_id' => $assessmentId,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'score_obtained' => $marksObtained,
            'score_percentage' => $scorePercentage,
            'status' => $status,
            'time_taken_seconds' => $timeTaken,
            'completed_at' => date('Y-m-d H:i:s')
        ]);

        // Store all 25 individual question responses linked to this resultId
        foreach ($questions as $q) {
            $qId = $q['id'];
            $selectedOption = isset($answers[$qId]) ? strtoupper(trim($answers[$qId])) : null;
            $isCorrect = ($selectedOption === strtoupper(trim($q['correct_option']))) ? 1 : 0;
            $gained = $isCorrect ? (int)$q['marks'] : 0;

            $db->insert('student_answers', [
                'result_id' => $resultId,
                'question_id' => $qId,
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect,
                'marks_obtained' => $gained
            ]);
        }

        $db->commit();

        // Trigger Skill Gap Analysis & Recommendation engine
        generate_recommendations_for_result($studentId, $assessmentId, $scorePercentage);

        log_activity($_SESSION['user_id'], 'ASSESSMENT_SUBMITTED', "Completed assessment {$assessment['title']} (25 MCQs) with score " . number_format($scorePercentage, 1) . "%");

        set_flash_message('success', 'Assessment submitted successfully! Here is your detailed result.');
        redirect(BASE_URL . 'student/assessment-result.php?result_id=' . $resultId);
        exit;

    } catch (Exception $e) {
        $db->rollBack();
        die("Error saving assessment result: " . $e->getMessage());
    }
}

$pageTitle = "Taking Assessment: " . $assessment['title'];
include __DIR__ . '/../includes/header.php';
?>

<!-- Anti-Cheat Proctoring Warning Overlay Modal -->
<div id="cheatWarningModal" style="display:none; position:fixed; inset:0; background:rgba(2,16,36,0.85); backdrop-filter:blur(8px); z-index:9999; align-items:center; justify-content:center;">
  <div class="card border-0 shadow-lg p-4 text-center" style="max-width:500px; width:90%; background:#021024; border:1px solid rgba(255,255,255,0.15); border-radius:16px; color:#fff;">
    <i class="fa-solid fa-triangle-exclamation text-danger display-3 mb-3"></i>
    <h2 class="text-danger fw-bold mb-2">Security Violation Warning!</h2>
    <p id="cheatWarningMsg" class="text-white-50 mb-4" style="font-size:0.9rem; line-height:1.6;">
      Tab switching or leaving window focus is strictly prohibited. Future events will lead to immediate submission.
    </p>
    <button onclick="resumeCheatTest()" class="btn btn-primary rounded-pill w-100 py-2 fw-bold">I Understand, Continue Test</button>
  </div>
</div>

<!-- Sticky Header & Timer Banner -->
<div class="bg-dark text-white p-3 rounded-4 shadow-lg mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <span class="badge bg-primary me-2"><?= htmlspecialchars($assessment['skill_name']) ?></span>
            <span class="fw-bold fs-5 text-white"><?= htmlspecialchars($assessment['title']) ?></span>
            <span class="badge bg-light text-dark border ms-2"><?= count($questions) ?> MCQs</span>
        </div>
        <div class="d-flex align-items-center gap-4">
            <div id="proctorStatusBadge" class="small text-warning">
                <i class="fa-solid fa-shield-halved me-1"></i> <span id="proctorWarningsCount">Warnings: 0/3</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clock-history fs-4 text-warning"></i>
                <span id="timerDisplay" class="font-monospace fs-4 fw-bold text-white">00:00</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Form Column: All 25 Questions -->
    <div class="col-lg-8">
        <form id="assessmentForm" action="<?= BASE_URL ?>student/take-assessment.php?id=<?= $assessmentId ?>" method="POST">
            <input type="hidden" name="assessment_id" value="<?= $assessmentId ?>">
            <input type="hidden" name="submit_assessment" value="1">
            <input type="hidden" name="time_taken_seconds" id="timeTakenSeconds" value="0">

            <div class="d-flex flex-column gap-4">
                <?php foreach ($questions as $idx => $q): 
                    $qNum = $idx + 1;
                ?>
                    <div class="card border-0 shadow-sm rounded-4 stat-card p-4" id="qCard_<?= $q['id'] ?>">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1 fw-bold">
                                Question <?= $qNum ?> of <?= count($questions) ?>
                            </span>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 text-secondary" id="reviewBtn_<?= $q['id'] ?>" onclick="toggleReviewMark(<?= $q['id'] ?>)">
                                <i class="fa-solid fa-thumbtack me-1"></i> Mark for Review
                            </button>
                        </div>

                        <h5 class="fw-bold text-dark mb-4" style="line-height: 1.5;"><?= htmlspecialchars($q['question_text']) ?></h5>

                        <div class="row g-3">
                            <?php foreach (['A' => $q['option_a'], 'B' => $q['option_b'], 'C' => $q['option_c'], 'D' => $q['option_d']] as $optKey => $optVal): ?>
                                <?php if (!empty($optVal)): ?>
                                    <div class="col-12 col-md-6">
                                        <label class="form-check-label w-100 p-3 rounded-3 border d-flex align-items-center gap-3 cursor-pointer option-hover bg-white" style="transition: all 0.2s ease;">
                                            <input type="radio" class="form-check-input flex-shrink-0" name="answers[<?= $q['id'] ?>]" value="<?= $optKey ?>" onclick="onAnswerSelected(<?= $q['id'] ?>)">
                                            <div>
                                                <strong class="text-primary me-1"><?= $optKey ?>.</strong>
                                                <span class="text-dark"><?= htmlspecialchars($optVal) ?></span>
                                            </div>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Submit Action Bar -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mt-4 bg-white text-center">
                <p class="text-muted small mb-3">Make sure you have reviewed all 25 questions before submitting your final assessment attempt.</p>
                <button type="submit" id="submitAssessmentBtn" class="btn btn-primary bg-gradient-primary border-0 btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="fa-solid fa-paper-plane me-2"></i> Submit Final Assessment Attempt
                </button>
            </div>
        </form>
    </div>

    <!-- Side Question Navigator Dot Grid -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-grid-3x3-gap text-primary me-2"></i>Question Navigator</h5>
            <div class="d-flex align-items-center gap-3 small text-muted mb-3">
                <span><i class="bi bi-circle-fill text-success me-1"></i> Attempted</span>
                <span><i class="bi bi-circle-fill text-info me-1"></i> Review</span>
                <span><i class="bi bi-circle-fill text-secondary me-1"></i> Unattempted</span>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-4" id="navDotsGrid">
                <?php foreach ($questions as $idx => $q): 
                    $qNum = $idx + 1;
                ?>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn" id="navDot_<?= $q['id'] ?>" style="width:36px; height:36px; font-size:12px;" onclick="scrollToQuestion(<?= $q['id'] ?>)">
                        <?= $qNum ?>
                    </button>
                <?php endforeach; ?>
            </div>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/js/assessment-timer.js"></script>
<script>
let proctorWarnings = 0;
const maxProctorWarnings = 3;
let isProctoringActive = true;
let isSubmittingForm = false;
const reviewMarks = {};

document.addEventListener('DOMContentLoaded', function() {
    let startTime = Date.now();
    
    // Anti-Cheat Proctoring Watchers
    document.addEventListener("visibilitychange", function() {
        if (document.hidden && isProctoringActive && !isSubmittingForm) {
            triggerProctorViolation("Tab switch or browser window change detected!");
        }
    });

    window.addEventListener("blur", function() {
        if (isProctoringActive && !isSubmittingForm) {
            triggerProctorViolation("Focus lost from assessment window!");
        }
    });

    // Form Submission Handler & Duplicate Submission Guard
    const form = document.getElementById('assessmentForm');
    form.addEventListener('submit', function(e) {
        if (isSubmittingForm) {
            e.preventDefault();
            return false;
        }
        isSubmittingForm = true;
        isProctoringActive = false;

        const btn = document.getElementById('submitAssessmentBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Submitting Assessment...';
        }

        const secondsElapsed = Math.floor((Date.now() - startTime) / 1000);
        document.getElementById('timeTakenSeconds').value = secondsElapsed;
    });

    // Initialize Timer (Duration in Mins)
    initAssessmentTimer(<?= (int)$assessment['duration_minutes'] ?>, 'assessmentForm', 'timerDisplay', null, null);
});

function triggerProctorViolation(msg) {
    if (isSubmittingForm) return;
    proctorWarnings++;
    const countEl = document.getElementById("proctorWarningsCount");
    if (countEl) {
        countEl.textContent = `Warnings: ${proctorWarnings}/${maxProctorWarnings}`;
    }

    if (proctorWarnings >= maxProctorWarnings) {
        alert("Maximum security violations reached (3/3). Submitting assessment automatically now.");
        isSubmittingForm = true;
        isProctoringActive = false;
        document.getElementById("assessmentForm").submit();
    } else {
        document.getElementById("cheatWarningMsg").textContent = `${msg} Warnings: ${proctorWarnings} of ${maxProctorWarnings}. Reaching ${maxProctorWarnings} will force automatic submission.`;
        document.getElementById("cheatWarningModal").style.display = "flex";
    }
}

function resumeCheatTest() {
    document.getElementById("cheatWarningModal").style.display = "none";
}

function onAnswerSelected(qId) {
    const dotBtn = document.getElementById(`navDot_${qId}`);
    if (dotBtn && !reviewMarks[qId]) {
        dotBtn.className = "btn btn-success btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn text-white";
    }
}

function toggleReviewMark(qId) {
    reviewMarks[qId] = !reviewMarks[qId];
    const dotBtn = document.getElementById(`navDot_${qId}`);
    const reviewBtn = document.getElementById(`reviewBtn_${qId}`);

    if (reviewMarks[qId]) {
        if (dotBtn) dotBtn.className = "btn btn-info btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn text-white";
        if (reviewBtn) {
            reviewBtn.className = "btn btn-sm btn-info text-white rounded-pill px-3 py-1";
            reviewBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Marked for Review';
        }
    } else {
        const isAnswered = document.querySelector(`input[name="answers[${qId}]"]:checked`);
        if (isAnswered) {
            if (dotBtn) dotBtn.className = "btn btn-success btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn text-white";
        } else {
            if (dotBtn) dotBtn.className = "btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn";
        }
        if (reviewBtn) {
            reviewBtn.className = "btn btn-sm btn-light border rounded-pill px-3 py-1 text-secondary";
            reviewBtn.innerHTML = '<i class="fa-solid fa-thumbtack me-1"></i> Mark for Review';
        }
    }
}

function scrollToQuestion(qId) {
    const card = document.getElementById(`qCard_${qId}`);
    if (card) {
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
