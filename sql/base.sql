CREATE DATABASE IF NOT EXISTS `w2s` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `w2s`;
-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: w2s
-- ------------------------------------------------------
-- Server version	5.7.15-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

--
-- Table structure for table `aliases`
--

DROP TABLE IF EXISTS `aliases`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aliases` (
  `id`        INT(11) NOT NULL AUTO_INCREMENT,
  `entity_id` VARCHAR(45)      DEFAULT NULL,
  `language`  VARCHAR(45)      DEFAULT NULL,
  `text`      VARCHAR(45)      DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `claims`
--

DROP TABLE IF EXISTS `claims`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `claims` (
  `wbid`        VARCHAR(45) NOT NULL,
  `entity_id`   VARCHAR(25)   DEFAULT NULL,
  `snaktype`    VARCHAR(45)   DEFAULT NULL,
  `property`    VARCHAR(45)   DEFAULT NULL,
  `entity_type` VARCHAR(45)   DEFAULT NULL,
  `value`       VARCHAR(1000) DEFAULT NULL,
  PRIMARY KEY (`wbid`),
  UNIQUE KEY `wbid_UNIQUE` (`wbid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `descriptions`
--

DROP TABLE IF EXISTS `descriptions`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `descriptions` (
  `id`        INT(11) NOT NULL AUTO_INCREMENT,
  `entity_id` VARCHAR(45)      DEFAULT NULL,
  `language`  VARCHAR(45)      DEFAULT NULL,
  `text`      VARCHAR(1000)    DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `wbid`          VARCHAR(45)      DEFAULT NULL,
  `revision_base` INT(11)          DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `labels`
--

DROP TABLE IF EXISTS `labels`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `labels` (
  `id`        INT(11) NOT NULL AUTO_INCREMENT,
  `entity_id` VARCHAR(45)      DEFAULT NULL,
  `language`  VARCHAR(45)      DEFAULT NULL,
  `text`      VARCHAR(45)      DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `properties` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `wbid`          VARCHAR(45)      DEFAULT NULL,
  `revision_base` INT(11)          DEFAULT NULL,
  `entity_type`   VARCHAR(45)      DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qualifiers`
--

DROP TABLE IF EXISTS `qualifiers`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qualifiers` (
  `wbid`        VARCHAR(45) NOT NULL,
  `claim_id`    VARCHAR(250)  DEFAULT NULL,
  `snaktype`    VARCHAR(45)   DEFAULT NULL,
  `property`    VARCHAR(45)   DEFAULT NULL,
  `entity_type` VARCHAR(45)   DEFAULT NULL,
  `value`       VARCHAR(1000) DEFAULT NULL,
  PRIMARY KEY (`wbid`),
  UNIQUE KEY `wbid_UNIQUE` (`wbid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `references`
--

DROP TABLE IF EXISTS `references`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `references` (
  `wbid`        VARCHAR(45)  NOT NULL,
  `claim_id`    VARCHAR(250) NOT NULL,
  `snaktype`    VARCHAR(45)   DEFAULT NULL,
  `property`    VARCHAR(45)   DEFAULT NULL,
  `entity_type` VARCHAR(45)   DEFAULT NULL,
  `value`       VARCHAR(1000) DEFAULT NULL,
  PRIMARY KEY (`wbid`, `claim_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;

-- Dump completed on 2016-11-01  0:57:22
