<?php
require_once 'config/database.php';

try {
    // Drop the tables if they exist to start fresh
    $pdo->exec("DROP TABLE IF EXISTS `horarios`");
    $pdo->exec("DROP TABLE IF EXISTS `profesor_curso_materia`");
    // $pdo->exec("DROP TABLE IF EXISTS `materias`"); // Wait, I shouldn't drop them if they have data. 
    // Actually this is the first time I create them.

    // 1. Tabla cursos
    $sql_cursos = "
        CREATE TABLE IF NOT EXISTS `cursos` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(100) NOT NULL,
            `turno` enum('Mañana','Tarde') NOT NULL,
            `estado` tinyint(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_cursos);

    // 2. Tabla materias
    $sql_materias = "
        CREATE TABLE IF NOT EXISTS `materias` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(150) NOT NULL,
            `es_especial` tinyint(1) NOT NULL DEFAULT 0,
            `estado` tinyint(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_materias);

    // 3. Tabla de asignaciones (Profesor -> Curso -> Materia)
    $sql_asignaciones = "
        CREATE TABLE IF NOT EXISTS `profesor_curso_materia` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `profesor_id` int(11) NOT NULL,
            `curso_id` int(11) NOT NULL,
            `materia_id` int(11) NOT NULL,
            `estado` tinyint(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`),
            KEY `fk_pcm_profesor` (`profesor_id`),
            KEY `fk_pcm_curso` (`curso_id`),
            KEY `fk_pcm_materia` (`materia_id`),
            CONSTRAINT `fk_pcm_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_pcm_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_pcm_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_asignaciones);

    // 4. Tabla de horarios de la cuadrícula
    $sql_horarios = "
        CREATE TABLE IF NOT EXISTS `horarios` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `asignacion_id` int(11) NOT NULL,
            `dia_semana` tinyint(1) NOT NULL,
            `hora_inicio` time NOT NULL,
            `hora_fin` time NOT NULL,
            PRIMARY KEY (`id`),
            KEY `fk_horario_asignacion` (`asignacion_id`),
            CONSTRAINT `fk_horario_asignacion` FOREIGN KEY (`asignacion_id`) REFERENCES `profesor_curso_materia` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_horarios);

    echo "SUCCESS";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
