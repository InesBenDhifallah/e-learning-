-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: pidev
-- ------------------------------------------------------
-- Server version	8.0.41

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
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matiere` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` int DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'aaaa@azrazr.com','[\"ROLE_TEACHER\"]','$2y$13$Rd/wuGR1HVo8m9kJ57vYEOPK9UGLIAPoOURtzfjQ2Vom7lkOzaNsK','aaa','123456','mathematiques',12,'aerzer',NULL,NULL,NULL,0),(2,'zaeraezr@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$wumCJA73t/eg5xMXAMDI0uVgb6Z74fDWh.SEi4gqhA7SOh6QVHynC','aaaa','123123','sciences',12,'azrazr',NULL,NULL,NULL,0),(3,'zaeraezaezeazer@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$r3jFTOhIq/3wGVebP4NovucCLmb8.WhYdPmAFd.MxssveYCqAk2zy','aaaa','123123','sciences',12,'azrazr',NULL,NULL,NULL,0),(4,'aaaab@azrazr.com','[\"ROLE_TEACHER\"]','$2y$13$GqxhpC8pCXA6um4xkwsKvu3tuZJxIs.wTLenGM2awa5oUWozyZ2xK','azerezar','123123','sciences',14,'zzz',NULL,NULL,NULL,0),(5,'aaaaaaaaaaa@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$VoPE6BfQqk26SwDBtcRBPuWDsWm0HG252sDdzJyB5CccBKc.Th/62','aaa','1234456','sciences',12,'aaa',NULL,NULL,NULL,0),(6,'nanous.bellagha@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$v.zkuZkvKBk8Buy6eVbcFuLCNmjk7Mu5eGVj87ueOVOrR9TzakwmC','nanousti','123456','sciences',12,'aaa',NULL,NULL,NULL,0),(7,'ines.bendhifallah@gmail.com','[]','$2y$13$a3uBAVkxPTvVPgvVy5blVO7DNNP9etF557m3BNqfZT7J4/S8No6/q','ines','123456','math',12,'rzaraer',NULL,NULL,NULL,0),(8,'oumaima.ghediri@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$YW4cGqEVkGydlyV9sIAkbeM9jpuPGl5ZBPccTonzkvLPOsjCtgr.C','razer','12346578','azerr',12,'aezrazer',NULL,NULL,NULL,0),(11,'oumaima.aghediri@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$VKRBXjVj6yl5NG/wNG3LsuC0Pj6wELEn/XlWqSryjAIgdiLHC43py','razer','12346578','azerr',12,'aezrazer',NULL,NULL,NULL,0),(12,'ines.bendhifallah@istic.ucar.tn','[\"ROLE_TEACHER\"]','$2y$13$CtdefmsdVSnXrivVU/tV9uXL/kVgwTYXiHt89znYHw21LpugiVWqC','razer','12346578','azerr',12,'aezrazer',NULL,NULL,NULL,0),(13,'bellagha.aziz03@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$dC6VVez21x0PE6MYs45i4.R2lOGKGYz.FzewtrProsvauPcYWKXzS','aezr','123456799','azer',12,'aezrazer',NULL,NULL,NULL,0),(14,'mohamedaziz.bellagha@istic.ucar.tn','[\"ROLE_TEACHER\"]','$2y$13$kGgpHdsOAmP/GFopNajsQu5ePymFQWPBObID36iK0jbSDA2bf/JC2','azerzer','12345678','azerzare',12,'aezrazre',NULL,NULL,NULL,0),(15,'bellagha.khalil01@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$WW85b/GCce6h9PsYUsdVZ.OmxrssydESxKxgYkk.UOTH5y6OIahOO','azerzer','12345678','azerzare',12,'aezrazre',NULL,NULL,NULL,0),(16,'kkk@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$PXjU1ZsdQZR4csn3cOlYNePT6ywppPXTRlj0WGesgOay2VRAvPCnG','zerazer','123456','azerzar',12,'zaer',NULL,NULL,NULL,0),(17,'aezr@gmail.com','[\"ROLE_Parent\"]','$2y$13$53wRVXlocnBrEMnRoo82KetBA9F37Ez.sZ0SUIf.uMPfz4Z/1qcS6','zerazre','123456',NULL,NULL,NULL,'azerazr','zaerazr','email',0),(18,'abc@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$h84Yu3jxYu26gxPUE2Ho1eqQMWdKdD9KtoVCqSebntqC6VnXT0KL.','azer','123456','azer',12,'zar',NULL,NULL,NULL,0),(19,'aaa@gmail.com','[\"ROLE_Parent\"]','$2y$13$oThKqd3FmReVER7ivfYnqeRba01cn9gEbAD3nygQ77deV1YN4jbzi','aaze','12345678',NULL,NULL,NULL,'azeae','aeazeae','email',0),(20,'bbb@gmail.com','[\"ROLE_Parent\"]','$2y$13$MuETC3CtDln1vEyhJaXKpuxDSGMAI.bPf3v4dnMnLtvYnAh6doM8O','bbb','12345678',NULL,NULL,NULL,'azeaze','azeaze','email',0),(21,'bbbbc@gmail.com','[\"ROLE_Parent\"]','$2y$13$7sFnjCUF.zHjEia48xBFMu3ctjy0rda6.SLRutQ.k1cZqpnU9TEjm','bbb','12345678',NULL,NULL,NULL,'azeaze','azeaze','email',0),(22,'ooo@gmail.com','[\"ROLE_Parent\"]','$2y$13$yCGiwwjJ5zujCJktk.TZ6.JovYAgsDByXvZcXZdhFT2EZhmzEgsgC','ooo','12345678',NULL,NULL,NULL,'azerazer','azerazr','email',0),(23,'zzz@gmail.com','[\"ROLE_TEACHER\"]','$2y$13$UYKBZ6OAROKJyvO60LVRNeVYS62YIUQA69JWkEqVRW0tjbbS7CK/m','aazer','12345678','aerezr',12,'azerezar',NULL,NULL,NULL,0);
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

-- Dump completed on 2025-02-16 20:14:12
