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
