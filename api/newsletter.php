<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $conn->real_escape_string($_POST['email']);

  $check = $conn->query("SELECT * FROM newsletter WHERE email='$email'");
  if ($check->num_rows > 0) {
    echo "<script>alert('You are already subscribed!'); window.location.href='../index.php#contact';</script>";
  } else {
    $sql = "INSERT INTO newsletter (email) VALUES ('$email')";
    if ($conn->query($sql)) {
      echo "<script>alert('Subscribed successfully!'); window.location.href='../index.php#contact';</script>";
    } else {
      echo "<script>alert('Error subscribing.'); window.location.href='../index.php#contact';</script>";
    }
  }
}
?>
