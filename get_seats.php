<?php
$conn = new mysqli("localhost","root","","movie_booking");
if($conn->connect_error) die("Connection failed");

$movie_id = $_GET['movie_id'];
$theater_id = $_GET['theater_id'];

$sql = "SELECT seat_number FROM bookings WHERE movie_id='$movie_id' AND theater_id='$theater_id'";
$result = $conn->query($sql);

$booked = [];
while($row = $result->fetch_assoc()) {
    $booked[] = $row['seat_number'];
}

echo json_encode($booked);
$conn->close();
?>
