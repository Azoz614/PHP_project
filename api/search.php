<?php
header('Content-Type: application/json');
include '../db.php';

$term = isset($_GET['term']) ? $conn->real_escape_string($_GET['term']) : '';

if ($term == '') {
  echo json_encode(["results" => []]);
  exit;
}

$sql = "SELECT id, title, year, poster FROM movies WHERE title LIKE '%$term%'";
$result = $conn->query($sql);

$movies = [];
while ($row = $result->fetch_assoc()) {
  $movies[] = [
    "id" => $row['id'],
    "title" => $row['title'] . " (" . $row['year'] . ")",
    "poster" => "img/" . $row['poster']
  ];
}

echo json_encode(["results" => $movies]);
?>
