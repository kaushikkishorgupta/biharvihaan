-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: biharvihaan
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
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (3,NULL,'User Login Failed','System','Failed login attempt for email: admin@biharvihaan.com','::1','2026-06-12 09:25:00'),(4,3,'User Login Successful','System','User ID 3 logged in successfully.','::1','2026-06-12 09:25:52'),(5,3,'User Logout','System','User ID 3 logged out.','::1','2026-06-12 09:37:26'),(6,5,'User Login Successful','System','User ID 5 logged in successfully.','::1','2026-06-12 09:38:28'),(7,5,'User Logout','System','User ID 5 logged out.','::1','2026-06-12 09:40:19'),(8,5,'User Login Successful','System','User ID 5 logged in successfully.','::1','2026-06-12 09:41:08'),(9,5,'User Logout','System','User ID 5 logged out.','::1','2026-06-12 09:43:25'),(10,2,'User Login Successful','System','User ID 2 logged in successfully.','::1','2026-06-12 09:43:51'),(11,2,'User Logout','System','User ID 2 logged out.','::1','2026-06-12 09:49:21'),(12,NULL,'User Registration','System','New user registration for ID: 10','::1','2026-06-12 10:19:03'),(13,10,'User Login Successful','System','User ID 10 logged in successfully.','::1','2026-06-12 10:19:47'),(14,10,'User Logout','System','User ID 10 logged out.','::1','2026-06-12 10:20:15'),(15,NULL,'User Password Reset Request','System','Password reset for user ID 1 triggered.','::1','2026-06-12 10:21:13');
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,NULL,'User Registration','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','New user registration for ID: 7','2026-06-11 12:50:47'),(2,NULL,'User Login Failed','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Failed login attempt for email: kaushikkishorgupta@gmail.com','2026-06-11 12:50:59'),(3,7,'User Login Successful','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged in successfully.','2026-06-11 12:51:15'),(4,7,'User Logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged out.','2026-06-11 19:04:38'),(5,7,'User Login Successful','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged in successfully.','2026-06-11 19:34:36'),(6,7,'User Logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged out.','2026-06-11 19:35:09'),(13,NULL,'User Login Failed','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Failed login attempt for email: kaushikkishorgupta@gmail.com','2026-06-11 19:35:41'),(14,NULL,'User Password Reset Request','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Password reset for user ID 7 triggered.','2026-06-11 19:35:50'),(15,NULL,'User Login Failed','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Failed login attempt for email: kaushikkishorgupta@gmail.com','2026-06-11 19:36:25'),(16,7,'User Login Successful','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged in successfully.','2026-06-11 19:36:31'),(17,7,'User Logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged out.','2026-06-11 19:36:36'),(18,NULL,'User Password Reset Request','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Password reset for user ID 7 triggered.','2026-06-11 19:36:45'),(19,NULL,'User Login Failed','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Failed login attempt for email: kaushikkishorgupta@gmail.com','2026-06-11 20:20:10'),(20,NULL,'User Password Reset Request','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Password reset for user ID 7 triggered.','2026-06-11 20:20:19'),(21,7,'User Login Successful','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged in successfully.','2026-06-11 20:20:37'),(22,7,'User Logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged out.','2026-06-11 20:20:55'),(23,7,'User Login Successful','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged in successfully.','2026-06-11 20:21:05'),(24,7,'User Logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged out.','2026-06-11 20:35:18'),(25,NULL,'User Password Reset Request','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Password reset for user ID 7 triggered.','2026-06-11 20:35:42'),(26,7,'User Login Successful','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged in successfully.','2026-06-11 20:35:51'),(27,7,'User Logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 7 logged out.','2026-06-11 21:10:50'),(28,NULL,'User Password Reset Request','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Password reset for user ID 7 triggered.','2026-06-11 21:11:28'),(29,NULL,'User Login Failed','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Failed login attempt for email: admin@biharvihaan.com','2026-06-11 21:27:47'),(30,NULL,'User Login Failed','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Failed login attempt for email: admin@biharvihaan.com','2026-06-11 21:28:34'),(31,1,'User Logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','User ID 1 logged out.','2026-06-12 03:47:18'),(32,NULL,'User Login Failed','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Failed login attempt for email: admin@biharvihaan.com','2026-06-12 03:47:48'),(34,NULL,'User Login Failed','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Failed login attempt for email: admin@biharvihaan.com','2026-06-12 03:48:16');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `advertisement_clicks`
--

DROP TABLE IF EXISTS `advertisement_clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advertisement_clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_name` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `clicked_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advertisement_clicks`
--

