-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: pidev
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `abonnement`
--

DROP TABLE IF EXISTS `abonnement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `abonnement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `duree` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abonnement`
--

LOCK TABLES `abonnement` WRITE;
/*!40000 ALTER TABLE `abonnement` DISABLE KEYS */;
INSERT INTO `abonnement` VALUES (1,'essai',100,'1 mois',''),(2,'intermédiaire',250,'3 mois',''),(3,'fidèle',700,'1 an','');
/*!40000 ALTER TABLE `abonnement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chapitre`
--

DROP TABLE IF EXISTS `chapitre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapitre` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8C62B025AFC2B591` (`module_id`),
  CONSTRAINT `FK_8C62B025AFC2B591` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapitre`
--

LOCK TABLES `chapitre` WRITE;
/*!40000 ALTER TABLE `chapitre` DISABLE KEYS */;
/*!40000 ALTER TABLE `chapitre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cours`
--

DROP TABLE IF EXISTS `cours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chapitre_id` int NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu_fichier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FDCA8C9C1FBEEF7B` (`chapitre_id`),
  CONSTRAINT `FK_FDCA8C9C1FBEEF7B` FOREIGN KEY (`chapitre_id`) REFERENCES `chapitre` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cours`
--

LOCK TABLES `cours` WRITE;
/*!40000 ALTER TABLE `cours` DISABLE KEYS */;
/*!40000 ALTER TABLE `cours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20250216192838','2025-02-16 19:28:44',443),('DoctrineMigrations\\Version20250217144635','2025-02-17 14:46:43',331),('DoctrineMigrations\\Version20250217173326','2025-02-17 17:33:34',207),('DoctrineMigrations\\Version20250217174537','2025-02-17 17:45:50',32);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enseignant`
--

