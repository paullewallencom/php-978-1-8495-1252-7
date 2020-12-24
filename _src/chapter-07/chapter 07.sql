-- MySQL dump 10.13  Distrib 5.1.46, for redhat-linux-gnu (i386)
--
-- Host: localhost    Database: cmsdb
-- ------------------------------------------------------
-- Server version	5.1.46-log

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
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'_superadministrators'),(2,'_administrators');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_comments_comment`
--

DROP TABLE IF EXISTS `page_comments_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_comments_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text,
  `author_name` text,
  `author_email` text,
  `author_website` text,
  `page_id` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_comments_comment`
--

LOCK TABLES `page_comments_comment` WRITE;
/*!40000 ALTER TABLE `page_comments_comment` DISABLE KEYS */;
INSERT INTO `page_comments_comment` VALUES (1,'test','kae','kae@verens.com','http://verens.com',24,'2010-05-20 11:45:01',NULL),(2,'test','kae','kae@verens.com','http://verens.com',24,'2010-05-20 11:45:46',1),(3,'test','kae','kae@verens.com','http://verens.com',24,'2010-05-20 11:48:39',0),(4,'Phasellus congue dui nec diam semper vestibulum. Cras volutpat fringilla orci non tincidunt. Curabitur sed dui iaculis metus mollis feugiat. Suspendisse potenti. Curabitur sit amet leo magna.\r\n\r\nQuisque sodales, mi at egestas gravida, justo tortor faucibus dui, non luctus urna tortor id est.','Kae Verens','kae@verens.com','http://verens.com/',24,'2010-05-20 12:06:24',1);
/*!40000 ALTER TABLE `page_comments_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `body` mediumtext,
  `parent` int(11) DEFAULT '0',
  `ord` int(11) NOT NULL DEFAULT '0',
  `cdate` datetime DEFAULT NULL,
  `special` bigint(20) DEFAULT NULL,
  `edate` datetime DEFAULT NULL,
  `title` text,
  `template` text,
  `type` varchar(64) DEFAULT NULL,
  `keywords` text,
  `description` text,
  `associated_date` date DEFAULT NULL,
  `vars` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (24,'Home','<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam imperdiet aliquet odio, non condimentum ante dignissim at. Morbi elementum mi sed quam porttitor id malesuada massa iaculis. Aliquam in dui ligula, feugiat auctor orci. Aliquam libero libero, imperdiet eu auctor eu, dignissim et arcu. In hac habitasse platea dictumst. Pellentesque leo diam, congue sodales cursus et, ornare eget augue.</p>\r\n',0,0,NULL,1,'2010-05-20 11:12:12','Kae\'s home page','_default','0','webme,cms,jquery,php','an example website','2010-04-28','{\"order_of_sub_pages\":\"0\",\"order_of_sub_pages_dir\":\"0\"}'),(25,'Second Page','<p>A Second Page</p>',0,1,NULL,0,'2010-04-28 21:35:33','',NULL,'0','','','2010-04-28','{\"order_of_sub_pages\":\"0\",\"order_of_sub_pages_dir\":\"0\"}'),(35,'Home2','',0,0,'2010-04-28 22:31:55',0,'2010-04-28 22:31:55','',NULL,'0','','','2010-04-28','{\"order_of_sub_pages\":\"0\",\"order_of_sub_pages_dir\":\"0\"}'),(38,'test3','<br />\r\n',24,0,'2010-04-28 22:46:06',0,'2010-05-14 09:10:10','',NULL,'0','','','2010-04-28','{\"order_of_sub_pages\":\"0\",\"order_of_sub_pages_dir\":\"0\"}'),(36,'test','<br />\r\n',24,0,'2010-04-28 22:44:29',2,'2010-05-14 09:10:17','',NULL,'0','','','2010-04-28','{\"order_of_sub_pages\":\"0\",\"order_of_sub_pages_dir\":\"0\"}');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_accounts`
--

DROP TABLE IF EXISTS `user_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text,
  `password` char(32) DEFAULT NULL,
  `active` smallint(6) DEFAULT '0',
  `groups` text,
  `activation_key` varchar(32) DEFAULT NULL,
  `extras` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_accounts`
--

LOCK TABLES `user_accounts` WRITE;
/*!40000 ALTER TABLE `user_accounts` DISABLE KEYS */;
INSERT INTO `user_accounts` VALUES (2,'kae@verens.com','6d24dde9d56b9eab99a303a713df2891',1,'[\"_superadministrators\"]','5d50e39420127d0bab44a56612f2d89b',NULL),(3,'user@verens.com','e83052ab33df32b94da18f6ff2353e94',1,'[]',NULL,NULL);
/*!40000 ALTER TABLE `user_accounts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-05-20 16:46:58
