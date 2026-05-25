<?php
$isUsuario = isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 text-secondary"><i class="fa-solid fa-link me-2"></i> Asignaciones Docentes</h1>
</div>

<div class="row">
    <!-- Panel Izquierdo: Lista de Profesores -->
    <div class="col-md-4">
        <div class="card p-3 mb-3">
            <h5 class="card-title"><i class="fa-solid fa-users me-2"></i> Seleccionar Docente</h5>
            <select class="form-select" id="select_profesor">
                <option value="">-- Seleccione un Docente --</option>
                <!-- Opciones cargadas por AJAX -->
            </select>
        </div>
    </div>
    
    <!-- Panel Derecho: Asignaciones del Profesor Seleccionado -->
    <div class="col-md-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="card-title mb-0" id="titulo_asignaciones">Asignaciones del Docente</h5>
                <?php if (!$isUsuario): ?>
                <button class="btn btn-primary btn-sm" id="btnNuevaAsignacion" onclick="abrirModalAsignacion()" disabled>
                    <i class="fa-solid fa-plus me-1"></i> Nueva Asignación
                </button>
                <?php endif; ?>
            </div>
            
            <div class="table-responsive">
                <table id="asignacionesTable" class="table table-striped table-hover align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>Curso</th>
                            <th>Materia</th>
                            <th>Turno</th>
                            <th>Carga Horaria</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5" class="text-center">Seleccione un docente para ver sus asignaciones</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if (!$isUsuario): ?>
<!-- Modal Nueva Asignación -->
<div class="modal fade" id="modalAsignacion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fa-solid fa-link me-2"></i> Asignar Materia</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formAsignacion">
          <div class="modal-body">
              <input type="hidden" id="asig_profesor_id" name="profesor_id">
              
              <div class="mb-3">
                  <label class="form-label fw-bold">1. Seleccione el Curso</label>
                  <select class="form-select" id="asig_curso" required>
                      <option value="">-- Seleccione un Curso --</option>
                  </select>
              </div>
              
              <div class="mb-3">
                  <label class="form-label fw-bold">2. Seleccione la Materia</label>
                  <select class="form-select" id="asig_materia" name="materia_id" required disabled>
                      <option value="">-- Primero seleccione un curso --</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Guardar Asignación</button>
          </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>



<script>
let tablaAsignaciones;
let currentProfesorId = '';

$(document).ready(function() {
    // Cargar lista de profesores
    $.get('controllers/asignaciones_ajax.php?action=obtener_profesores', function(res) {
        if(res.status === 'success') {
            let options = '<option value="">-- Seleccione un Docente --</option>';
            res.data.forEach(p => {
                options += `<option value="${p.id}">${p.apellido}, ${p.nombre} (DNI: ${p.dni})</option>`;
            });
            $('#select_profesor').html(options);
        }
    }, 'json');

    // Inicializar DataTable (vacío)
    tablaAsignaciones = $('#asignacionesTable').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        data: [],
        columns: [
            { data: "curso" },
            { data: "materia" },
            { 
                data: "turno",
                render: function(data) {
                    if (data === 'Mañana') return '<span class="badge bg-info text-dark"><i class="fa-solid fa-sun"></i> Mañana</span>';
                    if (data === 'Tarde') return '<span class="badge bg-warning text-dark"><i class="fa-solid fa-cloud-sun"></i> Tarde</span>';
                    return '<span class="badge bg-secondary">N/A</span>';
                }
            },
            { 
                data: "es_especial",
                render: function(data) {
                    return data == 1 ? '<span class="badge bg-primary">60m (Especial)</span>' : '<span class="badge bg-secondary">40m (Normal)</span>';
                }
            },
            { data: "acciones", orderable: false, searchable: false }
        ]
    });

    // Al seleccionar profesor
    $('#select_profesor').change(function() {
        currentProfesorId = $(this).val();
        if (currentProfesorId) {
            $('#btnNuevaAsignacion').prop('disabled', false);
            cargarAsignaciones(currentProfesorId);
        } else {
            $('#btnNuevaAsignacion').prop('disabled', true);
            tablaAsignaciones.clear().draw();
        }
    });

    <?php if (!$isUsuario): ?>
    // Cargar select de cursos en modal
    $.get('controllers/materias_ajax.php?action=obtener_cursos', function(res) {
        if(res.status === 'success') {
            let options = '<option value="">-- Seleccione un Curso --</option>';
            res.data.forEach(c => {
                options += `<option value="${c.idcurso}">${c.curso} ${c.turno ? '('+c.turno+')' : ''}</option>`;
            });
            $('#asig_curso').html(options);
        }
    }, 'json');

    // Al cambiar curso en modal, cargar materias
    $('#asig_curso').change(function() {
        let idcurso = $(this).val();
        if(idcurso) {
            $('#asig_materia').prop('disabled', true).html('<option>Cargando...</option>');
            $.get('controllers/asignaciones_ajax.php?action=obtener_materias_curso', { idcurso: idcurso }, function(res) {
                if(res.status === 'success') {
                    let options = '<option value="">-- Seleccione una Materia --</option>';
                    res.data.forEach(m => {
                        options += `<option value="${m.id}">${m.descripcion}</option>`;
                    });
                    $('#asig_materia').html(options).prop('disabled', false);
                }
            }, 'json');
        } else {
            $('#asig_materia').html('<option value="">-- Primero seleccione un curso --</option>').prop('disabled', true);
        }
    });

    // Guardar asignación
    $('#formAsignacion').submit(function(e) {
        e.preventDefault();
        $('#asig_profesor_id').val(currentProfesorId);
        const data = $(this).serialize();
        $.post('controllers/asignaciones_ajax.php?action=guardar', data, function(res) {
            if (res.status === 'success') {
                $('#modalAsignacion').modal('hide');
                cargarAsignaciones(currentProfesorId);
                Swal.fire('¡Guardado!', 'Asignación creada correctamente', 'success');
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    });
    <?php endif; ?>
});

function cargarAsignaciones(profesorId) {
    $.get('controllers/asignaciones_ajax.php?action=listar', { profesor_id: profesorId }, function(res) {
        if(res.status === 'success') {
            tablaAsignaciones.clear().rows.add(res.data).draw();
        }
    }, 'json');
}

function abrirModalAsignacion() {
    $('#formAsignacion')[0].reset();
    $('#asig_materia').html('<option value="">-- Primero seleccione un curso --</option>').prop('disabled', true);
    $('#modalAsignacion').modal('show');
}

function eliminarAsignacion(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará esta materia del docente y se borrarán todos sus horarios en la cuadrícula.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('controllers/asignaciones_ajax.php?action=eliminar', { id: id }, function(res) {
                if (res.status === 'success') {
                    cargarAsignaciones(currentProfesorId);
                    Swal.fire('Eliminado', 'La asignación ha sido eliminada.', 'success');
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        }
    });
}
</script>
