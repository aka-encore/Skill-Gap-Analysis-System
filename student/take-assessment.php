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

// Get dynamic proctoring violations limit
$maxViolations = (int)($db->fetch("SELECT setting_value FROM system_settings WHERE setting_key = 'proctoring_max_violations'")['setting_value'] ?? 3);

// Reset proctor session logs on initial assessment screen load (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['proctor_logs'] = [];
    $_SESSION['proctor_counts'] = [
        'total' => 0,
        'phone' => 0,
        'face_missing' => 0,
        'multiple_face' => 0,
        'tab_switch' => 0,
        'focus_loss' => 0,
        'camera_disconnect' => 0
    ];
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

        // Save Proctoring Logs & Summaries from current session
        $proctorLogs = $_SESSION['proctor_logs'] ?? [];
        $proctorCounts = $_SESSION['proctor_counts'] ?? [
            'total' => 0,
            'phone' => 0,
            'face_missing' => 0,
            'multiple_face' => 0,
            'tab_switch' => 0,
            'focus_loss' => 0,
            'camera_disconnect' => 0
        ];

        $totalViolations = $proctorCounts['total'];
        $phoneViolations = $proctorCounts['phone'];
        $faceMissingViolations = $proctorCounts['face_missing'];
        $multipleFaceViolations = $proctorCounts['multiple_face'];
        $tabSwitchViolations = $proctorCounts['tab_switch'];
        $focusLossViolations = $proctorCounts['focus_loss'];
        $cameraDisconnectViolations = $proctorCounts['camera_disconnect'];

        // Determine AI risk level
        $riskLevel = 'Low Risk';
        if ($totalViolations >= $maxViolations || $phoneViolations > 0 || $multipleFaceViolations > 0 || $tabSwitchViolations >= 2) {
            $riskLevel = 'High Risk';
        } elseif ($totalViolations >= 2 || $tabSwitchViolations == 1 || $focusLossViolations >= 2 || $faceMissingViolations >= 2) {
            $riskLevel = 'Medium Risk';
        }

        // Insert Proctoring Summary row
        $db->insert('assessment_proctoring_summaries', [
            'result_id' => $resultId,
            'total_violations' => $totalViolations,
            'phone_violations' => $phoneViolations,
            'face_missing_violations' => $faceMissingViolations,
            'multiple_face_violations' => $multipleFaceViolations,
            'tab_switch_violations' => $tabSwitchViolations,
            'focus_loss_violations' => $focusLossViolations,
            'camera_disconnect_violations' => $cameraDisconnectViolations,
            'risk_level' => $riskLevel
        ]);

        // Append final submit log
        $isAutoSubmit = isset($_POST['auto_submit_violations']) && $_POST['auto_submit_violations'] == '1';
        $finalEvent = $isAutoSubmit ? 'Auto Submission' : 'Assessment Submitted';
        $finalDesc = $isAutoSubmit ? 'Assessment submitted automatically due to exceeding maximum warnings limit.' : 'Student submitted the assessment manually.';

        $proctorLogs[] = [
            'event_type' => $finalEvent,
            'description' => $finalDesc,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Insert proctor timeline logs
        foreach ($proctorLogs as $log) {
            $db->insert('assessment_proctoring_logs', [
                'result_id' => $resultId,
                'event_type' => $log['event_type'],
                'description' => $log['description'],
                'created_at' => $log['created_at']
            ]);
        }

        // Commit transaction
        $db->commit();

        // Automatically update any linked active course state to completed if all requirements are met
        if ($status === 'pass') {
            $relatedCourses = $db->fetchAll(
                "SELECT c.id, c.title 
                 FROM courses c
                 JOIN course_skills cs ON c.id = cs.course_id
                 WHERE cs.skill_id = ? AND c.status = 'active'",
                [$assessment['skill_id']]
            );
            foreach ($relatedCourses as $rc) {
                $courseId = (int)$rc['id'];
                $sp = $db->fetch(
                    "SELECT * FROM student_progress WHERE student_id = ? AND course_id = ?",
                    [$studentId, $courseId]
                );
                if ($sp) {
                    $progressPct = (int)$sp['progress_percentage'];
                    if ($progressPct >= 100) {
                        $db->update(
                            'student_progress',
                            [
                                'status' => 'completed',
                                'last_updated' => date('Y-m-d H:i:s')
                            ],
                            'id = ?',
                            [$sp['id']]
                        );
                        log_activity($_SESSION['user_id'], 'COURSE_COMPLETED', "Automatically completed course: {$rc['title']} after passing assessment");
                    }
                }
            }
        }

        // Clear session logs
        unset($_SESSION['proctor_logs']);
        unset($_SESSION['proctor_counts']);

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

<!-- Fullscreen Enforcement Overlay (Displayed when student exits fullscreen) -->
<div id="fullscreenLockOverlay" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.95); backdrop-filter:blur(8px); z-index:11000; align-items:center; justify-content:center;">
  <div class="card border-0 shadow-lg p-5 text-center" style="max-width:550px; width:90%; background:var(--bg-card); border:1px solid rgba(255,255,255,0.15); border-radius:24px; color:var(--text-body);">
    <i class="fa-solid fa-expand text-primary display-3 mb-4 animate-pulse"></i>
    <h2 class="fw-bold mb-2 text-dark">Full-Screen Mode Required</h2>
    <p class="text-secondary mb-4" style="font-size:0.95rem; line-height:1.6;">
      This assessment is proctored and must be taken in full-screen mode. You have exited full-screen. Answering questions has been disabled until full-screen mode is restored.
    </p>
    <button onclick="requestFullscreenEnforcement()" class="btn btn-primary bg-gradient-primary border-0 rounded-pill w-100 py-3 fw-bold shadow">
        <i class="fa-solid fa-expand me-2"></i> Return to Full Screen
    </button>
  </div>
</div>

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

