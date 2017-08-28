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
-- Table structure for table `seoul_kboard_board_meta`
--

DROP TABLE IF EXISTS `seoul_kboard_board_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seoul_kboard_board_meta` (
  `board_id` bigint(20) unsigned NOT NULL,
  `key` varchar(127) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  UNIQUE KEY `meta_index` (`board_id`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seoul_kboard_board_meta`
--

LOCK TABLES `seoul_kboard_board_meta` WRITE;
/*!40000 ALTER TABLE `seoul_kboard_board_meta` DISABLE KEYS */;
INSERT INTO `seoul_kboard_board_meta` VALUES (2,'add_menu_page','1'),(2,'auto_page','1144'),(2,'comment_skin','default'),(2,'comments_plugin_row','10'),(2,'list_total','3'),(2,'pass_autop','disable'),(2,'permission_comment_write_roles','a:1:{i:0;s:13:\"administrator\";}'),(2,'permission_read_roles','a:1:{i:0;s:13:\"administrator\";}'),(2,'permission_write_roles','a:1:{i:0;s:13:\"administrator\";}'),(2,'permit','1'),(2,'total','3'),(3,'add_menu_page','1'),(3,'auto_page','1146'),(3,'comment_skin','default'),(3,'comments_plugin_row','10'),(3,'list_total','3'),(3,'pass_autop','disable'),(3,'permission_comment_write_roles','a:1:{i:0;s:13:\"administrator\";}'),(3,'permission_read_roles','a:1:{i:0;s:13:\"administrator\";}'),(3,'permission_write_roles','a:1:{i:0;s:13:\"administrator\";}'),(3,'permit','1'),(3,'total','3');
/*!40000 ALTER TABLE `seoul_kboard_board_meta` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-28 15:19:29
