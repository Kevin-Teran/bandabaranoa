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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

require_once 'config/db.php';
require_once 'includes/Database.php';

$route = $_GET['page'] ?? 'home';
$route = rtrim($route, '/');
$params = explode('/', $route);
$page_name = !empty($params[0]) ? $params[0] : 'home';

if ($page_name === 'noticia-detalle') {
    if (isset($params[1])) {
        $_GET['slug'] = $params[1];
    }
}

$page_file = BASE_PATH . '/pages/' . $page_name . '.php';

if (!file_exists($page_file)) {
    $page_file = BASE_PATH . '/pages/404.php';
    $page_name = '404';
    http_response_code(404);
}

$page_title = ucfirst(str_replace('-', ' ', $page_name));

require_once BASE_PATH . '/templates/header.php';
require_once BASE_PATH . '/templates/navigation.php';
require_once $page_file;
require_once BASE_PATH . '/templates/footer.php';
?>