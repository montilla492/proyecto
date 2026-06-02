<?php
require_once "init.php";
require_once "conexion.php";


if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$session_usuario = $_SESSION['usuario'];
$session_id_usuario = $_SESSION['usuario_id'];
$mensaje_exito = "";
$mensaje_error = "";

// Obtener datos del usuario
try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $session_id_usuario]);
    $perfil_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje_error = "Error al obtener perfil: " . $e->getMessage();
}

// Eliminar obra
if (isset($_POST['eliminar_obra'])) {
    $obra_id = $_POST['id'] ?? '';
    if ($obra_id !== '') {
        try {
            
            $stmt = $pdo->prepare("SELECT archivo_url FROM obras WHERE id = :id AND usuario_id = :uid");
            $stmt->execute([':id' => $obra_id, ':uid' => $session_id_usuario]);
            $obra = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($obra && !empty($obra['archivo_url']) && file_exists($obra['archivo_url'])) {
               
                if (strpos($obra['archivo_url'], 'img/fotofut') === false && strpos($obra['archivo_url'], 'img/glasses') === false && strpos($obra['archivo_url'], 'img/relatos') === false) {
                    unlink($obra['archivo_url']);
                }
            }

            
            $stmt = $pdo->prepare("DELETE FROM obras WHERE id = :id AND usuario_id = :uid");
            $stmt->execute([':id' => $obra_id, ':uid' => $session_id_usuario]);

            $mensaje_exito = "¡La obra ha sido eliminada correctamente!";
        } catch (PDOException $e) {
            $mensaje_error = "Error al eliminar la obra: " . $e->getMessage();
        }
    }
}

// Guardar obra (crear o editar)
if (isset($_POST['guardar_obra'])) {
    $obra_id = $_POST['id'] ?? '';
    $tipo = $_POST['tipo'] ?? 'imagen';
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $etiquetas = $_POST['etiquetas'] ?? '';
    $anio_proyectado = $_POST['anio_proyectado'] ?? 2076;
    $contenido_relato = $_POST['contenido_relato'] ?? '';

    // Manejo de carga de archivos (Imágenes y Videos)
    $archivo_db_path = null;
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK) {
        $nombre_archivo = time() . '_' . basename($_FILES['archivo']['name']);
        $directorio_destino = 'img/subidas/';
        
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        
        $ruta_final = $directorio_destino . $nombre_archivo;
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_final)) {
            $archivo_db_path = $ruta_final;
        }
    }

    if (empty($obra_id)) {
        // Nueva obra
        try {
            $sql = "INSERT INTO obras (usuario_id, titulo, descripcion, tipo, archivo_url, contenido_relato, etiquetas, anio_proyectado) 
                    VALUES (:usuario_id, :titulo, :descripcion, :tipo, :archivo_url, :contenido_relato, :etiquetas, :anio_proyectado)";
            
            
            if ($tipo == 'relato' && empty($archivo_db_path)) {
                $archivo_db_path = 'img/relatos.png';
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':usuario_id' => $session_id_usuario,
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':tipo' => $tipo,
                ':archivo_url' => $archivo_db_path,
                ':contenido_relato' => ($tipo == 'relato') ? $contenido_relato : null,
                ':etiquetas' => $etiquetas,
                ':anio_proyectado' => $anio_proyectado
            ]);

            $mensaje_exito = "¡Nueva obra publicada con éxito!";
        } catch (PDOException $e) {
            $mensaje_error = "Error al crear la obra: " . $e->getMessage();
        }
    } else {
        // Editar obra existente
        try {
            if ($archivo_db_path) {
                
                $stmt = $pdo->prepare("SELECT archivo_url FROM obras WHERE id = :id AND usuario_id = :uid");
                $stmt->execute([':id' => $obra_id, ':uid' => $session_id_usuario]);
                $vieja_obra = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($vieja_obra && !empty($vieja_obra['archivo_url']) && file_exists($vieja_obra['archivo_url'])) {
                    if (strpos($vieja_obra['archivo_url'], 'img/fotofut') === false && strpos($vieja_obra['archivo_url'], 'img/glasses') === false && strpos($vieja_obra['archivo_url'], 'img/relatos') === false) {
                        unlink($vieja_obra['archivo_url']);
                    }
                }

                $sql = "UPDATE obras SET titulo = :titulo, descripcion = :descripcion, tipo = :tipo, 
                        archivo_url = :archivo_url, contenido_relato = :contenido_relato, etiquetas = :etiquetas, 
                        anio_proyectado = :anio_proyectado WHERE id = :id AND usuario_id = :uid";
                $params = [
                    ':titulo' => $titulo,
                    ':descripcion' => $descripcion,
                    ':tipo' => $tipo,
                    ':archivo_url' => $archivo_db_path,
                    ':contenido_relato' => ($tipo == 'relato') ? $contenido_relato : null,
                    ':etiquetas' => $etiquetas,
                    ':anio_proyectado' => $anio_proyectado,
                    ':id' => $obra_id,
                    ':uid' => $session_id_usuario
                ];
            } else {
                $sql = "UPDATE obras SET titulo = :titulo, descripcion = :descripcion, tipo = :tipo, 
                        contenido_relato = :contenido_relato, etiquetas = :etiquetas, 
                        anio_proyectado = :anio_proyectado WHERE id = :id AND usuario_id = :uid";
                $params = [
                    ':titulo' => $titulo,
                    ':descripcion' => $descripcion,
                    ':tipo' => $tipo,
                    ':contenido_relato' => ($tipo == 'relato') ? $contenido_relato : null,
                    ':etiquetas' => $etiquetas,
                    ':anio_proyectado' => $anio_proyectado,
                    ':id' => $obra_id,
                    ':uid' => $session_id_usuario
                ];
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            $mensaje_exito = "¡La obra ha sido actualizada con éxito!";
        } catch (PDOException $e) {
            $mensaje_error = "Error al actualizar la obra: " . $e->getMessage();
        }
    }
}

