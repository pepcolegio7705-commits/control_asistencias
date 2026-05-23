<?php
session_start();
require_once 'config/database.php';

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index"); // URL amigable sin .php
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Por favor, ingresa usuario y contraseña.';
    } else {
        // Evitar SQL injection usando prepared statements
        $stmt = $pdo->prepare("SELECT id, password, rol, estado FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && $user['estado'] == 1) {
            // Verificar contraseña
            if (password_verify($password, $user['password'])) {
                // Prevenir Session Fixation
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['rol'] = $user['rol'];
                header("Location: index");
                exit;
            } else {
                $error = 'Credenciales incorrectas.';
            }
        } else {
            $error = 'Credenciales incorrectas o usuario inactivo.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Control de Asistencias</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .login-container { max-width: 400px; margin-top: 100px; }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Control de Asistencias</h4>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="login">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
