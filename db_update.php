<?php
require_once 'config/database.php';

try {
    // Agregar campo sector a articulos
    $pdo->exec("ALTER TABLE articulos ADD COLUMN sector ENUM('docente', 'auxiliar', 'ambos') NOT NULL DEFAULT 'ambos' AFTER descripcion");
} catch (PDOException $e) {
    echo "Error agregando sector: " . $e->getMessage() . "\n";
}

try {
    // Crear tabla auxiliares
    $pdo->exec("CREATE TABLE IF NOT EXISTS `auxiliares` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nombre` varchar(100) NOT NULL,
      `apellido` varchar(100) NOT NULL,
      `dni` varchar(20) NOT NULL,
      `cuil` varchar(15) NULL,
      `direccion` varchar(255) NULL,
      `telefono` varchar(30) NULL,
      `mail` varchar(150) NULL,
      `titulo` varchar(150) NULL,
      `legajo` varchar(30) NULL,
      `estado` tinyint(1) NOT NULL DEFAULT 1,
      PRIMARY KEY (`id`),
      UNIQUE KEY `dni` (`dni`),
      UNIQUE KEY `idx_cuil` (`cuil`),
      UNIQUE KEY `idx_legajo` (`legajo`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch (PDOException $e) {
    echo "Error creando auxiliares: " . $e->getMessage() . "\n";
}

try {
    // Crear tabla asistencias_auxiliares
    $pdo->exec("CREATE TABLE IF NOT EXISTS `asistencias_auxiliares` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `auxiliar_id` int(11) NOT NULL,
      `fecha` date NOT NULL,
      `articulo_id` int(11) NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `idx_auxiliar_fecha` (`auxiliar_id`, `fecha`),
      KEY `fk_asistencia_aux_articulo` (`articulo_id`),
      CONSTRAINT `fk_asistencia_auxiliar` FOREIGN KEY (`auxiliar_id`) REFERENCES `auxiliares` (`id`) ON DELETE CASCADE,
      CONSTRAINT `fk_asistencia_aux_articulo` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch (PDOException $e) {
    echo "Error creando asistencias_auxiliares: " . $e->getMessage() . "\n";
}

echo "Database updated successfully.\n";
