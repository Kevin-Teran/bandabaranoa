<?php
/**
 * @file index.php
 * @route /index.php
 * @description Punto de entrada principal con soporte i18n.
 * @author Kevin Mariano
 * @version 1.1.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

session_start();

// 1. Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Cargar núcleo
require_once 'config/db.php';
require_once 'includes/Database.php';

// 3. Sistema de Idiomas (Básico para que no falle header.php)
// Se define global para que esté disponible en las vistas
global $lang;
$langCode = $_GET['lang'] ?? $_SESSION['lang'] ?? 'es';
$_SESSION['lang'] = $langCode;

// Cargar archivo de idioma
$langFile = BASE_PATH . "/lang/{$langCode}.php";
if (file_exists($langFile)) {
    require_once $langFile;
} else {
    // Fallback a español si no existe
    require_once BASE_PATH . "/lang/es.php";
}

// 4. Cargar y Ejecutar Router
// (Las rutas se definen dentro de este archivo)
require_once 'includes/Router.php';

// Disparar la aplicación
Router::dispatch();
?>