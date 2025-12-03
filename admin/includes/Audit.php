<?php
/**
 * @file Audit.php
 * @route /admin/includes/Audit.php
 * @description Clase Helper para registrar acciones de usuarios (Logs).
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

class Audit {
    public static function log($action, $description) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        try {
            $db = Database::getInstance();
            $userId = $_SESSION['admin_id'] ?? null; // Obtener ID de sesión
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            
            // Insertar en tabla audit_logs creada en install.php
            $sql = "INSERT INTO audit_logs (user_id, action, description, ip_address, created_at) 
                    VALUES (:uid, :act, :desc, :ip, NOW())";
            
            $db->query($sql, [
                ':uid' => $userId,
                ':act' => $action,
                ':desc' => $description,
                ':ip' => $ip
            ]);
        } catch (Exception $e) {
            // Silenciar error para no detener el flujo principal, pero podrías guardarlo en un archivo.
        }
    }
}
?>