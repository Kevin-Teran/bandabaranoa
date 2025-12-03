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

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script_dir = dirname($_SERVER['SCRIPT_NAME']);
$script_dir = str_replace('\\', '/', $script_dir); 

if ($script_dir !== '/' && strpos($request_uri, $script_dir) === 0) {
    $clean_route = substr($request_uri, strlen($script_dir));
} else {
    $clean_route = $request_uri;
}
$clean_route = trim($clean_route, '/');

$es_home = ($clean_route === '' || $clean_route === 'home' || $clean_route === 'index.php');

if ($es_home) {
    $clase_header = 'header-1 header-3 header-transparent';
} else {
    $clase_header = 'header-1 header-3 header-interno';
}

$codigo_idioma = $_SESSION['lang'] ?? 'es';
$label_idioma = ($codigo_idioma == 'en') ? 'EN' : 'ES';

/**
 * Genera la URL para cambiar idioma
 */
function getUrlWithLang($newLang) {
    $current_url = $_SERVER['REQUEST_URI'];
    $parsed = parse_url($current_url);
    $path = $parsed['path'];
    $query = [];
    
    if (isset($parsed['query'])) {
        parse_str($parsed['query'], $query);
    }
    
    $query['lang'] = $newLang;
    return $path . '?' . http_build_query($query);
}

function isActive($routeToCheck, $currentRoute) {
    if ($routeToCheck === '' && ($currentRoute === '' || $currentRoute === 'home')) return 'active';
    if ($routeToCheck !== '' && strpos($currentRoute, $routeToCheck) === 0) return 'active';
    return '';
}

$url_inicio = Router::url(''); 
?>

<script>
    if (window.history.replaceState) {
        const url = new URL(window.location.href);
        if (url.searchParams.has('lang')) {
            url.searchParams.delete('lang');
            window.history.replaceState(null, '', url.toString());
        }
    }
</script>

