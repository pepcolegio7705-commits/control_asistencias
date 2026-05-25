# Gestión de Usuarios y Roles (RBAC)

## Propósito
Este módulo establece las directrices para el control de acceso en el sistema de asistencias, restringiendo acciones según el rol del usuario conectado.

## Roles del Sistema

1. **admin**
   - Acceso total a todos los módulos.
   - Es el único rol que puede ver, crear, editar y eliminar usuarios.

2. **Administrativo**
   - Acceso total a los módulos operativos (Profesores, Auxiliares, Artículos, Asistencias).
   - Puede crear, editar, y eliminar registros operativos.
   - **No tiene acceso** a la gestión de usuarios.

3. **Usuario**
   - Acceso de "Solo Lectura" a nivel global.
   - Puede navegar por todos los módulos operativos y visualizar los registros.
   - **No tiene permiso** para agregar, modificar o eliminar registros.

## Reglas de Implementación

### 1. Seguridad Frontend (UI)
- Si `$_SESSION['rol'] === 'usuario'`, todos los botones de "Nuevo", "Editar", "Eliminar" y "Cargar Vacaciones" deben estar **ocultos** en las vistas (`.php`). Solo se mostrarán los botones de consulta (ej. "Ver Información").
- En el menú lateral (`views/layout/header.php`), el enlace a la Gestión de Usuarios solo se imprime si `$_SESSION['rol'] === 'admin'`.

### 2. Seguridad Backend (Controladores)
- **Bloqueo de Modificación:** Todo controlador AJAX (`profesores_ajax.php`, `asistencias_ajax.php`, etc.) debe verificar el rol en cada acción que altere datos (`guardar`, `eliminar`, `cargar_vacacion`, `editar_vacacion`).
- **Código de Bloqueo:**
  ```php
  if ($_SESSION['rol'] === 'usuario') {
      echo json_encode(['status' => 'error', 'msg' => 'Acceso denegado. Permisos de solo lectura.']);
      exit;
  }
  ```
- **Módulo de Usuarios:** `usuarios_ajax.php` y `usuarios.php` deben bloquear completamente el acceso a cualquier rol distinto a `admin`.

### 3. Autenticación Segura
- Las contraseñas de los usuarios siempre deben almacenarse con `password_hash()` (algoritmo bcrypt por defecto en PHP).
- La sesión almacena `$_SESSION['rol']` al iniciar sesión.
