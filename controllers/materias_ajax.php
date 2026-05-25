<?php
require_once '../config/database.php';
require_once '../config/security.php';
session_start();
require_login();

$isUsuario = isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario';
$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'listar':
        $stmt = $pdo->query("
            SELECT m.*, c.curso as nombre_curso, c.turno 
            FROM materias m 
            LEFT JOIN cursos c ON m.curso = c.idcurso 
            ORDER BY m.id ASC
        ");
        $materias = $stmt->fetchAll();
        
        $data = [];
        foreach ($materias as $m) {
            $id = $m['id'];
            $encId = encrypt_id($id);
            $estado = $m['estatus'];
            
            if ($m['nombre_curso']) {
                $m['nombre_curso'] = $m['nombre_curso'] . ($m['turno'] ? " ({$m['turno']})" : "");
            } else {
                $m['nombre_curso'] = 'Sin Curso';
            }
            
            $acciones = "";
            if (!$isUsuario) {
                $acciones .= "<button class='btn btn-sm btn-outline-primary rounded-circle me-1' onclick='abrirModalMateria(\"{$encId}\")' title='Editar'><i class='fa-solid fa-pen'></i></button>";
                
                if ($estado == 1) {
                    $acciones .= "<button class='btn btn-sm btn-outline-danger rounded-circle' onclick='alternarEstadoMateria(\"{$encId}\", 1)' title='Desactivar'><i class='fa-solid fa-ban'></i></button>";
                } else {
                    $acciones .= "<button class='btn btn-sm btn-outline-success rounded-circle' onclick='alternarEstadoMateria(\"{$encId}\", 0)' title='Activar'><i class='fa-solid fa-check'></i></button>";
                }
            } else {
                $acciones = "<span class='text-muted'><i class='fa-solid fa-eye'></i></span>";
            }
            
            $m['acciones'] = $acciones;
            $data[] = $m;
        }
        echo json_encode(['data' => $data]);
        break;

    case 'obtener_cursos':
        $stmt = $pdo->query("SELECT idcurso, curso, turno FROM cursos WHERE estatus = 1 ORDER BY curso ASC");
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    case 'guardar':
        if ($isUsuario) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permisos.']);
            exit;
        }
        
        $id_enc = $_POST['materia_id'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $curso = $_POST['curso'] ?? null;
        $es_especial = isset($_POST['es_especial']) ? 1 : 0;
        
        try {
            if (empty($id_enc)) {
                // Nuevo
                $stmt = $pdo->prepare("INSERT INTO materias (descripcion, curso, iddepartamento, es_especial, estatus) VALUES (?, ?, 0, ?, 1)");
                $stmt->execute([$descripcion, $curso, $es_especial]);
            } else {
                // Editar
                $id = decrypt_id($id_enc);
                $stmt = $pdo->prepare("UPDATE materias SET descripcion = ?, curso = ?, es_especial = ? WHERE id = ?");
                $stmt->execute([$descripcion, $curso, $es_especial, $id]);
            }
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'obtener':
        $id = decrypt_id($_GET['id'] ?? '');
        $stmt = $pdo->prepare("SELECT * FROM materias WHERE id = ?");
        $stmt->execute([$id]);
        $materia = $stmt->fetch();
        if ($materia) {
            $materia['id'] = encrypt_id($materia['id']);
            echo json_encode(['status' => 'success', 'data' => $materia]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Materia no encontrada']);
        }
        break;

    case 'estado':
        if ($isUsuario) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permisos.']);
            exit;
        }
        
        $id = decrypt_id($_POST['id'] ?? '');
        $estado = $_POST['estado'] ?? 1;
        
        $stmt = $pdo->prepare("UPDATE materias SET estatus = ? WHERE id = ?");
        $stmt->execute([$estado, $id]);
        
        echo json_encode(['status' => 'success']);
        break;
}
