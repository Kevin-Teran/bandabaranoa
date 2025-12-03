<?php
/**
 * @file concha_acustica_home.php
 * @route /templates/sections/concha_acustica_home.php
 * @description Sección Concha Acústica traducible.
 * @author Kevin Mariano
 * @version 1.0.3
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

global $lang;
?>
<section class="video-section-2 section-padding bg-cover" id="concha-acustica" style="background-image: url('<?php echo BASE_URL; ?>/assets/img/Concha-acustica.png'); position: relative;">
    
    <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, rgba(0,0,0,0.5), rgba(0,0,0,0.8));"></div>

    <div class="container" style="position: relative; z-index: 2;">
        <div class="video-wrapper text-center">
            
            <div class="section-title mb-4">
                <span class="sub-title wow fadeInUp">
                    <?php echo $lang['concha_sub'] ?? 'ESCENARIO DE TALLA INTERNACIONAL'; ?>
                </span>
                <h2 class="wow fadeInUp wow text-white" data-wow-delay=".3s">
                    <?php echo $lang['concha_title'] ?? 'Concha Acústica "La Majestuosa"'; ?>
                </h2>
            </div>

            <div class="row justify-content-center mb-5 wow fadeInUp" data-wow-delay=".4s">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-around align-items-center p-3" style="border-top: 1px solid rgba(255,255,255,0.3); border-bottom: 1px solid rgba(255,255,255,0.3);">
                        <div class="text-white text-center">
                            <i class="fa-solid fa-users fa-2x mb-2 text-theme"></i>
                            <h4 class="text-white m-0" style="font-weight: 700;">22.000</h4>
                            <small style="opacity: 0.8;"><?php echo $lang['concha_stat1'] ?? 'Espectadores'; ?></small>
                        </div>
                        <div class="text-white text-center" style="border-left: 1px solid rgba(255,255,255,0.2); border-right: 1px solid rgba(255,255,255,0.2); padding: 0 20px;">
                            <i class="fa-solid fa-music fa-2x mb-2 text-theme"></i>
                            <h4 class="text-white m-0" style="font-weight: 700;">400</h4>
                            <small style="opacity: 0.8;"><?php echo $lang['concha_stat2'] ?? 'Artistas en Escena'; ?></small>
                        </div>
                        <div class="text-white text-center">
                            <i class="fa-solid fa-wifi fa-2x mb-2 text-theme"></i>
                            <h4 class="text-white m-0" style="font-weight: 700;">High Tech</h4>
                            <small style="opacity: 0.8;"><?php echo $lang['concha_stat3'] ?? 'Luces & Sonido'; ?></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="video-btn-wrapper mb-4 wow fadeInUp" data-wow-delay=".5s">
                <a href="https://www.youtube.com/watch?v=_vhue588RfI" class="video-popup ripple-btn" style="width: 90px; height: 90px; line-height: 90px; background: var(--color-theme); color: #fff; border-radius: 50%; display: inline-block; font-size: 28px; text-align: center; transition: all 0.3s;">
                    <i class="fa-solid fa-play"></i>
                </a>
                <p class="text-white mt-2" style="font-size: 0.9rem; opacity: 0.8;">
                    <?php echo $lang['concha_btn_video'] ?? 'Ver Show de Inauguración'; ?>
                </p>
            </div>

            <p class="text-white mb-5 wow fadeInUp" data-wow-delay=".7s" style="font-size: 1.1rem; max-width: 750px; margin: 0 auto; line-height: 1.8;">
                <?php echo $lang['concha_desc'] ?? 'Un hito arquitectónico en el Caribe. Diseñada para grandes formatos sinfónicos y festivales masivos. Disponible para alquiler de eventos corporativos, conciertos y espectáculos culturales.'; ?>
            </p>

            <div class="cta-buttons mt-4 wow fadeInUp" data-wow-delay=".9s">
                <a href="https://wa.me/<?php echo urlencode($lang['header_telefono'] ?? '+57'); ?>?text=Hola,%20me%20interesa%20cotizar%20la%20Concha%20Acústica" target="_blank" class="theme-btn" style="background: var(--color-theme); color: #fff; border: none; font-weight: 600;">
                    <?php echo $lang['concha_btn_cta'] ?? 'Cotizar Escenario'; ?> 
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>