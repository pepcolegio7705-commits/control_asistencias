<?php
require_once '../config/database.php';
require_once '../config/security.php';
session_start();
require_login();

$isUsuario = isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario';
$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'obtener_asignaciones_curso':
        $curso_id = $_GET['curso_id'] ?? '';
        $stmt = $pdo->prepare("
            SELECT pm.id, m.descripcion as materia, p.nombre, p.apellido 
            FROM profesor_materia pm
            INNER JOIN materias m ON pm.materia_id = m.id
            INNER JOIN profesores p ON pm.profesor_id = p.id
            WHERE m.curso = ? AND pm.estado = 1
            ORDER BY m.descripcion ASC
        ");
        $stmt->execute([$curso_id]);
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    case 'obtener_grilla':
        $curso_id = $_GET['curso_id'] ?? '';
        $stmt = $pdo->prepare("
            SELECT h.id as horario_id, h.dia_semana, h.hora_inicio, h.hora_fin, 
                   m.descripcion as materia, p.nombre, p.apellido
            FROM horarios h
            INNER JOIN profesor_materia pm ON h.asignacion_id = pm.id
            INNER JOIN materias m ON pm.materia_id = m.id
            INNER JOIN profesores p ON pm.profesor_id = p.id
            WHERE m.curso = ?
        ");
        $stmt->execute([$curso_id]);
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    case 'guardar_bloque':
        if ($isUsuario) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permisos.']);
            exit;
        }
        
        $curso_id = $_POST['curso_id'] ?? '';
        $asignacion_id = $_POST['asignacion_id'] ?? '';
        $dia = $_POST['dia_semana'] ?? '';
        $inicio = $_POST['hora_inicio'] ?? '';
        $fin = $_POST['hora_fin'] ?? '';
        
        try {
            $pdo->beginTransaction();
            
            // Primero, borrar si ya había algo en ese casillero (día, hora) para este curso
            $stmtDel = $pdo->prepare("
                DELETE h FROM horarios h
                INNER JOIN profesor_materia pm ON h.asignacion_id = pm.id
                INNER JOIN materias m ON pm.materia_id = m.id
                WHERE m.curso = ? AND h.dia_semana = ? AND h.hora_inicio = ?
            ");
            $stmtDel->execute([$curso_id, $dia, $inicio]);
            
            // Insertar el nuevo bloque
            $stmt = $pdo->prepare("INSERT INTO horarios (asignacion_id, dia_semana, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)");
            $stmt->execute([$asignacion_id, $dia, $inicio, $fin]);
            
            $pdo->commit();
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'vaciar_bloque':
        if ($isUsuario) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permisos.']);
            exit;
        }
        
        $curso_id = $_POST['curso_id'] ?? '';
        $dia = $_POST['dia_semana'] ?? '';
        $inicio = $_POST['hora_inicio'] ?? '';
        
        try {
            $stmtDel = $pdo->prepare("
                DELETE h FROM horarios h
                INNER JOIN profesor_materia pm ON h.asignacion_id = pm.id
                INNER JOIN materias m ON pm.materia_id = m.id
                WHERE m.curso = ? AND h.dia_semana = ? AND h.hora_inicio = ?
            ");
            $stmtDel->execute([$curso_id, $dia, $inicio]);
            
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
}
