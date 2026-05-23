<?php
// install_db.php - Script temporal para ejecutar database.sql y alter_profesores.sql
$host = 'localhost';
$user = 'root';
$pass = ''; // Por defecto en WAMP

try {
    // Conectar a MySQL sin seleccionar base de datos (para poder crearla)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h1>Instalación de Base de Datos</h1>";
    
    // Leer e importar database.sql
    if (file_exists('database.sql')) {
        $sql = file_get_contents('database.sql');
        $pdo->exec($sql);
        echo "<p style='color:green'>✅ database.sql importado correctamente.</p>";
    } else {
        echo "<p style='color:red'>❌ No se encontró database.sql</p>";
    }

    // Seleccionar la base de datos
    $pdo->exec("USE `control_asistencias`");

    // Leer e importar alter_profesores.sql si la tabla profesores existe
    if (file_exists('alter_profesores.sql')) {
        $sqlAlter = file_get_contents('alter_profesores.sql');
        try {
            $pdo->exec($sqlAlter);
            echo "<p style='color:green'>✅ alter_profesores.sql importado correctamente (campos actualizados).</p>";
        } catch (PDOException $e) {
            // Ignorar error si las columnas ya existen
            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                echo "<p style='color:blue'>ℹ️ Las columnas de alter_profesores ya existen.</p>";
            } else {
                echo "<p style='color:orange'>⚠️ Error al ejecutar alter_profesores.sql (probablemente ya fue aplicado): " . $e->getMessage() . "</p>";
            }
        }
    } else {
         echo "<p style='color:red'>❌ No se encontró alter_profesores.sql</p>";
    }
    
    echo "<h3>¡Base de Datos lista! <a href='index.php'>Ir al sistema</a></h3>";

} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Error de conexión/ejecución: " . $e->getMessage() . "</p>";
}
?>
