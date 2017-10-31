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
-- Table structure for table `seoul_kboard_board_setting`
--

DROP TABLE IF EXISTS `seoul_kboard_board_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seoul_kboard_board_setting` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `board_name` varchar(127) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `skin` varchar(127) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `use_comment` varchar(5) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `use_editor` varchar(5) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `permission_read` varchar(127) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `permission_write` varchar(127) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `admin_user` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `use_category` varchar(5) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `category1_list` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `category2_list` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `page_rpp` int(10) unsigned NOT NULL,
  `created` char(14) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seoul_kboard_board_setting`
--

LOCK TABLES `seoul_kboard_board_setting` WRITE;
/*!40000 ALTER TABLE `seoul_kboard_board_setting` DISABLE KEYS */;
INSERT INTO `seoul_kboard_board_setting` VALUES (2,'세미나 및 행사','default','yes','yes','all','editor','','','','',10,'20170725103237'),(3,'입주자 커뮤니티','default','yes','yes','all','editor','','','','',10,'20170725103414');
/*!40000 ALTER TABLE `seoul_kboard_board_setting` ENABLE KEYS */;
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
