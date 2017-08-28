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
-- Table structure for table `seoul_kboard_board_content`
--

DROP TABLE IF EXISTS `seoul_kboard_board_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seoul_kboard_board_content` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `board_id` bigint(20) unsigned NOT NULL,
  `parent_uid` bigint(20) unsigned DEFAULT NULL,
  `member_uid` bigint(20) unsigned DEFAULT NULL,
  `member_display` varchar(127) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `title` varchar(127) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `date` char(14) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `update` char(14) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `view` int(10) unsigned DEFAULT NULL,
  `comment` int(10) unsigned DEFAULT NULL,
  `like` int(10) unsigned DEFAULT NULL,
  `unlike` int(10) unsigned DEFAULT NULL,
  `vote` int(11) DEFAULT NULL,
  `thumbnail_file` varchar(127) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `thumbnail_name` varchar(127) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `category1` varchar(127) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `category2` varchar(127) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `secret` varchar(5) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `notice` varchar(5) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `search` char(1) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `password` varchar(127) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `board_id` (`board_id`),
  KEY `parent_uid` (`parent_uid`),
  KEY `member_uid` (`member_uid`),
  KEY `date` (`date`),
  KEY `update` (`update`),
  KEY `view` (`view`),
  KEY `vote` (`vote`),
  KEY `category1` (`category1`),
  KEY `category2` (`category2`),
  KEY `notice` (`notice`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seoul_kboard_board_content`
--

LOCK TABLES `seoul_kboard_board_content` WRITE;
/*!40000 ALTER TABLE `seoul_kboard_board_content` DISABLE KEYS */;
INSERT INTO `seoul_kboard_board_content` VALUES (1,2,0,1,'seoul','세미나 및 행사 게시판입니다.','테스트 글 입니다.','20170725153218','20170725153700',2,0,0,0,0,'','','','','','true','1','',''),(2,2,0,1,'seoul','월간 행사 입니다','테스트 글 입니다.','20170725153243','20170725153700',1,0,0,0,0,'','','','','','true','1','',''),(3,2,0,1,'seoul','청년주거 사이트를 오픈했습니다.','테스트입니다.','20170725153308','20170824182552',2,1,0,0,0,'','','','','','','1','',''),(4,3,0,1,'seoul','입주자 커뮤니티','테스트 입니다.','20170725153322','20170725153700',1,0,0,0,0,'','','','','','true','1','',''),(5,3,0,1,'seoul','신사동 원룸 오픈했습니다.','테스트 글 입니다.','20170725153428','20170725153700',1,0,0,0,0,'','','','','','','1','',''),(6,3,0,1,'seoul','논현동 투룸 오픈했습니다.','테스트 입니다.','20170725153450','20170725153700',3,0,0,0,0,'','','','','','','1','','');
/*!40000 ALTER TABLE `seoul_kboard_board_content` ENABLE KEYS */;
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
