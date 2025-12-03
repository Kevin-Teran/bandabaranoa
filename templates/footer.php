<?php
/**
 * @file footer.php
 * @route /templates/footer.php
 * @description Footer traducible.
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */
 
global $lang;

$url_inicio = Router::url('');
?>

<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    .footer-section {
        margin-top: auto;
    }
</style>

<footer class="footer-section fix" style="background-color: #000000; color: #ffffff;">
    <div class="container">
        <div class="footer-widget-wrapper-new">
            <div class="row">
                
                <div class="col-xl-4 col-lg-5 col-md-8 col-sm-6 wow fadeInUp" data-wow-delay=".2s">
                    <div class="single-widget-items text-center" style="background: transparent !important; box-shadow: none !important; padding: 0 !important;">
                        <div class="widget-head">
                            <a href="<?= $url_inicio ?>">
                                <img src="<?= Router::asset('img/logo/white-log.png') ?>" alt="<?= $lang['meta_titulo'] ?? 'Banda de Baranoa' ?>" style="max-width: 180px; height: auto;">
                            </a>
                        </div>
                        <div class="footer-content">
                            <p style="color: #000000; margin: 20px; margin-bottom: 25px; font-size: 15px; line-height: 1.6;">
                                <?= $lang['footer_desc'] ?? 'Fundación Banda de Baranoa.<br>Transformando vidas a través de la música y la cultura desde 1995.' ?>
                            </p>
                            
                            <div class="social-icon d-flex align-items-center justify-content-center">
                                <a href="https://www.facebook.com/bandadebaranoa1" target="_blank" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://x.com/bandadebaranoa1" target="_blank" class="social-btn"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.youtube.com/@BandadeBaranoaOficial" target="_blank" class="social-btn"><i class="fab fa-youtube"></i></a>
                                <a href="https://www.instagram.com/labandadebaranoa/" target="_blank" class="social-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 ps-lg-5 wow fadeInUp" data-wow-delay=".4s">
                    <div class="single-widget-items" style="background: transparent !important; box-shadow: none !important; padding: 0 !important;">
                        <div class="widget-head">
                           <h4 style="color: var(--color-theme); font-weight: 700;">
                               <?= $lang['footer_links'] ?? 'Explorar' ?>
                           </h4>
                        </div>
                        <ul class="list-items">
                            <li><a href="<?= $url_inicio ?>" style="color: #cccccc;"><?= $lang['nav_inicio'] ?? 'Inicio' ?></a></li>
                            <li><a href="<?= $url_inicio ?>#quienes-somos" style="color: #cccccc;"><?= $lang['nav_quienes_somos'] ?? 'Nosotros' ?></a></li>
                            <li><a href="<?= Router::url('noticias') ?>" style="color: #cccccc;"><?= $lang['nav_noticias'] ?? 'Noticias' ?></a></li>
                            <li><a href="<?= Router::url('eventos') ?>" style="color: #cccccc;"><?= $lang['nav_eventos'] ?? 'Eventos' ?></a></li>
                            <li><a href="<?= Router::url('galeria') ?>" style="color: #cccccc;"><?= $lang['nav_galeria'] ?? 'Galería' ?></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ps-lg-5 wow fadeInUp" data-wow-delay=".6s">
                    <div class="single-widget-items" style="background: transparent !important; box-shadow: none !important; padding: 0 !important;">
                        <div class="widget-head">
                           <h4 style="color: var(--color-theme); font-weight: 700;">
                               <?= $lang['corp_sub'] ?? 'Servicios' ?>
                           </h4>
                        </div>
                        <ul class="list-items">
                            <li><a href="<?= $url_inicio ?>#experiencias" style="color: #cccccc;"><?= $lang['nav_experiencias'] ?? 'Experiencias' ?></a></li>
                            <li><a href="<?= $url_inicio ?>#corporativo" style="color: #cccccc;"><?= $lang['nav_corporativo'] ?? 'Corporativo' ?></a></li>
                            <li><a href="<?= $url_inicio ?>#concha-acustica" style="color: #cccccc;"><?= $lang['nav_concha'] ?? 'Concha Acústica' ?></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ps-xl-5 wow fadeInUp" data-wow-delay=".6s">
                    <div class="single-widget-items" style="background: transparent !important; box-shadow: none !important; padding: 0 !important;">
                        <div class="widget-head">
                           <h4 style="color: var(--color-theme); font-weight: 700;">
                               <?= $lang['nav_contacto'] ?? 'Contacto' ?>
                           </h4>
                        </div>
                        <div class="contact-info ">
                            <div class="contact-items d-flex align-items-center justify-content-center">
                                <div class="icon" style="color: var(--color-theme) !important;">
                                    <i class="fa-regular fa-location-dot"></i>
                                </div>
                                <div class="content">
                                    <h6 style="color: #cccccc; font-weight: 400;">
                                        <?= $lang['contact_address'] ?? 'Baranoa, Atlántico Colombia' ?>
                                    </h6>
                                </div>
                            </div>

                            <div class="contact-items">
                               <div class="icon" style="color: var(--color-theme) !important;">
                                  <i class="fa-solid fa-phone"></i>
                               </div>
                               <div class="content">
                                   <h6>
                                       <a href="tel:<?= $lang['header_telefono'] ?? '+57' ?>" style="color: #cccccc; font-weight: 400;">
                                           <?= $lang['header_telefono'] ?? '+57' ?>
                                       </a>
                                   </h6>
                               </div>
                           </div>
                        </div>
                    </div>
                </div>

             </div>
        </div>
        
        <div class="footer-bottom" style="border-top: 1px solid #222; background-color: #0a0a0a;">
            <div class="container">
                <div class="footer-bottom-wrapper d-flex justify-content-between align-items-center flex-wrap">
                    <p style="color: #888; font-size: 14px;">© <?= date('Y') ?> <span style="color: var(--color-theme); font-weight: 600;">Banda de Baranoa</span>. 
                        <?= $lang['footer_rights'] ?? 'Todos los derechos reservados.' ?>
                    </p>
                    <p style="color: #888; font-size: 14px;">
                        <?= $lang['footer_dev'] ?? 'Desarrollado por' ?> 
                        <span style="color: #fff; font-weight: bold;">Kevin Mariano</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts Globales -->
<script src="<?= Router::asset('js/jquery-3.7.1.min.js') ?>"></script>
<script src="<?= Router::asset('js/viewport.jquery.js') ?>"></script>
<script src="<?= Router::asset('js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= Router::asset('js/jquery.nice-select.min.js') ?>"></script>
<script src="<?= Router::asset('js/jquery.waypoints.js') ?>"></script>
<script src="<?= Router::asset('js/jquery.counterup.min.js') ?>"></script>
<script src="<?= Router::asset('js/swiper-bundle.min.js') ?>"></script>
<script src="<?= Router::asset('js/jquery.meanmenu.min.js') ?>"></script>
<script src="<?= Router::asset('js/bootstrap-datepicker.js') ?>"></script>
<script src="<?= Router::asset('js/jquery.magnific-popup.min.js') ?>"></script>
<script src="<?= Router::asset('js/wow.min.js') ?>"></script>
<script src="<?= Router::asset('js/main.js') ?>"></script>

</body>
</html>