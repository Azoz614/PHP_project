<?php
$servername = "localhost";
$username = "root";
$password = ""; // Change if you set one
$dbname = "FLICK-FIX"; // Use your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT id, title FROM movies";
$result = $conn->query($sql);

$movies = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $movies[] = $row;
  }
}

header("Content-Type: application/json");
echo json_encode($movies);
$conn->close();
?>
