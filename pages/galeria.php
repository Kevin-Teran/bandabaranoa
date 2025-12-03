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

// Configuración
$limit = 12; 
$p = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($p - 1) * $limit;

// Consulta Base
$whereSQL = "status = 'published'";
$totalRows = $db->fetchOne("SELECT COUNT(*) as c FROM gallery WHERE $whereSQL")['c'];
$totalPages = ceil($totalRows / $limit);
$items = $db->fetchAll("SELECT * FROM gallery WHERE $whereSQL ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
?>

<style>
    /* Ajustes de Cabecera - Mantenemos para asegurar que el menú se vea bien */
    .header-area { background-color: #ffffff !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important; position: fixed !important; width: 100%; top: 0; z-index: 999; }
    .header-area .main-menu ul li a { color: #000000 !important; }
    
    /* Espaciador para el menú fijo. Ajusta la altura si es necesario */
    .nav-spacer { height: 100px; display: block; background: #fff; }

    /* Estilos de Galería */
    .gallery-card {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        cursor: pointer;
        height: 280px;
    }
    .gallery-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-card:hover img {
        transform: scale(1.1);
    }
    .gallery-overlay {
        position: absolute;
        bottom: -100%;
        left: 0;
        width: 100%;
        padding: 20px;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        transition: bottom 0.3s ease;
    }
    .gallery-card:hover .gallery-overlay {
        bottom: 0;
    }
</style>

<div class="nav-spacer"></div>

<section class="gallery-section section-padding fix">
    <div class="container">
        <div class="row mb-5">
             <div class="col-12 text-center">
                 <h2 class="fw-bold text-dark"><?php echo $lang['nav_galeria'] ?? 'Galería'; ?></h2>
             </div>
        </div>

        <?php if (empty($items)): ?>
            <div class="text-center py-5">
                <div class="mb-3"><i class="fa-regular fa-images fa-3x text-muted opacity-25"></i></div>
                <h4 class="text-muted">Aún no hay imágenes en la galería.</h4>
            </div>
        <?php else: ?>

            <div class="row g-4" id="gallery-grid">
                <?php foreach ($items as $item): 
                    $imgUrl = BASE_URL . '/' . $item['image_path'];
                ?>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="gallery-card shadow-sm">
                        <a href="<?php echo $imgUrl; ?>" class="popup-image" title="<?php echo htmlspecialchars($item['title']); ?>">
                            <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <div class="gallery-overlay">
                                <h5 class="mb-1 fw-bold text-white" style="font-size: 1.1rem;"><?php echo $item['title']; ?></h5>
                                <?php if($item['description']): ?>
                                    <p class="small mb-0 opacity-75"><?php echo mb_strimwidth($item['description'], 0, 60, '...'); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
            <nav class="mt-5 d-flex justify-content-center">
                <ul class="pagination">
                    <?php for($i=1; $i<=$totalPages; $i++): ?>
                        <li class="page-item <?php echo $p == $i ? 'active' : ''; ?>">
                            <a class="page-link border-0 rounded-circle mx-1" 
                               style="<?php echo $p == $i ? 'background-color: #E63946; color: white;' : 'color: #333;'; ?>" 
                               href="index.php?page=galeria&p=<?php echo $i; ?>">
                               <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si jQuery y magnificPopup están cargados
        if (typeof $ !== 'undefined' && $.fn.magnificPopup) {
            $('.popup-image').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true,
                    tCounter: '%curr% de %total%'
                },
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });
        }
    });
</script>