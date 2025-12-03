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
global $lang;

// 1. Configuración de Paginación
$limit = 6; 
$p = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($p - 1) * $limit;

// 2. Lógica de Búsqueda
$search = $_GET['q'] ?? '';
$noticias = [];
$totalNews = 0;
$totalPages = 0;

// 3. Consulta Segura
if ($db->isConnected()) {
    $whereSQL = "status = 'published'"; 
    $params = [];
    
    if (!empty($search)) {
        $whereSQL .= " AND (title LIKE :q OR content LIKE :q)";
        $params[':q'] = "%$search%";
    }

    // Contar total
    $countQuery = $db->fetchOne("SELECT COUNT(*) as c FROM news WHERE $whereSQL", $params);
    $totalNews = $countQuery ? $countQuery['c'] : 0;
    
    // Calcular páginas
    $totalPages = ceil($totalNews / $limit);
    
    // Obtener noticias
    $noticias = $db->fetchAll("SELECT * FROM news WHERE $whereSQL ORDER BY featured DESC, created_at DESC LIMIT $limit OFFSET $offset", $params);
}
?>

<style>
    /* Espaciador para menú fijo */
    .nav-spacer { height: 120px; display: block; background: #fff; }
    
    /* Ajuste para que las tarjetas se estiren igual en todas las filas */
    .news-card-items-3 {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .news-content {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    /* Color del botón de búsqueda */
    .btn-search {
        background-color: #d90a2c;
        color: white;
    }
    .btn-search:hover {
        background-color: #b00823;
        color: white;
    }
</style>

<div class="nav-spacer"></div>

<!-- Usamos las clases news-section-3 y section-padding del home para mantener espaciados -->
<section class="news-section-3 section-padding fix">
    <div class="container">
        
        <!-- Título -->
        <div class="section-title text-center mb-5">
            <span class="sub-title wow fadeInUp" style="color: #d90a2c; font-weight: 700; display: block; margin-bottom: 10px;">
                <?php echo $lang['news_sub'] ?? 'Actualidad'; ?>
            </span>
            <h2 class="fw-bold text-dark wow fadeInUp" data-wow-delay=".2s">
                <?php echo $lang['news_title'] ?? 'Noticias y Novedades'; ?>
            </h2>
        </div>

        <!-- Buscador -->
        <div class="row justify-content-center mb-5 wow fadeInUp" data-wow-delay=".3s">
            <div class="col-md-8 col-lg-6">
                <form action="" method="GET" class="d-flex shadow-sm rounded overflow-hidden border">
                    <input type="text" name="q" class="form-control border-0 px-4 py-3" 
                           placeholder="<?php echo $lang['search_placeholder'] ?? 'Buscar noticia...'; ?>" 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-search px-4">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <?php if (empty($noticias)): ?>
            <!-- Mensaje Vacío / Error DB -->
            <div class="col-12 text-center p-5 wow fadeInUp">
                <div class="alert alert-light d-inline-block border">
                    <i class="fa-regular fa-newspaper fa-3x mb-3 text-muted opacity-50"></i>
                    <p class="mb-0 fs-5 text-muted">
                        <?= $db->isConnected() 
                            ? ($lang['news_no_posts'] ?? 'No se encontraron noticias con esos criterios.') 
                            : 'La sección de noticias no está disponible temporalmente.' 
                        ?>
                    </p>
                    <?php if(!empty($search)): ?>
                        <a href="<?= Router::url('noticias') ?>" class="btn btn-link mt-2" style="color: #d90a2c;">Ver todas</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            
            <!-- Grid de Noticias -->
            <div class="row">
                <?php 
                $delay = 3;
                foreach ($noticias as $news): 
                    // 1. Fechas
                    $day = date('d', strtotime($news['created_at']));
                    $month = date('M', strtotime($news['created_at']));
                    
                    // 2. Enlace
                    $link = Router::url('noticia/' . $news['slug']);
                    
                    // 3. Imagen Inteligente (Misma lógica del home)
                    $defaultImg = 'img/news/news_1763672072.jpg';
                    $imgRaw = !empty($news['image_path']) ? $news['image_path'] : $defaultImg;
                    
                    if (strpos($imgRaw, 'http') === 0) {
                        $img = $imgRaw;
                    } elseif (strpos($imgRaw, 'assets/') === 0) {
                        $img = Router::url($imgRaw);
                    } else {
                        $img = Router::asset($imgRaw);
                    }

                    // 4. Traducción
                    $titulo = $news['title'];
                    $titulo = (defined('CURRENT_LANG') && CURRENT_LANG !== 'es' && class_exists('Translator')) 
                        ? Translator::translate($titulo, CURRENT_LANG) 
                        : $titulo;
                ?>
                <div class="col-xl-4 col-md-6 col-lg-6 wow fadeInUp d-flex align-items-stretch mb-4" data-wow-delay=".<?= $delay ?>s">
                    
                    <!-- ESTRUCTURA IDÉNTICA AL HOME (news-card-items-3 style-4) -->
                    <div class="news-card-items-3 style-4" style="width: 100%;">
                        <div class="news-image">
                            <img src="<?= $img ?>" 
                                 alt="<?= htmlspecialchars($titulo) ?>" 
                                 style="height: 280px; object-fit: cover; width: 100%;"
                                 onerror="this.onerror=null;this.src='<?= Router::asset($defaultImg) ?>';">
                        </div>
                        
                        <div class="news-content">
                            <ul class="post-meta">
                                <li class="post"><?= $day ?> <span><?= $month ?></span></li>
                                <li>
                                    <i class="fa-regular fa-tag"></i> 
                                    <?= $lang['nav_noticias'] ?? 'Noticia' ?>
                                </li>
                            </ul>
                            
                            <h4>
                                <a href="<?= $link ?>"><?= htmlspecialchars($titulo) ?></a>
                            </h4>
                            
                            <!-- Empujar botón al fondo -->
                            <div class="mt-auto">
                                <a href="<?= $link ?>" class="link-btn">
                                    <?= $lang['btn_leer_mas'] ?? 'Leer Más' ?> 
                                    <i class="fa-sharp fa-regular fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- FIN ESTRUCTURA -->

                </div>
                <?php 
                $delay += 2; // Efecto cascada en animación
                if($delay > 9) $delay = 3;
                endforeach; 
                ?>
            </div>

            <!-- Paginación -->
            <?php if ($totalPages > 1): ?>
            <nav class="mt-5 d-flex justify-content-center wow fadeInUp" data-wow-delay=".5s">
                <ul class="pagination">
                    <?php if($p > 1): ?>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle mx-1 text-dark" href="?page=noticias&p=<?= $p-1 ?>&q=<?= urlencode($search) ?>">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php for($i=1; $i<=$totalPages; $i++): ?>
                        <li class="page-item">
                            <a class="page-link border-0 rounded-circle mx-1" 
                               style="<?= $p == $i ? 'background-color: #d90a2c; color: white;' : 'color: #333;' ?>" 
                               href="?page=noticias&p=<?= $i ?>&q=<?= urlencode($search) ?>">
                               <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if($p < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle mx-1 text-dark" href="?page=noticias&p=<?= $p+1 ?>&q=<?= urlencode($search) ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</section>