DROP TABLE IF EXISTS `enseignant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enseignant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonenumber` bigint NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `matiere` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience` int NOT NULL,
  `cv` longblob,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cvpath` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enseignant`
--

LOCK TABLES `enseignant` WRITE;
/*!40000 ALTER TABLE `enseignant` DISABLE KEYS */;
INSERT INTO `enseignant` VALUES (1,'aaa',123,'aaaa@azrazr.com','aaa',12,NULL,'zaerzar',NULL),(2,'aaa',123,'aaa@aaa.com','aaa',12,NULL,'aaaa',NULL);
/*!40000 ALTER TABLE `enseignant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localisation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` VALUES (4,'new lessons math','67b319f774589.jpg','ccc','2025-02-28 00:00:00','2025-02-11 00:00:00','enligne','manouba',22),(5,'zzzz','67b3192746c4f.jpg','zz','2025-03-02 00:00:00','2025-02-27 00:00:00','presentiel','zzzzzzzzzzzzzz',123),(6,'eee','67b3345e2b443.jpg','aaa','2025-02-20 00:00:00','2025-03-01 00:00:00','presentiel','eeee',231),(7,'java courses for the begginnneerrsss and to improve your skills','67b347a61305d.png','java','2025-02-20 00:00:00','2025-02-21 00:00:00','enligne',NULL,300);
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `module` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module`
--

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` VALUES (1,'math');
/*!40000 ALTER TABLE `module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paiement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_carte` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_carte` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_expiration` date NOT NULL,
  `cvv` int NOT NULL,
  `montant` double NOT NULL,
  `id_abonnement_id` int DEFAULT NULL,
  `date_paiement` date NOT NULL,
  `userid_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B1DC7A1E4FFF9576` (`id_abonnement_id`),
  KEY `IDX_B1DC7A1E58E0A285` (`userid_id`),
  CONSTRAINT `FK_B1DC7A1E4FFF9576` FOREIGN KEY (`id_abonnement_id`) REFERENCES `abonnement` (`id`),
  CONSTRAINT `FK_B1DC7A1E58E0A285` FOREIGN KEY (`userid_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paiement`
--

LOCK TABLES `paiement` WRITE;
/*!40000 ALTER TABLE `paiement` DISABLE KEYS */;
INSERT INTO `paiement` VALUES (2,'ines','bendhifallahines@gmail.com','Visa','666','2025-02-27',99,250,NULL,'2025-02-11',NULL),(3,'ines','bendhifallahines@gmail.com','Visa','9','2025-02-13',99,250,NULL,'2025-02-11',NULL),(4,'asma','bendhifallahines@gmail.com','Mastercard','55','2025-02-21',99,700,NULL,'2025-02-11',NULL),(5,'asma','bendhifallahines@gmail.com','Mastercard','55','2025-02-21',99,700,NULL,'2025-02-11',NULL);
/*!40000 ALTER TABLE `paiement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participation`
--

DROP TABLE IF EXISTS `participation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AB55E24F71F7E88B` (`event_id`),
  CONSTRAINT `FK_AB55E24F71F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participation`
--

LOCK TABLES `participation` WRITE;
/*!40000 ALTER TABLE `participation` DISABLE KEYS */;
/*!40000 ALTER TABLE `participation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonenumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matiere` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` int DEFAULT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `work` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idmatiere_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D64939C5CF62` (`idmatiere_id`),
  CONSTRAINT `FK_8D93D64939C5CF62` FOREIGN KEY (`idmatiere_id`) REFERENCES `module` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'aaaa@azrazr.com','[\"ROLE_TEACHER\"]','$2y$13$Rd/wuGR1HVo8m9kJ57vYEOPK9UGLIAPoOURtzfjQ2Vom7lkOzaNsK','aaa','123456','mathematiques',12,'aerzer',0,NULL,NULL,NULL,NULL),(2,'zaeraezr@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$wumCJA73t/eg5xMXAMDI0uVgb6Z74fDWh.SEi4gqhA7SOh6QVHynC','aaaa','123123','sciences',12,'azrazr',0,NULL,NULL,NULL,NULL),(3,'zaeraezaezeazer@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$r3jFTOhIq/3wGVebP4NovucCLmb8.WhYdPmAFd.MxssveYCqAk2zy','aaaa','123123','sciences',12,'azrazr',0,NULL,NULL,NULL,NULL),(4,'aaaab@azrazr.com','[\"ROLE_TEACHER\"]','$2y$13$GqxhpC8pCXA6um4xkwsKvu3tuZJxIs.wTLenGM2awa5oUWozyZ2xK','azerezar','123123','sciences',14,'zzz',0,NULL,NULL,NULL,NULL),(5,'aaaaaaaaaaa@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$VoPE6BfQqk26SwDBtcRBPuWDsWm0HG252sDdzJyB5CccBKc.Th/62','aaa','1234456','sciences',12,'aaa',0,NULL,NULL,NULL,NULL),(6,'nanous.bellagha@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$v.zkuZkvKBk8Buy6eVbcFuLCNmjk7Mu5eGVj87ueOVOrR9TzakwmC','nanousti','123456','sciences',12,'aaa',0,NULL,NULL,NULL,NULL),(7,'ines.bendhifallah@gmail.com','[]','$2y$13$a3uBAVkxPTvVPgvVy5blVO7DNNP9etF557m3BNqfZT7J4/S8No6/q','ines','123456','math',12,'rzaraer',NULL,NULL,NULL,0),(8,'oumaima.ghediri@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$YW4cGqEVkGydlyV9sIAkbeM9jpuPGl5ZBPccTonzkvLPOsjCtgr.C','razer','12346578','azerr',12,'aezrazer',NULL,NULL,NULL,0),(11,'oumaima.aghediri@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$VKRBXjVj6yl5NG/wNG3LsuC0Pj6wELEn/XlWqSryjAIgdiLHC43py','razer','12346578','azerr',12,'aezrazer',NULL,NULL,NULL,0),(12,'ines.bendhifallah@istic.ucar.tn','[\"ROLE_TEACHER\"]','$2y$13$CtdefmsdVSnXrivVU/tV9uXL/kVgwTYXiHt89znYHw21LpugiVWqC','razer','12346578','azerr',12,'aezrazer',NULL,NULL,NULL,0),(13,'bellagha.aziz03@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$dC6VVez21x0PE6MYs45i4.R2lOGKGYz.FzewtrProsvauPcYWKXzS','aezr','123456799','azer',12,'aezrazer',NULL,NULL,NULL,0),(14,'mohamedaziz.bellagha@istic.ucar.tn','[\"ROLE_TEACHER\"]','$2y$13$kGgpHdsOAmP/GFopNajsQu5ePymFQWPBObID36iK0jbSDA2bf/JC2','azerzer','12345678','azerzare',12,'aezrazre',NULL,NULL,NULL,0),(15,'bellagha.khalil01@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$WW85b/GCce6h9PsYUsdVZ.OmxrssydESxKxgYkk.UOTH5y6OIahOO','azerzer','12345678','azerzare',12,'aezrazre',NULL,NULL,NULL,0),(16,'kkk@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$PXjU1ZsdQZR4csn3cOlYNePT6ywppPXTRlj0WGesgOay2VRAvPCnG','zerazer','123456','azerzar',12,'zaer',NULL,NULL,NULL,0),(17,'aezr@gmail.com','[\"ROLE_Parent\"]','$2y$13$53wRVXlocnBrEMnRoo82KetBA9F37Ez.sZ0SUIf.uMPfz4Z/1qcS6','zerazre','123456',NULL,NULL,NULL,'azerazr','zaerazr','email',0),(18,'abc@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$h84Yu3jxYu26gxPUE2Ho1eqQMWdKdD9KtoVCqSebntqC6VnXT0KL.','azer','123456','azer',12,'zar',NULL,NULL,NULL,0),(19,'aaa@gmail.com','[\"ROLE_Parent\"]','$2y$13$oThKqd3FmReVER7ivfYnqeRba01cn9gEbAD3nygQ77deV1YN4jbzi','aaze','12345678',NULL,NULL,NULL,'azeae','aeazeae','email',0),(20,'bbb@gmail.com','[\"ROLE_Parent\"]','$2y$13$MuETC3CtDln1vEyhJaXKpuxDSGMAI.bPf3v4dnMnLtvYnAh6doM8O','bbb','12345678',NULL,NULL,NULL,'azeaze','azeaze','email',0),(21,'bbbbc@gmail.com','[\"ROLE_Parent\"]','$2y$13$7sFnjCUF.zHjEia48xBFMu3ctjy0rda6.SLRutQ.k1cZqpnU9TEjm','bbb','12345678',NULL,NULL,NULL,'azeaze','azeaze','email',0),(22,'ooo@gmail.com','[\"ROLE_Parent\"]','$2y$13$yCGiwwjJ5zujCJktk.TZ6.JovYAgsDByXvZcXZdhFT2EZhmzEgsgC','ooo','12345678',NULL,NULL,NULL,'azerazer','azerazr','email',0),(23,'zzz@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$UYKBZ6OAROKJyvO60LVRNeVYS62YIUQA69JWkEqVRW0tjbbS7CK/m','aazer','12345678','aerezr',12,'azerezar',NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-18 13:00:55

