-- Bihar Vihaan Enterprise 3.0 Database Schema & Sample Data

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- 1. roles table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `description` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. permissions table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `description` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. role_permissions table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` INT NOT NULL,
  `permission_id` INT NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. users table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NULL,
  `role_id` INT NOT NULL,
  `status` ENUM('active', 'suspended') DEFAULT 'active',
  `two_factor_secret` VARCHAR(100) NULL,
  `two_factor_enabled` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  INDEX (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. activity_logs table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `action` VARCHAR(100) NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` VARCHAR(255) NULL,
  `details` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. destinations table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `destinations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(50) NOT NULL, -- Spiritual, Heritage, Nature, Art, Adventure
  `location` VARCHAR(100) NOT NULL,
  `latitude` DECIMAL(10,8) NULL,
  `longitude` DECIMAL(11,8) NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `views_count` INT DEFAULT 0,
  `rating` DECIMAL(2,1) DEFAULT 5.0,
  `status` VARCHAR(20) DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. attractions table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `attractions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `destination_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `image_url` VARCHAR(255) NULL,
  `distance_km` DECIMAL(5,2) NULL,
  FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 8. itineraries table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `itineraries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL,
  `duration_days` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 9. itinerary_days table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `itinerary_days` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `itinerary_id` INT NOT NULL,
  `day_number` INT NOT NULL,
  `activities` TEXT NOT NULL,
  FOREIGN KEY (`itinerary_id`) REFERENCES `itineraries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 10. festivals table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `festivals` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `date` DATE NOT NULL,
  `season` VARCHAR(50) NOT NULL,
  `location` VARCHAR(100) NOT NULL,
  `image_url` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 11. events table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  `location` VARCHAR(150) NOT NULL,
  `price` DECIMAL(10,2) DEFAULT 0.00,
  `max_tickets` INT NOT NULL,
  `available_tickets` INT NOT NULL,
  `organizer_id` INT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'active',
  FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 12. events_registrations table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events_registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `ticket_count` INT NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `payment_status` VARCHAR(20) DEFAULT 'pending',
  `ticket_code` VARCHAR(50) NOT NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 13. bookings table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `booking_type` ENUM('hotel', 'tour', 'guide', 'transport') NOT NULL,
  `item_name` VARCHAR(150) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NULL,
  `quantity_or_guests` INT NOT NULL,
  `details` TEXT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 14. payments table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `order_id` VARCHAR(100) NOT NULL,
  `transaction_id` VARCHAR(100) NOT NULL UNIQUE,
  `amount` DECIMAL(10,2) NOT NULL,
  `gateway` VARCHAR(50) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'captured',
  `reference_type` VARCHAR(50) NOT NULL,
  `reference_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 15. internships table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `internships` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `department` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `duration` VARCHAR(50) NOT NULL,
  `requirements` TEXT NOT NULL,
  `stipend` VARCHAR(50) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 16. job_applications table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `job_applications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `internship_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `resume_path` VARCHAR(255) NOT NULL,
  `cover_letter` TEXT NULL,
  `status` VARCHAR(20) DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 17. artists table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `artists` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `stage_name` VARCHAR(100) NOT NULL,
  `category` ENUM('folk_musician', 'content_creator', 'photographer', 'writer', 'journalist', 'other') NOT NULL,
  `bio` TEXT NOT NULL,
  `portfolio_url` VARCHAR(255) NULL,
  `verification_status` VARCHAR(20) DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 18. portfolios table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `portfolios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `artist_id` INT NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL,
  `media_type` ENUM('image', 'video', 'link') NOT NULL,
  `media_url` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 19. collaboration_requests table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `collaboration_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sender_id` INT NOT NULL,
  `receiver_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 20. business_listings table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `business_listings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `owner_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `address` TEXT NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `website` VARCHAR(100) NULL,
  `whatsapp_number` VARCHAR(20) NULL,
  `plan` ENUM('free', 'premium', 'featured') DEFAULT 'free',
  `verification_status` VARCHAR(20) DEFAULT 'pending',
  `logo_url` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 21. business_leads table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `business_leads` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `business_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`business_id`) REFERENCES `business_listings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 22. advertisement_clicks table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `advertisement_clicks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ad_name` VARCHAR(100) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `clicked_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 23. saved_places table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saved_places` (
  `user_id` INT NOT NULL,
  `destination_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `destination_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 24. user_preferences table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_preferences` (
  `user_id` INT NOT NULL PRIMARY KEY,
  `preferences` JSON NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 25. notifications table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `type` VARCHAR(20) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'unread',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ========================================================
-- ENTERPRISE 3.0 ADDITIONAL TABLES
-- ========================================================

-- --------------------------------------------------------
-- 26. destination_images table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `destination_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `destination_id` INT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 27. destination_videos table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `destination_videos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `destination_id` INT NOT NULL,
  `video_url` VARCHAR(255) NOT NULL,
  FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 28. business_categories table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `business_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 29. reviews table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `reference_type` VARCHAR(50) NOT NULL, -- destination, business, event
  `reference_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `rating` TINYINT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 30. blogs table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `content` TEXT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `author_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 31. news table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `news` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `category` ENUM('Politics', 'Tourism', 'Education', 'Startup', 'Culture', 'Sports') NOT NULL,
  `content` TEXT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `views_count` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 32. newsletter_subscribers table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 33. contact_messages table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 34. settings table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(50) NOT NULL UNIQUE,
  `setting_value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ========================================================
-- SEED DATA
-- ========================================================

-- Seed Roles
INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'super_admin', 'Full access to system controls, role management, payments, logs, and content moderators.'),
(2, 'admin', 'Access to booking management, business verifications, and career portal.'),
(3, 'editor', 'Can manage news, success stories, press releases, media galleries, and video libraries.'),
(4, 'business_manager', 'Owner/Manager of premium or free business listings.'),
(5, 'tourism_manager', 'Can publish destinations, itinerary helpers, and attractions.'),
(6, 'content_manager', 'Can moderate media uploads, event pages, and talent portfolios.'),
(7, 'moderator', 'Basic reports management and comment moderation.'),
(8, 'user', 'Standard tourist, applicant, or site browser.');

-- Seed Permissions
INSERT INTO `permissions` (`id`, `name`, `description`) VALUES
(1, 'manage_users', 'Can create, edit, delete, or suspend any user accounts'),
(2, 'view_logs', 'Can inspect access history, activity logs, and IP security logs'),
(3, 'manage_roles', 'Can map permissions to roles or change user ranks'),
(4, 'verify_businesses', 'Can review and issue verification badges to businesses'),
(5, 'manage_payments', 'Can view transactions, refunds, and trigger payments analytics'),
(6, 'publish_tourism', 'Can add or edit tourist hotspots, guides, and recommended trails'),
(7, 'manage_internships', 'Can edit jobs, review applications, and view applicant resumes'),
(8, 'moderate_talent', 'Can verify artist credentials, hide portfolios, or accept collaboration flags');

-- Map Permissions to Roles
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8),
(2, 4), (2, 5), (2, 7), (2, 8),
(3, 6),
(5, 6),
(6, 8);

-- Seed Users (default password: 'password')
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role_id`, `status`, `two_factor_enabled`) VALUES
(1, 'Super Admin Vihaan', 'admin@biharvihaan.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+919999999999', 1, 'active', 0),
(2, 'Rajesh Kumar', 'rajesh.tourism@biharvihaan.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+919876543210', 5, 'active', 0),
(3, 'Priya Singh', 'priya.recruitment@biharvihaan.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+919888888888', 2, 'active', 0),
(4, 'Amit Handicrafts', 'amit.business@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+917777777777', 4, 'active', 0),
(5, 'Sanjeev Folk Singer', 'sanjeev.folk@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+916666666666', 8, 'active', 0),
(6, 'Rohan Verma', 'rohan.traveler@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+915555555555', 8, 'active', 0);

