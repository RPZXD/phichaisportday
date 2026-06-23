-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: school_sports_day
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `classroom_houses`
--

DROP TABLE IF EXISTS `classroom_houses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classroom_houses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade_level` int(11) NOT NULL,
  `room_number` int(11) NOT NULL,
  `house_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_grade_room` (`grade_level`,`room_number`),
  KEY `fk_class_house` (`house_id`),
  CONSTRAINT `fk_class_house` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classroom_houses`
--

LOCK TABLES `classroom_houses` WRITE;
/*!40000 ALTER TABLE `classroom_houses` DISABLE KEYS */;
INSERT INTO `classroom_houses` VALUES (1,6,2,6),(2,5,4,6),(3,4,5,6),(4,3,3,6),(5,3,12,6),(6,2,4,6),(7,2,9,6),(8,1,5,6),(9,1,8,6),(10,6,6,2),(11,5,1,2),(12,4,6,2),(13,3,2,2),(14,3,8,2),(15,2,2,2),(16,2,12,2),(17,1,2,2),(18,1,11,2),(19,6,5,3),(20,5,3,3),(21,4,4,3),(22,3,6,3),(23,3,9,3),(24,2,6,3),(25,2,11,3),(26,1,3,3),(27,1,12,3),(28,4,7,3),(29,6,3,4),(30,5,2,4),(31,4,2,4),(32,3,4,4),(33,3,7,4),(34,2,5,4),(35,2,10,4),(36,1,1,4),(37,1,7,4),(38,6,7,5),(39,5,5,5),(40,4,3,5),(41,3,5,5),(42,3,10,5),(43,2,3,5),(44,2,8,5),(45,1,4,5),(46,1,9,5),(47,5,7,5),(48,6,1,1),(49,6,4,1),(50,5,6,1),(51,4,1,1),(52,3,1,1),(53,3,11,1),(54,2,1,1),(55,2,7,1),(56,1,6,1),(57,1,10,1);
/*!40000 ALTER TABLE `classroom_houses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_registrations`
--

DROP TABLE IF EXISTS `event_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `sport_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_reg_student` (`student_id`),
  KEY `fk_reg_sport` (`sport_id`),
  CONSTRAINT `fk_reg_sport` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_registrations`
--

LOCK TABLES `event_registrations` WRITE;
/*!40000 ALTER TABLE `event_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `houses`
--

DROP TABLE IF EXISTS `houses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_name` varchar(50) NOT NULL,
  `color_code` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `houses`
--

