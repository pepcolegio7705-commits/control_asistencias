<?php
require_once 'config/security.php';
require_login();
require_once 'views/layout/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 text-secondary"><i class="fa-solid fa-file-signature text-success me-2"></i> Códigos de Asistencia (Artículos)</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'usuario'): ?>
        <button type="button" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalArticulo" onclick="resetForm()">
            <i class="fa-solid fa-plus me-1"></i> Nuevo Artículo
        </button>
        <?php endif; ?>
        <button type="button" class="btn btn-sm btn-info shadow-sm rounded-pill px-3 py-2 ms-2 text-white" onclick="imprimirArticulos()">
            <i class="fa-solid fa-print me-1"></i> Imprimir / PDF
        </button>
    </div>
</div>

<!-- Contenedor visible solo al imprimir -->
<div id="print-articulos-container" class="d-none d-print-block w-100">
    <div id="print-articulos-content"></div>
</div>

<div class="card shadow border-0 rounded-4 mb-4 d-print-none">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tablaArticulos" class="table table-hover table-striped align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Sector</th>
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

<!-- Modal Articulo -->
<div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="modalArticuloLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-success text-white rounded-top-4">
        <h5 class="modal-title" id="modalArticuloLabel"><i class="fa-solid fa-tag me-2"></i> Datos del Artículo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formArticulo">
            <input type="hidden" id="articulo_id">
            <div class="mb-3">
                <label for="codigo" class="form-label text-muted fw-bold">Código Interno</label>
                <input type="text" class="form-control form-control-lg bg-light" id="codigo" required placeholder="Ej: ART-ENF" style="text-transform: uppercase;">
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label text-muted fw-bold">Descripción Completa</label>
                <input type="text" class="form-control form-control-lg bg-light" id="descripcion" required placeholder="Ej: Enfermedad con goce de sueldo">
            </div>
            <div class="mb-4">
                <label for="sector" class="form-label text-muted fw-bold">Sector Aplicable</label>
                <select class="form-select form-select-lg bg-light" id="sector" required>
                    <option value="ambos">Ambos (Docentes y Auxiliares)</option>
                    <option value="docente">Solo Docentes</option>
                    <option value="auxiliar">Solo Auxiliares</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-sm">Guardar Artículo</button>
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
        tabla = $('#tablaArticulos').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: 'controllers/articulos_ajax.php',
                type: 'POST',
                data: { action: 'listar' }
            }
        });

        $('#formArticulo').on('submit', function(e) {
            e.preventDefault();
            const formData = {
                action: 'guardar',
                id: $('#articulo_id').val(),
                codigo: $('#codigo').val(),
                descripcion: $('#descripcion').val(),
                sector: $('#sector').val()
            };
            $.post('controllers/articulos_ajax.php', formData, function(res) {
                const data = JSON.parse(res);
                if(data.status === 'success') {
                    Swal.fire('¡Éxito!', data.msg, 'success');
                    $('#modalArticulo').modal('hide');
                    tabla.ajax.reload(null, false);
                } else {
                    Swal.fire('Error', data.msg, 'error');
                }
            });
        });
    });

    function resetForm() {
        $('#formArticulo')[0].reset();
        $('#articulo_id').val('');
        $('#sector').val('ambos');
        $('#modalArticuloLabel').html('<i class="fa-solid fa-tag me-2"></i> Nuevo Artículo');
    }

    function editarArticulo(id) {
        $.post('controllers/articulos_ajax.php', { action: 'obtener', id: id }, function(res) {
            const result = JSON.parse(res);
            if(result.status === 'success') {
                const a = result.data;
                $('#articulo_id').val(a.enc_id);
                $('#codigo').val(a.codigo);
                $('#descripcion').val(a.descripcion);
                $('#sector').val(a.sector);
                $('#modalArticuloLabel').html('<i class="fa-solid fa-pen me-2"></i> Editar Artículo');
                $('#modalArticulo').modal('show');
            } else {
                Swal.fire('Error', result.msg, 'error');
            }
        });
    }

    function eliminarArticulo(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El artículo será eliminado lógicamente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('controllers/articulos_ajax.php', { action: 'eliminar', id: id }, function(res) {
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

    function imprimirArticulos() {
        // Mostrar loader
        Swal.fire({
            title: 'Preparando documento...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.post('controllers/articulos_ajax.php', { action: 'obtener_todos' }, function(res) {
            Swal.close();
            const result = JSON.parse(res);
            if (result.status === 'success') {
                const articulos = result.data;
                let html = '';

                const grupos = {
                    'docente': { titulo: 'Sector Docente', items: [] },
                    'auxiliar': { titulo: 'Sector Auxiliar', items: [] },
                    'ambos': { titulo: 'Ambos Sectores (Docentes y Auxiliares)', items: [] }
                };

                articulos.forEach(a => {
                    if (grupos[a.sector]) {
                        grupos[a.sector].items.push(a);
                    }
                });

                ['docente', 'auxiliar', 'ambos'].forEach(sec => {
                    const grupo = grupos[sec];
                    if (grupo.items.length > 0) {
                        html += `
                        <div class="mb-4">
                            <h4 class="border-bottom pb-2 mb-3" style="color: #000 !important;">${grupo.titulo}</h4>
                            <table class="table table-bordered table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">Código</th>
                                        <th style="width: 80%;">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                        grupo.items.forEach(item => {
                            html += `
                                    <tr>
                                        <td class="fw-bold">${item.codigo}</td>
                                        <td>${item.descripcion}</td>
                                    </tr>`;
                        });
                        html += `
                                </tbody>
                            </table>
                        </div>`;
                    }
                });

                $('#print-articulos-content').html(html);
                
                // Disparar impresión
                setTimeout(() => {
                    window.print();
                }, 200);
            } else {
                Swal.fire('Error', 'No se pudieron cargar los artículos.', 'error');
            }
        });
    }
</script>
