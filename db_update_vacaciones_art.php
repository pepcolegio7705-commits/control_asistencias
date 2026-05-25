<?php
require_once 'config/database.php';

try {
    // Buscar si existe el articulo de Vacaciones
    $stmt = $pdo->prepare("SELECT id FROM articulos WHERE codigo = 'VAC' OR descripcion LIKE '%Vacaciones%'");
    $stmt->execute();
    $articulo = $stmt->fetch();

    if (!$articulo) {
        $stmtInsert = $pdo->prepare("INSERT INTO articulos (codigo, descripcion, estado, sector) VALUES ('VAC', 'Vacaciones', 1, 'ambos')");
        $stmtInsert->execute();
        echo "Articulo 'Vacaciones' (VAC) insertado correctamente.<br>";
    } else {
        echo "El articulo 'Vacaciones' ya existe en la base de datos.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
?>
