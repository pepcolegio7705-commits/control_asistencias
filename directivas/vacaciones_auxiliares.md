# Módulo de Cálculo de Vacaciones para Agentes Auxiliares

## Objetivo
Implementar un módulo en PHP y MySQL que calcule el número de días de vacaciones correspondientes a un agente auxiliar basándose en su antigüedad (fecha de ingreso), de acuerdo con los criterios establecidos por la institución.

## Criterios de Cálculo de Vacaciones
La cantidad de días se calcula evaluando la diferencia entre la fecha de ingreso del agente y la fecha actual:
- 1 año o menos: 12 días
- 2 años: 14 días
- 3 años: 16 días
- 4 años: 18 días
- 5 años: 20 días
- De 6 a 10 años: 25 días
- De 11 a 15 años: 30 días
- De 16 a 20 años: 35 días
- De 21 a 25 años: 40 días
- De 26 a 30 años: 45 días

*Nota: Estos criterios deben ser almacenados en la base de datos para que sean dinámicamente modificables desde un nuevo módulo.*

## Plan de Implementación (Fase de Planificación)
1. **Base de Datos:**
   - Añadir la columna `fecha_ingreso` (DATE) a la tabla `auxiliares`.
   - Crear una tabla nueva `criterios_vacaciones` con los campos necesarios para almacenar los rangos (ej: `id`, `anios_min`, `anios_max`, `dias`).
   - Insertar los rangos iniciales provistos.
2. **Módulo de Auxiliares:**
   - Actualizar el alta/edición de auxiliares (`auxiliares.php` y sus vistas) para incluir el campo de `fecha_ingreso`.
3. **Módulo de Criterios de Vacaciones:**
   - Crear una nueva sección/pantalla para listar, agregar, editar y eliminar los criterios de vacaciones.
4. **Cálculo de Vacaciones:**
   - Crear la lógica en PHP para calcular los días disponibles para cada auxiliar cruzando su fecha de ingreso con los criterios almacenados en DB.

## Restricciones / Casos Borde
- **Tecnología Estricta:** El sistema debe construirse pura y exclusivamente en PHP y MySQL.
- **Sin Python:** Todo el análisis y la ejecución se realizarán sin usar scripts de Python ni dependencias externas.
- **Dinamismo:** Nunca 'hardcodear' los días en el código; siempre leer de la base de datos.
- **REGLA GLOBAL - Base de Datos:** Siempre que el agente necesite modificar la base de datos, debe crear un script de actualización (ej: `db_update_*.php`) y **ejecutarlo automáticamente** (vía `read_url_content`) sin esperar que el usuario lo corra manualmente.
