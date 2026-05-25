<?php
require_once '../config/database.php';
require_once '../config/security.php';
session_start();
require_login();

require_once '../fpdf/fpdf.php';

$curso_id = $_GET['curso_id'] ?? '';
if (empty($curso_id)) {
    die("ID de curso no proporcionado.");
}

// Obtener datos del curso
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE idcurso = ?");
$stmt->execute([$curso_id]);
$curso = $stmt->fetch();
if (!$curso) {
    die("Curso no encontrado.");
}

$turno = $curso['turno'];
$nombreCurso = $curso['curso'];

// Obtener los horarios armados en la grilla para este curso
$stmtH = $pdo->prepare("
    SELECT h.dia_semana, h.hora_inicio, h.hora_fin, 
           m.descripcion as materia, p.nombre, p.apellido
    FROM horarios h
    INNER JOIN profesor_materia pm ON h.asignacion_id = pm.id
    INNER JOIN materias m ON pm.materia_id = m.id
    INNER JOIN profesores p ON pm.profesor_id = p.id
    WHERE m.curso = ?
");
$stmtH->execute([$curso_id]);
$registros = $stmtH->fetchAll();

// Organizar datos en un array multidimensional: $horarios[hora_inicio][dia_semana]
$horariosMatrix = [];
foreach ($registros as $r) {
    // Formatear hora inicio a HH:MM para coincidir con las constantes
    $hora_inicio = substr($r['hora_inicio'], 0, 5); 
    $horariosMatrix[$hora_inicio][$r['dia_semana']] = [
        'materia' => $r['materia'],
        'profesor' => $r['apellido'] . ', ' . $r['nombre']
    ];
}

// Definir grilla según turno
if ($turno == 'Mañana') {
    $grilla = [
        ['inicio' => '08:00', 'fin' => '08:40', 'tipo' => 'clase', 'label' => '1° Módulo'],
        ['inicio' => '08:40', 'fin' => '09:20', 'tipo' => 'clase', 'label' => '2° Módulo'],
        ['inicio' => '09:20', 'fin' => '09:30', 'tipo' => 'recreo', 'label' => 'Recreo'],
        ['inicio' => '09:30', 'fin' => '10:10', 'tipo' => 'clase', 'label' => '3° Módulo'],
        ['inicio' => '10:10', 'fin' => '10:50', 'tipo' => 'clase', 'label' => '4° Módulo'],
        ['inicio' => '10:50', 'fin' => '11:00', 'tipo' => 'recreo', 'label' => 'Recreo'],
        ['inicio' => '11:00', 'fin' => '11:40', 'tipo' => 'clase', 'label' => '5° Módulo'],
        ['inicio' => '11:40', 'fin' => '12:20', 'tipo' => 'clase', 'label' => '6° Módulo'],
        ['inicio' => '12:20', 'fin' => '12:30', 'tipo' => 'recreo', 'label' => 'Recreo'],
        ['inicio' => '12:30', 'fin' => '13:10', 'tipo' => 'clase', 'label' => '7° Módulo']
    ];
} else {
    $grilla = [
        ['inicio' => '13:30', 'fin' => '14:10', 'tipo' => 'clase', 'label' => '1° Módulo'],
        ['inicio' => '14:10', 'fin' => '14:50', 'tipo' => 'clase', 'label' => '2° Módulo'],
        ['inicio' => '14:50', 'fin' => '15:00', 'tipo' => 'recreo', 'label' => 'Recreo'],
        ['inicio' => '15:00', 'fin' => '15:40', 'tipo' => 'clase', 'label' => '3° Módulo'],
        ['inicio' => '15:40', 'fin' => '16:20', 'tipo' => 'clase', 'label' => '4° Módulo'],
        ['inicio' => '16:20', 'fin' => '16:30', 'tipo' => 'recreo', 'label' => 'Recreo'],
        ['inicio' => '16:30', 'fin' => '17:10', 'tipo' => 'clase', 'label' => '5° Módulo'],
        ['inicio' => '17:10', 'fin' => '17:50', 'tipo' => 'clase', 'label' => '6° Módulo'],
        ['inicio' => '17:50', 'fin' => '18:00', 'tipo' => 'recreo', 'label' => 'Recreo'],
        ['inicio' => '18:00', 'fin' => '18:40', 'tipo' => 'clase', 'label' => '7° Módulo']
    ];
}

class PDF extends FPDF {
    public $cursoNombre;
    public $turno;
    
    function Header() {
        // Logo (si existe)
        if(file_exists('../sintek_logo.png')) {
            $this->Image('../sintek_logo.png', 10, 8, 30, 0, 'JPG');
        }
        
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(80);
        $this->Cell(120, 10, mb_convert_encoding('Horario Escolar Semanal', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        
        $this->SetFont('Arial', '', 12);
        $this->Cell(80);
        $this->Cell(120, 8, mb_convert_encoding('Curso: ' . $this->cursoNombre . ' - Turno: ' . $this->turno, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        
        $this->Ln(10);
        
        // Cabecera de la tabla
        $this->SetFont('Arial', 'B', 11);
        $this->SetFillColor(33, 37, 41); // Dark bg
        $this->SetTextColor(255, 255, 255);
        $this->Cell(35, 10, 'Horario', 1, 0, 'C', true);
        $this->Cell(48, 10, 'Lunes', 1, 0, 'C', true);
        $this->Cell(48, 10, 'Martes', 1, 0, 'C', true);
        $this->Cell(48, 10, mb_convert_encoding('Miércoles', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        $this->Cell(48, 10, 'Jueves', 1, 0, 'C', true);
        $this->Cell(48, 10, 'Viernes', 1, 1, 'C', true);
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 10, mb_convert_encoding('Página ', 'ISO-8859-1', 'UTF-8') . $this->PageNo() . ' - Generado por Sintek Gestión', 0, 0, 'C');
    }
}

// Crear PDF en formato Landscape (Horizontal)
$pdf = new PDF('L', 'mm', 'A4');
$pdf->cursoNombre = $nombreCurso;
$pdf->turno = $turno;
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Celdas
$w_hora = 35;
$w_dia = 48;

foreach ($grilla as $bloque) {
    if ($bloque['tipo'] == 'recreo') {
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetFillColor(226, 227, 229); // Gris claro
        $pdf->SetTextColor(100, 100, 100);
        
        $horaTxt = $bloque['inicio'] . ' - ' . $bloque['fin'];
        $pdf->Cell($w_hora, 8, $horaTxt, 1, 0, 'C', true);
        $pdf->Cell($w_dia * 5, 8, 'RECREO', 1, 1, 'C', true);
    } else {
        // Bloque de clase (40 mins). Necesitamos hacer celdas altas (MultiCell o trucos)
        // Usaremos celdas grandes para que quepa Materia + Profesor
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(248, 249, 250);
        $pdf->SetTextColor(0, 0, 0);
        
        $horaY = $pdf->GetY();
        $horaX = $pdf->GetX();
        
        $horaTxt = $bloque['inicio'] . "\n-\n" . $bloque['fin'];
        
        // Vamos a guardar la posición actual X, Y
        $startY = $pdf->GetY();
        $cellHeight = 16;
        
        // Celda Horario
        $pdf->SetXY($horaX, $startY);
        $pdf->MultiCell($w_hora, 8, $bloque['inicio'] . " - " . $bloque['fin'] . "\n" . mb_convert_encoding($bloque['label'], 'ISO-8859-1', 'UTF-8'), 1, 'C', true);
        
        // Celdas de los días
        for ($dia = 1; $dia <= 5; $dia++) {
            $currentX = $horaX + $w_hora + (($dia - 1) * $w_dia);
            $pdf->SetXY($currentX, $startY);
            
            if (isset($horariosMatrix[$bloque['inicio']][$dia])) {
                $mat = mb_convert_encoding($horariosMatrix[$bloque['inicio']][$dia]['materia'], 'ISO-8859-1', 'UTF-8');
                $prof = mb_convert_encoding($horariosMatrix[$bloque['inicio']][$dia]['profesor'], 'ISO-8859-1', 'UTF-8');
                
                // Truncar para que quepa bien
                if(strlen($mat) > 23) $mat = substr($mat, 0, 20).'...';
                if(strlen($prof) > 25) $prof = substr($prof, 0, 22).'...';
                
                $pdf->SetFillColor(230, 242, 255); // Un azul super clarito para celdas ocupadas
                // Dibujar MultiCell
                $pdf->MultiCell($w_dia, 8, $mat . "\n" . $prof, 1, 'C', true);
            } else {
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetTextColor(150, 150, 150);
                $pdf->MultiCell($w_dia, 16, 'Libre', 1, 'C', true);
                $pdf->SetTextColor(0, 0, 0);
            }
        }
        
        // Bajar al final de la fila
        $pdf->SetXY(10, $startY + $cellHeight);
    }
}

$pdf->Output('I', 'Horario_' . $nombreCurso . '.pdf');
?>
