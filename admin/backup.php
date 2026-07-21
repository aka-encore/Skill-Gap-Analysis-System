<?php
/**
 * SkillBridge - One-Click Database Exporter Utility
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('admin');

// Handle Backup Export Request
if (isset($_GET['download']) && $_GET['download'] == '1') {
    $db = Database::getInstance();
    $pdo = Database::getConnection();

    $tables = [];
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $sqlDump = "-- SkillBridge Database Backup Dump\n";
    $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $sqlDump .= "-- Database: " . DB_NAME . "\n\n";
    $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    foreach ($tables as $table) {
        $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
        $createRow = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_NUM);
        $sqlDump .= $createRow[1] . ";\n\n";

        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $cols = array_map(fn($c) => "`$c`", array_keys($row));
                $vals = array_map(function($v) use ($pdo) {
                    if ($v === null) return 'NULL';
                    return $pdo->quote($v);
                }, array_values($row));

                $sqlDump .= "INSERT INTO `$table` (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n";
            }
            $sqlDump .= "\n";
        }
    }

    $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

    $filename = 'skillbridge_backup_' . date('Y_m_d_His') . '.sql';
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($sqlDump));

    log_activity($_SESSION['user_id'], 'DATABASE_BACKUP', 'Exported database dump ' . $filename);

    echo $sqlDump;
    exit;
}

$pageTitle = "Backup Database - Admin Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-database-down text-primary me-2"></i>Database Backup Exporter</h3>
        <p class="text-muted small mb-0">Generate and download complete MySQL database backups (.sql)</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 max-w-2xl mx-auto p-4 p-md-5 text-center bg-white">
    <div class="stat-icon-box bg-primary-subtle text-primary mx-auto mb-3" style="width:70px; height:70px; font-size:2rem;">
        <i class="bi bi-database-fill-down"></i>
    </div>
    <h4 class="fw-bold text-dark mb-2">Export Full System Database</h4>
    <p class="text-muted small mb-4">Clicking the button below will compile all 19 relational tables, foreign key constraints, indices, and active data records into a downloadable <code>.sql</code> script file.</p>
    
    <a href="<?= BASE_URL ?>admin/backup.php?download=1" class="btn btn-primary bg-gradient-primary border-0 rounded-pill px-5 py-2.5 fw-semibold shadow-xs">
        <i class="bi bi-download me-2"></i> Download Database Backup (.sql)
    </a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