<!-- Proctoring Webcam Setup and Verification Overlay -->
<div id="proctorPrecheckOverlay">
  <div class="card border-0 shadow-lg p-4 text-center" id="proctorPrecheckCard">
    <i class="fa-solid fa-shield-halved text-primary display-3 mb-3"></i>
    <h2 class="fw-bold mb-2">Webcam & AI Security Verification</h2>
    <p class="text-secondary mb-4 small" style="line-height: 1.6;">
        This assessment is live-proctored using AI. Please authorize webcam access, align your face in the center, and ensure no mobile phones or other individuals are present.
    </p>

    <!-- Webcam Preview Window -->
    <div class="position-relative overflow-hidden rounded-3 mb-4 mx-auto bg-dark border border-secondary-subtle" style="aspect-ratio: 4/3; width: 80%; max-width: 320px;">
        <video id="precheckWebcam" autoplay muted playsinline class="w-100 h-100 object-fit-cover"></video>
        <div id="precheckSpinner" class="position-absolute top-50 start-50 translate-middle text-center w-100 p-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="small mt-2 text-secondary" id="precheckStatusText">Initializing Environment...</div>
        </div>
    </div>

    <!-- Verification Checklists -->
    <div class="precheck-checklist">
        <div class="d-flex align-items-center mb-2" id="checkModelLoading">
            <i class="fa-solid fa-circle-notch fa-spin text-warning me-2 fs-5"></i>
            <span>Loading AI Proctoring Models...</span>
        </div>
        <div class="d-flex align-items-center mb-2" id="checkCameraPermission">
            <i class="fa-solid fa-circle-notch fa-spin text-warning me-2 fs-5"></i>
            <span>Webcam Access Permission</span>
        </div>
        <div class="d-flex align-items-center" id="checkFaceVisible">
            <i class="fa-solid fa-circle-notch fa-spin text-warning me-2 fs-5"></i>
            <span>Face Alignment & Calibration</span>
        </div>
    </div>

    <div class="alert alert-danger py-2 px-3 small border-0 text-start rounded-3 mb-4 d-none" id="precheckErrorAlert">
        <i class="fa-solid fa-circle-exclamation me-1"></i> <span id="precheckErrorMsg"></span>
    </div>

    <button id="startAssessmentBtn" class="btn btn-primary bg-gradient-primary border-0 rounded-pill w-100 py-2.5 fw-bold" disabled>
        Proceed to Assessment
    </button>
  </div>
</div>

<!-- Sticky Header & Timer Banner -->
<div class="assessment-sticky-header bg-dark text-white p-3 rounded-4 shadow-lg mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <span class="badge bg-primary me-2"><?= htmlspecialchars($assessment['skill_name']) ?></span>
            <span class="fw-bold fs-5 text-white"><?= htmlspecialchars($assessment['title']) ?></span>
            <span class="badge bg-light text-dark border ms-2"><?= count($questions) ?> MCQs</span>
        </div>
        <div class="d-flex align-items-center gap-4">
            <div id="proctorStatusBadge" class="small text-warning">
                <i class="fa-solid fa-shield-halved me-1"></i> <span id="proctorWarningsCount">Warnings: 0/<?= $maxViolations ?></span>
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
            <input type="hidden" name="auto_submit_violations" id="autoSubmitViolations" value="0">

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
    <div class="col-lg-4" id="takeAssessmentSidebar">
        <div class="card border-0 shadow-sm rounded-4 p-4 sticky-sidebar" id="takeAssessmentSidebarCard">
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
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn nav-dot-<?= $q['id'] ?>" style="width:36px; height:36px; font-size:12px;" onclick="scrollToQuestion(<?= $q['id'] ?>)">
                        <?= $qNum ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <!-- Live Proctoring Preview Widget -->
            <div class="border-top pt-4" id="proctoringWidget" style="display:none;">
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-shield-halved text-danger me-2"></i>AI Live Proctoring</h6>
                <div class="position-relative overflow-hidden rounded-3 mb-3 bg-dark" style="aspect-ratio: 4/3; width: 100%;">
                    <video id="proctorWebcam" autoplay muted playsinline class="w-100 h-100 object-fit-cover"></video>
                    <canvas id="proctorCanvas" class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events: none; z-index: 10;"></canvas>
                    <div class="position-absolute bottom-0 start-0 m-2 px-2 py-1 rounded bg-dark bg-opacity-75 text-white small" style="z-index: 20; font-size: 10px;">
                        <span class="badge bg-success me-1">LIVE</span>
                        <span id="aiProcessingIndicator" class="small text-white-50"><i class="fa-solid fa-spinner fa-spin"></i> Processing...</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 small" style="font-size: 0.85rem;">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Proctor Status:</span>
                        <strong class="text-success" id="proctorStatusText">Active</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Face Presence:</span>
                        <strong class="text-success" id="proctorFaceStatus">Verifying...</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Phone Status:</span>
                        <strong class="text-success" id="proctorPhoneStatus">Scanning...</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Violations:</span>
                        <strong class="text-warning" id="proctorViolationsText">0 / <?= $maxViolations ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Floating Proctoring Widget (Shown only on screens < 992px) -->
<div id="mobileProctorFloatingWidget" class="d-none no-print">
    <div class="position-relative overflow-hidden rounded-3 bg-dark border border-secondary" style="width: 120px; height: 90px;">
        <video id="mobileProctorWebcam" autoplay muted playsinline class="w-100 h-100 object-fit-cover"></video>
        <canvas id="mobileProctorCanvas" class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events: none; z-index: 10;"></canvas>
        <div class="position-absolute bottom-0 start-0 m-1 px-1 py-0.5 rounded bg-dark bg-opacity-75 text-white" style="z-index: 20; font-size: 8px;">
            <span class="badge bg-danger p-1" id="mobileWarningsBadge">Warnings: 0</span>
        </div>
    </div>
    <div class="d-flex gap-1 mt-2">
        <button type="button" class="btn btn-primary btn-sm rounded-pill flex-grow-1 animate-hover" data-bs-toggle="modal" data-bs-target="#mobileNavModal" style="font-size: 10px; padding: 4px 8px;">
            <i class="bi bi-grid-3x3-gap me-1"></i> Navigator
        </button>
    </div>
</div>

<!-- Mobile Question Navigator Modal -->
<div class="modal fade no-print" id="mobileNavModal" tabindex="-1" aria-labelledby="mobileNavModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg" style="background: var(--bg-card); color: var(--text-body);">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark" id="mobileNavModalLabel"><i class="bi bi-grid-3x3-gap text-primary me-2"></i>Question Navigator</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="d-flex align-items-center gap-3 small text-muted mb-3 justify-content-center">
            <span><i class="bi bi-circle-fill text-success me-1"></i> Attempted</span>
            <span><i class="bi bi-circle-fill text-info me-1"></i> Review</span>
            <span><i class="bi bi-circle-fill text-secondary me-1"></i> Unattempted</span>
        </div>
        <div class="d-flex flex-wrap gap-2 justify-content-center py-2">
            <?php foreach ($questions as $idx => $q): 
                $qNum = $idx + 1;
            ?>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn nav-dot-<?= $q['id'] ?>" style="width:40px; height:40px;" onclick="scrollToQuestion(<?= $q['id'] ?>)" data-bs-dismiss="modal">
                    <?= $qNum ?>
                </button>
            <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Precheck Overlay Styles supporting themes */
