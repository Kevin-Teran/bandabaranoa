<?php
/**
 * @file Router.php
 * @route /includes/Router.php
 * @description Sistema centralizado de enrutamiento con soporte para URLs amigables
 * @author Kevin Mariano
 * @version 2.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

class Router {
    
    private static $routes = [];
    private static $current_route = null;
    
    /**
     * Inicializa el sistema de rutas
     * @return void
     */
    public static function init() {
        self::registerRoutes();
        self::dispatch();
    }
    
    /**
     * Registra todas las rutas disponibles
     * @return void
     */
    private static function registerRoutes() {
        self::$routes = [
            '' => 'home',
            'home' => 'home',
            'eventos' => 'eventos',
            'evento-detalle' => 'evento-detalle',
            'noticias' => 'noticias',
            'noticia' => 'noticia-detalle',
            'galeria' => 'galeria',
            'contact' => 'contact',
        ];
    }
    
    /**
     * Procesa la URL y despacha a la página correcta
     * @return void
     */
    private static function dispatch() {
        $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $uri = substr($request_uri, strlen($base));
        $uri = strtok($uri, '?'); 
        $uri = trim($uri, '/');
        
        $segments = $uri ? explode('/', $uri) : [];
        
        if (empty($segments)) {
            self::$current_route = 'home';
        } elseif (isset(self::$routes[$segments[0]])) {
            self::$current_route = self::$routes[$segments[0]];
            
            if (count($segments) > 1) {
                switch ($segments[0]) {
                    case 'noticia':
                        $_GET['slug'] = $segments[1];
                        break;
                    case 'evento-detalle':
                        $_GET['id'] = $segments[1];
                        break;
                }
            }
        } else {
            self::$current_route = '404';
            http_response_code(404);
        }
        
        $_GET['page'] = self::$current_route;
    }
    
    /**
     * Obtiene la ruta actual
     * @return string
     */
    public static function getCurrentRoute() {
        return self::$current_route ?? 'home';
    }
    
    /**
     * Genera una URL limpia
     * @param string $page Página destino
     * @param array $params Parámetros adicionales
     * @return string
     */
    public static function url($page, $params = []) {
        $base = defined('BASE_URL') ? BASE_URL : '';

        if ($page === 'home' || $page === 'index') {
            $url = $base . '/';
        } else {
            $url = $base . '/' . $page;
        }

        if (!empty($params)) {
            $query = http_build_query($params);
            $url .= '?' . $query;
        }
        
        return $url;
    }
    
    /**
     * Verifica si una ruta está activa
     * @param string $page
     * @return bool
     */
    public static function isActive($page) {
        return self::$current_route === $page;
    }
    
    /**
     * Redirecciona a una página
     * @param string $page
     * @param int $code Código HTTP (301, 302, etc)
     * @return void
     */
    public static function redirect($page, $code = 302) {
        $url = self::url($page);
        http_response_code($code);
        header("Location: $url");
        exit;
    }
}