<?php
$host = "localhost";
$user = "root";     // default XAMPP user
$pass = "";         // no password in XAMPP by default
$db   = "FLICK-FIX";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>