LOCK TABLES `advertisement_clicks` WRITE;
/*!40000 ALTER TABLE `advertisement_clicks` DISABLE KEYS */;
/*!40000 ALTER TABLE `advertisement_clicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ai_planner_rules`
--

DROP TABLE IF EXISTS `ai_planner_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ai_planner_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_type` varchar(50) NOT NULL,
  `criteria` varchar(255) NOT NULL,
  `recommendations_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`recommendations_json`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_planner_rules`
--

LOCK TABLES `ai_planner_rules` WRITE;
/*!40000 ALTER TABLE `ai_planner_rules` DISABLE KEYS */;
INSERT INTO `ai_planner_rules` VALUES (1,'circuit','spiritual','{\"name\":\"The Enlightenment Trail\",\"route\":[\"Patna\",\"Bodh Gaya\",\"Rajgir\",\"Nalanda\"],\"highlights\":[\"Mahabodhi Temple\",\"Vishwa Shanti Stupa\",\"Nalanda University Ruins\"],\"ideal_duration\":4}','2026-06-12 09:08:15'),(2,'circuit','heritage','{\"name\":\"The Golden Heritage\",\"route\":[\"Patna\",\"Vaishali\",\"Kesaria\",\"Valmiki Tiger Reserve\"],\"highlights\":[\"Bihar Museum\",\"Ashokan Pillar\",\"Kesaria Stupa\",\"Wildlife Safari\"],\"ideal_duration\":5}','2026-06-12 09:08:15'),(3,'circuit','nature','{\"name\":\"Wild Bihar Expedition\",\"route\":[\"Patna\",\"Valmiki Tiger Reserve\",\"Kaimur\",\"Rohtasgarh\"],\"highlights\":[\"Jungle Safari\",\"Kaimur Hills\",\"Waterfalls\",\"Rohtasgarh Fort\"],\"ideal_duration\":5}','2026-06-12 09:08:15'),(4,'hidden_gem','all','{\"places\":[{\"name\":\"Barabar Caves\",\"description\":\"The oldest surviving rock-cut caves in India, located in Jehanabad.\"},{\"name\":\"Tutel Bhawani Waterfall\",\"description\":\"A mesmerizing waterfall in Rohtas district, perfect for nature lovers.\"},{\"name\":\"Kakolat Waterfall\",\"description\":\"A scenic waterfall in Nawada, great for a refreshing dip.\"}]}','2026-06-12 09:08:15'),(5,'budget_breakdown','premium','{\"daily_multiplier\":8000,\"transport_pct\":25,\"accommodation_pct\":45,\"food_pct\":20,\"activities_pct\":10,\"suggested_stay\":\"4-Star Hotels & Heritage Resorts\",\"suggested_transport\":\"Private AC Cab (Innova\\/SUV)\"}','2026-06-12 09:08:15'),(6,'budget_breakdown','budget','{\"daily_multiplier\":2000,\"transport_pct\":30,\"accommodation_pct\":30,\"food_pct\":30,\"activities_pct\":10,\"suggested_stay\":\"Hostels & Budget Guesthouses\",\"suggested_transport\":\"Local Buses & Shared Autos\"}','2026-06-12 09:08:15');
/*!40000 ALTER TABLE `ai_planner_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `artisans`
--

DROP TABLE IF EXISTS `artisans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artisans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `experience_years` int(11) DEFAULT 0,
  `specialization` varchar(255) DEFAULT NULL,
  `awards` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artisans`
--

LOCK TABLES `artisans` WRITE;
/*!40000 ALTER TABLE `artisans` DISABLE KEYS */;
INSERT INTO `artisans` VALUES (1,'Ramesh Paswan','Master craftsman of Madhubani art.',20,'Madhubani Painting','State Award 2018','https://images.unsplash.com/photo-1544717302-de2939b7ef71?w=400',1,'2026-06-11 17:11:07'),(2,'Sunita Devi','Expert in Sujani embroidery and rural motifs.',15,'Sujani Embroidery','National Award 2021','https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=400',1,'2026-06-11 17:11:07'),(3,'Rajesh Kumar','Traditional Sikki grass weaver.',10,'Sikki Craft','','https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400',1,'2026-06-11 17:11:07');
/*!40000 ALTER TABLE `artisans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `artists`
--

DROP TABLE IF EXISTS `artists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `stage_name` varchar(100) NOT NULL,
  `category` enum('folk_musician','content_creator','photographer','writer','journalist','other') NOT NULL,
  `bio` text NOT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `verification_status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `artists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artists`
--

LOCK TABLES `artists` WRITE;
/*!40000 ALTER TABLE `artists` DISABLE KEYS */;
INSERT INTO `artists` VALUES (1,5,'Sanjeev Folk Ensemble','folk_musician','Traditional Bhojpuri singer dedicating two decades to archiving rural melodies, Kajri, and Chhath folklore. Performed at national festivals.','https://youtube.com/c/mock-sanjeev-music','verified','2026-06-11 12:09:44');
/*!40000 ALTER TABLE `artists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attractions`
--

DROP TABLE IF EXISTS `attractions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attractions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `destination_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `distance_km` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `destination_id` (`destination_id`),
  CONSTRAINT `attractions_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attractions`
--

LOCK TABLES `attractions` WRITE;
/*!40000 ALTER TABLE `attractions` DISABLE KEYS */;
INSERT INTO `attractions` VALUES (3,2,'Nalanda Archaeological Museum','Houses rich collection of seals, stone sculptures, coins, and bronzes excavated from Nalanda Mahavihara.',NULL,0.50),(4,3,'Vishwa Shanti Stupa','A majestic white peace pagoda built atop the Ratnagiri hill, reachable by single-seater ropeway.',NULL,3.50),(5,3,'Ghora Katora Lake','A pristine, eco-friendly lake surrounded by hills, featuring a tall Buddha statue standing on the water.',NULL,5.00);
/*!40000 ALTER TABLE `attractions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blockchain_blocks`
--

DROP TABLE IF EXISTS `blockchain_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blockchain_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_index` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `previous_hash` varchar(64) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `merkle_root` varchar(64) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blockchain_blocks`
--

LOCK TABLES `blockchain_blocks` WRITE;
/*!40000 ALTER TABLE `blockchain_blocks` DISABLE KEYS */;
INSERT INTO `blockchain_blocks` VALUES (1,0,1781184672,'0','8858b03937e2720d897c3f97a42d47a8e8948cacad439316e672d99271e76a15','62425c2dea9c374dbad2f92557ca0cc39433ffb69c47cd7d9c9513c927bfd50a','{\"info\":\"Bihar Vihaan Enterprise Cryptographic Ledger Genesis Block\"}');
/*!40000 ALTER TABLE `blockchain_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `status` enum('draft','published','scheduled') DEFAULT 'draft',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'Exploring Nalanda Ruins: A Walking Guide',NULL,'Nalanda was a university classrooms hosting 10,000 students and 2,000 teachers in the ancient times. Let\'s unpack its red-brick stupas...','draft',NULL,NULL,NULL,NULL,NULL,'https://images.unsplash.com/photo-1599932738712-4d0f622f96cf?q=80&w=600',2,'2026-06-11 12:09:44');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `booking_type` enum('hotel','tour','guide','transport') NOT NULL,
  `item_name` varchar(150) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `quantity_or_guests` int(11) NOT NULL,
  `details` text DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branding_settings`
--

DROP TABLE IF EXISTS `branding_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `branding_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(100) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `favicon_url` varchar(255) DEFAULT NULL,
  `footer_logo_url` varchar(255) DEFAULT NULL,
  `hero_logo_url` varchar(255) DEFAULT NULL,
  `primary_color` varchar(10) DEFAULT '#0B3D91',
  `accent_color` varchar(10) DEFAULT '#FF9933',
  `success_color` varchar(10) DEFAULT '#10B981',
  `background_color` varchar(10) DEFAULT '#F8F4F0',
  `dark_mode_bg` varchar(10) DEFAULT '#0F172A',
  `font_family` varchar(50) DEFAULT 'Inter',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branding_settings`
--

LOCK TABLES `branding_settings` WRITE;
/*!40000 ALTER TABLE `branding_settings` DISABLE KEYS */;
INSERT INTO `branding_settings` VALUES (1,'Bihar Vihaan',NULL,NULL,NULL,NULL,'#0B3D91','#FF9933','#10B981','#F8F4F0','#0F172A','Inter','2026-06-12 09:14:11');
/*!40000 ALTER TABLE `branding_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_categories`
--

DROP TABLE IF EXISTS `business_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_categories`
--

LOCK TABLES `business_categories` WRITE;
/*!40000 ALTER TABLE `business_categories` DISABLE KEYS */;
INSERT INTO `business_categories` VALUES (1,'Hotels & Homestays','hotels'),(2,'Restaurants & Cafes','restaurants'),(3,'Travel Agencies','agencies'),(4,'Tour Guides','guides'),(5,'Handicrafts & Arts','handicrafts');
/*!40000 ALTER TABLE `business_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_leads`
--

DROP TABLE IF EXISTS `business_leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `business_id` (`business_id`),
  CONSTRAINT `business_leads_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `business_listings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_leads`
--

LOCK TABLES `business_leads` WRITE;
/*!40000 ALTER TABLE `business_leads` DISABLE KEYS */;
/*!40000 ALTER TABLE `business_leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_listings`
--

DROP TABLE IF EXISTS `business_listings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_listings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `website` varchar(100) DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `plan` enum('free','premium','featured') DEFAULT 'free',
  `verification_status` varchar(20) DEFAULT 'pending',
  `logo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  CONSTRAINT `business_listings_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_listings`
--

LOCK TABLES `business_listings` WRITE;
/*!40000 ALTER TABLE `business_listings` DISABLE KEYS */;
INSERT INTO `business_listings` VALUES (1,4,'Mithila Handicrafts Emporium','handicrafts','Authentic hand-painted Madhubani sarees, designer wall art, wooden toys, and organic tussar silk fabrics sourced directly from rural craftswomen.','Maurya Lok Complex, Block C, Patna','+919431012345','mithila.arts@gmail.com','http://www.mithilahandicrafts.co.in','+919431012345','premium','verified',NULL,'2026-06-11 12:09:44'),(2,1,'Hotel Bodhgaya Regency','hotel','Luxury hotel offering fine dining, spa rooms, Buddhist meditation halls, and direct transport booking to Mahabodhi Temple.','Near Japanese Temple, Bodh Gaya','+916312200500','info@bodhgayaregency.com','http://www.bodhgayaregency.com','+916312200500','featured','verified',NULL,'2026-06-11 12:09:44'),(3,2,'Bihar Heritage Travel Agency','agency','Specialized custom tours for Nalanda ruins, Rajgir nature safaris, and transport guides with air-conditioned cabs.','Frazer Road, Patna','+919102345678','tours@biharheritage.com',NULL,'+919102345678','free','verified',NULL,'2026-06-11 12:09:44');
/*!40000 ALTER TABLE `business_listings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `businesses`
--

DROP TABLE IF EXISTS `businesses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `businesses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `is_verified` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `businesses`
--

LOCK TABLES `businesses` WRITE;
/*!40000 ALTER TABLE `businesses` DISABLE KEYS */;
INSERT INTO `businesses` VALUES (2,'Updated Business','test-business','Hotel','','Patna','','','','',0.00,0,'active','2026-06-11 21:43:06');
/*!40000 ALTER TABLE `businesses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,7,'f05pc4b0fp360b26pbauhvnpco','2026-06-11 17:12:37');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `module` varchar(50) NOT NULL COMMENT 'tourism, directory, marketplace',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_pages`
--

DROP TABLE IF EXISTS `cms_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_data`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_pages`
--

LOCK TABLES `cms_pages` WRITE;
/*!40000 ALTER TABLE `cms_pages` DISABLE KEYS */;
INSERT INTO `cms_pages` VALUES (1,'home','Bihar Vihaan Homepage','<h1>Welcome to Bihar Vihaan Enterprise!</h1>','{}','2026-06-12 09:28:20');
/*!40000 ALTER TABLE `cms_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collaboration_requests`
--

DROP TABLE IF EXISTS `collaboration_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collaboration_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `collaboration_requests_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collaboration_requests_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collaboration_requests`
--

LOCK TABLES `collaboration_requests` WRITE;
/*!40000 ALTER TABLE `collaboration_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `collaboration_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Test','test@test.com','Audit','Test msg','2026-06-12 04:33:38'),(2,'Test','test@test.com','Audit','Test msg','2026-06-12 04:33:55'),(3,'Test','test@test.com','Audit','Test msg','2026-06-12 04:34:26'),(4,'Test','test@test.com','Audit','Test msg','2026-06-12 04:34:40'),(5,'kaushik kishor gupta','kaushikkishorgupta@gmail.com','xyz','kausahik','2026-06-12 06:12:37'),(6,'Test','test@test.com','Audit','Test msg','2026-06-12 09:15:43'),(7,'Test','test@test.com','Audit','Test msg','2026-06-12 09:22:18'),(8,'Test','test@test.com','Audit','Test msg','2026-06-12 09:22:39'),(9,'Test','test@test.com','Audit','Test msg','2026-06-12 09:36:53'),(10,'Test','test@test.com','Audit','Test msg','2026-06-12 09:48:25'),(11,'Test','test@test.com','Audit','Test msg','2026-06-12 10:22:56'),(12,'Test','test@test.com','Audit','Test msg','2026-06-12 10:23:08'),(13,'Test','test@test.com','Audit','Test msg','2026-06-12 10:29:16');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_drafts`
--

DROP TABLE IF EXISTS `content_drafts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_drafts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_drafts`
--

LOCK TABLES `content_drafts` WRITE;
/*!40000 ALTER TABLE `content_drafts` DISABLE KEYS */;
/*!40000 ALTER TABLE `content_drafts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_versions`
--

DROP TABLE IF EXISTS `content_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_type` varchar(50) NOT NULL,
  `content_id` int(11) NOT NULL,
  `version_data` longtext NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_content` (`content_type`,`content_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_versions`
--

LOCK TABLES `content_versions` WRITE;
/*!40000 ALTER TABLE `content_versions` DISABLE KEYS */;
INSERT INTO `content_versions` VALUES (1,'page_autosave',1,'{\"title\":\"Bihar Vihaan Homepage\",\"content\":\"<h1>Welcome to Bihar Vihaan Enterprise!<\\/h1>\",\"meta_data\":\"{}\"}',3,'2026-06-12 09:36:50'),(2,'page',1,'{\"title\":\"Bihar Vihaan Homepage\",\"content\":\"<h1>Welcome to Bihar Vihaan Enterprise!<\\/h1>\",\"meta_data\":\"{}\"}',3,'2026-06-12 09:28:20');
/*!40000 ALTER TABLE `content_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('fixed','percent') DEFAULT 'fixed',
  `value` decimal(10,2) NOT NULL,
  `expires_at` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'VIHAAN50','fixed',50.00,'2027-12-31'),(2,'BIHAR20','percent',20.00,'2027-12-31');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_enrollments`
--

DROP TABLE IF EXISTS `course_enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `progress` int(11) DEFAULT 0,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_enrollments`
--

LOCK TABLES `course_enrollments` WRITE;
/*!40000 ALTER TABLE `course_enrollments` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `instructor` varchar(100) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'Madhubani Painting Masterclass','Learn the secrets of ancient Mithila art, traditional natural color mixing, and freehand patterns.','Smt. Shanti Devi (State Awardee)','https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=600','2026-06-11 12:09:44'),(2,'Bihar Tour Guide & Hospitality Training','Professional training program covering Buddhist heritage trails, safety tips, and communication skills.','Sri Rajesh Kumar (Tourism Dept.)','https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=600','2026-06-11 12:09:44');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `creators`
--

DROP TABLE IF EXISTS `creators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `creators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `bio` text DEFAULT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `verification_status` enum('pending','verified','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creators`
--

LOCK TABLES `creators` WRITE;
/*!40000 ALTER TABLE `creators` DISABLE KEYS */;
/*!40000 ALTER TABLE `creators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_leads`
--

DROP TABLE IF EXISTS `crm_leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `source` varchar(50) DEFAULT 'website_inquiry',
  `status` enum('new','contacted','qualified','lost','won') DEFAULT 'new',
  `score` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `business_id` (`business_id`),
  CONSTRAINT `crm_leads_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `business_listings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_leads`
--

LOCK TABLES `crm_leads` WRITE;
/*!40000 ALTER TABLE `crm_leads` DISABLE KEYS */;
INSERT INTO `crm_leads` VALUES (1,1,'Kumar Anupam','anupam@gmail.com','+919988776655','website_inquiry','new',35,'2026-06-11 12:09:44'),(2,1,'Madhuri Devi','madhuri@gmail.com','+919876543222','whatsapp','contacted',60,'2026-06-11 12:09:44'),(3,2,'Vikas Singh','vikas@gmail.com','+917766554433','website_inquiry','qualified',80,'2026-06-11 12:09:44'),(4,2,'kaushik kishor gupta','kaushikkishorgupta@gmail.com','09123266460','website_inquiry','new',20,'2026-06-11 16:17:31');
/*!40000 ALTER TABLE `crm_leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_notes`
--

DROP TABLE IF EXISTS `crm_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lead_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `note` text NOT NULL,
  `follow_up_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `crm_notes_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `crm_notes_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_notes`
--

LOCK TABLES `crm_notes` WRITE;
/*!40000 ALTER TABLE `crm_notes` DISABLE KEYS */;
INSERT INTO `crm_notes` VALUES (1,1,4,'Expressed interest in wholesale purchase of Madhubani paintings for corporate gifting.','2026-06-15','2026-06-11 12:09:44'),(2,2,4,'Discussed custom saree border patterns via WhatsApp call. Sent portfolio images.','2026-06-12','2026-06-11 12:09:44'),(3,3,1,'Inquired about booking 5 deluxe rooms for a heritage study group from Singapore.','2026-06-18','2026-06-11 12:09:44'),(4,4,1,'Client query: QWRRF',NULL,'2026-06-11 16:17:31');
/*!40000 ALTER TABLE `crm_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destination_images`
--

DROP TABLE IF EXISTS `destination_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destination_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `destination_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `destination_id` (`destination_id`),
  CONSTRAINT `destination_images_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destination_images`
--

LOCK TABLES `destination_images` WRITE;
/*!40000 ALTER TABLE `destination_images` DISABLE KEYS */;
INSERT INTO `destination_images` VALUES (3,2,'https://images.unsplash.com/photo-1585135497273-1a86b09fe70e?q=80&w=1200');
/*!40000 ALTER TABLE `destination_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destination_videos`
--

DROP TABLE IF EXISTS `destination_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destination_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `destination_id` int(11) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `destination_id` (`destination_id`),
  CONSTRAINT `destination_videos_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destination_videos`
--

LOCK TABLES `destination_videos` WRITE;
/*!40000 ALTER TABLE `destination_videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `destination_videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destinations`
--

DROP TABLE IF EXISTS `destinations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `history` text DEFAULT NULL,
  `travel_tips` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `district` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `gallery_json` text DEFAULT NULL,
  `views_count` int(11) DEFAULT 0,
  `rating` decimal(2,1) DEFAULT 5.0,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `circuits` varchar(255) DEFAULT NULL,
  `nearby_attractions` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destinations`
--

LOCK TABLES `destinations` WRITE;
/*!40000 ALTER TABLE `destinations` DISABLE KEYS */;
INSERT INTO `destinations` VALUES (2,'Nalanda Mahavihara Ruins','nalanda-mahavihara-ruins','The legendary ancient Buddhist monastery and learning hub of India, hosting scholars from China, Tibet, and Korea. Today, it stands as an outstanding archaeological site of red-brick classrooms, stupas, and open gardens.',NULL,NULL,'Heritage','Nalanda',NULL,25.13470000,85.42060000,'https://images.unsplash.com/photo-1599932738712-4d0f622f96cf?q=80&w=1200&auto=format&fit=crop',NULL,3891,4.8,'active','2026-06-11 12:09:44',NULL,NULL,NULL,NULL),(3,'Rajgir Glass Bridge & Ropeway','rajgir-glass-bridge-&-ropeway','Nestled between five scenic hills, Rajgir features thermal hot springs, the historic Cyclopean Wall, and the modern Glass Skywalk inside the Rajgir Nature Safari. Take a ropeway ride up to the Vishwa Shanti Stupa.',NULL,NULL,'Adventure','Rajgir, Nalanda',NULL,25.02980000,85.41740000,'https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=1200&auto=format&fit=crop',NULL,4756,4.7,'active','2026-06-11 12:09:44',NULL,NULL,NULL,NULL),(4,'Valmiki National Park & Tiger Reserve','valmiki-national-park-&-tiger-reserve','Bihar\'s only national park and tiger reserve located at the India-Nepal border along the banks of the Gandak river. Home to Bengal tigers, Indian rhinoceros, wild dogs, flying foxes, and diverse Himalayan flora.',NULL,NULL,'Nature','West Champaran',NULL,27.30000000,84.20000000,'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?q=80&w=1200&auto=format&fit=crop',NULL,2110,4.5,'active','2026-06-11 12:09:44',NULL,NULL,NULL,NULL),(5,'Sher Shah Suri Tomb','sher-shah-suri-tomb','An outstanding specimen of Indo-Islamic architecture built in the middle of a square artificial lake. The red sandstone mausoleum honors Sher Shah Suri, the legendary Afghan ruler who established the Grand Trunk Road.',NULL,NULL,'Heritage','Sasaram, Rohtas',NULL,24.95350000,84.02050000,'https://images.unsplash.com/photo-1585135497273-1a86b09fe70e?q=80&w=1200&auto=format&fit=crop',NULL,1982,4.6,'active','2026-06-11 12:09:44',NULL,NULL,NULL,NULL),(9,'Updated Dest','test-dest','Test',NULL,NULL,'Spiritual','Test',NULL,NULL,NULL,'',NULL,0,5.0,'active','2026-06-11 21:30:05',NULL,NULL,NULL,NULL),(10,'patna','patna','Patna, the capital city of Bihar, is one of the oldest continuously inhabited cities in the world. It is home to iconic landmarks such as Golghar, Takht Sri Harmandir Sahib (Patna Sahib), Bihar Museum, Gandhi Maidan, and Kumhrar. The city serves as a major center of culture, history, education, and tourism in Bihar.',NULL,NULL,'Heritage','Patna, Bihar, India',NULL,NULL,NULL,'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Golghar_Patna.jpg/1280px-Golghar_Patna.jpg',NULL,2,5.0,'active','2026-06-12 05:57:53',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `destinations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `directory_listings`
--

DROP TABLE IF EXISTS `directory_listings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `directory_listings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `is_verified` tinyint(1) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `directory_listings_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `directory_listings`
--

LOCK TABLES `directory_listings` WRITE;
/*!40000 ALTER TABLE `directory_listings` DISABLE KEYS */;
/*!40000 ALTER TABLE `directory_listings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `max_tickets` int(11) DEFAULT NULL,
  `available_tickets` int(11) DEFAULT NULL,
  `organizer_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `organizer_id` (`organizer_id`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'Madhubani Art & Craft Workshop',NULL,'Learn the traditional style of Madhubani painting from state-award winning artisans. All materials including hand-made paper, organic colors, and brushes will be provided.','2026-07-10','10:00:00','Upendra Maharathi Shilp Anusandhan Sansthan, Patna',NULL,NULL,NULL,NULL,499.00,30,28,1,'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?q=80&w=600&auto=format&fit=crop','active'),(2,'Sufi & Folk Music Night',NULL,'An evening of mesmerizing Sufiana Kalam and traditional Bhojpuri and Maithili folk songs performed by local legend Sanjeev Kumar and group.','2026-08-15','18:30:00','Bhartiya Nritya Kala Mandir, Patna',NULL,NULL,NULL,NULL,250.00,150,150,1,'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop','active'),(3,'Bodh Gaya Heritage Walk',NULL,'A guided historical tour explaining the Mahabodhi ruins, the ancient Monasteries representing international architectures, and Buddhist philosophy.','2026-07-05','06:30:00','Mahabodhi Main Gate, Bodh Gaya',NULL,NULL,NULL,NULL,150.00,20,19,2,'https://images.unsplash.com/photo-1627894483216-2138af692e2e?q=80&w=600&auto=format&fit=crop','active'),(4,'Chhath Puja','chhath-puja','The most revered ancient Hindu Vedic festival historically native to Bihar.',NULL,NULL,'Ganga Ghats, Patna','2026-11-15','2026-11-18','https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=800','Religious',0.00,NULL,NULL,NULL,NULL,'active'),(5,'Sonepur Mela','sonepur-mela','Asia\'s largest cattle fair held on Kartik Poornima.',NULL,NULL,'Sonepur, Saran','2026-11-20','2026-12-05','https://images.unsplash.com/photo-1596422846543-75c6fc197f07?w=800','Cultural',0.00,NULL,NULL,NULL,NULL,'active'),(6,'Rajgir Mahotsav','rajgir-mahotsav','A festival of dance and music with performances by renowned artists.',NULL,NULL,'Rajgir, Nalanda','2026-12-25','2026-12-27','https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=800','Festival',0.00,NULL,NULL,NULL,NULL,'active'),(7,'Pitrapaksha Mela','pitrapaksha-mela','A Hindu ritual to pay homage to ancestors.',NULL,NULL,'Gaya','2026-09-15','2026-09-30','https://images.unsplash.com/photo-1600096194534-95cf5ece04cf?w=800','Religious',0.00,NULL,NULL,NULL,NULL,'active'),(8,'Sama Chakeva','sama-chakeva','A traditional festival celebrating brother-sister bonds.',NULL,NULL,'Mithilanchal','2026-11-01','2026-11-15','https://images.unsplash.com/photo-1514222709107-a180c68d72b4?w=800','Cultural',0.00,NULL,NULL,NULL,NULL,'active'),(9,'Madhubani Art Festival','madhubani-art-festival','Showcasing the world-famous Mithila paintings and artisans.',NULL,NULL,'Madhubani','2026-02-10','2026-02-15','https://images.unsplash.com/photo-1579783900862-c0f25c7bc8aa?w=800','Art',0.00,NULL,NULL,NULL,NULL,'active'),(10,'Bihula Festival','bihula-festival','A prominent festival of the Bhagalpur district praying for family welfare.',NULL,NULL,'Bhagalpur','2026-08-15','2026-08-18','https://images.unsplash.com/photo-1623062831201-1b94b150c9dc?w=800','Religious',0.00,NULL,NULL,NULL,NULL,'active'),(11,'Makar Sankranti Festival','makar-sankranti','Celebrated with traditional fairs and holy dips in the Ganges.',NULL,NULL,'Across Bihar','2026-01-14','2026-01-15','https://images.unsplash.com/photo-1549420042-4fdfa069ba1e?w=800','Festival',0.00,NULL,NULL,NULL,NULL,'active'),(12,'Buddha Purnima','buddha-purnima','Celebrating the birth, enlightenment, and death of Lord Buddha.',NULL,NULL,'Bodh Gaya','2026-05-01','2026-05-03','https://images.unsplash.com/photo-1515542622106-78b28af7815b?w=800','Religious',0.00,NULL,NULL,NULL,NULL,'active'),(13,'Eid Celebrations','eid-celebrations','A major festival of harmony and feasts.',NULL,NULL,'Patna','2026-03-20','2026-03-22','https://images.unsplash.com/photo-1564630560244-a1a7c5c2fc7d?w=800','Religious',0.00,NULL,NULL,NULL,NULL,'active'),(14,'Guru Gobind Singh Jayanti','guru-gobind-singh-jayanti','Prakash Parv celebrated grandly at Takht Sri Patna Sahib.',NULL,NULL,'Patna Sahib','2026-01-05','2026-01-07','https://images.unsplash.com/photo-1588693959306-b511872bd78c?w=800','Religious',0.00,NULL,NULL,NULL,NULL,'active');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_registrations`
--

DROP TABLE IF EXISTS `events_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_count` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` varchar(20) DEFAULT 'pending',
  `ticket_code` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_code` (`ticket_code`),
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `events_registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `events_registrations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_registrations`
--

LOCK TABLES `events_registrations` WRITE;
/*!40000 ALTER TABLE `events_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `festivals`
--

DROP TABLE IF EXISTS `festivals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `festivals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `season` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `festivals`
--

LOCK TABLES `festivals` WRITE;
/*!40000 ALTER TABLE `festivals` DISABLE KEYS */;
INSERT INTO `festivals` VALUES (1,'Chhath Puja','The grandest, highly spiritual and unique solar festival of Bihar. Devotees offer arghya (prayers) to the setting and rising Sun on the banks of holy rivers Ganga and Sone, observing rigorous fasting without water.','2026-11-15','Winter','All Bihar & River Banks','https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=600&auto=format&fit=crop'),(2,'Sonepur Mela','One of Asia\'s largest animal fairs held during Kartik Purnima. Extending for a month at the confluence of Ganges and Gandak, the fair features local folk performances, diverse handcrafts, and elephant parades.','2026-11-23','Winter','Sonepur, Saran','https://images.unsplash.com/photo-1533105079780-92b9be482077?q=80&w=600&auto=format&fit=crop'),(3,'Rajgir Mahotsav','A vibrant three-day cultural extravaganza held annually in Rajgir. Features classical music, folk dancers from Bihar, traditional culinary stalls, and sporting events set against the historical hills.','2026-10-25','Winter','Qila Maidan, Rajgir','https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop'),(4,'Sama Chakeva','A charming winter festival celebrating the bond between brothers and sisters. Girls model small clay birds and sing traditional folk songs to welcome migrating winter birds.','2026-11-19','Winter','Mithila Region','https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=600&auto=format&fit=crop');
/*!40000 ALTER TABLE `festivals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_posts`
--

DROP TABLE IF EXISTS `forum_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_posts`
--

LOCK TABLES `forum_posts` WRITE;
/*!40000 ALTER TABLE `forum_posts` DISABLE KEYS */;
INSERT INTO `forum_posts` VALUES (1,1,6,'Planning a weekend safari and photography tour. Which months are best for spotting wildlife?','2026-06-11 12:09:44'),(2,1,2,'October to March is ideal! The weather is cold, roads are dry, and migratory birds arrive.','2026-06-11 12:09:44'),(3,2,5,'Keen to organize Bhojpuri and Maithili folk singing collaborations with modern instruments.','2026-06-11 12:09:44');
/*!40000 ALTER TABLE `forum_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_replies`
--

DROP TABLE IF EXISTS `forum_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_replies`
--

LOCK TABLES `forum_replies` WRITE;
/*!40000 ALTER TABLE `forum_replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `forum_replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_topics`
--

DROP TABLE IF EXISTS `forum_topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forum_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `forum_id` (`forum_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `forum_topics_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_topics_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_topics`
--

LOCK TABLES `forum_topics` WRITE;
/*!40000 ALTER TABLE `forum_topics` DISABLE KEYS */;
INSERT INTO `forum_topics` VALUES (1,1,6,'Best timeframe to visit Valmiki Tiger Reserve?','2026-06-11 12:09:44'),(2,2,5,'Bhojpuri folk music fusion workshops this winter','2026-06-11 12:09:44');
/*!40000 ALTER TABLE `forum_topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forums`
--

DROP TABLE IF EXISTS `forums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forums`
--

LOCK TABLES `forums` WRITE;
/*!40000 ALTER TABLE `forums` DISABLE KEYS */;
INSERT INTO `forums` VALUES (1,'Bihar Travel Discussions','Share itineraries, reviews, and travel tips about heritage destinations.','travel-talk'),(2,'Art & Craft Community','Interact with local artists, weavers, and craft promoters.','art-corner');
/*!40000 ALTER TABLE `forums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `photographer` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `likes` int(11) DEFAULT 0,
  `status` enum('active','pending','rejected') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_images`
--

LOCK TABLES `gallery_images` WRITE;
/*!40000 ALTER TABLE `gallery_images` DISABLE KEYS */;
INSERT INTO `gallery_images` VALUES (1,'Mahabodhi Temple','mahabodhi-temple','The holiest Buddhist site in the world.','Bodh Gaya','Buddhist Circuit','https://images.unsplash.com/photo-1621217734181-4203774f17df?w=800','Admin',0,0,'active','2026-06-11 17:02:35'),(2,'Nalanda Ruins','nalanda-ruins','Ancient ruins of one of the oldest universities in the world.','Nalanda','Heritage Sites','https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?w=800','Admin',0,0,'active','2026-06-11 17:02:35'),(3,'Rajgir Hills','rajgir-hills','Scenic hills known for the Vishwa Shanti Stupa and glass bridge.','Rajgir','Nature & Eco Tourism','https://images.unsplash.com/photo-1625754593922-b5f63d76e40b?w=800','Admin',0,0,'active','2026-06-11 17:02:35'),(4,'Vaishali Stupa','vaishali-stupa','Ancient stupa housing the sacred relics of Lord Buddha.','Vaishali','Buddhist Circuit','https://images.unsplash.com/photo-1598506822452-f4185799aee9?w=800','Admin',0,0,'active','2026-06-11 17:02:35'),(5,'Madhubani Art','madhubani-art','Traditional Mithila painting showcasing intricate patterns.','Madhubani','Arts & Crafts','https://images.unsplash.com/photo-1614050800720-3b02ddba753a?w=800','Admin',0,0,'active','2026-06-11 17:02:35'),(6,'Chhath Festival','chhath-festival','Millions gathering to worship the Sun God at the sacred ghats.','Patna','Festivals','https://images.unsplash.com/photo-1574513693246-17b2b73bc102?w=800','Admin',0,0,'active','2026-06-11 17:02:35'),(7,'Patna Sahib','patna-sahib','Takht Sri Patna Sahib, the birthplace of Guru Gobind Singh Ji.','Patna','Temples','https://images.unsplash.com/photo-1591505322409-eb5b796eb009?w=800','Admin',0,0,'active','2026-06-11 17:02:35'),(8,'Valmiki Tiger Reserve','valmiki-tiger-reserve','The only national park in Bihar with diverse wildlife.','West Champaran','Nature & Eco Tourism','https://images.unsplash.com/photo-1549471013-3364d7220b75?w=800','Admin',0,0,'active','2026-06-11 17:02:35'),(10,'Test Image','test-image-1781215044','','','Nature','test.jpg','',0,0,'','2026-06-11 21:57:24');
/*!40000 ALTER TABLE `gallery_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `government_schemes`
--

DROP TABLE IF EXISTS `government_schemes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `government_schemes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `department` varchar(255) DEFAULT NULL,
  `eligibility` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `government_schemes`
--

LOCK TABLES `government_schemes` WRITE;
/*!40000 ALTER TABLE `government_schemes` DISABLE KEYS */;
/*!40000 ALTER TABLE `government_schemes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `internships`
--

DROP TABLE IF EXISTS `internships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `internships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `department` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `duration` varchar(50) NOT NULL,
  `requirements` text NOT NULL,
  `stipend` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internships`
--

LOCK TABLES `internships` WRITE;
/*!40000 ALTER TABLE `internships` DISABLE KEYS */;
INSERT INTO `internships` VALUES (1,'Tourism & Heritage Guide Intern','Tourism Intelligence','Engage directly with travelers, map historical points of interest, write informational blogs about Rajgir/Nalanda, and support tour planning operations.','3 Months','Graduate/Undergraduate in History, Tourism, or Hospitality. Good command of Hindi & English. Knowledge of regional dialects is a plus.','₹8,000 / month','active','2026-06-11 12:09:44'),(2,'Social Media & Content Creator','Media & Press','Produce daily Instagram reels, design graphic flyers of Bihar cuisine, film mini YouTube vlogs at heritage spots, and curate newsletters.','2 Months','Proficiency in video editing apps (CapCut, Premiere), graphic design skills (Canva/Illustrator), and sound understanding of digital media trends.','₹6,000 / month','active','2026-06-11 12:09:44'),(3,'Bihar Vihaan Tech Support Intern','IT & Operations','Maintain business listing portals, help debug user reports on payments, check API statuses, and coordinate with database coordinators.','6 Months','Basic understanding of PHP, HTML/CSS, SQL database relations, and API endpoints.','₹10,000 / month','active','2026-06-11 12:09:44');
/*!40000 ALTER TABLE `internships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itineraries`
--

DROP TABLE IF EXISTS `itineraries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itineraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_days` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `itineraries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itineraries`
--

LOCK TABLES `itineraries` WRITE;
/*!40000 ALTER TABLE `itineraries` DISABLE KEYS */;
/*!40000 ALTER TABLE `itineraries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itinerary_days`
--

DROP TABLE IF EXISTS `itinerary_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itinerary_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itinerary_id` int(11) NOT NULL,
  `day_number` int(11) NOT NULL,
  `activities` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `itinerary_id` (`itinerary_id`),
  CONSTRAINT `itinerary_days_ibfk_1` FOREIGN KEY (`itinerary_id`) REFERENCES `itineraries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itinerary_days`
--

LOCK TABLES `itinerary_days` WRITE;
/*!40000 ALTER TABLE `itinerary_days` DISABLE KEYS */;
/*!40000 ALTER TABLE `itinerary_days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_applications`
--

DROP TABLE IF EXISTS `job_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `internship_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `resume_path` varchar(255) NOT NULL,
  `cover_letter` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `internship_id` (`internship_id`),
  CONSTRAINT `job_applications_ibfk_1` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_applications`
--

LOCK TABLES `job_applications` WRITE;
/*!40000 ALTER TABLE `job_applications` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `duration_mins` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lessons`
--

LOCK TABLES `lessons` WRITE;
/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
INSERT INTO `lessons` VALUES (1,1,'Introduction to Mithila Motifs','https://www.youtube.com/embed/dQw4w9WgXcQ',15),(2,1,'Preparing Natural Ochre Colors','https://www.youtube.com/embed/dQw4w9WgXcQ',20),(3,2,'Overview of Mahabodhi Temple History','https://www.youtube.com/embed/dQw4w9WgXcQ',25);
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `status` enum('success','failed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `size_bytes` int(11) NOT NULL,
  `folder` varchar(100) DEFAULT 'general',
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `uploaded_by` (`uploaded_by`),
  CONSTRAINT `media_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_library`
--

DROP TABLE IF EXISTS `media_library`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_library` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(11) NOT NULL,
  `folder` varchar(100) DEFAULT 'uploads',
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_library`
--

LOCK TABLES `media_library` WRITE;
/*!40000 ALTER TABLE `media_library` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_library` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order_index` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','hidden') DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `category` enum('Politics','Tourism','Education','Startup','Culture','Sports') NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `views_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'Sonepur Mela Cultural Stage Expansion Announced','Culture','The state tourism board has announced structural budget allocations to expand stages and add international folk showcases at Sonepur Saran.','https://images.unsplash.com/photo-1533105079780-92b9be482077?q=80&w=600',450,'2026-06-11 12:09:44'),(2,'Bihar Startups Hit Record Funding in 2026','Startup','New tech ventures in Patna, Bihar, specializing in Agri-tech, organic products logistics and handicrafts export hit positive VC funding loops.','https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600',920,'2026-06-11 12:09:44'),(3,'Valmiki National Park Tourism Eco-Cottages Open','Tourism','New solar-powered logs and safari cottage packages have officially been open for travelers booking custom wildlife packages.','https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?q=80&w=600',230,'2026-06-11 12:09:44');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscribers`
--

LOCK TABLES `newsletter_subscribers` WRITE;
/*!40000 ALTER TABLE `newsletter_subscribers` DISABLE KEYS */;
INSERT INTO `newsletter_subscribers` VALUES (1,'test_audit_1781238818@example.com','2026-06-12 04:33:38'),(2,'test_audit_1781238835@example.com','2026-06-12 04:33:55'),(3,'test_audit_1781238866@example.com','2026-06-12 04:34:26'),(4,'test_audit_1781238880@example.com','2026-06-12 04:34:40'),(5,'test_audit_1781255743@example.com','2026-06-12 09:15:43'),(6,'test_audit_1781256138@example.com','2026-06-12 09:22:18'),(7,'test_audit_1781256159@example.com','2026-06-12 09:22:39'),(8,'test_audit_1781257013@example.com','2026-06-12 09:36:53'),(9,'test_audit_1781257705@example.com','2026-06-12 09:48:25'),(10,'test_audit_1781259776@example.com','2026-06-12 10:22:56'),(11,'test_audit_1781259788@example.com','2026-06-12 10:23:08'),(12,'test_audit_1781260156@example.com','2026-06-12 10:29:16');
/*!40000 ALTER TABLE `newsletter_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,1,'crm','New Business Lead Received','You have a new inquiry from kaushik kishor gupta for Hotel Bodhgaya Regency. Open CRM dashboard to review.','unread','2026-06-11 16:17:31');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `billing_name` varchar(100) NOT NULL,
  `billing_email` varchar(100) NOT NULL,
  `billing_phone` varchar(20) NOT NULL,
  `billing_address` text NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `gst_amount` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `razorpay_order_id` varchar(100) DEFAULT NULL,
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `razorpay_signature` varchar(255) DEFAULT NULL,
  `delivery_status` enum('processing','shipped','delivered','cancelled') DEFAULT 'processing',
  `tracking_number` varchar(100) DEFAULT NULL,
  `refund_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_sections`
--

DROP TABLE IF EXISTS `page_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `section_key` varchar(100) NOT NULL,
  `content_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content_json`)),
  `order_index` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_section_unique` (`page_id`,`section_key`),
  CONSTRAINT `page_sections_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_sections`
--

LOCK TABLES `page_sections` WRITE;
/*!40000 ALTER TABLE `page_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_versions`
--

DROP TABLE IF EXISTS `page_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `content_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content_data`)),
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `page_versions_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `page_versions_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_versions`
--

LOCK TABLES `page_versions` WRITE;
/*!40000 ALTER TABLE `page_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `title` varchar(150) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `status` enum('published','draft') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'home','Homepage',NULL,NULL,'published','2026-06-11 17:53:58','2026-06-11 17:53:58'),(2,'about','About Us',NULL,NULL,'published','2026-06-11 17:53:58','2026-06-11 17:53:58'),(3,'services','Services',NULL,NULL,'published','2026-06-11 17:53:58','2026-06-11 17:53:58'),(4,'tourism','Tourism',NULL,NULL,'published','2026-06-11 17:53:58','2026-06-11 17:53:58'),(5,'directory','Directory',NULL,NULL,'published','2026-06-11 17:53:58','2026-06-11 17:53:58'),(6,'gallery','Gallery',NULL,NULL,'published','2026-06-11 17:53:58','2026-06-11 17:53:58'),(7,'marketplace','Marketplace',NULL,NULL,'published','2026-06-11 17:53:58','2026-06-11 17:53:58'),(8,'contact','Contact Us',NULL,NULL,'published','2026-06-11 17:53:58','2026-06-11 17:53:58');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_gallery`
--

DROP TABLE IF EXISTS `partner_gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  CONSTRAINT `partner_gallery_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_gallery`
--

LOCK TABLES `partner_gallery` WRITE;
/*!40000 ALTER TABLE `partner_gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `partner_gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` VALUES (1,'Bihar Tourism','bihar-tourism','Tourism Promotion Partner','Collaborating to elevate Bihar\'s tourism landscape on a global stage.','The Department of Tourism, Bihar is responsible for the development and promotion of tourism in the state. We collaborate to bring Bihar\'s rich history and culture to the world.','To promote sustainable tourism and showcase the state\'s heritage.','To make Bihar the most preferred tourist destination in India.','https://ui-avatars.com/api/?name=BT&background=E5E7EB&color=1F2937&rounded=true','https://tourism.bihar.gov.in','contact@tourism.bihar.gov.in','+91 612 2222622','Old Secretariat, Patna, Bihar','active','2026-06-12 05:00:05'),(2,'Nalanda University','nalanda-university','Education & Heritage Partner','Reviving the ancient academic legacy and research methodologies.','Nalanda University is an international and research-intensive university established to recreate the glory of ancient Nalanda.','To build an institution of higher learning that fosters intellectual curiosity.','To be a global center for knowledge and cultural exchange.','https://ui-avatars.com/api/?name=NU&background=E5E7EB&color=1F2937&rounded=true','https://nalandauniv.edu.in','info@nalandauniv.edu.in','+91 6112 255330','Rajgir, District Nalanda, Bihar','active','2026-06-12 05:00:05'),(3,'Bihar Museum','bihar-museum','Cultural Preservation Partner','Preserving artifacts and chronicling the grand history of Bihar.','A world-class museum in Patna showcasing the history of Bihar and the Indian subcontinent.','To preserve the rich cultural heritage and artifacts of Bihar.','To educate and inspire generations through historical exhibits.','https://ui-avatars.com/api/?name=BM&background=E5E7EB&color=1F2937&rounded=true','https://biharmuseum.org','info@biharmuseum.org','+91 612 2235555','Jawaharlal Nehru Marg, Patna, Bihar','active','2026-06-12 05:00:05'),(4,'Patna Sahib Management Board','patna-sahib-management-board','Spiritual Partner','Managing the sacred Takht Sri Patna Sahib for global pilgrims.','The board manages the affairs of Takht Sri Harmandir Ji Patna Sahib, the birthplace of Guru Gobind Singh Ji.','To serve the pilgrims and maintain the sanctity of the Gurdwara.','To spread the teachings of Sikh Gurus globally.','https://ui-avatars.com/api/?name=PSMB&background=E5E7EB&color=1F2937&rounded=true','https://takhtpatnasahib.in','admin@takhtpatnasahib.in','+91 612 2641400','Patna Sahib, Patna, Bihar','active','2026-06-12 05:00:05'),(5,'Madhubani Art Collective','madhubani-art-collective','Cultural Partner','Preserving and commercializing authentic Mithila artwork internationally.','A collective of traditional artists from the Mithila region focused on preserving Madhubani painting.','To empower local artisans and preserve traditional art forms.','To bring Madhubani art into the global contemporary art space.','https://ui-avatars.com/api/?name=MAC&background=E5E7EB&color=1F2937&rounded=true','https://madhubaniart.org','contact@madhubaniart.org','+91 99999 88888','Madhubani, Bihar','active','2026-06-12 05:00:05'),(6,'Bihar Heritage Foundation','bihar-heritage-foundation','Heritage Preservation Partner','Restoring monuments and documenting the historical artifacts of Pataliputra.','An NGO dedicated to the restoration and preservation of Bihar\'s physical and cultural heritage.','To protect, restore, and promote Bihar\'s ancient sites.','A Bihar where every historical monument is preserved for posterity.','https://ui-avatars.com/api/?name=BHF&background=E5E7EB&color=1F2937&rounded=true','https://biharheritage.org','hello@biharheritage.org','+91 88888 77777','Patna, Bihar','active','2026-06-12 05:00:05'),(7,'IIT Patna','iit-patna','Technology & Research Partner','Fostering innovation and technological advancement in the region.','Indian Institute of Technology Patna is one of the new IITs established by an Act of the Indian Parliament.','To generate new knowledge through research.','To be a premier center of technical education and research.','https://ui-avatars.com/api/?name=IITP&background=E5E7EB&color=1F2937&rounded=true','https://iitp.ac.in','info@iitp.ac.in','+91 6115 233000','Bihta, Patna, Bihar','active','2026-06-12 05:00:05'),(8,'Bihar State Tourism Development Corporation','bstdc','Tourism Operations Partner','Operating tourist bungalows and managing infrastructural tourism assets.','BSTDC develops tourism infrastructure and operates properties across Bihar to facilitate tourists.','To provide affordable and quality infrastructure to tourists.','To make Bihar a world-class tourism hub.','https://ui-avatars.com/api/?name=BSTDC&background=E5E7EB&color=1F2937&rounded=true','https://bstdc.bihar.gov.in','contact@bstdc.bihar.gov.in','+91 612 2225411','Bir Chand Patel Path, Patna, Bihar','active','2026-06-12 05:00:05');
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `email_idx` (`email`),
  KEY `token_idx` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `gateway` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'captured',
  `reference_type` varchar(50) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'Manage Settings','manage_settings','Global','Modify site config, database, and system variables.'),(2,'Manage Users','manage_users','Users','Add, edit, remove, and manage user roles.'),(3,'Manage CMS','manage_cms','Pages','Update static pages layout, variables, and HTML.'),(4,'Manage SEO','manage_seo','SEO','Manage canonicals, sitemaps, metatags, and OG records.'),(5,'Manage Media','manage_media','Media','Drag-drop media hub directories, sizing, WebP compressions.'),(6,'Manage Tourism','manage_tourism','Tourism','Coordinate circuits, district fields, attractions CRUD.'),(7,'Manage Events','manage_events','Events','Control ticket settings, organizer, banner configurations.'),(8,'Manage Gallery','manage_gallery','Gallery','Add photographer entries, categories, and lazy-loaders.'),(9,'Manage Blogs','manage_blogs','Blogs','Schedule posts, tags, autosaves, and revisions rollback.'),(10,'Manage Marketplace','manage_marketplace','Marketplace','Track product catalog, stocks, and order dispatch.'),(11,'Manage Directory','manage_directory','Directory','Approve business directories, badges, and feedback.'),(12,'Manage Social','manage_social','Social','Embed YouTube feeds, Instagram Reels, and links.'),(13,'Manage Branding','manage_branding','Branding','Modify official brand colors, logos, and favicons.'),(14,'View Activity Logs','view_activity_logs','Logs','Inspect user device login audits.'),(15,'View Notifications','view_notifications','Notifications','Inspect unread system counts.');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolios`
--

DROP TABLE IF EXISTS `portfolios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `media_type` enum('image','video','link') NOT NULL,
  `media_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `artist_id` (`artist_id`),
  CONSTRAINT `portfolios_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolios`
--

LOCK TABLES `portfolios` WRITE;
/*!40000 ALTER TABLE `portfolios` DISABLE KEYS */;
INSERT INTO `portfolios` VALUES (1,1,'Purvi & Kajri Melodies Recital','A live recording of seasonal songs from the heart of rural Bihar.','video','https://www.youtube.com/embed/dQw4w9WgXcQ','2026-06-11 12:09:44'),(2,1,'Chhath Mahaparv Folk Song Collection','Traditional tunes dedicated to Chhathi Maiya, capturing the true essence of spiritual Bihar.','link','https://open.spotify.com/album/mock-album-id','2026-06-11 12:09:44');
/*!40000 ALTER TABLE `portfolios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artisan_id` int(11) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `materials` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 10,
  `gst_rate` decimal(5,2) DEFAULT 18.00,
  `rating` decimal(3,2) DEFAULT 5.00,
  `reviews_count` int(11) DEFAULT 0,
  `is_handmade` tinyint(1) DEFAULT 1,
  `is_bestseller` tinyint(1) DEFAULT 0,
  `location` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `artisan_id` (`artisan_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`artisan_id`) REFERENCES `artisans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Madhubani Paintings','Madhubani Peacock Painting','madhubani-peacock','Authentic Madhubani peacock art painted on handmade paper.','Handmade paper, natural dyes',4500.00,15,18.00,5.00,0,1,1,'Madhubani','https://images.unsplash.com/photo-1582201943021-e8e5b66d43cc?w=800','active','2026-06-11 17:11:07'),(2,2,'Traditional Decor','Handmade Sujani Quilt','sujani-quilt','Beautifully embroidered traditional Sujani quilt.','Cotton, silk threads',8500.00,5,18.00,5.00,0,1,0,'Muzaffarpur','https://images.unsplash.com/photo-1620706857370-e1b9770e8bb1?w=800','active','2026-06-11 17:11:07'),(3,3,'Handicrafts','Sikki Grass Basket','sikki-basket','Eco-friendly handwoven basket made from golden Sikki grass.','Sikki grass',1200.00,20,18.00,5.00,0,1,1,'Darbhanga','https://images.unsplash.com/photo-1605297926189-913a863771fb?w=800','active','2026-06-11 17:11:07'),(4,1,'Cultural Souvenirs','Bodh Gaya Buddha Sculpture','buddha-sculpture','Intricately carved stone sculpture of Lord Buddha.','Sandstone',3500.00,10,18.00,5.00,0,1,1,'Bodh Gaya','https://images.unsplash.com/photo-1598506822452-f4185799aee9?w=800','active','2026-06-11 17:11:07'),(5,1,'Books & Heritage Collections','Nalanda Heritage Book Set','nalanda-books','A comprehensive collection of books on Nalanda history.','Paper',2500.00,50,18.00,5.00,0,0,0,'Nalanda','https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800','active','2026-06-11 17:11:07'),(6,2,'Festival Collections','Chhath Festival Gift Box','chhath-gift-box','A premium gift box containing traditional Chhath Puja offerings.','Bamboo, wheat, jaggery',1500.00,100,18.00,5.00,0,1,1,'Patna','https://images.unsplash.com/photo-1606788075765-d51452f12c19?w=800','active','2026-06-11 17:11:07'),(7,3,'Traditional Decor','Terracotta Village Art','terracotta-art','Decorative terracotta horse figure for home decor.','Baked Clay',900.00,25,18.00,5.00,0,1,0,'Patna','https://images.unsplash.com/photo-1614050800720-3b02ddba753a?w=800','active','2026-06-11 17:11:07');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue_jobs`
--

DROP TABLE IF EXISTS `queue_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handler` varchar(100) NOT NULL,
  `payload` text NOT NULL,
  `status` enum('pending','running','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue_jobs`
--

LOCK TABLES `queue_jobs` WRITE;
/*!40000 ALTER TABLE `queue_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `queue_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quizzes`
--

LOCK TABLES `quizzes` WRITE;
/*!40000 ALTER TABLE `quizzes` DISABLE KEYS */;
INSERT INTO `quizzes` VALUES (1,1,'Which town is the epicentre of Madhubani Painting?','Patna','Madhubani/Jitwarpur','Gaya','Bhagalpur','B'),(2,2,'Which emperor is associated with the propagation of Buddhism from Pataliputra?','Emperor Ashoka','Chandragupta Maurya','Sher Shah Suri','Samudragupta','A');
/*!40000 ALTER TABLE `quizzes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_type` varchar(50) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,'destination',1,6,5,'Highly peaceful atmosphere, clean temple complex and outstanding histories.','2026-06-11 12:09:44'),(2,'destination',2,6,4,'Breathtaking red brick ruins, hiring a guide is highly recommended to inspect details.','2026-06-11 12:09:44');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,9),(2,10),(2,11),(2,12),(2,13),(2,14),(2,15),(3,3),(3,5),(3,6),(3,7),(3,8),(3,9),(3,10),(3,11),(3,12),(4,5),(4,8),(4,9);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','super_admin','Full platform administrative control and configuration rights.'),(2,'Admin','admin','Manages content, directories, SEO settings, and files.'),(3,'Editor','editor','Edits and drafts destinations, blogs, events, and marketplace products.'),(4,'Contributor','contributor','Creates draft posts, reviews, and edits content submission drafts.'),(8,'User','user','General frontend access to tours, marketplace purchases, bookings, and profile.');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saved_places`
--

DROP TABLE IF EXISTS `saved_places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saved_places` (
  `user_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`destination_id`),
  KEY `destination_id` (`destination_id`),
  CONSTRAINT `saved_places_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `saved_places_ibfk_2` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saved_places`
--

LOCK TABLES `saved_places` WRITE;
/*!40000 ALTER TABLE `saved_places` DISABLE KEYS */;
/*!40000 ALTER TABLE `saved_places` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_settings`
--

DROP TABLE IF EXISTS `seo_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(100) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `open_graph_image` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `twitter_card` varchar(50) DEFAULT 'summary_large_image',
  `schema_markup` text DEFAULT NULL,
  `robots_settings` varchar(50) DEFAULT 'index, follow',
  `sitemap_settings` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `route` (`route`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_settings`
--

LOCK TABLES `seo_settings` WRITE;
/*!40000 ALTER TABLE `seo_settings` DISABLE KEYS */;
INSERT INTO `seo_settings` VALUES (2,'/testroute','Updated Title','','',NULL,NULL,NULL,NULL,'summary_large_image',NULL,'index, follow',NULL);
/*!40000 ALTER TABLE `seo_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','Bihar Vihaan Enterprise'),(2,'support_email','support@biharvihaan.com'),(3,'gst_number','10AAAAA1111A1Z1'),(4,'test_key','test_val'),(6,'site_logo',''),(7,'site_favicon',''),(8,'contact_phone','9999999999'),(9,'contact_email',''),(10,'contact_address',''),(11,'social_facebook',''),(12,'social_twitter',''),(13,'social_instagram','');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_feeds`
--

DROP TABLE IF EXISTS `social_feeds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` enum('youtube','instagram') NOT NULL,
  `video_id` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT 'General',
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_feeds`
--

LOCK TABLES `social_feeds` WRITE;
/*!40000 ALTER TABLE `social_feeds` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_feeds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_links`
--

DROP TABLE IF EXISTS `social_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` varchar(50) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `is_enabled` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform` (`platform`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_links`
--

LOCK TABLES `social_links` WRITE;
/*!40000 ALTER TABLE `social_links` DISABLE KEYS */;
INSERT INTO `social_links` VALUES (1,'Facebook','https://facebook.com/biharvihaan',1,'2026-06-12 09:14:11'),(2,'Instagram','https://instagram.com/biharvihaan',1,'2026-06-12 09:14:11'),(3,'YouTube','https://youtube.com/biharvihaan',1,'2026-06-12 09:14:11'),(4,'LinkedIn','https://linkedin.com/biharvihaan',1,'2026-06-12 09:14:11'),(5,'Pinterest','https://pinterest.com/biharvihaan',1,'2026-06-12 09:14:11'),(6,'Twitter/X','https://twitter.com/biharvihaan',1,'2026-06-12 09:14:11'),(7,'WhatsApp','https://whatsapp.com/biharvihaan',1,'2026-06-12 09:14:11');
/*!40000 ALTER TABLE `social_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_courses`
--

DROP TABLE IF EXISTS `student_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `progress` int(11) DEFAULT 0,
  `completed` tinyint(1) DEFAULT 0,
  `certificate_hash` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `student_courses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_courses`
--

LOCK TABLES `student_courses` WRITE;
/*!40000 ALTER TABLE `student_courses` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trip_planner_settings`
--

DROP TABLE IF EXISTS `trip_planner_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trip_planner_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trip_planner_settings`
--

LOCK TABLES `trip_planner_settings` WRITE;
/*!40000 ALTER TABLE `trip_planner_settings` DISABLE KEYS */;
INSERT INTO `trip_planner_settings` VALUES (1,'prompt_template','You are a professional tour guide in Bihar. Create a detailed itinerary for a {duration} day trip starting from {start_city} for a {group_type} group with a {budget} budget. The style should be {style} and interests are: {interests}.','2026-06-12 09:14:11'),(2,'default_budget_breakdown','{\"transport\": 30, \"accommodation\": 40, \"food\": 20, \"sightseeing\": 10}','2026-06-12 09:14:11'),(3,'seasonal_recommendations','{\"Winter\": [\"Rajgir Hot Springs\", \"Sonepur Mela\", \"Patna Sahib\"], \"Monsoon\": [\"Kakolat Waterfall\", \"Telhar Kund\", \"Rohtasgarh\"], \"Summer\": [\"Bodh Gaya Meditation\", \"Nalanda Museum\"]}','2026-06-12 09:14:11'),(4,'hidden_gems','[\"Valmiki Nagar Tiger Reserve\", \"Sher Shah Suri Tomb\", \"Vikramshila Ruins\", \"Lomas Rishi Caves\"]','2026-06-12 09:14:11');
/*!40000 ALTER TABLE `trip_planner_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_devices`
--

DROP TABLE IF EXISTS `user_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `device_fingerprint` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `last_active_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_devices`
--

LOCK TABLES `user_devices` WRITE;
/*!40000 ALTER TABLE `user_devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_preferences`
--

DROP TABLE IF EXISTS `user_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_preferences` (
  `user_id` int(11) NOT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`preferences`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_preferences`
--

LOCK TABLES `user_preferences` WRITE;
/*!40000 ALTER TABLE `user_preferences` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `status` enum('active','suspended') DEFAULT 'active',
  `two_factor_secret` varchar(100) DEFAULT NULL,
  `two_factor_enabled` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `email_2` (`email`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','admin@biharvihaan.com','$2y$10$wRqui7mBO.xuTnnitmKU7OzswIPAEA5rHiSl2h.rc/KCO/GnLiWyW','+919999999999',1,'active',NULL,0,'2026-06-11 12:09:44','2026-06-12 10:21:13',NULL),(2,'Rajesh Kumar','rajesh.tourism@biharvihaan.com','$2y$10$EL4DdKalO9KgSoko8ejkDuzXIG9jCbr9hMCwetvliS2mNHhZAa1NC','+919876543210',3,'active',NULL,0,'2026-06-11 12:09:44','2026-06-12 09:37:05',NULL),(3,'Priya Singh','priya.recruitment@biharvihaan.com','$2y$10$EL4DdKalO9KgSoko8ejkDuzXIG9jCbr9hMCwetvliS2mNHhZAa1NC','+919888888888',2,'active',NULL,0,'2026-06-11 12:09:44','2026-06-12 09:37:05',NULL),(4,'Amit Handicrafts','amit.business@gmail.com','$2y$10$EL4DdKalO9KgSoko8ejkDuzXIG9jCbr9hMCwetvliS2mNHhZAa1NC','+917777777777',3,'active',NULL,0,'2026-06-11 12:09:44','2026-06-12 09:37:05',NULL),(5,'Sanjeev Folk Singer','sanjeev.folk@gmail.com','$2y$10$EL4DdKalO9KgSoko8ejkDuzXIG9jCbr9hMCwetvliS2mNHhZAa1NC','+916666666666',8,'active',NULL,0,'2026-06-11 12:09:44','2026-06-12 09:37:05',NULL),(6,'Rohan Verma','rohan.traveler@gmail.com','$2y$10$EL4DdKalO9KgSoko8ejkDuzXIG9jCbr9hMCwetvliS2mNHhZAa1NC','+915555555555',8,'active',NULL,0,'2026-06-11 12:09:44','2026-06-12 09:37:05',NULL),(7,'kaushik kishor gupta','kaushikkishorgupta@gmail.com','$2y$10$EL4DdKalO9KgSoko8ejkDuzXIG9jCbr9hMCwetvliS2mNHhZAa1NC','+919123266460',8,'active',NULL,0,'2026-06-11 12:50:47','2026-06-12 09:37:05',NULL),(10,'Audit Tester','audit.tester@gmail.com','$2y$10$Fo6z9j.8RC3KbTmR/F9IM.dutl8ki519yRWjTyvOFHX5vpzfTD892','+919876543210',8,'active',NULL,0,'2026-06-12 10:19:03','2026-06-12 10:19:03',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_profiles`
--

DROP TABLE IF EXISTS `vendor_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `store_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `commission_rate` decimal(5,2) DEFAULT 10.00,
  `status` enum('pending','approved','suspended') DEFAULT 'approved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `vendor_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_profiles`
--

LOCK TABLES `vendor_profiles` WRITE;
/*!40000 ALTER TABLE `vendor_profiles` DISABLE KEYS */;
INSERT INTO `vendor_profiles` VALUES (1,4,'Mithila Mahila Cooperative','Sourcing Madhubani home decor and handloom accessories from rural women cooperatives.',8.00,'approved','2026-06-11 12:09:44');
/*!40000 ALTER TABLE `vendor_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_product` (`user_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-12 16:01:40
