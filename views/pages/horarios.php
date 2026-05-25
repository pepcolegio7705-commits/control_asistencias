<?php
$isUsuario = isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 text-secondary"><i class="fa-solid fa-calendar-days me-2"></i> Cuadrícula de Horarios</h1>
</div>

<div class="card p-3 mb-4 bg-light">
    <div class="row align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-bold">Seleccionar Curso</label>
            <select class="form-select" id="select_curso">
                <option value="">-- Seleccione un Curso --</option>
            </select>
        </div>
        <div class="col-md-8 text-end">
            <span class="badge bg-primary fs-6 me-2"><i class="fa-solid fa-clock"></i> Módulos: 40m</span>
            <span class="badge bg-secondary fs-6"><i class="fa-solid fa-stopwatch"></i> Recreos: 10m</span>
        </div>
    </div>
</div>

<div class="card p-4 shadow-sm" id="horario_container" style="display:none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary mb-0" id="horario_titulo">Horario Semanal</h4>
        <button class="btn btn-outline-danger" onclick="imprimirPDF()">
            <i class="fa-solid fa-file-pdf me-1"></i> Imprimir PDF
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm text-center align-middle" id="tabla_horarios">
            <thead class="table-dark">
                <tr>
                    <th style="width: 15%">Horario</th>
                    <th style="width: 17%">Lunes</th>
                    <th style="width: 17%">Martes</th>
                    <th style="width: 17%">Miércoles</th>
                    <th style="width: 17%">Jueves</th>
                    <th style="width: 17%">Viernes</th>
                </tr>
            </thead>
            <tbody id="horario_body">
                <!-- Se dibuja por JS -->
            </tbody>
        </table>
    </div>
</div>

<?php if (!$isUsuario): ?>
<!-- Modal Asignar Bloque -->
<div class="modal fade" id="modalAsignarBloque" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fa-solid fa-chalkboard-user me-2"></i> Asignar Docente a este Horario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formAsignarBloque">
          <div class="modal-body">
              <input type="hidden" id="bloque_curso_id" name="curso_id">
              <input type="hidden" id="bloque_dia" name="dia_semana">
              <input type="hidden" id="bloque_inicio" name="hora_inicio">
              <input type="hidden" id="bloque_fin" name="hora_fin">
              
              <div class="alert alert-info py-2 mb-3">
                  <strong id="bloque_dia_texto"></strong> de <strong id="bloque_hora_texto"></strong>
              </div>
              
              <div class="mb-3">
                  <label class="form-label fw-bold">Seleccionar Asignación (Profesor - Materia)</label>
                  <select class="form-select" id="asignacion_id" name="asignacion_id" required>
                      <!-- Cargado por AJAX -->
                  </select>
              </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
              <button type="button" class="btn btn-danger" id="btnVaciarBloque" style="display:none;"><i class="fa-solid fa-trash me-1"></i> Vaciar Casillero</button>
              <div>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Guardar</button>
              </div>
          </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>



<script>
const isUsuario = <?php echo $isUsuario ? 'true' : 'false'; ?>;
const horariosManana = [
    { inicio: '08:00', fin: '08:40', tipo: 'clase', label: '1° Módulo' },
    { inicio: '08:40', fin: '09:20', tipo: 'clase', label: '2° Módulo' },
    { inicio: '09:20', fin: '09:30', tipo: 'recreo', label: 'Recreo' },
    { inicio: '09:30', fin: '10:10', tipo: 'clase', label: '3° Módulo' },
    { inicio: '10:10', fin: '10:50', tipo: 'clase', label: '4° Módulo' },
    { inicio: '10:50', fin: '11:00', tipo: 'recreo', label: 'Recreo' },
    { inicio: '11:00', fin: '11:40', tipo: 'clase', label: '5° Módulo' },
    { inicio: '11:40', fin: '12:20', tipo: 'clase', label: '6° Módulo' },
    { inicio: '12:20', fin: '12:30', tipo: 'recreo', label: 'Recreo' },
    { inicio: '12:30', fin: '13:10', tipo: 'clase', label: '7° Módulo' }
];

const horariosTarde = [
    { inicio: '13:30', fin: '14:10', tipo: 'clase', label: '1° Módulo' },
    { inicio: '14:10', fin: '14:50', tipo: 'clase', label: '2° Módulo' },
    { inicio: '14:50', fin: '15:00', tipo: 'recreo', label: 'Recreo' },
    { inicio: '15:00', fin: '15:40', tipo: 'clase', label: '3° Módulo' },
    { inicio: '15:40', fin: '16:20', tipo: 'clase', label: '4° Módulo' },
    { inicio: '16:20', fin: '16:30', tipo: 'recreo', label: 'Recreo' },
    { inicio: '16:30', fin: '17:10', tipo: 'clase', label: '5° Módulo' },
    { inicio: '17:10', fin: '17:50', tipo: 'clase', label: '6° Módulo' },
    { inicio: '17:50', fin: '18:00', tipo: 'recreo', label: 'Recreo' },
    { inicio: '18:00', fin: '18:40', tipo: 'clase', label: '7° Módulo' }
];

const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
let cursoSeleccionado = null;
let bloquesHorario = [];

