

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
