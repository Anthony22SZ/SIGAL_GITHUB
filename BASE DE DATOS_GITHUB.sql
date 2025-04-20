-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: sigal
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accesos`
--

DROP TABLE IF EXISTS `accesos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accesos` (
  `COD_ACCESO` bigint(20) NOT NULL AUTO_INCREMENT,
  `COD_ROL` bigint(20) DEFAULT NULL,
  `COD_OBJETO` bigint(20) DEFAULT NULL,
  `ESTADO_MODULO` enum('1','0') DEFAULT '1',
  `ESTADO_SELECCION` enum('1','0') DEFAULT '1',
  `ESTADO_INSERCION` enum('1','0') DEFAULT '1',
  `ESTADO_ACTUALIZACION` enum('1','0') DEFAULT '1',
  `ESTADO_ELIMINACION` enum('1','0') DEFAULT '1',
  `USUARIO_CREA` varchar(255) DEFAULT NULL,
  `FECHA_CREA` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`COD_ACCESO`),
  UNIQUE KEY `uk_cod_rol_cod_objeto` (`COD_ROL`,`COD_OBJETO`),
  KEY `COD_OBJETO` (`COD_OBJETO`),
  CONSTRAINT `accesos_ibfk_1` FOREIGN KEY (`COD_ROL`) REFERENCES `roles` (`COD_ROL`),
  CONSTRAINT `accesos_ibfk_2` FOREIGN KEY (`COD_OBJETO`) REFERENCES `objetos` (`COD_OBJETO`)
) ENGINE=InnoDB AUTO_INCREMENT=333 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cai`
--

