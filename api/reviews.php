<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $movieId = $conn->real_escape_string($_POST['movieId']);
  $rating = floatval($_POST['rating']);
  $comment = $conn->real_escape_string($_POST['comment']);

  $sql = "INSERT INTO reviews (movie_id, rating, comment) VALUES ('$movieId', '$rating', '$comment')";
  if ($conn->query($sql)) {
    echo "<script>alert('Review submitted successfully!'); window.location.href='../index.php#trailers';</script>";
  } else {
    echo "<script>alert('Error saving review.'); window.location.href='../index.php#trailers';</script>";
  }
}
?>
