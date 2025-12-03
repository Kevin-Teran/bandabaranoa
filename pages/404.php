<?php
/**
 * @file 404.php
 * @route /pages/404.php
 * @description Página 404 moderna y funcional
 * @author Kevin Mariano
 * @version 2.0.0
 * @copyright Banda de Baranoa 2025
 */

global $lang;
$page_title = "Página No Encontrada - 404";
?>

<style>
    /* Espaciador para compensar header fijo */
    .error-spacer {
        height: 100px;
        background: #fff;
    }

    .error-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 60px 20px;
    }

    .error-content {
        text-align: center;
        background: white;
        padding: 60px 40px;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .error-icon {
        font-size: 120px;
        color: #E63946;
        margin-bottom: 20px;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .error-number {
        font-size: 120px;
        font-weight: 900;
        color: #1c1c1c;
        line-height: 1;
        margin: 0;
        text-shadow: 3px 3px 0px #E63946;
    }

    .error-title {
        font-size: 28px;
        font-weight: 700;
        color: #1c1c1c;
        margin: 25px 0 15px 0;
    }

    .error-text {
        font-size: 18px;
        color: #666;
        margin-bottom: 35px;
        line-height: 1.6;
    }

    .error-btn {
        display: inline-block;
        background: #E63946;
        color: white !important;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(230, 57, 70, 0.3);
    }

    .error-btn:hover {
        background: #d90a2c;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(230, 57, 70, 0.4);
    }

    .error-links {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .error-links h5 {
        font-size: 16px;
        color: #1c1c1c;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .error-links a {
        display: inline-block;
        margin: 5px 10px;
        color: #E63946;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
    }

    .error-links a:hover {
        color: #d90a2c;
        text-decoration: underline;
    }
</style>

<div class="error-spacer"></div>

<section class="error-section">
    <div class="container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fa-duotone fa-music-slash"></i>
            </div>
            
            <h1 class="error-number">404</h1>
            
            <h2 class="error-title">¡Ups! Esta nota no existe</h2>
            
            <p class="error-text">
                La página que buscas no está disponible o fue movida. 
                <br>No te preocupes, podemos ayudarte a encontrar lo que necesitas.
            </p>
            
            <a href="<?php echo BASE_URL; ?>/" class="error-btn">
                <i class="fa-solid fa-house me-2"></i> Volver al Inicio
            </a>

            <div class="error-links">
                <h5>Enlaces Rápidos:</h5>
                <a href="<?php echo BASE_URL; ?>/eventos">Eventos</a> • 
                <a href="<?php echo BASE_URL; ?>/noticias">Noticias</a> • 
                <a href="<?php echo BASE_URL; ?>/galeria">Galería</a> • 
                <a href="<?php echo BASE_URL; ?>/#quienes-somos">Nosotros</a>
            </div>
        </div>
    </div>
</section>