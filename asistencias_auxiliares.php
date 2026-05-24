<?php
require_once 'config/security.php';
require_login();
require_once 'views/layout/header.php';

$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$anio_actual = date('Y');
$mes_actual = date('n');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 text-secondary"><i class="fa-solid fa-calendar-check text-warning me-2"></i> Planilla de Asistencia (Auxiliares)</h1>
    <div class="btn-toolbar mb-2 mb-md-0 align-items-center">
        <label for="inputBuscarAuxiliar" class="me-2 fw-bold text-muted"><i class="fa-solid fa-magnifying-glass"></i> Buscar:</label>
        <input type="text" id="inputBuscarAuxiliar" class="form-control form-control-sm shadow-sm me-3" style="width: 200px;" placeholder="Nombre de auxiliar...">
        <label for="selectAnio" class="me-2 fw-bold text-muted">Año Lectivo:</label>
        <select id="selectAnio" class="form-select form-select-sm shadow-sm" style="width: 100px;">
            <?php for($y = $anio_actual - 2; $y <= $anio_actual + 1; $y++): ?>
                <option value="<?= $y ?>" <?= $y == $anio_actual ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <button type="button" class="btn btn-sm btn-info shadow-sm rounded-pill px-3 py-1 ms-3 text-white" onclick="window.print()">
            <i class="fa-solid fa-print me-1"></i> Imprimir
        </button>
    </div>
</div>

<div class="card shadow border-0 rounded-4 mb-4">
    <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
        <ul class="nav nav-tabs" id="mesesTabs" role="tablist">
            <?php foreach($meses as $num => $nombre): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $num == $mes_actual ? 'active fw-bold' : 'text-muted' ?>" 
                            id="tab-mes-<?= $num ?>" data-bs-toggle="tab" data-mes="<?= $num ?>" 
                            type="button" role="tab" aria-selected="<?= $num == $mes_actual ? 'true' : 'false' ?>">
                        <?= $nombre ?>
                    </button>
                </li>
            <?php endforeach; ?>
            <li class="nav-item ms-auto" role="presentation">
                <button class="nav-link text-primary fw-bold" id="tab-anual" data-bs-toggle="tab" data-mes="anual" type="button" role="tab">
                    <i class="fa-solid fa-chart-line me-1"></i> Resumen Anual
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body p-0">
        <div id="contenedorPlanilla" class="p-3 overflow-auto" style="max-height: 65vh;">
            <div class="text-center py-5 text-muted">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Cargando planilla...</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asignar Artículo -->
<div class="modal fade" id="modalAsistencia" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-warning text-dark rounded-top-4">
        <h5 class="modal-title"><i class="fa-solid fa-clipboard-user me-2"></i> Cargar Falta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formAsistencia">
            <input type="hidden" id="asis_id">
            <input type="hidden" id="auxiliar_id">
            <input type="hidden" id="fecha">
            
            <div class="mb-3 text-center">
                <span class="badge bg-light text-dark border p-2" id="labelFecha"></span>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted fw-bold">Seleccionar Artículo</label>
                <select class="form-select form-select-lg bg-light" id="articulo_id" required>
                    <option value="">Cargando...</option>
                </select>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning btn-lg rounded-pill shadow-sm fw-bold">Guardar</button>
                <button type="button" id="btnEliminarFalta" class="btn btn-outline-danger btn-sm rounded-pill d-none" onclick="eliminarFalta()">Quitar Falta (Marcar Presente)</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
require_once 'views/layout/footer.php';
?>

