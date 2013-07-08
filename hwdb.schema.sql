-- MySQL dump 10.13  Distrib 5.1.67, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: hwdb
-- ------------------------------------------------------
-- Server version	5.1.67

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
-- Table structure for table `exec`
--

DROP TABLE IF EXISTS `exec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exec` (
  `xno` bigint(20) NOT NULL AUTO_INCREMENT,
  `xwho` varchar(50) DEFAULT NULL,
  `xhow` varchar(1000) DEFAULT NULL,
  `xwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`xno`)
) ENGINE=MyISAM AUTO_INCREMENT=1048818 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `host`
--

DROP TABLE IF EXISTS `host`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `host` (
  `name` varchar(50) NOT NULL DEFAULT '',
  `atag` varchar(100) DEFAULT '-',
  `type` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `dept` varchar(30) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL,
  `crole` varchar(20) DEFAULT '-',
  `rtag` varchar(20) DEFAULT '-',
  `owner` varchar(30) DEFAULT NULL,
  `user` varchar(30) DEFAULT '-',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `osmain` varchar(100) DEFAULT '-',
  `osdwa` varchar(100) DEFAULT '-',
  `status` varchar(20) DEFAULT 'prod',
  `wstart` date DEFAULT NULL,
  `wend` date DEFAULT NULL,
  `slno` varchar(50) DEFAULT '-',
  `cpu` varchar(30) DEFAULT '-',
  `ram` varchar(10) DEFAULT '-',
  `gcard` varchar(50) DEFAULT '-',
  `gver` varchar(10) DEFAULT '-',
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-07-08 21:57:57
