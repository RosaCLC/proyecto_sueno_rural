-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-04-2026 a las 12:40:25
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
(1, 'https://via.placeholder.com/600x400?text=Casa+1', 1, 'Fachada'),
(2, 'https://via.placeholder.com/600x400?text=Casa+1+Interior', 1, 'Interior'),
(3, 'https://via.placeholder.com/600x400?text=Casa+1+Terreno', 1, 'Terreno'),
(4, 'https://via.placeholder.com/600x400?text=Cabana+Exterior', 2, 'Exterior'),
(5, 'https://via.placeholder.com/600x400?text=Cabana+Interior', 2, 'Interior'),
(6, 'https://via.placeholder.com/600x400?text=Casa+Reformada', 3, 'Fachada'),
(7, 'https://via.placeholder.com/600x400?text=Casa+Salon', 3, 'Salón'),
(8, 'https://via.placeholder.com/600x400?text=Terreno', 4, 'Terreno'),
(9, 'https://via.placeholder.com/600x400?text=Casa+Valle', 5, 'Vistas'),
(10, 'https://via.placeholder.com/600x400?text=Casa+Interior', 5, 'Interior'),
(11, 'https://via.placeholder.com/600x400?text=Piso', 6, 'Salón'),
(12, 'https://via.placeholder.com/600x400?text=Piso+Cocina', 6, 'Cocina');

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
  `estado_inmueble` enum('listo para entrar','necesita reformas','ruina') NOT NULL,
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
(1, 'casa', 'Casa rural con hórreo', 55000, 120, 500, 'necesita reformas', 43.3350000, -6.4140000, 'Casa tradicional con hórreo y terreno amplio.', 1, 1, 1, 'basico', 'camino', 1, '2026-04-06 12:20:30', 3, 1),
(2, 'cabana', 'Cabaña en plena montaña', 30000, 60, 800, 'ruina', 43.1780000, -6.5490000, 'Cabaña aislada ideal para reforma completa.', 2, 0, 0, 'no', '4 por 4', 0, '2026-04-06 12:20:30', 1, 0),
(3, 'casa', 'Casa lista para entrar', 95000, 140, 300, 'listo para entrar', 43.4880000, -6.1050000, 'Vivienda reformada con buenas conexiones.', 3, 1, 1, 'alta velocidad', 'directo', 1, '2026-04-06 12:20:30', 4, 2),
(4, 'terreno', 'Terreno edificable', 20000, NULL, 1000, 'listo para entrar', 43.3360000, -6.4200000, 'Terreno llano con acceso a carretera.', 1, 1, 0, 'no', 'directo', 0, '2026-04-06 12:20:30', NULL, NULL),
(5, 'casa', 'Casa con vistas al valle', 65000, 110, 600, 'necesita reformas', 43.1800000, -6.5400000, 'Vistas espectaculares y gran terreno.', 2, 1, 1, 'basico', 'camino', 1, '2026-04-06 12:20:30', 3, 1),
(6, 'piso', 'Piso en zona rural tranquila', 50000, 80, NULL, 'listo para entrar', 43.4900000, -6.1000000, 'Piso acogedor en núcleo rural.', 3, 1, 1, 'alta velocidad', 'directo', 1, '2026-04-06 12:20:30', 2, 1);

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
(1, 'Asturias', 'Tineo', 8, 'Municipio rural con gran tradición ganadera y paisajes naturales.'),
(2, 'Asturias', 'Cangas del Narcea', 9, 'Zona montañosa con abundante naturaleza y baja densidad de población.'),
(3, 'Asturias', 'Pravia', 7, 'Municipio tranquilo cercano a la costa y bien comunicado.');

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
(6, 'colegio', 'Colegio Público Pravia', 43.4920000, -6.1020000, 3);

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

INSERT INTO `usuario` (`id_usuario`, `nombre_usuario`, `email_usuario`, `rol_usuario`, `password_usuario`) VALUES
(1, 'test', 'test@suenorural.test', 'usuario', '$2y$10$8HreJEut35EDIkQPVLhBm.xEwJBQ.acxWmX5xbSrQsPUhsGrdgmVm');

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
  MODIFY `id_favorito` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `foto`
--
ALTER TABLE `foto`
  MODIFY `id_foto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `inmueble`
--
ALTER TABLE `inmueble`
  MODIFY `id_inmueble` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
  MODIFY `id_municipio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
