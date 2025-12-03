<?php 
/**
 * @file sidebar.php
 * @route /admin/includes/sidebar.php
 * @description 
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

$view = $_GET['view'] ?? 'dashboard'; 
?>
<nav id="sidebar-wrapper">
    <div class="sidebar-brand">
        <img src="<?php echo BASE_URL; ?>/assets/img/logo/black-logo.png" alt="Logo">
    </div>
    
    <div class="d-flex flex-column flex-grow-1">
        
        <div class="nav-label">Principal</div>
        
        <a href="index.php?view=dashboard" class="nav-link <?php echo $view == 'dashboard' ? 'active' : ''; ?>">
            <i class="fa-solid fa-grid-2"></i> 
            <span>Dashboard</span>
        </a>

        <div class="nav-label">Contenido</div>
        
        <a href="index.php?view=noticias" class="nav-link <?php echo $view == 'noticias' ? 'active' : ''; ?>">
            <i class="fa-regular fa-newspaper"></i> 
            <span>Noticias</span>
        </a>
        
        <a href="index.php?view=eventos" class="nav-link <?php echo $view == 'eventos' ? 'active' : ''; ?>">
            <i class="fa-regular fa-calendar"></i> 
            <span>Eventos</span>
        </a>
        
        <a href="index.php?view=galeria" class="nav-link <?php echo $view == 'galeria' ? 'active' : ''; ?>">
            <i class="fa-regular fa-images"></i> 
            <span>Galería</span>
        </a>
        
        </div>

    <div class="sidebar-footer">
        <a href="<?php echo BASE_URL; ?>" target="_blank" class="nav-link">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> 
            <span>Ver web actual</span>
        </a>
        
        <button onclick="confirmLogout()" class="nav-link w-100 text-start border-0 bg-transparent text-danger mt-1">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> 
            <span>Cerrar Sesión</span>
        </button>
    </div>
</nav>

<script>
function confirmLogout() {
    Swal.fire({
        title: '¿Salir?',
        text: "Cerrarás tu sesión actual.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#111827',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, salir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) window.location.href = 'index.php?action=logout';
    })
}
</script>