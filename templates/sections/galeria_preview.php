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

// Consulta segura a DB (Traemos 10 para que el carrusel tenga movimiento)
$previewImages = $db->isConnected() 
    ? $db->fetchAll("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 10") 
    : [];
?>

<style>
    /* Contenedor del slider: Evita desbordamientos */
    .instagram-banner-slider {
        overflow: hidden; 
        padding-bottom: 20px;
    }

    /* Tarjeta cuadrada perfecta */
    .gallery-card {
        display: block;
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        aspect-ratio: 1 / 1; /* Cuadrado */
        width: 100%;
        background-color: #f0f0f0; /* Fondo mientra carga */
    }

    .gallery-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .gallery-card:hover img {
        transform: scale(1.1);
    }

    /* Overlay con icono */
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .gallery-card:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-overlay i {
        color: white;
        font-size: 2rem;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
    }
</style>

<div class="instagram-banner fix section-padding" id="galeria-preview">
    <div class="instagram-wrapper">
        <div class="container-fluid"> <!-- Container fluid para ancho completo -->
            <h2 class="text-center wow fadeInUp" data-wow-delay=".3s">
                <?= $lang['gallery_title'] ?? ($lang['nav_galeria'] ?? 'Galería') . ' Destacada' ?>
            </h2>
            <p class="text-center mb-5">
                <?= $lang['gallery_desc'] ?? 'Momentos inolvidables en el escenario y detrás de cámaras.' ?>
            </p>
            
            <!-- Estructura Swiper -->
            <div class="swiper instagram-banner-slider">
                <div class="swiper-wrapper">
                    <?php if (!empty($previewImages)): ?>
                        <?php foreach ($previewImages as $img): ?>
                            <?php 
                                // Procesamiento de ruta de imagen seguro
                                $imgPath = $img['image_path'];
                                if (strpos($imgPath, 'http') === 0) {
                                    $finalImg = $imgPath;
                                } elseif (strpos($imgPath, 'assets/') === 0) {
                                    $finalImg = Router::url($imgPath);
                                } else {
                                    $finalImg = Router::asset($imgPath);
                                }
                            ?>
                            
                            <div class="swiper-slide">
                                <!-- CAMBIO CRÍTICO: Eliminada clase 'instagram-banner-items' para desactivar popup JS -->
                                <div class="px-2"> 
                                    <a href="<?= Router::url('galeria') ?>" class="gallery-card" title="<?= htmlspecialchars($img['title'] ?? '') ?>">
                                        <img src="<?= $finalImg ?>" 
                                             alt="<?= htmlspecialchars($img['title'] ?? 'Galería') ?>"
                                             loading="lazy">
                                        <div class="gallery-overlay">
                                            <i class="fa-regular fa-eye"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback (Envuelto en swiper-slide para no romper el layout) -->
                        <div class="swiper-slide">
                            <div class="col-12 text-center p-5">
                                <div class="alert alert-light d-inline-block">
                                    <i class="fa-regular fa-images fa-2x mb-2 text-muted"></i>
                                    <p class="mb-0 text-muted">
                                        <?= $lang['gallery_empty'] ?? 'Nuestra galería se está actualizando.' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center mt-5 wow fadeInUp" data-wow-delay=".5s">
                <a href="<?= Router::url('galeria') ?>" class="theme-btn">
                    <?= $lang['gallery_btn'] ?? 'Ver Galería Completa' ?>
                </a>
            </div>
        </div>
    </div>
</div>