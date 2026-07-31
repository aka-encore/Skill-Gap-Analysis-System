<?php
/**
 * SkillBridge - Bulk Import & Export Subsystem Helper
 * Centralized CSV/Excel import parser, validator, template generator, exporter, and audit logger.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

/**
 * Remove UTF-8 BOM header from string if present
 */
function remove_utf8_bom(string $text): string {
    $bom = pack('H*', 'EFBBBF');
    return preg_replace("/^$bom/", '', $text);
}

/**
 * Parse uploaded CSV or TXT file into headers and data array
 */
function parse_uploaded_csv(string $filepath): array {
    if (!file_exists($filepath) || !is_readable($filepath)) {
        return ['success' => false, 'error' => 'File could not be read or does not exist.'];
    }

    $content = file_get_contents($filepath);
    $content = remove_utf8_bom($content);

    // Temp file for fgetcsv stream reading
    $tmpHandle = fopen('php://temp', 'r+');
    fwrite($tmpHandle, $content);
    rewind($tmpHandle);

    $headers = [];
    $rows = [];
    $rowIndex = 0;

    while (($data = fgetcsv($tmpHandle, 2048, ",")) !== false) {
        $rowIndex++;
        // Skip empty rows
        if (empty(array_filter($data, fn($v) => trim((string)$v) !== ''))) {
            continue;
        }

        if (empty($headers)) {
            // Normalize header names
            $headers = array_map(function($h) {
                return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', trim((string)$h))));
            }, $data);
            continue;
        }

        $rowMap = [];
        foreach ($headers as $i => $colName) {
            $rowMap[$colName] = trim((string)($data[$i] ?? ''));
        }
        $rowMap['_row_num'] = $rowIndex;
        $rows[] = $rowMap;
    }

    fclose($tmpHandle);

    return [
        'success' => true,
        'headers' => $headers,
        'rows'    => $rows
    ];
}

/**
 * Download CSV Template for Student Import
 */
function download_student_import_template(): void {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="SkillBridge_Student_Import_Template.csv"');
    
    $output = fopen('php://output', 'w');
    // Write UTF-8 BOM for Excel compatibility
    fputs($output, "\xEF\xBB\xBF");
    
    fputcsv($output, [
        'First Name',
        'Last Name',
        'Email',
        'Phone',
        'Username',
        'Department',
        'Current Semester',
        'College Name'
    ]);

    // Sample Rows
    fputcsv($output, ['Alex', 'Morgan', 'alex.morgan@student.skillbridge.edu', '9876543210', 'alex_morgan', 'Computer Science', '3', 'SkillBridge University']);
    fputcsv($output, ['Sophia', 'Chen', 'sophia.chen@student.skillbridge.edu', '9876543211', 'sophia_chen', 'Information Technology', '5', 'SkillBridge University']);

    fclose($output);
    exit;
}

/**
 * Download CSV Template for Faculty Import
 */
function download_faculty_import_template(): void {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="SkillBridge_Faculty_Import_Template.csv"');

    $output = fopen('php://output', 'w');
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, [
        'First Name',
        'Last Name',
        'Email',
        'Mobile Number',
        'Username',
        'Department',
        'Designation',
        'Experience Years',
        'College Name'
    ]);

    // Sample Rows
    fputcsv($output, ['Alan', 'Turing', 'alan.turing@skillbridge.edu', '9123456780', 'prof_turing', 'Computer Science', 'Senior Professor', '12', 'SkillBridge University']);
    fputcsv($output, ['Grace', 'Hopper', 'grace.hopper@skillbridge.edu', '9123456781', 'prof_hopper', 'Information Technology', 'Associate Professor', '10', 'SkillBridge University']);

    fclose($output);
    exit;
}

/**
 * Validate Student Import Rows
 */
