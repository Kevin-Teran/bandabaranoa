<?php
/**
 * @file home.php
 * @route /pages/home.php
 * @description Landing Page (One-Page) consolidada. Incluye todas las secciones estáticas con anclas.
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

global $lang;

require_once BASE_PATH . '/templates/sections/hero_home.php';
require_once BASE_PATH . '/templates/sections/quienes_somos_home.php';
require_once BASE_PATH . '/templates/sections/experiencias_home.php';
require_once BASE_PATH . '/templates/sections/corporativo_home.php';
require_once BASE_PATH . '/templates/sections/concha_acustica_home.php';
require_once BASE_PATH . '/templates/sections/noticias_preview.php';
require_once BASE_PATH . '/templates/sections/galeria_preview.php';
?>