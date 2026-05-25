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
            <table id="tablaAuxiliares" class="table table-sm table-hover table-striped align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Apellido</th>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>CUIL</th>
                        <th>Legajo</th>
                        <th>Ingreso</th>
                        <th>Días Restantes</th>
                        <th>Tomados</th>
                        <th>Observaciones</th>
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
                <div class="col-md-4 mb-3">
                    <label for="legajo" class="form-label text-muted fw-bold">Legajo</label>
                    <input type="text" class="form-control form-control-lg bg-light" id="legajo" placeholder="Nro. de legajo">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_ingreso" class="form-label text-muted fw-bold">Fecha Ingreso</label>
                    <input type="date" class="form-control form-control-lg bg-light" id="fecha_ingreso">
                </div>
                <div class="col-md-4 mb-3">
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

<!-- Modal Ver Info Auxiliar -->
<div class="modal fade" id="modalInfoAuxiliar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-info text-white rounded-top-4">
        <h5 class="modal-title"><i class="fa-solid fa-address-card me-2"></i> Información del Auxiliar</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-4">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center border shadow-sm" style="width: 80px; height: 80px;">
                <i class="fa-solid fa-user-gear fa-3x text-secondary"></i>
            </div>
            <h4 class="mt-3 mb-0" id="info_nombre_completo">Nombre</h4>
            <p class="text-muted mb-0" id="info_titulo">Título</p>
        </div>
        <hr>
        <div class="row text-sm">
            <div class="col-6 mb-3">
                <span class="text-muted d-block small"><i class="fa-solid fa-id-card me-1"></i> DNI</span>
                <strong id="info_dni"></strong>
            </div>
            <div class="col-6 mb-3">
                <span class="text-muted d-block small"><i class="fa-solid fa-hashtag me-1"></i> CUIL</span>
                <strong id="info_cuil"></strong>
            </div>
            <div class="col-6 mb-3">
                <span class="text-muted d-block small"><i class="fa-solid fa-briefcase me-1"></i> Legajo</span>
                <strong id="info_legajo"></strong>
            </div>
            <div class="col-6 mb-3">
                <span class="text-muted d-block small"><i class="fa-solid fa-phone me-1"></i> Teléfono</span>
                <strong id="info_telefono"></strong>
            </div>
            <div class="col-12 mb-3">
                <span class="text-muted d-block small"><i class="fa-solid fa-envelope me-1"></i> E-mail</span>
                <strong id="info_mail"></strong>
            </div>
            <div class="col-12 mb-2">
                <span class="text-muted d-block small"><i class="fa-solid fa-map-location-dot me-1"></i> Dirección</span>
                <strong id="info_direccion"></strong>
            </div>
        </div>
      </div>
      <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Cargar Vacaciones -->
<div class="modal fade" id="modalCargarVacaciones" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-warning text-dark rounded-top-4">
        <h5 class="modal-title"><i class="fa-solid fa-plane-departure me-2"></i> Cargar Vacaciones Tomadas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formCargarVacaciones">
            <input type="hidden" id="vac_auxiliar_id">
            <input type="hidden" id="vac_registro_id">
            
            <div class="mb-3">
                <label for="vac_dias" class="form-label text-muted fw-bold">Cantidad de Días a Tomar <span class="text-danger">*</span></label>
                <input type="number" class="form-control form-control-lg bg-light" id="vac_dias" required min="1" placeholder="Ej: 5">
            </div>

            <div class="mb-4">
                <label for="vac_obs" class="form-label text-muted fw-bold">Observaciones <span class="text-danger">*</span></label>
                <textarea class="form-control bg-light" id="vac_obs" rows="3" required placeholder="Ej: Vacaciones de invierno correspondientes al año 2023..."></textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-warning text-dark fw-bold btn-lg rounded-pill shadow-sm" id="btnGuardarVac">Registrar Vacaciones</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Historial Vacaciones -->