<style>
    /* Estilos Header Interno (Fondo Blanco) */
    header.header-interno {
        background-color: #ffffff !important;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1) !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        z-index: 9999 !important;
    }
    
    header.header-interno .main-menu ul li a,
    header.header-interno .lang-btn,
    header.header-interno .sidebar__toggle i {
        color: #000000 !important;
        font-weight: 700 !important;
    }

    /* Logo Switch */
    header.header-interno .logo .header-logo { display: block !important; }
    header.header-interno .logo .header-logo-2 { display: none !important; }
    
    header.header-interno .theme-btn {
        background-color: #d90a2c !important;
        color: #ffffff !important;
        border-color: #d90a2c !important;
    }

    /* Estilos Header Home (Transparente inicial) */
    header.header-transparent:not(.sticky) {
        background-color: transparent !important;
        box-shadow: none !important;
    }
    header.header-transparent:not(.sticky) .main-menu ul li a,
    header.header-transparent:not(.sticky) .lang-btn,
    header.header-transparent:not(.sticky) .sidebar__toggle i {
        color: #ffffff !important;
    }
    header.header-transparent:not(.sticky) .logo .header-logo { display: none !important; }
    header.header-transparent:not(.sticky) .logo .header-logo-2 { display: block !important; }

    /* Estilos Header Home (Sticky al bajar) */
    header.header-transparent.sticky {
        background-color: #ffffff !important;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1) !important;
    }
    header.header-transparent.sticky .main-menu ul li a,
    header.header-transparent.sticky .lang-btn,
    header.header-transparent.sticky .sidebar__toggle i {
        color: #000000 !important;
    }
    header.header-transparent.sticky .logo .header-logo { display: block !important; }
    header.header-transparent.sticky .logo .header-logo-2 { display: none !important; }

    /* Menú Móvil */
    .mean-container .mean-nav { background-color: #ffffff !important; }
    .mean-container .mean-nav ul li a { color: #333 !important; border-top: 1px solid #eee !important; }
    
    /* Selector Idioma */
    .lang-selector { position: relative; margin-right: 15px; display: inline-block; }
    .lang-btn { background: transparent; border: none; font-weight: 700; font-size: 14px; cursor: pointer; text-transform: uppercase; }
    .lang-menu { display: none; position: absolute; top: 100%; right: 0; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); min-width: 120px; z-index: 1000; }
    .lang-selector:hover .lang-menu { display: block; }
    .lang-menu a { display: block; padding: 8px 15px; color: #333 !important; font-size: 13px; text-decoration: none; }
    .lang-menu a:hover { background: #f9f9f9; color: #d90a2c !important; }

    /* Active State */
    .main-menu ul li.active > a { color: #d90a2c !important; }

    @media (max-width: 1199px) {
        header.header-interno { position: fixed !important; }
        .header-right .theme-btn { display: none !important; }
    }
</style>

<div class="fix-area">
    <div class="offcanvas__info">
        <div class="offcanvas__wrapper">
            <div class="offcanvas__content">
                <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                    <div class="offcanvas__logo">
                        <a href="<?= $url_inicio ?>">
                            <img src="<?= Router::asset('img/logo/black-logo.png') ?>" alt="logo-img">
                        </a>
                    </div>
                    <div class="offcanvas__close">
                        <button><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="mobile-menu fix mb-3"></div>
                
                <div class="offcanvas__contact">
                    <h4><?= $lang['nav_contacto'] ?? 'Contacto' ?></h4>
                    <ul>
                        <li class="d-flex align-items-center">
                            <div class="offcanvas__contact-icon">
                                <i class="fal fa-map-marker-alt"></i>
                            </div>
                            <div class="offcanvas__contact-text">
                                <a target="_blank" href="#"><?= $lang['contact_address'] ?? 'Baranoa, Atlántico' ?></a>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <div class="offcanvas__contact-icon mr-15">
                                <i class="far fa-phone"></i>
                            </div>
                            <div class="offcanvas__contact-text">
                                <a href="tel:<?= $lang['header_telefono'] ?? '+57' ?>">
                                    <?= $lang['header_telefono'] ?? '+57' ?>
                                </a>
                            </div>
                        </li>
                    </ul>
                    
                    <div class="mt-4">
                        <a href="https://wa.me/<?= urlencode($lang['header_telefono'] ?? '') ?>" target="_blank" class="btn btn-success w-100 py-3 rounded fw-bold">
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

<header id="header-sticky" class="<?= $clase_header ?>">
    <div class="container-fluid">
        <div class="mega-menu-wrapper">
            <div class="header-main">
                <div class="logo">
                    <a href="<?= $url_inicio ?>" class="header-logo">
                        <img src="<?= Router::asset('img/logo/black-logo.png') ?>" alt="logo-black">
                    </a>
                    <a href="<?= $url_inicio ?>" class="header-logo-2">
                        <img src="<?= Router::asset('img/logo/white-logo.png') ?>" alt="logo-white">
                    </a>
                </div>
                
                <div class="mean__menu-wrapper">
                    <div class="main-menu">
                        <nav id="mobile-menu">
                            <ul>
                                <li class="<?= isActive('', $clean_route) ?>">
                                    <a href="<?= $url_inicio ?>"><?= $lang['nav_inicio'] ?? 'Inicio' ?></a>
                                </li>
                                <li>
                                    <a href="<?= $es_home ? '#quienes-somos' : $url_inicio . '#quienes-somos' ?>">
                                        <?= $lang['nav_quienes_somos'] ?? 'Nosotros' ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $es_home ? '#experiencias' : $url_inicio . '#experiencias' ?>">
                                        <?= $lang['nav_experiencias'] ?? 'Experiencias' ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $es_home ? '#corporativo' : $url_inicio . '#corporativo' ?>">
                                        <?= $lang['nav_corporativo'] ?? 'Corporativo' ?>
                                    </a>
                                </li>
                                <li class="<?= isActive('eventos', $clean_route) ?>">
                                    <a href="<?= Router::url('eventos') ?>"><?= $lang['nav_eventos'] ?? 'Eventos' ?></a>
                                </li>
                                <li class="<?= isActive('noticias', $clean_route) ?>">
                                    <a href="<?= Router::url('noticias') ?>"><?= $lang['nav_noticias'] ?? 'Noticias' ?></a>
                                </li>
                                <li class="<?= isActive('galeria', $clean_route) ?>">
                                    <a href="<?= Router::url('galeria') ?>"><?= $lang['nav_galeria'] ?? 'Galería' ?></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                
                <div class="header-right d-flex justify-content-end align-items-center">
                    <div class="lang-selector">
                        <button class="lang-btn">
                            <i class="fa-solid fa-globe"></i> <?= $label_idioma ?> <i class="fa-solid fa-angle-down"></i>
                        </button>
                        <div class="lang-menu">
                            <a href="<?= getUrlWithLang('es') ?>"><span>🇪🇸</span> Español</a>
                            <a href="<?= getUrlWithLang('en') ?>"><span>🇺🇸</span> English</a>
                        </div>
                    </div>

                    <a href="https://wa.me/<?= urlencode($lang['header_telefono'] ?? '') ?>" target="_blank" class="theme-btn">
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