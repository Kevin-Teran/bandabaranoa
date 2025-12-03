<?php
/**
 * @file test.php
 * @route /test.php
 * @description Script de diagnóstico completo del sistema
 * @author Kevin Mariano
 * @version 1.0.0
 */

require_once 'config.php';

$tests = [];
$hasErrors = false;

// --- TEST 1: Rutas y Entorno ---
$tests['Environment'] = [
    'BASE_PATH' => defined('BASE_PATH') ? BASE_PATH : 'FAIL',
    'BASE_URL'  => defined('BASE_URL') ? BASE_URL : 'FAIL',
    'Modo'      => defined('ENVIRONMENT') ? ENVIRONMENT : 'FAIL',
    'PHP Ver'   => phpversion()
];

// --- TEST 2: Base de Datos ---
try {
    if (class_exists('Database')) {
        $db = Database::getInstance();
        $res = $db->fetchOne("SELECT @@version as ver");
        $tests['Database'] = ['Status' => '✅ Conectado', 'MySQL Ver' => $res['ver']];
    } else {
        $tests['Database'] = ['Status' => '❌ Clase Database no encontrada'];
        $hasErrors = true;
    }
} catch (Exception $e) {
    $tests['Database'] = ['Status' => '❌ Error Conexión', 'Msg' => $e->getMessage()];
    $hasErrors = true;
}

// --- TEST 3: Permisos de Escritura (Logs/Uploads) ---
$writableDirs = [
    '/assets/img/news',
    '/assets/img/events',
    '/assets/img/gallery'
];
foreach ($writableDirs as $dir) {
    $fullPath = BASE_PATH . $dir;
    if (!is_dir($fullPath)) {
        // Intentar crear si no existe
        @mkdir($fullPath, 0755, true);
    }
    $tests['Permisos'][$dir] = is_writable($fullPath) ? '✅ Escribible' : '❌ NO Escribible';
    if (!is_writable($fullPath)) $hasErrors = true;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Diagnóstico del Sistema</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; padding: 2rem; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #111827; border-bottom: 2px solid #e5e7eb; padding-bottom: 1rem; }
        h2 { color: #374151; margin-top: 2rem; font-size: 1.2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 0.75rem; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-weight: 600; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; }
        .success { background: #d1fae5; color: #065f46; }
        .fail { background: #fee2e2; color: #991b1b; }
        .url-check { background: #eff6ff; padding: 1.5rem; border-radius: 8px; border: 1px dashed #3b82f6; text-align: center; margin-top: 2rem; }
        .btn { background: #2563eb; color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛠 Diagnóstico del Sistema</h1>
        
        <?php if($hasErrors): ?>
            <div style="background:#fee2e2; color:#991b1b; padding:1rem; border-radius:6px; margin-bottom:1rem;">
                ⚠️ Se encontraron errores críticos. Revise los items en rojo.
            </div>
        <?php else: ?>
            <div style="background:#d1fae5; color:#065f46; padding:1rem; border-radius:6px; margin-bottom:1rem;">
                ✅ Todos los sistemas base están operativos.
            </div>
        <?php endif; ?>

        <h2>1. Configuración del Entorno</h2>
        <table>
            <?php foreach($tests['Environment'] as $k => $v): ?>
            <tr>
                <td><?= $k ?></td>
                <td><code><?= $v ?></code></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h2>2. Base de Datos</h2>
        <table>
            <?php foreach($tests['Database'] as $k => $v): ?>
            <tr>
                <td><?= $k ?></td>
                <td><?= $v ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h2>3. Permisos de Carpetas</h2>
        <table>
            <?php foreach($tests['Permisos'] as $dir => $status): ?>
            <tr>
                <td><?= $dir ?></td>
                <td><?= $status ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="url-check">
            <h3>Prueba de URLs Amigables</h3>
            <p>Haz clic abajo. Si ves la página de noticias correctamente, el <code>.htaccess</code> funciona.</p>
            <a href="<?= Router::url('noticias') ?>" target="_blank" class="btn">Probar Enlace: /noticias</a>
            <br><br>
            <small>Si recibes un error 404 del servidor (no de PHP), revisa que el archivo .htaccess esté en la raíz.</small>
        </div>
    </div>
</body>
</html>