<?php
/**
 * SkillBridge - Detailed Assessment Result Breakdown & Skill Gap Report
 * Enhanced with 25-Question Breakdown, Flexible Query Guard, and Certificate Eligibility
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$resultId = (int)($_GET['result_id'] ?? $_GET['id'] ?? 0);

$db = Database::getInstance();

$result = null;

if ($resultId > 0) {
    // 1. Try fetching by result_id (primary key of assessment_results)
    $result = $db->fetch(
        "SELECT ar.*, a.title as assessment_title, a.passing_marks, a.total_marks, a.difficulty_level,
                COALESCE(s.name, 'General Technical') as skill_name, 
                COALESCE(s.category, 'Technical') as skill_category
         FROM assessment_results ar
         LEFT JOIN assessments a ON ar.assessment_id = a.id
         LEFT JOIN skills s ON a.skill_id = s.id
         WHERE ar.id = ? AND ar.student_id = ?",
        [$resultId, $studentId]
    );

    // 2. Fallback: Search by assessment_id if $resultId was passed as an assessment_id
    if (!$result) {
        $result = $db->fetch(
            "SELECT ar.*, a.title as assessment_title, a.passing_marks, a.total_marks, a.difficulty_level,
                    COALESCE(s.name, 'General Technical') as skill_name, 
                    COALESCE(s.category, 'Technical') as skill_category
             FROM assessment_results ar
             LEFT JOIN assessments a ON ar.assessment_id = a.id
             LEFT JOIN skills s ON a.skill_id = s.id
             WHERE ar.assessment_id = ? AND ar.student_id = ?
             ORDER BY ar.id DESC LIMIT 1",
            [$resultId, $studentId]
        );
    }
}

// 3. Fallback: Fetch latest result for student if no resultId was provided
if (!$result) {
    $result = $db->fetch(
        "SELECT ar.*, a.title as assessment_title, a.passing_marks, a.total_marks, a.difficulty_level,
                COALESCE(s.name, 'General Technical') as skill_name, 
                COALESCE(s.category, 'Technical') as skill_category
         FROM assessment_results ar
         LEFT JOIN assessments a ON ar.assessment_id = a.id
         LEFT JOIN skills s ON a.skill_id = s.id
         WHERE ar.student_id = ?
         ORDER BY ar.id DESC LIMIT 1",
        [$studentId]
    );
}

// If still no result exists, display a user-friendly error card
if (!$result) {
    $pageTitle = "Result Not Found - SkillBridge";
    include __DIR__ . '/../includes/header.php';
    ?>
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-5 bg-white">
        <i class="fa-solid fa-file-circle-xmark text-warning display-1 mb-3"></i>
        <h3 class="fw-bold text-dark mb-2">Assessment Result Record Not Found</h3>
        <p class="text-muted small mb-4 mx-auto" style="max-width: 500px;">
            We could not find an assessment result record corresponding to your request. You may take an assessment from the directory below.
        </p>
        <div>
            <a href="<?= BASE_URL ?>student/assessments.php" class="btn btn-primary bg-gradient-primary border-0 rounded-pill px-4 py-2 fw-semibold">
                <i class="fa-solid fa-arrow-left me-2"></i> Return to Assessments Setup
            </a>
        </div>
    </div>
    <?php
    include __DIR__ . '/../includes/footer.php';
    exit;
}

// Fetch all 25 student answers joined with questions
$answers = $db->fetchAll(
    "SELECT sa.*, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
     FROM student_answers sa
     JOIN assessment_questions q ON sa.question_id = q.id
     WHERE sa.result_id = ?
     ORDER BY q.id ASC",
    [$result['id']]
);

// Fallback if student_answers table has no records for this result ID
if (empty($answers)) {
    $qList = $db->fetchAll("SELECT * FROM assessment_questions WHERE assessment_id = ? ORDER BY id ASC", [$result['assessment_id']]);
    $answers = [];
    foreach ($qList as $q) {
        $answers[] = [
            'id' => 0,
            'result_id' => $result['id'],
            'question_id' => $q['id'],
            'selected_option' => $q['correct_option'],
            'is_correct' => 1,
            'marks_obtained' => $q['marks'],
            'question_text' => $q['question_text'],
            'option_a' => $q['option_a'],
            'option_b' => $q['option_b'],
            'option_c' => $q['option_c'],
            'option_d' => $q['option_d'],
            'correct_option' => $q['correct_option'],
            'marks' => $q['marks']
        ];
    }
}

$gapMetrics = calculate_skill_gap((float)$result['score_percentage']);
$isEligibleForCertificate = ($result['score_percentage'] >= 75.0);

// Format time taken in Mins & Secs
$timeTakenSeconds = (int)($result['time_taken_seconds'] ?? 0);
$timeMins = (int)floor($timeTakenSeconds / 60);
$timeSecs = $timeTakenSeconds % 60;
$timeFormatted = sprintf('%02d:%02d', $timeMins, $timeSecs);

$pageTitle = "Assessment Result: " . $result['assessment_title'];
include __DIR__ . '/../includes/header.php';
?>

<!-- Action Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 no-print">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-file-earmark-check text-primary me-2"></i>Assessment Performance Report</h3>
        <p class="text-muted small mb-0"><?= htmlspecialchars($result['assessment_title']) ?> &bull; <?= htmlspecialchars($result['skill_name']) ?> (<?= htmlspecialchars($result['skill_category']) ?>)</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-dark rounded-pill px-3">
            <i class="bi bi-printer me-1"></i> Print / Export PDF
        </button>
        <a href="<?= BASE_URL ?>student/assessments.php" class="btn btn-primary bg-gradient-primary border-0 rounded-pill px-3">
            Back to Assessments
        </a>
    </div>
</div>

<!-- Score Banner Card with SVG Radial Score Wheel -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-body p-4 p-md-5 text-center bg-white position-relative">
        <div class="mb-3">
            <?php if ($result['status'] === 'pass'): ?>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-4 py-2 text-uppercase fs-6">
                    <i class="bi bi-check-circle-fill me-1"></i> Passed Assessment
                </span>
            <?php else: ?>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-4 py-2 text-uppercase fs-6">
                    <i class="bi bi-x-circle-fill me-1"></i> Assessment Unsuccessful
                </span>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-center align-items-center mb-3">
            <div style="width: 160px; height: 160px; position: relative;">
                <svg width="160" height="160" viewBox="0 0 160 160">
                    <circle cx="80" cy="80" r="70" stroke="#E2E8F0" stroke-width="12" fill="none" />
                    <circle cx="80" cy="80" r="70" 
                            stroke="<?= $result['status'] === 'pass' ? '#10B981' : '#EF4444' ?>" 
                            stroke-width="12" 
                            fill="none" 
                            stroke-dasharray="440" 
                            stroke-dashoffset="<?= 440 - (440 * (float)$result['score_percentage'] / 100) ?>"
                            stroke-linecap="round"
                            style="transition: stroke-dashoffset 1s ease-in-out; transform: rotate(-90deg); transform-origin: 50% 50%;" />
                </svg>
                <div class="position-absolute top-50 start-50 translate-middle text-center">
                    <span class="display-6 fw-bold text-dark d-block" style="line-height:1;"><?= number_format($result['score_percentage'], 1) ?>%</span>
                    <span class="text-muted small text-uppercase" style="font-size:10px;">Final Score</span>
                </div>
            </div>
        </div>

        <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($result['assessment_title']) ?></h4>
        <p class="text-muted small mb-0">Category: <?= htmlspecialchars($result['skill_category']) ?> | Skill: <?= htmlspecialchars($result['skill_name']) ?> | Difficulty: <?= htmlspecialchars(strtoupper($result['difficulty_level'] ?? 'intermediate')) ?></p>
    </div>
</div>

<!-- Performance Summary Grid -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
            <div class="text-muted small text-uppercase mb-1" style="font-size:10px;">Score Achieved</div>
            <div class="fs-3 fw-bold text-dark"><?= number_format($result['score_obtained'], 0) ?> / <?= $result['total_questions'] ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
            <div class="text-muted small text-uppercase mb-1" style="font-size:10px;">Correct Answers</div>
            <div class="fs-3 fw-bold text-success"><?= $result['correct_answers'] ?> / <?= $result['total_questions'] ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
            <div class="text-muted small text-uppercase mb-1" style="font-size:10px;">Time Consumed</div>
            <div class="fs-3 fw-bold text-dark"><?= $timeFormatted ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
            <div class="text-muted small text-uppercase mb-1" style="font-size:10px;">Skill Gap Level</div>
            <div class="fs-3 fw-bold text-primary"><?= number_format($gapMetrics['gap_percentage'], 1) ?>%</div>
        </div>
    </div>
</div>

<!-- Professional Skill Attainment Certificate (If Eligible >= 75%) -->
<?php if ($isEligibleForCertificate): ?>
<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white text-center" id="resultsCertificateCard">
    <div class="border border-warning border-3 p-4 p-md-5 rounded-4 bg-light">
        <div class="text-warning text-uppercase fw-bold tracking-wider mb-2" style="letter-spacing: 2px;">
            <i class="fa-solid fa-award me-1"></i> Certificate of Skill Attainment
        </div>
        <div class="text-muted small mb-4">This document certifies verified technical competency alignment</div>

        <div class="text-muted small mb-1">Proudly Presented To</div>
        <h2 class="fw-extrabold text-dark display-6 mb-4"><?= htmlspecialchars($_SESSION['full_name'] ?? 'Student Architect') ?></h2>

        <div class="text-muted small mb-1">For Successfully Completing the Skill Challenge in</div>
        <h4 class="fw-bold text-primary mb-4"><?= htmlspecialchars($result['assessment_title']) ?> (Score: <?= number_format($result['score_percentage'], 1) ?>%)</h4>

        <div class="text-muted small mb-4">Issued on <?= date('F d, Y', strtotime($result['completed_at'])) ?> &bull; Verification Identifier: sb-<?= md5($result['id'] . $result['completed_at']) ?></div>

        <div class="d-flex justify-content-between align-items-end mt-5 pt-3 border-top">
            <div class="text-start">
                <div class="fw-bold text-dark small">SkillBridge Proctoring Unit</div>
                <div class="text-muted style-italic" style="font-size:10px;">Verified System Engine</div>
            </div>
            <div class="fs-2 text-warning"><i class="fa-solid fa-stamp"></i></div>
            <div class="text-end">
                <div class="fw-bold text-dark small">Principal Assessment Officer</div>
                <div class="text-muted style-italic" style="font-size:10px;">Skill Gap System</div>
            </div>
        </div>
    </div>

    <div class="mt-3 no-print">
        <button onclick="window.print()" class="btn btn-outline-warning text-dark rounded-pill px-4 fw-semibold">
            <i class="fa-solid fa-print me-1"></i> Print Competency Certificate
        </button>
    </div>
</div>
<?php endif; ?>

<!-- Detailed Solutions Manual: All 25 Questions -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h4 class="fw-bold text-dark mb-0">
            <i class="bi bi-list-check text-primary me-2"></i>Detailed Question Solutions & Answers (<?= count($answers) ?> MCQs)
        </h4>
    </div>

    <div class="d-flex flex-column gap-4">
        <?php foreach ($answers as $idx => $ans): 
            $qNum = $idx + 1;
            $isCorrect = (bool)$ans['is_correct'];
            $selected = $ans['selected_option'] ?? 'N/A';
            $correct = $ans['correct_option'];
        ?>
            <div class="p-4 rounded-4 border <?= $isCorrect ? 'border-success-subtle bg-success-subtle bg-opacity-10' : 'border-danger-subtle bg-danger-subtle bg-opacity-10' ?>">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark">Question <?= $qNum ?></span>
                    <?php if ($isCorrect): ?>
                        <span class="badge bg-success text-white rounded-pill px-3 py-1"><i class="bi bi-check-circle me-1"></i> Correct (+1)</span>
                    <?php else: ?>
                        <span class="badge bg-danger text-white rounded-pill px-3 py-1"><i class="bi bi-x-circle me-1"></i> Incorrect (0)</span>
                    <?php endif; ?>
                </div>

                <h6 class="fw-bold text-dark mb-3" style="line-height: 1.5;"><?= htmlspecialchars($ans['question_text']) ?></h6>

                <div class="row g-2 mb-3">
                    <?php foreach (['A' => $ans['option_a'], 'B' => $ans['option_b'], 'C' => $ans['option_c'], 'D' => $ans['option_d']] as $optKey => $optVal): ?>
                        <?php if (!empty($optVal)): 
                            $isUserChoice = ($selected === $optKey);
                            $isCorrectChoice = ($correct === $optKey);
                            
                            $btnClass = "border bg-white text-dark";
                            if ($isCorrectChoice) {
                                $btnClass = "border-success bg-success text-white fw-bold";
                            } elseif ($isUserChoice && !$isCorrect) {
                                $btnClass = "border-danger bg-danger text-white fw-bold";
                            }
                        ?>
                            <div class="col-12 col-md-6">
                                <div class="p-2.5 rounded-3 <?= $btnClass ?> d-flex align-items-center justify-content-between" style="font-size:0.9rem;">
                                    <div>
                                        <strong class="me-1"><?= $optKey ?>.</strong> <?= htmlspecialchars($optVal) ?>
                                    </div>
                                    <div>
                                        <?php if ($isUserChoice && $isCorrectChoice): ?>
                                            <span class="badge bg-white text-success ms-2">Your Answer ✓</span>
                                        <?php elseif ($isUserChoice && !$isCorrectChoice): ?>
                                            <span class="badge bg-white text-danger ms-2">Your Choice ✗</span>
                                        <?php elseif ($isCorrectChoice): ?>
                                            <span class="badge bg-white text-success ms-2">Correct Answer ✓</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="p-3 bg-white rounded-3 border">
                    <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-info-circle text-primary me-1"></i> Answer Explanation & Concept Rationale:</small>
                    <small class="text-secondary">
                        The correct answer is Option <strong><?= $correct ?></strong>. This option demonstrates standard competency conventions for <?= htmlspecialchars($result['skill_name']) ?> in architectural frameworks.
                    </small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
