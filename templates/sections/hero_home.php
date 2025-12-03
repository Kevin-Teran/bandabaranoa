<?php
/**
 * @file hero_home.php
 * @route /templates/sections/hero_home.php
 * @description Slider Principal con 3 estrategias: Identidad, Servicios y Eventos.
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

global $lang;
?>
<section class="hero-section hero-3" id="home-hero">
    <div class="swiper hero-slider-3">
        <div class="swiper-wrapper">
            
            <div class="swiper-slide">
                <div class="hero-image bg-cover" style="background-image: url('<?php echo BASE_URL; ?>/assets/img/hero/01.jpg');"></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="hero-content">
                                <div class="sub-title" data-animation="fadeInUp" data-delay="1.2s">
                                    <?php echo $lang['hero_s1_sub'] ?? 'Quiénes Somos'; ?>
                                </div>
                                <h1 data-animation="fadeInUp" data-delay="1.4s">
                                    <?php echo $lang['hero_s1_title'] ?? 'Tesoro Cultural de la Música Colombiana'; ?>
                                </h1>
                                <p data-animation="fadeInUp" data-delay="1.6s">
                                    <?php echo $lang['hero_s1_desc'] ?? 'Más de 30 años fusionando la tradición sinfónica con la alegría del caribe.'; ?>
                                </p>
                                <div class="about-button" data-animation="fadeInUp" data-delay="1.8s">
                                    <a href="<?php echo BASE_URL; ?>/#quienes-somos" class="theme-btn">
                                        <?php echo $lang['hero_s1_btn'] ?? 'Conoce Nuestra Historia'; ?>
                                        <i class="fa-sharp fa-regular fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="hero-image bg-cover" style="background-image: url('<?php echo BASE_URL; ?>/assets/img/hero/02.jpg');"></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="hero-content">
                                <div class="sub-title" data-animation="fadeInUp" data-delay="1.2s">
                                    <?php echo $lang['hero_s2_sub'] ?? 'Experiencias'; ?>
                                </div>
                                <h1 data-animation="fadeInUp" data-delay="1.4s">
                                    <?php echo $lang['hero_s2_title'] ?? 'Vive la Música Desde Adentro'; ?>
                                </h1>
                                <p data-animation="fadeInUp" data-delay="1.6s">
                                    <?php echo $lang['hero_s2_desc'] ?? 'Sé músico por un día, graba en nuestro estudio o lleva la banda a tu evento corporativo.'; ?>
                                </p>
                                <div class="about-button" data-animation="fadeInUp" data-delay="1.8s">
                                    <a href="<?php echo BASE_URL; ?>/#experiencias" class="theme-btn">
                                        <?php echo $lang['hero_s2_btn1'] ?? 'Ver Experiencias'; ?>
                                        <i class="fa-sharp fa-regular fa-arrow-right"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/#corporativo" class="theme-btn style-2">
                                        <?php echo $lang['hero_s2_btn2'] ?? 'Planes Corporativos'; ?>
                                        <i class="fa-sharp fa-regular fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="hero-image bg-cover" style="background-image: url('<?php echo BASE_URL; ?>/assets/img/hero/03.png');"></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="hero-content">
                                <div class="sub-title" data-animation="fadeInUp" data-delay="1.2s">
                                    <?php echo $lang['hero_s3_sub'] ?? 'Eventos'; ?>
                                </div>
                                <h1 data-animation="fadeInUp" data-delay="1.4s">
                                    <?php echo $lang['hero_s3_title'] ?? 'Próxima Temporada de Conciertos'; ?>
                                </h1>
                                <p data-animation="fadeInUp" data-delay="1.6s">
                                    <?php echo $lang['hero_s3_desc'] ?? 'No te pierdas nuestras presentaciones en el Carnaval y festivales nacionales.'; ?>
                                </p>
                                <div class="about-button" data-animation="fadeInUp" data-delay="1.8s">
                                    <a href="<?php echo BASE_URL; ?>/eventos" class="theme-btn">
                                        <?php echo $lang['hero_s3_btn'] ?? 'Ver Calendario'; ?>
                                        <i class="fa-sharp fa-regular fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="swiper-dot">
            <div class="dot2"></div>
        </div>
    </div>
</section>