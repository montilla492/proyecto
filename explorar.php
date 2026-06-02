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
    <title>Explorador - Futuros Compartidos</title>
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

  <main class="fc-explorer-main fc-section">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fc-hero-title" style="font-size: clamp(2rem, 5vw, 3.5rem);">EXPLORADOR DE <span class="fc-gradient-text">OBRAS</span></h1>
        </div>

        <div class="fc-filter-wrapper mb-4">
            <div class="fc-filter-group" id="mainFilterBar">
                <button class="fc-filter-btn" data-mode="search">Buscar</button>
                <button class="fc-filter-btn active" data-mode="type">Tipo de obra</button>
                <button class="fc-filter-btn" data-mode="theme">Temática</button>
            </div>
        </div>

        <div class="fc-subfilters-container mb-5">
            <div id="filter-search" class="fc-subfilter-mode d-none">
                <div class="fc-search-wrap mx-auto" style="max-width: 500px;">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-secondary text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="obraSearch" class="form-control bg-transparent border-secondary text-white" placeholder="Escribe el nombre de una obra o autor...">
                    </div>
                </div>
            </div>

            <div id="filter-type" class="fc-subfilter-mode">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <button class="fc-tag-btn active" data-filter="all">
                        <i class="bi bi-grid"></i> Todo
                    </button>
                    <button class="fc-tag-btn" data-filter="imagen">
                        <i class="bi bi-image"></i> Imágenes
                    </button>
                    <button class="fc-tag-btn" data-filter="video">
                        <i class="bi bi-play-circle"></i> Vídeos
                    </button>
                    <button class="fc-tag-btn" data-filter="relato">
                        <i class="bi bi-file-earmark-text"></i> Relatos
                    </button>
                </div>
            </div>

            <div id="filter-theme" class="fc-subfilter-mode d-none">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <button class="fc-tag-btn active" data-filter="all">Todas</button>
                    <button class="fc-tag-btn" data-filter-theme="espacio">Espacio</button>
                    <button class="fc-tag-btn" data-filter-theme="naturaleza">Naturaleza</button>
                    <button class="fc-tag-btn" data-filter-theme="cyberpunk">Cyberpunk</button>
                </div>
            </div>
        </div>

        <div class="row g-4 fc-obra-grid">
            <div id="noResults" class="col-12 text-center py-5 d-none">
                <i class="bi bi-search fs-1 mb-3 fc-purple-light"></i>
                <h3 class="fc-section-title" style="font-size: 1.5rem;">No se encontraron obras</h3>
                <p class="fc-text-secondary">Intenta con otros términos o cambia la categoría.</p>
            </div>

            <?php
            try {
                $stmt = $pdo->prepare("SELECT o.*, u.nombre AS autor_nombre FROM obras o JOIN usuarios u ON o.usuario_id = u.id ORDER BY o.id DESC");
                $stmt->execute();
                $obras = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($obras) > 0) {
                    foreach ($obras as $obra) {
                        $badge = strtoupper($obra['tipo']);
                        
                        // Temática según etiquetas
                        $tags_lower = strtolower($obra['etiquetas']);
                        $theme = 'cyberpunk';
                        if (strpos($tags_lower, 'espacio') !== false || strpos($tags_lower, 'marte') !== false) {
                            $theme = 'espacio';
                        } elseif (strpos($tags_lower, 'naturaleza') !== false || strpos($tags_lower, 'ecologia') !== false || strpos($tags_lower, 'submarino') !== false) {
                            $theme = 'naturaleza';
                        }
                        ?>
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3 fc-obra-item" data-category="<?php echo htmlspecialchars($obra['tipo']); ?>" data-theme="<?php echo $theme; ?>">
                            <article class="fc-obra-card">
                                <div class="fc-obra-img">
                                    <span class="fc-obra-badge"><?php echo $badge; ?></span>
                                    <img src="<?php echo htmlspecialchars($obra['archivo_url']); ?>" alt="<?php echo htmlspecialchars($obra['titulo']); ?>" class="fc-img-fluid">
                                    <div class="fc-obra-overlay">
                                        <a href="obra.php?id=<?php echo $obra['id']; ?>" class="fc-btn-primary btn-sm">VER OBRA</a>
                                    </div>
                                </div>
                                <div class="fc-obra-info">
                                    <h3 class="fc-obra-title"><?php echo htmlspecialchars($obra['titulo']); ?></h3>
                                    <span class="fc-obra-author"><?php echo htmlspecialchars($obra['autor_nombre']); ?></span>
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
                    echo '<div class="col-12 text-center py-5"><p class="text-secondary italic">No hay ninguna obra registrada en el archivo todavía.</p></div>';
                }
            } catch (PDOException $e) {
                echo '<div class="col-12 text-center text-danger py-5"><p>Error al obtener obras: ' . $e->getMessage() . '</p></div>';
            }
            ?>
        </div>

        <div class="text-center mt-5">
            <button class="fc-btn-primary" style="background: var(--fc-surface); border: 1px solid var(--fc-border); box-shadow: none;">CARGAR MÁS OBRAS</button>
        </div>
    </div>
  </main>

  <footer class="fc-footer">
    <div class="container">
      <div class="row gy-5">
        <!-- Columna 1: Marca -->
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

        <!-- Columna 2: Navegación -->
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

        <!-- Columna 3: Contacto -->
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
  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const obraItems = document.querySelectorAll('.fc-obra-item');
        
        const modeBtns = document.querySelectorAll('#mainFilterBar .fc-filter-btn');
        const subfilterModes = document.querySelectorAll('.fc-subfilter-mode');

        modeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modeBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const targetMode = btn.getAttribute('data-mode');
                subfilterModes.forEach(mode => {
                    if(mode.id === `filter-${targetMode}`) {
                        mode.classList.remove('d-none');
                    } else {
                        mode.classList.add('d-none');
                    }
                });

                // Al cambiar de modo, reseteamos filtros para mostrar todo
                resetFilters();
            });
        });

        // Filtros por tipo y temática
        const tagBtns = document.querySelectorAll('.fc-tag-btn');

        tagBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active from siblings in the same group
                const parent = btn.parentElement;
                parent.querySelectorAll('.fc-tag-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                applyFilters();
            });
        });

        // Búsqueda por texto
        const searchInput = document.getElementById('obraSearch');
        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        function applyFilters() {
            const activeModeBtn = document.querySelector('#mainFilterBar .fc-filter-btn.active');
            const activeMode = activeModeBtn ? activeModeBtn.getAttribute('data-mode') : 'search';
            
            const searchText = searchInput ? searchInput.value.toLowerCase() : '';
            const activeTypeBtn = document.querySelector('#filter-type .fc-tag-btn.active');
            const activeThemeBtn = document.querySelector('#filter-theme .fc-tag-btn.active');

            const typeFilter = activeTypeBtn ? activeTypeBtn.getAttribute('data-filter') : 'all';
            const themeFilter = activeThemeBtn ? activeThemeBtn.getAttribute('data-filter-theme') : 'all';

            const noResults = document.getElementById('noResults');
            let visibleCount = 0;

            obraItems.forEach(item => {
                const category = item.getAttribute('data-category');
                const theme = item.getAttribute('data-theme');
                const titleEl = item.querySelector('.fc-obra-title');
                const authorEl = item.querySelector('.fc-obra-author');
                const title = titleEl ? titleEl.textContent.toLowerCase() : '';
                const author = authorEl ? authorEl.textContent.toLowerCase() : '';

                let isVisible = false;

                if (activeMode === 'search') {
                    isVisible = title.includes(searchText) || author.includes(searchText);
                } else if (activeMode === 'type') {
                    isVisible = (typeFilter === 'all' || category === typeFilter);
                } else if (activeMode === 'theme') {
                    isVisible = (themeFilter === 'all' || theme === themeFilter);
                }

                if (isVisible) {
                    visibleCount++;
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });

            if (visibleCount === 0) {
                if (noResults) noResults.classList.remove('d-none');
            } else {
                if (noResults) noResults.classList.add('d-none');
            }
        }

        function resetFilters() {
            if (searchInput) searchInput.value = '';
            document.querySelectorAll('.fc-tag-btn').forEach(btn => {
                if(btn.getAttribute('data-filter') === 'all' || btn.getAttribute('data-filter-theme') === 'all') {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            applyFilters();
        }
    });
  </script>
</body>
</html>
