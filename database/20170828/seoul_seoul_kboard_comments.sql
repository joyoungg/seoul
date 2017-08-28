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
-- Table structure for table `seoul_kboard_comments`
--

DROP TABLE IF EXISTS `seoul_kboard_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seoul_kboard_comments` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content_uid` bigint(20) unsigned NOT NULL,
  `parent_uid` bigint(20) unsigned DEFAULT NULL,
  `user_uid` bigint(20) unsigned DEFAULT NULL,
  `user_display` varchar(127) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `like` int(10) unsigned DEFAULT NULL,
  `unlike` int(10) unsigned DEFAULT NULL,
  `vote` int(11) DEFAULT NULL,
  `created` char(14) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `password` varchar(127) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `content_uid` (`content_uid`),
  KEY `parent_uid` (`parent_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seoul_kboard_comments`
--

LOCK TABLES `seoul_kboard_comments` WRITE;
/*!40000 ALTER TABLE `seoul_kboard_comments` DISABLE KEYS */;
INSERT INTO `seoul_kboard_comments` VALUES (1,3,0,0,'seoul','안녕하세요',0,0,0,'20170824182552','','seoul12');
/*!40000 ALTER TABLE `seoul_kboard_comments` ENABLE KEYS */;
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
