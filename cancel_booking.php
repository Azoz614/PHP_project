<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FLICK-FIX"; // ✅ Updated database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("<h3 style='color:red;text-align:center;'>Database connection failed: " . $conn->connect_error . "</h3>");
}

// ✅ Validate and sanitize input
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
  echo "<h2 style='color:red;text-align:center;'>❌ Invalid booking ID!</h2>";
  echo "<a href='view_bookings.php' style='display:block;text-align:center;'>Back to Bookings</a>";
  exit;
}

// ✅ Check if booking exists
$check = $conn->query("SELECT booking_id FROM bookings WHERE booking_id = $id");
if ($check->num_rows == 0) {
  echo "<h2 style='color:red;text-align:center;'>⚠️ Booking not found!</h2>";
  echo "<a href='view_bookings.php' style='display:block;text-align:center;'>Back to Bookings</a>";
  exit;
}

// ✅ Delete the booking
if ($conn->query("DELETE FROM bookings WHERE booking_id = $id") === TRUE) {
  echo "<h2 style='color:green;text-align:center;'>✅ Booking Cancelled Successfully!</h2>";
} else {
  echo "<h2 style='color:red;text-align:center;'>❌ Error cancelling booking: " . $conn->error . "</h2>";
}

echo "<a href='view_bookings.php' style='display:block;text-align:center;'>Back to Bookings</a>";

$conn->close();
?>
