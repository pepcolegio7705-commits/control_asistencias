# Directiva: Sistema de Control de Asistencias (Institución Educativa)

Esta directiva define la arquitectura lógica y las restricciones para la construcción del sistema de asistencias. Actúa como **Memoria Viva** para mí (Agente de Desarrollo Autónomo). Guiará la ejecución de mis scripts/código PHP y debo actualizarla siempre que enfrente un fallo o descubra un caso borde.

## El Bucle Central de Desarrollo (Mis Reglas de Agente)
1. **Consultar:** Revisar esta directiva antes de escribir cualquier código de asistencia.
2. **Ejecutar:** Programar el código (interfaz gráfica y ABM) simultáneamente, basándome estrictamente en esta estructura.
3. **Observar y Aprender:** Si encuentro un error (ej. problemas con DataTables, IDs ofuscados o PDO), corrijo el código y documento el problema en las secciones de *Restricciones* abajo.

## Flujo de Desarrollo (Regla Global del Sistema)
- **Desarrollo Integral:** Al desarrollar cada módulo, se debe construir la interfaz gráfica y **simultáneamente** los códigos ABM (backend). Las tablas de los DataTables deben cargarse siempre con los datos reales de la base de datos desde el inicio, salvo que se solicite explícitamente usar datos de prueba.

## Módulo: Base de Datos (Estructura Inicial)
### Objetivo
Soportar la lógica de "Presente por defecto" y eliminaciones lógicas.
### Lógica
- Tabla `usuarios`: id, username, password, rol, estado.
- Tabla `profesores`: id, nombre, apellido, dni, **cuil** (único), **direccion**, **telefono**, **mail**, **titulo**, **legajo** (único), estado (eliminación lógica).
- Tabla `articulos`: id, codigo, descripcion, estado (eliminación lógica).
- Tabla `asistencias`: id, profesor_id, fecha, articulo_id (solo se insertan registros cuando el profesor se ausenta).

### Restricciones / Casos Borde
- **Campos únicos en profesores:** DNI, CUIL y Legajo tienen restricciones UNIQUE. Los demás campos nuevos (direccion, telefono, mail, titulo) son informativos sin restricción de unicidad.
- **Campos opcionales:** Solo Nombre, Apellido y DNI son obligatorios. Los 6 campos nuevos (cuil, direccion, telefono, mail, titulo, legajo) son opcionales (NULL por defecto).
- **Migración:** Para bases de datos existentes, ejecutar `alter_profesores.sql` (ALTER TABLE) en lugar de recrear la tabla.

## Módulo: Autenticación (Login)
### Objetivo
Controlar el acceso al sistema mediante usuarios, protegiendo rutas y datos.
### Restricciones / Casos Borde
- Contraseñas deben encriptarse siempre con `password_hash()`.
- Protección contra SQL Injection forzando el uso de consultas preparadas PDO en toda interacción.
- Regenerar el ID de sesión para evitar *Session Fixation*.
### Lógica
1. Recepción de credenciales.
2. Validación PDO.
3. Creación de variables de sesión (`$_SESSION['user_id']`, etc.).

## Módulo: Gestión de Profesores y Artículos (ABM)
### Objetivo
Administrar los catálogos maestros del sistema.
### Restricciones / Casos Borde
- **Eliminación Lógica:** Nunca usar `DELETE`. Siempre actualizar campo `estado = 0`.
- **Rendimiento:** El listado debe hacerse obligatoriamente mediante **DataTables Server-Side** para asegurar que no se sature al tener miles de registros.
- Identificadores (IDs) pasados por URL deben estar encriptados/ofuscados.
- **Modal de Profesores:** Usar `modal-lg` para acomodar los campos en layout de 2 columnas (row/col-md-6).
- **Errores de unicidad:** Detectar qué campo causó el error duplicado (DNI, CUIL o Legajo) y mostrar mensaje específico.

## Módulo: Planilla de Asistencia (Estilo Excel)
### Objetivo
Interfaz con pestañas (meses + resumen anual), filas (profesores) y columnas (días del mes/contadores).
### Restricciones / Casos Borde
- **Lógica de Ausencias:** Si no existe un registro en la tabla `asistencias` para un profesor y fecha, se asume PRESENTE. No se debe insertar "presentes" masivos en la base de datos (por eficiencia).
- **Interfaz:** Las pestañas de meses deben renderizar los días hábiles del año lectivo. Al hacer clic en un día para un profesor, se abrirá un select/modal con los Artículos activos.
- Los contadores deben ser consultas SQL de agrupación (`COUNT() y GROUP BY`) filtrando por mes y año, o directamente en PHP al renderizar la tabla del mes.
- **CRÍTICO - Contadores Compactos:** Con muchos artículos (~40), los contadores NO deben ser columnas individuales por artículo. En su lugar, usar una **columna única "Faltas"** con un **popover de Bootstrap** que muestra el desglose por artículo al hacer clic. Esto aplica tanto a la planilla mensual como al resumen anual.
- **Resumen Anual:** Debe mostrar columnas por mes (Ene-Dic) + columna Total Anual con popover de desglose. NO mostrar una columna por cada artículo.
- **Popovers:** Deben reinicializarse cada vez que se carga nuevo HTML via AJAX (`inicializarPopovers()`). Usar `placement: 'left'` y cerrar los demás al abrir uno nuevo.
