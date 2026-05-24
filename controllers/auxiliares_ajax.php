<?php
require_once '../config/security.php';
require_once '../config/database.php';
require_login();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        // DataTables Server-Side Processing
        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 10;
        $searchValue = $_POST['search']['value'] ?? '';

        // Query Base
        $query = "SELECT id, nombre, apellido, dni, cuil, legajo FROM auxiliares WHERE estado = 1";
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

        $data = [];
        foreach ($records as $row) {
            $encId = encrypt_id($row['id']);
            $btnEdit = "<button class='btn btn-sm btn-outline-info rounded-circle me-1' onclick='editarAuxiliar(\"{$encId}\")' title='Editar'><i class='fa-solid fa-pen'></i></button>";
            $btnDelete = "<button class='btn btn-sm btn-outline-danger rounded-circle' onclick='eliminarAuxiliar(\"{$encId}\")' title='Eliminar'><i class='fa-solid fa-trash'></i></button>";

            $data[] = [
                $row['id'],
                htmlspecialchars($row['apellido']),
                htmlspecialchars($row['nombre']),
                htmlspecialchars($row['dni']),
                htmlspecialchars($row['cuil'] ?? ''),
                htmlspecialchars($row['legajo'] ?? ''),
                "<div class='text-center'>{$btnEdit}{$btnDelete}</div>"
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
                $stmt = $pdo->prepare("UPDATE auxiliares SET nombre = ?, apellido = ?, dni = ?, cuil = ?, direccion = ?, telefono = ?, mail = ?, titulo = ?, legajo = ? WHERE id = ?");
                $stmt->execute([$nombre, $apellido, $dni, $cuil, $direccion, $telefono, $mail, $titulo, $legajo, $id]);
                echo json_encode(['status' => 'success', 'msg' => 'Auxiliar actualizado correctamente.']);
            } else {
                // Nuevo
                $stmt = $pdo->prepare("INSERT INTO auxiliares (nombre, apellido, dni, cuil, direccion, telefono, mail, titulo, legajo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $apellido, $dni, $cuil, $direccion, $telefono, $mail, $titulo, $legajo]);
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
            $stmt = $pdo->prepare("SELECT id, nombre, apellido, dni, cuil, direccion, telefono, mail, titulo, legajo FROM auxiliares WHERE id = ?");
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

    default:
        echo json_encode(['status' => 'error', 'msg' => 'Acción no válida.']);
        break;
}
?>
