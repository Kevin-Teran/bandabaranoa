<?php
/**
 * @file Database.php
 * @route /includes/Database.php
 * @description Wrapper PDO Singleton para gestión optimizada de base de datos (Estilo ORM ligero).
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

require_once dirname(__DIR__) . '/config/db.php';

class Database {
    private static $instance = null;
    private $pdo;

    /**
     * Constructor privado para patrón Singleton.
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
        } catch (PDOException $e) {
            // En producción, loguear esto en un archivo, no mostrar al usuario
            die("Error Crítico de Base de Datos: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la instancia única de la conexión.
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Ejecuta una consulta SQL preparada de forma segura.
     * @param string $sql La consulta SQL.
     * @param array $params Parámetros para binding (evita inyección SQL).
     * @return PDOStatement
     */
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Obtiene una única fila de resultados.
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Obtiene todas las filas de resultados.
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Método Helper: INSERT simplificado (Estilo Prisma).
     * @param string $table Nombre de la tabla.
     * @param array $data Array asociativo ['columna' => 'valor'].
     * @return int ID del último registro insertado.
     */
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $this->query($sql, $data);
        return $this->pdo->lastInsertId();
    }

    /**
     * Método Helper: UPDATE simplificado.
     * @param string $table Nombre de la tabla.
     * @param array $data Datos a actualizar ['columna' => 'valor'].
     * @param string $where Condición WHERE (ej: "id = :id").
     * @param array $whereParams Parámetros para la condición WHERE.
     * @return int Número de filas afectadas.
     */
    public function update($table, $data, $where, $whereParams = []) {
        $setPart = "";
        foreach ($data as $key => $value) {
            $setPart .= "{$key} = :{$key}, ";
        }
        $setPart = rtrim($setPart, ", "); // Quitar última coma
        
        $sql = "UPDATE {$table} SET {$setPart} WHERE {$where}";
        
        // Combinar datos y parámetros del where
        $params = array_merge($data, $whereParams);
        
        return $this->query($sql, $params)->rowCount();
    }

    /**
     * Método Helper: DELETE simplificado.
     * @param string $table Nombre de la tabla.
     * @param string $where Condición WHERE.
     * @param array $params Parámetros.
     * @return int Filas afectadas.
     */
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($sql, $params)->rowCount();
    }
}
?>