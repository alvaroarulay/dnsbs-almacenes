-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para inventory
CREATE DATABASE IF NOT EXISTS `inventory` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `inventory`;

-- Volcando estructura para tabla inventory.auxiliar
CREATE TABLE IF NOT EXISTS `auxiliar` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nomaux` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_codcont` int unsigned NOT NULL,
  `condicion` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomaux` (`nomaux`),
  KEY `auxiliar_id_codcont_foreign` (`id_codcont`),
  CONSTRAINT `auxiliar_id_codcont_foreign` FOREIGN KEY (`id_codcont`) REFERENCES `codcont` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla inventory.auxiliar: ~191 rows (aproximadamente)
INSERT INTO `auxiliar` (`id`, `nomaux`, `id_codcont`, `condicion`, `created_at`, `updated_at`) VALUES
	(1, 'ALFOMBRA', 2, 1, NULL, '2025-04-11 20:12:51'),
	(2, 'BANCA', 2, 1, NULL, NULL),
	(4, 'BOTIQUIN', 2, 1, NULL, NULL),
	(5, 'BUZON', 2, 1, NULL, NULL),
	(6, 'CAJONERA', 2, 1, NULL, NULL),
	(7, 'CAJA FUERTE', 2, 1, NULL, NULL),
	(9, 'CASETA POLICIAL', 2, 1, NULL, NULL),
	(10, 'CASILLERO', 2, 1, NULL, NULL),
	(11, 'CHAPA MECANICA', 2, 1, NULL, NULL),
	(12, 'CREDENZA', 2, 1, NULL, NULL),
	(13, 'ESCALERA', 2, 1, NULL, NULL),
	(14, 'ESCRITORIO', 2, 1, NULL, NULL),
	(16, 'ESTANTE', 2, 1, NULL, NULL),
	(17, 'ASPIRADORA', 2, 1, NULL, NULL),
	(18, 'FOTOCOPIADORA', 2, 1, NULL, NULL),
	(19, 'FUENTE DE PODER', 2, 1, NULL, NULL),
	(20, 'GAVETERO', 2, 1, NULL, NULL),
	(21, 'GRAPADORA', 2, 0, NULL, '2025-04-11 20:13:57'),
	(22, 'GUILLOTINA', 2, 1, NULL, NULL),
	(23, 'LAMPARA', 2, 1, NULL, NULL),
	(24, 'LECTOR BIOMETRICO', 2, 1, NULL, NULL),
	(25, 'LECTOR DE COD. DE BARRAS', 2, 1, NULL, NULL),
	(26, 'MAQUINA CALCULADORA', 2, 1, NULL, NULL),
	(27, 'MAQUINA DE ESCRIBIR', 2, 1, NULL, NULL),
	(28, 'MASTIL', 2, 1, NULL, NULL),
	(29, 'MESA', 2, 1, NULL, NULL),
	(30, 'MESON', 2, 1, NULL, NULL),
	(31, 'MODULAR', 2, 1, NULL, NULL),
	(32, 'MUEBLE PARA COMPUTADORA', 2, 1, NULL, NULL),
	(33, 'MUEBLE PARA LLAVES', 2, 1, NULL, NULL),
	(34, 'PANEL INFORMATIVO', 6, 1, NULL, NULL),
	(35, 'PERCHERO', 2, 1, NULL, NULL),
	(36, 'PERFORADORA', 2, 0, NULL, '2025-04-11 20:13:28'),
	(37, 'PICADORA DE PAPEL', 2, 1, NULL, NULL),
	(38, 'REGULADOR DE VOLTAJE', 2, 1, NULL, NULL),
	(39, 'RELOJ TARJETERO', 2, 1, NULL, NULL),
	(40, 'SELLO SECO', 2, 1, NULL, NULL),
	(41, 'SILLA', 2, 1, NULL, NULL),
	(42, 'SILLON', 2, 1, NULL, NULL),
	(43, 'SOFA', 2, 1, NULL, NULL),
	(44, 'TABURETE', 2, 1, NULL, NULL),
	(45, 'VITRINA', 2, 1, NULL, NULL),
	(46, 'LECTOR DE HUELLAS', 2, 1, NULL, NULL),
	(47, 'CENICERO', 2, 1, NULL, NULL),
	(48, 'TRANSFORMADOR', 2, 1, NULL, NULL),
	(49, 'FICHERO', 2, 1, NULL, NULL),
	(50, 'PERSIANA', 2, 1, NULL, NULL),
	(51, 'DISPENSADOR DE AGUA', 2, 1, NULL, NULL),
	(52, 'ANILLADORA', 2, 1, NULL, NULL),
	(53, 'CAJA METALICA', 2, 1, NULL, NULL),
	(54, 'ENGRANPADORA', 2, 0, NULL, '2025-04-11 20:13:38'),
	(55, 'RELOJ BIOMETRICO', 2, 1, NULL, NULL),
	(56, 'ESTUFA', 41, 1, NULL, NULL),
	(57, 'ARMAZON', 2, 1, NULL, NULL),
	(58, 'GRADILLA', 2, 1, NULL, NULL),
	(59, 'ESQUINERO', 2, 1, NULL, NULL),
	(60, 'COCINA', 2, 1, NULL, NULL),
	(61, 'GARRAFA', 2, 1, NULL, NULL),
	(62, 'ADAPTADOR', 5, 1, NULL, NULL),
	(63, 'CÁMARA DE SEGURIDAD', 5, 1, NULL, NULL),
	(64, 'CELULAR', 5, 1, NULL, NULL),
	(65, 'CENTRAL TELEFONICA', 5, 1, NULL, NULL),
	(66, 'CONSOLA', 5, 1, NULL, NULL),
	(67, 'CONSOLA DE OPERADORA', 5, 1, NULL, NULL),
	(68, 'CONSOLA DE PODER', 5, 1, NULL, NULL),
	(69, 'FAXIMILE', 5, 1, NULL, NULL),
	(70, 'GPS', 5, 1, NULL, NULL),
	(71, 'GRABADOR', 5, 1, NULL, NULL),
	(72, 'HANDY', 5, 1, NULL, NULL),
	(73, 'MICROFONO', 5, 1, NULL, NULL),
	(74, 'MULTIPLICADOR', 5, 1, NULL, NULL),
	(75, 'PARLANTE', 5, 1, NULL, NULL),
	(76, 'PARLANTE CPU BAJO', 5, 1, NULL, NULL),
	(77, 'PROCESADOR DE VOZ', 5, 1, NULL, NULL),
	(78, 'RADIO GRABADORA', 5, 1, NULL, NULL),
	(79, 'RADIO TRANSMISOR', 5, 1, NULL, NULL),
	(80, 'APARATO TELEFONICO', 5, 1, NULL, NULL),
	(81, 'TELEFONO FAX', 5, 1, NULL, NULL),
	(82, 'TRANCEIVER', 15, 1, NULL, NULL),
	(83, 'TRANSCEPTOR', 5, 1, NULL, NULL),
	(84, 'AMPLIFICADOR', 6, 1, NULL, NULL),
	(85, 'PIZARRA', 6, 1, NULL, NULL),
	(86, 'PROYECTOR', 6, 1, NULL, NULL),
	(87, 'RADIO', 6, 1, NULL, NULL),
	(88, 'REPRODUCTOR DE DVD', 6, 1, NULL, NULL),
	(89, 'TELEVISOR', 6, 1, NULL, NULL),
	(90, 'REPRODUCTOR DE VHS', 6, 1, NULL, NULL),
	(91, 'PROYECTOR MULTIMEDIA', 6, 1, NULL, NULL),
	(92, 'MAPA', 6, 1, NULL, NULL),
	(93, 'VEHICULO', 8, 1, NULL, NULL),
	(94, 'AMOLADORA', 13, 1, NULL, NULL),
	(95, 'ARCO DE SOLDAR', 13, 1, NULL, NULL),
	(96, 'TALADRO', 13, 1, NULL, NULL),
	(97, 'BLOQUEADOR', 15, 1, NULL, NULL),
	(98, 'CD ROM EXTERNO', 15, 1, NULL, NULL),
	(99, 'COMPUTADORA PORTATIL', 15, 1, NULL, NULL),
	(100, 'CPU', 15, 1, NULL, NULL),
	(101, 'DISCO DURO EXTERNO', 15, 1, NULL, NULL),
	(102, 'ESCANER', 15, 1, NULL, NULL),
	(103, 'ESTABILIZADOR', 15, 0, NULL, '2025-04-11 20:15:01'),
	(104, 'FIREWALL', 15, 1, NULL, NULL),
	(105, 'FLOPPY DISK EXTERNO', 15, 1, NULL, NULL),
	(106, 'GABINETE DE COMUNICACIÓN', 15, 1, NULL, NULL),
	(107, 'IMPRESORA', 15, 1, NULL, NULL),
	(108, 'KVM', 15, 1, NULL, NULL),
	(109, 'MODEM', 15, 1, NULL, NULL),
	(110, 'MONITOR', 15, 1, NULL, NULL),
	(111, 'NOTEBOOK', 15, 1, NULL, NULL),
	(112, 'RACK', 15, 1, NULL, NULL),
	(113, 'ROUTER', 15, 1, NULL, NULL),
	(114, 'SERVIDOR', 15, 1, NULL, NULL),
	(115, 'SWITCH', 15, 1, NULL, NULL),
	(116, 'TARJETAS KIT', 15, 0, NULL, '2025-04-11 20:15:21'),
	(117, 'TECLADO', 15, 0, NULL, '2025-04-11 20:15:13'),
	(118, 'UPS', 15, 1, NULL, NULL),
	(119, 'VIRTUALIZADOR', 15, 1, NULL, NULL),
	(120, 'CAFETERA', 2, 1, NULL, NULL),
	(121, 'CATRE', 2, 1, NULL, NULL),
	(122, 'MICROONDAS', 2, 1, NULL, NULL),
	(123, 'ROPERO', 2, 1, NULL, NULL),
	(124, 'VELADOR', 2, 1, NULL, NULL),
	(125, 'ANTIGUEDADES', 36, 1, NULL, NULL),
	(126, 'LIBRO', 36, 1, NULL, NULL),
	(127, 'CÁMARA', 41, 1, NULL, NULL),
	(128, 'CARRO', 41, 1, NULL, NULL),
	(129, 'EXTINTOR', 41, 1, NULL, NULL),
	(130, 'SOPLADOR', 13, 1, NULL, NULL),
	(131, 'TERMOVENTILADOR', 41, 1, NULL, NULL),
	(132, 'DATA TRANSFER', 15, 1, NULL, NULL),
	(133, 'PATCH PANEL', 15, 1, NULL, NULL),
	(134, 'ACCESS POINT', 15, 1, NULL, NULL),
	(135, 'CAJONERO', 2, 1, NULL, NULL),
	(136, 'DESTRUCTOR DE PAPEL', 2, 1, NULL, NULL),
	(137, 'ECRAN', 6, 1, NULL, NULL),
	(138, 'ESTACION DE TRABAJO', 2, 1, NULL, NULL),
	(139, 'FRIGOBAR', 2, 1, NULL, NULL),
	(140, 'MINI PC', 15, 1, NULL, NULL),
	(141, 'DICCIONARIO', 36, 1, NULL, NULL),
	(142, 'ENCICLOPEDIA', 36, 1, NULL, NULL),
	(143, 'CUADRO', 36, 1, NULL, NULL),
	(144, 'ESTATUA', 36, 1, NULL, NULL),
	(145, 'PINTURA', 36, 1, NULL, NULL),
	(146, 'RELOJ', 2, 1, NULL, NULL),
	(147, 'EXTRACTOR', 41, 1, NULL, NULL),
	(148, 'HANDIE', 5, 1, NULL, NULL),
	(149, 'MAQUINA SUMADORA', 2, 1, NULL, NULL),
	(150, 'PORTA LLAVES', 2, 1, NULL, NULL),
	(152, 'VENTILADOR', 41, 1, NULL, NULL),
	(153, 'BALANZA', 2, 1, NULL, NULL),
	(154, 'BASURERO', 2, 1, NULL, NULL),
	(155, 'AIRE ACONDICIONADO', 2, 1, NULL, NULL),
	(156, 'SOLDADOR', 13, 1, NULL, NULL),
	(157, 'INTERCOMUNICADOR', 5, 1, NULL, NULL),
	(158, 'MINI HUB', 15, 1, NULL, NULL),
	(159, 'CONSERVADORA', 2, 1, NULL, NULL),
	(160, 'HUB', 15, 1, NULL, NULL),
	(161, 'MOSTRADOR', 2, 1, NULL, NULL),
	(162, 'REFRIGERADOR', 2, 1, NULL, NULL),
	(163, 'TOCA DISCOS', 2, 1, NULL, NULL),
	(164, 'CAMION', 8, 1, NULL, NULL),
	(165, 'VAGONETA', 8, 1, NULL, NULL),
	(166, 'BASE DE BANNER', 2, 1, NULL, NULL),
	(167, 'BOTELLÓN', 41, 1, NULL, NULL),
	(171, 'CONTROL REMOTO', 6, 1, NULL, NULL),
	(174, 'FLASH MEMORY', 15, 1, NULL, NULL),
	(175, 'FLORERO', 2, 1, NULL, NULL),
	(179, 'MOUSE', 15, 1, NULL, NULL),
	(180, 'PARLANTES', 15, 1, NULL, NULL),
	(188, 'RECEPTOR', 15, 1, NULL, NULL),
	(191, 'TOKEN', 15, 1, NULL, NULL),
	(192, 'WYSE', 15, 1, NULL, NULL),
	(193, 'CALENDARIO', 36, 1, NULL, NULL),
	(194, 'LLAVE', 13, 1, NULL, NULL),
	(195, 'BOMBA', 41, 1, NULL, NULL),
	(196, 'GATA HIDRAULICA', 13, 1, NULL, NULL),
	(198, 'SISTEMA DE SONIDO', 2, 1, NULL, NULL),
	(199, 'EQUIPO DE SONIDO', 2, 1, NULL, NULL),
	(200, 'SERVIDOR DE CABALLEROS', 2, 1, NULL, NULL),
	(202, 'RADIO BASE', 5, 1, NULL, NULL),
	(205, 'MP3', 15, 1, NULL, NULL),
	(206, 'SOPLADOR DE AIRE', 41, 1, NULL, NULL),
	(207, 'KIT DE ALARMA', 41, 1, NULL, NULL),
	(208, 'MINIBUS', 8, 1, NULL, NULL),
	(209, 'JEEP', 8, 1, NULL, NULL),
	(210, 'LINTERNA', 13, 1, NULL, NULL),
	(212, 'LICENCIA', 37, 1, NULL, NULL),
	(213, 'GRABADOR DE CD', 15, 1, NULL, NULL),
	(214, 'BOMBA DE AIRE', 41, 1, NULL, NULL),
	(215, 'COMPRESORA DE AIRE', 41, 1, NULL, NULL),
	(222, 'terreno', 1, 1, '2025-04-04 23:56:13', '2025-04-05 00:05:21'),
	(224, 'vivienda', 1, 1, '2025-04-05 00:05:36', '2025-04-05 00:05:36');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
