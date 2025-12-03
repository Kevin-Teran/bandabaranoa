<?php
/**
 * @file eventos.php
 * @route /admin/views/eventos.php
 * @description Gestión de Eventos (CRUD completo con estilo moderno).
 * @author Kevin Mariano
 * @version 2.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

require_once __DIR__ . '/../includes/Audit.php';
$db = Database::getInstance();

// ==============================================================
// 1. BUSCADOR EN VIVO (AJAX)
// ==============================================================
if (isset($_GET['ajax_search'])) {
    while (ob_get_level()) ob_end_clean();
    
    $search = trim($_GET['q'] ?? '');
    $whereSQL = ""; 
    $params = [];
    
    if ($search) {
        $whereSQL = " WHERE title LIKE :s1 OR location LIKE :s2 OR description LIKE :s3";
        $params[':s1'] = "%$search%";
        $params[':s2'] = "%$search%";
        $params[':s3'] = "%$search%";
    }
    
    try {
        $events = $db->fetchAll("SELECT * FROM events $whereSQL ORDER BY start_date DESC LIMIT 20", $params);
        if (empty($events)) {
            echo '<tr><td colspan="6" class="text-center py-5 text-muted"><i class="fa-regular fa-calendar-xmark fa-2x mb-3 opacity-25"></i><p class="mb-0">No se encontraron eventos.</p></td></tr>';
        } else {
            foreach ($events as $item) renderRow($item);
        }
    } catch (Exception $e) {
        echo '<tr><td colspan="6" class="text-danger">Error: ' . $e->getMessage() . '</td></tr>';
    }
    exit;
}

// ==============================================================
// 2. LÓGICA CRUD (POST)
// ==============================================================
$alertScript = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $location = $_POST['location'] ?? '';
    $startDate = $_POST['start_date'] ?? date('Y-m-d H:i:s');
    $endDate = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $description = $_POST['description'] ?? '';
    $mapUrl = $_POST['map_url'] ?? '';
    $status = $_POST['status'] ?? 'published';
    
    // Slug automático
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    if(empty($slug)) $slug = 'evento-'.time();

    // Imagen
    $imagePath = $_POST['current_image'] ?? '';
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'evt_' . time() . '.' . $ext;
        $target = ROOT_PATH . '/assets/img/events/' . $filename;
        if (!is_dir(dirname($target))) mkdir(dirname($target), 0777, true);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) $imagePath = 'assets/img/events/' . $filename;
    }

    try {
        if ($action === 'create') {
            $db->insert('events', [
                'title' => $title, 'slug' => $slug, 'location' => $location,
                'start_date' => $startDate, 'end_date' => $endDate,
                'description' => $description, 'map_url' => $mapUrl,
                'status' => $status, 'image_path' => $imagePath
            ]);
            Audit::log('CREAR', "Creó evento: $title");
            $alertScript = "Swal.fire({icon: 'success', title: 'Evento Creado', text: 'Se ha publicado correctamente.', timer: 1500, showConfirmButton: false});";
        
        } elseif ($action === 'update' && $id) {
            $db->update('events', [
                'title' => $title, 'slug' => $slug, 'location' => $location,
                'start_date' => $startDate, 'end_date' => $endDate,
                'description' => $description, 'map_url' => $mapUrl,
                'status' => $status, 'image_path' => $imagePath
            ], "id=:id", [':id'=>$id]);
            Audit::log('EDITAR', "Editó evento ID $id");
            $alertScript = "Swal.fire({icon: 'success', title: 'Actualizado', text: 'Cambios guardados.', timer: 1500, showConfirmButton: false});";
        
        } elseif ($action === 'delete' && $id) {
            $db->delete('events', "id=:id", [':id'=>$id]);
            Audit::log('ELIMINAR', "Eliminó evento ID $id");
            $alertScript = "Swal.fire({icon: 'success', title: 'Eliminado', text: 'Evento borrado.', timer: 1500, showConfirmButton: false});";
        }

    } catch (Exception $e) {
        $alertScript = "Swal.fire('Error', '".addslashes($e->getMessage())."', 'error');";
    }
}

// ==============================================================
// 3. VISTA PRINCIPAL
// ==============================================================
$limit = 10; 
$page = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$whereSQL = ""; $params = [];
if ($search) {
    $whereSQL = " WHERE title LIKE :s1 OR location LIKE :s2";
    $params[':s1'] = "%$search%";
    $params[':s2'] = "%$search%";
}

$totalRows = $db->fetchOne("SELECT COUNT(*) as total FROM events" . $whereSQL, $params)['total'];
$totalPages = ceil($totalRows / $limit);
// Ordenar por fecha de inicio DESC (más recientes/futuros primero si se quisiera lógica compleja, pero DESC es estándar admin)
$events = $db->fetchAll("SELECT * FROM events $whereSQL ORDER BY start_date DESC LIMIT $limit OFFSET $offset", $params);

function renderRow($item) {
    $statusColors = [
        'published' => 'success', 'draft' => 'secondary',
        'cancelled' => 'danger', 'completed' => 'info'
    ];
    $stColor = $statusColors[$item['status']] ?? 'secondary';
    $stLabel = ucfirst($item['status']); // Podrías traducir esto
    
    $statusBadge = "<span class='badge bg-{$stColor} bg-opacity-10 text-{$stColor} border border-{$stColor} border-opacity-10 px-3 py-2 rounded-pill'>{$stLabel}</span>";
    
    $img = BASE_URL . '/' . ($item['image_path'] ?: 'assets/img/default-event.jpg');
    $start = date('d M Y - h:i A', strtotime($item['start_date']));
    
    $json = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
    
    echo <<<HTML
    <tr class="align-middle transition-hover">
        <td class="ps-4">
            <div class="ratio ratio-1x1 rounded overflow-hidden shadow-sm" style="width: 50px;">
                <img src="$img" class="object-fit-cover">
            </div>
        </td>
        <td>
            <div class="fw-bold text-dark mb-1">{$item['title']}</div>
            <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> {$item['location']}</small>
        </td>
        <td><span class="font-monospace small">$start</span></td>
        <td>$statusBadge</td>
        <td class="text-end pe-4">
            <button class="btn btn-icon btn-light text-primary me-1 shadow-sm" onclick='openModal("update", $json)' title="Editar">
                <i class="fa-solid fa-pen"></i>
            </button>
            <button class="btn btn-icon btn-light text-danger shadow-sm" onclick="confirmDelete({$item['id']})" title="Eliminar">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    </tr>
HTML;
}
?>

<style>
    .btn-icon { width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s; }
    .btn-icon:hover { transform: translateY(-2px); }
    .transition-hover:hover { background-color: #f8f9fa; }
</style>

<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Agenda de Eventos</h2>
        <p class="text-muted mb-0 small">Programa conciertos y actividades.</p>
    </div>
    <button class="btn btn-dark px-4 py-2 rounded-pill shadow-sm fw-medium" onclick="openModal('create')">
        <i class="fa-solid fa-calendar-plus me-2"></i> Nuevo Evento
    </button>
</div>

<div class="card-modern p-1 mb-4 d-flex align-items-center shadow-sm bg-white rounded-3">
    <div class="p-3 text-muted"><i class="fa-solid fa-magnifying-glass"></i></div>
    <input type="text" id="liveSearchInput" class="form-control border-0 shadow-none bg-transparent ps-0 py-3" 
           placeholder="Buscar evento por nombre o lugar..." autocomplete="off" value="<?php echo htmlspecialchars($search); ?>">
    <div id="searchSpinner" class="spinner-border spinner-border-sm text-primary me-4 d-none" role="status"></div>
</div>

<div class="card-modern table-responsive mb-4 shadow-sm border-0 bg-white rounded-3">
    <table class="table-modern w-100 mb-0">
        <thead class="bg-light border-bottom">
            <tr class="text-uppercase text-muted small fw-bold">
                <th class="ps-4 py-3">Img</th>
                <th class="py-3">Evento</th>
                <th class="py-3">Fecha Inicio</th>
                <th class="py-3">Estado</th>
                <th class="text-end pe-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody id="eventsTableBody">
            <?php 
            if (empty($events)) {
                echo '<tr><td colspan="6" class="text-center py-5 text-muted">No hay eventos programados.</td></tr>';
            } else {
                foreach ($events as $item) renderRow($item);
            }
            ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<div class="d-flex justify-content-center pb-5">
    <nav>
        <ul class="pagination align-items-center">
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                    <a class="page-link shadow-sm <?php echo $page == $i ? 'bg-dark text-white border-dark' : 'bg-white text-dark border'; ?>" 
                       style="border-radius: 8px; margin: 0 3px;"
                       href="?view=eventos&page_num=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<?php endif; ?>

<form id="deleteForm" method="POST" style="display:none;"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" id="deleteId"></form>

<div class="modal fade" id="eventModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 pt-4 pb-0 bg-white">
                <h5 class="modal-title fw-bold" id="modalTitle">Gestión de Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" enctype="multipart/form-data" id="eventForm">
                    <input type="hidden" name="action" id="formAction">
                    <input type="hidden" name="id" id="eventId">
                    <input type="hidden" name="current_image" id="currentImage">
                    
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control bg-light border-0 fw-bold" id="evtTitle" name="title" placeholder="Título" required>
                                <label>Título del Evento</label>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="datetime-local" class="form-control bg-light border-0" id="evtStart" name="start_date" required>
                                        <label>Inicia</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="datetime-local" class="form-control bg-light border-0" id="evtEnd" name="end_date">
                                        <label>Termina (Opcional)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control bg-light border-0" id="evtLocation" name="location" placeholder="Lugar" required>
                                <label><i class="fa-solid fa-location-dot me-1"></i> Lugar / Ubicación</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control bg-light border-0" id="evtDesc" name="description" placeholder="Descripción" style="height: 100px"></textarea>
                                <label>Descripción</label>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <label class="form-label small fw-bold text-muted mb-2">ESTADO</label>
                                <select class="form-select border-0 mb-3" name="status" id="evtStatus">
                                    <option value="published">Publicado</option>
                                    <option value="draft">Borrador</option>
                                    <option value="completed">Finalizado</option>
                                    <option value="cancelled">Cancelado</option>
                                </select>

                                <label class="form-label small fw-bold text-muted mb-2">LINK MAPA (Google Maps)</label>
                                <input type="url" class="form-control border-0 form-control-sm" name="map_url" id="evtMap" placeholder="https://maps.google...">
                            </div>

                            <div class="p-3 bg-light rounded-3 border border-dashed text-center">
                                <label class="form-label small fw-bold text-muted mb-2">AFICHE / IMAGEN</label>
                                <div id="previewImage" class="mb-2"></div>
                                <input type="file" name="image" id="imageInput" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4 border-top pt-3">
                        <button type="submit" class="btn btn-dark btn-lg rounded-pill">Guardar Evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Alertas PHP
    <?php if ($alertScript) echo $alertScript; ?>

    // Live Search
    const searchInput = document.getElementById('liveSearchInput');
    const tableBody = document.getElementById('eventsTableBody');
    const spinner = document.getElementById('searchSpinner');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value;
        spinner.classList.remove('d-none');
        timeout = setTimeout(() => {
            fetch(`index.php?view=eventos&ajax_search=1&q=${encodeURIComponent(query)}`)
                .then(res => res.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    spinner.classList.add('d-none');
                });
        }, 300);
    });

    function confirmDelete(id) {
        Swal.fire({
            title: '¿Borrar Evento?', text: "No se podrá recuperar.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#212529', cancelButtonColor: '#dc3545',
            confirmButtonText: 'Sí, borrar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteForm').submit();
            }
        });
    }

    document.getElementById('imageInput').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').innerHTML = 
                    `<img src="${e.target.result}" class="img-fluid rounded shadow-sm" style="max-height: 120px;">`;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    function openModal(mode, data = null) {
        document.getElementById('eventForm').reset();
        document.getElementById('formAction').value = mode;
        document.getElementById('previewImage').innerHTML = '';
        
        if (mode === 'update' && data) {
            document.getElementById('modalTitle').innerText = 'Editar Evento';
            document.getElementById('eventId').value = data.id;
            document.getElementById('evtTitle').value = data.title;
            document.getElementById('evtLocation').value = data.location;
            document.getElementById('evtDesc').value = data.description || '';
            document.getElementById('evtStatus').value = data.status;
            document.getElementById('evtMap').value = data.map_url || '';
            document.getElementById('currentImage').value = data.image_path;
            
            // Formato fecha para input datetime-local (YYYY-MM-DDTHH:MM)
            if(data.start_date) document.getElementById('evtStart').value = data.start_date.replace(' ', 'T').slice(0,16);
            if(data.end_date) document.getElementById('evtEnd').value = data.end_date.replace(' ', 'T').slice(0,16);

            if (data.image_path) {
                document.getElementById('previewImage').innerHTML = 
                    `<img src="${'<?php echo BASE_URL; ?>/' + data.image_path}" class="img-fluid rounded shadow-sm" style="max-height: 120px;">`;
            }
        } else {
            document.getElementById('modalTitle').innerText = 'Nuevo Evento';
            document.getElementById('evtStatus').value = 'published';
        }
        
        new bootstrap.Modal(document.getElementById('eventModal')).show();
    }
</script>