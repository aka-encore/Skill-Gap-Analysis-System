<?php
/**
 * SkillBridge - Faculty Student Roster & Evaluation Directory
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('faculty');
check_suspended_status();

$db = Database::getInstance();

// Initial page state parameters for initial load (optional GET query parameters)
$initSearch = trim($_GET['search'] ?? '');
$initDept = trim($_GET['department'] ?? '');
$initSem = trim($_GET['semester'] ?? '');
$initSkill = trim($_GET['skill_id'] ?? '');

// AJAX handler
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');

    $search = trim($_GET['search'] ?? '');
    $dept = trim($_GET['department'] ?? '');
    $semester = trim($_GET['semester'] ?? '');
    $skillId = trim($_GET['skill_id'] ?? '');
    $progressFilter = trim($_GET['progress'] ?? '');
    $assessStatusFilter = trim($_GET['assessment_status'] ?? '');
    $statusFilter = trim($_GET['status'] ?? '');
    $sort = trim($_GET['sort'] ?? 'name_asc');
    $page = max(1, (int)($_GET['page'] ?? 1));

    $where = " WHERE 1=1";
    $params = [];

    // 1. Search Query across Name, Username, Code, Email, Department, Sem/Year, Course, Skill, Progress, Status
    if (!empty($search)) {
        $searchWildcard = "%$search%";
        $where .= " AND (
            s.first_name LIKE ? OR
            s.last_name LIKE ? OR
            CONCAT(s.first_name, ' ', s.last_name) LIKE ? OR
            u.username LIKE ? OR
            s.student_code LIKE ? OR
            u.email LIKE ? OR
            s.department LIKE ? OR
            CONCAT('Sem ', s.current_semester) LIKE ? OR
            CONCAT('Semester ', s.current_semester) LIKE ? OR
            CONCAT('Year ', CEIL(s.current_semester / 2)) LIKE ? OR
            CONCAT('Yr ', CEIL(s.current_semester / 2)) LIKE ? OR
            EXISTS (SELECT 1 FROM student_progress sp JOIN courses c ON sp.course_id = c.id WHERE sp.student_id = s.id AND c.title LIKE ?) OR
            EXISTS (SELECT 1 FROM assessment_results ar JOIN assessments a ON ar.assessment_id = a.id JOIN skills sk ON a.skill_id = sk.id WHERE ar.student_id = s.id AND sk.name LIKE ?) OR
            EXISTS (SELECT 1 FROM student_progress sp JOIN course_skills cs ON sp.course_id = cs.course_id JOIN skills sk ON cs.skill_id = sk.id WHERE sp.student_id = s.id AND sk.name LIKE ?)
        ";
        
        // Add 14 parameter instances for the 14 placeholders above
        for ($i = 0; $i < 14; $i++) {
            $params[] = $searchWildcard;
        }

        // Check if search is numeric to match current semester or overall progress rounded
        if (is_numeric($search)) {
            $searchInt = (int)$search;
            $where .= " OR s.current_semester = ? OR ROUND((SELECT COALESCE(AVG(progress_percentage), 0) FROM student_progress WHERE student_id = s.id)) = ?";
            $params[] = $searchInt;
            $params[] = $searchInt;
        }

        // Special keywords for Assessment Status
        $lowerSearch = strtolower($search);
        if ($lowerSearch === 'pass' || $lowerSearch === 'passed') {
            $where .= " OR EXISTS (SELECT 1 FROM assessment_results WHERE student_id = s.id AND status = 'pass')";
        } elseif ($lowerSearch === 'fail' || $lowerSearch === 'failed') {
            $where .= " OR (EXISTS (SELECT 1 FROM assessment_results WHERE student_id = s.id AND status = 'fail') AND NOT EXISTS (SELECT 1 FROM assessment_results WHERE student_id = s.id AND status = 'pass'))";
        } elseif ($lowerSearch === 'none' || $lowerSearch === 'no assessments' || $lowerSearch === 'pending') {
            $where .= " OR NOT EXISTS (SELECT 1 FROM assessment_results WHERE student_id = s.id)";
        }

        $where .= " )";
    }

    // 2. Department filter
    if (!empty($dept)) {
        $where .= " AND s.department = ?";
        $params[] = $dept;
    }

    // 3. Semester filter
    if (!empty($semester)) {
        $where .= " AND s.current_semester = ?";
        $params[] = (int)$semester;
    }

    // 4. Skill filter (attempted assessment for skill or has course progress mapped to skill)
    if (!empty($skillId)) {
        $where .= " AND (
            EXISTS (SELECT 1 FROM assessment_results ar JOIN assessments a ON ar.assessment_id = a.id WHERE ar.student_id = s.id AND a.skill_id = ?) OR
            EXISTS (SELECT 1 FROM student_progress sp JOIN course_skills cs ON sp.course_id = cs.course_id WHERE sp.student_id = s.id AND cs.skill_id = ?)
        )";
        $params[] = (int)$skillId;
        $params[] = (int)$skillId;
    }

    // 5. Progress filter
    if (!empty($progressFilter)) {
        switch ($progressFilter) {
            case 'not_started':
                $where .= " AND (SELECT COALESCE(AVG(progress_percentage), 0) FROM student_progress WHERE student_id = s.id) = 0";
                break;
            case 'under_50':
                $where .= " AND (SELECT COALESCE(AVG(progress_percentage), 0) FROM student_progress WHERE student_id = s.id) > 0 AND (SELECT COALESCE(AVG(progress_percentage), 0) FROM student_progress WHERE student_id = s.id) < 50";
                break;
            case '50_or_more':
                $where .= " AND (SELECT COALESCE(AVG(progress_percentage), 0) FROM student_progress WHERE student_id = s.id) >= 50 AND (SELECT COALESCE(AVG(progress_percentage), 0) FROM student_progress WHERE student_id = s.id) < 100";
                break;
            case 'completed':
                $where .= " AND (SELECT COALESCE(AVG(progress_percentage), 0) FROM student_progress WHERE student_id = s.id) = 100";
                break;
        }
    }

    // 6. Assessment Status filter
    if (!empty($assessStatusFilter)) {
        switch ($assessStatusFilter) {
            case 'passed':
                $where .= " AND EXISTS (SELECT 1 FROM assessment_results WHERE student_id = s.id AND status = 'pass')";
                break;
            case 'failed':
                $where .= " AND EXISTS (SELECT 1 FROM assessment_results WHERE student_id = s.id AND status = 'fail') AND NOT EXISTS (SELECT 1 FROM assessment_results WHERE student_id = s.id AND status = 'pass')";
                break;
            case 'none':
                $where .= " AND NOT EXISTS (SELECT 1 FROM assessment_results WHERE student_id = s.id)";
                break;
        }
    }

    // 7. Active/Inactive user account filter
    if (!empty($statusFilter)) {
        if ($statusFilter === 'active') {
            $where .= " AND u.status = 'active'";
        } elseif ($statusFilter === 'inactive') {
            $where .= " AND u.status != 'active'";
        }
    }

    // 8. Sorting
    $orderBy = " ORDER BY s.first_name ASC, s.last_name ASC"; // default
    switch ($sort) {
        case 'name_desc':
            $orderBy = " ORDER BY s.first_name DESC, s.last_name DESC";
            break;
        case 'newest':
            $orderBy = " ORDER BY s.created_at DESC, s.id DESC";
            break;
        case 'oldest':
            $orderBy = " ORDER BY s.created_at ASC, s.id ASC";
            break;
        case 'progress_desc':
            $orderBy = " ORDER BY (SELECT COALESCE(AVG(progress_percentage), 0) FROM student_progress WHERE student_id = s.id) DESC, s.first_name ASC";
            break;
        case 'progress_asc':
            $orderBy = " ORDER BY (SELECT COALESCE(AVG(progress_percentage), 0) FROM student_progress WHERE student_id = s.id) ASC, s.first_name ASC";
            break;
        case 'score_desc':
            $orderBy = " ORDER BY (SELECT COALESCE(AVG(score_percentage), 0) FROM assessment_results WHERE student_id = s.id) DESC, s.first_name ASC";
            break;
        case 'score_asc':
            $orderBy = " ORDER BY (SELECT COALESCE(AVG(score_percentage), 0) FROM assessment_results WHERE student_id = s.id) ASC, s.first_name ASC";
            break;
        case 'latest_assessment':
            $orderBy = " ORDER BY (SELECT MAX(completed_at) FROM assessment_results WHERE student_id = s.id) DESC, s.first_name ASC";
            break;
        case 'oldest_assessment':
            $orderBy = " ORDER BY (SELECT MIN(completed_at) FROM assessment_results WHERE student_id = s.id) ASC, s.first_name ASC";
            break;
    }

    // Get Total Count for Pagination
    $countSql = "SELECT COUNT(DISTINCT s.id) as cnt FROM students s JOIN users u ON s.user_id = u.id" . $where;
    $totalRow = $db->fetch($countSql, $params);
    $totalRecords = (int)($totalRow['cnt'] ?? 0);

    // Pagination constants
    $pageSize = 10;
    $totalPages = max(1, ceil($totalRecords / $pageSize));
    $page = max(1, min($totalPages, $page));
    $offset = ($page - 1) * $pageSize;

    // Main records query
    $mainSql = "SELECT s.*, u.email, u.username, u.status as user_status,
                       (SELECT COUNT(*) FROM assessment_results WHERE student_id = s.id) as tests_completed,
                       (SELECT COALESCE(AVG(score_percentage), 0) FROM assessment_results WHERE student_id = s.id) as avg_score,
                       (SELECT COALESCE(AVG(progress_percentage), 0) FROM student_progress WHERE student_id = s.id) as overall_progress,
                       (SELECT MAX(completed_at) FROM assessment_results WHERE student_id = s.id) as latest_assessment_at,
                       (SELECT MIN(completed_at) FROM assessment_results WHERE student_id = s.id) as oldest_assessment_at
                FROM students s
                JOIN users u ON s.user_id = u.id" . $where . $orderBy . " LIMIT " . (int)$pageSize . " OFFSET " . (int)$offset;

    $students = $db->fetchAll($mainSql, $params);

    echo json_encode([
        'success' => true,
        'data' => $students,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_records' => $totalRecords,
            'page_size' => $pageSize
        ]
    ]);
    exit;
}

// Fetch all available skills and unique departments for the dropdown options
$skills = $db->fetchAll("SELECT id, name FROM skills ORDER BY name ASC");
$departments = $db->fetchAll("SELECT DISTINCT department FROM students WHERE department != '' ORDER BY department ASC");

$pageTitle = "Students Roster - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<style>
.notif-pagination-btn {
    min-width: 36px;
    height: 36px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--bg-card, #ffffff);
    color: var(--text-body);
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
    padding: 0;
}
.notif-pagination-btn:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
}
.notif-pagination-btn.active {
    background: var(--primary);
    color: #ffffff;
    border-color: var(--primary);
}
.notif-pagination-btn.disabled {
    opacity: 0.5;
    pointer-events: none;
    cursor: not-allowed;
}
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-people text-primary me-2"></i>Enrolled Students Roster</h3>
        <p class="text-muted small mb-0">View student profiles, evaluation metrics, and individual skill gap reports</p>
    </div>
    <a href="<?= BASE_URL ?>faculty/student-import.php" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
        <i class="bi bi-cloud-arrow-up me-1"></i> Import Students
    </a>
</div>

<!-- Modern Filter and Search Controls Card -->
<div class="saas-card mb-4">
    <div class="card-body p-3">
        <form id="filterForm" class="row g-3">
            <!-- Row 1: Search, Sort and Clear Filters -->
            <div class="col-lg-6 col-md-12">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="pointer-events:none; z-index:1;"></i>
                    <input type="text" id="searchInput" class="saas-form-control ps-5 w-100" placeholder="Search by name, username, code, email, course, skill, status...">
                </div>
            </div>
            
            <div class="col-lg-4 col-md-8">
                <select id="sortSelect" class="saas-form-select w-100">
                    <option value="name_asc">Sort by: Name (A-Z)</option>
                    <option value="name_desc">Sort by: Name (Z-A)</option>
                    <option value="newest">Sort by: Newest Registered</option>
                    <option value="oldest">Sort by: Oldest Registered</option>
                    <option value="progress_desc">Sort by: Highest Progress</option>
                    <option value="progress_asc">Sort by: Lowest Progress</option>
                    <option value="score_desc">Sort by: Highest Skill Score</option>
                    <option value="score_asc">Sort by: Lowest Skill Score</option>
                    <option value="latest_assessment">Sort by: Latest Assessment</option>
                    <option value="oldest_assessment">Sort by: Oldest Assessment</option>
                </select>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <button type="button" id="clearFiltersBtn" class="btn btn-outline-secondary rounded-pill w-100 fw-semibold">
                    <i class="bi bi-x-circle me-1"></i> Clear
                </button>
            </div>
            
            <!-- Row 2: Advanced Dropdown Filters -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small fw-semibold mb-1">Department</label>
                <select id="deptFilter" class="saas-form-select w-100">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= htmlspecialchars($d['department']) ?>"><?= htmlspecialchars($d['department']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small fw-semibold mb-1">Semester</label>
                <select id="semesterFilter" class="saas-form-select w-100">
                    <option value="">All Semesters</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>">Semester <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small fw-semibold mb-1">Target Skill</label>
                <select id="skillFilter" class="saas-form-select w-100">
                    <option value="">All Skills</option>
                    <?php foreach ($skills as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small fw-semibold mb-1">Overall Progress</label>
                <select id="progressFilter" class="saas-form-select w-100">
                    <option value="">All Progress Levels</option>
                    <option value="not_started">Not Started (0%)</option>
                    <option value="under_50">Under 50% Progress</option>
                    <option value="50_or_more">50% or More Progress</option>
                    <option value="completed">Completed (100%)</option>
                </select>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small fw-semibold mb-1">Assessment Status</label>
                <select id="assessStatusFilter" class="saas-form-select w-100">
                    <option value="">All Statuses</option>
                    <option value="passed">Passed (Has &ge;1 Pass)</option>
                    <option value="failed">Failed (Only Failures)</option>
                    <option value="none">No Assessments Taken</option>
                </select>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small fw-semibold mb-1">Account Status</label>
                <select id="statusFilter" class="saas-form-select w-100">
                    <option value="">All Accounts</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive / Suspended</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="saas-card overflow-hidden mb-4">
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
                <tbody id="studentsTableBody">
                    <!-- Loaded dynamically via JavaScript AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination Panel -->
<div id="paginationContainer" class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 pt-2">
    <span class="text-muted small" id="paginationInfo">Showing 0-0 of 0 students</span>
    <div class="d-flex align-items-center gap-1" id="paginationButtons">
        <!-- Renders dynamically -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Current pagination & filter state variables
    const state = {
        search: <?= json_encode($initSearch) ?>,
        department: <?= json_encode($initDept) ?>,
        semester: <?= json_encode($initSem) ?>,
        skill_id: <?= json_encode($initSkill) ?>,
        progress: '',
        assessment_status: '',
        status: '',
        sort: 'name_asc',
        page: 1
    };

    // Grab elements
    const searchInput = document.getElementById('searchInput');
    const deptFilter = document.getElementById('deptFilter');
    const semesterFilter = document.getElementById('semesterFilter');
    const skillFilter = document.getElementById('skillFilter');
    const progressFilter = document.getElementById('progressFilter');
    const assessStatusFilter = document.getElementById('assessStatusFilter');
    const statusFilter = document.getElementById('statusFilter');
    const sortSelect = document.getElementById('sortSelect');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    // Populate initial inputs if query params existed
    if (state.search) searchInput.value = state.search;
    if (state.department) deptFilter.value = state.department;
    if (state.semester) semesterFilter.value = state.semester;
    if (state.skill_id) skillFilter.value = state.skill_id;

    // Debounce helper to prevent heavy queries on typing
    let debounceTimer;
    function debounce(func, delay) {
        return function(...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Fetch and load student records
    async function fetchStudents() {
        const tbody = document.getElementById('studentsTableBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="spinner-border text-primary spinner-border-sm me-2" role="status"></div>
                    <span class="text-muted small">Loading students roster...</span>
                </td>
            </tr>
        `;

        const queryParams = new URLSearchParams({
            ajax: 1,
            search: state.search,
            department: state.department,
            semester: state.semester,
            skill_id: state.skill_id,
            progress: state.progress,
            assessment_status: state.assessment_status,
            status: state.status,
            sort: state.sort,
            page: state.page
        });

        try {
            const response = await fetch(`${window.BASE_URL}faculty/students.php?${queryParams.toString()}`);
            if (!response.ok) throw new Error('Response error');
            const result = await response.json();

            if (result.success) {
                renderTable(result.data);
                renderPagination(result.pagination);
            } else {
                showError('Error loading roster database entries.');
            }
        } catch (e) {
            console.error('AJAX Fetch Failed:', e);
            showError('Unable to connect to the server. Please try again.');
        }
    }

    // HTML escape function
    function escapeHtml(str) {
        if (!str) return '';
        return str.toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Render table rows
    function renderTable(students) {
        const tbody = document.getElementById('studentsTableBody');
        if (!students || students.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7">
                        <div class="saas-empty-state py-5 text-center">
                            <div class="saas-empty-icon text-muted mb-2"><i class="bi bi-people" style="font-size: 2.5rem;"></i></div>
                            <h6 class="fw-bold text-dark mb-1">No students match your search.</h6>
                            <p class="text-muted small mb-0">Try clearing filters or adjusting your search term.</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        students.forEach(st => {
            const avg = roundNum(st.avg_score, 1);
            const progress = Math.round(st.overall_progress);
            const statusBadge = st.user_status === 'active' 
                ? '<span class="badge bg-success-light text-success ms-1 small" style="font-size: 10px;">Active</span>' 
                : '<span class="badge bg-danger-light text-danger ms-1 small" style="font-size: 10px;">Inactive</span>';

            html += `
                <tr>
                    <td class="ps-4"><span class="badge saas-badge-primary">${escapeHtml(st.student_code)}</span></td>
                    <td>
                        <div class="fw-semibold text-dark">${escapeHtml(st.first_name)} ${escapeHtml(st.last_name)} ${statusBadge}</div>
                        <div class="small text-muted" style="font-size: 11px;">@${escapeHtml(st.username)}</div>
                    </td>
                    <td>
                        <span class="small text-dark fw-medium">${escapeHtml(st.department)}</span>
                        <div class="small text-muted" style="font-size: 11px;">Semester ${st.current_semester}</div>
                    </td>
                    <td><span class="small text-muted">${escapeHtml(st.email)}</span></td>
                    <td><span class="badge saas-badge-info">${st.tests_completed} Tests</span></td>
                    <td>
                        <strong class="text-dark">${avg}%</strong>
                        <div class="text-muted" style="font-size: 11px;">Progress: ${progress}%</div>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="${window.BASE_URL}faculty/recommend-courses.php?student_id=${st.id}" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">
                            <i class="bi bi-journal-plus me-1"></i> Recommend
                        </a>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    // Helper to format float values
    function roundNum(val, dec) {
        const floatVal = parseFloat(val);
        return isNaN(floatVal) ? '0.0' : floatVal.toFixed(dec);
    }

    // Render pagination buttons & info
    function renderPagination(pag) {
        const info = document.getElementById('paginationInfo');
        const buttons = document.getElementById('paginationButtons');

        const { current_page, total_pages, total_records, page_size } = pag;

        if (total_records === 0) {
            info.textContent = 'Showing 0-0 of 0 students';
            buttons.innerHTML = '';
            return;
        }

        const start = (current_page - 1) * page_size + 1;
        const end = Math.min(current_page * page_size, total_records);

        info.textContent = `Showing ${start}-${end} of ${total_records} students`;

        let btnHtml = '';

        // Back Arrow
        btnHtml += `
            <button type="button" class="notif-pagination-btn ${current_page === 1 ? 'disabled' : ''}" 
                    onclick="changePage(${current_page - 1})" aria-label="Previous">
                <i class="bi bi-chevron-left"></i>
            </button>
        `;

        // Page buttons (visible range of 5 pages)
        const visiblePages = 5;
        let startPage = Math.max(1, current_page - Math.floor(visiblePages / 2));
        let endPage = Math.min(total_pages, startPage + visiblePages - 1);

        if (endPage - startPage + 1 < visiblePages) {
            startPage = Math.max(1, endPage - visiblePages + 1);
        }

        if (startPage > 1) {
            btnHtml += `
                <button type="button" class="notif-pagination-btn" onclick="changePage(1)">1</button>
                ${startPage > 2 ? '<span class="px-1 text-muted">...</span>' : ''}
            `;
        }

        for (let i = startPage; i <= endPage; i++) {
            btnHtml += `
                <button type="button" class="notif-pagination-btn ${i === current_page ? 'active' : ''}" 
                        onclick="changePage(${i})">${i}</button>
            `;
        }

        if (endPage < total_pages) {
            btnHtml += `
                ${endPage < total_pages - 1 ? '<span class="px-1 text-muted">...</span>' : ''}
                <button type="button" class="notif-pagination-btn" onclick="changePage(${total_pages})">${total_pages}</button>
            `;
        }

        // Forward Arrow
        btnHtml += `
            <button type="button" class="notif-pagination-btn ${current_page === total_pages ? 'disabled' : ''}" 
                    onclick="changePage(${current_page + 1})" aria-label="Next">
                <i class="bi bi-chevron-right"></i>
            </button>
        `;

        buttons.innerHTML = btnHtml;
    }

    // Display AJAX database error alerts
    function showError(msg) {
        const tbody = document.getElementById('studentsTableBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="7">
                    <div class="alert alert-danger mx-4 my-3 py-2.5 px-3 small border-0 rounded-3 text-center">
                        <i class="bi bi-exclamation-triangle me-1"></i> ${escapeHtml(msg)}
                    </div>
                </td>
            </tr>
        `;
        document.getElementById('paginationInfo').textContent = 'Showing 0-0 of 0 students';
        document.getElementById('paginationButtons').innerHTML = '';
    }

    // Page state changer
    window.changePage = function(pageNumber) {
        state.page = pageNumber;
        fetchStudents();
    };

    // Bind filters event listeners
    searchInput.addEventListener('input', debounce(function(e) {
        state.search = e.target.value.trim();
        state.page = 1;
        fetchStudents();
    }, 300));

    deptFilter.addEventListener('change', function(e) {
        state.department = e.target.value;
        state.page = 1;
        fetchStudents();
    });

    semesterFilter.addEventListener('change', function(e) {
        state.semester = e.target.value;
        state.page = 1;
        fetchStudents();
    });

    skillFilter.addEventListener('change', function(e) {
        state.skill_id = e.target.value;
        state.page = 1;
        fetchStudents();
    });

    progressFilter.addEventListener('change', function(e) {
        state.progress = e.target.value;
        state.page = 1;
        fetchStudents();
    });

    assessStatusFilter.addEventListener('change', function(e) {
        state.assessment_status = e.target.value;
        state.page = 1;
        fetchStudents();
    });

    statusFilter.addEventListener('change', function(e) {
        state.status = e.target.value;
        state.page = 1;
        fetchStudents();
    });

    sortSelect.addEventListener('change', function(e) {
        state.sort = e.target.value;
        state.page = 1;
        fetchStudents();
    });

    // Clear filters button action
    clearFiltersBtn.addEventListener('click', function() {
        state.search = '';
        state.department = '';
        state.semester = '';
        state.skill_id = '';
        state.progress = '';
        state.assessment_status = '';
        state.status = '';
        state.sort = 'name_asc';
        state.page = 1;

        searchInput.value = '';
        deptFilter.value = '';
        semesterFilter.value = '';
        skillFilter.value = '';
        progressFilter.value = '';
        assessStatusFilter.value = '';
        statusFilter.value = '';
        sortSelect.value = 'name_asc';

        fetchStudents();
    });

    // Initial Fetch
    fetchStudents();
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
