/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: login
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
-- Table structure for table `ingredientes`
--

DROP TABLE IF EXISTS `ingredientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingredientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(200) NOT NULL,
  `Tipo` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredientes`
--

LOCK TABLES `ingredientes` WRITE;
/*!40000 ALTER TABLE `ingredientes` DISABLE KEYS */;
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (1,'tomate verde','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (2,'chile serrano','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (3,'diente de ajo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (4,'cilantro','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (5,'totopos','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (6,'pechuga de pollo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (7,'crema ácida','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (8,'queso fresco','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (9,'cebolla morada','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (10,'aceite','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (11,'huevo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (12,'leche','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (13,'mantequilla','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (14,'pan','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (15,'avena','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (16,'agua','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (17,'plátano','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (18,'miel','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (19,'Fruta','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (20,'yogurth natural','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (21,'granola','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (22,'fresas','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (23,'aguacate','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (24,'pan integral','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (25,'yogur','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (26,'tomate rojo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (27,'cebolla blanca','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (28,'dientes de ajo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (29,'chiles serranos','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (30,'Sal','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (31,'bolillo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (32,'Frijoles refritos','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (33,'Queso manchego','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (34,'arroz','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (35,'pechuga pollo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (36,'zanahoria','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (37,'elote','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (38,'chícharos','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (39,'pasta','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (40,'atún en agua','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (41,'salsa de tomate','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (42,'Orégano','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (43,'lechuga romana','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (44,'aderezo César','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (45,'crotones','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (46,'tortilla de harina','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (47,'carne','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (48,'lentejas','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (49,'cebolla','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (50,'caldo de pollo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (51,'carne molida','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (52,'tortilla','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (53,'Salsa al gusto','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (54,'muslo de pollo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (55,'papas','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (56,'aceite de oliva','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (57,'espagueti','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (58,'filete pescado','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (59,'Jugo de limón','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (60,'espinaca fresca','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (61,'tomate cherry','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (62,'vinagre balsámico','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (63,'pimiento rojo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (64,'queso rallado','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (65,'pollo cocido','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (66,'cebolla picada','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (67,'tortilla de maíz','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (68,'tomate','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (69,'arvejas','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (70,'arroz cocido','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (71,'diente ajo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (72,'soja','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (73,'baguette','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (74,'jamón','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (75,'queso','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (76,'ciabatta','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (77,'papa','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (78,'ajo en polvo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (79,'tostada de maíz','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (80,'atún escurrido','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (81,'mayonesa','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (82,'pan de caja','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (83,'pasta cocida','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (84,'crema','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (85,'mango','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (86,'Azucar','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (87,'Chocolate semiamargo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (88,'Canela','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (89,'Cascara de citrico','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (90,'Vainilla','\" \" ');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (91,'Leche condensada','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (92,'Leche evaporada','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (93,'Cacao en polvo','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (94,'Gelatina','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (95,'Galleta','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (96,'Queso crema','\" \" ');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (97,'Harina','\" \"');
INSERT INTO `ingredientes` (`id`, `Nombre`, `Tipo`) VALUES (98,'Polvo para hornear','\" \"');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2026_09_24_042022_create_cocinalab_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2026_09_25_220411_add_role_and_status_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2026_09_25_223210_add_cajero_role_to_users_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2026_09_26_010720_add_codigo_empleado_to_users_table',3);
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
  PRIMARY KEY (`id`),
  KEY `receta_ingrediente_receta_id_foreign` (`receta_id`),
  KEY `receta_ingrediente_ingrediente_id_foreign` (`ingrediente_id`),
  CONSTRAINT `receta_ingrediente_ingrediente_id_foreign` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `receta_ingrediente_receta_id_foreign` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receta_ingrediente`
--

LOCK TABLES `receta_ingrediente` WRITE;
/*!40000 ALTER TABLE `receta_ingrediente` DISABLE KEYS */;
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (1,1,1,'3');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (2,1,2,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (3,1,71,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (4,1,4,'3 cucharadas de cilantro picado');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (5,1,5,'2 tazas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (6,1,6,'1, aunque esto es opcional');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (7,1,7,'2 cucharadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (8,1,8,'50g');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (9,1,9,'1/4 en aros');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (10,1,10,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (11,2,11,'2');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (12,2,12,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (13,2,13,'1 cucharadita');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (14,2,82,'1 rebanada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (15,3,15,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (16,3,12,'1 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (17,3,17,'1 en rodajas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (18,3,18,'1 cucharadita');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (19,3,19,'al gusto, opcional');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (20,4,20,'1 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (21,4,21,'1/4 de taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (22,4,22,'1/2 taza picadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (23,4,18,'1 cucharadita, opcional');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (24,5,23,'1 en rodajas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (25,5,11,'2 ');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (26,5,24,'2 rebanadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (27,5,30,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (28,6,17,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (29,6,22,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (30,6,12,'1 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (31,6,18,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (32,7,5,'1 bolsa, alrededor de 400-500g');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (33,7,26,'de 6 a 8');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (34,7,27,'1/4');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (35,7,3,'2');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (36,7,29,'1-2 opcional');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (37,7,30,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (38,7,8,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (39,7,7,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (40,7,9,'1/4, opcional');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (41,7,23,'1, opcional');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (42,8,31,'1 partido en 2 rebanadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (43,8,32,'50-70g');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (44,8,33,'40-40 gramos rallados');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (45,9,34,'1 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (46,9,6,'1 en cubos');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (47,9,11,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (48,9,36,'1/6 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (49,9,37,'1/6 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (50,9,38,'1/6 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (51,9,10,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (52,9,16,'2 tazas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (53,10,39,'1 taza ');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (54,10,40,'1 lata');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (55,10,41,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (56,10,42,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (57,11,43,'2 tazas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (58,11,6,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (59,11,10,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (60,11,44,'2 cucharadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (61,11,45,'1/4 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (62,12,46,'3');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (63,12,47,'250g');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (64,12,30,'y pimienta al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (65,12,33,'60-80g o 1/2-2/3 de taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (66,13,48,'1 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (67,13,66,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (68,13,36,'1 partida en cubos');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (69,13,50,'2 tazas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (70,14,51,'200g');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (71,14,67,'4');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (72,14,66,'1/4');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (73,14,53,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (74,15,54,'2');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (75,15,77,'2 partidas en cubos');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (76,15,56,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (77,15,30,'y pimienta al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (78,16,57,'200g');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (79,16,51,'1/2 taza ya cocida');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (80,16,41,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (81,17,58,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (82,17,59,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (83,17,30,'y pimienta al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (84,18,6,'1 (150g)');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (85,18,60,'2 tazas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (86,18,61,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (87,18,9,'1/4');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (88,18,56,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (89,18,62,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (90,18,30,'y pimienta al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (91,19,11,'3');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (92,19,63,'1/4 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (93,19,66,'1/4 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (94,19,64,'1/4 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (95,19,56,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (96,19,30,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (97,20,46,'2');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (98,20,6,'100g');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (99,20,64,'1/2');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (100,20,66,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (101,20,10,'1 cucharadita');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (102,21,40,'1 lata (120g)');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (103,21,67,'2');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (104,21,66,'1/4');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (105,21,68,'1/2');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (106,21,59,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (107,21,30,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (108,21,4,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (109,22,70,'1 taza ');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (110,22,11,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (111,22,36,'1/4 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (112,22,69,'1/4 taza ');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (113,22,71,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (114,22,10,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (115,22,72,'al gusto, opcional');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (116,23,76,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (117,23,74,'2 rebanadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (118,23,75,'2 rebanadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (119,23,13,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (120,24,6,'1 (150g)');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (121,24,77,'1 mediana, en cubos');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (122,24,59,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (123,24,78,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (124,24,56,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (125,24,30,'y pimienta al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (126,25,79,'2');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (127,25,80,'1 lata (120g)');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (128,25,81,'1/4 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (129,25,64,'1/4');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (130,25,66,'2 cucharadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (131,26,82,'2 rebanadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (132,26,74,'2 rebanadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (133,26,75,'2 rebanadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (134,26,13,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (135,27,83,'150g');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (136,27,84,'100ml');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (137,27,74,'2 rebanadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (138,27,13,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (139,27,30,'y pimienta al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (140,28,87,'200g');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (141,28,11,'3 separados');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (142,28,86,'2 cucharadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (143,28,30,'1 pizca');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (144,29,34,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (145,29,12,'2 tazas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (146,29,86,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (147,29,88,'1 ramita');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (148,29,89,'al gusto, opcional');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (149,30,91,'1 lata (395g)');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (150,30,92,'1 lata (375ml)');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (151,30,11,'4');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (152,30,90,'1 cucharadita');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (153,30,86,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (154,31,20,'1 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (155,31,19,'1 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (156,31,18,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (157,32,17,'1, maduro');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (158,32,15,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (159,32,88,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (160,33,85,'2, maduros');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (161,33,20,'1/2 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (162,33,18,'1 cucharada, opcional');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (163,34,86,'2 cucharadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (164,34,93,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (165,34,12,'2 cucharadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (166,34,10,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (167,34,30,'1 pizca');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (168,34,90,'1 pizca');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (169,34,97,'2 cucharadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (170,35,12,'1 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (171,35,16,'1 taza, caliente');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (172,35,94,'1 sobre, sabor fresa');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (173,36,95,'10, tipo maria');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (174,36,96,'3 cucharadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (175,36,87,'100g');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (182,37,97,'2 cucharadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (183,37,86,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (184,37,59,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (185,37,12,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (186,37,10,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (187,37,98,'1/4 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (188,38,97,'1 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (189,38,11,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (190,38,12,'3/4 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (191,38,13,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (192,38,18,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (193,39,46,'1');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (194,39,32,'1/4 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (195,39,33,'al gusto');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (196,40,11,'2');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (197,40,74,'2 rebanadas');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (198,40,64,'1/4 taza');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (199,40,10,'1 cucharada');
INSERT INTO `receta_ingrediente` (`id`, `receta_id`, `ingrediente_id`, `cantidad`) VALUES (200,40,30,'y pimienta, al gusto');
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recetas`
--

LOCK TABLES `recetas` WRITE;
/*!40000 ALTER TABLE `recetas` DISABLE KEYS */;
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (1,'','Chilaquiles Verdes','1. Hierve tomates, chile y ajo en agua por 5 min. Licúa con cilantro y sal.\n2. En sartén con aceite, cocínala 3 min hasta espesar ligeramente.\n3. Mezcla los totopos con la salsa caliente.\n4. Añade pollo, crema, queso y cebolla.','Desayuno');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (2,'','Huevos Revueltos con Pan Tostado','1. Bate los huevos con leche, sal y pimienta.\n2. Derrite la mantequilla en una sartén y cocina los huevos revolviendo hasta que estén cocidos.\n3. Tuesta el pan y sirve con los huevos.','Desayuno');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (3,'','Avena con Plátano y Miel','1. Cocina la avena con leche (o agua) hasta que espese.\n2. Sirve en un tazón y agrega plátano y miel.','Desayuno');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (4,'','Yogur con Granola y Frutas','1. Poner yogur en tazón.\n2. Añadir granola y frutas.\n3. Endulzar con un poco de miel al gusto.','Desayuno');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (5,'','Tostadas con Aguacate y Huevo Pochado','1. Tostar pan y untar aguacate machacado.\n2. Vierte los huevos en agua hirviendo con vinagre por no más de 5 minutos.\n3. Colocar sobre tostadas y salpimentar al gusto.','Desayuno');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (6,'','Smoothie de Frutas','1. Licuar todos los ingredientes en la licuadora hasta obtener mezcla homogénea.\n2. Servir frío y decorar con fruta por encima al gusto.','Desayuno');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (7,'','Chilaquiles Rojos','1. Cocina los tomates, la cebolla y los chiles en agua hasta que estén suaves (10-15 minutos).\n2. Escurre y licúa los ingredientes hasta obtener una salsa suave. Agrega sal al gusto.\n3. Calienta un poco de aceite en una olla, vierte la salsa y cocina a fuego lento por 10 minutos.\n4. Agrega los totopos a la salsa y mezcla suavemente hasta que estén cubiertos.\n5. Sirve en platos y agrega queso fresco, crema, cebolla y aguacate al gusto.','Desayuno');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (8,'','Molletes','1. Parte los bolillos por la mitad y retírales el migajón.\n2. Embárrales frijoles por toda la superficie.\n3. Agrega queso encima.\n4. Hornea a fuego bajo hasta que el queso se derrita.','Comida');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (9,'','Arroz con Pollo y Verduras','1. Sofreír pollo con aceite hasta dorar.\n2. Añadir arroz, verduras y agua.\n3. Cocinar a fuego bajo 20 min.\n4. Añadir el huevo cocido al final.','Comida');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (10,'','Pasta con Atún y Tomate','1. Cocer la pasta en agua.\n2. Mezclar con atún y salsa.\n3. Calentar y espolvorear orégano.','Comida');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (11,'','Ensalada César con Pollo','1. Cocina el pollo a la plancha.\n2. Córtalo en cubos.\n3. Mezcla con el resto de ingredientes.','Comida');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (12,'','Quesadillas de Carne','1. Cocina la carne con sal y pimienta.\n2. Rellena las tortillas con carne y queso.\n3. Calienta en sartén plano.','Comida');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (13,'','Sopa de Lentejas','1. Hervir todo por 25-30 minutos.','Comida');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (14,'','Tacos de Carne Molida','1. Dorar carne con cebolla.\n2. Servir en tortillas con salsa.','Comida');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (15,'','Pollo al Horno con Papas','1. Mezclar ingredientes.\n2. Hornear 40 min a 180°C.','Comida');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (16,'','Espagueti a la Boloñesa','1. Cocinar pasta.\n2. Mezclar con carne y salsa.','Comida');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (17,'','Filete de Pescado a la Plancha','1. Marinar con limón, sal y pimienta.\n2. Cocinar 3-4 min por lado.','Comida');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (18,'','Ensalada de Pollo y Espinaca','1. Asa el pollo con sal y pimienta.\n2. Corta y mezcla con los demás ingredientes.','Cena');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (19,'','Omelette de Queso y Verduras','1. Bate los huevos, añade sal.\n2. Sofríe las verduras.\n3. Agrega el huevo y queso, cocina hasta que cuaje.','Cena');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (20,'','Quesadillas de Pollo','1. Coloca pollo, queso y cebolla entre tortillas.\n2. Cocina en sartén con aceite hasta dorar.','Cena');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (21,'','Tacos de Atún','1. Mezcla atún con cebolla, tomate, limón y sal.\n2. Sirve en tortillas con cilantro.','Cena');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (22,'','Arroz con Verduras y Huevo','1. Saltea ajo y verduras.\n2. Añade arroz y huevo batido.\n3. Cocina hasta que el huevo esté cocido.\n4. Agrega salsa de soja al gusto.','Cena');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (23,'','Panini de Jamón y Queso','1. Abre el pan, coloca jamón y queso.\n2. Úntalo con mantequilla y cocina hasta dorar.','Cena');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (24,'','Pollo al Limón con Papas','1. Marina el pollo con los ingredientes.\n2. Hornea junto con las papas a 200°C por 25–30 min.','Cena');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (25,'','Tostadas de Atún Gratinadas','1. Mezcla atún, mayonesa y cebolla.\n2. Coloca sobre las tostadas.\n3. Espolvorea queso y gratina 5–7 min.','Cena');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (26,'','Sándwich Caliente de Jamón y Queso','1. Arma el sándwich.\n2. Unta mantequilla por fuera.\n3. Dora en sartén hasta derretir el queso.','Cena');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (27,'','Pasta con Crema y Jamón','1. Derrite mantequilla y añade jamón.\n2. Agrega la crema, sal y pimienta.\n3. Mezcla con la pasta caliente.','Cena');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (28,'','Mousse de chocolate rápido','1. Derrite el chocolate.\\n2. Bate las yemas con el chocolate tibio.\\n3. Bate las claras con azúcar y sal hasta punto de nieve.\\n4. Mezcla y refrigera 2 hrs.','Postre');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (29,'','Arroz con leche','1. Cocina el arroz en agua 10 min, cuela.\\n2. Añade leche, azúcar y canela.\\n3. Cocina hasta espesar.\\n4. Enfría.','Postre');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (30,'','Flan casero sin horno','1. Haz el caramelo y vierte en molde.\\n2. Licua el resto y vierte.\\n3. Cocina a baño María en la estufa 50 min.\\n4. Enfría.','Postre');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (31,'','Yogur con frutas y miel','1. Sirve el yogur en vaso.\\n2. Añade frutas en capas y termina con miel por encima.','Postre');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (32,'','Galletas de avena con plátano','1. Tritura el plátano, mezcla con avena y canela.\\n2. Forma galletas y hornea 12–15 min a 180 °C.','Postre');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (33,'','Helado casero de mango','1. Licúa todo, vierte en moldes.\\n2. Congela 4 horas.','Postre');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (34,'','Brownie en taza','1. Mezcla en una taza y microondas 1 minuto.\\n2. Deja reposar antes de comer.','Postre');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (35,'','Gelatina de leche y fresa','1. Disuelve la gelatina en el agua caliente, añade la leche.\\n2. Mezcla y refrigera 3–4 horas.','Postre');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (36,'','Trufas de galleta','1. Tritura las galletas, mezcla con queso crema, forma bolitas.\\n2. Refrigera 15 min, cubre con chocolate derretido y enfría.','Postre');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (37,'','Pastel de limón','1. Mezcla todo en una taza, microondas 1 minuto.\\n2. Espolvorea ralladura de limón.','Postre');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (38,NULL,'Panqueques con miel','1.	Mezclar harina, huevo, un poco de mantequilla y leche. \\n\r\n2.	Cocinar en sartén antiadherente con un poco de mantequilla. \\n\r\n3.	Servir con miel o leche condensada.','Desayuno');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (39,NULL,'Burrito de frijoles y queso','1.	Calentar frijoles y esparcir una cantidad razonable sobre la tortilla. \\n\r\n2.	Añadir queso manchego a gusto, enrollar dejando el relleno en el centro y calentar en un sartén plano.\r\n','Desayuno');
INSERT INTO `recetas` (`id`, `Imagenes`, `Nombre`, `Procedimiento`, `TipoC`) VALUES (40,NULL,'Omelette de jamon y queso','1.	Batir huevos con sal/pimienta. \\n\r\n2.	Cocinar en sartén con 1 cucharada de aceite. \\n\r\n3.	Cuando esté casi cocinado por completo añadir jamón y queso y doblar.\r\n','Desayuno');
/*!40000 ALTER TABLE `recetas` ENABLE KEYS */;
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
  `role` enum('cocinero','mesero','capitan','almacen','cajero','admin') DEFAULT NULL,
  `status` enum('pendiente','activo','inactivo') NOT NULL DEFAULT 'pendiente',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
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
INSERT INTO `users` (`id`, `name`, `codigo_empleado`, `email`, `role`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `approved_by`, `approved_at`) VALUES (1,'miguel','100002','malvarado041@gmail.com','cocinero','activo',NULL,'$2y$10$Edf6MrBFqmf9uVVAck.2oedBB3uDCHHL9CaBtcVfTeNyTCb/LXWJ.',NULL,'2026-09-26 05:09:48','2026-09-26 08:09:13',NULL,NULL);
INSERT INTO `users` (`id`, `name`, `codigo_empleado`, `email`, `role`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `approved_by`, `approved_at`) VALUES (2,'mitch','100003','mitch@gmail.com','cocinero','activo',NULL,'$2y$10$mrpbjUizeAo420.JD2jgWOPh3csTzv4gPlZkzgiNL57skOf/ZmDdC',NULL,'2026-09-26 05:09:48','2026-09-26 08:09:13',NULL,NULL);
INSERT INTO `users` (`id`, `name`, `codigo_empleado`, `email`, `role`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `approved_by`, `approved_at`) VALUES (4,'Administrador','100005','admin@cocinalab.com','admin','activo',NULL,'$2y$12$QA1li5tRWeoB3jV2Yd5gqu3xoasyjfJKNLkEFW4uB60kbwA4HSCHu','9W7ZKRhF7sRVzvpeSmJhlTpu80NTvm31cHV7NdxOCYkSf8uq22GYoXOE1vF7','2026-09-26 05:09:48','2026-09-26 08:09:13',NULL,NULL);
INSERT INTO `users` (`id`, `name`, `codigo_empleado`, `email`, `role`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `approved_by`, `approved_at`) VALUES (5,'aldahir','100006','vicente@gmail.com','admin','activo',NULL,'$2y$12$MvPMP.nmq11MR0Q0LplyGej4uHD8ap5gfRn8BXvSlHURdIuCbmCmK','7PsD7qejH7YJsBpwWQvbXHlzKYdZ4BDPiWU8znUDJ8eYvCAjcbhzBlnMnqXY','2026-09-26 05:12:32','2026-09-26 08:09:13',NULL,NULL);
INSERT INTO `users` (`id`, `name`, `codigo_empleado`, `email`, `role`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `approved_by`, `approved_at`) VALUES (7,'testuser2','100008','testuser2@example.com','almacen','activo',NULL,'$2y$12$0gZYAvK1d2QALEXCVSkvi.TANgizpPaHm4pUKSRpk63KuBJHcZPGG',NULL,'2026-09-26 05:15:50','2026-09-26 08:09:13',5,'2026-09-26 05:28:54');
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

-- Dump completed on 2026-09-25 18:23:57
