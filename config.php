<?php
/**
 * @file config.php
 * @route /config.php
 * @description Configuración central del sitio con constantes y carga de módulos
 * @author Kevin Mariano
 * @version 2.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', __DIR__);
define('BASE_URL', '/bandabaranoa');
define('ENVIRONMENT', 'development'); 
define('DEFAULT_LANG', 'es');
define('SITE_NAME', 'Banda de Baranoa');
define('SITE_VERSION', '1.0.0');

define('CSRF_ENABLED', true);
define('SESSION_LIFETIME', 3600); 

define('CACHE_ENABLED', false); 
define('CACHE_TIME', 3600);

date_default_timezone_set('America/Bogota');

$core_modules = [
    '/includes/Language.php',
    '/includes/Translator.php',
    '/includes/functions.php'
];

foreach ($core_modules as $module) {
    $file = BASE_PATH . $module;
    if (file_exists($file)) {
        require_once $file;
    }
}

if (class_exists('Language')) {
    $language = new Language();
    $lang = $language->getTranslations();
    define('CURRENT_LANG', $language->current());
} else {
    $lang_file = BASE_PATH . '/lang/es.php';
    $lang = file_exists($lang_file) ? require $lang_file : [];
    define('CURRENT_LANG', 'es');
}

/**
 * Verifica si estamos en modo desarrollo
 * @return bool
 */
function isDevelopment() {
    return defined('ENVIRONMENT') && ENVIRONMENT === 'development';
}

/**
 * Verifica si estamos en modo producción
 * @return bool
 */
function isProduction() {
    return defined('ENVIRONMENT') && ENVIRONMENT === 'production';
}

/**
 * Obtiene una configuración del sitio
 * @param string $key Clave de configuración
 * @param mixed $default Valor por defecto
 * @return mixed
 */
function config($key, $default = null) {
    static $config = null;
    
    if ($config === null) {
        $config = [
            'site.name' => SITE_NAME,
            'site.version' => SITE_VERSION,
            'site.lang' => CURRENT_LANG,
            'security.csrf' => CSRF_ENABLED,
            'cache.enabled' => CACHE_ENABLED,
            'cache.time' => CACHE_TIME
        ];
    }
    
    return $config[$key] ?? $default;
}

spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . '/includes/' . $class . '.php',
        BASE_PATH . '/admin/includes/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

if (isDevelopment()) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
    
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        $message = "Error [$errno]: $errstr in $errfile on line $errline";
        logEvent($message, 'error');
        
        if (!headers_sent()) {
            http_response_code(500);
            include BASE_PATH . '/pages/500.php';
            exit;
        }
    });
}