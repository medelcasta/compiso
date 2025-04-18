-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql302.infinityfree.com
-- Tiempo de generación: 15-04-2025 a las 10:29:29
-- Versión del servidor: 10.6.19-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_38638851_compiso_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Inquilino`
--

CREATE TABLE `Inquilino` (
  `id_inquilino` varchar(45) NOT NULL,
  `preferencias` varchar(45) DEFAULT NULL,
  `datos_bancarios` varchar(45) DEFAULT NULL,
  `id_usuario` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `Inquilino`
--

INSERT INTO `Inquilino` (`id_inquilino`, `preferencias`, `datos_bancarios`, `id_usuario`) VALUES
('inq1', 'No fumadores, sin mascotas', 'ES12345678901234567890', 'u1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Mensaje`
--

CREATE TABLE `Mensaje` (
  `id_mensaje` varchar(45) NOT NULL,
  `id_usuario1` varchar(45) NOT NULL,
  `id_usuario2` varchar(45) NOT NULL,
  `contenido` varchar(45) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `Mensaje`
--

INSERT INTO `Mensaje` (`id_mensaje`, `id_usuario1`, `id_usuario2`, `contenido`, `fecha`, `hora`) VALUES
('m1', 'u1', 'u2', 'Hola, ¿sigue disponible el piso?', '2025-04-01', '18:00:00'),
('m2', 'u2', 'u1', '¡Hola! Sí, está disponible.', '2025-04-01', '18:05:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Propietario`
--

CREATE TABLE `Propietario` (
  `id_propietario` varchar(45) NOT NULL,
  `datos_bancarios` varchar(45) NOT NULL,
  `id_usuario` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `Propietario`
--

INSERT INTO `Propietario` (`id_propietario`, `datos_bancarios`, `id_usuario`) VALUES
('prop1', 'ES98765432109876543210', 'u2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Reserva`
--

CREATE TABLE `Reserva` (
  `id_reserva` varchar(45) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` varchar(45) NOT NULL,
  `precio` decimal(10,0) DEFAULT NULL,
  `id_vivienda` varchar(45) NOT NULL,
  `id_inquilino` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `Reserva`
--

INSERT INTO `Reserva` (`id_reserva`, `fecha_inicio`, `fecha_fin`, `estado`, `precio`, `id_vivienda`, `id_inquilino`) VALUES
('r1', '2025-05-01', '2026-05-01', 'activa', '450', 'v1', 'inq1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario`
--

CREATE TABLE `Usuario` (
  `id_usuario` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `apellidos` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `telefono` int(11) DEFAULT NULL,
  `tipo_usuario` varchar(45) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `administrador` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `Usuario`
--

INSERT INTO `Usuario` (`id_usuario`, `nombre`, `contrasena`, `apellidos`, `email`, `telefono`, `tipo_usuario`, `fecha_nacimiento`, `sexo`, `descripcion`, `administrador`) VALUES
('67f67c44c3006', 'loles', '$2y$10$r4kI0SlJVWj3iAu3O2q9iedMiXzDDCUq2ZLrP8NLYyuKaNqFAwMPS', NULL, '', NULL, '1', '0000-00-00', NULL, NULL, 0),
('67fbdb97c6ec7', 'Paula', '$2y$10$8tDeJYyPcG8qqRoGNkDbPumLMHBcSelJ.2pf3G8mqq9NrX1RNgXki', 'FernÃ¡ndez CaÃ±as', 'paulafc7@gmail.com', 617593279, '1', '2004-11-17', 'Mujer', '', 0),
('67fbfaf59e9f8', 'Fran', '$2y$10$f/s1LxRFxnKiA4MVbW2.7.f4OuFKyk8UuK3cBabwy/Wxr1hdXdkUG', 'Soria', 'fransoria@gmail.com', 617593279, '1', '2004-11-17', 'Hombre', '', 0),
('67fe5f7f461ed', 'charly', '$2y$10$JgDVCPIyF2D1AKGo4awQNu2O9pNgxFBdgjP/6z/HtNG2dUDMwbc6a', 'chumpy', 'csp0017@alu.medac.es', 655634002, '1', '2002-02-14', 'Hombre', '', 0),
('67fe6a5bb542a', 'Aurora', '$2y$10$sv1yf02YttUtD8RGIx3ow.p/tfqI2vQcNeFpz6SOhGnVMQEN0vcXa', 'Medel', 'auroramedel03@gmail.com', 600000000, '1', '2003-03-30', 'Mujer', 'Me gusta mi trabajo final', 0),
('u1', 'Laura', '1234hash', 'Gómez López', 'laura@email.com', 600112233, 'inquilino', '2000-05-20', 'Femenino', 'Estudiante responsable', 0),
('u2', 'Carlos', 'abcdhash', 'Pérez Ruiz', 'carlos@email.com', 611223344, 'propietario', '1990-09-15', 'Masculino', 'Propietario amable', 0),
('u3', 'Admin', 'adminhash', 'Admin', 'admin@email.com', NULL, 'admin', '1985-01-01', 'Otro', 'Admin del sistema', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Vivienda`
--

CREATE TABLE `Vivienda` (
  `id_vivienda` varchar(45) NOT NULL,
  `direccion` varchar(45) NOT NULL,
  `ciudad` varchar(45) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `precio` decimal(6,2) NOT NULL,
  `habitaciones` int(11) DEFAULT NULL,
  `baños` int(11) DEFAULT NULL,
  `metros_cuadrados` int(11) DEFAULT NULL,
  `disponibilidad` tinyint(4) NOT NULL,
  `imagenes` varchar(45) NOT NULL,
  `id_propietario` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `Vivienda`
--

INSERT INTO `Vivienda` (`id_vivienda`, `direccion`, `ciudad`, `descripcion`, `precio`, `habitaciones`, `baños`, `metros_cuadrados`, `disponibilidad`, `imagenes`, `id_propietario`) VALUES
('v1', 'Calle Falsa 123', 'Sevilla', 'Piso amplio con luz natural', '450.00', 3, 1, 80, 1, 'img1.jpg', 'prop1'),
('v2', 'Avda. Libertad 45', 'Granada', 'Ideal estudiantes, céntrico', '350.00', 2, 1, 60, 1, 'img2.jpg', 'prop1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Inquilino`
--
ALTER TABLE `Inquilino`
  ADD PRIMARY KEY (`id_inquilino`),
  ADD UNIQUE KEY `id_inquilino` (`id_inquilino`),
  ADD KEY `FK_inquilino_usuario_idx` (`id_usuario`);

--
-- Indices de la tabla `Mensaje`
--
ALTER TABLE `Mensaje`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD UNIQUE KEY `id_mensaje` (`id_mensaje`),
  ADD KEY `FK_mensaje_usuario1_idx` (`id_usuario1`),
  ADD KEY `FK_mensaje_usuario2_idx` (`id_usuario2`);

--
-- Indices de la tabla `Propietario`
--
ALTER TABLE `Propietario`
  ADD PRIMARY KEY (`id_propietario`,`datos_bancarios`),
  ADD UNIQUE KEY `id_propietario` (`id_propietario`),
  ADD UNIQUE KEY `id_propietario_UNIQUE` (`id_propietario`),
  ADD KEY `FK_propietario_usuario_idx` (`id_usuario`);

--
-- Indices de la tabla `Reserva`
--
ALTER TABLE `Reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD UNIQUE KEY `id_reserva` (`id_reserva`),
  ADD KEY `FK_reseva_inquilino_idx` (`id_inquilino`),
  ADD KEY `FK_reserva_vivienda_idx` (`id_vivienda`);

--
-- Indices de la tabla `Usuario`
--
ALTER TABLE `Usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indices de la tabla `Vivienda`
--
ALTER TABLE `Vivienda`
  ADD PRIMARY KEY (`id_vivienda`),
  ADD UNIQUE KEY `id_vivienda` (`id_vivienda`),
  ADD UNIQUE KEY `direccion_UNIQUE` (`direccion`),
  ADD KEY `FK_vivienda_propietario_idx` (`id_propietario`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Inquilino`
--
ALTER TABLE `Inquilino`
  ADD CONSTRAINT `FK_inquilino_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `Usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Mensaje`
--
ALTER TABLE `Mensaje`
  ADD CONSTRAINT `FK_mensaje_usuario1` FOREIGN KEY (`id_usuario1`) REFERENCES `Usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_mensaje_usuario2` FOREIGN KEY (`id_usuario2`) REFERENCES `Usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Propietario`
--
ALTER TABLE `Propietario`
  ADD CONSTRAINT `FK_propietario_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `Usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Reserva`
--
ALTER TABLE `Reserva`
  ADD CONSTRAINT `FK_reserva_inquilino` FOREIGN KEY (`id_inquilino`) REFERENCES `Inquilino` (`id_inquilino`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_reserva_vivienda` FOREIGN KEY (`id_vivienda`) REFERENCES `Vivienda` (`id_vivienda`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Vivienda`
--
ALTER TABLE `Vivienda`
  ADD CONSTRAINT `FK_vivienda_propietario` FOREIGN KEY (`id_propietario`) REFERENCES `Propietario` (`id_propietario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
