<?php
/**
 * SkillBridge - Faculty Student Roster & Evaluation Directory
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');

$db = Database::getInstance();

$search = trim($_GET['search'] ?? '');
$dept = trim($_GET['department'] ?? '');

$sql = "SELECT s.*, u.email, u.username,
               (SELECT COUNT(*) FROM assessment_results WHERE student_id = s.id) as tests_completed,
               (SELECT AVG(score_percentage) FROM assessment_results WHERE student_id = s.id) as avg_score
        FROM students s
        JOIN users u ON s.user_id = u.id
        WHERE 1=1";

$params = [];
if (!empty($search)) {
    $sql .= " AND (s.first_name LIKE ? OR s.last_name LIKE ? OR s.student_code LIKE ? OR u.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($dept)) {
    $sql .= " AND s.department = ?";
    $params[] = $dept;
}

$sql .= " ORDER BY s.student_code ASC";

$students = $db->fetchAll($sql, $params);

$pageTitle = "Students Roster - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-people text-primary me-2"></i>Enrolled Students Roster</h3>
        <p class="text-muted small mb-0">View student profiles, evaluation metrics, and individual skill gap reports</p>
    </div>
</div>

<div class="saas-card mb-4">
    <div class="card-body p-3">
        <form action="<?= BASE_URL ?>faculty/students.php" method="GET" class="row g-2 align-items-center">
            <div class="col-md-7">
                <input type="text" name="search" class="saas-form-control w-100" placeholder="Search by student name, code, or email..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="department" class="saas-form-select w-100">
                    <option value="">All Departments</option>
                    <option value="Computer Science" <?= $dept === 'Computer Science' ? 'selected' : '' ?>>Computer Science</option>
                    <option value="Information Technology" <?= $dept === 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
                    <option value="Software Engineering" <?= $dept === 'Software Engineering' ? 'selected' : '' ?>>Software Engineering</option>
                    <option value="Data Science" <?= $dept === 'Data Science' ? 'selected' : '' ?>>Data Science</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-semibold">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="saas-card overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="saas-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Student Code</th>
                        <th>Student Name</th>
                        <th>Department & Sem</th>
                        <th>Email Contact</th>
                        <th>Tests Taken</th>
                        <th>Average Score</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="saas-empty-state">
                                    <div class="saas-empty-icon"><i class="bi bi-people"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">No students matching criteria found</h6>
                                    <p class="text-muted small mb-0">Try clearing filters or adjusting your search term.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $st): 
                            $avg = round((float)($st['avg_score'] ?? 0), 1);
                        ?>
                            <tr>
                                <td class="ps-4"><span class="badge saas-badge-primary"><?= htmlspecialchars($st['student_code']) ?></span></td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($st['first_name'] . ' ' . $st['last_name']) ?></div>
                                </td>
                                <td><span class="small text-muted"><?= htmlspecialchars($st['department']) ?> &bull; Sem <?= $st['current_semester'] ?></span></td>
                                <td><span class="small text-muted"><?= htmlspecialchars($st['email']) ?></span></td>
                                <td><span class="badge saas-badge-info"><?= $st['tests_completed'] ?> Tests</span></td>
                                <td><strong class="text-dark"><?= $avg ?>%</strong></td>
                                <td class="pe-4 text-end">
                                    <a href="<?= BASE_URL ?>faculty/recommend-courses.php?student_id=<?= $st['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">
                                        <i class="bi bi-journal-plus me-1"></i> Recommend
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
