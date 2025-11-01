<?php
$conn = new mysqli("localhost","root","","flick-fix");
if($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = intval($_GET['id']);
$conn->query("DELETE FROM bookings WHERE booking_id=$id");

echo "<h2 style='color:red;text-align:center;'>Booking Cancelled!</h2>";
echo "<a href='view_bookings.php' style='display:block;text-align:center;'>Back to Bookings</a>";

$conn->close();
?>
