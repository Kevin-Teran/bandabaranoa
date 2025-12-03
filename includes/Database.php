<?php
/**
 * @file Database.php
 * @route /includes/Database.php
 * @description Wrapper PDO Singleton seguro.
 * @author Kevin Mariano
 * @version 1.1.0
 * @copyright Banda de Baranoa 2025
 */

require_once dirname(__DIR__) . '/config/db.php';

class Database {
    private static $instance = null;
    private $pdo = null;
    private $connected = false; 
    private $error_msg = null;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
            $this->connected = true;
        } catch (PDOException $e) {
            $this->connected = false;
            $this->error_msg = $e->getMessage();
            error_log("DB Connection Failed: " . $e->getMessage());
        }
    }

    private function __clone() {}
    public function __wakeup() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Verifica si la base de datos está disponible.
     * Útil para mostrar mensajes de "Mantenimiento" en las vistas.
     */
    public function isConnected() {
        return $this->connected;
    }

    /**
     * Devuelve el error de conexión (solo para admins o debug)
     */
    public function getError() {
        return $this->error_msg;
    }

    public function query($sql, $params = []) {
        if (!$this->connected) {
            return null;
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query Failed: " . $e->getMessage());
            return null;
        }
    }

    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetch() : false; 
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll() : []; 
    }

    public function insert($table, $data) {
        if (!$this->connected) return false;

        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $this->query($sql, $data);
        return $this->pdo->lastInsertId();
    }
}
?>