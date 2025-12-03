<?php
/**
 * @file auditoria.php
 * @route /admin/views/auditoria.php
 * @description 
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

if (!defined('ROOT_PATH')) exit('Acceso Denegado');
$db = Database::getInstance();

// Configuración
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Construcción de Consulta Segura
$baseSql = "FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id";
$whereSql = "";
$params = [];

if ($search) {
    // USO DE PARÁMETROS ÚNICOS para evitar error PDO
    $whereSql = " WHERE (a.action LIKE :s1 OR a.description LIKE :s2 OR u.username LIKE :s3)";
    $params[':s1'] = "%$search%";
    $params[':s2'] = "%$search%";
    $params[':s3'] = "%$search%";
}

// Obtener Totales
$totalRows = $db->fetchOne("SELECT COUNT(*) as total $baseSql $whereSql", $params)['total'];
$totalPages = ceil($totalRows / $limit);

// Obtener Logs
$logs = $db->fetchAll("SELECT a.*, u.username $baseSql $whereSql ORDER BY a.created_at DESC LIMIT $limit OFFSET $offset", $params);
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Registro de Actividad</h2>
        <p class="text-muted mb-0">Monitoreo de acciones en el sistema.</p>
    </div>
    
    <form method="GET" class="d-flex shadow-sm rounded overflow-hidden">
        <input type="hidden" name="view" value="auditoria">
        <input type="text" name="q" class="form-control border-0" placeholder="Buscar usuario, acción..." value="<?php echo htmlspecialchars($search); ?>" style="min-width: 250px;">
        <button class="btn btn-dark rounded-0 px-3"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
</div>

<div class="card-modern table-responsive">
    <table class="table-modern w-100">
        <thead>
            <tr class="text-uppercase text-muted small">
                <th class="ps-4">Fecha / Hora</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Descripción</th>
                <th class="text-end pe-4">IP</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logs)): ?>
                <tr><td colspan="5" class="text-center py-5 text-muted fw-light">No hay registros de auditoría disponibles.</td></tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td class="ps-4 text-muted small">
                        <i class="fa-regular fa-clock me-2"></i><?php echo date('d M Y, h:i A', strtotime($log['created_at'])); ?>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px;">
                                <i class="fa-solid fa-user text-secondary small"></i>
                            </div>
                            <span class="fw-bold text-dark"><?php echo htmlspecialchars($log['username'] ?? 'Sistema'); ?></span>
                        </div>
                    </td>
                    <td>
                        <?php 
                            $color = match($log['action']) {
                                'CREAR' => 'success',
                                'ELIMINAR' => 'danger',
                                'EDITAR' => 'warning',
                                'LOGIN' => 'primary',
                                default => 'secondary'
                            };
                        ?>
                        <span class="badge bg-<?php echo $color; ?> bg-opacity-10 text-<?php echo $color; ?> border border-<?php echo $color; ?> rounded-pill px-3">
                            <?php echo $log['action']; ?>
                        </span>
                    </td>
                    <td class="text-dark"><?php echo htmlspecialchars($log['description']); ?></td>
                    <td class="text-end pe-4 text-monospace text-muted small"><?php echo $log['ip_address']; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<div class="d-flex justify-content-center mt-4">
    <nav>
        <ul class="pagination">
            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link border-0 shadow-sm mx-1 rounded" href="?view=auditoria&page=<?php echo $page-1; ?>&q=<?php echo $search; ?>"><i class="fa-solid fa-chevron-left"></i></a>
            </li>
            <li class="page-item disabled">
                <span class="page-link border-0 bg-transparent fw-bold mx-2">Página <?php echo $page; ?> de <?php echo $totalPages; ?></span>
            </li>
            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link border-0 shadow-sm mx-1 rounded" href="?view=auditoria&page=<?php echo $page+1; ?>&q=<?php echo $search; ?>"><i class="fa-solid fa-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>