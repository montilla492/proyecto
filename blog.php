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
    <title>Blog & Eventos - Futuros Compartidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Syne:wght@400;500;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet" />
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
          <li class="nav-item"><a class="nav-link fc-nav-link" href="index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link fc-nav-link" href="quiensomos.php">¿Quiénes Somos?</a></li>
          <li class="nav-item"><a class="nav-link fc-nav-link" href="explorar.php">Explorar</a></li>
          <li class="nav-item"><a class="nav-link fc-nav-link active" href="blog.php">Blog/Eventos</a></li>
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

  <main>
    <!-- Hero -->
    <section class="fc-blog-hero">
        <div class="container">
            <div class="fc-blog-hero-content">
                <h1 class="fc-blog-hero-title">Actualidad</h1>
                <div class="fc-blog-hero-line"></div>
            </div>
        </div>
    </section>

    <!-- Filtros -->
    <section class="fc-blog-filter py-5">
        <div class="container">
            <div class="fc-filter-wrapper">
                <div class="fc-filter-group" id="blogTabs" role="tablist">
                    <button class="fc-filter-btn active" id="blog-tab" data-bs-toggle="tab" data-bs-target="#blog-content" type="button" role="tab">Blog de Futuro</button>
                    <button class="fc-filter-btn" id="eventos-tab" data-bs-toggle="tab" data-bs-target="#eventos-content" type="button" role="tab">Eventos Reales</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="blogTabContent">
        <!-- Blog -->
        <div class="tab-pane fade show active" id="blog-content" role="tabpanel">
            <section class="fc-section pt-0">
                <div class="container">
                    <div class="fc-blog-list">
                        <article class="fc-blog-item">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4">
                                    <div class="fc-blog-img-wrap">
                                        <img src="img/fotofut3.png" alt="IA y Arte" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="fc-blog-content">
                                        <h2 class="fc-blog-title">La Inteligencia Artificial: ¿Aliada o Enemiga del Artista?</h2>
                                        <span class="fc-blog-date">10 DE MAYO DE 2026</span>
                                        <p class="fc-blog-excerpt">Exploramos cómo las nuevas herramientas generativas están transformando el proceso creativo. ¿Estamos ante una democratización del arte o ante el fin de la originalidad humana? [...]</p>
                                        <a href="blog-ia-arte.html" class="fc-blog-link">Leer artículo completo <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="fc-blog-item">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4">
                                    <div class="fc-blog-img-wrap">
                                        <img src="img/relatos.png" alt="Museos Inmersivos" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="fc-blog-content">
                                        <h2 class="fc-blog-title">Museos del Mañana: Realidad Aumentada y Experiencias Inmersivas</h2>
                                        <span class="fc-blog-date">28 DE ABRIL DE 2026</span>
                                        <p class="fc-blog-excerpt">La visita al museo ya no es un acto pasivo. Analizamos cómo la tecnología inmersiva permite al espectador "entrar" literalmente en la obra y participar en ella. [...]</p>
                                        <a href="blog-museos-inmersivos.html" class="fc-blog-link">Leer artículo completo <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="fc-blog-item">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4">
                                    <div class="fc-blog-img-wrap">
                                        <img src="img/glasses-17902.gif" alt="Criptoarte" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="fc-blog-content">
                                        <h2 class="fc-blog-title">El Criptoarte y los NFTs: ¿Moda Pasajera o Revolución?</h2>
                                        <span class="fc-blog-date">15 DE MARZO de 2026</span>
                                        <p class="fc-blog-excerpt">Tras el boom inicial, el mercado del criptoarte se estabiliza. Entrevistamos a coleccionistas y artistas que están definiendo las nuevas reglas de propiedad digital. [...]</p>
                                        <a href="blog-criptoarte.html" class="fc-blog-link">Leer artículo completo <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
        </div>

        <!-- Eventos -->
        <div class="tab-pane fade" id="eventos-content" role="tabpanel">
            <section class="fc-section pt-0">
                <div class="container">
                    <div class="fc-blog-list">
                        <article class="fc-blog-item">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4">
                                    <div class="fc-blog-img-wrap">
                                        <img src="img/ojo hero.gif" alt="MMMAD" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="fc-blog-content">
                                        <h2 class="fc-blog-title">MMMAD - Festival Urbano de Arte Digital</h2>
                                        <span class="fc-blog-date">ABRIL 2026 | MADRID, ESPAÑA</span>
                                        <p class="fc-blog-excerpt">Bajo el lema "Condiciones de Uso", el festival transforma Madrid en un museo abierto de arte digital, utilizando pantallas publicitarias y fachadas para instalaciones inmersivas.</p>
                                        <a href="https://mmmad.art" target="_blank" class="fc-blog-link">Visitar web oficial <i class="bi bi-box-arrow-up-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="fc-blog-item">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4">
                                    <div class="fc-blog-img-wrap">
                                        <img src="img/fotofut3.png" alt="Sónar+D" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="fc-blog-content">
                                        <h2 class="fc-blog-title">Sónar+D: Creatividad, Tecnología y Negocio</h2>
                                        <span class="fc-blog-date">JUNIO 2026 | BARCELONA, ESPAÑA</span>
                                        <p class="fc-blog-excerpt">El encuentro internacional de referencia que explora cómo la creatividad cambia nuestro presente e imagina nuevos futuros, con un enfoque masivo en IA generativa este año.</p>
                                        <a href="https://sonar.es/es/plusd" target="_blank" class="fc-blog-link">Explorar programa <i class="bi bi-box-arrow-up-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="fc-blog-item">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4">
                                    <div class="fc-blog-img-wrap">
                                        <img src="img/relatos.png" alt="Dataland" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="fc-blog-content">
                                        <h2 class="fc-blog-title">Dataland: El primer museo de arte de IA del mundo</h2>
                                        <span class="fc-blog-date">APERTURA 2025/2026 | LOS ÁNGELES, EE.UU.</span>
                                        <p class="fc-blog-excerpt">Fundado por Refik Anadol Studio, este espacio pionero utiliza la visualización de datos y la inteligencia artificial para crear entornos arquitectónicos vivos y artísticos.</p>
                                        <a href="https://refikanadol.com" target="_blank" class="fc-blog-link">Saber más del proyecto <i class="bi bi-box-arrow-up-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="fc-blog-item">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4">
                                    <div class="fc-blog-img-wrap">
                                        <img src="img/glasses-17902.gif" alt="AI Biennale" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="fc-blog-content">
                                        <h2 class="fc-blog-title">1st International AI Art Biennale</h2>
                                        <span class="fc-blog-date">MARZO 2026 | CRACOVIA, POLONIA</span>
                                        <p class="fc-blog-excerpt">Un evento global dedicado específicamente al diálogo creativo y la educación a través de la IA, integrando exposiciones masivas y conferencias de expertos mundiales.</p>
                                        <a href="https://biennaleai.org" target="_blank" class="fc-blog-link">Información oficial <i class="bi bi-box-arrow-up-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
        </div>
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
