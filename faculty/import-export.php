<?php
/**
 * SkillBridge - Faculty Bulk Student Import & Export Portal
 * System for Faculty members to bulk import and export Student records via CSV/Excel templates.
 * Restricts faculty from accessing Faculty account import/export.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';
require_once __DIR__ . '/../includes/import_export_helper.php';

require_role('faculty');

$db = Database::getInstance();
$facultyUserId = $_SESSION['user_id'];
$facultyProfileId = $_SESSION['profile_id'];

// Action Handling (Restricted to Students Only)
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$entityType = 'students'; // Strict role restriction: Faculty can ONLY manage Students

if ($action === 'download_template') {
    download_student_import_template();
    exit;
}

if ($action === 'export') {
    $selectedIds = $_POST['selected_ids'] ?? [];
    if (!is_array($selectedIds)) $selectedIds = [];

    $filters = [
        'department' => trim($_REQUEST['department'] ?? 'all'),
        'search'     => trim($_REQUEST['search'] ?? '')
    ];

    export_students_to_csv($db, $filters, $selectedIds, $facultyUserId);
    exit;
}

$error = '';
$success = '';
$previewData = null;

// Handle Form Submissions (File Upload & Import Execution)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } elseif (isset($_POST['confirm_import'])) {
        $importPayloadJson = $_POST['import_payload'] ?? '';
        $importRows = json_decode($importPayloadJson, true);

        if (empty($importRows) || !is_array($importRows)) {
            $error = 'Import session expired or invalid payload.';
        } else {
            try {
                $count = execute_student_import($importRows, $db, $facultyUserId);
                set_flash_message('success', "Successfully imported {$count} student records into your department roster!");
                redirect(BASE_URL . 'faculty/students.php');
            } catch (Throwable $e) {
                $error = 'Import database transaction error: ' . $e->getMessage();
            }
        }
    } elseif (isset($_FILES['csv_file'])) {
        $file = $_FILES['csv_file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $error = 'File upload failed. Please try again.';
        } else {
            $fileName = $file['name'];
            $fileTmp  = $file['tmp_name'];
            $fileSize = $file['size'];
            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($ext, ['csv', 'txt'])) {
                $error = 'Invalid file extension. Only .csv files are supported.';
            } elseif ($fileSize > 10 * 1024 * 1024) {
                $error = 'File size exceeds maximum allowed limit (10MB).';
            } else {
                $parsed = parse_uploaded_csv($fileTmp);
                if (!$parsed['success']) {
                    $error = $parsed['error'];
                } elseif (empty($parsed['rows'])) {
                    $error = 'Uploaded file contains no data rows.';
                } else {
                    $previewData = validate_student_import_rows($parsed['rows'], $db);
                    $previewData['entity_type'] = 'students';
                }
            }
        }
    }
}

// Fetch departments list for filter
$departmentsList = array_column($db->fetchAll("SELECT DISTINCT department FROM students WHERE department IS NOT NULL AND department != ''"), 'department');

$pageTitle = "Student Roster Bulk Import & Export – Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3); color: #818cf8; font-size: 1.5rem;">
            <i class="bi bi-cloud-arrow-up"></i>
        </div>
        <div>
            <h3 class="fw-bold mb-0" style="color: var(--text-heading);">Bulk Student Import & Export</h3>
            <p class="text-muted small mb-0">Import student class rosters or export current student data via CSV/Excel templates</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>faculty/students.php" class="btn btn-outline-secondary rounded-pill px-3 py-2 small fw-semibold">
        <i class="bi bi-people me-1"></i> Students Roster
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4 shadow-xs"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2.5 px-3 small border-0 rounded-3 mb-4 shadow-xs"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if ($previewData): ?>
    <!-- Preview & Validation Card -->
    <div class="saas-card overflow-hidden mb-4 border-primary">
        <div class="saas-card-header bg-primary bg-opacity-10 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-primary">
                <i class="bi bi-file-earmark-check me-2"></i>Student Import Preview & Validation Summary
            </h5>
            <span class="badge bg-primary rounded-pill px-3 py-1.5">Total Rows Processed: <?= $previewData['total_rows'] ?></span>
        </div>
        <div class="card-body p-4">
            <!-- Metrics Summary Grid -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 text-center">
                        <span class="d-block text-muted small fw-semibold text-uppercase">Valid Records</span>
                        <strong class="fs-3 text-success"><?= $previewData['valid_count'] ?></strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 text-center">
                        <span class="d-block text-muted small fw-semibold text-uppercase">Invalid / Errors</span>
                        <strong class="fs-3 text-danger"><?= $previewData['invalid_count'] ?></strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25 text-center">
                        <span class="d-block text-muted small fw-semibold text-uppercase">Duplicates Found</span>
                        <strong class="fs-3 text-warning"><?= $previewData['duplicate_count'] ?></strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-light border text-center">
                        <span class="d-block text-muted small fw-semibold text-uppercase">Ready To Import</span>
                        <strong class="fs-3 text-primary"><?= $previewData['valid_count'] ?></strong>
                    </div>
                </div>
            </div>

            <!-- Preview Data Table -->
            <div class="table-responsive mb-4" style="max-height: 380px;">
                <table class="saas-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ROW #</th>
                            <th>STUDENT NAME</th>
                            <th>EMAIL</th>
                            <th>USERNAME</th>
                            <th>DEPARTMENT</th>
                            <th class="pe-4 text-end">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($previewData['rows'] as $r): ?>
                            <tr class="<?= !$r['is_valid'] ? 'bg-danger bg-opacity-10' : '' ?>">
                                <td class="ps-4 fw-semibold"><?= $r['row_num'] ?></td>
                                <td><strong style="color: var(--text-heading);"><?= htmlspecialchars(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? '')) ?></strong></td>
                                <td class="small"><?= htmlspecialchars($r['email'] ?? '') ?></td>
                                <td class="small font-monospace"><?= htmlspecialchars($r['username'] ?? '') ?></td>
                                <td class="small"><?= htmlspecialchars($r['department'] ?? '') ?></td>
                                <td class="pe-4 text-end">
                                    <?php if ($r['is_valid']): ?>
                                        <span class="saas-badge saas-badge-success"><i class="bi bi-check-circle me-1"></i> Valid</span>
                                    <?php else: ?>
                                        <span class="saas-badge saas-badge-danger" title="<?= htmlspecialchars($r['error_text']) ?>"><i class="bi bi-x-circle me-1"></i> <?= htmlspecialchars($r['error_text']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-2 border-top">
                <a href="<?= BASE_URL ?>faculty/import-export.php" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i> Cancel & Re-upload
                </a>

                <div class="d-flex gap-2">
                    <?php if ($previewData['valid_count'] > 0): ?>
                        <form action="<?= BASE_URL ?>faculty/import-export.php" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="confirm_import" value="1">
                            <input type="hidden" name="import_payload" value="<?= htmlspecialchars(json_encode(array_filter($previewData['rows'], fn($x) => $x['is_valid']))) ?>">
                            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold">
                                <i class="bi bi-check-lg me-1"></i> Confirm & Import <?= $previewData['valid_count'] ?> Valid Students
                            </button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary rounded-pill px-4 disabled" disabled>No Valid Rows To Import</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Upload Zone & Export Section Grid -->
    <div class="row g-4 mb-4">
        <!-- Import Card -->
        <div class="col-lg-6">
            <div class="saas-card h-100 overflow-hidden">
                <div class="saas-card-header py-3 px-4">
                    <h5 class="fw-bold mb-0" style="color: var(--text-heading);">
                        <i class="bi bi-file-earmark-arrow-up text-primary me-2"></i>Bulk Import Students
                    </h5>
                    <a href="<?= BASE_URL ?>faculty/import-export.php?action=download_template" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="bi bi-download me-1"></i> Download CSV Template
                    </a>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <form action="<?= BASE_URL ?>faculty/import-export.php" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="border-2 border-dashed rounded-4 p-4 text-center mb-3 transition-all" style="border-color: #CBD5E1; background: var(--bg-muted);">
                            <div class="stat-icon-saas primary-gradient mx-auto mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                                <i class="bi bi-cloud-upload"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--text-heading);">Upload Student Roster CSV</h6>
                            <p class="text-muted small mb-3">Drag & drop your formatted CSV or Excel file here, or click Browse</p>
                            
                            <input type="file" name="csv_file" id="csvFileInput" class="d-none" accept=".csv,.txt" onchange="this.form.submit()">
                            <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm" onclick="document.getElementById('csvFileInput').click()">
                                <i class="bi bi-folder2-open me-1"></i> Browse File
                            </button>
                        </div>

                        <div class="p-3 bg-light rounded-3 small text-muted">
                            <strong class="d-block text-dark mb-1"><i class="bi bi-info-circle me-1"></i> Import Requirements:</strong>
                            <ul class="mb-0 ps-3">
                                <li>Supported Formats: <code>.csv</code> (UTF-8 Encoded)</li>
                                <li>Required Headers: <code>First Name, Email, Username, Department</code></li>
                                <li>Duplicate emails or usernames will be flagged and skipped automatically.</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Export Card -->
        <div class="col-lg-6">
            <div class="saas-card h-100 overflow-hidden">
                <div class="saas-card-header py-3 px-4">
                    <h5 class="fw-bold mb-0" style="color: var(--text-heading);">
                        <i class="bi bi-file-earmark-arrow-down text-success me-2"></i>Bulk Export Students
                    </h5>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <form action="<?= BASE_URL ?>faculty/import-export.php" method="GET">
                        <input type="hidden" name="action" value="export">

                        <p class="text-muted small mb-3">Filter and export student records directly into an Excel-compatible CSV file.</p>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Department Filter</label>
                            <select name="department" class="saas-form-select w-100">
                                <option value="all">All Departments</option>
                                <?php foreach ($departmentsList as $d): ?>
                                    <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-secondary">Search Keyword (Optional)</label>
                            <input type="text" name="search" class="saas-form-control w-100" placeholder="Filter by student name, email, or code...">
                        </div>

                        <button type="submit" class="btn btn-success rounded-pill px-4 w-100 fw-semibold shadow-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export Student Records to CSV
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
