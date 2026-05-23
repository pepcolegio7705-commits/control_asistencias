<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'control_asistencias');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    // Habilitar excepciones para errores de base de datos
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Evitar emulación de consultas preparadas para mayor seguridad
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Modo de retorno por defecto a array asociativo
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos. Asegúrate de haber importado database.sql y que WAMP esté encendido. Detalle: " . $e->getMessage());
}
?>
