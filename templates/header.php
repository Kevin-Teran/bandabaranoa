<?php
/**
 * @file header.php
 * @route /templates/header.php
 * @description Header corregido con Router::asset para cargar estilos en cualquier subpágina.
 * @author Kevin Mariano & Refactor
 * @version 1.2.0
 */
 
global $lang, $page_title;
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? DEFAULT_LANG ?>">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Kevin Mariano">
        <meta name="description" content="<?= $lang['meta_descripcion'] ?? '' ?>">
        <title><?= htmlspecialchars($page_title ?? 'Banda de Baranoa') ?> - <?= $lang['meta_titulo'] ?? '' ?></title>
        
        <link rel="shortcut icon" href="<?= Router::asset('img/favicon.png') ?>"> 

        <link rel="stylesheet" href="<?= Router::asset('css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/all.min.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/animate.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/magnific-popup.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/meanmenu.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/swiper-bundle.min.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/datepickerboot.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/nice-select.css') ?>">
        <link rel="stylesheet" href="<?= Router::asset('css/color.css') ?>">
        
        <link rel="stylesheet" href="<?= Router::asset('css/main.css') ?>">

        <style>
            body {
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
            img { pointer-events: none; }
            input, textarea { user-select: text !important; }

            .header-logo img, .header-logo-2 img {
                max-width: 150px;
                height: auto;
            }
        </style>
    </head>
    <body oncontextmenu="return false;">
        <?php 
        if (defined('BASE_PATH')) {
            include BASE_PATH . '/templates/preloader.php';
        } else {
            include 'preloader.php';
        }
        ?>
    