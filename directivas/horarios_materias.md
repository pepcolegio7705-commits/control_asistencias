# Módulo de Horarios, Cursos y Materias

## Propósito
Este documento establece las reglas de negocio y arquitectónicas para la gestión de la cuadrícula de horarios de la institución, así como la asignación de materias a los profesores, respetando la estructura de base de datos preexistente del usuario.

## Estructura de Datos (Tablas Reales)

1. **`cursos`**:
   - Campos base: `idcurso` (PK), `curso` (Nombre, varchar), `estatus` (int).
   - *Agregado:* `turno` (ENUM: 'Mañana' o 'Tarde'). Se debe agregar mediante `ALTER TABLE`.
   - *Regla:* Los cursos representan aulas/divisiones reales (ej: "1° Año 1° Div.").

2. **`materias`**:
   - Campos base: `id` (PK), `descripcion` (Nombre, varchar), `curso` (FK a cursos.idcurso), `iddepartamento`, `estatus`.
   - *Agregado:* `es_especial` (Booleano). Se debe agregar mediante `ALTER TABLE`.
   - *Regla:* Las materias normales ocupan módulos de 40 minutos. Si `es_especial = 1` (ej. Educación Física), ocupan 1 hora reloj (60 minutos) y se dictan a contraturno. **IMPORTANTE:** En esta arquitectura, una materia está acoplada inherentemente a un curso. No se puede desvincular. Por ende, la asignación es 1-a-1 con el curso.

3. **`profesor_materia` (Asignaciones)**:
   - Tabla pivote que asocia a un Profesor (`profesor_id`) con una Materia (`materia_id`).
   - Dado que la Materia ya conoce su Curso (campo `curso` en `materias`), asignar un profesor a una materia automáticamente define el trinomio Profesor-Curso-Materia.

4. **`horarios`**:
   - Guarda el bloque horario específico para una asignación en la semana.
   - `asignacion_id` (FK a `profesor_materia.id`)
   - `dia_semana` (1 = Lunes, 5 = Viernes)
   - `hora_inicio` (TIME)
   - `hora_fin` (TIME)

## Lógica de UI y Grilla (Horarios y Recreos)

**Turno Mañana:**
- M1: 08:00 a 08:40
- M2: 08:40 a 09:20
- *Recreo: 09:20 a 09:30*
- M3: 09:30 a 10:10
- M4: 10:10 a 10:50
- *Recreo: 10:50 a 11:00*
- M5: 11:00 a 11:40
- M6: 11:40 a 12:20
- *Recreo: 12:20 a 12:30*
- M7: 12:30 a 13:10 (Se debe dar la flexibilidad de que dure 40 mins o 30 mins, guardando la `hora_fin` real en la DB).

**Turno Tarde:**
- M1: 13:30 a 14:10
- M2: 14:10 a 14:50
- *Recreo: 14:50 a 15:00*
- M3: 15:00 a 15:40
- M4: 15:40 a 16:20
- *Recreo: 16:20 a 16:30*
- M5: 16:30 a 17:10
- M6: 17:10 a 17:50
- *Recreo: 17:50 a 18:00*
- M7: 18:00 a 18:40 (Igual que M7 Mañana).

**Restricciones de Roles:**
- El frontend construirá la grilla visual filtrando los registros por `hora_inicio`. 
- Rol `usuario`: solo podrá ver los módulos operativos (solo lectura). Los botones de CRUD estarán ocultos.
- Rol `Administrativo`: puede gestionar todo el módulo operativo.
