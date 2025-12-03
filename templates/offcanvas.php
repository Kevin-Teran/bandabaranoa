<?php
/**
 * @file offcanvas.php
 * @route /templates/offcanvas.php
 * @description Plantilla para el menú lateral (offcanvas) y de contacto.
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */
 
global $lang;
?>
<div class="fix-area">
    <div class="offcanvas__info">
        <div class="offcanvas__wrapper">
            <div class="offcanvas__content">
                <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                    <div class="offcanvas__logo">
                        <a href="<?= Router::url('home') ?>">
                            <img src="<?= Router::asset('img/logo/black-logo.png') ?>" alt="logo-img">
                        </a>
                    </div>
                    <div class="offcanvas__close">
                        <button>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <p class="text d-none d-xl-block">
                    <?= $lang['footer_desc'] ?? 'Somos un tesoro cultural, orquesta Fusión y Sinfónica. Conoce nuestra historia y fundación.' ?>
                </p>
                
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
                                <i class="fal fa-envelope"></i>
                            </div>
                            <div class="offcanvas__contact-text">
                                <a href="mailto:<?= $lang['contact_email'] ?? 'info@bandadebaranoa.com' ?>">
                                    <?= $lang['contact_email'] ?? 'info@bandadebaranoa.com' ?>
                                </a>
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
                    
                    <div class="header-button mt-4">
                        <a href="https://wa.me/<?= urlencode($lang['header_telefono'] ?? '') ?>" target="_blank" class="theme-btn text-center"> 
                            <?= $lang['nav_comprar'] ?? 'Contáctanos' ?> <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                    
                    <div class="social-icon d-flex align-items-center mt-4">
                        <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="https://youtube.com" target="_blank"><i class="fab fa-youtube"></i></a>
                        <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas__overlay"></div>