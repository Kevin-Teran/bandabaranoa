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

// --- CONFIGURACIÓN ---
$limit = 9; // Eventos por página
$p = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($p - 1) * $limit;

// --- CONSULTA ---
// Filtramos solo los publicados. 
// Ordenamos por start_date DESC para ver los últimos añadidos o más lejanos primero.
// (Si prefieres ver los más próximos a hoy primero, cambia a: ORDER BY start_date ASC)
$whereSQL = "status = 'published'";
$totalRows = $db->fetchOne("SELECT COUNT(*) as c FROM events WHERE $whereSQL")['c'];
$totalPages = ceil($totalRows / $limit);
$events = $db->fetchAll("SELECT * FROM events WHERE $whereSQL ORDER BY start_date DESC LIMIT $limit OFFSET $offset");

// Array auxiliar para meses en español corto
$months = [
    '01'=>'ENE', '02'=>'FEB', '03'=>'MAR', '04'=>'ABR', '05'=>'MAY', '06'=>'JUN',
    '07'=>'JUL', '08'=>'AGO', '09'=>'SEP', '10'=>'OCT', '11'=>'NOV', '12'=>'DIC'
];
?>

<style>
    /* Ajustes de Cabecera Fija */
    .header-area { background-color: #ffffff !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important; position: fixed !important; width: 100%; top: 0; z-index: 999; }
    .header-area .main-menu ul li a { color: #000000 !important; }
    .nav-spacer { height: 120px; display: block; background: #fff; }

    /* Tarjeta de Evento */
    .event-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
        height: 100%; /* Altura completa para flexbox */
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    
    /* Caja de Fecha Flotante */
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
    
    /* Imagen con Zoom */
    .event-img-wrap { position: relative; height: 220px; overflow: hidden; }
    .event-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .event-card:hover .event-img-wrap img { transform: scale(1.05); }

    /* Badge de Ubicación */
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
                 <span class="sub-title fw-bold text-uppercase" style="color: #E63946; letter-spacing: 2px;">Agenda</span>
                 <h2 class="fw-bold text-dark display-5">Próximos Eventos</h2>
             </div>
        </div>

        <?php if (empty($events)): ?>
            <div class="text-center py-5">
                <div class="mb-3"><i class="fa-regular fa-calendar-xmark fa-3x text-muted opacity-25"></i></div>
                <h4 class="text-muted">No hay eventos programados por ahora.</h4>
            </div>
        <?php else: ?>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($events as $evt): 
                    // Preparar Datos
                    $imgUrl = BASE_URL . '/' . (!empty($evt['image_path']) ? $evt['image_path'] : 'assets/img/default-event.jpg');
                    $day = date('d', strtotime($evt['start_date']));
                    $monthKey = date('m', strtotime($evt['start_date']));
                    $month = $months[$monthKey] ?? 'MES';
                    $time = date('h:i A', strtotime($evt['start_date']));
                    $detailLink = "index.php?page=evento-detalle&id=" . $evt['id'];
                ?>
                <div class="col">
                    <div class="event-card shadow-sm d-flex flex-column">
                        
                        <div class="event-img-wrap">
                            <div class="event-date-box">
                                <span class="day"><?php echo $day; ?></span>
                                <span class="month"><?php echo $month; ?></span>
                            </div>
                            <a href="<?php echo $detailLink; ?>">
                                <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($evt['title']); ?>">
                            </a>
                        </div>
                        
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            
                            <div class="mb-2 d-flex align-items-center justify-content-between">
                                <div class="location-badge text-truncate" style="max-width: 70%;">
                                    <i class="fa-solid fa-location-dot me-2 text-danger"></i> 
                                    <?php echo mb_strimwidth($evt['location'], 0, 20, '...'); ?>
                                </div>
                                <span class="small text-muted fw-bold">
                                    <i class="fa-regular fa-clock me-1"></i> <?php echo $time; ?>
                                </span>
                            </div>
                            
                            <h5 class="card-title fw-bold mb-3">
                                <a href="<?php echo $detailLink; ?>" class="text-dark text-decoration-none stretched-link-custom">
                                    <?php echo $evt['title']; ?>
                                </a>
                            </h5>
                            
                            <?php if(!empty($evt['description'])): ?>
                                <p class="card-text text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">
                                    <?php echo mb_strimwidth(strip_tags($evt['description']), 0, 90, '...'); ?>
                                </p>
                            <?php else: ?>
                                <div class="flex-grow-1"></div>
                            <?php endif; ?>

                            <div class="mt-auto">
                                <a href="<?php echo $detailLink; ?>" class="btn w-100 rounded-pill fw-bold py-2" 
                                   style="background-color: #E63946; color: white; border: none; transition: all 0.3s;">
                                    Ver Detalles y Agendar <i class="fa-solid fa-arrow-right ms-2"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
            <nav class="mt-5 d-flex justify-content-center">
                <ul class="pagination">
                    <li class="page-item <?php echo $p <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link border-0 rounded-circle mx-1" href="index.php?page=eventos&p=<?php echo $p-1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    
                    <?php for($i=1; $i<=$totalPages; $i++): ?>
                        <li class="page-item <?php echo $p == $i ? 'active' : ''; ?>">
                            <a class="page-link border-0 rounded-circle mx-1 shadow-sm" 
                               style="<?php echo $p == $i ? 'background-color: #E63946; color: white;' : 'color: #333;'; ?>" 
                               href="index.php?page=eventos&p=<?php echo $i; ?>">
                               <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php echo $p >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link border-0 rounded-circle mx-1" href="index.php?page=eventos&p=<?php echo $p+1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</section>