<?php
require_once 'config/database.php';

try {
    // 1. Agregar turno a cursos si no existe
    $stmt = $pdo->query("SHOW COLUMNS FROM `cursos` LIKE 'turno'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `cursos` ADD `turno` ENUM('Mañana','Tarde') NULL DEFAULT NULL AFTER `curso`");
    }

    // 2. Agregar es_especial a materias si no existe
    $stmt = $pdo->query("SHOW COLUMNS FROM `materias` LIKE 'es_especial'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `materias` ADD `es_especial` TINYINT(1) NOT NULL DEFAULT 0 AFTER `descripcion`");
    }

    // 3. Crear tabla de asignaciones (Profesor -> Materia)
    $sql_asignaciones = "
        CREATE TABLE IF NOT EXISTS `profesor_materia` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `profesor_id` int(11) NOT NULL,
            `materia_id` int(11) NOT NULL,
            `estado` tinyint(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`),
            KEY `fk_pm_profesor` (`profesor_id`),
            KEY `fk_pm_materia` (`materia_id`),
            CONSTRAINT `fk_pm_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_pm_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_asignaciones);

    // 4. Tabla de horarios de la cuadrícula
    $sql_horarios = "
        CREATE TABLE IF NOT EXISTS `horarios` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `asignacion_id` int(11) NOT NULL,
            `dia_semana` tinyint(1) NOT NULL COMMENT '1=Lunes, 2=Martes, 3=Miercoles, 4=Jueves, 5=Viernes',
            `hora_inicio` time NOT NULL,
            `hora_fin` time NOT NULL,
            PRIMARY KEY (`id`),
            KEY `fk_horario_asignacion` (`asignacion_id`),
            CONSTRAINT `fk_horario_asignacion` FOREIGN KEY (`asignacion_id`) REFERENCES `profesor_materia` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_horarios);

    echo "SUCCESS_UPDATE";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