LOCK TABLES `houses` WRITE;
/*!40000 ALTER TABLE `houses` DISABLE KEYS */;
INSERT INTO `houses` VALUES (1,'Purple House','#9333ea'),(2,'Green House','#16a34a'),(3,'Orange House','#ea580c'),(4,'Blue House','#1d4ed8'),(5,'Light Blue House','#0ea5e9'),(6,'Pink House','#db2777');
/*!40000 ALTER TABLE `houses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matches_events`
--

DROP TABLE IF EXISTS `matches_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matches_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sport_id` int(11) NOT NULL,
  `event_date` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Scheduled',
  PRIMARY KEY (`id`),
  KEY `fk_match_sport` (`sport_id`),
  CONSTRAINT `fk_match_sport` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matches_events`
--

LOCK TABLES `matches_events` WRITE;
/*!40000 ALTER TABLE `matches_events` DISABLE KEYS */;
INSERT INTO `matches_events` VALUES (95,1,'2026-06-23 09:36:03','Scheduled'),(96,1,'2026-06-23 10:06:03','Scheduled'),(97,1,'2026-06-23 10:36:03','Scheduled'),(98,1,'1970-01-01 07:00:00','Scheduled'),(99,1,'2026-06-23 11:36:03','Scheduled'),(100,1,'1970-01-01 07:00:00','Scheduled'),(101,3,'2026-06-23 09:36:35','Scheduled'),(102,3,'2026-06-23 10:06:35','Scheduled'),(103,3,'2026-06-23 10:36:35','Scheduled'),(104,3,'1970-01-01 07:00:00','Scheduled'),(105,3,'2026-06-23 11:36:35','Scheduled'),(106,3,'1970-01-01 07:00:00','Scheduled'),(107,4,'2026-06-23 09:36:59','Scheduled'),(108,4,'2026-06-23 10:06:59','Scheduled'),(109,4,'2026-06-23 10:36:59','Scheduled'),(110,4,'1970-01-01 07:00:00','Scheduled'),(111,4,'2026-06-23 11:36:59','Scheduled'),(112,4,'1970-01-01 07:00:00','Scheduled'),(113,5,'2026-06-23 09:37:27','Scheduled'),(114,5,'2026-06-23 10:07:27','Scheduled'),(115,5,'2026-06-23 10:37:27','Scheduled'),(116,5,'1970-01-01 07:00:00','Scheduled'),(117,5,'2026-06-23 11:37:27','Scheduled'),(118,5,'1970-01-01 07:00:00','Scheduled'),(119,6,'2026-06-23 09:37:45','Scheduled'),(120,6,'2026-06-23 10:07:45','Scheduled'),(121,6,'2026-06-23 10:37:45','Scheduled'),(122,6,'1970-01-01 07:00:00','Scheduled'),(123,6,'2026-06-23 11:37:45','Scheduled'),(124,6,'1970-01-01 07:00:00','Scheduled'),(125,8,'2026-06-23 09:38:05','Scheduled'),(126,8,'2026-06-23 10:08:05','Scheduled'),(127,8,'2026-06-23 10:38:05','Scheduled'),(128,8,'1970-01-01 07:00:00','Scheduled'),(129,8,'2026-06-23 11:38:05','Scheduled'),(130,8,'1970-01-01 07:00:00','Scheduled'),(131,7,'2026-06-23 09:38:34','Scheduled'),(132,7,'2026-06-23 10:08:34','Scheduled'),(133,7,'2026-06-23 10:38:34','Scheduled'),(134,7,'1970-01-01 07:00:00','Scheduled'),(135,7,'2026-06-23 11:38:34','Scheduled'),(136,7,'1970-01-01 07:00:00','Scheduled'),(137,10,'2026-06-23 09:38:53','Scheduled'),(138,10,'2026-06-23 10:08:53','Scheduled'),(139,10,'2026-06-23 10:38:53','Scheduled'),(140,10,'1970-01-01 07:00:00','Scheduled'),(141,10,'2026-06-23 11:38:53','Scheduled'),(142,10,'1970-01-01 07:00:00','Scheduled'),(143,12,'2026-06-23 09:39:18','Scheduled'),(144,12,'2026-06-23 10:09:18','Scheduled'),(145,12,'2026-06-23 10:39:18','Scheduled'),(146,12,'1970-01-01 07:00:00','Scheduled'),(147,12,'2026-06-23 11:39:18','Scheduled'),(148,12,'1970-01-01 07:00:00','Scheduled'),(149,11,'2026-06-23 09:39:47','Scheduled'),(150,11,'2026-06-23 10:09:47','Scheduled'),(151,11,'2026-06-23 10:39:47','Scheduled'),(152,11,'1970-01-01 07:00:00','Scheduled'),(153,11,'2026-06-23 11:39:47','Scheduled'),(154,11,'1970-01-01 07:00:00','Scheduled'),(155,14,'2026-06-23 09:40:10','Scheduled'),(156,14,'2026-06-23 10:10:10','Scheduled'),(157,14,'2026-06-23 10:40:10','Scheduled'),(158,14,'1970-01-01 07:00:00','Scheduled'),(159,14,'2026-06-23 11:40:10','Scheduled'),(160,14,'1970-01-01 07:00:00','Scheduled'),(161,13,'2026-06-23 09:40:33','Scheduled'),(162,13,'2026-06-23 10:10:33','Scheduled'),(163,13,'2026-06-23 10:40:33','Scheduled'),(164,13,'1970-01-01 07:00:00','Scheduled'),(165,13,'2026-06-23 11:40:33','Scheduled'),(166,13,'1970-01-01 07:00:00','Scheduled'),(167,16,'2026-06-23 09:40:59','Scheduled'),(168,16,'2026-06-23 10:10:59','Scheduled'),(169,16,'2026-06-23 10:40:59','Scheduled'),(170,16,'1970-01-01 07:00:00','Scheduled'),(171,16,'2026-06-23 11:40:59','Scheduled'),(172,16,'1970-01-01 07:00:00','Scheduled'),(173,15,'2026-06-23 09:41:21','Scheduled'),(174,15,'2026-06-23 10:11:21','Scheduled'),(175,15,'2026-06-23 10:41:21','Scheduled'),(176,15,'1970-01-01 07:00:00','Scheduled'),(177,15,'2026-06-23 11:41:21','Scheduled'),(178,15,'1970-01-01 07:00:00','Scheduled'),(179,18,'2026-06-23 09:41:53','Scheduled'),(180,18,'2026-06-23 10:11:53','Scheduled'),(181,18,'2026-06-23 10:41:53','Scheduled'),(182,18,'1970-01-01 07:00:00','Scheduled'),(183,18,'2026-06-23 11:41:53','Scheduled'),(184,18,'1970-01-01 07:00:00','Scheduled');
/*!40000 ALTER TABLE `matches_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `house_id` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `medal` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_result_match` (`match_id`),
  KEY `fk_result_house` (`house_id`),
  CONSTRAINT `fk_result_house` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_result_match` FOREIGN KEY (`match_id`) REFERENCES `matches_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sports`
--

DROP TABLE IF EXISTS `sports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sport_name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sports`
--

LOCK TABLES `sports` WRITE;
/*!40000 ALTER TABLE `sports` DISABLE KEYS */;
INSERT INTO `sports` VALUES (1,'บาสเกตบอล ม.ปลาย หญิง','Team'),(2,'บาสเกตบอล ม.ปลาย ชาย','Team'),(3,'เซปักตะกร้อ ม.ต้น หญิง','Team'),(4,'เซปักตะกร้อ ม.ต้น ชาย','Team'),(5,'เซปักตะกร้อ ม.ปลาย หญิง','Team'),(6,'เซปักตะกร้อ ม.ปลาย ชาย','Team'),(7,'เปตอง ทีม 3 คน ม.ต้น ชาย','Team'),(8,'เปตอง ทีม 3 คน ม.ต้น หญิง','Team'),(9,'เปตอง ทีม 3 คน ม.ปลาย ชาย','Team'),(10,'เปตอง ทีม 3 คน ม.ปลาย หญิง','Team'),(11,'เทเบิลเทนนิส ประเภทเดี่ยว ม.ต้น ชาย','Individual'),(12,'เทเบิลเทนนิส ประเภทเดี่ยว ม.ต้น หญิง','Individual'),(13,'เทเบิลเทนนิส ประเภทเดี่ยว ม.ปลาย ชาย','Individual'),(14,'เทเบิลเทนนิส ประเภทเดี่ยว ม.ปลาย หญิง','Individual'),(15,'E-Sport (ROV) ทีม 5 คน ม.ต้น ชาย','Esports'),(16,'E-Sport (ROV) ทีม 5 คน ม.ต้น หญิง','Esports'),(17,'E-Sport (ROV) ทีม 5 คน ม.ปลาย ชาย','Esports'),(18,'E-Sport (ROV) ทีม 5 คน ม.ปลาย หญิง','Esports');
/*!40000 ALTER TABLE `sports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sports_day_athletes`
--

DROP TABLE IF EXISTS `sports_day_athletes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sports_day_athletes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `house_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_student_id` (`student_id`),
  KEY `fk_athlete_house` (`house_id`),
  CONSTRAINT `fk_athlete_house` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sports_day_athletes`
--

LOCK TABLES `sports_day_athletes` WRITE;
/*!40000 ALTER TABLE `sports_day_athletes` DISABLE KEYS */;
INSERT INTO `sports_day_athletes` VALUES (2,'27505',6);
/*!40000 ALTER TABLE `sports_day_athletes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournament_brackets`
--

DROP TABLE IF EXISTS `tournament_brackets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tournament_brackets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `sport_id` int(11) NOT NULL,
  `round_name` varchar(50) NOT NULL,
  `round_number` int(11) NOT NULL,
  `match_order` int(11) NOT NULL,
  `team1_house_id` int(11) DEFAULT NULL,
  `team2_house_id` int(11) DEFAULT NULL,
  `team1_score` int(11) DEFAULT NULL,
  `team2_score` int(11) DEFAULT NULL,
  `winner_house_id` int(11) DEFAULT NULL,
  `next_match_id` int(11) DEFAULT NULL,
  `next_match_position` enum('team1','team2') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `match_id` (`match_id`),
  KEY `fk_bracket_sport` (`sport_id`),
  KEY `fk_bracket_team1` (`team1_house_id`),
  KEY `fk_bracket_team2` (`team2_house_id`),
  KEY `fk_bracket_winner` (`winner_house_id`),
  KEY `fk_bracket_next_match` (`next_match_id`),
  CONSTRAINT `fk_bracket_match` FOREIGN KEY (`match_id`) REFERENCES `matches_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bracket_next_match` FOREIGN KEY (`next_match_id`) REFERENCES `tournament_brackets` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_bracket_sport` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bracket_team1` FOREIGN KEY (`team1_house_id`) REFERENCES `houses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_bracket_team2` FOREIGN KEY (`team2_house_id`) REFERENCES `houses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_bracket_winner` FOREIGN KEY (`winner_house_id`) REFERENCES `houses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournament_brackets`
--

LOCK TABLES `tournament_brackets` WRITE;
/*!40000 ALTER TABLE `tournament_brackets` DISABLE KEYS */;
INSERT INTO `tournament_brackets` VALUES (95,95,1,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(96,96,1,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(97,97,1,'Semi-finals',2,1,1,NULL,NULL,NULL,NULL,95,'team1'),(98,98,1,'Semi-finals',2,2,4,NULL,NULL,NULL,NULL,95,'team2'),(99,99,1,'Quarter-finals',1,1,6,2,NULL,NULL,NULL,97,'team2'),(100,100,1,'Quarter-finals',1,2,3,5,NULL,NULL,NULL,98,'team2'),(101,101,3,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(102,102,3,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(103,103,3,'Semi-finals',2,1,4,NULL,NULL,NULL,NULL,101,'team1'),(104,104,3,'Semi-finals',2,2,2,NULL,NULL,NULL,NULL,101,'team2'),(105,105,3,'Quarter-finals',1,1,6,3,NULL,NULL,NULL,103,'team2'),(106,106,3,'Quarter-finals',1,2,5,1,NULL,NULL,NULL,104,'team2'),(107,107,4,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(108,108,4,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(109,109,4,'Semi-finals',2,1,4,NULL,NULL,NULL,NULL,107,'team1'),(110,110,4,'Semi-finals',2,2,5,NULL,NULL,NULL,NULL,107,'team2'),(111,111,4,'Quarter-finals',1,1,6,2,NULL,NULL,NULL,109,'team2'),(112,112,4,'Quarter-finals',1,2,1,3,NULL,NULL,NULL,110,'team2'),(113,113,5,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(114,114,5,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(115,115,5,'Semi-finals',2,1,1,NULL,NULL,NULL,NULL,113,'team1'),(116,116,5,'Semi-finals',2,2,2,NULL,NULL,NULL,NULL,113,'team2'),(117,117,5,'Quarter-finals',1,1,4,6,NULL,NULL,NULL,115,'team2'),(118,118,5,'Quarter-finals',1,2,5,3,NULL,NULL,NULL,116,'team2'),(119,119,6,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(120,120,6,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(121,121,6,'Semi-finals',2,1,4,NULL,NULL,NULL,NULL,119,'team1'),(122,122,6,'Semi-finals',2,2,1,NULL,NULL,NULL,NULL,119,'team2'),(123,123,6,'Quarter-finals',1,1,2,6,NULL,NULL,NULL,121,'team2'),(124,124,6,'Quarter-finals',1,2,3,5,NULL,NULL,NULL,122,'team2'),(125,125,8,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(126,126,8,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(127,127,8,'Semi-finals',2,1,4,NULL,NULL,NULL,NULL,125,'team1'),(128,128,8,'Semi-finals',2,2,6,NULL,NULL,NULL,NULL,125,'team2'),(129,129,8,'Quarter-finals',1,1,6,5,NULL,NULL,NULL,127,'team2'),(130,130,8,'Quarter-finals',1,2,2,3,NULL,NULL,NULL,128,'team2'),(131,131,7,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(132,132,7,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(133,133,7,'Semi-finals',2,1,4,NULL,NULL,NULL,NULL,131,'team1'),(134,134,7,'Semi-finals',2,2,5,NULL,NULL,NULL,NULL,131,'team2'),(135,135,7,'Quarter-finals',1,1,1,2,NULL,NULL,NULL,133,'team2'),(136,136,7,'Quarter-finals',1,2,3,6,NULL,NULL,NULL,134,'team2'),(137,137,10,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(138,138,10,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(139,139,10,'Semi-finals',2,1,5,NULL,NULL,NULL,NULL,137,'team1'),(140,140,10,'Semi-finals',2,2,2,NULL,NULL,NULL,NULL,137,'team2'),(141,141,10,'Quarter-finals',1,1,6,3,NULL,NULL,NULL,139,'team2'),(142,142,10,'Quarter-finals',1,2,1,4,NULL,NULL,NULL,140,'team2'),(143,143,12,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(144,144,12,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(145,145,12,'Semi-finals',2,1,1,NULL,NULL,NULL,NULL,143,'team1'),(146,146,12,'Semi-finals',2,2,3,NULL,NULL,NULL,NULL,143,'team2'),(147,147,12,'Quarter-finals',1,1,4,2,NULL,NULL,NULL,145,'team2'),(148,148,12,'Quarter-finals',1,2,5,6,NULL,NULL,NULL,146,'team2'),(149,149,11,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(150,150,11,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(151,151,11,'Semi-finals',2,1,2,NULL,NULL,NULL,NULL,149,'team1'),(152,152,11,'Semi-finals',2,2,3,NULL,NULL,NULL,NULL,149,'team2'),(153,153,11,'Quarter-finals',1,1,5,4,NULL,NULL,NULL,151,'team2'),(154,154,11,'Quarter-finals',1,2,6,1,NULL,NULL,NULL,152,'team2'),(155,155,14,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(156,156,14,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(157,157,14,'Semi-finals',2,1,6,NULL,NULL,NULL,NULL,155,'team1'),(158,158,14,'Semi-finals',2,2,5,NULL,NULL,NULL,NULL,155,'team2'),(159,159,14,'Quarter-finals',1,1,3,1,NULL,NULL,NULL,157,'team2'),(160,160,14,'Quarter-finals',1,2,4,2,NULL,NULL,NULL,158,'team2'),(161,161,13,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(162,162,13,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(163,163,13,'Semi-finals',2,1,2,NULL,NULL,NULL,NULL,161,'team1'),(164,164,13,'Semi-finals',2,2,6,NULL,NULL,NULL,NULL,161,'team2'),(165,165,13,'Quarter-finals',1,1,1,4,NULL,NULL,NULL,163,'team2'),(166,166,13,'Quarter-finals',1,2,5,3,NULL,NULL,NULL,164,'team2'),(167,167,16,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(168,168,16,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(169,169,16,'Semi-finals',2,1,2,NULL,NULL,NULL,NULL,167,'team1'),(170,170,16,'Semi-finals',2,2,4,NULL,NULL,NULL,NULL,167,'team2'),(171,171,16,'Quarter-finals',1,1,3,5,NULL,NULL,NULL,169,'team2'),(172,172,16,'Quarter-finals',1,2,1,6,NULL,NULL,NULL,170,'team2'),(173,173,15,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(174,174,15,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(175,175,15,'Semi-finals',2,1,3,NULL,NULL,NULL,NULL,173,'team1'),(176,176,15,'Semi-finals',2,2,6,NULL,NULL,NULL,NULL,173,'team2'),(177,177,15,'Quarter-finals',1,1,4,1,NULL,NULL,NULL,175,'team2'),(178,178,15,'Quarter-finals',1,2,5,2,NULL,NULL,NULL,176,'team2'),(179,179,18,'Finals',3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(180,180,18,'Third-place',3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(181,181,18,'Semi-finals',2,1,4,NULL,NULL,NULL,NULL,179,'team1'),(182,182,18,'Semi-finals',2,2,1,NULL,NULL,NULL,NULL,179,'team2'),(183,183,18,'Quarter-finals',1,1,3,5,NULL,NULL,NULL,181,'team2'),(184,184,18,'Quarter-finals',1,2,2,6,NULL,NULL,NULL,182,'team2');
/*!40000 ALTER TABLE `tournament_brackets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-23 11:31:06