function validate_student_import_rows(array $rows, Database $db): array {
    $existingUsers = $db->fetchAll("SELECT username, email FROM users");
    $dbUsernames = array_column($existingUsers, 'username');
    $dbEmails = array_column($existingUsers, 'email');

    $seenUsernamesInFile = [];
    $seenEmailsInFile = [];

    $validatedRows = [];
    $validCount = 0;
    $invalidCount = 0;
    $duplicateCount = 0;

    foreach ($rows as $row) {
        $rowNum = $row['_row_num'] ?? 0;
        $firstName = trim($row['first_name'] ?? $row['firstname'] ?? '');
        $lastName = trim($row['last_name'] ?? $row['lastname'] ?? '');
        $email = strtolower(trim($row['email'] ?? ''));
        $username = strtolower(trim($row['username'] ?? ''));
        $phone = trim($row['phone'] ?? $row['mobile'] ?? '');
        $dept = trim($row['department'] ?? 'Computer Science');
        $sem = (int)($row['current_semester'] ?? $row['semester'] ?? 1);
        $college = trim($row['college_name'] ?? $row['college'] ?? 'SkillBridge University');

        $errors = [];
        $isDuplicate = false;

        // Validation Checks
        if (empty($firstName)) {
            $errors[] = 'First Name is required.';
        }
        if (empty($username)) {
            $errors[] = 'Username is required.';
        } elseif (in_array($username, $dbUsernames)) {
            $errors[] = 'Username is already registered in the system.';
            $isDuplicate = true;
        } elseif (in_array($username, $seenUsernamesInFile)) {
            $errors[] = 'Duplicate username in this import file.';
            $isDuplicate = true;
        }

        if (empty($email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address format.';
        } elseif (in_array($email, $dbEmails)) {
            $errors[] = 'Email is already registered in the system.';
            $isDuplicate = true;
        } elseif (in_array($email, $seenEmailsInFile)) {
            $errors[] = 'Duplicate email in this import file.';
            $isDuplicate = true;
        }

        if ($sem < 1 || $sem > 12) {
            $sem = 1;
        }

        if (!empty($username)) $seenUsernamesInFile[] = $username;
        if (!empty($email)) $seenEmailsInFile[] = $email;

        $isValid = empty($errors);
        if ($isValid) {
            $validCount++;
        } else {
            $invalidCount++;
            if ($isDuplicate) $duplicateCount++;
        }

        $validatedRows[] = [
            'row_num'    => $rowNum,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'username'   => $username,
            'phone'      => $phone,
            'department' => $dept,
            'semester'   => $sem,
            'college'    => $college,
            'is_valid'   => $isValid,
            'errors'     => $errors,
            'error_text' => implode(' | ', $errors)
        ];
    }

    return [
        'total_rows'      => count($rows),
        'valid_count'     => $validCount,
        'invalid_count'   => $invalidCount,
        'duplicate_count' => $duplicateCount,
        'rows'            => $validatedRows
    ];
}

/**
 * Validate Faculty Import Rows
 */
function validate_faculty_import_rows(array $rows, Database $db): array {
    $existingUsers = $db->fetchAll("SELECT username, email FROM users");
    $dbUsernames = array_column($existingUsers, 'username');
    $dbEmails = array_column($existingUsers, 'email');

    $seenUsernamesInFile = [];
    $seenEmailsInFile = [];

    $validatedRows = [];
    $validCount = 0;
    $invalidCount = 0;
    $duplicateCount = 0;

    foreach ($rows as $row) {
        $rowNum = $row['_row_num'] ?? 0;
        $firstName = trim($row['first_name'] ?? $row['firstname'] ?? '');
        $lastName = trim($row['last_name'] ?? $row['lastname'] ?? '');
        $email = strtolower(trim($row['email'] ?? ''));
        $username = strtolower(trim($row['username'] ?? ''));
        $mobile = trim($row['mobile_number'] ?? $row['mobile'] ?? $row['phone'] ?? '');
        $dept = trim($row['department'] ?? 'Computer Science');
        $designation = trim($row['designation'] ?? 'Assistant Professor');
        $expYears = (int)($row['experience_years'] ?? $row['experience'] ?? 0);
        $college = trim($row['college_name'] ?? $row['college'] ?? 'SkillBridge University');

        $errors = [];
        $isDuplicate = false;

        if (empty($firstName)) {
            $errors[] = 'First Name is required.';
        }
        if (empty($username)) {
            $errors[] = 'Username is required.';
        } elseif (in_array($username, $dbUsernames)) {
            $errors[] = 'Username is already registered in system.';
            $isDuplicate = true;
        } elseif (in_array($username, $seenUsernamesInFile)) {
            $errors[] = 'Duplicate username in file.';
            $isDuplicate = true;
        }

        if (empty($email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address format.';
        } elseif (in_array($email, $dbEmails)) {
            $errors[] = 'Email is already registered in system.';
            $isDuplicate = true;
        } elseif (in_array($email, $seenEmailsInFile)) {
            $errors[] = 'Duplicate email in file.';
            $isDuplicate = true;
        }

        if (!empty($username)) $seenUsernamesInFile[] = $username;
        if (!empty($email)) $seenEmailsInFile[] = $email;

        $isValid = empty($errors);
        if ($isValid) {
            $validCount++;
        } else {
            $invalidCount++;
            if ($isDuplicate) $duplicateCount++;
        }

        $validatedRows[] = [
            'row_num'          => $rowNum,
            'first_name'       => $firstName,
            'last_name'        => $lastName,
            'email'            => $email,
            'username'         => $username,
            'mobile'           => $mobile,
            'department'       => $dept,
            'designation'      => $designation,
            'experience_years' => $expYears,
            'college'          => $college,
            'is_valid'         => $isValid,
            'errors'           => $errors,
            'error_text'       => implode(' | ', $errors)
        ];
    }

    return [
        'total_rows'      => count($rows),
        'valid_count'     => $validCount,
        'invalid_count'   => $invalidCount,
        'duplicate_count' => $duplicateCount,
        'rows'            => $validatedRows
    ];
}

/**
 * Execute Student Bulk Import Insert inside Database Transaction
 */
function execute_student_import(array $validRows, Database $db, int $actorUserId): int {
    if (empty($validRows)) return 0;

    $importedCount = 0;
    $db->beginTransaction();

    try {
        foreach ($validRows as $r) {
            if (!$r['is_valid']) continue;

            $defaultPassword = password_hash('Password123!', PASSWORD_BCRYPT);
            $uId = $db->insert('users', [
                'username'   => $r['username'],
                'email'      => $r['email'],
                'password'   => $defaultPassword,
                'role'       => 'student',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $code = 'STU-' . (1000 + $uId);
            $db->insert('students', [
                'user_id'          => $uId,
                'student_code'     => $code,
                'first_name'       => $r['first_name'],
                'last_name'        => $r['last_name'],
                'phone'            => $r['phone'],
                'department'       => $r['department'],
                'current_semester' => $r['semester'],
                'college_name'     => $r['college'],
                'created_at'       => date('Y-m-d H:i:s')
            ]);

            $importedCount++;
        }

        $db->commit();

        log_activity($actorUserId, 'BULK_IMPORT_STUDENTS', "Bulk imported {$importedCount} student accounts");
        return $importedCount;
    } catch (Throwable $e) {
        $db->rollBack();
        throw $e;
    }
}

/**
 * Execute Faculty Bulk Import Insert inside Database Transaction
 */
function execute_faculty_import(array $validRows, Database $db, int $actorUserId): int {
    if (empty($validRows)) return 0;

    $importedCount = 0;
    $db->beginTransaction();

    try {
        foreach ($validRows as $r) {
            if (!$r['is_valid']) continue;

            $defaultPassword = password_hash('Password123!', PASSWORD_BCRYPT);
            $uId = $db->insert('users', [
                'username'   => $r['username'],
                'email'      => $r['email'],
                'password'   => $defaultPassword,
                'role'       => 'faculty',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $code = 'FAC-' . (100 + $uId);
            $db->insert('faculty', [
                'user_id'          => $uId,
                'employee_code'    => $code,
                'first_name'       => $r['first_name'],
                'last_name'        => $r['last_name'],
                'mobile_number'    => $r['mobile'],
                'department'       => $r['department'],
                'designation'      => $r['designation'],
                'experience_years' => $r['experience_years'],
                'college_name'     => $r['college'],
                'approval_status'  => 'approved',
                'created_at'       => date('Y-m-d H:i:s')
            ]);

            $importedCount++;
        }

        $db->commit();

        log_activity($actorUserId, 'BULK_IMPORT_FACULTY', "Bulk imported {$importedCount} faculty accounts");
        return $importedCount;
    } catch (Throwable $e) {
        $db->rollBack();
        throw $e;
    }
}

/**
 * Export Students to CSV
 */
function export_students_to_csv(Database $db, array $filters = [], array $selectedIds = [], int $actorUserId = 0): void {
    $where = [];
    $params = [];

    if (!empty($selectedIds)) {
        $inClause = implode(',', array_fill(0, count($selectedIds), '?'));
        $where[] = "s.id IN ($inClause)";
        $params = array_merge($params, $selectedIds);
    } else {
        if (!empty($filters['department']) && $filters['department'] !== 'all') {
            $where[] = "s.department = ?";
            $params[] = $filters['department'];
        }
        if (!empty($filters['search'])) {
            $where[] = "(s.first_name LIKE ? OR s.last_name LIKE ? OR u.email LIKE ? OR s.student_code LIKE ?)";
            $term = '%' . $filters['search'] . '%';
            $params[] = $term; $params[] = $term; $params[] = $term; $params[] = $term;
        }
    }

    $whereSql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    $students = $db->fetchAll(
        "SELECT s.*, u.username, u.email, u.status as user_status 
         FROM students s 
         JOIN users u ON s.user_id = u.id 
         {$whereSql} 
         ORDER BY s.student_code ASC",
        $params
    );

    log_activity($actorUserId, 'BULK_EXPORT_STUDENTS', "Exported " . count($students) . " student records");

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="SkillBridge_Students_Export_' . date('Y-m-d_His') . '.csv"');

    $output = fopen('php://output', 'w');
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, [
        'Student Code',
        'First Name',
        'Last Name',
        'Username',
        'Email',
        'Phone',
        'Department',
        'Current Semester',
        'College Name',
        'Status',
        'Registered Date'
    ]);

    foreach ($students as $s) {
        fputcsv($output, [
            $s['student_code'],
            $s['first_name'],
            $s['last_name'],
            $s['username'],
            $s['email'],
            $s['phone'] ?? '',
            $s['department'],
            $s['current_semester'],
            $s['college_name'] ?? 'SkillBridge University',
            strtoupper($s['user_status'] ?? 'ACTIVE'),
            date('Y-m-d H:i:s', strtotime($s['created_at']))
        ]);
    }

    fclose($output);
    exit;
}

/**
 * Export Faculty to CSV
 */
function export_faculty_to_csv(Database $db, array $filters = [], array $selectedIds = [], int $actorUserId = 0): void {
    $where = [];
    $params = [];

    if (!empty($selectedIds)) {
        $inClause = implode(',', array_fill(0, count($selectedIds), '?'));
        $where[] = "f.id IN ($inClause)";
        $params = array_merge($params, $selectedIds);
    } else {
        if (!empty($filters['department']) && $filters['department'] !== 'all') {
            $where[] = "f.department = ?";
            $params[] = $filters['department'];
        }
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $where[] = "f.approval_status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['search'])) {
            $where[] = "(f.first_name LIKE ? OR f.last_name LIKE ? OR u.email LIKE ? OR f.employee_code LIKE ?)";
            $term = '%' . $filters['search'] . '%';
            $params[] = $term; $params[] = $term; $params[] = $term; $params[] = $term;
        }
    }

    $whereSql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    $facultyList = $db->fetchAll(
        "SELECT f.*, u.username, u.email, u.status as user_status 
         FROM faculty f 
         JOIN users u ON f.user_id = u.id 
         {$whereSql} 
         ORDER BY f.employee_code ASC",
        $params
    );

    log_activity($actorUserId, 'BULK_EXPORT_FACULTY', "Exported " . count($facultyList) . " faculty records");

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="SkillBridge_Faculty_Export_' . date('Y-m-d_His') . '.csv"');

    $output = fopen('php://output', 'w');
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, [
        'Employee Code',
        'First Name',
        'Last Name',
        'Username',
        'Email',
        'Mobile Number',
        'Department',
        'Designation',
        'Experience Years',
        'College Name',
        'Approval Status',
        'Registered Date'
    ]);

    foreach ($facultyList as $f) {
        fputcsv($output, [
            $f['employee_code'],
            $f['first_name'],
            $f['last_name'],
            $f['username'],
            $f['email'],
            $f['mobile_number'] ?? '',
            $f['department'],
            $f['designation'],
            $f['experience_years'] ?? 0,
            $f['college_name'] ?? 'SkillBridge University',
            strtoupper($f['approval_status'] ?? 'APPROVED'),
            date('Y-m-d H:i:s', strtotime($f['created_at']))
        ]);
    }

    fclose($output);
    exit;
}

/**
 * Export Downloadable CSV Error Report for Invalid Import Rows
 */
function download_import_error_report(array $validatedRows, string $entityType = 'Students'): void {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="SkillBridge_' . $entityType . '_Import_Error_Report.csv"');

    $output = fopen('php://output', 'w');
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, ['Row #', 'Name', 'Email', 'Username', 'Department', 'Validation Errors']);

    foreach ($validatedRows as $r) {
        if ($r['is_valid']) continue;
        fputcsv($output, [
            $r['row_num'],
            trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? '')),
            $r['email'] ?? '',
            $r['username'] ?? '',
            $r['department'] ?? '',
            $r['error_text'] ?? 'Invalid Record'
        ]);
    }

    fclose($output);
    exit;
}

