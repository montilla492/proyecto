-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 02, 2026 at 10:47 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `futuros_compartidos`
--

-- --------------------------------------------------------

--
-- Table structure for table `obras`
--

CREATE TABLE `obras` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `titulo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('imagen','video','relato') COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contenido_relato` text COLLATE utf8mb4_unicode_ci,
  `etiquetas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anio_proyectado` int DEFAULT '2076',
  `likes` int DEFAULT '0',
  `visitas` int DEFAULT '0',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `obras`
--

INSERT INTO `obras` (`id`, `usuario_id`, `titulo`, `descripcion`, `tipo`, `archivo_url`, `contenido_relato`, `etiquetas`, `anio_proyectado`, `likes`, `visitas`, `creado_en`) VALUES
(2, 1, 'Planeta 2076', 'Como visionario, te presento esta imagen de nuestro planeta dentro de 50 años. En esta visión del futuro, la Tierra aparece rodeada por una avanzada red de infraestructura orbital y estaciones espaciales que conectan el globo. Incluso podemos ver los primeros signos de una presencia humana permanente en la Luna, con luces brillando en su superficie. Es un futuro de conectividad global y expansión hacia el cosmos.', 'imagen', 'img/subidas/1780434534_planeta1.png', NULL, '#planeta', 2076, 2, 3, '2026-06-02 21:08:54'),
(3, 1, 'Cicatrices del Progreso', 'La imagen presenta una visión distópica y sobrecogedora de una metrópolis costera devastada, donde la naturaleza empieza a reclamar su espacio entre las ruinas.\r\n\r\nDevastación y Colapso: El paisaje urbano está dominado por rascacielos semidestruidos, puentes colapsados y un enorme cráter en el centro que sugiere un evento catastrófico pasado. El humo aún emerge de algunos puntos en el horizonte.\r\n\r\nReclamación de la Naturaleza: La vegetación tropical y la maleza crecen sin control sobre las estructuras de hormigón abandonadas, creando un contraste directo entre la destrucción humana y la resiliencia de la vida vegetal.\r\n\r\nInundación Costera: El aumento del nivel del mar o el impacto del evento ha inundado gran parte de la infraestructura, dejando edificios parcialmente sumergidos en el agua.\r\n\r\nPerspectiva Celestial: En la parte superior, se aprecia una vista inusual del planeta Tierra y la Luna (con sus propias luces artificiales) bajo un cielo crepuscular y cargado de nubes densas, lo que añade una escala global y futurista a la tragedia local.', 'imagen', 'img/subidas/1780434776_planeta2.png', NULL, 'cicatrices', 2056, 1, 3, '2026-06-02 21:12:56'),
(4, 1, 'El Latido de las Dos Tierras', 'El relato describe el año 2060 como una era de coexistencia y reconstrucción, alejándose tanto de la utopía perfecta como del apocalipsis total. La humanidad ha logrado expandirse hacia el espacio, construyendo una avanzada red orbital y colonias lunares impulsadas por energía limpia y fusión nuclear. Sin embargo, en la superficie terrestre aún son visibles las cicatrices del \"Gran Colapso\" del pasado, con ciudades costeras inundadas y una naturaleza salvaje que ha reclamado el hormigón. En este escenario, la sociedad del futuro no busca dominar el planeta, sino integrar su tecnología de vanguardia con un respeto profundo y biológico por las ruinas y los nuevos ecosistemas de la Tierra.', 'relato', 'img/relatos.png', 'El aire en la Neo-Metrópolis del año 2060 ya no huele a azufre ni a combustión; huele a ozono y a lluvia húmeda sobre hojas modificadas genéticamente para absorber el triple de carbono. Desde mi ventana, el mundo se divide en dos realidades que conviven en un equilibrio tan fascinante como frágil.\r\n\r\nSi levanto la mirada hacia el cielo purificado, veo la Red Orbital. Es un cinturón parpadeante de estaciones espaciales y ascensores de carga que conectan nuestro planeta con las colonias mineras de la Luna. La humanidad, finalmente, extendió sus alas. La energía limpia abunda gracias a los reactores de fusión y a los campos de recolección solar en el espacio, permitiendo que las ciudades floten en un vals tecnológico de trenes de levitación magnética y hologramas que organizan el tráfico aéreo. Hemos tocado las estrellas.\r\n\r\nSin embargo, si miro hacia el suelo, la Tierra lleva las cicatrices de su propia historia.\r\n\r\nAbajo, el océano lame las bases de los antiguos rascacielos del siglo XXI. El Gran Colapso de la década de los 30 dejó zonas enteras convertidas en monumentos al exceso del pasado: ciudades costeras inundadas, cráteres de viejos conflictos energéticos y estructuras donde el hormigón fue devorado por una selva tropical implacable. No limpiamos el desastre; aprendimos a habitarlo. Las comunidades del 2060 no destruyen la naturaleza para construir; integran sus hogares en las raíces de los nuevos ecosistemas, utilizando biotecnología para convivir con un clima impredecible.\r\n\r\nEl año 2060 no es la utopía inmaculada que prometían las corporaciones, ni el apocalipsis definitivo que temían los profetas del desastre. Es el año de la reconstrucción consciente. Un tiempo donde la humanidad mira al cosmos con ambición, pero camina sobre la Tierra con pies descalzos y un respeto sagrado por el suelo que casi llega a perder.', '2060', 2060, 0, 1, '2026-06-02 21:16:05');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'img/ojo hero.gif',
  `banner_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'img/gift ciudad.gif',
  `rol` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  `instagram` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiktok` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `email`, `password`, `bio`, `avatar_url`, `banner_url`, `rol`, `instagram`, `tiktok`, `twitter`, `creado_en`) VALUES
(1, 'David Montilla', 'montilla492', 'david.montilla@ejemplo.com', '$2y$10$bWVWd0hOpENS/Kq4dHEcjOIAhI98UjJsokDYodo9axk8ys3nedrN6', 'Curioso por el futuro, la ciencia y las posibilidades que aún están por descubrirse en el arte digital.', 'img/ojo hero.gif', 'img/gift ciudad.gif', 'admin', '@montilla_insta', '@montilla_tok', '@montilla492', '2026-05-30 18:26:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `obras`
--
ALTER TABLE `obras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `obras`
--
ALTER TABLE `obras`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `obras`
--
ALTER TABLE `obras`
  ADD CONSTRAINT `obras_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