#proctorPrecheckOverlay {
    position: fixed;
    inset: 0;
    background: var(--bg-main);
    background-image: radial-gradient(circle at 10% 20%, rgba(38, 101, 140, 0.08) 9.9%, rgba(139, 92, 246, 0.05) 80.3%);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow-y: auto;
    padding: 20px;
    transition: background-color 0.22s ease;
}

#proctorPrecheckCard {
    max-width: 600px;
    width: 100%;
    background: var(--bg-card);
    border: 1px solid var(--border) !important;
    border-radius: 20px;
    color: var(--text-body);
    box-shadow: var(--shadow-lg);
    transition: background-color 0.22s ease, border-color 0.22s ease, color 0.22s ease;
}

#proctorPrecheckCard h2 {
    color: var(--text-heading);
}

#proctorPrecheckCard p {
    color: var(--text-secondary);
}

.precheck-checklist {
    text-align: left;
    margin-bottom: 24px;
    background: var(--bg-muted);
    border: 1px solid var(--border-light);
    padding: 16px;
    border-radius: var(--radius-md);
    color: var(--text-body);
    font-size: 0.88rem;
    transition: background-color 0.22s ease, border-color 0.22s ease, color 0.22s ease;
}

/* Sticky Assessment Header styling */
.assessment-sticky-header {
    position: -webkit-sticky !important;
    position: sticky !important;
    top: 65px !important;
    z-index: 1020 !important;
}

/* Sticky sidebar styling for desktop/laptops */
@media (min-width: 992px) {
    .sticky-sidebar {
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 150px !important;
        max-height: calc(100vh - 170px) !important;
        overflow-y: auto !important;
        scrollbar-width: none !important; /* Firefox */
        transition: top 0.22s ease;
    }
    .sticky-sidebar::-webkit-scrollbar {
        display: none !important; /* Safari and Chrome */
    }
}

:fullscreen .sticky-sidebar {
    top: 85px !important;
    max-height: calc(100vh - 105px) !important;
}

/* Mobile floating widget styling */
@media (max-width: 991.98px) {
    #takeAssessmentSidebar {
        display: none !important;
    }
    #mobileProctorFloatingWidget.active {
        display: block !important;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 2000;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
}

/* Disabled navigation styling during assessment */
.assessment-active #sidebarToggle,
.assessment-active .sidebar-brand a,
.assessment-active .sidebar-nav-item,
.assessment-active #notifDropdown,
.assessment-active #userProfileDropdown,
.assessment-active .profile-pill-trigger,
.assessment-active .sidebar-profile-link,
.assessment-active .sidebar-user a {
    opacity: 0.4 !important;
    cursor: not-allowed !important;
}
.assessment-active .sidebar-nav-item:hover,
.assessment-active .sidebar-toggle-btn:hover,
.assessment-active .profile-pill-trigger:hover,
.assessment-active .sidebar-profile-link:hover,
.assessment-active .sidebar-user a:hover {
    background: none !important;
    color: inherit !important;
}
</style>

<!-- Load TensorFlow.js and Models -->
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.15.0/dist/tf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/coco-ssd@2.2.3/dist/coco-ssd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface@0.0.7/dist/blazeface.min.js"></script>

<script src="<?= BASE_URL ?>assets/js/assessment-timer.js"></script>
<script>
const initTimerStart = performance.now();
console.log(`[Perf Log] Script execution started at: ${initTimerStart.toFixed(2)}ms`);
console.log(`[Perf Log] TensorFlow.js loaded: ${typeof tf !== 'undefined'}`);
console.log(`[Perf Log] cocoSsd model loaded: ${typeof cocoSsd !== 'undefined'}`);
console.log(`[Perf Log] blazeface model loaded: ${typeof blazeface !== 'undefined'}`);

let cocoModel = null;
let faceModel = null;
let localStream = null;
let detectionInterval = null;
let proctorWarnings = 0;
const maxProctorWarnings = <?= $maxViolations ?>;
let isProctoringActive = false;
let isSubmittingForm = false;
let lastViolationTime = 0;
const reviewMarks = {};

// Camera Disconnect Tracking
let cameraGraceTimer = null;
let isCameraDisconnected = false;
const CAMERA_GRACE_PERIOD_MS = 5000;

// Phone Tracking
let phoneTimer = null;
let isPhonePresent = false;
let phoneViolationCounted = false;
const PHONE_CONSECUTIVE_LIMIT = 5; // 5 seconds continuous

// Face Tracking
let faceMissingCount = 0;
let multipleFaceCount = 0;
let faceViolationCounted = false;
let multipleFaceViolationCounted = false;

// Cooldown to prevent duplicate focus loss/visibility warnings
let lastBrowserViolationTime = 0;

// Form submit startTime
let startTime = 0;

// Event Blockers
const eventBlockers = {
    contextmenu: e => e.preventDefault(),
    copy: e => {
        e.preventDefault();
        logBlockerEvent('Copy Attempt', 'Copy operation is not allowed during the assessment.');
    },
    paste: e => {
        e.preventDefault();
        logBlockerEvent('Paste Attempt', 'Paste operation is not allowed during the assessment.');
    },
    cut: e => {
        e.preventDefault();
        logBlockerEvent('Cut Attempt', 'Cut operation is not allowed during the assessment.');
    },
    dragstart: e => e.preventDefault(),
    selectstart: e => e.preventDefault()
};

function logBlockerEvent(eventType, description) {
    if (isProctoringActive && !isSubmittingForm) {
        logProctorEventToServer(eventType, description);
    }
}