/**
 * Parse uploaded Excel (.xlsx) file using ZipArchive and SimpleXML
 */
function parse_xlsx_file(string $filepath): array {
    if (!class_exists('ZipArchive')) {
        return ['success' => false, 'error' => 'ZipArchive PHP extension is not enabled.'];
    }

    $zip = new ZipArchive();
    if ($zip->open($filepath) !== true) {
        return ['success' => false, 'error' => 'Unable to open Excel (.xlsx) file.'];
    }

    // 1. Read shared strings
    $sharedStrings = [];
    $sstEntry = $zip->getFromName('xl/sharedStrings.xml');
    if ($sstEntry !== false) {
        $xml = simplexml_load_string($sstEntry);
        if ($xml) {
            foreach ($xml->si as $si) {
                if (isset($si->t)) {
                    $sharedStrings[] = (string)$si->t;
                } elseif (isset($si->r)) {
                    $text = '';
                    foreach ($si->r as $r) {
                        $text .= (string)$r->t;
                    }
                    $sharedStrings[] = $text;
                } else {
                    $sharedStrings[] = '';
                }
            }
        }
    }

    // 2. Read Sheet1
    $sheetEntry = $zip->getFromName('xl/worksheets/sheet1.xml');
    if ($sheetEntry === false) {
        $sheetEntry = $zip->getFromName('xl/worksheets/Sheet1.xml');
    }
    if ($sheetEntry === false) {
        $zip->close();
        return ['success' => false, 'error' => 'Sheet1 not found in Excel file.'];
    }

    $xml = simplexml_load_string($sheetEntry);
    $zip->close();
    if (!$xml) {
        return ['success' => false, 'error' => 'Failed to parse sheet data.'];
    }

    $rows = [];
    
    // Column letter to index helper (e.g. A -> 0, B -> 1, Z -> 25, AA -> 26)
    $colLetterToIndex = function(string $col): int {
        $len = strlen($col);
        $idx = 0;
        for ($i = 0; $i < $len; $i++) {
            $idx = $idx * 26 + (ord($col[$i]) - 64);
        }
        return $idx - 1;
    };

    foreach ($xml->sheetData->row as $row) {
        $rowNum = (int)$row['r'];
        $rowData = [];

        foreach ($row->c as $cell) {
            $rRef = (string)$cell['r'];
            preg_match('/^[A-Z]+/', $rRef, $matches);
            $colLetter = $matches[0] ?? '';
            $colIdx = $colLetter ? $colLetterToIndex($colLetter) : count($rowData);

            $val = '';
            if (isset($cell->v)) {
                $val = (string)$cell->v;
                $type = (string)$cell['t'];
                if ($type === 's') {
                    $val = $sharedStrings[(int)$val] ?? '';
                }
            } elseif (isset($cell->is->t)) {
                $val = (string)$cell->is->t;
            }
            $rowData[$colIdx] = $val;
        }

        $maxIdx = !empty($rowData) ? max(array_keys($rowData)) : -1;
        $rowCells = [];
        for ($i = 0; $i <= $maxIdx; $i++) {
            $rowCells[] = trim($rowData[$i] ?? '');
        }

        if (!empty(array_filter($rowCells, fn($v) => trim((string)$v) !== ''))) {
            $rows[$rowNum] = $rowCells;
        }
    }

    if (empty($rows)) {
        return ['success' => false, 'error' => 'No rows found in Excel sheet.'];
    }

    ksort($rows);

    $headers = [];
    $dataRows = [];
    
    $firstRowKey = array_key_first($rows);
    $rawHeaders = $rows[$firstRowKey];
    
    $headers = array_map(function($h) {
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', trim((string)$h))));
    }, $rawHeaders);

    unset($rows[$firstRowKey]);

    foreach ($rows as $rowNum => $cells) {
        $rowMap = [];
        foreach ($headers as $i => $colName) {
            $rowMap[$colName] = trim((string)($cells[$i] ?? ''));
        }
        $rowMap['_row_num'] = $rowNum;
        $dataRows[] = $rowMap;
    }

    return [
        'success' => true,
        'headers' => $headers,
        'rows'    => $dataRows
    ];
}

