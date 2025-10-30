<?php
session_start();
include 'db.php'; // ✅ Make sure this file exists and connects to your DB

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $user['id'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $message = "<div class='alert alert-danger text-center'>❌ New passwords do not match.</div>";
    } else {
        // Verify current password
        $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $db_user = $result->fetch_assoc();

        if (!$db_user || !password_verify($current_password, $db_user['password'])) {
            $message = "<div class='alert alert-danger text-center'>❌ Current password is incorrect.</div>";
        } else {
            // Update new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $hashed_password, $user_id);

            if ($stmt->execute()) {
                $message = "<div class='alert alert-success text-center'>✅ Password changed successfully. Redirecting...</div>";
                echo "<script>
                        setTimeout(() => { window.location.href = 'index.php'; }, 2000);
                      </script>";
            } else {
                $message = "<div class='alert alert-danger text-center'>⚠️ Failed to update password.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password — FLICK-FIX</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/global.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
<script src="js/bootstrap.bundle.min.js"></script>
<style>
body {
  font-family: 'Rajdhani', sans-serif;
  background: #0b0b0b;
  color: #fff;
}
.card-auth {
  background: #0f0f10;
  border: 1px solid rgba(255,255,255,0.05);
  border-radius: 12px;
  box-shadow: 0 6px 30px rgba(0,0,0,0.6);
  padding: 30px;
}
.btn-red {
  background: #ff2d2d;
  color: #fff;
  border: none;
}
.btn-red:hover {
  background: #e02222;
}
.form-control {
  background: #1a1a1a;
  color: #fff;
  border: 1px solid #333;
}
.form-control:focus {
  border-color: #ff2d2d;
  box-shadow: none;
}
.alert {
  border-radius: 6px;
  font-weight: 500;
}
label {
  color: #ddd;
}
</style>
</head>
<body>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card-auth">
        <h3 class="text-center mb-4">
          <i class="fa fa-lock me-2" style="color:#ff2d2d;"></i>Change Password
        </h3>

        <?php echo $message; ?>

        <form method="POST">
          <div class="mb-3">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
          </div>

          <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" placeholder="Enter new password" minlength="6" required>
          </div>

          <div class="mb-3">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" minlength="6" required>
          </div>

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-red px-4">Update Password</button>
            <a href="update_profile.php" class="btn btn-secondary ms-2">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>
