<?php
/**
 * SkillBridge - Question Import Page
 * Context-specific CSV question importer.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validators.php';
require_once __DIR__ . '/../includes/import_export_helper.php';

require_role('faculty');
check_suspended_status();

$facultyId = $_SESSION['profile_id'];
$facultyUserId = $_SESSION['user_id'];
$db = Database::getInstance();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'download_template') {
    download_question_import_template();
    exit;
}

$qbId = (int)($_GET['qb_id'] ?? $_POST['qb_id'] ?? 0);
$qb = $db->fetch("SELECT * FROM question_banks WHERE id = ?", [$qbId]);

if (!$qb) {
    set_flash_message('danger', 'Question Bank not found.');
    redirect(BASE_URL . 'faculty/question-bank.php');
}

$error = '';
$success = '';
$previewData = null;

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
                $count = execute_qbank_question_import($importRows, $db, $qbId, $facultyUserId);
                set_flash_message('success', "Successfully imported {$count} questions into the {$qb['title']}!");
                redirect(BASE_URL . 'faculty/question-bank.php?qb_id=' . $qbId);
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
                    $previewData = validate_qbank_question_import_rows($parsed['rows'], $db, $qbId);
                }
            }
        }
    }
}

$pageTitle = "Import Questions - Faculty Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-file-earmark-excel text-success me-2"></i>Import Questions</h3>
        <p class="text-muted small mb-0">Import questions directly to <strong><?= htmlspecialchars($qb['title']) ?></strong> (<?= htmlspecialchars($qb['category']) ?> - <?= htmlspecialchars($qb['skill']) ?> - <?= ucfirst($qb['difficulty']) ?>)</p>
    </div>
    <a href="<?= BASE_URL ?>faculty/question-bank.php?qb_id=<?= $qbId ?>" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 small fw-semibold">
        <i class="bi bi-arrow-left me-1"></i> Back to Bank
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2.5 px-3 small border-0 rounded-3 mb-4"><i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- DRAG AND DROP CSV UPLOAD AND INSTRUCTIONS -->
<?php if (!$previewData): ?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="saas-card p-5 text-center border-dashed position-relative" id="dropZone" style="border: 2px dashed var(--success); background: rgba(25, 135, 84, 0.02); border-radius: 1rem;">
            <form action="<?= BASE_URL ?>faculty/question-import.php" method="POST" enctype="multipart/form-data" id="uploadForm" onsubmit="showUploadProgress()">
                <?= csrf_field() ?>
                <input type="hidden" name="qb_id" value="<?= $qbId ?>">
                <div class="mb-3 text-success" style="font-size: 3rem;"><i class="bi bi-file-earmark-arrow-up"></i></div>
                <h5 class="fw-bold text-dark mb-2">Drag and drop your Questions CSV here</h5>
                <p class="text-muted small mb-4">or click to browse your local directory (Max size: 10MB)</p>
                <input type="file" name="csv_file" id="fileInput" accept=".csv,.txt" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" style="z-index: 10;" onchange="this.form.submit()">
            </form>
            <div id="uploadProgressWrapper" class="mt-3" style="display: none;">
                <div class="progress rounded-pill mb-1" style="height: 8px;">
                    <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%;"></div>
                </div>
                <div class="small text-muted" id="uploadProgressText">Uploading and parsing...</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="saas-card p-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-success me-1"></i>Instructions</h5>
            <ol class="small text-secondary ps-3 mb-4">
                <li class="mb-2">Download the official question CSV template using the button below.</li>
                <li class="mb-2">Fill in your question records (Category, Skill, Difficulty Level, Question, Option A, B, C, D, Correct Answer, Explanation).</li>
                <li class="mb-2">Correct Answer must be A, B, C, or D. Category, Skill, and Difficulty must match this Question Bank.</li>
                <li>Upload the file to validate and preview questions before finalizing.</li>
            </ol>
            <a href="<?= BASE_URL ?>faculty/question-import.php?action=download_template" class="btn btn-outline-success w-100 rounded-pill fw-semibold py-2 small">
                <i class="bi bi-download me-1"></i> Download CSV Template
            </a>
        </div>
    </div>
</div>

<?php else: ?>
<!-- PREVIEW VALIDATION GRID -->
<div class="saas-card mb-4 p-4">
    <div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold text-dark mb-1">CSV Verification Summary</h5>
            <p class="text-muted small mb-0">Total rows parsed: <strong><?= $previewData['total_rows'] ?></strong></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>faculty/question-import.php?qb_id=<?= $qbId ?>" class="btn btn-light rounded-pill px-4 py-1.5 small fw-semibold">Cancel</a>
            <?php if ($previewData['valid_count'] > 0): ?>
                <form action="<?= BASE_URL ?>faculty/question-import.php" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="qb_id" value="<?= $qbId ?>">
                    <input type="hidden" name="confirm_import" value="1">
                    <input type="hidden" name="import_payload" value="<?= htmlspecialchars(json_encode($previewData['rows'])) ?>">
                    <button type="submit" class="btn btn-success rounded-pill px-4 py-1.5 small fw-semibold">
                        Confirm Import (<?= $previewData['valid_count'] ?> Questions)
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Counters -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="p-3 border rounded-3 bg-light text-center">
                <div class="text-secondary small font-bold uppercase mb-1">Total Found</div>
                <div class="fs-4 fw-bold text-dark"><?= $previewData['total_rows'] ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 border rounded-3 bg-success-subtle text-center">
                <div class="text-success small font-bold uppercase mb-1">Valid Records</div>
                <div class="fs-4 fw-bold text-success"><?= $previewData['valid_count'] ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 border rounded-3 bg-danger-subtle text-center">
                <div class="text-danger small font-bold uppercase mb-1">Failed Records</div>
                <div class="fs-4 fw-bold text-danger"><?= $previewData['invalid_count'] ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 border rounded-3 bg-warning-subtle text-center">
                <div class="text-warning small font-bold uppercase mb-1">Duplicates Skipped</div>
                <div class="fs-4 fw-bold text-warning"><?= $previewData['duplicate_count'] ?></div>
            </div>
        </div>
    </div>

    <!-- Rows Details -->
    <div class="table-responsive">
        <table class="saas-table border-top align-middle">
            <thead>
                <tr>
                    <th class="ps-3">Row #</th>
                    <th>Question Content</th>
                    <th>Correct Option</th>
                    <th>Status</th>
                    <th class="pe-3">Validation Errors</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($previewData['rows'] as $r): ?>
                    <tr>
                        <td class="ps-3 fw-bold small text-secondary">#<?= $r['row_num'] ?></td>
                        <td><?= htmlspecialchars(substr($r['question_text'] ?? '', 0, 80)) ?>...</td>
                        <td>Option <?= htmlspecialchars($r['correct_answer'] ?? '') ?></td>
                        <td>
                            <?php if ($r['is_valid']): ?>
                                <span class="badge rounded-pill bg-success-subtle text-success">Valid</span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-danger-subtle text-danger">Invalid</span>
                            <?php endif; ?>
                        </td>
                        <td class="pe-3 text-danger small font-medium"><?= htmlspecialchars($r['error_text'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<script>
// Simple drag and drop styling
const dropZone = document.getElementById('dropZone');
if (dropZone) {
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.background = "rgba(25, 135, 84, 0.08)";
        dropZone.style.borderColor = "var(--success)";
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.style.background = "rgba(25, 135, 84, 0.02)";
        dropZone.style.borderColor = "var(--success-light)";
    });
}

function showUploadProgress() {
    document.getElementById('uploadProgressWrapper').style.display = 'block';
    let progress = 0;
    const bar = document.getElementById('uploadProgressBar');
    const interval = setInterval(() => {
        progress += 10;
        bar.style.width = progress + '%';
        if (progress >= 90) clearInterval(interval);
    }, 150);
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