// Guardar cambios de perfil
if (isset($_POST['guardar_perfil'])) {
    $nombre = $_POST['nombre'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $email = $_POST['email'] ?? '';
    $password_nueva = $_POST['password'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $instagram = $_POST['instagram'] ?? '';
    $tiktok = $_POST['tiktok'] ?? '';
    $twitter = $_POST['twitter'] ?? '';

    try {
        // Validar si el usuario o el email ya están en uso por otro ID
        $check_stmt = $pdo->prepare("SELECT id FROM usuarios WHERE (usuario = :usuario OR email = :email) AND id != :id");
        $check_stmt->execute([':usuario' => $usuario, ':email' => $email, ':id' => $session_id_usuario]);
        
        if ($check_stmt->rowCount() > 0) {
            $mensaje_error = "El nombre de usuario o correo electrónico ya están siendo utilizados.";
        } else {
            // Manejo de avatar y banner
            $avatar_path = $perfil_usuario['avatar_url'];
            $banner_path = $perfil_usuario['banner_url'];

            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
                $nombre_avatar = 'avatar_' . time() . '_' . basename($_FILES['avatar']['name']);
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], 'img/' . $nombre_avatar)) {
                    $avatar_path = 'img/' . $nombre_avatar;
                }
            }

            if (isset($_FILES['banner']) && $_FILES['banner']['error'] == UPLOAD_ERR_OK) {
                $nombre_banner = 'banner_' . time() . '_' . basename($_FILES['banner']['name']);
                if (move_uploaded_file($_FILES['banner']['tmp_name'], 'img/' . $nombre_banner)) {
                    $banner_path = 'img/' . $nombre_banner;
                }
            }

            // Actualizar contraseña si se proporciona una nueva
            if ($password_nueva !== '') {
                $password_hash = password_hash($password_nueva, PASSWORD_BCRYPT);
                $sql = "UPDATE usuarios SET nombre = :nombre, usuario = :usuario, email = :email, password = :password,
                        bio = :bio, avatar_url = :avatar_url, banner_url = :banner_url, instagram = :instagram,
                        tiktok = :tiktok, twitter = :twitter WHERE id = :id";
                $params = [
                    ':nombre' => $nombre,
                    ':usuario' => $usuario,
                    ':email' => $email,
                    ':password' => $password_hash,
                    ':bio' => $bio,
                    ':avatar_url' => $avatar_path,
                    ':banner_url' => $banner_path,
                    ':instagram' => $instagram,
                    ':tiktok' => $tiktok,
                    ':twitter' => $twitter,
                    ':id' => $session_id_usuario
                ];
            } else {
                $sql = "UPDATE usuarios SET nombre = :nombre, usuario = :usuario, email = :email,
                        bio = :bio, avatar_url = :avatar_url, banner_url = :banner_url, instagram = :instagram,
                        tiktok = :tiktok, twitter = :twitter WHERE id = :id";
                $params = [
                    ':nombre' => $nombre,
                    ':usuario' => $usuario,
                    ':email' => $email,
                    ':bio' => $bio,
                    ':avatar_url' => $avatar_path,
                    ':banner_url' => $banner_path,
                    ':instagram' => $instagram,
                    ':tiktok' => $tiktok,
                    ':twitter' => $twitter,
                    ':id' => $session_id_usuario
                ];
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            // Actualizar variables de sesión y perfil local
            $_SESSION['usuario'] = $usuario;
            $mensaje_exito = "¡Perfil actualizado correctamente!";

            // Recargar datos actualizados
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
            $stmt->execute([':id' => $session_id_usuario]);
            $perfil_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $mensaje_error = "Error al actualizar perfil: " . $e->getMessage();
    }
}

