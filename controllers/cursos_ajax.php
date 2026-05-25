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
        $stmt = $pdo->query("SELECT * FROM cursos ORDER BY idcurso ASC");
        $cursos = $stmt->fetchAll();
        
        $data = [];
        foreach ($cursos as $c) {
            $id = $c['idcurso'];
            $encId = encrypt_id($id);
            $estado = $c['estatus'];
            
            $acciones = "";
            if (!$isUsuario) {
                $acciones .= "<button class='btn btn-sm btn-outline-primary rounded-circle me-1' onclick='abrirModalCurso(\"{$encId}\")' title='Editar'><i class='fa-solid fa-pen'></i></button>";
                
                if ($estado == 1) {
                    $acciones .= "<button class='btn btn-sm btn-outline-danger rounded-circle' onclick='alternarEstadoCurso(\"{$encId}\", 1)' title='Desactivar'><i class='fa-solid fa-ban'></i></button>";
                } else {
                    $acciones .= "<button class='btn btn-sm btn-outline-success rounded-circle' onclick='alternarEstadoCurso(\"{$encId}\", 0)' title='Activar'><i class='fa-solid fa-check'></i></button>";
                }
            } else {
                $acciones = "<span class='text-muted'><i class='fa-solid fa-eye'></i></span>";
            }
            
            $c['acciones'] = $acciones;
            $data[] = $c;
        }
        echo json_encode(['data' => $data]);
        break;

    case 'guardar':
        if ($isUsuario) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permisos.']);
            exit;
        }
        
        $id_enc = $_POST['curso_id'] ?? '';
        $nombre = $_POST['nombre_curso'] ?? '';
        $turno = $_POST['turno'] ?? null;
        
        if (empty($turno)) $turno = null;

        try {
            if (empty($id_enc)) {
                // Nuevo
                $stmt = $pdo->prepare("INSERT INTO cursos (curso, turno, estatus) VALUES (?, ?, 1)");
                $stmt->execute([$nombre, $turno]);
            } else {
                // Editar
                $id = decrypt_id($id_enc);
                $stmt = $pdo->prepare("UPDATE cursos SET curso = ?, turno = ? WHERE idcurso = ?");
                $stmt->execute([$nombre, $turno, $id]);
            }
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'obtener':
        $id = decrypt_id($_GET['id'] ?? '');
        $stmt = $pdo->prepare("SELECT * FROM cursos WHERE idcurso = ?");
        $stmt->execute([$id]);
        $curso = $stmt->fetch();
        if ($curso) {
            // Reencriptamos el ID para devolverlo al cliente seguro
            $curso['idcurso'] = encrypt_id($curso['idcurso']);
            echo json_encode(['status' => 'success', 'data' => $curso]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Curso no encontrado']);
        }
        break;

    case 'estado':
        if ($isUsuario) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permisos.']);
            exit;
        }
        
        $id = decrypt_id($_POST['id'] ?? '');
        $estado = $_POST['estado'] ?? 1;
        
        $stmt = $pdo->prepare("UPDATE cursos SET estatus = ? WHERE idcurso = ?");
        $stmt->execute([$estado, $id]);
        
        echo json_encode(['status' => 'success']);
        break;
}
