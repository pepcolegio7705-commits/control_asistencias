# Planeamiento y Bitácora de Desarrollo (Changelog)
**Proyecto:** Sistema de Control de Asistencias

Este documento sirve como registro histórico de todas las acciones, pasos técnicos y decisiones tomadas en el desarrollo. Actúa como memoria a largo plazo.

## [2026-05-23] - Configuración Inicial y Auditoría
1. **Configuración Global:** Se establecieron las reglas globales de desarrollo Full-Stack, sincronización de GitHub, diseño UI premium y procesos de agencia de marketing.
2. **Inicialización de Git:** Se configuró el repositorio local, enlazado a `pepcolegio7705-commits/control_asistencias` y se configuró la identidad de autor `pepcolegio7705@gmail.com`.
3. **Auditoría de Módulos (Backend & UI):**
   - **Base de Datos:** Se crearon las tablas `usuarios`, `profesores`, `articulos`, y `asistencias`. Se generó el script `install_db.php` para inyectar los datos en el WAMP evadiendo restricciones de permisos de terminal local.
   - **Login:** Se validó la seguridad con PDO y `password_verify()`.
   - **ABM Profesores:** Implementación de DataTables Server-Side y campos específicos (CUIL, Legajo, etc.). Eliminación lógica configurada (`estado = 0`).
   - **Asistencias (Planilla Excel):** Lógica validada de "Presente por Defecto". Se agruparon las faltas en una sola columna con popovers dinámicos (Bootstrap) para evitar la saturación visual.
4. **Documentación:** Se generaron el Registro de Prompts, el Manual de Usuario comercial y esta bitácora.
