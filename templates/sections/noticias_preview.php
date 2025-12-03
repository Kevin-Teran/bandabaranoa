<?php
/**
 * @file noticias_preview.php
 * @route /templates/sections/noticias_preview.php
 * @description Sección Híbrida: Traduce estructura con Archivos y contenido DB con API.
 * @author Kevin Mariano
 * @version 1.0.2
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

$db = Database::getInstance();
global $lang;

$latestNews = $db->isConnected() 
    ? $db->fetchAll("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT 3") 
    : [];
?>

<section class="news-section-3 section-padding fix" id="noticias-preview">
    <div class="container">
        
        <div class="section-title text-center">
            <span class="sub-title wow fadeInUp">
                <?php echo $lang['news_sub'] ?? 'Blog y Actualidad'; ?>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".2s">
                <?php echo $lang['news_title'] ?? 'Noticias Recientes'; ?>
            </h2>
        </div>
        
        <div class="row">
            <?php if (!empty($latestNews)): ?>
                <?php 
                $delay = 3; 
                foreach ($latestNews as $news): 
                    $day = date('d', strtotime($news['created_at']));
                    $month = date('M', strtotime($news['created_at'])); 
                    
                    $link = Router::url('noticia/' . $news['slug']); 
                    
                    $defaultImg = 'img/news/news_1763672072.jpg';
                    
                    $imgPath = !empty($news['image_path']) ? $news['image_path'] : $defaultImg;
                    
                    if (strpos($imgPath, 'http') === 0) {
                        $img = $imgPath;
                    } elseif (strpos($imgPath, 'assets/') === 0) {
                        $img = Router::url($imgPath);
                    } else {
                        $img = Router::asset($imgPath);
                    }
                    
                    $titulo_noticia = $news['title'];
                    if (defined('CURRENT_LANG') && CURRENT_LANG !== 'es' && class_exists('Translator')) {
                        $titulo_noticia = Translator::translate($titulo_noticia, CURRENT_LANG);
                    }
                ?>
                <div class="col-xl-4 col-md-6 col-lg-6 wow fadeInUp d-flex align-items-stretch" data-wow-delay=".<?php echo $delay; ?>s">
                    <div class="news-card-items-3 style-4 h-100 d-flex flex-column" style="width: 100%;">
                        <div class="news-image">
                            <img src="<?php echo $img; ?>" 
                                 alt="<?php echo htmlspecialchars($titulo_noticia); ?>" 
                                 style="height: 280px; object-fit: cover; width: 100%;"
                                 onerror="this.onerror=null;this.src='<?php echo Router::asset($defaultImg); ?>';">
                        </div>
                        
                        <div class="news-content d-flex flex-column flex-grow-1">
                            <ul class="post-meta">
                                <li class="post"><?php echo $day; ?><span><?php echo $month; ?></span></li>
                                <li>
                                    <i class="fa-regular fa-tag"></i> 
                                    <?php echo $lang['nav_noticias'] ?? 'Noticia'; ?>
                                </li>
                            </ul>
                            
                            <h4>
                                <a href="<?php echo $link; ?>">
                                    <?php echo htmlspecialchars($titulo_noticia); ?>
                                </a>
                            </h4>
                            
                            <div class="mt-auto">
                                <a href="<?php echo $link; ?>" class="link-btn">
                                    <?php echo $lang['btn_leer_mas'] ?? 'Leer Más'; ?> 
                                    <i class="fa-sharp fa-regular fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                $delay += 2; 
                endforeach; 
                ?>
            <?php else: ?>
                <div class="col-12 text-center p-5 wow fadeInUp">
                    <div class="alert alert-light d-inline-block">
                        <i class="fa-regular fa-newspaper fa-2x mb-2 text-muted"></i>
                        <p class="mb-0 text-muted">
                            <?php echo $lang['news_empty'] ?? 'No hay noticias recientes para mostrar en este momento.'; ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5 wow fadeInUp" data-wow-delay=".9s">
            <a href="<?php echo Router::url('noticias'); ?>" class="theme-btn">
                <?php echo $lang['news_btn_all'] ?? 'Ver Todas las Noticias'; ?>
            </a>
        </div>
    </div>
</section>