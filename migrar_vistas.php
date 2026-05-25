<?php
$files = [
    'profesores.php',
    'asistencias.php',
    'auxiliares.php',
    'asistencias_auxiliares.php',
    'vacaciones_criterios.php',
    'articulos.php',
    'cursos.php',
    'materias.php',
    'asignaciones.php',
    'horarios.php',
    'usuarios.php'
];

if (!is_dir('views/pages')) {
    mkdir('views/pages', 0777, true);
}

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Remove standard header block
        $content = preg_replace('/<\?php\s*require_once \'config\/security\.php\';\s*require_login\(\);\s*require_once \'views\/layout\/header\.php\';\s*\?>/is', '', $content);
        $content = preg_replace('/<\?php\s*require_once \'views\/layout\/header\.php\';\s*\$isUsuario = isset\(\$_SESSION\[\'rol\'\]\) && \$_SESSION\[\'rol\'\] === \'usuario\';\s*\?>/is', "<?php\n\$isUsuario = isset(\$_SESSION['rol']) && \$_SESSION['rol'] === 'usuario';\n?>", $content);
        $content = preg_replace('/<\?php\s*require_once \'views\/layout\/header\.php\';\s*\?>/is', '', $content);
        
        // Remove standard footer block
        $content = preg_replace('/<\?php\s*require_once \'views\/layout\/footer\.php\';\s*\?>/is', '', $content);

        // Remove whitespace at top
        $content = ltrim($content);
        
        file_put_contents('views/pages/' . $file, $content);
        
        // Delete original file
        unlink($file);
        echo "Migrado: $file\n";
    }
}
echo "Migracion completa.\n";
