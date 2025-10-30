<?php
session_start();
include 'db.php'; // ✅ Connect to your existing DB file

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$user = $_SESSION['user'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $user_id = $user['id'];

  // Verify password
  $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $db_user = $result->fetch_assoc();

  if (!password_verify($password, $db_user['password'])) {
    $message = "<div class='alert alert-danger text-center'>❌ Incorrect password. Changes not saved.</div>";
  } else {
    // Handle profile picture upload
    $file_name = $user['profile_pic'];
    if (!empty($_FILES['profile_pic']['name'])) {
      $target_dir = "uploads/";
      $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
      $target_file = $target_dir . $file_name;
      move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);
    }

    // Update user data
    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, profile_pic=? WHERE id=?");
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $file_name, $user_id);

    if ($stmt->execute()) {
      // Update session instantly
      $_SESSION['user']['first_name'] = $first_name;
      $_SESSION['user']['last_name'] = $last_name;
      $_SESSION['user']['email'] = $email;
      $_SESSION['user']['profile_pic'] = $file_name;

      // ✅ Redirect to index.php with a success message
      $_SESSION['profile_updated'] = true;
      header("Location: index.php");
      exit();
    } else {
      $message = "<div class='alert alert-danger text-center'>⚠️ Error updating profile.</div>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Profile — FLICK-FIX</title>
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
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 12px;
  box-shadow: 0 6px 30px rgba(0, 0, 0, 0.6);
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
</style>
</head>
<body>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card-auth">
        <h3 class="text-center mb-4">
          <i class="fa fa-user me-2" style="color:#ff2d2d;"></i>Update Profile
        </h3>

        <?php echo $message; ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="text-center mb-3">
            <img src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" 
                 alt="Profile" class="rounded-circle mb-2" 
                 style="width:100px;height:100px;object-fit:cover;">
          </div>

          <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" 
                   value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
          </div>

          <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" 
                   value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" 
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
          </div>

          <div class="mb-3">
            <label>Profile Picture</label>
            <input type="file" name="profile_pic" class="form-control">
          </div>

          <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password" class="form-control" 
                   placeholder="Enter your password to confirm" required>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-red px-4">Save Changes</button>
            <a href="index.php" class="btn btn-secondary ms-2">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>