<script>
    let mesSeleccionado = <?= $mes_actual ?>;
    
    $(document).ready(function() {
        cargarArticulosSelect();
        cargarPlanilla();

        // Al cambiar de tab
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            mesSeleccionado = $(e.target).data('mes');
            cargarPlanilla();
        });

        // Al cambiar el año
        $('#selectAnio').change(function() {
            cargarPlanilla();
        });

        // Búsqueda en tiempo real
        $('#inputBuscarAuxiliar').on('keyup', function() {
            filtrarTablaAuxiliares();
        });

        // Submit del formulario
        $('#formAsistencia').submit(function(e) {
            e.preventDefault();
            const formData = {
                action: 'guardar',
                asis_id: $('#asis_id').val(),
                auxiliar_id: $('#auxiliar_id').val(),
                fecha: $('#fecha').val(),
                articulo_id: $('#articulo_id').val()
            };
            $.post('controllers/asistencias_auxiliares_ajax.php', formData, function(res) {
                const data = JSON.parse(res);
                if(data.status === 'success') {
                    $('#modalAsistencia').modal('hide');
                    cargarPlanilla(); // recargar la vista actual
                } else {
                    Swal.fire('Error', data.msg, 'error');
                }
            });
        });
    });

    function filtrarTablaAuxiliares() {
        const query = $('#inputBuscarAuxiliar').val().toLowerCase();
        $('#contenedorPlanilla table tbody tr').each(function() {
            const auxiliarName = $(this).find('td:first').text().toLowerCase();
            if (auxiliarName.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function cargarPlanilla() {
        $('#contenedorPlanilla').html('<div class="text-center py-5 text-muted"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando...</p></div>');
        
        const anio = $('#selectAnio').val();
        
        if (mesSeleccionado === 'anual') {
            $.post('controllers/asistencias_auxiliares_ajax.php', { action: 'obtener_anual', anio: anio }, function(html) {
                $('#contenedorPlanilla').html(html);
                inicializarPopovers();
                filtrarTablaAuxiliares();
            });
        } else {
            $.post('controllers/asistencias_auxiliares_ajax.php', { action: 'obtener_planilla', mes: mesSeleccionado, anio: anio }, function(html) {
                $('#contenedorPlanilla').html(html);
                inicializarPopovers();
                filtrarTablaAuxiliares();
            });
        }
    }

    function inicializarPopovers() {
        // Inicializar popovers de Bootstrap para los badges de faltas
        const popoverList = document.querySelectorAll('.popover-faltas[data-bs-toggle="popover"]');
        popoverList.forEach(el => {
            new bootstrap.Popover(el, {
                html: true,
                trigger: 'click',
                placement: 'left',
                container: 'body'
            });
        });
        // Cerrar otros popovers al abrir uno nuevo
        $(document).on('click', '.popover-faltas', function() {
            $('.popover-faltas').not(this).each(function() {
                const popover = bootstrap.Popover.getInstance(this);
                if (popover) popover.hide();
            });
        });
    }

    function cargarArticulosSelect() {
        $.post('controllers/asistencias_auxiliares_ajax.php', { action: 'obtener_articulos' }, function(res) {
            const data = JSON.parse(res);
            if(data.status === 'success') {
                let options = '<option value="">-- Seleccione --</option>';
                data.data.forEach(art => {
                    options += `<option value="${art.id}">${art.codigo} - ${art.descripcion}</option>`;
                });
                $('#articulo_id').html(options);
            }
        });
    }

    function abrirModalAsistencia(auxiliar_id, fecha, asis_id, articulo_id) {
        $('#auxiliar_id').val(auxiliar_id);
        $('#fecha').val(fecha);
        $('#asis_id').val(asis_id);
        $('#articulo_id').val(articulo_id);
        
        // Formatear fecha para mostrar
        const partes = fecha.split('-');
        $('#labelFecha').text(partes[2] + '/' + partes[1] + '/' + partes[0]);

        if (asis_id !== '') {
            $('#btnEliminarFalta').removeClass('d-none');
        } else {
            $('#btnEliminarFalta').addClass('d-none');
        }

        $('#modalAsistencia').modal('show');
    }

    function eliminarFalta() {
        const asis_id = $('#asis_id').val();
        if(!asis_id) return;

        Swal.fire({
            title: '¿Quitar falta?',
            text: "El auxiliar volverá a estar PRESENTE en este día.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, quitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('controllers/asistencias_auxiliares_ajax.php', { action: 'eliminar', asis_id: asis_id }, function(res) {
                    const data = JSON.parse(res);
                    if(data.status === 'success') {
                        $('#modalAsistencia').modal('hide');
                        cargarPlanilla();
                    } else {
                        Swal.fire('Error', data.msg, 'error');
                    }
                });
            }
        });
    }
</script>