$(document).ready(function() {
    // Cargar select de cursos
    $.get('controllers/materias_ajax.php?action=obtener_cursos', function(res) {
        if(res.status === 'success') {
            let options = '<option value="">-- Seleccione un Curso --</option>';
            res.data.forEach(c => {
                options += `<option value="${c.idcurso}" data-turno="${c.turno}">${c.curso} ${c.turno ? '('+c.turno+')' : ''}</option>`;
            });
            $('#select_curso').html(options);
        }
    }, 'json');

    $('#select_curso').change(function() {
        let opt = $(this).find('option:selected');
        cursoSeleccionado = opt.val();
        let turno = opt.data('turno');
        
        if (cursoSeleccionado && turno) {
            $('#horario_titulo').text(`Horario Semanal - ${opt.text()}`);
            $('#horario_container').fadeIn();
            dibujarGrilla(turno);
            cargarDatosGrilla(cursoSeleccionado);
        } else {
            $('#horario_container').fadeOut();
            if (cursoSeleccionado && !turno) {
                Swal.fire('Atención', 'Este curso no tiene un Turno asignado. Asignele un turno desde Gestión de Cursos.', 'warning');
            }
        }
    });

    <?php if (!$isUsuario): ?>
    $('#formAsignarBloque').submit(function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        $.post('controllers/horarios_ajax.php?action=guardar_bloque', data, function(res) {
            if (res.status === 'success') {
                $('#modalAsignarBloque').modal('hide');
                cargarDatosGrilla(cursoSeleccionado);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    });

    $('#btnVaciarBloque').click(function() {
        let dia = $('#bloque_dia').val();
        let inicio = $('#bloque_inicio').val();
        
        $.post('controllers/horarios_ajax.php?action=vaciar_bloque', {
            curso_id: cursoSeleccionado, dia_semana: dia, hora_inicio: inicio
        }, function(res) {
            if (res.status === 'success') {
                $('#modalAsignarBloque').modal('hide');
                cargarDatosGrilla(cursoSeleccionado);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    });
    <?php endif; ?>
});

function dibujarGrilla(turno) {
    let grilla = turno === 'Mañana' ? horariosManana : horariosTarde;
    let html = '';
    
    grilla.forEach((b) => {
        if (b.tipo === 'recreo') {
            html += `<tr class="table-secondary text-muted fst-italic">
                        <td class="fw-bold">${b.inicio} - ${b.fin}</td>
                        <td colspan="5" class="text-center">${b.label}</td>
                     </tr>`;
        } else {
            html += `<tr>
                        <td class="fw-bold align-middle bg-light">${b.inicio} - ${b.fin}<br><small class="text-muted">${b.label}</small></td>`;
            for(let i = 1; i <= 5; i++) {
                let cellId = `cell_${i}_${b.inicio.replace(':','')}`;
                html += `<td id="${cellId}" class="bloque-clase" style="cursor: ${isUsuario ? 'default' : 'pointer'}; height: 70px; vertical-align: middle;" 
                            ${!isUsuario ? `onclick="abrirModalBloque(${i}, '${b.inicio}', '${b.fin}')"` : ''}>
                            <span class="text-muted small">Libre</span>
                         </td>`;
            }
            html += `</tr>`;
        }
    });
    $('#horario_body').html(html);
}

function cargarDatosGrilla(cursoId) {
    // Primero limpiar la grilla
    $('.bloque-clase').html('<span class="text-muted small">Libre</span>').css('background-color', '');
    
    // Traer los bloques de la DB
    $.get('controllers/horarios_ajax.php?action=obtener_grilla', { curso_id: cursoId }, function(res) {
        if(res.status === 'success') {
            res.data.forEach(h => {
                let cellId = `cell_${h.dia_semana}_${h.hora_inicio.substring(0,5).replace(':','')}`;
                let cell = $(`#${cellId}`);
                if (cell.length) {
                    cell.html(`<div class="fw-bold text-dark">${h.materia}</div><div class="small text-primary">${h.apellido}, ${h.nombre}</div>`);
                    cell.css('background-color', 'rgba(111, 177, 252, 0.1)'); // Color azulcito sutil
                }
            });
        }
    }, 'json');
}

<?php if (!$isUsuario): ?>
function abrirModalBloque(dia, inicio, fin) {
    $('#bloque_curso_id').val(cursoSeleccionado);
    $('#bloque_dia').val(dia);
    $('#bloque_inicio').val(inicio);
    $('#bloque_fin').val(fin);
    
    $('#bloque_dia_texto').text(dias[dia-1]);
    $('#bloque_hora_texto').text(inicio + ' a ' + fin);
    
    // Comprobar si la celda tiene contenido para mostrar el botón de vaciar
    let cellId = `cell_${dia}_${inicio.replace(':','')}`;
    if ($(`#${cellId}`).text().includes('Libre')) {
        $('#btnVaciarBloque').hide();
    } else {
        $('#btnVaciarBloque').show();
    }
    
    // Cargar opciones de asignaciones disponibles para este curso
    $('#asignacion_id').html('<option>Cargando...</option>');
    $.get('controllers/horarios_ajax.php?action=obtener_asignaciones_curso', { curso_id: cursoSeleccionado }, function(res) {
        if(res.status === 'success') {
            let options = '<option value="">-- Seleccione una Asignación --</option>';
            res.data.forEach(a => {
                options += `<option value="${a.id}">${a.materia} - Prof. ${a.apellido}</option>`;
            });
            $('#asignacion_id').html(options);
            $('#modalAsignarBloque').modal('show');
        }
    }, 'json');
}
<?php endif; ?>
function imprimirPDF() {
    if (!cursoSeleccionado) {
        Swal.fire('Atención', 'Primero debe seleccionar un curso.', 'warning');
        return;
    }
    window.open('controllers/imprimir_horario.php?curso_id=' + cursoSeleccionado, '_blank');
}
</script>
