<?php
/**
 * @file noticia-detalle.php
 * @route /pages/noticia-detalle.php
 * @description Vista individual de noticia con TRADUCCIÓN HÍBRIDA.
 * @author Kevin Mariano
 * @version 1.1.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

$db = Database::getInstance();
global $lang;

// 1. Obtener Slug de forma segura
$slug = $_GET['slug'] ?? '';

// Si no hay slug, redirigir a la lista
if (empty($slug)) { 
    if (class_exists('Router')) {
        header("Location: " . Router::url('noticias'));
    } else {
        header("Location: index.php?page=noticias");
    }
    exit; 
}

// 2. Variables iniciales
$noticia = false;
$galeria = [];
$recientes = [];
$dbAvailable = $db->isConnected();

// 3. Consultas Seguras
if ($dbAvailable) {
    // Buscar noticia
    $noticia = $db->fetchOne("SELECT * FROM news WHERE slug = :s AND status = 'published'", [':s' => $slug]);
    
    if ($noticia) {
        // Registrar vista
        $db->query("UPDATE news SET views = views + 1 WHERE id = :id", [':id' => $noticia['id']]);
        
        // Obtener galería y recientes
        $galeria = $db->fetchAll("SELECT * FROM news_gallery WHERE news_id = :id ORDER BY sort_order ASC", [':id' => $noticia['id']]);
        $recientes = $db->fetchAll("SELECT * FROM news WHERE status = 'published' AND id != :id ORDER BY created_at DESC LIMIT 4", [':id' => $noticia['id']]);
    }
}

// Validación final: Si no existe la noticia o no hay DB
if (!$noticia) { 
    echo "<div class='container py-5 text-center' style='min-height: 50vh; display:flex; flex-direction:column; justify-content:center;'>
            <div class='mb-4'><i class='fa-regular fa-newspaper fa-4x text-muted opacity-25'></i></div>
            <h3 class='text-muted'>" . ($lang['news_not_found'] ?? 'Noticia no encontrada o no disponible.') . "</h3>
            <div class='mt-4'><a href='".Router::url('noticias')."' class='btn theme-btn'>Volver a Noticias</a></div>
          </div>"; 
    return; 
}

// --- PROCESAMIENTO DE DATOS ---

// 1. Imagen Principal
$defaultImg = 'img/news/news_1763672072.jpg';
$imgRaw = !empty($noticia['image_path']) ? $noticia['image_path'] : $defaultImg;

if (strpos($imgRaw, 'http') === 0) {
    $imgPortada = $imgRaw;
} elseif (strpos($imgRaw, 'assets/') === 0) {
    $imgPortada = Router::url($imgRaw);
} else {
    $imgPortada = Router::asset($imgRaw);
}

// 2. Traducción
$titulo = $noticia['title'];
$contenido = html_entity_decode($noticia['content']);

if (defined('CURRENT_LANG') && CURRENT_LANG !== 'es' && class_exists('Translator')) {
    $titulo = Translator::translate($titulo, CURRENT_LANG);
    // Traducir contenido (opcional, depende de la longitud)
    // $contenido = Translator::translate($contenido, CURRENT_LANG); 
}
?>

<style>
    .nav-spacer { height: 120px; display: block; background: #fff; }
    
    /* Estilos del contenido */
    .news-content-body { font-size: 1.1rem; line-height: 1.8; color: #444; }
    .news-content-body p { margin-bottom: 1.5rem; }
    .news-content-body img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }
    
    /* Sidebar Widgets */
    .sidebar-widget { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    .recent-post-items:last-child { border-bottom: none !important; padding-bottom: 0 !important; margin-bottom: 0 !important; }
    .recent-post-thumb img { transition: transform 0.3s; }
    .recent-post-items:hover .recent-post-thumb img { transform: scale(1.1); }
</style>

<div class="nav-spacer"></div>

<section class="news-details-section section-padding">
    <div class="container">
        <div class="row">
            
            <!-- CONTENIDO PRINCIPAL -->
            <div class="col-lg-8">
                <div class="news-details-wrapper">
                    
                    <!-- Imagen Destacada -->
                    <div class="mb-4 rounded overflow-hidden shadow-sm position-relative" style="width: 100%; padding-top: 56.25%;"> <!-- Aspect Ratio 16:9 -->
                        <img src="<?= $imgPortada ?>" 
                             alt="<?= htmlspecialchars($titulo) ?>" 
                             style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"
                             onerror="this.onerror=null;this.src='<?= Router::asset($defaultImg) ?>';">
                    </div>

                    <!-- Metadatos -->
                    <div class="post-meta mb-3 d-flex align-items-center flex-wrap">
                        <span class="text-danger fw-bold me-3">
                            <i class="fa-solid fa-calendar-days me-1"></i> 
                            <?= date('d F, Y', strtotime($noticia['created_at'])) ?>
                        </span>
                        <span class="text-muted me-3">|</span>
                        <span class="text-muted">
                            <i class="fa-solid fa-eye me-1"></i> 
                            <?= $noticia['views'] ?> <?= $lang['news_views'] ?? 'Vistas' ?>
                        </span>
                    </div>

                    <!-- Título -->
                    <h2 class="mb-4 fw-bold text-dark" style="font-size: 2.2rem; line-height: 1.3;">
                        <?= htmlspecialchars($titulo) ?>
                    </h2>
                    
                    <!-- Cuerpo de la noticia -->
                    <div class="news-content-body text-justify mb-5">
                        <?= $contenido ?>
                    </div>

                    <!-- Galería Interna (Si existe) -->
                    <?php if (!empty($galeria)): ?>
                    <div class="news-gallery mb-5 p-4 bg-light rounded border">
                        <h4 class="mb-4 pb-2 border-bottom fw-bold" style="color: #d90a2c;">
                            <?= $lang['news_gallery'] ?? 'Galería de Fotos' ?>
                        </h4>
                        <div class="row g-3"> 
                            <?php foreach ($galeria as $foto): 
                                // Procesar ruta galería
                                $gRaw = $foto['image_path'];
                                if (strpos($gRaw, 'http') === 0) $gImg = $gRaw;
                                elseif (strpos($gRaw, 'assets/') === 0) $gImg = Router::url($gRaw);
                                else $gImg = Router::asset($gRaw);
                            ?>
                            <div class="col-6 col-md-4">
                                <div class="gallery-item h-100 overflow-hidden rounded shadow-sm position-relative" style="padding-top: 100%;">
                                    <a href="<?= $gImg ?>" class="popup-image">
                                        <img src="<?= $gImg ?>" 
                                             class="w-100 h-100 position-absolute top-0 start-0" 
                                             style="object-fit: cover; transition: transform 0.3s;" 
                                             onmouseover="this.style.transform='scale(1.1)'" 
                                             onmouseout="this.style.transform='scale(1)'" 
                                             alt="Galería"
                                             onerror="this.style.display='none'">
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Botón Volver -->
                    <div class="mt-5 border-top pt-4">
                        <a href="<?= Router::url('noticias') ?>" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                            <i class="fa-solid fa-arrow-left me-2"></i> 
                            <?= $lang['news_back'] ?? 'Volver a Noticias' ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR (Noticias Recientes) -->
            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="sidebar-widget">
                    <h4 class="widget-title mb-4 fw-bold position-relative pb-3">
                        <?= $lang['news_recent'] ?? 'Recientes' ?>
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background: #d90a2c;"></span>
                    </h4>
                    
                    <?php if(!empty($recientes)): ?>
                        <?php foreach ($recientes as $rec): 
                            $rLink = Router::url('noticia/' . $rec['slug']);
                            
                            // Imagen Reciente Inteligente
                            $rRaw = !empty($rec['image_path']) ? $rec['image_path'] : $defaultImg;
                            if (strpos($rRaw, 'http') === 0) $rImg = $rRaw;
                            elseif (strpos($rRaw, 'assets/') === 0) $rImg = Router::url($rRaw);
                            else $rImg = Router::asset($rRaw);
                            
                            // Traducir título reciente
                            $recTitulo = $rec['title'];
                            if (defined('CURRENT_LANG') && CURRENT_LANG !== 'es' && class_exists('Translator')) {
                                $recTitulo = Translator::translate($recTitulo, CURRENT_LANG);
                            }
                        ?>
                        <div class="recent-post-items d-flex align-items-center mb-4 pb-3 border-bottom">
                            <div class="recent-post-thumb me-3 rounded overflow-hidden shadow-sm" style="width: 90px; height: 90px; flex-shrink: 0;">
                                <a href="<?= $rLink ?>">
                                    <img src="<?= $rImg ?>" 
                                         class="w-100 h-100" 
                                         style="object-fit: cover;" 
                                         alt="Reciente"
                                         onerror="this.src='<?= Router::asset($defaultImg) ?>'">
                                </a>
                            </div>
                            <div class="recent-post-content">
                                <span class="d-block text-muted small mb-1">
                                    <i class="fa-regular fa-calendar me-1"></i> <?= date('d M, Y', strtotime($rec['created_at'])) ?>
                                </span>
                                <h6 class="mb-0" style="font-size: 15px; line-height: 1.4;">
                                    <a href="<?= $rLink ?>" class="text-dark text-decoration-none fw-bold hover-red">
                                        <?= mb_strimwidth($recTitulo, 0, 50, "...") ?>
                                    </a>
                                </h6>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted small">No hay más noticias recientes.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Script para la galería interna (Lightbox) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ !== 'undefined' && $.fn.magnificPopup) {
            $('.popup-image').magnificPopup({
                type: 'image',
                gallery: { enabled: true },
                mainClass: 'mfp-fade'
            });
        }
    });
</script>