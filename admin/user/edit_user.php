<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../function.php';

require_admin();

// Get user info
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $result ? $result->fetch_assoc() : null;

if (!$user) {
    header("Location: users.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $new_password = trim($_POST['password']);

    if ($new_password !== "") {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=?, password=? WHERE id=?");
        $stmt->bind_param("ssssi", $username, $email, $role, $hashed, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $email, $role, $id);
    }

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>✅ User updated successfully!</div>";
        // refresh user info after update
        $result = $conn->query("SELECT * FROM users WHERE id = $id");
        $user = $result ? $result->fetch_assoc() : null;
    } else {
        $message = "<div class='alert alert-danger text-center'>❌ Update failed: {$conn->error}</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<link href="../../css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#0b0b0b; color:#fff; padding:30px; }
.card-dark {
  background:#121212; border-radius:10px; padding:25px;
  box-shadow:0 5px 25px rgba(0,0,0,0.5);
}
.btn-red { background:#ff2d2d; color:#fff; border:none; }
.btn-red:hover { background:#e02222; }
.form-control { background:#1a1a1a; color:#fff; border:1px solid #333; }
.form-control:focus { border-color:#ff2d2d; box-shadow:none; }
</style>
</head>
<body>
<div class="container py-4">
  <div class="col-md-6 mx-auto">
    <div class="card-dark">
      <h3 class="mb-4 text-center text-danger">✏️ Edit User</h3>

      <?= $message ?>

      <form method="post">
        <div class="mb-3">
          <label>Username</label>
          <input type="text" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
        </div>

        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
        </div>

        <div class="mb-3">
          <label>Role</label>
          <select name="role" class="form-control">
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
          </select>
        </div>

        <div class="mb-3">
          <label>New Password (leave blank to keep current)</label>
          <input type="password" name="password" class="form-control" placeholder="Enter new password">
        </div>

        <div class="text-center">
          <button class="btn btn-red px-4">Save Changes</button>
          <a href="users.php" class="btn btn-secondary ms-2">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
