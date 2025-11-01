<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movie_booking";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$name = $_POST['name'];
$movie = $_POST['movie'];
$theater = $_POST['theater'];
$seat = $_POST['seat'];
$time = $_POST['time']; // New field for showtime_id

// Start transaction
$conn->begin_transaction();

try {
    // Check if seat is already booked for the same movie, theater & showtime
    $check_sql = "SELECT * FROM bookings 
                  WHERE movie_id='$movie' AND theater_id='$theater' 
                  AND showtime_id='$time' AND seat_number='$seat' 
                  FOR UPDATE";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        throw new Exception("❌ Seat $seat is already booked for this showtime! Try another.");
    }

    // Insert booking
    $insert_sql = "INSERT INTO bookings (customer_name, movie_id, theater_id, showtime_id, seat_number) 
                   VALUES ('$name', '$movie', '$theater', '$time', '$seat')";
    if (!$conn->query($insert_sql)) {
        throw new Exception("Booking failed: " . $conn->error);
    }

    // Get the showtime text to display
    $time_sql = "SELECT show_time FROM show_times WHERE time_id='$time' LIMIT 1";
    $time_result = $conn->query($time_sql);
    $time_row = $time_result->fetch_assoc();
    $showtime = $time_row ? $time_row['show_time'] : 'Unknown Time';

    // Commit transaction
    $conn->commit();

    echo "<h2 style='color:green;text-align:center;'>✅ Booking Successful!</h2>";
    echo "<p style='text-align:center;'>Movie ID: $movie<br>Theater ID: $theater<br>Seat: $seat<br>Showtime: <b>$showtime</b></p>";
    echo "<a href='view_bookings.php' style='display:block;text-align:center;'>View Bookings</a>";

} catch (Exception $e) {
    $conn->rollback();
    echo "<h2 style='color:red;text-align:center;'>{$e->getMessage()}</h2>";
    echo "<a href='index.html' style='display:block;text-align:center;'>Go Back</a>";
}

$conn->close();
?>
