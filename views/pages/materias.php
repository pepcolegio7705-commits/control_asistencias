<?php
$isUsuario = isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 text-secondary"><i class="fa-solid fa-book-open me-2"></i> Gestión de Materias</h1>
</div>

<div class="card p-4">
    <div class="d-flex justify-content-between mb-3">
        <h5 class="card-title mb-0">Listado de Materias por Curso</h5>
        <?php if (!$isUsuario): ?>
        <button class="btn btn-primary btn-sm" onclick="abrirModalMateria()">
            <i class="fa-solid fa-plus me-1"></i> Nueva Materia
        </button>
        <?php endif; ?>
    </div>
    
    <div class="table-responsive">
        <table id="materiasTable" class="table table-striped table-hover align-middle" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre de Materia</th>
                    <th>Curso Perteneciente</th>
                    <th>Tipo</th>
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
<!-- Modal Materia -->
<div class="modal fade" id="modalMateria" tabindex="-1" aria-labelledby="modalMateriaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalMateriaLabel"><i class="fa-solid fa-book-open me-2"></i> <span>Nueva Materia</span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formMateria">
          <div class="modal-body">
              <input type="hidden" id="materia_id" name="materia_id">
              <div class="mb-3">
                  <label class="form-label fw-bold">Nombre de la Materia</label>
                  <input type="text" class="form-control" id="descripcion" name="descripcion" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold">Curso al que Pertenece</label>
                  <select class="form-select" id="curso" name="curso" required>
                      <option value="">-- Cargando Cursos... --</option>
                  </select>
              </div>
              <div class="mb-3">
                  <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="es_especial" name="es_especial" value="1">
                      <label class="form-check-label fw-bold" for="es_especial">Materia Especial (60 minutos a contraturno)</label>
                      <small class="d-block text-muted">Marque esta opción para materias como Educación Física.</small>
                  </div>
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
let tablaMaterias;

$(document).ready(function() {
    tablaMaterias = $('#materiasTable').DataTable({
        "ajax": {
            "url": "controllers/materias_ajax.php?action=listar",
            "type": "GET",
            "dataSrc": "data"
        },
        "columns": [
            { "data": "id" },
            { "data": "descripcion" },
            { "data": "nombre_curso" },
            { 
                "data": "es_especial",
                "render": function(data) {
                    return data == 1 ? '<span class="badge bg-primary"><i class="fa-solid fa-stopwatch"></i> Especial (60m)</span>' : '<span class="badge bg-secondary"><i class="fa-solid fa-clock"></i> Normal (40m)</span>';
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

    <?php if (!$isUsuario): ?>
    // Cargar select de cursos
    $.get('controllers/materias_ajax.php?action=obtener_cursos', function(res) {
        let options = '<option value="">-- Seleccione un Curso --</option>';
        res.data.forEach(c => {
            options += `<option value="${c.idcurso}">${c.curso} ${c.turno ? '('+c.turno+')' : ''}</option>`;
        });
        $('#curso').html(options);
    }, 'json');

    $('#formMateria').submit(function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        $.post('controllers/materias_ajax.php?action=guardar', data, function(res) {
            if (res.status === 'success') {
                $('#modalMateria').modal('hide');
                tablaMaterias.ajax.reload();
                Swal.fire('¡Guardado!', 'Materia guardada correctamente', 'success');
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    });
    <?php endif; ?>
});

function abrirModalMateria(id = null) {
    $('#formMateria')[0].reset();
    $('#materia_id').val('');
    $('#modalMateriaLabel span').text('Nueva Materia');
    
    if (id) {
        $('#modalMateriaLabel span').text('Editar Materia');
        $.get('controllers/materias_ajax.php?action=obtener', { id: id }, function(res) {
            if (res.status === 'success') {
                $('#materia_id').val(res.data.id);
                $('#descripcion').val(res.data.descripcion);
                $('#curso').val(res.data.curso);
                if (res.data.es_especial == 1) {
                    $('#es_especial').prop('checked', true);
                } else {
                    $('#es_especial').prop('checked', false);
                }
                $('#modalMateria').modal('show');
            }
        }, 'json');
    } else {
        $('#modalMateria').modal('show');
    }
}

function alternarEstadoMateria(id, estadoActual) {
    let nuevoEstado = estadoActual == 1 ? 0 : 1;
    let accion = estadoActual == 1 ? 'desactivar' : 'activar';
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas ${accion} esta materia?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('controllers/materias_ajax.php?action=estado', { id: id, estado: nuevoEstado }, function(res) {
                if (res.status === 'success') {
                    tablaMaterias.ajax.reload(null, false);
                    Swal.fire('¡Actualizado!', 'El estado ha sido actualizado.', 'success');
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        }
    });
}
</script>
