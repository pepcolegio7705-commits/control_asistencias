
-- phpMyAdmin SQL Dump
-- Sistema de Control de Asistencias

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `control_asistencias` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `control_asistencias`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','user') NOT NULL DEFAULT 'admin',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario por defecto (admin / admin123)
-- El hash de "admin123" generado con password_hash()
INSERT INTO `usuarios` (`username`, `password`, `rol`, `estado`) VALUES
('admin', '$2y$10$wN1rB9Z.v5oN7J5l8H4u4OQ/6Jz1g/P6.rM.5G6nZ1q.Z8o/2H2.m', 'admin', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `cuil` varchar(15) NULL,
  `direccion` varchar(255) NULL,
  `telefono` varchar(30) NULL,
  `mail` varchar(150) NULL,
  `titulo` varchar(150) NULL,
  `legajo` varchar(30) NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dni` (`dni`),
  UNIQUE KEY `idx_cuil` (`cuil`),
  UNIQUE KEY `idx_legajo` (`legajo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunos articulos de ejemplo
INSERT INTO `articulos` (`codigo`, `descripcion`, `estado`) VALUES
('ART10', 'Enfermedad', 1),
('ART20', 'Razones Particulares', 1),
('ART30', 'Maternidad', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profesor_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `articulo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_profesor_fecha` (`profesor_id`, `fecha`),
  KEY `fk_asistencia_articulo` (`articulo_id`),
  CONSTRAINT `fk_asistencia_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_asistencia_articulo` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
