<?php
require_once 'config/database.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS vacaciones_tomadas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        auxiliar_id INT NOT NULL,
        dias INT NOT NULL,
        observaciones VARCHAR(255) DEFAULT NULL,
        anio INT NOT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (auxiliar_id) REFERENCES auxiliares(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "Tabla 'vacaciones_tomadas' creada o verificada exitosamente.<br>";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage() . "<br>";
}
?>
