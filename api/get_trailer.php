<?php
header('Content-Type: application/json');
include '../db.php';

$movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;

if ($movie_id <= 0) {
  echo json_encode(["status" => "error", "message" => "Invalid movie ID"]);
  exit;
}

$sql = "SELECT youtube_link FROM trailers WHERE movie_id = $movie_id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo json_encode([
    "status" => "success",
    "youtube_link" => $row['youtube_link']
  ]);
} else {
  echo json_encode([
    "status" => "error",
    "message" => "Trailer not found"
  ]);
}
?>
