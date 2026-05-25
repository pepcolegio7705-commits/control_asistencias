<?php
$isUsuario = isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 text-secondary"><i class="fa-solid fa-school me-2"></i> Gestión de Cursos</h1>
</div>

<div class="card p-4">
    <div class="d-flex justify-content-between mb-3">
        <h5 class="card-title mb-0">Listado de Cursos</h5>
        <?php if (!$isUsuario): ?>
        <button class="btn btn-primary btn-sm" onclick="abrirModalCurso()">
            <i class="fa-solid fa-plus me-1"></i> Nuevo Curso
        </button>
        <?php endif; ?>
    </div>
    
    <div class="table-responsive">
        <table id="cursosTable" class="table table-striped table-hover align-middle" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Curso</th>
                    <th>Turno</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Cargado por AJAX -->
            </tbody>
        </table>
    </div>
</div>

<?php if (!$isUsuario): ?>
<!-- Modal Curso -->
<div class="modal fade" id="modalCurso" tabindex="-1" aria-labelledby="modalCursoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalCursoLabel"><i class="fa-solid fa-school me-2"></i> <span>Nuevo Curso</span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formCurso">
          <div class="modal-body">
              <input type="hidden" id="curso_id" name="curso_id">
              <div class="mb-3">
                  <label class="form-label fw-bold">Nombre del Curso</label>
                  <input type="text" class="form-control" id="nombre_curso" name="nombre_curso" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold">Turno</label>
                  <select class="form-select" id="turno" name="turno">
                      <option value="">-- Seleccionar Turno --</option>
                      <option value="Mañana">Mañana</option>
                      <option value="Tarde">Tarde</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Guardar</button>
          </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>



<script>
let tablaCursos;

$(document).ready(function() {
    tablaCursos = $('#cursosTable').DataTable({
        "ajax": {
            "url": "controllers/cursos_ajax.php?action=listar",
            "type": "GET",
            "dataSrc": "data"
        },
        "columns": [
            { "data": "idcurso" },
            { "data": "curso" },
            { 
                "data": "turno",
                "render": function(data) {
                    if (data === 'Mañana') return '<span class="badge bg-info text-dark"><i class="fa-solid fa-sun"></i> Mañana</span>';
                    if (data === 'Tarde') return '<span class="badge bg-warning text-dark"><i class="fa-solid fa-cloud-sun"></i> Tarde</span>';
                    return '<span class="badge bg-secondary">Sin Asignar</span>';
                }
            },
            { 
                "data": "estatus",
                "render": function(data) {
                    return data == 1 ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';
                }
            },
            { "data": "acciones", "orderable": false, "searchable": false }
        ],
        "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
    });

    $('#formCurso').submit(function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        $.post('controllers/cursos_ajax.php?action=guardar', data, function(res) {
            if (res.status === 'success') {
                $('#modalCurso').modal('hide');
                tablaCursos.ajax.reload();
                Swal.fire('¡Guardado!', 'Curso guardado correctamente', 'success');
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    });
});

function abrirModalCurso(id = null) {
    $('#formCurso')[0].reset();
    $('#curso_id').val('');
    $('#modalCursoLabel span').text('Nuevo Curso');
    
    if (id) {
        $('#modalCursoLabel span').text('Editar Curso');
        $.get('controllers/cursos_ajax.php?action=obtener', { id: id }, function(res) {
            if (res.status === 'success') {
                $('#curso_id').val(res.data.idcurso);
                $('#nombre_curso').val(res.data.curso);
                $('#turno').val(res.data.turno);
                $('#modalCurso').modal('show');
            }
        }, 'json');
    } else {
        $('#modalCurso').modal('show');
    }
}

function alternarEstadoCurso(id, estadoActual) {
    let nuevoEstado = estadoActual == 1 ? 0 : 1;
    let accion = estadoActual == 1 ? 'desactivar' : 'activar';
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas ${accion} este curso?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('controllers/cursos_ajax.php?action=estado', { id: id, estado: nuevoEstado }, function(res) {
                if (res.status === 'success') {
                    tablaCursos.ajax.reload(null, false);
                    Swal.fire('¡Actualizado!', 'El estado ha sido actualizado.', 'success');
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        }
    });
}
</script>
