-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-05-2025 a las 12:06:29
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
-- Base de datos: `scolary`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `id_tutor` int(11) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `id_tutor`, `id_grupo`) VALUES
(1, 'Alumno 1', 6, 1),
(3, 'Alumno 3', 8, 1),
(4, 'Alumno 4', 9, 1),
(5, 'Alumno 5', 10, 1),
(6, 'Alumno 6', 6, 1),
(7, 'Alumno 7', 7, 2),
(8, 'Alumno 8', 8, 2),
(9, 'Alumno 9', 9, 2),
(10, 'Alumno 10', 10, 2),
(11, 'Alumno 11', 6, 2),
(12, 'Alumno 12', 7, 2),
(13, 'Alumno 13', 8, 3),
(14, 'Alumno 14', 9, 3),
(15, 'Alumno 15', 10, 3),
(16, 'Alumno 16', 6, 3),
(17, 'Alumno 17', 7, 3),
(18, 'Alumno 18', 8, 3),
(19, 'Alumno 19', 9, 4),
(20, 'Alumno 20', 10, 4),
(21, 'Alumno 21', 6, 4),
(22, 'Alumno 22', 7, 4),
(23, 'Alumno 23', 8, 4),
(24, 'Alumno 24', 9, 4),
(25, 'Alumno 25', 10, 5),
(26, 'Alumno 26', 6, 5),
(27, 'Alumno 27', 7, 5),
(28, 'Alumno 28', 8, 5),
(29, 'Alumno 29', 9, 5),
(30, 'Alumno 30', 10, 5),
(31, 'Alumno 26', 11, 1),
(32, 'Alumno 26', 11, 3),
(33, 'Alumno 26', 6, 1),
(34, 'Alumno 26', 6, 1),
(36, 'Alumno 26', 6, 1),
(37, 'Alumno 45', 6, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `id_alumno` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `presente` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autorizaciones`
--

CREATE TABLE `autorizaciones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('excursion','falta','otro') DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `id_profesor` int(11) DEFAULT NULL,
  `id_alumno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comedor`
--

CREATE TABLE `comedor` (
  `id` int(11) NOT NULL,
  `id_alumno` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `confirmado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `firmas_autorizacion`
--

CREATE TABLE `firmas_autorizacion` (
  `id` int(11) NOT NULL,
  `id_autorizacion` int(11) DEFAULT NULL,
  `firmado_por` enum('tutor','profesor') DEFAULT NULL,
  `fecha_firma` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `id_profesor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id`, `nombre`, `id_profesor`) VALUES
(1, '1ºA', NULL),
(2, '1ºB', NULL),
(3, '2ºA', NULL),
(4, '2ºB', NULL),
(5, '3ºA', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('profesor','tutor','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'Profesor Juan Pérez', 'juan.perez@colegio.com', 'password123', 'profesor'),
(2, 'Profesor Laura Gómez', 'laura.gomez@colegio.com', 'password123', 'profesor'),
(3, 'Profesor Andrés Martínez', 'andres.martinez@colegio.com', 'password123', 'profesor'),
(4, 'Profesor María López', 'maria.lopez@colegio.com', 'password123', 'profesor'),
(5, 'Profesor Carlos Ruiz', 'carlos.ruiz@colegio.com', 'password123', 'profesor'),
(6, 'Tutor Ana Torres', 'ana.torres@familias.com', 'password123', 'tutor'),
(7, 'Tutor David Fernández', 'david.fernandez@familias.com', 'password123', 'tutor'),
(8, 'Tutor Marta Sánchez', 'marta.sanchez@familias.com', 'password123', 'tutor'),
(9, 'Tutor Jorge Díaz', 'jorge.diaz@familias.com', 'password123', 'tutor'),
(10, 'Tutor Paula Navarro', 'paula.navarro@familias.com', 'password123', 'tutor'),
(11, 'Emilio Roblas', 'emrob@familiar.com', '$2y$10$yLtjc6hcC.WNMIaBFUJKxuLUHBqnPJZ1VUiaS/SJniAsHOS5fs4s2', 'tutor'),
(12, 'María Navarro', 'marinav@gmail.com', '$2y$10$xOzzsju/iRapbpANXA7soOyroY2Fp0RKFhTRFgtLgmdZruj6QsHO2', 'tutor'),
(13, 'Jesús Calleja', 'jesusito@colegio.com', '$2y$10$gA5SpY0ts/P17zBlXEgHju9RWyFrEsZIockTMmZuLki0/BOzt9N/a', 'profesor'),
(14, 'Pablo Jimenez', 'asdas@familia.com', '$2y$10$rW6PW.eLgu48Zd6WtthNXeeZNGrWEyP2IhEIUjgxqMtVUO.l.Sk.u', 'tutor'),
(15, 'Emilio Roblas', 'emi@colegio.com', '$2y$10$isLDxEKSk0h24yJFVfR3CuE8chWUFN105aqlsDE2Oso0KPat8zscm', 'profesor');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tutor` (`id_tutor`),
  ADD KEY `id_aula` (`id_grupo`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_alumno` (`id_alumno`);

--
-- Indices de la tabla `autorizaciones`
--
ALTER TABLE `autorizaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_profesor` (`id_profesor`),
  ADD KEY `id_alumno` (`id_alumno`);

--
-- Indices de la tabla `comedor`
--
ALTER TABLE `comedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_alumno` (`id_alumno`);

--
-- Indices de la tabla `firmas_autorizacion`
--
ALTER TABLE `firmas_autorizacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_autorizacion` (`id_autorizacion`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_profesor` (`id_profesor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `autorizaciones`
--
ALTER TABLE `autorizaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comedor`
--
ALTER TABLE `comedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `firmas_autorizacion`
--
ALTER TABLE `firmas_autorizacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`id_tutor`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `alumnos_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id`);

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `autorizaciones`
--
ALTER TABLE `autorizaciones`
  ADD CONSTRAINT `autorizaciones_ibfk_1` FOREIGN KEY (`id_profesor`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `autorizaciones_ibfk_2` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `comedor`
--
ALTER TABLE `comedor`
  ADD CONSTRAINT `comedor_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `firmas_autorizacion`
--
ALTER TABLE `firmas_autorizacion`
  ADD CONSTRAINT `firmas_autorizacion_ibfk_1` FOREIGN KEY (`id_autorizacion`) REFERENCES `autorizaciones` (`id`);

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `fk_id_profesor` FOREIGN KEY (`id_profesor`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
