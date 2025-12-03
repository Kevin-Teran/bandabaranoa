<?php
/**
 * @file experiencias_home.php
 * @route /templates/sections/experiencias_home.php
 * @description Sección de Experiencias.
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

global $lang;
?>
<section class="activities-section section-padding fix" id="experiencias">
    <div class="container">
        <div class="section-title text-center">
            <span class="sub-title wow fadeInUp">
                <?php echo $lang['exp_sub'] ?? 'Vive la Banda'; ?>
            </span>
            <h2 class="wow fadeInUp wow" data-wow-delay=".3s">
                <?php echo $lang['exp_title'] ?? 'Nuestras Experiencias Únicas'; ?>
            </h2>
        </div>
        
        <div class="row">
            
            <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay=".3s">
                <div class="activities-items h-100 d-flex flex-column">
                    <div class="activities-image">
                        <img src="<?php echo BASE_URL; ?>/assets/img/activities/01.jpg" alt="Músico por un Día" style="width: 100%; height: 250px; object-fit: cover;">
                    </div>
                    <div class="activities-content d-flex flex-column flex-grow-1">
                        <h4><?php echo $lang['exp_c1_title'] ?? 'Músico por un Día'; ?></h4>
                        <p class="flex-grow-1">
                            <?php echo $lang['exp_c1_desc'] ?? 'Ponte el uniforme, toma un instrumento y vive la adrenalina de ensayar con la orquesta más grande de Colombia.'; ?>
                        </p>
                        <a href="https://wa.me/<?php echo urlencode($lang['header_telefono'] ?? '+57'); ?>?text=Hola,%20me%20gustaría%20ser%20músico%20por%20un%20día" target="_blank" class="link-btn mt-auto">
                            <?php echo $lang['exp_c1_btn'] ?? 'Reservar Cupo'; ?> 
                            <i class="fa-sharp fa-regular fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay=".5s">
                <div class="activities-items h-100 d-flex flex-column">
                    <div class="activities-image">
                        <img src="<?php echo BASE_URL; ?>/assets/img/activities/02.jpg" alt="Grabar tu Música" style="width: 100%; height: 250px; object-fit: cover;">
                    </div>
                    <div class="activities-content d-flex flex-column flex-grow-1">
                        <h4><?php echo $lang['exp_c2_title'] ?? 'Grabar tu Música'; ?></h4>
                        <p class="flex-grow-1">
                            <?php echo $lang['exp_c2_desc'] ?? 'Nuestro estudio profesional está abierto para ti. Tecnología de punta y acústica perfecta para tus proyectos.'; ?>
                        </p>
                        <a href="https://wa.me/<?php echo urlencode($lang['header_telefono'] ?? '+57'); ?>?text=Hola,%20quisiera%20cotizar%20el%20alquiler%20del%20estudio" target="_blank" class="link-btn mt-auto">
                            <?php echo $lang['exp_c2_btn'] ?? 'Cotizar Estudio'; ?> 
                            <i class="fa-sharp fa-regular fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay=".7s">
                <div class="activities-items h-100 d-flex flex-column">
                    <div class="activities-image">
                        <img src="<?php echo BASE_URL; ?>/assets/img/activities/03.jpg" alt="Visita Guiada" style="width: 100%; height: 250px; object-fit: cover;">
                    </div>
                    <div class="activities-content d-flex flex-column flex-grow-1">
                        <h4><?php echo $lang['exp_c3_title'] ?? 'Visita Guiada'; ?></h4>
                        <p class="flex-grow-1">
                            <?php echo $lang['exp_c3_desc'] ?? 'Recorre nuestra sede campestre, conoce el museo de historia y presencia cómo se forman nuestros artistas.'; ?>
                        </p>
                        <a href="https://wa.me/<?php echo urlencode($lang['header_telefono'] ?? '+57'); ?>?text=Hola,%20me%20gustaría%20agendar%20una%20visita%20guiada" target="_blank" class="link-btn mt-auto">
                            <?php echo $lang['exp_c3_btn'] ?? 'Agendar Visita'; ?> 
                            <i class="fa-sharp fa-regular fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>