function keydownBlocker(e) {
    const isCtrl = e.ctrlKey || e.metaKey;
    const isCtrlShift = (e.ctrlKey || e.metaKey) && e.shiftKey;
    const isCmdOpt = e.metaKey && e.altKey; // macOS Cmd+Opt+I
    const key = e.key.toLowerCase();

    // Block F12 and other Developer Tools shortcuts
    if (e.key === 'F12' || 
        (isCtrlShift && ['i', 'j', 'c'].includes(key)) ||
        (isCmdOpt && key === 'i') ||
        (e.ctrlKey && key === 'u')) {
        e.preventDefault();
        e.stopPropagation();
        logBlockerEvent('Developer Tools', 'Developer tools were opened.');
        return false;
    }

    if (isCtrl) {
        if (['c', 'v', 'x', 'a', 'p'].includes(key)) {
            e.preventDefault();
            e.stopPropagation();
            
            let eventType = '';
            let description = '';
            if (key === 'c') {
                eventType = 'Copy Attempt';
                description = 'Copy operation is not allowed during the assessment.';
            } else if (key === 'v') {
                eventType = 'Paste Attempt';
                description = 'Paste operation is not allowed during the assessment.';
            } else if (key === 'x') {
                eventType = 'Cut Attempt';
                description = 'Cut operation is not allowed during the assessment.';
            } else if (key === 'a') {
                eventType = 'Select All Attempt';
                description = 'Select All operation is not allowed during the assessment.';
            } else if (key === 'p') {
                eventType = 'Print Attempt';
                description = 'Print operation is not allowed during the assessment.';
            }
            
            if (eventType) {
                logBlockerEvent(eventType, description);
            }
            return false;
        }
    }
}

function addBlockers() {
    for (const [event, handler] of Object.entries(eventBlockers)) {
        document.addEventListener(event, handler, true);
    }
    document.addEventListener('keydown', keydownBlocker, true);
}

function removeBlockers() {
    for (const [event, handler] of Object.entries(eventBlockers)) {
        document.removeEventListener(event, handler, true);
    }
    document.removeEventListener('keydown', keydownBlocker, true);
}

// Log Event Helper function
function logProctorEventToServer(eventType, description) {
    const fetchStart = performance.now();
    console.log(`[Perf Log] API log event requested: ${eventType} at: ${fetchStart.toFixed(2)}ms`);
    console.log(`[Proctor Log] ${eventType}: ${description}`);
    const formData = new FormData();
    formData.append('event_type', eventType);
    formData.append('description', description);

    fetch(window.BASE_URL + 'api/log-proctor-event.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log(`[Perf Log] API log event response received in: ${(performance.now() - fetchStart).toFixed(2)}ms`);
        if (data.status === 'success' && data.is_violation) {
            updateViolationCount(data.current_violations, eventType + ": " + description);
        }
    })
    .catch(err => {
        console.error('Failed to log event', err);
        console.log(`[Perf Log] API log event failed in: ${(performance.now() - fetchStart).toFixed(2)}ms`);
    });
}

// Update violations UI and check limit
function updateViolationCount(currentCount, msg) {
    proctorWarnings = currentCount;
    const countEl = document.getElementById("proctorWarningsCount");
    const widgetCountEl = document.getElementById("proctorViolationsText");
    const mobileCountEl = document.getElementById("mobileWarningsBadge");
    
    if (countEl) {
        countEl.textContent = `Warnings: ${proctorWarnings}/${maxProctorWarnings}`;
    }
    if (widgetCountEl) {
        widgetCountEl.textContent = `${proctorWarnings} / ${maxProctorWarnings}`;
    }
    if (mobileCountEl) {
        mobileCountEl.textContent = `Warnings: ${proctorWarnings}`;
    }

    if (proctorWarnings >= maxProctorWarnings) {
        isSubmittingForm = true;
        isProctoringActive = false;
        
        removeBlockers();
        removeNavigationLocks();
        
        document.getElementById("autoSubmitViolations").value = "1";
        
        alert("Maximum security violations reached (" + proctorWarnings + "/" + maxProctorWarnings + "). Submitting assessment automatically now.");
        document.getElementById("assessmentForm").submit();
    } else {
        // Parse the event type and details from the log message
        let title = "Security Violation Warning!";
        let reason = "An integrity check violation was detected. Please follow the guidelines.";
        
        if (msg) {
            const parts = msg.split(':');
            if (parts.length >= 2) {
                const eventType = parts[0].trim();
                const description = parts.slice(1).join(':').trim();
                
                if (eventType === 'Tab Switch' || eventType === 'Window Focus Lost') {
                    title = "⚠ Browser Tab Changed";
                    reason = "You switched to another browser tab or window.";
                } else if (eventType === 'Full-screen Exit') {
                    title = "⚠ Full Screen Exited";
                    reason = "You exited Full Screen Mode.";
                } else if (eventType === 'Face Missing') {
                    title = "⚠ Face Not Detected";
                    reason = "No face was detected in the camera.";
                } else if (eventType === 'Multiple Faces Detected') {
                    title = "⚠ Multiple Faces";
                    reason = "More than one person was detected.";
                } else if (eventType === 'Mobile Phone Detected') {
                    title = "⚠ Mobile Phone Detected";
                    reason = "A mobile phone was detected.";
                } else if (eventType === 'Camera Disabled') {
                    title = "⚠ Camera Disabled";
                    reason = "Camera feed stopped.";
                } else if (eventType === 'Copy Attempt') {
                    title = "⚠ Copy Attempt";
                    reason = "Copy operation is not allowed during the assessment.";
                } else if (eventType === 'Paste Attempt') {
                    title = "⚠ Paste Attempt";
                    reason = "Paste operation is not allowed during the assessment.";
                } else if (eventType === 'Cut Attempt') {
                    title = "⚠ Cut Attempt";
                    reason = "Cut operation is not allowed during the assessment.";
                } else if (eventType === 'Developer Tools') {
                    title = "⚠ Developer Tools";
                    reason = "Developer tools were opened.";
                } else {
                    title = "⚠ " + eventType;
                    reason = description;
                }
            }
        }

        // Show progressive warning overlay with specific reason
        const warningTitleEl = document.querySelector("#cheatWarningModal h2");
        if (warningTitleEl) {
            warningTitleEl.className = "text-danger fw-bold mb-2";
            warningTitleEl.textContent = title;
        }
        
        const warningMsgEl = document.getElementById("cheatWarningMsg");
        if (warningMsgEl) {
            warningMsgEl.innerHTML = `
                <div class="mb-3">
                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 fw-bold">Warning ${proctorWarnings} of ${maxProctorWarnings}</span>
                </div>
                <div class="p-3 rounded-3 mb-2 text-white-50 text-start" style="background: rgba(255,255,255,0.05); border-left: 4px solid var(--danger);">
                    <div class="text-white fw-bold small mb-1">Reason:</div>
                    <div style="font-size: 0.88rem; line-height: 1.5;">${reason}</div>
                </div>
            `;
        }
        document.getElementById("cheatWarningModal").style.display = "flex";
    }
}

