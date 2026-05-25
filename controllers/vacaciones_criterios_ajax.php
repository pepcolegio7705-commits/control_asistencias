<?php
require_once '../config/security.php';
require_once '../config/database.php';
require_login();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 10;
        $searchValue = $_POST['search']['value'] ?? '';

        $query = "SELECT id, anios_min, anios_max, dias FROM vacaciones_criterios WHERE 1=1";
        $params = [];

        if (!empty($searchValue)) {
            $query .= " AND (dias LIKE ? OR anios_min LIKE ? OR anios_max LIKE ?)";
            $searchWildcard = "%$searchValue%";
            $params = [$searchWildcard, $searchWildcard, $searchWildcard];
        }

        $stmtTotal = $pdo->prepare($query);
        $stmtTotal->execute($params);
        $totalRecords = $stmtTotal->rowCount();

        $query .= " ORDER BY anios_min ASC";

        if ($length != -1) {
            $query .= " LIMIT " . (int)$start . ", " . (int)$length;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $records = $stmt->fetchAll();

        $data = [];
        foreach ($records as $row) {
            $encId = encrypt_id($row['id']);
            $btnEdit = "<button class='btn btn-sm btn-outline-info rounded-circle me-1' onclick='editarCriterio(\"{$encId}\")' title='Editar'><i class='fa-solid fa-pen'></i></button>";
            $btnDelete = "<button class='btn btn-sm btn-outline-danger rounded-circle' onclick='eliminarCriterio(\"{$encId}\")' title='Eliminar'><i class='fa-solid fa-trash'></i></button>";

            $data[] = [
                $row['id'],
                $row['anios_min'],
                $row['anios_max'],
                "<strong>{$row['dias']} días</strong>",
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
        $anios_min = (int)($_POST['anios_min'] ?? 0);
        $anios_max = (int)($_POST['anios_max'] ?? 0);
        $dias = (int)($_POST['dias'] ?? 0);

        if ($anios_min > $anios_max) {
            echo json_encode(['status' => 'error', 'msg' => 'Los años mínimos no pueden ser mayores a los máximos.']);
            exit;
        }

        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE vacaciones_criterios SET anios_min = ?, anios_max = ?, dias = ? WHERE id = ?");
                $stmt->execute([$anios_min, $anios_max, $dias, $id]);
                echo json_encode(['status' => 'success', 'msg' => 'Criterio actualizado correctamente.']);
            } else {
                $stmt = $pdo->prepare("INSERT INTO vacaciones_criterios (anios_min, anios_max, dias) VALUES (?, ?, ?)");
                $stmt->execute([$anios_min, $anios_max, $dias]);
                echo json_encode(['status' => 'success', 'msg' => 'Criterio registrado correctamente.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'msg' => 'Error de base de datos.']);
        }
        break;

    case 'obtener':
        $id = decrypt_id($_POST['id'] ?? '');
        if ($id) {
            $stmt = $pdo->prepare("SELECT id, anios_min, anios_max, dias FROM vacaciones_criterios WHERE id = ?");
            $stmt->execute([$id]);
            $criterio = $stmt->fetch();
            if ($criterio) {
                $criterio['enc_id'] = encrypt_id($criterio['id']);
                echo json_encode(['status' => 'success', 'data' => $criterio]);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Criterio no encontrado.']);
            }
        }
        break;

    case 'eliminar':
        $id = decrypt_id($_POST['id'] ?? '');
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM vacaciones_criterios WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo json_encode(['status' => 'success', 'msg' => 'Criterio eliminado correctamente.']);
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