/**
 * Download CSV Template for Question Bank Import (Faculty & Admin)
 */
function download_question_import_template(): void {
    try {
        $filename = 'Question_Bank_Template.csv';
        $filePath = __DIR__ . '/../assets/templates/' . $filename;

        if (file_exists($filePath) && is_readable($filePath)) {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');

            $content = file_get_contents($filePath);
            if (substr($content, 0, 3) !== "\xEF\xBB\xBF") {
                echo "\xEF\xBB\xBF";
            }
            echo $content;
            exit;
        }

        // Dynamic fallback generation if physical file is unavailable
        $output = fopen('php://output', 'w');
        if ($output === false) {
            throw new Exception("Unable to open output stream.");
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, [
            'Category',
            'Skill',
            'Difficulty Level',
            'Question',
            'Option A',
            'Option B',
            'Option C',
            'Option D',
            'Correct Answer',
            'Explanation'
        ]);

        fputcsv($output, [
            'Frontend Development',
            'HTML',
            'Beginner',
            'Which HTML tag is used to create a hyperlink?',
            '<a>',
            '<link>',
            '<href>',
            '<url>',
            'A',
            'The <a> tag defines a hyperlink in HTML.'
        ]);

        fclose($output);
        exit;
    } catch (Throwable $e) {
        if (!headers_sent()) {
            header_remove('Content-Type');
            header_remove('Content-Disposition');
        }
        set_flash_message('danger', 'Unable to generate the CSV template. Please try again.');
        redirect($_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'faculty/question-bank.php'));
    }
}

