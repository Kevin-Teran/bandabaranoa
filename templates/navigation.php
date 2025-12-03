<?php
/**
 * @file navigation.php
 * @route /templates/navigation.php
 * @description Barra de navegación con header visible siempre en páginas internas
 * @author Kevin Mariano
 * @version 1.0.3
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

global $lang; 

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$es_home = ($page == 'home' || $page == 'index' || empty($page));

if ($es_home) {
    $clase_header = 'header-1 header-3 header-transparent';
} else {
    $clase_header = 'header-1 header-3 header-interno';
}

$codigo_idioma = defined('CURRENT_LANG') ? CURRENT_LANG : 'es';
$label_idioma = ($codigo_idioma == 'en') ? 'EN' : 'ES';

function getUrlWithLang($newLang) {
    $params = $_GET;
    $params['lang'] = $newLang;
    return '?' . http_build_query($params);
}
?>

<style>
    /* ==============================================
       HEADER EN PÁGINAS INTERNAS (SIEMPRE VISIBLE)
       ============================================== */
    header.header-interno {
        background-color: #ffffff !important;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1) !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        z-index: 9999 !important;
        transform: translateY(0) !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    /* Texto negro en páginas internas */
    header.header-interno .main-menu ul li a {
        color: #000000 !important;
        font-weight: 700 !important;
    }
    
    header.header-interno .lang-btn {
        color: #000000 !important;
        font-weight: 700 !important;
    }
    
    header.header-interno .sidebar__toggle i {
        color: #000000 !important;
    }
    
    /* Logo negro visible en internas */
    header.header-interno .logo .header-logo { 
        display: block !important; 
    }
    header.header-interno .logo .header-logo-2 { 
        display: none !important; 
    }
    
    /* Botón WhatsApp rojo */
    header.header-interno .theme-btn {
        background-color: #d90a2c !important;
        color: #ffffff !important;
        border-color: #d90a2c !important;
    }

    /* ==============================================
       HEADER EN HOME (TRANSPARENTE -> FIJO)
       ============================================== */
    
    /* Home sin scroll: transparente */
    header.header-transparent:not(.sticky) {
        background-color: transparent !important;
        box-shadow: none !important;
    }
    
    header.header-transparent:not(.sticky) .main-menu ul li a,
    header.header-transparent:not(.sticky) .lang-btn,
    header.header-transparent:not(.sticky) .sidebar__toggle i {
        color: #ffffff !important;
    }
    
    /* Logo blanco en home transparente */
    header.header-transparent:not(.sticky) .logo .header-logo { 
        display: none !important; 
    }
    header.header-transparent:not(.sticky) .logo .header-logo-2 { 
        display: block !important; 
    }

    /* Home con scroll: fondo blanco */
    header.header-transparent.sticky {
        background-color: #ffffff !important;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1) !important;
    }
    
    header.header-transparent.sticky .main-menu ul li a,
    header.header-transparent.sticky .lang-btn,
    header.header-transparent.sticky .sidebar__toggle i {
        color: #000000 !important;
        font-weight: 700 !important;
    }
    
    /* Logo negro en home con scroll */
    header.header-transparent.sticky .logo .header-logo { 
        display: block !important; 
    }
    header.header-transparent.sticky .logo .header-logo-2 { 
        display: none !important; 
    }

    /* ==============================================
       MENÚ MÓVIL (MEAN MENU)
       ============================================== */
    
    /* Home transparente - menú oscuro */
    header.header-transparent:not(.sticky) .mean-container a.meanmenu-reveal {
        color: #ffffff !important;
        border: 1px solid rgba(255,255,255,0.5) !important;
    }
    
    header.header-transparent:not(.sticky) .mean-container a.meanmenu-reveal span {
        background-color: #ffffff !important;
    }
    
    header.header-transparent:not(.sticky) .mean-container .mean-nav {
        background-color: rgba(0,0,0,0.95) !important;
    }
    
    header.header-transparent:not(.sticky) .mean-container .mean-nav ul li a {
        color: #ffffff !important;
        border-top: 1px solid rgba(255,255,255,0.1) !important;
    }

    /* Home con scroll y páginas internas - menú claro */
    header.header-transparent.sticky .mean-container a.meanmenu-reveal,
    header.header-interno .mean-container a.meanmenu-reveal {
        color: #000000 !important;
        border: 1px solid #000000 !important;
    }
    
    header.header-transparent.sticky .mean-container a.meanmenu-reveal span,
    header.header-interno .mean-container a.meanmenu-reveal span {
        background-color: #000000 !important;
    }
    
    header.header-transparent.sticky .mean-container .mean-nav,
    header.header-interno .mean-container .mean-nav {
        background-color: #ffffff !important;
    }
    
    header.header-transparent.sticky .mean-container .mean-nav ul li a,
    header.header-interno .mean-container .mean-nav ul li a {
        color: #000000 !important;
        font-weight: 700 !important;
        border-top: 1px solid #f0f0f0 !important;
    }

    /* ==============================================
       SELECTOR DE IDIOMA
       ============================================== */
    .lang-selector { 
        position: relative; 
        margin-right: 15px; 
        display: inline-block; 
    }
    
    .lang-btn {
        background: transparent; 
        border: none; 
        font-weight: 700; 
        font-size: 14px; 
        cursor: pointer; 
        padding: 5px 0; 
        display: flex; 
        align-items: center; 
        gap: 5px;
        text-transform: uppercase;
    }
    
    .lang-menu {
        display: none; 
        position: absolute; 
        top: 100%; 
        right: 0;
        background: #fff; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        min-width: 120px; 
        border-radius: 5px; 
        padding: 10px 0; 
        z-index: 1000;
    }
    
    .lang-selector:hover .lang-menu { 
        display: block; 
    }
    
    .lang-menu a { 
        display: block; 
        padding: 8px 15px; 
        color: #333 !important; 
        font-size: 13px; 
        text-decoration: none; 
        transition: 0.3s;
    }
    
    .lang-menu a:hover { 
        background: #f9f9f9; 
        color: #d90a2c !important; 
        padding-left: 20px; 
    }
    
    .lang-menu img { 
        margin-right: 8px; 
        vertical-align: middle; 
    }
    
    /* Links activos */
    .main-menu ul li.active > a {
        color: var(--color-theme) !important;
    }
    
    /* ==============================================
       RESPONSIVE
       ============================================== */
    @media (max-width: 1199px) {
        /* Asegurar header visible en móvil */
        header.header-interno {
            display: block !important;
            position: fixed !important;
            top: 0 !important;
            width: 100% !important;
        }
        
        /* Ocultar WhatsApp en móvil */
        .header-right .theme-btn {
            display: none !important;
        }
        
        /* Mostrar selector idioma en móvil */
        .lang-selector {
            display: inline-block !important;
            margin-right: 10px;
        }
    }

    @media (min-width: 1200px) {
        /* Mostrar WhatsApp en desktop */
        .header-right .theme-btn {
            display: inline-block !important;
        }
    }
</style>

<div class="fix-area">
    <div class="offcanvas__info">
        <div class="offcanvas__wrapper">
            <div class="offcanvas__content">
                <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                    <div class="offcanvas__logo">
                        <a href="<?php echo BASE_URL; ?>/">
                            <img src="<?php echo BASE_URL; ?>/assets/img/logo/black-logo.png" alt="logo-img">
                        </a>
                    </div>
                    <div class="offcanvas__close">
                        <button><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="mobile-menu fix mb-3"></div>
                <div class="offcanvas__contact">
                    <h4><?php echo $lang['nav_contacto'] ?? 'Contacto'; ?></h4>
                    <ul>
                        <li class="d-flex align-items-center">
                            <div class="offcanvas__contact-icon">
                                <i class="fal fa-map-marker-alt"></i>
                            </div>
                            <div class="offcanvas__contact-text">
                                <a target="_blank" href="#"><?php echo $lang['contact_address'] ?? 'Baranoa, Atlántico, Colombia'; ?></a>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <div class="offcanvas__contact-icon mr-15">
                                <i class="far fa-phone"></i>
                            </div>
                            <div class="offcanvas__contact-text">
                                <a href="tel:<?php echo $lang['header_telefono'] ?? '+57'; ?>">
                                    <?php echo $lang['header_telefono'] ?? '+57'; ?>
                                </a>
                            </div>
                        </li>
                    </ul>
                    
                    <div class="mt-4">
                        <a href="https://wa.me/<?php echo urlencode($lang['header_telefono'] ?? '+57'); ?>" target="_blank" class="btn btn-success w-100 py-3 rounded fw-bold">
                            <i class="fab fa-whatsapp me-2"></i> WhatsApp
                        </a>
                    </div>
                    
                    <div class="social-icon d-flex align-items-center mt-4">
                        <a href="https://www.facebook.com/bandadebaranoa1" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/bandadebaranoa1" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.youtube.com/@BandadeBaranoaOficial" target="_blank"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.instagram.com/labandadebaranoa/" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas__overlay"></div>

<header id="header-sticky" class="<?php echo $clase_header; ?>">
    <div class="container-fluid">
        <div class="mega-menu-wrapper">
            <div class="header-main">
                
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>/" class="header-logo">
                        <img src="<?php echo BASE_URL; ?>/assets/img/logo/black-logo.png" alt="logo-black">
                    </a>
                    <a href="<?php echo BASE_URL; ?>/" class="header-logo-2">
                        <img src="<?php echo BASE_URL; ?>/assets/img/logo/white-logo.png" alt="logo-white">
                    </a>
                </div>
                
                <div class="mean__menu-wrapper">
                    <div class="main-menu">
                        <nav id="mobile-menu">
                            <ul>
                                <li class="<?php echo ($page == 'home') ? 'active' : ''; ?>">
                                    <a href="<?php echo BASE_URL; ?>/"><?php echo $lang['nav_inicio'] ?? 'Inicio'; ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL; ?>/#quienes-somos"><?php echo $lang['nav_quienes_somos'] ?? 'Nosotros'; ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL; ?>/#experiencias"><?php echo $lang['nav_experiencias'] ?? 'Experiencias'; ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL; ?>/#corporativo"><?php echo $lang['nav_corporativo'] ?? 'Corporativo'; ?></a>
                                </li>
                                <li class="<?php echo ($page == 'eventos') ? 'active' : ''; ?>">
                                    <a href="<?php echo BASE_URL; ?>/eventos"><?php echo $lang['nav_eventos'] ?? 'Eventos'; ?></a>
                                </li>
                                <li class="<?php echo ($page == 'noticias') ? 'active' : ''; ?>">
                                    <a href="<?php echo BASE_URL; ?>/noticias"><?php echo $lang['nav_noticias'] ?? 'Noticias'; ?></a>
                                </li>
                                <li class="<?php echo ($page == 'galeria') ? 'active' : ''; ?>">
                                    <a href="<?php echo BASE_URL; ?>/galeria"><?php echo $lang['nav_galeria'] ?? 'Galería'; ?></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                
                <div class="header-right d-flex justify-content-end align-items-center">
                    
                    <div class="lang-selector">
                        <button class="lang-btn">
                            <i class="fa-solid fa-globe"></i> <?php echo $label_idioma; ?> <i class="fa-solid fa-angle-down"></i>
                        </button>
                        <div class="lang-menu">
                            <a href="<?php echo getUrlWithLang('es'); ?>">
                                <span>🇪🇸</span>  Español
                            </a>
                            <a href="<?php echo getUrlWithLang('en'); ?>">
                                <span>🇺🇸</span>  English
                            </a>
                        </div>
                    </div>

                    <a href="https://wa.me/<?php echo urlencode($lang['header_telefono'] ?? '+57'); ?>" target="_blank" class="theme-btn">
                        WhatsApp <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    
                    <div class="header__hamburger d-xl-none my-auto">
                        <div class="sidebar__toggle">
                            <i class="fas fa-bars"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const header = document.getElementById('header-sticky');
    
    if (header && header.classList.contains('header-interno')) {
        header.style.cssText = `
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            background-color: #ffffff !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1) !important;
            z-index: 9999 !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
            transform: translateY(0) !important;
        `;
    }
});
</script>