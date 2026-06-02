<?php
require_once "init.php";
require_once "conexion.php";

$logged_in = isset($_SESSION['usuario']);
$avatar_url = 'img/ojo hero.gif';

if ($logged_in) {
    try {
        $stmt_nav = $pdo->prepare("SELECT avatar_url FROM usuarios WHERE usuario = :usuario");
        $stmt_nav->execute([':usuario' => $_SESSION['usuario']]);
        $user_nav = $stmt_nav->fetch(PDO::FETCH_ASSOC);
        if ($user_nav && !empty($user_nav['avatar_url'])) {
            $avatar_url = $user_nav['avatar_url'];
        }
    } catch (PDOException $e) {
        // Ignorar
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¿Quiénes Somos? - Futuros Compartidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Syne:wght@400;700&family=Inter:wght@300;400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark fc-navbar">
    <div class="container">
      <a class="navbar-brand fc-brand" href="index.php">
        <img src="img/Logonuevo.png" alt="Logo" class="fc-brand-logo">
        <span class="fc-brand-text">FUTUROS<br/><span class="fc-brand-sub">COMPARTIDOS</span></span>
      </a>

      <button class="navbar-toggler fc-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link fc-nav-link" href="index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link fc-nav-link active" href="quiensomos.php">¿Quiénes Somos?</a></li>
          <li class="nav-item"><a class="nav-link fc-nav-link" href="explorar.php">Explorar</a></li>
          <li class="nav-item"><a class="nav-link fc-nav-link" href="blog.php">Blog/Eventos</a></li>
          <li class="nav-item"><a class="nav-link fc-nav-link" href="contacto.php">Contacto</a></li>
        </ul>
        <div class="fc-nav-actions d-flex align-items-center gap-2">
          <?php if ($logged_in): ?>
            <a href="dashboard.php" title="Panel de Control" class="d-flex align-items-center" style="border: 2px solid var(--fc-purple); border-radius: 50%; overflow: hidden; width: 40px; height: 40px; transition: transform 0.3s ease;">
              <img src="<?php echo htmlspecialchars($avatar_url); ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
            </a>
          <?php else: ?>
            <a href="registro.html" class="fc-icon-btn" title="Registro"><i class="bi bi-person-plus"></i></a>
            <a href="login.php" class="fc-icon-btn" title="Entrar"><i class="bi bi-box-arrow-in-right"></i></a>
          <?php endif; ?>
        </div>
      </div>
  </nav>
  </header>
  <main class="fc-quienes-somos-main">
    <div class="container position-relative z-2 py-5">
      <!-- Hero -->
      <section class="fc-qs-hero text-center mb-5 pb-5">
        <span class="fc-label">Nuestra Identidad</span>
        <h1 class="fc-hero-title" style="font-size: clamp(2.5rem, 8vw, 4.5rem);">EL MUSEO DE LAS <br/><span class="fc-gradient-text">POSIBILIDADES</span></h1>
        <p class="lead text-white-50 mt-3 mx-auto" style="max-width: 700px;">Documentamos la imaginación humana para entender el horizonte que compartimos.</p>
        <div class="fc-hero-line mx-auto mt-4"></div>
      </section>

      <section class="row g-5 align-items-center mb-5">
        <div class="col-lg-6 order-2 order-lg-1">
          <div class="fc-glass-card p-4 p-md-5">
            <h2 class="fc-section-title mb-4" style="text-align: left;">Nuestra <span class="fc-purple-light">Misión</span></h2>
            <p class="text-white-50">Somos un museo virtual nacido de la necesidad de proyectar escenarios colectivos. No somos un archivo del pasado, sino un observatorio del futuro.</p>
            <p class="text-white-50">Creemos que el futuro no es algo que simplemente sucede, sino algo que diseñamos hoy con nuestras ideas y aspiraciones. Esta plataforma nace como un archivo vivo de escenarios posibles.</p>
            <div class="mt-4 d-flex gap-3">
              <div class="text-center">
                <h4 class="mb-0 fc-purple-light">50+</h4>
                <small class="text-muted">Países</small>
              </div>
              <div class="vr mx-2"></div>
              <div class="text-center">
                <h4 class="mb-0 fc-purple-light">1200</h4>
                <small class="text-muted">Visiones</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2">
          <div class="fc-qs-img-wrap">
            <img src="img/fotofut3.png" alt="Misión Futura" class="img-fluid rounded-4 shadow-lg border border-secondary">
          </div>
        </div>
      </section>

      <section class="mb-5 py-5">
        <div class="text-center mb-5">
          <h2 class="fc-section-title">Valores que nos <span class="fc-purple-light">mueven</span></h2>
        </div>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="fc-feature-card h-100">
              <div class="fc-feature-icon"><i class="bi bi-people"></i></div>
              <h3 class="fc-feature-title">Comunidad</h3>
              <p class="fc-feature-desc text-white-50">El futuro es un proyecto común. Fomentamos el diálogo entre artistas, científicos y soñadores.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="fc-feature-card h-100">
              <div class="fc-feature-icon"><i class="bi bi-cpu"></i></div>
              <h3 class="fc-feature-title">Tecnología Ética</h3>
              <p class="fc-feature-desc text-white-50">Exploramos la IA y la robótica desde una perspectiva humanista y responsable.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="fc-feature-card h-100">
              <div class="fc-feature-icon"><i class="bi bi-infinity"></i></div>
              <h3 class="fc-feature-title">Evolución</h3>
              <p class="fc-feature-desc text-white-50">No buscamos soluciones finales, sino la capacidad de adaptarnos a lo inesperado.</p>
            </div>
          </div>
        </div>
      </section>

      <section class="mb-5">
        <div class="fc-glass-card p-4 p-md-5">
          <h2 class="fc-section-title mb-5 text-center">Nuestra <span class="fc-purple-light">Trayectoria</span></h2>
          <div class="row g-4 text-center">
            <div class="col-md-4">
                <span class="badge bg-dark border border-secondary mb-3 p-2 px-3">2024</span>
                <h5>El Origen</h5>
                <p class="small text-white-50">Nace la idea de un repositorio digital para el arte visionario.</p>
            </div>
            <div class="col-md-4">
                <span class="badge bg-dark border border-secondary mb-3 p-2 px-3">2025</span>
                <h5>Expansión Global</h5>
                <p class="small text-white-50">Colaboramos con más de 10 festivales de arte tecnológico.</p>
            </div>
            <div class="col-md-4">
                <span class="badge bg-dark border border-secondary mb-3 p-2 px-3">2026</span>
                <h5>Museo 3.0</h5>
                <p class="small text-white-50">Lanzamos la plataforma interactiva que habitas hoy.</p>
            </div>
          </div>
        </div>
      </section>

      <section class="text-center py-5">
        <div class="fc-qs-cta p-5 rounded-5 border border-secondary" style="background: rgba(139, 92, 246, 0.05);">
          <h2 class="mb-4">¿Tienes una visión del <span class="fc-gradient-text">mañana</span>?</h2>
          <p class="text-white-50 mb-4 mx-auto" style="max-width: 600px;">Estamos buscando mentes inquietas que quieran compartir cómo imaginan el mundo. Tu relato puede ser la pieza que falta.</p>
          <a href="contacto.php" class="fc-btn-primary px-5">ÚNETE AL PROYECTO</a>
        </div>
      </section>
    </div>
  </main>

  <footer class="fc-footer">
    <div class="container">
      <div class="row gy-5">
        <div class="col-lg-4 col-md-6">
          <div class="fc-footer-logo-wrap">
            <img src="img/Logonuevo.png" alt="Logo" class="fc-brand-logo mb-3">
            <div class="fc-footer-brand-name">Futuros<br/>Compartidos</div>
          </div>
          <p class="fc-text-muted small pe-lg-5">Documentando la imaginación colectiva para construir el mundo del mañana.</p>
          <div class="fc-social-links">
            <a href="#" class="fc-social-btn"><i class="bi bi-instagram"></i></a>
            <a href="#" class="fc-social-btn"><i class="bi bi-tiktok"></i></a>
            <a href="#" class="fc-social-btn"><i class="bi bi-twitter-x"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <h4 class="fc-footer-heading">Navegación</h4>
          <ul class="fc-footer-links list-unstyled">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="quiensomos.php">¿Quiénes Somos?</a></li>
            <li><a href="explorar.php">Explorar Obras</a></li>
            <li><a href="blog.php">Blog/Eventos</a></li>
            <li><a href="contacto.php">Contacto</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12">
          <h4 class="fc-footer-heading">Contacto</h4>
          <div class="fc-footer-contact-item">
            <i class="bi bi-envelope"></i>
            <span>hola@futuroscompartidos.com</span>
          </div>
          <div class="fc-footer-contact-item">
            <i class="bi bi-geo-alt"></i>
            <span>Madrid, España</span>
          </div>
        </div>
      </div>

      <div class="fc-footer-bottom">
        <div class="fc-footer-legal">
          <a href="#">Privacidad</a>
          <a href="#">Términos</a>
          <a href="#">Cookies</a>
        </div>
        <p class="fc-footer-copyright">&copy; 2026 Futuros Compartidos. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="script.js"></script>

</body>

</html>
