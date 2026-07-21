<?php
/**
 * SkillBridge - Database Singleton & PDO Helper Wrapper
 * Pure PHP 8.x PDO implementation
 */

require_once __DIR__ . '/config.php';

class Database {
    private static ?Database $instance = null;
    private ?PDO $pdo = null;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("<div style='font-family:sans-serif; padding:20px; background:#fee2e2; color:#991b1b; border:1px solid #f87171; border-radius:8px; margin:20px;'>
                <h2>Database Connection Error</h2>
                <p>Unable to connect to MySQL database <strong>" . DB_NAME . "</strong>.</p>
                <p><strong>Error Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                <hr>
                <p><em>Please make sure MySQL is running in XAMPP and you have imported <code>sql/skillbridge_db.sql</code>.</em></p>
            </div>");
        }
    }

    // Singleton Instance Getter
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Get Raw PDO Connection
    public static function getConnection(): PDO {
        return self::getInstance()->pdo;
    }

    // Execute Prepared Statement (Select, Insert, Update, Delete)
    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Fetch Single Row
    public function fetch(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    }

    // Fetch All Rows
    public function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    // Insert Helper
    public function insert(string $table, array $data): int {
        $columns = implode(', ', array_map(fn($col) => "`$col`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
        $this->query($sql, array_values($data));
        return (int)$this->pdo->lastInsertId();
    }

    // Update Helper
    public function update(string $table, array $data, string $where, array $whereParams = []): int {
        $set = implode(', ', array_map(fn($col) => "`$col` = ?", array_keys($data)));
        $sql = "UPDATE `$table` SET $set WHERE $where";
        $params = array_merge(array_values($data), $whereParams);
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    // Delete Helper
    public function delete(string $table, string $where, array $params = []): int {
        $sql = "DELETE FROM `$table` WHERE $where";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    // Last Insert ID
    public function lastInsertId(): int {
        return (int)$this->pdo->lastInsertId();
    }

    // Transaction Wrappers
    public function beginTransaction(): bool {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool {
        return $this->pdo->commit();
    }

    public function rollBack(): bool {
        return $this->pdo->rollBack();
    }
}
