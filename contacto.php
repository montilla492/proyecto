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
    <title>Contacto - Futuros Compartidos</title>
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
                    <span class="fc-brand-text">FUTUROS<br /><span class="fc-brand-sub">COMPARTIDOS</span></span>
                </a>

                <button class="navbar-toggler fc-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link fc-nav-link" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link fc-nav-link" href="quiensomos.php">¿Quiénes Somos?</a></li>
                        <li class="nav-item"><a class="nav-link fc-nav-link" href="explorar.php">Explorar</a></li>
                        <li class="nav-item"><a class="nav-link fc-nav-link" href="blog.php">Blog/Eventos</a></li>
                        <li class="nav-item"><a class="nav-link fc-nav-link active" href="contacto.php">Contacto</a></li>
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

    <section class="fc-contact-section fc-section" style="padding: 6rem 0;">
        <div class="container">
            <div class="text-center mb-5">
                <span class="fc-label">Conecta con nosotros</span>
                <h2 class="fc-section-title">Hablemos del <span class="fc-gradient-text">futuro</span></h2>
                <div class="fc-contact-intro mx-auto" style="max-width: 720px;">
                    <p>Este museo es un espacio vivo que se nutre del diálogo. Si tienes una propuesta, una duda o
                        simplemente quieres compartir una reflexión sobre el mañana, nos encantaría escucharte.</p>
                </div>
            </div>

            <div class="row g-5">
                <!-- Formulario -->
                <div class="col-lg-7">
                    <div class="fc-form-container">
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="fc-form-label">Nombre completo</label>
                                    <input type="text" class="fc-form-input" placeholder="Tu nombre">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="fc-form-label">Correo electrónico</label>
                                    <input type="email" class="fc-form-input" placeholder="tu@email.com">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="fc-form-label">Asunto</label>
                                <input type="text" class="fc-form-input" placeholder="¿De qué trata tu mensaje?">
                            </div>
                            <div class="mb-4">
                                <label class="fc-form-label">Tu mensaje o reflexión</label>
                                <textarea class="fc-form-input" rows="6" placeholder="Escribe aquí..."></textarea>
                            </div>
                            <button type="submit" class="fc-btn-submit">Enviar mensaje</button>
                        </form>
                    </div>
                </div>

                <!-- Información lateral -->
                <div class="col-lg-5">
                    <div class="fc-text-wrapper" style="height: 100%; margin-bottom: 0;">
                        <h3 class="fc-section-title" style="font-size: 1.5rem;">Canales directos</h3>
                        <p class="fc-section-desc">Estamos presentes en distintas plataformas para mantener viva la
                            conversación.</p>

                        <div class="fc-contact-info-list mt-4">
                            <div class="fc-info-item d-flex align-items-center gap-3 mb-4">
                                <i class="bi bi-envelope-at fc-purple-light fs-4"></i>
                                <div>
                                    <span class="d-block fc-text-muted fs-7 text-uppercase fw-bold">Email</span>
                                    <span class="fc-text-primary fw-medium">hola@futuroscompartidos.com</span>
                                </div>
                            </div>
                            <div class="fc-info-item d-flex align-items-center gap-3 mb-4">
                                <i class="bi bi-telephone fc-purple-light fs-4"></i>
                                <div>
                                    <span class="d-block fc-text-muted fs-7 text-uppercase fw-bold">Teléfono</span>
                                    <span class="fc-text-primary fw-medium">+34 687 234 654</span>
                                </div>
                            </div>
                        </div>

                        <div class="fc-social-links mt-5">
                            <a href="#" class="fc-social-btn"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="fc-social-btn"><i class="bi bi-tiktok"></i></a>
                            <a href="#" class="fc-social-btn"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="fc-social-btn"><i class="bi bi-discord"></i></a>
                        </div>

                        <div class="mt-5 p-4 rounded-4"
                             style="background: var(--fc-gradient-subtle); border: 1px solid var(--fc-border);">
                            <p class="mb-3 fw-bold">¿Tienes una obra lista?</p>
                            <p class="fc-text-secondary small mb-4">Únete a nuestra comunidad de visionarios y comparte
                                tu creación con el mundo.</p>
                            <a href="registro.html" class="fc-btn-primary w-100 text-center">CREA TU FUTURO</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Preguntas frecuentes -->
    <section class="fc-section fc-faq-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="fc-label">Dudas frecuentes</span>
                <h2 class="fc-section-title">PREGUNTAS <span class="fc-gradient-text">FRECUENTES</span></h2>
            </div>

            <div class="fc-faq-container mx-auto" style="max-width: 850px;">
                <div class="fc-faq-item">
                    <button class="fc-faq-question">
                        <span>¿Qué es Futuros Compartidos?</span>
                        <i class="bi bi-plus-lg fc-faq-icon"></i>
                    </button>
                    <div class="fc-faq-answer">
                        <div class="fc-faq-content">
                            <p>Es una plataforma colaborativa donde cualquier persona puede compartir imágenes, videos o
                                relatos que representen su visión del mundo dentro de 50 años. Nuestro objetivo es crear
                                un archivo vivo de imaginación colectiva sobre el porvenir.</p>
                        </div>
                    </div>
                </div>

                <div class="fc-faq-item">
                    <button class="fc-faq-question">
                        <span>¿Qué tipo de contenido puedo subir?</span>
                        <i class="bi bi-plus-lg fc-faq-icon"></i>
                    </button>
                    <div class="fc-faq-answer">
                        <div class="fc-faq-content">
                            <p>Puedes compartir imágenes, videos y relatos escritos que representen tu visión del futuro
                                dentro de 50 años. No hay límites temáticos: pueden ser ciudades, formas de vida,
                                tecnología, naturaleza, utopías, distopías o reflexiones personales. Buscamos miradas
                                diversas y creativas sobre el mañana.</p>
                        </div>
                    </div>
                </div>

                <div class="fc-faq-item">
                    <button class="fc-faq-question">
                        <span>¿Necesito ser artista profesional para participar?</span>
                        <i class="bi bi-plus-lg fc-faq-icon"></i>
                    </button>
                    <div class="fc-faq-answer">
                        <div class="fc-faq-content">
                            <p>No. Este museo está abierto a todas las personas, sin importar su formación o experiencia
                                artística. Valoramos tanto la creatividad profesional como las ideas espontáneas y
                                personales. Lo importante es tu visión del futuro.</p>
                        </div>
                    </div>
                </div>

                <div class="fc-faq-item">
                    <button class="fc-faq-question">
                        <span>¿Hay algún requisito técnico para los archivos?</span>
                        <i class="bi bi-plus-lg fc-faq-icon"></i>
                    </button>
                    <div class="fc-faq-answer">
                        <div class="fc-faq-content">
                            <p>Sí, para asegurar una buena experiencia en la plataforma: <br>
                                <b>Imágenes:</b> formatos JPG o PNG, buena resolución. <br>
                                <b>Videos:</b> formatos MP4 o similares, duración moderada. <br>
                                <b>Relatos:</b> texto digital, con una extensión razonable.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="fc-faq-item">
                    <button class="fc-faq-question">
                        <span>¿Mi contenido pasará por revisión?</span>
                        <i class="bi bi-plus-lg fc-faq-icon"></i>
                    </button>
                    <div class="fc-faq-answer">
                        <div class="fc-faq-content">
                            <p>Sí. Todo el contenido es revisado antes de publicarse para garantizar que respete las
                                normas de convivencia, derechos de autor y el espíritu del museo. No se evalúa la
                                calidad artística, sino el cumplimiento de estas pautas.</p>
                        </div>
                    </div>
                </div>

                <div class="fc-faq-item">
                    <button class="fc-faq-question">
                        <span>¿Conservo los derechos de autor de mi obra?</span>
                        <i class="bi bi-plus-lg fc-faq-icon"></i>
                    </button>
                    <div class="fc-faq-answer">
                        <div class="fc-faq-content">
                            <p>Sí. Siempre conservas la autoría y los derechos sobre tu obra. Al subir contenido, solo
                                nos autorizas a exhibirlo dentro del museo virtual y a difundirlo en el marco del
                                proyecto, siempre con el debido crédito.</p>
                        </div>
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
                    <p class="fc-text-muted small pe-lg-5">Documentando la imaginación colectiva para construir el mundo
                        del mañana.</p>
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
