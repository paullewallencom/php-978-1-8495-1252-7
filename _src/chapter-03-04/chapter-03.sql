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
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (24,'Home','<p>Hello World</p>',0,0,NULL,1,'2010-04-28 21:35:29','Kae\'s home page',NULL,'0','webme,cms,jquery,php','an example website','2010-04-28','{\"order_of_sub_pages\":\"0\",\"order_of_sub_pages_dir\":\"0\"}'),(25,'Second Page','<p>A Second Page</p>',0,1,NULL,0,'2010-04-28 21:35:33','',NULL,'0','','','2010-04-28','{\"order_of_sub_pages\":\"0\",\"order_of_sub_pages_dir\":\"0\"}'),(35,'Home2','',0,0,'2010-04-28 22:31:55',0,'2010-04-28 22:31:55','',NULL,'0','','','2010-04-28','{\"order_of_sub_pages\":\"0\",\"order_of_sub_pages_dir\":\"0\"}'),(38,'test3','',24,0,'2010-04-28 22:46:06',2,'2010-04-28 22:46:06','',NULL,'0','','','2010-04-28','[]'),(36,'test','',24,0,'2010-04-28 22:44:29',2,'2010-04-28 22:44:29','',NULL,'0','','','2010-04-28','[]');
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

-- Dump completed on 2010-05-02 22:23:06
