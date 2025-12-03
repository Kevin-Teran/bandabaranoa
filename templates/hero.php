<?php
/**
 * @file hero.php
 * @route /templates/sections/hero.php
 * @description Plantilla para la sección del Hero Slider (One Page).
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */
global $lang;
?>
<section class="hero-section">
    <div class="swiper hero-slider-3">
        <div class="swiper-wrapper">
            
            <div class="swiper-slide">
                <div class="hero-3" style="background-image: url(<?php echo BASE_URL; ?>/assets/img/hero/03.jpg);">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="hero-content">
                                    <div class="sub-title">
                                        <?php echo $lang['hero_subtitulo']; ?>
                                    </div>
                                    <h1>
                                        <?php echo $lang['hero_titulo']; ?>
                                    </h1>
                                    <a href="#quienes-somos" class="theme-btn" style="margin-top: 30px;">
                                        <?php echo $lang['hero_cta']; ?> <i class="fa-sharp fa-regular fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="hero-3" style="background-image: url(<?php echo BASE_URL; ?>/assets/img/hero/01.jpg);">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="hero-content">
                                    <div class="sub-title">
                                        <?php echo $lang['quienes_somos_item_2_titulo']; ?>
                                    </div>
                                    <h1>
                                        Música para <br> Todos los Eventos
                                    </h1>
                                    <a href="#corporativo" class="theme-btn" style="margin-top: 30px;">
                                        <?php echo $lang['header_cotizar']; ?> <i class="fa-sharp fa-regular fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="hero-3" style="background-image: url(<?php echo BASE_URL; ?>/assets/img/hero/02.jpg);">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="hero-content">
                                    <div class="sub-title">
                                        <?php echo $lang['concha_tag']; ?>
                                    </div>
                                    <h1>
                                        <?php echo $lang['concha_titulo']; ?>
                                    </h1>
                                    <a href="#concha-acustica" class="theme-btn" style="margin-top: 30px;">
                                        <?php echo $lang['concha_cta_1']; ?> <i class="fa-solid fa-vr-cardboard"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="swiper-dot-3">
            <div class="dot"></div>
        </div>
    </div>
</section>