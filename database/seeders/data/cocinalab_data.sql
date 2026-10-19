/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: login
-- ------------------------------------------------------
-- Server version	10.11.18-MariaDB-0+deb12u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `almacen_productos`
--

DROP TABLE IF EXISTS `almacen_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `almacen_productos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `categoria` enum('ingrediente','abarrote','bebida','pan_tortilla','insumo','limpieza') NOT NULL,
  `subcategoria` varchar(50) NOT NULL,
  `unidad` enum('pieza','kg','g','L','ml','paquete','caja','rollo','botella','lata','garrafon','manojo','frasco','sobre','costal') NOT NULL,
  `stock` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `perecedero` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_caducidad` date DEFAULT NULL,
  `proveedor` varchar(200) DEFAULT NULL,
  `motivo_baja` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `almacen_productos_nombre_unique` (`nombre`),
  KEY `almacen_productos_categoria_subcategoria_index` (`categoria`,`subcategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `almacen_productos`
--

LOCK TABLES `almacen_productos` WRITE;
/*!40000 ALTER TABLE `almacen_productos` DISABLE KEYS */;
INSERT INTO `almacen_productos` VALUES
(1,'tomate verde','ingrediente','verdura','kg',8.00,2.00,28.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(2,'chile serrano','ingrediente','verdura','pieza',60.00,15.00,1.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(3,'diente de ajo','ingrediente','verdura','pieza',40.00,10.00,0.50,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(4,'cilantro','ingrediente','verdura','manojo',12.00,3.00,8.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(5,'totopos','pan_tortilla','totopo_tostada','paquete',8.00,2.00,35.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(6,'pechuga de pollo','ingrediente','proteina_fresca','kg',10.00,3.00,95.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(7,'crema ácida','ingrediente','lacteo_fresco','pieza',8.00,2.00,35.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(8,'queso fresco','ingrediente','lacteo_fresco','pieza',6.00,2.00,55.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(9,'cebolla morada','ingrediente','verdura','pieza',15.00,4.00,6.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(10,'aceite vegetal','abarrote','aceite_vinagre','L',8.00,2.00,45.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(11,'huevo','ingrediente','proteina_fresca','pieza',120.00,30.00,3.50,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(12,'leche','ingrediente','lacteo_fresco','L',12.00,3.00,28.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(13,'mantequilla','ingrediente','lacteo_fresco','pieza',6.00,2.00,40.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(15,'avena','abarrote','grano_cereal','kg',5.00,1.00,35.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(16,'agua','bebida','agua','botella',24.00,6.00,15.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(17,'plátano','ingrediente','fruta','pieza',25.00,6.00,4.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(18,'miel','abarrote','dulce','kg',2.00,0.50,150.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(20,'yogurth natural','ingrediente','lacteo_fresco','pieza',5.00,1.00,45.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(21,'granola','abarrote','grano_cereal','kg',3.00,1.00,90.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(22,'fresas','ingrediente','fruta','kg',3.00,1.00,80.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(23,'aguacate','ingrediente','fruta','pieza',20.00,5.00,18.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(24,'pan integral','pan_tortilla','pan_dulce','pieza',5.00,1.00,50.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(26,'tomate rojo','ingrediente','verdura','kg',8.00,2.00,25.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(27,'cebolla blanca','ingrediente','verdura','pieza',20.00,5.00,5.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(30,'Sal','abarrote','especia','g',2000.00,500.00,0.02,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(31,'bolillo','pan_tortilla','pan_dulce','pieza',60.00,15.00,3.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(32,'Frijoles refritos','abarrote','enlatado','lata',12.00,3.00,22.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(33,'Queso manchego','ingrediente','lacteo_fresco','pieza',4.00,1.00,90.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(34,'arroz','abarrote','grano_cereal','kg',10.00,3.00,28.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(36,'zanahoria','ingrediente','verdura','kg',6.00,2.00,18.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(37,'elote','ingrediente','verdura','kg',10.00,3.00,25.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(38,'chícharos','ingrediente','verdura','kg',4.00,1.00,35.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(39,'pasta','abarrote','grano_cereal','kg',6.00,2.00,30.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(40,'atún en agua','abarrote','enlatado','lata',20.00,5.00,22.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(41,'salsa de tomate','abarrote','salsa_condimento','pieza',8.00,2.00,30.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(42,'Orégano','abarrote','especia','g',300.00,50.00,0.60,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(43,'lechuga romana','ingrediente','verdura','kg',5.00,1.50,22.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(44,'aderezo César','abarrote','salsa_condimento','pieza',6.00,2.00,45.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(45,'crotones','abarrote','grano_cereal','kg',2.00,0.50,80.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(46,'tortilla de harina','pan_tortilla','tortilla','kg',5.00,1.00,25.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(47,'carne de res','ingrediente','proteina_fresca','kg',8.00,2.00,180.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(48,'lentejas','abarrote','grano_cereal','kg',5.00,1.00,32.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(50,'caldo de pollo','abarrote','enlatado','pieza',8.00,2.00,25.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(51,'carne molida','ingrediente','proteina_fresca','kg',6.00,2.00,170.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(54,'muslo de pollo','ingrediente','proteina_fresca','kg',8.00,2.00,65.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(56,'aceite de oliva','abarrote','aceite_vinagre','L',3.00,1.00,180.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(57,'espagueti','abarrote','grano_cereal','kg',5.00,1.00,30.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(58,'filete de pescado','ingrediente','proteina_fresca','kg',5.00,1.50,160.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(59,'Jugo de limón','bebida','jugo','pieza',6.00,2.00,35.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(60,'espinaca fresca','ingrediente','verdura','kg',2.00,0.50,60.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(61,'tomate cherry','ingrediente','verdura','kg',2.00,0.50,70.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(62,'vinagre balsámico','abarrote','aceite_vinagre','L',2.00,0.50,120.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(63,'pimiento rojo','ingrediente','verdura','kg',3.00,1.00,55.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(64,'queso rallado','ingrediente','lacteo_fresco','pieza',5.00,1.00,70.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(65,'pollo cocido','ingrediente','proteina_fresca','kg',4.00,1.00,110.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(67,'tortilla de maíz','pan_tortilla','tortilla','kg',8.00,2.00,22.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(72,'soja','abarrote','salsa_condimento','pieza',4.00,1.00,60.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(73,'baguette','pan_tortilla','pan_dulce','pieza',8.00,2.00,25.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(74,'jamón','ingrediente','proteina_fresca','kg',4.00,1.00,120.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(76,'ciabatta','pan_tortilla','pan_dulce','pieza',12.00,3.00,15.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(77,'papa','ingrediente','verdura','kg',10.00,3.00,22.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(78,'ajo en polvo','abarrote','especia','g',500.00,100.00,0.40,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(79,'tostada de maíz','pan_tortilla','totopo_tostada','paquete',8.00,2.00,30.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(81,'mayonesa','abarrote','salsa_condimento','pieza',5.00,1.00,65.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(82,'pan de caja','pan_tortilla','pan_dulce','pieza',6.00,2.00,45.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(85,'mango','ingrediente','fruta','pieza',15.00,4.00,12.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(86,'Azucar','abarrote','dulce','kg',8.00,2.00,28.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(87,'Chocolate semiamargo','abarrote','dulce','kg',2.00,0.50,180.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(88,'Canela','abarrote','especia','g',200.00,50.00,0.80,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(89,'Cascara de citrico','ingrediente','fruta','pieza',10.00,2.00,2.00,1,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(90,'Vainilla','abarrote','especia','g',250.00,50.00,0.50,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(91,'Leche condensada','abarrote','enlatado','lata',8.00,2.00,28.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(92,'Leche evaporada','abarrote','enlatado','lata',8.00,2.00,25.00,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(93,'Cacao en polvo','abarrote','dulce','g',500.00,100.00,0.24,0,NULL,NULL,NULL,'2026-10-06 18:44:58','2026-10-06 18:44:58',NULL),
(94,'Gelatina','abarrote','dulce','pieza',15.00,4.00,18.00,0,NULL,NULL,NULL,'2026-10-06 18:44:59','2026-10-06 18:44:59',NULL),
(95,'Galleta','abarrote','dulce','paquete',8.00,2.00,35.00,0,NULL,NULL,NULL,'2026-10-06 18:44:59','2026-10-06 18:44:59',NULL),
(96,'Queso crema','ingrediente','lacteo_fresco','pieza',6.00,2.00,45.00,1,NULL,NULL,NULL,'2026-10-06 18:44:59','2026-10-06 18:44:59',NULL),
(97,'Harina','abarrote','harina','kg',8.00,2.00,20.00,0,NULL,NULL,NULL,'2026-10-06 18:44:59','2026-10-06 18:44:59',NULL),
(98,'Polvo para hornear','abarrote','especia','g',300.00,50.00,0.25,0,NULL,NULL,NULL,'2026-10-06 18:44:59','2026-10-06 18:44:59',NULL);
/*!40000 ALTER TABLE `almacen_productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comanda_items`
--

DROP TABLE IF EXISTS `comanda_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comanda_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comanda_id` bigint(20) unsigned NOT NULL,
  `receta_id` bigint(20) unsigned NOT NULL,
  `cantidad` int(10) unsigned NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  `notas` text DEFAULT NULL,
  `estado` enum('pendiente','en_preparacion','listo','servido','cancelado') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comanda_items_receta_id_foreign` (`receta_id`),
  KEY `comanda_items_comanda_id_estado_index` (`comanda_id`,`estado`),
  CONSTRAINT `comanda_items_comanda_id_foreign` FOREIGN KEY (`comanda_id`) REFERENCES `comandas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comanda_items_receta_id_foreign` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comanda_items`
--

LOCK TABLES `comanda_items` WRITE;
/*!40000 ALTER TABLE `comanda_items` DISABLE KEYS */;
INSERT INTO `comanda_items` VALUES
(1,1,41,1,85.00,NULL,'servido','2026-10-13 12:56:44','2026-10-13 12:59:16'),
(2,1,29,1,80.00,NULL,'servido','2026-10-13 12:58:36','2026-10-13 12:59:16'),
(3,2,29,2,80.00,NULL,'servido','2026-10-14 04:22:16','2026-10-14 04:24:01'),
(4,2,41,1,85.00,NULL,'servido','2026-10-14 04:22:16','2026-10-14 04:24:01'),
(5,3,29,1,80.00,NULL,'en_preparacion','2026-10-14 04:24:28','2026-10-14 04:24:44');
/*!40000 ALTER TABLE `comanda_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comandas`
--

DROP TABLE IF EXISTS `comandas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comandas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mesa` varchar(100) NOT NULL,
  `tipo` enum('mesa','llevar') NOT NULL DEFAULT 'mesa',
  `mesero_id` bigint(20) unsigned NOT NULL,
  `estado` enum('abierta','en_cocina','lista','servida','cancelada') NOT NULL DEFAULT 'abierta',
  `notas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comandas_mesero_id_foreign` (`mesero_id`),
  KEY `comandas_estado_tipo_index` (`estado`,`tipo`),
  CONSTRAINT `comandas_mesero_id_foreign` FOREIGN KEY (`mesero_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comandas`
--

LOCK TABLES `comandas` WRITE;
/*!40000 ALTER TABLE `comandas` DISABLE KEYS */;
INSERT INTO `comandas` VALUES
(1,'2','mesa',5,'servida',NULL,'2026-10-13 12:56:44','2026-10-13 12:59:16',NULL),
(2,'3','mesa',7,'servida',NULL,'2026-10-14 04:22:16','2026-10-14 04:24:01',NULL),
(3,'4','mesa',7,'en_cocina',NULL,'2026-10-14 04:24:28','2026-10-14 04:24:44',NULL);
/*!40000 ALTER TABLE `comandas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredientes`
--

DROP TABLE IF EXISTS `ingredientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingredientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(200) NOT NULL,
  `Tipo` varchar(200) DEFAULT NULL,
  `almacen_producto_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ingredientes_almacen_producto_id_foreign` (`almacen_producto_id`),
  CONSTRAINT `ingredientes_almacen_producto_id_foreign` FOREIGN KEY (`almacen_producto_id`) REFERENCES `almacen_productos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredientes`
--

LOCK TABLES `ingredientes` WRITE;
/*!40000 ALTER TABLE `ingredientes` DISABLE KEYS */;
INSERT INTO `ingredientes` VALUES
(1,'tomate verde','\" \"',NULL),
(2,'chile serrano','\" \"',NULL),
(3,'diente de ajo','\" \"',NULL),
(4,'cilantro','\" \"',NULL),
(5,'totopos','\" \"',NULL),
(6,'pechuga de pollo','\" \"',NULL),
(7,'crema ácida','\" \"',NULL),
(8,'queso fresco','\" \"',NULL),
(9,'cebolla morada','\" \"',NULL),
(10,'aceite','\" \"',NULL),
(11,'huevo','\" \"',NULL),
(12,'leche','\" \"',NULL),
(13,'mantequilla','\" \"',NULL),
(14,'pan','\" \"',NULL),
(15,'avena','\" \"',NULL),
(16,'agua','\" \"',NULL),
(17,'plátano','\" \"',NULL),
(18,'miel','\" \"',NULL),
(19,'Fruta','\" \"',NULL),
(20,'yogurth natural','\" \"',NULL),
(21,'granola','\" \"',NULL),
(22,'fresas','\" \"',NULL),
(23,'aguacate','\" \"',NULL),
(24,'pan integral','\" \"',NULL),
(25,'yogur','\" \"',NULL),
(26,'tomate rojo','\" \"',NULL),
(27,'cebolla blanca','\" \"',NULL),
(28,'dientes de ajo','\" \"',NULL),
(29,'chiles serranos','\" \"',NULL),
(30,'Sal','\" \"',NULL),
(31,'bolillo','\" \"',NULL),
(32,'Frijoles refritos','\" \"',NULL),
(33,'Queso manchego','\" \"',NULL),
(34,'arroz','\" \"',NULL),
(35,'pechuga pollo','\" \"',NULL),
(36,'zanahoria','\" \"',NULL),
(37,'elote','\" \"',NULL),
(38,'chícharos','\" \"',NULL),
(39,'pasta','\" \"',NULL),
(40,'atún en agua','\" \"',NULL),
(41,'salsa de tomate','\" \"',NULL),
(42,'Orégano','\" \"',NULL),
(43,'lechuga romana','\" \"',NULL),
(44,'aderezo César','\" \"',NULL),
(45,'crotones','\" \"',NULL),
(46,'tortilla de harina','\" \"',NULL),
(47,'carne','\" \"',NULL),
(48,'lentejas','\" \"',NULL),
(49,'cebolla','\" \"',NULL),
(50,'caldo de pollo','\" \"',NULL),
(51,'carne molida','\" \"',NULL),
(52,'tortilla','\" \"',NULL),
(53,'Salsa al gusto','\" \"',NULL),
(54,'muslo de pollo','\" \"',NULL),
(55,'papas','\" \"',NULL),
(56,'aceite de oliva','\" \"',NULL),
(57,'espagueti','\" \"',NULL),
(58,'filete pescado','\" \"',NULL),
(59,'Jugo de limón','\" \"',NULL),
(60,'espinaca fresca','\" \"',NULL),
(61,'tomate cherry','\" \"',NULL),
(62,'vinagre balsámico','\" \"',NULL),
(63,'pimiento rojo','\" \"',NULL),
(64,'queso rallado','\" \"',NULL),
(65,'pollo cocido','\" \"',NULL),
(66,'cebolla picada','\" \"',NULL),
(67,'tortilla de maíz','\" \"',NULL),
(68,'tomate','\" \"',NULL),
(69,'arvejas','\" \"',NULL),
(70,'arroz cocido','\" \"',NULL),
(71,'diente ajo','\" \"',NULL),
(72,'soja','\" \"',NULL),
(73,'baguette','\" \"',NULL),
(74,'jamón','\" \"',NULL),
(75,'queso','\" \"',NULL),
(76,'ciabatta','\" \"',NULL),
(77,'papa','\" \"',NULL),
(78,'ajo en polvo','\" \"',NULL),
(79,'tostada de maíz','\" \"',NULL),
(80,'atún escurrido','\" \"',NULL),
(81,'mayonesa','\" \"',NULL),
(82,'pan de caja','\" \"',NULL),
(83,'pasta cocida','\" \"',NULL),
(84,'crema','\" \"',NULL),
(85,'mango','\" \"',NULL),
(86,'Azucar','\" \"',NULL),
(87,'Chocolate semiamargo','\" \"',NULL),
(88,'Canela','\" \"',NULL),
(89,'Cascara de citrico','\" \"',NULL),
(90,'Vainilla','\" \" ',NULL),
(91,'Leche condensada','\" \"',NULL),
(92,'Leche evaporada','\" \"',NULL),
(93,'Cacao en polvo','\" \"',NULL),
(94,'Gelatina','\" \"',NULL),
(95,'Galleta','\" \"',NULL),
(96,'Queso crema','\" \" ',NULL),
(97,'Harina','\" \"',NULL),
(98,'Polvo para hornear','\" \"',NULL);
/*!40000 ALTER TABLE `ingredientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_09_24_042022_create_cocinalab_tables',1),
(5,'2026_09_25_220411_add_role_and_status_to_users_table',1),
(6,'2026_09_25_223210_add_cajero_role_to_users_table',2),
(7,'2026_09_26_010720_add_codigo_empleado_to_users_table',3),
(8,'2026_09_26_120000_add_deleted_at_to_users_table',4),
(9,'2026_09_26_130000_add_sistemas_role_to_users_table',4),
(10,'2026_09_27_000001_add_password_reset_requested_at_to_users_table',5),
(11,'2026_10_06_000001_create_almacen_productos_table',6),
(12,'2026_10_06_000002_limpia_almacen_productos',7),
(13,'2026_10_06_000003_almacen_stock_precios',8),
(14,'2026_10_09_000001_add_soft_deletes_to_recetas_table',9),
(15,'2026_10_09_000002_create_receta_imagenes_table',9),
(16,'2026_10_09_000003_add_almacen_producto_to_ingredientes_table',9),
(17,'2026_10_09_000004_add_cantidad_num_unidad_to_receta_ingrediente_table',10),
(18,'2026_10_09_000005_backfill_ingredientes_desde_almacen',11),
(19,'2026_10_09_000006_add_precio_platillo_to_recetas_table',12),
(20,'2026_10_14_000001_add_motivo_baja_to_almacen_productos_table',13),
(21,'2026_10_14_000002_create_proveedores_table',14),
(22,'2026_10_14_000003_create_comandas_tables',15);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pago`
--

DROP TABLE IF EXISTS `pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pago` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Metodo` varchar(200) DEFAULT NULL,
  `Fecha` timestamp NULL DEFAULT current_timestamp(),
  `Tarjeta` varchar(200) DEFAULT NULL,
  `Vencimiento` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago`
--

LOCK TABLES `pago` WRITE;
/*!40000 ALTER TABLE `pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proveedores_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES
(1,'asas','6692237128','asassas@gmail.com','Francisco Solís Loma Linda 214','2026-10-13 12:16:19','2026-10-13 12:16:19',NULL);
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receta_imagenes`
--

DROP TABLE IF EXISTS `receta_imagenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `receta_imagenes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `receta_id` bigint(20) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `orden` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receta_imagenes_receta_id_orden_index` (`receta_id`,`orden`),
  CONSTRAINT `receta_imagenes_receta_id_foreign` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receta_imagenes`
--

LOCK TABLES `receta_imagenes` WRITE;
/*!40000 ALTER TABLE `receta_imagenes` DISABLE KEYS */;
INSERT INTO `receta_imagenes` VALUES
(1,41,'recetas/jDnT2SXOgJdQ5xieS1NxADjbiHPLsyWTKBBU8jNW.png',0,'2026-10-10 06:13:29','2026-10-10 06:13:29');
/*!40000 ALTER TABLE `receta_imagenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receta_ingrediente`
--

DROP TABLE IF EXISTS `receta_ingrediente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `receta_ingrediente` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `receta_id` bigint(20) unsigned NOT NULL,
  `ingrediente_id` bigint(20) unsigned NOT NULL,
  `cantidad` varchar(100) DEFAULT NULL,
  `cantidad_num` decimal(10,2) DEFAULT NULL,
  `unidad` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receta_ingrediente_receta_id_foreign` (`receta_id`),
  KEY `receta_ingrediente_ingrediente_id_foreign` (`ingrediente_id`),
  CONSTRAINT `receta_ingrediente_ingrediente_id_foreign` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `receta_ingrediente_receta_id_foreign` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receta_ingrediente`
--

LOCK TABLES `receta_ingrediente` WRITE;
/*!40000 ALTER TABLE `receta_ingrediente` DISABLE KEYS */;
INSERT INTO `receta_ingrediente` VALUES
(1,1,1,'3',NULL,NULL),
(2,1,2,'1',NULL,NULL),
(3,1,71,'1',NULL,NULL),
(4,1,4,'3 cucharadas de cilantro picado',NULL,NULL),
(5,1,5,'2 tazas',NULL,NULL),
(6,1,6,'1, aunque esto es opcional',NULL,NULL),
(7,1,7,'2 cucharadas',NULL,NULL),
(8,1,8,'50g',NULL,NULL),
(9,1,9,'1/4 en aros',NULL,NULL),
(10,1,10,'1 cucharada',NULL,NULL),
(11,2,11,'2',NULL,NULL),
(12,2,12,'1 cucharada',NULL,NULL),
(13,2,13,'1 cucharadita',NULL,NULL),
(14,2,82,'1 rebanada',NULL,NULL),
(15,3,15,'1/2 taza',NULL,NULL),
(16,3,12,'1 taza',NULL,NULL),
(17,3,17,'1 en rodajas',NULL,NULL),
(18,3,18,'1 cucharadita',NULL,NULL),
(19,3,19,'al gusto, opcional',NULL,NULL),
(20,4,20,'1 taza',NULL,NULL),
(21,4,21,'1/4 de taza',NULL,NULL),
(22,4,22,'1/2 taza picadas',NULL,NULL),
(23,4,18,'1 cucharadita, opcional',NULL,NULL),
(24,5,23,'1 en rodajas',NULL,NULL),
(25,5,11,'2 ',NULL,NULL),
(26,5,24,'2 rebanadas',NULL,NULL),
(27,5,30,'al gusto',NULL,NULL),
(28,6,17,'1',NULL,NULL),
(29,6,22,'1/2 taza',NULL,NULL),
(30,6,12,'1 taza',NULL,NULL),
(31,6,18,'1 cucharada',NULL,NULL),
(32,7,5,'1 bolsa, alrededor de 400-500g',NULL,NULL),
(33,7,26,'de 6 a 8',NULL,NULL),
(34,7,27,'1/4',NULL,NULL),
(35,7,3,'2',NULL,NULL),
(36,7,29,'1-2 opcional',NULL,NULL),
(37,7,30,'al gusto',NULL,NULL),
(38,7,8,'al gusto',NULL,NULL),
(39,7,7,'al gusto',NULL,NULL),
(40,7,9,'1/4, opcional',NULL,NULL),
(41,7,23,'1, opcional',NULL,NULL),
(42,8,31,'1 partido en 2 rebanadas',NULL,NULL),
(43,8,32,'50-70g',NULL,NULL),
(44,8,33,'40-40 gramos rallados',NULL,NULL),
(45,9,34,'1 taza',NULL,NULL),
(46,9,6,'1 en cubos',NULL,NULL),
(47,9,11,'1',NULL,NULL),
(48,9,36,'1/6 taza',NULL,NULL),
(49,9,37,'1/6 taza',NULL,NULL),
(50,9,38,'1/6 taza',NULL,NULL),
(51,9,10,'1 cucharada',NULL,NULL),
(52,9,16,'2 tazas',NULL,NULL),
(53,10,39,'1 taza ',NULL,NULL),
(54,10,40,'1 lata',NULL,NULL),
(55,10,41,'1/2 taza',NULL,NULL),
(56,10,42,'al gusto',NULL,NULL),
(57,11,43,'2 tazas',NULL,NULL),
(58,11,6,'1',NULL,NULL),
(59,11,10,'1 cucharada',NULL,NULL),
(60,11,44,'2 cucharadas',NULL,NULL),
(61,11,45,'1/4 taza',NULL,NULL),
(62,12,46,'3',NULL,NULL),
(63,12,47,'250g',NULL,NULL),
(64,12,30,'y pimienta al gusto',NULL,NULL),
(65,12,33,'60-80g o 1/2-2/3 de taza',NULL,NULL),
(66,13,48,'1 taza',NULL,NULL),
(67,13,66,'1/2 taza',NULL,NULL),
(68,13,36,'1 partida en cubos',NULL,NULL),
(69,13,50,'2 tazas',NULL,NULL),
(70,14,51,'200g',NULL,NULL),
(71,14,67,'4',NULL,NULL),
(72,14,66,'1/4',NULL,NULL),
(73,14,53,'al gusto',NULL,NULL),
(74,15,54,'2',NULL,NULL),
(75,15,77,'2 partidas en cubos',NULL,NULL),
(76,15,56,'1 cucharada',NULL,NULL),
(77,15,30,'y pimienta al gusto',NULL,NULL),
(78,16,57,'200g',NULL,NULL),
(79,16,51,'1/2 taza ya cocida',NULL,NULL),
(80,16,41,'1/2 taza',NULL,NULL),
(81,17,58,'1',NULL,NULL),
(82,17,59,'1/2 taza',NULL,NULL),
(83,17,30,'y pimienta al gusto',NULL,NULL),
(84,18,6,'1 (150g)',NULL,NULL),
(85,18,60,'2 tazas',NULL,NULL),
(86,18,61,'1/2 taza',NULL,NULL),
(87,18,9,'1/4',NULL,NULL),
(88,18,56,'1 cucharada',NULL,NULL),
(89,18,62,'1 cucharada',NULL,NULL),
(90,18,30,'y pimienta al gusto',NULL,NULL),
(91,19,11,'3',NULL,NULL),
(92,19,63,'1/4 taza',NULL,NULL),
(93,19,66,'1/4 taza',NULL,NULL),
(94,19,64,'1/4 taza',NULL,NULL),
(95,19,56,'1 cucharada',NULL,NULL),
(96,19,30,'al gusto',NULL,NULL),
(97,20,46,'2',NULL,NULL),
(98,20,6,'100g',NULL,NULL),
(99,20,64,'1/2',NULL,NULL),
(100,20,66,'1 cucharada',NULL,NULL),
(101,20,10,'1 cucharadita',NULL,NULL),
(102,21,40,'1 lata (120g)',NULL,NULL),
(103,21,67,'2',NULL,NULL),
(104,21,66,'1/4',NULL,NULL),
(105,21,68,'1/2',NULL,NULL),
(106,21,59,'1/2 taza',NULL,NULL),
(107,21,30,'al gusto',NULL,NULL),
(108,21,4,'al gusto',NULL,NULL),
(109,22,70,'1 taza ',NULL,NULL),
(110,22,11,'1',NULL,NULL),
(111,22,36,'1/4 taza',NULL,NULL),
(112,22,69,'1/4 taza ',NULL,NULL),
(113,22,71,'1',NULL,NULL),
(114,22,10,'1 cucharada',NULL,NULL),
(115,22,72,'al gusto, opcional',NULL,NULL),
(116,23,76,'1',NULL,NULL),
(117,23,74,'2 rebanadas',NULL,NULL),
(118,23,75,'2 rebanadas',NULL,NULL),
(119,23,13,'1 cucharada',NULL,NULL),
(120,24,6,'1 (150g)',NULL,NULL),
(121,24,77,'1 mediana, en cubos',NULL,NULL),
(122,24,59,'1 cucharada',NULL,NULL),
(123,24,78,'1 cucharada',NULL,NULL),
(124,24,56,'1 cucharada',NULL,NULL),
(125,24,30,'y pimienta al gusto',NULL,NULL),
(126,25,79,'2',NULL,NULL),
(127,25,80,'1 lata (120g)',NULL,NULL),
(128,25,81,'1/4 taza',NULL,NULL),
(129,25,64,'1/4',NULL,NULL),
(130,25,66,'2 cucharadas',NULL,NULL),
(131,26,82,'2 rebanadas',NULL,NULL),
(132,26,74,'2 rebanadas',NULL,NULL),
(133,26,75,'2 rebanadas',NULL,NULL),
(134,26,13,'1 cucharada',NULL,NULL),
(135,27,83,'150g',NULL,NULL),
(136,27,84,'100ml',NULL,NULL),
(137,27,74,'2 rebanadas',NULL,NULL),
(138,27,13,'1 cucharada',NULL,NULL),
(139,27,30,'y pimienta al gusto',NULL,NULL),
(140,28,87,'200g',NULL,NULL),
(141,28,11,'3 separados',NULL,NULL),
(142,28,86,'2 cucharadas',NULL,NULL),
(143,28,30,'1 pizca',NULL,NULL),
(144,29,34,'1/2 taza',NULL,NULL),
(145,29,12,'2 tazas',NULL,NULL),
(146,29,86,'1/2 taza',NULL,NULL),
(147,29,88,'1 ramita',NULL,NULL),
(148,29,89,'al gusto, opcional',NULL,NULL),
(149,30,91,'1 lata (395g)',NULL,NULL),
(150,30,92,'1 lata (375ml)',NULL,NULL),
(151,30,11,'4',NULL,NULL),
(152,30,90,'1 cucharadita',NULL,NULL),
(153,30,86,'1/2 taza',NULL,NULL),
(154,31,20,'1 taza',NULL,NULL),
(155,31,19,'1 taza',NULL,NULL),
(156,31,18,'1 cucharada',NULL,NULL),
(157,32,17,'1, maduro',NULL,NULL),
(158,32,15,'1/2 taza',NULL,NULL),
(159,32,88,'1 cucharada',NULL,NULL),
(160,33,85,'2, maduros',NULL,NULL),
(161,33,20,'1/2 taza',NULL,NULL),
(162,33,18,'1 cucharada, opcional',NULL,NULL),
(163,34,86,'2 cucharadas',NULL,NULL),
(164,34,93,'1 cucharada',NULL,NULL),
(165,34,12,'2 cucharadas',NULL,NULL),
(166,34,10,'1 cucharada',NULL,NULL),
(167,34,30,'1 pizca',NULL,NULL),
(168,34,90,'1 pizca',NULL,NULL),
(169,34,97,'2 cucharadas',NULL,NULL),
(170,35,12,'1 taza',NULL,NULL),
(171,35,16,'1 taza, caliente',NULL,NULL),
(172,35,94,'1 sobre, sabor fresa',NULL,NULL),
(173,36,95,'10, tipo maria',NULL,NULL),
(174,36,96,'3 cucharadas',NULL,NULL),
(175,36,87,'100g',NULL,NULL),
(182,37,97,'2 cucharadas',NULL,NULL),
(183,37,86,'1 cucharada',NULL,NULL),
(184,37,59,'1 cucharada',NULL,NULL),
(185,37,12,'1 cucharada',NULL,NULL),
(186,37,10,'1 cucharada',NULL,NULL),
(187,37,98,'1/4 cucharada',NULL,NULL),
(188,38,97,'1 taza',NULL,NULL),
(189,38,11,'1',NULL,NULL),
(190,38,12,'3/4 taza',NULL,NULL),
(191,38,13,'al gusto',NULL,NULL),
(192,38,18,'al gusto',NULL,NULL),
(193,39,46,'1',NULL,NULL),
(194,39,32,'1/4 taza',NULL,NULL),
(195,39,33,'al gusto',NULL,NULL),
(196,40,11,'2',NULL,NULL),
(197,40,74,'2 rebanadas',NULL,NULL),
(198,40,64,'1/4 taza',NULL,NULL),
(199,40,10,'1 cucharada',NULL,NULL),
(200,40,30,'y pimienta, al gusto',NULL,NULL);
/*!40000 ALTER TABLE `receta_ingrediente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recetas`
--

DROP TABLE IF EXISTS `recetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recetas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Imagenes` varchar(255) DEFAULT NULL,
  `Nombre` varchar(200) NOT NULL,
  `Procedimiento` text DEFAULT NULL,
  `TipoC` varchar(50) DEFAULT NULL,
  `precio_platillo` decimal(10,2) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recetas`
--

LOCK TABLES `recetas` WRITE;
/*!40000 ALTER TABLE `recetas` DISABLE KEYS */;
INSERT INTO `recetas` VALUES
(1,'','Chilaquiles Verdes','1. Hierve tomates, chile y ajo en agua por 5 min. Licúa con cilantro y sal.\n2. En sartén con aceite, cocínala 3 min hasta espesar ligeramente.\n3. Mezcla los totopos con la salsa caliente.\n4. Añade pollo, crema, queso y cebolla.','Desayuno',NULL,NULL),
(2,'','Huevos Revueltos con Pan Tostado','1. Bate los huevos con leche, sal y pimienta.\n2. Derrite la mantequilla en una sartén y cocina los huevos revolviendo hasta que estén cocidos.\n3. Tuesta el pan y sirve con los huevos.','Desayuno',NULL,NULL),
(3,'','Avena con Plátano y Miel','1. Cocina la avena con leche (o agua) hasta que espese.\n2. Sirve en un tazón y agrega plátano y miel.','Desayuno',NULL,NULL),
(4,'','Yogur con Granola y Frutas','1. Poner yogur en tazón.\n2. Añadir granola y frutas.\n3. Endulzar con un poco de miel al gusto.','Desayuno',NULL,NULL),
(5,'','Tostadas con Aguacate y Huevo Pochado','1. Tostar pan y untar aguacate machacado.\n2. Vierte los huevos en agua hirviendo con vinagre por no más de 5 minutos.\n3. Colocar sobre tostadas y salpimentar al gusto.','Desayuno',NULL,NULL),
(6,'','Smoothie de Frutas','1. Licuar todos los ingredientes en la licuadora hasta obtener mezcla homogénea.\n2. Servir frío y decorar con fruta por encima al gusto.','Desayuno',NULL,NULL),
(7,'','Chilaquiles Rojos','1. Cocina los tomates, la cebolla y los chiles en agua hasta que estén suaves (10-15 minutos).\n2. Escurre y licúa los ingredientes hasta obtener una salsa suave. Agrega sal al gusto.\n3. Calienta un poco de aceite en una olla, vierte la salsa y cocina a fuego lento por 10 minutos.\n4. Agrega los totopos a la salsa y mezcla suavemente hasta que estén cubiertos.\n5. Sirve en platos y agrega queso fresco, crema, cebolla y aguacate al gusto.','Desayuno',NULL,NULL),
(8,'','Molletes','1. Parte los bolillos por la mitad y retírales el migajón.\n2. Embárrales frijoles por toda la superficie.\n3. Agrega queso encima.\n4. Hornea a fuego bajo hasta que el queso se derrita.','Comida',NULL,NULL),
(9,'','Arroz con Pollo y Verduras','1. Sofreír pollo con aceite hasta dorar.\n2. Añadir arroz, verduras y agua.\n3. Cocinar a fuego bajo 20 min.\n4. Añadir el huevo cocido al final.','Comida',NULL,NULL),
(10,'','Pasta con Atún y Tomate','1. Cocer la pasta en agua.\n2. Mezclar con atún y salsa.\n3. Calentar y espolvorear orégano.','Comida',NULL,NULL),
(11,'','Ensalada César con Pollo','1. Cocina el pollo a la plancha.\n2. Córtalo en cubos.\n3. Mezcla con el resto de ingredientes.','Comida',NULL,NULL),
(12,'','Quesadillas de Carne','1. Cocina la carne con sal y pimienta.\n2. Rellena las tortillas con carne y queso.\n3. Calienta en sartén plano.','Comida',NULL,NULL),
(13,'','Sopa de Lentejas','1. Hervir todo por 25-30 minutos.','Comida',NULL,NULL),
(14,'','Tacos de Carne Molida','1. Dorar carne con cebolla.\n2. Servir en tortillas con salsa.','Comida',NULL,NULL),
(15,'','Pollo al Horno con Papas','1. Mezclar ingredientes.\n2. Hornear 40 min a 180°C.','Comida',NULL,NULL),
(16,'','Espagueti a la Boloñesa','1. Cocinar pasta.\n2. Mezclar con carne y salsa.','Comida',NULL,NULL),
(17,'','Filete de Pescado a la Plancha','1. Marinar con limón, sal y pimienta.\n2. Cocinar 3-4 min por lado.','Comida',NULL,NULL),
(18,'','Ensalada de Pollo y Espinaca','1. Asa el pollo con sal y pimienta.\n2. Corta y mezcla con los demás ingredientes.','Cena',NULL,NULL),
(19,'','Omelette de Queso y Verduras','1. Bate los huevos, añade sal.\n2. Sofríe las verduras.\n3. Agrega el huevo y queso, cocina hasta que cuaje.','Cena',NULL,NULL),
(20,'','Quesadillas de Pollo','1. Coloca pollo, queso y cebolla entre tortillas.\n2. Cocina en sartén con aceite hasta dorar.','Cena',NULL,NULL),
(21,'','Tacos de Atún','1. Mezcla atún con cebolla, tomate, limón y sal.\n2. Sirve en tortillas con cilantro.','Cena',NULL,NULL),
(22,'','Arroz con Verduras y Huevo','1. Saltea ajo y verduras.\n2. Añade arroz y huevo batido.\n3. Cocina hasta que el huevo esté cocido.\n4. Agrega salsa de soja al gusto.','Cena',NULL,NULL),
(23,'','Panini de Jamón y Queso','1. Abre el pan, coloca jamón y queso.\n2. Úntalo con mantequilla y cocina hasta dorar.','Cena',NULL,NULL),
(24,'','Pollo al Limón con Papas','1. Marina el pollo con los ingredientes.\n2. Hornea junto con las papas a 200°C por 25–30 min.','Cena',NULL,NULL),
(25,'','Tostadas de Atún Gratinadas','1. Mezcla atún, mayonesa y cebolla.\n2. Coloca sobre las tostadas.\n3. Espolvorea queso y gratina 5–7 min.','Cena',NULL,NULL),
(26,'','Sándwich Caliente de Jamón y Queso','1. Arma el sándwich.\n2. Unta mantequilla por fuera.\n3. Dora en sartén hasta derretir el queso.','Cena',NULL,NULL),
(27,'','Pasta con Crema y Jamón','1. Derrite mantequilla y añade jamón.\n2. Agrega la crema, sal y pimienta.\n3. Mezcla con la pasta caliente.','Cena',NULL,NULL),
(28,'','Mousse de chocolate rápido','1. Derrite el chocolate.\\n2. Bate las yemas con el chocolate tibio.\\n3. Bate las claras con azúcar y sal hasta punto de nieve.\\n4. Mezcla y refrigera 2 hrs.','Postre',NULL,NULL),
(29,'','Arroz con leche','1. Cocina el arroz en agua 10 min, cuela.\\n2. Añade leche, azúcar y canela.\\n3. Cocina hasta espesar.\\n4. Enfría.','Postre',NULL,NULL),
(30,'','Flan casero sin horno','1. Haz el caramelo y vierte en molde.\\n2. Licua el resto y vierte.\\n3. Cocina a baño María en la estufa 50 min.\\n4. Enfría.','Postre',NULL,NULL),
(31,'','Yogur con frutas y miel','1. Sirve el yogur en vaso.\\n2. Añade frutas en capas y termina con miel por encima.','Postre',NULL,NULL),
(32,'','Galletas de avena con plátano','1. Tritura el plátano, mezcla con avena y canela.\\n2. Forma galletas y hornea 12–15 min a 180 °C.','Postre',NULL,NULL),
(33,'','Helado casero de mango','1. Licúa todo, vierte en moldes.\\n2. Congela 4 horas.','Postre',NULL,NULL),
(34,'','Brownie en taza','1. Mezcla en una taza y microondas 1 minuto.\\n2. Deja reposar antes de comer.','Postre',NULL,NULL),
(35,'','Gelatina de leche y fresa','1. Disuelve la gelatina en el agua caliente, añade la leche.\\n2. Mezcla y refrigera 3–4 horas.','Postre',NULL,NULL),
(36,'','Trufas de galleta','1. Tritura las galletas, mezcla con queso crema, forma bolitas.\\n2. Refrigera 15 min, cubre con chocolate derretido y enfría.','Postre',NULL,NULL),
(37,'','Pastel de limón','1. Mezcla todo en una taza, microondas 1 minuto.\\n2. Espolvorea ralladura de limón.','Postre',NULL,NULL),
(38,NULL,'Panqueques con miel','1.	Mezclar harina, huevo, un poco de mantequilla y leche. \\n\r\n2.	Cocinar en sartén antiadherente con un poco de mantequilla. \\n\r\n3.	Servir con miel o leche condensada.','Desayuno',NULL,NULL),
(39,NULL,'Burrito de frijoles y queso','1.	Calentar frijoles y esparcir una cantidad razonable sobre la tortilla. \\n\r\n2.	Añadir queso manchego a gusto, enrollar dejando el relleno en el centro y calentar en un sartén plano.\r\n','Desayuno',NULL,NULL),
(40,NULL,'Omelette de jamon y queso','1.	Batir huevos con sal/pimienta. \\n\r\n2.	Cocinar en sartén con 1 cucharada de aceite. \\n\r\n3.	Cuando esté casi cocinado por completo añadir jamón y queso y doblar.\r\n','Desayuno',NULL,NULL);
/*!40000 ALTER TABLE `recetas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('jCrMVO1fFlLxcN5TTTNL8sjgTdRTfzlmvhrjldog',5,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTTlhMUYxNzBEalNmRUlNM2I0dFJNOTloc1lsQkVFUXpFRnkybFgwNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9jb2NpbmEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=',1791929087),
('yEKoCQKSzcfCLrEywJGKOr21Int1sQErulBiXRI3',5,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSDl6S003dGlVcFA1YW1lNWo1MndxWm5WQmtoelpBbWJKcnpUVkg0SyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jb21hbmRhcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==',1792387613);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `codigo_empleado` varchar(6) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('cocinero','mesero','capitan','almacen','cajero','admin','sistemas') DEFAULT NULL,
  `status` enum('pendiente','activo','inactivo') NOT NULL DEFAULT 'pendiente',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `password_reset_requested_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_codigo_empleado_unique` (`codigo_empleado`),
  KEY `users_approved_by_foreign` (`approved_by`),
  CONSTRAINT `users_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'miguel','100002','malvarado041@gmail.com','cocinero','activo',NULL,'$2y$10$Edf6MrBFqmf9uVVAck.2oedBB3uDCHHL9CaBtcVfTeNyTCb/LXWJ.',NULL,'2026-09-26 05:09:48','2026-09-26 08:09:13',NULL,NULL,NULL,NULL),
(2,'mitch','100003','mitch@gmail.com','cocinero','activo',NULL,'$2y$10$mrpbjUizeAo420.JD2jgWOPh3csTzv4gPlZkzgiNL57skOf/ZmDdC',NULL,'2026-09-26 05:09:48','2026-10-06 09:32:05',NULL,NULL,NULL,NULL),
(4,'Administrador','100005','admin@cocinalab.com','admin','activo',NULL,'$2y$12$iaUe69lv/WOxcg2Hf3q/iOMDHEZFWQQENevpQVcCp2rBQE5zNxjci',NULL,'2026-10-19 12:33:06','2026-10-19 12:33:06',NULL,NULL,NULL,NULL),
(5,'aldahir','100006','vicente@gmail.com','sistemas','activo',NULL,'$2y$12$MvPMP.nmq11MR0Q0LplyGej4uHD8ap5gfRn8BXvSlHURdIuCbmCmK',NULL,'2026-09-26 05:12:32','2026-09-29 02:37:56',NULL,NULL,NULL,NULL),
(7,'testuser2','100008','testuser2@example.com','mesero','activo',NULL,'$2y$12$T4yWkcMU6kG7idEv7nMjU.TWr20RlKD1.3cVRKqTdodey6itlF8se',NULL,'2026-09-26 05:15:50','2026-10-14 04:21:38',5,'2026-09-26 05:28:54',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-18 22:33:14
