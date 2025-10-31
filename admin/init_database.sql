-- Create database
CREATE DATABASE IF NOT EXISTS `FLICK-FIX` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `FLICK-FIX`;

-- ===========================
-- USERS TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) DEFAULT NULL,
  `username` VARCHAR(100) UNIQUE NOT NULL,
  `email` VARCHAR(150) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user', 'admin') DEFAULT 'user',
  `profile_pic` VARCHAR(255) DEFAULT 'default.png',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default Admin (password = admin123)
INSERT INTO `users` (first_name, last_name, username, email, password, role)
VALUES 
('Admin', 'User', 'admin', 'admin@flickfix.com',
 '$2y$10$rU95ZPCMUkPXD5j2uNu8DeNATBI8U8a8EqAK5RAIUzbgY4Qw4ixku', 'admin');

-- ===========================
-- MOVIES TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS `movies` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `year` YEAR DEFAULT NULL,
  `runtime` VARCHAR(50) DEFAULT NULL,
  `mpaa` VARCHAR(20) DEFAULT NULL,
  `release_date` DATE DEFAULT NULL,
  `director` VARCHAR(255) DEFAULT NULL,
  `stars` VARCHAR(255) DEFAULT NULL,
  `description` TEXT,
  `poster` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===========================
-- SAMPLE 3 FAMOUS MOVIES
-- ===========================
INSERT INTO `movies` (`title`, `year`, `runtime`, `mpaa`, `release_date`, `director`, `stars`, `description`, `poster`)
VALUES
('Inception', 2010, '148 min', 'PG-13', '2010-07-16', 'Christopher Nolan', 
 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page', 
 'A thief who enters the dreams of others to steal secrets from their subconscious.', 
 'inception.jpg'),

('The Dark Knight', 2008, '152 min', 'PG-13', '2008-07-18', 'Christopher Nolan', 
 'Christian Bale, Heath Ledger, Aaron Eckhart', 
 'Batman faces the Joker, a criminal mastermind who wants to plunge Gotham into chaos.', 
 'dark_knight.jpg'),

('Interstellar', 2014, '169 min', 'PG-13', '2014-11-07', 'Christopher Nolan', 
 'Matthew McConaughey, Anne Hathaway, Jessica Chastain', 
 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.', 
 'interstellar.jpg');


