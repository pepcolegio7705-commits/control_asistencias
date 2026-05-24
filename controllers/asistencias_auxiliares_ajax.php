<?php
require_once '../config/security.php';
require_once '../config/database.php';
require_login();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'obtener_planilla':
        $mes = (int)($_POST['mes'] ?? date('n'));
        $anio = (int)($_POST['anio'] ?? date('Y'));
        
        // Días del mes
        $dias_en_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
        
        // Obtener auxiliares activos
        $stmtAux = $pdo->query("SELECT id, nombre, apellido FROM auxiliares WHERE estado = 1 ORDER BY apellido, nombre");
        $auxiliares = $stmtAux->fetchAll();
        
        // Obtener artículos para el desglose del popover
        $stmtArt = $pdo->query("SELECT id, codigo, descripcion FROM articulos WHERE estado = 1 AND sector IN ('auxiliar', 'ambos') ORDER BY codigo");
        $articulos = $stmtArt->fetchAll();
        // Mapear artículos por código para búsqueda rápida
        $articulosMap = [];
        foreach ($articulos as $art) {
            $articulosMap[$art['codigo']] = $art['descripcion'];
        }
        
        // Obtener asistencias (faltas) del mes
        $fecha_inicio = sprintf('%04d-%02d-01', $anio, $mes);
        $fecha_fin = sprintf('%04d-%02d-%02d', $anio, $mes, $dias_en_mes);
        
        $stmtAsis = $pdo->prepare("
            SELECT a.id as asis_id, a.auxiliar_id, a.fecha, a.articulo_id, ar.codigo 
            FROM asistencias_auxiliares a
            JOIN articulos ar ON a.articulo_id = ar.id
            WHERE a.fecha BETWEEN ? AND ?
        ");
        $stmtAsis->execute([$fecha_inicio, $fecha_fin]);
        $asistenciasRaw = $stmtAsis->fetchAll();
        
        // Mapear asistencias por auxiliar y dia
        $mapa_asistencias = [];
        $totales_auxiliar = []; // Para los contadores finales del mes
        
        foreach ($asistenciasRaw as $row) {
            $dia = (int)date('j', strtotime($row['fecha']));
            $mapa_asistencias[$row['auxiliar_id']][$dia] = [
                'asis_id' => encrypt_id($row['asis_id']),
                'codigo' => $row['codigo'],
                'articulo_id' => encrypt_id($row['articulo_id'])
            ];
            
            if(!isset($totales_auxiliar[$row['auxiliar_id']][$row['codigo']])) {
                $totales_auxiliar[$row['auxiliar_id']][$row['codigo']] = 0;
            }
            $totales_auxiliar[$row['auxiliar_id']][$row['codigo']]++;
        }
        
        // Construir HTML de la tabla
        $html = '<table class="table table-bordered table-sm table-hover align-middle" style="font-size: 0.85rem;">';
        $html .= '<thead class="table-dark sticky-top"><tr>';
        $html .= '<th style="min-width: 200px;">Auxiliar</th>';
        
        // Nombres de días
        $nombres_dias = [1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom'];

        // Cabeceras de días
        for ($d = 1; $d <= $dias_en_mes; $d++) {
            $fecha_actual = sprintf('%04d-%02d-%02d', $anio, $mes, $d);
            $dia_semana = date('N', strtotime($fecha_actual)); // 1 (Lunes) - 7 (Domingo)
            $is_weekend = ($dia_semana >= 6);
            $bg = $is_weekend ? 'bg-secondary text-white' : '';
            $nombre_dia = $nombres_dias[$dia_semana];
            $html .= "<th class='text-center {$bg}' style='font-size: 0.75rem;'><span class='fw-normal'>{$nombre_dia}</span><br><span class='fs-6'>{$d}</span></th>";
        }
        
        // Cabecera única de faltas
        $html .= '<th class="table-warning text-center border-start border-dark" style="min-width:70px;">Faltas</th>';
        $html .= '</tr></thead><tbody>';
        
        foreach ($auxiliares as $aux) {
            $aux_enc_id = encrypt_id($aux['id']);
            $html .= "<tr>";
            $html .= "<td class='fw-bold text-nowrap'>" . htmlspecialchars($aux['apellido'] . ', ' . $aux['nombre']) . "</td>";
            
            for ($d = 1; $d <= $dias_en_mes; $d++) {
                $fecha_actual = sprintf('%04d-%02d-%02d', $anio, $mes, $d);
                $dia_semana = date('N', strtotime($fecha_actual));
                $is_weekend = ($dia_semana >= 6);
                $td_class = $is_weekend ? 'bg-light' : '';
                
                if (isset($mapa_asistencias[$aux['id']][$d])) {
                    $asis = $mapa_asistencias[$aux['id']][$d];
                    $html .= "<td class='text-center {$td_class}' style='cursor:pointer;' onclick='abrirModalAsistencia(\"{$aux_enc_id}\", \"{$fecha_actual}\", \"{$asis['asis_id']}\", \"{$asis['articulo_id']}\")'>";
                    $html .= "<span class='badge bg-danger'>{$asis['codigo']}</span>";
                    $html .= "</td>";
                } else {
                    // Presente (celda vacía)
                    $html .= "<td class='text-center {$td_class}' style='cursor:pointer;' onclick='abrirModalAsistencia(\"{$aux_enc_id}\", \"{$fecha_actual}\", \"\", \"\")'></td>";
                }
            }
            
            // Celda única de Faltas con popover de desglose
            $totalFaltas = 0;
            $desgloseItems = [];
            if (isset($totales_auxiliar[$aux['id']])) {
                foreach ($totales_auxiliar[$aux['id']] as $cod => $cant) {
                    $totalFaltas += $cant;
                    $desc = isset($articulosMap[$cod]) ? htmlspecialchars($articulosMap[$cod]) : $cod;
                    $desgloseItems[] = "<div class='d-flex justify-content-between'><span class='me-3'><strong>{$cod}</strong> - {$desc}</span><span class='badge bg-danger'>{$cant}</span></div>";
                }
            }
            
            if ($totalFaltas > 0) {
                $popoverContent = htmlspecialchars(implode('', $desgloseItems), ENT_QUOTES);
                $popoverTitle = htmlspecialchars("<i class='fa-solid fa-chart-pie me-1'></i> Desglose de Faltas", ENT_QUOTES);
                $html .= "<td class='table-warning text-center border-start'>";
                $html .= "<span class='badge bg-danger fs-6 popover-faltas' style='cursor:pointer;' ";
                $html .= "data-bs-toggle='popover' data-bs-html='true' data-bs-trigger='click' ";
                $html .= "data-bs-title='{$popoverTitle}' ";
                $html .= "data-bs-content='{$popoverContent}'>";
                $html .= "{$totalFaltas} <i class='fa-solid fa-magnifying-glass-plus' style='font-size:0.7rem;'></i>";
                $html .= "</span></td>";
            } else {
                $html .= "<td class='table-warning text-center border-start text-muted'>-</td>";
            }
            
            $html .= "</tr>";
        }
        
        $html .= '</tbody></table>';
        echo $html;
        break;

    case 'obtener_anual':
        $anio = (int)($_POST['anio'] ?? date('Y'));
        
        $stmtAux = $pdo->query("SELECT id, nombre, apellido FROM auxiliares WHERE estado = 1 ORDER BY apellido, nombre");
        $auxiliares = $stmtAux->fetchAll();
        
        $stmtArt = $pdo->query("SELECT id, codigo, descripcion FROM articulos WHERE estado = 1 AND sector IN ('auxiliar', 'ambos') ORDER BY codigo");
        $articulos = $stmtArt->fetchAll();
        $articulosMap = [];
        foreach ($articulos as $art) {
            $articulosMap[$art['codigo']] = $art['descripcion'];
        }
        
        $fecha_inicio = "{$anio}-01-01";
        $fecha_fin = "{$anio}-12-31";
        
        $stmtAsis = $pdo->prepare("
            SELECT a.auxiliar_id, ar.codigo, COUNT(a.id) as cantidad
            FROM asistencias_auxiliares a
            JOIN articulos ar ON a.articulo_id = ar.id
            WHERE a.fecha BETWEEN ? AND ?
            GROUP BY a.auxiliar_id, ar.codigo
        ");
        $stmtAsis->execute([$fecha_inicio, $fecha_fin]);
        
        $totales = [];
        while($row = $stmtAsis->fetch()) {
            $totales[$row['auxiliar_id']][$row['codigo']] = $row['cantidad'];
        }
        
        // Meses para desglose mensual
        $meses = [1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun', 
                  7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'];
        
        // Obtener totales por mes por auxiliar
        $stmtMensual = $pdo->prepare("
            SELECT a.auxiliar_id, MONTH(a.fecha) as mes, COUNT(a.id) as cantidad
            FROM asistencias_auxiliares a
            WHERE a.fecha BETWEEN ? AND ?
            GROUP BY a.auxiliar_id, MONTH(a.fecha)
        ");
        $stmtMensual->execute([$fecha_inicio, $fecha_fin]);
        $totalesMensuales = [];
        while($row = $stmtMensual->fetch()) {
            $totalesMensuales[$row['auxiliar_id']][$row['mes']] = $row['cantidad'];
        }
        
        $html = '<table class="table table-bordered table-striped align-middle">';
        $html .= '<thead class="table-dark"><tr>';
        $html .= '<th>Auxiliar</th>';
        foreach ($meses as $num => $nombre) {
            $html .= "<th class='text-center'>{$nombre}</th>";
        }
        $html .= '<th class="table-warning text-center text-dark fw-bold">Total Anual</th>';
        $html .= '</tr></thead><tbody>';
        
        foreach ($auxiliares as $aux) {
            $html .= "<tr>";
            $html .= "<td class='fw-bold'>" . htmlspecialchars($aux['apellido'] . ', ' . $aux['nombre']) . "</td>";
            
            $totalAnual = 0;
            foreach ($meses as $numMes => $nombreMes) {
                $cantMes = $totalesMensuales[$aux['id']][$numMes] ?? 0;
                $totalAnual += $cantMes;
                if ($cantMes > 0) {
                    $html .= "<td class='text-center'><span class='badge bg-danger'>{$cantMes}</span></td>";
                } else {
                    $html .= "<td class='text-center text-muted'>0</td>";
                }
            }
            
            // Columna Total Anual con popover de desglose por artículo
            $desgloseItems = [];
            if (isset($totales[$aux['id']])) {
                foreach ($totales[$aux['id']] as $cod => $cant) {
                    $desc = isset($articulosMap[$cod]) ? htmlspecialchars($articulosMap[$cod]) : $cod;
                    $desgloseItems[] = "<div class='d-flex justify-content-between'><span class='me-3'><strong>{$cod}</strong> - {$desc}</span><span class='badge bg-danger'>{$cant}</span></div>";
                }
            }
            
            if ($totalAnual > 0) {
                $popoverContent = htmlspecialchars(implode('', $desgloseItems), ENT_QUOTES);
                $popoverTitle = htmlspecialchars("<i class='fa-solid fa-chart-pie me-1'></i> Desglose por Artículo", ENT_QUOTES);
                $html .= "<td class='table-warning text-center'>";
                $html .= "<span class='badge bg-danger fs-5 popover-faltas' style='cursor:pointer;' ";
                $html .= "data-bs-toggle='popover' data-bs-html='true' data-bs-trigger='click' ";
                $html .= "data-bs-title='{$popoverTitle}' ";
                $html .= "data-bs-content='{$popoverContent}'>";
                $html .= "{$totalAnual} <i class='fa-solid fa-magnifying-glass-plus' style='font-size:0.7rem;'></i>";
                $html .= "</span></td>";
            } else {
                $html .= "<td class='table-warning text-center text-muted'>0</td>";
            }
            
            $html .= "</tr>";
        }
        $html .= '</tbody></table>';
        echo $html;
        break;

    case 'obtener_articulos':
        $stmt = $pdo->query("SELECT id, codigo, descripcion FROM articulos WHERE estado = 1 AND sector IN ('auxiliar', 'ambos') ORDER BY codigo");
        $data = [];
        while($row = $stmt->fetch()) {
            $data[] = [
                'id' => encrypt_id($row['id']),
                'codigo' => $row['codigo'],
                'descripcion' => $row['descripcion']
            ];
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'guardar':
        $asis_id = isset($_POST['asis_id']) && !empty($_POST['asis_id']) ? decrypt_id($_POST['asis_id']) : null;
        $auxiliar_id = decrypt_id($_POST['auxiliar_id'] ?? '');
        $fecha = $_POST['fecha'] ?? '';
        $articulo_id = decrypt_id($_POST['articulo_id'] ?? '');
        
        if (!$auxiliar_id || empty($fecha) || !$articulo_id) {
            echo json_encode(['status' => 'error', 'msg' => 'Datos inválidos.']);
            exit;
        }

        try {
            if ($asis_id) {
                // Actualizar registro existente
                $stmt = $pdo->prepare("UPDATE asistencias_auxiliares SET articulo_id = ? WHERE id = ?");
                $stmt->execute([$articulo_id, $asis_id]);
                echo json_encode(['status' => 'success', 'msg' => 'Asistencia actualizada.']);
            } else {
                // Prevenir duplicados (idx_auxiliar_fecha)
                $stmt = $pdo->prepare("INSERT INTO asistencias_auxiliares (auxiliar_id, fecha, articulo_id) VALUES (?, ?, ?)");
                $stmt->execute([$auxiliar_id, $fecha, $articulo_id]);
                echo json_encode(['status' => 'success', 'msg' => 'Artículo cargado correctamente.']);
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo json_encode(['status' => 'error', 'msg' => 'Ya existe un artículo cargado para este auxiliar en esta fecha.']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Error de base de datos.']);
            }
        }
        break;

    case 'eliminar':
        $asis_id = decrypt_id($_POST['asis_id'] ?? '');
        if ($asis_id) {
            // El borrado en asistencias sí puede ser físico, ya que si se anula una falta, vuelve a ser "Presente"
            $stmt = $pdo->prepare("DELETE FROM asistencias_auxiliares WHERE id = ?");
            if ($stmt->execute([$asis_id])) {
                echo json_encode(['status' => 'success', 'msg' => 'Artículo eliminado. El auxiliar vuelve a figurar Presente.']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Error al eliminar.']);
            }
        }
        break;
}
?>
