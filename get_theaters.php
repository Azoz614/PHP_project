<?php
$conn = new mysqli("localhost", "root", "", "movie_booking");
if ($conn->connect_error) {
  die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;

// Dummy logic – all theaters show all movies
$sql = "SELECT theater_id, name FROM theaters";
$result = $conn->query($sql);

$theaters = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $theaters[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($theaters);
$conn->close();
?>
