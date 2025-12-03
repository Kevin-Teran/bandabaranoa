<?php
/**
 * @file index.php
 * @route /admin/index.php
 * @description Controlador Frontal del Panel Administrativo.
 * Maneja la autenticación, enrutamiento y renderizado de vistas.
 * @author Kevin Mariano
 * @version 1.1.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
ob_start();

if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__));
if (!defined('BASE_URL')) define('BASE_URL', '//' . $_SERVER['HTTP_HOST'] . '/banda_de_Baranoa');

require_once ROOT_PATH . '/config/db.php';
require_once ROOT_PATH . '/includes/Database.php';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    try {
        $db = Database::getInstance();
        $user = $db->fetchOne("SELECT * FROM users WHERE username = :u LIMIT 1", [':u' => $username]);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $user['username'];
            header('Location: index.php?view=dashboard');
            exit;
        } else {
            $error = 'Credenciales incorrectas.';
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

if (!isset($_SESSION['admin_logged_in'])) {
    require_once 'login_view.php';
    exit;
}

$view = isset($_GET['view']) ? preg_replace('/[^a-z0-9-_]/', '', $_GET['view']) : 'dashboard';
$viewPath = __DIR__ . "/views/{$view}.php";

require_once 'includes/header.php';
require_once 'includes/sidebar.php';

echo '<div id="page-content-wrapper">';

    echo '
    <nav class="navbar navbar-light bg-white border-bottom px-3 py-3 mb-4 d-lg-none shadow-sm sticky-top">
        <div class="d-flex align-items-center w-100">
            <button class="btn btn-light border shadow-sm me-3" id="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="fw-bold text-secondary h5 mb-0">Panel Admin</span>
        </div>
    </nav>';

    echo '<div class="container-fluid px-0">';
    if (file_exists($viewPath)) {
        include $viewPath;
    } else {
        echo '<div class="alert alert-danger m-3">Vista no encontrada</div>';
    }
    echo '</div>';

require_once 'includes/footer.php';
ob_end_flush();
?>