/**
 * Validate Question Import Rows
 */
function validate_question_import_rows(array $rows, Database $db, ?int $facultyProfileId = null): array {
    // Pre-cache assessments
    $assessmentsList = $db->fetchAll("SELECT id, title, skill_id, created_by_faculty_id, difficulty_level, status FROM assessments");
    $dbAssessments = [];
    foreach ($assessmentsList as $a) {
        $dbAssessments[strtolower(trim($a['title']))] = $a;
    }

    // Pre-cache skills
    $skillsList = $db->fetchAll("SELECT id, name, category FROM skills");
    $dbSkills = [];
    $skillCategories = [];
    foreach ($skillsList as $s) {
        $dbSkills[strtolower(trim($s['name']))] = $s;
        $skillCategories[strtolower(trim($s['category']))] = true;
    }

    // Cache existing questions to detect database duplicates
    $existingQList = $db->fetchAll("SELECT assessment_id, LOWER(TRIM(question_text)) as q_text FROM assessment_questions");
    $dbQuestions = [];
    foreach ($existingQList as $eq) {
        $dbQuestions[$eq['assessment_id']][] = $eq['q_text'];
    }

    $seenInFile = [];
    $validatedRows = [];
    $validCount = 0;
    $invalidCount = 0;
    $duplicateCount = 0;

    foreach ($rows as $row) {
        $rowNum = $row['_row_num'] ?? 0;
        
        // Clean values
        $assessTitle = trim($row['assessment_title'] ?? $row['assessment'] ?? '');
        $category = trim($row['category'] ?? '');
        $skill = trim($row['skill'] ?? '');
        $diffLevel = strtolower(trim($row['difficulty_level'] ?? $row['difficulty'] ?? ''));
        $qType = trim($row['question_type'] ?? 'MCQ');
        $qText = trim($row['question_text'] ?? $row['question'] ?? '');
        $optA = trim($row['option_a'] ?? '');
        $optB = trim($row['option_b'] ?? '');
        $optC = trim($row['option_c'] ?? '');
        $optD = trim($row['option_d'] ?? '');
        $correctAns = strtoupper(trim($row['correct_answer'] ?? $row['correct_option'] ?? ''));
        $explanation = trim($row['explanation'] ?? '');
        $marks = trim($row['marks'] ?? '1');
        $status = strtolower(trim($row['status'] ?? 'active'));

        $errors = [];
        $isDuplicate = false;

        // 1. Validate Assessment Title
        $assessmentId = null;
        if (empty($assessTitle)) {
            $errors[] = 'Assessment Title is empty.';
        } else {
            $lowerTitle = strtolower($assessTitle);
            if (!isset($dbAssessments[$lowerTitle])) {
                $errors[] = "Assessment '{$assessTitle}' does not exist in the system.";
            } else {
                $assessRec = $dbAssessments[$lowerTitle];
                $assessmentId = (int)$assessRec['id'];
                
                // Faculty permissions enforcement
                if ($facultyProfileId !== null && (int)$assessRec['created_by_faculty_id'] !== $facultyProfileId) {
                    $errors[] = "Unauthorized: You did not create assessment '{$assessTitle}'.";
                }
            }
        }

        // 2. Validate Category
        if (empty($category)) {
            $errors[] = 'Category is empty.';
        } else {
            $lowerCat = strtolower($category);
            // Allow common variations like "Backend Development" -> "Backend"
            $matchedCategory = false;
            foreach (array_keys($skillCategories) as $dbCat) {
                if ($dbCat === $lowerCat || strpos($lowerCat, $dbCat) !== false || strpos($dbCat, $lowerCat) !== false) {
                    $matchedCategory = true;
                    break;
                }
            }
            if (!$matchedCategory) {
                $errors[] = "Category '{$category}' is not a valid skill category.";
            }
        }

        // 3. Validate Skill
        if (empty($skill)) {
            $errors[] = 'Skill is empty.';
        } else {
            $lowerSkill = strtolower($skill);
            $matchedSkill = false;
            foreach ($dbSkills as $dbName => $sRec) {
                if ($dbName === $lowerSkill || strpos($dbName, $lowerSkill) !== false || strpos($lowerSkill, $dbName) !== false) {
                    $matchedSkill = true;
                    break;
                }
            }
            if (!$matchedSkill) {
                $errors[] = "Skill '{$skill}' is not registered in the system.";
            }
        }

        // 4. Validate Difficulty Level
        $validDiffs = ['beginner', 'easy', 'intermediate', 'advanced', 'expert'];
        if (empty($diffLevel)) {
            $errors[] = 'Difficulty Level is empty.';
        } elseif (!in_array($diffLevel, $validDiffs)) {
            $errors[] = "Invalid Difficulty Level '{$diffLevel}'. Must be one of: " . implode(', ', $validDiffs);
        }

        // 5. Validate Question Text
        if (empty($qText)) {
            $errors[] = 'Question Text is required.';
        }

        // 6. Validate Options
        if (empty($optA) || empty($optB) || empty($optC) || empty($optD)) {
            $errors[] = 'All four options (Option A, B, C, D) must be provided.';
        }

        // 7. Validate Correct Answer
        if (empty($correctAns)) {
            $errors[] = 'Correct Answer is required.';
        } elseif (!in_array($correctAns, ['A', 'B', 'C', 'D'])) {
            $errors[] = "Invalid Correct Answer '{$correctAns}'. Must be A, B, C, or D.";
        }

        // 8. Validate Marks
        if (!is_numeric($marks) || (float)$marks <= 0) {
            $errors[] = 'Marks must be a positive number.';
        }

        // 9. Check Duplicates inside File
        if (!empty($qText) && $assessmentId !== null) {
            $fileKey = $assessmentId . '||' . strtolower($qText);
            if (isset($seenInFile[$fileKey])) {
                $errors[] = 'Duplicate question text within this import file.';
                $isDuplicate = true;
            } else {
                $seenInFile[$fileKey] = true;
            }
        }

        // 10. Check Duplicates already in Database
        if (!empty($qText) && $assessmentId !== null && !$isDuplicate) {
            $normText = strtolower($qText);
            if (isset($dbQuestions[$assessmentId]) && in_array($normText, $dbQuestions[$assessmentId])) {
                $errors[] = 'Duplicate question: already exists in database for this assessment.';
                $isDuplicate = true;
            }
        }

        $isValid = empty($errors);
        if ($isValid) {
            $validCount++;
        } else {
            $invalidCount++;
            if ($isDuplicate) $duplicateCount++;
        }

        $validatedRows[] = [
            'row_num'          => $rowNum,
            'assessment_title' => $assessTitle,
            'assessment_id'    => $assessmentId,
            'category'         => $category,
            'skill'            => $skill,
            'difficulty'       => $diffLevel,
            'question_type'    => $qType,
            'question_text'    => $qText,
            'option_a'         => $optA,
            'option_b'         => $optB,
            'option_c'         => $optC,
            'option_d'         => $optD,
            'correct_answer'   => $correctAns,
            'explanation'      => $explanation,
            'marks'            => (int)$marks,
            'status'           => $status,
            'is_valid'         => $isValid,
            'errors'           => $errors,
            'error_text'       => implode(' | ', $errors)
        ];
    }

    return [
        'total_rows'      => count($rows),
        'valid_count'     => $validCount,
        'invalid_count'   => $invalidCount,
        'duplicate_count' => $duplicateCount,
        'rows'            => $validatedRows
    ];
}

