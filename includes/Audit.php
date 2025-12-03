<?php
/**
 * @file Audit.php
 * @route /includes/Audit.php
 * @description Sistema de auditoría para registrar acciones de los usuarios.
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

require_once 'Database.php';

class Audit {
    /**
     * Registra una acción en la base de datos.
     * * @param int $userId ID del usuario que realiza la acción.
     * @param string $action Tipo de acción (LOGIN, CREATE, UPDATE, DELETE).
     * @param string $description Descripción detallada del cambio.
     * @param string $table (Opcional) Tabla afectada.
     * @param int $recordId (Opcional) ID del registro afectado.
     * @return bool
     */
    public static function log($userId, $action, $description, $table = null, $recordId = null) {
        $db = Database::getInstance();
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

        return $db->insert('audit_logs', [
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'table_name' => $table,
            'record_id' => $recordId,
            'ip_address' => $ip,
            'user_agent' => $userAgent
        ]);
    }
}
?>