function applyNavigationLocks() {
    const locksStart = performance.now();
    document.body.classList.add('assessment-active');
    const selectors = '.sidebar-brand a, #sidebarToggle, .sidebar-nav-item, #notifDropdown, #userProfileDropdown, .profile-pill-trigger, .sidebar-profile-link, .sidebar-user a';
    const navItems = document.querySelectorAll(selectors);
    navItems.forEach(item => {
        item.setAttribute('data-original-title', item.getAttribute('title') || '');
        item.setAttribute('title', 'Navigation is disabled during an active assessment.');
        item.removeAttribute('data-bs-toggle');
    });
    console.log(`[Perf Log] applyNavigationLocks completed at: ${performance.now().toFixed(2)}ms (Duration: ${(performance.now() - locksStart).toFixed(2)}ms)`);
}

function removeNavigationLocks() {
    document.body.classList.remove('assessment-active');
    const selectors = '.sidebar-brand a, #sidebarToggle, .sidebar-nav-item, #notifDropdown, #userProfileDropdown, .profile-pill-trigger, .sidebar-profile-link, .sidebar-user a';
    const navItems = document.querySelectorAll(selectors);
    navItems.forEach(item => {
        const originalTitle = item.getAttribute('data-original-title');
        if (originalTitle !== null) {
            item.setAttribute('title', originalTitle);
        } else {
            item.removeAttribute('title');
        }
        if (item.id === 'notifDropdown' || item.id === 'userProfileDropdown') {
            item.setAttribute('data-bs-toggle', 'dropdown');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const domReadyTime = performance.now();
    console.log(`[Perf Log] DOMContentLoaded fired at: ${domReadyTime.toFixed(2)}ms (Time since script start: ${(domReadyTime - initTimerStart).toFixed(2)}ms)`);
    
    startTime = Date.now();
    
    // Apply navigation locks immediately on page load (covers precheck overlay and active assessment)
    applyNavigationLocks();
    
    let webcamPromiseReady = false;
    let modelsPromiseReady = false;
    let calibrationInterval = null;
    
    const updatePrecheckProgress = (step, status, textHtml) => {
        const el = document.getElementById(step);
        if (el) {
            el.innerHTML = textHtml;
        }
    };
    
    // 1. Initialize Webcam in Parallel Immediately
    const webcamStart = performance.now();
    console.log(`[Perf Log] Webcam getUserMedia requested at: ${webcamStart.toFixed(2)}ms`);
    document.getElementById('precheckStatusText').textContent = "Initializing webcam...";
    updatePrecheckProgress('checkCameraPermission', 'pending', 
        '<i class="fa-solid fa-circle-notch fa-spin text-warning me-2 fs-5"></i><span>Requesting webcam permission...</span>'
    );
    
    const webcamPromise = navigator.mediaDevices.getUserMedia({ 
        video: { 
            width: { ideal: 640 }, 
            height: { ideal: 480 },
            facingMode: 'user' 
        } 
    }).then(stream => {
        const webcamReady = performance.now();
        console.log(`[Perf Log] Webcam stream granted at: ${webcamReady.toFixed(2)}ms (Duration: ${(webcamReady - webcamStart).toFixed(2)}ms)`);
        localStream = stream;
        
        // Attach stream to precheck video
        const attachStart = performance.now();
        const precheckVideo = document.getElementById('precheckWebcam');
        precheckVideo.srcObject = stream;
        precheckVideo.play().catch(e => console.log('precheckVideo play fail:', e));
        console.log(`[Perf Log] Webcam attached and play() invoked at: ${performance.now().toFixed(2)}ms (Duration: ${(performance.now() - attachStart).toFixed(2)}ms)`);
        
        // Attach stream to active proctoring video
        const activeVideo = document.getElementById('proctorWebcam');
        activeVideo.srcObject = stream;
        
        // Attach stream to mobile active proctoring video
        const mobileActiveVideo = document.getElementById('mobileProctorWebcam');
        if (mobileActiveVideo) {
            mobileActiveVideo.srcObject = stream;
        }
        
        webcamPromiseReady = true;
        updatePrecheckProgress('checkCameraPermission', 'success', 
            '<i class="fa-solid fa-circle-check text-success me-2 fs-5"></i><span>Webcam connected.</span>'
        );
        
        checkSetupReadiness();
    }).catch(err => {
        console.error('Camera initialization failed', err);
        console.log(`[Perf Log] Camera initialization failed in: ${(performance.now() - webcamStart).toFixed(2)}ms`);
        updatePrecheckProgress('checkCameraPermission', 'error', 
            '<i class="fa-solid fa-circle-xmark text-danger me-2 fs-5"></i><span>Webcam Access Denied / Failed</span>'
        );
        document.getElementById('precheckSpinner').classList.add('d-none');
        showPrecheckError("Webcam access is required to take this assessment. Please allow camera access in your browser settings.");
    });
    
    // 2. Load AI Models in Parallel in Background
    const modelsStart = performance.now();
    console.log(`[Perf Log] Promise.all model loads requested at: ${modelsStart.toFixed(2)}ms`);
    
    // Force CPU backend to avoid WebGL SwiftShader compiler hangs in virtualized environments
    try {
        tf.setBackend('cpu');
        console.log(`[Perf Log] TensorFlow backend set to CPU at: ${performance.now().toFixed(2)}ms`);
    } catch(e) {
        console.error('Failed to set CPU backend:', e);
    }

    updatePrecheckProgress('checkModelLoading', 'pending', 
        '<i class="fa-solid fa-circle-notch fa-spin text-warning me-2 fs-5"></i><span>Loading AI models...</span>'
    );
    
    const modelsPromise = Promise.all([
        cocoSsd.load({ 
            base: 'lite_mobilenet_v2',
            modelUrl: '<?= BASE_URL ?>assets/models/coco-ssd/model.json'
        }),
        blazeface.load({ 
            modelUrl: '<?= BASE_URL ?>assets/models/blazeface/model.json' 
        })
    ]).then(models => {
        const modelsReady = performance.now();
        console.log(`[Perf Log] AI Models loaded successfully at: ${modelsReady.toFixed(2)}ms (Duration: ${(modelsReady - modelsStart).toFixed(2)}ms)`);
        cocoModel = models[0];
        faceModel = models[1];
        
        modelsPromiseReady = true;
        updatePrecheckProgress('checkModelLoading', 'success', 
            '<i class="fa-solid fa-circle-check text-success me-2 fs-5"></i><span>AI models ready.</span>'
        );
        
        checkSetupReadiness();
    }).catch(err => {
        console.error('Failed to load AI proctoring models', err);
        console.log(`[Perf Log] AI models load failed in: ${(performance.now() - modelsStart).toFixed(2)}ms`);
        updatePrecheckProgress('checkModelLoading', 'error', 
            '<i class="fa-solid fa-circle-xmark text-danger me-2 fs-5"></i><span>Failed to load AI models.</span>'
        );
        document.getElementById('precheckSpinner').classList.add('d-none');
        showPrecheckError("Failed to load AI proctoring models. Please verify your internet connection and reload the page.");
    });
    
    // 3. Coordination function once both parts are resolved
    function checkSetupReadiness() {
        console.log(`[Perf Log] checkSetupReadiness invoked. Webcam ready: ${webcamPromiseReady}, Models ready: ${modelsPromiseReady}`);
        if (!webcamPromiseReady || !modelsPromiseReady) {
            if (webcamPromiseReady && !modelsPromiseReady) {
                document.getElementById('precheckStatusText').textContent = "Loading AI models...";
            } else if (!webcamPromiseReady && modelsPromiseReady) {
                document.getElementById('precheckStatusText').textContent = "Initializing webcam...";
            }
            return;
        }
        
        const calibInitStart = performance.now();
        console.log(`[Perf Log] Starting Face Calibration checks at: ${calibInitStart.toFixed(2)}ms`);
        document.getElementById('precheckStatusText').textContent = "Verifying camera...";
        document.getElementById('precheckSpinner').classList.add('d-none');
        
        updatePrecheckProgress('checkFaceVisible', 'pending', 
            '<i class="fa-solid fa-circle-notch fa-spin text-warning me-2 fs-5"></i><span>Calibrating face alignment...</span>'
        );
        
        if (calibrationInterval) clearInterval(calibrationInterval);
        
        let calibrationChecks = 0;
        const precheckVideo = document.getElementById('precheckWebcam');
        
        calibrationInterval = setInterval(async () => {
            if (!faceModel || !localStream) return;
            const tickStart = performance.now();
            try {
                const predictions = await faceModel.estimateFaces(precheckVideo, false);
                const tickEnd = performance.now();
                console.log(`[Perf Log] Face Calibration tick check completed. Faces found: ${predictions.length}. Duration: ${(tickEnd - tickStart).toFixed(2)}ms`);
                
                if (predictions.length === 1) {
                    calibrationChecks++;
                    updatePrecheckProgress('checkFaceVisible', 'success', 
                        `<i class="fa-solid fa-circle-check text-success me-2 fs-5"></i><span>Face Calibrated (${calibrationChecks}/3)</span>`
                    );
                    if (calibrationChecks >= 3) {
                        clearInterval(calibrationInterval);
                        console.log(`[Perf Log] Face Calibration success at: ${performance.now().toFixed(2)}ms`);
                        document.getElementById('precheckStatusText').textContent = "Environment ready.";
                        checkStartEligibility();
                    }
                } else if (predictions.length > 1) {
                    updatePrecheckProgress('checkFaceVisible', 'warning', 
                        '<i class="fa-solid fa-triangle-exclamation text-warning me-2 fs-5"></i><span>Multiple Faces Detected in Setup</span>'
                    );
                } else {
                    updatePrecheckProgress('checkFaceVisible', 'error', 
                        '<i class="fa-solid fa-circle-xmark text-danger me-2 fs-5"></i><span>No Face Detected in Setup</span>'
                    );
                }
            } catch(e) {
                console.error('Calibration check failed', e);
            }
        }, 1000);
    }

    function checkStartEligibility() {
        const hasCamera = localStream !== null;
        const hasModels = cocoModel !== null && faceModel !== null;
        const isCalibrated = document.getElementById('checkFaceVisible').innerHTML.includes('Calibrated (3/3)');
        
        if (hasCamera && hasModels && isCalibrated) {
            console.log(`[Perf Log] Environment ready and Proceed button enabled at: ${performance.now().toFixed(2)}ms`);
            document.getElementById('startAssessmentBtn').removeAttribute('disabled');
        }
    }

    function showPrecheckError(msg) {
        const errorAlert = document.getElementById('precheckErrorAlert');
        const errorMsg = document.getElementById('precheckErrorMsg');
        errorMsg.textContent = msg;
        errorAlert.classList.remove('d-none');
    }

    // (applyNavigationLocks and removeNavigationLocks moved to global scope above DOMContentLoaded)

    // Intercept clicks on navigation elements during assessment using Capture phase
    const blockClick = (e) => {
        if (isSubmittingForm) return;
        const target = e.target.closest('.sidebar-brand a, #sidebarToggle, .sidebar-nav-item, #notifDropdown, #userProfileDropdown, .profile-pill-trigger, .sidebar-profile-link, .sidebar-user a');
        if (target) {
            e.preventDefault();
            e.stopPropagation();
            alert("Navigation is disabled during an active assessment.");
        }
    };
    document.addEventListener('click', blockClick, true);

    // Start Assessment trigger
    document.getElementById('startAssessmentBtn').addEventListener('click', function() {
        document.getElementById('proctorPrecheckOverlay').style.display = 'none';
        document.getElementById('proctoringWidget').style.display = 'block';
        
        const mobileWidget = document.getElementById('mobileProctorFloatingWidget');
        if (mobileWidget) {
            mobileWidget.classList.add('active');
        }
        
        // Lock navigation links and controls
        applyNavigationLocks();
        
        // Collapse sidebar dynamically for additional viewport workspace
        const appLayout = document.getElementById('appLayout') || document.querySelector('.dashboard-layout');
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 768) {
            if (sidebar) sidebar.classList.add('collapsed');
            if (appLayout) appLayout.classList.add('sidebar-collapsed');
        } else {
            if (sidebar) sidebar.classList.remove('mobile-open');
            const overlay = document.getElementById('sidebarOverlay');
            if (overlay) overlay.classList.remove('active');
            if (appLayout) appLayout.classList.remove('sidebar-open');
        }
        
        // Explicitly play video stream once containers are displayed to prevent freeze frame issues
        const activeVideo = document.getElementById('proctorWebcam');
        if (activeVideo) activeVideo.play().catch(e => console.log('proctorWebcam play fail:', e));
        const mobileActiveVideo = document.getElementById('mobileProctorWebcam');
        if (mobileActiveVideo) mobileActiveVideo.play().catch(e => console.log('mobileProctorWebcam play fail:', e));
        
        // Enter Fullscreen if supported
        const docEl = document.documentElement;
        if (docEl.requestFullscreen) {
            docEl.requestFullscreen().catch(err => console.log('Fullscreen rejected'));
        } else if (docEl.webkitRequestFullscreen) {
            docEl.webkitRequestFullscreen();
        }
        
        // Log setup completions
        logProctorEventToServer('Assessment Started', 'Student authorized webcam and loaded proctoring environment.');
        logProctorEventToServer('Camera Enabled', 'Webcam stream validated and active.');
        
        // Activate proctoring
        isProctoringActive = true;
        
        // Enable copy/paste inhibitors
        addBlockers();
        
        // Reset timer start time
        startTime = Date.now();
        
        // Initialize Timer (Duration in Mins)
        initAssessmentTimer(<?= (int)$assessment['duration_minutes'] ?>, 'assessmentForm', 'timerDisplay', null, null);
        
        // Start live AI loop
        startProctoringLoop();
    });

    // Browser activity listeners
    document.addEventListener("visibilitychange", function() {
        if (document.hidden && isProctoringActive && !isSubmittingForm) {
            const now = Date.now();
            if (now - lastBrowserViolationTime > 1500) {
                lastBrowserViolationTime = now;
                logProctorEventToServer('Tab Switch', 'Student switched tabs or minimized the browser window.');
            }
        }
    });

    window.addEventListener("blur", function() {
        if (isProctoringActive && !isSubmittingForm) {
            const now = Date.now();
            if (now - lastBrowserViolationTime > 1500) {
                lastBrowserViolationTime = now;
                logProctorEventToServer('Window Focus Lost', 'Student focused away from the assessment window.');
            }
        }
    });

    const showFullscreenLock = () => {
        const lockOverlay = document.getElementById("fullscreenLockOverlay");
        if (lockOverlay) {
            lockOverlay.style.display = "flex";
        }
        const mainContainer = document.getElementById("assessmentForm");
        if (mainContainer) {
            mainContainer.style.pointerEvents = "none";
            mainContainer.style.filter = "blur(5px)";
            mainContainer.style.userSelect = "none";
        }
    };

    const hideFullscreenLock = () => {
        const lockOverlay = document.getElementById("fullscreenLockOverlay");
        if (lockOverlay) {
            lockOverlay.style.display = "none";
        }
        const mainContainer = document.getElementById("assessmentForm");
        if (mainContainer) {
            mainContainer.style.pointerEvents = "auto";
            mainContainer.style.filter = "none";
            mainContainer.style.userSelect = "auto";
        }
    };

    window.requestFullscreenEnforcement = () => {
        const docEl = document.documentElement;
        if (docEl.requestFullscreen) {
            docEl.requestFullscreen().catch(err => console.log('Fullscreen request failed:', err));
        } else if (docEl.webkitRequestFullscreen) {
            docEl.webkitRequestFullscreen();
        } else if (docEl.mozRequestFullScreen) {
            docEl.mozRequestFullScreen();
        } else if (docEl.msRequestFullscreen) {
            docEl.msRequestFullscreen();
        }
    };

    const onFullscreenChange = () => {
        const isFullscreen = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement;
        if (!isFullscreen && isProctoringActive && !isSubmittingForm) {
            const now = Date.now();
            if (now - lastBrowserViolationTime > 1500) {
                lastBrowserViolationTime = now;
                logProctorEventToServer('Full-screen Exit', 'Student exited full-screen mode.');
            }
            showFullscreenLock();
        } else if (isFullscreen && isProctoringActive) {
            hideFullscreenLock();
        }
    };
    document.addEventListener('fullscreenchange', onFullscreenChange);
    document.addEventListener('webkitfullscreenchange', onFullscreenChange);
    document.addEventListener('mozfullscreenchange', onFullscreenChange);

    // Form submission
    const form = document.getElementById('assessmentForm');
    form.addEventListener('submit', function(e) {
        if (isSubmittingForm) {
            e.preventDefault();
            return false;
        }
        isSubmittingForm = true;
        isProctoringActive = false;

        // Remove copy blockers
        removeBlockers();

        // Restore navigation links
        removeNavigationLocks();

        const btn = document.getElementById('submitAssessmentBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Submitting Assessment...';
        }

        const secondsElapsed = Math.floor((Date.now() - startTime) / 1000);
        document.getElementById('timeTakenSeconds').value = secondsElapsed;
    });
});

function startProctoringLoop() {
    const indicatorEl = document.getElementById('aiProcessingIndicator');
    if (indicatorEl) {
        indicatorEl.innerHTML = '<span class="text-success"><i class="fa-solid fa-circle-check"></i> Monitoring Active</span>';
    }

    detectionInterval = setInterval(async () => {
        if (!isProctoringActive || isSubmittingForm) return;

        const isMobile = window.innerWidth < 992;
        const video = isMobile ? document.getElementById('mobileProctorWebcam') : document.getElementById('proctorWebcam');
        const canvas = isMobile ? document.getElementById('mobileProctorCanvas') : document.getElementById('proctorCanvas');
        
        if (!video || !canvas) return;
        const ctx = canvas.getContext('2d');
        const statusTextEl = document.getElementById('proctorStatusText');
        const faceTextEl = document.getElementById('proctorFaceStatus');
        const phoneTextEl = document.getElementById('proctorPhoneStatus');

        // 1. Camera state check
        const isStreamActive = localStream && localStream.active;
        const hasVideoTracks = localStream && localStream.getVideoTracks().length > 0;
        const isTrackMuted = hasVideoTracks && localStream.getVideoTracks()[0].muted;
        const isTrackEnabled = hasVideoTracks && localStream.getVideoTracks()[0].enabled;
        
        if (!isStreamActive || !isTrackEnabled || isTrackMuted || video.paused || video.ended) {
            handleCameraDisconnect();
            return;
        } else {
            handleCameraReconnect();
        }

        if (canvas.width !== video.videoWidth) {
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
        }

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        try {
            // Run detections
            const [faces, predictions] = await Promise.all([
                faceModel.estimateFaces(video, false),
                cocoModel.detect(video)
            ]);

            // 2. Face Presence Check (2s threshold)
            if (faces.length === 0) {
                faceMissingCount++;
                if (faceTextEl) {
                    faceTextEl.textContent = "Missing (" + faceMissingCount + "s)";
                    faceTextEl.className = "text-danger";
                }

                if (faceMissingCount > 2) {
                    if (!faceViolationCounted) {
                        faceViolationCounted = true;
                        logProctorEventToServer('Face Missing', 'No face visible in webcam frame.');
                    }
                }
            } else if (faces.length > 1) {
                multipleFaceCount++;
                if (faceTextEl) {
                    faceTextEl.textContent = "Multiple Faces (" + multipleFaceCount + "s)";
                    faceTextEl.className = "text-danger";
                }

                if (multipleFaceCount > 2) {
                    if (!multipleFaceViolationCounted) {
                        multipleFaceViolationCounted = true;
                        logProctorEventToServer('Multiple Faces Detected', 'Multiple faces visible in webcam frame.');
                    }
                }
            } else {
                if (faceTextEl) {
                    faceTextEl.textContent = "Visible";
                    faceTextEl.className = "text-success";
                }
                
                if (faceViolationCounted || multipleFaceViolationCounted) {
                    logProctorEventToServer('Face Re-calibrated', 'One face presence restored.');
                }
                faceMissingCount = 0;
                multipleFaceCount = 0;
                faceViolationCounted = false;
                multipleFaceViolationCounted = false;
            }

            // Draw Face bounds
            ctx.strokeStyle = '#10B981';
            ctx.lineWidth = 2;
            faces.forEach(face => {
                const start = face.topLeft;
                const end = face.bottomRight;
                const size = [end[0] - start[0], end[1] - start[1]];
                ctx.strokeRect(start[0], start[1], size[0], size[1]);
            });

            // 3. Mobile Phone Check (5s threshold)
            let phoneDetectedThisFrame = false;
            predictions.forEach(pred => {
                if (pred.class === 'cell phone' && pred.score > 0.45) {
                    phoneDetectedThisFrame = true;
                    ctx.strokeStyle = '#EF4444';
                    ctx.lineWidth = 3;
                    ctx.strokeRect(pred.bbox[0], pred.bbox[1], pred.bbox[2], pred.bbox[3]);
                }
            });

            if (phoneDetectedThisFrame) {
                isPhonePresent = true;
                if (!phoneTimer) {
                    phoneTimer = 1;
                } else {
                    phoneTimer++;
                }
                if (phoneTextEl) {
                    phoneTextEl.textContent = "Detected (" + phoneTimer + "s)";
                    phoneTextEl.className = "text-danger";
                }

                if (phoneTimer > PHONE_CONSECUTIVE_LIMIT) {
                    if (!phoneViolationCounted) {
                        phoneViolationCounted = true;
                        logProctorEventToServer('Mobile Phone Detected', 'Mobile phone detected in webcam view.');
                    }
                }
            } else {
                if (phoneTextEl) {
                    phoneTextEl.textContent = "Scanning...";
                    phoneTextEl.className = "text-success";
                }
                
                if (phoneViolationCounted) {
                    logProctorEventToServer('Mobile Phone Removed', 'Mobile phone removed from camera frame.');
                }
                phoneTimer = null;
                isPhonePresent = false;
                phoneViolationCounted = false;
            }

        } catch (err) {
            console.error('AI estimation error', err);
        }
    }, 1000);
}

function handleCameraDisconnect() {
    if (isCameraDisconnected || !isProctoringActive || isSubmittingForm) return;
    
    isCameraDisconnected = true;
    const statusTextEl = document.getElementById('proctorStatusText');
    if (statusTextEl) {
        statusTextEl.textContent = "Camera Disconnected";
        statusTextEl.className = "text-danger";
    }

    logProctorEventToServer('Camera Disabled', 'Webcam stream disconnected.');

    let remainingGrace = CAMERA_GRACE_PERIOD_MS / 1000;
    cameraGraceTimer = setInterval(() => {
        if (!isCameraDisconnected || !isProctoringActive || isSubmittingForm) {
            clearInterval(cameraGraceTimer);
            return;
        }
        remainingGrace--;
        
        const warningMsg = "Webcam disconnect detected! Please reconnect your camera immediately. Grace period: " + remainingGrace + "s.";
        document.getElementById("cheatWarningMsg").textContent = warningMsg;
        document.getElementById("cheatWarningModal").style.display = "flex";

        if (remainingGrace <= 0) {
            clearInterval(cameraGraceTimer);
            logProctorEventToServer('Camera Disabled', 'Camera remains disconnected beyond the grace period.');
        }
    }, 1000);
}

function handleCameraReconnect() {
    if (!isCameraDisconnected) return;
    
    isCameraDisconnected = false;
    if (cameraGraceTimer) {
        clearInterval(cameraGraceTimer);
    }
    
    const statusTextEl = document.getElementById('proctorStatusText');
    if (statusTextEl) {
        statusTextEl.textContent = "Active";
        statusTextEl.className = "text-success";
    }
    
    document.getElementById("cheatWarningModal").style.display = "none";
    logProctorEventToServer('Camera Reconnected', 'Webcam stream reconnected.');
}

function resumeCheatTest() {
    document.getElementById("cheatWarningModal").style.display = "none";
}

function onAnswerSelected(qId) {
    const dotBtns = document.querySelectorAll(`.nav-dot-${qId}`);
    dotBtns.forEach(dotBtn => {
        if (!reviewMarks[qId]) {
            dotBtn.className = "btn btn-success btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn text-white nav-dot-" + qId;
        }
    });
}

function toggleReviewMark(qId) {
    reviewMarks[qId] = !reviewMarks[qId];
    const dotBtns = document.querySelectorAll(`.nav-dot-${qId}`);
    const reviewBtn = document.getElementById(`reviewBtn_${qId}`);

    dotBtns.forEach(dotBtn => {
        if (reviewMarks[qId]) {
            dotBtn.className = "btn btn-info btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn text-white nav-dot-" + qId;
        } else {
            const isAnswered = document.querySelector(`input[name="answers[${qId}]"]:checked`);
            if (isAnswered) {
                dotBtn.className = "btn btn-success btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn text-white nav-dot-" + qId;
            } else {
                dotBtn.className = "btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center fw-bold nav-dot-btn nav-dot-" + qId;
            }
        }
    });

    if (reviewMarks[qId]) {
        if (reviewBtn) {
            reviewBtn.className = "btn btn-sm btn-info text-white rounded-pill px-3 py-1";
            reviewBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Marked for Review';
        }
    } else {
        if (reviewBtn) {
            reviewBtn.className = "btn btn-sm btn-light border rounded-pill px-3 py-1 text-secondary";
            reviewBtn.innerHTML = '<i class="fa-solid fa-thumbtack me-1"></i> Mark for Review';
        }
    }
}

function scrollToQuestion(qId) {
    const card = document.getElementById(`qCard_${qId}`);
    if (card) {
        // Use a short timeout to prevent Bootstrap modal closing animation from canceling the scroll behavior
        setTimeout(() => {
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 150);
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
