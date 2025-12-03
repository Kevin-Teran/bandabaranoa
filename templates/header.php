<?php
/**
 * @file header.php
 * @route /templates/header.php
 * @description Header limpio usando los estilos nativos de la plantilla.
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */
 
global $lang, $page_title;
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?? DEFAULT_LANG; ?>">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Kevin Mariano">
        <meta name="description" content="<?php echo $lang['meta_descripcion']; ?>">
        <title><?php echo htmlspecialchars($page_title); ?> - <?php echo $lang['meta_titulo']; ?></title>
        
        <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/assets/img/favicon.png"> 
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/all.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/animate.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/magnific-popup.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/meanmenu.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/swiper-bundle.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/datepickerboot.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/nice-select.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/color.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/main.css">

        <style>
            /* --- SEGURIDAD ANTI-COPIA (No afecta el diseño) --- */
            body {
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
            img { pointer-events: none; }
            input, textarea { user-select: text !important; }

            /* --- AJUSTE ÚNICO DE LOGO (Para que no se vea gigante) --- */
            .header-logo img, .header-logo-2 img {
                max-width: 150px; /* Tamaño estándar de plantilla */
                height: auto;
            }
        </style>
    </head>
    <body oncontextmenu="return false;">
        <?php include 'preloader.php'; ?>