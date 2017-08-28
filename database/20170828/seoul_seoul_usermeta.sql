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
-- Table structure for table `seoul_usermeta`
--

DROP TABLE IF EXISTS `seoul_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seoul_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seoul_usermeta`
--

LOCK TABLES `seoul_usermeta` WRITE;
/*!40000 ALTER TABLE `seoul_usermeta` DISABLE KEYS */;
INSERT INTO `seoul_usermeta` VALUES (1,1,'nickname','seoul'),(2,1,'first_name',''),(3,1,'last_name',''),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'comment_shortcuts','false'),(7,1,'admin_color','fresh'),(8,1,'use_ssl','0'),(9,1,'show_admin_bar_front','true'),(10,1,'locale',''),(11,1,'seoul_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(12,1,'seoul_user_level','10'),(13,1,'dismissed_wp_pointers',''),(14,1,'show_welcome_panel','0'),(16,1,'seoul_dashboard_quick_press_last_post_id','1230'),(17,1,'community-events-location','a:1:{s:2:\"ip\";s:12:\"58.150.169.0\";}'),(18,2,'nickname','admin'),(19,2,'first_name','admin'),(20,2,'last_name',''),(21,2,'description',''),(22,2,'rich_editing','true'),(23,2,'comment_shortcuts','false'),(24,2,'admin_color','fresh'),(25,2,'use_ssl','0'),(26,2,'show_admin_bar_front','true'),(27,2,'locale',''),(28,2,'seoul_capabilities','a:1:{s:10:\"subscriber\";b:1;}'),(29,2,'seoul_user_level','0'),(30,2,'dismissed_wp_pointers',''),(31,1,'closedpostboxes_page','a:0:{}'),(32,1,'metaboxhidden_page','a:6:{i:0;s:24:\"fusion_settings_meta_box\";i:1;s:10:\"postcustom\";i:2;s:16:\"commentstatusdiv\";i:3;s:11:\"commentsdiv\";i:4;s:7:\"slugdiv\";i:5;s:9:\"authordiv\";}'),(33,1,'seoul_user-settings','libraryContent=browse&editor=tinymce&imgsize=thumbnail'),(34,1,'seoul_user-settings-time','1503649198'),(35,1,'nav_menu_recently_edited','15'),(36,1,'managenav-menuscolumnshidden','a:5:{i:0;s:11:\"link-target\";i:1;s:15:\"title-attribute\";i:2;s:11:\"css-classes\";i:3;s:3:\"xfn\";i:4;s:11:\"description\";}'),(37,1,'metaboxhidden_nav-menus','a:12:{i:0;s:20:\"add-post-type-kboard\";i:1;s:29:\"add-post-type-avada_portfolio\";i:2;s:23:\"add-post-type-avada_faq\";i:3;s:33:\"add-post-type-themefusion_elastic\";i:4;s:19:\"add-post-type-slide\";i:5;s:12:\"add-post_tag\";i:6;s:15:\"add-post_format\";i:7;s:22:\"add-portfolio_category\";i:8;s:20:\"add-portfolio_skills\";i:9;s:18:\"add-portfolio_tags\";i:10;s:16:\"add-faq_category\";i:11;s:25:\"add-themefusion_es_groups\";}'),(38,1,'closedpostboxes_dashboard','a:0:{}'),(39,1,'metaboxhidden_dashboard','a:1:{i:0;s:16:\"themefusion_news\";}'),(40,1,'closedpostboxes_settings_page_noakes_menu_manager_settings','a:1:{i:0;s:58:\"noakes_menu_manager_settings_meta_box_nmm_general_settings\";}'),(41,1,'metaboxhidden_settings_page_noakes_menu_manager_settings','a:0:{}'),(42,1,'session_tokens','a:2:{s:64:\"87f8db93d0fb0d93066e024159ff15053103cf8b894201bad63519c83433e8f5\";a:4:{s:10:\"expiration\";i:1504833116;s:2:\"ip\";s:12:\"58.150.169.3\";s:2:\"ua\";s:114:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36\";s:5:\"login\";i:1503623516;}s:64:\"8f2645e9b7a8997593e72a257ffd36f7af921c6042b0e03eb2bb40dd15bf4b71\";a:4:{s:10:\"expiration\";i:1503818658;s:2:\"ip\";s:12:\"58.150.169.3\";s:2:\"ua\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36\";s:5:\"login\";i:1503645858;}}'),(43,1,'closedpostboxes_slide','a:0:{}'),(44,1,'metaboxhidden_slide','a:1:{i:0;s:7:\"slugdiv\";}');
/*!40000 ALTER TABLE `seoul_usermeta` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-28 15:19:27