<div class="modal fade" id="modalHistorialVacaciones" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-dark text-white rounded-top-4">
        <h5 class="modal-title"><i class="fa-solid fa-clock-rotate-left me-2"></i> Historial de Vacaciones</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Fecha de Carga</th>
                        <th>Días</th>
                        <th>Observaciones</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyHistorialVacaciones">
                    <tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>
                </tbody>
            </table>
        </div>
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
                fecha_ingreso: $('#fecha_ingreso').val(),
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
                $('#fecha_ingreso').val(p.fecha_ingreso || '');
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

    function verInfoAuxiliar(id) {
        $.post('controllers/auxiliares_ajax.php', { action: 'obtener', id: id }, function(res) {
            const result = JSON.parse(res);
            if(result.status === 'success') {
                const p = result.data;
                $('#info_nombre_completo').text(p.nombre + ' ' + p.apellido);
                $('#info_titulo').text(p.titulo || 'Sin título registrado');
                $('#info_dni').text(p.dni);
                $('#info_cuil').text(p.cuil || '-');
                $('#info_legajo').text(p.legajo || '-');
                $('#info_telefono').text(p.telefono || '-');
                $('#info_mail').text(p.mail || '-');
                $('#info_direccion').text(p.direccion || '-');
                $('#modalInfoAuxiliar').modal('show');
            } else {
                Swal.fire('Error', result.msg, 'error');
            }
        });
    }

    function cargarVacacionModal(id) {
        $('#formCargarVacaciones')[0].reset();
        $('#vac_auxiliar_id').val(id);
        $('#vac_registro_id').val('');
        $('#btnGuardarVac').text('Registrar Vacaciones');
        $('#modalCargarVacaciones').modal('show');
    }

    function editarVacacion(id) {
        $.post('controllers/auxiliares_ajax.php', { action: 'obtener_vacacion', id: id }, function(res) {
            const data = JSON.parse(res);
            if(data.status === 'success') {
                $('#vac_registro_id').val(data.data.id);
                $('#vac_auxiliar_id').val(data.data.auxiliar_id);
                $('#vac_dias').val(data.data.dias);
                $('#vac_obs').val(data.data.observaciones);
                $('#btnGuardarVac').text('Guardar Cambios');
                $('#modalHistorialVacaciones').modal('hide');
                $('#modalCargarVacaciones').modal('show');
            } else {
                Swal.fire('Error', data.msg, 'error');
            }
        });
    }

    function eliminarVacacion(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se eliminarán estos días tomados y se restablecerán los días restantes.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('controllers/auxiliares_ajax.php', { action: 'eliminar_vacacion', id: id }, function(res) {
                    const data = JSON.parse(res);
                    if(data.status === 'success') {
                        Swal.fire('Eliminado', data.msg, 'success');
                        $('#modalHistorialVacaciones').modal('hide');
                        tabla.ajax.reload(null, false);
                    } else {
                        Swal.fire('Error', data.msg, 'error');
                    }
                });
            }
        });
    }

    function verHistorialVacaciones(id) {
        $('#tbodyHistorialVacaciones').html('<tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>');
        $('#modalHistorialVacaciones').modal('show');
        
        $.post('controllers/auxiliares_ajax.php', { action: 'historial_vacaciones', id: id }, function(res) {
            const data = JSON.parse(res);
            if(data.status === 'success') {
                $('#tbodyHistorialVacaciones').html(data.html);
            } else {
                $('#tbodyHistorialVacaciones').html('<tr><td colspan="4" class="text-center text-danger">Error al cargar historial.</td></tr>');
            }
        });
    }

    $('#formCargarVacaciones').on('submit', function(e) {
        e.preventDefault();
        const actionType = $('#vac_registro_id').val() ? 'editar_vacacion' : 'cargar_vacacion';
        const formData = {
            action: actionType,
            auxiliar_id: $('#vac_auxiliar_id').val(),
            registro_id: $('#vac_registro_id').val(),
            dias: $('#vac_dias').val(),
            observaciones: $('#vac_obs').val()
        };
        $.post('controllers/auxiliares_ajax.php', formData, function(res) {
            const data = JSON.parse(res);
            if(data.status === 'success') {
                Swal.fire('¡Éxito!', data.msg, 'success');
                $('#modalCargarVacaciones').modal('hide');
                tabla.ajax.reload(null, false);
            } else {
                Swal.fire('Error', data.msg, 'error');
            }
        });
    });
</script>
