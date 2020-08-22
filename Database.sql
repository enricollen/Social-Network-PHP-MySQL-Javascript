-- Progettazione Web 
DROP DATABASE if exists pausacaffe; 
CREATE DATABASE pausacaffe; 
USE pausacaffe; 
-- MySQL dump 10.13  Distrib 5.6.20, for Win32 (x86)
--
-- Host: localhost    Database: pausacaffe
-- ------------------------------------------------------
-- Server version	5.6.20

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idadmin` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `livello` tinyint(3) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin',1);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `album_foto`
--

DROP TABLE IF EXISTS `album_foto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album_foto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `uploaded_on` datetime NOT NULL,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_foto`
--

LOCK TABLES `album_foto` WRITE;
/*!40000 ALTER TABLE `album_foto` DISABLE KEYS */;
INSERT INTO `album_foto` VALUES (5,'garda.jpeg','2019-01-08 17:20:12','utente4'),(6,'lucca.jpeg','2019-01-08 17:20:12','utente4'),(7,'mare.jpeg','2019-01-08 17:20:12','utente4'),(8,'montagna.jpeg','2019-01-08 17:23:15','utente1'),(9,'paesaggio.jpeg','2019-01-08 17:23:15','utente1'),(10,'palazzo.jpeg','2019-01-08 17:23:15','utente1'),(11,'palme.jpeg','2019-01-08 17:25:52','utente2'),(12,'porta.jpeg','2019-01-08 17:25:52','utente2'),(14,'torre.jpeg','2019-01-08 17:36:21','utente3'),(15,'surf.png','2019-01-27 18:22:15','utente1'),(16,'libri.jpeg','2019-01-27 18:33:09','utente5'),(17,'recco.jpg','2019-01-27 18:33:09','utente5'),(18,'tennis.jpg','2019-01-27 18:33:09','utente5'),(19,'boschetto.png','2019-01-27 18:40:33','utente6'),(20,'gazebo.png','2019-01-27 18:40:33','utente6'),(21,'libri.jpg','2019-01-27 18:40:33','utente6'),(22,'tramonto.png','2019-01-27 18:40:33','utente6');
/*!40000 ALTER TABLE `album_foto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `amicizie`
--

