<?php
/**
 * @file header.php 
 * @route /admin/includes/header.php
 * @description Diseño Premium estilo React/SaaS.
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

if (!defined('BASE_URL')) define('BASE_URL', '//' . $_SERVER['HTTP_HOST'] . '/banda_de_Baranoa');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Banda de Baranoa</title>
    
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/img/favicon.png">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary: #E30000; /* Rojo Banda */
            --primary-light: #FFF0F0;
            --sidebar-bg: #FFFFFF;
            --sidebar-width: 260px;
            --body-bg: #F9FAFB;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --radius: 12px;
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* --- SIDEBAR PREMIUM --- */
        #sidebar-wrapper {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid #F3F4F6; /* Borde sutil */
            position: fixed;
            top: 0; left: 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            padding: 0 12px 32px 12px;
            display: flex;
            justify-content: center;
        }
        .sidebar-brand img { max-height: 50px; width: auto; }

        /* Links del Menú estilo 'Pill' */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin-bottom: 4px;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.95rem;
            border-radius: var(--radius);
            transition: all 0.2s ease;
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            margin-right: 12px;
            text-align: center;
            color: #9CA3AF;
            transition: color 0.2s;
        }

        .nav-link:hover {
            background-color: #F9FAFB;
            color: var(--text-main);
        }

        .nav-link.active {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .nav-link.active i { color: var(--primary); }

        .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9CA3AF;
            font-weight: 700;
            padding: 24px 16px 8px 16px;
        }

        /* Footer del Sidebar */
        .sidebar-footer {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid #F3F4F6;
        }

        /* --- CONTENIDO --- */
        #page-content-wrapper {
            margin-left: var(--sidebar-width);
            padding: 40px;
            width: calc(100% - var(--sidebar-width));
            transition: margin 0.3s;
        }

        /* Tarjetas Estilo Dashboard Moderno */
        .card-modern {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #F3F4F6;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            overflow: hidden;
        }

        /* Responsive */
        @media (max-width: 992px) {
            #sidebar-wrapper { transform: translateX(-100%); box-shadow: 10px 0 25px rgba(0,0,0,0.05); }
            #sidebar-wrapper.show { transform: translateX(0); }
            #page-content-wrapper { margin-left: 0; width: 100%; padding: 20px; }
            
            .overlay {
                display: none; position: fixed; top:0; left:0; right:0; bottom:0;
                background: rgba(0,0,0,0.3); backdrop-filter: blur(2px); z-index: 1040;
            }
            .overlay.active { display: block; }
        }
    </style>
</head>
<body>
    <div class="overlay" id="mobileOverlay"></div>
    <div id="wrapper">