<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FLICK-FIX"; // ✅ Updated database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
  exit;
}

// ✅ Decode JSON data from frontend
$data = json_decode(file_get_contents("php://input"), true);

$name = isset($data['name']) ? $conn->real_escape_string($data['name']) : '';
$movie = isset($data['movie']) ? intval($data['movie']) : 0;
$theater = isset($data['theater']) ? intval($data['theater']) : 0;
$time = isset($data['time']) ? intval($data['time']) : 0;
$seats = isset($data['seats']) && is_array($data['seats']) ? $data['seats'] : [];

if (!$name || !$movie || !$theater || !$time || empty($seats)) {
  echo json_encode(["success" => false, "message" => "⚠️ Missing required booking details."]);
  exit;
}

// ✅ Begin transaction (to prevent double-booking)
$conn->begin_transaction();

try {
    foreach ($seats as $seat) {
        $seat = $conn->real_escape_string($seat);

        // ✅ Check if seat already booked for this movie, theater & time
        $check_sql = "SELECT booking_id FROM bookings 
                      WHERE movie_id = $movie 
                      AND theater_id = $theater 
                      AND showtime_id = $time 
                      AND seat_number = '$seat' 
                      FOR UPDATE";
        $result = $conn->query($check_sql);

        if ($result && $result->num_rows > 0) {
            throw new Exception("❌ Seat '$seat' is already booked. Please choose another.");
        }

        // ✅ Insert booking
        $insert_sql = "INSERT INTO bookings (customer_name, movie_id, theater_id, showtime_id, seat_number, booking_date)
                       VALUES ('$name', $movie, $theater, $time, '$seat', NOW())";
        if (!$conn->query($insert_sql)) {
            throw new Exception("❌ Booking failed for seat '$seat': " . $conn->error);
        }
    }

    // ✅ Commit successful transaction
    $conn->commit();
    echo json_encode(["success" => true, "message" => "✅ Booking Successful!"]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
?>
