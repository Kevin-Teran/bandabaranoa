<?php
/**
 * @file usuarios.php
 * @route /admin/views/usuarios.php
 * @description Gestión de Usuarios del Sistema (Admin/Editores).
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

require_once __DIR__ . '/../includes/Audit.php';
$db = Database::getInstance();

// Obtener ID del usuario actual (asumiendo que se guarda en sesión al hacer login)
$currentUserId = $_SESSION['user_id'] ?? 0;

// ==============================================================
// 1. BUSCADOR EN VIVO (AJAX)
// ==============================================================
if (isset($_GET['ajax_search'])) {
    while (ob_get_level()) ob_end_clean();
    $search = trim($_GET['q'] ?? '');
    $whereSQL = ""; 
    $params = [];
    
    if ($search) {
        $whereSQL = " WHERE username LIKE :s1 OR email LIKE :s2";
        $params[':s1'] = "%$search%";
        $params[':s2'] = "%$search%";
    }
    
    try {
        $users = $db->fetchAll("SELECT * FROM users $whereSQL ORDER BY created_at DESC LIMIT 20", $params);
        if (empty($users)) {
            echo '<tr><td colspan="6" class="text-center py-5 text-muted">No se encontraron usuarios.</td></tr>';
        } else {
            foreach ($users as $item) renderUserRow($item, $currentUserId);
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
    
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? ''; // Solo si se cambia
    $role = $_POST['role'] ?? 'editor';
    $status = $_POST['status'] ?? 'active';

    try {
        if ($action === 'create') {
            // 1. Validar duplicados
            $exist = $db->fetchOne("SELECT id FROM users WHERE username = :u OR email = :e", [':u' => $username, ':e' => $email]);
            if ($exist) throw new Exception("El usuario o el correo ya están registrados.");
            
            // 2. Validar contraseña
            if (empty($password)) throw new Exception("La contraseña es obligatoria para nuevos usuarios.");

            // 3. Insertar
            $db->insert('users', [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT), // Encriptación segura
                'role' => $role,
                'status' => $status
            ]);
            
            Audit::log('CREAR', "Creó usuario: $username ($role)");
            $alertScript = "Swal.fire({icon: 'success', title: 'Usuario Creado', text: 'Acceso concedido.', timer: 1500, showConfirmButton: false});";

        } elseif ($action === 'update' && $id) {
            $data = ['username' => $username, 'email' => $email, 'role' => $role, 'status' => $status];
            
            // Solo actualizar password si el campo no está vacío
            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_BCRYPT);
            }

            $db->update('users', $data, "id = :id", [':id' => $id]);
            Audit::log('EDITAR', "Editó usuario ID $id");
            $alertScript = "Swal.fire({icon: 'success', title: 'Actualizado', text: 'Cambios guardados.', timer: 1500, showConfirmButton: false});";

        } elseif ($action === 'delete' && $id) {
            // Protección: No borrarse a sí mismo
            if ($id == $currentUserId) throw new Exception("No puedes eliminar tu propia cuenta mientras estás conectado.");
            
            $db->delete('users', "id = :id", [':id' => $id]);
            Audit::log('ELIMINAR', "Eliminó usuario ID $id");
            $alertScript = "Swal.fire({icon: 'success', title: 'Eliminado', text: 'Usuario borrado.', timer: 1500, showConfirmButton: false});";
        }
    } catch (Exception $e) {
        $alertScript = "Swal.fire('Error', '" . addslashes($e->getMessage()) . "', 'error');";
    }
}

// ==============================================================
// 3. LISTADO INICIAL
// ==============================================================
$users = $db->fetchAll("SELECT * FROM users ORDER BY created_at DESC LIMIT 10");

function renderUserRow($u, $currentId) {
    // Badge de Rol
    $roleBadge = $u['role'] === 'admin' 
        ? '<span class="badge bg-dark border border-dark"><i class="fa-solid fa-shield-halved me-1"></i> Admin</span>' 
        : '<span class="badge bg-light text-dark border"><i class="fa-solid fa-pen-nib me-1"></i> Editor</span>';
    
    // Badge de Estado
    $stMap = [
        'active' => ['success', 'Activo'],
        'inactive' => ['secondary', 'Inactivo'],
        'banned' => ['danger', 'Bloqueado']
    ];
    $stInfo = $stMap[$u['status']] ?? ['secondary', $u['status']];
    $statusBadge = "<span class='badge bg-{$stInfo[0]} bg-opacity-10 text-{$stInfo[0]} border border-{$stInfo[0]} border-opacity-10'>{$stInfo[1]}</span>";

    // Botón de borrar (Deshabilitado si es uno mismo)
    if ($u['id'] == $currentId) {
        $delBtn = '<button class="btn btn-icon btn-light text-muted opacity-50 shadow-sm" disabled title="No puedes borrarte a ti mismo"><i class="fa-solid fa-trash"></i></button>';
    } else {
        $delBtn = "<button class='btn btn-icon btn-light text-danger shadow-sm' onclick='confirmDelete({$u['id']})' title='Eliminar'><i class='fa-solid fa-trash'></i></button>";
    }

    // Avatar con iniciales
    $initial = strtoupper(substr($u['username'], 0, 1));
    $bgAvatar = $u['role'] === 'admin' ? '#212529' : '#6c757d';
    
    $json = htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8');

    echo <<<HTML
    <tr class="align-middle transition-hover">
        <td class="ps-4">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm me-3" 
                     style="width:40px; height:40px; background:{$bgAvatar};">
                    {$initial}
                </div>
                <div>
                    <div class="fw-bold text-dark">{$u['username']}</div>
                    <div class="small text-muted">{$u['email']}</div>
                </div>
            </div>
        </td>
        <td>$roleBadge</td>
        <td>$statusBadge</td>
        <td class="text-muted small">{$u['created_at']}</td>
        <td class="text-end pe-4">
            <button class="btn btn-icon btn-light text-primary me-1 shadow-sm" onclick='openModal("update", $json)' title="Editar">
                <i class="fa-solid fa-pen"></i>
            </button>
            $delBtn
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
        <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Usuarios del Sistema</h2>
        <p class="text-muted mb-0 small">Gestiona el acceso al panel administrativo.</p>
    </div>
    <button class="btn btn-dark px-4 py-2 rounded-pill shadow-sm fw-medium" onclick="openModal('create')">
        <i class="fa-solid fa-user-plus me-2"></i> Nuevo Usuario
    </button>
</div>

<div class="card-modern p-1 mb-4 d-flex align-items-center shadow-sm bg-white rounded-3">
    <div class="p-3 text-muted"><i class="fa-solid fa-magnifying-glass"></i></div>
    <input type="text" id="liveSearchInput" class="form-control border-0 shadow-none bg-transparent ps-0 py-3" 
           placeholder="Buscar por nombre o correo..." autocomplete="off">
</div>

<div class="card-modern table-responsive mb-4 shadow-sm border-0 bg-white rounded-3">
    <table class="table-modern w-100 mb-0">
        <thead class="bg-light border-bottom">
            <tr class="text-uppercase text-muted small fw-bold">
                <th class="ps-4 py-3">Usuario</th>
                <th class="py-3">Rol</th>
                <th class="py-3">Estado</th>
                <th class="py-3">Registro</th>
                <th class="text-end pe-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody id="usersTableBody">
            <?php 
            if (empty($users)) {
                echo '<tr><td colspan="6" class="text-center py-5 text-muted">No hay usuarios registrados.</td></tr>';
            } else {
                foreach ($users as $u) renderUserRow($u, $currentUserId); 
            }
            ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="userModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 pt-4 pb-0 bg-white">
                <h5 class="modal-title fw-bold" id="modalTitle">Gestión de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" id="userForm">
                    <input type="hidden" name="action" id="formAction">
                    <input type="hidden" name="id" id="userId">
                    
                    <div class="form-floating mb-3">
                        <input type="text" name="username" id="uName" class="form-control bg-light border-0 fw-bold" placeholder="Usuario" required>
                        <label>Nombre de Usuario</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="email" name="email" id="uEmail" class="form-control bg-light border-0" placeholder="Email" required>
                        <label>Correo Electrónico</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" name="password" id="uPass" class="form-control bg-light border-0" placeholder="Contraseña">
                        <label>Contraseña <span id="passHint" class="small text-muted fw-normal"></span></label>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="form-floating">
                                <select name="role" id="uRole" class="form-select bg-light border-0">
                                    <option value="editor">Editor</option>
                                    <option value="admin">Administrador</option>
                                </select>
                                <label>Rol</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <select name="status" id="uStatus" class="form-select bg-light border-0">
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                    <option value="banned">Bloqueado</option>
                                </select>
                                <label>Estado</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid mt-4 border-top pt-3">
                        <button type="submit" class="btn btn-dark btn-lg rounded-pill">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display:none">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
    // Alertas PHP
    <?php if($alertScript) echo $alertScript; ?>

    // Live Search
    const searchInput = document.getElementById('liveSearchInput');
    const tableBody = document.getElementById('usersTableBody');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            fetch(`index.php?view=usuarios&ajax_search=1&q=${encodeURIComponent(this.value)}`)
                .then(res => res.text())
                .then(html => tableBody.innerHTML = html);
        }, 300);
    });

    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar usuario?', text: "Esta acción es permanente.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#212529', cancelButtonColor: '#dc3545',
            confirmButtonText: 'Sí, borrar'
        }).then((r) => {
            if(r.isConfirmed) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteForm').submit();
            }
        });
    }

    function openModal(mode, data = null) {
        document.getElementById('userForm').reset();
        document.getElementById('formAction').value = mode;
        
        if (mode === 'update' && data) {
            document.getElementById('modalTitle').innerText = 'Editar Usuario';
            document.getElementById('userId').value = data.id;
            document.getElementById('uName').value = data.username;
            document.getElementById('uEmail').value = data.email;
            document.getElementById('uRole').value = data.role;
            document.getElementById('uStatus').value = data.status;
            
            // Contraseña opcional al editar
            document.getElementById('uPass').required = false;
            document.getElementById('passHint').innerText = "(Opcional: Dejar vacío para mantener)";
        } else {
            document.getElementById('modalTitle').innerText = 'Nuevo Usuario';
            document.getElementById('uStatus').value = 'active';
            document.getElementById('uRole').value = 'editor';
            
            // Contraseña obligatoria al crear
            document.getElementById('uPass').required = true;
            document.getElementById('passHint').innerText = "(Obligatorio)";
        }
        new bootstrap.Modal(document.getElementById('userModal')).show();
    }
</script>