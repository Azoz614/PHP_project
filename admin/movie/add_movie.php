<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../function.php';

require_admin(); // allow only admin

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $year = trim($_POST['year']);
    $mpaa = trim($_POST['mpaa']);
    $runtime = trim($_POST['runtime']);
    $release_date = trim($_POST['release_date']);
    $director = trim($_POST['director']);
    $stars = trim($_POST['stars']);
    $description = trim($_POST['description']);

    $poster = 'default.png';

    // handle poster upload
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $poster = uniqid('poster_', true) . '.' . $ext;
            $upload_dir = __DIR__ . '/../../uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            move_uploaded_file($_FILES['poster']['tmp_name'], $upload_dir . $poster);
        }
    }

    // insert movie
    $stmt = $conn->prepare("INSERT INTO movies (title, year, mpaa, runtime, release_date, director, stars, description, poster)
                            VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssssss", $title, $year, $mpaa, $runtime, $release_date, $director, $stars, $description, $poster);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>✅ Movie added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>❌ Failed to add movie.</div>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add New Movie — Admin</title>
<link href="../../css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Rajdhani', sans-serif;
    background: #0b0b0b;
    color: #fff;
}
.card-dark {
    background: #121212;
    border: 1px solid #222;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.5);
}
.btn-red {
    background-color: #ff2d2d;
    color: #fff;
    border: none;
}
.btn-red:hover {
    background-color: #e02222;
}
.form-control {
    background: #1a1a1a;
    border: 1px solid #333;
    color: #fff;
}
.form-control:focus {
    border-color: #ff2d2d;
    box-shadow: none;
}
h3 {
    color: #ff2d2d;
    font-weight: bold;
}
</style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card-dark">
        <h3 class="mb-4 text-center">🎬 Add New Movie</h3>

        <?= $message ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Year</label>
            <input type="number" name="year" class="form-control" min="1900" max="2099">
          </div>
          <div class="mb-3">
            <label>Rating (MPAA)</label>
            <input type="text" name="mpaa" class="form-control" placeholder="e.g. PG-13">
          </div>
          <div class="mb-3">
            <label>Run Time</label>
            <input type="text" name="runtime" class="form-control" placeholder="e.g. 120 min">
          </div>
          <div class="mb-3">
            <label>Release Date</label>
            <input type="date" name="release_date" class="form-control">
          </div>
          <div class="mb-3">
            <label>Director</label>
            <input type="text" name="director" class="form-control">
          </div>
          <div class="mb-3">
            <label>Stars</label>
            <input type="text" name="stars" class="form-control">
          </div>
          <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
          </div>
          <div class="mb-3">
            <label>Poster Image</label>
            <input type="file" name="poster" class="form-control" accept="image/*">
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-red px-4">Add Movie</button>
            <a href="movies.php" class="btn btn-secondary ms-2">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
