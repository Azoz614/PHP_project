

CREATE TABLE theaters (
  theater_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  location VARCHAR(100)
);

-- Sample theaters
INSERT INTO theaters (name, location) VALUES
('CineMax Colombo', 'Colombo'),
('Savoy 3D', 'Wellawatte'),
('Liberty Cinema', 'Colombo 03'),
('PVR Kandy', 'Kandy');



CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    movie_id INT NOT NULL,
    theater_id INT NOT NULL,
    showtime_id INT NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    booking_date DATETIME DEFAULT CURRENT_TIMESTAMP);
	




-- Create the 'showtimes' table
CREATE TABLE showtimes (
    showtime_id INT(11) NOT NULL AUTO_INCREMENT,
    movie_id INT(11) NOT NULL,
    theater_id INT(11) NOT NULL,
    show_time TIME NOT NULL,
    PRIMARY KEY (showtime_id)
);

-- Insert the data shown in the table
INSERT INTO showtimes (showtime_id, movie_id, theater_id, show_time) VALUES
(1, 1, 1, '10:00:00'),
(2, 1, 1, '15:00:00'), -- 3:00 PM
(3, 1, 2, '12:00:00'),
(4, 2, 1, '11:00:00'),
(5, 3, 2, '16:00:00'); -- 4:00 PM



-- Create the 'show_times' table
CREATE TABLE show_times (
    time_id INT(11) NOT NULL AUTO_INCREMENT,
    movie_id INT(11) NOT NULL,
    theater_id INT(11) NOT NULL,
    show_time TIME NOT NULL,
    PRIMARY KEY (time_id)
);

-- Insert the data shown in the table
INSERT INTO show_times (time_id, movie_id, theater_id, show_time) VALUES
(1, 1, 1, '10:00:00'),
(2, 1, 1, '13:00:00'), -- 1:00 PM
(3, 1, 1, '18:00:00'), -- 6:00 PM
(4, 2, 2, '11:30:00'),
(5, 2, 2, '14:30:00'), -- 2:30 PM
(6, 3, 3, '15:00:00'); -- 3:00 PM





