<?php
/**
 * SkillBridge - Dynamic Student Skill Gap Analysis & Competency Matrix
 * Fully integrated with database persistence, weighted 5-level percentage system, 
 * interactive Chart.js radar visualization, KPI modals, & PDF report exporter.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$userId = $_SESSION['user_id'];
$db = Database::getInstance();

// Fetch student details
$student = $db->fetch(
    "SELECT s.*, u.username, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?",
    [$studentId]
);

$studentName = htmlspecialchars($student['first_name'] . ' ' . $student['last_name']);
$studentDept = htmlspecialchars($student['department'] ?? 'Computer Science');
$targetCareer = !empty($student['department']) ? htmlspecialchars($student['department']) . ' Specialist / Engineer' : 'Full Stack Developer';

// Fetch all active skills from DB
$skillsDataRaw = $db->fetchAll("SELECT s.* FROM skills s ORDER BY s.name ASC");

$skillsData = [];
$radarLabels = [];
$radarActualScores = [];
$radarTargetScores = [];

$totalScoreSum = 0;
$totalSkillsCount = count($skillsDataRaw);
$weakCount = 0;
$strongCount = 0;

foreach ($skillsDataRaw as $s) {
    // ALWAYS use centralized weighted skill percentage formula across all 5 levels
    $weighted = calculate_weighted_skill_percentage($studentId, (int)$s['id']);
    $score = (float)$weighted['overall_percentage'];
    $metrics = calculate_skill_gap($score);

    $gapPct = max(0, round(100.0 - $score, 1));

    $s['best_score'] = $score;
    $s['gap_percentage'] = $gapPct;
    $s['weighted_status'] = $weighted['status'];
    $s['breakdown'] = $weighted['breakdown'];
    $s['attempted_levels'] = $weighted['attempted_levels'];
    $s['metrics'] = $metrics;

    $skillsData[] = $s;
    $skillsDataMap[$s['id']] = $s;

    $totalScoreSum += $score;
    $radarLabels[] = $s['name'];
    $radarActualScores[] = $score;
    $radarTargetScores[] = 100; // Benchmark target is 100%

    if ($score < 60) $weakCount++;
    if ($score >= 75) $strongCount++;
}

// Global Metrics Calculation
$currentSkillLevel = $totalSkillsCount > 0 ? round($totalScoreSum / $totalSkillsCount, 1) : 0.0;
$overallGap = round(max(0, 100.0 - $currentSkillLevel), 1);
$careerMatch = max(15, min(99, round($currentSkillLevel * 0.90 + 10)));
$estLearningWeeks = max(1, round(($overallGap / 100) * 16)); 
$estLearningTimeText = $estLearningWeeks >= 4 ? round($estLearningWeeks / 4, 1) . ' mo' : $estLearningWeeks . ' wk';

// Priority Skills (Sorted by score ascending = highest gap first)
$prioritySkills = $skillsData;
usort($prioritySkills, function($a, $b) {
    return $a['best_score'] <=> $b['best_score'];
});

// Missing / Low Competency Skills (Score < 40% or 0 attempted levels)
$missingSkills = array_filter($skillsData, function($s) {
    return $s['best_score'] < 40 || $s['attempted_levels'] == 0;
});
if (empty($missingSkills)) {
    $missingSkills = array_slice($prioritySkills, 0, 4);
}

// Weakest skill for AI Insights
$weakestSkill = !empty($prioritySkills) ? $prioritySkills[0] : null;
$strongestSkill = !empty($prioritySkills) ? end($prioritySkills) : null;

// --- DATABASE DATA FOR EXPORT PDF REPORT ---
// 1. Check if the student has completed any assessments
$hasAssessments = (int)($db->fetch(
    "SELECT COUNT(*) as cnt FROM assessment_results WHERE student_id = ?",
    [$studentId]
)['cnt'] ?? 0) > 0;

// 2. Fetch completed assessments history
$completedAssessments = $db->fetchAll(
    "SELECT ar.score_obtained, a.total_marks, ar.score_percentage, ar.completed_at,
            a.title as assessment_title, a.difficulty_level,
            s.name as skill_name, s.category as skill_category
     FROM assessment_results ar
     JOIN assessments a ON ar.assessment_id = a.id
     JOIN skills s ON a.skill_id = s.id
     WHERE ar.student_id = ?
     ORDER BY ar.completed_at DESC",
    [$studentId]
);

// 3. Fetch courses completed & total courses
$coursesCompleted = (int)($db->fetch(
    "SELECT COUNT(*) as cnt FROM student_progress WHERE student_id = ? AND (progress_percentage = 100 OR status = 'completed')",
    [$studentId]
)['cnt'] ?? 0);

$totalCoursesCount = (int)($db->fetch(
    "SELECT COUNT(*) as cnt FROM courses"
)['cnt'] ?? 0);

$coursesRemaining = max(0, $totalCoursesCount - $coursesCompleted);

// 4. Certificates earned (Score >= 75%)
$certificatesEarned = (int)($db->fetch(
    "SELECT COUNT(DISTINCT assessment_id) as cnt FROM assessment_results WHERE student_id = ? AND score_percentage >= 75.0",
    [$studentId]
)['cnt'] ?? 0);

// 5. Recommended Learning: weakest 3 skills with their course recommendations
$weakestSkillsForRec = array_slice($prioritySkills, 0, 3);
$recommendedLearning = [];
foreach ($weakestSkillsForRec as $ws) {
    // Find course recommending for this skill
    $courseRec = $db->fetch(
        "SELECT c.title, c.course_code 
         FROM courses c 
         JOIN course_skills cs ON c.id = cs.course_id 
         WHERE cs.skill_id = ? 
         LIMIT 1",
        [$ws['id']]
    );
    $recommendedLearning[] = [
        'skill_name' => $ws['name'],
        'course_title' => $courseRec ? $courseRec['title'] : null,
        'course_code' => $courseRec ? $courseRec['course_code'] : null
    ];
}

$pageTitle = "Skill Gap Analysis - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<!-- Include jsPDF & AutoTable CDNs for Client-Side PDF Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<style>
/* ── Skill Gap Interactive Styles ── */
.clickable-skill-item, .card-stat-clickable {
    cursor: pointer;
    transition: all 0.25s ease;
    user-select: none;
}
.clickable-skill-item:hover, .card-stat-clickable:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(38, 101, 140, 0.12);
    border-color: var(--bs-primary) !important;
    background: rgba(38, 101, 140, 0.04) !important;
}

