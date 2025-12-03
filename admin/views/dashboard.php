<?php
/**
 * @file dashboard.php
 * @route /admin/views/dashboard.php
 * @description 
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

if (!defined('ROOT_PATH')) exit('Acceso Denegado');
$db = Database::getInstance();
// Stats placeholders
try {
    $newsC = $db->fetchOne("SELECT COUNT(*) as c FROM news")['c'] ?? 0;
    $eventsC = $db->fetchOne("SELECT COUNT(*) as c FROM events")['c'] ?? 0;
    $galleryC = $db->fetchOne("SELECT COUNT(*) as c FROM gallery")['c'] ?? 0;
} catch (Exception $e) { $newsC = 0; $eventsC = 0; $galleryC = 0; }
?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1 text-dark">Bienvenido, Admin 👋</h2>
        <p class="text-muted mb-0">Aquí tienes un resumen de tu plataforma hoy.</p>
    </div>
    <div class="d-none d-md-block">
        <span class="badge bg-white text-dark py-2 px-3 shadow-sm border rounded-pill">
            <i class="fa-regular fa-calendar me-2"></i> <?php echo date('d M, Y'); ?>
        </span>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-4">
        <div class="card-modern h-100 p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="d-block text-uppercase fw-bold text-muted small mb-2">Noticias</span>
                    <h1 class="display-4 fw-bold mb-0 text-dark"><?php echo $newsC; ?></h1>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: #e0f2fe;">
                    <i class="fa-regular fa-newspaper fa-xl" style="color: #0284c7;"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-success small fw-bold"><i class="fa-solid fa-arrow-trend-up"></i> Activas</span>
                <a href="index.php?view=noticias" class="btn btn-sm btn-light rounded-pill px-3">Ver todo</a>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card-modern h-100 p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="d-block text-uppercase fw-bold text-muted small mb-2">Eventos</span>
                    <h1 class="display-4 fw-bold mb-0 text-dark"><?php echo $eventsC; ?></h1>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: #dcfce7;">
                    <i class="fa-regular fa-calendar fa-xl" style="color: #16a34a;"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-success small fw-bold"><i class="fa-solid fa-check"></i> Programados</span>
                <a href="index.php?view=eventos" class="btn btn-sm btn-light rounded-pill px-3">Ver todo</a>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card-modern h-100 p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="d-block text-uppercase fw-bold text-muted small mb-2">Multimedia</span>
                    <h1 class="display-4 fw-bold mb-0 text-dark"><?php echo $galleryC; ?></h1>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: #fef3c7;">
                    <i class="fa-solid fa-camera fa-xl" style="color: #d97706;"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">Imágenes subidas</span>
                <a href="index.php?view=galeria" class="btn btn-sm btn-light rounded-pill px-3">Ver todo</a>
            </div>
        </div>
    </div>
</div>