/**
 * Execute Question Bulk Import inside Database Transaction
 */
function execute_question_import(array $validRows, Database $db, int $actorUserId): int {
    if (empty($validRows)) return 0;

    $importedCount = 0;
    $db->beginTransaction();

    try {
        foreach ($validRows as $r) {
            if (!$r['is_valid']) continue;

            $db->insert('assessment_questions', [
                'assessment_id'   => $r['assessment_id'],
                'question_text'   => $r['question_text'],
                'option_a'        => $r['option_a'],
                'option_b'        => $r['option_b'],
                'option_c'        => $r['option_c'],
                'option_d'        => $r['option_d'],
                'correct_option'  => $r['correct_answer'],
                'marks'           => $r['marks'],
                'category'        => !empty($r['category']) ? $r['category'] : 'Core Concepts'
            ]);

            $importedCount++;
        }

        $db->commit();

        log_activity($actorUserId, 'BULK_IMPORT_QUESTIONS', "Bulk imported {$importedCount} questions successfully.");
        return $importedCount;
    } catch (Throwable $e) {
        $db->rollBack();
        throw $e;
    }
}

/**
 * Export Questions to CSV
 */
function export_questions_to_csv(Database $db, array $filters = [], array $selectedIds = [], int $actorUserId = 0, ?int $facultyProfileId = null): void {
    $where = [];
    $params = [];

    if (!empty($selectedIds)) {
        $inClause = implode(',', array_fill(0, count($selectedIds), '?'));
        $where[] = "q.id IN ($inClause)";
        $params = array_merge($params, $selectedIds);
    } else {
        if (!empty($filters['assessment_id']) && $filters['assessment_id'] !== 'all') {
            $where[] = "q.assessment_id = ?";
            $params[] = (int)$filters['assessment_id'];
        }
        if (!empty($filters['difficulty']) && $filters['difficulty'] !== 'all') {
            $where[] = "a.difficulty_level = ?";
            $params[] = $filters['difficulty'];
        }
        if (!empty($filters['search'])) {
            $where[] = "(q.question_text LIKE ? OR q.category LIKE ? OR a.title LIKE ?)";
            $term = '%' . $filters['search'] . '%';
            $params[] = $term; $params[] = $term; $params[] = $term;
        }
    }

    // Role restrictions for Faculty: can only export questions they authored
    if ($facultyProfileId !== null) {
        $where[] = "a.created_by_faculty_id = ?";
        $params[] = $facultyProfileId;
    }

    $whereSql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    $questions = $db->fetchAll(
        "SELECT q.*, a.title as assessment_title, a.difficulty_level as difficulty, s.name as skill_name, s.category as skill_cat 
         FROM assessment_questions q
         JOIN assessments a ON q.assessment_id = a.id
         JOIN skills s ON a.skill_id = s.id
         {$whereSql} 
         ORDER BY a.title ASC, q.id ASC",
        $params
    );

    log_activity($actorUserId, 'BULK_EXPORT_QUESTIONS', "Exported " . count($questions) . " question records.");

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="SkillBridge_Questions_Export_' . date('Y-m-d_His') . '.csv"');

    $output = fopen('php://output', 'w');
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, [
        'Assessment Title',
        'Category',
        'Skill',
        'Difficulty Level',
        'Question Type',
        'Question Text',
        'Option A',
        'Option B',
        'Option C',
        'Option D',
        'Correct Answer',
        'Explanation',
        'Marks',
        'Status'
    ]);

    foreach ($questions as $q) {
        fputcsv($output, [
            $q['assessment_title'],
            $q['skill_cat'],
            $q['skill_name'],
            $q['difficulty'],
            'MCQ',
            $q['question_text'],
            $q['option_a'],
            $q['option_b'],
            $q['option_c'],
            $q['option_d'],
            $q['correct_option'],
            'PHP variables and core structures.', // Default explanation if none in DB
            $q['marks'],
            'active'
        ]);
    }

    fclose($output);
    exit;
}