/* ── Premium SaaS Dashboard KPI Card Styles ── */
.kpi-card-premium {
    position: relative;
    overflow: hidden;
    background: var(--bg-card) !important;
    border: 1px solid var(--border) !important;
    border-radius: 20px !important;
    padding: 1.25rem 1.25rem !important;
    box-shadow: var(--shadow-sm) !important;
    transition: transform 150ms cubic-bezier(0.4, 0, 0.2, 1), 
                box-shadow 150ms cubic-bezier(0.4, 0, 0.2, 1), 
                border-color 150ms cubic-bezier(0.4, 0, 0.2, 1) !important;
    cursor: pointer;
    user-select: none;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

/* Light Mode Accent Glows & Colors */
.kpi-card-blue {
    --kpi-accent: #2563EB;
    --kpi-glow: rgba(37, 99, 235, 0.04);
    --kpi-icon-bg: rgba(37, 99, 235, 0.05);
    --kpi-icon-border: rgba(37, 99, 235, 0.12);
    background-image: radial-gradient(circle at 100% 0%, var(--kpi-glow), transparent 70%) !important;
}
.kpi-card-red {
    --kpi-accent: #EF4444;
    --kpi-glow: rgba(239, 68, 68, 0.04);
    --kpi-icon-bg: rgba(239, 68, 68, 0.05);
    --kpi-icon-border: rgba(239, 68, 68, 0.12);
    background-image: radial-gradient(circle at 100% 0%, var(--kpi-glow), transparent 70%) !important;
}
.kpi-card-amber {
    --kpi-accent: #D97706;
    --kpi-glow: rgba(217, 119, 6, 0.04);
    --kpi-icon-bg: rgba(217, 119, 6, 0.05);
    --kpi-icon-border: rgba(217, 119, 6, 0.12);
    background-image: radial-gradient(circle at 100% 0%, var(--kpi-glow), transparent 70%) !important;
}
.kpi-card-green {
    --kpi-accent: #10B981;
    --kpi-glow: rgba(16, 185, 129, 0.04);
    --kpi-icon-bg: rgba(16, 185, 129, 0.05);
    --kpi-icon-border: rgba(16, 185, 129, 0.12);
    background-image: radial-gradient(circle at 100% 0%, var(--kpi-glow), transparent 70%) !important;
}

/* Dark Mode Overrides */
[data-theme="dark"] .kpi-card-blue {
    --kpi-accent: #60A5FA;
    --kpi-glow: rgba(96, 165, 250, 0.06);
    --kpi-icon-bg: rgba(96, 165, 250, 0.10);
    --kpi-icon-border: rgba(96, 165, 250, 0.20);
}
[data-theme="dark"] .kpi-card-red {
    --kpi-accent: #F87171;
    --kpi-glow: rgba(248, 113, 113, 0.06);
    --kpi-icon-bg: rgba(248, 113, 113, 0.10);
    --kpi-icon-border: rgba(248, 113, 113, 0.20);
}
[data-theme="dark"] .kpi-card-amber {
    --kpi-accent: #FBBF24;
    --kpi-glow: rgba(251, 191, 36, 0.06);
    --kpi-icon-bg: rgba(251, 191, 36, 0.10);
    --kpi-icon-border: rgba(251, 191, 36, 0.20);
}
[data-theme="dark"] .kpi-card-green {
    --kpi-accent: #34D399;
    --kpi-glow: rgba(52, 211, 153, 0.06);
    --kpi-icon-bg: rgba(52, 211, 153, 0.10);
    --kpi-icon-border: rgba(52, 211, 153, 0.20);
}

/* Hover Elevations */
.kpi-card-premium:hover {
    transform: translateY(-2px) !important;
    box-shadow: var(--shadow-md) !important;
    border-color: var(--kpi-accent) !important;
}

/* Circular Premium Icon Container */
.kpi-icon-container {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--kpi-icon-bg);
    border: 1px solid var(--kpi-icon-border);
    color: var(--kpi-accent);
    font-size: 0.95rem;
    flex-shrink: 0;
    transition: transform 150ms ease;
}

.kpi-card-premium:hover .kpi-icon-container {
    transform: scale(1.05);
}

/* Typography styles */
.kpi-label {
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-muted);
    line-height: 1.3;
}

.kpi-value {
    font-size: 1.85rem;
    font-weight: 800;
    letter-spacing: -0.03em;
    color: var(--text-heading);
    margin-top: 0.4rem;
    margin-bottom: 0.25rem;
    line-height: 1.1;
    font-family: var(--font-heading);
}

.kpi-status {
    font-size: 0.74rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}


.modal-backdrop-custom {
    position: fixed;
    inset: 0;
    background: rgba(2, 16, 36, 0.75);
    backdrop-filter: blur(6px);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}
.modal-backdrop-custom.open {
    opacity: 1;
    pointer-events: auto;
}

