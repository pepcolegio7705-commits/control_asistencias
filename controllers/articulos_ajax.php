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

        $query = "SELECT id, codigo, descripcion FROM articulos WHERE estado = 1";
        $params = [];

        if (!empty($searchValue)) {
            $query .= " AND (codigo LIKE ? OR descripcion LIKE ?)";
            $searchWildcard = "%$searchValue%";
            $params = [$searchWildcard, $searchWildcard];
        }

        $stmtTotal = $pdo->prepare($query);
        $stmtTotal->execute($params);
        $totalRecords = $stmtTotal->rowCount();

        $query .= " ORDER BY codigo ASC";

        if ($length != -1) {
            $query .= " LIMIT " . (int)$start . ", " . (int)$length;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $records = $stmt->fetchAll();

        $data = [];
        foreach ($records as $row) {
            $encId = encrypt_id($row['id']);
            $btnEdit = "<button class='btn btn-sm btn-outline-info rounded-circle me-1' onclick='editarArticulo(\"{$encId}\")' title='Editar'><i class='fa-solid fa-pen'></i></button>";
            $btnDelete = "<button class='btn btn-sm btn-outline-danger rounded-circle' onclick='eliminarArticulo(\"{$encId}\")' title='Eliminar'><i class='fa-solid fa-trash'></i></button>";

            $data[] = [
                $row['id'],
                "<span class='badge bg-dark'>" . htmlspecialchars($row['codigo']) . "</span>",
                htmlspecialchars($row['descripcion']),
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
        $codigo = trim($_POST['codigo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (empty($codigo) || empty($descripcion)) {
            echo json_encode(['status' => 'error', 'msg' => 'Todos los campos son obligatorios.']);
            exit;
        }

        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE articulos SET codigo = ?, descripcion = ? WHERE id = ?");
                $stmt->execute([strtoupper($codigo), $descripcion, $id]);
                echo json_encode(['status' => 'success', 'msg' => 'Artículo actualizado.']);
            } else {
                $stmt = $pdo->prepare("INSERT INTO articulos (codigo, descripcion) VALUES (?, ?)");
                $stmt->execute([strtoupper($codigo), $descripcion]);
                echo json_encode(['status' => 'success', 'msg' => 'Artículo registrado.']);
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo json_encode(['status' => 'error', 'msg' => 'El Código ya existe.']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Error de base de datos.']);
            }
        }
        break;

    case 'obtener':
        $id = decrypt_id($_POST['id'] ?? '');
        if ($id) {
            $stmt = $pdo->prepare("SELECT id, codigo, descripcion FROM articulos WHERE id = ?");
            $stmt->execute([$id]);
            $art = $stmt->fetch();
            if ($art) {
                $art['enc_id'] = encrypt_id($art['id']);
                echo json_encode(['status' => 'success', 'data' => $art]);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Artículo no encontrado.']);
            }
        }
        break;

    case 'eliminar':
        $id = decrypt_id($_POST['id'] ?? '');
        if ($id) {
            $stmt = $pdo->prepare("UPDATE articulos SET estado = 0 WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo json_encode(['status' => 'success', 'msg' => 'Artículo eliminado.']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Error al eliminar.']);
            }
        }
        break;
}
?>
