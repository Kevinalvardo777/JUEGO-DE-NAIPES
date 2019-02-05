-- MySQL dump 10.13  Distrib 8.0.12, for Win64 (x86_64)
--
-- Host: localhost    Database: instaticket_JuegodeNaipe
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
-- Table structure for table `log_participante`
--

DROP TABLE IF EXISTS `log_participante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `log_participante` (
  `log_participante_id` int(11) NOT NULL AUTO_INCREMENT,
  `registro_anterior` varchar(500) DEFAULT NULL,
  `registro_nuevo` varchar(500) DEFAULT NULL,
  `tipo_accion` varchar(1) DEFAULT NULL,
  `fecha_ingresa` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_tabla` int(11) DEFAULT NULL,
  PRIMARY KEY (`log_participante_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_participante`
--

LOCK TABLES `log_participante` WRITE;
/*!40000 ALTER TABLE `log_participante` DISABLE KEYS */;
INSERT INTO `log_participante` VALUES (1,NULL,'PARTICIPANTE_ID: 1 |NOMBRE: GABRIELA  |APELLIDO: ROSADO |CÉDULA: 0929354835 |EMAIL: gyra_92@hotmail.com |TELÉFONO: 043148093 |CELULAR: 0981065404 |BOLETO: 1002220001000015','I','2018-04-11 15:54:47',1),(2,'PARTICIPANTE_ID: 1 |NOMBRE: GABRIELA  |APELLIDO: ROSADO |CÉDULA: 0929354835 |EMAIL: gyra_92@hotmail.com |TELÉFONO: 043148093 |CELULAR: 0981065404','PARTICIPANTE_ID: 1 |NOMBRE: GABRIELA  |APELLIDO: ROSADO |CÉDULA: 0929354835 |EMAIL: gyra_92@hotmail.com |TELÉFONO: 043148093 |CELULAR: 0981065404 |BOLETO: 1002220001000013','A','2018-04-11 20:34:31',1),(3,'PARTICIPANTE_ID: 1 |NOMBRE: GABRIELA  |APELLIDO: ROSADO |CÉDULA: 0929354835 |EMAIL: gyra_92@hotmail.com |TELÉFONO: 043148093 |CELULAR: 0981065404','PARTICIPANTE_ID: 1 |NOMBRE: GABRIELA  |APELLIDO: ROSADO |CÉDULA: 0929354835 |EMAIL: gyra_92@hotmail.com |TELÉFONO: 043148093 |CELULAR: 0981065404 |BOLETO: 1002220001000014','A','2018-04-11 20:35:20',1),(4,NULL,'PARTICIPANTE_ID: 2 |NOMBRE: WILSON |CÉDULA: 0941106080 |EMAIL: Wilso.quintolmedo@gmail.com  |TELÉFONO:  |CELULAR: 0912345678 |BOLETO: 1002220001000149','I','2018-05-10 19:26:55',2),(5,'PARTICIPANTE_ID: 2 |NOMBRE: WILSON |CÉDULA: 0941106080 |EMAIL: Wilso.quintolmedo@gmail.com  |TELÉFONO:  |CELULAR: 0912345678','PARTICIPANTE_ID: 2 |NOMBRE: WILSON |CÉDULA: 0941106080 |EMAIL: Wilso.quintolmedo@gmail.com |TELÉFONO:  |CELULAR: 0912345678 |BOLETO: 1002220001000145','A','2018-05-10 19:43:58',2),(6,NULL,'PARTICIPANTE_ID: 3 |NOMBRE: WILSON |CÉDULA: 0941106080 |EMAIL: Wilson.quinto@yahoo.es  |TELÉFONO: 043148093 |CELULAR: 0981065404 |BOLETO: 1002220001000013','I','2018-08-21 19:40:42',3),(7,'PARTICIPANTE_ID: 3 |NOMBRE: WILSON |CÉDULA: 0941106080 |EMAIL: Wilson.quinto@yahoo.es  |TELÉFONO: 043148093 |CELULAR: 0981065404','PARTICIPANTE_ID: 3 |NOMBRE: WILSON |CÉDULA: 0941106080 |EMAIL: Wilson.quinto@yahoo.es |TELÉFONO: 043148093 |CELULAR: 0981065404 |BOLETO: 1002220001000014','A','2018-08-21 19:45:25',3),(8,NULL,'PARTICIPANTE_ID: 4 |NOMBRE: WILSON |CÉDULA: 0941106080 |EMAIL: Wilson.quinto@yahoo.es |TELÉFONO: 123123 |CELULAR: 0981065404 |BOLETO: 1002220001000014','I','2018-08-21 19:46:35',4),(9,NULL,'PARTICIPANTE_ID: 5 |NOMBRE: WILSON |CÉDULA: 0941106080 |EMAIL: Wilson.quinto@yahoo.es |TELÉFONO: 123123 |CELULAR: 0981065404 |BOLETO: 1002220001000014','I','2018-08-21 19:48:26',5),(10,NULL,'PARTICIPANTE_ID: 6 |NOMBRE: WILSON |CÉDULA: 0941106080 |EMAIL: Wilson.quinto@yahoo.es |TELÉFONO: 123123 |CELULAR: 0981065404 |BOLETO: 1002220001000013','I','2018-08-21 20:08:52',6),(11,NULL,'PARTICIPANTE_ID: 7 |NOMBRE: WILSON |CÉDULA: 0941106080 |EMAIL: gyra_92@hotmail.com |TELÉFONO: 043148093 |CELULAR: 0981065404 |BOLETO: 1002220001000013','I','2018-08-22 15:55:50',7),(12,NULL,'PARTICIPANTE_ID: 8 |NOMBRE: WILSON |CÉDULA: 0941106081 |EMAIL: gyra_92@hotmail.com |TELÉFONO: 043148093 |CELULAR: 0981065404 |BOLETO: 1002220001000015','I','2018-08-22 20:07:43',8),(13,NULL,'PARTICIPANTE_ID: 9 |NOMBRE: GABRIELA |CÉDULA: 0967808374 |EMAIL: gabi@hotmail.com |TELÉFONO: 123123 |CELULAR: 0981065404 |BOLETO: 1002220001000016','I','2018-08-22 20:16:37',9);
/*!40000 ALTER TABLE `log_participante` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-22 15:36:28