-- Seed Destinations
INSERT INTO `destinations` (`id`, `name`, `description`, `category`, `location`, `latitude`, `longitude`, `image_url`, `views_count`, `rating`) VALUES
(1, 'Mahabodhi Temple Complex', 'The sacred site where Prince Siddhartha attained enlightenment and became Lord Buddha. A UNESCO World Heritage Site featuring the holy Bodhi tree, serene meditating environment, and beautiful architectures from around Asia.', 'Spiritual', 'Bodh Gaya, Gaya', 24.69510000, 84.99140000, 'https://images.unsplash.com/photo-1627894483216-2138af692e2e?q=80&w=1200&auto=format&fit=crop', 5420, 4.9),
(2, 'Nalanda Mahavihara Ruins', 'The legendary ancient Buddhist monastery and learning hub of India, hosting scholars from China, Tibet, and Korea. Today, it stands as an outstanding archaeological site of red-brick classrooms, stupas, and open gardens.', 'Heritage', 'Nalanda', 25.13470000, 85.42060000, 'https://images.unsplash.com/photo-1599932738712-4d0f622f96cf?q=80&w=1200&auto=format&fit=crop', 3890, 4.8),
(3, 'Rajgir Glass Bridge & Ropeway', 'Nestled between five scenic hills, Rajgir features thermal hot springs, the historic Cyclopean Wall, and the modern Glass Skywalk inside the Rajgir Nature Safari. Take a ropeway ride up to the Vishwa Shanti Stupa.', 'Adventure', 'Rajgir, Nalanda', 25.02980000, 85.41740000, 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=1200&auto=format&fit=crop', 4750, 4.7),
(4, 'Valmiki National Park & Tiger Reserve', 'Bihar\'s only national park and tiger reserve located at the India-Nepal border along the banks of the Gandak river. Home to Bengal tigers, Indian rhinoceros, wild dogs, flying foxes, and diverse Himalayan flora.', 'Nature', 'West Champaran', 27.30000000, 84.20000000, 'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?q=80&w=1200&auto=format&fit=crop', 2110, 4.5),
(5, 'Sher Shah Suri Tomb', 'An outstanding specimen of Indo-Islamic architecture built in the middle of a square artificial lake. The red sandstone mausoleum honors Sher Shah Suri, the legendary Afghan ruler who established the Grand Trunk Road.', 'Heritage', 'Sasaram, Rohtas', 24.95350000, 84.02050000, 'https://images.unsplash.com/photo-1585135497273-1a86b09fe70e?q=80&w=1200&auto=format&fit=crop', 1980, 4.6),
(6, 'Takht Sri Patna Sahib', 'One of the five takhts of Sikhism, marking the birthplace of the tenth Sikh Guru, Guru Gobind Singh Ji. The magnificent white marble Gurudwara sits majestically on the banks of the river Ganga.', 'Spiritual', 'Patna City, Patna', 25.59740000, 85.22670000, 'https://images.unsplash.com/photo-1564507592333-c60657eea523?q=80&w=1200&auto=format&fit=crop', 3200, 4.8);

-- Seed Destination Images (Enterprise 3.0)
INSERT INTO `destination_images` (`destination_id`, `image_url`) VALUES
(1, 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=1200'),
(1, 'https://images.unsplash.com/photo-1564507592333-c60657eea523?q=80&w=1200'),
(2, 'https://images.unsplash.com/photo-1585135497273-1a86b09fe70e?q=80&w=1200');

-- Seed Business Categories (Enterprise 3.0)
INSERT INTO `business_categories` (`name`, `slug`) VALUES
('Hotels & Homestays', 'hotels'),
('Restaurants & Cafes', 'restaurants'),
('Travel Agencies', 'agencies'),
('Tour Guides', 'guides'),
('Handicrafts & Arts', 'handicrafts');

-- Seed Reviews (Enterprise 3.0)
INSERT INTO `reviews` (`reference_type`, `reference_id`, `user_id`, `rating`, `comment`) VALUES
('destination', 1, 6, 5, 'Highly peaceful atmosphere, clean temple complex and outstanding histories.'),
('destination', 2, 6, 4, 'Breathtaking red brick ruins, hiring a guide is highly recommended to inspect details.');

-- Seed News (Enterprise 3.0)
INSERT INTO `news` (`title`, `category`, `content`, `image_url`, `views_count`) VALUES
('Sonepur Mela Cultural Stage Expansion Announced', 'Culture', 'The state tourism board has announced structural budget allocations to expand stages and add international folk showcases at Sonepur Saran.', 'https://images.unsplash.com/photo-1533105079780-92b9be482077?q=80&w=600', 450),
('Bihar Startups Hit Record Funding in 2026', 'Startup', 'New tech ventures in Patna, Bihar, specializing in Agri-tech, organic products logistics and handicrafts export hit positive VC funding loops.', 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600', 920),
('Valmiki National Park Tourism Eco-Cottages Open', 'Tourism', 'New solar-powered logs and safari cottage packages have officially been open for travelers booking custom wildlife packages.', 'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?q=80&w=600', 230);

-- Seed Blogs (Enterprise 3.0)
INSERT INTO `blogs` (`title`, `content`, `image_url`, `author_id`) VALUES
('Exploring Nalanda Ruins: A Walking Guide', 'Nalanda was a university classrooms hosting 10,000 students and 2,000 teachers in the ancient times. Let\'s unpack its red-brick stupas...', 'https://images.unsplash.com/photo-1599932738712-4d0f622f96cf?q=80&w=600', 2);

-- Seed Settings (Enterprise 3.0)
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'Bihar Vihaan Portal'),
('support_email', 'support@biharvihaan.com'),
('gst_number', '10AAAAA1111A1Z1');

-- Seed Nearby Attractions
INSERT INTO `attractions` (`id`, `destination_id`, `name`, `description`, `image_url`, `distance_km`) VALUES
(1, 1, 'Great Buddha Statue', 'An iconic 80-foot stone sculpture of Buddha sitting in a meditative posture, unveiled by the Dalai Lama.', NULL, 1.20),
(2, 1, 'Dungeshwari Cave Temples', 'Where Lord Buddha meditated for six years before descending to Gaya, also known as Mahakala caves.', NULL, 12.00),
(3, 2, 'Nalanda Archaeological Museum', 'Houses rich collection of seals, stone sculptures, coins, and bronzes excavated from Nalanda Mahavihara.', NULL, 0.50),
(4, 3, 'Vishwa Shanti Stupa', 'A majestic white peace pagoda built atop the Ratnagiri hill, reachable by single-seater ropeway.', NULL, 3.50),
(5, 3, 'Ghora Katora Lake', 'A pristine, eco-friendly lake surrounded by hills, featuring a tall Buddha statue standing on the water.', NULL, 5.00);

-- Seed Festivals
INSERT INTO `festivals` (`id`, `name`, `description`, `date`, `season`, `location`, `image_url`) VALUES
(1, 'Chhath Puja', 'The grandest, highly spiritual and unique solar festival of Bihar. Devotees offer arghya (prayers) to the setting and rising Sun on the banks of holy rivers Ganga and Sone, observing rigorous fasting without water.', '2026-11-15', 'Winter', 'All Bihar & River Banks', 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=600&auto=format&fit=crop'),
(2, 'Sonepur Mela', 'One of Asia\'s largest animal fairs held during Kartik Purnima. Extending for a month at the confluence of Ganges and Gandak, the fair features local folk performances, diverse handcrafts, and elephant parades.', '2026-11-23', 'Winter', 'Sonepur, Saran', 'https://images.unsplash.com/photo-1533105079780-92b9be482077?q=80&w=600&auto=format&fit=crop'),
(3, 'Rajgir Mahotsav', 'A vibrant three-day cultural extravaganza held annually in Rajgir. Features classical music, folk dancers from Bihar, traditional culinary stalls, and sporting events set against the historical hills.', '2026-10-25', 'Winter', 'Qila Maidan, Rajgir', 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop'),
(4, 'Sama Chakeva', 'A charming winter festival celebrating the bond between brothers and sisters. Girls model small clay birds and sing traditional folk songs to welcome migrating winter birds.', '2026-11-19', 'Winter', 'Mithila Region', 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=600&auto=format&fit=crop');

-- Seed Events
INSERT INTO `events` (`id`, `title`, `description`, `date`, `time`, `location`, `price`, `max_tickets`, `available_tickets`, `organizer_id`, `image_url`) VALUES
(1, 'Madhubani Art & Craft Workshop', 'Learn the traditional style of Madhubani painting from state-award winning artisans. All materials including hand-made paper, organic colors, and brushes will be provided.', '2026-07-10', '10:00:00', 'Upendra Maharathi Shilp Anusandhan Sansthan, Patna', 499.00, 30, 28, 1, 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?q=80&w=600&auto=format&fit=crop'),
(2, 'Sufi & Folk Music Night', 'An evening of mesmerizing Sufiana Kalam and traditional Bhojpuri and Maithili folk songs performed by local legend Sanjeev Kumar and group.', '2026-08-15', '18:30:00', 'Bhartiya Nritya Kala Mandir, Patna', 250.00, 150, 150, 1, 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop'),
(3, 'Bodh Gaya Heritage Walk', 'A guided historical tour explaining the Mahabodhi ruins, the ancient Monasteries representing international architectures, and Buddhist philosophy.', '2026-07-05', '06:30:00', 'Mahabodhi Main Gate, Bodh Gaya', 150.00, 20, 19, 2, 'https://images.unsplash.com/photo-1627894483216-2138af692e2e?q=80&w=600&auto=format&fit=crop');

-- Seed Internships
INSERT INTO `internships` (`id`, `title`, `department`, `description`, `duration`, `requirements`, `stipend`) VALUES
(1, 'Tourism & Heritage Guide Intern', 'Tourism Intelligence', 'Engage directly with travelers, map historical points of interest, write informational blogs about Rajgir/Nalanda, and support tour planning operations.', '3 Months', 'Graduate/Undergraduate in History, Tourism, or Hospitality. Good command of Hindi & English. Knowledge of regional dialects is a plus.', '₹8,000 / month'),
(2, 'Social Media & Content Creator', 'Media & Press', 'Produce daily Instagram reels, design graphic flyers of Bihar cuisine, film mini YouTube vlogs at heritage spots, and curate newsletters.', '2 Months', 'Proficiency in video editing apps (CapCut, Premiere), graphic design skills (Canva/Illustrator), and sound understanding of digital media trends.', '₹6,000 / month'),
(3, 'Bihar Vihaan Tech Support Intern', 'IT & Operations', 'Maintain business listing portals, help debug user reports on payments, check API statuses, and coordinate with database coordinators.', '6 Months', 'Basic understanding of PHP, HTML/CSS, SQL database relations, and API endpoints.', '₹10,000 / month');

-- Seed Artists
INSERT INTO `artists` (`id`, `user_id`, `stage_name`, `category`, `bio`, `portfolio_url`, `verification_status`) VALUES
(1, 5, 'Sanjeev Folk Ensemble', 'folk_musician', 'Traditional Bhojpuri singer dedicating two decades to archiving rural melodies, Kajri, and Chhath folklore. Performed at national festivals.', 'https://youtube.com/c/mock-sanjeev-music', 'verified');

-- Seed Portfolios
INSERT INTO `portfolios` (`id`, `artist_id`, `title`, `description`, `media_type`, `media_url`) VALUES
(1, 1, 'Purvi & Kajri Melodies Recital', 'A live recording of seasonal songs from the heart of rural Bihar.', 'video', 'https://www.youtube.com/embed/dQw4w9WgXcQ'),
(2, 1, 'Chhath Mahaparv Folk Song Collection', 'Traditional tunes dedicated to Chhathi Maiya, capturing the true essence of spiritual Bihar.', 'link', 'https://open.spotify.com/album/mock-album-id');

-- Seed Business Listings
INSERT INTO `business_listings` (`id`, `owner_id`, `name`, `category`, `description`, `address`, `phone`, `email`, `website`, `whatsapp_number`, `plan`, `verification_status`) VALUES
(1, 4, 'Mithila Handicrafts Emporium', 'handicrafts', 'Authentic hand-painted Madhubani sarees, designer wall art, wooden toys, and organic tussar silk fabrics sourced directly from rural craftswomen.', 'Maurya Lok Complex, Block C, Patna', '+919431012345', 'mithila.arts@gmail.com', 'http://www.mithilahandicrafts.co.in', '+919431012345', 'premium', 'verified'),
(2, 1, 'Hotel Bodhgaya Regency', 'hotel', 'Luxury hotel offering fine dining, spa rooms, Buddhist meditation halls, and direct transport booking to Mahabodhi Temple.', 'Near Japanese Temple, Bodh Gaya', '+916312200500', 'info@bodhgayaregency.com', 'http://www.bodhgayaregency.com', '+916312200500', 'featured', 'verified'),
(3, 2, 'Bihar Heritage Travel Agency', 'agency', 'Specialized custom tours for Nalanda ruins, Rajgir nature safaris, and transport guides with air-conditioned cabs.', 'Frazer Road, Patna', '+919102345678', 'tours@biharheritage.com', NULL, '+919102345678', 'free', 'verified');

-- --------------------------------------------------------
-- 35. products table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `gst_rate` DECIMAL(5,2) DEFAULT 18.00,
  `stock` INT DEFAULT 10,
  `category` ENUM('handicrafts', 'madhubani_art', 'local_products', 'sweets') NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 36. carts table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `carts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `session_id` VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 37. cart_items table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `cart_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 38. orders table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `billing_name` VARCHAR(100) NOT NULL,
  `billing_email` VARCHAR(100) NOT NULL,
  `billing_phone` VARCHAR(20) NOT NULL,
  `billing_address` TEXT NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `gst_amount` DECIMAL(10,2) NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `payment_status` ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
  `razorpay_order_id` VARCHAR(100) NULL,
  `razorpay_payment_id` VARCHAR(100) NULL,
  `delivery_status` ENUM('processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'processing',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 39. order_items table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 40. crm_leads table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `crm_leads` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `business_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `source` VARCHAR(50) DEFAULT 'website_inquiry',
  `status` ENUM('new', 'contacted', 'qualified', 'lost', 'won') DEFAULT 'new',
  `score` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`business_id`) REFERENCES `business_listings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 41. crm_notes table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `crm_notes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT NOT NULL,
  `author_id` INT NOT NULL,
  `note` TEXT NOT NULL,
  `follow_up_date` DATE NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 42. user_devices table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_devices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) NOT NULL,
  `device_fingerprint` VARCHAR(100) NULL,
  `location` VARCHAR(100) NULL,
  `last_active_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 43. login_attempts table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(100) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `status` ENUM('success', 'failed') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Marketplace Products
INSERT INTO `products` (`name`, `description`, `price`, `gst_rate`, `stock`, `category`, `image_url`) VALUES
('Handmade Madhubani Tree of Life Painting', 'An authentic Tree of Life Madhubani painting, created by local artists using natural colors on handmade paper.', 2499.00, 12.00, 15, 'madhubani_art', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=600'),
('Bhagalpuri Tussar Silk Saree', 'Exquisite, light-weight organic Bhagalpuri Tussar Silk saree in vibrant natural dyes, representing centuries of handloom heritage.', 4500.00, 5.00, 8, 'local_products', 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600'),
('Sikki Grass Decorative Basket', 'Vibrant traditional utility basket handmade using golden Sikki grass, an ancient craft from Mithila.', 799.00, 12.00, 25, 'handicrafts', 'https://images.unsplash.com/photo-1590736969955-71cc94801759?q=80&w=600'),
('Traditional Silao Khaja Sweet Box', 'Crisp, multi-layered traditional sweet from Silao, Nalanda, awarded a GI Tag. Contains 12 pieces.', 350.00, 18.00, 50, 'sweets', 'https://images.unsplash.com/photo-1587314168485-3236d6710814?q=80&w=600');

-- Seed CRM Leads
INSERT INTO `crm_leads` (`business_id`, `name`, `email`, `phone`, `source`, `status`, `score`) VALUES
(1, 'Kumar Anupam', 'anupam@gmail.com', '+919988776655', 'website_inquiry', 'new', 35),
(1, 'Madhuri Devi', 'madhuri@gmail.com', '+919876543222', 'whatsapp', 'contacted', 60),
(2, 'Vikas Singh', 'vikas@gmail.com', '+917766554433', 'website_inquiry', 'qualified', 80);

-- Seed CRM Notes
INSERT INTO `crm_notes` (`lead_id`, `author_id`, `note`, `follow_up_date`) VALUES
(1, 4, 'Expressed interest in wholesale purchase of Madhubani paintings for corporate gifting.', '2026-06-15'),
(2, 4, 'Discussed custom saree border patterns via WhatsApp call. Sent portfolio images.', '2026-06-12'),
(3, 1, 'Inquired about booking 5 deluxe rooms for a heritage study group from Singapore.', '2026-06-18');

-- --------------------------------------------------------
-- 44. courses table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `courses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `instructor` VARCHAR(100) NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 45. lessons table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lessons` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_id` INT NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `video_url` VARCHAR(255) NOT NULL,
  `duration_mins` INT NOT NULL,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 46. quizzes table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_id` INT NOT NULL,
  `question` TEXT NOT NULL,
  `option_a` VARCHAR(255) NOT NULL,
  `option_b` VARCHAR(255) NOT NULL,
  `option_c` VARCHAR(255) NOT NULL,
  `option_d` VARCHAR(255) NOT NULL,
  `correct_option` CHAR(1) NOT NULL,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 47. student_courses table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `student_courses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `progress` INT DEFAULT 0,
  `completed` TINYINT(1) DEFAULT 0,
  `certificate_hash` VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 48. forums table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `forums` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 49. forum_topics table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `forum_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 50. forum_posts table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `topic_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 51. vendor_profiles table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `vendor_profiles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `store_name` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `commission_rate` DECIMAL(5,2) DEFAULT 10.00,
  `status` ENUM('pending', 'approved', 'suspended') DEFAULT 'approved',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 52. coupons table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `discount_type` ENUM('fixed', 'percent') DEFAULT 'fixed',
  `value` DECIMAL(10,2) NOT NULL,
  `expires_at` DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 53. blockchain_blocks table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `blockchain_blocks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `block_index` INT NOT NULL,
  `timestamp` INT NOT NULL,
  `previous_hash` VARCHAR(64) NOT NULL,
  `hash` VARCHAR(64) NOT NULL,
  `merkle_root` VARCHAR(64) NOT NULL,
  `data` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 54. queue_jobs table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `queue_jobs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `handler` VARCHAR(100) NOT NULL,
  `payload` TEXT NOT NULL,
  `status` ENUM('pending', 'running', 'completed', 'failed') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Course and Learning Hub Data
INSERT INTO `courses` (`id`, `title`, `description`, `instructor`, `image_url`) VALUES
(1, 'Madhubani Painting Masterclass', 'Learn the secrets of ancient Mithila art, traditional natural color mixing, and freehand patterns.', 'Smt. Shanti Devi (State Awardee)', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=600'),
(2, 'Bihar Tour Guide & Hospitality Training', 'Professional training program covering Buddhist heritage trails, safety tips, and communication skills.', 'Sri Rajesh Kumar (Tourism Dept.)', 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=600');

-- Seed Lessons
INSERT INTO `lessons` (`course_id`, `title`, `video_url`, `duration_mins`) VALUES
(1, 'Introduction to Mithila Motifs', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 15),
(1, 'Preparing Natural Ochre Colors', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 20),
(2, 'Overview of Mahabodhi Temple History', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 25);

-- Seed Quizzes
INSERT INTO `quizzes` (`course_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 'Which town is the epicentre of Madhubani Painting?', 'Patna', 'Madhubani/Jitwarpur', 'Gaya', 'Bhagalpur', 'B'),
(2, 'Which emperor is associated with the propagation of Buddhism from Pataliputra?', 'Emperor Ashoka', 'Chandragupta Maurya', 'Sher Shah Suri', 'Samudragupta', 'A');

-- Seed Forums
INSERT INTO `forums` (`id`, `name`, `description`, `slug`) VALUES
(1, 'Bihar Travel Discussions', 'Share itineraries, reviews, and travel tips about heritage destinations.', 'travel-talk'),
(2, 'Art & Craft Community', 'Interact with local artists, weavers, and craft promoters.', 'art-corner');

-- Seed Forum Topics
INSERT INTO `forum_topics` (`id`, `forum_id`, `user_id`, `title`) VALUES
(1, 1, 6, 'Best timeframe to visit Valmiki Tiger Reserve?'),
(2, 2, 5, 'Bhojpuri folk music fusion workshops this winter');

-- Seed Forum Posts
INSERT INTO `forum_posts` (`topic_id`, `user_id`, `content`) VALUES
(1, 6, 'Planning a weekend safari and photography tour. Which months are best for spotting wildlife?'),
(1, 2, 'October to March is ideal! The weather is cold, roads are dry, and migratory birds arrive.'),
(2, 5, 'Keen to organize Bhojpuri and Maithili folk singing collaborations with modern instruments.');

-- Seed Vendor Profiles
INSERT INTO `vendor_profiles` (`user_id`, `store_name`, `description`, `commission_rate`, `status`) VALUES
(4, 'Mithila Mahila Cooperative', 'Sourcing Madhubani home decor and handloom accessories from rural women cooperatives.', 8.00, 'approved');

-- Seed Coupons
INSERT INTO `coupons` (`code`, `discount_type`, `value`, `expires_at`) VALUES
('VIHAAN50', 'fixed', 50.00, '2027-12-31'),
('BIHAR20', 'percent', 20.00, '2027-12-31');

COMMIT;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `title` VARCHAR(150) NOT NULL,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` TEXT NULL,
  `status` ENUM('published', 'draft') DEFAULT 'published',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `page_sections` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `page_id` INT NOT NULL,
  `section_key` VARCHAR(100) NOT NULL,
  `content_json` JSON NOT NULL,
  `order_index` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `page_section_unique` (`page_id`, `section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `media` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_type` VARCHAR(50) NOT NULL,
  `size_bytes` INT NOT NULL,
  `folder` VARCHAR(100) DEFAULT 'general',
  `uploaded_by` INT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `label` VARCHAR(100) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `parent_id` INT NULL,
  `order_index` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default pages
INSERT IGNORE INTO `pages` (`id`, `slug`, `title`) VALUES 
(1, 'home', 'Homepage'),
(2, 'about', 'About Us'),
(3, 'services', 'Services'),
(4, 'tourism', 'Tourism'),
(5, 'directory', 'Directory'),
(6, 'gallery', 'Gallery'),
(7, 'marketplace', 'Marketplace'),
(8, 'contact', 'Contact Us');
