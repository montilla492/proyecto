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
        
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Futuros Compartidos</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Syne:wght@400;500;600;700&family=Inter:wght@300;400;500&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />

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
          <li class="nav-item"><a class="nav-link fc-nav-link active" href="index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link fc-nav-link" href="quiensomos.php">¿Quiénes Somos?</a></li>
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
    </div>
  </nav>
  </header>

  <section id="inicio" class="fc-hero">
    <div class="fc-hero-gif-background" aria-hidden="true">
      <img src="img/ojo hero.gif" alt="Fondo animado" class="fc-hero-gif-full">
    </div>

    <div class="container position-relative z-3">
      <div class="row justify-content-center">
        <div class="col-lg-10 text-center">
          <h1 class="fc-hero-title">Imagina el <span class="fc-gradient-text">mañana</span> hoy</h1>
          <p class="fc-hero-subtitle mx-auto">Un museo digital colaborativo donde el arte y la visión colectiva
            construyen el mundo que habitaremos en 50 años.</p>
          <div class="d-flex gap-3 justify-content-center mt-4">
            <a href="dashboard.php" class="fc-btn-primary">COMENZAR A CREAR</a>
            <a href="explorar.php" class="fc-btn-primary"
              style="background: var(--fc-surface); border: 1px solid var(--fc-border); box-shadow: none;">EXPLORAR
              OBRAS</a>
          </div>
        </div>
      </div>
    </div>

    <div class="fc-scroll-indicator">
      <div class="fc-scroll-dot"></div>
    </div>
  </section>

  <section id="quienes-somos" class="fc-section fc-museum-section">
    <div class="container">
      <div class="text-center mb-5">
        <span class="fc-label">El Museo</span>
        <h2 class="fc-section-title">Donde nuestros <span class="fc-gradient-text">futuros</span> se encuentran</h2>
        <p class="fc-section-desc mx-auto">
          Reunimos creaciones de artistas y visionarios de todo el mundo. Cada obra es una ventana a una posibilidad,
          una pieza del rompecabezas de nuestra supervivencia y evolución.
        </p>
      </div>

      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="fc-feature-card">
            <div class="fc-card-icon"><i class="bi bi-compass"></i></div>
            <h3 class="fc-card-title">Explora</h3>
            <p class="fc-card-text">Navega por una galería infinita de visiones, desde ciudades flotantes hasta nuevas
              formas de vida.</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="fc-feature-card">
            <div class="fc-card-icon"><i class="bi bi-chat-heart"></i></div>
            <h3 class="fc-card-title">Conecta</h3>
            <p class="fc-card-text">Forma parte de una comunidad que debate y cuestiona el rumbo de nuestra sociedad.
            </p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="fc-feature-card">
            <div class="fc-card-icon"><i class="bi bi-pencil-square"></i></div>
            <h3 class="fc-card-title">Crea</h3>
            <p class="fc-card-text">Tu perspectiva es única. Sube tus ilustraciones, relatos o vídeos y deja tu huella
              en el archivo.</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="fc-feature-card">
            <div class="fc-card-icon"><i class="bi bi-archive"></i></div>
            <h3 class="fc-card-title">Archiva</h3>
            <p class="fc-card-text">Construimos un legado digital de la imaginación humana para las generaciones
              venideras.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Últimas obras de la BD -->
  <section id="explorar" class="fc-section fc-gallery-section">
    <div class="container">
      <div class="text-center mb-5">
        <span class="fc-label">Galería</span>
        <h2 class="fc-section-title">Últimas obras <span class="fc-gradient-text">añadidas</span></h2>
      </div>

      <div class="row g-4 justify-content-center">
        <?php
        try {
            $stmt_obras = $pdo->prepare("SELECT o.*, u.nombre AS autor_nombre FROM obras o JOIN usuarios u ON o.usuario_id = u.id ORDER BY o.id DESC LIMIT 3");
            $stmt_obras->execute();
            $destacadas = $stmt_obras->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($destacadas) > 0) {
                foreach ($destacadas as $obra) {
                    $badge = strtoupper($obra['tipo']);
                    ?>
                    <div class="col-md-6 col-lg-4">
                      <article class="fc-obra-card">
                        <div class="fc-obra-img">
                          <span class="fc-obra-badge"><?php echo $badge; ?></span>
                          <img src="<?php echo htmlspecialchars($obra['archivo_url']); ?>" alt="<?php echo htmlspecialchars($obra['titulo']); ?>" class="fc-img-fluid">
                          <div class="fc-obra-overlay">
                            <a href="obra.php?id=<?php echo $obra['id']; ?>" class="fc-btn-primary">VER DETALLES</a>
                          </div>
                        </div>
                        <div class="fc-obra-info">
                          <h3 class="fc-obra-title"><?php echo htmlspecialchars($obra['titulo']); ?></h3>
                          <span class="fc-obra-author">por <?php echo htmlspecialchars($obra['autor_nombre']); ?></span>
                          <div class="fc-obra-stats">
                            <span><i class="bi bi-heart-fill"></i> <?php echo number_format($obra['likes']); ?></span>
                            <span><i class="bi bi-eye-fill"></i> <?php echo number_format($obra['visitas']); ?></span>
                          </div>
                        </div>
                      </article>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12 text-center text-secondary py-5"><p class="italic">No hay obras destacadas en el archivo todavía.</p></div>';
            }
        } catch (PDOException $e) {
            echo '<div class="col-12 text-center text-danger py-5"><p>Error al cargar las obras: ' . $e->getMessage() . '</p></div>';
        }
        ?>
      </div>

      <div class="text-center mt-5">
        <a href="explorar.php" class="fc-btn-primary"
          style="background: transparent; border: 1px solid var(--fc-border); box-shadow: none;">VER TODA LA GALERÍA</a>
      </div>
    </div>
  </section>

  <section id="crear" class="fc-section fc-cta-section">
    <div class="fc-cta-orb fc-cta-orb-1"></div>
    <div class="fc-cta-orb fc-cta-orb-2"></div>
    <div class="container position-relative z-2">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="fc-cta-title">¿Cómo imaginas el mundo <br />en <span class="fc-gradient-text">nuestro
              futuro</span>?</h2>
          <p class="fc-section-desc mx-auto">Tu visión merece ser compartida. Únete hoy y ayuda a construir el archivo
            de imaginación colectiva más grande del planeta.</p>
          <a href="registro.html" class="fc-btn-primary mt-3 px-5 py-3 fs-6">EMPIEZA A CREAR AHORA</a>
        </div>
      </div>
    </div>
  </section>

  <section id="comunidad" class="fc-section fc-stats-section" aria-labelledby="stats-title">
    <div class="container">

      <div class="text-center mb-5">
        <span class="fc-label">Nuestra Comunidad</span>
        <h2 id="stats-title" class="fc-section-title">
          Únete a miles de <span class="fc-gradient-text">visionarios</span>
        </h2>
        <p class="fc-section-desc mx-auto">
          Toma parte de una comunidad global de artistas, escritores y creativos que imaginan juntos el mundo del
          mañana.
        </p>
      </div>

      <div class="row g-4 justify-content-center">

        <div class="col-6 col-md-3">
          <div class="fc-stat-card">
            <div class="fc-stat-icon"><i class="bi bi-person-fill"></i></div>
            <div class="fc-stat-number">20.5K</div>
            <div class="fc-stat-label">Miembros activos</div>
          </div>
        </div>

        <div class="col-6 col-md-3">
          <div class="fc-stat-card">
            <div class="fc-stat-icon"><i class="bi bi-graph-up"></i></div>
            <div class="fc-stat-number">150+</div>
            <div class="fc-stat-label">Nuevas obras/día</div>
          </div>
        </div>

        <div class="col-6 col-md-3">
          <div class="fc-stat-card">
            <div class="fc-stat-icon"><i class="bi bi-calendar-event"></i></div>
            <div class="fc-stat-number">5</div>
            <div class="fc-stat-label">Eventos Mensuales</div>
          </div>
        </div>

        <div class="col-6 col-md-3">
          <div class="fc-stat-card">
            <div class="fc-stat-icon"><i class="bi bi-globe2"></i></div>
            <div class="fc-stat-number">500+</div>
            <div class="fc-stat-label">Eventos realizados</div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <footer class="fc-footer">
    <div class="container">
      <div class="row gy-5">
        <div class="col-lg-4 col-md-6">
          <div class="fc-footer-logo-wrap">
            <img src="img/Logonuevo.png" alt="Logo" class="fc-brand-logo mb-3">
            <div class="fc-footer-brand-name">Futuros<br />Compartidos</div>
          </div>
          <p class="fc-text-muted small pe-lg-5">Documentando la imaginación colectiva para construir el mundo del
            mañana.</p>
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
