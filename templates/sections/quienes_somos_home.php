<?php
/**
 * @file quienes_somos_home.php
 * @route /templates/sections/quienes_somos_home.php
 * @description Sección Quiénes Somos (Historia, Fundación, Orquestas) - TRADUCIDO
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

global $lang;
?>
<section class="about-section section-padding fix" id="quienes-somos">
    <div class="container">
        <div class="about-wrapper">
            <div class="row g-4 align-items-center">
                
                <div class="col-lg-6">
                    <div class="about-image-area">
                        <div class="about-image wow fadeInUp" data-wow-delay=".3s">
                            <img src="<?php echo BASE_URL; ?>/assets/img/about/06.jpg" alt="Integrante Banda de Baranoa" style="max-width: 350px; height: auto; border-radius: 10px;">
                        </div>
                        <br>
                        <div class="about-image-2 wow fadeInUp" data-wow-delay=".5s">
                            <img src="<?php echo BASE_URL; ?>/assets/img/about/07.jpg" alt="Detalle Instrumento" style="max-width: 350px; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="section-title">
                            <span class="sub-title wow fadeInUp">
                                <?php echo $lang['about_sub'] ?? 'Quiénes Somos'; ?>
                            </span>
                            
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                <?php echo $lang['about_title'] ?? 'Más que una Banda, Somos un Patrimonio Vivo'; ?>
                            </h2>
                        </div>
                        
                        <p class="wow fadeInUp" data-wow-delay=".5s">
                            <?php echo $lang['about_desc'] ?? 'Desde 1995, la Fundación Banda de Baranoa ha transformado la vida de miles de niños y jóvenes del Atlántico a través de la música.'; ?>
                        </p>
                        
                        <ul class="about-list wow fadeInUp" data-wow-delay=".7s">
                            <li>
                                <i class="fa-solid fa-circle-check"></i>
                                <?php echo $lang['about_list1'] ?? 'Embajadores culturales de Colombia ante el mundo.'; ?>
                            </li>
                            <li>
                                <i class="fa-solid fa-circle-check"></i>
                                <?php echo $lang['about_list2'] ?? 'Formación artística integral y gratuita.'; ?>
                            </li>
                        </ul>

                        <div class="counter-area mt-4">
                            <div class="row">
                                <div class="col-md-4 col-6">
                                    <div class="counter-item">
                                        <h2 class="count">30</h2>
                                        <p><?php echo $lang['about_stat1'] ?? 'Años de Historia'; ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="counter-item">
                                        <h2><span class="count">600</span>+</h2>
                                        <p><?php echo $lang['about_stat2'] ?? 'Músicos en Escena'; ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="counter-item">
                                        <h2 class="count">18</h2>
                                        <p><?php echo $lang['about_stat3'] ?? 'Países Visitados'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="about-button mt-4 wow fadeInUp" data-wow-delay=".9s">
                            <a href="https://www.youtube.com/watch?v=WsUh2f9xtJw" class="theme-btn video-popup">
                                <span><?php echo $lang['about_btn'] ?? 'Conoce Nuestra Historia'; ?></span>
                                <i class="fa-solid fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>