

-- ✅ Add genre column if not already present
ALTER TABLE movies 
ADD COLUMN genre VARCHAR(100) DEFAULT 'Unknown';

-- ✅ Watchlist Table (stores per-session/user watchlist)
CREATE TABLE IF NOT EXISTS watchlists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(128) NOT NULL,
  movie_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(session_id, movie_id),
  FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Actors Table (for actor/actress voting)
CREATE TABLE IF NOT EXISTS actors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ac_name VARCHAR(255) NOT NULL,
  lives_in VARCHAR(255) DEFAULT NULL,
  latest_film VARCHAR(255) DEFAULT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Actor Votes Table
CREATE TABLE IF NOT EXISTS vote_actor (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(128) DEFAULT NULL,
  actor_id INT NOT NULL,
  vote TINYINT NOT NULL CHECK (vote BETWEEN 1 AND 5),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(session_id, actor_id),
  FOREIGN KEY (actor_id) REFERENCES actors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Update existing movies with genres
UPDATE movies
SET genre = CASE
  WHEN title = 'Inception' THEN 'Sci-Fi'
  WHEN title = 'The Dark Knight' THEN 'Action'
  WHEN title = 'Interstellar' THEN 'Adventure'
  ELSE 'Drama'
END;

-- ✅ Add more sample movies with genre variety
INSERT INTO movies (title, year, runtime, mpaa, release_date, director, stars, description, poster, genre)
VALUES
('Titanic', 1997, '195 min', 'PG-13', '1997-12-19', 'James Cameron', 'Leonardo DiCaprio, Kate Winslet', 'A love story unfolds on the ill-fated Titanic.', 'titanic.jpg', 'Romance'),
('Avengers: Endgame', 2019, '181 min', 'PG-13', '2019-04-26', 'Anthony Russo, Joe Russo', 'Robert Downey Jr., Chris Evans, Scarlett Johansson', 'The Avengers assemble for one last stand against Thanos.', 'endgame.jpg', 'Action'),
('The Notebook', 2004, '123 min', 'PG-13', '2004-06-25', 'Nick Cassavetes', 'Ryan Gosling, Rachel McAdams', 'A young couple fall in love during the early years of World War II.', 'notebook.jpg', 'Romance'),
('Joker', 2019, '122 min', 'R', '2019-10-04', 'Todd Phillips', 'Joaquin Phoenix, Robert De Niro', 'A mentally troubled comedian embarks on a downward spiral into madness.', 'joker.jpg', 'Thriller'),
('The Conjuring', 2013, '112 min', 'R', '2013-07-19', 'James Wan', 'Vera Farmiga, Patrick Wilson', 'Paranormal investigators help a family terrorized by a dark presence.', 'conjuring.jpg', 'Horror'),
('Frozen', 2013, '102 min', 'PG', '2013-11-27', 'Chris Buck, Jennifer Lee', 'Kristen Bell, Idina Menzel', 'Two sisters discover the power of love and magic.', 'frozen.jpg', 'Animation');

-- ✅ Sample Actors
INSERT INTO actors (ac_name, lives_in, latest_film, photo) VALUES
('Leonardo DiCaprio', 'Los Angeles, USA', 'Killers of the Flower Moon', 'leo.jpg'),
('Christian Bale', 'London, UK', 'Thor: Love and Thunder', 'bale.jpg'),
('Anne Hathaway', 'Brooklyn, USA', 'Eileen', 'hathaway.jpg'),
('Heath Ledger', 'Perth, Australia', 'The Dark Knight', 'ledger.jpg'),
('Joaquin Phoenix', 'San Juan, Puerto Rico', 'Joker: Folie à Deux', 'phoenix.jpg'),
('Scarlett Johansson', 'New York, USA', 'Asteroid City', 'scarlett.jpg'),
('Chris Evans', 'Boston, USA', 'Ghosted', 'evans.jpg'),
('Kate Winslet', 'Reading, UK', 'Avatar: The Way of Water', 'winslet.jpg');




USE `FLICK-FIX`;

-- ✅ Contact Messages Table
CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(200) DEFAULT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Newsletter Table (email subscription)
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL UNIQUE,
  subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ News Table (admin-controlled movie news)
CREATE TABLE IF NOT EXISTS movie_news (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Movie Recommendation Table (user suggested new movies)
CREATE TABLE IF NOT EXISTS new_movies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  movie_title VARCHAR(255) NOT NULL,
  reason TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Support Teams Table
CREATE TABLE IF NOT EXISTS support_teams (
  id INT AUTO_INCREMENT PRIMARY KEY,
  team_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Scheduled Calls/Meetings Table
CREATE TABLE IF NOT EXISTS scheduled_calls (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  team_id INT NOT NULL,
  schedule_time DATETIME NOT NULL,
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (team_id) REFERENCES support_teams(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Insert Sample Support Teams
INSERT INTO support_teams (team_name, email)
VALUES
('Technical Support', 'techsupport@flickfix.com'),
('Partnerships', 'partners@flickfix.com'),
('Media Enquiries', 'media@flickfix.com')
ON DUPLICATE KEY UPDATE team_name=VALUES(team_name);




-- Use Flick-Fix Database
USE `FLICK-FIX`;

-- Reviews Table
CREATE TABLE IF NOT EXISTS `reviews` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`movie_id` VARCHAR(100) NOT NULL,
`rating` FLOAT NOT NULL,
`comment` TEXT NOT NULL,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Newsletter Table
CREATE TABLE IF NOT EXISTS `newsletter` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`email` VARCHAR(150) UNIQUE NOT NULL,
`subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact Table
CREATE TABLE IF NOT EXISTS `contact` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`name` VARCHAR(150) NOT NULL,
`email` VARCHAR(150) NOT NULL,
`message` TEXT NOT NULL,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users Table
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
VALUES ('Admin', 'User', 'admin', '[admin@flickfix.com](mailto:admin@flickfix.com)', '$2y$10$rU95ZPCMUkPXD5j2uNu8DeNATBI8U8a8EqAK5RAIUzbgY4Qw4ixku', 'admin');

-- Movies Table
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

-- Insert Sample Movies
INSERT INTO `movies` (title, year, runtime, mpaa, release_date, director, stars, description, poster)
VALUES
('Inception', 2010, '148 min', 'PG-13', '2010-07-16', 'Christopher Nolan', 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page', 'A thief who enters the dreams of others to steal secrets from their subconscious.', 'inception.jpg'),
('The Dark Knight', 2008, '152 min', 'PG-13', '2008-07-18', 'Christopher Nolan', 'Christian Bale, Heath Ledger, Aaron Eckhart', 'Batman faces the Joker, a criminal mastermind who wants to plunge Gotham into chaos.', 'dark_knight.jpg'),
('Interstellar', 2014, '169 min', 'PG-13', '2014-11-07', 'Christopher Nolan', 'Matthew McConaughey, Anne Hathaway, Jessica Chastain', 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity''s survival.', 'interstellar.jpg');

-- Trailers Table
CREATE TABLE IF NOT EXISTS `trailers` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`movie_id` INT NOT NULL,
`youtube_link` VARCHAR(255) NOT NULL,
FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
);

-- Insert Trailers 
INSERT INTO trailers (movie_id, youtube_link)
VALUES 
(1, 'https://youtu.be/Po3jStA673E?si=5PgXb8P0Nsltfcoj'),
(2, 'https://youtu.be/xenOE1Tma0A?si=lLUXNHn7VUIA-QYQ'),
(3, 'https://youtu.be/OKBMCL-frPU?si=BcsxwJxG0urxj5tj');



CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    subject VARCHAR(255),
    phone VARCHAR(100),
    message TEXT,
    created_at DATETIME
);

CREATE TABLE newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    subscribed_at DATETIME
);



-- schema.sql
-- Database: planet_db (create if needed)
CREATE DATABASE IF NOT EXISTS planet_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE planet_db;

-- ---------------------
-- mood_movies
-- ---------------------
CREATE TABLE IF NOT EXISTS mood_movies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  mood VARCHAR(50) NOT NULL,
  movie_title VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO mood_movies (mood, movie_title) VALUES
('happy', 'Avengers: Endgame'),
('happy', 'The Grand Budapest Hotel'),
('sad', 'Titanic'),
('sad', 'The Notebook'),
('heartbroken', 'Premam'),
('heartbroken', '500 Days of Summer'),
('chill', 'Frozen'),
('chill', 'Amélie'),
('adventure', 'Inception'),
('adventure', 'Interstellar'),
('adventure', 'Indiana Jones: Raiders of the Lost Ark'),
('heartbroken', 'La La Land'),
('happy', 'Guardians of the Galaxy'),
('sad', 'Manchester by the Sea'),
('chill', 'The Secret Life of Walter Mitty'),
('adventure', 'The Dark Knight');

-- ---------------------
-- quiz_questions & quiz_options
-- ---------------------
CREATE TABLE IF NOT EXISTS quiz_questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question_text VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS quiz_options (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question_id INT NOT NULL,
  option_text VARCHAR(255) NOT NULL,
  mapped_movie VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO quiz_questions (question_text) VALUES
('What pace do you prefer?'),
('Pick a lead character you relate to'),
('Choose a mood');

INSERT INTO quiz_options (question_id, option_text, mapped_movie) VALUES
(1, 'Fast & intense', 'Inception'),
(1, 'Slow & emotional', 'The Notebook'),
(1, 'Relaxed & fun', 'Guardians of the Galaxy'),
(2, 'Brave/heroic', 'The Dark Knight'),
(2, 'Romantic dreamer', 'La La Land'),
(2, 'Curious explorer', 'Interstellar'),
(3, 'I want to cry', 'Titanic'),
(3, 'I want to laugh', 'The Grand Budapest Hotel'),
(3, 'I want to think', 'Joker');

-- ---------------------
-- upcoming_releases
-- ---------------------
CREATE TABLE IF NOT EXISTS upcoming_releases (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  release_date DATETIME NOT NULL,
  poster VARCHAR(255) DEFAULT NULL,
  trailer_url VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO upcoming_releases (id,title, release_date, poster, trailer_url) VALUES
(1,'Vanangaan', '2025-12-20 10:00:00', 'future_a.jpg', '#'),
(2,'Kanguva', '2025-12-28 09:00:00', 'future_b.jpg', '#'),
(3,'Jana nayagan', '2026-01-10 12:00:00', 'future_c.jpg', '#');

-- ---------------------
-- spin_pool
-- ---------------------
CREATE TABLE IF NOT EXISTS spin_pool (
  id INT AUTO_INCREMENT PRIMARY KEY,
  movie_title VARCHAR(255) NOT NULL,
  poster VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO spin_pool (movie_title, poster) VALUES
('Inception', 'inception.jpg'),
('Titanic', 'titanic.jpg'),
('Interstellar', 'interstellar.jpg'),
('The Dark Knight', 'dark_knight.jpg'),
('Premam', 'premam.jpg'),
('Frozen', 'frozen.jpg');

-- ---------------------
-- poll_of_the_day, poll_options, poll_votes
-- ---------------------
CREATE TABLE IF NOT EXISTS poll_of_the_day (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  expires_at DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS poll_options (
  id INT AUTO_INCREMENT PRIMARY KEY,
  poll_id INT NOT NULL,
  option_text VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (poll_id) REFERENCES poll_of_the_day(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS poll_votes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  poll_id INT NOT NULL,
  option_id INT NOT NULL,
  user_identifier VARCHAR(255) DEFAULT NULL,
  voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (poll_id) REFERENCES poll_of_the_day(id) ON DELETE CASCADE,
  FOREIGN KEY (option_id) REFERENCES poll_options(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO poll_of_the_day (question, expires_at) VALUES ('Which movie genre should we feature tonight?', DATE_ADD(NOW(), INTERVAL 1 DAY));
INSERT INTO poll_options (poll_id, option_text) VALUES (LAST_INSERT_ID(), 'Action'), (LAST_INSERT_ID(), 'Romance'), (LAST_INSERT_ID(), 'Sci-Fi');

-- ---------------------
-- news_updated (movie news)
-- ---------------------
CREATE TABLE IF NOT EXISTS news_updated (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  summary TEXT,
  content TEXT,
  image VARCHAR(255) DEFAULT NULL,
  published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO news_updated (title, summary, content, image, published_at) VALUES
('Company Foundation Provides Grant to Emory Law', 'Consectetuer adipiscing elit...', 'Full content goes here...', 'img/4.jpg', '2021-12-14 09:00:00');

-- ---------------------
-- authors
-- ---------------------
CREATE TABLE IF NOT EXISTS authors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  bio TEXT,
  photo VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO authors (name, bio, photo) VALUES
('nizha afra', 'Nizha writes about cinema and culture.', 'img/34.jpg'),
('Gayathri', 'Gayathri covers film reviews and interviews.', 'img/35.jpg'),
('rishma', 'Rishma focuses on industry news and analysis.', 'img/36.jpg');

-- ---------------------
-- posts + post_reactions (user-generated posts & reactions)
-- ---------------------
CREATE TABLE IF NOT EXISTS posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  category VARCHAR(120) DEFAULT 'Blog',
  author_id INT DEFAULT NULL,
  content TEXT NOT NULL,
  publish_date DATETIME DEFAULT NULL,
  status ENUM('published','scheduled','draft') DEFAULT 'published',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS post_reactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  reaction ENUM('love','wow','boring','sad') NOT NULL,
  count INT DEFAULT 0,
  UNIQUE KEY post_reaction_unique (post_id, reaction),
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO posts (title, category, author_id, content, publish_date, status) VALUES
('Welcome to FlickFix', 'Blog', 1, 'This is the first sample post.', NOW(), 'published');

SET @last_post = LAST_INSERT_ID();
INSERT INTO post_reactions (post_id, reaction, count) VALUES
(@last_post, 'love', 2), (@last_post, 'wow', 1), (@last_post, 'boring', 0), (@last_post, 'sad', 0);

-- Done.





