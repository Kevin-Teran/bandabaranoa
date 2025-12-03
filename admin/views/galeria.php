<?php
/**
 * @file galeria.php
 * @route /admin/views/galeria.php
 * @description Gestión completa de la Galería Multimedia (estilo Noticias).
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
        $whereSQL = " WHERE title LIKE :s1 OR description LIKE :s2 OR category LIKE :s3";
        $params[':s1'] = "%$search%";
        $params[':s2'] = "%$search%";
        $params[':s3'] = "%$search%";
    }
    
    try {
        $gallery = $db->fetchAll("SELECT * FROM gallery $whereSQL ORDER BY created_at DESC LIMIT 20", $params);
        if (empty($gallery)) {
            echo '<tr><td colspan="6" class="text-center py-5 text-muted"><i class="fa-solid fa-magnifying-glass fa-2x mb-3 opacity-25"></i><p class="mb-0">No hay coincidencias.</p></td></tr>';
        } else {
            foreach ($gallery as $item) renderRow($item);
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
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? 'General';
    $status = $_POST['status'] ?? 'published';
    
    // Manejo de Imagen
    $imagePath = $_POST['current_image'] ?? '';
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'gal_' . time() . '.' . $ext;
        $target = ROOT_PATH . '/assets/img/gallery/' . $filename;
        
        // Crear carpeta si no existe
        if (!is_dir(dirname($target))) mkdir(dirname($target), 0777, true);
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Si es actualización, borrar la anterior si existe
            if ($action === 'update' && !empty($imagePath) && file_exists(ROOT_PATH . '/' . $imagePath)) {
                unlink(ROOT_PATH . '/' . $imagePath);
            }
            $imagePath = 'assets/img/gallery/' . $filename;
        }
    }

    try {
        if ($action === 'create') {
            if (empty($imagePath)) throw new Exception("La imagen es obligatoria.");
            
            $db->insert('gallery', [
                'title' => $title, 
                'description' => $description, 
                'category' => $category,
                'image_path' => $imagePath,
                'status' => $status
            ]);
            Audit::log('CREAR', "Subió imagen a galería: $title");
            $alertScript = "Swal.fire({icon: 'success', title: 'Subido', text: 'Imagen agregada a la galería.', timer: 1500, showConfirmButton: false});";
        
        } elseif ($action === 'update' && $id) {
            $db->update('gallery', [
                'title' => $title, 
                'description' => $description, 
                'category' => $category,
                'image_path' => $imagePath,
                'status' => $status
            ], "id=:id", [':id'=>$id]);
            Audit::log('EDITAR', "Editó imagen galería ID $id");
            $alertScript = "Swal.fire({icon: 'success', title: 'Actualizado', text: 'Cambios guardados.', timer: 1500, showConfirmButton: false});";
        
        } elseif ($action === 'delete' && $id) {
            // Obtener ruta para borrar archivo físico
            $oldImg = $db->fetchOne("SELECT image_path FROM gallery WHERE id = :id", [':id' => $id]);
            if ($oldImg && file_exists(ROOT_PATH . '/' . $oldImg['image_path'])) {
                unlink(ROOT_PATH . '/' . $oldImg['image_path']);
            }
            
            $db->delete('gallery', "id=:id", [':id'=>$id]);
            Audit::log('ELIMINAR', "Eliminó imagen galería ID $id");
            $alertScript = "Swal.fire({icon: 'success', title: 'Eliminado', text: 'Imagen borrada.', timer: 1500, showConfirmButton: false});";
        }

    } catch (Exception $e) {
        $alertScript = "Swal.fire('Error', '".addslashes($e->getMessage())."', 'error');";
    }
}

// ==============================================================
// 3. VISTA PRINCIPAL
// ==============================================================
$limit = 10; 
$page = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1; // Cambio nombre var para no chocar con router
$offset = ($page - 1) * $limit;
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$whereSQL = ""; $params = [];
if ($search) {
    $whereSQL = " WHERE title LIKE :s1 OR category LIKE :s2";
    $params[':s1'] = "%$search%";
    $params[':s2'] = "%$search%";
}

$totalRows = $db->fetchOne("SELECT COUNT(*) as total FROM gallery" . $whereSQL, $params)['total'];
$totalPages = ceil($totalRows / $limit);
$gallery = $db->fetchAll("SELECT * FROM gallery $whereSQL ORDER BY created_at DESC LIMIT $limit OFFSET $offset", $params);

// Función Render
function renderRow($item) {
    $statusBadge = $item['status'] === 'published' 
        ? '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 px-3 py-2 rounded-pill">Publicado</span>'
        : '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 px-3 py-2 rounded-pill">Borrador</span>';
    
    $img = BASE_URL . '/' . ($item['image_path'] ?: 'assets/img/default.jpg');
    $date = date('d M Y', strtotime($item['created_at']));
    
    // Codificar para JS
    $json = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
    
    echo <<<HTML
    <tr class="align-middle transition-hover">
        <td class="ps-4">
            <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm" style="width: 80px;">
                <img src="$img" class="object-fit-cover" style="cursor:pointer" onclick="window.open('$img', '_blank')">
            </div>
        </td>
        <td>
            <div class="fw-bold text-dark mb-1">{$item['title']}</div>
            <div class="text-muted small text-truncate" style="max-width: 250px;">{$item['description']}</div>
        </td>
        <td><span class="badge bg-light text-dark border">{$item['category']}</span></td>
        <td>$statusBadge</td>
        <td class="text-muted small"><i class="fa-regular fa-calendar me-2"></i>$date</td>
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
        <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Galería Multimedia</h2>
        <p class="text-muted mb-0 small">Gestiona fotos y categorías del sitio.</p>
    </div>
    <button class="btn btn-dark px-4 py-2 rounded-pill shadow-sm fw-medium" onclick="openModal('create')">
        <i class="fa-solid fa-cloud-arrow-up me-2"></i> Subir Nueva Foto
    </button>
</div>

<div class="card-modern p-1 mb-4 d-flex align-items-center shadow-sm bg-white rounded-3">
    <div class="p-3 text-muted"><i class="fa-solid fa-magnifying-glass"></i></div>
    <input type="text" id="liveSearchInput" class="form-control border-0 shadow-none bg-transparent ps-0 py-3" 
           placeholder="Buscar por título, descripción o categoría..." autocomplete="off" value="<?php echo htmlspecialchars($search); ?>">
    <div id="searchSpinner" class="spinner-border spinner-border-sm text-primary me-4 d-none" role="status"></div>
</div>

<div class="card-modern table-responsive mb-4 shadow-sm border-0 bg-white rounded-3">
    <table class="table-modern w-100 mb-0">
        <thead class="bg-light border-bottom">
            <tr class="text-uppercase text-muted small fw-bold">
                <th class="ps-4 py-3">Vista Previa</th>
                <th class="py-3">Información</th>
                <th class="py-3">Categoría</th>
                <th class="py-3">Estado</th>
                <th class="py-3">Fecha</th>
                <th class="text-end pe-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody id="galleryTableBody">
            <?php 
            if (empty($gallery)) {
                echo '<tr><td colspan="6" class="text-center py-5 text-muted">No hay imágenes en la galería.</td></tr>';
            } else {
                foreach ($gallery as $item) renderRow($item);
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
                       href="?view=galeria&page_num=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<?php endif; ?>

<form id="deleteForm" method="POST" style="display:none;"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" id="deleteId"></form>

<div class="modal fade" id="galleryModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 pt-4 pb-0 bg-white">
                <h5 class="modal-title fw-bold" id="modalTitle">Gestión de Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" enctype="multipart/form-data" id="galleryForm">
                    <input type="hidden" name="action" id="formAction">
                    <input type="hidden" name="id" id="galleryId">
                    <input type="hidden" name="current_image" id="currentImage">
                    
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control bg-light border-0 fw-bold" id="galTitle" name="title" placeholder="Título" required>
                                <label>Título</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control bg-light border-0" id="galDescription" name="description" placeholder="Descripción" style="height: 100px"></textarea>
                                <label>Descripción Corta</label>
                            </div>
                            
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-light border-0" id="galCategory" name="category" list="catOptions" placeholder="Categoría">
                                        <label>Categoría</label>
                                        <datalist id="catOptions">
                                            <option value="General">
                                            <option value="Conciertos">
                                            <option value="Ensayos">
                                            <option value="Eventos">
                                            <option value="Viajes">
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select bg-light border-0" name="status" id="galStatus">
                                            <option value="published">Publicado</option>
                                            <option value="draft">Borrador</option>
                                        </select>
                                        <label>Estado</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="p-3 bg-light rounded-3 border border-dashed text-center h-100 d-flex flex-column justify-content-center">
                                <label class="form-label small fw-bold text-muted mb-2">ARCHIVO DE IMAGEN</label>
                                <div id="previewImage" class="mb-3">
                                    <i class="fa-regular fa-image fa-3x text-muted opacity-25"></i>
                                </div>
                                <input type="file" name="image" id="imageInput" class="form-control form-control-sm" accept="image/*">
                                <small class="text-muted mt-2 d-block" style="font-size: 10px;">Recomendado: .jpg, .png, .webp</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4 border-top pt-3">
                        <button type="submit" class="btn btn-dark btn-lg rounded-pill">Guardar Imagen</button>
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
    const tableBody = document.getElementById('galleryTableBody');
    const spinner = document.getElementById('searchSpinner');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value;
        spinner.classList.remove('d-none');
        timeout = setTimeout(() => {
            fetch(`index.php?view=galeria&ajax_search=1&q=${encodeURIComponent(query)}`)
                .then(res => res.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    spinner.classList.add('d-none');
                });
        }, 300);
    });

    // Eliminar
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar?', text: "Se borrará esta imagen de forma permanente.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#212529', cancelButtonColor: '#dc3545',
            confirmButtonText: 'Sí, borrar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteForm').submit();
            }
        });
    }

    // Previsualización de imagen al seleccionar
    document.getElementById('imageInput').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').innerHTML = 
                    `<img src="${e.target.result}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">`;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Abrir Modal
    function openModal(mode, data = null) {
        document.getElementById('galleryForm').reset();
        document.getElementById('formAction').value = mode;
        document.getElementById('previewImage').innerHTML = '<i class="fa-regular fa-image fa-3x text-muted opacity-25"></i>';
        
        if (mode === 'update' && data) {
            document.getElementById('modalTitle').innerText = 'Editar Imagen';
            document.getElementById('galleryId').value = data.id;
            document.getElementById('galTitle').value = data.title;
            document.getElementById('galDescription').value = data.description;
            document.getElementById('galCategory').value = data.category;
            document.getElementById('galStatus').value = data.status;
            document.getElementById('currentImage').value = data.image_path;

            if (data.image_path) {
                document.getElementById('previewImage').innerHTML = 
                    `<img src="${'<?php echo BASE_URL; ?>/' + data.image_path}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">`;
            }
        } else {
            document.getElementById('modalTitle').innerText = 'Subir Nueva Imagen';
            document.getElementById('galStatus').value = 'published';
        }
        
        const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
        modal.show();
    }
</script>