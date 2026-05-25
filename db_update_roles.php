<?php
require_once 'config/database.php';

try {
    // 1. Ampliar el ENUM temporalmente para incluir los nuevos sin perder los viejos
    $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN rol enum('admin', 'user', 'Administrativo', 'usuario') NOT NULL DEFAULT 'usuario'");
    
    // 2. Migrar los viejos 'user' a 'usuario'
    $pdo->exec("UPDATE usuarios SET rol = 'usuario' WHERE rol = 'user'");
    
    // 3. Dejar el ENUM definitivo
    $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN rol enum('admin', 'Administrativo', 'usuario') NOT NULL DEFAULT 'usuario'");
    
    echo "Base de datos actualizada con éxito. El campo 'rol' ahora acepta admin, Administrativo y usuario.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
