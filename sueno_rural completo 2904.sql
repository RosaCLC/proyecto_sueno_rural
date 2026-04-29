-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-04-2026 a las 15:02:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sueno_rural`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favorito`
--

CREATE TABLE `favorito` (
  `id_favorito` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_inmueble` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `favorito`
--

INSERT INTO `favorito` (`id_favorito`, `id_usuario`, `id_inmueble`) VALUES
(48, 3, 1),
(55, 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foto`
--

CREATE TABLE `foto` (
  `id_foto` int(10) UNSIGNED NOT NULL,
  `url_foto` varchar(255) NOT NULL,
  `id_inmueble` int(10) UNSIGNED NOT NULL,
  `descripcion_foto` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `foto`
--

INSERT INTO `foto` (`id_foto`, `url_foto`, `id_inmueble`, `descripcion_foto`) VALUES
(13, 'img/Inmuebles/Casa1-Frontal.jpg', 1, 'Fachada'),
(14, 'img/Inmuebles/Casa1-Cocina.jpg', 1, 'Cocina'),
(15, 'img/Inmuebles/Casa1-Horreo.jpg', 1, 'Hórreo'),
(16, 'img/Inmuebles/Cabana2-Cabana.jpg', 2, 'Fachada'),
(17, 'img/Inmuebles/Casa3-Fachada.jpg', 3, 'Fachada'),
(18, 'img/Inmuebles/Casa3-Salon.jpg', 3, 'Sala'),
(19, 'img/Inmuebles/Terreno4.jpg', 4, 'Terreno'),
(20, 'img/Inmuebles/Casa5-Fachada.jpg', 5, 'Fachada'),
(21, 'img/Inmuebles/Casa5-Interior.jpg', 5, 'Interior'),
(22, 'img/Inmuebles/Casa5-Vistas.jpg', 5, 'Vistas al valle'),
(23, 'img/Inmuebles/Piso6-Fachada.jpg', 6, 'Fachada'),
(24, 'img/Inmuebles/Piso6-Interior.jpg', 6, 'Interior'),
(25, 'img/Inmuebles/Casa8-Fachada.jpg', 8, 'Fachada'),
(26, 'img/Inmuebles/Casa8-Interior.jpg', 8, 'Salón'),
(27, 'img/Inmuebles/Casa8-Lago.jpg', 8, 'Lago de Sanabria'),
(28, 'img/Inmuebles/Casa9-Fachada.jpg', 9, 'Fachada'),
(29, 'img/Inmuebles/Casa9-Cocina.jpg', 9, 'Cocina'),
(30, 'img/Inmuebles/Casa9-Pueblo.jpg', 9, 'Pueblo'),
(31, 'img/Inmuebles/Cabana10-Montana.jpg', 10, 'Edificación'),
(32, 'img/Inmuebles/Cabana10-Salon.jpg', 10, 'Salón'),
(33, 'img/Inmuebles/Cabana10-Embalse.jpg', 10, 'Embalse de Riaño'),
(34, 'img/Inmuebles/Terreno11.jpg', 11, 'Terreno'),
(35, 'img/Inmuebles/Casa12-Fachada.jpg', 12, 'Fachada'),
(36, 'img/Inmuebles/Casa12-Interior.jpg', 12, 'Interior salón-cocina'),
(37, 'img/Inmuebles/Casa12-Patio.jpg', 12, 'Patio'),
(38, 'img/Inmuebles/Casa13-Fachada.jpg', 13, 'Fachada'),
(39, 'img/Inmuebles/Casa13-Interior.jpg', 13, 'Interior'),
(40, 'img/Inmuebles/Cabana14-Edificacion.jpg', 14, 'Edificación en naturaleza'),
(41, 'img/Inmuebles/Piso15-Fachada.jpg', 15, 'Fachada'),
(42, 'img/Inmuebles/Piso15-Cocina.jpg', 15, 'Cocina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inmueble`
--

CREATE TABLE `inmueble` (
  `id_inmueble` int(10) UNSIGNED NOT NULL,
  `tipo_inmueble` enum('casa','terreno','cabana','piso') NOT NULL,
  `titulo_inmueble` varchar(255) NOT NULL,
  `precio_inmueble` int(10) UNSIGNED NOT NULL,
  `metros_vivienda` int(10) UNSIGNED DEFAULT NULL,
  `metros_terreno` int(10) UNSIGNED DEFAULT NULL,
  `estado_inmueble` enum('listo para entrar','necesita reformas','ruina') DEFAULT NULL,
  `latitud_inmueble` decimal(10,7) NOT NULL,
  `longitud_inmueble` decimal(10,7) NOT NULL,
  `descripcion_inmueble` text NOT NULL,
  `id_municipio` int(10) UNSIGNED NOT NULL,
  `agua_inmueble` tinyint(4) NOT NULL DEFAULT 0,
  `electricidad_inmueble` tinyint(4) NOT NULL DEFAULT 0,
  `internet_inmueble` enum('no','basico','alta velocidad') NOT NULL,
  `acceso_inmueble` enum('directo','4 por 4','camino') NOT NULL,
  `saneamiento_inmueble` tinyint(4) NOT NULL DEFAULT 0,
  `fecha_publicacion_inmueble` datetime NOT NULL DEFAULT current_timestamp(),
  `habitaciones_inmueble` int(10) UNSIGNED DEFAULT NULL,
  `banos_inmueble` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `inmueble`
--

INSERT INTO `inmueble` (`id_inmueble`, `tipo_inmueble`, `titulo_inmueble`, `precio_inmueble`, `metros_vivienda`, `metros_terreno`, `estado_inmueble`, `latitud_inmueble`, `longitud_inmueble`, `descripcion_inmueble`, `id_municipio`, `agua_inmueble`, `electricidad_inmueble`, `internet_inmueble`, `acceso_inmueble`, `saneamiento_inmueble`, `fecha_publicacion_inmueble`, `habitaciones_inmueble`, `banos_inmueble`) VALUES
(1, 'casa', 'Casa rural con hórreo', 55000, 120, 500, 'necesita reformas', 43.3350000, -6.4140000, 'Vivienda tradicional asturiana situada en un entorno rural tranquilo, con hórreo independiente y terreno amplio. La propiedad ofrece múltiples posibilidades de uso, tanto residencial como vacacional. Dispone de espacio exterior suficiente para jardín, huerto o zona de descanso.', 1, 1, 1, 'basico', 'camino', 1, '2026-04-06 12:20:30', 3, 1),
(2, 'cabana', 'Cabaña en plena montaña', 30000, 60, 800, 'ruina', 43.1780000, -6.5490000, 'Cabaña rural ubicada en una zona aislada, rodeada de naturaleza y con gran privacidad. La construcción requiere una reforma integral, pero cuenta con una base estructural aprovechable. Es ideal como proyecto de rehabilitación para uso turístico o vivienda de desconexión.', 2, 0, 0, 'no', '4 por 4', 0, '2026-04-06 12:20:30', 1, 0),
(3, 'casa', 'Casa lista para entrar', 95000, 140, 300, 'listo para entrar', 43.4880000, -6.1050000, 'Vivienda reformada en buen estado, situada en un núcleo rural con acceso cómodo y buena conexión por carretera. Dispone de estancias luminosas y distribución funcional. Es una opción adecuada para entrar a vivir sin necesidad de grandes inversiones adicionales.', 3, 1, 1, 'alta velocidad', 'directo', 1, '2026-04-06 12:20:30', 4, 2),
(4, 'terreno', 'Terreno edificable', 20000, NULL, 1000, NULL, 43.3360000, -6.4200000, 'Terreno de superficie llana con acceso directo desde carretera, lo que facilita su uso y aprovechamiento. Se encuentra en una zona tranquila, con entorno natural y buenas vistas. Ideal para uso agrícola, recreativo o como inversión a largo plazo.', 1, 1, 0, 'no', 'directo', 0, '2026-04-06 12:20:30', NULL, NULL),
(5, 'casa', 'Casa con vistas al valle', 65000, 110, 600, 'necesita reformas', 43.1800000, -6.5400000, 'Propiedad situada en una ubicación elevada, con vistas abiertas al paisaje natural del entorno. Cuenta con una parcela amplia que permite múltiples usos exteriores. Es una opción interesante para quienes buscan tranquilidad y contacto directo con la naturaleza.', 2, 1, 1, 'basico', 'camino', 1, '2026-04-06 12:20:30', 3, 1),
(6, 'piso', 'Piso en zona rural tranquila', 50000, 80, NULL, 'listo para entrar', 43.4900000, -6.1000000, 'Piso acogedor ubicado en un entorno rural, cercano a servicios básicos y con buen acceso. La vivienda ofrece una distribución cómoda y funcional, adecuada tanto como residencia habitual como segunda vivienda. Permite disfrutar de un estilo de vida tranquilo sin renunciar a comodidades.', 3, 1, 1, 'alta velocidad', 'directo', 1, '2026-04-06 12:20:30', 2, 1),
(8, 'casa', 'Casa de piedra cerca del lago de Sanabria', 78000, 135, 420, 'necesita reformas', 42.0543000, -6.6339000, 'Casa tradicional de piedra situada en un entorno tranquilo, próxima a caminos rurales y zonas naturales. La vivienda conserva elementos originales y ofrece muchas posibilidades de reforma. Cuenta con terreno suficiente para huerto o zona exterior de descanso.', 4, 1, 1, 'basico', 'directo', 1, '2026-04-29 12:27:23', 3, 1),
(9, 'casa', 'Vivienda reformada en núcleo rural zamorano', 112000, 150, 300, 'listo para entrar', 42.0581000, -6.6402000, 'Vivienda en buen estado situada en una zona rural con acceso cómodo en coche. Dispone de espacios amplios y una pequeña parcela exterior. Es adecuada para entrar a vivir sin necesidad de una reforma inicial importante.', 4, 1, 1, 'alta velocidad', 'directo', 1, '2026-04-29 12:27:23', 4, 2),
(10, 'cabana', 'Cabaña con vistas al embalse de Riaño', 46000, 70, 850, 'necesita reformas', 42.9762000, -5.0039000, 'Cabaña rural situada en un entorno de montaña con vistas abiertas al paisaje. Requiere mejoras interiores, pero mantiene una estructura aprovechable. Es una opción interesante para uso vacacional o proyecto de rehabilitación rural.', 5, 1, 1, 'basico', 'camino', 0, '2026-04-29 12:27:23', 2, 1),
(11, 'terreno', 'Terreno rústico con acceso en Riaño', 24000, NULL, 1600, NULL, 42.9815000, -5.0107000, 'Terreno amplio situado en una zona natural tranquila, con acceso por camino rural. Puede resultar útil para uso agrícola, recreativo o como espacio vinculado a un proyecto rural. El entorno destaca por sus vistas y baja densidad de población.', 5, 0, 0, 'no', 'camino', 0, '2026-04-29 12:27:23', NULL, NULL),
(12, 'casa', 'Casa gallega con patio en Mondoñedo', 89000, 125, 260, 'listo para entrar', 43.4288000, -7.3631000, 'Casa situada en un entorno tranquilo, cercana al núcleo urbano de Mondoñedo. Dispone de patio exterior y estancias luminosas. Es una vivienda adecuada para quienes buscan un equilibrio entre vida rural y proximidad a servicios.', 6, 1, 1, 'alta velocidad', 'directo', 1, '2026-04-29 12:27:23', 3, 2),
(13, 'casa', 'Casa para rehabilitar en A Fonsagrada', 52000, 110, 700, 'ruina', 43.1246000, -7.0678000, 'Vivienda rural que necesita una rehabilitación integral, situada en un entorno de montaña muy tranquilo. El terreno ofrece posibilidades para jardín, huerto o animales pequeños. Es una opción pensada para un proyecto de reforma amplio.', 7, 1, 0, 'no', '4 por 4', 0, '2026-04-29 12:27:23', 3, 1),
(14, 'cabana', 'Cabaña aislada entre prados gallegos', 39000, 65, 1200, 'necesita reformas', 43.1302000, -7.0759000, 'Cabaña sencilla ubicada en una zona muy tranquila, rodeada de prados y monte. Requiere trabajos de acondicionamiento, aunque mantiene un gran potencial como refugio rural. Su parcela permite disfrutar de privacidad y contacto directo con la naturaleza.', 7, 0, 1, 'basico', 'camino', 0, '2026-04-29 12:27:23', 1, 1),
(15, 'piso', 'Piso acogedor en el centro de Potes', 97000, 85, NULL, 'listo para entrar', 43.1542000, -4.6233000, 'Piso ubicado en el núcleo de Potes, cerca de comercios y servicios básicos. Ofrece una alternativa cómoda para vivir en un entorno rural sin renunciar a servicios cercanos. Su estado permite entrar a vivir sin grandes reformas.', 8, 1, 1, 'alta velocidad', 'directo', 1, '2026-04-29 12:27:23', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE `municipio` (
  `id_municipio` int(10) UNSIGNED NOT NULL,
  `region_municipio` varchar(45) NOT NULL,
  `nombre_municipio` varchar(45) NOT NULL,
  `tranquilidad_municipio` int(10) UNSIGNED NOT NULL,
  `descripcion_municipio` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`id_municipio`, `region_municipio`, `nombre_municipio`, `tranquilidad_municipio`, `descripcion_municipio`) VALUES
(1, 'Asturias', 'Tineo', 8, 'Municipio situado en el occidente asturiano, caracterizado por su entorno natural y su tradición rural. Cuenta con una buena red de comunicaciones dentro de la zona y servicios básicos disponibles. Es una opción interesante para quienes buscan tranquilidad sin aislamiento total.'),
(2, 'Asturias', 'Cangas del Narcea', 9, 'Uno de los municipios más extensos de Asturias, con un fuerte carácter rural y gran riqueza natural. Destaca por sus montañas, bosques y tradición vinícola. Ofrece servicios básicos y un entorno ideal para quienes buscan naturaleza y calidad de vida.'),
(3, 'Asturias', 'Pravia', 7, 'Municipio bien comunicado dentro de Asturias, cercano a la costa y con acceso a servicios variados. Combina zonas rurales con un núcleo urbano activo. Es una opción equilibrada para quienes buscan tranquilidad sin renunciar a buenas conexiones.'),
(4, 'Castilla y León', 'Puebla de Sanabria', 8, 'Municipio zamorano situado en un entorno natural de gran valor paisajístico. Destaca por su casco histórico, su cercanía al lago de Sanabria y su ambiente tranquilo. Es una zona adecuada para quienes buscan vida rural con servicios básicos próximos.'),
(5, 'Castilla y León', 'Riaño', 9, 'Localidad leonesa rodeada de montañas y embalse, con un entorno especialmente atractivo para amantes de la naturaleza. Su ritmo de vida es tranquilo y está orientado al turismo rural. Cuenta con servicios básicos y buena calidad paisajística.'),
(6, 'Galicia', 'Mondoñedo', 7, 'Municipio lucense con patrimonio histórico, entorno verde y buena conexión con otros núcleos de población. Combina tranquilidad rural con algunos servicios urbanos básicos. Es una zona interesante para quienes buscan vivienda en el norte de Galicia.'),
(7, 'Galicia', 'A Fonsagrada', 9, 'Municipio de montaña situado en la provincia de Lugo, con baja densidad de población y abundante naturaleza. Es ideal para quienes buscan aislamiento, tranquilidad y espacios abiertos. Dispone de servicios esenciales en el núcleo principal.'),
(8, 'Cantabria', 'Potes', 8, 'Localidad cántabra situada en la comarca de Liébana, rodeada de montañas y paisajes naturales. Es una zona con atractivo turístico y ambiente rural. Cuenta con servicios básicos y buena conexión con otros pueblos del entorno.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id_servicio` int(10) UNSIGNED NOT NULL,
  `tipo_servicio` enum('alimentación','colegio','instituto','sanidad') NOT NULL,
  `nombre_servicio` varchar(255) NOT NULL,
  `latitud_servicio` decimal(10,7) NOT NULL,
  `longitud_servicio` decimal(10,7) NOT NULL,
  `id_municipio` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_servicio`, `tipo_servicio`, `nombre_servicio`, `latitud_servicio`, `longitud_servicio`, `id_municipio`) VALUES
(1, 'alimentación', 'Supermercado Rural Tineo', 43.3360000, -6.4150000, 1),
(2, 'sanidad', 'Centro de Salud Tineo', 43.3350000, -6.4170000, 1),
(3, 'alimentación', 'Tienda Local Cangas', 43.1780000, -6.5480000, 2),
(4, 'sanidad', 'Centro Médico Cangas', 43.1800000, -6.5500000, 2),
(5, 'alimentación', 'Supermercado Pravia', 43.4900000, -6.1000000, 3),
(6, 'colegio', 'Colegio Público Pravia', 43.4920000, -6.1020000, 3),
(7, 'alimentación', 'Supermercado Sanabria', 42.0550000, -6.6345000, 4),
(8, 'sanidad', 'Centro de Salud Puebla de Sanabria', 42.0537000, -6.6318000, 4),
(9, 'alimentación', 'Tienda de Alimentación Riaño', 42.9770000, -5.0044000, 5),
(10, 'sanidad', 'Consultorio Médico Riaño', 42.9759000, -5.0028000, 5),
(11, 'alimentación', 'Supermercado Mondoñedo', 43.4293000, -7.3626000, 6),
(12, 'colegio', 'Colegio Público de Mondoñedo', 43.4279000, -7.3640000, 6),
(13, 'alimentación', 'Tienda Local A Fonsagrada', 43.1251000, -7.0683000, 7),
(14, 'sanidad', 'Centro de Salud A Fonsagrada', 43.1240000, -7.0669000, 7),
(15, 'alimentación', 'Supermercado Liébana Potes', 43.1548000, -4.6228000, 8),
(16, 'instituto', 'IES Jesús de Monasterio', 43.1536000, -4.6240000, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `nombre_usuario` varchar(45) NOT NULL,
  `email_usuario` varchar(100) NOT NULL,
  `rol_usuario` enum('usuario','administrador') NOT NULL,
  `password_usuario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`nombre_usuario`, `email_usuario`, `rol_usuario`, `password_usuario`) VALUES
('admin', 'admin@admin.admin', 'administrador', '$2y$10$uRsB8g3Nq7iTj.x6G67CRO.FdemRxEXOQFa/nnR8NXQ6NxEeYADtG'),
('usuario', 'usuario@usuario.usuario', 'usuario', '$2y$10$RvGrU5bwPz6RT8uFtwJlDOIu4aM2reGonf0ZowAjvpx.nf1xf7cNq');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `favorito`
--
ALTER TABLE `favorito`
  ADD PRIMARY KEY (`id_favorito`),
  ADD UNIQUE KEY `usuario_inmueble_UNIQUE` (`id_usuario`,`id_inmueble`),
  ADD KEY `id_usuario_idx` (`id_usuario`),
  ADD KEY `id_inmueble_idx` (`id_inmueble`);

--
-- Indices de la tabla `foto`
--
ALTER TABLE `foto`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `id_inmueble_idx` (`id_inmueble`);

--
-- Indices de la tabla `inmueble`
--
ALTER TABLE `inmueble`
  ADD PRIMARY KEY (`id_inmueble`),
  ADD KEY `id_municipio_idx` (`id_municipio`);

--
-- Indices de la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`id_municipio`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `id_municipio_idx` (`id_municipio`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email_usuario_UNIQUE` (`email_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `favorito`
--
ALTER TABLE `favorito`
  MODIFY `id_favorito` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `foto`
--
ALTER TABLE `foto`
  MODIFY `id_foto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `inmueble`
--
ALTER TABLE `inmueble`
  MODIFY `id_inmueble` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
  MODIFY `id_municipio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `favorito`
--
ALTER TABLE `favorito`
  ADD CONSTRAINT `fk_favorito_inmueble` FOREIGN KEY (`id_inmueble`) REFERENCES `inmueble` (`id_inmueble`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_favorito_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `foto`
--
ALTER TABLE `foto`
  ADD CONSTRAINT `fk_foto_inmueble` FOREIGN KEY (`id_inmueble`) REFERENCES `inmueble` (`id_inmueble`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `inmueble`
--
ALTER TABLE `inmueble`
  ADD CONSTRAINT `fk_inmueble_municipio` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id_municipio`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD CONSTRAINT `fk_servicio_municipio` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id_municipio`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
