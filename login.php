<?php
require_once "init.php";
require_once "conexion.php";

$mensaje = "";

// Redireccionar si ya inició sesión
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $recordarme = isset($_POST['recordarme']);

    if ($usuario !== '' && $password !== '') {
        try {
            // Permitir loguearse tanto con el nombre de usuario como con el email
            $stmt = $pdo->prepare("SELECT id, usuario, password FROM usuarios WHERE usuario = :usuario OR email = :usuario");
            $stmt->execute([':usuario' => $usuario]);
            $user_row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_row) {
                if (password_verify($password, $user_row['password'])) {
                    $_SESSION['usuario'] = $user_row['usuario'];
                    $_SESSION['usuario_id'] = $user_row['id'];

                    if ($recordarme) {
                        setcookie('session_id_activa', session_id(), time() + (60 * 60 * 24 * 365), '/');
                    }

                    header("Location: dashboard.php");
                    exit;
                } else {
                    $mensaje = "Contraseña incorrecta.";
                }
            } else {
                $mensaje = "El usuario o correo electrónico no existe.";
            }
        } catch (PDOException $e) {
            $mensaje = "Error en el sistema: " . $e->getMessage();
        }
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Futuros Compartidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Syne:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="registro-page">

    <canvas id="canvas-registro"></canvas>

    <div class="registro-wrapper">
        <a href="index.php" class="fc-back-btn">
            <i class="bi bi-arrow-left"></i> Volver al inicio
        </a>

        <div class="registro-card">
            <div class="text-center mb-4">
                <img src="img/Logonuevo.png" alt="Logo" class="fc-brand-logo mb-4" style="height: 60px;">
                <h2 class="fc-section-title" style="font-size: 1.8rem;">Bienvenido de <span class="fc-gradient-text">nuevo</span></h2>
                <p class="fc-text-secondary small">Accede a tu portal del futuro</p>
            </div>

            <?php if ($mensaje != ""): ?>
                <div class="alert alert-danger py-2 px-3 small border-0 mb-4 text-center" style="background: rgba(244, 63, 94, 0.15); color: #F43F5E; border: 1px solid rgba(244, 63, 94, 0.2) !important; border-radius: var(--fc-radius-sm);">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label class="fc-form-label">Email o Usuario</label>
                    <input type="text" name="usuario" class="fc-form-input" placeholder="tu_usuario o tu@futuro.com" required value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label class="fc-form-label">Contraseña</label>
                    <input type="password" name="password" class="fc-form-input" placeholder="••••••••" required>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check m-0 d-flex align-items-center gap-2">
                        <input class="form-check-input bg-transparent border-secondary" type="checkbox" name="recordarme" id="recordarme" style="cursor: pointer;">
                        <label class="form-check-label text-secondary small" for="recordarme" style="cursor: pointer; user-select: none;">Recordarme</label>
                    </div>
                    <a href="#" class="small fc-text-muted" style="text-decoration: none;">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="fc-btn-submit w-100 mb-3">INICIAR SESIÓN</button>
                
                <div class="fc-divider">
                    <span>o continuar con</span>
                </div>

                <div class="d-flex gap-3 mb-4">
                    <a href="#" class="fc-btn-social w-100">
                        <i class="bi bi-google"></i> Google
                    </a>
                    <a href="#" class="fc-btn-social w-100">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>
                </div>

                <div class="text-center mt-4">
                    <span class="fc-text-muted small">¿No tienes cuenta?</span>
                    <a href="registro.html" class="small ms-2 fw-bold" style="color: var(--fc-purple-light);">Regístrate aquí</a>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
