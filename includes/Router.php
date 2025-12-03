<?php
/**
 * @file Router.php
 * @route /includes/Router.php
 * @description Enrutador robusto con corrección de ruta raíz y definiciones centralizadas.
 * @author Kevin Mariano
 * @version 3.2.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

class Router {
    private static $routes = [];

    /**
     * Registra una ruta GET
     */
    public static function get($uri, $callback) {
        // Al registrar '/', trim lo convierte en cadena vacía ""
        $uri = trim($uri, '/');
        self::$routes['GET'][$uri] = $callback;
    }

    /**
     * Procesa la URL actual
     */
    public static function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Detección automática de la carpeta raíz del proyecto
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        
        // Corrección para Windows (reemplazar \ por /)
        $scriptDir = str_replace('\\', '/', $scriptDir);

        // Si estamos en una subcarpeta, la quitamos de la URI
        if ($scriptDir !== '/' && strpos($uri, $scriptDir) === 0) {
            $uri = substr($uri, strlen($scriptDir));
        }

        // CORRECCIÓN APLICADA: 
        // Solo hacemos trim. Si es la raíz, queda como "" (igual que en el registro).
        // Eliminamos la línea que forzaba $uri = '/' para que coincidan.
        $uri = trim($uri, '/');

        $method = $_SERVER['REQUEST_METHOD'];

        if (isset(self::$routes[$method])) {
            foreach (self::$routes[$method] as $route => $callback) {
                // Convertir :param en regex
                $pattern = "@^" . preg_replace('/:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', $route) . "$@D";
                $matches = [];

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // Quitar la coincidencia completa
                    
                    if (is_callable($callback)) {
                        call_user_func_array($callback, $matches);
                    }
                    return;
                }
            }
        }

        self::render('pages/404.php', ['page_title' => 'Página no encontrada']);
    }

    /**
     * Renderiza una vista inyectando variables globales de forma segura.
     */
    public static function render($viewPath, $data = []) {
        global $db, $lang, $page_title;

        if (!$db) $db = Database::getInstance();

        if (isset($data['page_title'])) {
            $page_title = $data['page_title']; 
        }

        extract($data);

        require_once BASE_PATH . '/templates/header.php';
        require_once BASE_PATH . '/templates/navigation.php';

        $fullPath = BASE_PATH . '/' . $viewPath;
        if (file_exists($fullPath)) {
            require_once $fullPath;
        } else {
            // Estilo simple para error de desarrollo
            echo "<div style='padding:50px; text-align:center; color:red;'>";
            echo "<h2>Error 404: Vista no encontrada</h2>";
            echo "<p>Buscando en: " . htmlspecialchars($fullPath) . "</p>";
            echo "</div>";
        }

        require_once BASE_PATH . '/templates/footer.php';
    }

    public static function url($path = '') {
        return BASE_URL . '/' . ltrim($path, '/');
    }

    public static function asset($path = '') {
        return BASE_URL . '/assets/' . ltrim($path, '/');
    }
}

/* -------------------------------------------------------------------------- */
/* DEFINICIÓN DE RUTAS                         */
/* -------------------------------------------------------------------------- */

Router::get('/', function() {
    Router::render('pages/home.php', ['page_title' => 'Inicio']);
});
Router::get('home', function() {
    Router::render('pages/home.php', ['page_title' => 'Inicio']);
});

Router::get('noticias', function() {
    Router::render('pages/noticias.php', ['page_title' => 'Noticias']);
});

Router::get('eventos', function() {
    Router::render('pages/eventos.php', ['page_title' => 'Eventos']);
});

Router::get('galeria', function() {
    Router::render('pages/galeria.php', ['page_title' => 'Galería Multimedia']);
});

Router::get('noticia/:slug', function($slug) {
    $_GET['slug'] = $slug; 
    Router::render('pages/noticia-detalle.php', ['page_title' => 'Detalle Noticia', 'slug' => $slug]);
});

Router::get('evento/:slug', function($slug) {
    $_GET['slug'] = $slug;
    Router::render('pages/evento-detalle.php', ['page_title' => 'Detalle Evento', 'slug' => $slug]);
});

Router::get('contacto', function() {
    Router::render('pages/contacto.php', ['page_title' => 'Contáctanos']);
});
?>