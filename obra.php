<?php
require_once "init.php";
require_once "conexion.php";

$obra_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$obra = null;

if ($obra_id > 0) {
    try {
        // Sumar visita
        $stmt_visit = $pdo->prepare("UPDATE obras SET visitas = visitas + 1 WHERE id = :id");
        $stmt_visit->execute([':id' => $obra_id]);

        // Obtener datos de la obra y su autor
        $stmt = $pdo->prepare("SELECT o.*, u.nombre AS autor_nombre, u.avatar_url AS autor_avatar, u.instagram, u.twitter, u.tiktok 
                               FROM obras o 
                               JOIN usuarios u ON o.usuario_id = u.id 
                               WHERE o.id = :id");
        $stmt->execute([':id' => $obra_id]);
        $obra = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}

// Redirigir si la obra no existe
if (!$obra) {
    header("Location: explorar.php");
    exit;
}

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
    <title><?php echo htmlspecialchars($obra['titulo']); ?> - Futuros Compartidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Syne:wght@400;500;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

  <!-- Navbar -->
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
            <li class="nav-item"><a class="nav-link fc-nav-link" href="quiensomos.php">¿Quiénes Somos?</a></li>
            <li class="nav-item"><a class="nav-link fc-nav-link active" href="explorar.php">Explorar</a></li>
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
      </div>
    </nav>
  </header>

  <!-- Detalle de la obra -->
  <main class="fc-detail-main py-5">
    <div class="container">
      
      <div class="mb-4">
        <a href="explorar.php" class="btn btn-outline-light btn-sm px-3 rounded-pill" style="border-color: var(--fc-border);">
          <i class="bi bi-arrow-left me-2"></i> Volver a la galería
        </a>
      </div>

      <div class="row g-5">
        
        <!-- Multimedia -->
        <div class="col-lg-7">
          <div class="fc-detail-media-card">
            <?php if ($obra['tipo'] === 'video'): ?>
              <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg border border-secondary">
                <video src="<?php echo htmlspecialchars($obra['archivo_url']); ?>" controls class="w-100 h-100" style="object-fit: cover;"></video>
              </div>
            <?php elseif ($obra['tipo'] === 'imagen'): ?>
              <div class="fc-detail-img-wrap rounded-4 overflow-hidden shadow-lg border border-secondary">
                <img src="<?php echo htmlspecialchars($obra['archivo_url']); ?>" alt="<?php echo htmlspecialchars($obra['titulo']); ?>" class="img-fluid w-100" style="object-fit: cover; max-height: 550px;">
              </div>
            <?php elseif ($obra['tipo'] === 'relato'): ?>
              <div class="fc-detail-img-wrap rounded-4 overflow-hidden shadow-lg border border-secondary mb-4" style="max-height: 300px;">
                <img src="<?php echo htmlspecialchars($obra['archivo_url']); ?>" alt="<?php echo htmlspecialchars($obra['titulo']); ?>" class="img-fluid w-100" style="object-fit: cover; height: 300px;">
              </div>
              <div class="fc-detail-relato-box p-4 p-md-5 rounded-4 border border-secondary" style="background: var(--fc-bg-2);">
                <div class="fc-relato-header mb-4">
                  <i class="bi bi-quote fs-1 fc-purple-light"></i>
                  <h4 class="font-heading text-white">Relato de Ciencia Ficción</h4>
                </div>
                <div class="fc-relato-text text-white-50" style="white-space: pre-wrap; font-size: 1.05rem; line-height: 1.8; letter-spacing: 0.01em;">
                  <?php echo htmlspecialchars($obra['contenido_relato']); ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Información -->
        <div class="col-lg-5">
          <div class="fc-detail-info-card p-4 p-md-5 rounded-4" style="background: var(--fc-bg-2); border: 1px solid var(--fc-border);">
            

            <span class="fc-label"><?php echo htmlspecialchars($obra['tipo']); ?></span>
            

            <h1 class="font-heading mb-3" style="font-family: var(--fc-font-heading); font-size: 2.2rem; font-weight: 800; color: white;">
              <?php echo htmlspecialchars($obra['titulo']); ?>
            </h1>


            <div class="d-flex align-items-center gap-3 mb-4">
              <span class="badge bg-dark border border-secondary py-2 px-3 rounded-pill text-uppercase font-monospace" style="font-size: 0.8rem; color: var(--fc-purple-light);">
                Año Proyectado: <?php echo htmlspecialchars($obra['anio_proyectado']); ?>
              </span>
              <span class="text-secondary small">
                <i class="bi bi-calendar-check me-1"></i> <?php echo date('d/m/Y', strtotime($obra['creado_en'])); ?>
              </span>
            </div>


            <div class="mb-4">
              <h5 class="fs-6 text-uppercase fw-bold text-secondary mb-2" style="font-family: var(--fc-font-display);">El Concepto</h5>
              <p class="text-white-50" style="line-height: 1.7; font-size: 0.95rem;">
                <?php echo htmlspecialchars($obra['descripcion']); ?>
              </p>
            </div>


            <?php if (!empty($obra['etiquetas'])): ?>
              <div class="mb-4">
                <h5 class="fs-6 text-uppercase fw-bold text-secondary mb-2" style="font-family: var(--fc-font-display);">Etiquetas</h5>
                <div class="d-flex flex-wrap gap-2">
                  <?php 
                  $tags = explode(',', $obra['etiquetas']);
                  foreach ($tags as $tag): 
                      $tag_trimmed = trim($tag);
                      if ($tag_trimmed !== ''):
                  ?>
                    <span class="badge rounded-pill text-white" style="background: rgba(255,255,255,0.05); border: 1px solid var(--fc-border); font-size: 0.75rem;">
                      #<?php echo htmlspecialchars($tag_trimmed); ?>
                    </span>
                  <?php 
                      endif;
                  endforeach; 
                  ?>
                </div>
              </div>
            <?php endif; ?>


            <div class="d-flex gap-4 border-top border-bottom border-secondary py-3 my-4">
              <div class="text-center">
                <h4 class="mb-0 fs-5 text-white"><?php echo number_format($obra['visitas']); ?></h4>
                <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Visitas</small>
              </div>
              <div class="text-center">
                <h4 class="mb-0 fs-5 text-white" id="likesCount"><?php echo number_format($obra['likes']); ?></h4>
                <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Likes</small>
              </div>
              <div class="ms-auto d-flex align-items-center">
                <button class="btn btn-outline-danger btn-sm rounded-pill px-3" id="likeBtn" data-id="<?php echo $obra['id']; ?>">
                  <i class="bi bi-heart me-1"></i> Dar Like
                </button>
              </div>
            </div>


            <div class="fc-detail-author-wrap d-flex align-items-center gap-3">
              <div class="rounded-circle overflow-hidden border border-secondary" style="width: 50px; height: 50px;">
                <img src="<?php echo htmlspecialchars($obra['autor_avatar'] ? $obra['autor_avatar'] : 'img/ojo hero.gif'); ?>" alt="Autor" class="w-100 h-100" style="object-fit: cover;">
              </div>
              <div>
                <span class="d-block text-secondary small text-uppercase">Creador de la obra</span>
                <strong class="text-white fs-6"><?php echo htmlspecialchars($obra['autor_nombre']); ?></strong>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </main>

  <footer class="fc-footer">
    <div class="container">
      <div class="row gy-5">
        <div class="col-lg-4 col-md-6">
          <div class="fc-footer-logo-wrap">
            <img src="img/Logonuevo.png" alt="Logo" class="fc-brand-logo mb-3">
            <div class="fc-footer-brand-name">Futuros<br />Compartidos</div>
          </div>
          <p class="fc-text-muted small pe-lg-5">Documentando la imaginación colectiva para construir el mundo del mañana.</p>
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
          <div class="fc-footer-contact-item"><i class="bi bi-envelope"></i> <span>hola@futuroscompartidos.com</span></div>
          <div class="fc-footer-contact-item"><i class="bi bi-geo-alt"></i> <span>Madrid, España</span></div>
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
