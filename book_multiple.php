<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flick-fix";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Get JSON data from frontend
$data = json_decode(file_get_contents("php://input"), true);

$name = $conn->real_escape_string($data['name']);
$movie = intval($data['movie']);
$theater = intval($data['theater']);
$time = intval($data['time']);
$seats = $data['seats'];

$response = ["success" => false, "message" => ""];

// Start transaction to prevent seat duplication
$conn->begin_transaction();

try {
    foreach ($seats as $seat) {
        $seat = $conn->real_escape_string($seat);

        // Check if seat already booked for the same movie, theater & showtime
        $check_sql = "SELECT * FROM bookings 
                      WHERE movie_id='$movie' AND theater_id='$theater' 
                      AND showtime_id='$time' AND seat_number='$seat' 
                      FOR UPDATE";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            throw new Exception("❌ Seat $seat is already booked! Please choose another.");
        }

        // Insert booking
        $insert_sql = "INSERT INTO bookings (customer_name, movie_id, theater_id, showtime_id, seat_number) 
                       VALUES ('$name', '$movie', '$theater', '$time', '$seat')";
        if (!$conn->query($insert_sql)) {
            throw new Exception("Booking failed for seat $seat: " . $conn->error);
        }
    }

    // Commit all bookings
    $conn->commit();
    $response = ["success" => true, "message" => "✅ Booking Successful!"];

} catch (Exception $e) {
    $conn->rollback(); // Rollback transaction on error
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
$conn->close();
?>