/**
 * Export Downloadable CSV Error Report for Invalid Questions
 */
function download_question_error_report(array $validatedRows): void {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="SkillBridge_Question_Import_Error_Report_' . date('Y-m-d_His') . '.csv"');

    $output = fopen('php://output', 'w');
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, ['Row #', 'Assessment Title', 'Question Text', 'Validation Errors']);

    foreach ($validatedRows as $r) {
        if ($r['is_valid']) continue;
        fputcsv($output, [
            $r['row_num'],
            $r['assessment_title'] ?? '',
            $r['question_text'] ?? '',
            $r['error_text'] ?? 'Invalid Record'
        ]);
    }

    fclose($output);
    exit;
}

/**
 * Validate Question Bank Question Import Rows
 */
function validate_qbank_question_import_rows(array $rows, Database $db, int $qbId): array {
    $qb = $db->fetch("SELECT * FROM question_banks WHERE id = ?", [$qbId]);
    if (!$qb) {
        return [
            'total_rows' => count($rows),
            'valid_count' => 0,
            'invalid_count' => count($rows),
            'duplicate_count' => 0,
            'rows' => []
        ];
    }

    // Cache existing questions in this specific bank to check for duplicates
    $existingQs = $db->fetchAll("SELECT LOWER(TRIM(question_text)) as q_text FROM questions WHERE question_bank_id = ?", [$qbId]);
    $dbQuestions = array_column($existingQs, 'q_text');

    $seenInFile = [];
    $validatedRows = [];
    $validCount = 0;
    $invalidCount = 0;
    $duplicateCount = 0;

    foreach ($rows as $row) {
        $rowNum = $row['_row_num'] ?? 0;
        
        $category = trim($row['category'] ?? '');
        $skill = trim($row['skill'] ?? '');
        $diffLevel = strtolower(trim($row['difficulty_level'] ?? $row['difficulty'] ?? ''));
        $qText = trim($row['question_text'] ?? $row['question'] ?? '');
        $optA = trim($row['option_a'] ?? '');
        $optB = trim($row['option_b'] ?? '');
        $optC = trim($row['option_c'] ?? '');
        $optD = trim($row['option_d'] ?? '');
        $correctAns = strtoupper(trim($row['correct_answer'] ?? $row['correct_option'] ?? ''));
        $explanation = trim($row['explanation'] ?? '');

        $errors = [];
        $isDuplicate = false;

        // Validate category matches bank if provided
        if (!empty($category) && strtolower($category) !== strtolower($qb['category'])) {
            $errors[] = "Category '{$category}' does not match Question Bank category '{$qb['category']}'.";
        }
        // Validate skill matches bank if provided
        if (!empty($skill) && strtolower($skill) !== strtolower($qb['skill'])) {
            $errors[] = "Skill '{$skill}' does not match Question Bank skill '{$qb['skill']}'.";
        }
        // Validate difficulty matches bank if provided
        if (!empty($diffLevel) && strtolower($diffLevel) !== strtolower($qb['difficulty'])) {
            $errors[] = "Difficulty '{$diffLevel}' does not match Question Bank difficulty '{$qb['difficulty']}'.";
        }

        // Validate Question Text
        if (empty($qText)) {
            $errors[] = 'Question Text is required.';
        }

        // Validate Options
        if (empty($optA) || empty($optB) || empty($optC) || empty($optD)) {
            $errors[] = 'All four options (Option A, B, C, D) must be provided.';
        }

        // Validate Correct Answer
        if (empty($correctAns)) {
            $errors[] = 'Correct Answer is required.';
        } elseif (!in_array($correctAns, ['A', 'B', 'C', 'D'])) {
            $errors[] = "Invalid Correct Answer '{$correctAns}'. Must be A, B, C, or D.";
        }

        // Check duplicate in file
        if (!empty($qText)) {
            $normText = strtolower($qText);
            if (isset($seenInFile[$normText])) {
                $errors[] = 'Duplicate question text within this CSV file.';
                $isDuplicate = true;
            } else {
                $seenInFile[$normText] = true;
            }
        }

        // Check duplicate in database
        if (!empty($qText) && !$isDuplicate) {
            $normText = strtolower($qText);
            if (in_array($normText, $dbQuestions)) {
                $errors[] = 'Duplicate question: already exists in this Question Bank.';
                $isDuplicate = true;
            }
        }

        $isValid = empty($errors);
        if ($isValid) {
            $validCount++;
        } else {
            $invalidCount++;
            if ($isDuplicate) $duplicateCount++;
        }

        $validatedRows[] = [
            'row_num'          => $rowNum,
            'category'         => $category,
            'skill'            => $skill,
            'difficulty'       => $diffLevel,
            'question_text'    => $qText,
            'option_a'         => $optA,
            'option_b'         => $optB,
            'option_c'         => $optC,
            'option_d'         => $optD,
            'correct_answer'   => $correctAns,
            'explanation'      => $explanation,
            'is_valid'         => $isValid,
            'errors'           => $errors,
            'error_text'       => implode(' | ', $errors)
        ];
    }

    return [
        'total_rows'      => count($rows),
        'valid_count'     => $validCount,
        'invalid_count'   => $invalidCount,
        'duplicate_count' => $duplicateCount,
        'rows'            => $validatedRows
    ];
}

