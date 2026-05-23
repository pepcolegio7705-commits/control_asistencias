<?php
require_once 'config/security.php';
require_login();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Control de Asistencias</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { padding-top: 56px; }
        .sidebar { position: fixed; top: 56px; bottom: 0; left: 0; z-index: 100; padding: 48px 0 0; box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); }
        .sidebar-sticky { position: relative; top: 0; height: calc(100vh - 48px); padding-top: .5rem; overflow-x: hidden; overflow-y: auto; }
        .nav-link { font-weight: 500; color: #333; }
        .nav-link.active { color: #0d6efd; }
    </style>
</head>
<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="index">Asistencias</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav w-100 d-flex flex-row justify-content-end px-3">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="logout">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index">
                                <i class="fa-solid fa-home me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profesores">
                                <i class="fa-solid fa-users me-2"></i> Profesores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="articulos">
                                <i class="fa-solid fa-file-signature me-2"></i> Artículos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="asistencias">
                                <i class="fa-solid fa-calendar-check me-2"></i> Planilla de Asistencia
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
