<?php
/**
 * @file login_view.php
 * @route /admin/login_view.php
 * @description Vista HTML del formulario de login.
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/img/favicon.png">
    <title>Acceso Restringido | Banda de Baranoa</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body {
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card img { max-width: 180px; margin-bottom: 20px; }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            background-color: #e30000; /* Rojo Tema */
            color: white;
            border: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-login:hover { background-color: #cc0000; }
        .error-msg { color: #dc3545; font-size: 14px; margin-bottom: 15px; display: block; }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="../assets/img/logo/black-logo.png" alt="Logo Banda">
        
        <h4 class="mb-4">Gestor de Contenidos</h4>
        
        <?php if (!empty($error)): ?>
            <span class="error-msg"><?php echo $error; ?></span>
        <?php endif; ?>

        <form method="POST" action="index.php">
            <input type="text" class="form-control" name="username" placeholder="Usuario" required autofocus autocomplete="off">
            <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
            <button type="submit" name="login" class="btn-login">Ingresar</button>
        </form>
        
        <div class="mt-4">
            <a href="../" style="text-decoration: none; color: #666; font-size: 14px;">← Volver a la página principal</a>
        </div>
    </div>
</body>
</html>