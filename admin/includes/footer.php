<?php
/**
 * @file footer.php
 * @route /admin/includes/footer.php
 * @description 
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

?>
    </div> <script src="<?php echo BASE_URL; ?>/assets/js/jquery-3.7.1.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // 1. Toggle Sidebar (Click en botón)
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#sidebar-wrapper").toggleClass("show-sidebar");
                $("#mobileOverlay").toggleClass("active");
            });

            // 2. Cerrar Sidebar (Click en fondo oscuro)
            $("#mobileOverlay").click(function() {
                $("#sidebar-wrapper").removeClass("show-sidebar");
                $(this).removeClass("active");
            });

            // 3. Auto-cerrar alertas
            setTimeout(function() {
                $('.alert-dismissible').fadeOut('slow');
            }, 4000);
        });
    </script>
</body>
</html>