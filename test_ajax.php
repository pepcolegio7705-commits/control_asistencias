<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$_SESSION['user_id'] = 1; // fake login
$_SESSION['rol'] = 'admin';

// fake POST data
$_POST['action'] = 'listar';
$_POST['draw'] = 1;
$_POST['start'] = 0;
$_POST['length'] = 10;
$_POST['search'] = ['value' => ''];

// execute controller
ob_start();
require_once 'controllers/profesores_ajax.php';
$output = ob_get_clean();

echo "OUTPUT:\n$output\n";
?>
