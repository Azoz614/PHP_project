<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FLICK-FIX"; // ✅ Updated database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// ✅ Safely receive GET parameters
$movie_id   = isset($_GET['movie_id'])   ? intval($_GET['movie_id'])   : 0;
$theater_id = isset($_GET['theater_id']) ? intval($_GET['theater_id']) : 0;
$time_id    = isset($_GET['time_id'])    ? intval($_GET['time_id'])    : 0;

if ($movie_id === 0 || $theater_id === 0 || $time_id === 0) {
    echo json_encode(["error" => "Invalid parameters"]);
    exit;
}

// ✅ Fetch booked seats for the selected movie, theater, and showtime
$sql = "SELECT seat_number 
        FROM bookings 
        WHERE movie_id = ? AND theater_id = ? AND showtime_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $movie_id, $theater_id, $time_id);
$stmt->execute();
$result = $stmt->get_result();

$booked = [];
while ($row = $result->fetch_assoc()) {
    $booked[] = $row['seat_number'];
}

echo json_encode($booked);

$stmt->close();
$conn->close();
?>
