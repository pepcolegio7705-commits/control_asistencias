<?php
require_once '../config/security.php';
require_once '../config/database.php';
require_login();

// Seguridad estricta: Solo admin
if ($_SESSION['rol'] !== 'admin') {
    echo json_encode(['status' => 'error', 'msg' => 'Acceso denegado. Permisos insuficientes.']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 10;
        $searchValue = $_POST['search']['value'] ?? '';

        $query = "SELECT id, username, rol, estado FROM usuarios WHERE 1=1";
        $params = [];

        if (!empty($searchValue)) {
            $query .= " AND (username LIKE ? OR rol LIKE ?)";
            $searchWildcard = "%$searchValue%";
            $params = [$searchWildcard, $searchWildcard];
        }

        $stmtTotal = $pdo->prepare($query);
        $stmtTotal->execute($params);
        $totalRecords = $stmtTotal->rowCount();

        $query .= " ORDER BY id ASC";

        if ($length != -1) {
            $query .= " LIMIT " . (int)$start . ", " . (int)$length;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $records = $stmt->fetchAll();

        $data = [];
        foreach ($records as $row) {
            $encId = encrypt_id($row['id']);
            $btnEdit = "<button class='btn btn-sm btn-outline-info rounded-circle me-1' onclick='editarUsuario(\"{$encId}\")' title='Editar'><i class='fa-solid fa-pen'></i></button>";
            
            // No permitir que el admin se borre a sí mismo accidentalmente (opcional, pero buena práctica)
            if ($row['id'] == $_SESSION['user_id']) {
                $btnDelete = "<button class='btn btn-sm btn-outline-secondary rounded-circle' disabled title='No puedes eliminar tu propio usuario'><i class='fa-solid fa-trash'></i></button>";
            } else {
                $btnDelete = "<button class='btn btn-sm btn-outline-danger rounded-circle' onclick='eliminarUsuario(\"{$encId}\")' title='Eliminar'><i class='fa-solid fa-trash'></i></button>";
            }

            $badgeRol = $row['rol'] === 'admin' ? "<span class='badge bg-dark'>Admin</span>" : 
                        ($row['rol'] === 'Administrativo' ? "<span class='badge bg-primary'>Administrativo</span>" : "<span class='badge bg-secondary'>Usuario</span>");
                        
            $badgeEstado = $row['estado'] == 1 ? "<span class='badge bg-success'>Activo</span>" : "<span class='badge bg-danger'>Inactivo</span>";

            $data[] = [
                $row['id'],
                htmlspecialchars($row['username']),
                $badgeRol,
                $badgeEstado,
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
        $id = isset($_POST['id']) && $_POST['id'] !== '' ? decrypt_id($_POST['id']) : null;
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $rol = $_POST['rol'] ?? 'usuario';
        $estado = (int)($_POST['estado'] ?? 1);

        if (empty($username)) {
            echo json_encode(['status' => 'error', 'msg' => 'El nombre de usuario es requerido.']);
            exit;
        }

        try {
            if ($id) {
                // Verificar si el usuario editado es el mismo logueado y se está quitando el rol de admin
                if ($id == $_SESSION['user_id'] && $rol !== 'admin') {
                    echo json_encode(['status' => 'error', 'msg' => 'No puedes quitarte el rol de administrador a ti mismo.']);
                    exit;
                }

                // Check username exists for other IDs
                $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE username = ? AND id != ?");
                $stmtCheck->execute([$username, $id]);
                if ($stmtCheck->fetch()) {
                    echo json_encode(['status' => 'error', 'msg' => 'El nombre de usuario ya está en uso.']);
                    exit;
                }

                if (!empty($password)) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE usuarios SET username = ?, password = ?, rol = ?, estado = ? WHERE id = ?");
                    $stmt->execute([$username, $hash, $rol, $estado, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE usuarios SET username = ?, rol = ?, estado = ? WHERE id = ?");
                    $stmt->execute([$username, $rol, $estado, $id]);
                }
                echo json_encode(['status' => 'success', 'msg' => 'Usuario actualizado correctamente.']);
            } else {
                if (empty($password)) {
                    echo json_encode(['status' => 'error', 'msg' => 'La contraseña es requerida para nuevos usuarios.']);
                    exit;
                }
                
                // Check username exists
                $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
                $stmtCheck->execute([$username]);
                if ($stmtCheck->fetch()) {
                    echo json_encode(['status' => 'error', 'msg' => 'El nombre de usuario ya está en uso.']);
                    exit;
                }

                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, rol, estado) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $hash, $rol, $estado]);
                echo json_encode(['status' => 'success', 'msg' => 'Usuario registrado correctamente.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'msg' => 'Error de base de datos.']);
        }
        break;

    case 'obtener':
        $id = decrypt_id($_POST['id'] ?? '');
        if ($id) {
            $stmt = $pdo->prepare("SELECT id, username, rol, estado FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $user['enc_id'] = encrypt_id($user['id']);
                echo json_encode(['status' => 'success', 'data' => $user]);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Usuario no encontrado.']);
            }
        }
        break;

    case 'eliminar':
        $id = decrypt_id($_POST['id'] ?? '');
        if ($id) {
            if ($id == $_SESSION['user_id']) {
                echo json_encode(['status' => 'error', 'msg' => 'No puedes eliminar tu propio usuario activo.']);
                exit;
            }
            // Borrado físico o lógico. Haremos borrado físico en esta tabla o lógico dependiendo.
            // Para usuarios, mejor eliminarlo físicamente o desactivarlo.
            // Según la BDD actual, tiene estado. Haremos borrado físico.
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo json_encode(['status' => 'success', 'msg' => 'Usuario eliminado correctamente.']);
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
