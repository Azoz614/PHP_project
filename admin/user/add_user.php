<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../function.php';

// Only admin can access
require_admin();

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role       = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password, role)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $password, $role);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>✅ User added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>❌ Failed to add user. (" . $conn->error . ")</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add New User — Admin</title>
<link href="../../css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #0b0b0b;
    color: #fff;
    font-family: Arial, sans-serif;
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
        <h3 class="mb-4 text-center">👤 Add New User</h3>

        <?= $message ?>

        <form method="POST">
          <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control">
          </div>
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-red px-4">Add User</button>
            <a href="users.php" class="btn btn-secondary ms-2">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
