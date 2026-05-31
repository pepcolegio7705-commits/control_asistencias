-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 31-05-2026 a las 16:01:07
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `control_asistencias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

DROP TABLE IF EXISTS `articulos`;
CREATE TABLE IF NOT EXISTS `articulos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sector` enum('docente','auxiliar','ambos') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ambos',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `codigo`, `descripcion`, `sector`, `estado`) VALUES
(1, '36A', 'Particulares', 'docente', 1),
(2, '5A', 'Enfermedad', 'docente', 1),
(3, '5B', 'Enfermedad larga duración', 'docente', 1),
(4, '2A', 'Anual de Vacaciones (Docentes)', 'docente', 1),
(5, '2B', 'Anual de Vacaciones (Directivos y Supervisores)', 'docente', 1),
(6, '2G', 'Adelanto de Vacaciones (Docentes)', 'docente', 1),
(7, '5E', 'Violencia de género', 'docente', 1),
(8, '17A', 'Tenencia, guarda o tutela de menor de 0 a 6 meses de edad', 'docente', 1),
(9, '17B', 'Tenencia, guarda o tutela de menor de 6 meses a 7 años de edad', 'docente', 1),
(10, '17C', 'Tenencia, guarda o tutela de menor de 7 a 18 años de edad', 'docente', 1),
(11, '19A', 'Matrimonio', 'docente', 1),
(12, '19B', 'Matrimonio - de hijo', 'docente', 1),
(13, '20', 'Nacimiento de Hijo - Agente Varón', 'docente', 1),
(14, '20A', 'Nacimiento de Hijo - Parto Natural', 'docente', 1),
(15, '20B', 'Nacimiento de Hijo - por Cesárea o Prematuro', 'docente', 1),
(16, '20C', 'Nacimiento de Hijos Múltiples', 'docente', 1),
(17, '21A', 'Fallecimiento de familiar 1er y 2do grado, cónyuge y/o pareja, padres', 'docente', 1),
(18, '21B', 'Fallecimiento de familiar 3er grado - cuñados, tíos, primos', 'docente', 1),
(19, '21C', 'Fallecimiento de familiar', 'docente', 1),
(20, '22', 'Fallecimiento cónyuge cuando existen hijos menores de 7 años', 'docente', 1),
(21, '25', 'Licencia sin Goce de Haberes', 'docente', 1),
(22, '26A', 'Traslado dentro de la jurisdicción - distancia entre 30 a 400Km - (AUX)', 'docente', 1),
(23, '26B', 'Traslado dentro de la jurisdicción - distancia entre 401 a 800Km - (AUX)', 'docente', 1),
(24, '26C', 'Traslado dentro de la jurisdicción - distancia más de 801Km - (AUX)', 'docente', 1),
(25, '27A', 'Traslado Interjurisdiccional con sueldo', 'docente', 1),
(26, '27B', 'Traslado Interjurisdiccional sin sueldo', 'docente', 1),
(27, 'LEY VIII 20 CAP IX', 'Traslado transitorio', 'docente', 1),
(28, '28A', 'Por incorporación a las FFAA, sin sueldo, hasta 30 días después de la baja', 'docente', 1),
(29, '28B', 'Por incorporación a las FFAA, con 50% de sueldo, hasta 30 días después de la baja', 'docente', 1),
(30, '29A', 'Cargo mayor jerarquía o superior sin estabilidad dentro del Organismo', 'docente', 1),
(31, '29B', 'Cargo mayor jerarquía sin estabilidad dentro de la Administración Publica, Nacional, Provincial o Municipal', 'docente', 1),
(32, '29C', 'Cargo mayor jerarquía dentro de la Administración Publica, Nacional, Provincial o Municipal', 'docente', 1),
(33, '29D', 'Cargo y/u horas cátedras dentro del Sistema Educativo de igual o mayor Remuneración', 'docente', 1),
(34, '30 1A', 'Licencia Gremial - Cargo en Comisión Directiva', 'docente', 1),
(35, '30 1B', 'Licencia Gremial - Delegados Escolares', 'docente', 1),
(36, '30 1C', 'Licencia Gremial - Apoderados de Listas Titulares', 'docente', 1),
(37, '30 1D', 'Licencia Gremial - Integrantes de Juntas Electorales', 'docente', 1),
(38, '30 1E', 'Licencia Gremial - Integrantes Comision Negociadora y Paritaria', 'docente', 1),
(39, '30 2', 'Licencia Cargo Político (C/G)', 'docente', 1),
(41, '30 2 S/G', 'Licencia Cargo Político (S/G)', 'docente', 1),
(42, '31', 'Por Estudio', 'docente', 1),
(43, '31 2', 'Capacitación', 'docente', 1),
(44, '31A', 'Por Estudio - Becas relacionadas a la función', 'docente', 1),
(45, '31B', 'Por Estudio - Cursos de Actualización y Perfeccionamiento', 'docente', 1),
(46, '31C', 'Por Estudio - Beca no relacionada a la función - Sin Haberes (Doc)', 'docente', 1),
(47, '31 D1', 'Por Estudio - Prácticas previstas en planes de Estudio (Doc)', 'docente', 1),
(48, '31 D2', 'Capacitación Docente dependiente de Nación y/o Provincia (Doc)', 'docente', 1),
(49, '31E', 'Por investigación o actividades culturales (Sin sueldo)', 'docente', 1),
(50, '32', 'Por Examen', 'docente', 1),
(51, '32A', 'Por Examen - Por Tesis, Examen o Examen final de la carrera', 'docente', 1),
(52, '32B', 'Por Examen - Integrar Mesas examinadoras', 'docente', 1),
(53, '32C', 'Por Examen - Ascenso de su carrera laboral', 'docente', 1),
(54, '33', 'Práctica Deportiva - Amateur y/o aficcionado con sueldo', 'docente', 1),
(55, '34A', 'Práctica Deportiva - Dirigente o representate de Delegaciones', 'docente', 1),
(56, '34B', 'Práctica Deportiva - Asistencia a Congresos, Asambleas, reuniones o cursos u otras manifestaciones relacionadas con el deporte', 'docente', 1),
(57, '34C', 'Práctica Deportiva - Participación en carácter de Juez, Arbitro o Jurado', 'docente', 1),
(60, '36C', 'Asuntos Personales - Donación de sangre', 'docente', 1),
(61, '36D', 'Asuntos Particulares - Citación de Autoridad Judicial', 'docente', 1),
(62, '36A (DIRECTORES)', 'Asuntos Particulares - (Directores)', 'docente', 1),
(63, '36B', 'Asuntos Particulares - Viaje a consecuencia de otras licencias', 'docente', 1),
(64, '36B (DIRECTORES)', 'Asuntos Particulares - Viaje a consecuencia de otras licencias (Dir)', 'docente', 1),
(65, '45', 'Licencia Extraordinaria', 'docente', 1),
(66, '45 GOCE DE HABERES', 'Licencia Extraordinaria', 'docente', 1),
(67, '45 SIN GOCE DE HABER', 'Licencia Extraordinaria - Sin sueldo', 'docente', 1),
(68, '46 3', 'Viaje de estudio con proyecto educativo/pedagógico', 'docente', 1),
(69, '319', 'Comisión de Servicio de corta duración', 'docente', 1),
(70, '609', 'Asignación/cambio de función', 'docente', 1),
(71, '905', 'Miembro de Junta de Clasificación Docente - Ley VIII N° 138 art. 10', 'docente', 1),
(72, '906', 'Adscripción', 'docente', 1),
(73, '910', 'Designación en cargo sin subrogancia', 'docente', 1),
(74, '911', 'Cambio de Función', 'docente', 1),
(75, '913', 'Tareas Pasivas', 'docente', 1),
(76, '924', 'Sociocultural - Ley I N° 960 - Sin Goce', 'docente', 1),
(77, '924A', 'Sociocultural - Ley I N° 960', 'docente', 1),
(78, '930', 'Sumario sin baja de Salario', 'docente', 1),
(79, '999', 'Débito Laboral al 70%', 'docente', 1),
(80, '9999', 'Año Nuevo Judío', 'docente', 1),
(81, '9999A', 'Día del Perdón', 'docente', 1),
(82, '9999B', 'Pascuas Judías', 'docente', 1),
(83, 'A', 'Prevención Sumaria', 'docente', 1),
(84, 'LEY XVI N° 89 (S/G)', 'Ley XVI Nº89 art. 60 inciso 2 (S/G)', 'docente', 1),
(85, 'LEY XVI N° 89 (C/G)', 'Ley XVI Nº89 - Ley XVI Nº89 art. 60 inciso 3 (C/G)', 'docente', 1),
(86, 'LEY I 654 ART 9', 'Ley I 654 Art 9 - Pareja', 'docente', 1),
(87, 'LEY PROV N°650 A', 'Adopción', 'docente', 1),
(88, 'LEY PROV N°650 B', 'Adopción niño con discapacidades', 'docente', 1),
(89, 'LEY PROV N°650 C', 'Adopción múltiple', 'docente', 1),
(90, '2 (AUX)', 'Anual de Vacaciones', 'auxiliar', 1),
(91, '2E (AUX)', 'Adelanto de Vacaciones', 'auxiliar', 1),
(92, '3 (AUX)', 'Interrupción o posponer Licencia Anual de Vacaciones (Aux)', 'auxiliar', 1),
(93, '4 (AUX)', 'Receso Invernal (Aux)', 'auxiliar', 1),
(94, '10A (AUX)', 'Por razones de fuerza mayor - Desastres naturales - (Aux)', 'auxiliar', 1),
(95, '10B (AUX)', 'Por razones de fuerza mayor - Cortes de ruta - (Aux)', 'auxiliar', 1),
(96, '12C (AUX)', 'Día Femenino (Aux)', 'auxiliar', 1),
(97, '16A (AUX)', 'Tenencia, guarda, o tutela de menores otorgada por autoridad judicial o administrativa competente de 0 a 6 meses de edad - (AUX)', 'auxiliar', 1),
(98, '16A1 (AUX)', 'Tenencia, guarda, o tutela de menores con capacidad diferente otorgada por autoridad judicial o administrativa competente de 0 a 6 meses de edad (AUX)', 'auxiliar', 1),
(99, '16B (AUX)', 'Tenencia, guarda, o tutela de menores otorgada por autoridad judicial o administrativa competente de 6 meses a 7 años de edad - (AUX)', 'auxiliar', 1),
(100, '16B1 (AUX)', 'Tenencia, guarda, o tutela de menores con capacidad diferente otorgada por autoridad judicial o administrativa competente de 6 meses a 7 años - (AUX)', 'auxiliar', 1),
(101, '16C (AUX)', 'Tenencia, guarda, o tutela de menores otorgada por autoridad judicial o administrativa competente de 7 años a 18 años de edad (AUX)', 'auxiliar', 1),
(102, '16C1 (AUX)', 'Tenencia, guarda, o tutela de menores con capacidad diferente otorgada por autoridad judicial o administrativa competente de 7 años a 18 años - (AUX)', 'auxiliar', 1),
(103, '18A (AUX)', 'Matrimonio (Aux)', 'auxiliar', 1),
(104, '18B (AUX)', 'Matrimonio - de hijo (Aux)', 'auxiliar', 1),
(105, '18C (AUX)', 'Matrimonio - Concubinato (Aux)', 'auxiliar', 1),
(106, '19A (AUX)', 'Nacimiento de Hijo - Parto Natural (Aux)', 'auxiliar', 1),
(107, '19B (AUX)', 'Nacimiento de Hijo - por Cesárea o Prematuro (Aux)', 'auxiliar', 1),
(108, '19C (AUX)', 'Nacimiento de Hijos Múltiples (Aux)', 'auxiliar', 1),
(109, '20A (AUX)', 'Fallecimiento de familiar 1er y 2do grado, cónyuge y/o pareja, padres - (AUX)', 'auxiliar', 1),
(110, '20B (AUX)', 'Fallecimiento de familiar 3er grado- cuñados, tíos, primos - (AUX)', 'auxiliar', 1),
(111, '20C (AUX)', 'Fallecimiento de familiar - hijo (AUX)', 'auxiliar', 1),
(112, '21 (AUX)', 'Fallecimiento cónyuge cuando existen hijos menores de 7 años (AUX)', 'auxiliar', 1),
(113, '23 (AUX)', 'Licencia sin Goce de Haberes (Aux)', 'auxiliar', 1),
(114, '24A (AUX)', 'Cargo mayor jerarquía o superior sin estabilidad dentro del Organismo - (Aux)', 'auxiliar', 1),
(115, '24B (AUX)', 'Cargo mayor jerarquía sin estabilidad dentro de la Administración Publica, Nacional, Provincial o Municipal (Aux)', 'auxiliar', 1),
(116, '25A (AUX)', 'Traslado dentro de la jurisdicción - distancia entre 30 a 400Km - (AUX)', 'auxiliar', 1),
(117, '25B (AUX)', 'Traslado dentro de la jurisdicción - distancia entre 401 a 800Km - (AUX)', 'auxiliar', 1),
(118, '25C (AUX)', 'Traslado dentro de la jurisdicción - distancia más de 801Km - (AUX)', 'auxiliar', 1),
(119, '25.1 (AUX)', 'Mudanza dentro de la localidad (Aux)', 'auxiliar', 1),
(120, '25 2A (AUX)', 'Mudanza por permuta o traslado dentro de la Jurisdicción 400 km (5 días)', 'auxiliar', 1),
(121, '25 2B (AUX)', 'Mudanza por permuta o traslado dentro de la Jurisdicción 800 km (10 días)', 'auxiliar', 1),
(122, '25 2C (AUX)', 'Mudanza por permuta o traslado dentro de la Jurisdicción 800 km (15 días)', 'auxiliar', 1),
(123, '25 3A (AUX)', 'Mudanza por regreso de un traslado previo (5 días)', 'auxiliar', 1),
(124, '25 3B (AUX)', 'Mudanza por regreso de un traslado previo (10 días)', 'auxiliar', 1),
(125, '25 3C (AUX)', 'Mudanza por regreso de un traslado previo (15 días)', 'auxiliar', 1),
(126, '26 1A (AUX)', 'Licencia Gremial - Cargo en Comisión Directiva (Aux)', 'auxiliar', 1),
(127, '26 1B (AUX)', 'Licencia Gremial - Delegados Escolares (Aux)', 'auxiliar', 1),
(128, '26 1C (AUX)', 'Licencia Gremial - Apoderados de Listas Titulares (Aux)', 'auxiliar', 1),
(129, '26 1D (AUX)', 'Licencia Gremial - Integrantes de Juntas Electorales (Aux)', 'auxiliar', 1),
(130, '26 1E (AUX)', 'Licencia Gremial - Integrantes Comision Negociadora y Paritaria (Aux)', 'auxiliar', 1),
(131, '26 2 (AUX)', 'Licencia Cargo Político (Aux)', 'auxiliar', 1),
(133, '27A (AUX)', 'Por Estudio - Becas relacionadas a la función (Aux)', 'auxiliar', 1),
(134, '27B (AUX)', 'Por Estudio - Cursos de Actualización y Perfeccionamiento (Aux)', 'auxiliar', 1),
(135, '27C (AUX)', 'Por Estudio - Becas no relacionadas a la función (Aux)', 'auxiliar', 1),
(136, '27D(AUX)', 'Por Estudio - Prácticas previstas en planes de Estudio (Aux)', 'auxiliar', 1),
(137, '27E (AUX)', 'Por Estudio - Sin goce de Haberes (Aux)', 'auxiliar', 1),
(138, '28A (AUX)', 'Por Examen - Por Tesis, Examen o Examen final de la carrera (Aux)', 'auxiliar', 1),
(139, '28B (AUX)', 'Por Examen - Integrar Mesas examinadoras- (Aux)', 'auxiliar', 1),
(140, '28C (AUX)', 'Por Examen - Ascenso de su carrera laboral - (Aux)', 'auxiliar', 1),
(141, '29 (AUX)', 'Práctica Deportiva - Amateur y/o aficcionado con sueldo (Aux)', 'auxiliar', 1),
(142, '30A (AUX)', 'Práctica Deportiva - Dirigente o representate de Delegaciones (Aux)', 'auxiliar', 1),
(143, '30B (AUX)', 'Práctica Deportiva - Asistencia a Congresos, Asambleas, reuniones o cursos u otras manifestaciones relacionadas con el deporte - (Aux)', 'auxiliar', 1),
(144, '30C (AUX)', 'Práctica Deportiva - Participación en carácter de Juez, Arbitro o Jurado (Aux)', 'auxiliar', 1),
(145, '31A (AUX)', 'Asuntos Personales (Aux)', 'auxiliar', 1),
(146, '31B (AUX)', 'Asuntos Particulares - Días de Viaje de mas de 250 km (Aux)', 'auxiliar', 1),
(147, '31C (AUX)', 'Asuntos Personales - Donación de sangre (Aux)', 'auxiliar', 1),
(148, '31D (AUX)', 'Asuntos Particulares - Citación de Autoridad Judicial (Aux)', 'auxiliar', 1),
(149, '36 (AUX)', 'Situaciones no contempladas en el Régimen de Licencias -Con Goce de Sueldo', 'auxiliar', 1),
(150, '36 (AUX) S/G', 'Situaciones no contempladas en el Régimen de Licencias - Sin Goce de Sueldo', 'auxiliar', 1),
(151, '39 (AUX)', 'Licencia por actividad cultural (aux)', 'auxiliar', 1),
(152, '40 (AUX)', 'Licencia por violencia de género (Aux)', 'auxiliar', 1),
(153, '900 (AUX)', 'Designación de cargo político por Decreto (Aux)', 'auxiliar', 1),
(154, '912 (AUX)', 'Cambio de Función (Aux)', 'auxiliar', 1),
(155, '915 (AUX)', 'Adscripcion (Aux)', 'auxiliar', 1),
(156, '927 (AUX)', 'Franco Compensatorio (Aux)', 'auxiliar', 1),
(157, '929 (AUX)', 'Sumario sin baja de Salario (Aux)', 'auxiliar', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

DROP TABLE IF EXISTS `asistencias`;
CREATE TABLE IF NOT EXISTS `asistencias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `profesor_id` int NOT NULL,
  `fecha` date NOT NULL,
  `articulo_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_profesor_fecha` (`profesor_id`,`fecha`),
  KEY `fk_asistencia_articulo` (`articulo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `profesor_id`, `fecha`, `articulo_id`) VALUES
(12, 2, '2026-05-05', 1),
(13, 7, '2026-05-04', 1),
(14, 2, '2026-05-08', 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias_auxiliares`
--

DROP TABLE IF EXISTS `asistencias_auxiliares`;
CREATE TABLE IF NOT EXISTS `asistencias_auxiliares` (
  `id` int NOT NULL AUTO_INCREMENT,
  `auxiliar_id` int NOT NULL,
  `fecha` date NOT NULL,
  `articulo_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_auxiliar_fecha` (`auxiliar_id`,`fecha`),
  KEY `fk_asistencia_aux_articulo` (`articulo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auxiliares`
--

DROP TABLE IF EXISTS `auxiliares`;
CREATE TABLE IF NOT EXISTS `auxiliares` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dni` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `cuil` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titulo` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legajo` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dni` (`dni`),
  UNIQUE KEY `idx_cuil` (`cuil`),
  UNIQUE KEY `idx_legajo` (`legajo`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `auxiliares`
--

INSERT INTO `auxiliares` (`id`, `nombre`, `apellido`, `dni`, `fecha_ingreso`, `cuil`, `direccion`, `telefono`, `mail`, `titulo`, `legajo`, `estado`) VALUES
(1, 'Veronica', 'ALTAMIRA', '25124297', '2009-01-01', '27-25124297-1', NULL, NULL, NULL, 'Auxiliar Operativo', NULL, 1),
(2, 'Ana', 'AVILA', '32720250', '2009-01-01', '27-32720250-8', NULL, NULL, NULL, 'Auxiliar Operativo', NULL, 1),
(3, 'Nélida', 'CERDÁ', '21703013', '2001-12-26', '27-21703013-2', NULL, NULL, NULL, 'Auxiliar Administrativo', NULL, 1),
(4, 'Olga Mabel', 'CUAL', '22935212', '2008-02-14', '27-22935212-7', NULL, NULL, NULL, 'Auxiliar Administrativo', NULL, 1),
(5, 'Paola Andrea', 'DELGADO', '25407801', '2011-03-04', '27-25407801-3', NULL, NULL, NULL, 'Auxiliar Operativo', NULL, 1),
(6, 'Segundo', 'IBAÑEZ VALLEJOS', '18778693', '2003-12-15', '20-18778693-3', NULL, NULL, NULL, 'Auxiliar Operativo', NULL, 1),
(7, 'Gustavo', 'KELLER', '29983510', '2009-03-01', '20-29983510-4', NULL, NULL, NULL, 'Auxiliar Operativo', NULL, 1),
(8, 'Maximiliano Nestor', 'LINARES', '34665019', '2009-03-17', '20-34665019-3', NULL, NULL, NULL, 'Auxiliar Administrativo', NULL, 1),
(9, 'Mirta', 'Quilaleo', '17680074', '2009-03-17', '27-17680074-2', NULL, NULL, NULL, 'Auxiliar Operativo', NULL, 1),
(10, 'Cristina', 'RUBILAR', '23241401', '2009-02-12', '23-23241401-4', NULL, NULL, NULL, 'Auxiliar Operativo', NULL, 1),
(11, 'María José', 'TEBES', '26957136', '2011-09-01', '27-26957136-0', NULL, NULL, NULL, 'Auxiliar Administrativo', NULL, 1),
(12, 'Carlos Alberto', 'ILLANES', '17078628', '2010-04-13', '23-17078628-9', NULL, NULL, NULL, 'Auxiliar Administrativo', NULL, 1),
(13, 'Edith', 'OJEDA', '32887816', '2013-08-06', '32887816', NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

DROP TABLE IF EXISTS `cursos`;
CREATE TABLE IF NOT EXISTS `cursos` (
  `idcurso` int NOT NULL AUTO_INCREMENT,
  `curso` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `turno` enum('Mañana','Tarde') COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `estatus` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`idcurso`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`idcurso`, `curso`, `turno`, `estatus`) VALUES
(1, '1° Año 1° Div.', 'Mañana', 1),
(2, '1° Año 2° Div.', 'Mañana', 1),
(3, '1° Año 3° Div.', 'Mañana', 1),
(4, '1° Año 4° Div.', 'Tarde', 1),
(5, '1° Año 5° Div.', 'Tarde', 1),
(6, '1° Año 6° Div.', 'Tarde', 1),
(7, '2° Año 1° Div.', 'Mañana', 1),
(8, '2° Año 2° Div.', 'Mañana', 1),
(9, '2° Año 3° Div.', 'Mañana', 1),
(10, '2° Año 4° Div.', 'Tarde', 1),
(11, '2° Año 5° Div.', 'Tarde', 1),
(12, '2° Año 6° Div.', 'Tarde', 1),
(13, '3° Año 1° Div.', 'Mañana', 1),
(14, '3° Año 2° Div.', 'Mañana', 1),
(15, '3° Año 3° Div.', 'Mañana', 1),
(16, '3° Año 4° Div.', 'Tarde', 1),
(17, '3° Año 5° Div.', 'Tarde', 1),
(18, '3° Año 6° Div.', 'Tarde', 1),
(19, '4° Año 1° Div. ECO', 'Mañana', 1),
(20, '4° Año 2° Div. ECO', 'Tarde', 1),
(21, '4° Año 3° Div. ECO', 'Mañana', 1),
(22, '4° Año 1° Div. HUM', 'Mañana', 1),
(23, '4° Año 2° Div. HUM', 'Tarde', 1),
(24, '5° Año 1° Div. ECO', 'Mañana', 1),
(25, '5° Año 2° Div. ECO', 'Tarde', 1),
(26, '5° Año 3° Div. ECO', 'Mañana', 1),
(27, '5° Año 1° Div. HUM', 'Mañana', 1),
(28, '5° Año 2° Div. HUM', 'Tarde', 1),
(29, '6° Año 1° Div. ECO', 'Mañana', 1),
(30, '6° Año 2° Div. ECO', 'Tarde', 1),
(31, '6° Año 3° Div. ECO', 'Mañana', 1),
(32, '6° Año 1° Div. HUM', 'Mañana', 1),
(33, '6° Año 2° Div. HUM', 'Tarde', 1),
(34, 'Provisorios', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

DROP TABLE IF EXISTS `horarios`;
CREATE TABLE IF NOT EXISTS `horarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `asignacion_id` int NOT NULL,
  `dia_semana` tinyint(1) NOT NULL COMMENT '1=Lunes, 2=Martes, 3=Miercoles, 4=Jueves, 5=Viernes',
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_horario_asignacion` (`asignacion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

DROP TABLE IF EXISTS `materias`;
CREATE TABLE IF NOT EXISTS `materias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(25) NOT NULL,
  `es_especial` tinyint(1) NOT NULL DEFAULT '0',
  `curso` int NOT NULL,
  `iddepartamento` int NOT NULL,
  `estatus` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `curso` (`curso`),
  KEY `iddepartamento` (`iddepartamento`)
) ENGINE=InnoDB AUTO_INCREMENT=384 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id`, `descripcion`, `es_especial`, `curso`, `iddepartamento`, `estatus`) VALUES
(1, 'CIENCIAS SOCIALES', 0, 1, 0, 1),
(2, 'CONSTRUCCION CIUDADANIA', 0, 1, 0, 1),
(3, 'LENGUA Y LITERATURA', 0, 1, 0, 1),
(4, 'MÚSICA', 0, 1, 0, 1),
(5, 'ARTES VISUALES', 0, 1, 0, 1),
(6, 'INGLÉS', 0, 1, 0, 1),
(7, 'EDUCACIÓN FÍSICA', 1, 1, 0, 1),
(8, 'CIENCIAS NATURALES', 0, 1, 0, 1),
(9, 'MATEMÁTICA', 0, 1, 0, 1),
(10, 'EDUC. TECNOLÓGICA', 0, 1, 0, 1),
(11, 'EIS', 0, 1, 0, 1),
(12, 'CIENCIAS SOCIALES', 0, 2, 0, 1),
(13, 'CONSTRUCCION CIUDADANIA', 0, 2, 0, 1),
(14, 'LENGUA Y LITERATURA', 0, 2, 0, 1),
(15, 'MÚSICA', 0, 2, 0, 1),
(16, 'ARTES VISUALES', 0, 2, 0, 1),
(17, 'INGLÉS', 0, 2, 0, 1),
(18, 'EDUCACIÓN FÍSICA', 1, 2, 0, 1),
(19, 'CIENCIAS NATURALES', 0, 2, 0, 1),
(20, 'MATEMÁTICA', 0, 2, 0, 1),
(21, 'EDUC. TECNOLÓGICA', 0, 2, 0, 1),
(22, 'EIS', 0, 2, 0, 1),
(23, 'CIENCIAS SOCIALES', 0, 3, 0, 1),
(24, 'CONSTRUCCION CIUDADANIA', 0, 3, 0, 1),
(25, 'LENGUA Y LITERATURA', 0, 3, 0, 1),
(26, 'MÚSICA', 0, 3, 0, 1),
(27, 'ARTES VISUALES', 0, 3, 0, 1),
(28, 'INGLÉS', 0, 3, 0, 1),
(29, 'EDUCACIÓN FÍSICA', 1, 3, 0, 1),
(30, 'CIENCIAS NATURALES', 0, 3, 0, 1),
(31, 'MATEMÁTICA', 0, 3, 0, 1),
(32, 'EDUC. TECNOLÓGICA', 0, 3, 0, 1),
(33, 'EIS', 0, 3, 0, 1),
(34, 'CIENCIAS SOCIALES', 0, 4, 0, 1),
(35, 'CONSTRUCCION CIUDADANIA', 0, 4, 0, 1),
(36, 'LENGUA Y LITERATURA', 0, 4, 0, 1),
(37, 'MÚSICA', 0, 4, 0, 1),
(38, 'ARTES VISUALES', 0, 4, 0, 1),
(39, 'INGLÉS', 0, 4, 0, 1),
(40, 'EDUCACIÓN FÍSICA', 1, 4, 0, 1),
(41, 'CIENCIAS NATURALES', 0, 4, 0, 1),
(42, 'MATEMÁTICA', 0, 4, 0, 1),
(43, 'EDUC. TECNOLÓGICA', 0, 4, 0, 1),
(44, 'EIS', 0, 4, 0, 1),
(45, 'CIENCIAS SOCIALES', 0, 5, 0, 1),
(46, 'CONSTRUCCION CIUDADANIA', 0, 5, 0, 1),
(47, 'LENGUA Y LITERATURA', 0, 5, 0, 1),
(48, 'MÚSICA', 0, 5, 0, 1),
(49, 'ARTES VISUALES', 0, 5, 0, 1),
(50, 'INGLÉS', 0, 5, 0, 1),
(51, 'EDUCACIÓN FÍSICA', 1, 5, 0, 1),
(52, 'CIENCIAS NATURALES', 0, 5, 0, 1),
(53, 'MATEMÁTICA', 0, 5, 0, 1),
(54, 'EDUC. TECNOLÓGICA', 0, 5, 0, 1),
(55, 'EIS', 0, 5, 0, 1),
(56, 'CIENCIAS SOCIALES', 0, 6, 0, 1),
(57, 'CONSTRUCCION CIUDADANIA', 0, 6, 0, 1),
(58, 'LENGUA Y LITERATURA', 0, 6, 0, 1),
(59, 'MÚSICA', 0, 6, 0, 1),
(60, 'ARTES VISUALES', 0, 6, 0, 1),
(61, 'INGLÉS', 0, 6, 0, 1),
(62, 'EDUCACIÓN FÍSICA', 1, 6, 0, 1),
(63, 'CIENCIAS NATURALES', 0, 6, 0, 1),
(64, 'MATEMÁTICA', 0, 6, 0, 1),
(65, 'EDUC. TECNOLÓGICA', 0, 6, 0, 1),
(66, 'EIS', 0, 6, 0, 1),
(67, 'CIENCIAS SOCIALES', 0, 7, 0, 1),
(68, 'CONSTRUCCION CIUDADANA', 0, 7, 0, 1),
(69, 'LENGUA Y LITERATURA', 0, 7, 0, 1),
(70, 'ARTES VISUALES', 0, 7, 0, 1),
(71, 'INGLES', 0, 7, 0, 1),
(72, 'EDUCACIÓN FÍSICA', 1, 7, 0, 1),
(73, 'CIENCIAS NATURALES', 0, 7, 0, 1),
(74, 'MATEMÁTICA', 0, 7, 0, 1),
(75, 'EDUC. TECNOLÓGICA', 0, 7, 0, 1),
(76, 'EIS', 0, 7, 0, 1),
(77, 'CIENCIAS SOCIALES', 0, 8, 0, 1),
(78, 'CONSTRUCCION CIUDADANA', 0, 8, 0, 1),
(79, 'LENGUA Y LITERATURA', 0, 8, 0, 1),
(80, 'ARTES VISUALES', 0, 8, 0, 1),
(81, 'INGLES', 0, 8, 0, 1),
(82, 'EDUCACIÓN FÍSICA', 1, 8, 0, 1),
(83, 'CIENCIAS NATURALES', 0, 8, 0, 1),
(84, 'MATEMÁTICA', 0, 8, 0, 1),
(85, 'EDUC. TECNOLÓGICA', 0, 8, 0, 1),
(86, 'EIS', 0, 8, 0, 1),
(87, 'CIENCIAS SOCIALES', 0, 9, 0, 1),
(88, 'CONSTRUCCION CIUDADANA', 0, 9, 0, 1),
(89, 'LENGUA Y LITERATURA', 0, 9, 0, 1),
(90, 'ARTES VISUALES', 0, 9, 0, 1),
(91, 'INGLES', 0, 9, 0, 1),
(92, 'EDUCACIÓN FÍSICA', 1, 9, 0, 1),
(93, 'CIENCIAS NATURALES', 0, 9, 0, 1),
(94, 'MATEMÁTICA', 0, 9, 0, 1),
(95, 'EDUC. TECNOLÓGICA', 0, 9, 0, 1),
(96, 'EIS', 0, 9, 0, 1),
(97, 'CIENCIAS SOCIALES', 0, 10, 0, 1),
(98, 'CONSTRUCCION CIUDADANA', 0, 10, 0, 1),
(99, 'LENGUA Y LITERATURA', 0, 10, 0, 1),
(100, 'ARTES VISUALES', 0, 10, 0, 1),
(101, 'INGLES', 0, 10, 0, 1),
(102, 'EDUCACIÓN FÍSICA', 1, 10, 0, 1),
(103, 'CIENCIAS NATURALES', 0, 10, 0, 1),
(104, 'MATEMÁTICA', 0, 10, 0, 1),
(105, 'EDUC. TECNOLÓGICA', 0, 10, 0, 1),
(106, 'EIS', 0, 10, 0, 1),
(107, 'CIENCIAS SOCIALES', 0, 11, 0, 1),
(108, 'CONSTRUCCION CIUDADANA', 0, 11, 0, 1),
(109, 'LENGUA Y LITERATURA', 0, 11, 0, 1),
(110, 'ARTES VISUALES', 0, 11, 0, 1),
(111, 'INGLES', 0, 11, 0, 1),
(112, 'EDUCACIÓN FÍSICA', 1, 11, 0, 1),
(113, 'CIENCIAS NATURALES', 0, 11, 0, 1),
(114, 'MATEMÁTICA', 0, 11, 0, 1),
(115, 'EDUC. TECNOLÓGICA', 0, 11, 0, 1),
(116, 'EIS', 0, 11, 0, 1),
(117, 'CIENCIAS SOCIALES', 0, 12, 0, 1),
(118, 'CONSTRUCCION CIUDADANA', 0, 12, 0, 1),
(119, 'LENGUA Y LITERATURA', 0, 12, 0, 1),
(120, 'ARTES VISUALES', 0, 12, 0, 1),
(121, 'INGLES', 0, 12, 0, 1),
(122, 'EDUCACIÓN FÍSICA', 1, 12, 0, 1),
(123, 'CIENCIAS NATURALES', 0, 12, 0, 1),
(124, 'MATEMÁTICA', 0, 12, 0, 1),
(125, 'EDUC. TECNOLÓGICA', 0, 12, 0, 1),
(126, 'EIS', 0, 12, 0, 1),
(127, 'HISTORIA', 0, 13, 0, 1),
(128, 'GEOGRAFIA', 0, 13, 0, 1),
(129, 'CONST. CIUDADANA', 0, 13, 0, 1),
(130, 'LENGUA Y LITERATURA', 0, 13, 0, 1),
(131, 'MÚSICA', 0, 13, 0, 1),
(132, 'INGLÉS', 0, 13, 0, 1),
(133, 'EDUC. FÍSICA', 1, 13, 0, 1),
(134, 'BIOLOGIA', 0, 13, 0, 1),
(135, 'FÍSICO-QUÍMICA', 0, 13, 0, 1),
(136, 'MATEMÁTICA', 0, 13, 0, 1),
(137, 'EDUC. TECNOLÓGICA', 0, 13, 0, 1),
(138, 'EIS', 0, 13, 0, 1),
(139, 'HISTORIA', 0, 14, 0, 1),
(140, 'GEOGRAFIA', 0, 14, 0, 1),
(141, 'CONST. CIUDADANA', 0, 14, 0, 1),
(142, 'LENGUA Y LITERATURA', 0, 14, 0, 1),
(143, 'MÚSICA', 0, 14, 0, 1),
(144, 'INGLÉS', 0, 14, 0, 1),
(145, 'EDUC. FÍSICA', 1, 14, 0, 1),
(146, 'BIOLOGIA', 0, 14, 0, 1),
(147, 'FÍSICO-QUÍMICA', 0, 14, 0, 1),
(148, 'MATEMÁTICA', 0, 14, 0, 1),
(149, 'EDUC. TECNOLÓGICA', 0, 14, 0, 1),
(150, 'EIS', 0, 14, 0, 1),
(151, 'HISTORIA', 0, 15, 0, 1),
(152, 'GEOGRAFIA', 0, 15, 0, 1),
(153, 'CONST. CIUDADANA', 0, 15, 0, 1),
(154, 'LENGUA Y LITERATURA', 0, 15, 0, 1),
(155, 'MÚSICA', 0, 15, 0, 1),
(156, 'INGLÉS', 0, 15, 0, 1),
(157, 'EDUC. FÍSICA', 1, 15, 0, 1),
(158, 'BIOLOGIA', 0, 15, 0, 1),
(159, 'FÍSICO-QUÍMICA', 0, 15, 0, 1),
(160, 'MATEMÁTICA', 0, 15, 0, 1),
(161, 'EDUC. TECNOLÓGICA', 0, 15, 0, 1),
(162, 'EIS', 0, 15, 0, 1),
(163, 'HISTORIA', 0, 16, 0, 1),
(164, 'GEOGRAFIA', 0, 16, 0, 1),
(165, 'CONST. CIUDADANA', 0, 16, 0, 1),
(166, 'LENGUA Y LITERATURA', 0, 16, 0, 1),
(167, 'MÚSICA', 0, 16, 0, 1),
(168, 'INGLÉS', 0, 16, 0, 1),
(169, 'EDUC. FÍSICA', 1, 16, 0, 1),
(170, 'BIOLOGIA', 0, 16, 0, 1),
(171, 'FÍSICO-QUÍMICA', 0, 16, 0, 1),
(172, 'MATEMÁTICA', 0, 16, 0, 1),
(173, 'EDUC. TECNOLÓGICA', 0, 16, 0, 1),
(174, 'EIS', 0, 16, 0, 1),
(175, 'HISTORIA', 0, 17, 0, 1),
(176, 'GEOGRAFIA', 0, 17, 0, 1),
(177, 'CONST. CIUDADANA', 0, 17, 0, 1),
(178, 'LENGUA Y LITERATURA', 0, 17, 0, 1),
(179, 'MÚSICA', 0, 17, 0, 1),
(180, 'INGLÉS', 0, 17, 0, 1),
(181, 'EDUC. FÍSICA', 1, 17, 0, 1),
(182, 'BIOLOGIA', 0, 17, 0, 1),
(183, 'FÍSICO-QUÍMICA', 0, 17, 0, 1),
(184, 'MATEMÁTICA', 0, 17, 0, 1),
(185, 'EDUC. TECNOLÓGICA', 0, 17, 0, 1),
(186, 'EIS', 0, 17, 0, 1),
(187, 'HISTORIA', 0, 18, 0, 1),
(188, 'GEOGRAFIA', 0, 18, 0, 1),
(189, 'CONST. CIUDADANA', 0, 18, 0, 1),
(190, 'LENGUA Y LITERATURA', 0, 18, 0, 1),
(191, 'MÚSICA', 0, 18, 0, 1),
(192, 'INGLÉS', 0, 18, 0, 1),
(193, 'EDUC. FÍSICA', 1, 18, 0, 1),
(194, 'BIOLOGIA', 0, 18, 0, 1),
(195, 'FÍSICO-QUÍMICA', 0, 18, 0, 1),
(196, 'MATEMÁTICA', 0, 18, 0, 1),
(197, 'EDUC. TECNOLÓGICA', 0, 18, 0, 1),
(198, 'EIS', 0, 18, 0, 1),
(199, 'HISTORIA', 0, 19, 0, 1),
(200, 'GEOGRAFÍA', 0, 19, 0, 1),
(201, 'CONST. CIUDADANA', 0, 19, 0, 1),
(202, 'LENGUA Y LITERATURA', 0, 19, 0, 1),
(203, 'INGLÉS', 0, 19, 0, 1),
(204, 'LENG. ARTÍSTICOS', 0, 19, 0, 1),
(205, 'EDUC. FÍSICA', 1, 19, 0, 1),
(206, 'BIOLOGÍA', 0, 19, 0, 1),
(207, 'QUÍMICA', 0, 19, 0, 1),
(208, 'MATEMÁTICA', 0, 19, 0, 1),
(209, 'MICROECONOMÍA', 0, 19, 0, 1),
(210, 'ADM. DE LAS ORG.', 0, 19, 0, 1),
(211, 'DERECHO', 0, 19, 0, 1),
(212, 'HISTORIA', 0, 20, 0, 1),
(213, 'GEOGRAFÍA', 0, 20, 0, 1),
(214, 'CONST. CIUDADANA', 0, 20, 0, 1),
(215, 'LENGUA Y LITERATURA', 0, 20, 0, 1),
(216, 'INGLÉS', 0, 20, 0, 1),
(217, 'LENG. ARTÍSTICOS', 0, 20, 0, 1),
(218, 'EDUC. FÍSICA', 1, 20, 0, 1),
(219, 'BIOLOGÍA', 0, 20, 0, 1),
(220, 'QUÍMICA', 0, 20, 0, 1),
(221, 'MATEMÁTICA', 0, 20, 0, 1),
(222, 'MICROECONOMÍA', 0, 20, 0, 1),
(223, 'ADM. DE LAS ORG.', 0, 20, 0, 1),
(224, 'DERECHO', 0, 20, 0, 1),
(225, 'HISTORIA', 0, 21, 0, 1),
(226, 'GEOGRAFÍA', 0, 21, 0, 1),
(227, 'CONST. CIUDADANA', 0, 21, 0, 1),
(228, 'LENGUA Y LITERATURA', 0, 21, 0, 1),
(229, 'INGLÉS', 0, 21, 0, 1),
(230, 'LENG. ARTÍSTICOS', 0, 21, 0, 1),
(231, 'EDUC. FÍSICA', 1, 21, 0, 1),
(232, 'BIOLOGÍA', 0, 21, 0, 1),
(233, 'QUÍMICA', 0, 21, 0, 1),
(234, 'MATEMÁTICA', 0, 21, 0, 1),
(235, 'MICROECONOMÍA', 0, 21, 0, 1),
(236, 'ADM. DE LAS ORG.', 0, 21, 0, 1),
(237, 'DERECHO', 0, 21, 0, 1),
(238, 'HISTORIA', 0, 22, 0, 1),
(239, 'GEOGRAFÍA', 0, 22, 0, 1),
(240, 'CONST. CIUDADANIA', 0, 22, 0, 1),
(241, 'LENGUA Y LITERATURA', 0, 22, 0, 1),
(242, 'INGLÉS', 0, 22, 0, 1),
(243, 'LENG. ARTÍSTICOS', 0, 22, 0, 1),
(244, 'EDUC. FÍSICA', 1, 22, 0, 1),
(245, 'BIOLOGÍA', 0, 22, 0, 1),
(246, 'QUÍMICA', 0, 22, 0, 1),
(247, 'MATEMÁTICA', 0, 22, 0, 1),
(248, 'SOCIOLOGÍA', 0, 22, 0, 1),
(249, 'HISTORIA', 0, 23, 0, 1),
(250, 'GEOGRAFÍA', 0, 23, 0, 1),
(251, 'CONST. CIUDADANIA', 0, 23, 0, 1),
(252, 'LENGUA Y LITERATURA', 0, 23, 0, 1),
(253, 'INGLÉS', 0, 23, 0, 1),
(254, 'LENG. ARTÍSTICOS', 0, 23, 0, 1),
(255, 'EDUC. FÍSICA', 1, 23, 0, 1),
(256, 'BIOLOGÍA', 0, 23, 0, 1),
(257, 'QUÍMICA', 0, 23, 0, 1),
(258, 'MATEMÁTICA', 0, 23, 0, 1),
(259, 'SOCIOLOGÍA', 0, 23, 0, 1),
(260, 'HISTORIA', 0, 24, 0, 1),
(261, 'GEOGRAFÍA', 0, 24, 0, 1),
(262, 'CIUD. Y PARTICIPACIÓN', 0, 24, 0, 1),
(263, 'PROY. SOLIDARIO', 0, 24, 0, 1),
(264, 'LENGUA Y LITERATURA', 0, 24, 0, 1),
(265, 'INGLÉS', 0, 24, 0, 1),
(266, 'EDUC. FÍSICA', 1, 24, 0, 1),
(267, 'BIOLOGÍA', 0, 24, 0, 1),
(268, 'FÍSICA', 0, 24, 0, 1),
(269, 'MATEMÁTICA', 0, 24, 0, 1),
(270, 'ECONOMÍA', 0, 24, 0, 1),
(271, 'COMERCIAL. Y MARKETING', 0, 24, 0, 1),
(272, 'COM. INF. ORG.', 0, 24, 0, 1),
(273, 'RR HH', 0, 24, 0, 1),
(274, 'HISTORIA', 0, 25, 0, 1),
(275, 'GEOGRAFÍA', 0, 25, 0, 1),
(276, 'CIUD. Y PARTICIPACIÓN', 0, 25, 0, 1),
(277, 'PROY. SOLIDARIO', 0, 25, 0, 1),
(278, 'LENGUA Y LITERATURA', 0, 25, 0, 1),
(279, 'INGLÉS', 0, 25, 0, 1),
(280, 'EDUC. FÍSICA', 1, 25, 0, 1),
(281, 'BIOLOGÍA', 0, 25, 0, 1),
(282, 'FÍSICA', 0, 25, 0, 1),
(283, 'MATEMÁTICA', 0, 25, 0, 1),
(284, 'ECONOMÍA', 0, 25, 0, 1),
(285, 'COMUN. Y MARKETING', 0, 25, 0, 1),
(286, 'COM. INF. ORG.', 0, 25, 0, 1),
(287, 'RR HH', 0, 25, 0, 1),
(288, 'HISTORIA', 0, 26, 0, 1),
(289, 'GEOGRAFÍA', 0, 26, 0, 1),
(290, 'CIUD. Y PARTICIPACIÓN', 0, 26, 0, 1),
(291, 'PROY. SOLIDARIO', 0, 26, 0, 1),
(292, 'LENGUA Y LITERATURA', 0, 26, 0, 1),
(293, 'INGLÉS', 0, 26, 0, 1),
(294, 'EDUC. FÍSICA', 1, 26, 0, 1),
(295, 'BIOLOGÍA', 0, 26, 0, 1),
(296, 'FÍSICA', 0, 26, 0, 1),
(297, 'MATEMÁTICA', 0, 26, 0, 1),
(298, 'ECONOMÍA', 0, 26, 0, 1),
(299, 'COMUN. Y MARKETING', 0, 26, 0, 1),
(300, 'COM. INF. ORG.', 0, 26, 0, 1),
(301, 'RR HH', 0, 26, 0, 1),
(302, 'HISTORIA', 0, 27, 0, 1),
(303, 'GEOGRAFÍA', 0, 27, 0, 1),
(304, 'CIUD. Y PARTICIPACIÓN', 0, 27, 0, 1),
(305, 'PROYECTO SOLIDARIO', 0, 27, 0, 1),
(306, 'LENGUA Y LITERATURA', 0, 27, 0, 1),
(307, 'INGLÉS', 0, 27, 0, 1),
(308, 'BIOLOGÍA', 0, 27, 0, 1),
(309, 'FÍSICA', 0, 27, 0, 1),
(310, 'MATEMÁTICA', 0, 27, 0, 1),
(311, 'EDUC. FÍSICA', 1, 27, 0, 1),
(312, 'PSICOLOGÍA', 0, 27, 0, 1),
(313, 'MET. INV.', 0, 27, 0, 1),
(314, 'HISTORIA', 0, 28, 0, 1),
(315, 'GEOGRAFÍA', 0, 28, 0, 1),
(316, 'CIUD. Y PARTICIPACIÓN', 0, 28, 0, 1),
(317, 'PROYECTO SOLIDARIO', 0, 28, 0, 1),
(318, 'LENGUA Y LITERATURA', 0, 28, 0, 1),
(319, 'INGLÉS', 0, 28, 0, 1),
(320, 'BIOLOGÍA', 0, 28, 0, 1),
(321, 'FÍSICA', 0, 28, 0, 1),
(322, 'MATEMÁTICA', 0, 28, 0, 1),
(323, 'EDUC. FÍSICA', 1, 28, 0, 1),
(324, 'PSICOLOGÍA', 0, 28, 0, 1),
(325, 'MET. INV.', 0, 28, 0, 1),
(326, 'PROBL. M. ACTUAL Y CONT.', 0, 29, 0, 1),
(327, 'ECONOMÍA', 0, 29, 0, 1),
(328, 'FIOLOSOFÍA', 0, 29, 0, 1),
(329, 'CIUD. Y TRABAJO', 0, 29, 0, 1),
(330, 'PROY. VOCACIONAL', 0, 29, 0, 1),
(331, 'LENGUA Y LITERATURA', 0, 29, 0, 1),
(332, 'INGLÉS', 0, 29, 0, 1),
(333, 'EDUC. FÍSICA', 1, 29, 0, 1),
(334, 'PROB. CONT. CS. NAT.', 0, 29, 0, 1),
(335, 'MATEMÁTICA', 0, 29, 0, 1),
(336, 'MACROECONOMÍA', 0, 29, 0, 1),
(337, 'EMP. SOCIO PRODUCTIVO', 0, 29, 0, 1),
(338, 'PROBL. M. ACTUAL Y CONT.', 0, 30, 0, 1),
(339, 'ECONOMÍA', 0, 30, 0, 1),
(340, 'FIOLOSOFÍA', 0, 30, 0, 1),
(341, 'CIUD. Y TRABAJO', 0, 30, 0, 1),
(342, 'PROY. VOCACIONAL', 0, 30, 0, 1),
(343, 'LENGUA Y LITERATURA', 0, 30, 0, 1),
(344, 'INGLÉS', 0, 30, 0, 1),
(345, 'EDUC. FÍSICA', 1, 30, 0, 1),
(346, 'PROB. CONT. CS. NAT.', 0, 30, 0, 1),
(347, 'MATEMÁTICA', 0, 30, 0, 1),
(348, 'MACROECONOMÍA', 0, 30, 0, 1),
(349, 'EMP. SOCIO PRODUCTIVO', 0, 30, 0, 1),
(350, 'PROBL. M. ACTUAL Y CONT.', 0, 31, 0, 1),
(351, 'ECONOMÍA', 0, 31, 0, 1),
(352, 'FIOLOSOFÍA', 0, 31, 0, 1),
(353, 'CIUD. Y TRABAJO', 0, 31, 0, 1),
(354, 'PROY. VOCACIONAL', 0, 31, 0, 1),
(355, 'LENGUA Y LITERATURA', 0, 31, 0, 1),
(356, 'INGLÉS', 0, 31, 0, 1),
(357, 'EDUC. FÍSICA', 1, 31, 0, 1),
(358, 'PROB. CONT. CS. NAT.', 0, 31, 0, 1),
(359, 'MATEMÁTICA', 0, 31, 0, 1),
(360, 'MACROECONOMÍA', 0, 31, 0, 1),
(361, 'EMP. SOCIO PRODUCTIVO', 0, 31, 0, 1),
(362, 'PROB. ACT. MUNDO CONT.', 0, 32, 0, 1),
(363, 'ECONOMÍA', 0, 32, 0, 1),
(364, 'FIOLOSOFÍA', 0, 32, 0, 1),
(365, 'CIUD. Y TRABAJO', 0, 32, 0, 1),
(366, 'LENGUA Y LITERATURA', 0, 32, 0, 1),
(367, 'PROY. VOCACIONAL', 0, 32, 0, 1),
(368, 'INGLÉS', 0, 32, 0, 1),
(369, 'PROB. CONT. CS. NAT.', 0, 32, 0, 1),
(370, 'MATEMÁTICA', 0, 32, 0, 1),
(371, 'EDUC. FÍSICA', 1, 32, 0, 1),
(372, 'PROY. INVESTIFACIÓN', 0, 32, 0, 1),
(373, 'PROB. ACT. MUNDO CONT.', 0, 33, 0, 1),
(374, 'ECONOMÍA', 0, 33, 0, 1),
(375, 'FIOLOSOFÍA', 0, 33, 0, 1),
(376, 'CIUD. Y TRABAJO', 0, 33, 0, 1),
(377, 'LENGUA Y LITERATURA', 0, 33, 0, 1),
(378, 'PROY. VOCACIONAL', 0, 33, 0, 1),
(379, 'INGLÉS', 0, 33, 0, 1),
(380, 'PROB. CONT. CS. NAT.', 0, 33, 0, 1),
(381, 'MATEMÁTICA', 0, 33, 0, 1),
(382, 'EDUC. FÍSICA', 1, 33, 0, 1),
(383, 'PROY. INVESTIGACIÓN', 0, 33, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

DROP TABLE IF EXISTS `profesores`;
CREATE TABLE IF NOT EXISTS `profesores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dni` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuil` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titulo` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legajo` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dni` (`dni`),
  UNIQUE KEY `idx_cuil` (`cuil`),
  UNIQUE KEY `idx_legajo` (`legajo`)
) ENGINE=InnoDB AUTO_INCREMENT=377 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id`, `nombre`, `apellido`, `dni`, `cuil`, `direccion`, `telefono`, `mail`, `titulo`, `legajo`, `estado`) VALUES
(1, 'SERGIO FABIAN', 'MONTANARES', '12121212', '11-12121212-1', '', '', '', '', '', 1),
(2, 'Emilia', 'ABRAHAM', '33392800', '27-33392800-6', NULL, NULL, NULL, NULL, '686', 1),
(3, 'MATIAS EZEQUIEL', 'ABRIGO', '38046352', '20-38046352-1', '', '', '', '', '1194', 1),
(6, 'RUTH DANIELA', 'AGUERO', '32568693', '27-32568693-1', '', '', '', '', '1193', 1),
(7, 'Jorge Hernán', 'AGUIRRE', '32935239', '20-32935239-1', '', '', '', '', '959', 1),
(8, 'MARIANO', 'ALBERTOLI', '3', '99262', NULL, NULL, NULL, NULL, '1', 1),
(9, 'BRENDA AYELEN', 'ALCAPAN', '4', '4', '', '', '', '', '2', 1),
(10, 'Mirla Sofía (', 'ALDERETE', '5', '5', '', '', '', '', '3', 1),
(11, 'María Virginia', 'ALEJANDRO', '6', '6', '', '', '', '', '4', 1),
(12, 'Verónica', 'ALTAMIRANTA', '25124297', '27-25124297-1', '', '', '', '', '656', 1),
(13, 'Silvina ( Art.29 d)', 'ALVARENGA MORENO', '33878810', '27-33878810-5', '', '', '', '', '935', 1),
(14, 'CANDELA ANGELA', 'AMARATO', '7', '7', '', '', '', '', '5', 1),
(17, 'Flavia', 'AMARILLA', '29260342', '27-29260342-3', '', '', '', '', '659', 1),
(18, 'Erica Magali', 'AMBROGIO', '30054789', '27-30054789-9', '', '', '', '', '792', 1),
(19, 'BRENDA DEYANIRA', 'AMPUERO', '8', '8', '', '', '', '', '6', 1),
(20, 'GABRIELA JUDITH ( LIC,29 D)', 'ARAGON, GABRIELA JUDITH ( LIC', '31318320', '27-31318320-9', '', '', '', '', '1115', 1),
(21, 'Germán José', 'ARAGÓN', '9', '9', '', '', '', '', '7', 1),
(22, 'Mariano Agustín', 'ARANDA', '36970657', '20-36970657-9', '', '', '', '', '1123', 1),
(23, 'Laura Graciela', 'ARFELLI', '26518571', '20-26518571-2', '', '', '', '', '1128', 1),
(24, 'Carolina Anahí', 'ARGÜERO', '31256176', '27-31256176-5', '', '', '', '', '777', 1),
(25, 'Ornella ( LIC. 29 D)', 'ARIAS', '10', '10', '', '', '', '', '8', 1),
(26, 'Ornella Belén', 'ARIAS', '11', '11', '', '', '', '', '9', 1),
(27, 'Natalia Gabriela (Lic 12 b)', 'ARRIOLA', '12', '12', '', '', '', '', '10', 1),
(28, 'Daiana Soledad', 'ARROQUI', '33392754', '27-33392754-9', '', '', '', '', '825', 1),
(29, 'Marcela R.A', 'ASTETE THOMAS', '21806498', '27-21806498-7', '', '', '', '', '693', 1),
(30, 'Ana María', 'ÁVILA', '20806183', '27-20806183-1', '', '', '', '', '657', 1),
(31, 'Jesica Yanina', 'AVILA', '32720250', '27-32720250-8', '', '', '', '', '1255', 1),
(32, 'FERNANDO', 'BAEZA BAEZA', '13', '13', '', '', '', '', '11', 1),
(33, 'Karen Yanina', 'BAEZA PAVEZ', '14', '14', '', '', '', '', '12', 1),
(34, 'DARIO EMANUEL', 'BAEZA', '32246391', '20-32246391-0', '', '', '', '', '1198', 1),
(35, 'FLORENCIA', 'BARBATO', '15', '15', '', '', '', '', '13', 1),
(37, 'Norma Alejandra', 'BARBOSA', '21959064', '23-21959064-4', '', '', '', '', '557', 1),
(38, 'Leonardo Marcelo', 'BARBOZA', '20858349', '17', '', '', '', '', '812', 1),
(39, 'Alan', 'BARRA', '17', '18', '', '', '', '', '14', 1),
(40, 'Moira G.', 'BARRERA ORTIZ', '27841287', '27-27841287-9', '', '', '', '', '559', 1),
(41, 'Adriana ( SE JUBILO)', 'BARRERA', '22091128', '23-22091128-4', '', '', '', '', '368', 1),
(42, 'Gloria Noemí ( ART. 29 a)', 'BARRERA', '24403490', '27-24403490-5', '', '', '', '', '512', 1),
(43, 'Jorge Luis (Lic Art. 30)', 'BARRIONUEVO', '27525932', '20-27525932-3', '', '', '', '', '643', 1),
(44, 'Silvana Elizabeth', 'BARROS', '35257658', '27-35257658-7', '', '', '', '', '817', 1),
(45, 'Mónica Beatriz (Cambio Func)', 'BASSANI', '20541600', '27-20541600-0', '', '', '', '', '538', 1),
(46, 'Martín Antonio ( SE JUBILO)', 'BAZÁN', '18', '19', '', '', '', '', '15', 1),
(47, 'Virginia Isabel', 'BERRA', '24245510', '27-24245510-5', '', '', '', '', '1259', 1),
(48, 'LUCIANA', 'BERTORINI', '25082886', '27-25082886-7', '', '', '', '', '1262', 1),
(49, 'María Agustina', 'BIDERA', '38805920', '27-38805920-1', '', '', '', '', '1168', 1),
(50, 'Javier', 'BILLODAS', '23600837', '20-23600837-2', '', '', '', '', '574', 1),
(51, 'LAURA AGUSTINA', 'BON GARCIA', '19', '20', '', '', '', '', '16', 1),
(52, 'Agustín', 'BONATO', '36212602', '23-36212602-9', '', '', '', '', '1045', 1),
(53, 'María de los Ángeles (Lic. Art. 29)', 'BOVERO', '20', '21', '', '', '', '', '17', 1),
(56, 'Silvana', 'BOVERO', '26544327', '27-26544327-9', '', '', '', '', '1094', 1),
(57, 'Sandra Graciela (ART. 29 d)', 'BOZZANI', '22', '24', '', '', '', '', '18', 1),
(58, 'Marcela Alejandra', 'BRANDAN', '38800287', '27-38800287-0', '', '', '', '', '1155', 1),
(59, 'Milagros del Valle', 'BRITEZ', '39265702', '27-39265702-4', '', '', '', '', '1016', 1),
(60, 'Hugo Oscar', 'BRUNELLI', '23', '25', '', '', '', '', '19', 1),
(61, 'Darío Fernando (Com. De Servicios)', 'BURGOS', '26067652', '20-26067652-1', '', '', '', '', '419', 1),
(62, 'Micaela Soledad', 'CABAÑA', '29157636', '27-29157636-8', '', '', '', '', '892', 1),
(63, 'Valentina', 'CABRAL D`AMBROSIO', '198', '26', '', '', '', '', '20', 1),
(64, 'Paola Yanina (Dispos. STES/RW', 'CAMACHO SOLÍS', '26607692', '23-26607692-4', '', '', '', '', '907', 1),
(65, 'Janet Magalí', 'CANESSA', '24', '27', '', '', '', '', '21', 1),
(66, 'Santiago', 'CANTEROS', '36970392', '20-36970392-8', '', '', '', '', '1186', 1),
(67, 'Carina Ayelén', 'CAÑIO', '28949497', '27-28949497-4', '', '', '', '', '640', 1),
(68, 'Ignacio Leonel ( LIC. L.P.)', 'CARABALLO', '41017281', '20-41017281-0', '', '', '', '', '1258', 1),
(69, 'ARIANA', 'CARBALLO MALANYE', '25', '28', '', '', '', '', '22', 1),
(70, 'Ilén Micaela', 'CARBALLO', '26', '29', '', '', '', '', '23', 1),
(71, 'Natalia ( ART 29d)', 'CARDOZO', '29653280', '27-29653280-6', '', '', '', '', '1036', 1),
(72, 'Juan José', 'CARRAZANA', '37498284', '20-37498284-3', '', '', '', '', '1008', 1),
(73, 'Maria José', 'CARRERAS FERNANDEZ', '27', '30', '', '', '', '', '24', 1),
(74, 'YANINA,AYELEN', 'CASTILLO YANINA', '28', '31', '', '', '', '', '25', 1),
(75, 'Bárbara Desirée', 'CASTILLO', '29', '32', '', '', '', '', '26', 1),
(76, 'Ma. Victoria', 'CASTILLO', '23578804', '27-23578804-2', '', '', '', '', '666', 1),
(77, 'LUCIA', 'CASTRO CODESAL', '40872065', '27-40872065-1', '', '', '', '', '1247', 1),
(78, 'Andrés Alberto (LIC 29 d)', 'CASTRO', '30', '33', '', '', '', '', '27', 1),
(79, 'NATALIA LORENA', 'CASTRO', '29763903', '27-29763903-5', '', '', '', '', '1221', 1),
(80, 'SUSANA BEATRIZ.', 'CASUSCELLI', '31', '34', '', '', '', '', '28', 1),
(81, 'Fabiana Elisabeth', 'CAYUPIL', '37909590', '27-37909590-4', '', '', '', '', '1139', 1),
(83, 'José Luis .', 'CENDRA', '31504939', '20-31504939-4', '', '', '', '', '717', 1),
(84, 'Viviana Cristina', 'CENTURIÓN', '34265012', '27-34265012-6', '', '', '', '', '1263', 1),
(85, 'Nélida', 'CERDÁ', '21703013', '27-21703013-2', '', '', '', '', '1156', 1),
(86, 'Celina', 'CERULLO', '33', '36', '', '', '', '', '29', 1),
(87, 'Stella Maris (Lic. Art. 29º)', 'CHAILE', '34', '37', '', '', '', '', '30', 1),
(88, 'Silvana Micaela', 'CHANADO', '38244034', '24-38244034-6', '', '', '', '', '1211', 1),
(89, 'FLORENCIA BELEN', 'CHAVEZ', '42698213', '27-42698213-2', '', '', '', '', '1201', 1),
(90, 'José Antonio', 'CHAVEZ', '22606005', '20-22606005-8', '', '', '', '', '974', 1),
(91, 'Fernanda', 'CHOMÓN', '22260532', '27-22260532-1', '', '', '', '', '1096', 1),
(92, 'Paola A. (Art. 29º)', 'CICCARONE', '20568212', '27-20568212-6', '', '', '', '', '591', 1),
(93, 'Luciana Alejandra', 'CICCONE', '35428705', '27-35428705-1', '', '', '', '', '1269', 1),
(94, 'Ramiro', 'CLARO', '29037354', '23-29037354-9', '', '', '', '', '690', 1),
(95, 'Silvia Noemi', 'COLINA', '42716113', '27-42716113-2', '', '', '', '', '1264', 1),
(96, 'ANGELA ( ART. 29 d)', 'COLPI', '30580234', '23-30580234-4', '', '', '', '', '1127', 1),
(97, 'Julia', 'CONSTANTINOFF', '35', '38', '', '', '', '', '31', 1),
(98, 'CANDELA VIVIANA.', 'CORIA', '36', '27-42267554-5', '', '', '', '', '1230', 1),
(99, 'M. Algustina', 'CORNELIO OLSINA', '36757045', '27-36757045-3', '', '', '', '', '1216', 1),
(100, 'Alicia Viviana', 'CORREA', '28054684', '23-28054684-4', '', '', '', '', '582', 1),
(101, 'Juan Ramón', 'CORTEZ', '25970777', '20-25970777-4', '', '', '', '', '553', 1),
(102, 'NESTOR FABIAN ( DISP)', 'CRETTON', '20238873', '20-20238873-7', '', '', '', '', '851', 1),
(103, 'Olga Mabel', 'CUAL', '22935212', '27-22935212-7', '', '', '', '', '681', 1),
(104, 'KAREN', 'CUELLO', '37498703', '27-37498703-3', '', '', '', '', '1048', 1),
(105, 'María Laura', 'CULASSO', '22568860', '27-22568860-0', '', '', '', '', '414', 1),
(106, 'Yanina Elizabeth', 'CURTIDO', '38190384', '27-38190384-8', '', '', '', '', '991', 1),
(107, 'MARISA CRISTINA', 'D\'AGOSTINO', '22244550', '27-22244550-2', '', '', '', '', '896', 1),
(108, 'Daniel', 'DE BRITO', '37', '39', '', '', '', '', '32', 1),
(109, 'Lautaro (Lic. 29 d)', 'DE HERNÁNDEZ', '27092518', '23-27092518-9', '', '', '', '', '1044', 1),
(110, 'Guadalupe(29 \"d\")', 'DE LA BARRERA', '35720561', '27-35720561-7', '', '', '', '', '1164', 1),
(111, 'María Fernanda (Lic. Art. 29º)', 'DEFEA', '23906835', '27-23906835-4', '', '', '', '', '391', 1),
(112, 'Paola Andrea', 'DELGADO', '25407801', '27-25407801-3', '', '', '', '', '1188', 1),
(113, 'Romina Belén', 'DELGADO', '29983789', '27-29983789-6', '', '', '', '', '903', 1),
(114, 'Carlos Daniel', 'DIAZ', '38', '40', '', '', '', '', '33', 1),
(115, 'Tamara Vanina', 'DIAZ', '39900173', '27-39900173-6', '', '', '', '', '1236', 1),
(116, 'Juan Ariel', 'DUARTE', '39', '41', '', '', '', '', '34', 1),
(117, 'Eugenia Stella', 'DUARTE', '26063522', '27-26063522-6', '', '', '', '', '872', 1),
(118, 'Néstor Omar', 'EAGNEY', '27377020', '24-27377020-4', '', '', '', '', '649', 1),
(119, 'Natalia Romina', 'ELORZA', '40', '42', '', '', '', '', '35', 1),
(120, 'Jairo Rodrigo', 'EPULEF', '33771129', '20-33771129-5', '', '', '', '', '1021', 1),
(121, 'JORDANA', 'ERCORECA LAURIENTE', '36650709', '23-36650709-4', '', '', '', '', '1192', 1),
(122, 'Bryan Pablo', 'ESCALONA URRUTIA', '37550541', '20-37550541-0', '', '', '', '', '1270', 1),
(123, 'Luana Ayelén', 'FACCIUTTO', '41', '43', '', '', '', '', '36', 1),
(124, 'Susana Carolina (Lic 29 d)', 'FARÍAS', '28680294', '27-28680294-5', '', '', '', '', '1092', 1),
(125, 'Miriam Verónica', 'FERNÁNDEZ', '24811384', '27-24811384-2', '', '', '', '', '625', 1),
(127, 'Martina', 'FERRER PETRICCA', '33704889', '27-33704889-2', '', '', '', '', '850', 1),
(128, 'Victoria María', 'FLORES', '42', '44', '', '', '', '', '37', 1),
(129, 'Emanuel (Art. 29º)', 'FOSSATI', '36475039', '20-36475039-1', '', '', '', '', '1038', 1),
(130, 'Sofía', 'FOSSATI', '33771836', '27-33771836-7', '', '', '', '', '841', 1),
(131, 'GUSTAVO', 'FREEMAN CATIVA', '43', '45', '', '', '', '', '38', 1),
(132, 'Héctor Fabio', 'FREEMAN', '44', '46', '', '', '', '', '39', 1),
(133, 'Gabriela (Com. De Serv.)', 'GALARZA', '17552357', '27-17552357-5', '', '', '', '', '580', 1),
(134, 'Rolando', 'GALLINATTI', '18027278', '20-18027278-0', '', '', '', '', '466', 1),
(135, 'Luciana Huanelén (Lic 29 d)', 'GALVÁN', '33287149', '27-33287149-3', '', '', '', '', '887', 1),
(136, 'Brian Marcelo', 'GANGA', '45', '47', '', '', '', '', '40', 1),
(137, 'BELKIS GISEL', 'GANGAS', '46', '48', '', '', '', '', '41', 1),
(138, 'ANALIA MONICA', 'GARCIA', '47', '49', '', '', '', '', '42', 1),
(139, 'Flavia Romina', 'GARCÍA', '48', '50', '', '', '', '', '43', 1),
(140, 'ESTEBAN ADRIAN', 'GARRO', '49', '51', '', '', '', '', '44', 1),
(141, 'Leonardo', 'GELVEZ', '27841112', '20-27841112-6', '', '', '', '', '1034', 1),
(142, 'HUGO DEL JESUS', 'GEREZ', '38721164', '20-38721164-1', '', '', '', '', '1237', 1),
(143, 'Ivana Melina (Lic. 29 d)', 'GOMBAC', '27363841', '27-27363841-0', '', '', '', '', '1047', 1),
(144, 'Haydée', 'GÓMEZ ALCORTA', '50', '52', '', '', '', '', '45', 1),
(145, 'Carlos Gaspar', 'GOMEZ', '51', '53', '', '', '', '', '46', 1),
(146, 'CELESTE FIORELLA', 'GOMEZ', '36813795', '27-36813795-8', '', '', '', '', '1197', 1),
(147, 'PABLO NICOLES', 'GONZALEZ MOREL', '52', '54', '', '', '', '', '47', 1),
(148, 'IVAN MARIANO', 'GONZALEZ', '38193142', '20-38193142-1', '', '', '', '', '1250', 1),
(149, 'CRISTINA LORENA', 'GOÑI', '23514914', '27-23514914-7', '', '', '', '', '1229', 1),
(150, 'Luis Alberto', 'GRAMAJO', '53', '55', '', '', '', '', '48', 1),
(151, 'Malvina', 'GRIMOLICHE', '25974340', '27-25974340-6', '', '', '', '', '790', 1),
(152, 'Natalia', 'GUAYMAS', '54', '56', '', '', '', '', '49', 1),
(153, 'Delma Marisa', 'GUTIÉRREZ', '21959016', '23-21959016-4', '', '', '', '', '970', 1),
(154, 'Vanina Yasmín (Resolución/JCD)', 'GUTIÉRREZ', '29260352', '27-29260352-0', '', '', '', '', '783', 1),
(155, 'ANTONELLA JAZMIN', 'GUZMAN', '41041415', '27-41041415-0', '', '', '', '', '1219', 1),
(156, 'Claudia Mabel', 'GUZMÁN', '21970059', '27-21970059-3', '', '', '', '', '573', 1),
(157, 'Claudia Mabel (Art. 29 a)', 'GUZMÁN', '55', '57', '', '', '', '', '50', 1),
(158, 'HELEN JOHANA', 'GUZMAN', '40385065', '27-40385065-4', '', '', '', '', '1246', 1),
(159, 'Zaida Solange', 'GUZMÁN', '33771048', '23-33771048-474', '', '', '', '', '51', 1),
(160, 'MARIA SOL', 'HERNANDEZ', '32983583', '27-32983583-4', '', '', '', '', '1144', 1),
(161, 'Segundo', 'IBAÑEZ VALLEJOS', '18778693', '20-18778693-3', '', '', '', '', '509', 1),
(162, 'CARLOS ALBERTO.', 'ILLANES', '17078628', '23-17078628-9', '', '', '', '', '1232', 1),
(163, 'Carla Romina ( ART. 29)', 'IRALDE', '23998736', '27-23998736-8', '', '', '', '', '445', 1),
(164, 'Lara Tiziana', 'IRIBARREN', '56', '58', '', '', '', '', '52', 1),
(165, 'Omar Alberto', 'JAIME', '20441588', '23-20441588-9', '', '', '', '', '309', 1),
(166, 'Ana Cecilia', 'JARAMILLO', '29645966', '27-29645966-1', '', '', '', '', '1081', 1),
(167, 'Gloria Magalí (Art. 29º)', 'JARAMILLO', '57', '59', '', '', '', '', '53', 1),
(168, 'Laura Isabel', 'JARAMILLO', '24926650', '27-24926650-2', '', '', '', '', '1152', 1),
(169, 'Lorena Patricia (Art 29 d)', 'JARAMILLO', '27363218', '27-27363218-8', '', '', '', '', '740', 1),
(170, 'Silvina Véronica', 'JARAMILLO', '29091854', '27-29091854-0', '', '', '', '', '1254', 1),
(171, 'Vilma Nanci', 'JONES', '21993838', '27-21993838-7', '', '', '', '', '274', 1),
(173, 'Gustavo Daniel', 'KELLER', '29983510', '20-29983510-4', '', '', '', '', '673', 1),
(174, 'Walter Guillermo ( ART. 29)', 'KRUSE', '26067361', '20-26067361-1', '', '', '', '', '756', 1),
(175, 'Laura ( SE JUBILO)', 'LANDÍVAR', '22495646', '27-22495646-6', '', '', '', '', '293', 1),
(176, 'Cecilia Analía', 'LEDESMA', '30432876', '27-30432876', '', '', '', '', '1145', 1),
(177, 'KAREN MARIEL', 'LEGUIZAMON', '38890566', '27-38890566-8', '', '', '', '', '1208', 1),
(178, 'Nancy', 'LEGUIZAMON', '26257199', '27-26257199-3', '', '', '', '', '834', 1),
(179, 'Aldana Sofía LIC ( 25)', 'LEON', '36002245', '27-36002245-0', '', '', '', '', '1062', 1),
(180, 'Lisandro (Disp 16/23 STESRW)', 'LERTORA', '29577707', '23-29577707-9', '', '', '', '', '898', 1),
(181, 'Claudia Roxana', 'LETELIER', '21661489', '27-21661489-0', '', '', '', '', '1163', 1),
(182, 'YAEL TAMAR', 'LEZCANO', '59', '61', '', '', '', '', '54', 1),
(183, 'Germán Edgardo (Com. Serv.)', 'LINARES', '60', '62', '', '', '', '', '55', 1),
(184, 'Gonzalo', 'LINARES', '61', '63', '', '', '', '', '56', 1),
(185, 'Néstor Maximiliano ( ART. 20 a)', 'LINARES', '34665019', '20-34665019-3', '', '', '', '', '672', 1),
(186, 'Rosendo Damián (SE JUBILO)', 'LIZAMA', '22495610', '20-22495610-0', '', '', '', '', '478', 1),
(188, 'Claudia Saraí', 'LLANQUETRÚ DÍAZ', '36392918', '23-36392918-4', '', '', '', '', '1178', 1),
(189, 'SABRINA ANTONELLA', 'LOPEZ ALDERETE', '63', '65', '', '', '', '', '57', 1),
(190, 'MATIAS', 'LOPEZ', '64', '66', '', '', '', '', '58', 1),
(191, 'Matías', 'LÓPEZ', '65', '67', '', '', '', '', '59', 1),
(192, 'Zulema M. Danisa LIC ( 12 A)', 'LOZANO', '35436300', '27-35436300-9', '', '', '', '', '1088', 1),
(193, 'Maribel Ailén (Art. 29 d)', 'LUNA', '22074516', '27-22074516-9', '', '', '', '', '552', 1),
(194, 'Pablo', 'MACIEL', '30855138', '20-30855138-6', '', '', '', '', '996', 1),
(195, 'Andrea Verónica', 'MAIDANA', '23741590', '27-23741590-1', '', '', '', '', '590', 1),
(196, 'Rubén Ernesto (Art. 30 a)', 'MAINECUL', '25656663', '20-25656663-0', '', '', '', '', '562', 1),
(197, 'JUAN CRUZ', 'MALERBA', '66', '68', '', '', '', '', '60', 1),
(198, 'José Martín', 'MALNERO', '67', '69', '', '', '', '', '61', 1),
(199, 'Alejandro Miguel', 'MAMANÍ', '29735567', '20-29735567-9', '', '', '', '', '947', 1),
(200, 'MARIA ALEJANDRA', 'MANSILLA', '41057567', '27-41057567-7', '', '', '', '', '1200', 1),
(201, 'Brenda Lucìa', 'MANSILLA', '34706467', '27-34706467-5', '', '', '', '', '62', 1),
(202, 'Melissa Aldana', 'MANSILLA', '39581570', '27-39581570-4', '', '', '', '', '1228', 1),
(203, 'DANIEL ALEJANDRO', 'MARECHAL', '68', '70', '', '', '', '', '63', 1),
(204, 'Maite Oriana', 'MARIN', '38806051', '23-38806051-4', '', '', '', '', '1143', 1),
(205, 'Valeria Priscila (Lic. Art. 29º)', 'MÁRQUEZ', '69', '71', '', '', '', '', '64', 1),
(206, 'Ariadna Maite', 'MARTIN', '26889157', '27-26889157-4', '', '', '', '', '1242', 1),
(207, 'Viviana', 'MARTINEZ LARANGA', '25407882', '23-25407882-4', '', '', '', '', '527', 1),
(208, 'Sergio Luis', 'MARTINEZ', '28745233', '20-28745233-1', '', '', '', '', '780', 1),
(209, 'Romina Marianella (Lic 29 c)', 'MARTINI', '32246456', '27-32246456-3', '', '', '', '', '605', 1),
(210, 'CAMILA', 'MATSCHKE', '70', '72', '', '', '', '', '65', 1),
(211, 'Carolina', 'MELGAREJO', '71', '', '', '', '', '', '66', 1),
(212, 'Juan Marcelo (Comisión de Servicios)', 'MELGAREJO', '944', '73', '', '', '', '', '382', 1),
(213, 'CLARISA ANALIA', 'MENDEZ', '72', '74', '', '', '', '', '67', 1),
(214, 'Marcela (Lic. Art. 29 a)', 'MESA', '73', '75', '', '', '', '', '68', 1),
(215, 'Raúl', 'MOGGIANO', '32142502', '20-32142502-0', '', '', '', '', '816', 1),
(216, 'Mercedes Edith', 'MONTEROS', '74', '76', '', '', '', '', '69', 1),
(217, 'Lucia', 'MORALES', '75', '77', '', '', '', '', '70', 1),
(218, 'Sabrina Natlia', 'MORALES', '33774042', '27-33774042-7', '', '', '', '', '1235', 1),
(219, 'María Cecilia', 'MORENO', '28799970', '23-28799970-4', '', '', '', '', '1075', 1),
(220, 'Sara Beatriz', 'MORGAN', '22453732', '27-22453732-3', '', '', '', '', '985', 1),
(221, 'Fátima', 'MOYANO', '76', '78', '', '', '', '', '126', 1),
(222, 'Miguel ( ART.. 29 d)', 'MUÑOZ', '25138127', '20-25138127-6', '', '', '', '', '449', 1),
(223, 'Cintia Gisela', 'NAVAS', '77', '79', '', '', '', '', '71', 1),
(224, '29 d)', 'NEIRA, GUSTAVO MARTÍN ( ART.', '29983566', '23-29983566-9', '', '', '', '', '519', 1),
(225, 'Mariana Andrea', 'NIEVA', '31504709', '27-31504709-4', '', '', '', '', '687', 1),
(227, 'Matías Nicolás', 'NOCETI', '79', '81', '', '', '', '', '72', 1),
(228, 'MARIA ELENA', 'NUÑEZ', '80', '82', '', '', '', '', '73', 1),
(229, 'Carlos Martín Orlando', 'ÑÁÑEZ', '81', '83', '', '', '', '', '74', 1),
(230, 'Susana Mabel', 'OCARES', '22934753', '27-22934753-0', '', '', '', '', '1070', 1),
(231, 'BERENICE LIC 12A)', 'OLGUIN OYARZO', '38147644', '27-38147644-3', '', '', '', '', '1195', 1),
(232, 'Carina Susana', 'OLGUIN', '82', '84', '', '', '', '', '75', 1),
(233, 'Carina Susana', 'OLGUÍN', '23058179', '27-23058179-2', '', '', '', '', '410', 1),
(234, 'Graciela Beatriz (Lic Art 29 a)', 'OLLER', '83', '85', '', '', '', '', '76', 1),
(235, 'DIEGO', 'ORFILA', '28708234', '20-28708234-8', '', '', '', '', '856', 1),
(236, 'Ariana Jazmín', 'OROSCO', '84', '86', '', '', '', '', '77', 1),
(237, 'Cintia Rene', 'ORTEGA', '26288082', '27-26288082-1', '', '', '', '', '787', 1),
(238, 'Delicia Lujan', 'ORTUSTE', '35600930', '23-35600930-4', '', '', '', '', '1256', 1),
(239, 'Marina Noemí', 'OVIEDO', '85', '87', '', '', '', '', '78', 1),
(240, 'Guillermo Enrique (Dispos. 38/18 STES)', 'OYARZO', '294', '24929437', '', '', '', '', '579', 1),
(241, 'SOFIA BELE', 'PAREDES', '86', '88', '', '', '', '', '79', 1),
(242, 'Catalina Elizabeth', 'PATIÑO', '25279026', '27-25279026-3', '', '', '', '', '928', 1),
(243, 'Cristian Hernán', 'PAURA', '24449116', '20-24449116-3', '', '', '', '', '496', 1),
(244, 'Liliana Beatriz', 'PAYALEF', '20808814', '27-20808814-4', '', '', '', '', '587', 1),
(245, 'Diego Mauricio ( ART. 30 a)', 'PEDROSO', '87', '20-30544995-5', '', '', '', '', '813', 1),
(246, 'Sandra Leonor', 'PEDROSO', '88', '89', '', '', '', '', '80', 1),
(247, 'Rocío Marcela .', 'PERALTA', '41500201', '27-41500201-2', '', '', '', '', '1175', 1),
(248, 'ROXANA BEATRIZ', 'PERALTA', '89', '90', '', '', '', '', '81', 1),
(249, 'Ana Florencia (Lic 29 a)', 'PERATA', '90', '91', '', '', '', '', '82', 1),
(250, 'RODRIGO GABRIEL', 'PEREYRA', '91', '93', '', '', '', '', '83', 1),
(251, 'Marianela( Art. 29d)', 'PÉREZ BETANCES', '24204945', '23-24204945-4', '', '', '', '', '428', 1),
(252, 'Ioana Ornella (Lic Art. 29)', 'PICCARDINI', '25407983', '27-25407983-4', '', '', '', '', '564', 1),
(253, 'Antonella ( ART 29 d)', 'PIÑERO', '38535588', '27-38535588-8', '', '', '', '', '976', 1),
(254, 'Dina Mariana', 'PIZZOGLIO', '27042893', '27-27042893-8', '', '', '', '', '992', 1),
(255, 'Francisco', 'PLIVELIC', '17857140', '20-17857140-1', '', '', '', '', '685', 1),
(256, 'Antonella Rocío', 'PORFIRI', '33345119', '27-33345119-6', '', '', '', '', '1109', 1),
(257, 'Malena Florencia', 'PORTILLO', '40737070', '27-40737070-3', '', '', '', '', '1101', 1),
(258, 'Benjamín Guillermo (Art. 29)', 'PRADO', '25668134', '20-25668134-0', '', '', '', '', '890', 1),
(259, 'MIRTA ESTER MARIA', 'PRADO', '92', '94', '', '', '', '', '84', 1),
(260, 'Romina Silvana(Lic. Art. 29º)', 'PUCHATT', '27418175', '27-27418175-9', '', '', '', '', '641', 1),
(261, 'Agustina Mariel', 'PUGH', '35099375', '23-35099375-4', '', '', '', '', '1268', 1),
(262, 'ANA LAURA', 'PULZONI CABRERA', '93', '95', '', '', '', '', '85', 1),
(263, 'Margarita Mirta', 'QUILALEO', '17680074', '27-17680074-2', '', '', '', '', '726', 1),
(264, 'Laura Cecilia', 'QUINTRQUEO', '94', '96', '', '', '', '', '86', 1),
(266, 'Roberto Matías', 'RAMALLAL', '33611235', '20-33611235-5', '', '', '', '', '953', 1),
(267, 'Delfina Alicia', 'RAMÍREZ', '31847931', '27-31847931-9', '', '', '', '', '575', 1),
(268, 'Sandra Mabel', 'REARTE', '18557342', '27-18557342-2', '', '', '', '', '1039', 1),
(269, 'Stella Maris', 'REINHEIMER', '29983562', '27-29983562-1', '', '', '', '', '950', 1),
(270, 'Verónica C.S)', 'RENDON MOREIRA', '18895220', '27-18895220-3', '', '', '', '', '869', 1),
(271, 'Beatriz Deolinda (Lic 29 d)', 'RIFO', '23065228', '27-23065228-2', '', '', '', '', '882', 1),
(272, 'Alan Ezequiel', 'RÍOS', '95', '2578', '', '', '', '', '87', 1),
(273, 'DEBORA PAMELA', 'RIQUELME', '35887463', '27-35887463-6', '', '', '', '', '1218', 1),
(274, 'Mauricio Adrián', 'RIQUELME', '34665021', '20-34665021-5', '', '', '', '', '889', 1),
(275, 'Karina Lorena', 'RIVERA', '27363849', '27-27363849-6', '', '', '', '', '949', 1),
(276, 'Paola', 'RIVERO', '96', '97', '', '', '', '', '88', 1),
(277, 'Malen', 'ROBERT', '97', '98', '', '', '', '', '89', 1),
(278, 'Jessica Mariela LIC. 29)', 'ROBERTS', '29463041', '23-29463041-4', '', '', '', '', '728', 1),
(279, 'Andrea Janet (Lic 12 A)', 'RODRÍGUEZ', '33392572', '27-33392572-4', '', '', '', '', '1111', 1),
(280, 'Emiliana', 'RODRÍGUEZ', '29736548', '27-29736548-2', '', '', '', '', '1153', 1),
(281, 'Gustavo', 'RODRÍGUEZ', '25510139', '20-25510139-1', '', '', '', '', '639', 1),
(282, 'Florencia Belén', 'ROGEL', '35887811', '27-35887811-9', '', '', '', '', '944', 1),
(283, 'Crhistian Andrés', 'ROJAS', '98', '100', '', '', '', '', '90', 1),
(284, 'Guadalupe Velia', 'ROJAS', '99', '101', '', '', '', '', '91', 1),
(285, 'Sandra (Lic Art 29)', 'ROLDÁN', '21993839', '27-21993839-5', '', '', '', '', '839', 1),
(286, 'María Cristina', 'ROLON', '3058203', '23-3058203-4', '', '', '', '', '642', 1),
(287, 'NESTOR FABIAN.', 'ROMAN', '36956731', '20-36956731-5', '', '', '', '', '1265', 1),
(288, 'LUCAS EXEQUIEL.', 'ROMANO', '39357035', '20-39357035-1', '', '', '', '', '1240', 1),
(289, 'Leandro Ángel', 'ROMERO', '34931441', '20-34931441-0', '', '', '', '', '1026', 1),
(290, 'Elbio David', 'ROSALES', '23325100', '20-23325100-4', '', '', '', '', '426', 1),
(291, 'Evelyn Alejandra', 'ROWLANDS', '100', '102', '', '', '', '', '92', 1),
(292, 'Pamela Elizabeth', 'ROWLANDS', '101', '104', '', '', '', '', '93', 1),
(293, 'Cristina Ester', 'RUBILAR', '23241401', '103', '', '', '', '', '763', 1),
(294, 'FACUNDO ESTEBAN', 'RUBILAR', '102', '108', '', '', '', '', '94', 1),
(295, 'Luciano Damian', 'RUIZ DIAZ', '41041265', '23-41041265-9', '', '', '', '', '1260', 1),
(296, 'Silvia Valeria', 'RUIZ', '27384746', '23-27384746-4', '', '', '', '', '702', 1),
(298, 'MARIA ELENA', 'SAAVEDRA', '20843735', '27-20843735-1', '', '', '', '', '1202', 1),
(299, 'Daiana', 'SAIBIENE', '35171543', '27-35171543-5', '', '', '', '', '1190', 1),
(300, 'Tatiana Beatriz', 'SALDÍVAR', '104', '110', '', '', '', '', '95', 1),
(301, 'Edgardo Alejandro (Lic Art 29)', 'SALOMÓN', '105', '111', '', '', '', '', '96', 1),
(302, 'Agustina Andrea', 'SANCHEZ', '37860761', '27-37860761-8', '', '', '', '', '1187', 1),
(305, 'Andrea Patricia (Lic. Art. 29º en hs)', 'SÁNCHEZ', '26078543', '27-26078543-0', '', '', '', '', '612', 1),
(306, 'BARBARA SOLEDAD', 'SANCHEZ', '107', '113', '', '', '', '', '97', 1),
(307, 'Fabiana Lucía', 'SCHIAFFINO', '108', '114', '', '', '', '', '98', 1),
(308, 'Yamila Estefanía', 'SEGOVIA', '38714253', '27-38714253-9', '', '', '', '', '1120', 1),
(309, 'VALERIA KARINA', 'SEGUNDO', '28390102', '27-28390102-0', '', '', '', '', '1106', 1),
(310, 'Daniela Sabrina', 'SEPULVEDA', '34275931', '27-34275931-4', '', '', '', '', '1184', 1),
(311, 'Jessica Luciano', 'SOSA', '32887743', '27-32887743-6', '', '', '', '', '1234', 1),
(312, 'Lorena Veronica', 'SOTO', '24307439', '27-24307439-3', '', '', '', '', '1257', 1),
(313, 'ANDRES GASTON', 'SPUR', '28482147', '20-28482147-6', '', '', '', '', '1233', 1),
(314, 'Fernando', 'SUAREZ CORONEL', '34198325', '20-34198325-9', '', '', '', '', '1126', 1),
(315, 'TORRES DEMIAN MATIAS', 'SUAREZ', '109', '115', '', '', '', '', '99', 1),
(316, 'CARLA ANTONELLA', 'TAPIA', '37067788', '27-37067788-9', '', '', '', '', '1238', 1),
(317, 'Cinthia Andrea', 'TAPIA', '38147724', '27-38147724-5', '', '', '', '', '1252', 1),
(318, 'María José', 'TEBES', '26957136', '27-26957136-0', '', '', '', '', '1159', 1),
(319, 'María Ayelén (CF)', 'TENORIO', '34665284', '27-34665284-0', '', '', '', '', '796', 1),
(320, 'Agustín Pablo (DISp esp)', 'TERESA GARCÍA', '110', '116', '', '', '', '', '100', 1),
(321, 'Héctor Aníbal', 'TESSARO', '111', '117', '', '', '', '', '101', 1),
(322, 'Carlos Adrián', 'TISSERA', '24518662', '20-24518662-3', '', '', '', '', '940', 1),
(323, 'Ernestina (Lic 29)', 'TORRISI', '37067983', '27-37067983-0', '', '', '', '', '1133', 1),
(324, 'Mariela Janet', 'UGARTE', '31261352', '27-31261352-8', '', '', '', '', '732', 1),
(325, 'Paula (Lic 29)', 'ULARIAGA', '27667722', '27-27667722-0', '', '', '', '', '1119', 1),
(326, 'LOHANA FERNANDA', 'UMBIDES', '40052385', '27-40052385-7', '', '', '', '', '1243', 1),
(327, 'LOS ANFELES', 'URQUIJO', '112', '118', '', '', '', '', '102', 1),
(328, 'Sonia Cristina', 'VALQUÍN', '29493924', '27-29493924-0', '', '', '', '', '699', 1),
(329, 'Gustavo', 'VAZQUEZ JUNG', '38226816', '20-38226816-5', '', '', '', '', '936', 1),
(332, 'Gabriela Analía', 'VÁZQUEZ', '23065156', '27-23065156-1', '', '', '', '', '684', 1),
(333, 'Washington Michel', 'VAZQUEZ', '38046318', '20-38046318-1', '', '', '', '', '1013', 1),
(334, 'Jorge D', 'VEGA LLANCABURE', '32801301', '20-32801301-1', '', '', '', '', '1057', 1),
(335, 'Nahir', 'VERA JONES', '37970129', '27-37970129-4', '', '', '', '', '1074', 1),
(336, 'Paola A. (Art. 29º)', 'VICENTE', '926', '27092643', '', '', '', '', '416', 1),
(338, 'Luis Alejandro (LIC Art. 29)', 'VIDAL GALLARDO', '114', '120', '', '', '', '', '103', 1),
(339, 'Juan Carlos (Lic Art. 29)', 'VILLALBA', '115', '121', '', '', '', '', '104', 1),
(340, 'Miguel Ángel ( LIC. 29D)', 'VILLALBA', '25052441', '20-25052441-3', '', '', '', '', '986', 1),
(341, 'Norma Viviana', 'VILLALBA', '23912649', '27-23912649-4', '', '', '', '', '1041', 1),
(342, 'MARTA LILIAN', 'VILLANNI ARRECHEA', '22388328', '27-22388328-7', '', '', '', '', '539', 1),
(343, 'Carol', 'VILLANUEVA', '27092535', '27-27092535-4', '', '', '', '', '824', 1),
(344, 'Jorge', 'WIENER', '28880532', '20-28880532-7', NULL, '2804847619', NULL, NULL, '1112', 1),
(345, 'Alexis Camilo ( C.F)', 'WILLIAMS', '23114656', '20-23114656-4', '', '', '', '', '347', 1),
(346, 'Marta Ester( SE JUBILO', 'WILLIAMS', '22260472', '27-22260472-4', '', '', '', '', '436', 1),
(347, 'Rodolfo ( DISP.63/23)', 'YORIO', '24133592', '20-24133592-6', '', '', '', '', '311', 1),
(348, 'Julieta (Com. Serv.)', 'ZÁRATE MARTÍNEZ', '35219047', '27-35219047-6', '', '', '', '', '948', 1),
(349, 'ALEJANDRA VANESSA ( LIC 29 D)', 'ZOLOA', '728', '35172882', '', '', '', '', '1223', 1),
(350, 'SELVA ( LIC. 29 A)', 'ZUNINO', '24921410', '27-24921410-3', '', '', '', '', '608', 1),
(351, 'Enzo', 'CORIA', '990', '32399088', '', '', '', '', '1271', 1),
(352, 'DIEGO', 'BENAVIDEZ', '650', '34665090', '', '', '', '', '1275', 1),
(353, 'ANDRA PAULA', 'SAMANA GRANEROS', '895623', '36.037.132', '', '', '', '', '1276', 1),
(354, 'FLAVIO', 'QUIROGA', '392', '33.392.662', '', '', '', '', '1277', 1),
(355, 'ROCÍO BELÉN', 'MALDONADO', '925', '33392508', '', '', '', '', '1279', 1),
(356, 'EDITH GENOVEVA', 'OJEDA', '878', '32887816', '', '', '', '', '105', 1),
(357, 'MATIAS', 'SANDOVAL', '363', '31136342', '', '', '', '', '106', 1),
(358, 'MICAELA DE LOS ANGELÉS', 'DIAZ', '8956478778', '42803158', '', '', '', '', '1280', 1),
(359, 'ANA VICTORIA', 'LOPEZ', '781256', '38803908', '', '', '', '', '1281', 1),
(360, 'ROCIO BELEN', 'ORTEGA', '879846516', '38801130', '', '', '', '', '1282', 1),
(361, 'SOL', 'CRUZ, CAMILA', '32132134', '38802901', '', '', '', '', '1283', 1),
(362, 'WALTER SANTIAGO', 'LLANOS', '187321', '36610986', '', '', '', '', '1284', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_materia`
--

DROP TABLE IF EXISTS `profesor_materia`;
CREATE TABLE IF NOT EXISTS `profesor_materia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `profesor_id` int NOT NULL,
  `materia_id` int NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_pm_profesor` (`profesor_id`),
  KEY `fk_pm_materia` (`materia_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `profesor_materia`
--

INSERT INTO `profesor_materia` (`id`, `profesor_id`, `materia_id`, `estado`) VALUES
(1, 13, 5, 1),
(2, 13, 16, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('admin','Administrativo','usuario') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usuario',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `rol`, `estado`) VALUES
(1, 'admin', '$2y$10$v/gK5jKBsn3OitwBiV3EnOpcG7zzZzQ.Tvgl2Nwoa6DltwNpUL4r2', 'admin', 1),
(2, 'Usuario', '$2y$10$aA9ijS1uyaxAQOGwzPr5eeawuB1wOReVHO6EuHX5//ZuBk46KEg1e', 'usuario', 1),
(3, 'majo', '$2y$10$fyudWF8cVOyN/xLDXVzHdOfAdXwOJXm.EmATh9KPkkKlfDOrfsL8C', 'Administrativo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones_criterios`
--

DROP TABLE IF EXISTS `vacaciones_criterios`;
CREATE TABLE IF NOT EXISTS `vacaciones_criterios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `anios_min` int NOT NULL,
  `anios_max` int NOT NULL,
  `dias` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vacaciones_criterios`
--

INSERT INTO `vacaciones_criterios` (`id`, `anios_min`, `anios_max`, `dias`) VALUES
(1, 0, 1, 12),
(2, 2, 2, 14),
(3, 3, 3, 16),
(4, 4, 4, 18),
(5, 5, 5, 20),
(6, 6, 10, 25),
(7, 11, 15, 30),
(8, 16, 20, 35),
(9, 21, 25, 40),
(10, 26, 30, 45);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones_tomadas`
--

DROP TABLE IF EXISTS `vacaciones_tomadas`;
CREATE TABLE IF NOT EXISTS `vacaciones_tomadas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `auxiliar_id` int NOT NULL,
  `dias` int NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `anio` int NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `auxiliar_id` (`auxiliar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `fk_asistencia_articulo` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_asistencia_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asistencias_auxiliares`
--
ALTER TABLE `asistencias_auxiliares`
  ADD CONSTRAINT `fk_asistencia_aux_articulo` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_asistencia_auxiliar` FOREIGN KEY (`auxiliar_id`) REFERENCES `auxiliares` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `fk_horario_asignacion` FOREIGN KEY (`asignacion_id`) REFERENCES `profesor_materia` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
  ADD CONSTRAINT `fk_pm_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pm_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `vacaciones_tomadas`
--
ALTER TABLE `vacaciones_tomadas`
  ADD CONSTRAINT `vacaciones_tomadas_ibfk_1` FOREIGN KEY (`auxiliar_id`) REFERENCES `auxiliares` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
