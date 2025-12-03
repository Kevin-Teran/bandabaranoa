<?php
/**
 * @file footer.php
 * @route /admin/includes/footer.php
 * @description Footer del panel administrativo
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

if (!defined('BASE_URL')) {
    die('Acceso denegado');
}
?>
    </div> 
    
    <script src="<?php echo BASE_URL; ?>/assets/js/jquery-3.7.1.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#sidebar-wrapper").toggleClass("show-sidebar");
                $("#mobileOverlay").toggleClass("active");
            });

            $("#mobileOverlay").click(function() {
                $("#sidebar-wrapper").removeClass("show-sidebar");
                $(this).removeClass("active");
            });

            setTimeout(function() {
                $('.alert-dismissible').fadeOut('slow');
            }, 4000);
        });
    </script>
</body>
</html>