<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../function.php';

require_admin(); // restrict to admin

$message = "";

// get movie id
if (!isset($_GET['id'])) {
    die("❌ No movie ID provided.");
}

$id = (int)$_GET['id'];

// fetch existing movie
$stmt = $conn->prepare("SELECT * FROM movies WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Movie not found.");
}

$movie = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $year = trim($_POST['year']);
    $mpaa = trim($_POST['mpaa']);
    $runtime = trim($_POST['runtime']);
    $release_date = trim($_POST['release_date']);
    $director = trim($_POST['director']);
    $stars = trim($_POST['stars']);
    $description = trim($_POST['description']);

    $poster = $movie['poster'];

    // handle new poster upload
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

    // update movie
    $stmt = $conn->prepare("UPDATE movies 
                            SET title=?, year=?, mpaa=?, runtime=?, release_date=?, director=?, stars=?, description=?, poster=? 
                            WHERE id=?");
    $stmt->bind_param("sssssssssi", $title, $year, $mpaa, $runtime, $release_date, $director, $stars, $description, $poster, $id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>✅ Movie updated successfully!</div>";
        // refresh data after update
        $movie = [
            'title' => $title,
            'year' => $year,
            'mpaa' => $mpaa,
            'runtime' => $runtime,
            'release_date' => $release_date,
            'director' => $director,
            'stars' => $stars,
            'description' => $description,
            'poster' => $poster
        ];
    } else {
        $message = "<div class='alert alert-danger text-center'>❌ Failed to update movie.</div>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Movie — Admin</title>
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
        <h3 class="mb-4 text-center">🎬 Edit Movie</h3>

        <?= $message ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($movie['title']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Year</label>
            <input type="number" name="year" class="form-control" value="<?= htmlspecialchars($movie['year']) ?>" min="1900" max="2099">
          </div>
          <div class="mb-3">
            <label>Rating (MPAA)</label>
            <input type="text" name="mpaa" class="form-control" value="<?= htmlspecialchars($movie['mpaa']) ?>">
          </div>
          <div class="mb-3">
            <label>Run Time</label>
            <input type="text" name="runtime" class="form-control" value="<?= htmlspecialchars($movie['runtime']) ?>">
          </div>
          <div class="mb-3">
            <label>Release Date</label>
            <input type="date" name="release_date" class="form-control" value="<?= htmlspecialchars($movie['release_date']) ?>">
          </div>
          <div class="mb-3">
            <label>Director</label>
            <input type="text" name="director" class="form-control" value="<?= htmlspecialchars($movie['director']) ?>">
          </div>
          <div class="mb-3">
            <label>Stars</label>
            <input type="text" name="stars" class="form-control" value="<?= htmlspecialchars($movie['stars']) ?>">
          </div>
          <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($movie['description']) ?></textarea>
          </div>
          <div class="mb-3">
            <label>Poster Image</label>
            <input type="file" name="poster" class="form-control" accept="image/*">
            <?php if (!empty($movie['poster'])): ?>
              <div class="mt-2">
                <img src="../../uploads/<?= htmlspecialchars($movie['poster']) ?>" alt="Poster" width="100" style="border-radius:8px;">
              </div>
            <?php endif; ?>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-red px-4">Update Movie</button>
            <a href="movies.php" class="btn btn-secondary ms-2">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
