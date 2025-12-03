<?php
/**
 * @file galeria.php
 * @route /pages/galeria.php
 * @description Vista pública de la galería multimedia con Lightbox y Paginación.
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

$db = Database::getInstance();
global $lang;

// Consulta de Datos (Protegida)
$items = [];
$totalRows = 0;

if ($db->isConnected()) {
    $whereSQL = "status = 'published'";
    
    // Obtener TODOS los items sin límite
    $items = $db->fetchAll("SELECT * FROM gallery WHERE $whereSQL ORDER BY created_at DESC");
    
    // Contar total de items obtenidos
    $totalRows = count($items);
}
?>

<style>
    /* Espaciador para menú fijo */
    .nav-spacer { height: 100px; display: block; background: #fff; }

    /* --- ESTILOS UNIFICADOS (Igual que el Preview) --- */
    .gallery-grid-item {
        display: block;
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        aspect-ratio: 1 / 1; /* Cuadrado perfecto */
        width: 100%;
        background-color: #f0f0f0;
        cursor: pointer;
    }

    .gallery-grid-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    /* Efecto Zoom suave */
    .gallery-grid-item:hover img {
        transform: scale(1.1);
    }

    /* Overlay Oscuro */
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .gallery-grid-item:hover .gallery-overlay {
        opacity: 1;
    }

    /* Icono Central */
    .gallery-icon {
        color: white;
        font-size: 2.5rem;
        margin-bottom: 0; /* Centrado total sin texto abajo */
        transform: translateY(20px);
        transition: transform 0.4s ease;
    }

    .gallery-grid-item:hover .gallery-icon {
        transform: translateY(0);
    }
</style>

<div class="nav-spacer"></div>

<section class="gallery-section section-padding fix">
    <div class="container">
        
        <!-- Título de Sección -->
        <div class="row mb-5">
             <div class="col-12 text-center">
                 <span class="sub-title wow fadeInUp" style="color: #d90a2c; font-weight: 700; display: block; margin-bottom: 10px;">
                     <?= $lang['gallery_subtitle'] ?? 'Revive nuestros mejores momentos' ?>
                 </span>
                 <h2 class="fw-bold text-dark wow fadeInUp" data-wow-delay=".2s">
                     <?= $lang['gallery_title'] ?? 'Nuestra Galería' ?>
                 </h2>
             </div>
        </div>

        <?php if (empty($items)): ?>
            <!-- Estado Vacío / Error DB -->
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fa-regular fa-images fa-4x text-muted opacity-25"></i>
                </div>
                <h4 class="text-muted">
                    <?= $db->isConnected() 
                        ? ($lang['gallery_empty'] ?? 'Aún no hay imágenes publicadas.') 
                        : 'La galería no está disponible temporalmente.' 
                    ?>
                </h4>
            </div>
        <?php else: ?>

            <!-- Grid de Galería -->
            <div class="row g-4" id="gallery-grid">
                <?php foreach ($items as $item): 
                    // Procesamiento inteligente de ruta (igual que en home)
                    $imgPath = $item['image_path'];
                    if (strpos($imgPath, 'http') === 0) {
                        $finalImg = $imgPath;
                    } elseif (strpos($imgPath, 'assets/') === 0) {
                        $finalImg = Router::url($imgPath);
                    } else {
                        $finalImg = Router::asset($imgPath);
                    }
                ?>
                <div class="col-6 col-md-4 col-lg-3 wow fadeInUp" data-wow-delay=".3s">
                    <div class="gallery-wrapper">
                        <!-- El 'title' aquí se usa solo para el Lightbox, no se muestra en la tarjeta -->
                        <a href="<?= $finalImg ?>" class="gallery-grid-item popup-image" title="<?= htmlspecialchars($item['title']) ?>">
                            <img src="<?= $finalImg ?>" 
                                 alt="Imagen Galería"
                                 loading="lazy">
                            
                            <div class="gallery-overlay">
                                <div class="gallery-icon">
                                    <i class="fa-regular fa-eye"></i>
                                </div>
                                <!-- Texto eliminado para limpiar la vista -->
                            </div>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>
</section>

<!-- Script para activar Lightbox (Magnific Popup) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Usamos un intervalo seguro para esperar a jQuery
        const initPopup = () => {
            if (typeof $ !== 'undefined' && $.fn.magnificPopup) {
                $('.popup-image').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true,
                        tCounter: '<span class="mfp-counter">%curr% / %total%</span>' // Contador limpio
                    },
                    mainClass: 'mfp-fade',
                    removalDelay: 300,
                    image: {
                        titleSrc: function(item) {
                            return item.el.attr('title');
                        }
                    }
                });
            } else {
                setTimeout(initPopup, 100);
            }
        };
        initPopup();
    });
</script>