<?php
require_once 'config/security.php';
require_login();

// Seguridad: Solo admin
if ($_SESSION['rol'] !== 'admin') {
    echo "<div style='padding:20px; text-align:center;'><h2>Acceso Denegado</h2><p>No tienes permisos para ver esta sección.</p></div>";
    exit;
}

require_once 'views/layout/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 text-secondary"><i class="fa-solid fa-user-shield text-info me-2"></i> Gestión de Usuarios</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="resetForm()">
            <i class="fa-solid fa-user-plus me-1"></i> Nuevo Usuario
        </button>
    </div>
</div>

<div class="card shadow border-0 rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tablaUsuarios" class="table table-hover table-striped align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Datos cargados por AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-dark text-white rounded-top-4">
        <h5 class="modal-title" id="modalUsuarioLabel"><i class="fa-solid fa-user-plus me-2"></i> Datos del Usuario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formUsuario">
            <input type="hidden" id="usuario_id">
            
            <div class="mb-3">
                <label for="username" class="form-label text-muted fw-bold">Nombre de Usuario <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg bg-light" id="username" required placeholder="Ej: jlopez">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-muted fw-bold">Contraseña <span id="pass_req" class="text-danger">*</span></label>
                <input type="password" class="form-control form-control-lg bg-light" id="password" placeholder="Mínimo 6 caracteres">
                <div class="form-text">Si editas el usuario y dejas esto en blanco, la contraseña no cambiará.</div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="rol" class="form-label text-muted fw-bold">Rol en el Sistema</label>
                    <select class="form-select bg-light" id="rol" required>
                        <option value="usuario">Usuario (Solo Lectura)</option>
                        <option value="Administrativo">Administrativo</option>
                        <option value="admin">Administrador Global</option>
                    </select>
                </div>
                <div class="col-md-6 mb-4">
                    <label for="estado" class="form-label text-muted fw-bold">Estado</label>
                    <select class="form-select bg-light" id="estado" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-dark fw-bold btn-lg rounded-pill shadow-sm">Guardar Usuario</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery (necesario para DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    let tabla;
    $(document).ready(function() {
        tabla = $('#tablaUsuarios').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: 'controllers/usuarios_ajax.php',
                type: 'POST',
                data: { action: 'listar' }
            }
        });

        $('#formUsuario').on('submit', function(e) {
            e.preventDefault();
            const formData = {
                action: 'guardar',
                id: $('#usuario_id').val(),
                username: $('#username').val(),
                password: $('#password').val(),
                rol: $('#rol').val(),
                estado: $('#estado').val()
            };
            $.post('controllers/usuarios_ajax.php', formData, function(res) {
                const data = JSON.parse(res);
                if(data.status === 'success') {
                    Swal.fire('¡Éxito!', data.msg, 'success');
                    $('#modalUsuario').modal('hide');
                    tabla.ajax.reload(null, false);
                } else {
                    Swal.fire('Error', data.msg, 'error');
                }
            });
        });
    });

    function resetForm() {
        $('#formUsuario')[0].reset();
        $('#usuario_id').val('');
        $('#modalUsuarioLabel').html('<i class="fa-solid fa-user-plus me-2"></i> Nuevo Usuario');
        $('#password').prop('required', true);
        $('#pass_req').show();
        $('#rol').val('usuario');
        $('#estado').val('1');
    }

    function editarUsuario(id) {
        $.post('controllers/usuarios_ajax.php', { action: 'obtener', id: id }, function(res) {
            const result = JSON.parse(res);
            if(result.status === 'success') {
                const u = result.data;
                $('#usuario_id').val(u.enc_id);
                $('#username').val(u.username);
                $('#password').val('');
                $('#password').prop('required', false);
                $('#pass_req').hide();
                $('#rol').val(u.rol);
                $('#estado').val(u.estado);
                
                $('#modalUsuarioLabel').html('<i class="fa-solid fa-user-pen me-2"></i> Editar Usuario');
                $('#modalUsuario').modal('show');
            } else {
                Swal.fire('Error', result.msg, 'error');
            }
        });
    }

    function eliminarUsuario(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El usuario será eliminado permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('controllers/usuarios_ajax.php', { action: 'eliminar', id: id }, function(res) {
                    const data = JSON.parse(res);
                    if(data.status === 'success') {
                        Swal.fire('Eliminado', data.msg, 'success');
                        tabla.ajax.reload(null, false);
                    } else {
                        Swal.fire('Error', data.msg, 'error');
                    }
                });
            }
        });
    }
</script>
</body>
</html>
