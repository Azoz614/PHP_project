<?php
$conn = new mysqli("localhost","root","","flick-fix");
if($conn->connect_error) die("Connection failed");

$movie_id = $_GET['movie_id'];

$sql = "SELECT * FROM theaters"; // For simplicity, all theaters show all movies
$result = $conn->query($sql);

$theaters = [];
while($row = $result->fetch_assoc()) {
    $theaters[] = $row;
}

echo json_encode($theaters);
$conn->close();
?>
