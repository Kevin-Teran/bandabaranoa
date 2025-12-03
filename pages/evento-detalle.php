<?php
/**
 * @file evento-detalle.php
 * @route /pages/evento-detalle.php
 * @description Vista individual completa de un evento con función "Agendar".
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

$db = Database::getInstance();
global $lang;

// 1. Detectar Identificador (Slug o ID)
$slug = $_GET['slug'] ?? null;
$id = $_GET['id'] ?? null;
$evt = false;

// 2. Buscar Evento
if ($slug) {
    $evt = $db->fetchOne("SELECT * FROM events WHERE slug = :s AND status = 'published'", [':s' => $slug]);
} elseif ($id) {
    $evt = $db->fetchOne("SELECT * FROM events WHERE id = :id AND status = 'published'", [':id' => $id]);
}

// 3. Validación y Redirección Segura
if (!$evt) {
    // Usamos Router::url para asegurar que la redirección vaya a la raíz correcta
    // Evita el error "bandabaranoa/evento/index.php"
    $redirectUrl = class_exists('Router') ? Router::url('eventos') : 'index.php?page=eventos';
    
    echo "<script>window.location.href='$redirectUrl';</script>";
    exit;
}

// 4. Preparar Datos
// Imagen Inteligente
$defaultImg = 'assets/img/default-event.jpg';
$imgRaw = !empty($evt['image_path']) ? $evt['image_path'] : $defaultImg;

if (strpos($imgRaw, 'http') === 0) {
    $img = $imgRaw;
} elseif (strpos($imgRaw, 'assets/') === 0) {
    $img = Router::url($imgRaw);
} else {
    $img = Router::asset($imgRaw);
}

$titulo = $evt['title'];
$desc = $evt['description'];
$lugar = $evt['location'];
$inicio = strtotime($evt['start_date']);
$fin = !empty($evt['end_date']) ? strtotime($evt['end_date']) : $inicio + 7200; 

// Traducción Dinámica del Título
if (defined('CURRENT_LANG') && CURRENT_LANG !== 'es' && class_exists('Translator')) {
    $tituloTrad = Translator::translate($titulo, CURRENT_LANG);
    if (!empty($tituloTrad)) $titulo = $tituloTrad;
    
    // Opcional: Traducir descripción
    // $desc = Translator::translate($desc, CURRENT_LANG);
}

// Formatos Visuales
$dia = date('d', $inicio);
$mes = date('M', $inicio);
$hora = date('h:i A', $inicio);
// Formato de fecha localizado manual o simple
$fechaTexto = date('l, d F Y', $inicio);

// 5. Link Google Calendar
$gStart = date('Ymd\THis', $inicio); 
$gEnd = date('Ymd\THis', $fin);

$gLink = "https://calendar.google.com/calendar/render?action=TEMPLATE";
$gLink .= "&text=" . urlencode($titulo);
$gLink .= "&dates=" . $gStart . "/" . $gEnd;
$gLink .= "&details=" . urlencode(strip_tags($desc));
$gLink .= "&location=" . urlencode($lugar);
$gLink .= "&sf=true&output=xml";
?>

<style>
    /* Estilos Base */
    .nav-spacer { height: 100px; background: #fff; }
    
    /* Header con Imagen Oscurecida */
    .event-header-bg {
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?php echo $img; ?>');
        background-size: cover;
        background-position: center;
        padding: 100px 0 140px 0;
        color: white;
        position: relative;
    }

    /* Caja Flotante */
    .info-box {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        padding: 30px;
        margin-top: -60px;
        position: relative;
        z-index: 10;
        border: 1px solid #eee;
    }

    /* Iconos */
    .icon-circle {
        width: 55px; height: 55px;
        background: #f8f9fa; 
        color: #000;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin-right: 15px;
        border: 1px solid #ddd;
    }

    .text-pure-black { color: #000000 !important; }
    .text-dark-gray { color: #333333 !important; }
    
    .content-body {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
    }
</style>

<div class="nav-spacer"></div>

<section class="event-header-bg text-center">
    <div class="container">
        <span class="badge bg-danger px-3 py-2 mb-3 text-uppercase fw-bold" style="letter-spacing: 1px;">
            <?= $lang['events_badge'] ?? 'Evento' ?>
        </span>
        <h1 class="display-4 fw-bold text-white mb-3"><?php echo htmlspecialchars($titulo); ?></h1>
        <p class="fs-4 opacity-90 text-white">
            <i class="fa-regular fa-calendar me-2"></i> <?php echo $fechaTexto; ?>
        </p>
    </div>
</section>

<section class="section-padding pt-0">
    <div class="container">
        <div class="row g-5">
            
            <div class="col-lg-8">
                
                <!-- Caja Flotante de Info -->
                <div class="info-box d-flex flex-wrap align-items-center justify-content-between gap-4">
                    
                    <div class="d-flex align-items-center">
                        <div class="icon-circle"><i class="fa-solid fa-calendar-day text-danger"></i></div>
                        <div>
                            <h6 class="mb-0 fw-bold text-uppercase text-muted small">Fecha</h6>
                            <span class="fw-bold text-pure-black fs-5"><?php echo $dia . ' ' . $mes; ?></span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="icon-circle"><i class="fa-regular fa-clock text-danger"></i></div>
                        <div>
                            <h6 class="mb-0 fw-bold text-uppercase text-muted small">Hora</h6>
                            <span class="fw-bold text-pure-black fs-5"><?php echo $hora; ?></span>
                        </div>
                    </div>

                    <div class="ms-auto-md">
                        <a href="<?php echo $gLink; ?>" target="_blank" class="btn btn-dark rounded-pill px-4 py-2 shadow-sm fw-bold">
                            <i class="fa-brands fa-google me-2"></i> Agendar
                        </a>
                    </div>
                </div>

                <div class="content-body mt-5">
                    <h3 class="fw-bold text-pure-black mb-4">Detalles del Evento</h3>
                    <div class="text-dark-gray">
                        <?php echo nl2br($desc); ?>
                    </div>
                </div>

                <?php if(!empty($evt['map_url'])): ?>
                <div class="mt-5 p-4 bg-light rounded-4 border">
                    <h4 class="fw-bold text-pure-black mb-3"><i class="fa-solid fa-map-location-dot me-2"></i> Ubicación</h4>
                    <p class="text-dark-gray mb-3"><?php echo $lugar; ?></p>
                    
                    <a href="<?php echo $evt['map_url']; ?>" target="_blank" class="btn btn-outline-dark w-100 fw-bold">
                        Abrir Mapa en Google Maps <i class="fa-solid fa-arrow-up-right-from-square ms-2"></i>
                    </a>
                </div>
                <?php endif; ?>

            </div>

            <!-- Sidebar Lateral -->
            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border">
                    <h5 class="fw-bold text-pure-black mb-4 border-bottom pb-3">Resumen</h5>
                    
                    <ul class="list-unstyled">
                        <li class="mb-4">
                            <strong class="d-block text-dark small text-uppercase mb-1">Lugar</strong>
                            <span class="text-dark-gray fs-6"><i class="fa-solid fa-location-dot text-danger me-2"></i> <?php echo $lugar; ?></span>
                        </li>
                        
                        <li class="mb-4">
                            <strong class="d-block text-dark small text-uppercase mb-1">Entrada</strong>
                            <span class="text-dark-gray fs-6"><i class="fa-solid fa-ticket text-danger me-2"></i> Entrada Libre</span>
                        </li>

                        <li>
                            <strong class="d-block text-dark small text-uppercase mb-2">Compartir</strong>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-circle" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                                <a href="https://api.whatsapp.com/send?text=<?php echo urlencode("$titulo - ¡Vamos! " . "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" target="_blank" class="btn btn-outline-success btn-sm rounded-circle" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="mt-4 text-center">
                    <a href="<?= Router::url('eventos') ?>" class="btn btn-link text-dark text-decoration-none fw-bold">
                        <i class="fa-solid fa-arrow-left me-2"></i> Volver a la Agenda
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>