<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FLICK-FIX"; // ✅ Updated database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// ✅ Safely receive and sanitize parameters
$movie_id   = isset($_GET['movie_id'])   ? intval($_GET['movie_id'])   : 0;
$theater_id = isset($_GET['theater_id']) ? intval($_GET['theater_id']) : 0;

if ($movie_id === 0 || $theater_id === 0) {
    echo json_encode(["error" => "Invalid parameters"]);
    exit;
}

// ✅ Fetch showtimes for selected movie and theater
$sql = "SELECT time_id, show_time 
        FROM show_times 
        WHERE movie_id = ? AND theater_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $movie_id, $theater_id);
$stmt->execute();
$result = $stmt->get_result();

$showtimes = [];
while ($row = $result->fetch_assoc()) {
    $showtimes[] = $row;
}

echo json_encode($showtimes);

$stmt->close();
$conn->close();
?>
