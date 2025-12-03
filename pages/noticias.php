<?php
/**
 * @file noticias.php
 * @route /pages/noticias.php
 * @description Vista principal del listado de noticias con paginación.
 * @author Kevin Mariano
 * @version 1.1.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

$db = Database::getInstance();
global $lang; // 1. Importante para textos fijos

$limit = 6; 
$p = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($p - 1) * $limit;

// Búsqueda
$search = $_GET['q'] ?? '';
$whereSQL = "status = 'published'"; 
$params = [];
if (!empty($search)) {
    $whereSQL .= " AND (title LIKE :q OR content LIKE :q)";
    $params[':q'] = "%$search%";
}

// Consultas
$totalNews = $db->fetchOne("SELECT COUNT(*) as c FROM news WHERE $whereSQL", $params)['c'];
$totalPages = ceil($totalNews / $limit);
$noticias = $db->fetchAll("SELECT * FROM news WHERE $whereSQL ORDER BY featured DESC, created_at DESC LIMIT $limit OFFSET $offset", $params);
?>

<style>
    /* Estilos originales mantenidos */
    .header-area { background-color: #ffffff !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important; position: fixed !important; width: 100%; top: 0; z-index: 999; }
    .header-area .main-menu ul li a, .mean-container .mean-nav ul li a { color: #000000 !important; font-weight: 700 !important; opacity: 1 !important; }
    .mean-container a.meanmenu-reveal span { background-color: #000000 !important; }
    .nav-spacer { height: 120px; display: block; background: #fff; }
</style>

<div class="nav-spacer"></div>

<section class="news-section section-padding fix">
    <div class="container">
        
        <div class="section-title text-center mb-5">
            <span class="sub-title fw-bold text-uppercase" style="color: #E63946;">
                <?php echo $lang['news_sub'] ?? 'Actualidad'; ?>
            </span>
            <h2 class="fw-bold text-dark">
                <?php echo $lang['news_title'] ?? 'Noticias y Novedades'; ?>
            </h2>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-md-6">
                <form action="index.php" method="GET" class="d-flex shadow-sm rounded overflow-hidden border">
                    <input type="hidden" name="page" value="noticias">
                    <input type="text" name="q" class="form-control border-0 px-4 py-3" placeholder="<?php echo $lang['search_placeholder'] ?? 'Buscar...'; ?>" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn px-4" style="background-color: #E63946; color: white;"><i class="fa-solid fa-search"></i></button>
                </form>
            </div>
        </div>

        <?php if (empty($noticias)): ?>
            <div class="alert alert-light text-center p-5 border rounded">
                <p class="mb-0 fs-5 text-muted">
                    <?php echo $lang['news_no_posts'] ?? 'No hay noticias publicadas.'; ?>
                </p>
            </div>
        <?php else: ?>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($noticias as $news): 
                    $img = !empty($news['image_path']) ? $news['image_path'] : 'assets/img/news/default.jpg';
                    $link = "index.php?page=noticia-detalle&slug=" . $news['slug'];
                    $fechaDay = date('d', strtotime($news['created_at']));
                    $fechaMonth = date('M', strtotime($news['created_at']));

                    // --- TRADUCCIÓN DINÁMICA ---
                    // 1. Preparamos el texto base (Título y Resumen)
                    $titulo = $news['title'];
                    
                    // Lógica para el resumen (si no hay resumen manual, usa el contenido recortado)
                    $resumenRaw = !empty($news['summary']) ? $news['summary'] : strip_tags(html_entity_decode($news['content']));
                    $resumen = mb_strimwidth($resumenRaw, 0, 90, "...");

                    // 2. Si NO es español, traducimos con la API
                    if (defined('CURRENT_LANG') && CURRENT_LANG !== 'es' && class_exists('Translator')) {
                        $titulo = Translator::translate($titulo, CURRENT_LANG);
                        $resumen = Translator::translate($resumen, CURRENT_LANG);
                    }
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="position-relative overflow-hidden" style="height: 240px;">
                            <a href="<?php echo $link; ?>">
                                <img src="<?php echo $img; ?>" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="<?php echo htmlspecialchars($titulo); ?>">
                            </a>
                            <div class="position-absolute top-0 end-0 bg-white px-3 py-2 m-3 rounded shadow-sm text-center lh-1">
                                <span class="d-block fw-bold fs-5 text-dark"><?php echo $fechaDay; ?></span>
                                <small class="text-uppercase fw-bold text-muted" style="font-size: 10px;"><?php echo $fechaMonth; ?></small>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold mb-3 lh-sm">
                                <a href="<?php echo $link; ?>" class="text-dark text-decoration-none">
                                    <?php echo $titulo; ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted mb-4" style="flex-grow: 1; font-size: 0.95rem;">
                                <?php echo $resumen; ?>
                            </p>
                            
                            <a href="<?php echo $link; ?>" class="btn w-100 rounded-pill fw-bold" style="border: 2px solid #E63946; color: #E63946; background: transparent;" onmouseover="this.style.background='#E63946'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#E63946';">
                                <?php echo $lang['btn_leer_mas'] ?? 'Leer más'; ?> <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
            <nav class="mt-5 d-flex justify-content-center">
                <ul class="pagination">
                    <?php for($i=1; $i<=$totalPages; $i++): ?>
                        <li class="page-item <?php echo $p == $i ? 'active' : ''; ?>">
                            <a class="page-link border-0 rounded-circle mx-1" style="<?php echo $p == $i ? 'background-color: #E63946; color: white;' : 'color: #333;'; ?>" href="index.php?page=noticias&p=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</section>