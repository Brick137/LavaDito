-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 20-11-2025 a las 05:25:38
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lavadito`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

DROP TABLE IF EXISTS `carrito`;
CREATE TABLE IF NOT EXISTS `carrito` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `servicio_id` int NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `servicio_id` (`servicio_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `cliente_id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) DEFAULT NULL,
  `apellidos` varchar(25) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cliente_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cliente_id`, `nombre`, `apellidos`, `telefono`, `email`, `direccion`) VALUES
(1, 'monserrat', 'perez', '5563214789', 'jos_lui15.3@outlook.com', '1er retorno valle de bravo Col.cumbria C.p.54720'),
(10, 'fernando', 'Cruz', '7894522646', 'spoiderma@gmail.com', 'Valle de Bravo Cumbria'),
(8, 'chuy', 'perez', '7894522646', 'fernan10.2@outlook.com', 'cumbria Av. nezahualcóyotl 42B Mtz.12'),
(7, 'chuy', 'hernan', '7894522646', 'pepeluis_@gmail.com', 'cumbria Av. nezahualcóyotl 42B Mtz.12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductores`
--

DROP TABLE IF EXISTS `conductores`;
CREATE TABLE IF NOT EXISTS `conductores` (
  `conductor_id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) DEFAULT NULL,
  `apellido` varchar(20) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `licencia` varchar(20) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`conductor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `conductores`
--

INSERT INTO `conductores` (`conductor_id`, `nombre`, `apellido`, `telefono`, `licencia`, `estado`) VALUES
(1, 'fernando', 'Cruz', '7894522646', '98754621987', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `furgonetas`
--

DROP TABLE IF EXISTS `furgonetas`;
CREATE TABLE IF NOT EXISTS `furgonetas` (
  `furgoneta_id` int NOT NULL AUTO_INCREMENT,
  `placa` varchar(15) DEFAULT NULL,
  `modelo` varchar(30) DEFAULT NULL,
  `capacidad` float DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`furgoneta_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `furgonetas`
--

INSERT INTO `furgonetas` (`furgoneta_id`, `placa`, `modelo`, `capacidad`, `estado`) VALUES
(1, 'EM-AS-1', 'Nissan NV1500', 300, 'Libre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

DROP TABLE IF EXISTS `pagos`;
CREATE TABLE IF NOT EXISTS `pagos` (
  `pago_id` int NOT NULL AUTO_INCREMENT,
  `pedido_id` int DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `metodo` varchar(20) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`pago_id`),
  KEY `pedido_id` (`pedido_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`pago_id`, `pedido_id`, `monto`, `fecha_pago`, `metodo`, `estado`) VALUES
(1, 1, 705, '2025-11-19', 'Efectivo', 'Completado'),
(2, 2, 215, '2025-11-19', 'Efectivo', 'Completado'),
(3, 3, 140, '2025-11-19', 'Efectivo', 'Completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `pedido_id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int DEFAULT NULL,
  `fecha_pedido` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `estado` enum('pendiente','aceptado','preparando','en_ruta','entregado') DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`pedido_id`),
  KEY `cliente_id` (`cliente_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`pedido_id`, `cliente_id`, `fecha_pedido`, `fecha_entrega`, `estado`, `total`) VALUES
(1, 1, '2025-11-19', '2025-11-19', 'entregado', 705.00),
(2, 1, '2025-11-19', '2025-11-19', 'entregado', 215.00),
(3, 1, '2025-11-19', '2025-11-19', 'entregado', 140.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutas`
--

DROP TABLE IF EXISTS `rutas`;
CREATE TABLE IF NOT EXISTS `rutas` (
  `ruta_id` int NOT NULL AUTO_INCREMENT,
  `pedido_id` int DEFAULT NULL,
  `furgoneta_id` int DEFAULT NULL,
  `conductor_id` int DEFAULT NULL,
  `fecha_hora_salida` datetime DEFAULT NULL,
  `fecha_hora_entrega` datetime DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ruta_id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `furgoneta_id` (`furgoneta_id`),
  KEY `conductor_id` (`conductor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `rutas`
--

INSERT INTO `rutas` (`ruta_id`, `pedido_id`, `furgoneta_id`, `conductor_id`, `fecha_hora_salida`, `fecha_hora_entrega`, `estado`) VALUES
(1, 1, 1, 1, NULL, '2025-11-19 22:46:51', 'Finalizado'),
(2, 2, 1, 1, NULL, '2025-11-19 23:04:15', 'Finalizado'),
(3, 3, 1, 1, '2025-11-19 23:24:01', '2025-11-19 23:24:53', 'Finalizado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

DROP TABLE IF EXISTS `servicios`;
CREATE TABLE IF NOT EXISTS `servicios` (
  `servicio_id` int NOT NULL AUTO_INCREMENT,
  `pedido_id` int DEFAULT NULL,
  `tipo_servicio` varchar(50) DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `precio` float DEFAULT NULL,
  PRIMARY KEY (`servicio_id`),
  KEY `pedido_id` (`pedido_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`servicio_id`, `pedido_id`, `tipo_servicio`, `peso`, `precio`) VALUES
(1, NULL, 'Lavado General (1 Kg)', 1, 20),
(2, NULL, 'Planchado (Docena)', 1.5, 120),
(3, NULL, 'Lavado de Edredón', 3, 85),
(4, NULL, 'Secado Rápido (1 Kg)', 1, 15),
(5, NULL, 'Tintorería (Traje)', 0.5, 150),
(6, 1, 'Tintorería (Traje)', 0.5, 150),
(7, 1, 'Tintorería (Traje)', 0.5, 150),
(8, 1, 'Tintorería (Traje)', 0.5, 150),
(9, 1, 'Tintorería (Traje)', 0.5, 150),
(10, 1, 'Lavado General (1 Kg)', 1, 20),
(11, 1, 'Lavado de Edredón', 3, 85),
(12, 2, 'Secado Rápido (1 Kg)', 1, 15),
(13, 2, 'Secado Rápido (1 Kg)', 1, 15),
(14, 2, 'Secado Rápido (1 Kg)', 1, 15),
(15, 2, 'Lavado de Edredón', 3, 85),
(16, 2, 'Lavado de Edredón', 3, 85),
(17, 3, 'Secado Rápido (1 Kg)', 1, 15),
(18, 3, 'Secado Rápido (1 Kg)', 1, 15),
(19, 3, 'Secado Rápido (1 Kg)', 1, 15),
(20, 3, 'Secado Rápido (1 Kg)', 1, 15),
(21, 3, 'Lavado General (1 Kg)', 1, 20),
(22, 3, 'Lavado General (1 Kg)', 1, 20),
(23, 3, 'Lavado General (1 Kg)', 1, 20),
(24, 3, 'Lavado General (1 Kg)', 1, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario_id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('cliente','lavanderia','conductor') NOT NULL DEFAULT 'cliente',
  PRIMARY KEY (`usuario_id`),
  UNIQUE KEY `usuario` (`usuario`),
  KEY `cliente_id` (`cliente_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `cliente_id`, `usuario`, `clave`, `rol`) VALUES
(1, 1, 'spoi', '12345', 'cliente'),
(8, 10, 'Fernando', '4557', 'conductor'),
(6, 8, 'DownWass', '123', 'lavanderia');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
