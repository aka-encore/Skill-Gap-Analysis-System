<?php
/**
 * SkillBridge - Faculty Bulk Questions Import & Export Portal
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';
require_once __DIR__ . '/../includes/import_export_helper.php';

require_role('faculty');
check_suspended_status();

$db = Database::getInstance();
$facultyUserId = $_SESSION['user_id'];
$facultyProfileId = $_SESSION['profile_id'];

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// 1. Handle template download
if ($action === 'download_template') {
    download_question_import_template();
    exit;
}

// 2. Handle CSV error report download
if ($action === 'download_errors') {
    if (isset($_SESSION['question_import_errors'])) {
        download_question_error_report($_SESSION['question_import_errors']);
    } else {
        set_flash_message('danger', 'No error report available. Please upload a file first.');
        redirect(BASE_URL . 'faculty/questions-import-export.php');
    }
    exit;
}

// 3. Handle Bulk Export of Questions
if ($action === 'export') {
    $filters = [
        'assessment_id' => trim($_REQUEST['assessment_id'] ?? 'all'),
        'difficulty'    => trim($_REQUEST['difficulty'] ?? 'all'),
        'search'        => trim($_REQUEST['search'] ?? '')
    ];
    
    // Select specific IDs if checked
    $selectedIds = $_POST['selected_ids'] ?? [];
    if (!is_array($selectedIds)) $selectedIds = [];

    export_questions_to_csv($db, $filters, $selectedIds, $facultyUserId, $facultyProfileId);
    exit;
}

$error = '';
$success = '';
$previewData = null;

// 4. Handle Uploading & Parsing File
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Invalid security token.';
    } elseif (isset($_POST['confirm_import'])) {
        // Confirmation Stage
        $importPayloadJson = $_POST['import_payload'] ?? '';
        $importRows = json_decode($importPayloadJson, true);

        if (empty($importRows) || !is_array($importRows)) {
            $error = 'Import session expired or invalid payload.';
        } else {
            try {
                $count = execute_question_import($importRows, $db, $facultyUserId);
                set_flash_message('success', "Successfully imported {$count} questions into the assessment system!");
                unset($_SESSION['question_import_errors']);
                redirect(BASE_URL . 'faculty/question-bank.php');
            } catch (Throwable $e) {
                $error = 'Import database transaction error: ' . $e->getMessage();
            }
        }
    } elseif (isset($_FILES['question_file'])) {
        // Upload Stage
        $file = $_FILES['question_file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $error = 'File upload failed. Please try again.';
        } else {
            $fileName = $file['name'];
            $fileTmp  = $file['tmp_name'];
            $fileSize = $file['size'];
            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($ext, ['csv', 'txt', 'xlsx'])) {
                $error = 'Invalid file format. Only Excel (.xlsx) and CSV (.csv) files are supported.';
            } elseif ($fileSize > 15 * 1024 * 1024) {
                $error = 'File size exceeds maximum allowed limit (15MB).';
            } else {
                // Parse CSV or XLSX
                if ($ext === 'xlsx') {
                    $parsed = parse_xlsx_file($fileTmp);
                } else {
                    $parsed = parse_uploaded_csv($fileTmp);
                }

                if (!$parsed['success']) {
                    $error = $parsed['error'];
                } elseif (empty($parsed['rows'])) {
                    $error = 'Uploaded file contains no data rows.';
                } else {
                    // Validate Questions (pass facultyProfileId to enforce ownership check)
                    $previewData = validate_question_import_rows($parsed['rows'], $db, $facultyProfileId);
                    
                    // Save invalid rows to session for error report download
                    $invalidRows = array_filter($previewData['rows'], fn($x) => !$x['is_valid']);
                    $_SESSION['question_import_errors'] = $invalidRows;
                }
            }
        }
    }
}

// Fetch assessments created by this faculty to populate the export filters
$assessmentsList = $db->fetchAll(
    "SELECT id, title FROM assessments WHERE created_by_faculty_id = ? ORDER BY title ASC",
    [$facultyProfileId]
);

$pageTitle = "Bulk Question Import & Export – Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3); color: #818cf8; font-size: 1.5rem;">
            <i class="bi bi-cloud-arrow-up"></i>
        </div>
        <div>
            <h3 class="fw-bold mb-0" style="color: var(--text-heading);">Bulk Questions Import & Export</h3>
            <p class="text-muted small mb-0">Import large batches of questions or export authored questions to CSV templates</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>faculty/question-bank.php" class="btn btn-outline-secondary rounded-pill px-3 py-2 small fw-semibold">
        <i class="bi bi-question-circle me-1"></i> Question Bank Builder
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4 shadow-xs"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success py-2.5 px-3 small border-0 rounded-3 mb-4 shadow-xs"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<!-- Type Selection Tabs -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom-0 p-3">
        <ul class="nav nav-pills nav-fill gap-2" id="importExportTabs">
            <li class="nav-item">
                <a class="nav-link rounded-pill py-2.5 fw-semibold" href="<?= BASE_URL ?>faculty/import-export.php">
                    <i class="bi bi-mortarboard me-1.5"></i> Students Import & Export
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill py-2.5 fw-semibold active" href="<?= BASE_URL ?>faculty/questions-import-export.php">
                    <i class="bi bi-question-circle me-1.5"></i> Questions Import & Export
                </a>
            </li>
        </ul>
    </div>
</div>

<?php if ($previewData): ?>
    <!-- Validation Summary & Interactive Preview -->
    <div class="saas-card overflow-hidden mb-4 border-primary shadow-sm">
        <div class="saas-card-header bg-primary bg-opacity-10 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-primary">
                <i class="bi bi-file-earmark-check me-2"></i>Question Import Preview & Validation Report
            </h5>
            <span class="badge bg-primary rounded-pill px-3 py-1.5">Total Processed: <?= $previewData['total_rows'] ?></span>
        </div>
        
        <div class="card-body p-4">
            <!-- Metrics Summary Ratios -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 text-center h-100">
                        <span class="d-block text-muted small fw-semibold text-uppercase mb-1">Valid (Ready)</span>
                        <strong class="fs-2 text-success"><?= $previewData['valid_count'] ?></strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 text-center h-100">
                        <span class="d-block text-muted small fw-semibold text-uppercase mb-1">Errors/Violations</span>
                        <strong class="fs-2 text-danger"><?= $previewData['invalid_count'] ?></strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25 text-center h-100">
                        <span class="d-block text-muted small fw-semibold text-uppercase mb-1">Duplicates Found</span>
                        <strong class="fs-2 text-warning"><?= $previewData['duplicate_count'] ?></strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <?php if ($previewData['invalid_count'] > 0): ?>
                        <a href="?action=download_errors" class="btn btn-outline-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center border-dashed rounded-3 p-3 transition-all hover:bg-danger hover:bg-opacity-10">
                            <i class="bi bi-download fs-4 mb-1"></i>
                            <span class="small fw-bold">Download Error Report</span>
                        </a>
                    <?php else: ?>
                        <div class="p-3 rounded-3 bg-light border text-center h-100 d-flex flex-column justify-content-center">
                            <span class="d-block text-success small fw-semibold"><i class="bi bi-shield-check me-1"></i> File is Clean</span>
                            <span class="text-muted small">0 validation warnings</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Preview Data Table -->
            <h6 class="fw-bold mb-3 text-secondary"><i class="bi bi-table me-1"></i> Question Preview Grid</h6>
            <div class="table-responsive mb-4 border rounded-3" style="max-height: 400px;">
                <table class="saas-table align-middle mb-0" style="min-width: 1000px;">
                    <thead class="sticky-top bg-white border-bottom shadow-xs">
                        <tr>
                            <th class="ps-3" style="width: 70px;">ROW</th>
                            <th>ASSESSMENT TITLE</th>
                            <th>SKILL</th>
                            <th>DIFFICULTY</th>
                            <th style="width: 250px;">QUESTION TEXT</th>
                            <th>OPTIONS</th>
                            <th>CORRECT</th>
                            <th>MARKS</th>
                            <th class="pe-3 text-end" style="width: 180px;">VALIDATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($previewData['rows'] as $r): ?>
                            <tr class="<?= !$r['is_valid'] ? 'bg-danger bg-opacity-10' : '' ?>">
                                <td class="ps-3 fw-bold text-muted">#<?= $r['row_num'] ?></td>
                                <td><div class="text-dark fw-semibold text-truncate" style="max-width: 160px;" title="<?= htmlspecialchars($r['assessment_title']) ?>"><?= htmlspecialchars($r['assessment_title']) ?></div></td>
                                <td><span class="badge bg-light text-secondary border"><?= htmlspecialchars($r['skill']) ?></span></td>
                                <td><span class="badge bg-secondary-subtle text-secondary-emphasis text-uppercase"><?= htmlspecialchars($r['difficulty']) ?></span></td>
                                <td><div class="text-truncate text-secondary" style="max-width: 240px;" title="<?= htmlspecialchars($r['question_text']) ?>"><?= htmlspecialchars($r['question_text']) ?></div></td>
                                <td class="small text-muted" style="font-size: 11px;">
                                    A: <?= htmlspecialchars($r['option_a']) ?><br>
                                    B: <?= htmlspecialchars($r['option_b']) ?><br>
                                    C: <?= htmlspecialchars($r['option_c']) ?><br>
                                    D: <?= htmlspecialchars($r['option_d']) ?>
                                </td>
                                <td><span class="badge bg-success bg-opacity-75">Option <?= htmlspecialchars($r['correct_answer']) ?></span></td>
                                <td class="fw-semibold text-dark"><?= htmlspecialchars($r['marks']) ?></td>
                                <td class="pe-3 text-end">
                                    <?php if ($r['is_valid']): ?>
                                        <span class="saas-badge saas-badge-success py-1"><i class="bi bi-check-circle me-1"></i> Ready</span>
                                    <?php else: ?>
                                        <span class="saas-badge saas-badge-danger py-1" style="cursor: help;" data-bs-toggle="tooltip" data-bs-placement="left" title="<?= htmlspecialchars($r['error_text']) ?>">
                                            <i class="bi bi-x-circle me-1"></i> Invalid Row
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Action Controllers -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                <a href="<?= BASE_URL ?>faculty/questions-import-export.php" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i> Cancel & Re-upload
                </a>

                <div class="d-flex gap-2">
                    <?php if ($previewData['valid_count'] > 0): ?>
                        <form action="<?= BASE_URL ?>faculty/questions-import-export.php" method="POST" onsubmit="showProgressBar()">
                            <?= csrf_field() ?>
                            <input type="hidden" name="confirm_import" value="1">
                            <input type="hidden" name="import_payload" value="<?= htmlspecialchars(json_encode(array_filter($previewData['rows'], fn($x) => $x['is_valid']))) ?>">
                            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold">
                                <i class="bi bi-check-lg me-1"></i> Import <?= $previewData['valid_count'] ?> Valid Questions
                            </button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary rounded-pill px-4 disabled" disabled>No Valid Rows To Import</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Importing Progress Overlay -->
    <div id="progressOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark bg-opacity-75" style="z-index: 9999;">
        <div class="bg-white p-4 rounded-4 shadow-lg text-center" style="max-width: 400px; width: 90%;">
            <div class="spinner-border text-primary mb-3" style="width: 3.5rem; height: 3.5rem;" role="status"></div>
            <h5 class="fw-bold mb-1">Importing Questions...</h5>
            <p class="text-muted small mb-3">Please do not refresh or close the page while the transaction completes.</p>
            <div class="progress rounded-pill overflow-hidden" style="height: 10px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <script>
    function showProgressBar() {
        document.getElementById('progressOverlay').classList.remove('d-none');
    }
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    </script>

<?php else: ?>
    <!-- Upload Area & Bulk Export Grid -->
    <div class="row g-4 mb-4">
        <!-- Import Card -->
        <div class="col-lg-6">
            <div class="saas-card h-100 overflow-hidden">
                <div class="saas-card-header py-3 px-4">
                    <h5 class="fw-bold mb-0" style="color: var(--text-heading);">
                        <i class="bi bi-file-earmark-arrow-up text-primary me-2"></i>Bulk Import Questions
                    </h5>
                    <a href="?action=download_template" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="bi bi-download me-1"></i> Download Template
                    </a>
                </div>
                
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <form action="<?= BASE_URL ?>faculty/questions-import-export.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                        <?= csrf_field() ?>

                        <div class="border-2 border-dashed rounded-4 p-4 text-center mb-3 transition-all" 
                             id="dropZone" 
                             style="border-color: #CBD5E1; background: var(--bg-muted); cursor: pointer; transition: all 0.2s ease;">
                            
                            <div class="stat-icon-saas primary-gradient mx-auto mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                                <i class="bi bi-cloud-upload"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--text-heading);">Upload Questions File</h6>
                            <p class="text-muted small mb-3">Drag & drop your CSV or Excel (.xlsx) file here, or click to browse</p>
                            
                            <input type="file" name="question_file" id="fileInput" class="d-none" accept=".csv,.txt,.xlsx">
                            <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                                <i class="bi bi-folder2-open me-1"></i> Browse File
                            </button>
                        </div>

                        <div class="p-3 bg-light rounded-3 small text-muted">
                            <strong class="d-block text-dark mb-1"><i class="bi bi-info-circle me-1"></i> Format Checklist:</strong>
                            <ul class="mb-0 ps-3">
                                <li>Compatible Formats: <code>.csv</code> (UTF-8) or <code>.xlsx</code> (Excel)</li>
                                <li>Headers must match: <code>Assessment Title</code>, <code>Question Text</code>, <code>Option A-D</code>, etc.</li>
                                <li>Duplicate checking: checks both files and database for collisions.</li>
                                <li>Permissions: you can only import into assessments you created.</li>
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
                        <i class="bi bi-file-earmark-arrow-down text-success me-2"></i>Bulk Export Questions
                    </h5>
                </div>
                
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <form action="<?= BASE_URL ?>faculty/questions-import-export.php" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="export">

                        <p class="text-muted small mb-3">Filter and export your authored assessment questions to an Excel-compatible CSV file.</p>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Filter by Assessment Context</label>
                            <select name="assessment_id" class="saas-form-select w-100">
                                <option value="all">All Authored Assessments</option>
                                <?php foreach ($assessmentsList as $a): ?>
                                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Difficulty Level</label>
                            <select name="difficulty" class="saas-form-select w-100">
                                <option value="all">All Difficulties</option>
                                <option value="beginner">Beginner</option>
                                <option value="easy">Easy</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-secondary">Search Keyword</label>
                            <input type="text" name="search" class="saas-form-control w-100" placeholder="Search by question text or category...">
                        </div>

                        <button type="submit" class="btn btn-success rounded-pill px-4 w-100 fw-semibold shadow-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export Questions to CSV
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Drag and Drop JS Handler -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const uploadForm = document.getElementById('uploadForm');

        if (dropZone && fileInput) {
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.style.borderColor = 'var(--primary)';
                dropZone.style.background = 'rgba(99, 102, 241, 0.05)';
            });

            dropZone.addEventListener('dragleave', function() {
                dropZone.style.borderColor = '#CBD5E1';
                dropZone.style.background = 'var(--bg-muted)';
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.style.borderColor = '#CBD5E1';
                dropZone.style.background = 'var(--bg-muted)';

                if (e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    uploadForm.submit();
                }
            });

            dropZone.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    uploadForm.submit();
                }
            });
        }
    });
    </script>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
