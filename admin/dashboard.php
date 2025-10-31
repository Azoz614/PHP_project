<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard — FLICK-FIX</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #0b0b0b;
      color: #fff;
      font-family: Arial, sans-serif;
      padding: 30px;
    }
    .dashboard-card {
      border-radius: 8px;
      padding: 25px;
      text-align: center;
      font-weight: bold;
      font-size: 18px;
      transition: 0.3s;
    }
    .dashboard-card:hover {
      transform: scale(1.05);
    }
    .movies-card { background: #007bff; color: white; }
    .users-card { background: #ffc107; color: black; }
  </style>
</head>
<body>

  <h3 class="mb-4">Admin Dashboard</h3>

  <div class="row">
    <div class="col-md-6 mb-3">
      <a href="movie/movies.php" style="text-decoration:none;">
        <div class="dashboard-card movies-card">
          🎬 Movie Management
        </div>
      </a>
    </div>

    <div class="col-md-6 mb-3">
      <a href="user/users.php" style="text-decoration:none;">
        <div class="dashboard-card users-card">
          👤 User Management
        </div>
      </a>
    </div>
  </div>

</body>
</html>
