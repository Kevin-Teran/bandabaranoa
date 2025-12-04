<?php
/**
 * @file header.php
 * @route /templates/header.php
 * @description Header corregido con Router::asset para cargar estilos en cualquier subpágina.
 * @author Kevin Mariano & Refactor
 * @version 1.2.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */
 
global $lang, $page_title;

$idiomaActual = defined('CURRENT_LANG') ? CURRENT_LANG : (defined('DEFAULT_LANG') ? DEFAULT_LANG : 'es');
?>
<!DOCTYPE html>
<html lang="<?= $idiomaActual ?>">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Kevin Mariano">
        
        <meta name="description" content="<?= htmlspecialchars($lang['meta_descripcion'] ?? '') ?>">
        
        <title><?= htmlspecialchars($page_title ?? 'Banda de Baranoa') ?> - <?= htmlspecialchars($lang['meta_titulo'] ?? '') ?></title>
        
        <link rel="shortcut icon" href="<?= Router::asset('img/favicon.png') ?>"> 

        <link rel="stylesheet" href="<?= Router::asset('css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/all.min.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/animate.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/magnific-popup.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/meanmenu.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/swiper-bundle.min.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/datepickerboot.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/nice-select.css') ?>">
        
        <link rel="stylesheet" href="<?= Router::asset('css/main.css') ?>">

        <style>
            body {
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
            input, textarea, .selectable-text { user-select: text !important; }
            img { pointer-events: none; }

            .header-logo img, .header-logo-2 img {
                max-width: 150px;
                height: auto;
            }
        </style>
    </head>
    <body oncontextmenu="return false;">
        <?php 
        $preloaderPath = defined('BASE_PATH') ? BASE_PATH . '/templates/preloader.php' : 'preloader.php';
        if (file_exists($preloaderPath)) {
            include $preloaderPath;
        }
        ?>