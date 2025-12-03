<?php
/**
 * @file eventos.php
 * @route /pages/eventos.php
 * @description Página de listado de eventos.
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

$db = Database::getInstance();
global $lang;

// 1. Configuración
$limit = 9; 
$p = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($p - 1) * $limit;

// 2. Variables Iniciales
$events = [];
$totalRows = 0;
$totalPages = 0;

// 3. Consulta Segura
if ($db->isConnected()) {
    $whereSQL = "status = 'published'";
    
    // Contar
    $countQuery = $db->fetchOne("SELECT COUNT(*) as c FROM events WHERE $whereSQL");
    $totalRows = $countQuery ? $countQuery['c'] : 0;
    $totalPages = ceil($totalRows / $limit);
    
    // Obtener Eventos (Ordenados por fecha de inicio)
    $events = $db->fetchAll("SELECT * FROM events WHERE $whereSQL ORDER BY start_date DESC LIMIT $limit OFFSET $offset");
}

// Array auxiliar para meses (Si falla la traducción del sistema)
$months = [
    '01'=>'ENE', '02'=>'FEB', '03'=>'MAR', '04'=>'ABR', '05'=>'MAY', '06'=>'JUN',
    '07'=>'JUL', '08'=>'AGO', '09'=>'SEP', '10'=>'OCT', '11'=>'NOV', '12'=>'DIC'
];
?>

<style>
    .nav-spacer { height: 120px; display: block; background: #fff; }

    /* Tarjeta de Evento */
    .event-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
        height: 100%;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    
    /* Caja de Fecha */
    .event-date-box {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #fff;
        border-radius: 10px;
        padding: 8px 12px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        min-width: 60px;
        z-index: 2;
    }
    .event-date-box .day { font-size: 1.4rem; font-weight: 800; line-height: 1; color: #E63946; display: block; }
    .event-date-box .month { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #333; letter-spacing: 1px; }
    
    /* Imagen */
    .event-img-wrap { position: relative; height: 220px; overflow: hidden; }
    .event-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .event-card:hover .event-img-wrap img { transform: scale(1.05); }

    /* Badge */
    .location-badge {
        display: inline-flex; align-items: center;
        background: #f8f9fa; color: #666;
        padding: 5px 12px; border-radius: 50px; font-size: 0.85rem;
        margin-bottom: 12px;
    }
</style>

<div class="nav-spacer"></div>

<section class="event-section section-padding fix">
    <div class="container">
        
        <div class="row mb-5">
             <div class="col-12 text-center">
                 <span class="sub-title fw-bold text-uppercase wow fadeInUp" style="color: #E63946; letter-spacing: 2px;">
                     <?= $lang['events_badge'] ?? 'Agenda' ?>
                 </span>
                 <h2 class="fw-bold text-dark display-5 wow fadeInUp" data-wow-delay=".2s">
                     <?= $lang['events_title'] ?? 'Próximos Eventos' ?>
                 </h2>
             </div>
        </div>

        <?php if (empty($events)): ?>
            <!-- Estado Vacío / Error -->
            <div class="text-center py-5 wow fadeInUp">
                <div class="mb-3"><i class="fa-regular fa-calendar-xmark fa-3x text-muted opacity-25"></i></div>
                <h4 class="text-muted">
                    <?= $db->isConnected() 
                        ? ($lang['events_empty'] ?? 'No hay eventos programados por ahora.') 
                        : 'La agenda no está disponible temporalmente.' 
                    ?>
                </h4>
            </div>
        <?php else: ?>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($events as $evt): 
                    // 1. Imagen Inteligente
                    $defaultImg = 'assets/img/events/evt_1763672101.jpg'; // Imagen real existente
                    $imgRaw = !empty($evt['image_path']) ? $evt['image_path'] : $defaultImg;
                    
                    if (strpos($imgRaw, 'http') === 0) {
                        $imgUrl = $imgRaw;
                    } elseif (strpos($imgRaw, 'assets/') === 0) {
                        $imgUrl = Router::url($imgRaw);
                    } else {
                        $imgUrl = Router::asset($imgRaw);
                    }

                    // 2. Fechas
                    $timestamp = strtotime($evt['start_date']);
                    $day = date('d', $timestamp);
                    $monthKey = date('m', $timestamp);
                    $month = $months[$monthKey] ?? 'MES';
                    $time = date('h:i A', $timestamp);
                    
                    // 3. Enlace con Slug (CORREGIDO)
                    // Aseguramos que el slug no esté vacío, si lo está usamos el ID como fallback
                    $slug = !empty($evt['slug']) ? $evt['slug'] : $evt['id'];
                    $detailLink = Router::url('evento/' . $slug);

                    // 4. Traducción Título
                    $titulo = $evt['title'];
                    if (defined('CURRENT_LANG') && CURRENT_LANG !== 'es' && class_exists('Translator')) {
                        $titulo = Translator::translate($titulo, CURRENT_LANG);
                    }
                ?>
                <div class="col wow fadeInUp" data-wow-delay=".3s">
                    <div class="event-card shadow-sm d-flex flex-column">
                        
                        <div class="event-img-wrap">
                            <div class="event-date-box">
                                <span class="day"><?= $day ?></span>
                                <span class="month"><?= $month ?></span>
                            </div>
                            <a href="<?= $detailLink ?>">
                                <img src="<?= $imgUrl ?>" 
                                     alt="<?= htmlspecialchars($titulo) ?>"
                                     onerror="this.onerror=null;this.src='<?= Router::asset($defaultImg) ?>';">
                            </a>
                        </div>
                        
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            
                            <div class="mb-2 d-flex align-items-center justify-content-between">
                                <div class="location-badge text-truncate" style="max-width: 70%;">
                                    <i class="fa-solid fa-location-dot me-2 text-danger"></i> 
                                    <?= mb_strimwidth($evt['location'], 0, 20, '...') ?>
                                </div>
                                <span class="small text-muted fw-bold">
                                    <i class="fa-regular fa-clock me-1"></i> <?= $time ?>
                                </span>
                            </div>
                            
                            <h5 class="card-title fw-bold mb-3">
                                <a href="<?= $detailLink ?>" class="text-dark text-decoration-none stretched-link-custom">
                                    <?= htmlspecialchars($titulo) ?>
                                </a>
                            </h5>
                            
                            <div class="mt-auto">
                                <a href="<?= $detailLink ?>" class="btn w-100 rounded-pill fw-bold py-2" 
                                   style="background-color: #E63946; color: white; border: none; transition: all 0.3s;">
                                    <?= $lang['btn_view_event'] ?? 'Ver Detalles' ?> 
                                    <i class="fa-solid fa-arrow-right ms-2"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
            <nav class="mt-5 d-flex justify-content-center wow fadeInUp">
                <ul class="pagination">
                    <!-- Anterior -->
                    <?php if($p > 1): ?>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle mx-1" href="<?= Router::url('eventos') ?>?p=<?= $p-1 ?>">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Números -->
                    <?php for($i=1; $i<=$totalPages; $i++): ?>
                        <li class="page-item <?= $p == $i ? 'active' : ''; ?>">
                            <a class="page-link border-0 rounded-circle mx-1 shadow-sm" 
                               style="<?= $p == $i ? 'background-color: #E63946; color: white;' : 'color: #333;' ?>" 
                               href="<?= Router::url('eventos') ?>?p=<?= $i ?>">
                               <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Siguiente -->
                    <?php if($p < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle mx-1" href="<?= Router::url('eventos') ?>?p=<?= $p+1 ?>">
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