// Cargar obra para editar si se pasa ?edit=ID
$obra_edit = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'] ?? '';
    if ($edit_id !== '') {
        try {
            $stmt = $pdo->prepare("SELECT * FROM obras WHERE id = :id AND usuario_id = :uid");
            $stmt->execute([':id' => $edit_id, ':uid' => $session_id_usuario]);
            $obra_edit = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $mensaje_error = "Error al obtener la obra para editar: " . $e->getMessage();
        }
    }
}

// Obtener obras del usuario y métricas
$obras_imagenes = [];
$obras_videos = [];
$obras_relatos = [];
$total_obras = 0;
$total_likes = 0;
$total_visitas = 0;

try {
    // Listar obras del usuario
    $stmt = $pdo->prepare("SELECT * FROM obras WHERE usuario_id = :uid ORDER BY id DESC");
    $stmt->execute([':uid' => $session_id_usuario]);
    $todas_las_obras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($todas_las_obras as $o) {
        $total_obras++;
        $total_likes += $o['likes'];
        $total_visitas += $o['visitas'];

        // Usamos 'tipo' que es el nombre real de la columna en la BD
        if ($o['tipo'] == 'imagen') {
            $obras_imagenes[] = $o;
        } elseif ($o['tipo'] == 'video') {
            $obras_videos[] = $o;
        } elseif ($o['tipo'] == 'relato') {
            $obras_relatos[] = $o;
        }
    }
} catch (PDOException $e) {
    $mensaje_error = "Error al listar las obras: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Control - Futuros Compartidos</title>

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
            <li class="nav-item"><a class="nav-link fc-nav-link" href="explorar.php">Explorar</a></li>
            <li class="nav-item"><a class="nav-link fc-nav-link" href="blog.php">Blog/Eventos</a></li>
            <li class="nav-item"><a class="nav-link fc-nav-link" href="contacto.php">Contacto</a></li>
          </ul>
          <div class="fc-nav-actions d-flex align-items-center gap-2">
            <a href="dashboard.php" class="fc-icon-btn active" style="color:#fff; border-color: var(--fc-purple); background: var(--fc-gradient-subtle);" title="Panel de Control"><i class="bi bi-speedometer2"></i></a>
            <a href="logout.php" class="fc-icon-btn" title="Cerrar Sesión"><i class="bi bi-box-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- Banner de perfil -->
  <section class="container mt-4">
    <div class="fc-db-banner" style="background: linear-gradient(rgba(10, 10, 20, 0.2), rgba(10, 10, 20, 0.95)), url('<?php echo htmlspecialchars($perfil_usuario['banner_url']); ?>') center/cover no-repeat;">
      
      <button class="fc-icon-btn fc-db-banner-settings" onclick="activateTab('ajustes-tab')" title="Ajustes de Perfil">
        <i class="bi bi-gear-fill"></i>
      </button>

      <!-- Ficha de usuario superpuesta -->
      <div class="fc-db-profile-card">
        <div class="fc-db-avatar-wrap">
          <img src="<?php echo htmlspecialchars($perfil_usuario['avatar_url']); ?>" alt="Avatar" class="fc-db-avatar">
        </div>
        <div class="fc-db-user-info">
          <h2 class="fc-db-name"><?php echo htmlspecialchars($perfil_usuario['nombre']); ?></h2>
          <span class="fc-db-username"><?php echo htmlspecialchars($perfil_usuario['usuario']); ?></span>
          <p class="fc-db-bio"><?php echo htmlspecialchars($perfil_usuario['bio']); ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contenido principal -->
  <main class="container fc-db-content-wrapper">
    
   
    <?php if ($mensaje_exito != ""): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 mb-4 py-3" style="background: rgba(16, 185, 129, 0.15); color: #10B981; border-radius: var(--fc-radius);" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($mensaje_exito); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1);"></button>
        </div>
    <?php endif; ?>

    <?php if ($mensaje_error != ""): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-4 py-3" style="background: rgba(244, 63, 94, 0.15); color: #F43F5E; border-radius: var(--fc-radius);" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($mensaje_error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1);"></button>
        </div>
    <?php endif; ?>
    
    <!-- 1. BLOQUE  -->
    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="fc-db-stat-card">
          <div class="fc-db-stat-icon"><i class="bi bi-images"></i></div>
          <div class="fc-db-stat-number"><?php echo $total_obras; ?></div>
          <div class="fc-db-stat-label">Obras Publicadas</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="fc-db-stat-card">
          <div class="fc-db-stat-icon"><i class="bi bi-heart-fill"></i></div>
          <div class="fc-db-stat-number"><?php echo number_format($total_likes); ?></div>
          <div class="fc-db-stat-label">Likes Recibidos</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="fc-db-stat-card">
          <div class="fc-db-stat-icon"><i class="bi bi-eye-fill"></i></div>
          <div class="fc-db-stat-number"><?php echo number_format($total_visitas); ?></div>
          <div class="fc-db-stat-label">Visitas Totales</div>
        </div>
      </div>
    </div>

    <!-- 2. NAVEGACIÓN PRINCIPAL DEL PANEL (Pestañas de control) -->
    <div class="row g-4">
      <div class="col-lg-3">
        <div class="nav flex-column nav-pills fc-db-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <button class="nav-link fc-db-nav-link active" id="obras-tab" data-bs-toggle="pill" data-bs-target="#v-pills-obras" type="button" role="tab" aria-controls="v-pills-obras" aria-selected="true">
            <i class="bi bi-palette"></i> Mis Obras
          </button>
          <button class="nav-link fc-db-nav-link" id="subir-tab" data-bs-toggle="pill" data-bs-target="#v-pills-subir" type="button" role="tab" aria-controls="v-pills-subir" aria-selected="false">
            <i class="bi bi-cloud-arrow-up"></i> <?php echo $obra_edit ? 'Editar Obra' : 'Subir Nueva Obra'; ?>
          </button>
          <button class="nav-link fc-db-nav-link" id="stats-tab" data-bs-toggle="pill" data-bs-target="#v-pills-stats" type="button" role="tab" aria-controls="v-pills-stats" aria-selected="false">
            <i class="bi bi-graph-up-arrow"></i> Estadísticas
          </button>
          <button class="nav-link fc-db-nav-link" id="ajustes-tab" data-bs-toggle="pill" data-bs-target="#v-pills-ajustes" type="button" role="tab" aria-controls="v-pills-ajustes" aria-selected="false">
            <i class="bi bi-person-gear"></i> Ajustes de Perfil
          </button>
        </div>
      </div>

      <!-- 3. CONTENEDOR DE CONTENIDOS -->
      <div class="col-lg-9">
        <div class="tab-content" id="v-pills-tabContent">
          
          <!-- Pestaña: Mis obras -->
          <div class="tab-pane fade show active" id="v-pills-obras" role="tabpanel" aria-labelledby="obras-tab">
            <div class="fc-db-card">
              <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h3 class="m-0 font-heading" style="font-family: var(--fc-font-heading); font-weight: 700;">
                  <i class="bi bi-folder-symlink fc-gradient-text"></i> Galería Personal
                </h3>
                <button class="fc-btn-primary" onclick="activateTab('subir-tab')">
                  <i class="bi bi-plus-lg"></i> NUEVA OBRA
                </button>
              </div>

              <!-- Pestañas: Imagenes, Videos, Relatos -->
              <ul class="nav fc-db-subtabs" id="gallerySubtabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="fc-db-subtab-btn active" id="subtab-img-btn" data-bs-toggle="tab" data-bs-target="#subtab-img" type="button" role="tab" aria-controls="subtab-img" aria-selected="true">
                    <i class="bi bi-image"></i> Imágenes (<?php echo count($obras_imagenes); ?>)
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="fc-db-subtab-btn" id="subtab-vid-btn" data-bs-toggle="tab" data-bs-target="#subtab-vid" type="button" role="tab" aria-controls="subtab-vid" aria-selected="false">
                    <i class="bi bi-camera-video"></i> Videos (<?php echo count($obras_videos); ?>)
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="fc-db-subtab-btn" id="subtab-rel-btn" data-bs-toggle="tab" data-bs-target="#subtab-rel" type="button" role="tab" aria-controls="subtab-rel" aria-selected="false">
                    <i class="bi bi-justify-left"></i> Relatos (<?php echo count($obras_relatos); ?>)
                  </button>
                </li>
              </ul>

              <!-- Contenido de las sub-pestañas -->
              <div class="tab-content" id="gallerySubtabsContent">
                
                <!-- Sub-pestaña IMÁGENES -->
                <div class="tab-pane fade show active" id="subtab-img" role="tabpanel" aria-labelledby="subtab-img-btn">
                  <div class="row g-4">
                    <?php if (count($obras_imagenes) > 0): ?>
                        <?php foreach ($obras_imagenes as $img): ?>
                            <div class="col-md-6 col-xl-4">
                              <div class="fc-obra-card">
                                <div class="fc-obra-img">
                                  <span class="fc-obra-badge">IMAGEN</span>
                                  <img src="<?php echo htmlspecialchars($img['archivo_url']); ?>" alt="<?php echo htmlspecialchars($img['titulo']); ?>" class="fc-img-fluid">
                                </div>
                                <div class="fc-obra-info">
                                  <h4 class="fc-obra-title fs-6"><?php echo htmlspecialchars($img['titulo']); ?></h4>
                                  <span class="fc-obra-author">Año Proyectado: <?php echo htmlspecialchars($img['anio_proyectado']); ?></span>
                                  <div class="d-flex gap-2 justify-content-end mt-2 pt-2 border-top border-secondary">
                                    <a href="dashboard.php?edit=<?php echo $img['id']; ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-pencil"></i></a>
                                    <form action="dashboard.php" method="POST" onsubmit="return confirm('¿Eliminar esta imagen de tu galería?');" class="d-inline m-0 p-0">
                                        <input type="hidden" name="id" value="<?php echo $img['id']; ?>">
                                        <button type="submit" name="eliminar_obra" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center text-secondary py-5">
                            <i class="bi bi-image fs-1 mb-2"></i>
                            <p class="m-0 italic text-secondary">No has subido ninguna imagen todavía.</p>
                        </div>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Sub-pestaña VIDEOS -->
                <div class="tab-pane fade" id="subtab-vid" role="tabpanel" aria-labelledby="subtab-vid-btn">
                  <div class="row g-4">
                    <?php if (count($obras_videos) > 0): ?>
                        <?php foreach ($obras_videos as $vid): ?>
                            <div class="col-md-6 col-xl-4">
                              <div class="fc-obra-card">
                                <div class="fc-obra-img">
                                  <span class="fc-obra-badge">VIDEO</span>
                                  <img src="<?php echo htmlspecialchars($vid['archivo_url']); ?>" alt="<?php echo htmlspecialchars($vid['titulo']); ?>" class="fc-img-fluid">
                                </div>
                                <div class="fc-obra-info">
                                  <h4 class="fc-obra-title fs-6"><?php echo htmlspecialchars($vid['titulo']); ?></h4>
                                  <span class="fc-obra-author">Año Proyectado: <?php echo htmlspecialchars($vid['anio_proyectado']); ?></span>
                                  <div class="d-flex gap-2 justify-content-end mt-2 pt-2 border-top border-secondary">
                                    <a href="dashboard.php?edit=<?php echo $vid['id']; ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-pencil"></i></a>
                                    <form action="dashboard.php" method="POST" onsubmit="return confirm('¿Eliminar este video de tu galería?');" class="d-inline m-0 p-0">
                                        <input type="hidden" name="id" value="<?php echo $vid['id']; ?>">
                                        <button type="submit" name="eliminar_obra" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center text-secondary py-5">
                            <i class="bi bi-camera-video fs-1 mb-2"></i>
                            <p class="m-0 italic text-secondary">No has subido ningún vídeo todavía.</p>
                        </div>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Sub-pestaña RELATOS -->
                <div class="tab-pane fade" id="subtab-rel" role="tabpanel" aria-labelledby="subtab-rel-btn">
                  <div class="row g-4">
                    <?php if (count($obras_relatos) > 0): ?>
                        <?php foreach ($obras_relatos as $rel): ?>
                            <div class="col-md-6">
                              <div class="fc-obra-card">
                                <div class="fc-obra-img" style="aspect-ratio: 16/7;">
                                  <span class="fc-obra-badge">RELATO</span>
                                  <img src="<?php echo htmlspecialchars($rel['archivo_url']); ?>" alt="<?php echo htmlspecialchars($rel['titulo']); ?>" class="fc-img-fluid">
                                </div>
                                <div class="fc-obra-info">
                                  <h4 class="fc-obra-title fs-5"><?php echo htmlspecialchars($rel['titulo']); ?></h4>
                                  <span class="fc-obra-author">Año Proyectado: <?php echo htmlspecialchars($rel['anio_proyectado']); ?></span>
                                  <p class="fc-text-muted small mt-2"><?php echo htmlspecialchars(substr($rel['contenido_relato'], 0, 140)) . '...'; ?></p>
                                  <div class="d-flex gap-2 justify-content-end mt-2 pt-2 border-top border-secondary">
                                    <a href="dashboard.php?edit=<?php echo $rel['id']; ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-pencil"></i></a>
                                    <form action="dashboard.php" method="POST" onsubmit="return confirm('¿Eliminar este relato de tu galería?');" class="d-inline m-0 p-0">
                                        <input type="hidden" name="id" value="<?php echo $rel['id']; ?>">
                                        <button type="submit" name="eliminar_obra" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center text-secondary py-5">
                            <i class="bi bi-justify-left fs-1 mb-2"></i>
                            <p class="m-0 italic text-secondary">No has escrito ningún relato todavía.</p>
                        </div>
                    <?php endif; ?>
                  </div>
                </div>

              </div>

            </div>
          </div>

          <!-- Pestaña: Subir / editar obra -->
          <div class="tab-pane fade" id="v-pills-subir" role="tabpanel" aria-labelledby="subir-tab">
            <div class="fc-db-card">
              <h3 class="fc-db-card-title">
                  <i class="bi bi-plus-circle-fill"></i> 
                  <?php echo $obra_edit ? 'Editar Obra Existente' : 'Crear Nueva Obra'; ?>
              </h3>
              
              <form action="dashboard.php" method="POST" enctype="multipart/form-data" id="uploadWorkForm">
                <!-- ID oculto para edición -->
                <input type="hidden" name="id" value="<?php echo $obra_edit ? $obra_edit['id'] : ''; ?>">

                <div class="row g-4">
                  
                  <!-- Tipo de obra -->
                  <div class="col-md-6">
                    <label class="fc-form-label">Tipo de Obra</label>
                    <select class="form-select fc-form-input" name="tipo" id="obraTypeSelect" onchange="toggleUploadInputs()">
                      <option value="imagen" <?php if ($obra_edit && $obra_edit['tipo'] == 'imagen') echo 'selected'; ?>>Imagen Ilustrativa</option>
                      <option value="video" <?php if ($obra_edit && $obra_edit['tipo'] == 'video') echo 'selected'; ?>>Vídeo Animado</option>
                      <option value="relato" <?php if ($obra_edit && $obra_edit['tipo'] == 'relato') echo 'selected'; ?>>Relato Escrito</option>
                    </select>
                  </div>

                  <!-- Título de la obra -->
                  <div class="col-md-6">
                    <label class="fc-form-label" for="obraTitle">Título</label>
                    <input type="text" class="fc-form-input" name="titulo" id="obraTitle" placeholder="Ej. Ecosistema Virtual 2088" required value="<?php echo $obra_edit ? htmlspecialchars($obra_edit['titulo']) : ''; ?>">
                  </div>

                  <!-- Descripción -->
                  <div class="col-12">
                    <label class="fc-form-label" for="obraDesc">Descripción / Concepto del Futuro</label>
                    <textarea class="fc-form-input" name="descripcion" id="obraDesc" rows="4" placeholder="Describe cómo esta obra refleja nuestra vida en 50 años..." required><?php echo $obra_edit ? htmlspecialchars($obra_edit['descripcion']) : ''; ?></textarea>
                  </div>

                  <!-- Secciones condicionales por tipo de obra -->
                  <!-- Campo multimedia (Imagen / Video) -->
                  <div class="col-12" id="mediaUploadContainer">
                    <label class="fc-form-label">Archivo Multimedia</label>
                    <div class="fc-upload-dropzone" onclick="document.getElementById('fileUploadInput').click()">
                      <i class="bi bi-cloud-arrow-up-fill"></i>
                      <h5 class="fs-6 m-0">Arrastra tu archivo aquí o haz clic para buscar</h5>
                      <p class="fc-text-muted small mt-1 mb-0">Formatos permitidos: JPG, PNG, GIF, MP4 (Máximo 25MB)</p>
                      <input type="file" name="archivo" id="fileUploadInput" class="d-none">
                    </div>
                    <?php if ($obra_edit && !empty($obra_edit['archivo_url'])): ?>
                        <p class="text-xs text-secondary mt-2">Archivo actual: <?php echo htmlspecialchars($obra_edit['archivo_url']); ?></p>
                    <?php endif; ?>
                  </div>

                  <!-- Campo de texto (Solo para Relatos) -->
                  <div class="col-12 d-none" id="textRelatoContainer">
                    <label class="fc-form-label" for="relatoText">Cuerpo del Relato</label>
                    <textarea class="fc-form-input" name="contenido_relato" id="relatoText" rows="10" placeholder="Escribe aquí tu relato de ciencia ficción o visión de futuro..."><?php echo $obra_edit ? htmlspecialchars($obra_edit['contenido_relato']) : ''; ?></textarea>
                  </div>

                  <!-- Categoría o etiquetas -->
                  <div class="col-md-6">
                    <label class="fc-form-label" for="obraCategory">Etiquetas / Tags (Separadas por comas)</label>
                    <input type="text" class="fc-form-input" name="etiquetas" id="obraCategory" placeholder="Ej. ciberpunk, IA, ecologia" value="<?php echo $obra_edit ? htmlspecialchars($obra_edit['etiquetas']) : ''; ?>">
                  </div>

                  <!-- Año del futuro proyectado -->
                  <div class="col-md-6">
                    <label class="fc-form-label" for="obraYear">Año Proyectado del Futuro</label>
                    <input type="number" class="fc-form-input" name="anio_proyectado" id="obraYear" placeholder="Ej. 2076" min="2026" max="2200" value="<?php echo $obra_edit ? htmlspecialchars($obra_edit['anio_proyectado']) : '2076'; ?>">
                  </div>

                  <!-- Botones de Acción -->
                  <div class="col-12 pt-3 border-top border-secondary d-flex gap-3">
                    <button type="submit" name="guardar_obra" class="fc-btn-primary px-5">PUBLICAR OBRA</button>
                    <?php if ($obra_edit): ?>
                        <a href="dashboard.php" class="btn btn-outline-light px-4 py-2">CANCELAR EDICIÓN</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-light px-4" onclick="activateTab('obras-tab')">CANCELAR</button>
                    <?php endif; ?>
                  </div>

                </div>
              </form>
            </div>
          </div>

          <!-- Pestaña: Estadísticas -->
          <div class="tab-pane fade" id="v-pills-stats" role="tabpanel" aria-labelledby="stats-tab">
            <div class="fc-db-card">
              <h3 class="fc-db-card-title"><i class="bi bi-graph-up-arrow"></i> Estadísticas Detalladas</h3>
              
              <div class="row g-4">
                <!-- Gráfico de Visitas -->
                <div class="col-md-7">
                  <div class="p-4 rounded-3" style="background: var(--fc-surface); border: 1px solid var(--fc-border);">
                    <h5 class="fs-6 mb-4 text-uppercase fw-bold" style="font-family: var(--fc-font-display); color: var(--fc-purple-light);">Visitas mensuales (Último semestre)</h5>
                    <div class="fc-db-chart-bar-wrap">
                      <div class="fc-db-chart-bar-col">
                        <div class="fc-db-chart-bar fc-db-chart-bar-cyan" style="height: 60px;"><span class="fc-db-chart-val">450</span></div>
                        <div class="fc-db-chart-label">Dic</div>
                      </div>
                      <div class="fc-db-chart-bar-col">
                        <div class="fc-db-chart-bar fc-db-chart-bar-cyan" style="height: 90px;"><span class="fc-db-chart-val">720</span></div>
                        <div class="fc-db-chart-label">Ene</div>
                      </div>
                      <div class="fc-db-chart-bar-col">
                        <div class="fc-db-chart-bar fc-db-chart-bar-cyan" style="height: 140px;"><span class="fc-db-chart-val">1.2K</span></div>
                        <div class="fc-db-chart-label">Feb</div>
                      </div>
                      <div class="fc-db-chart-bar-col">
                        <div class="fc-db-chart-bar fc-db-chart-bar-cyan" style="height: 110px;"><span class="fc-db-chart-val">980</span></div>
                        <div class="fc-db-chart-label">Mar</div>
                      </div>
                      <div class="fc-db-chart-bar-col">
                        <div class="fc-db-chart-bar fc-db-chart-bar-cyan" style="height: 160px;"><span class="fc-db-chart-val">1.6K</span></div>
                        <div class="fc-db-chart-label">Abr</div>
                      </div>
                      <div class="fc-db-chart-bar-col">
                        <div class="fc-db-chart-bar fc-db-chart-bar-cyan" style="height: 180px;"><span class="fc-db-chart-val">2.1K</span></div>
                        <div class="fc-db-chart-label">May</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Resumen en cifras circulares/porcentajes -->
                <div class="col-md-5">
                  <div class="p-4 rounded-3 h-100" style="background: var(--fc-surface); border: 1px solid var(--fc-border); display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                      <h5 class="fs-6 mb-3 text-uppercase fw-bold" style="font-family: var(--fc-font-display); color: var(--fc-purple-light);">Rendimiento Medio</h5>
                      <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary pb-2">
                        <span class="small text-secondary">Tasa de Interacción</span>
                        <span class="fw-bold text-success">+15.8%</span>
                      </div>
                      <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary pb-2">
                        <span class="small text-secondary">Likes / Obra Promedio</span>
                        <span class="fw-bold"><?php echo $total_obras > 0 ? round($total_likes / $total_obras, 1) : 0; ?></span>
                      </div>
                      <div class="d-flex align-items-center justify-content-between">
                        <span class="small text-secondary">Seguidores Ganados</span>
                        <span class="fw-bold text-info">+240</span>
                      </div>
                    </div>
                    <div class="pt-3">
                      <div class="text-center p-3 rounded-2" style="background: rgba(139, 92, 246, 0.05); border: 1px solid rgba(139, 92, 246, 0.1);">
                        <i class="bi bi-trophy-fill fs-4 text-warning"></i>
                        <h6 class="mt-2 mb-1" style="font-family: var(--fc-font-heading);">Obra Más Popular</h6>
                        <p class="text-secondary small m-0">"Ciudad Submarina 2075"</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Tabla de Obras más populares -->
                <div class="col-12 mt-3">
                  <h4 class="fs-6 text-uppercase fw-bold mb-3" style="font-family: var(--fc-font-display); color: var(--fc-purple-light);">Obras Registradas en la base de datos</h4>
                  <div class="table-responsive">
                    <table class="table fc-db-table">
                      <thead>
                        <tr>
                          <th>Obra</th>
                          <th>Tipo</th>
                          <th>Año Proyectado</th>
                          <th>Visitas</th>
                          <th>Likes</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (count($todas_las_obras) > 0): ?>
                            <?php foreach ($todas_las_obras as $o): ?>
                                <tr>
                                  <td>
                                    <div class="d-flex align-items-center gap-2">
                                      <img src="<?php echo htmlspecialchars($o['archivo_url']); ?>" class="fc-db-thumb" alt="<?php echo htmlspecialchars($o['titulo']); ?>">
                                      <span><?php echo htmlspecialchars($o['titulo']); ?></span>
                                    </div>
                                  </td>
                                  <td>
                                      <?php if ($o['tipo'] == 'imagen'): ?>
                                          <span class="badge bg-primary">Imagen</span>
                                      <?php elseif ($o['tipo'] == 'video'): ?>
                                          <span class="badge bg-success">Video</span>
                                      <?php else: ?>
                                          <span class="badge bg-warning text-dark">Relato</span>
                                      <?php endif; ?>
                                  </td>
                                  <td class="text-center"><?php echo htmlspecialchars($o['anio_proyectado']); ?></td>
                                  <td><?php echo number_format($o['visitas']); ?></td>
                                  <td><?php echo number_format($o['likes']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="p-4 text-center italic text-secondary">No hay obras registradas en tu catálogo de base de datos.</td>
                            </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- Pestaña: Ajustes de perfil -->
          <div class="tab-pane fade" id="v-pills-ajustes" role="tabpanel" aria-labelledby="ajustes-tab">
            <div class="fc-db-card">
              <h3 class="fc-db-card-title"><i class="bi bi-person-gear"></i> Ajustes de Perfil</h3>
              
              <form action="dashboard.php" method="POST" enctype="multipart/form-data" id="profileSettingsForm">
                
                <!-- Datos básicos -->
                <h5 class="fs-6 text-uppercase fw-bold mb-3" style="font-family: var(--fc-font-display); color: var(--fc-purple-light);">Información Personal</h5>
                <div class="row g-4 mb-4">
                  <div class="col-md-6">
                    <label class="fc-form-label" for="profileName">Nombre en pantalla</label>
                    <input type="text" class="fc-form-input" name="nombre" id="profileName" value="<?php echo htmlspecialchars($perfil_usuario['nombre']); ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label class="fc-form-label" for="profileUsername">Nombre de usuario</label>
                    <input type="text" class="fc-form-input" name="usuario" id="profileUsername" value="<?php echo htmlspecialchars($perfil_usuario['usuario']); ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label class="fc-form-label" for="profileEmail">Correo Electrónico</label>
                    <input type="email" class="fc-form-input" name="email" id="profileEmail" value="<?php echo htmlspecialchars($perfil_usuario['email']); ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label class="fc-form-label" for="profilePassword">Contraseña Nueva (Dejar en blanco para mantener)</label>
                    <input type="password" class="fc-form-input" name="password" id="profilePassword" placeholder="••••••••">
                  </div>
                  <div class="col-12">
                    <label class="fc-form-label" for="profileBio">Sobre mí (Biografía corta)</label>
                    <textarea class="fc-form-input" name="bio" id="profileBio" rows="3"><?php echo htmlspecialchars($perfil_usuario['bio']); ?></textarea>
                  </div>
                </div>

                <!-- Aspecto visual -->
                <h5 class="fs-6 text-uppercase fw-bold mb-3 border-top border-secondary pt-4" style="font-family: var(--fc-font-display); color: var(--fc-purple-light);">Aspecto Visual</h5>
                <div class="row g-4 mb-4">
                  <div class="col-md-6">
                    <label class="fc-form-label">Foto de Perfil (Avatar)</label>
                    <input type="file" name="avatar" class="form-control fc-form-input">
                  </div>
                  <div class="col-md-6">
                    <label class="fc-form-label">Banner de Perfil</label>
                    <input type="file" name="banner" class="form-control fc-form-input">
                  </div>
                </div>

                <!-- Redes sociales -->
                <h5 class="fs-6 text-uppercase fw-bold mb-3 border-top border-secondary pt-4" style="font-family: var(--fc-font-display); color: var(--fc-purple-light);">Redes Sociales</h5>
                <div class="row g-4 mb-4">
                  <div class="col-md-4">
                    <label class="fc-form-label" for="socialInsta">Instagram</label>
                    <div class="input-group fc-db-input-group">
                      <span class="input-group-text fc-db-input-group-text"><i class="bi bi-instagram"></i></span>
                      <input type="text" class="form-control fc-db-input" name="instagram" id="socialInsta" value="<?php echo htmlspecialchars($perfil_usuario['instagram']); ?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label class="fc-form-label" for="socialTikTok">TikTok</label>
                    <div class="input-group fc-db-input-group">
                      <span class="input-group-text fc-db-input-group-text"><i class="bi bi-tiktok"></i></span>
                      <input type="text" class="form-control fc-db-input" name="tiktok" id="socialTikTok" value="<?php echo htmlspecialchars($perfil_usuario['tiktok']); ?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label class="fc-form-label" for="socialX">Twitter / X</label>
                    <div class="input-group fc-db-input-group">
                      <span class="input-group-text fc-db-input-group-text"><i class="bi bi-twitter-x"></i></span>
                      <input type="text" class="form-control fc-db-input" name="twitter" id="socialX" value="<?php echo htmlspecialchars($perfil_usuario['twitter']); ?>">
                    </div>
                  </div>
                </div>

                <!-- Botón guardar -->
                <div class="col-12 pt-3 border-top border-secondary">
                  <button type="submit" name="guardar_perfil" class="fc-btn-primary px-5">GUARDAR CONFIGURACIÓN</button>
                </div>

              </form>
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

  <?php if ($obra_edit): ?>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        activateTab('subir-tab');
        toggleUploadInputs();
      });
    </script>
  <?php endif; ?>
</body>

</html>
