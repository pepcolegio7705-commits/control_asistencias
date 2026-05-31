<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// Copiar logo si se generó y no está en la carpeta actual
$logo_src = 'C:\Users\jawsi\.gemini\antigravity-ide\brain\7b27a674-c97e-4244-b6b7-b3c85eee858e\sintek_logo_1779582511778.png';
$logo_dest = 'sintek_logo.png';
if (!file_exists($logo_dest) && file_exists($logo_src)) {
    copy($logo_src, $logo_dest);
}

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php?page=dashboard");
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
                header("Location: index.php?page=dashboard");
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
    <title>Sintek - Control Asistencias</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }
        .login-card h4 {
            color: #fff;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(77, 168, 218, 0.5);
            background: #fff;
        }
        .btn-primary {
            background: linear-gradient(to right, #0052D4, #4364F7, #6FB1FC);
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(111, 177, 252, 0.4);
            background: linear-gradient(to right, #0052D4, #4364F7, #6FB1FC);
        }
        .logo-img {
            max-width: 140px;
            margin-bottom: 1.5rem;
            border-radius: 15px;
            padding: 5px;
            background: rgba(255,255,255,0.05);
        }
        .footer {
            background: rgba(0, 0, 0, 0.6);
            padding: 1.5rem 0;
            text-align: center;
            font-size: 0.95rem;
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .footer a {
            color: #6FB1FC;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .footer a:hover {
            color: #fff;
            text-decoration: underline;
        }
        .contact-info span {
            display: inline-block;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="login-card text-center">
            <?php if (file_exists('sintek_logo.png') || file_exists($logo_src)): ?>
                <img src="sintek_logo.png" alt="Sintek Logo" class="logo-img shadow-sm">
            <?php endif; ?>
            
            <h4 class="mb-4">Sintek - Control Asistencias</h4>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" style="background: rgba(220,53,69,0.9); color: white; border: none; border-radius: 10px;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="?page=login" class="text-start">
                <div class="mb-3">
                    <label for="username" class="form-label text-light fw-semibold">Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Ingresa tu usuario" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label text-light fw-semibold">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-2">Ingresar al Sistema</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-4 mb-3 mb-md-0 fw-bold fs-5 text-md-start">
                    Sintek Gestión
                </div>
                <div class="col-12 col-md-4 mb-3 mb-md-0 contact-info">
                    <span>Tel.: 0280154847619</span><br class="d-md-none">
                    <span class="d-none d-md-inline">|</span>
                    <span>Email: sintekgestion@gmail.com</span>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a href="https://www.youtube.com/@sintek-gestion" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-youtube me-1" viewBox="0 0 16 16" style="vertical-align: text-bottom;">
                          <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.05-.074 1.958l-.008.103-.022.26l-.01.104c-.048.519-.119 1.023-.22 1.402a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c1.16-.312 5.569-.334 6.18-.335h.142c.309 0 1.587.006 2.927.052l.17.006.087.004.171.007.171.007c1.11.049 2.167.128 2.654.26zM6.5 11.5 10.5 8 6.5 4.5v7z"/>
                        </svg>
                        YouTube: @sintek-gestion
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
