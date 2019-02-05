-- MySQL dump 10.13  Distrib 8.0.12, for Win64 (x86_64)
--
-- Host: localhost    Database: instaticket_juegodenaipe
-- ------------------------------------------------------
-- Server version	8.0.12

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `log_premio`
--

DROP TABLE IF EXISTS `log_premio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `log_premio` (
  `log_premio_id` int(11) NOT NULL AUTO_INCREMENT,
  `registro_anterior` varchar(500) DEFAULT NULL,
  `registro_nuevo` varchar(500) DEFAULT NULL,
  `tipo_accion` varchar(1) DEFAULT NULL,
  `fecha_ingresa` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_tabla` int(11) DEFAULT NULL,
  PRIMARY KEY (`log_premio_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_premio`
--

LOCK TABLES `log_premio` WRITE;
/*!40000 ALTER TABLE `log_premio` DISABLE KEYS */;
INSERT INTO `log_premio` VALUES (1,NULL,'PREMIO_ID: 1 |NOMBRE: PREMIO 1 |DESCRIPCIÓN: Premio |CATEGORÍA: 6-Clase F |IMAGEN: premios/imagenes/premio_1.jpg |ESTADO: 1-Activo','I','2018-04-11 15:01:55',1),(2,NULL,'PREMIO_ID: 2 |NOMBRE: PREMIO 2 |DESCRIPCIÓN: Prueba |CATEGORÍA: 5-Clase E |IMAGEN: premios/imagenes/premio_2.jpg |ESTADO: 1-Activo','I','2018-04-11 15:02:22',2),(3,'PREMIO_ID: 2 |NOMBRE: PENDRIVE |DESCRIPCIÓN: Prueba |CATEGORÍA: 5-Clase E |IMAGEN: premios/imagenes/premio_2.png |ESTADO: 1-Activo','PREMIO_ID: 2 |NOMBRE: TOMATODO |DESCRIPCIÓN: Prueba |CATEGORÍA: 5-Clase E |IMAGEN: premios/imagenes/premio_2.png |ESTADO: 1-Activo','A','2018-04-13 14:44:49',2),(4,'PREMIO_ID: 1 |NOMBRE: GORRA |DESCRIPCIÓN: Premio |CATEGORÍA: 6-Clase F |IMAGEN: premios/imagenes/premio_1.png |ESTADO: 1-Activo','PREMIO_ID: 1 |NOMBRE: GORRA |DESCRIPCIÓN: Premio |CATEGORÍA: 4-Clase F |IMAGEN: premios/imagenes/premio_1.png |ESTADO: 1-Activo','A','2018-04-13 14:46:32',1),(5,NULL,'PREMIO_ID: 3 |NOMBRE: PENDRIVE |DESCRIPCIÓN: Premio |CATEGORÍA: 3-Clase C |IMAGEN: premios/imagenes/premio_3.png |ESTADO: 1-Activo','I','2018-04-13 14:47:12',3),(6,NULL,'PREMIO_ID: 4 |NOMBRE: CELULAR |DESCRIPCIÓN: Premio |CATEGORÍA: 2-Clase B |IMAGEN: premios/imagenes/premio_4.png |ESTADO: 1-Activo','I','2018-04-13 14:47:37',4),(7,NULL,'PREMIO_ID: 5 |NOMBRE: MOTO |DESCRIPCIÓN: Premio |CATEGORÍA: 1-Clase A |IMAGEN: premios/imagenes/premio_5.png |ESTADO: 1-Activo','I','2018-04-13 14:47:52',5),(8,NULL,'PREMIO_ID: 6 |NOMBRE: CHOMPA |DESCRIPCIÓN: Premio |CATEGORÍA: 3-Clase C |IMAGEN: premios/imagenes/premio_6.png |ESTADO: 1-Activo','I','2018-04-13 14:48:48',6),(9,'PREMIO_ID: 4 |NOMBRE: CELULAR |DESCRIPCIÓN: Premio |CATEGORÍA: 2-Clase B |IMAGEN: premios/imagenes/premio_4.png |ESTADO: 1-Activo','PREMIO_ID: 4 |NOMBRE: CELULAR |DESCRIPCIÓN: Premio |CATEGORÍA: 1-Clase B |IMAGEN: premios/imagenes/premio_4.png |ESTADO: 1-Activo','A','2018-04-13 19:26:13',4),(10,NULL,'PREMIO_ID: 7 |NOMBRE: COMPUTADORA |DESCRIPCIÓN: Premio medio |CATEGORÍA: 5-CATEGORÍA E |IMAGEN: premios/imagenes/premio_7.jpg |ESTADO: 1-Activo','I','2018-04-23 20:57:23',7),(11,NULL,'PREMIO_ID: 8 |NOMBRE: SILLA |DESCRIPCIÓN: Silla |CATEGORÍA: 4-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','I','2018-04-23 20:57:51',8),(12,NULL,'PREMIO_ID: 9 |NOMBRE: PELOTA |DESCRIPCIÓN: Premio bajo |CATEGORÍA: 1-CATEGORÍA A |IMAGEN: premios/imagenes/premio_9.png |ESTADO: 1-Activo','I','2018-04-23 20:58:15',9),(13,'PREMIO_ID: 9 |NOMBRE: PELOTA |DESCRIPCIÓN: Premio bajo |CATEGORÍA: 1-CATEGORÍA A |IMAGEN: premios/imagenes/premio_9.png |ESTADO: 1-Activo','PREMIO_ID: 9 |NOMBRE: PELOTA |DESCRIPCIÓN: Premio bajo |CATEGORÍA: 6-CATEGORÍA A |IMAGEN: premios/imagenes/premio_9.png |ESTADO: 1-Activo','A','2018-04-23 20:59:24',9),(14,'PREMIO_ID: 8 |NOMBRE: SILLA |DESCRIPCIÓN: Silla |CATEGORÍA: 4-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','PREMIO_ID: 8 |NOMBRE: SILLA |DESCRIPCIÓN: Silla |CATEGORÍA: 4-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','A','2018-04-23 21:00:00',8),(15,'PREMIO_ID: 7 |NOMBRE: COMPUTADORA |DESCRIPCIÓN: Premio medio |CATEGORÍA: 5-CATEGORÍA E |IMAGEN: premios/imagenes/premio_7.jpg |ESTADO: 1-Activo','PREMIO_ID: 7 |NOMBRE: MOTO 2 |DESCRIPCIÓN: Premio medio |CATEGORÍA: 5-CATEGORÍA E |IMAGEN: premios/imagenes/premio_7.png |ESTADO: 1-Activo','A','2018-05-11 20:41:13',7),(16,'PREMIO_ID: 8 |NOMBRE: SILLA |DESCRIPCIÓN: Silla |CATEGORÍA: 4-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','PREMIO_ID: 8 |NOMBRE: GORR 2 |DESCRIPCIÓN: Silla |CATEGORÍA: 4-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','A','2018-05-11 20:41:58',8),(17,'PREMIO_ID: 9 |NOMBRE: PELOTA |DESCRIPCIÓN: Premio bajo |CATEGORÍA: 6-CATEGORÍA F |IMAGEN: premios/imagenes/premio_9.png |ESTADO: 1-Activo','PREMIO_ID: 9 |NOMBRE: TOMATODO 2 |DESCRIPCIÓN: Premio bajo |CATEGORÍA: 6-CATEGORÍA F |IMAGEN: premios/imagenes/premio_9.png |ESTADO: 1-Activo','A','2018-05-11 20:42:33',9),(18,'PREMIO_ID: 8 |NOMBRE: GORRA ADIDAS |DESCRIPCIÓN: Silla |CATEGORÍA: 4-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','PREMIO_ID: 8 |NOMBRE: GORRA ADIDAS |DESCRIPCIÓN: Gorra |CATEGORÍA: 4-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','A','2018-06-11 17:34:37',8),(19,'PREMIO_ID: 7 |NOMBRE: MOTO HONDA |DESCRIPCIÓN: Premio medio |CATEGORÍA: 5-CATEGORÍA E |IMAGEN: premios/imagenes/premio_7.png |ESTADO: 1-Activo','PREMIO_ID: 7 |NOMBRE: MOTO HONDA |DESCRIPCIÓN: Premio medio |CATEGORÍA: 1-CATEGORÍA E |IMAGEN: premios/imagenes/premio_7.png |ESTADO: 1-Activo','A','2018-06-11 17:34:54',7),(20,'PREMIO_ID: 7 |NOMBRE: MOTO HONDA |DESCRIPCIÓN: Premio medio |CATEGORÍA: 1-CATEGORÍA A |IMAGEN: premios/imagenes/premio_7.png |ESTADO: 1-Activo','PREMIO_ID: 7 |NOMBRE: MOTO HONDA |DESCRIPCIÓN: premio alto |CATEGORÍA: 1-CATEGORÍA A |IMAGEN: premios/imagenes/premio_7.png |ESTADO: 1-Activo','A','2018-06-11 17:35:29',7),(21,'PREMIO_ID: 8 |NOMBRE: GORRA ADIDAS |DESCRIPCIÓN: Gorra |CATEGORÍA: 4-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','PREMIO_ID: 8 |NOMBRE: GORRA ADIDAS |DESCRIPCIÓN: premio bajo |CATEGORÍA: 4-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','A','2018-06-11 17:35:45',8),(22,'PREMIO_ID: 9 |NOMBRE: TOMATODO NIKE |DESCRIPCIÓN: Premio bajo |CATEGORÍA: 6-CATEGORÍA F |IMAGEN: premios/imagenes/premio_9.png |ESTADO: 1-Activo','PREMIO_ID: 9 |NOMBRE: TOMATODO NIKE |DESCRIPCIÓN: Premio medio |CATEGORÍA: 4-CATEGORÍA F |IMAGEN: premios/imagenes/premio_9.png |ESTADO: 1-Activo','A','2018-06-11 17:36:14',9),(23,NULL,'PREMIO_ID: 10 |NOMBRE: GORRA ADIDAS PRUEBA |DESCRIPCIÓN: Gorra Adidas Prueba |CATEGORÍA: 6-CATEGORÍA F |IMAGEN: premios/imagenes/premio_10.png |ESTADO: 1-Activo','I','2018-06-11 17:37:53',10),(24,'PREMIO_ID: 8 |NOMBRE: GORRA ADIDAS |DESCRIPCIÓN: premio bajo |CATEGORÍA: 4-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','PREMIO_ID: 8 |NOMBRE: GORRA ADIDAS |DESCRIPCIÓN: premio bajo |CATEGORÍA: 6-CATEGORÍA D |IMAGEN: premios/imagenes/premio_8.png |ESTADO: 1-Activo','A','2018-06-11 17:38:14',8);
/*!40000 ALTER TABLE `log_premio` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-22 15:36:14
