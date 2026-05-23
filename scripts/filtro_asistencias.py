import sys
import re

file_path = r"c:\wamp64\www\control_asistencias\asistencias.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add input field
input_html = """        <label for="inputBuscarProfesor" class="me-2 fw-bold text-muted"><i class="fa-solid fa-search"></i> Buscar:</label>
        <input type="text" id="inputBuscarProfesor" class="form-control form-control-sm shadow-sm me-3" style="width: 200px;" placeholder="Nombre de profesor...">
        <label for="selectAnio" class="me-2 fw-bold text-muted">Año Lectivo:</label>"""
content = content.replace('<label for="selectAnio" class="me-2 fw-bold text-muted">Año Lectivo:</label>', input_html)

# 2. Add keyup event
keyup_js = """        // Al cambiar el año
        $('#selectAnio').change(function() {
            cargarPlanilla();
        });

        // Búsqueda en tiempo real
        $('#inputBuscarProfesor').on('keyup', function() {
            filtrarTablaProfesores();
        });"""
content = content.replace("""        // Al cambiar el año
        $('#selectAnio').change(function() {
            cargarPlanilla();
        });""", keyup_js)

# 3. Add filter function
filter_fn = """    function filtrarTablaProfesores() {
        const query = $('#inputBuscarProfesor').val().toLowerCase();
        $('#contenedorPlanilla table tbody tr').each(function() {
            const profesorName = $(this).find('td:first').text().toLowerCase();
            if (profesorName.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function cargarPlanilla() {"""
content = content.replace('    function cargarPlanilla() {', filter_fn)

# 4. Re-apply filter after ajax
ajax_anual = """            $.post('controllers/asistencias_ajax.php', { action: 'obtener_anual', anio: anio }, function(html) {
                $('#contenedorPlanilla').html(html);
                inicializarPopovers();
                filtrarTablaProfesores();
            });"""
content = content.replace("""            $.post('controllers/asistencias_ajax.php', { action: 'obtener_anual', anio: anio }, function(html) {
                $('#contenedorPlanilla').html(html);
                inicializarPopovers();
            });""", ajax_anual)

ajax_mes = """            $.post('controllers/asistencias_ajax.php', { action: 'obtener_planilla', mes: mesSeleccionado, anio: anio }, function(html) {
                $('#contenedorPlanilla').html(html);
                inicializarPopovers();
                filtrarTablaProfesores();
            });"""
content = content.replace("""            $.post('controllers/asistencias_ajax.php', { action: 'obtener_planilla', mes: mesSeleccionado, anio: anio }, function(html) {
                $('#contenedorPlanilla').html(html);
                inicializarPopovers();
            });""", ajax_mes)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("asistencias.php modificado exitosamente.")
