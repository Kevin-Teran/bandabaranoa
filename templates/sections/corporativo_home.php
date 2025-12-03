<?php
/**
 * @file corporativo_home.php
 * @route /templates/sections/corporativo_home.php
 * @description Sección Corporativa traducible.
 * @author Kevin Mariano
 * @version 1.0.2
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

global $lang;
?>
<section class="feature-section section-padding" id="corporativo" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="section-title text-center">
            <span class="sub-title wow fadeInUp">
                <?php echo $lang['corp_sub'] ?? 'Servicios'; ?>
            </span>
            <h2 class="wow fadeInUp wow" data-wow-delay=".3s">
                <?php echo $lang['corp_title'] ?? 'Soluciones para su Organización'; ?>
            </h2>
        </div>
        
        <div class="row g-4">
            
            <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="feature-card p-5 bg-white h-100 text-center" style="border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: all 0.3s ease;">
                    <div class="icon mb-4">
                        <i class="fa-solid fa-champagne-glasses fa-3x" style="color: var(--color-theme);"></i>
                    </div>
                    <h4 class="mb-3"><?php echo $lang['corp_c1_title'] ?? 'Eventos & Celebraciones'; ?></h4>
                    <p class="mb-4 text-muted">
                        <?php echo $lang['corp_c1_desc'] ?? 'Eleve el nivel de sus fiestas de fin de año o aniversarios con el show musical más prestigioso del Caribe.'; ?>
                    </p>
                    <a href="https://wa.me/<?php echo urlencode($lang['header_telefono'] ?? '+57'); ?>?text=Hola,%20quisiera%20cotizar%20un%20evento%20corporativo" target="_blank" class="theme-btn style-2" style="border-radius: 50px; padding: 10px 30px;">
                        <?php echo $lang['corp_c1_btn'] ?? 'Cotizar Evento'; ?>
                    </a>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="feature-card p-5 bg-white h-100 text-center" style="border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: all 0.3s ease;">
                    <div class="icon mb-4">
                        <i class="fa-solid fa-hand-holding-heart fa-3x" style="color: var(--color-theme);"></i>
                    </div>
                    <h4 class="mb-3"><?php echo $lang['corp_c2_title'] ?? 'Alianzas & RSE'; ?></h4>
                    <p class="mb-4 text-muted">
                        <?php echo $lang['corp_c2_desc'] ?? 'Vincule su marca a nuestra fundación. Obtenga beneficios tributarios (Certificado de Donación) mientras apoya la cultura.'; ?>
                    </p>
                    <a href="https://wa.me/<?php echo urlencode($lang['header_telefono'] ?? '+57'); ?>?text=Hola,%20nos%20interesa%20realizar%20una%20alianza%20o%20donación" target="_blank" class="theme-btn style-2" style="border-radius: 50px; padding: 10px 30px;">
                        <?php echo $lang['corp_c2_btn'] ?? 'Ser Aliado'; ?>
                    </a>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="feature-card p-5 bg-white h-100 text-center" style="border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: all 0.3s ease;">
                    <div class="icon mb-4">
                        <i class="fa-solid fa-building fa-3x" style="color: var(--color-theme);"></i>
                    </div>
                    <h4 class="mb-3"><?php echo $lang['corp_c3_title'] ?? 'Alquiler de Espacios'; ?></h4>
                    <p class="mb-4 text-muted">
                        <?php echo $lang['corp_c3_desc'] ?? 'Disponemos de auditorios, aulas y nuestra imponente Concha Acústica para sus convenciones o eventos masivos.'; ?>
                    </p>
                    <a href="https://wa.me/<?php echo urlencode($lang['header_telefono'] ?? '+57'); ?>?text=Hola,%20quisiera%20información%20sobre%20el%20alquiler%20de%20espacios" target="_blank" class="theme-btn style-2" style="border-radius: 50px; padding: 10px 30px;">
                        <?php echo $lang['corp_c3_btn'] ?? 'Consultar Espacios'; ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>