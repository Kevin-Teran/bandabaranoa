<?php
/**
 * @file Language.php
 * @route /includes/Language.php
 * @description Clase Helper para gestión de traducciones estáticas y dinámicas.
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

class Language {
    private $current_lang;
    private $translations = [];
    private $available_langs = ['es', 'en'];

    public function __construct() {
        // 1. Detectar idioma (Session o GET)
        if (isset($_GET['lang']) && in_array($_GET['lang'], $this->available_langs)) {
            $_SESSION['lang'] = $_GET['lang'];
        }
        
        // Definir idioma actual
        $this->current_lang = $_SESSION['lang'] ?? 'es';

        // 2. Cargar el archivo de traducción
        $this->load();
    }

    private function load() {
        // Aseguramos que la ruta sea correcta usando BASE_PATH si está definido, o __DIR__ como respaldo
        $base = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
        $file = $base . '/lang/' . $this->current_lang . '.php';
        
        if (file_exists($file)) {
            $loadedData = include($file);
            if (is_array($loadedData)) {
                $this->translations = $loadedData;
            }
        } else {
            // Cargar español por defecto si falla el archivo
            $fallback = $base . '/lang/es.php';
            if (file_exists($fallback)) {
                $this->translations = include($fallback);
            }
        }
    }

    // Esta es la función que le faltaba a tu archivo anterior
    public function getTranslations() {
        return $this->translations;
    }

    public function current() {
        return $this->current_lang;
    }
}
?>