<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FLICK-FIX"; // ✅ Updated database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch bookings including movie, theater, and show time details
$sql = "SELECT 
          b.booking_id, 
          b.customer_name, 
          m.title AS movie, 
          t.name AS theater, 
          s.show_time AS time, 
          b.seat_number, 
          b.booking_date 
        FROM bookings b
        JOIN movies m ON b.movie_id = m.id
        JOIN theaters t ON b.theater_id = t.theater_id
        LEFT JOIN show_times s ON b.showtime_id = s.time_id
        ORDER BY b.booking_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Bookings - FLICK FIX</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      color: #333;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 85%;
      margin: 40px auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #444;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }
    th {
      background-color: #2c3e50;
      color: #fff;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    a {
      text-decoration: none;
    }
    .links {
      text-align: center;
      margin-top: 20px;
    }
    .cancel-link {
      color: red;
      font-weight: bold;
    }
    .cancel-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🎟️ Your Bookings</h1>
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Movie</th>
        <th>Theater</th>
        <th>Time</th>
        <th>Seat</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
      <?php
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['booking_id']}</td>
                  <td>{$row['customer_name']}</td>
                  <td>{$row['movie']}</td>
                  <td>{$row['theater']}</td>
                  <td>{$row['time']}</td>
                  <td>{$row['seat_number']}</td>
                  <td>{$row['booking_date']}</td>
                  <td><a href='cancel_booking.php?id={$row['booking_id']}' class='cancel-link'>Cancel</a></td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='8'>No bookings yet</td></tr>";
      }
      ?>
    </table>
    <div class="links">
      <a href="booking.php">⬅ Back to Booking</a>
    </div>
  </div>
</body>
</html>

<?php $conn->close(); ?>
