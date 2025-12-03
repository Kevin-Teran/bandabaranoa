<?php
/**
 * @file functions.php
 * @route /includes/functions.php
 * @description Funciones helper globales para todo el sitio
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

/**
 * Genera un breadcrumb dinámico
 * @param string $page_title Título de la página actual
 * @return void
 */
function generateBreadcrumb($page_title) {
    global $lang;
    $home_label = $lang['nav_inicio'] ?? 'Inicio';
    
    echo '<section class="breadcrumb-section" style="padding: 60px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">';
    echo '<div class="container">';
    echo '<div class="breadcrumb-wrapper text-center">';
    echo '<h1 class="text-white mb-3">' . htmlspecialchars($page_title) . '</h1>';
    echo '<nav aria-label="breadcrumb">';
    echo '<ol class="breadcrumb justify-content-center mb-0">';
    echo '<li class="breadcrumb-item"><a href="' . Router::url('home') . '" class="text-white">' . $home_label . '</a></li>';
    echo '<li class="breadcrumb-item active text-white" aria-current="page">' . htmlspecialchars($page_title) . '</li>';
    echo '</ol>';
    echo '</nav>';
    echo '</div>';
    echo '</div>';
    echo '</section>';
}

/**
 * Sanitiza una cadena para prevenir XSS
 * @param string $string Cadena a sanitizar
 * @return string Cadena sanitizada
 */
function sanitize($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Genera un slug SEO-friendly desde un texto
 * @param string $text Texto original
 * @return string Slug generado
 */
function generateSlug($text) {
    // Convertir a minúsculas
    $text = strtolower($text);
    
    // Reemplazar caracteres especiales
    $text = str_replace(['á','é','í','ó','ú','ñ'], ['a','e','i','o','u','n'], $text);
    
    // Eliminar caracteres no alfanuméricos
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    
    // Reemplazar espacios por guiones
    $text = preg_replace('/[\s-]+/', '-', $text);
    
    // Eliminar guiones al inicio y final
    $text = trim($text, '-');
    
    return $text;
}

/**
 * Formatea una fecha en español
 * @param string $date Fecha en formato MySQL
 * @param string $format Formato deseado
 * @return string Fecha formateada
 */
function formatDate($date, $format = 'd M Y') {
    $months = [
        'Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Abr',
        'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago',
        'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'
    ];
    
    $formatted = date($format, strtotime($date));
    
    return str_replace(array_keys($months), array_values($months), $formatted);
}

/**
 * Recorta un texto manteniendo palabras completas
 * @param string $text Texto original
 * @param int $length Longitud máxima
 * @param string $suffix Sufijo (ej: ...)
 * @return string Texto recortado
 */
function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    $text = substr($text, 0, $length);
    $lastSpace = strrpos($text, ' ');
    
    if ($lastSpace !== false) {
        $text = substr($text, 0, $lastSpace);
    }
    
    return $text . $suffix;
}

/**
 * Valida un email
 * @param string $email Email a validar
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Genera un token CSRF
 * @return string Token generado
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida un token CSRF
 * @param string $token Token a validar
 * @return bool
 */
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Obtiene la IP del cliente
 * @return string IP del cliente
 */
function getClientIP() {
    $ip = '0.0.0.0';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

/**
 * Debug helper - muestra variables de forma legible
 * @param mixed $var Variable a mostrar
 * @param bool $die Detener ejecución después
 * @return void
 */
function dd($var, $die = true) {
    echo '<pre style="background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 5px; font-family: monospace;">';
    var_dump($var);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}

/**
 * Registra un evento en el log
 * @param string $message Mensaje a registrar
 * @param string $level Nivel: info, warning, error
 * @return void
 */
function logEvent($message, $level = 'info') {
    $logFile = BASE_PATH . '/logs/' . date('Y-m-d') . '.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = getClientIP();
    $logLine = "[$timestamp] [$level] [IP: $ip] $message" . PHP_EOL;
    
    file_put_contents($logFile, $logLine, FILE_APPEND);
}