DROP TABLE IF EXISTS `cai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cai` (
  `COD_CAI` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO_CAI` varchar(37) NOT NULL,
  `FECHA_EMISION` date NOT NULL,
  `FECHA_VENCIMIENTO` date NOT NULL,
  `COD_TIPO_DOCUMENTO` int(11) NOT NULL,
  `COD_PUNTO_EMISION` int(11) NOT NULL,
  `ESTABLECIMIENTO` varchar(3) NOT NULL,
  `PUNTO_EMISION_CODIGO` varchar(3) NOT NULL,
  `TIPO_DOCUMENTO_CODIGO` varchar(2) NOT NULL,
  `RANGO_INICIAL` varchar(8) NOT NULL,
  `RANGO_FINAL` varchar(8) NOT NULL,
  `RANGO_ACTUAL` varchar(8) DEFAULT NULL,
  `ESTADO` enum('ACTIVO','VENCIDO','ANULADO') DEFAULT 'ACTIVO',
  PRIMARY KEY (`COD_CAI`),
  KEY `COD_TIPO_DOCUMENTO` (`COD_TIPO_DOCUMENTO`),
  KEY `COD_PUNTO_EMISION` (`COD_PUNTO_EMISION`),
  CONSTRAINT `cai_ibfk_1` FOREIGN KEY (`COD_TIPO_DOCUMENTO`) REFERENCES `tipos_documento` (`COD_TIPO_DOCUMENTO`),
  CONSTRAINT `cai_ibfk_2` FOREIGN KEY (`COD_PUNTO_EMISION`) REFERENCES `puntos_emision` (`COD_PUNTO_EMISION`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `COD_CATEGORIA` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(50) NOT NULL,
  `DESCRIPCION` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`COD_CATEGORIA`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `COD_CLIENTE` int(11) NOT NULL AUTO_INCREMENT,
  `COD_PERSONA` int(11) NOT NULL,
  `PRIMER_NOMBRE_C` varchar(100) NOT NULL,
  `SEGUNDO_NOMBRE_C` varchar(100) NOT NULL,
  `PRIMER_APELLIDO_C` varchar(100) NOT NULL,
  `SEGUNDO_APELLIDO_C` varchar(100) NOT NULL,
  PRIMARY KEY (`COD_CLIENTE`),
  KEY `COD_PERSONA` (`COD_PERSONA`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`COD_PERSONA`) REFERENCES `personas` (`COD_PERSONA`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `correlativos`
--

DROP TABLE IF EXISTS `correlativos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `correlativos` (
  `COD_CORRELATIVO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_CAI` int(11) NOT NULL,
  `PREFIJO` varchar(15) NOT NULL,
  `ULTIMO_NUMERO` int(11) NOT NULL DEFAULT 0,
  `FECHA_ULTIMO_USO` datetime DEFAULT NULL,
  PRIMARY KEY (`COD_CORRELATIVO`),
  KEY `COD_CAI` (`COD_CAI`),
  CONSTRAINT `correlativos_ibfk_1` FOREIGN KEY (`COD_CAI`) REFERENCES `cai` (`COD_CAI`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `correos`
--

DROP TABLE IF EXISTS `correos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `correos` (
  `COD_CORREO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_PERSONA` int(11) NOT NULL,
  `CORREO_ELECTRONICO` varchar(30) NOT NULL,
  `TIPO` enum('PERSONAL','LABORAL','OTRO') DEFAULT NULL,
  PRIMARY KEY (`COD_CORREO`),
  UNIQUE KEY `CORREO_ELECTRONICO` (`CORREO_ELECTRONICO`),
  KEY `COD_PERSONA` (`COD_PERSONA`),
  CONSTRAINT `correos_ibfk_1` FOREIGN KEY (`COD_PERSONA`) REFERENCES `personas` (`COD_PERSONA`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `detallecompra`
--

DROP TABLE IF EXISTS `detallecompra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detallecompra` (
  `COD_DETALLE_COMPRA` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO_FACTURA` int(11) NOT NULL,
  `MATERIAL_CODIGO` varchar(10) NOT NULL,
  `CANTIDAD` decimal(10,2) NOT NULL,
  `PRECIO` decimal(10,2) NOT NULL,
  `SUBTOTAL` decimal(10,2) GENERATED ALWAYS AS (`CANTIDAD` * `PRECIO`) STORED,
  PRIMARY KEY (`COD_DETALLE_COMPRA`),
  KEY `CODIGO_FACTURA` (`CODIGO_FACTURA`),
  KEY `MATERIAL_CODIGO` (`MATERIAL_CODIGO`),
  CONSTRAINT `detallecompra_ibfk_1` FOREIGN KEY (`CODIGO_FACTURA`) REFERENCES `facturascompra` (`COD_FACTURA`),
  CONSTRAINT `detallecompra_ibfk_2` FOREIGN KEY (`MATERIAL_CODIGO`) REFERENCES `materiales` (`CODIGO`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_ActualizarTotalFactura_After_Insert
AFTER INSERT ON DETALLECOMPRA
FOR EACH ROW
BEGIN
    UPDATE FACTURASCOMPRA
    SET SUBTOTAL = (SELECT COALESCE(SUM(SUBTOTAL), 0) 
                    FROM DETALLECOMPRA 
                    WHERE CODIGO_FACTURA = NEW.CODIGO_FACTURA)
    WHERE COD_FACTURA = NEW.CODIGO_FACTURA;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_ActualizarTotalFactura_After_Update
AFTER UPDATE ON DETALLECOMPRA
FOR EACH ROW
BEGIN
    UPDATE FACTURASCOMPRA
    SET SUBTOTAL = (SELECT COALESCE(SUM(SUBTOTAL), 0) 
                    FROM DETALLECOMPRA 
                    WHERE CODIGO_FACTURA = NEW.CODIGO_FACTURA)
    WHERE COD_FACTURA = NEW.CODIGO_FACTURA;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_ActualizarTotalFactura_After_Delete
AFTER DELETE ON DETALLECOMPRA
FOR EACH ROW
BEGIN
    UPDATE FACTURASCOMPRA
    SET SUBTOTAL = (SELECT COALESCE(SUM(SUBTOTAL), 0) 
                    FROM DETALLECOMPRA 
                    WHERE CODIGO_FACTURA = OLD.CODIGO_FACTURA)
    WHERE COD_FACTURA = OLD.CODIGO_FACTURA;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `detallepedidos`
--

DROP TABLE IF EXISTS `detallepedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detallepedidos` (
  `COD_DETALLE_PEDIDO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_PEDIDO` int(11) NOT NULL,
  `DESCRIPCION_PRODUCTO` varchar(200) NOT NULL,
  `CANTIDAD` decimal(10,2) NOT NULL,
  `PRECIO_UNITARIO` decimal(10,2) NOT NULL,
  `SUBTOTAL` decimal(10,2) GENERATED ALWAYS AS (`CANTIDAD` * `PRECIO_UNITARIO`) STORED,
  PRIMARY KEY (`COD_DETALLE_PEDIDO`),
  KEY `COD_PEDIDO` (`COD_PEDIDO`),
  CONSTRAINT `detallepedidos_ibfk_1` FOREIGN KEY (`COD_PEDIDO`) REFERENCES `pedidos` (`COD_PEDIDO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `detallesfacturapedido`
--

DROP TABLE IF EXISTS `detallesfacturapedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detallesfacturapedido` (
  `COD_DETALLE` int(11) NOT NULL AUTO_INCREMENT,
  `COD_FACTURA` int(11) NOT NULL,
  `DESCRIPCION_PRODUCTO` varchar(200) NOT NULL,
  `CANTIDAD` decimal(10,2) NOT NULL,
  `PRECIO_UNITARIO` decimal(10,2) NOT NULL,
  `SUBTOTAL` decimal(10,2) GENERATED ALWAYS AS (`CANTIDAD` * `PRECIO_UNITARIO`) STORED,
  `COD_PRODUCTO` int(11) DEFAULT NULL,
  PRIMARY KEY (`COD_DETALLE`),
  KEY `COD_FACTURA` (`COD_FACTURA`),
  KEY `COD_PRODUCTO` (`COD_PRODUCTO`),
  CONSTRAINT `detallesfacturapedido_ibfk_1` FOREIGN KEY (`COD_FACTURA`) REFERENCES `facturaspedido` (`COD_FACTURA`),
  CONSTRAINT `detallesfacturapedido_ibfk_2` FOREIGN KEY (`COD_PRODUCTO`) REFERENCES `productos` (`COD_PRODUCTO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `detalletraslados`
--

DROP TABLE IF EXISTS `detalletraslados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalletraslados` (
  `COD_DETALLE_TRASLADO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_TRASLADO` int(11) NOT NULL,
  `COD_PRODUCTO` int(11) NOT NULL,
  `CANTIDAD` decimal(10,2) NOT NULL,
  PRIMARY KEY (`COD_DETALLE_TRASLADO`),
  KEY `COD_TRASLADO` (`COD_TRASLADO`),
  KEY `COD_PRODUCTO` (`COD_PRODUCTO`),
  CONSTRAINT `detalletraslados_ibfk_1` FOREIGN KEY (`COD_TRASLADO`) REFERENCES `traslados` (`COD_TRASLADO`),
  CONSTRAINT `detalletraslados_ibfk_2` FOREIGN KEY (`COD_PRODUCTO`) REFERENCES `productos` (`COD_PRODUCTO`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `detalleventa`
--

DROP TABLE IF EXISTS `detalleventa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalleventa` (
  `COD_DETALLE_VENTA` int(11) NOT NULL AUTO_INCREMENT,
  `COD_FACTURA` int(11) NOT NULL,
  `COD_PRODUCTO` int(11) NOT NULL,
  `CANTIDAD` decimal(10,2) NOT NULL,
  `PRECIO` decimal(10,2) NOT NULL,
  `SUBTOTAL` decimal(10,2) GENERATED ALWAYS AS (`CANTIDAD` * `PRECIO`) STORED,
  PRIMARY KEY (`COD_DETALLE_VENTA`),
  KEY `COD_FACTURA` (`COD_FACTURA`),
  KEY `COD_PRODUCTO` (`COD_PRODUCTO`),
  CONSTRAINT `detalleventa_ibfk_1` FOREIGN KEY (`COD_FACTURA`) REFERENCES `facturasventa` (`COD_FACTURA`),
  CONSTRAINT `detalleventa_ibfk_2` FOREIGN KEY (`COD_PRODUCTO`) REFERENCES `productos` (`COD_PRODUCTO`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `direcciones`
--

DROP TABLE IF EXISTS `direcciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `direcciones` (
  `COD_DIRECCION` int(11) NOT NULL AUTO_INCREMENT,
  `COD_PERSONA` int(11) NOT NULL,
  `CALLE` varchar(50) NOT NULL,
  `CIUDAD` varchar(50) NOT NULL,
  `PAIS` varchar(50) NOT NULL,
  `CODIGO_POSTAL` varchar(50) DEFAULT NULL,
  `TIPO` enum('DOMICILIO','LABORAL','OTRO') DEFAULT NULL,
  PRIMARY KEY (`COD_DIRECCION`),
  KEY `COD_PERSONA` (`COD_PERSONA`),
  CONSTRAINT `direcciones_ibfk_1` FOREIGN KEY (`COD_PERSONA`) REFERENCES `personas` (`COD_PERSONA`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleados` (
  `COD_EMPLEADO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_PERSONA` int(11) NOT NULL,
  `PRIMER_NOMBRE_E` varchar(100) NOT NULL,
  `SEGUNDO_NOMBRE_E` varchar(100) NOT NULL,
  `PRIMER_APELLIDO_E` varchar(100) NOT NULL,
  `SEGUNDO_APELLIDO_E` varchar(100) NOT NULL,
  `PUESTO` enum('ADMINISTRADOR','VENDEDOR','GERENTE','JEFE PRODUCCION','JEFE DE VENTAS') DEFAULT NULL,
  PRIMARY KEY (`COD_EMPLEADO`),
  KEY `COD_PERSONA` (`COD_PERSONA`),
  CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`COD_PERSONA`) REFERENCES `personas` (`COD_PERSONA`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresa` (
  `COD_EMPRESA` int(11) NOT NULL AUTO_INCREMENT,
  `RAZON_SOCIAL` varchar(100) NOT NULL,
  `NOMBRE_COMERCIAL` varchar(50) NOT NULL,
  `RTN` varchar(16) NOT NULL,
  `DIRECCION` varchar(200) NOT NULL,
  `CIUDAD` varchar(50) NOT NULL,
  `DEPARTAMENTO` varchar(50) NOT NULL,
  `TELEFONO` varchar(15) NOT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `SITIO_WEB` varchar(100) DEFAULT NULL,
  `REGIMEN_FISCAL` varchar(50) NOT NULL,
  `ACTIVIDAD_ECONOMICA` varchar(100) NOT NULL,
  `LOGO` longblob DEFAULT NULL,
  PRIMARY KEY (`COD_EMPRESA`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estado_sucursales`
--

DROP TABLE IF EXISTS `estado_sucursales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_sucursales` (
  `ID_ESTADO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_SUCURSAL` int(11) NOT NULL,
  `ESTADO` enum('ACTIVA','INACTIVA') NOT NULL DEFAULT 'ACTIVA',
  PRIMARY KEY (`ID_ESTADO`),
  KEY `COD_SUCURSAL` (`COD_SUCURSAL`),
  CONSTRAINT `estado_sucursales_ibfk_1` FOREIGN KEY (`COD_SUCURSAL`) REFERENCES `sucursales` (`COD_SUCURSAL`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `facturascompra`
--

DROP TABLE IF EXISTS `facturascompra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facturascompra` (
  `COD_FACTURA` int(11) NOT NULL AUTO_INCREMENT,
  `NUMERO_FACTURA` varchar(10) NOT NULL,
  `COD_PROVEEDORES` int(11) NOT NULL,
  `FECHA` timestamp NOT NULL DEFAULT current_timestamp(),
  `SUBTOTAL` decimal(10,2) DEFAULT 0.00,
  `IMPUESTO` decimal(10,2) DEFAULT 0.00,
  `DESCUENTO` decimal(10,2) DEFAULT 0.00,
  `TOTAL` decimal(10,2) GENERATED ALWAYS AS (`SUBTOTAL` - `SUBTOTAL` * `DESCUENTO` + (`SUBTOTAL` - `SUBTOTAL` * `DESCUENTO`) * `IMPUESTO`) STORED,
  PRIMARY KEY (`COD_FACTURA`),
  UNIQUE KEY `NUMERO_FACTURA` (`NUMERO_FACTURA`),
  KEY `COD_PROVEEDORES` (`COD_PROVEEDORES`),
  CONSTRAINT `facturascompra_ibfk_1` FOREIGN KEY (`COD_PROVEEDORES`) REFERENCES `proveedores` (`COD_PROVEEDORES`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `facturaspedido`
--

DROP TABLE IF EXISTS `facturaspedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facturaspedido` (
  `COD_FACTURA` int(11) NOT NULL AUTO_INCREMENT,
  `NUMERO_FACTURA` varchar(15) NOT NULL,
  `COD_CLIENTE` int(11) NOT NULL,
  `COD_SUCURSAL` int(11) NOT NULL,
  `COD_PEDIDO` int(11) NOT NULL,
  `CAI` varchar(37) DEFAULT NULL,
  `NUMERO_FISCAL` varchar(50) DEFAULT NULL,
  `FECHA` timestamp NOT NULL DEFAULT current_timestamp(),
  `SUBTOTAL` decimal(10,2) DEFAULT 0.00,
  `IMPUESTO` decimal(10,2) DEFAULT 0.00,
  `DESCUENTO` decimal(10,2) DEFAULT 0.00,
  `TOTAL` decimal(10,2) GENERATED ALWAYS AS (`SUBTOTAL` - `SUBTOTAL` * `DESCUENTO` + (`SUBTOTAL` - `SUBTOTAL` * `DESCUENTO`) * `IMPUESTO`) STORED,
  `METODO_PAGO` enum('EFECTIVO','TARJETA','TRANSFERENCIA','OTRO') DEFAULT 'EFECTIVO',
  `ESTADO` enum('PENDIENTE','PAGADA','ANULADA') DEFAULT 'PENDIENTE',
  PRIMARY KEY (`COD_FACTURA`),
  UNIQUE KEY `NUMERO_FACTURA` (`NUMERO_FACTURA`),
  KEY `COD_CLIENTE` (`COD_CLIENTE`),
  KEY `COD_SUCURSAL` (`COD_SUCURSAL`),
  KEY `COD_PEDIDO` (`COD_PEDIDO`),
  CONSTRAINT `facturaspedido_ibfk_1` FOREIGN KEY (`COD_CLIENTE`) REFERENCES `clientes` (`COD_CLIENTE`),
  CONSTRAINT `facturaspedido_ibfk_2` FOREIGN KEY (`COD_SUCURSAL`) REFERENCES `sucursales` (`COD_SUCURSAL`),
  CONSTRAINT `facturaspedido_ibfk_3` FOREIGN KEY (`COD_PEDIDO`) REFERENCES `pedidos` (`COD_PEDIDO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `facturasventa`
--

DROP TABLE IF EXISTS `facturasventa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facturasventa` (
  `COD_FACTURA` int(11) NOT NULL AUTO_INCREMENT,
  `NUMERO_FACTURA` varchar(15) NOT NULL,
  `COD_CLIENTE` int(11) DEFAULT NULL,
  `COD_SUCURSAL` int(11) NOT NULL,
  `CAI` varchar(37) DEFAULT NULL,
  `NUMERO_FISCAL` varchar(50) DEFAULT NULL,
  `FECHA` timestamp NOT NULL DEFAULT current_timestamp(),
  `SUBTOTAL` decimal(10,2) DEFAULT 0.00,
  `IMPUESTO` decimal(10,2) DEFAULT 0.00,
  `DESCUENTO` decimal(10,2) DEFAULT 0.00,
  `TOTAL` decimal(10,2) GENERATED ALWAYS AS (`SUBTOTAL` - `SUBTOTAL` * `DESCUENTO` + (`SUBTOTAL` - `SUBTOTAL` * `DESCUENTO`) * `IMPUESTO`) STORED,
  `METODO_PAGO` enum('EFECTIVO','TARJETA','TRANSFERENCIA','OTRO') DEFAULT 'EFECTIVO',
  `ESTADO` enum('PENDIENTE','PAGADA','ANULADA') DEFAULT 'PENDIENTE',
  `NOTAS` text DEFAULT NULL,
  PRIMARY KEY (`COD_FACTURA`),
  UNIQUE KEY `NUMERO_FACTURA` (`NUMERO_FACTURA`),
  KEY `COD_CLIENTE` (`COD_CLIENTE`),
  KEY `COD_SUCURSAL` (`COD_SUCURSAL`),
  CONSTRAINT `facturasventa_ibfk_1` FOREIGN KEY (`COD_CLIENTE`) REFERENCES `clientes` (`COD_CLIENTE`),
  CONSTRAINT `facturasventa_ibfk_2` FOREIGN KEY (`COD_SUCURSAL`) REFERENCES `sucursales` (`COD_SUCURSAL`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `garantias`
--

DROP TABLE IF EXISTS `garantias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `garantias` (
  `COD_GARANTIA` int(11) NOT NULL AUTO_INCREMENT,
  `COD_FACTURA_PEDIDO` int(11) DEFAULT NULL,
  `COD_FACTURA_VENTA` int(11) DEFAULT NULL,
  `COD_PRODUCTO` int(11) NOT NULL,
  `FECHA_INICIO` date NOT NULL,
  `FECHA_FIN` date NOT NULL,
  `ESTADO` enum('ACTIVA','VENCIDA','APLICADA','CANCELADA') DEFAULT 'ACTIVA',
  `DESCRIPCION` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`COD_GARANTIA`),
  KEY `COD_FACTURA_PEDIDO` (`COD_FACTURA_PEDIDO`),
  KEY `COD_FACTURA_VENTA` (`COD_FACTURA_VENTA`),
  KEY `COD_PRODUCTO` (`COD_PRODUCTO`),
  CONSTRAINT `garantias_ibfk_1` FOREIGN KEY (`COD_FACTURA_PEDIDO`) REFERENCES `facturaspedido` (`COD_FACTURA`),
  CONSTRAINT `garantias_ibfk_2` FOREIGN KEY (`COD_FACTURA_VENTA`) REFERENCES `facturasventa` (`COD_FACTURA`),
  CONSTRAINT `garantias_ibfk_3` FOREIGN KEY (`COD_PRODUCTO`) REFERENCES `productos` (`COD_PRODUCTO`),
  CONSTRAINT `CONSTRAINT_1` CHECK (`COD_FACTURA_PEDIDO` is not null or `COD_FACTURA_VENTA` is not null)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `histcontrasena`
--

DROP TABLE IF EXISTS `histcontrasena`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `histcontrasena` (
  `COD_HISTCONTRASENA` bigint(20) NOT NULL AUTO_INCREMENT,
  `COD_USUARIO` bigint(20) NOT NULL,
  `CONTRASENA` text NOT NULL,
  `FECHA_CREA` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`COD_HISTCONTRASENA`),
  KEY `COD_USUARIO` (`COD_USUARIO`),
  CONSTRAINT `histcontrasena_ibfk_1` FOREIGN KEY (`COD_USUARIO`) REFERENCES `usuarios` (`COD_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `intentos_fallidos`
--

DROP TABLE IF EXISTS `intentos_fallidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intentos_fallidos` (
  `COD_INTENTO` bigint(20) NOT NULL AUTO_INCREMENT,
  `COD_USUARIO` bigint(20) DEFAULT NULL,
  `NOMBRE_USUARIO` varchar(255) NOT NULL,
  `IP` varchar(50) DEFAULT NULL,
  `FECHA` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`COD_INTENTO`),
  KEY `COD_USUARIO` (`COD_USUARIO`),
  CONSTRAINT `intentos_fallidos_ibfk_1` FOREIGN KEY (`COD_USUARIO`) REFERENCES `usuarios` (`COD_USUARIO`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventariomateriales`
--

DROP TABLE IF EXISTS `inventariomateriales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventariomateriales` (
  `COD_INVMATERIAL` int(11) NOT NULL AUTO_INCREMENT,
  `MATERIAL_CODIGO` varchar(10) NOT NULL,
  `STOCK_ACTUAL` decimal(10,2) NOT NULL DEFAULT 0.00,
  `STOCK_MINIMO` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`COD_INVMATERIAL`),
  UNIQUE KEY `MATERIAL_CODIGO` (`MATERIAL_CODIGO`),
  CONSTRAINT `inventariomateriales_ibfk_1` FOREIGN KEY (`MATERIAL_CODIGO`) REFERENCES `materiales` (`CODIGO`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventarioproductos`
--

DROP TABLE IF EXISTS `inventarioproductos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventarioproductos` (
  `COD_INVENTARIO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_PRODUCTO` int(11) NOT NULL,
  `COD_SUCURSAL` int(11) NOT NULL,
  `STOCK_ACTUAL` decimal(10,2) NOT NULL DEFAULT 0.00,
  `STOCK_MINIMO` decimal(10,2) NOT NULL DEFAULT 0.00,
  `PRECIO_VENTA` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`COD_INVENTARIO`),
  UNIQUE KEY `COD_PRODUCTO` (`COD_PRODUCTO`,`COD_SUCURSAL`),
  KEY `COD_SUCURSAL` (`COD_SUCURSAL`),
  CONSTRAINT `inventarioproductos_ibfk_1` FOREIGN KEY (`COD_PRODUCTO`) REFERENCES `productos` (`COD_PRODUCTO`),
  CONSTRAINT `inventarioproductos_ibfk_2` FOREIGN KEY (`COD_SUCURSAL`) REFERENCES `sucursales` (`COD_SUCURSAL`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `materiales`
--

DROP TABLE IF EXISTS `materiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materiales` (
  `COD_MATERIAL` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` varchar(10) NOT NULL,
  `MATERIAL` varchar(30) NOT NULL,
  PRIMARY KEY (`COD_MATERIAL`),
  UNIQUE KEY `CODIGO` (`CODIGO`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `objetos`
--

DROP TABLE IF EXISTS `objetos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `objetos` (
  `COD_OBJETO` bigint(20) NOT NULL AUTO_INCREMENT,
  `NOMBRE_OBJETO` varchar(255) NOT NULL,
  `TIPO_OBJETO` varchar(255) DEFAULT NULL,
  `DESCRIPCION_OBJETO` varchar(255) DEFAULT NULL,
  `ESTADO_OBJETO` enum('1','0') DEFAULT '1',
  `USUARIO_CREA` varchar(255) DEFAULT NULL,
  `FECHA_CREA` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`COD_OBJETO`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parametros`
--

DROP TABLE IF EXISTS `parametros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parametros` (
  `COD_PARAMETRO` bigint(20) NOT NULL AUTO_INCREMENT,
  `PARAMETRO` varchar(50) NOT NULL,
  `VALOR` varchar(255) NOT NULL,
  `COD_USUARIO` bigint(20) NOT NULL,
  `FECHA_CREADO` datetime DEFAULT current_timestamp(),
  `FECHA_MODIFICADO` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`COD_PARAMETRO`),
  KEY `COD_USUARIO` (`COD_USUARIO`),
  CONSTRAINT `parametros_ibfk_1` FOREIGN KEY (`COD_USUARIO`) REFERENCES `usuarios` (`COD_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `COD_PEDIDO` int(11) NOT NULL AUTO_INCREMENT,
  `NUMERO_PEDIDO` varchar(15) NOT NULL,
  `COD_CLIENTE` int(11) NOT NULL,
  `COD_SUCURSAL` int(11) NOT NULL,
  `FECHA_PEDIDO` timestamp NOT NULL DEFAULT current_timestamp(),
  `FECHA_ENTREGA` date DEFAULT NULL,
  `ESTADO` enum('PENDIENTE','EN PROCESO','COMPLETADO','CANCELADO') DEFAULT 'PENDIENTE',
  `NOTAS` varchar(200) DEFAULT NULL,
  `IMPUESTO` decimal(10,2) DEFAULT 0.00,
  `DESCUENTO` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`COD_PEDIDO`),
  UNIQUE KEY `NUMERO_PEDIDO` (`NUMERO_PEDIDO`),
  KEY `COD_CLIENTE` (`COD_CLIENTE`),
  KEY `COD_SUCURSAL` (`COD_SUCURSAL`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`COD_CLIENTE`) REFERENCES `clientes` (`COD_CLIENTE`),
  CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`COD_SUCURSAL`) REFERENCES `sucursales` (`COD_SUCURSAL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personas` (
  `COD_PERSONA` int(11) NOT NULL AUTO_INCREMENT,
  `PRIMER_NOMBRE` varchar(30) NOT NULL,
  `SEGUNDO_NOMBRE` varchar(30) DEFAULT NULL,
  `PRIMER_APELLIDO` varchar(30) NOT NULL,
  `SEGUNDO_APELLIDO` varchar(30) DEFAULT NULL,
  `NUMERO_IDENTIDAD` varchar(16) DEFAULT NULL,
  `RTN` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`COD_PERSONA`),
  UNIQUE KEY `NUMERO_IDENTIDAD` (`NUMERO_IDENTIDAD`),
  UNIQUE KEY `RTN` (`RTN`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `COD_PRODUCTO` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` varchar(15) NOT NULL,
  `MODELO` varchar(50) NOT NULL,
  `DESCRIPCION` varchar(200) DEFAULT NULL,
  `COD_CATEGORIA` int(11) NOT NULL,
  `TIEMPO_GARANTIA` int(11) DEFAULT 0,
  PRIMARY KEY (`COD_PRODUCTO`),
  UNIQUE KEY `CODIGO` (`CODIGO`),
  KEY `COD_CATEGORIA` (`COD_CATEGORIA`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`COD_CATEGORIA`) REFERENCES `categorias` (`COD_CATEGORIA`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `COD_PROVEEDORES` int(11) NOT NULL AUTO_INCREMENT,
  `COD_PERSONA` int(11) NOT NULL,
  `NOMBRE_EMPRESA` varchar(30) NOT NULL,
  `NOMBRE_CONTACTO` varchar(30) NOT NULL,
  `APELLIDO_CONTACTO` varchar(30) NOT NULL,
  PRIMARY KEY (`COD_PROVEEDORES`),
  KEY `COD_PERSONA` (`COD_PERSONA`),
  CONSTRAINT `proveedores_ibfk_1` FOREIGN KEY (`COD_PERSONA`) REFERENCES `personas` (`COD_PERSONA`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `puntos_emision`
--

DROP TABLE IF EXISTS `puntos_emision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `puntos_emision` (
  `COD_PUNTO_EMISION` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` varchar(3) NOT NULL,
  `NOMBRE` varchar(50) NOT NULL,
  `ESTABLECIMIENTO` varchar(3) NOT NULL,
  `ESTADO` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `COD_SUCURSAL` int(11) DEFAULT NULL,
  PRIMARY KEY (`COD_PUNTO_EMISION`),
  KEY `COD_SUCURSAL` (`COD_SUCURSAL`),
  CONSTRAINT `puntos_emision_ibfk_1` FOREIGN KEY (`COD_SUCURSAL`) REFERENCES `sucursales` (`COD_SUCURSAL`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reclamosgarantia`
--

DROP TABLE IF EXISTS `reclamosgarantia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reclamosgarantia` (
  `COD_RECLAMO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_GARANTIA` int(11) NOT NULL,
  `FECHA_RECLAMO` timestamp NOT NULL DEFAULT current_timestamp(),
  `DESCRIPCION_PROBLEMA` varchar(200) NOT NULL,
  `ESTADO` enum('PENDIENTE','EN PROCESO','RESUELTO','RECHAZADO') DEFAULT 'PENDIENTE',
  `SOLUCION` varchar(200) DEFAULT NULL,
  `FECHA_SOLUCION` date DEFAULT NULL,
  PRIMARY KEY (`COD_RECLAMO`),
  KEY `COD_GARANTIA` (`COD_GARANTIA`),
  CONSTRAINT `reclamosgarantia_ibfk_1` FOREIGN KEY (`COD_GARANTIA`) REFERENCES `garantias` (`COD_GARANTIA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `COD_ROL` bigint(20) NOT NULL AUTO_INCREMENT,
  `NOMBRE_ROL` varchar(255) NOT NULL,
  `DESCRIPCION_ROL` varchar(255) DEFAULT NULL,
  `ESTADO_ROL` enum('1','0') DEFAULT '1',
  `USUARIO_CREA` varchar(255) DEFAULT NULL,
  `FECHA_CREA` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`COD_ROL`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sesiones`
--

DROP TABLE IF EXISTS `sesiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sesiones` (
  `COD_SESION` bigint(20) NOT NULL AUTO_INCREMENT,
  `COD_USUARIO` bigint(20) NOT NULL,
  `TOKEN` varchar(500) NOT NULL,
  `FECHA_CREACION` datetime DEFAULT current_timestamp(),
  `FECHA_EXPIRACION` datetime DEFAULT NULL,
  PRIMARY KEY (`COD_SESION`),
  KEY `COD_USUARIO` (`COD_USUARIO`),
  CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`COD_USUARIO`) REFERENCES `usuarios` (`COD_USUARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sesiones_recuperacion`
--

DROP TABLE IF EXISTS `sesiones_recuperacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sesiones_recuperacion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` varchar(6) NOT NULL,
  `COD_USUARIO` bigint(20) NOT NULL,
  `FECHA_EXPIRACION` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `COD_USUARIO` (`COD_USUARIO`),
  CONSTRAINT `sesiones_recuperacion_ibfk_1` FOREIGN KEY (`COD_USUARIO`) REFERENCES `usuarios` (`COD_USUARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sucursales`
--

DROP TABLE IF EXISTS `sucursales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sucursales` (
  `COD_SUCURSAL` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(50) NOT NULL,
  `DIRECCION` varchar(100) NOT NULL,
  `COD_EMPLEADO_ENCARGADO` int(11) DEFAULT NULL,
  PRIMARY KEY (`COD_SUCURSAL`),
  KEY `COD_EMPLEADO_ENCARGADO` (`COD_EMPLEADO_ENCARGADO`),
  CONSTRAINT `sucursales_ibfk_1` FOREIGN KEY (`COD_EMPLEADO_ENCARGADO`) REFERENCES `empleados` (`COD_EMPLEADO`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `telefonos`
--

DROP TABLE IF EXISTS `telefonos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telefonos` (
  `COD_TELEFONO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_PERSONA` int(11) NOT NULL,
  `NUMERO_TELEFONO` varchar(15) NOT NULL,
  `TIPO_TELEFONO` enum('PERSONAL','LABORAL','OTRO') DEFAULT NULL,
  PRIMARY KEY (`COD_TELEFONO`),
  UNIQUE KEY `NUMERO_TELEFONO` (`NUMERO_TELEFONO`),
  KEY `COD_PERSONA` (`COD_PERSONA`),
  CONSTRAINT `telefonos_ibfk_1` FOREIGN KEY (`COD_PERSONA`) REFERENCES `personas` (`COD_PERSONA`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_documento`
--

DROP TABLE IF EXISTS `tipos_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_documento` (
  `COD_TIPO_DOCUMENTO` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` varchar(2) NOT NULL,
  `NOMBRE` varchar(50) NOT NULL,
  `DESCRIPCION` varchar(200) DEFAULT NULL,
  `AFECTA_INVENTARIO` tinyint(1) DEFAULT 0,
  `REQUIERE_CLIENTE` tinyint(1) DEFAULT 1,
  `ESTADO` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  PRIMARY KEY (`COD_TIPO_DOCUMENTO`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `traslados`
--

DROP TABLE IF EXISTS `traslados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `traslados` (
  `COD_TRASLADO` int(11) NOT NULL AUTO_INCREMENT,
  `SUCURSAL_ORIGEN` int(11) NOT NULL,
  `SUCURSAL_DESTINO` int(11) NOT NULL,
  `FECHA_TRASLADO` timestamp NOT NULL DEFAULT current_timestamp(),
  `ESTADO` enum('PENDIENTE','EN TRANSITO','COMPLETADO','CANCELADO') DEFAULT 'PENDIENTE',
  `NOTAS` varchar(200) DEFAULT NULL,
  `COD_USUARIO` bigint(20) NOT NULL,
  PRIMARY KEY (`COD_TRASLADO`),
  KEY `SUCURSAL_ORIGEN` (`SUCURSAL_ORIGEN`),
  KEY `SUCURSAL_DESTINO` (`SUCURSAL_DESTINO`),
  KEY `COD_USUARIO` (`COD_USUARIO`),
  CONSTRAINT `traslados_ibfk_1` FOREIGN KEY (`SUCURSAL_ORIGEN`) REFERENCES `sucursales` (`COD_SUCURSAL`),
  CONSTRAINT `traslados_ibfk_2` FOREIGN KEY (`SUCURSAL_DESTINO`) REFERENCES `sucursales` (`COD_SUCURSAL`),
  CONSTRAINT `traslados_ibfk_3` FOREIGN KEY (`COD_USUARIO`) REFERENCES `usuarios` (`COD_USUARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `COD_USUARIO` bigint(20) NOT NULL AUTO_INCREMENT,
  `COD_PERSONA` bigint(20) DEFAULT NULL,
  `COD_EMPLEADO` int(11) DEFAULT NULL,
  `COD_ROL` bigint(20) DEFAULT NULL,
  `NOMBRE_USUARIO` varchar(255) NOT NULL,
  `CONTRASENA` varchar(2000) NOT NULL,
  `ESTADO_USUARIO` enum('1','0') DEFAULT '1',
  `PERMISO_INSERCION` enum('1','0') DEFAULT '1',
  `USUARIO_CREA` varchar(255) DEFAULT NULL,
  `FECHA_CREA` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`COD_USUARIO`),
  KEY `COD_EMPLEADO` (`COD_EMPLEADO`),
  KEY `COD_ROL` (`COD_ROL`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`COD_EMPLEADO`) REFERENCES `empleados` (`COD_EMPLEADO`),
  CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`COD_ROL`) REFERENCES `roles` (`COD_ROL`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'sigal'
--
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarCAI` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarCAI`(
    IN p_cod_cai INT,
    IN p_codigo_cai VARCHAR(37),
    IN p_fecha_emision DATE,
    IN p_fecha_vencimiento DATE,
    IN p_estado ENUM('ACTIVO', 'VENCIDO', 'ANULADO')
)
BEGIN
    UPDATE CAI SET
        CODIGO_CAI = p_codigo_cai,
        FECHA_EMISION = p_fecha_emision,
        FECHA_VENCIMIENTO = p_fecha_vencimiento,
        ESTADO = p_estado
    WHERE COD_CAI = p_cod_cai;
    
    SELECT 'CAI actualizado correctamente' AS MENSAJE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarCategoria` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarCategoria`(
    IN p_cod_categoria INT,
    IN p_nombre VARCHAR(50),
    IN p_descripcion VARCHAR(100)
)
BEGIN
    UPDATE CATEGORIAS
    SET NOMBRE = p_nombre,
        DESCRIPCION = p_descripcion
    WHERE COD_CATEGORIA = p_cod_categoria;
    
    SELECT COD_CATEGORIA, NOMBRE, DESCRIPCION
    FROM CATEGORIAS
    WHERE COD_CATEGORIA = p_cod_categoria;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarCliente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarCliente`(
    IN P_COD_CLIENTE INT,
    IN P_PRIMER_NOMBRE_C VARCHAR(100), 
    IN P_SEGUNDO_NOMBRE_C VARCHAR(100),
    IN P_PRIMER_APELLIDO_C VARCHAR(100), 
    IN P_SEGUNDO_APELLIDO_C VARCHAR(100),
    IN P_NUMERO_IDENTIDAD VARCHAR(16), 
    IN P_RTN VARCHAR(16),
    IN P_NUMERO_TELEFONO VARCHAR(15), 
    IN P_TIPO_TELEFONO ENUM('PERSONAL', 'LABORAL', 'OTRO'),
    IN P_CORREO_ELECTRONICO VARCHAR(30), 
    IN P_TIPO_CORREO ENUM('PERSONAL', 'LABORAL', 'OTRO'),
    IN P_CALLE VARCHAR(50), 
    IN P_CIUDAD VARCHAR(50), 
    IN P_PAIS VARCHAR(50), 
    IN P_CODIGO_POSTAL VARCHAR(50),
    IN P_TIPO_DIRECCION ENUM('DOMICILIO', 'LABORAL', 'OTRO')
)
BEGIN
    DECLARE V_COD_PERSONA INT;
    DECLARE V_EXISTE_IDENTIDAD INT;

    -- Obtener COD_PERSONA asociado al cliente
    SELECT COD_PERSONA INTO V_COD_PERSONA 
    FROM CLIENTES 
    WHERE COD_CLIENTE = P_COD_CLIENTE;

    -- Verificar si se encontró un COD_PERSONA válido
    IF V_COD_PERSONA IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Cliente no encontrado.';
    END IF;

    -- Validar si el número de identidad ya existe en otra persona
    SELECT COUNT(*) INTO V_EXISTE_IDENTIDAD 
    FROM PERSONAS 
    WHERE NUMERO_IDENTIDAD = P_NUMERO_IDENTIDAD 
      AND COD_PERSONA != V_COD_PERSONA;

    IF V_EXISTE_IDENTIDAD > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El número de identidad ya existe en otra persona.';
    ELSE
        -- Actualizar tabla PERSONAS
        UPDATE PERSONAS 
        SET PRIMER_NOMBRE = P_PRIMER_NOMBRE_C, 
            SEGUNDO_NOMBRE = P_SEGUNDO_NOMBRE_C, 
            PRIMER_APELLIDO = P_PRIMER_APELLIDO_C, 
            SEGUNDO_APELLIDO = P_SEGUNDO_APELLIDO_C, 
            NUMERO_IDENTIDAD = P_NUMERO_IDENTIDAD, 
            RTN = P_RTN
        WHERE COD_PERSONA = V_COD_PERSONA;

        -- Actualizar tabla CLIENTES
        UPDATE CLIENTES
        SET PRIMER_NOMBRE_C = P_PRIMER_NOMBRE_C,
            SEGUNDO_NOMBRE_C = P_SEGUNDO_NOMBRE_C,
            PRIMER_APELLIDO_C = P_PRIMER_APELLIDO_C,
            SEGUNDO_APELLIDO_C = P_SEGUNDO_APELLIDO_C
        WHERE COD_CLIENTE = P_COD_CLIENTE;

        IF EXISTS (SELECT 1 FROM TELEFONOS WHERE COD_PERSONA = V_COD_PERSONA AND NUMERO_TELEFONO != P_NUMERO_TELEFONO) THEN
            UPDATE TELEFONOS 
            SET NUMERO_TELEFONO = P_NUMERO_TELEFONO, 
                TIPO_TELEFONO = P_TIPO_TELEFONO
            WHERE COD_PERSONA = V_COD_PERSONA;
        END IF;

        IF EXISTS (SELECT 1 FROM CORREOS WHERE COD_PERSONA = V_COD_PERSONA) THEN
            UPDATE CORREOS 
            SET CORREO_ELECTRONICO = P_CORREO_ELECTRONICO, 
                TIPO = P_TIPO_CORREO
            WHERE COD_PERSONA = V_COD_PERSONA;
        ELSE
            INSERT INTO CORREOS (COD_PERSONA, CORREO_ELECTRONICO, TIPO)
            VALUES (V_COD_PERSONA, P_CORREO_ELECTRONICO, P_TIPO_CORREO);
        END IF;

        IF EXISTS (SELECT 1 FROM DIRECCIONES WHERE COD_PERSONA = V_COD_PERSONA) THEN
            UPDATE DIRECCIONES 
            SET CALLE = P_CALLE, 
                CIUDAD = P_CIUDAD, 
                PAIS = P_PAIS, 
                CODIGO_POSTAL = P_CODIGO_POSTAL, 
                TIPO = P_TIPO_DIRECCION
            WHERE COD_PERSONA = V_COD_PERSONA;
        ELSE
            INSERT INTO DIRECCIONES (COD_PERSONA, CALLE, CIUDAD, PAIS, CODIGO_POSTAL, TIPO)
            VALUES (V_COD_PERSONA, P_CALLE, P_CIUDAD, P_PAIS, P_CODIGO_POSTAL, P_TIPO_DIRECCION);
        END IF;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarEmpleado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarEmpleado`(
    IN p_cod_empleado INT,
    IN p_primer_nombre VARCHAR(30),
    IN p_segundo_nombre VARCHAR(30),
    IN p_primer_apellido VARCHAR(30),
    IN p_segundo_apellido VARCHAR(30),
    IN p_numero_identidad VARCHAR(16),
    IN p_rtn VARCHAR(16),
    IN p_puesto ENUM('ADMINISTRADOR', 'VENDEDOR', 'GERENTE', 'JEFE PRODUCCION', 'JEFE DE VENTAS'),
    IN p_numero_telefono VARCHAR(15),
    IN p_tipo_telefono ENUM('PERSONAL', 'LABORAL', 'OTRO'),
    IN p_correo_electronico VARCHAR(30),
    IN p_tipo_correo ENUM('PERSONAL', 'LABORAL', 'OTRO'),
    IN p_calle VARCHAR(50),
    IN p_ciudad VARCHAR(50),
    IN p_pais VARCHAR(50),
    IN p_codigo_postal VARCHAR(50),
    IN p_tipo_direccion ENUM('DOMICILIO', 'LABORAL', 'OTRO')
)
BEGIN
    DECLARE v_cod_persona INT;

    SELECT COD_PERSONA INTO v_cod_persona
    FROM EMPLEADOS
    WHERE COD_EMPLEADO = p_cod_empleado;

    IF v_cod_persona IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El empleado no existe.';
    ELSE
        IF EXISTS (SELECT 1 FROM PERSONAS WHERE NUMERO_IDENTIDAD = p_numero_identidad AND COD_PERSONA != v_cod_persona) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El número de identidad ya existe para otra persona.';
        ELSE
            UPDATE PERSONAS
            SET PRIMER_NOMBRE = p_primer_nombre,
                SEGUNDO_NOMBRE = p_segundo_nombre,
                PRIMER_APELLIDO = p_primer_apellido,
                SEGUNDO_APELLIDO = p_segundo_apellido,
                NUMERO_IDENTIDAD = p_numero_identidad,
                RTN = p_rtn
            WHERE COD_PERSONA = v_cod_persona;
            UPDATE EMPLEADOS
            SET PRIMER_NOMBRE_E = p_primer_nombre,
                SEGUNDO_NOMBRE_E = p_segundo_nombre,
                PRIMER_APELLIDO_E = p_primer_apellido,
                SEGUNDO_APELLIDO_E = p_segundo_apellido,
                PUESTO = p_puesto
            WHERE COD_EMPLEADO = p_cod_empleado;
            
        IF EXISTS (SELECT 1 FROM TELEFONOS WHERE COD_PERSONA = v_cod_persona AND NUMERO_TELEFONO != p_numero_telefono) THEN
            UPDATE TELEFONOS 
            SET NUMERO_TELEFONO = p_numero_telefono, 
                TIPO_TELEFONO = p_tipo_telefono
            WHERE COD_PERSONA = v_cod_persona;
        END IF;

        IF EXISTS (SELECT 1 FROM CORREOS WHERE COD_PERSONA = v_cod_persona) THEN
            UPDATE CORREOS 
            SET CORREO_ELECTRONICO = p_correo_electronico, 
                TIPO = p_tipo_correo
            WHERE COD_PERSONA = v_cod_persona;
        ELSE
            INSERT INTO CORREOS (COD_PERSONA, CORREO_ELECTRONICO, TIPO)
            VALUES (v_cod_persona, p_correo_electronico, p_tipo_correo);
        END IF;

        IF EXISTS (SELECT 1 FROM DIRECCIONES WHERE COD_PERSONA = v_cod_persona) THEN
            UPDATE DIRECCIONES 
            SET CALLE = p_calle, 
                CIUDAD = p_ciudad, 
                PAIS = p_pais, 
                CODIGO_POSTAL = p_codigo_postal, 
                TIPO = p_tipo_direccion
            WHERE COD_PERSONA = v_cod_persona;
        ELSE
            INSERT INTO DIRECCIONES (COD_PERSONA, CALLE, CIUDAD, PAIS, CODIGO_POSTAL, TIPO)
            VALUES (v_cod_persona, p_calle, p_ciudad, p_pais, p_codigo_postal, p_tipo_direccion);
        END IF;
  END IF;
END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarEmpresa` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarEmpresa`(
    IN p_cod_empresa INT,
    IN p_razon_social VARCHAR(100),
    IN p_nombre_comercial VARCHAR(50),
    IN p_rtn VARCHAR(16),
    IN p_direccion VARCHAR(200),
    IN p_ciudad VARCHAR(50),
    IN p_departamento VARCHAR(50),
    IN p_telefono VARCHAR(15),
    IN p_email VARCHAR(50),
    IN p_sitio_web VARCHAR(100),
    IN p_regimen_fiscal VARCHAR(50),
    IN p_actividad_economica VARCHAR(100)
)
BEGIN
    UPDATE EMPRESA SET
        RAZON_SOCIAL = p_razon_social,
        NOMBRE_COMERCIAL = p_nombre_comercial,
        RTN = p_rtn,
        DIRECCION = p_direccion,
        CIUDAD = p_ciudad,
        DEPARTAMENTO = p_departamento,
        TELEFONO = p_telefono,
        EMAIL = p_email,
        SITIO_WEB = p_sitio_web,
        REGIMEN_FISCAL = p_regimen_fiscal,
        ACTIVIDAD_ECONOMICA = p_actividad_economica
    WHERE COD_EMPRESA = p_cod_empresa;
    
    SELECT 'Datos de empresa actualizados correctamente' AS MENSAJE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarInventarioProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarInventarioProducto`(
    IN p_codigo_producto VARCHAR(15),  
    IN p_cod_sucursal INT,
    IN p_stock_minimo DECIMAL(10,2),
    IN p_precio_venta DECIMAL(10,2)
)
BEGIN
    -- Declarar variables al inicio del bloque
    DECLARE v_cod_producto INT;
    DECLARE v_existe INT;

    -- Obtener el COD_PRODUCTO basado en el código del producto
    SELECT COD_PRODUCTO INTO v_cod_producto
    FROM PRODUCTOS
    WHERE CODIGO = p_codigo_producto;

    -- Verificar si el producto existe
    IF v_cod_producto IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El producto no existe';
    ELSE
        -- Verificar si el producto existe en el inventario de la sucursal
        SELECT COUNT(*) INTO v_existe
        FROM INVENTARIOPRODUCTOS
        WHERE COD_PRODUCTO = v_cod_producto AND COD_SUCURSAL = p_cod_sucursal;

        IF v_existe = 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Error: El producto no existe en el inventario de la sucursal especificada';
        ELSE
            -- Actualizar el inventario (solo stock mínimo y precio de venta)
            UPDATE INVENTARIOPRODUCTOS
            SET 
                STOCK_MINIMO = p_stock_minimo,
                PRECIO_VENTA = p_precio_venta
            WHERE COD_PRODUCTO = v_cod_producto AND COD_SUCURSAL = p_cod_sucursal;

            SELECT 'Inventario actualizado correctamente' AS MENSAJE;
        END IF;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarLogoEmpresa` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarLogoEmpresa`(
    IN p_cod_empresa INT,
    IN p_logo LONGBLOB
)
BEGIN
    UPDATE EMPRESA SET
        LOGO = p_logo
    WHERE COD_EMPRESA = p_cod_empresa;
    
    SELECT 'Logo de empresa actualizado correctamente' AS MENSAJE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarProducto`(
    IN p_cod_producto INT,
    IN p_codigo VARCHAR(15),
    IN p_modelo VARCHAR(50),
    IN p_descripcion VARCHAR(200),
    IN p_cod_categoria INT,
    IN p_tiempo_garantia INT
)
BEGIN
    DECLARE v_codigo_anterior VARCHAR(15);

    SELECT CODIGO INTO v_codigo_anterior
    FROM PRODUCTOS
    WHERE COD_PRODUCTO = p_cod_producto;

    UPDATE PRODUCTOS
    SET 
        CODIGO = p_codigo,
        MODELO = p_modelo,
        DESCRIPCION = p_descripcion,
        COD_CATEGORIA = p_cod_categoria,
        TIEMPO_GARANTIA = p_tiempo_garantia
    WHERE COD_PRODUCTO = p_cod_producto;

    IF v_codigo_anterior <> p_codigo THEN
        UPDATE INVENTARIOPRODUCTOS
        SET COD_PRODUCTO = p_cod_producto
        WHERE COD_PRODUCTO = p_cod_producto;
    END IF;

    SELECT 'Producto actualizado correctamente' AS MENSAJE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarProveedor` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarProveedor`(
    IN P_COD_PROVEEDOR INT,
    IN P_NOMBRE_PROVEEDOR VARCHAR(100),
    IN P_NOMBRE_CONTACTO VARCHAR(100),
    IN P_APELLIDO_CONTACTO VARCHAR(100),
    IN P_NUMERO_IDENTIDAD VARCHAR(16),
    IN P_RTN VARCHAR(16),
    IN P_CALLE VARCHAR(50),
    IN P_CIUDAD VARCHAR(50),
    IN P_PAIS VARCHAR(50),
    IN P_CODIGO_POSTAL VARCHAR(50),
    IN P_TELEFONO VARCHAR(15),
    IN P_CORREO VARCHAR(100)
)
BEGIN
    DECLARE V_COD_PERSONA INT;

    SELECT COD_PERSONA 
    INTO V_COD_PERSONA FROM PROVEEDORES WHERE COD_PROVEEDORES = P_COD_PROVEEDOR;

    UPDATE PERSONAS
    SET PRIMER_NOMBRE = P_NOMBRE_CONTACTO,
        PRIMER_APELLIDO = P_APELLIDO_CONTACTO,
        NUMERO_IDENTIDAD = P_NUMERO_IDENTIDAD,
        RTN = P_RTN
    WHERE COD_PERSONA = V_COD_PERSONA;

    UPDATE PROVEEDORES
    SET NOMBRE_EMPRESA = P_NOMBRE_PROVEEDOR,
        NOMBRE_CONTACTO = P_NOMBRE_CONTACTO,
        APELLIDO_CONTACTO = P_APELLIDO_CONTACTO
    WHERE COD_PROVEEDORES = P_COD_PROVEEDOR;

    UPDATE DIRECCIONES
    SET CALLE = P_CALLE,
        CIUDAD = P_CIUDAD,
        PAIS = P_PAIS,
        CODIGO_POSTAL = P_CODIGO_POSTAL
    WHERE COD_PERSONA = V_COD_PERSONA AND TIPO = 'LABORAL';

    UPDATE TELEFONOS
    SET NUMERO_TELEFONO = P_TELEFONO
    WHERE COD_PERSONA = V_COD_PERSONA AND TIPO_TELEFONO = 'LABORAL';

    UPDATE CORREOS
    SET CORREO_ELECTRONICO = P_CORREO
    WHERE COD_PERSONA = V_COD_PERSONA AND TIPO = 'LABORAL';

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarPuntoEmision` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarPuntoEmision`(
    IN p_cod_punto_emision INT,
    IN p_codigo VARCHAR(3),
    IN p_nombre VARCHAR(50),
    IN p_establecimiento VARCHAR(3),
    -- IN p_direccion VARCHAR(200),
   --  IN p_telefono VARCHAR(15),
    IN p_estado ENUM('ACTIVO', 'INACTIVO'),
    IN p_cod_sucursal INT
)
BEGIN
    UPDATE PUNTOS_EMISION SET
        CODIGO = p_codigo,
        NOMBRE = p_nombre,
        ESTABLECIMIENTO = p_establecimiento,
       -- DIRECCION = p_direccion,
       -- TELEFONO = p_telefono,
        ESTADO = p_estado,
        COD_SUCURSAL = p_cod_sucursal
    WHERE COD_PUNTO_EMISION = p_cod_punto_emision;
    
    SELECT 'Punto de emisión actualizado correctamente' AS MENSAJE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarSucursal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarSucursal`(
    IN p_cod_sucursal INT,
    IN p_nombre VARCHAR(50),
    IN p_direccion VARCHAR(100),
    IN p_cod_empleado_encargado INT
)
BEGIN
    UPDATE SUCURSALES 
    SET NOMBRE = p_nombre,
        DIRECCION = p_direccion,
        COD_EMPLEADO_ENCARGADO = p_cod_empleado_encargado
    WHERE COD_SUCURSAL = p_cod_sucursal;
    SELECT E.COD_EMPLEADO, 
           CONCAT(P.PRIMER_NOMBRE, ' ', P.PRIMER_APELLIDO) AS NOMBRE_ENCARGADO, 
           T.NUMERO_TELEFONO 
    FROM EMPLEADOS E
    JOIN PERSONAS P ON E.COD_PERSONA = P.COD_PERSONA
    LEFT JOIN TELEFONOS T ON P.COD_PERSONA = T.COD_PERSONA
    WHERE E.COD_EMPLEADO = p_cod_empleado_encargado;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ActualizarTipoDocumento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarTipoDocumento`(
    IN p_cod_tipo_documento INT,
    IN p_codigo VARCHAR(2),
    IN p_nombre VARCHAR(50),
    IN p_descripcion VARCHAR(200),
    IN p_afecta_inventario BOOLEAN,
    IN p_requiere_cliente BOOLEAN,
    IN p_estado ENUM('ACTIVO', 'INACTIVO')
)
BEGIN
    UPDATE TIPOS_DOCUMENTO SET
        CODIGO = p_codigo,
        NOMBRE = p_nombre,
        DESCRIPCION = p_descripcion,
        AFECTA_INVENTARIO = p_afecta_inventario,
        REQUIERE_CLIENTE = p_requiere_cliente,
        ESTADO = p_estado
    WHERE COD_TIPO_DOCUMENTO = p_cod_tipo_documento;
    
    SELECT 'Tipo de documento actualizado correctamente' AS MENSAJE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_AgregarDetalleFacturaVenta` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_AgregarDetalleFacturaVenta`(
    IN p_NUMERO_FACTURA VARCHAR(15),
    IN p_COD_PRODUCTO INT,
    IN p_CANTIDAD DECIMAL(10,2),
    OUT p_MENSAJE VARCHAR(255)
)
BEGIN
    DECLARE v_COD_FACTURA INT;
    DECLARE v_STOCK_ACTUAL DECIMAL(10,2);
    DECLARE v_COD_SUCURSAL INT;
    DECLARE v_PRECIO_VENTA DECIMAL(10,2);

    -- Iniciar transacción
    START TRANSACTION;

    -- Obtener el COD_FACTURA y COD_SUCURSAL de la factura
    SELECT COD_FACTURA, COD_SUCURSAL
    INTO v_COD_FACTURA, v_COD_SUCURSAL
    FROM FACTURASVENTA
    WHERE NUMERO_FACTURA = p_NUMERO_FACTURA;

    IF v_COD_FACTURA IS NULL THEN
        SET p_MENSAJE = 'Error: La factura no existe.';
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_MENSAJE;
    END IF;

    -- Verificar si el producto existe
    IF NOT EXISTS (SELECT 1 FROM PRODUCTOS WHERE COD_PRODUCTO = p_COD_PRODUCTO) THEN
        SET p_MENSAJE = 'Error: El producto no existe.';
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_MENSAJE;
    END IF;

    -- Obtener el stock actual y precio de venta del producto en la sucursal
    SELECT STOCK_ACTUAL, PRECIO_VENTA
    INTO v_STOCK_ACTUAL, v_PRECIO_VENTA
    FROM INVENTARIOPRODUCTOS
    WHERE COD_PRODUCTO = p_COD_PRODUCTO AND COD_SUCURSAL = v_COD_SUCURSAL;

    IF v_STOCK_ACTUAL IS NULL THEN
        SET p_MENSAJE = 'Error: El producto no está disponible en esta sucursal.';
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_MENSAJE;
    END IF;

    -- Verificar stock suficiente
    IF v_STOCK_ACTUAL < p_CANTIDAD THEN
        SET p_MENSAJE = 'Error: Stock insuficiente.';
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_MENSAJE;
    END IF;

    -- Insertar el detalle de la venta
    INSERT INTO DETALLEVENTA (COD_FACTURA, COD_PRODUCTO, CANTIDAD, PRECIO)
    VALUES (v_COD_FACTURA, p_COD_PRODUCTO, p_CANTIDAD, v_PRECIO_VENTA);

    -- Actualizar el stock en INVENTARIOPRODUCTOS
    UPDATE INVENTARIOPRODUCTOS
    SET STOCK_ACTUAL = STOCK_ACTUAL - p_CANTIDAD
    WHERE COD_PRODUCTO = p_COD_PRODUCTO AND COD_SUCURSAL = v_COD_SUCURSAL;

    -- Actualizar el subtotal de la factura
    UPDATE FACTURASVENTA
    SET SUBTOTAL = (SELECT COALESCE(SUM(SUBTOTAL), 0) FROM DETALLEVENTA WHERE COD_FACTURA = v_COD_FACTURA)
    WHERE COD_FACTURA = v_COD_FACTURA;

    -- Confirmar transacción
    SET p_MENSAJE = 'Detalle agregado correctamente.';
    COMMIT;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_AgregarProductoFacturaVentaConCAI` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_AgregarProductoFacturaVentaConCAI`(
    IN p_cod_factura INT,                -- Código de la factura
    IN p_codigo_producto VARCHAR(15),    -- Código del producto
    IN p_cantidad DECIMAL(10,2),         -- Cantidad a vender
    IN p_precio_override DECIMAL(10,2)   -- Precio manual (NULL para usar precio de inventario)
)
BEGIN
    DECLARE v_cod_producto INT;
    DECLARE v_precio DECIMAL(10,2);
    DECLARE v_cod_sucursal INT;
    DECLARE v_stock_actual DECIMAL(10,2);
    DECLARE v_estado_factura VARCHAR(20);
    DECLARE v_subtotal_actual DECIMAL(10,2);
    DECLARE v_subtotal_nuevo DECIMAL(10,2);
    
    -- Verificar que la factura exista y esté en estado PENDIENTE
    SELECT ESTADO, COD_SUCURSAL, SUBTOTAL INTO v_estado_factura, v_cod_sucursal, v_subtotal_actual
    FROM FACTURASVENTA
    WHERE COD_FACTURA = p_cod_factura;
    
    IF v_estado_factura IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La factura no existe';
    END IF;
    
    IF v_estado_factura != 'PENDIENTE' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La factura no está en estado PENDIENTE';
    END IF;
    
    -- Obtener el código del producto y su precio
    SELECT p.COD_PRODUCTO, COALESCE(i.PRECIO_VENTA, 0) 
    INTO v_cod_producto, v_precio
    FROM PRODUCTOS p
    JOIN INVENTARIOPRODUCTOS i ON p.COD_PRODUCTO = i.COD_PRODUCTO
    WHERE p.CODIGO = p_codigo_producto AND i.COD_SUCURSAL = v_cod_sucursal;
    
    IF v_cod_producto IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El producto no existe en esta sucursal';
    END IF;
    
    -- Usar precio override si se proporciona
    IF p_precio_override IS NOT NULL AND p_precio_override > 0 THEN
        SET v_precio = p_precio_override;
    END IF;
    
    -- Validar stock
    SELECT STOCK_ACTUAL INTO v_stock_actual
    FROM INVENTARIOPRODUCTOS
    WHERE COD_PRODUCTO = v_cod_producto AND COD_SUCURSAL = v_cod_sucursal;
    
    IF v_stock_actual < p_cantidad THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Stock insuficiente';
    END IF;
    
    -- Verificar si el producto ya existe en la factura
    SELECT COUNT(*) INTO @existe_producto
    FROM DETALLEVENTA
    WHERE COD_FACTURA = p_cod_factura AND COD_PRODUCTO = v_cod_producto;
    
    IF @existe_producto > 0 THEN
        -- Actualizar cantidad y precio si ya existe
        UPDATE DETALLEVENTA
        SET CANTIDAD = CANTIDAD + p_cantidad,
            PRECIO = v_precio
        WHERE COD_FACTURA = p_cod_factura AND COD_PRODUCTO = v_cod_producto;
    ELSE
        -- Insertar en detalle de venta si es nuevo
        INSERT INTO DETALLEVENTA(COD_FACTURA, COD_PRODUCTO, CANTIDAD, PRECIO)
        VALUES(p_cod_factura, v_cod_producto, p_cantidad, v_precio);
    END IF;
    
    -- Calcular nuevo subtotal
    SELECT SUM(CANTIDAD * PRECIO) INTO v_subtotal_nuevo
    FROM DETALLEVENTA
    WHERE COD_FACTURA = p_cod_factura;
    
    -- Actualizar subtotal de la factura
    UPDATE FACTURASVENTA
    SET SUBTOTAL = v_subtotal_nuevo
    WHERE COD_FACTURA = p_cod_factura;
    
    -- No actualizamos el inventario todavía, eso se hará al finalizar la factura
    
    SELECT 
        'Producto agregado a la factura correctamente' AS MENSAJE,
        p_codigo_producto AS CODIGO_PRODUCTO,
        p_cantidad AS CANTIDAD,
        v_precio AS PRECIO_UNITARIO,
        (p_cantidad * v_precio) AS SUBTOTAL_LINEA,
        v_subtotal_nuevo AS SUBTOTAL_FACTURA;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_AgregarStockProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_AgregarStockProducto`(
    IN p_codigo VARCHAR(15),
    IN p_cod_sucursal INT,
    IN p_cantidad DECIMAL(10,2),
    OUT p_mensaje VARCHAR(255)
)
BEGIN
    DECLARE v_cod_producto INT;

    -- Buscar el COD_PRODUCTO correspondiente al CODIGO
    SELECT COD_PRODUCTO INTO v_cod_producto
    FROM PRODUCTOS
    WHERE CODIGO = p_codigo;

    -- Verificar si se encontró el producto
    IF v_cod_producto IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: No se encontró un producto con ese código';
    ELSE
        -- Verificar si el producto existe en el inventario de la sucursal
        IF EXISTS (
            SELECT 1 FROM INVENTARIOPRODUCTOS 
            WHERE COD_PRODUCTO = v_cod_producto 
            AND COD_SUCURSAL = p_cod_sucursal
        ) THEN
            -- Actualizar el stock actual sumando la cantidad
            UPDATE INVENTARIOPRODUCTOS
            SET STOCK_ACTUAL = STOCK_ACTUAL + p_cantidad
            WHERE COD_PRODUCTO = v_cod_producto 
            AND COD_SUCURSAL = p_cod_sucursal;

            SET p_mensaje = 'Stock agregado correctamente';
        ELSE
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Error: El producto no está registrado en esta sucursal';
        END IF;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_AnularFacturaVentaConCAI` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_AnularFacturaVentaConCAI`(
    IN p_cod_factura INT,
    IN p_motivo VARCHAR(200)
)
BEGIN
    DECLARE v_estado_factura VARCHAR(20);
    DECLARE v_cod_sucursal INT;
    
    -- Verificar que la factura exista
    SELECT ESTADO, COD_SUCURSAL INTO v_estado_factura, v_cod_sucursal
    FROM FACTURASVENTA
    WHERE COD_FACTURA = p_cod_factura;
    
    IF v_estado_factura IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La factura no existe';
    END IF;
    
    IF v_estado_factura = 'ANULADA' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La factura ya está anulada';
    END IF;
    
    -- Anular garantías asociadas
    UPDATE GARANTIAS
    SET ESTADO = 'CANCELADA'
    WHERE COD_FACTURA_VENTA = p_cod_factura;
    
    -- Si la factura estaba PAGADA, devolver productos al inventario
    IF v_estado_factura = 'PAGADA' THEN
        UPDATE INVENTARIOPRODUCTOS i
        JOIN DETALLEVENTA dv ON i.COD_PRODUCTO = dv.COD_PRODUCTO
        SET i.STOCK_ACTUAL = i.STOCK_ACTUAL + dv.CANTIDAD
        WHERE dv.COD_FACTURA = p_cod_factura AND i.COD_SUCURSAL = v_cod_sucursal;
    END IF;
    
    -- Actualizar estado de la factura
    UPDATE FACTURASVENTA
    SET 
        ESTADO = 'ANULADA',
        NOTAS = CONCAT(IFNULL(NOTAS, ''), ' | Anulada: ', p_motivo)
    WHERE COD_FACTURA = p_cod_factura;
    
    SELECT 'Factura anulada correctamente' AS MENSAJE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_AutenticarUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_AutenticarUsuario`(
    IN p_nombre_usuario VARCHAR(255)
)
BEGIN
    DECLARE v_cod_usuario BIGINT;
    DECLARE v_contrasena_hash VARCHAR(2000);
    DECLARE v_cod_rol BIGINT;
    DECLARE v_nombre_rol VARCHAR(255);
    DECLARE v_estado ENUM('1', '0');

    -- Obtener el usuario y verificar si existe
    SELECT COD_USUARIO, CONTRASENA, COD_ROL, ESTADO_USUARIO
    INTO v_cod_usuario, v_contrasena_hash, v_cod_rol, v_estado
    FROM USUARIOS
    WHERE NOMBRE_USUARIO = p_nombre_usuario;

    -- Verificar si el usuario existe
    IF v_cod_usuario IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Usuario no encontrado.';
    END IF;

    -- Verificar si la cuenta está activa
    IF v_estado = '0' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Cuenta desactivada.';
    END IF;

    -- Obtener nombre del rol
    SELECT NOMBRE_ROL INTO v_nombre_rol FROM ROLES WHERE COD_ROL = v_cod_rol;

    -- Devolver la información del usuario y la contraseña hasheada para compararla en Node.js
    SELECT v_cod_usuario AS COD_USUARIO, p_nombre_usuario AS NOMBRE_USUARIO, v_contrasena_hash AS CONTRASENA, v_cod_rol AS COD_ROL, v_nombre_rol AS ROL;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_BuscarFacturas` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_BuscarFacturas`(
    IN p_numero_factura VARCHAR(15),        -- Número interno de factura
    IN p_numero_fiscal VARCHAR(50),         -- Número fiscal con formato CAI
    IN p_nombre_cliente VARCHAR(100),       -- Nombre del cliente
    IN p_fecha_inicio DATE,                 -- Rango de fechas
    IN p_fecha_fin DATE,
    IN p_estado ENUM('PENDIENTE', 'PAGADA', 'ANULADA')
)
BEGIN
    SELECT 
        F.COD_FACTURA,
        F.NUMERO_FACTURA,
        F.NUMERO_FISCAL,
        F.CAI,
        DATE_FORMAT(F.FECHA, '%d/%m/%Y %H:%i:%s') AS FECHA,
        CASE 
            WHEN F.COD_CLIENTE IS NOT NULL THEN CONCAT(P.PRIMER_NOMBRE, ' ', P.PRIMER_APELLIDO)
            ELSE 'CONSUMIDOR FINAL'
        END AS NOMBRE_CLIENTE,
        S.NOMBRE AS SUCURSAL,
        F.SUBTOTAL,
        F.IMPUESTO,
        F.DESCUENTO,
        F.TOTAL,
        F.METODO_PAGO,
        F.ESTADO
    FROM FACTURASVENTA F
    LEFT JOIN CLIENTES C ON F.COD_CLIENTE = C.COD_CLIENTE
    LEFT JOIN PERSONAS P ON C.COD_PERSONA = P.COD_PERSONA
    JOIN SUCURSALES S ON F.COD_SUCURSAL = S.COD_SUCURSAL
    WHERE 
        (p_numero_factura IS NULL OR F.NUMERO_FACTURA LIKE CONCAT('%', p_numero_factura, '%')) AND
        (p_numero_fiscal IS NULL OR F.NUMERO_FISCAL LIKE CONCAT('%', p_numero_fiscal, '%')) AND
        (p_nombre_cliente IS NULL OR CONCAT(P.PRIMER_NOMBRE, ' ', P.PRIMER_APELLIDO) LIKE CONCAT('%', p_nombre_cliente, '%')) AND
        (p_fecha_inicio IS NULL OR F.FECHA >= p_fecha_inicio) AND
        (p_fecha_fin IS NULL OR F.FECHA <= DATE_ADD(p_fecha_fin, INTERVAL 1 DAY)) AND
        (p_estado IS NULL OR F.ESTADO = p_estado)
    ORDER BY F.FECHA DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_CambiarEstadoSucursal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_CambiarEstadoSucursal`(
    IN p_cod_sucursal INT,
    IN p_estado ENUM('ACTIVA', 'INACTIVA')
)
BEGIN
    -- Verificar si el registro existe
    IF EXISTS (SELECT 1 FROM estado_sucursales WHERE COD_SUCURSAL = p_cod_sucursal) THEN
        -- Actualizar el estado existente
        UPDATE estado_sucursales 
        SET ESTADO = p_estado
        WHERE COD_SUCURSAL = p_cod_sucursal;
    ELSE
        -- Insertar un nuevo registro si no existe
        INSERT INTO estado_sucursales (COD_SUCURSAL, ESTADO)
        VALUES (p_cod_sucursal, p_estado);
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_CrearFacturaVentaConCAI` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_CrearFacturaVentaConCAI`(
    IN p_cod_cliente INT,                    -- Puede ser NULL para ventas al contado
    IN p_cod_sucursal INT,                   -- Sucursal donde se emite la factura
    IN p_cod_empleado INT,                   -- Empleado que realiza la venta
    IN p_impuesto DECIMAL(10,2),             -- Tasa de impuesto (ej: 0.15 para 15%)
    IN p_descuento DECIMAL(10,2),            -- Descuento general (ej: 0.05 para 5%)
    IN p_metodo_pago ENUM('EFECTIVO', 'TARJETA', 'TRANSFERENCIA', 'OTRO'),
    IN p_cod_tipo_documento INT,             -- Tipo de documento fiscal
    IN p_cod_punto_emision INT,              -- Punto de emisión
    OUT p_cod_factura INT,                   -- Código de factura generado
    OUT p_numero_factura VARCHAR(15),        -- Número de factura interno
    OUT p_numero_fiscal VARCHAR(50),         -- Número fiscal con formato CAI
    OUT p_cai VARCHAR(37)                    -- CAI utilizado
)
BEGIN
    DECLARE v_existe_cai INT DEFAULT 0;
    DECLARE v_numero_factura VARCHAR(15);
    
    -- Verificar que exista un CAI activo para el tipo de documento y punto de emisión
    SELECT COUNT(*) INTO v_existe_cai
    FROM CAI
    WHERE COD_TIPO_DOCUMENTO = p_cod_tipo_documento
    AND COD_PUNTO_EMISION = p_cod_punto_emision
    AND ESTADO = 'ACTIVO'
    AND CURDATE() BETWEEN FECHA_EMISION AND FECHA_VENCIMIENTO;
    
    IF v_existe_cai = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: No existe un CAI activo para este tipo de documento y punto de emisión';
    END IF;
    
    -- Generar número de factura interno
    CALL sp_GenerarNumeroFacturaVenta(v_numero_factura);
    
    -- Insertar la factura en la tabla FACTURASVENTA
    INSERT INTO FACTURASVENTA(
        NUMERO_FACTURA, 
        COD_CLIENTE, 
        COD_SUCURSAL, 
        FECHA,
        SUBTOTAL,
        IMPUESTO, 
        DESCUENTO, 
        METODO_PAGO,
        ESTADO
    )
    VALUES(
        v_numero_factura, 
        p_cod_cliente, 
        p_cod_sucursal, 
        NOW(),
        0,
        p_impuesto / 100,  -- Convertir el porcentaje a decimal (15 -> 0.15)
        p_descuento / 100, -- Convertir el porcentaje a decimal (5 -> 0.05)
        p_metodo_pago,
        'PENDIENTE'
    );
    
    -- Obtener el código de la factura recién insertada
    SET p_cod_factura = LAST_INSERT_ID();
    SET p_numero_factura = v_numero_factura;
    
    -- Generar el número fiscal y asignar CAI
    CALL sp_GenerarFacturaFiscal(
        p_cod_factura,
        'VENTA',
        p_cod_tipo_documento,
        p_cod_punto_emision
    );
    
    -- Obtener el número fiscal y CAI generados
    SELECT NUMERO_FISCAL, CAI INTO p_numero_fiscal, p_cai
    FROM FACTURASVENTA
    WHERE COD_FACTURA = p_cod_factura;
    
    -- Registrar el empleado que emitió la factura (si se tiene una tabla para esto)
    -- Esta parte depende de tu estructura, podrías agregar una columna COD_EMPLEADO a FACTURASVENTA
    -- o crear una tabla de auditoría
    
    -- Devolver información de la factura creada
    SELECT 
        p_cod_factura AS COD_FACTURA,
        p_numero_factura AS NUMERO_FACTURA,
        p_numero_fiscal AS NUMERO_FISCAL,
        p_cai AS CAI;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_EliminarCategoria` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_EliminarCategoria`(
    IN p_cod_categoria INT
)
BEGIN
    -- Verificar si la categoría existe antes de eliminarla
    IF EXISTS (SELECT 1 FROM CATEGORIAS WHERE COD_CATEGORIA = p_cod_categoria) THEN
        DELETE FROM CATEGORIAS WHERE COD_CATEGORIA = p_cod_categoria;
        SELECT 'Categoría eliminada correctamente' AS Mensaje;
    ELSE
        SELECT 'Error: La categoría no existe' AS Mensaje;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_EliminarCliente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_EliminarCliente`(
    IN P_COD_CLIENTE INT
)
BEGIN
    DECLARE P_COD_PERSONA INT;
    
    -- Obtener el COD_PERSONA del cliente
    SELECT COD_PERSONA INTO P_COD_PERSONA
    FROM CLIENTES
    WHERE COD_CLIENTE = P_COD_CLIENTE;
    
    -- Eliminar registros relacionados
    DELETE FROM DIRECCIONES WHERE COD_PERSONA = P_COD_PERSONA;
    DELETE FROM CORREOS WHERE COD_PERSONA = P_COD_PERSONA;
    DELETE FROM TELEFONOS WHERE COD_PERSONA = P_COD_PERSONA;

    -- Eliminar el cliente
    DELETE FROM CLIENTES WHERE COD_CLIENTE = P_COD_CLIENTE;
    
    -- Eliminar la persona
    DELETE FROM PERSONAS WHERE COD_PERSONA = P_COD_PERSONA;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_EliminarEmpleado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_EliminarEmpleado`(
    IN p_cod_empleado INT
)
BEGIN
    DECLARE v_cod_persona INT;
    
    SELECT COD_PERSONA INTO v_cod_persona
    FROM EMPLEADOS
    WHERE COD_EMPLEADO = p_cod_empleado;
    
    IF v_cod_persona IS NOT NULL THEN
        DELETE FROM DIRECCIONES
        WHERE COD_PERSONA = v_cod_persona;
        
        DELETE FROM CORREOS
        WHERE COD_PERSONA = v_cod_persona;
        
        DELETE FROM TELEFONOS
        WHERE COD_PERSONA = v_cod_persona;
        
        DELETE FROM EMPLEADOS
        WHERE COD_EMPLEADO = p_cod_empleado; 
        
        DELETE FROM PERSONAS
        WHERE COD_PERSONA = v_cod_persona;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Empleado no encontrado';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_EliminarMaterial` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_EliminarMaterial`(
    IN p_codigo VARCHAR(10)
)
BEGIN
    IF EXISTS (SELECT 1 FROM INVENTARIOMATERIALES WHERE MATERIAL_CODIGO = p_codigo) THEN
       
        DELETE FROM INVENTARIOMATERIALES WHERE MATERIAL_CODIGO = p_codigo;
    END IF;

    DELETE FROM MATERIALES WHERE CODIGO = p_codigo;

    SELECT 'Material eliminado correctamente.' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_EliminarProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_EliminarProducto`(
    IN p_cod_producto INT
)
BEGIN
    -- Verificar si el producto existe antes de eliminarlo
    IF EXISTS (SELECT 1 FROM PRODUCTOS WHERE COD_PRODUCTO = p_cod_producto) THEN
        -- Eliminar el producto
        DELETE FROM PRODUCTOS
        WHERE COD_PRODUCTO = p_cod_producto;

        SELECT 'Producto eliminado correctamente' AS MENSAJE;
    ELSE
        SELECT 'Error: El producto no existe' AS MENSAJE;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_EliminarProductoInventario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_EliminarProductoInventario`(
    IN p_cod_producto INT, 
    IN p_cod_sucursal INT,  
    IN p_eliminar_completamente BOOLEAN
)
BEGIN
    DECLARE v_existe INT;

    -- Verificar si el producto existe
    SELECT COUNT(*) INTO v_existe
    FROM PRODUCTOS
    WHERE COD_PRODUCTO = p_cod_producto;

    IF v_existe = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El producto no existe';
    ELSE
        IF p_eliminar_completamente = FALSE THEN
            -- Verificar si la sucursal existe
            SELECT COUNT(*) INTO v_existe
            FROM SUCURSALES
            WHERE COD_SUCURSAL = p_cod_sucursal;

            IF v_existe = 0 THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Error: La sucursal no existe';
            ELSE
                -- Verificar si el producto existe en el inventario de la sucursal
                SELECT COUNT(*) INTO v_existe
                FROM INVENTARIOPRODUCTOS
                WHERE COD_PRODUCTO = p_cod_producto AND COD_SUCURSAL = p_cod_sucursal;

                IF v_existe = 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Error: El producto no existe en el inventario de la sucursal especificada';
                ELSE
                    DELETE FROM INVENTARIOPRODUCTOS
                    WHERE COD_PRODUCTO = p_cod_producto AND COD_SUCURSAL = p_cod_sucursal;

                    SELECT 'Producto eliminado del inventario de la sucursal correctamente' AS MENSAJE;
                END IF;
            END IF;
        ELSE
            DELETE FROM INVENTARIOPRODUCTOS
            WHERE COD_PRODUCTO = p_cod_producto;

            DELETE FROM PRODUCTOS
            WHERE COD_PRODUCTO = p_cod_producto;

            SELECT 'Producto y registros de inventario eliminados completamente' AS MENSAJE;
        END IF;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_EliminarProveedor` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_EliminarProveedor`(
    IN P_COD_PROVEEDOR INT
)
BEGIN
    DECLARE V_COD_PERSONA INT;

    IF (SELECT COUNT(*) FROM PROVEEDORES WHERE COD_PROVEEDORES = P_COD_PROVEEDOR) = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El proveedor no existe.';
    ELSE
        SELECT COD_PERSONA INTO V_COD_PERSONA FROM PROVEEDORES WHERE COD_PROVEEDORES = P_COD_PROVEEDOR;

        DELETE FROM DIRECCIONES WHERE COD_PERSONA = V_COD_PERSONA;
        DELETE FROM TELEFONOS WHERE COD_PERSONA = V_COD_PERSONA;
        DELETE FROM CORREOS WHERE COD_PERSONA = V_COD_PERSONA;
        DELETE FROM PROVEEDORES WHERE COD_PROVEEDORES = P_COD_PROVEEDOR;
        DELETE FROM PERSONAS WHERE COD_PERSONA = V_COD_PERSONA;
    END IF;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_EliminarUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_EliminarUsuario`(
    IN p_cod_usuario BIGINT
)
BEGIN
    DELETE FROM USUARIOS WHERE COD_USUARIO = p_cod_usuario;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_Estadisticas` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_Estadisticas`()
BEGIN
    -- Declarar todas las variables al inicio
    DECLARE total_compras DECIMAL(12,2);
    DECLARE total_materiales INT;
    DECLARE stock_bajo INT;

    -- Total de Compras (suma de los totales de las facturas)
    SELECT COALESCE(SUM(TOTAL), 0) INTO total_compras 
    FROM FACTURASCOMPRA;

    -- Total de Materiales (número de materiales registrados)
    SELECT COUNT(*) INTO total_materiales 
    FROM MATERIALES;

    -- Materiales con Stock Bajo (stock actual menor al mínimo)
    SELECT COUNT(*) INTO stock_bajo 
    FROM INVENTARIOMATERIALES 
    WHERE STOCK_ACTUAL < STOCK_MINIMO;

    -- Devolver los resultados
    SELECT 
        total_compras AS total_compras,
        total_materiales AS total_materiales,
        stock_bajo AS stock_bajo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_FinalizarFacturaVenta` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_FinalizarFacturaVenta`(
    IN p_cod_factura INT,
    IN p_metodo_pago ENUM('EFECTIVO', 'TARJETA', 'TRANSFERENCIA', 'OTRO')
)
BEGIN
    DECLARE v_estado_factura VARCHAR(20);
    DECLARE v_cod_sucursal INT;
    DECLARE v_tiene_productos INT DEFAULT 0;
    
    -- Verificar que la factura exista y esté en estado PENDIENTE
    SELECT ESTADO, COD_SUCURSAL INTO v_estado_factura, v_cod_sucursal
    FROM FACTURASVENTA
    WHERE COD_FACTURA = p_cod_factura;
    
    IF v_estado_factura IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La factura no existe';
    END IF;
    
    IF v_estado_factura != 'PENDIENTE' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La factura no está en estado PENDIENTE';
    END IF;
    
    -- Verificar que la factura tenga productos
    SELECT COUNT(*) INTO v_tiene_productos
    FROM DETALLEVENTA
    WHERE COD_FACTURA = p_cod_factura;
    
    IF v_tiene_productos = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La factura no tiene productos';
    END IF;
    
    -- Actualizar el método de pago si se proporciona
    IF p_metodo_pago IS NOT NULL THEN
        UPDATE FACTURASVENTA
        SET METODO_PAGO = p_metodo_pago
        WHERE COD_FACTURA = p_cod_factura;
    END IF;
    
    -- Actualizar estado de la factura a PAGADA
    UPDATE FACTURASVENTA
    SET ESTADO = 'PAGADA'
    WHERE COD_FACTURA = p_cod_factura;
    
    -- Actualizar inventario
    UPDATE INVENTARIOPRODUCTOS i
    JOIN DETALLEVENTA dv ON i.COD_PRODUCTO = dv.COD_PRODUCTO
    SET i.STOCK_ACTUAL = i.STOCK_ACTUAL - dv.CANTIDAD
    WHERE dv.COD_FACTURA = p_cod_factura AND i.COD_SUCURSAL = v_cod_sucursal;
    
    -- Generar garantías para los productos que apliquen
    INSERT INTO GARANTIAS(
        COD_FACTURA_PEDIDO, 
        COD_FACTURA_VENTA,
        COD_PRODUCTO, 
        FECHA_INICIO, 
        FECHA_FIN, 
        ESTADO, 
        DESCRIPCION
    )
    SELECT 
        NULL,
        p_cod_factura,
        dv.COD_PRODUCTO,
        CURDATE(),
        DATE_ADD(CURDATE(), INTERVAL p.TIEMPO_GARANTIA MONTH),
        'ACTIVA',
        CONCAT('Garantía por ', p.TIEMPO_GARANTIA, ' meses')
    FROM DETALLEVENTA dv
    JOIN PRODUCTOS p ON dv.COD_PRODUCTO = p.COD_PRODUCTO
    WHERE dv.COD_FACTURA = p_cod_factura AND p.TIEMPO_GARANTIA > 0;
    
    SELECT 'Factura finalizada y emitida correctamente' AS MENSAJE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_GenerarFacturaFiscal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GenerarFacturaFiscal`(
    IN p_cod_factura INT,
    IN p_tipo_factura ENUM('VENTA', 'PEDIDO'),
    IN p_cod_tipo_documento INT,
    IN p_cod_punto_emision INT
)
BEGIN
    DECLARE v_numero_fiscal VARCHAR(50);
    DECLARE v_cai VARCHAR(37);
    
    -- Obtener el siguiente número fiscal
    CALL sp_ObtenerSiguienteNumeroDocumento(
        p_cod_tipo_documento,
        p_cod_punto_emision,
        v_numero_fiscal,
        v_cai
    );
    
    -- Actualizar la factura con el número fiscal y CAI
    IF p_tipo_factura = 'VENTA' THEN
        UPDATE FACTURASVENTA
        SET 
            NUMERO_FISCAL = v_numero_fiscal,
            CAI = v_cai
        WHERE COD_FACTURA = p_cod_factura;
    ELSE
        UPDATE FACTURASPEDIDO
        SET 
            NUMERO_FISCAL = v_numero_fiscal,
            CAI = v_cai
        WHERE COD_FACTURA = p_cod_factura;
    END IF;
    
    SELECT 
        v_numero_fiscal AS NUMERO_FISCAL,
        v_cai AS CAI;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_GenerarNumeroFacturaVenta` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GenerarNumeroFacturaVenta`(
    OUT p_numero_factura VARCHAR(15)
)
BEGIN
    DECLARE v_anio VARCHAR(4);
    DECLARE v_mes VARCHAR(2);
    DECLARE v_ultimo_numero INT;
    DECLARE v_prefijo VARCHAR(7);
    DECLARE v_nuevo_numero VARCHAR(8);
    
    -- Obtener año y mes actual para el prefijo
    SET v_anio = DATE_FORMAT(NOW(), '%Y');
    SET v_mes = DATE_FORMAT(NOW(), '%m');
    
    -- Crear prefijo con formato AAAAMM-
    SET v_prefijo = CONCAT(v_anio, v_mes, '-');
    
    -- Buscar el último número utilizado con este prefijo
    SELECT 
        IFNULL(
            MAX(
                CAST(
                    SUBSTRING_INDEX(NUMERO_FACTURA, '-', -1) AS UNSIGNED
                )
            ), 
            0
        ) INTO v_ultimo_numero
    FROM FACTURASVENTA
    WHERE NUMERO_FACTURA LIKE CONCAT(v_prefijo, '%');
    
    -- Incrementar el número
    SET v_ultimo_numero = v_ultimo_numero + 1;
    
    -- Formatear el nuevo número con ceros a la izquierda (8 dígitos)
    SET v_nuevo_numero = LPAD(v_ultimo_numero, 8, '0');
    
    -- Crear el número de factura completo
    SET p_numero_factura = CONCAT(v_prefijo, v_nuevo_numero);
    
    -- Verificar que no exista ya (por seguridad)
    SELECT COUNT(*) INTO @existe
    FROM FACTURASVENTA
    WHERE NUMERO_FACTURA = p_numero_factura;
    
    -- Si ya existe (muy improbable), incrementar y verificar de nuevo
    WHILE @existe > 0 DO
        SET v_ultimo_numero = v_ultimo_numero + 1;
        SET v_nuevo_numero = LPAD(v_ultimo_numero, 8, '0');
        SET p_numero_factura = CONCAT(v_prefijo, v_nuevo_numero);
        
        SELECT COUNT(*) INTO @existe
        FROM FACTURASVENTA
        WHERE NUMERO_FACTURA = p_numero_factura;
    END WHILE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarCAI` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarCAI`(
    IN p_codigo_cai VARCHAR(37),
    IN p_fecha_emision DATE,
    IN p_fecha_vencimiento DATE,
    IN p_cod_tipo_documento INT,
    IN p_cod_punto_emision INT,
    IN p_establecimiento VARCHAR(3),
    IN p_punto_emision_codigo VARCHAR(3),
    IN p_tipo_documento_codigo VARCHAR(2),
    IN p_rango_inicial VARCHAR(8),
    IN p_rango_final VARCHAR(8)
)
BEGIN
    DECLARE v_prefijo VARCHAR(15);
    DECLARE v_cod_cai INT;
    
    -- Validar que el CAI no exista ya para el mismo tipo de documento y punto de emisión
    SELECT COUNT(*) INTO @existe
    FROM CAI
    WHERE COD_TIPO_DOCUMENTO = p_cod_tipo_documento
    AND COD_PUNTO_EMISION = p_cod_punto_emision
    AND ESTADO = 'ACTIVO';
    
    IF @existe > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Ya existe un CAI activo para este tipo de documento y punto de emisión';
    END IF;
    
    -- Insertar el CAI
    INSERT INTO CAI (
        CODIGO_CAI,
        FECHA_EMISION,
        FECHA_VENCIMIENTO,
        COD_TIPO_DOCUMENTO,
        COD_PUNTO_EMISION,
        ESTABLECIMIENTO,
        PUNTO_EMISION_CODIGO,
        TIPO_DOCUMENTO_CODIGO,
        RANGO_INICIAL,
        RANGO_FINAL,
        RANGO_ACTUAL,
        ESTADO
    ) VALUES (
        p_codigo_cai,
        p_fecha_emision,
        p_fecha_vencimiento,
        p_cod_tipo_documento,
        p_cod_punto_emision,
        p_establecimiento,
        p_punto_emision_codigo,
        p_tipo_documento_codigo,
        p_rango_inicial,
        p_rango_final,
        p_rango_inicial,
        'ACTIVO'
    );
    
    SET v_cod_cai = LAST_INSERT_ID();
    
    -- Crear el prefijo para el correlativo
    SET v_prefijo = CONCAT(
        p_establecimiento,
        p_punto_emision_codigo,
        p_tipo_documento_codigo
    );
    
    -- Insertar el correlativo
    INSERT INTO CORRELATIVOS (
        COD_CAI,
        PREFIJO,
        ULTIMO_NUMERO
    ) VALUES (
        v_cod_cai,
        v_prefijo,
        CAST(p_rango_inicial AS UNSIGNED) - 1
    );
    
    SELECT v_cod_cai AS COD_CAI;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarCategoria` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarCategoria`(
    IN p_nombre VARCHAR(50),
    IN p_descripcion VARCHAR(100)
)
BEGIN
    INSERT INTO CATEGORIAS(NOMBRE, DESCRIPCION)
    VALUES(p_nombre, p_descripcion);
    SELECT LAST_INSERT_ID() AS COD_CATEGORIA;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarCliente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarCliente`(
    IN P_PRIMER_NOMBRE_C VARCHAR(100), 
    IN P_SEGUNDO_NOMBRE_C VARCHAR(100),
    IN P_PRIMER_APELLIDO_C VARCHAR(100), 
    IN P_SEGUNDO_APELLIDO_C VARCHAR(100),
    IN P_NUMERO_IDENTIDAD VARCHAR(16),
    IN P_RTN VARCHAR(16),
    IN P_NUMERO_TELEFONO VARCHAR(15), 
    IN P_TIPO_TELEFONO ENUM('PERSONAL', 'LABORAL', 'OTRO'),
    IN P_CORREO_ELECTRONICO VARCHAR(30), 
    IN P_TIPO_CORREO ENUM('PERSONAL', 'LABORAL', 'OTRO'),
    IN P_CALLE VARCHAR(50), 
    IN P_CIUDAD VARCHAR(50), 
    IN P_PAIS VARCHAR(50), 
    IN P_CODIGO_POSTAL VARCHAR(50),
    IN P_TIPO_DIRECCION ENUM('DOMICILIO', 'LABORAL', 'OTRO')
)
BEGIN
    DECLARE V_COD_PERSONA INT;
    
    INSERT INTO PERSONAS (PRIMER_NOMBRE, SEGUNDO_NOMBRE, PRIMER_APELLIDO, SEGUNDO_APELLIDO, NUMERO_IDENTIDAD, RTN)
    VALUES (P_PRIMER_NOMBRE_C, P_SEGUNDO_NOMBRE_C, P_PRIMER_APELLIDO_C, P_SEGUNDO_APELLIDO_C, P_NUMERO_IDENTIDAD, P_RTN);
    
    SET V_COD_PERSONA = LAST_INSERT_ID();

    INSERT INTO CLIENTES (COD_PERSONA, PRIMER_NOMBRE_C, SEGUNDO_NOMBRE_C, PRIMER_APELLIDO_C, SEGUNDO_APELLIDO_C)
    VALUES (V_COD_PERSONA, P_PRIMER_NOMBRE_C, P_SEGUNDO_NOMBRE_C, P_PRIMER_APELLIDO_C, P_SEGUNDO_APELLIDO_C);

    INSERT INTO TELEFONOS (COD_PERSONA, NUMERO_TELEFONO, TIPO_TELEFONO)
    VALUES (V_COD_PERSONA, P_NUMERO_TELEFONO, P_TIPO_TELEFONO);

    INSERT INTO CORREOS (COD_PERSONA, CORREO_ELECTRONICO, TIPO)
    VALUES (V_COD_PERSONA, P_CORREO_ELECTRONICO, P_TIPO_CORREO);

    INSERT INTO DIRECCIONES (COD_PERSONA, CALLE, CIUDAD, PAIS, CODIGO_POSTAL, TIPO)
    VALUES (V_COD_PERSONA, P_CALLE, P_CIUDAD, P_PAIS, P_CODIGO_POSTAL, P_TIPO_DIRECCION);
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarDetalleCompra` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarDetalleCompra`(
    IN p_numero_factura VARCHAR(20),
    IN p_material_codigo VARCHAR(10),
    IN p_cantidad DECIMAL(10,2),
    IN p_precio DECIMAL(10,2)
)
BEGIN
    DECLARE v_cod_factura INT;

    SELECT COD_FACTURA INTO v_cod_factura
    FROM FACTURASCOMPRA
    WHERE NUMERO_FACTURA = p_numero_factura
    LIMIT 1;

    IF v_cod_factura IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La factura no existe.';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM MATERIALES WHERE CODIGO = p_material_codigo) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El material no existe.';
    END IF;

    INSERT INTO DETALLECOMPRA (CODIGO_FACTURA, MATERIAL_CODIGO, CANTIDAD, PRECIO)
    VALUES (v_cod_factura, p_material_codigo, p_cantidad, p_precio);
    
    UPDATE INVENTARIOMATERIALES
    SET STOCK_ACTUAL = STOCK_ACTUAL + p_cantidad
    WHERE MATERIAL_CODIGO = p_material_codigo;

    SELECT 'Detalle de compra registrado y stock actualizado' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarEmpleado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarEmpleado`(
    IN p_primer_nombre VARCHAR(30),
    IN p_segundo_nombre VARCHAR(30),
    IN p_primer_apellido VARCHAR(30),
    IN p_segundo_apellido VARCHAR(30),
    IN p_numero_identidad VARCHAR(16),
    IN p_rtn VARCHAR(16),
    IN p_puesto ENUM('ADMINISTRADOR', 'VENDEDOR', 'GERENTE', 'JEFE PRODUCCION', 'JEFE DE VENTAS'),
    IN p_numero_telefono VARCHAR(15),
    IN p_tipo_telefono ENUM('PERSONAL', 'LABORAL', 'OTRO'),
    IN p_correo_electronico VARCHAR(30),
    IN p_tipo_correo ENUM('PERSONAL', 'LABORAL', 'OTRO'),
    IN p_calle VARCHAR(50),
    IN p_ciudad VARCHAR(50),
    IN p_pais VARCHAR(50),
    IN p_codigo_postal VARCHAR(50),
    IN p_tipo_direccion ENUM('DOMICILIO', 'LABORAL', 'OTRO')
)
BEGIN
    DECLARE v_cod_persona INT;
    
    INSERT INTO PERSONAS (PRIMER_NOMBRE, SEGUNDO_NOMBRE, PRIMER_APELLIDO, SEGUNDO_APELLIDO, NUMERO_IDENTIDAD, RTN)
    VALUES (p_primer_nombre, p_segundo_nombre, p_primer_apellido, p_segundo_apellido, p_numero_identidad, p_rtn);
    
    SET v_cod_persona = LAST_INSERT_ID();
    
    INSERT INTO EMPLEADOS (COD_PERSONA, PRIMER_NOMBRE_E, SEGUNDO_NOMBRE_E, PRIMER_APELLIDO_E, SEGUNDO_APELLIDO_E, PUESTO)
    VALUES (v_cod_persona, p_primer_nombre, p_segundo_nombre, p_primer_apellido, p_segundo_apellido, p_puesto);
    
    INSERT INTO TELEFONOS (COD_PERSONA, NUMERO_TELEFONO, TIPO_TELEFONO)
    VALUES (v_cod_persona, p_numero_telefono, p_tipo_telefono);
    
    INSERT INTO CORREOS (COD_PERSONA, CORREO_ELECTRONICO, TIPO)
    VALUES (v_cod_persona, p_correo_electronico, p_tipo_correo);
    
    INSERT INTO DIRECCIONES (COD_PERSONA, CALLE, CIUDAD, PAIS, CODIGO_POSTAL, TIPO)
    VALUES (v_cod_persona, p_calle, p_ciudad, p_pais, p_codigo_postal, p_tipo_direccion);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarEmpresa` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarEmpresa`(
    IN p_razon_social VARCHAR(100),
    IN p_nombre_comercial VARCHAR(50),
    IN p_rtn VARCHAR(16),
    IN p_direccion VARCHAR(200),
    IN p_ciudad VARCHAR(50),
    IN p_departamento VARCHAR(50),
    IN p_telefono VARCHAR(15),
    IN p_email VARCHAR(50),
    IN p_sitio_web VARCHAR(100),
    IN p_regimen_fiscal VARCHAR(50),
    IN p_actividad_economica VARCHAR(100)
)
BEGIN
    INSERT INTO EMPRESA (
        RAZON_SOCIAL, 
        NOMBRE_COMERCIAL, 
        RTN, 
        DIRECCION, 
        CIUDAD, 
        DEPARTAMENTO, 
        TELEFONO, 
        EMAIL, 
        SITIO_WEB, 
        REGIMEN_FISCAL, 
        ACTIVIDAD_ECONOMICA
    ) VALUES (
        p_razon_social,
        p_nombre_comercial,
        p_rtn,
        p_direccion,
        p_ciudad,
        p_departamento,
        p_telefono,
        p_email,
        p_sitio_web,
        p_regimen_fiscal,
        p_actividad_economica
    );
    
    SELECT LAST_INSERT_ID() AS COD_EMPRESA;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarFacturaCompra` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarFacturaCompra`(
    IN p_numero_factura VARCHAR(10),
    IN p_cod_proveedor INT,
    IN p_impuesto DECIMAL(10,2),
    IN p_descuento DECIMAL(10,2)
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM PROVEEDORES WHERE COD_PROVEEDORES = p_cod_proveedor) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El proveedor no existe.';
    END IF;
    
      SET p_impuesto = p_impuesto / 100;
    SET p_descuento = p_descuento / 100;

    INSERT INTO FACTURASCOMPRA (NUMERO_FACTURA, COD_PROVEEDORES, IMPUESTO, DESCUENTO)
    VALUES (p_numero_factura, p_cod_proveedor, p_impuesto, p_descuento);
    
    SELECT LAST_INSERT_ID() AS COD_FACTURA;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarMaterial` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarMaterial`(
    IN p_codigo VARCHAR(10),
    IN p_material VARCHAR(30)
)
BEGIN
    IF EXISTS (SELECT 1 FROM MATERIALES WHERE CODIGO = p_codigo) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El código del material ya existe.';
    END IF;

    IF EXISTS (SELECT 1 FROM MATERIALES WHERE MATERIAL = p_material) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El material ya está registrado.';
    END IF;

    INSERT INTO MATERIALES (CODIGO, MATERIAL)
    VALUES (p_codigo, p_material);

    INSERT INTO INVENTARIOMATERIALES (MATERIAL_CODIGO, STOCK_ACTUAL, STOCK_MINIMO)
    VALUES (p_codigo, 0, 10);

    SELECT 'Material registrado correctamente' AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarOActualizarAcceso` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarOActualizarAcceso`(
    IN P_COD_ROL BIGINT,
    IN P_COD_OBJETO BIGINT,
    IN P_ESTADO_MODULO ENUM('1', '0'),
    IN P_ESTADO_SELECCION ENUM('1', '0'),
    IN P_ESTADO_INSERCION ENUM('1', '0'),
    IN P_ESTADO_ACTUALIZACION ENUM('1', '0'),
    IN P_ESTADO_ELIMINACION ENUM('1', '0'),
    IN P_USUARIO_CREA VARCHAR(255)
)
BEGIN
    INSERT INTO ACCESOS (
        COD_ROL, COD_OBJETO, ESTADO_MODULO, ESTADO_SELECCION, 
        ESTADO_INSERCION, ESTADO_ACTUALIZACION, ESTADO_ELIMINACION, 
        USUARIO_CREA, FECHA_CREA
    ) VALUES (
        P_COD_ROL, P_COD_OBJETO, P_ESTADO_MODULO, P_ESTADO_SELECCION, 
        P_ESTADO_INSERCION, P_ESTADO_ACTUALIZACION, P_ESTADO_ELIMINACION, 
        P_USUARIO_CREA, CURRENT_TIMESTAMP
    )
    ON DUPLICATE KEY UPDATE
        ESTADO_MODULO = P_ESTADO_MODULO,
        ESTADO_SELECCION = P_ESTADO_SELECCION,
        ESTADO_INSERCION = P_ESTADO_INSERCION,
        ESTADO_ACTUALIZACION = P_ESTADO_ACTUALIZACION,
        ESTADO_ELIMINACION = P_ESTADO_ELIMINACION,
        USUARIO_CREA = P_USUARIO_CREA,
        FECHA_CREA = CURRENT_TIMESTAMP;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarObjeto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarObjeto`(
    IN P_NOMBRE_OBJETO VARCHAR(255),
    IN P_TIPO_OBJETO VARCHAR(255),
    IN P_DESCRIPCION_OBJETO VARCHAR(255),
    IN P_USUARIO_CREA VARCHAR(255)
)
BEGIN
    INSERT INTO OBJETOS (NOMBRE_OBJETO, TIPO_OBJETO, DESCRIPCION_OBJETO, USUARIO_CREA) 
    VALUES (P_NOMBRE_OBJETO, P_TIPO_OBJETO, P_DESCRIPCION_OBJETO, P_USUARIO_CREA);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarProducto`(
    IN p_codigo VARCHAR(15),
    IN p_modelo VARCHAR(50),
    IN p_descripcion VARCHAR(200),
    IN p_cod_categoria INT,
    IN p_precio_venta DECIMAL(10,2),
    IN p_tiempo_garantia INT
)
BEGIN
    INSERT INTO PRODUCTOS(CODIGO, MODELO, DESCRIPCION, COD_CATEGORIA, TIEMPO_GARANTIA)
    VALUES(p_codigo, p_modelo, p_descripcion, p_cod_categoria, p_tiempo_garantia);
    
    SELECT LAST_INSERT_ID() AS COD_PRODUCTO;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarProductoInventario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarProductoInventario`(
    IN p_cod_producto INT,
    IN p_cod_sucursal INT,
    IN p_cantidad DECIMAL(10,2),
    IN p_stock_minimo DECIMAL(10,2),
    IN p_precio_venta DECIMAL(10,2)
)
BEGIN
    -- Verificar si el producto ya está en el inventario
    IF EXISTS (
        SELECT 1 FROM INVENTARIOPRODUCTOS 
        WHERE COD_PRODUCTO = p_cod_producto 
        AND COD_SUCURSAL = p_cod_sucursal
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El producto ya está registrado en esta sucursal';
    ELSE
        -- Insertar en el inventario si no existe
        INSERT INTO INVENTARIOPRODUCTOS (COD_PRODUCTO, COD_SUCURSAL, STOCK_ACTUAL, STOCK_MINIMO, PRECIO_VENTA)
        VALUES (p_cod_producto, p_cod_sucursal, p_cantidad, p_stock_minimo, p_precio_venta);
        
        SELECT 'Producto agregado al inventario correctamente' AS MENSAJE;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarProveedores` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarProveedores`(
    IN P_NOMBRE_PROVEEDOR VARCHAR(100),
    IN P_NOMBRE_CONTACTO VARCHAR(100),
    IN P_APELLIDO_CONTACTO VARCHAR(100),
    IN P_NUMERO_IDENTIDAD VARCHAR(16),
    IN P_RTN VARCHAR(16),
    IN P_CALLE VARCHAR(50),
    IN P_CIUDAD VARCHAR(50),
    IN P_PAIS VARCHAR(50),
    IN P_CODIGO_POSTAL VARCHAR(50),
    IN P_TELEFONO VARCHAR(15),
    IN P_CORREO VARCHAR(100)
)
BEGIN
    DECLARE V_PERSONA_ID INT;
    
    INSERT INTO PERSONAS (PRIMER_NOMBRE, PRIMER_APELLIDO, NUMERO_IDENTIDAD, RTN)
    VALUES (P_NOMBRE_CONTACTO, P_APELLIDO_CONTACTO, P_NUMERO_IDENTIDAD, P_RTN);
    
    SET V_PERSONA_ID = LAST_INSERT_ID();
    
    INSERT INTO PROVEEDORES (COD_PERSONA, NOMBRE_EMPRESA, NOMBRE_CONTACTO, APELLIDO_CONTACTO)
    VALUES (V_PERSONA_ID, P_NOMBRE_PROVEEDOR, P_NOMBRE_CONTACTO, P_APELLIDO_CONTACTO);
    
    INSERT INTO DIRECCIONES (COD_PERSONA, CALLE, CIUDAD, PAIS, CODIGO_POSTAL, TIPO)
    VALUES (V_PERSONA_ID, P_CALLE, P_CIUDAD, P_PAIS, P_CODIGO_POSTAL, 'LABORAL');
    
    INSERT INTO TELEFONOS (COD_PERSONA, NUMERO_TELEFONO, TIPO_TELEFONO)
    VALUES (V_PERSONA_ID, P_TELEFONO, 'LABORAL');
    
    INSERT INTO CORREOS (COD_PERSONA, CORREO_ELECTRONICO, TIPO)
    VALUES (V_PERSONA_ID, P_CORREO, 'LABORAL');
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarPuntoEmision` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarPuntoEmision`(
    IN p_codigo VARCHAR(3),
    IN p_nombre VARCHAR(50),
    IN p_establecimiento VARCHAR(3),
    -- IN p_direccion VARCHAR(200),
	-- IN p_telefono VARCHAR(15),
    IN p_cod_sucursal INT
)
BEGIN
    INSERT INTO PUNTOS_EMISION (
        CODIGO,
        NOMBRE,
        ESTABLECIMIENTO,
       -- DIRECCION,
       -- TELEFONO,
        COD_SUCURSAL
    ) VALUES (
        p_codigo,
        p_nombre,
        p_establecimiento,
     -- p_direccion,
	 -- p_telefono,
        p_cod_sucursal
    );
    
    SELECT LAST_INSERT_ID() AS COD_PUNTO_EMISION;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarRol` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarRol`(
    IN P_NOMBRE_ROL VARCHAR(255),
    IN P_DESCRIPCION_ROL VARCHAR(255),
    IN P_USUARIO_CREA VARCHAR(255)
)
BEGIN
    INSERT INTO ROLES (NOMBRE_ROL, DESCRIPCION_ROL, USUARIO_CREA) 
    VALUES (P_NOMBRE_ROL, P_DESCRIPCION_ROL, P_USUARIO_CREA);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarSucursales` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarSucursales`(
    IN p_nombre VARCHAR(50),
    IN p_direccion VARCHAR(100),
    IN p_cod_empleado_encargado INT
)
BEGIN
    INSERT INTO SUCURSALES(NOMBRE, DIRECCION, COD_EMPLEADO_ENCARGADO)
    VALUES(p_nombre, p_direccion, p_cod_empleado_encargado);
    SELECT LAST_INSERT_ID() AS COD_SUCURSAL;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarTipoDocumento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarTipoDocumento`(
    IN p_codigo VARCHAR(2),
    IN p_nombre VARCHAR(50),
    IN p_descripcion VARCHAR(200),
    IN p_afecta_inventario BOOLEAN,
    IN p_requiere_cliente BOOLEAN
)
BEGIN
    INSERT INTO TIPOS_DOCUMENTO (
        CODIGO,
        NOMBRE,
        DESCRIPCION,
        AFECTA_INVENTARIO,
        REQUIERE_CLIENTE
    ) VALUES (
        p_codigo,
        p_nombre,
        p_descripcion,
        p_afecta_inventario,
        p_requiere_cliente
    );
    
    SELECT LAST_INSERT_ID() AS COD_TIPO_DOCUMENTO;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_InsertarUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertarUsuario`(
    IN p_cod_empleado INT,
    IN p_cod_rol BIGINT,
    IN p_nombre_usuario VARCHAR(255),
    IN p_contrasena VARCHAR(2000),
    IN p_usuario_crea VARCHAR(255)
)
BEGIN
    INSERT INTO USUARIOS (
		COD_EMPLEADO, COD_ROL, NOMBRE_USUARIO, CONTRASENA, 
        ESTADO_USUARIO, PERMISO_INSERCION, USUARIO_CREA, FECHA_CREA
    ) VALUES (
         p_cod_empleado, p_cod_rol, p_nombre_usuario, p_contrasena, '1', '1', p_usuario_crea, NOW()
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarAccesosPorRol` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarAccesosPorRol`(
    IN P_COD_ROL BIGINT
)
BEGIN
    SELECT a.*, o.NOMBRE_OBJETO
    FROM ACCESOS a
    JOIN OBJETOS o ON a.COD_OBJETO = o.COD_OBJETO
    WHERE a.COD_ROL = P_COD_ROL;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarCAI` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarCAI`(
    IN p_estado ENUM('ACTIVO', 'VENCIDO', 'ANULADO')
)
BEGIN
    SELECT 
        C.COD_CAI,
        C.CODIGO_CAI,
        C.FECHA_EMISION,
        C.FECHA_VENCIMIENTO,
        TD.NOMBRE AS TIPO_DOCUMENTO,
        PE.NOMBRE AS PUNTO_EMISION,
        C.ESTABLECIMIENTO,
        C.PUNTO_EMISION_CODIGO,
        C.TIPO_DOCUMENTO_CODIGO,
        C.RANGO_INICIAL,
        C.RANGO_FINAL,
        C.RANGO_ACTUAL,
        C.ESTADO,
        CASE 
            WHEN CURDATE() > C.FECHA_VENCIMIENTO THEN 'VENCIDO'
            WHEN DATEDIFF(C.FECHA_VENCIMIENTO, CURDATE()) <= 30 THEN 'POR VENCER'
            ELSE 'VIGENTE'
        END AS ESTADO_VENCIMIENTO,
        DATEDIFF(C.FECHA_VENCIMIENTO, CURDATE()) AS DIAS_RESTANTES
    FROM CAI C
    JOIN TIPOS_DOCUMENTO TD ON C.COD_TIPO_DOCUMENTO = TD.COD_TIPO_DOCUMENTO
    JOIN PUNTOS_EMISION PE ON C.COD_PUNTO_EMISION = PE.COD_PUNTO_EMISION
    WHERE (p_estado IS NULL OR C.ESTADO = p_estado)
    ORDER BY C.FECHA_VENCIMIENTO;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarCategorias` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarCategorias`(
    IN p_cod_categoria INT,
    IN p_nombre_categoria VARCHAR(50)
)
BEGIN
    IF p_cod_categoria IS NOT NULL AND p_nombre_categoria IS NOT NULL THEN
        -- Filtrar por ambos: código y nombre
        SELECT COD_CATEGORIA, NOMBRE, DESCRIPCION
        FROM CATEGORIAS
        WHERE COD_CATEGORIA = p_cod_categoria
        AND NOMBRE LIKE CONCAT('%', p_nombre_categoria, '%');
    ELSEIF p_cod_categoria IS NOT NULL THEN
        -- Filtrar solo por código
        SELECT COD_CATEGORIA, NOMBRE, DESCRIPCION
        FROM CATEGORIAS
        WHERE COD_CATEGORIA = p_cod_categoria;
    ELSEIF p_nombre_categoria IS NOT NULL THEN
        -- Filtrar solo por nombre
        SELECT COD_CATEGORIA, NOMBRE, DESCRIPCION
        FROM CATEGORIAS
        WHERE NOMBRE LIKE CONCAT('%', p_nombre_categoria, '%');
    ELSE
        -- Listar todo si ambos parámetros son NULL
        SELECT COD_CATEGORIA, NOMBRE, DESCRIPCION
        FROM CATEGORIAS;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarClientes` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarClientes`(
    IN p_nombre VARCHAR(100),
    IN p_numero_identidad VARCHAR(15),
    IN p_cod_cliente INT
)
BEGIN
    SELECT 
        C.COD_CLIENTE,
        CONCAT(P.PRIMER_NOMBRE, ' ', P.SEGUNDO_NOMBRE, ' ', P.PRIMER_APELLIDO, ' ', P.SEGUNDO_APELLIDO) AS NOMBRE_COMPLETO,
        P.PRIMER_NOMBRE AS PRIMER_NOMBRE_C,
        P.SEGUNDO_NOMBRE AS SEGUNDO_NOMBRE_C,
        P.PRIMER_APELLIDO AS PRIMER_APELLIDO_C,
        P.SEGUNDO_APELLIDO AS SEGUNDO_APELLIDO_C,
        P.NUMERO_IDENTIDAD,
        P.RTN,
        T.NUMERO_TELEFONO,
        T.TIPO_TELEFONO,
        CO.CORREO_ELECTRONICO,
        CO.TIPO AS TIPO_CORREO,
        D.CALLE,
        D.CIUDAD,
        D.PAIS,
        D.CODIGO_POSTAL,
        D.TIPO AS TIPO_DIRECCION
    FROM CLIENTES C
    JOIN PERSONAS P ON C.COD_PERSONA = P.COD_PERSONA
    LEFT JOIN TELEFONOS T ON C.COD_PERSONA = T.COD_PERSONA
    LEFT JOIN CORREOS CO ON C.COD_PERSONA = CO.COD_PERSONA
    LEFT JOIN DIRECCIONES D ON C.COD_PERSONA = D.COD_PERSONA
    WHERE 
        (p_nombre IS NULL OR 
         CONCAT(P.PRIMER_NOMBRE, ' ', P.SEGUNDO_NOMBRE, ' ', P.PRIMER_APELLIDO, ' ', P.SEGUNDO_APELLIDO) LIKE CONCAT('%', p_nombre, '%'))
        AND
        (p_numero_identidad IS NULL OR P.NUMERO_IDENTIDAD LIKE CONCAT('%', p_numero_identidad, '%'))
        AND
        (p_cod_cliente IS NULL OR C.COD_CLIENTE = p_cod_cliente);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarDetalleCompra` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarDetalleCompra`(   
 IN p_numero_factura VARCHAR(20),   
    IN p_nombre_empresa VARCHAR(100))
BEGIN    
SELECT 
        F.NUMERO_FACTURA,         
        P.NOMBRE_EMPRESA, 
        F.FECHA,         
        D.MATERIAL_CODIGO, 
        D.CANTIDAD,        
        D.PRECIO, 
        D.SUBTOTAL,        
        F.IMPUESTO, 
        F.DESCUENTO,        
        F.TOTAL
    FROM DETALLECOMPRA D    
    JOIN FACTURASCOMPRA F ON D.CODIGO_FACTURA = F.COD_FACTURA
    JOIN PROVEEDORES P ON F.COD_PROVEEDORES = P.COD_PROVEEDORES    
    WHERE 
        (p_numero_factura IS NULL OR p_numero_factura = '' OR F.NUMERO_FACTURA = p_numero_factura)    AND 
        (p_nombre_empresa IS NULL OR p_nombre_empresa = '' OR P.NOMBRE_EMPRESA LIKE CONCAT('%', p_nombre_empresa, '%'))    ORDER BY F.NUMERO_FACTURA;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarEmpleados` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarEmpleados`(
    IN p_cod_empleado INT,
    IN p_nombre VARCHAR(100),
    IN p_numero_identidad VARCHAR(15),  
    IN p_puesto VARCHAR(50)
)
BEGIN
    -- Si p_cod_empleado no es NULL, buscar por COD_EMPLEADO
    IF p_cod_empleado IS NOT NULL THEN
        SELECT        
            EMPLEADOS.COD_EMPLEADO,
            PRIMER_NOMBRE,  -- Nuevo: devolver campo separado
            SEGUNDO_NOMBRE, -- Nuevo: devolver campo separado
            PRIMER_APELLIDO, -- Nuevo: devolver campo separado
            SEGUNDO_APELLIDO, -- Nuevo: devolver campo separado
            CONCAT(PRIMER_NOMBRE, ' ', SEGUNDO_NOMBRE, ' ', PRIMER_APELLIDO, ' ', SEGUNDO_APELLIDO) AS NOMBRE_COMPLETO,        
            NUMERO_IDENTIDAD,
            RTN,        
            PUESTO,
            TELEFONOS.NUMERO_TELEFONO,        
            TELEFONOS.TIPO_TELEFONO,
            CORREOS.CORREO_ELECTRONICO,        
            CORREOS.TIPO AS TIPO_CORREO,
            DIRECCIONES.CALLE,        
            DIRECCIONES.CIUDAD,
            DIRECCIONES.PAIS,        
            DIRECCIONES.CODIGO_POSTAL,
            DIRECCIONES.TIPO AS TIPO_DIRECCION   
        FROM PERSONAS
        JOIN EMPLEADOS ON PERSONAS.COD_PERSONA = EMPLEADOS.COD_PERSONA
        LEFT JOIN TELEFONOS ON PERSONAS.COD_PERSONA = TELEFONOS.COD_PERSONA
        LEFT JOIN CORREOS ON PERSONAS.COD_PERSONA = CORREOS.COD_PERSONA
        LEFT JOIN DIRECCIONES ON PERSONAS.COD_PERSONA = DIRECCIONES.COD_PERSONA
        WHERE EMPLEADOS.COD_EMPLEADO = p_cod_empleado;
    ELSE
        -- Si p_cod_empleado es NULL, aplicar los filtros originales
        SELECT        
            EMPLEADOS.COD_EMPLEADO,
            PRIMER_NOMBRE,  -- Nuevo: devolver campo separado
            SEGUNDO_NOMBRE, -- Nuevo: devolver campo separado
            PRIMER_APELLIDO, -- Nuevo: devolver campo separado
            SEGUNDO_APELLIDO, -- Nuevo: devolver campo separado
            CONCAT(PRIMER_NOMBRE, ' ', SEGUNDO_NOMBRE, ' ', PRIMER_APELLIDO, ' ', SEGUNDO_APELLIDO) AS NOMBRE_COMPLETO,        
            NUMERO_IDENTIDAD,
            RTN,        
            PUESTO,
            TELEFONOS.NUMERO_TELEFONO,        
            TELEFONOS.TIPO_TELEFONO,
            CORREOS.CORREO_ELECTRONICO,        
            CORREOS.TIPO AS TIPO_CORREO,
            DIRECCIONES.CALLE,        
            DIRECCIONES.CIUDAD,
            DIRECCIONES.PAIS,        
            DIRECCIONES.CODIGO_POSTAL,
            DIRECCIONES.TIPO AS TIPO_DIRECCION   
        FROM PERSONAS
        JOIN EMPLEADOS ON PERSONAS.COD_PERSONA = EMPLEADOS.COD_PERSONA
        LEFT JOIN TELEFONOS ON PERSONAS.COD_PERSONA = TELEFONOS.COD_PERSONA
        LEFT JOIN CORREOS ON PERSONAS.COD_PERSONA = CORREOS.COD_PERSONA
        LEFT JOIN DIRECCIONES ON PERSONAS.COD_PERSONA = DIRECCIONES.COD_PERSONA
        WHERE (p_nombre IS NULL OR 
               CONCAT(PRIMER_NOMBRE, ' ', SEGUNDO_NOMBRE, ' ', PRIMER_APELLIDO, ' ', SEGUNDO_APELLIDO) LIKE CONCAT('%', p_nombre, '%'))
          AND (p_numero_identidad IS NULL OR NUMERO_IDENTIDAD LIKE CONCAT('%', p_numero_identidad, '%'))
          AND (p_puesto IS NULL OR PUESTO LIKE CONCAT('%', p_puesto, '%'));
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarFacturas` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarFacturas`(
IN p_proveedor VARCHAR(100), 
IN p_numero_factura VARCHAR(50))
BEGIN
    SELECT         
    f.NUMERO_FACTURA,
        pr.NOMBRE_EMPRESA,       
        f.FECHA,
        f.TOTAL    
        FROM 
        FACTURASCOMPRA f    
        JOIN 
        PROVEEDORES pr ON f.COD_PROVEEDORES = pr.COD_PROVEEDORES   
        WHERE 
        (pr.NOMBRE_EMPRESA LIKE CONCAT('%', p_proveedor, '%') OR p_proveedor IS NULL)    AND 
        (f.NUMERO_FACTURA LIKE CONCAT('%', p_numero_factura, '%') OR p_numero_factura IS NULL);
        
        END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarInventario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarInventario`(   
 IN p_material_codigo VARCHAR(20),    -- Parámetro de entrada para el código del material
    IN p_nombre_material VARCHAR(100),   -- Parámetro de entrada para el nombre del material    
    IN p_stock_minimo BOOLEAN )
BEGIN
    SELECT         
    IM.MATERIAL_CODIGO,
        M.MATERIAL AS NOMBRE_MATERIAL,        
        IM.STOCK_ACTUAL,
        IM.STOCK_MINIMO    
        FROM 
        INVENTARIOMATERIALES IM    
        JOIN 
        MATERIALES M ON IM.MATERIAL_CODIGO = M.CODIGO   
        WHERE
        (p_material_codigo IS NULL OR p_material_codigo = '' OR IM.MATERIAL_CODIGO = p_material_codigo)    AND 
        (p_nombre_material IS NULL OR p_nombre_material = '' OR M.MATERIAL LIKE CONCAT('%', p_nombre_material, '%'))    AND 
        (p_stock_minimo IS NULL OR p_stock_minimo = FALSE OR IM.STOCK_ACTUAL < IM.STOCK_MINIMO)    ORDER BY 
        IM.MATERIAL_CODIGO;
        END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarInventarios` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarInventarios`(
    IN p_nombre_sucursal VARCHAR(255)
)
BEGIN
    IF p_nombre_sucursal IS NOT NULL THEN
        SELECT 
            p.CODIGO AS CODIGO_PRODUCTO,
            p.MODELO AS NOMBRE_PRODUCTO,
            p.DESCRIPCION,
            c.NOMBRE AS CATEGORIA,
            s.NOMBRE AS NOMBRE_SUCURSAL, -- Cambiado de s.NOMBRE_SUCURSAL a s.NOMBRE
            s.COD_SUCURSAL, -- Incluimos el ID de la sucursal
            ip.STOCK_ACTUAL,
            ip.STOCK_MINIMO,
            ip.PRECIO_VENTA
        FROM INVENTARIOPRODUCTOS ip
        INNER JOIN PRODUCTOS p ON ip.COD_PRODUCTO = p.COD_PRODUCTO
        INNER JOIN SUCURSALES s ON ip.COD_SUCURSAL = s.COD_SUCURSAL
        INNER JOIN CATEGORIAS c ON p.COD_CATEGORIA = c.COD_CATEGORIA
        WHERE s.NOMBRE = p_nombre_sucursal; -- Ajustado a s.NOMBRE
    ELSE
        SELECT 
            p.CODIGO AS CODIGO_PRODUCTO,
            p.MODELO AS NOMBRE_PRODUCTO,
            p.DESCRIPCION,
            c.NOMBRE AS CATEGORIA,
            s.NOMBRE AS NOMBRE_SUCURSAL, -- Cambiado de s.NOMBRE_SUCURSAL a s.NOMBRE
            s.COD_SUCURSAL, -- Incluimos el ID de la sucursal
            ip.STOCK_ACTUAL,
            ip.STOCK_MINIMO,
            ip.PRECIO_VENTA
        FROM INVENTARIOPRODUCTOS ip
        INNER JOIN PRODUCTOS p ON ip.COD_PRODUCTO = p.COD_PRODUCTO
        INNER JOIN SUCURSALES s ON ip.COD_SUCURSAL = s.COD_SUCURSAL
        INNER JOIN CATEGORIAS c ON p.COD_CATEGORIA = c.COD_CATEGORIA;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarMateriales` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarMateriales`(
IN p_codigo VARCHAR(50), 
IN p_material VARCHAR(100))
BEGIN
    SELECT CODIGO, MATERIAL    
    FROM MATERIALES
    WHERE (CODIGO LIKE CONCAT('%', p_codigo, '%') OR p_codigo IS NULL)    
    AND (MATERIAL LIKE CONCAT('%', p_material, '%') OR p_material IS NULL);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarObjetos` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarObjetos`()
BEGIN
    SELECT * FROM OBJETOS;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarProductos` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarProductos`(
    IN p_codigo VARCHAR(15)
)
BEGIN
    IF p_codigo IS NOT NULL THEN
        -- Filtrar por código de producto y unir con CATEGORIAS
        SELECT 
            p.COD_PRODUCTO, 
            p.CODIGO, 
            p.MODELO, 
            p.DESCRIPCION, 
            c.NOMBRE AS NOMBRE_CATEGORIA, 
            p.TIEMPO_GARANTIA
        FROM PRODUCTOS p
        LEFT JOIN CATEGORIAS c ON p.COD_CATEGORIA = c.COD_CATEGORIA
        WHERE p.CODIGO = p_codigo;
    ELSE
        -- Listar todos los productos y unir con CATEGORIAS
        SELECT 
            p.COD_PRODUCTO, 
            p.CODIGO, 
            p.MODELO, 
            p.DESCRIPCION, 
            c.NOMBRE AS NOMBRE_CATEGORIA, 
            p.TIEMPO_GARANTIA
        FROM PRODUCTOS p
        LEFT JOIN CATEGORIAS c ON p.COD_CATEGORIA = c.COD_CATEGORIA;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarProveedores` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarProveedores`(    
    IN p_cod_proveedor INT,
    IN p_nombre_empresa VARCHAR(100),
    IN p_nombre_contacto VARCHAR(100))
BEGIN    
    SELECT 
        P.COD_PROVEEDORES,       
        P.NOMBRE_EMPRESA,
        CONCAT(
            IFNULL(PE.PRIMER_NOMBRE, ''), ' ',
            IFNULL(PE.SEGUNDO_NOMBRE, '')
        ) AS NOMBRE_CONTACTO,  -- Solo nombres
        CONCAT(
            IFNULL(PE.PRIMER_APELLIDO, ''), ' ',
            IFNULL(PE.SEGUNDO_APELLIDO, '')
        ) AS APELLIDO_CONTACTO, -- Solo apellidos
        PE.NUMERO_IDENTIDAD,      
        PE.RTN,
        D.CALLE,       
        D.CIUDAD,
        D.PAIS,        
        D.CODIGO_POSTAL,
        T.NUMERO_TELEFONO,        
        C.CORREO_ELECTRONICO
    FROM PROVEEDORES P   
    INNER JOIN PERSONAS PE ON P.COD_PERSONA = PE.COD_PERSONA
    LEFT JOIN DIRECCIONES D ON PE.COD_PERSONA = D.COD_PERSONA AND D.TIPO = 'LABORAL'   
    LEFT JOIN TELEFONOS T ON PE.COD_PERSONA = T.COD_PERSONA AND T.TIPO_TELEFONO = 'LABORAL'
    LEFT JOIN CORREOS C ON PE.COD_PERSONA = C.COD_PERSONA AND C.TIPO = 'LABORAL'   
    WHERE 
        (p_cod_proveedor IS NULL OR P.COD_PROVEEDORES = p_cod_proveedor) AND
        (p_nombre_empresa IS NULL OR P.NOMBRE_EMPRESA LIKE CONCAT('%', p_nombre_empresa, '%')) AND
        (p_nombre_contacto IS NULL OR         
        CONCAT(
            IFNULL(PE.PRIMER_NOMBRE, ''), ' ',
            IFNULL(PE.SEGUNDO_NOMBRE, '')
        ) LIKE CONCAT('%', p_nombre_contacto, '%'));
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarPuntosEmision` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarPuntosEmision`(
    IN p_estado ENUM('ACTIVO', 'INACTIVO')
)
BEGIN
    SELECT 
        PE.COD_PUNTO_EMISION,
        PE.CODIGO,
        PE.NOMBRE,
        PE.ESTABLECIMIENTO,
       -- PE.DIRECCION,
       -- PE.TELEFONO,
        PE.ESTADO,
        S.NOMBRE AS NOMBRE_SUCURSAL
    FROM PUNTOS_EMISION PE
    LEFT JOIN SUCURSALES S ON PE.COD_SUCURSAL = S.COD_SUCURSAL
    WHERE (p_estado IS NULL OR PE.ESTADO = p_estado)
    ORDER BY PE.CODIGO;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarRoles` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarRoles`(
    IN p_nombre_rol VARCHAR(50)
)
BEGIN
    SELECT *
    FROM ROLES
    WHERE 
        p_nombre_rol IS NULL
        OR NOMBRE_ROL LIKE CONCAT('%', p_nombre_rol, '%');
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarSucursales` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarSucursales`(
    IN p_cod_sucursal INT,
    IN p_nombre_sucursal VARCHAR(100),
    IN p_nombre_empleado VARCHAR(100)
)
BEGIN
    SELECT 
        s.COD_SUCURSAL,
        s.NOMBRE AS NOMBRE_SUCURSAL,
        s.DIRECCION,
        CONCAT(p.PRIMER_NOMBRE, ' ', p.PRIMER_APELLIDO) AS NOMBRE_ENCARGADO,
        t.NUMERO_TELEFONO AS TELEFONO_ENCARGADO,
        s.COD_EMPLEADO_ENCARGADO AS COD_EMPLEADO,
        es.ESTADO  -- Nueva columna
    FROM SUCURSALES s
    LEFT JOIN EMPLEADOS e ON s.COD_EMPLEADO_ENCARGADO = e.COD_EMPLEADO
    LEFT JOIN PERSONAS p ON e.COD_PERSONA = p.COD_PERSONA
    LEFT JOIN TELEFONOS t ON p.COD_PERSONA = t.COD_PERSONA AND t.TIPO_TELEFONO = 'PERSONAL'
    LEFT JOIN estado_sucursales es ON s.COD_SUCURSAL = es.COD_SUCURSAL
    WHERE (p_cod_sucursal IS NULL OR s.COD_SUCURSAL = p_cod_sucursal)
      AND (p_nombre_sucursal IS NULL OR s.NOMBRE LIKE CONCAT('%', p_nombre_sucursal, '%'))
      AND (p_nombre_empleado IS NULL OR CONCAT(p.PRIMER_NOMBRE, ' ', p.PRIMER_APELLIDO) LIKE CONCAT('%', p_nombre_empleado, '%'))
    ORDER BY s.NOMBRE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarTiposDocumento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarTiposDocumento`(
    IN p_estado ENUM('ACTIVO', 'INACTIVO')
)
BEGIN
    SELECT 
        COD_TIPO_DOCUMENTO,
        CODIGO,
        NOMBRE,
        DESCRIPCION,
        AFECTA_INVENTARIO,
        REQUIERE_CLIENTE,
        ESTADO
    FROM TIPOS_DOCUMENTO
    WHERE (p_estado IS NULL OR ESTADO = p_estado)
    ORDER BY CODIGO;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarTraslados` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarTraslados`(
    IN p_COD_TRASLADO INT
)
BEGIN
    SELECT 
        T.COD_TRASLADO,
        SO.NOMBRE AS SUCURSAL_ORIGEN,
        SD.NOMBRE AS SUCURSAL_DESTINO,
        DT.COD_PRODUCTO,
        P.MODELO AS NOMBRE_PRODUCTO, -- Cambiado de NOMBRE_PRODUCTO a MODELO
        DT.CANTIDAD,
        T.FECHA_TRASLADO,
        T.ESTADO,
        T.NOTAS,
        U.NOMBRE_USUARIO AS USUARIO,
        CONCAT(E.PRIMER_NOMBRE_E, ' ', E.PRIMER_APELLIDO_E) AS EMPLEADO
    FROM TRASLADOS T
    INNER JOIN SUCURSALES SO ON T.SUCURSAL_ORIGEN = SO.COD_SUCURSAL
    INNER JOIN SUCURSALES SD ON T.SUCURSAL_DESTINO = SD.COD_SUCURSAL
    INNER JOIN DETALLETRASLADOS DT ON T.COD_TRASLADO = DT.COD_TRASLADO
    INNER JOIN PRODUCTOS P ON DT.COD_PRODUCTO = P.COD_PRODUCTO
    INNER JOIN USUARIOS U ON T.COD_USUARIO = U.COD_USUARIO
    INNER JOIN EMPLEADOS E ON U.COD_EMPLEADO = E.COD_EMPLEADO
    WHERE p_COD_TRASLADO IS NULL OR T.COD_TRASLADO = p_COD_TRASLADO;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ListarUsuarios` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ListarUsuarios`(
    IN p_cod_usuario VARCHAR(10),
    IN p_cod_empleado VARCHAR(10),
    IN p_cod_rol VARCHAR(10)
)
BEGIN
    SELECT 
        u.COD_USUARIO,
        CONCAT(
            COALESCE(e.PRIMER_NOMBRE_E, ''), ' ',
            COALESCE(e.SEGUNDO_NOMBRE_E, ''), ' ',
            COALESCE(e.PRIMER_APELLIDO_E, ''), ' ',
            COALESCE(e.SEGUNDO_APELLIDO_E, '')
        ) AS NOMBRE_EMPLEADO,
        r.NOMBRE_ROL,
        u.NOMBRE_USUARIO,
        c.CORREO_ELECTRONICO,
        c.TIPO AS TIPO_CORREO,  -- Renombramos TIPO a TIPO_CORREO para consistencia
        u.ESTADO_USUARIO
    FROM USUARIOS u
    JOIN EMPLEADOS e ON u.COD_EMPLEADO = e.COD_EMPLEADO
    JOIN PERSONAS p ON e.COD_PERSONA = p.COD_PERSONA
    LEFT JOIN CORREOS c ON p.COD_PERSONA = c.COD_PERSONA  -- LEFT JOIN por si no hay correo
    JOIN ROLES r ON u.COD_ROL = r.COD_ROL
    WHERE (p_cod_usuario IS NULL OR u.COD_USUARIO = p_cod_usuario)
      AND (p_cod_empleado IS NULL OR u.COD_EMPLEADO = p_cod_empleado)
      AND (p_cod_rol IS NULL OR u.COD_ROL = p_cod_rol);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ObtenerDatosEmpresa` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerDatosEmpresa`()
BEGIN
    SELECT * FROM EMPRESA LIMIT 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ObtenerDatosImpresionFactura` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerDatosImpresionFactura`(
    IN p_cod_factura INT
)
BEGIN
    -- Datos de la empresa
    SELECT 'DATOS_EMPRESA' AS SECCION;
    SELECT 
        E.RAZON_SOCIAL,
        E.NOMBRE_COMERCIAL,
        E.RTN,
        E.DIRECCION,
        E.CIUDAD,
        E.DEPARTAMENTO,
        E.TELEFONO,
        E.EMAIL,
        E.SITIO_WEB,
        E.REGIMEN_FISCAL
    FROM EMPRESA E
    LIMIT 1;
    
    -- Datos de la factura
    SELECT 'DATOS_FACTURA' AS SECCION;
    SELECT 
        F.NUMERO_FACTURA,
        F.NUMERO_FISCAL,
        F.CAI,
        DATE_FORMAT(F.FECHA, '%d/%m/%Y %H:%i:%s') AS FECHA,
        S.NOMBRE AS SUCURSAL,
        S.DIRECCION AS DIRECCION_SUCURSAL,
        F.SUBTOTAL,
        F.IMPUESTO,
        CONCAT(F.IMPUESTO * 100, '%') AS PORCENTAJE_IMPUESTO,
        F.DESCUENTO,
        CONCAT(F.DESCUENTO * 100, '%') AS PORCENTAJE_DESCUENTO,
        F.TOTAL,
        F.METODO_PAGO,
        F.ESTADO
    FROM FACTURASVENTA F
    JOIN SUCURSALES S ON F.COD_SUCURSAL = S.COD_SUCURSAL
    WHERE F.COD_FACTURA = p_cod_factura;
    
    -- Datos del cliente
    SELECT 'DATOS_CLIENTE' AS SECCION;
    SELECT 
        CASE 
            WHEN F.COD_CLIENTE IS NOT NULL THEN CONCAT(P.PRIMER_NOMBRE, ' ', P.PRIMER_APELLIDO)
            ELSE 'CONSUMIDOR FINAL'
        END AS NOMBRE_CLIENTE,
        CASE 
            WHEN F.COD_CLIENTE IS NOT NULL THEN P.RTN
            ELSE 'CF'
        END AS RTN_CLIENTE
    FROM FACTURASVENTA F
    LEFT JOIN CLIENTES C ON F.COD_CLIENTE = C.COD_CLIENTE
    LEFT JOIN PERSONAS P ON C.COD_PERSONA = P.COD_PERSONA
    WHERE F.COD_FACTURA = p_cod_factura;
    
    -- Datos del CAI
    SELECT 'DATOS_CAI' AS SECCION;
    SELECT 
        C.CODIGO_CAI,
        DATE_FORMAT(C.FECHA_EMISION, '%d/%m/%Y') AS FECHA_EMISION,
        DATE_FORMAT(C.FECHA_VENCIMIENTO, '%d/%m/%Y') AS FECHA_VENCIMIENTO,
        CONCAT(C.ESTABLECIMIENTO, '-', C.PUNTO_EMISION_CODIGO, '-', C.TIPO_DOCUMENTO_CODIGO) AS PREFIJO,
        C.RANGO_INICIAL,
        C.RANGO_FINAL
    FROM CAI C
    JOIN FACTURASVENTA F ON F.CAI = C.CODIGO_CAI
    WHERE F.COD_FACTURA = p_cod_factura
    AND CONCAT(C.ESTABLECIMIENTO, C.PUNTO_EMISION_CODIGO, C.TIPO_DOCUMENTO_CODIGO) = 
        LEFT(F.NUMERO_FISCAL, LENGTH(CONCAT(C.ESTABLECIMIENTO, C.PUNTO_EMISION_CODIGO, C.TIPO_DOCUMENTO_CODIGO)))
    LIMIT 1;
    
    -- Detalle de productos
    SELECT 'DETALLE_PRODUCTOS' AS SECCION;
    SELECT 
        P.CODIGO,
        P.MODELO,
        P.DESCRIPCION,
        DV.CANTIDAD,
        DV.PRECIO AS PRECIO_UNITARIO,
        DV.SUBTOTAL
    FROM DETALLEVENTA DV
    JOIN PRODUCTOS P ON DV.COD_PRODUCTO = P.COD_PRODUCTO
    WHERE DV.COD_FACTURA = p_cod_factura
    ORDER BY P.CODIGO;
    
    -- Totales
    SELECT 'TOTALES' AS SECCION;
    SELECT 
        F.SUBTOTAL,
        F.SUBTOTAL * F.DESCUENTO AS MONTO_DESCUENTO,
        (F.SUBTOTAL - (F.SUBTOTAL * F.DESCUENTO)) AS SUBTOTAL_NETO,
        (F.SUBTOTAL - (F.SUBTOTAL * F.DESCUENTO)) * 
        (CASE 
            WHEN F.IMPUESTO > 1 THEN F.IMPUESTO / 100  -- Para facturas existentes con IMPUESTO como porcentaje (15)
            ELSE F.IMPUESTO                            -- Para nuevas facturas con IMPUESTO como decimal (0.15)
        END) AS MONTO_IMPUESTO,
        F.TOTAL,
        CASE 
            WHEN F.METODO_PAGO = 'EFECTIVO' THEN 'EFECTIVO'
            WHEN F.METODO_PAGO = 'TARJETA' THEN 'TARJETA DE CRÉDITO/DÉBITO'
            WHEN F.METODO_PAGO = 'TRANSFERENCIA' THEN 'TRANSFERENCIA BANCARIA'
            ELSE F.METODO_PAGO
        END AS FORMA_PAGO
    FROM FACTURASVENTA F
    WHERE F.COD_FACTURA = p_cod_factura;
    
    -- Leyendas fiscales
    SELECT 'LEYENDAS_FISCALES' AS SECCION;
    SELECT 
        'LA FACTURA ES BENEFICIO DE TODOS, ¡EXÍJALA!' AS LEYENDA1,
        CONCAT('ORIGINAL: CLIENTE - COPIA: EMISOR - FECHA LÍMITE DE EMISIÓN: ', 
               DATE_FORMAT(C.FECHA_VENCIMIENTO, '%d/%m/%Y')) AS LEYENDA2,
        'ES CONTRIBUYENTE RESPONSABLE, RESOLUCIÓN XXXXXXXX' AS LEYENDA3
    FROM CAI C
    JOIN FACTURASVENTA F ON F.CAI = C.CODIGO_CAI
    WHERE F.COD_FACTURA = p_cod_factura
    AND CONCAT(C.ESTABLECIMIENTO, C.PUNTO_EMISION_CODIGO, C.TIPO_DOCUMENTO_CODIGO) = 
        LEFT(F.NUMERO_FISCAL, LENGTH(CONCAT(C.ESTABLECIMIENTO, C.PUNTO_EMISION_CODIGO, C.TIPO_DOCUMENTO_CODIGO)))
    LIMIT 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ObtenerSiguienteNumeroDocumento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerSiguienteNumeroDocumento`(
    IN p_cod_tipo_documento INT,
    IN p_cod_punto_emision INT,
    OUT p_numero_documento VARCHAR(50),
    OUT p_cai VARCHAR(37)
)
BEGIN
    DECLARE v_cod_cai INT;
    DECLARE v_prefijo VARCHAR(15);
    DECLARE v_ultimo_numero INT;
    DECLARE v_siguiente_numero INT;
    DECLARE v_rango_final INT;
    DECLARE v_numero_formateado VARCHAR(8);
    
    -- Obtener el CAI activo para el tipo de documento y punto de emisión
    SELECT 
        C.COD_CAI, 
        C.CODIGO_CAI,
        CAST(C.RANGO_FINAL AS UNSIGNED)
    INTO 
        v_cod_cai, 
        p_cai,
        v_rango_final
    FROM CAI C
    WHERE C.COD_TIPO_DOCUMENTO = p_cod_tipo_documento
    AND C.COD_PUNTO_EMISION = p_cod_punto_emision
    AND C.ESTADO = 'ACTIVO'
    AND CURDATE() BETWEEN C.FECHA_EMISION AND C.FECHA_VENCIMIENTO
    LIMIT 1;
    
    IF v_cod_cai IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No existe un CAI activo para este tipo de documento y punto de emisión';
    END IF;
    
    -- Obtener el correlativo
    SELECT 
        PREFIJO,
        ULTIMO_NUMERO
    INTO 
        v_prefijo,
        v_ultimo_numero
    FROM CORRELATIVOS
    WHERE COD_CAI = v_cod_cai;
    
    -- Calcular el siguiente número
    SET v_siguiente_numero = v_ultimo_numero + 1;
    
    -- Verificar que no exceda el rango final
    IF v_siguiente_numero > v_rango_final THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Se ha alcanzado el límite del rango autorizado por el CAI';
    END IF;
    
    -- Formatear el número con ceros a la izquierda
    SET v_numero_formateado = LPAD(v_siguiente_numero, 8, '0');
    
    -- Actualizar el correlativo
    UPDATE CORRELATIVOS
    SET 
        ULTIMO_NUMERO = v_siguiente_numero,
        FECHA_ULTIMO_USO = NOW()
    WHERE COD_CAI = v_cod_cai;
    
    -- Actualizar el rango actual en el CAI
    UPDATE CAI
    SET RANGO_ACTUAL = v_numero_formateado
    WHERE COD_CAI = v_cod_cai;
    
    -- Devolver el número de documento completo
    SET p_numero_documento = CONCAT(v_prefijo, '-', v_numero_formateado);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_RealizarTraslado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_RealizarTraslado`(
    IN p_COD_SUCURSAL_ORIGEN INT,
    IN p_COD_SUCURSAL_DESTINO INT,
    IN p_CODIGO_PRODUCTO INT,
    IN p_CANTIDAD DECIMAL(10,2),
    IN p_COD_USUARIO BIGINT,
    IN p_FECHA_TRASLADO DATE,
    IN p_NOTAS VARCHAR(255),
    OUT MENSAJE VARCHAR(255)
)
BEGIN
    DECLARE v_COD_TRASLADO INT;
    DECLARE v_STOCK_ACTUAL DECIMAL(10,2);

    -- Manejo de excepciones
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        SET MENSAJE = 'Error al realizar el traslado';
        ROLLBACK;
    END;

    -- Iniciar transacción
    START TRANSACTION;

    -- Verificar que las sucursales no sean iguales
    IF p_COD_SUCURSAL_ORIGEN = p_COD_SUCURSAL_DESTINO THEN
        SET MENSAJE = 'Las sucursales de origen y destino no pueden ser iguales';
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = MENSAJE;
    END IF;

    -- Verificar que el producto exista
    IF NOT EXISTS (SELECT 1 FROM PRODUCTOS WHERE COD_PRODUCTO = p_CODIGO_PRODUCTO) THEN
        SET MENSAJE = 'El producto no existe';
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = MENSAJE;
    END IF;

    -- Verificar que las sucursales existan
    IF NOT EXISTS (SELECT 1 FROM SUCURSALES WHERE COD_SUCURSAL = p_COD_SUCURSAL_ORIGEN) THEN
        SET MENSAJE = 'La sucursal de origen no existe';
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = MENSAJE;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM SUCURSALES WHERE COD_SUCURSAL = p_COD_SUCURSAL_DESTINO) THEN
        SET MENSAJE = 'La sucursal de destino no existe';
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = MENSAJE;
    END IF;

    -- Verificar que el usuario exista
    IF NOT EXISTS (SELECT 1 FROM USUARIOS WHERE COD_USUARIO = p_COD_USUARIO) THEN
        SET MENSAJE = 'El usuario no existe';
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = MENSAJE;
    END IF;

    -- Verificar stock en origen
    SELECT STOCK_ACTUAL INTO v_STOCK_ACTUAL 
    FROM INVENTARIOPRODUCTOS 
    WHERE COD_PRODUCTO = p_CODIGO_PRODUCTO AND COD_SUCURSAL = p_COD_SUCURSAL_ORIGEN;

    IF v_STOCK_ACTUAL IS NULL OR v_STOCK_ACTUAL < p_CANTIDAD THEN
        SET MENSAJE = 'Stock insuficiente en la sucursal de origen';
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = MENSAJE;
    END IF;

    -- Insertar el traslado
    INSERT INTO TRASLADOS (
        SUCURSAL_ORIGEN, 
        SUCURSAL_DESTINO, 
        FECHA_TRASLADO, 
        ESTADO, 
        NOTAS, 
        COD_USUARIO
    ) VALUES (
        p_COD_SUCURSAL_ORIGEN, 
        p_COD_SUCURSAL_DESTINO, 
        p_FECHA_TRASLADO, 
        'PENDIENTE', 
        p_NOTAS, 
        p_COD_USUARIO
    );

    -- Obtener el COD_TRASLADO recién insertado
    SET v_COD_TRASLADO = LAST_INSERT_ID();

    -- Insertar el detalle del traslado
    INSERT INTO DETALLETRASLADOS (
        COD_TRASLADO, 
        COD_PRODUCTO, 
        CANTIDAD
    ) VALUES (
        v_COD_TRASLADO, 
        p_CODIGO_PRODUCTO, 
        p_CANTIDAD
    );

    -- Actualizar inventario en origen
    UPDATE INVENTARIOPRODUCTOS 
    SET STOCK_ACTUAL = STOCK_ACTUAL - p_CANTIDAD 
    WHERE COD_PRODUCTO = p_CODIGO_PRODUCTO AND COD_SUCURSAL = p_COD_SUCURSAL_ORIGEN;

    -- Actualizar o insertar en destino
    IF EXISTS (SELECT 1 FROM INVENTARIOPRODUCTOS WHERE COD_PRODUCTO = p_CODIGO_PRODUCTO AND COD_SUCURSAL = p_COD_SUCURSAL_DESTINO) THEN
        UPDATE INVENTARIOPRODUCTOS 
        SET STOCK_ACTUAL = STOCK_ACTUAL + p_CANTIDAD 
        WHERE COD_PRODUCTO = p_CODIGO_PRODUCTO AND COD_SUCURSAL = p_COD_SUCURSAL_DESTINO;
    ELSE
        INSERT INTO INVENTARIOPRODUCTOS (COD_PRODUCTO, COD_SUCURSAL, STOCK_ACTUAL, STOCK_MINIMO, PRECIO_VENTA)
        VALUES (p_CODIGO_PRODUCTO, p_COD_SUCURSAL_DESTINO, p_CANTIDAD, 0, 0);
    END IF;

    -- Confirmar transacción
    SET MENSAJE = 'Traslado realizado correctamente';
    COMMIT;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ReporteVentasPorPeriodo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ReporteVentasPorPeriodo`(
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE,
    IN p_cod_sucursal INT
)
BEGIN
    -- Ventas diarias
    SELECT 'VENTAS_DIARIAS' AS SECCION;
    SELECT 
        DATE(F.FECHA) AS FECHA,
        COUNT(*) AS CANTIDAD_FACTURAS,
        SUM(F.SUBTOTAL) AS SUBTOTAL,
        SUM(F.TOTAL) AS TOTAL
    FROM FACTURASVENTA F
    WHERE 
        F.ESTADO = 'PAGADA' AND
        (p_fecha_inicio IS NULL OR F.FECHA >= p_fecha_inicio) AND
        (p_fecha_fin IS NULL OR F.FECHA <= DATE_ADD(p_fecha_fin, INTERVAL 1 DAY)) AND
        (p_cod_sucursal IS NULL OR F.COD_SUCURSAL = p_cod_sucursal)
    GROUP BY DATE(F.FECHA)
    ORDER BY DATE(F.FECHA);
    
    -- Ventas por sucursal
    SELECT 'VENTAS_POR_SUCURSAL' AS SECCION;
    SELECT 
        S.NOMBRE AS SUCURSAL,
        COUNT(*) AS CANTIDAD_FACTURAS,
        SUM(F.SUBTOTAL) AS SUBTOTAL,
        SUM(F.TOTAL) AS TOTAL
    FROM FACTURASVENTA F
    JOIN SUCURSALES S ON F.COD_SUCURSAL = S.COD_SUCURSAL
    WHERE 
        F.ESTADO = 'PAGADA' AND
        (p_fecha_inicio IS NULL OR F.FECHA >= p_fecha_inicio) AND
        (p_fecha_fin IS NULL OR F.FECHA <= DATE_ADD(p_fecha_fin, INTERVAL 1 DAY)) AND
        (p_cod_sucursal IS NULL OR F.COD_SUCURSAL = p_cod_sucursal)
    GROUP BY S.NOMBRE
    ORDER BY SUM(F.TOTAL) DESC;
    
    -- Ventas por método de pago
    SELECT 'VENTAS_POR_METODO_PAGO' AS SECCION;
    SELECT 
        F.METODO_PAGO,
        COUNT(*) AS CANTIDAD_FACTURAS,
        SUM(F.TOTAL) AS TOTAL
    FROM FACTURASVENTA F
    WHERE 
        F.ESTADO = 'PAGADA' AND
        (p_fecha_inicio IS NULL OR F.FECHA >= p_fecha_inicio) AND
        (p_fecha_fin IS NULL OR F.FECHA <= DATE_ADD(p_fecha_fin, INTERVAL 1 DAY)) AND
        (p_cod_sucursal IS NULL OR F.COD_SUCURSAL = p_cod_sucursal)
    GROUP BY F.METODO_PAGO
    ORDER BY SUM(F.TOTAL) DESC;
    
    -- Productos más vendidos
    SELECT 'PRODUCTOS_MAS_VENDIDOS' AS SECCION;
    SELECT 
        P.CODIGO,
        P.MODELO,
        P.DESCRIPCION,
        SUM(DV.CANTIDAD) AS CANTIDAD_TOTAL,
        SUM(DV.SUBTOTAL) AS TOTAL_VENDIDO
    FROM DETALLEVENTA DV
    JOIN PRODUCTOS P ON DV.COD_PRODUCTO = P.COD_PRODUCTO
    JOIN FACTURASVENTA F ON DV.COD_FACTURA = F.COD_FACTURA
    WHERE 
        F.ESTADO = 'PAGADA' AND
        (p_fecha_inicio IS NULL OR F.FECHA >= p_fecha_inicio) AND
        (p_fecha_fin IS NULL OR F.FECHA <= DATE_ADD(p_fecha_fin, INTERVAL 1 DAY)) AND
        (p_cod_sucursal IS NULL OR F.COD_SUCURSAL = p_cod_sucursal)
    GROUP BY P.CODIGO, P.MODELO, P.DESCRIPCION
    ORDER BY SUM(DV.CANTIDAD) DESC
    LIMIT 10;
    
    -- Resumen general
    SELECT 'RESUMEN_GENERAL' AS SECCION;
    SELECT 
        COUNT(*) AS TOTAL_FACTURAS,
        SUM(F.SUBTOTAL) AS SUBTOTAL_GENERAL,
        SUM(F.SUBTOTAL * F.DESCUENTO) AS DESCUENTOS_GENERAL,
        SUM((F.SUBTOTAL - (F.SUBTOTAL * F.DESCUENTO)) * F.IMPUESTO) AS IMPUESTOS_GENERAL,
        SUM(F.TOTAL) AS TOTAL_GENERAL,
        MIN(F.FECHA) AS PRIMERA_FACTURA,
        MAX(F.FECHA) AS ULTIMA_FACTURA
    FROM FACTURASVENTA F
    WHERE 
        F.ESTADO = 'PAGADA' AND
        (p_fecha_inicio IS NULL OR F.FECHA >= p_fecha_inicio) AND
        (p_fecha_fin IS NULL OR F.FECHA <= DATE_ADD(p_fecha_fin, INTERVAL 1 DAY)) AND
        (p_cod_sucursal IS NULL OR F.COD_SUCURSAL = p_cod_sucursal);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_SalidaMaterial` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_SalidaMaterial`(
    IN p_codigo VARCHAR(10),
    IN p_cantidad DECIMAL(10,2)
)
BEGIN
    DECLARE v_stock_actual DECIMAL(10,2);
    START TRANSACTION;
    IF NOT EXISTS (SELECT 1 FROM INVENTARIOMATERIALES WHERE MATERIAL_CODIGO = p_codigo) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El material no existe en el inventario.';
    END IF;
    
    SELECT STOCK_ACTUAL INTO v_stock_actual
    FROM INVENTARIOMATERIALES
    WHERE MATERIAL_CODIGO = p_codigo
    FOR UPDATE; 
    
    IF v_stock_actual < p_cantidad THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Stock insuficiente para la salida.';
    END IF;
    
    UPDATE INVENTARIOMATERIALES
    SET STOCK_ACTUAL = STOCK_ACTUAL - p_cantidad
    WHERE MATERIAL_CODIGO = p_codigo;
    
    COMMIT;
    SELECT CONCAT('Salida de ', p_cantidad, ' unidades de material ', p_codigo, ' registrada correctamente.') AS mensaje;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-20 16:10:08
