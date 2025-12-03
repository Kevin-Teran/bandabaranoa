<?php
/**
 * @file noticias.php
 * @route /admin/views/noticias.php
 * @description 
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

require_once __DIR__ . '/../includes/Audit.php';
$db = Database::getInstance();

// ==============================================================
// 0. ELIMINAR IMAGEN DE GALERÍA (AJAX PURO)
// ==============================================================
if (isset($_GET['action']) && $_GET['action'] === 'delete_image' && isset($_GET['id'])) {
    ob_clean(); // Limpiar buffer
    $imgId = (int)$_GET['id'];
    try {
        $img = $db->fetchOne("SELECT image_path FROM news_gallery WHERE id = :id", [':id' => $imgId]);
        if ($img) {
            $fullPath = ROOT_PATH . '/' . $img['image_path'];
            if (file_exists($fullPath)) unlink($fullPath); // Borrar archivo físico
            $db->delete('news_gallery', "id = :id", [':id' => $imgId]);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Imagen no encontrada']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// ==============================================================
// 1. BUSCADOR EN VIVO (AJAX)
// ==============================================================
if (isset($_GET['ajax_search'])) {
    while (ob_get_level()) ob_end_clean();
    
    $search = trim($_GET['q'] ?? '');
    $whereSQL = ""; 
    $params = [];
    
    if ($search) {
        $whereSQL = " WHERE title LIKE :s1 OR content LIKE :s2";
        $params[':s1'] = "%$search%";
        $params[':s2'] = "%$search%";
    }
    
    try {
        $news = $db->fetchAll("SELECT * FROM news $whereSQL ORDER BY created_at DESC LIMIT 20", $params);
        if (empty($news)) {
            echo '<tr><td colspan="6" class="text-center py-5 text-muted"><i class="fa-solid fa-magnifying-glass fa-2x mb-3 opacity-25"></i><p class="mb-0">No hay coincidencias.</p></td></tr>';
        } else {
            foreach ($news as $item) renderRow($item, $db);
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
    $summary = $_POST['summary'] ?? '';
    $status = $_POST['status'] ?? 'published';
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    // Slug automático
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    if(empty($slug)) $slug = 'news-'.time();
    
    // Imagen Principal
    $imagePath = $_POST['current_image'] ?? '';
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'news_' . time() . '.' . $ext;
        $target = ROOT_PATH . '/assets/img/news/' . $filename;
        if (!is_dir(dirname($target))) mkdir(dirname($target), 0777, true);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) $imagePath = 'assets/img/news/' . $filename;
    }

    try {
        $newsId = $id;
        
        if ($action === 'create') {
            $newsId = $db->insert('news', [
                'title' => $title, 'slug' => $slug, 'summary' => $summary, 
                'content' => $_POST['content'], 'status' => $status, 
                'featured' => $featured, 'image_path' => $imagePath
            ]);
            Audit::log('CREAR', "Creó noticia: $title");
            $alertScript = "Swal.fire({icon: 'success', title: 'Publicado', text: 'Noticia creada.', timer: 1500, showConfirmButton: false});";
        
        } elseif ($action === 'update' && $id) {
            $db->update('news', [
                'title' => $title, 'slug' => $slug, 'summary' => $summary, 
                'content' => $_POST['content'], 'status' => $status, 
                'featured' => $featured, 'image_path' => $imagePath
            ], "id=:id", [':id'=>$id]);
            Audit::log('EDITAR', "Editó noticia ID $id");
            $alertScript = "Swal.fire({icon: 'success', title: 'Actualizado', text: 'Cambios guardados.', timer: 1500, showConfirmButton: false});";
        
        } elseif ($action === 'delete' && $id) {
            $db->delete('news', "id=:id", [':id'=>$id]);
            Audit::log('ELIMINAR', "Eliminó noticia ID $id");
            $alertScript = "Swal.fire({icon: 'success', title: 'Eliminado', text: 'Noticia borrada.', timer: 1500, showConfirmButton: false});";
        }

        // --- PROCESAR GALERÍA (Múltiples Archivos) ---
        if (($action === 'create' || $action === 'update') && $newsId && !empty($_FILES['gallery']['name'][0])) {
            $galleryDir = ROOT_PATH . '/assets/img/news/gallery/';
            if (!is_dir($galleryDir)) mkdir($galleryDir, 0777, true);
            
            $count = count($_FILES['gallery']['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($_FILES['gallery']['error'][$i] === 0) {
                    $gExt = pathinfo($_FILES['gallery']['name'][$i], PATHINFO_EXTENSION);
                    $gName = 'gal_' . $newsId . '_' . time() . '_' . $i . '.' . $gExt;
                    
                    if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $galleryDir . $gName)) {
                        $db->insert('news_gallery', [
                            'news_id' => $newsId,
                            'image_path' => 'assets/img/news/gallery/' . $gName,
                            'sort_order' => $i
                        ]);
                    }
                }
            }
        }

    } catch (Exception $e) {
        $alertScript = "Swal.fire('Error', 'Técnico: ".addslashes($e->getMessage())."', 'error');";
    }
}

// ==============================================================
// 3. VISTA PRINCIPAL
// ==============================================================
$limit = 8; 
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$whereSQL = ""; $params = [];
if ($search) {
    $whereSQL = " WHERE title LIKE :s1 OR content LIKE :s2";
    $params[':s1'] = "%$search%";
    $params[':s2'] = "%$search%";
}

$totalRows = $db->fetchOne("SELECT COUNT(*) as total FROM news" . $whereSQL, $params)['total'];
$totalPages = ceil($totalRows / $limit);
$news = $db->fetchAll("SELECT * FROM news $whereSQL ORDER BY created_at DESC LIMIT $limit OFFSET $offset", $params);

// Función Render
function renderRow($item, $db) {
    // Obtener imágenes de galería para pasarlas al JS
    $gallery = $db->fetchAll("SELECT * FROM news_gallery WHERE news_id = :id", [':id' => $item['id']]);
    $item['gallery'] = $gallery; // Adjuntar al objeto JSON

    $statusBadge = $item['status'] === 'published' 
        ? '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 px-3 py-2 rounded-pill">Publicado</span>'
        : '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 px-3 py-2 rounded-pill">Borrador</span>';
    
    $featuredIcon = $item['featured'] 
        ? '<i class="fa-solid fa-star text-warning" title="Destacada en Home"></i>' 
        : '<i class="fa-regular fa-star text-muted opacity-25"></i>';

    $img = BASE_URL . '/' . ($item['image_path'] ?: 'assets/img/default.jpg');
    $date = date('d M', strtotime($item['created_at']));
    
    // Codificar para JS
    $json = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
    
    echo <<<HTML
    <tr class="align-middle transition-hover">
        <td class="ps-4">
            <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm" style="width: 60px;">
                <img src="$img" class="object-fit-cover">
            </div>
        </td>
        <td>
            <div class="fw-bold text-dark mb-1 text-truncate" style="max-width: 300px;">{$item['title']}</div>
            <small class="text-muted font-monospace bg-light px-2 py-1 rounded">/{$item['slug']}</small>
        </td>
        <td class="text-center fs-5">$featuredIcon</td>
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
    .gallery-thumb { position: relative; display: inline-block; margin: 5px; }
    .gallery-thumb img { width: 80px; height: 80px; object-fit: cover; border-radius: 5px; }
    .gallery-thumb .btn-delete { position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 12px; line-height: 20px; text-align: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
</style>

<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Noticias</h2>
        <p class="text-muted mb-0 small">Blog, Galería y Destacados.</p>
    </div>
    <button class="btn btn-dark px-4 py-2 rounded-pill shadow-sm fw-medium" onclick="openModal('create')">
        <i class="fa-solid fa-plus me-2"></i> Nueva Noticia
    </button>
</div>

<div class="card-modern p-1 mb-4 d-flex align-items-center shadow-sm bg-white rounded-3">
    <div class="p-3 text-muted"><i class="fa-solid fa-magnifying-glass"></i></div>
    <input type="text" id="liveSearchInput" class="form-control border-0 shadow-none bg-transparent ps-0 py-3" 
           placeholder="Buscar por título o contenido..." autocomplete="off" value="<?php echo htmlspecialchars($search); ?>">
    <div id="searchSpinner" class="spinner-border spinner-border-sm text-primary me-4 d-none" role="status"></div>
</div>

<div class="card-modern table-responsive mb-4 shadow-sm border-0 bg-white rounded-3">
    <table class="table-modern w-100 mb-0">
        <thead class="bg-light border-bottom">
            <tr class="text-uppercase text-muted small fw-bold">
                <th class="ps-4 py-3">Portada</th>
                <th class="py-3">Info</th>
                <th class="py-3 text-center">Destacado</th>
                <th class="py-3">Estado</th>
                <th class="py-3">Fecha</th>
                <th class="text-end pe-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody id="newsTableBody">
            <?php 
            if (empty($news)) {
                echo '<tr><td colspan="6" class="text-center py-5 text-muted">No hay noticias.</td></tr>';
            } else {
                foreach ($news as $item) renderRow($item, $db);
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
                       href="?view=noticias&page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<?php endif; ?>

<form id="deleteForm" method="POST" style="display:none;"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" id="deleteId"></form>

<div class="modal fade" id="newsModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 pt-4 pb-0 bg-white">
                <h5 class="modal-title fw-bold" id="modalTitle">Gestión de Noticias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" enctype="multipart/form-data" id="newsForm">
                    <input type="hidden" name="action" id="formAction">
                    <input type="hidden" name="id" id="newsId">
                    <input type="hidden" name="current_image" id="currentImage">
                    
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control bg-light border-0 fw-bold" id="newsTitle" name="title" placeholder="Título" required>
                                <label>Título Principal</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control bg-light border-0" id="newsSummary" name="summary" placeholder="Resumen" style="height: 80px"></textarea>
                                <label>Resumen Corto (Intro)</label>
                            </div>
                            
                            <div class="form-floating mb-3">
                                <textarea class="form-control bg-light border-0" id="newsContent" name="content" placeholder="Contenido" style="height: 300px" required></textarea>
                                <label>Contenido Completo (HTML)</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <h6 class="text-uppercase small fw-bold text-muted mb-3">Configuración</h6>
                                
                                <div class="form-floating mb-3">
                                    <select class="form-select border-0" name="status" id="newsStatus">
                                        <option value="published">Publicado</option>
                                        <option value="draft">Borrador</option>
                                    </select>
                                    <label>Estado</label>
                                </div>

                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="newsFeatured" name="featured" value="1">
                                    <label class="form-check-label fw-bold" for="newsFeatured">Destacar en Inicio</label>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded-3 mb-3 border border-dashed">
                                <label class="form-label small fw-bold text-muted mb-2">IMAGEN DE PORTADA</label>
                                <input type="file" name="image" class="form-control form-control-sm mb-2" accept="image/*">
                                <div id="previewMainImage" class="mt-2 text-center"></div>
                            </div>

                            <div class="p-3 bg-light rounded-3 border border-dashed">
                                <label class="form-label small fw-bold text-muted mb-2">GALERÍA (Múltiples)</label>
                                <input type="file" name="gallery[]" class="form-control form-control-sm mb-2" multiple accept="image/*">
                                
                                <div id="existingGallery" class="mt-2 d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4 border-top pt-3">
                        <button type="submit" class="btn btn-dark btn-lg rounded-pill">Guardar Cambios</button>
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
    const tableBody = document.getElementById('newsTableBody');
    const spinner = document.getElementById('searchSpinner');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value;
        spinner.classList.remove('d-none');
        timeout = setTimeout(() => {
            fetch(`index.php?view=noticias&ajax_search=1&q=${encodeURIComponent(query)}`)
                .then(res => res.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    spinner.classList.add('d-none');
                });
        }, 300);
    });

    // Eliminar Noticia
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar?', text: "No podrás recuperar esta noticia.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#212529', cancelButtonColor: '#dc3545',
            confirmButtonText: 'Sí, borrar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteForm').submit();
            }
        });
    }

    // Eliminar Imagen Galería (AJAX)
    function deleteGalleryImage(id, element) {
        Swal.fire({
            title: '¿Borrar foto?', text: "Se eliminará de la galería.", icon: 'warning',
            showCancelButton: true, confirmButtonText: 'Borrar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`index.php?view=noticias&action=delete_image&id=${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if(data.status === 'success') {
                            element.remove();
                            const Toast = Swal.mixin({toast: true, position: 'top-end', showConfirmButton: false, timer: 2000});
                            Toast.fire({icon: 'success', title: 'Imagen borrada'});
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
            }
        });
    }

    // Abrir Modal
    function openModal(mode, data = null) {
        document.getElementById('newsForm').reset();
        document.getElementById('existingGallery').innerHTML = ''; // Limpiar galería previa
        document.getElementById('previewMainImage').innerHTML = '';
        
        const modal = new bootstrap.Modal(document.getElementById('newsModal'));
        document.getElementById('formAction').value = mode;
        
        if (mode === 'update' && data) {
            document.getElementById('modalTitle').innerText = 'Editar Noticia';
            document.getElementById('newsId').value = data.id;
            document.getElementById('newsTitle').value = data.title;
            document.getElementById('newsSummary').value = data.summary || '';
            document.getElementById('newsContent').value = data.content;
            document.getElementById('currentImage').value = data.image_path;
            document.getElementById('newsStatus').value = data.status || 'published';
            document.getElementById('newsFeatured').checked = (data.featured == 1);

            // Previsualizar imagen principal
            if (data.image_path) {
                document.getElementById('previewMainImage').innerHTML = 
                    `<img src="${'<?php echo BASE_URL; ?>/' + data.image_path}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">`;
            }

            // Renderizar Galería
            if (data.gallery && data.gallery.length > 0) {
                const galleryContainer = document.getElementById('existingGallery');
                data.gallery.forEach(img => {
                    const div = document.createElement('div');
                    div.className = 'gallery-thumb';
                    div.innerHTML = `
                        <img src="${'<?php echo BASE_URL; ?>/' + img.image_path}">
                        <span class="btn-delete" onclick="deleteGalleryImage(${img.id}, this.parentElement)">×</span>
                    `;
                    galleryContainer.appendChild(div);
                });
            }

        } else {
            document.getElementById('modalTitle').innerText = 'Nueva Noticia';
            document.getElementById('newsStatus').value = 'published';
            document.getElementById('newsFeatured').checked = false;
        }
        modal.show();
    }
</script>