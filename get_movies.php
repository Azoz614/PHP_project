<?php
$servername = "localhost";
$username = "root";
$password = "";  // change if needed
$dbname = "movie_booking"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title FROM movies";  // your table (based on the screenshot)
$result = $conn->query($sql);

$movies = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $movies[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($movies);
$conn->close();
?>
