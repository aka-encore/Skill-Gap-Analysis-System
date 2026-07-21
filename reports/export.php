<?php
/**
 * SkillBridge - Multi-Format Institutional Reports Exporter
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$db = Database::getInstance();
$type = $_GET['type'] ?? 'student_performance';
$format = $_GET['format'] ?? 'html'; // 'html' for printable view, 'csv' for CSV download

if ($format === 'csv') {
    $filename = "skillbridge_report_" . $type . "_" . date('Ymd_His') . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $output = fopen('php://output', 'w');

    if ($type === 'student_performance') {
        fputcsv($output, ['Student Code', 'First Name', 'Last Name', 'Assessment Title', 'Score %', 'Status', 'Completed At']);
        $rows = $db->fetchAll(
            "SELECT s.student_code, s.first_name, s.last_name, a.title, ar.score_percentage, ar.status, ar.completed_at
             FROM assessment_results ar
             JOIN students s ON ar.student_id = s.id
             JOIN assessments a ON ar.assessment_id = a.id
             ORDER BY ar.completed_at DESC"
        );
        foreach ($rows as $r) {
            fputcsv($output, [
                $r['student_code'],
                $r['first_name'],
                $r['last_name'],
                $r['title'],
                $r['score_percentage'] . '%',
                strtoupper($r['status']),
                $r['completed_at']
            ]);
        }
    } else {
        fputcsv($output, ['Skill Name', 'Category', 'Average Score %', 'Tested Students']);
        $rows = $db->fetchAll(
            "SELECT s.name, s.category, AVG(ar.score_percentage) as avg_score, COUNT(DISTINCT ar.student_id) as student_count
             FROM skills s
             LEFT JOIN assessments a ON s.id = a.skill_id
             LEFT JOIN assessment_results ar ON a.id = ar.assessment_id
             GROUP BY s.id, s.name, s.category"
        );
        foreach ($rows as $r) {
            fputcsv($output, [
                $r['name'],
                $r['category'],
                number_format((float)$r['avg_score'], 2) . '%',
                $r['student_count']
            ]);
        }
    }
    fclose($output);
    exit;
}

// Default: Printable HTML / PDF Export View
$reportTitle = match($type) {
    'skill_gap' => 'Institutional Skill Gap & Deficit Report',
    'course_completion' => 'Course Enrollment & Completion Metrics Report',
    default => 'Comprehensive Student Performance Evaluation Report'
};

$records = [];
if ($type === 'skill_gap') {
    $records = $db->fetchAll(
        "SELECT s.name as skill_name, s.category, AVG(ar.score_percentage) as avg_score, COUNT(DISTINCT ar.student_id) as tested_count
         FROM skills s
         LEFT JOIN assessments a ON s.id = a.skill_id
         LEFT JOIN assessment_results ar ON a.id = ar.assessment_id
         GROUP BY s.id, s.name, s.category
         ORDER BY avg_score ASC"
    );
} elseif ($type === 'course_completion') {
    $records = $db->fetchAll(
        "SELECT c.course_code, c.title, c.duration_hours, COUNT(sp.id) as total_enrolled,
                SUM(CASE WHEN sp.status = 'completed' THEN 1 ELSE 0 END) as completed_count
         FROM courses c
         LEFT JOIN student_progress sp ON c.id = sp.course_id
         GROUP BY c.id, c.course_code, c.title, c.duration_hours"
    );
} else {
    $records = $db->fetchAll(
        "SELECT ar.*, s.student_code, s.first_name, s.last_name, a.title as assessment_title
         FROM assessment_results ar
         JOIN students s ON ar.student_id = s.id
         JOIN assessments a ON ar.assessment_id = a.id
         ORDER BY ar.completed_at DESC"
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($reportTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #fff; color: #000; padding: 2rem; }
        .report-header { border-bottom: 3px double #000; padding-bottom: 1rem; margin-bottom: 2rem; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-between align-items-center mb-4 no-print bg-light p-3 rounded border">
    <div>
        <strong>Format Options:</strong>
        <a href="<?= BASE_URL ?>reports/export.php?type=<?= $type ?>&format=csv" class="btn btn-success btn-sm ms-2"><i class="bi bi-file-earmark-excel"></i> Download CSV</a>
    </div>
    <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> Print / Save as PDF</button>
</div>

<div class="report-header d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold mb-0">SkillBridge LMS</h2>
        <div class="text-uppercase text-muted small" style="letter-spacing: 1px;">Skill Gap Analysis & Learning Management System</div>
    </div>
    <div class="text-end small text-muted">
        <div><strong>Date Generated:</strong> <?= date('F d, Y h:i A') ?></div>
        <div><strong>Report Identifier:</strong> RPT-<?= strtoupper(substr(md5($type . time()), 0, 8)) ?></div>
    </div>
</div>

<h3 class="fw-bold mb-3"><?= htmlspecialchars($reportTitle) ?></h3>

<table class="table table-bordered align-middle small mb-4">
    <?php if ($type === 'skill_gap'): ?>
        <thead class="table-light">
            <tr>
                <th>Skill Name</th>
                <th>Category</th>
                <th>Tested Students</th>
                <th>Class Average Score</th>
                <th>Skill Benchmark Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $r): 
                $avg = round((float)($r['avg_score'] ?? 0), 1);
            ?>
                <tr>
                    <td class="fw-bold"><?= htmlspecialchars($r['skill_name']) ?></td>
                    <td><?= htmlspecialchars($r['category']) ?></td>
                    <td><?= $r['tested_count'] ?></td>
                    <td><strong><?= $avg ?>%</strong></td>
                    <td>
                        <span class="badge bg-<?= $avg >= 60 ? 'success' : 'danger' ?>">
                            <?= $avg >= 60 ? 'BENCHMARK MET' : 'CRITICAL SKILL DEFICIT' ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    <?php elseif ($type === 'course_completion'): ?>
        <thead class="table-light">
            <tr>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Duration</th>
                <th>Enrolled Students</th>
                <th>Completed Count</th>
                <th>Completion Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $r): 
                $rate = $r['total_enrolled'] > 0 ? round(($r['completed_count'] / $r['total_enrolled']) * 100, 1) : 0;
            ?>
                <tr>
                    <td class="fw-bold"><?= htmlspecialchars($r['course_code']) ?></td>
                    <td><?= htmlspecialchars($r['title']) ?></td>
                    <td><?= $r['duration_hours'] ?> Hrs</td>
                    <td><?= $r['total_enrolled'] ?></td>
                    <td><?= $r['completed_count'] ?></td>
                    <td><strong><?= $rate ?>%</strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    <?php else: ?>
        <thead class="table-light">
            <tr>
                <th>Student Code</th>
                <th>Student Name</th>
                <th>Assessment</th>
                <th>Score Obtained</th>
                <th>Percentage</th>
                <th>Status</th>
                <th>Date Completed</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $r): ?>
                <tr>
                    <td><code><?= htmlspecialchars($r['student_code']) ?></code></td>
                    <td class="fw-bold"><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?></td>
                    <td><?= htmlspecialchars($r['assessment_title']) ?></td>
                    <td><?= $r['score_obtained'] ?> / <?= $r['total_questions'] ?></td>
                    <td><strong><?= number_format($r['score_percentage'], 1) ?>%</strong></td>
                    <td><span class="badge bg-<?= $r['status'] === 'pass' ? 'success' : 'danger' ?>"><?= strtoupper($r['status']) ?></span></td>
                    <td><?= date('M d, Y', strtotime($r['completed_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    <?php endif; ?>
</table>

<div class="row pt-5 mt-5 border-top">
    <div class="col-6">
        <div class="small text-muted">Authorized System Administrator Signature</div>
        <div style="height: 40px; border-bottom: 1px solid #ccc; width: 220px;" class="my-2"></div>
        <div class="small fw-bold">SkillBridge Academic Oversight</div>
    </div>
    <div class="col-6 text-end">
        <div class="small text-muted">&copy; <?= date('Y') ?> SkillBridge LMS. Institutional Record.</div>
    </div>
</div>

</body>
</html>
