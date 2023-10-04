-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: apirest_db
-- ------------------------------------------------------
-- Server version	8.1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `idcategories` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `delete` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`idcategories`),
  UNIQUE KEY `nom_UNIQUE` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Langages de programmation',0),(2,'Frameworks et bibliothèques',0),(3,'Base de données',0),(4,'Développement front end',0),(5,'Développement back end',0),(6,'Sécurité web',0),(7,'Déploiement et gestion de serveurs',0),(8,'Développement mobile et responsive',0),(9,'Outils de développement',0),(10,'Tendances et nouveautés',0),(11,'Communauté et ressources d\'apprentissage',0),(12,'testup',0),(13,'a delete',1),(14,'Nouvelles catégorie',0),(15,'testupdate',0),(16,'test2',1),(17,'test3',0),(18,'creer23',0),(19,'creer1',1),(20,'creer2',1),(21,'creer27',0);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ressources`
--

DROP TABLE IF EXISTS `ressources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ressources` (
  `id_ressources` int NOT NULL AUTO_INCREMENT,
  `url_ressource` varchar(512) DEFAULT NULL,
  `delete` tinyint NOT NULL DEFAULT '0',
  `technologies_id_technologie` int NOT NULL,
  PRIMARY KEY (`id_ressources`),
  KEY `fk_ressources_technologies1_idx` (`technologies_id_technologie`),
  CONSTRAINT `fk_ressources_technologies1` FOREIGN KEY (`technologies_id_technologie`) REFERENCES `technologies` (`id_technologie`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ressources`
--

LOCK TABLES `ressources` WRITE;
/*!40000 ALTER TABLE `ressources` DISABLE KEYS */;
INSERT INTO `ressources` VALUES (1,'https://getbootstrap.com/docs/5.3/getting-started/introduction/',0,5),(2,'https://getbootstrap.com/docs/5.3/getting-started/introduction/',0,6),(3,'https://developer.mozilla.org/fr/docs/Web/JavaScript',0,3),(4,'https://developer.mozilla.org/fr/docs/Learn/JavaScript/First_steps/What_is_JavaScript',1,3),(5,'https://www.example.com/ma-ressource',1,1),(6,'https://testressource6',1,10),(7,'https://vos-competences3.com/login',0,18),(8,'https://vos-competences2.com/login',1,18),(9,'https://vos-competences5.com/login',1,18);
/*!40000 ALTER TABLE `ressources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technologies`
--

DROP TABLE IF EXISTS `technologies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `technologies` (
  `id_technologie` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `delete` tinyint NOT NULL DEFAULT '0',
  `categories_idcategories` int NOT NULL,
  PRIMARY KEY (`id_technologie`),
  KEY `fk_technologies_categories_idx` (`categories_idcategories`),
  CONSTRAINT `fk_technologies_categories` FOREIGN KEY (`categories_idcategories`) REFERENCES `categories` (`idcategories`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technologies`
--

LOCK TABLES `technologies` WRITE;
/*!40000 ALTER TABLE `technologies` DISABLE KEYS */;
INSERT INTO `technologies` VALUES (1,'HTML','https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/icons/html5/html5-original.svg',0,1),(2,'HTML','https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/icons/html5/html5-original.svg',0,4),(3,'Javascript','https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/icons/javascript/javascript-original.svg',0,1),(4,'Javascript','https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/icons/javascript/javascript-original.svg',0,4),(5,'Bootstrap','https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/icons/bootstrap/bootstrap-original.svg',0,2),(6,'Bootstrap','https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/icons/bootstrap/bootstrap-original.svg',0,4),(7,'Symfony','https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/icons/symfony/symfony-original.svg',0,2),(8,'Symfony test update','https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/icons/symfony/symfony-original.svg',0,5),(10,'test creation8','logo_test creation8.png',1,15),(11,'cre230','http://php-dev-2.online/logos/logo_cre230.jpg',1,15),(12,'debug6','python-original-svg.png',0,15),(13,'test_creation9','logo_test_creation9',0,18),(14,'test_creation10','logo_test_creation10',0,18),(15,'test_creation10','logo_test_creation10',0,18),(16,'test_creation11','logo_test_creation11',0,18),(17,'test_creation12','./logos/logo_test_creation12.png',0,18),(18,'test_creation13','http://php-dev-2.online./logos/logo_test_creation13.png',0,18),(19,'test_creation14','http//php-dev-2.online./logos/logo_test_creation14.png',0,18),(20,'test_creation15','http//:php-dev-2.online./logos/logo_test_creation15.png',1,18),(21,'cre229','http://php-dev-2.online./logos/logo_cre229.png',0,15);
/*!40000 ALTER TABLE `technologies` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-04 18:43:40
