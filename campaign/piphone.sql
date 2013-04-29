-- MySQL dump 10.13  Distrib 5.1.66, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: piphone
-- ------------------------------------------------------
-- Server version	5.1.66-0+squeeze1-log

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
-- Table structure for table `calls`
--

DROP TABLE IF EXISTS `calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign` int(10) unsigned NOT NULL,
  `caller` varchar(32) NOT NULL,
  `callee` varchar(32) NOT NULL,
  `datestart` datetime NOT NULL,
  `dateend` datetime NOT NULL,
  `uuid` varchar(48) NOT NULL,
  `feedback` text NOT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uuid` (`uuid`),
  KEY `campaign` (`campaign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The call history';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `campaign`
--

DROP TABLE IF EXISTS `campaign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name-fr` varchar(255) NOT NULL,
  `longname-fr` text NOT NULL,
  `longname` text NOT NULL,
  `description` text NOT NULL,
  `description-fr` text NOT NULL,
  `description-de` text NOT NULL,
  `slug` varchar(32) NOT NULL,
  `datestart` datetime NOT NULL,
  `datestop` datetime NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  `wavfile` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cookies`
--

DROP TABLE IF EXISTS `cookies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cookies` (
  `cookie` varchar(32) NOT NULL,
  `country` varchar(2) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `lang` varchar(2) NOT NULL,
  PRIMARY KEY (`cookie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The cookies to remember phone and country of our users...';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `code` varchar(2) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The countries in EU';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lists`
--

DROP TABLE IF EXISTS `lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `campaign` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `country` varchar(2) NOT NULL,
  `callcount` int(10) unsigned NOT NULL,
  `callduration` int(10) unsigned NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  `meta` text NOT NULL,
  `scores` int(11) NOT NULL DEFAULT '0',
  `pond_scores` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `campaign` (`campaign`),
  KEY `lang` (`country`),
  KEY `callcount` (`callcount`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meps_committeerole`
--

DROP TABLE IF EXISTS `meps_committeerole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meps_committeerole` (
  `begin` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `mep_id` varchar(255) NOT NULL,
  `committee_id` int(10) NOT NULL,
  `id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(64) NOT NULL,
  `pass` varchar(64) NOT NULL COMMENT 'PASSWORD()-encrypted password',
  `email` varchar(255) NOT NULL,
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `admin` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `token` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-04-29 17:37:04
INSERT INTO `user` SET `id`=1, `login`='admin', `pass`=PASSWORD('admin'), `email`='admin@piphone.eu', `enabled`=1, `admin`=1;

