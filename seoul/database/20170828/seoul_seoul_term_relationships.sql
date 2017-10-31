-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 1.234.83.234    Database: seoul
-- ------------------------------------------------------
-- Server version	5.7.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `seoul_term_relationships`
--

DROP TABLE IF EXISTS `seoul_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seoul_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seoul_term_relationships`
--

LOCK TABLES `seoul_term_relationships` WRITE;
/*!40000 ALTER TABLE `seoul_term_relationships` DISABLE KEYS */;
INSERT INTO `seoul_term_relationships` VALUES (1,1,0),(18,15,0),(19,15,0),(22,15,0),(23,16,0),(103,17,0),(131,18,0),(299,19,0),(350,20,0),(771,21,0),(931,3,0),(931,5,0),(931,6,0),(931,10,0),(931,13,0),(933,2,0),(933,3,0),(933,5,0),(933,6,0),(933,9,0),(933,14,0),(935,3,0),(935,5,0),(935,6,0),(935,11,0),(935,13,0),(935,14,0),(940,22,0),(955,2,0),(955,3,0),(955,5,0),(955,7,0),(955,8,0),(955,14,0),(966,2,0),(966,3,0),(966,4,0),(966,6,0),(966,9,0),(966,12,0),(966,14,0),(968,2,0),(968,3,0),(968,4,0),(968,7,0),(968,8,0),(968,11,0),(970,3,0),(970,5,0),(970,6,0),(970,11,0),(970,12,0),(970,14,0),(972,3,0),(972,4,0),(972,5,0),(972,8,0),(972,13,0),(972,14,0),(974,2,0),(974,3,0),(974,6,0),(974,9,0),(974,14,0),(1104,15,0),(1107,15,0),(1150,15,0),(1151,15,0),(1152,15,0),(1153,15,0),(1154,15,0),(1155,15,0),(1156,15,0),(1157,15,0),(1158,15,0),(1159,15,0),(1160,15,0),(1161,15,0),(1162,15,0),(1163,15,0),(1167,23,0),(1167,24,0);
/*!40000 ALTER TABLE `seoul_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-28 15:19:31
