<?php
require_once '../config/security.php';
require_once '../config/database.php';
require_login();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Seguridad RBAC: Si es usuario de solo lectura, bloquear acciones de escritura
$readonly_actions = ['listar', 'obtener'];
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario' && !in_array($action, $readonly_actions)) {
    echo json_encode(['status' => 'error', 'msg' => 'Acceso denegado. Permisos de solo lectura.']);
    exit;
}

switch ($action) {
    case 'listar':
        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 10;
        $searchValue = $_POST['search']['value'] ?? '';

        $query = "SELECT id, codigo, descripcion, sector FROM articulos WHERE estado = 1";
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

        $isUsuario = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario');
        $data = [];
        foreach ($records as $row) {
            $encId = encrypt_id($row['id']);
            $btnEdit = $isUsuario ? "" : "<button class='btn btn-sm btn-outline-info rounded-circle me-1' onclick='editarArticulo(\"{$encId}\")' title='Editar'><i class='fa-solid fa-pen'></i></button>";
            $btnDelete = $isUsuario ? "" : "<button class='btn btn-sm btn-outline-danger rounded-circle' onclick='eliminarArticulo(\"{$encId}\")' title='Eliminar'><i class='fa-solid fa-trash'></i></button>";

            $badgeSector = '';
            if ($row['sector'] === 'docente') {
                $badgeSector = "<span class='badge bg-info'>Docentes</span>";
            } elseif ($row['sector'] === 'auxiliar') {
                $badgeSector = "<span class='badge bg-warning text-dark'>Auxiliares</span>";
            } else {
                $badgeSector = "<span class='badge bg-secondary'>Ambos</span>";
            }

            $data[] = [
                $row['id'],
                "<span class='badge bg-dark'>" . htmlspecialchars($row['codigo']) . "</span>",
                htmlspecialchars($row['descripcion']),
                $badgeSector,
                "<div class='text-center'>{$btnEdit}{$btnDelete}</div>"
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords),
            "data" => $data
        ]);
        break;

    case 'obtener_todos':
        // Utilizado para impresión del listado completo agrupado
        $stmt = $pdo->query("SELECT codigo, descripcion, sector FROM articulos WHERE estado = 1 ORDER BY sector, codigo ASC");
        $articulos = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $articulos]);
        break;

    case 'guardar':
        $id = isset($_POST['id']) ? decrypt_id($_POST['id']) : null;
        $codigo = trim($_POST['codigo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $sector = trim($_POST['sector'] ?? 'ambos');

        if (empty($codigo) || empty($descripcion)) {
            echo json_encode(['status' => 'error', 'msg' => 'Todos los campos son obligatorios.']);
            exit;
        }

        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE articulos SET codigo = ?, descripcion = ?, sector = ? WHERE id = ?");
                $stmt->execute([strtoupper($codigo), $descripcion, $sector, $id]);
                echo json_encode(['status' => 'success', 'msg' => 'Artículo actualizado.']);
            } else {
                $stmt = $pdo->prepare("INSERT INTO articulos (codigo, descripcion, sector) VALUES (?, ?, ?)");
                $stmt->execute([strtoupper($codigo), $descripcion, $sector]);
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
            $stmt = $pdo->prepare("SELECT id, codigo, descripcion, sector FROM articulos WHERE id = ?");
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
