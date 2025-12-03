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
?>
<footer class="footer-section fix" style="background-color: #000000; color: #ffffff;">
    <div class="container">
        <div class="footer-widget-wrapper-new">
            <div class="row">
                
                <div class="col-xl-4 col-lg-5 col-md-8 col-sm-6 wow fadeInUp" data-wow-delay=".2s">
                    <div class="single-widget-items text-center" style="background: transparent !important; box-shadow: none !important; padding: 0 !important;">
                        <div class="widget-head">
                            <a href="<?php echo BASE_URL; ?>/">
                                <img src="<?php echo BASE_URL; ?>/assets/img/logo/white-log.png" alt="<?php echo $lang['meta_titulo'] ?? 'Banda de Baranoa'; ?>" style="max-width: 180px; height: auto;">
                            </a>
                        </div>
                        <div class="footer-content">
                            <p style="color: #000000; margin: 20px; margin-bottom: 25px; font-size: 15px; line-height: 1.6;">
                                <?php echo $lang['footer_desc'] ?? 'Fundación Banda de Baranoa.<br>Transformando vidas a través de la música y la cultura desde 1995.'; ?>
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
                               <?php echo $lang['footer_links'] ?? 'Explorar'; ?>
                           </h4>
                        </div>
                        <ul class="list-items">
                            <li><a href="<?php echo BASE_URL; ?>/#home-hero" style="color: #cccccc;"><?php echo $lang['nav_inicio'] ?? 'Inicio'; ?></a></li>
                            <li><a href="<?php echo BASE_URL; ?>/#quienes-somos" style="color: #cccccc;"><?php echo $lang['nav_quienes_somos'] ?? 'Nosotros'; ?></a></li>
                            <li><a href="<?php echo BASE_URL; ?>/noticias" style="color: #cccccc;"><?php echo $lang['nav_noticias'] ?? 'Noticias'; ?></a></li>
                            <li><a href="<?php echo BASE_URL; ?>/eventos" style="color: #cccccc;"><?php echo $lang['nav_eventos'] ?? 'Eventos'; ?></a></li>
                            <li><a href="<?php echo BASE_URL; ?>/galeria" style="color: #cccccc;"><?php echo $lang['nav_galeria'] ?? 'Galería'; ?></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ps-lg-5 wow fadeInUp" data-wow-delay=".6s">
                    <div class="single-widget-items" style="background: transparent !important; box-shadow: none !important; padding: 0 !important;">
                        <div class="widget-head">
                           <h4 style="color: var(--color-theme); font-weight: 700;">
                               <?php echo $lang['corp_sub'] ?? 'Servicios'; ?>
                           </h4>
                        </div>
                        <ul class="list-items">
                            <li><a href="<?php echo BASE_URL; ?>/#experiencias" style="color: #cccccc;"><?php echo $lang['nav_experiencias'] ?? 'Experiencias'; ?></a></li>
                            <li><a href="<?php echo BASE_URL; ?>/#corporativo" style="color: #cccccc;"><?php echo $lang['nav_corporativo'] ?? 'Corporativo'; ?></a></li>
                            <li><a href="<?php echo BASE_URL; ?>/#concha-acustica" style="color: #cccccc;"><?php echo $lang['nav_concha'] ?? 'Concha Acústica'; ?></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ps-xl-5 wow fadeInUp" data-wow-delay=".6s">
                    <div class="single-widget-items" style="background: transparent !important; box-shadow: none !important; padding: 0 !important;">
                        <div class="widget-head">
                           <h4 style="color: var(--color-theme); font-weight: 700;">
                               <?php echo $lang['nav_contacto'] ?? 'Contacto'; ?>
                           </h4>
                        </div>
                        <div class="contact-info ">
                            <div class="contact-items d-flex align-items-center justify-content-center">
                                <div class="icon" style="color: var(--color-theme) !important;">
                                    <i class="fa-regular fa-location-dot"></i>
                                </div>
                                <div class="content">
                                    <h6 style="color: #cccccc; font-weight: 400;">
                                        <?php echo $lang['contact_address'] ?? 'Baranoa, Atlántico Colombia'; ?>
                                    </h6>
                                </div>
                            </div>

                            <div class="contact-items">
                               <div class="icon" style="color: var(--color-theme) !important;">
                                  <i class="fa-solid fa-phone"></i>
                               </div>
                               <div class="content">
                                   <h6>
                                       <a href="tel:<?php echo $lang['header_telefono'] ?? '+57'; ?>" style="color: #cccccc; font-weight: 400;">
                                           <?php echo $lang['header_telefono'] ?? '+57'; ?>
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
                    <p style="color: #888; font-size: 14px;">© <?php echo date('Y'); ?> <span style="color: var(--color-theme); font-weight: 600;">Banda de Baranoa</span>. 
                        <?php echo $lang['footer_rights'] ?? 'Todos los derechos reservados.'; ?>
                    </p>
                    <p style="color: #888; font-size: 14px;">
                        <?php echo $lang['footer_dev'] ?? 'Desarrollado por'; ?> 
                        <span style="color: #fff; font-weight: bold;">Kevin Mariano</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="<?php echo BASE_URL; ?>/assets/js/jquery-3.7.1.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/viewport.jquery.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/jquery.nice-select.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/jquery.waypoints.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/jquery.counterup.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/swiper-bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/jquery.meanmenu.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/jquery.magnific-popup.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/wow.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>

</body>
</html>