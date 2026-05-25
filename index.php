<?php
require_once 'config/security.php';
session_start();

// Validar si el usuario está logueado, excepto para la página de login
$page = $_GET['page'] ?? 'dashboard';

// Páginas que no requieren header/footer completo (por ahora login, que tiene su propio layout en la raíz)
// Nota: Si pasamos a ?page=login, login.php debería estar en views/pages, pero lo dejaremos en la raíz por compatibilidad por ahora.
if ($page === 'login') {
    // Si ya está logueado, mandarlo al dashboard
    if (isset($_SESSION['usuario_id'])) {
        header("Location: index.php?page=dashboard");
        exit;
    }
    require_once 'login.php';
    exit;
}

if ($page === 'logout') {
    require_once 'logout.php';
    exit;
}

// Para todas las demás páginas, exigir login
require_login();

// Lista blanca de páginas permitidas por seguridad
$allowed_pages = [
    'dashboard', 
    'profesores', 
    'asistencias', 
    'auxiliares', 
    'asistencias_auxiliares', 
    'vacaciones_criterios', 
    'articulos', 
    'usuarios', 
    'cursos', 
    'materias', 
    'asignaciones', 
    'horarios'
];

// Si la página no existe en la lista, forzar al dashboard
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

// Variable global útil para las vistas
$isUsuario = isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario';

// Cargar la estructura (MVC)
require_once 'views/layout/header.php';

// Cargar la vista (módulo)
$view_path = 'views/pages/' . $page . '.php';
if (file_exists($view_path)) {
    require_once $view_path;
} else {
    echo "<div class='alert alert-danger mt-4'>Error: El módulo solicitado no existe o fue movido.</div>";
}

require_once 'views/layout/footer.php';
?>