/**
 * Execute Question Bank Import inside Database Transaction
 */
function execute_qbank_question_import(array $validRows, Database $db, int $qbId, int $actorUserId): int {
    if (empty($validRows)) return 0;

    $importedCount = 0;
    $db->beginTransaction();

    try {
        foreach ($validRows as $r) {
            if (!$r['is_valid']) continue;

            $db->insert('questions', [
                'question_bank_id' => $qbId,
                'question_text'   => $r['question_text'],
                'option_a'        => $r['option_a'],
                'option_b'        => $r['option_b'],
                'option_c'        => $r['option_c'],
                'option_d'        => $r['option_d'],
                'correct_option'  => $r['correct_answer'],
                'marks'           => 1
            ]);

            $importedCount++;
        }

        // Recalculate marks and durations for assessments linked to this Question Bank
        $qCount = (int)($db->fetch("SELECT COUNT(*) as cnt FROM questions WHERE question_bank_id = ?", [$qbId])['cnt'] ?? 0);
        $totalMarks = max(1, $qCount);
        $passThreshold = (float)get_system_setting('pass_mark_threshold', 60);
        $passingMarks = (int)round($totalMarks * ($passThreshold / 100.0));

        $db->update('assessments', [
            'total_marks' => $totalMarks,
            'passing_marks' => $passingMarks,
            'duration_minutes' => max(15, $qCount * 1)
        ], 'question_bank_id = ?', [$qbId]);

        // Update Question Bank updated_at timestamp
        $db->update('question_banks', ['updated_at' => date('Y-m-d H:i:s')], 'id = ?', [$qbId]);

        $db->commit();
        invalidate_assessment_sync_cache($db);

        log_activity($actorUserId, 'IMPORT_QUESTIONS_QBANK', "Imported {$importedCount} questions successfully to Bank ID: {$qbId}.");
        return $importedCount;
    } catch (Throwable $e) {
        $db->rollBack();
        throw $e;
    }
}

