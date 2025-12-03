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
global $lang; // 1. Importante para textos fijos

$slug = $_GET['slug'] ?? '';

if (empty($slug)) { echo "<script>window.location='index.php?page=noticias';</script>"; exit; }

// Consultas
$noticia = $db->fetchOne("SELECT * FROM news WHERE slug = :s AND status = 'published'", [':s' => $slug]);

// Validación si no existe
if (!$noticia) { 
    echo "<div class='container py-5 text-center'><h3>" . ($lang['news_not_found'] ?? 'Noticia no encontrada') . "</h3></div>"; 
    return; 
}

$db->query("UPDATE news SET views = views + 1 WHERE id = :id", [':id' => $noticia['id']]);
$galeria = $db->fetchAll("SELECT * FROM news_gallery WHERE news_id = :id ORDER BY sort_order ASC", [':id' => $noticia['id']]);
$recientes = $db->fetchAll("SELECT * FROM news WHERE status = 'published' AND id != :id ORDER BY created_at DESC LIMIT 3", [':id' => $noticia['id']]);

$imgPortada = !empty($noticia['image_path']) ? $noticia['image_path'] : '';

// --- 2. TRADUCCIÓN DINÁMICA (Título y Contenido) ---
$titulo = $noticia['title'];
$contenido = html_entity_decode($noticia['content']);

// Si NO es español, intentamos traducir
if (defined('CURRENT_LANG') && CURRENT_LANG !== 'es' && class_exists('Translator')) {
    $titulo = Translator::translate($titulo, CURRENT_LANG);
    // NOTA: Traducir el contenido completo puede tardar un poco más o tener límites de longitud en la API gratuita.
    // Si es muy largo, la API podría devolver el texto original, lo cual es seguro.
    $contenido = Translator::translate($contenido, CURRENT_LANG);
}
?>

<section class="news-details-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="news-details-wrapper">
                    
                    <?php if($imgPortada): ?>
                    <div class="mb-4 rounded overflow-hidden shadow-sm" style="width: 100%; height: 450px;">
                        <img src="<?php echo $imgPortada; ?>" alt="Portada" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <?php endif; ?>

                    <div class="post-meta mb-2">
                        <span class="text-primary">
                            <i class="fa-solid fa-calendar-days"></i> 
                            <?php echo date('d F, Y', strtotime($noticia['created_at'])); ?>
                        </span>
                        <span class="mx-2 text-muted">|</span>
                        <span class="text-muted">
                            <i class="fa-solid fa-eye"></i> 
                            <?php echo $noticia['views']; ?> <?php echo $lang['news_views'] ?? 'Vistas'; ?>
                        </span>
                    </div>

                    <h2 class="mb-4 fw-bold" style="font-size: 2rem; color: #222;">
                        <?php echo htmlspecialchars($titulo); ?>
                    </h2>
                    
                    <div class="news-content-body text-justify mb-5" style="line-height: 1.8; font-size: 1.1rem; color: #444;">
                        <?php echo $contenido; ?>
                    </div>

                    <?php if (!empty($galeria)): ?>
                    <div class="news-gallery mb-5 p-4 bg-light rounded">
                        <h4 class="mb-4 pb-2 border-bottom">
                            <?php echo $lang['news_gallery'] ?? 'Galería de Fotos'; ?>
                        </h4>
                        <div class="row g-3"> 
                            <?php foreach ($galeria as $foto): ?>
                            <div class="col-sm-6 col-md-4">
                                <div class="gallery-item h-100 overflow-hidden rounded shadow-sm">
                                    <a href="<?php echo $foto['image_path']; ?>" target="_blank">
                                        <img src="<?php echo $foto['image_path']; ?>" class="w-100" style="height: 200px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" alt="Galería">
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="index.php?page=noticias" class="theme-btn-2 btn btn-outline-dark px-4">
                            <i class="fa-solid fa-arrow-left me-2"></i> 
                            <?php echo $lang['news_back'] ?? 'Volver a Noticias'; ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="sidebar-widget p-4 bg-white border rounded shadow-sm">
                    <h4 class="widget-title mb-4 fw-bold">
                        <?php echo $lang['news_recent'] ?? 'Recientes'; ?>
                    </h4>
                    
                    <?php foreach ($recientes as $rec): 
                        $rLink = "index.php?page=noticia-detalle&slug=" . $rec['slug'];
                        $rImg = !empty($rec['image_path']) ? $rec['image_path'] : 'assets/img/news/default.jpg';
                        
                        // Traducir títulos de recientes también
                        $recTitulo = $rec['title'];
                        if (defined('CURRENT_LANG') && CURRENT_LANG !== 'es' && class_exists('Translator')) {
                            $recTitulo = Translator::translate($recTitulo, CURRENT_LANG);
                        }
                    ?>
                    <div class="recent-post-items d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="recent-post-thumb me-3 rounded overflow-hidden" style="width: 80px; height: 70px; flex-shrink: 0;">
                            <a href="<?php echo $rLink; ?>">
                                <img src="<?php echo $rImg; ?>" class="w-100 h-100" style="object-fit: cover;">
                            </a>
                        </div>
                        <div class="recent-post-content">
                            <h6 class="mb-1" style="font-size: 15px; line-height: 1.3;">
                                <a href="<?php echo $rLink; ?>" class="text-dark text-decoration-none fw-bold">
                                    <?php echo mb_strimwidth($recTitulo, 0, 45, "..."); ?>
                                </a>
                            </h6>
                            <small class="text-muted"><?php echo date('d M', strtotime($rec['created_at'])); ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</section>