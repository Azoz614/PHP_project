<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $conn->real_escape_string($_POST['name']);
  $email = $conn->real_escape_string($_POST['email']);
  $message = $conn->real_escape_string($_POST['message']);

  $sql = "INSERT INTO contact (name, email, message) VALUES ('$name', '$email', '$message')";
  if ($conn->query($sql)) {
    echo "<script>alert('Your message has been sent successfully!'); window.location.href='../index.php#contact';</script>";
  } else {
    echo "<script>alert('Error sending message.'); window.location.href='../index.php#contact';</script>";
  }
}
?>
