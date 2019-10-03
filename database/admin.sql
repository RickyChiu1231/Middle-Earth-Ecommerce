-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: middle-earth
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

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
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,0,4,'Coupon Management','fa-tags','/coupon_codes',NULL,'2019-09-04 09:05:01','2019-10-02 08:23:04'),(2,4,9,'Menu','fa-bar-chart','/auth/menu',NULL,'2019-09-04 10:49:45','2019-10-02 07:29:56'),(3,0,1,'Home','fa-bar-chart','/',NULL,'2019-09-04 11:03:10','2019-10-02 08:23:04'),(4,0,5,'Admin','fa-bars',NULL,NULL,'2019-09-04 11:06:02','2019-10-02 07:29:56'),(5,4,6,'Users','fa-users','/auth/users',NULL,'2019-09-04 11:10:04','2019-10-02 07:29:56'),(6,4,7,'Roles','fa-user','auth/roles',NULL,'2019-09-04 11:11:16','2019-10-02 08:50:45'),(7,4,8,'Permission','fa-ban','/auth/permissions',NULL,'2019-09-04 11:12:52','2019-10-02 07:29:56'),(8,4,10,'Operation log','fa-history','/auth/logs',NULL,'2019-09-04 11:15:34','2019-10-02 07:29:56'),(9,0,2,'User Management','fa-users','/users',NULL,'2019-09-04 11:19:58','2019-10-02 08:23:04'),(10,0,3,'Product Management','fa-cubes','/products',NULL,'2019-10-02 07:29:35','2019-10-02 08:23:04');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'All permission','*','','*',NULL,NULL),(2,'Dashboard','dashboard','GET','/',NULL,NULL),(3,'Login','auth.login','','/auth/login\r\n/auth/logout',NULL,NULL),(4,'User setting','auth.setting','GET,PUT','/auth/setting',NULL,NULL),(5,'Auth management','auth.management','','/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',NULL,NULL),(6,'User Management','users','','/users*','2019-08-05 11:18:31','2019-08-05 11:18:31'),(7,'Product Management','products','','/products*','2019-10-03 09:20:27','2019-10-03 09:20:27'),(8,'Coupon Code Management','coupon_codes','','/coupon_codes*','2019-10-03 09:21:29','2019-10-03 09:21:29'),(9,'Order Management','orders','','/orders*','2019-10-03 09:23:26','2019-10-03 09:23:26');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_menu`
--

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_permissions`
--

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
INSERT INTO `admin_role_permissions` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL),(2,3,NULL,NULL),(2,4,NULL,NULL),(2,6,NULL,NULL),(2,7,NULL,NULL),(2,8,NULL,NULL),(2,9,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_users`
--

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;
INSERT INTO `admin_role_users` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_roles`
--

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES (1,'Administrator','administrator','2019-08-05 10:52:41','2019-08-05 10:52:41'),(2,'Shop operator','operation','2019-08-05 11:19:47','2019-08-05 11:19:47');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_user_permissions`
--

LOCK TABLES `admin_user_permissions` WRITE;
/*!40000 ALTER TABLE `admin_user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$HxJKY8h3pAuNfysikZksw.c6Tn9p0qQqNyy.I.WE5oS4k6wT8..zW','Administrator',NULL,'AK20AjQwLvSB4NE6WqrzpkOrV5eVZfi32TFhTYjS54wd4BpKEAAqU1vcP73x','2019-08-05 10:52:41','2019-08-05 10:52:41'),(2,'operator','$2y$10$Ezj7ciUMCXh5EuQiQNZ1b.stnpFh9Tk9Hjla4igAKdudF0dgDcPcO','Shop operator',NULL,'tNLmKk47rSnMrsLVTv9iCsBAlnNquIBjMqNQHoXFsnrNPrzFH683iIFHclcX','2019-08-05 11:22:06','2019-08-05 11:22:06');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-03 10:15:42
