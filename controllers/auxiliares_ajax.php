<?php
require_once '../config/security.php';
require_once '../config/database.php';
require_login();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Seguridad RBAC: Si es usuario de solo lectura, bloquear acciones de escritura
$readonly_actions = ['listar', 'obtener', 'historial_vacaciones', 'obtener_vacacion'];
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario' && !in_array($action, $readonly_actions)) {
    echo json_encode(['status' => 'error', 'msg' => 'Acceso denegado. Permisos de solo lectura.']);
    exit;
}

switch ($action) {
    case 'listar':
        // DataTables Server-Side Processing
        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 10;
        $searchValue = $_POST['search']['value'] ?? '';

        // Query Base
        $query = "SELECT id, nombre, apellido, dni, cuil, legajo, fecha_ingreso FROM auxiliares WHERE estado = 1";
        $params = [];

        // Filtro de búsqueda
        if (!empty($searchValue)) {
            $query .= " AND (nombre LIKE ? OR apellido LIKE ? OR dni LIKE ? OR cuil LIKE ? OR legajo LIKE ?)";
            $searchWildcard = "%$searchValue%";
            $params = [$searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard];
        }

        // Count Total (Filtered)
        $stmtTotal = $pdo->prepare($query);
        $stmtTotal->execute($params);
        $totalRecords = $stmtTotal->rowCount();

        // Ordenamiento
        $query .= " ORDER BY apellido ASC, nombre ASC";

        // Paginación
        if ($length != -1) {
            $query .= " LIMIT " . (int)$start . ", " . (int)$length;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $records = $stmt->fetchAll();

        $stmtCriterios = $pdo->query("SELECT anios_min, anios_max, dias FROM vacaciones_criterios");
        $criterios = $stmtCriterios->fetchAll();

        // Cargar el ID del articulo de Vacaciones
        $stmtArt = $pdo->prepare("SELECT id FROM articulos WHERE codigo = 'VAC' OR descripcion LIKE '%Vacaciones%' LIMIT 1");
        $stmtArt->execute();
        $artVacaciones = $stmtArt->fetchColumn();

        $isUsuario = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario');
        $data = [];
        foreach ($records as $row) {
            $encId = encrypt_id($row['id']);
            
            // Botones de acción
            $btnInfo = "<button class='btn btn-sm btn-outline-secondary rounded-circle me-1' onclick='verInfoAuxiliar(\"{$encId}\")' title='Ver Información'><i class='fa-solid fa-eye'></i></button>";
            $btnHistorial = "<button class='btn btn-sm btn-outline-dark rounded-circle me-1' onclick='verHistorialVacaciones(\"{$encId}\")' title='Historial de Vacaciones'><i class='fa-solid fa-clock-rotate-left'></i></button>";
            $btnVac = $isUsuario ? "" : "<button class='btn btn-sm btn-outline-warning rounded-circle me-1' onclick='cargarVacacionModal(\"{$encId}\")' title='Cargar Vacaciones'><i class='fa-solid fa-plane'></i></button>";
            $btnEdit = $isUsuario ? "" : "<button class='btn btn-sm btn-outline-info rounded-circle me-1' onclick='editarAuxiliar(\"{$encId}\")' title='Editar'><i class='fa-solid fa-pen'></i></button>";
            $btnDelete = $isUsuario ? "" : "<button class='btn btn-sm btn-outline-danger rounded-circle' onclick='eliminarAuxiliar(\"{$encId}\")' title='Eliminar'><i class='fa-solid fa-trash'></i></button>";

            $fechaIngresoStr = '-';
            $diasVacaciones = '-';
            $diasTomadosVisual = '-';
            $observacionesVisual = '-';
            
            if (!empty($row['fecha_ingreso'])) {
                $fechaIngresoStr = date('d/m/Y', strtotime($row['fecha_ingreso']));
                
                $fecha1 = new DateTime($row['fecha_ingreso']);
                $fecha2 = new DateTime();
                $diff = $fecha1->diff($fecha2);
                $anios = $diff->y;
                
                $diasTotales = 0;
                foreach ($criterios as $c) {
                    if ($anios >= $c['anios_min'] && $anios <= $c['anios_max']) {
                        $diasTotales = $c['dias'];
                        break;
                    }
                }
                
                if ($diasTotales > 0) {
                    $diasTomados = 0;
                    $obsConcat = '';
                    
                    // Buscar en la nueva tabla vacaciones_tomadas
                    $stmtTomados = $pdo->prepare("SELECT SUM(dias) as total_dias, GROUP_CONCAT(observaciones SEPARATOR ' | ') as obs FROM vacaciones_tomadas WHERE auxiliar_id = ? AND anio = YEAR(CURDATE())");
                    $stmtTomados->execute([$row['id']]);
                    $resTomados = $stmtTomados->fetch();
                    
                    if ($resTomados && $resTomados['total_dias']) {
                        $diasTomados = (int)$resTomados['total_dias'];
                        $obsConcat = htmlspecialchars($resTomados['obs']);
                    }
                    
                    $diasRestantes = $diasTotales - $diasTomados;
                    $badgeClass = $diasRestantes > 0 ? 'bg-success' : 'bg-danger';
                    $diasVacaciones = "<span class='badge {$badgeClass} rounded-pill px-3' title='Totales: {$diasTotales}'>{$diasRestantes} rest.</span>";
                    
                    if ($diasTomados > 0) {
                        $diasTomadosVisual = "<span class='badge bg-secondary rounded-pill px-2'>{$diasTomados} días</span>";
                        $observacionesVisual = "<span class='small text-muted text-truncate d-inline-block' style='max-width: 150px;' title='{$obsConcat}'>{$obsConcat}</span>";
                    }
                }
            }

            $data[] = [
                $row['id'],
                htmlspecialchars($row['apellido']),
                htmlspecialchars($row['nombre']),
                htmlspecialchars($row['dni']),
                htmlspecialchars($row['cuil'] ?? ''),
                htmlspecialchars($row['legajo'] ?? ''),
                $fechaIngresoStr,
                $diasVacaciones,
                $diasTomadosVisual,
                $observacionesVisual,
                "<div class='text-center text-nowrap'>{$btnInfo}{$btnHistorial}{$btnVac}{$btnEdit}{$btnDelete}</div>"
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data
        ]);
        break;

    case 'guardar':
        $id = isset($_POST['id']) ? decrypt_id($_POST['id']) : null;
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $dni = trim($_POST['dni'] ?? '');
        $cuil = trim($_POST['cuil'] ?? '') ?: null;
        $fecha_ingreso = trim($_POST['fecha_ingreso'] ?? '') ?: null;
        $direccion = trim($_POST['direccion'] ?? '') ?: null;
        $telefono = trim($_POST['telefono'] ?? '') ?: null;
        $mail = trim($_POST['mail'] ?? '') ?: null;
        $titulo = trim($_POST['titulo'] ?? '') ?: null;
        $legajo = trim($_POST['legajo'] ?? '') ?: null;

        if (empty($nombre) || empty($apellido) || empty($dni)) {
            echo json_encode(['status' => 'error', 'msg' => 'Nombre, Apellido y DNI son obligatorios.']);
            exit;
        }

        try {
            if ($id) {
                // Editar
                $stmt = $pdo->prepare("UPDATE auxiliares SET nombre = ?, apellido = ?, dni = ?, cuil = ?, fecha_ingreso = ?, direccion = ?, telefono = ?, mail = ?, titulo = ?, legajo = ? WHERE id = ?");
                $stmt->execute([$nombre, $apellido, $dni, $cuil, $fecha_ingreso, $direccion, $telefono, $mail, $titulo, $legajo, $id]);
                echo json_encode(['status' => 'success', 'msg' => 'Auxiliar actualizado correctamente.']);
            } else {
                // Nuevo
                $stmt = $pdo->prepare("INSERT INTO auxiliares (nombre, apellido, dni, cuil, fecha_ingreso, direccion, telefono, mail, titulo, legajo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $apellido, $dni, $cuil, $fecha_ingreso, $direccion, $telefono, $mail, $titulo, $legajo]);
                echo json_encode(['status' => 'success', 'msg' => 'Auxiliar registrado correctamente.']);
            }
        } catch (PDOException $e) {
            // Errores de unicidad (DNI, CUIL, Legajo)
            if ($e->getCode() == 23000) {
                $errorMsg = $e->getMessage();
                if (strpos($errorMsg, 'idx_cuil') !== false) {
                    echo json_encode(['status' => 'error', 'msg' => 'El CUIL ya se encuentra registrado para otro auxiliar.']);
                } elseif (strpos($errorMsg, 'idx_legajo') !== false) {
                    echo json_encode(['status' => 'error', 'msg' => 'El Legajo ya se encuentra registrado para otro auxiliar.']);
                } elseif (strpos($errorMsg, 'dni') !== false) {
                    echo json_encode(['status' => 'error', 'msg' => 'El DNI ya se encuentra registrado.']);
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Ya existe un registro con esos datos (campo duplicado).']);
                }
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Error de base de datos.']);
            }
        }
        break;

    case 'obtener':
        $id = decrypt_id($_POST['id'] ?? '');
        if ($id) {
            $stmt = $pdo->prepare("SELECT id, nombre, apellido, dni, cuil, fecha_ingreso, direccion, telefono, mail, titulo, legajo FROM auxiliares WHERE id = ?");
            $stmt->execute([$id]);
            $auxiliar = $stmt->fetch();
            if ($auxiliar) {
                $auxiliar['enc_id'] = encrypt_id($auxiliar['id']);
                echo json_encode(['status' => 'success', 'data' => $auxiliar]);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Auxiliar no encontrado.']);
            }
        }
        break;

    case 'eliminar':
        $id = decrypt_id($_POST['id'] ?? '');
        if ($id) {
            // Borrado lógico
            $stmt = $pdo->prepare("UPDATE auxiliares SET estado = 0 WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo json_encode(['status' => 'success', 'msg' => 'Auxiliar eliminado correctamente.']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Error al eliminar.']);
            }
        }
        break;

    case 'cargar_vacacion':
        $aux_id = decrypt_id($_POST['auxiliar_id'] ?? '');
        $dias = (int)($_POST['dias'] ?? 0);
        $observaciones = trim($_POST['observaciones'] ?? '');
        
        if ($aux_id && $dias > 0 && !empty($observaciones)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO vacaciones_tomadas (auxiliar_id, dias, observaciones, anio) VALUES (?, ?, ?, YEAR(CURDATE()))");
                if ($stmt->execute([$aux_id, $dias, $observaciones])) {
                    echo json_encode(['status' => 'success', 'msg' => 'Vacaciones registradas correctamente.']);
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Error al guardar en base de datos.']);
                }
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'msg' => 'Error de base de datos.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Datos inválidos.']);
        }
        break;

    case 'editar_vacacion':
        $reg_id = (int)($_POST['registro_id'] ?? 0);
        $dias = (int)($_POST['dias'] ?? 0);
        $observaciones = trim($_POST['observaciones'] ?? '');
        
        if ($reg_id > 0 && $dias > 0 && !empty($observaciones)) {
            try {
                $stmt = $pdo->prepare("UPDATE vacaciones_tomadas SET dias = ?, observaciones = ? WHERE id = ?");
                if ($stmt->execute([$dias, $observaciones, $reg_id])) {
                    echo json_encode(['status' => 'success', 'msg' => 'Vacaciones actualizadas correctamente.']);
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Error al actualizar.']);
                }
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'msg' => 'Error de base de datos.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Datos inválidos.']);
        }
        break;

    case 'obtener_vacacion':
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("SELECT * FROM vacaciones_tomadas WHERE id = ?");
            $stmt->execute([$id]);
            $vac = $stmt->fetch();
            if ($vac) {
                $vac['auxiliar_id'] = encrypt_id($vac['auxiliar_id']);
                echo json_encode(['status' => 'success', 'data' => $vac]);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Registro no encontrado.']);
            }
        }
        break;

    case 'eliminar_vacacion':
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM vacaciones_tomadas WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo json_encode(['status' => 'success', 'msg' => 'Registro eliminado.']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Error al eliminar.']);
            }
        }
        break;

    case 'historial_vacaciones':
        $aux_id = decrypt_id($_POST['id'] ?? '');
        if ($aux_id) {
            $stmt = $pdo->prepare("SELECT id, fecha_registro, dias, observaciones FROM vacaciones_tomadas WHERE auxiliar_id = ? AND anio = YEAR(CURDATE()) ORDER BY fecha_registro DESC");
            $stmt->execute([$aux_id]);
            $registros = $stmt->fetchAll();
            
            $html = '';
            if (count($registros) > 0) {
                foreach ($registros as $r) {
                    $fechaStr = date('d/m/Y H:i', strtotime($r['fecha_registro']));
                    $html .= "<tr>";
                    $html .= "<td>{$fechaStr}</td>";
                    $html .= "<td><span class='badge bg-secondary rounded-pill px-2'>{$r['dias']} días</span></td>";
                    $html .= "<td><span class='small text-muted'>".htmlspecialchars($r['observaciones'])."</span></td>";
                    $html .= "<td class='text-center text-nowrap'>";
                    $html .= "<button class='btn btn-sm btn-outline-info rounded-circle me-1' onclick='editarVacacion({$r['id']})' title='Editar'><i class='fa-solid fa-pen'></i></button>";
                    $html .= "<button class='btn btn-sm btn-outline-danger rounded-circle' onclick='eliminarVacacion({$r['id']})' title='Eliminar'><i class='fa-solid fa-trash'></i></button>";
                    $html .= "</td>";
                    $html .= "</tr>";
                }
            } else {
                $html = "<tr><td colspan='4' class='text-center text-muted'>No hay vacaciones cargadas en este año.</td></tr>";
            }
            
            echo json_encode(['status' => 'success', 'html' => $html]);
        } else {
            echo json_encode(['status' => 'error', 'html' => '<tr><td colspan="4" class="text-center text-danger">Auxiliar no válido.</td></tr>']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'msg' => 'Acción no válida.']);
        break;
}
?>
