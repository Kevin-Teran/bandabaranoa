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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';
require_once 'includes/Database.php';
require_once 'includes/Translator.php'; 

global $lang;
$lang = []; 

if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $langCode = $_GET['lang'];
    $_SESSION['lang'] = $langCode; 
} else {
    $langCode = $_SESSION['lang'] ?? 'es';
}

$langFile = BASE_PATH . "/lang/{$langCode}.php";
if (!file_exists($langFile)) {
    $langFile = BASE_PATH . "/lang/es.php";
}

$loadedData = require_once $langFile;
if (is_array($loadedData)) {
    $lang = $loadedData;
}

if (!defined('CURRENT_LANG')) {
    define('CURRENT_LANG', $langCode);
}

require_once 'includes/Router.php';
Router::dispatch();
?>