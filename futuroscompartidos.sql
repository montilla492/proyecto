-- =====================================================================
-- BASE DE DATOS: Futuros Compartidos
-- Diseñada para demostración con Administrador único y gestión de obras
-- =====================================================================

-- Crear la base de datos si no existe (MySQL)
CREATE DATABASE IF NOT EXISTS futuros_compartidos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE futuros_compartidos;

-- ---------------------------------------------------------------------
-- TABLA: usuarios
-- Guarda la información del administrador y de posibles usuarios futuros
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Almacena la contraseña cifrada
    bio TEXT,
    avatar_url VARCHAR(255) DEFAULT 'img/ojo hero.gif',
    banner_url VARCHAR(255) DEFAULT 'img/gift ciudad.gif',
    rol VARCHAR(20) DEFAULT 'admin', -- 'admin' o 'usuario'
    instagram VARCHAR(100),
    tiktok VARCHAR(100),
    twitter VARCHAR(100),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- TABLA: obras
-- Almacena las obras subidas por el administrador (Imágenes, Videos o Relatos)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS obras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    tipo ENUM('imagen', 'video', 'relato') NOT NULL,
    archivo_url VARCHAR(255), -- Ruta de archivo para Imágenes y Videos (ej. 'img/fotofut3.png')
    contenido_relato TEXT,    -- Cuerpo del relato (solo se usa si tipo = 'relato')
    etiquetas VARCHAR(255),   -- Tags separados por comas
    anio_proyectado INT DEFAULT 2076, -- Año futurista que representa la obra
    likes INT DEFAULT 0,
    visitas INT DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- =====================================================================
-- INSERCIÓN DE DATOS DE PRUEBA (SEEDERS)
-- =====================================================================

-- 1. Insertar el Administrador por defecto (David Montilla)
-- Contraseña original: admin1234
-- Cifrada con Bcrypt (Algoritmo standard $2y$ con coste 10 para PHP/Node.js)
INSERT INTO usuarios (nombre, usuario, email, password, bio, rol, instagram, tiktok, twitter) 
VALUES (
    'David Montilla', 
    'montilla492', 
    'david.montilla@ejemplo.com', 
    '$2y$10$Qj28V6q1Z/o7D9kQv/kKdeq7H2bF6wG3sE3P.rOpe8tK5W4lZtL1q', -- Hash Bcrypt de 'admin1234'
    'Curioso por el futuro, la ciencia y las posibilidades que aún están por descubrirse en el arte digital.', 
    'admin',
    '@montilla_insta',
    '@montilla_tok',
    '@montilla492'
) ON DUPLICATE KEY UPDATE id=id;


-- 2. La base de datos arranca sin obras.
--    El administrador puede subir obras desde su Panel de Control (dashboard.php)
--    y aparecerán automáticamente en el Explorador (explorar.php) y en el Inicio (index.php).
