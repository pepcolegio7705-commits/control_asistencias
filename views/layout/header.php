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
        body { 
            padding-top: 56px; 
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }
        .main-wrapper { flex: 1; display: flex; flex-direction: column; }
        .row-wrapper { flex: 1; }
        .sidebar { 
            position: fixed; top: 56px; bottom: 0; left: 0; z-index: 100; padding: 48px 0 0; 
            background: rgba(15, 32, 39, 0.95) !important;
            box-shadow: inset -1px 0 0 rgba(255, 255, 255, .1); 
            backdrop-filter: blur(10px);
        }
        .sidebar-sticky { position: relative; top: 0; height: calc(100vh - 48px); padding-top: .5rem; overflow-x: hidden; overflow-y: auto; }
        .nav-link { font-weight: 500; color: #cbd5e1; margin-bottom: 5px; }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .nav-link.active { color: #6FB1FC; background: rgba(255,255,255,0.1); border-radius: 5px; }
        .navbar { background: rgba(15, 32, 39, 0.95) !important; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .navbar-brand { font-weight: bold; font-size: 1.2rem; }
        
        .card { 
            background: rgba(255, 255, 255, 0.97); /* Cartas blancas para mejor lectura del texto negro */
            color: #333; 
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .modal-content {
            background: rgba(255, 255, 255, 0.98);
            color: #333;
        }
        h1.h2.text-secondary {
            color: #f8f9fa !important; /* Títulos de página en blanco */
        }
        .border-bottom {
            border-bottom-color: rgba(255,255,255,0.2) !important;
        }
        
        /* Estilos del footer global */
        .footer-system {
            background: rgba(0, 0, 0, 0.6);
            padding: 1.5rem 0;
            text-align: center;
            font-size: 0.95rem;
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: white;
            position: relative;
            z-index: 99;
        }
        .footer-system a {
            color: #6FB1FC;
            text-decoration: none;
            font-weight: 500;
        }
        .footer-system a:hover {
            color: #fff;
            text-decoration: underline;
        }

        /* Estilos de Impresión */
        @media print {
            body {
                background: #fff !important;
                color: #000 !important;
                padding-top: 0 !important;
            }
            .navbar, .sidebar, .footer-system, .btn, .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate {
                display: none !important;
            }
            main.col-md-9.ms-sm-auto.col-lg-10 {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
            }
            .card {
                box-shadow: none !important;
                border: none !important;
            }
            h1.h2.text-secondary {
                color: #000 !important;
                text-align: center;
                margin-top: 20px;
                font-weight: bold;
            }
            .table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            .table th, .table td {
                border: 1px solid #000 !important;
                background-color: transparent !important;
                color: #000 !important;
                padding: 4px !important;
            }
            /* Ocultar elementos visuales como popovers y badges que no aplican */
            .badge {
                border: 1px solid #000;
                color: #000 !important;
                background: transparent !important;
            }
            
            /* Contenedores de cabecera y pie de impresión */
            #print-header, #print-footer {
                display: block !important;
            }
            
            /* Forzar tabla anual a verse bien en impresión apaisada */
            @page {
                size: landscape;
                margin: 10mm;
            }
        }
        
        /* Ocultar elementos de impresión en pantalla */
        #print-header, #print-footer {
            display: none;
        }
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

    <div class="container-fluid main-wrapper">
        <div id="print-header" class="text-center mb-4">
            <img src="sintek_logo.png" alt="Sintek Logo" style="max-width: 150px; margin-bottom: 10px;">
            <h2 style="margin:0; font-weight:bold;">Sintek Gestión</h2>
            <hr style="border: 1px solid #000; margin-top: 10px; margin-bottom: 15px;">
        </div>
        
        <div class="row row-wrapper">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky sidebar-sticky">
                    <ul class="nav flex-column px-2">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index">
                                <i class="fa-solid fa-home me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profesores">
                                <i class="fa-solid fa-chalkboard-user me-2"></i> Docentes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="asistencias">
                                <i class="fa-solid fa-calendar-check me-2"></i> Planilla Docentes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="auxiliares">
                                <i class="fa-solid fa-users-gear me-2"></i> Auxiliares
                            </a>
                        </li>
                        <li class="nav-item ms-3">
                            <a class="nav-link" href="vacaciones_criterios" style="font-size: 0.9em;">
                                <i class="fa-solid fa-umbrella-beach text-warning me-2"></i> Criterios Vacaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="asistencias_auxiliares">
                                <i class="fa-solid fa-calendar-check text-warning me-2"></i> Planilla Auxiliares
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="articulos">
                                <i class="fa-solid fa-file-signature me-2"></i> Artículos
                            </a>
                        </li>
                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <li class="nav-item border-top mt-3 pt-3 border-secondary">
                            <a class="nav-link text-info" href="usuarios">
                                <i class="fa-solid fa-user-shield me-2"></i> Gestión de Usuarios
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
