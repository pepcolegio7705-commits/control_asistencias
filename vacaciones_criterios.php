<?php
require_once 'config/security.php';
require_login();
require_once 'views/layout/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 text-secondary"><i class="fa-solid fa-umbrella-beach text-warning me-2"></i> Criterios de Vacaciones</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'usuario'): ?>
        <button type="button" class="btn btn-sm btn-warning text-dark fw-bold shadow-sm rounded-pill px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalCriterio" onclick="resetForm()">
            <i class="fa-solid fa-plus me-1"></i> Nuevo Criterio
        </button>
        <?php endif; ?>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info border-0 shadow-sm rounded-4">
            <i class="fa-solid fa-circle-info me-2"></i> Aquí se configuran los rangos de antigüedad (en años) y la cantidad de días de vacaciones correspondientes para los auxiliares.
        </div>
    </div>
</div>

<div class="card shadow border-0 rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tablaCriterios" class="table table-hover table-striped align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Años Mínimos</th>
                        <th>Años Máximos</th>
                        <th>Días de Vacaciones</th>
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

<!-- Modal Criterio -->
<div class="modal fade" id="modalCriterio" tabindex="-1" aria-labelledby="modalCriterioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-warning text-dark rounded-top-4">
        <h5 class="modal-title" id="modalCriterioLabel"><i class="fa-solid fa-calendar-plus me-2"></i> Criterio de Vacaciones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formCriterio">
            <input type="hidden" id="criterio_id">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="anios_min" class="form-label text-muted fw-bold">Años Mínimos <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-lg bg-light" id="anios_min" required min="0" placeholder="Ej: 0">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="anios_max" class="form-label text-muted fw-bold">Años Máximos <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-lg bg-light" id="anios_max" required min="0" placeholder="Ej: 5">
                </div>
            </div>

            <div class="mb-4">
                <label for="dias" class="form-label text-muted fw-bold">Días de Vacaciones <span class="text-danger">*</span></label>
                <input type="number" class="form-control form-control-lg bg-light" id="dias" required min="0" placeholder="Ej: 14">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-warning text-dark btn-lg rounded-pill shadow-sm fw-bold">Guardar Cambios</button>
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
        tabla = $('#tablaCriterios').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: 'controllers/vacaciones_criterios_ajax.php',
                type: 'POST',
                data: { action: 'listar' }
            },
            order: [[1, 'asc']]
        });

        $('#formCriterio').on('submit', function(e) {
            e.preventDefault();
            const formData = {
                action: 'guardar',
                id: $('#criterio_id').val(),
                anios_min: $('#anios_min').val(),
                anios_max: $('#anios_max').val(),
                dias: $('#dias').val()
            };
            $.post('controllers/vacaciones_criterios_ajax.php', formData, function(res) {
                const data = JSON.parse(res);
                if(data.status === 'success') {
                    Swal.fire('¡Éxito!', data.msg, 'success');
                    $('#modalCriterio').modal('hide');
                    tabla.ajax.reload(null, false);
                } else {
                    Swal.fire('Error', data.msg, 'error');
                }
            });
        });
    });

    function resetForm() {
        $('#formCriterio')[0].reset();
        $('#criterio_id').val('');
        $('#modalCriterioLabel').html('<i class="fa-solid fa-plus me-2"></i> Nuevo Criterio');
    }

    function editarCriterio(id) {
        $.post('controllers/vacaciones_criterios_ajax.php', { action: 'obtener', id: id }, function(res) {
            const result = JSON.parse(res);
            if(result.status === 'success') {
                const c = result.data;
                $('#criterio_id').val(c.enc_id);
                $('#anios_min').val(c.anios_min);
                $('#anios_max').val(c.anios_max);
                $('#dias').val(c.dias);
                $('#modalCriterioLabel').html('<i class="fa-solid fa-pen me-2"></i> Editar Criterio');
                $('#modalCriterio').modal('show');
            } else {
                Swal.fire('Error', result.msg, 'error');
            }
        });
    }

    function eliminarCriterio(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El criterio será eliminado del sistema.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('controllers/vacaciones_criterios_ajax.php', { action: 'eliminar', id: id }, function(res) {
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
