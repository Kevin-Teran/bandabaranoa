<?php
/**
 * @file db.php
 * @route /config/db.php
 * @description Configuración central: Base de datos y constantes del sistema.
 * @author Kevin Mariano
 * @version 1.2.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// 2. Definir URL Base Automática (Para Router::asset y enlaces)
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $scriptDir = str_replace('\\', '/', $scriptDir); // Fix para Windows
    
    // Si el script está en la raíz, dejar vacío, si no, usar el directorio
    $path = ($scriptDir === '/') ? '' : $scriptDir;
    
    define('BASE_URL', rtrim($protocol . "://" . $host . $path, '/'));
}

// 3. Credenciales BD
define('DB_HOST', 'localhost');
define('DB_NAME', 'banda_baranoa');
define('DB_USER', 'root');
define('DB_PASS', '');     
define('DB_CHARSET', 'utf8mb4');

define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    PDO::ATTR_EMULATE_PREPARES => false, 
]);
?>