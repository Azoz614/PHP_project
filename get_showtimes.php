<?php
$conn = new mysqli("localhost","root","","flick-fix");
if($conn->connect_error) die("Connection failed");

$movie_id = $_GET['movie_id'];
$theater_id = $_GET['theater_id'];

$sql = "SELECT showtime_id, show_time FROM showtimes 
        WHERE movie_id='$movie_id' AND theater_id='$theater_id'";
$result = $conn->query($sql);

$showtimes = [];
while($row = $result->fetch_assoc()) $showtimes[] = $row;

echo json_encode($showtimes);
$conn->close();
?>
