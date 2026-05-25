<?php
require_once 'config/database.php';

$output = [];

$stmt1 = $pdo->query("SELECT * FROM cursos LIMIT 5");
$output['cursos_sample'] = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $pdo->query("SELECT * FROM materias LIMIT 5");
$output['materias_sample'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($output, JSON_PRETTY_PRINT);
?>
