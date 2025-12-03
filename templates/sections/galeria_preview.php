<?php
/**
 * @file galeria_preview.php
 * @route /templates/sections/galeria_preview.php
 * @description Sección de previsualización de imágenes de la Galería (Instagram Banner).
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

$db = Database::getInstance();
global $lang;

$previewImages = $db->fetchAll("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 6");
?>

<div class="instagram-banner fix section-padding" id="galeria-preview">
    <div class="instagram-wrapper">
        <h2 class="text-center wow fadeInUp" data-wow-delay=".3s">
            <?php echo $lang['nav_galeria'] ?? 'Galería'; ?> Destacada
        </h2>
        <p class="text-center mb-5">Momentos inolvidables en el escenario y detrás de cámaras.</p>
        
        <div class="swiper instagram-banner-slider">
            <div class="swiper-wrapper">
                <?php if (!empty($previewImages)): ?>
                    <?php foreach ($previewImages as $img): ?>
                        <div class="swiper-slide">
                            <div class="instagram-banner-items">
                                <div class="banner-image">
                                    <img src="<?php echo BASE_URL . '/' . $img['image_path']; ?>" 
                                         alt="<?php echo htmlspecialchars($img['title']); ?>"
                                         style="height: 300px; object-fit: cover;"> <a href="<?php echo BASE_URL; ?>/index.php?page=galeria" class="icon" title="Ver en Galería">
                                        <i class="fa-regular fa-image"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="swiper-slide">
                        <div class="text-center p-5 bg-light text-muted">
                            <i class="fa-regular fa-images fa-2x mb-2"></i>
                            <p>Pronto subiremos fotos.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center mt-5 wow fadeInUp" data-wow-delay=".5s">
            <a href="<?php echo BASE_URL; ?>/index.php?page=galeria" class="theme-btn">
                Ver Galería Completa
            </a>
        </div>
    </div>
</div>