DROP TABLE IF EXISTS `amicizie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amicizie` (
  `id_amicizia` int(11) NOT NULL AUTO_INCREMENT,
  `iduser1` int(11) NOT NULL,
  `iduser2` int(11) NOT NULL,
  `data_inizio_amicizia` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_amicizia`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amicizie`
--

LOCK TABLES `amicizie` WRITE;
/*!40000 ALTER TABLE `amicizie` DISABLE KEYS */;
INSERT INTO `amicizie` VALUES (1,1,4,'2019-01-08 17:21:46'),(2,1,2,'2019-01-08 17:26:17'),(3,4,3,'2019-01-08 17:37:25'),(4,3,2,'2019-01-08 17:38:10'),(5,4,6,'2019-01-27 18:34:52'),(6,6,7,'2019-01-27 18:43:15');
/*!40000 ALTER TABLE `amicizie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `informazioni`
--

DROP TABLE IF EXISTS `informazioni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `informazioni` (
  `username` varchar(250) NOT NULL DEFAULT '',
  `occupazione` varchar(250) DEFAULT NULL,
  `eta` int(3) DEFAULT NULL,
  `stato_sentimentale` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `informazioni`
--

LOCK TABLES `informazioni` WRITE;
/*!40000 ALTER TABLE `informazioni` DISABLE KEYS */;
INSERT INTO `informazioni` VALUES ('utente1','Studente',22,'Single'),('utente2','Impiegata',34,'Sposata'),('utente3','Pensionato',68,'Divorziato'),('utente4','Studentessa',25,'Impegnata'),('utente5','Avvocato',37,'Impegnato'),('utente6','Ricercatrice',26,'Fidanzata');
/*!40000 ALTER TABLE `informazioni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interessi`
--

DROP TABLE IF EXISTS `interessi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interessi` (
  `username` varchar(255) NOT NULL DEFAULT '',
  `interesse` varchar(255) NOT NULL,
  PRIMARY KEY (`username`,`interesse`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interessi`
--

LOCK TABLES `interessi` WRITE;
/*!40000 ALTER TABLE `interessi` DISABLE KEYS */;
INSERT INTO `interessi` VALUES ('utente1','informatica'),('utente1','sci'),('utente1','surf'),('utente2','Animali'),('utente2','Commedia'),('utente2','Fotografia'),('utente2','Teatro'),('utente3','Calcio'),('utente3','Economia'),('utente3','Pittura'),('utente4','Cibo'),('utente4','Film Romantici'),('utente4','Gatti'),('utente4','Viaggiare'),('utente5','Arte'),('utente5','Politica'),('utente5','Romanzi'),('utente5','Tennis'),('utente6','Biologia'),('utente6','Equitazione'),('utente6','Fotografia'),('utente6','Moda'),('utente6','Recitazione');
/*!40000 ALTER TABLE `interessi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `id_like` int(100) NOT NULL AUTO_INCREMENT,
  `id_importato` int(100) NOT NULL,
  `tipologia` char(1) DEFAULT NULL,
  `ricevitore_mipiace` varchar(100) DEFAULT NULL,
  `mittente_mipiace` varchar(100) DEFAULT NULL,
  `timestamp_like` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_like`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (1,1,'a','utente1','utente2','2019-01-22 16:30:00'),(2,1,'a','utente1','utente4','2019-01-22 16:31:06'),(3,2,'b','utente2','utente1','2019-01-22 16:32:05'),(4,3,'b','utente4','utente1','2019-01-22 16:32:06'),(5,5,'b','utente2','utente4','2019-01-22 16:42:39'),(6,5,'b','utente2','utente3','2019-01-22 16:44:20'),(7,7,'b','utente4','utente3','2019-01-22 16:44:24'),(8,9,'a','utente2','utente1','2019-01-22 19:02:59');
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifiche`
--

DROP TABLE IF EXISTS `notifiche`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifiche` (
  `id_notifica` int(50) NOT NULL AUTO_INCREMENT,
  `iduser_che_causa_la_notifica` int(50) NOT NULL,
  `iduser_destinatario_notifica` int(50) NOT NULL,
  `id_importato` int(100) DEFAULT NULL,
  `tipologia_notifica` varchar(45) DEFAULT NULL,
  `timestamp_notifica` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notifica`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifiche`
--

LOCK TABLES `notifiche` WRITE;
/*!40000 ALTER TABLE `notifiche` DISABLE KEYS */;
INSERT INTO `notifiche` VALUES (1,4,1,NULL,'richiesta amicizia','2019-01-22 17:19:39'),(2,4,1,NULL,'amicizia confermata','2019-01-22 17:21:46'),(3,2,1,NULL,'richiesta amicizia','2019-01-22 17:24:01'),(4,2,1,NULL,'amicizia confermata','2019-01-22 17:26:17'),(5,1,4,NULL,'nuovo post amico','2019-01-22 17:29:18'),(6,1,2,NULL,'nuovo post amico','2019-01-22 17:29:18'),(7,2,1,1,'nuovo like su proprio post','2019-01-22 17:30:00'),(8,2,1,1,'nuovo commento su proprio post','2019-01-22 17:30:21'),(9,4,1,1,'nuovo like su proprio post','2019-01-22 17:31:06'),(10,4,1,1,'nuovo commento su proprio post','2019-01-22 17:31:13'),(11,1,2,2,'nuovo like su proprio commento','2019-01-22 17:32:05'),(12,1,4,3,'nuovo like su proprio commento','2019-01-22 17:32:06'),(13,3,1,NULL,'richiesta amicizia','2019-01-22 17:33:03'),(14,3,4,NULL,'richiesta amicizia','2019-01-22 17:33:08'),(15,3,4,NULL,'amicizia confermata','2019-01-22 17:37:25'),(16,2,3,NULL,'richiesta amicizia','2019-01-22 17:37:55'),(17,2,3,NULL,'amicizia confermata','2019-01-22 17:38:10'),(18,3,2,NULL,'nuovo post amico','2019-01-22 17:40:39'),(19,3,4,NULL,'nuovo post amico','2019-01-22 17:40:39'),(20,2,3,4,'nuovo commento su proprio post','2019-01-22 17:41:50'),(21,4,2,5,'nuovo like su proprio commento','2019-01-22 17:42:39'),(22,4,3,4,'nuovo commento su proprio post','2019-01-22 17:43:15'),(23,4,3,4,'nuovo commento su proprio post','2019-01-22 17:43:55'),(24,3,2,5,'nuovo like su proprio commento','2019-01-22 17:44:20'),(25,3,4,7,'nuovo like su proprio commento','2019-01-22 17:44:24'),(26,2,1,NULL,'nuovo post amico','2019-01-22 17:47:29'),(27,2,3,NULL,'nuovo post amico','2019-01-22 17:47:29'),(28,1,2,9,'nuovo like su proprio post','2019-01-22 20:02:59'),(29,6,3,NULL,'richiesta amicizia','2019-02-10 18:28:37'),(30,6,4,NULL,'richiesta amicizia','2019-02-10 18:28:57'),(31,6,4,NULL,'amicizia confermata','2019-02-10 18:34:52'),(32,7,1,NULL,'richiesta amicizia','2019-02-10 18:40:52'),(33,7,2,NULL,'richiesta amicizia','2019-02-10 18:40:58'),(34,7,6,NULL,'richiesta amicizia','2019-02-10 18:41:04'),(35,3,7,NULL,'richiesta amicizia','2019-02-10 18:41:21'),(36,7,6,NULL,'amicizia confermata','2019-02-10 18:43:15'),(37,6,4,NULL,'nuovo post amico','2019-02-10 18:44:34'),(38,6,7,NULL,'nuovo post amico','2019-02-10 18:44:34');
/*!40000 ALTER TABLE `notifiche` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osid` int(11) NOT NULL,
  `account_name` varchar(16) NOT NULL,
  `author` varchar(16) NOT NULL,
  `type` enum('a','b','c') NOT NULL,
  `data` text NOT NULL,
  `postdate` datetime NOT NULL,
  `likes` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,1,'utente1','utente1','a','Ciao a tutti, questo &egrave; il mio primo post!','2019-01-22 17:29:18',2),(2,1,'utente1','utente2','b','ciao Enrico, anche io mi sono iscritta da poco :)','2019-01-22 17:30:21',1),(3,1,'utente1','utente4','b','Benvenuto!','2019-01-22 17:31:13',1),(4,4,'utente3','utente3','a','Buongiorno, qualche consiglio su un film da guardare stasera? Grazie in anticipo','2019-01-22 17:40:39',0),(5,4,'utente3','utente2','b','Ciao Roberto, se ti piacciono i film d\'azione, non puoi perderti Robin Hood :P','2019-01-22 17:41:50',2),(7,4,'utente3','utente4','b','&quot;Quasi Amici&quot; se non lo hai gi&agrave; visto.','2019-01-22 17:43:55',1),(8,4,'utente3','utente3','b','Grazie infinite ad entrambe!\nvi far&ograve; sapere se ho gradito...','2019-01-22 17:45:16',0),(9,9,'utente2','utente2','a','Che freddo terribile in questi giorni, spero soltanto che la bella stagione non tardi ad arrivare.','2019-01-22 17:47:29',1),(10,10,'utente_inattivo','utente_inattivo','a','post datato','2018-03-27 20:22:12',0),(11,11,'utente5','utente5','a','Qualcuno disponibile per una partita a tennis lunedi sera?','2019-02-10 18:44:34',0);
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `richiesteamicizia`
--

DROP TABLE IF EXISTS `richiesteamicizia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `richiesteamicizia` (
  `id_richiesta_amicizia` int(50) NOT NULL AUTO_INCREMENT,
  `iduser_richiedente` int(50) NOT NULL,
  `iduser_destinatario` int(50) NOT NULL,
  `stato_richiesta_amicizia` varchar(50) DEFAULT NULL,
  `data_invio_richiesta` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_richiesta_amicizia`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `richiesteamicizia`
--

LOCK TABLES `richiesteamicizia` WRITE;
/*!40000 ALTER TABLE `richiesteamicizia` DISABLE KEYS */;
INSERT INTO `richiesteamicizia` VALUES (6,3,1,'in attesa','2019-01-22 17:33:03'),(7,6,3,'in attesa','2019-02-10 18:28:37'),(9,7,1,'in attesa','2019-02-10 18:40:52'),(10,7,2,'in attesa','2019-02-10 18:40:58'),(12,3,7,'in attesa','2019-02-10 18:41:21');
/*!40000 ALTER TABLE `richiesteamicizia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `segnalazioni`
--

DROP TABLE IF EXISTS `segnalazioni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `segnalazioni` (
  `idsegnalazione` int(100) NOT NULL AUTO_INCREMENT,
  `id_post` int(100) DEFAULT NULL,
  `contenuto_post` varchar(250) DEFAULT NULL,
  `username_mittente` varchar(250) DEFAULT NULL,
  `username_coinvolto` varchar(250) DEFAULT NULL,
  `tipologia_segnalazione` varchar(250) DEFAULT NULL,
  `stato_segnalazione` varchar(250) DEFAULT 'in attesa',
  PRIMARY KEY (`idsegnalazione`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `segnalazioni`
--

LOCK TABLES `segnalazioni` WRITE;
/*!40000 ALTER TABLE `segnalazioni` DISABLE KEYS */;
INSERT INTO `segnalazioni` VALUES (1,1,'Ciao a tutti, questo Ã¨ il mio primo post!','utente2','utente1','Contenuto offensivo','in attesa');
/*!40000 ALTER TABLE `segnalazioni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `psw` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `cognome` varchar(45) DEFAULT NULL,
  `citta` varchar(45) DEFAULT NULL,
  `genere` char(1) NOT NULL DEFAULT 'M',
  `avatar` varchar(45) DEFAULT 'default',
  `data_registrazione` datetime DEFAULT CURRENT_TIMESTAMP,
  `ultimo_accesso` datetime DEFAULT NULL,
  `ultimo_check_notifiche` datetime DEFAULT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'utente1','utente1','nello.enrico@gmail.com','Enrico','Nello','Pisa','M','bw.jpg','2019-01-08 16:37:34','2019-02-10 18:57:12','2019-01-22 17:31:55'),(2,'utente2','utente2','luisa.c@hotmail.it','Luisa','Canepa','Urbino','F','luisa.jpeg','2019-01-08 17:07:10','2019-02-10 18:58:06','2019-01-22 17:45:30'),(3,'utente3','utente3','robe88@tin.it','Roberto','Petroni','Livorno','M','roberto.jpeg','2019-01-08 17:27:16','2019-02-10 18:41:16','2019-01-22 17:45:18'),(4,'utente4','utente4','francistar@gmail.com','Francesca','Rossi','Pisa','F','ragazza.jpeg','2019-01-06 19:07:10','2019-02-10 18:42:51','2019-01-23 18:33:58'),(5,'utente_inattivo','utente_inattivo','utenteinattivo@live.it','Simone','Razzauti','Livorno','M','default','2017-01-08 16:37:34','2018-01-22 17:14:41','2018-01-22 17:29:53'),(6,'utente5','utente5','nmorelli@tin.it','Nicola','Morelli','Recco','M','nicola.jpg','2019-01-27 18:24:07','2019-02-10 18:43:08','2019-02-10 18:24:07'),(7,'utente6','utente6','serena90@hotmail.it','Serena','Cantini','Livorno','F','serena.jpg','2019-01-27 18:24:07','2019-02-10 18:41:41','2019-02-10 18:24:07');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-14  9:55:43
