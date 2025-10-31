<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection and functions
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../function.php';

// Only allow admins to access this page
require_admin();

// Fetch movies
$result = $conn->query("SELECT * FROM movies ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Movie Management — Admin</title>
<link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#0b0b0b; color:#fff;">

<div class="container py-5">
  <h3 class="mb-4">🎬 Movie Management</h3>

  <p><a href="add_movie.php" class="btn btn-success">Add Movie</a></p>

  <table class="table table-dark table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Year</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($m = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $m['id'] ?></td>
        <td><?= esc($m['title']) ?></td>
        <td><?= esc($m['year']) ?></td>
        <td>
          <a class="btn btn-sm btn-primary" href="edit_movie.php?id=<?= $m['id'] ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="delete_movie.php?id=<?= $m['id'] ?>" onclick="return confirm('Delete movie?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