.modal-container-custom {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    max-width: 680px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    padding: 1.75rem;
    position: relative;
    transform: scale(0.92);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.modal-backdrop-custom.open .modal-container-custom {
    transform: scale(1);
}

.modal-close-btn-custom {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #F4F9FF;
    border: 1px solid #E2E8F0;
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}
.modal-close-btn-custom:hover {
    background: #EF4444;
    color: white;
    border-color: #EF4444;
}

.modal-interactive-pill {
    cursor: pointer;
    padding: 0.75rem 1rem;
    background: #F4F9FF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    transition: all 0.2s ease;
}
.modal-interactive-pill:hover {
    border-color: #26658C;
    background: rgba(38, 101, 140, 0.08);
    transform: translateY(-2px);
}

.cmp-bar-fill {
    height: 100%;
    width: 0%;
    border-radius: 999px;
    transition: width 1.4s cubic-bezier(0.16, 1, 0.3, 1);
}
</style>

<!-- 1. SKILL GAP ANALYSIS HEADER -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-radar text-primary me-2"></i>Skill Gap Analysis</h3>
        <p class="text-muted small mb-0">Detailed breakdown of your current vs required skills for your target career path: <strong class="text-primary"><?= $targetCareer ?></strong></p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold">
            <i class="bi bi-calendar-check me-1"></i> Updated Today
        </span>
        <button onclick="exportPDFReport()" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">
            <i class="fa-solid fa-file-pdf me-1"></i> Export Report
        </button>
    </div>
</div>

<!-- 2. SKILL STATISTICS CARDS (4 Top Clickable KPIs) -->
<div class="row g-3 mb-4">
    <!-- A. Current Skill Level -->
    <div class="col-6 col-lg-3">
        <div class="card h-100 kpi-card-premium kpi-card-blue card-stat-clickable" onclick="openStatCardModal('skill-level')" title="Click for Skill Progress Breakdown">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <span class="kpi-label">Current Skill Level</span>
                    <div class="kpi-icon-container">
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
                <div>
                    <h3 class="kpi-value"><?= number_format($currentSkillLevel, 1) ?>%</h3>
                    <div class="kpi-status" style="color: var(--success-text);">
                        <i class="fa-solid fa-arrow-up"></i> Weighted Average
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- B. Overall Skill Gap -->
    <div class="col-6 col-lg-3">
        <div class="card h-100 kpi-card-premium kpi-card-red card-stat-clickable" onclick="openStatCardModal('skill-gap')" title="Click for Gap Breakdown & Priorities">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <span class="kpi-label">Overall Skill Gap</span>
                    <div class="kpi-icon-container">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
                <div>
                    <h3 class="kpi-value"><?= number_format($overallGap, 1) ?>%</h3>
                    <div class="kpi-status" style="color: var(--danger-text);">
                        <i class="fa-solid fa-arrow-down"></i> Needs Improvement
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- C. Target Career Match -->
    <div class="col-6 col-lg-3">
        <div class="card h-100 kpi-card-premium kpi-card-amber card-stat-clickable" onclick="openStatCardModal('career-match')" title="Click for Career Readiness">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <span class="kpi-label">Target Career Match</span>
                    <div class="kpi-icon-container">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                </div>
                <div>
                    <h3 class="kpi-value"><?= $careerMatch ?>%</h3>
                    <div class="kpi-status" style="color: var(--warning-text);">
                        <i class="fa-solid fa-arrow-up"></i> <?= $studentDept ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- D. Estimated Learning Time -->
    <div class="col-6 col-lg-3">
        <div class="card h-100 kpi-card-premium kpi-card-green card-stat-clickable" onclick="openStatCardModal('learning-time')" title="Click for Study Plan">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <span class="kpi-label">Est. Learning Time</span>
                    <div class="kpi-icon-container">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
                <div>
                    <h3 class="kpi-value"><?= $estLearningTimeText ?></h3>
                    <div class="kpi-status" style="color: var(--success-text);">
                        <i class="fa-solid fa-check"></i> Personalized Plan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. SKILL RADAR CHART + SKILL COMPARISON (SIDE BY SIDE GRID) -->
<div class="row g-4 mb-4">
    <!-- Skill Radar Chart -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-chart-radar text-primary me-2"></i>Skill Competency Radar</h5>
                <div class="d-flex gap-3 small text-muted">
                    <span class="d-flex align-items-center gap-1"><span class="badge bg-primary rounded-circle p-1"></span> Current Score</span>
                    <span class="d-flex align-items-center gap-1"><span class="badge bg-secondary-subtle border rounded-circle p-1"></span> Benchmark (100%)</span>
                </div>
            </div>
            <div style="position: relative; height: 320px;" class="d-flex align-items-center justify-content-center">
                <canvas id="skillRadarCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Skill Comparison Progress Bars -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-arrow-right-arrow-left text-accent me-2"></i>Skill Comparison</h5>
                <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1">vs 100% Required</span>
            </div>

            <div class="d-flex flex-column gap-3 overflow-y-auto" style="max-height: 320px;">
                <?php foreach ($skillsData as $s): 
                    $score = $s['best_score'];
                    $barColor = match(true) {
                        $score >= 75 => '#10B981',
                        $score >= 40 => '#F59E0B',
                        default => '#EF4444'
                    };
                    $statusIcon = match(true) {
                        $score >= 75 => 'fa-circle-check',
                        $score >= 40 => 'fa-triangle-exclamation',
                        default => 'fa-circle-xmark'
                    };
                    $statusLabel = match(true) {
                        $score >= 75 => 'Strong Skill',
                        $score >= 40 => 'Needs Improvement',
                        default => 'Low Skill (Critical)'
                    };
                ?>
                    <div class="skill-cmp-row">
                        <div class="d-flex justify-content-between small fw-semibold mb-1">
                            <span class="text-dark"><?= htmlspecialchars($s['name']) ?></span>
                            <span>
                                <strong style="color: <?= $barColor ?>"><?= number_format($score, 1) ?>%</strong> 
                                <span class="text-muted">/ 100%</span>
                            </span>
                        </div>
                        <div class="progress rounded-pill bg-light" style="height: 10px;">
                            <div class="progress-bar rounded-pill" style="width: <?= max(4, $score) ?>%; background-color: <?= $barColor ?>;"></div>
                        </div>
                        <div class="small mt-1 fw-medium" style="color: <?= $barColor ?>; font-size: 11px;">
                            <i class="fa-solid <?= $statusIcon ?> me-1"></i> Gap: <?= number_format($s['gap_percentage'], 1) ?>% — <?= $statusLabel ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- 4. PRIORITY SKILLS & MISSING SKILLS (SIDE BY SIDE CARDS) -->
<div class="row g-4 mb-4">
    <!-- Priority Skills Card -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-bullseye text-danger me-2"></i>Priority Skills</h5>
                    <span class="badge bg-danger-subtle text-danger border rounded-pill px-3 py-1">High Impact</span>
                </div>

                <div class="d-flex flex-column gap-2">
                    <?php 
                    $rank = 1;
                    foreach (array_slice($prioritySkills, 0, 5) as $ps): 
                        $badgeClass = match(true) {
                            $ps['best_score'] < 30 => 'bg-danger text-white',
                            $ps['best_score'] < 60 => 'bg-warning text-dark',
                            default => 'bg-info text-white'
                        };
                        $priorityLabel = match(true) {
                            $ps['best_score'] < 30 => 'Critical',
                            $ps['best_score'] < 60 => 'High',
                            default => 'Medium'
                        };
                    ?>
                        <div class="clickable-skill-item p-3 border rounded-3 d-flex align-items-center justify-content-between" onclick="openSkillModal('<?= $ps['id'] ?>')">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px; height:28px; font-size:12px;"><?= $rank++ ?></span>
                                <div>
                                    <div class="fw-semibold text-dark small"><?= htmlspecialchars($ps['name']) ?></div>
                                    <div class="text-muted" style="font-size: 11px;"><?= number_format($ps['gap_percentage'], 1) ?>% gap · <?= htmlspecialchars($ps['category']) ?></div>
                                </div>
                            </div>
                            <span class="badge <?= $badgeClass ?> rounded-pill px-2.5 py-1"><?= $priorityLabel ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mt-4">
                <a href="<?= BASE_URL ?>student/recommendations.php" class="btn btn-primary bg-gradient-primary border-0 rounded-pill w-100 py-2.5 fw-semibold">
                    <i class="fa-solid fa-graduation-cap me-1"></i> Start Priority Learning
                </a>
            </div>
        </div>
    </div>

    <!-- Missing Skills Card -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-circle-xmark text-danger me-2"></i>Missing & Low Competency Skills</h5>
                    <span class="badge bg-danger-subtle text-danger border rounded-pill px-3 py-1"><?= count($missingSkills) ?> Required Gaps</span>
                </div>

                <div class="d-flex flex-column gap-2">
                    <?php foreach (array_slice($missingSkills, 0, 5) as $ms): ?>
                        <div class="clickable-skill-item p-3 border border-danger-subtle rounded-3 bg-danger-subtle bg-opacity-10 d-flex align-items-center justify-content-between" onclick="openSkillModal('<?= $ms['id'] ?>')">
                            <div>
                                <div class="fw-semibold text-dark small"><?= htmlspecialchars($ms['name']) ?></div>
                                <div class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($ms['description'] ?? 'Requires foundational module completion.') ?></div>
                            </div>
                            <span class="badge bg-danger text-white rounded-pill px-2.5 py-1"><?= number_format($ms['best_score'], 0) ?>% Score</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mt-4">
                <a href="<?= BASE_URL ?>student/assessments.php" class="btn btn-outline-secondary rounded-pill w-100 py-2.5 fw-semibold">
                    <i class="fa-solid fa-list-check me-1"></i> Explore All Skill Assessments
                </a>
            </div>
        </div>
    </div>
</div>



<!-- ══════════ TOP STAT CARDS MODAL POPUP ══════════ -->
<div id="statCardModal" class="modal-backdrop-custom" onclick="handleStatModalBackdropClick(event)">
    <div class="modal-container-custom">
        <button class="modal-close-btn-custom" onclick="closeStatModal()" title="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div id="statCardModalContent"></div>
    </div>
</div>

<!-- ══════════ DYNAMIC SKILL DETAIL MODAL POPUP ══════════ -->
<div id="skillDetailModal" class="modal-backdrop-custom" onclick="handleModalBackdropClick(event)">
    <div class="modal-container-custom">
        <button class="modal-close-btn-custom" onclick="closeSkillModal()" title="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="p-3 rounded-3 bg-primary text-white fs-3" id="modalSkillIcon">
                <i class="fa-solid fa-code"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-dark" id="modalSkillName">Skill Name</h4>
                <div class="d-flex align-items-center gap-2">
                    <span id="modalSkillStatus" class="badge bg-primary">Status</span>
                    <span id="modalSkillDifficulty" class="text-muted small">Category: Technical</span>
                </div>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-3">
                <div class="modal-interactive-pill text-center" onclick="switchModalSubView('score')">
                    <div class="text-muted small text-uppercase">Score</div>
                    <div class="fw-extrabold text-primary fs-5" id="modalSkillScore">0%</div>
                    <div class="text-primary small fw-semibold" style="font-size: 10px;"><i class="fa-solid fa-chart-line"></i> Graph</div>
                </div>
            </div>
            <div class="col-3">
                <div class="modal-interactive-pill text-center" onclick="switchModalSubView('timeline')">
                    <div class="text-muted small text-uppercase">Est. Time</div>
                    <div class="fw-extrabold text-info fs-5" id="modalSkillTime">14 Days</div>
                    <div class="text-info small fw-semibold" style="font-size: 10px;"><i class="fa-solid fa-calendar"></i> Plan</div>
                </div>
            </div>
            <div class="col-3">
                <div class="modal-interactive-pill text-center" onclick="switchModalSubView('overall')">
                    <div class="text-muted small text-uppercase">Levels</div>
                    <div class="fw-extrabold text-success fs-5" id="modalSkillCount">0 / 5</div>
                    <div class="text-success small fw-semibold" style="font-size: 10px;"><i class="fa-solid fa-list-check"></i> Tiers</div>
                </div>
            </div>
            <div class="col-3">
                <div class="modal-interactive-pill text-center" onclick="switchModalSubView('career')">
                    <div class="text-muted small text-uppercase">Match</div>
                    <div class="fw-extrabold text-warning fs-5" id="modalCareerMatch">0%</div>
                    <div class="text-warning small fw-semibold" style="font-size: 10px;"><i class="fa-solid fa-briefcase"></i> Details</div>
                </div>
            </div>
        </div>

        <div class="p-3 bg-light rounded-3 mb-3 border">
            <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-circle-info text-primary me-1"></i> Skill Description & Importance</h6>
            <p id="modalSkillDesc" class="text-secondary small mb-2" style="line-height:1.5;"></p>
            <div class="small fw-semibold text-dark">
                <span class="text-primary">Why Important:</span> <span id="modalSkillImportance" class="fw-normal text-secondary"></span>
            </div>
        </div>

        <div class="mb-3">
            <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-book-bookmark text-info me-1"></i> Level-wise Progression Breakdown</h6>
            <div id="modalSkillTopics" class="d-flex flex-wrap gap-2"></div>
        </div>

        <div id="modalDynamicSubView" class="p-3 bg-white border rounded-3 shadow-xs"></div>
    </div>
</div>

<script>
// JSON Skills Database passed directly from PHP PDO query
const skillsDataMap = <?php echo json_encode($skillsDataMap); ?>;
let activeModalSkillId = null;

let skillRadarChartInstance = null;

window.initSkillGap = function() {
    if (skillRadarChartInstance) {
        try {
            skillRadarChartInstance.destroy();
        } catch(e) {}
        skillRadarChartInstance = null;
    }

    const ctx = document.getElementById('skillRadarCanvas');
    if (!ctx) return;

    skillRadarChartInstance = new Chart(ctx.getContext('2d'), {
        type: 'radar',
        data: {
            labels: <?php echo json_encode($radarLabels); ?>,
            datasets: [
                {
                    label: 'Current Score (%)',
                    data: <?php echo json_encode($radarActualScores); ?>,
                    backgroundColor: 'rgba(38, 101, 140, 0.25)',
                    borderColor: '#26658C',
                    borderWidth: 2,
                    pointBackgroundColor: '#14B8A6',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#26658C'
                },
                {
                    label: 'Benchmark Target (100%)',
                    data: <?php echo json_encode($radarTargetScores); ?>,
                    backgroundColor: 'rgba(226, 232, 240, 0.15)',
                    borderColor: '#CBD5E1',
                    borderWidth: 1.5,
                    borderDash: [4, 4],
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { color: 'rgba(0, 0, 0, 0.08)' },
                    grid: { color: 'rgba(0, 0, 0, 0.08)' },
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: { backdropColor: 'transparent', stepSize: 25 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
};
// Handled by app.js runPageSpecificInitializer()

// Modal Handler Functions
function openStatCardModal(type) {
    const content = document.getElementById('statCardModalContent');
    if (type === 'skill-level') {
        content.innerHTML = 
            '<h4 class="fw-bold text-dark mb-2"><i class="fa-solid fa-star text-primary me-2"></i>Current Skill Level Breakdown</h4>' +
            '<p class="text-muted small">Your overall proficiency level is <strong><?php echo number_format($currentSkillLevel, 1); ?>%</strong>, calculated across all evaluated technical skills.</p>' +
            '<div class="p-3 bg-light rounded-3 mb-3 border">' +
                '<div class="d-flex justify-content-between small fw-bold mb-1">' +
                    '<span>Mastery Progress</span>' +
                    '<span class="text-primary"><?php echo number_format($currentSkillLevel, 1); ?>%</span>' +
                '</div>' +
                '<div class="progress rounded-pill mb-2" style="height:10px;">' +
                    '<div class="progress-bar bg-primary rounded-pill" style="width: <?php echo max(5, $currentSkillLevel); ?>%"></div>' +
                '</div>' +
                '<div class="small text-muted">Evaluated Skills: <?php echo $totalSkillsCount; ?> | High Competency: <?php echo $strongCount; ?> | Needs Work: <?php echo $weakCount; ?></div>' +
            '</div>';
    } else if (type === 'skill-gap') {
        content.innerHTML = 
            '<h4 class="fw-bold text-dark mb-2"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Overall Skill Gap Breakdown</h4>' +
            '<p class="text-muted small">Your total remaining skill gap is <strong><?php echo number_format($overallGap, 1); ?>%</strong>. Closing this gap accelerates career progression.</p>' +
            '<div class="p-3 bg-danger-subtle bg-opacity-10 border border-danger-subtle rounded-3 mb-3">' +
                '<div class="fw-bold text-danger mb-1">Top Priority Area</div>' +
                '<div class="small text-secondary">Focus on <strong><?php echo htmlspecialchars($weakestSkill['name'] ?? 'Core Skills'); ?></strong> (Gap: <?php echo number_format($weakestSkill['gap_percentage'] ?? 0, 1); ?>%).</div>' +
            '</div>';
    } else if (type === 'career-match') {
        content.innerHTML = 
            '<h4 class="fw-bold text-dark mb-2"><i class="fa-solid fa-briefcase text-warning me-2"></i>Target Career Readiness</h4>' +
            '<p class="text-muted small">You are currently a <strong><?php echo $careerMatch; ?>%</strong> match for your target role: <strong><?php echo $targetCareer; ?></strong>.</p>' +
            '<div class="p-3 bg-light rounded-3 mb-3 border">' +
                '<div class="small fw-semibold text-dark mb-1">Role Alignment</div>' +
                '<div class="progress rounded-pill mb-2" style="height:10px;">' +
                    '<div class="progress-bar bg-warning rounded-pill" style="width: <?php echo $careerMatch; ?>%"></div>' +
                '</div>' +
                '<div class="small text-muted">Field: <?php echo $studentDept; ?></div>' +
            '</div>';
    } else {
        content.innerHTML = 
            '<h4 class="fw-bold text-dark mb-2"><i class="fa-solid fa-clock text-success me-2"></i>Estimated Study Schedule</h4>' +
            '<p class="text-muted small">Based on your current gap, dedicated study of 2 hrs/day will close critical gaps in <strong><?php echo $estLearningTimeText; ?></strong>.</p>';
    }
    document.getElementById('statCardModal').classList.add('open');
}

function closeStatModal() {
    document.getElementById('statCardModal').classList.remove('open');
}

function handleStatModalBackdropClick(e) {
    if (e.target.id === 'statCardModal') closeStatModal();
}

function openSkillModal(skillId) {
    const s = skillsDataMap[skillId];
    if (!s) return;

    activeModalSkillId = skillId;
    document.getElementById('modalSkillName').textContent = s.name;
    document.getElementById('modalSkillStatus').textContent = s.weighted_status;
    document.getElementById('modalSkillDifficulty').textContent = 'Category: ' + s.category;
    document.getElementById('modalSkillScore').textContent = number_format(s.best_score, 1) + '%';
    document.getElementById('modalSkillTime').textContent = Math.ceil(s.gap_percentage / 3) + ' Days';
    document.getElementById('modalSkillCount').textContent = s.attempted_levels + ' / 5';
    document.getElementById('modalCareerMatch').textContent = Math.min(99, Math.round(s.best_score * 0.9 + 10)) + '%';
    
    document.getElementById('modalSkillDesc').textContent = s.description || 'Technical skill evaluation module covering core topics and practical assessments.';
    document.getElementById('modalSkillImportance').textContent = 'High priority for ' + s.category + ' technical competency.';

    const topicsContainer = document.getElementById('modalSkillTopics');
    topicsContainer.innerHTML = '';
    const levels = ['Beginner (10%)', 'Easy (15%)', 'Intermediate (20%)', 'Advanced (25%)', 'Expert (30%)'];
    const levelKeys = ['beginner', 'easy', 'intermediate', 'advanced', 'expert'];
    
    levelKeys.forEach((key, idx) => {
        const lvlData = s.breakdown[key] || { attempted: false, score_pct: 0 };
        const badge = document.createElement('span');
        badge.className = 'badge ' + (lvlData.attempted ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border');
        badge.innerHTML = levels[idx] + ': ' + (lvlData.attempted ? lvlData.score_pct + '%' : 'Unattempted');
        topicsContainer.appendChild(badge);
    });

    switchModalSubView('score');
    document.getElementById('skillDetailModal').classList.add('open');
}

function closeSkillModal() {
    document.getElementById('skillDetailModal').classList.remove('open');
}

function handleModalBackdropClick(e) {
    if (e.target.id === 'skillDetailModal') closeSkillModal();
}

function switchModalSubView(view) {
    const container = document.getElementById('modalDynamicSubView');
    const s = skillsDataMap[activeModalSkillId];
    if (!s) return;

    if (view === 'score') {
        let rowsHtml = '';
        Object.keys(s.breakdown).forEach(function(lvl) {
            const b = s.breakdown[lvl];
            const badgeBg = b.attempted ? 'bg-primary' : 'bg-secondary';
            rowsHtml += '<tr>' +
                '<td class="text-capitalize fw-semibold">' + lvl + '</td>' +
                '<td>' + b.weight + '%</td>' +
                '<td><span class="badge ' + badgeBg + '">' + b.score_pct + '%</span></td>' +
                '<td class="fw-bold text-success">+' + b.contribution + '%</td>' +
            '</tr>';
        });

        container.innerHTML = 
            '<div class="fw-bold text-dark small mb-2"><i class="fa-solid fa-chart-line text-primary me-1"></i> Level-by-Level Weighted Breakdown</div>' +
            '<div class="table-responsive">' +
                '<table class="table table-sm text-center align-middle mb-0">' +
                    '<thead class="bg-light">' +
                        '<tr><th>Level</th><th>Weight</th><th>Best Score</th><th>Contribution</th></tr>' +
                    '</thead>' +
                    '<tbody>' + rowsHtml + '</tbody>' +
                '</table>' +
            '</div>';
    } else if (view === 'timeline') {
        container.innerHTML = 
            '<div class="fw-bold text-dark small mb-2"><i class="fa-solid fa-calendar-days text-info me-1"></i> Recommended Study Milestones</div>' +
            '<p class="text-muted small mb-0">Complete remaining unattempted levels in <strong>' + s.name + '</strong> to raise your overall score by up to <strong>' + s.gap_percentage + '%</strong>.</p>';
    } else if (view === 'overall') {
        container.innerHTML = 
            '<div class="fw-bold text-dark small mb-2"><i class="fa-solid fa-list-check text-success me-1"></i> Competency Level Status</div>' +
            '<div class="small text-secondary">Attempted Tiers: <strong>' + s.attempted_levels + ' / 5</strong>. Status: <span class="badge bg-success">' + s.weighted_status + '</span></div>';
    } else {
        container.innerHTML = 
            '<div class="fw-bold text-dark small mb-2"><i class="fa-solid fa-briefcase text-warning me-1"></i> Role Readiness Impact</div>' +
            '<p class="text-muted small mb-0">Mastering <strong>' + s.name + '</strong> improves your eligibility for senior technical software roles.</p>';
    }
}

function number_format(num, decimals) {
    return parseFloat(num).toFixed(decimals);
}

const reportStudentName = <?php echo json_encode($studentName); ?>;
const reportStudentUsername = <?php echo json_encode($student['username']); ?>;
const reportStudentDept = <?php echo json_encode($studentDept); ?>;
const reportDate = <?php echo json_encode(date("M d, Y")); ?>;
const reportSkillLevel = <?php echo json_encode(number_format($currentSkillLevel, 1)); ?>;
const reportOverallGap = <?php echo json_encode(number_format($overallGap, 1)); ?>;
const reportCareerMatch = <?php echo json_encode((string)$careerMatch); ?>;
const reportSemester = <?php echo json_encode($student['current_semester']); ?>;
const reportLearningPath = <?php echo json_encode($targetCareer); ?>;
const reportHasAssessments = <?php echo json_encode($hasAssessments); ?>;
const reportTotalSkills = <?php echo json_encode($totalSkillsCount); ?>;
const reportSkillsMastered = <?php echo json_encode($strongCount); ?>;
const reportSkillsInProgress = <?php echo json_encode($totalSkillsCount - $weakCount - $strongCount); ?>;
const reportSkillsNeedImprovement = <?php echo json_encode($weakCount); ?>;
const reportAssessmentsHistory = <?php echo json_encode($completedAssessments); ?>;
const reportCoursesCompleted = <?php echo json_encode($coursesCompleted); ?>;
const reportCoursesRemaining = <?php echo json_encode($coursesRemaining); ?>;
const reportCertificatesEarned = <?php echo json_encode($certificatesEarned); ?>;
const reportRecommendations = <?php echo json_encode($recommendedLearning); ?>;

const reportTableRows = <?php 
    $pdfRows = [];
    foreach ($skillsData as $s) {
        $pdfRows[] = [
            $s['name'],
            $s['category'],
            number_format($s['best_score'], 1) . '%',
            number_format($s['gap_percentage'], 1) . '%',
            $s['attempted_levels'] . ' / 5',
            $s['weighted_status']
        ];
    }
    echo json_encode($pdfRows);
?>;

function getFormattedDate() {
    const d = new Date();
    const year = d.getFullYear();
    let month = '' + (d.getMonth() + 1);
    let day = '' + d.getDate();
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    return [year, month, day].join('-');
}

function exportPDFReport() {
    if (!window.jspdf || !window.jspdf.jsPDF) {
        alert('PDF library loading... Please try again in a moment.');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');

    // ─── 1. REPORT HEADER ───
    // Draw premium logo icon
    doc.setFillColor(38, 101, 140);
    doc.roundedRect(14, 15, 10, 10, 2, 2, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(8);
    doc.text('SB', 16.5, 22);

    // Title
    doc.setTextColor(2, 16, 36);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(14);
    doc.text('SkillBridge', 27, 22);

    // Meta details
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(8.5);
    doc.setTextColor(100, 116, 139);
    doc.text('Skill Gap Analysis Report', 196, 18, { align: 'right' });
    doc.text('Generated: ' + reportDate + ' ' + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }), 196, 23, { align: 'right' });

    // Divider
    doc.setDrawColor(226, 232, 240);
    doc.setLineWidth(0.5);
    doc.line(14, 28, 196, 28);

    // ─── 2. STUDENT METADATA CARD ───
    doc.setFillColor(248, 250, 252);
    doc.rect(14, 32, 182, 28, 'F');
    doc.setDrawColor(226, 232, 240);
    doc.rect(14, 32, 182, 28, 'S');

    doc.setTextColor(2, 16, 36);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(9);
    doc.text('STUDENT PROFILE', 18, 38);

    doc.setFont('helvetica', 'normal');
    doc.setFontSize(8.5);
    doc.setTextColor(71, 85, 105);
    doc.text('Name: ' + reportStudentName, 18, 44);
    doc.text('Username: ' + reportStudentUsername, 18, 49);
    doc.text('Department: ' + reportStudentDept, 18, 54);

    doc.text('Learning Path: ' + reportLearningPath, 110, 44);
    doc.text('Current Semester: ' + (reportSemester ? 'Semester ' + reportSemester : 'N/A'), 110, 49);
    doc.text('Report ID: SB-GAP-' + Math.floor(100000 + Math.random() * 900000), 110, 54);

    // ─── 3. EMPTY STATE CHECK ───
    if (!reportHasAssessments) {
        doc.setFillColor(254, 242, 242);
        doc.rect(14, 68, 182, 40, 'F');
        doc.setDrawColor(248, 113, 113);
        doc.rect(14, 68, 182, 40, 'S');

        doc.setTextColor(220, 38, 38);
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10.5);
        doc.text('No Skill Gap Data Available', 20, 78);

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8.5);
        doc.setTextColor(127, 29, 29);
        doc.text('You have not completed any skill assessments yet.', 20, 86);
        doc.text('Complete an assessment on the platform to generate your personalized Skill Gap Report.', 20, 92);

        writeFooterAndSave(doc);
        return;
    }

    // ─── 4. OVERALL SKILL SUMMARY ───
    doc.setTextColor(2, 16, 36);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.text('Overall Competency Summary', 14, 68);

    const cardWidth = 56;
    const cardHeight = 18;
    const cardGap = 7;

    // Card 1
    doc.setFillColor(248, 250, 252);
    doc.roundedRect(14, 73, cardWidth, cardHeight, 1.5, 1.5, 'F');
    doc.setDrawColor(226, 232, 240);
    doc.roundedRect(14, 73, cardWidth, cardHeight, 1.5, 1.5, 'S');
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(7.5);
    doc.setTextColor(100, 116, 139);
    doc.text('OVERALL SKILL SCORE', 18, 78);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(12.5);
    doc.setTextColor(38, 101, 140);
    doc.text(reportSkillLevel + '%', 18, 86);

    // Card 2
    doc.setFillColor(248, 250, 252);
    doc.roundedRect(14 + cardWidth + cardGap, 73, cardWidth, cardHeight, 1.5, 1.5, 'F');
    doc.roundedRect(14 + cardWidth + cardGap, 73, cardWidth, cardHeight, 1.5, 1.5, 'S');
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(7.5);
    doc.setTextColor(100, 116, 139);
    doc.text('CAREER MATCH SCORE', 14 + cardWidth + cardGap + 4, 78);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(12.5);
    doc.setTextColor(217, 119, 6);
    doc.text(reportCareerMatch + '%', 14 + cardWidth + cardGap + 4, 86);

    // Card 3
    doc.setFillColor(248, 250, 252);
    doc.roundedRect(14 + (cardWidth + cardGap) * 2, 73, cardWidth, cardHeight, 1.5, 1.5, 'F');
    doc.roundedRect(14 + (cardWidth + cardGap) * 2, 73, cardWidth, cardHeight, 1.5, 1.5, 'S');
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(7.5);
    doc.setTextColor(100, 116, 139);
    doc.text('ASSESSED SKILLS STATUS', 14 + (cardWidth + cardGap) * 2 + 4, 78);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(8.5);
    doc.setTextColor(2, 16, 36);
    doc.text('Mastered: ' + reportSkillsMastered + ' | In Progress: ' + reportSkillsInProgress, 14 + (cardWidth + cardGap) * 2 + 4, 86);

    // ─── 5. SKILL GAP ANALYSIS TABLE ───
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.setTextColor(2, 16, 36);
    doc.text('Skill Gap Analysis Breakdown', 14, 98);

    const tableBody = reportTableRows.map(row => {
        return [
            row[0], 
            row[2], 
            '100.0%', 
            row[3], 
            row[5]  
        ];
    });

    doc.autoTable({
        startY: 102,
        head: [['Skill Name', 'Current Score', 'Target Score', 'Skill Gap', 'Status']],
        body: tableBody,
        theme: 'striped',
        headStyles: { fillColor: [38, 101, 140], textColor: [255, 255, 255], fontStyle: 'bold' },
        styles: { fontSize: 8.5, cellPadding: 2.2 },
        columnStyles: {
            0: { cellWidth: 50 },
            1: { cellWidth: 33, halign: 'center' },
            2: { cellWidth: 33, halign: 'center' },
            3: { cellWidth: 33, halign: 'center' },
            4: { cellWidth: 33, halign: 'center' }
        }
    });

    // ─── 6. ASSESSMENT HISTORY ───
    let nextY = doc.lastAutoTable.finalY + 10;
    if (nextY + 35 > 280) {
        doc.addPage();
        nextY = 20;
    }

    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.setTextColor(2, 16, 36);
    doc.text('Assessment History Log', 14, nextY);

    const historyBody = reportAssessmentsHistory.map(row => {
        const scoreStr = row.score_obtained + ' / ' + row.total_marks;
        const pctStr = parseFloat(row.score_percentage).toFixed(1) + '%';
        const dateStr = new Date(row.completed_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
        return [
            row.assessment_title,
            row.skill_category,
            row.difficulty_level.charAt(0).toUpperCase() + row.difficulty_level.slice(1),
            scoreStr,
            pctStr,
            dateStr
        ];
    });

    doc.autoTable({
        startY: nextY + 4,
        head: [['Assessment Title', 'Category', 'Difficulty', 'Marks', 'Percentage', 'Date Completed']],
        body: historyBody.length > 0 ? historyBody : [['No completed assessments found.', '', '', '', '', '']],
        theme: 'striped',
        headStyles: { fillColor: [2, 16, 36], textColor: [255, 255, 255], fontStyle: 'bold' },
        styles: { fontSize: 8, cellPadding: 2.2 }
    });

    // ─── 7. PROGRESS & RECOMMENDATIONS ───
    nextY = doc.lastAutoTable.finalY + 10;
    if (nextY + 48 > 280) {
        doc.addPage();
        nextY = 20;
    }

    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.setTextColor(2, 16, 36);
    doc.text('Learning Actions & Progress Summary', 14, nextY);

    // Box 1
    doc.setFillColor(250, 251, 252);
    doc.rect(14, nextY + 4, 88, 38, 'F');
    doc.setDrawColor(226, 232, 240);
    doc.rect(14, nextY + 4, 88, 38, 'S');

    doc.setTextColor(2, 16, 36);
    doc.setFontSize(8.5);
    doc.setFont('helvetica', 'bold');
    doc.text('Progress Overview', 18, nextY + 9);

    doc.setFont('helvetica', 'normal');
    doc.setTextColor(71, 85, 105);
    doc.text('• Learning Pathway: ' + reportLearningPath, 18, nextY + 15);
    doc.text('• Courses Completed: ' + reportCoursesCompleted, 18, nextY + 20);
    doc.text('• Courses Remaining: ' + reportCoursesRemaining, 18, nextY + 25);
    doc.text('• Certificates Earned: ' + reportCertificatesEarned, 18, nextY + 30);
    doc.text('• Current Rank: N/A', 18, nextY + 35);

    // Box 2
    doc.setFillColor(250, 251, 252);
    doc.rect(108, nextY + 4, 88, 38, 'F');
    doc.rect(108, nextY + 4, 88, 38, 'S');

    doc.setTextColor(38, 101, 140);
    doc.setFont('helvetica', 'bold');
    doc.text('Recommended Actions', 112, nextY + 9);

    doc.setFont('helvetica', 'normal');
    doc.setTextColor(71, 85, 105);
    
    let recY = nextY + 15;
    reportRecommendations.slice(0, 3).forEach(function(rec) {
        if (rec.course_title) {
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(2, 16, 36);
            doc.text('• Complete ' + rec.course_code + ':', 112, recY);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(71, 85, 105);
            doc.text(rec.course_title, 112, recY + 4);
            recY += 8;
        } else {
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(100, 116, 139);
            doc.text('• For ' + rec.skill_name + ':', 112, recY);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(148, 163, 184);
            doc.text('Course not currently available.', 112, recY + 4);
            recY += 8;
        }
    });

    writeFooterAndSave(doc);
}

function writeFooterAndSave(doc) {
    const totalPages = doc.internal.getNumberOfPages();
    for (let i = 1; i <= totalPages; i++) {
        doc.setPage(i);
        
        // Footer line
        doc.setDrawColor(226, 232, 240);
        doc.setLineWidth(0.5);
        doc.line(14, 282, 196, 282);
        
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(7.5);
        doc.setTextColor(148, 163, 184);
        doc.text('Generated by SkillBridge – Skill Gap Analysis & Learning Management System', 14, 287);
        doc.text('This report is generated automatically based on the student\'s current learning progress and assessment data.', 14, 291);
        
        doc.text('Page ' + i + ' of ' + totalPages, 196, 287, { align: 'right' });
    }

    doc.save('SkillBridge_SkillGapReport_' + reportStudentUsername + '_' + getFormattedDate() + '.pdf');
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
