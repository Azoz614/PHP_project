<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flick-fix";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$movie_id = $_GET['movie_id'];
$theater_id = $_GET['theater_id'];

$sql = "SELECT time_id, show_time FROM show_times WHERE movie_id='$movie_id' AND theater_id='$theater_id'";
$result = $conn->query($sql);

$times = [];
while ($row = $result->fetch_assoc()) {
  $times[] = $row;
}

echo json_encode($times);
$conn->close();
?>
