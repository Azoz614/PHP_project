<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FLICK-FIX"; // ✅ Updated to your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

// Get POST data safely
$name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
$movie = isset($_POST['movie']) ? intval($_POST['movie']) : 0;
$theater = isset($_POST['theater']) ? intval($_POST['theater']) : 0;
$seat = isset($_POST['seat']) ? $conn->real_escape_string($_POST['seat']) : '';
$time = isset($_POST['time']) ? intval($_POST['time']) : 0;

if (!$name || !$movie || !$theater || !$seat || !$time) {
  die("<h2 style='color:red;text-align:center;'>⚠️ Missing required booking details!</h2>
       <a href='index.php' style='display:block;text-align:center;'>Go Back</a>");
}

$conn->begin_transaction();

try {
    // ✅ Check if seat already booked for same showtime
    $check_sql = "SELECT * FROM bookings 
                  WHERE movie_id = $movie 
                  AND theater_id = $theater 
                  AND showtime_id = $time 
                  AND seat_number = '$seat' 
                  FOR UPDATE";

    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        throw new Exception("❌ Seat '$seat' is already booked for this showtime!");
    }

    // ✅ Insert booking
    $insert_sql = "INSERT INTO bookings (customer_name, movie_id, theater_id, showtime_id, seat_number, booking_date)
                   VALUES ('$name', $movie, $theater, $time, '$seat', NOW())";

    if (!$conn->query($insert_sql)) {
        throw new Exception("Booking failed: " . $conn->error);
    }

    // ✅ Fetch readable info
    $movie_sql = "SELECT title FROM movies WHERE id = $movie";
    $movie_result = $conn->query($movie_sql);
    $movie_row = $movie_result->fetch_assoc();
    $movie_name = $movie_row ? $movie_row['title'] : 'Unknown Movie';

    $theater_sql = "SELECT name FROM theaters WHERE theater_id = $theater";
    $theater_result = $conn->query($theater_sql);
    $theater_row = $theater_result->fetch_assoc();
    $theater_name = $theater_row ? $theater_row['name'] : 'Unknown Theater';

    $time_sql = "SELECT show_time FROM show_times WHERE time_id = $time";
    $time_result = $conn->query($time_sql);
    $time_row = $time_result->fetch_assoc();
    $showtime = $time_row ? $time_row['show_time'] : 'Unknown Time';

    // ✅ Commit transaction
    $conn->commit();

    echo "<h2 style='color:green;text-align:center;'>✅ Booking Successful!</h2>";
    echo "<p style='text-align:center;'>
            <b>Name:</b> $name<br>
            <b>Movie:</b> $movie_name<br>
            <b>Theater:</b> $theater_name<br>
            <b>Seat:</b> $seat<br>
            <b>Showtime:</b> $showtime
          </p>";
    echo "<a href='view_bookings.php' style='display:block;text-align:center;'>View Bookings</a>";

} catch (Exception $e) {
    $conn->rollback();
    echo "<h2 style='color:red;text-align:center;'>{$e->getMessage()}</h2>";
    echo "<a href='index.php' style='display:block;text-align:center;'>Go Back</a>";
}

$conn->close();
?>
