# Directiva: Filtro de Búsqueda en Tiempo Real para Asistencias

## Objetivo
Agregar un campo de texto en el módulo de asistencias (`asistencias.php`) que permita buscar en tiempo real a los profesores. Al escribir en el campo, las filas de la tabla de asistencias deben ocultarse o mostrarse dinámicamente según el nombre del profesor.

## Entradas y Salidas
- **Entradas:** `asistencias.php` (archivo de vista).
- **Salidas:** Modificación en `asistencias.php` para incluir el input HTML y la lógica en JavaScript.

## Restricciones / Casos Borde (Memoria Viva)
> *Nota: Todo aprendizaje nuevo tras un error se documenta aquí para no repetirlo.*
- Hay que asegurarse de que el input no se borre al cambiar de pestaña o de año, o si se borra, que quede claro para el usuario.
- El filtro debe buscar en la primera celda (`td`) de cada fila (`tr`), que contiene el nombre del profesor.
- Debe ser un filtro insensible a mayúsculas/minúsculas.
- Al cargar la tabla de nuevo (por Ajax), el script debe volver a aplicar el filtro actual si el input tiene texto. O podemos hacer que el input se escuche cada vez que se teclea, pero si se recarga la tabla hay que disparar el evento de búsqueda de nuevo.
- **Aprendizaje Técnico (Ejecución):** El sistema falló al intentar delegar el reemplazo a un script Python (error "python : The term 'python' is not recognized"). En este entorno de servidor no está instalado Python, por lo que las modificaciones a los archivos se hicieron directamente mediante las herramientas de edición de código nativas del agente, manteniendo la lógica planteada.

## Lógica (Paso a Paso)
1. Leer el archivo `asistencias.php`.
2. Insertar el HTML de un input de búsqueda arriba de la tabla, en la botonera donde se selecciona el año lectivo o en el encabezado.
3. Insertar la función en JavaScript `filtrarTablaProfesores()` que lea el valor del input, itere sobre las filas del `tbody` (o busque todos los `tr` dentro de la tabla) y aplique `display: none` o `display: table-row` según corresponda.
4. Escuchar el evento `keyup` del input de búsqueda.
5. Al final de la función `cargarPlanilla()`, disparar el evento del filtro para que se aplique sobre el HTML recién traído por Ajax.
