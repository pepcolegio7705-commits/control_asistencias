<?php
// config/security.php

// Una clave secreta para la encriptación (debería estar en un archivo .env, pero por simplicidad para WAMP la dejamos aquí)
define('SECRET_KEY', 'Asistencias_S3cr3t_2026!');
define('SECRET_IV', '9876543210123456');

/**
 * Encripta un ID o texto
 * @param string|int $string
 * @return string
 */
function encrypt_id($string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);
    // Hacer seguro para URL
    return str_replace(['+','/','='], ['-','_',''], $output);
}

/**
 * Desencripta un ID o texto
 * @param string $string
 * @return string
 */
function decrypt_id($string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    // Revertir de URL segura a base64
    $string = str_replace(['-','_'], ['+','/'], $string);
    // Agregar padding '=' faltante si es necesario
    $pad = strlen($string) % 4;
    if ($pad) {
        $string .= str_repeat('=', 4 - $pad);
    }
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}

/**
 * Verifica que el usuario haya iniciado sesión
 */
function require_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header("Location: /control_asistencias/index.php?page=login");
        exit;
    }
}
?>
