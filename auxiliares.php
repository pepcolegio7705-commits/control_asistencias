<?php
require_once 'config/security.php';
require_login();
require_once 'views/layout/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 text-secondary"><i class="fa-solid fa-users-gear text-info me-2"></i> Gestión de Auxiliares</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalAuxiliar" onclick="resetForm()">
            <i class="fa-solid fa-plus me-1"></i> Nuevo Auxiliar
        </button>
        <button type="button" class="btn btn-sm btn-info shadow-sm rounded-pill px-3 py-2 ms-2 text-white" onclick="window.print()">
            <i class="fa-solid fa-print me-1"></i> Imprimir / PDF
        </button>
    </div>
</div>

<div class="card shadow border-0 rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tablaAuxiliares" class="table table-hover table-striped align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Apellido</th>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>CUIL</th>
                        <th>Legajo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se cargarán por AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Auxiliar -->
<div class="modal fade" id="modalAuxiliar" tabindex="-1" aria-labelledby="modalAuxiliarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-info text-white rounded-top-4">
        <h5 class="modal-title" id="modalAuxiliarLabel"><i class="fa-solid fa-user-plus me-2"></i> Datos del Auxiliar</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formAuxiliar">
            <input type="hidden" id="auxiliar_id">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label text-muted fw-bold">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg bg-light" id="nombre" required placeholder="Ej: Carlos">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="apellido" class="form-label text-muted fw-bold">Apellido <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg bg-light" id="apellido" required placeholder="Ej: Rodríguez">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="dni" class="form-label text-muted fw-bold">DNI <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-lg bg-light" id="dni" required placeholder="Sin puntos ni espacios">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cuil" class="form-label text-muted fw-bold">CUIL</label>
                    <input type="text" class="form-control form-control-lg bg-light" id="cuil" placeholder="Ej: 20-12345678-9" maxlength="15">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="legajo" class="form-label text-muted fw-bold">Legajo</label>
                    <input type="text" class="form-control form-control-lg bg-light" id="legajo" placeholder="Nro. de legajo">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="titulo" class="form-label text-muted fw-bold">Título / Función</label>
                    <input type="text" class="form-control form-control-lg bg-light" id="titulo" placeholder="Ej: Portero">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label text-muted fw-bold">Teléfono</label>
                    <input type="text" class="form-control form-control-lg bg-light" id="telefono" placeholder="Ej: 2664-123456">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="mail" class="form-label text-muted fw-bold">E-mail</label>
                    <input type="email" class="form-control form-control-lg bg-light" id="mail" placeholder="Ej: auxiliar@mail.com">
                </div>
            </div>

            <div class="mb-4">
                <label for="direccion" class="form-label text-muted fw-bold">Dirección</label>
                <input type="text" class="form-control form-control-lg bg-light" id="direccion" placeholder="Ej: Av. San Martín 1234, Ciudad">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-info text-white btn-lg rounded-pill shadow-sm">Guardar Cambios</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
require_once 'views/layout/footer.php';
?>
<!-- Lógica UI AJAX -->
<script>
    let tabla;
    $(document).ready(function() {
        tabla = $('#tablaAuxiliares').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: 'controllers/auxiliares_ajax.php',
                type: 'POST',
                data: { action: 'listar' }
            }
        });

        $('#formAuxiliar').on('submit', function(e) {
            e.preventDefault();
            const formData = {
                action: 'guardar',
                id: $('#auxiliar_id').val(),
                nombre: $('#nombre').val(),
                apellido: $('#apellido').val(),
                dni: $('#dni').val(),
                cuil: $('#cuil').val(),
                direccion: $('#direccion').val(),
                telefono: $('#telefono').val(),
                mail: $('#mail').val(),
                titulo: $('#titulo').val(),
                legajo: $('#legajo').val()
            };
            $.post('controllers/auxiliares_ajax.php', formData, function(res) {
                const data = JSON.parse(res);
                if(data.status === 'success') {
                    Swal.fire('¡Éxito!', data.msg, 'success');
                    $('#modalAuxiliar').modal('hide');
                    tabla.ajax.reload(null, false);
                } else {
                    Swal.fire('Error', data.msg, 'error');
                }
            });
        });
    });

    function resetForm() {
        $('#formAuxiliar')[0].reset();
        $('#auxiliar_id').val('');
        $('#modalAuxiliarLabel').html('<i class="fa-solid fa-user-plus me-2"></i> Nuevo Auxiliar');
    }

    function editarAuxiliar(id) {
        $.post('controllers/auxiliares_ajax.php', { action: 'obtener', id: id }, function(res) {
            const result = JSON.parse(res);
            if(result.status === 'success') {
                const p = result.data;
                $('#auxiliar_id').val(p.enc_id);
                $('#nombre').val(p.nombre);
                $('#apellido').val(p.apellido);
                $('#dni').val(p.dni);
                $('#cuil').val(p.cuil || '');
                $('#direccion').val(p.direccion || '');
                $('#telefono').val(p.telefono || '');
                $('#mail').val(p.mail || '');
                $('#titulo').val(p.titulo || '');
                $('#legajo').val(p.legajo || '');
                $('#modalAuxiliarLabel').html('<i class="fa-solid fa-pen me-2"></i> Editar Auxiliar');
                $('#modalAuxiliar').modal('show');
            } else {
                Swal.fire('Error', result.msg, 'error');
            }
        });
    }

    function eliminarAuxiliar(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El auxiliar será eliminado del sistema.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('controllers/auxiliares_ajax.php', { action: 'eliminar', id: id }, function(res) {
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
