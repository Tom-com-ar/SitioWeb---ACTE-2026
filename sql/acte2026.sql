-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-04-2026 a las 16:47:49
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
-- Base de datos: `acte2026`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `taller_id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `taller_id`, `nombre`, `descripcion`, `imagen`) VALUES
(1, 1, 'Introducción a IA', 'Actividad sobre conceptos básicos de inteligencia artificial', 'https://herramientas-ia.com/wp-content/uploads/2024/09/ia-cerebro.jpeg.webp'),
(2, 1, 'Chatbots con Python', 'Creación de chatbots con Python.', 'https://www.apriorit.com/wp-content/uploads/2022/05/10-chatbots-benefits.png'),
(3, 2, 'Lógica de programación', 'Actividad práctica de lógica y algoritmos.', 'https://www.dongee.com/tutoriales/content/images/2024/04/image-47.png'),
(4, 3, 'Circuitos básicos', 'Montaje y pruebas de circuitos básicos.', 'https://www.aprendeelectricidad.com/wp-content/uploads/2020/07/circuito-electrico-1.png'),
(5, 4, 'Robótica móvil', 'Construcción de un robot móvil simple.', 'https://static.designboom.com/wp-content/uploads/2026/01/mobile-robot-transports-gear-camping-W1-zeroth-designboom-03.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_inscripciones`
--

CREATE TABLE `actividad_inscripciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `actividad_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividad_inscripciones`
--

INSERT INTO `actividad_inscripciones` (`id`, `usuario_id`, `actividad_id`, `nombre`, `email`, `telefono`) VALUES
(3, 2, 1, 'Joaquin Acevedo', 'joaquiinacevedo@gmail.com', '1140226781'),
(4, 2, 2, 'Joaquin Acevedo', 'joaquiinacevedo@gmail.com', '1140226781');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `taller_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id`, `usuario_id`, `taller_id`, `nombre`, `email`, `telefono`) VALUES
(4, 1, 1, 'Alan', 'alanbissio@gmail.com', '1165486795'),
(5, 1, 2, 'Alan', 'alanbissio@gmail.com', '1165486795'),
(8, 2, 1, 'Joaquin Acevedo', 'joaquiinacevedo@gmail.com', '1140226781');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `asunto` varchar(150) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talleres`
--

CREATE TABLE `talleres` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `talleres`
--

INSERT INTO `talleres` (`id`, `nombre`, `descripcion`, `imagen`) VALUES
(1, 'IA', 'Taller sobre Inteligencia artificial', 'https://msmk.university/wp-content/uploads/2024/11/imagen-de-inteligencia-artificial.webp'),
(2, 'Programacion', 'Taller sobre fundamentos basicos de Programacion', 'https://i.blogs.es/34c810/coding/500_333.jpeg'),
(3, 'Electronica', 'Taller sobre fundamentos de electronica', 'https://www.vencoel.com/wp-content/uploads/2023/11/normativas-electronica.jpg'),
(4, 'Robotica', 'Taller sobre robotica', 'https://bmfschool.com/wp-content/uploads/2022/01/para-que-sirve-robotica-1.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `password`, `fecha_registro`) VALUES
(1, 'Alan', 'Bissio', 'alanbissio@gmail.com', '$2y$10$MVYpoyarut6X1h1xDdPg3OdhrTbC0UDCo2CzoPYNv/lV4hT6.2o9S', '2026-04-08 23:53:23'),
(2, 'Joaquin', 'Acevedo', 'joaquiinacevedo@gmail.com', '$2y$10$8eLn0nklkkI7igrWPph9H.ItFrI6jOcTmtxm16IIKkZi3uEUkHECa', '2026-04-14 14:36:48');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taller_id` (`taller_id`);

--
-- Indices de la tabla `actividad_inscripciones`
--
ALTER TABLE `actividad_inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `actividad_id` (`actividad_id`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_inscripcion` (`usuario_id`,`taller_id`),
  ADD KEY `taller_id` (`taller_id`);

--
-- Indices de la tabla `talleres`
--
ALTER TABLE `talleres`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `actividad_inscripciones`
--
ALTER TABLE `actividad_inscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `talleres`
--
ALTER TABLE `talleres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`);

--
-- Filtros para la tabla `actividad_inscripciones`
--
ALTER TABLE `actividad_inscripciones`
  ADD CONSTRAINT `actividad_inscripciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `actividad_inscripciones_ibfk_2` FOREIGN KEY (`actividad_id`) REFERENCES `actividades` (`id`);

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
