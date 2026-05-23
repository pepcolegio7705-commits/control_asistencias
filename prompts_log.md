# Registro de Prompts (Recursos Educativos)
**Proyecto:** Sistema de Control de Asistencias

En este archivo se documentarán los *prompts* utilizados durante el ciclo de vida del proyecto, estructurados por título y propósito, para su utilización en el canal de YouTube (Informática y Desarrollo).

---

### [Prompt] 1. Creación de Reglas e Identidad Autónoma
**Propósito:** Inyectar en el agente de IA una estructura de desarrollo, protocolo de control de versiones y mentalidad Full-Stack.
**Contexto de Uso:** Ideal para iniciar cualquier proyecto y forzar a la IA a organizar sus conocimientos antes de programar a ciegas.

**El Prompt (Estructura base usada):**
> *"Actúa como un Agente de Desarrollo Autónomo. Nunca escribas código sin un plan. Utiliza el siguiente Bucle Central: 1) Consultar/Crear Directiva, 2) Ejecutar, 3) Observar y Aprender. Concéntrate en la lógica Full-Stack (UI Premium y Backend Seguro) y automatiza los comandos de Git al finalizar un módulo. Si hay errores, documenta en la directiva para no volver a cometerlos."*

---

### [Prompt] 2. Ejecución de Módulos en PHP (PDO + Server-Side)
**Propósito:** Generar un ABM (Alta, Baja, Modificación) robusto, paginado desde el servidor y resistente a inyecciones SQL.
**Contexto de Uso:** Cuando las tablas de bases de datos son grandes y se necesita velocidad de carga y seguridad extrema (Contraseñas hasheadas y validaciones).

**El Prompt (Estructura base usada):**
> *"Desarrolla el módulo de 'Gestión de Profesores'. Requisitos estrictos: Usa PDO con sentencias preparadas. No uses DELETE, utiliza eliminación lógica (estado=0). La interfaz debe ser Bootstrap 5 y la tabla de listado debe ser 'DataTables Server-Side'. Encripta las IDs que pasen por AJAX. Captura errores de unicidad de la BD y muestra alertas de SweetAlert2."*
