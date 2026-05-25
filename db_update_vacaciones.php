<?php
require_once 'config/database.php';

try {
    // 1. Agregar fecha_ingreso a auxiliares
    $pdo->exec("ALTER TABLE auxiliares ADD COLUMN fecha_ingreso DATE NULL DEFAULT NULL AFTER dni");
    echo "Columna fecha_ingreso añadida a auxiliares.<br>";
} catch (PDOException $e) {
    echo "Nota (fecha_ingreso): " . $e->getMessage() . "<br>";
}

try {
    // 2. Crear tabla vacaciones_criterios
    $pdo->exec("CREATE TABLE IF NOT EXISTS `vacaciones_criterios` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `anios_min` int(11) NOT NULL,
      `anios_max` int(11) NOT NULL,
      `dias` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "Tabla vacaciones_criterios creada.<br>";
    
    // 3. Limpiar tabla (por si acaso para este run) e insertar los predeterminados
    $pdo->exec("TRUNCATE TABLE `vacaciones_criterios`");
    
    $criterios = [
        [0, 1, 12],
        [2, 2, 14],
        [3, 3, 16],
        [4, 4, 18],
        [5, 5, 20],
        [6, 10, 25],
        [11, 15, 30],
        [16, 20, 35],
        [21, 25, 40],
        [26, 30, 45]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO vacaciones_criterios (anios_min, anios_max, dias) VALUES (?, ?, ?)");
    foreach ($criterios as $c) {
        $stmt->execute($c);
    }
    echo "Criterios insertados correctamente.<br>";
    
} catch (PDOException $e) {
    echo "Error creando vacaciones_criterios: " . $e->getMessage() . "<br>";
}

echo "Base de datos actualizada para el modulo de vacaciones.";
?>
