<?php
require_once '../config/database.php';
require_once '../config/security.php';
session_start();
require_login();

$isUsuario = isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario';
$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'obtener_profesores':
        $stmt = $pdo->query("SELECT id, nombre, apellido, dni FROM profesores WHERE estado = 1 ORDER BY apellido ASC, nombre ASC");
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    case 'obtener_materias_curso':
        $idcurso = $_GET['idcurso'] ?? '';
        $stmt = $pdo->prepare("SELECT id, descripcion FROM materias WHERE curso = ? AND estatus = 1 ORDER BY descripcion ASC");
        $stmt->execute([$idcurso]);
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    case 'listar':
        $profesor_id = $_GET['profesor_id'] ?? '';
        $stmt = $pdo->prepare("
            SELECT pm.id, m.descripcion as materia, m.es_especial, c.curso, c.turno
            FROM profesor_materia pm
            INNER JOIN materias m ON pm.materia_id = m.id
            INNER JOIN cursos c ON m.curso = c.idcurso
            WHERE pm.profesor_id = ? AND pm.estado = 1
            ORDER BY c.curso ASC, m.descripcion ASC
        ");
        $stmt->execute([$profesor_id]);
        $asignaciones = $stmt->fetchAll();
        
        $data = [];
        foreach ($asignaciones as $a) {
            $encId = encrypt_id($a['id']);
            $a['acciones'] = "";
            if (!$isUsuario) {
                $a['acciones'] = "<button class='btn btn-sm btn-outline-danger rounded-circle' onclick='eliminarAsignacion(\"{$encId}\")' title='Eliminar Asignación'><i class='fa-solid fa-trash'></i></button>";
            } else {
                $a['acciones'] = "<span class='text-muted'><i class='fa-solid fa-lock'></i></span>";
            }
            $data[] = $a;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'guardar':
        if ($isUsuario) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permisos.']);
            exit;
        }
        
        $profesor_id = $_POST['profesor_id'] ?? '';
        $materia_id = $_POST['materia_id'] ?? '';
        
        try {
            // Verificar si ya existe
            $stmtCheck = $pdo->prepare("SELECT id FROM profesor_materia WHERE profesor_id = ? AND materia_id = ? AND estado = 1");
            $stmtCheck->execute([$profesor_id, $materia_id]);
            if ($stmtCheck->rowCount() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'El docente ya tiene asignada esta materia.']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO profesor_materia (profesor_id, materia_id, estado) VALUES (?, ?, 1)");
            $stmt->execute([$profesor_id, $materia_id]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'eliminar':
        if ($isUsuario) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permisos.']);
            exit;
        }
        
        $id = decrypt_id($_POST['id'] ?? '');
        try {
            // Borrado lógico o físico? Como hay horarios atados con CASCADE, si lo borramos físicamente, se borran los horarios de la grilla.
            // Es lo correcto: si desasignas a un profe de una materia, se vacían los casilleros en la grilla que estaban atados a esa asignación.
            $stmt = $pdo->prepare("DELETE FROM profesor_materia WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
}
