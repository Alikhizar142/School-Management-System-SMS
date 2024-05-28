CREATE DATABASE  IF NOT EXISTS `sms` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `sms`;
-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: sms
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `attendence`
--

DROP TABLE IF EXISTS `attendence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendence` (
  `AttenID` int(11) NOT NULL AUTO_INCREMENT,
  `StdID` int(11) NOT NULL,
  `StdClass` int(11) NOT NULL,
  `Date` date DEFAULT NULL,
  `Status` enum('P','A','L') DEFAULT NULL,
  PRIMARY KEY (`AttenID`,`StdID`,`StdClass`),
  KEY `fk_Attendence_Registration1_idx` (`StdID`,`StdClass`),
  CONSTRAINT `fk_Attendence_Registration1` FOREIGN KEY (`StdID`, `StdClass`) REFERENCES `registration` (`StdId`, `ClassId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendence`
--

LOCK TABLES `attendence` WRITE;
/*!40000 ALTER TABLE `attendence` DISABLE KEYS */;
INSERT INTO `attendence` VALUES (5,5,5,'2024-01-05','P'),(6,6,6,'2024-01-06','A'),(7,7,7,'2024-01-07','L'),(8,8,8,'2024-01-08','P'),(11,5,5,'2024-05-20','P'),(12,5,5,'2024-05-21','P'),(13,5,5,'2024-05-14','A'),(14,5,5,'2024-05-16','L'),(15,5,5,'2024-05-22','P'),(16,5,5,'0000-00-00','P'),(17,6,6,'2024-05-22','A');
/*!40000 ALTER TABLE `attendence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class` (
  `idClass` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `Section` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idClass`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class`
--

LOCK TABLES `class` WRITE;
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
INSERT INTO `class` VALUES (2,'Prep','A'),(3,'I','A'),(4,'II','A'),(5,'III','A'),(6,'IV','A'),(7,'V','A'),(8,'VI','A'),(9,'VII','A'),(10,'VIII','A'),(11,'IX','A'),(13,'X','A');
/*!40000 ALTER TABLE `class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exam`
--

DROP TABLE IF EXISTS `exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exam` (
  `idExam` int(11) NOT NULL AUTO_INCREMENT,
  `ExamType` varchar(10) NOT NULL,
  PRIMARY KEY (`idExam`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam`
--

LOCK TABLES `exam` WRITE;
/*!40000 ALTER TABLE `exam` DISABLE KEYS */;
INSERT INTO `exam` VALUES (1,'1stTerm'),(2,'2ndTerm'),(3,'Annual');
/*!40000 ALTER TABLE `exam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marks`
--

DROP TABLE IF EXISTS `marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marks` (
  `ExamId` int(11) NOT NULL,
  `StdId` int(11) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `SubID` int(11) NOT NULL,
  `TotalMarks` float NOT NULL,
  `ObtainedMarks` float NOT NULL,
  PRIMARY KEY (`ExamId`,`StdId`,`ClassId`,`SubID`),
  KEY `fk_Marks_Exam1_idx` (`ExamId`),
  KEY `fk_Marks_Registration1_idx` (`StdId`,`ClassId`,`SubID`),
  CONSTRAINT `fk_Marks_Exam1` FOREIGN KEY (`ExamId`) REFERENCES `exam` (`idExam`) ON DELETE CASCADE,
  CONSTRAINT `fk_Marks_Registration1` FOREIGN KEY (`StdId`, `ClassId`, `SubID`) REFERENCES `registration` (`StdId`, `ClassId`, `SubID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marks`
--

LOCK TABLES `marks` WRITE;
/*!40000 ALTER TABLE `marks` DISABLE KEYS */;
INSERT INTO `marks` VALUES (1,6,6,6,100,50),(1,7,7,7,80,0),(1,8,8,8,100,50),(2,5,5,5,92.5,0),(2,8,8,8,89.5,0);
/*!40000 ALTER TABLE `marks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parent`
--

DROP TABLE IF EXISTS `parent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parent` (
  `idParent` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone_no` varchar(45) DEFAULT NULL,
  `CNIC_NO` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idParent`),
  UNIQUE KEY `CNIC_NO_UNIQUE` (`CNIC_NO`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parent`
--

LOCK TABLES `parent` WRITE;
/*!40000 ALTER TABLE `parent` DISABLE KEYS */;
INSERT INTO `parent` VALUES (1,'Parent1','parent1@example.com','1234567890','12345-1234567-1'),(2,'Parent2','parent2@example.com','1234567891','12345-1234567-2'),(3,'Parent3','parent3@example.com','1234567892','12345-1234567-3'),(4,'Parent4','parent4@example.com','1234567893','12345-1234567-4'),(5,'Parent5','parent5@example.com','1234567894','12345-1234567-5'),(6,'Parent6','parent6@example.com','1234567895','12345-1234567-6'),(7,'Parent7','parent7@example.com','1234567896','12345-1234567-7'),(8,'Parent8','parent8@example.com','1234567897','12345-1234567-8'),(9,'Parent9','parent9@example.com','1234567898','12345-1234567-9'),(10,'Parent10','parent10@example.com','1234567899','12345-1234567-10'),(11,'Ali','222','22','1111111111111');
/*!40000 ALTER TABLE `parent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registration`
--

DROP TABLE IF EXISTS `registration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registration` (
  `StdId` int(11) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `SubID` int(11) NOT NULL,
  `Section` varchar(10) NOT NULL,
  `Session` varchar(10) NOT NULL,
  PRIMARY KEY (`StdId`,`ClassId`,`SubID`),
  KEY `fk_Student_has_Class_Class1_idx` (`ClassId`),
  KEY `fk_Student_has_Class_Student1_idx` (`StdId`),
  KEY `fk_Registration_Subject1_idx` (`SubID`),
  CONSTRAINT `fk_Registration_Subject1` FOREIGN KEY (`SubID`) REFERENCES `subject` (`idSubject`) ON DELETE CASCADE,
  CONSTRAINT `fk_Student_has_Class_Class1` FOREIGN KEY (`ClassId`) REFERENCES `class` (`idClass`) ON DELETE CASCADE,
  CONSTRAINT `fk_Student_has_Class_Student1` FOREIGN KEY (`StdId`) REFERENCES `student` (`idStudent`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registration`
--

LOCK TABLES `registration` WRITE;
/*!40000 ALTER TABLE `registration` DISABLE KEYS */;
INSERT INTO `registration` VALUES (5,5,5,'A','2024'),(6,6,6,'A','2024'),(7,7,7,'A','2024'),(8,8,8,'A','2024');
/*!40000 ALTER TABLE `registration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student` (
  `idStudent` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `ParentId` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `Gender` varchar(45) NOT NULL,
  `DOB` date NOT NULL,
  `Address` varchar(250) NOT NULL,
  PRIMARY KEY (`idStudent`),
  KEY `fk_Student_USER1_idx` (`UserID`),
  KEY `fk_Student_Parent1_idx` (`ParentId`),
  CONSTRAINT `fk_Student_Parent1` FOREIGN KEY (`ParentId`) REFERENCES `parent` (`idParent`),
  CONSTRAINT `fk_Student_USER1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (1,11,1,'Student1','Male','2010-01-01',''),(2,12,2,'Student2','Male','2010-02-01',''),(5,15,5,'Student5','Male','2010-05-01',''),(6,16,6,'Student6','Male','2010-06-01',''),(7,17,7,'Student7','Male','2010-07-01',''),(8,18,8,'Student8','Male','2010-08-01',''),(9,19,9,'Student9','Male','2010-09-01',''),(10,20,10,'Student10','Male','2010-10-01',''),(11,21,11,'Ali Abbas ','Male','2003-12-25','karachi');
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subject` (
  `idSubject` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`idSubject`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject`
--

LOCK TABLES `subject` WRITE;
/*!40000 ALTER TABLE `subject` DISABLE KEYS */;
INSERT INTO `subject` VALUES (3,'Maths'),(4,'Science'),(5,'GK'),(6,'Islamiat'),(7,'Computer'),(8,'Art');
/*!40000 ALTER TABLE `subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher` (
  `idTeacher` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone_no` varchar(45) DEFAULT NULL,
  `Education` varchar(45) NOT NULL,
  `Address` varchar(45) DEFAULT NULL,
  `DOB` date NOT NULL,
  `CNIC_NO` varchar(45) NOT NULL,
  `Date_of_joining` date NOT NULL,
  PRIMARY KEY (`idTeacher`),
  UNIQUE KEY `CNIC_NO_UNIQUE` (`CNIC_NO`),
  KEY `fk_Teacher_USER1_idx` (`userID`),
  CONSTRAINT `fk_Teacher_USER1` FOREIGN KEY (`userID`) REFERENCES `user` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher`
--

LOCK TABLES `teacher` WRITE;
/*!40000 ALTER TABLE `teacher` DISABLE KEYS */;
INSERT INTO `teacher` VALUES (2,3,'Saad Khan','Female','saad2@example.com','1234567891','MSc','Lahor','1981-01-01','11111-1111111-2','2000-12-30'),(3,4,'khizar','Male','teacher3@example.com','1234567892','BEd','afg','1982-01-01','11111-1111111-3','2000-12-30'),(4,5,'Hussain','Male','teacher4@example.com','1234567893','BSAI','malir','2005-01-01','11111-1111111-4','2000-12-30'),(5,6,'Teacher5','Male','teacher5@example.com','1234567894','BSc','kemari','1984-01-01','11111-1111111-5','2000-12-30'),(6,7,'Teacher6','female','teacher6@example.com','1234567895','MSc','peshawawar','1985-01-01','11111-1111111-6','2000-12-30'),(7,8,'Teacher7','Male','teacher7@example.com','1234567896','BEd','swat','1986-01-01','11111-1111111-7','2000-12-30'),(8,9,'Teacher8','female','teacher8@example.com','1234567897','MEd','swabi','1987-01-01','11111-1111111-8','2000-12-30'),(11,22,'Aashir','Male','kkkkk','03172358854','BSAI','gulbahar','2000-02-02','42401-2358544','2024-05-20');
/*!40000 ALTER TABLE `teacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_subjects`
--

DROP TABLE IF EXISTS `teacher_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_subjects` (
  `TeacherId` int(11) NOT NULL,
  `SubID` int(11) NOT NULL,
  PRIMARY KEY (`TeacherId`,`SubID`),
  KEY `fk_Teacher_has_Subject_Subject1_idx` (`SubID`),
  KEY `fk_Teacher_has_Subject_Teacher1_idx` (`TeacherId`),
  CONSTRAINT `fk_Teacher_has_Subject_Subject1` FOREIGN KEY (`SubID`) REFERENCES `subject` (`idSubject`) ON DELETE CASCADE,
  CONSTRAINT `fk_Teacher_has_Subject_Teacher1` FOREIGN KEY (`TeacherId`) REFERENCES `teacher` (`idTeacher`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_subjects`
--

LOCK TABLES `teacher_subjects` WRITE;
/*!40000 ALTER TABLE `teacher_subjects` DISABLE KEYS */;
INSERT INTO `teacher_subjects` VALUES (2,6),(2,7),(2,8),(3,3),(3,4),(3,6),(4,3),(4,4),(4,5),(4,6),(5,5),(6,6),(7,7),(8,8),(11,3),(11,4),(11,6);
/*!40000 ALTER TABLE `teacher_subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) DEFAULT NULL,
  `Role` enum('Teacher','Admin','Student') NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','admin','Admin'),(3,'t','akrcy1234','Teacher'),(4,'akrcy','akrcy1234','Teacher'),(5,'hu','125','Teacher'),(6,'t5','126','Teacher'),(7,'t6','127','Teacher'),(8,'t7','128','Teacher'),(9,'t8','129','Teacher'),(11,'user11','password11','Student'),(12,'user12','password12','Student'),(13,'user13','password13','Student'),(14,'user14','7MWdgCBg','Student'),(15,'user15','password15','Student'),(16,'user16','password16','Student'),(17,'user17','password17','Student'),(18,'user18','password18','Student'),(19,'user19','password19','Student'),(20,'user20','password20','Student'),(21,'abbas ','1nF8','Student'),(22,'aashir','123','Teacher'),(23,'khizar','12345','Student');
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

-- Dump completed on 2024-05-28  7:37:54
