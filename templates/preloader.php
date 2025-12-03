<?php
/**
 * @file preloader.php
 * @route /templates/preloader.php
 * @description Plantilla para la animación de precarga del sitio.
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */
?>
<div id="preloader" class="preloader">
    <div class="animation-preloader">
        <div class="spinner"></div>
        <div class="txt-loading">
            <span data-text-preloader="B" class="letters-loading">B</span>
            <span data-text-preloader="A" class="letters-loading">A</span>
            <span data-text-preloader="N" class="letters-loading">N</span>
            <span data-text-preloader="D" class="letters-loading">D</span>
            <span data-text-preloader="A" class="letters-loading">A</span>
            <span data-text-preloader=" " class="letters-loading">&nbsp;</span>
            <span data-text-preloader="D" class="letters-loading">D</span>
            <span data-text-preloader="E" class="letters-loading">E</span>
            <span data-text-preloader=" " class="letters-loading">&nbsp;</span>
            <span data-text-preloader="B" class="letters-loading">B</span>
            <span data-text-preloader="A" class="letters-loading">A</span>
            <span data-text-preloader="R" class="letters-loading">R</span>
            <span data-text-preloader="A" class="letters-loading">A</span>
            <span data-text-preloader="N" class="letters-loading">N</span>
            <span data-text-preloader="O" class="letters-loading">O</span>
            <span data-text-preloader="A" class="letters-loading">A</span>
        </div>
        <p class="text-center">Cargando...</p>
    </div>
    <div class="loader">
        <div class="row">
            <div class="col-3 loader-section section-left"><div class="bg"></div></div>
            <div class="col-3 loader-section section-left"><div class="bg"></div></div>
            <div class="col-3 loader-section section-right"><div class="bg"></div></div>
            <div class="col-3 loader-section section-right"><div class="bg"></div></div>
        </div>
    </div>
</div>