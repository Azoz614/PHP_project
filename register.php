<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirmPassword'];

    $profile_pic = 'default.png';

    // Handle profile picture upload
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === 0) {
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), $allowed)) {
            $newName = uniqid('profile_', true) . "." . $ext;
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $uploadFile = $uploadDir . $newName;
            if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $uploadFile)) {
                $profile_pic = $newName;
            }
        }
    }

    // Password validation
    if ($password !== $confirm) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        // Check duplicate email/username
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=? OR username=?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email or username already exists";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (first_name,last_name,email,username,password,profile_pic) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("ssssss", $firstName, $lastName, $email, $username, $hashed, $profile_pic);

            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $_SESSION['user'] = [
                    'id' => $user_id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'username' => $username,
                    'profile_pic' => $profile_pic
                ];
                header("Location: index.php");
                exit;
            } else {
                $error = "Database error: Could not register user";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>FLICK-FIX — Register</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/global.css" rel="stylesheet">
<link href="css/index.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
<style>
body {font-family:'Rajdhani',sans-serif; background:#030303; color:#fff;}
.register-card {border-radius:12px;background:#0f0f10;padding:28px;box-shadow:0 10px 40px rgba(0,0,0,0.6);}
.brand {color:#ff2d2d;font-weight:700;}
.form-control:focus {border-color: rgba(255,45,45,0.85);box-shadow:none;}
.btn-primary.custom {background:#ff2d2d;border-color:#ff2d2d;}
</style>
</head>
<body>
<header class="mb-4">
<nav class="navbar navbar-expand-md navbar-light">
<div class="container">
<a class="navbar-brand text-white fw-bold brand" href="index.php"><img src="img/newlogo.png" style="width:100px;height:50px;"></a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
<span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="navMain">
<ul class="navbar-nav ms-auto mb-0">
<li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li>
<li class="nav-item"><a class="nav-link text-white" href="about.html">About</a></li>
<li class="nav-item"><a class="nav-link text-white" href="contact.html">Contact</a></li>
</ul>
</div>
</div>
</nav>
</header>

<main class="py-5">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-7 col-md-9">
<div class="register-card">
<h2 class="mb-1">Create your FLICK-FIX account</h2>
<p class="small-muted mb-4">Fast, simple sign up. We only ask for what's necessary.</p>

<?php
if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>';
?>

<form id="registerForm" method="POST" enctype="multipart/form-data" novalidate>
<div class="row g-3">
<div class="col-md-6">
<label class="form-label small-muted" for="firstName">First name</label>
<input id="firstName" name="firstName" type="text" class="form-control" placeholder="First" required>
</div>
<div class="col-md-6">
<label class="form-label small-muted" for="lastName">Last name</label>
<input id="lastName" name="lastName" type="text" class="form-control" placeholder="Last" required>
</div>
<div class="col-12">
<label class="form-label small-muted" for="regEmail">Email</label>
<input id="regEmail" name="email" type="email" class="form-control" placeholder="name@domain.com" required>
</div>
<div class="col-md-6">
<label class="form-label small-muted" for="password">Password</label>
<input id="password" name="password" type="password" class="form-control" minlength="6" required>
</div>
<div class="col-md-6">
<label class="form-label small-muted" for="confirmPassword">Confirm password</label>
<input id="confirmPassword" name="confirmPassword" type="password" class="form-control" required>
</div>
<div class="col-12">
<label class="form-label small-muted" for="username">Username (public)</label>
<input id="username" name="username" type="text" class="form-control" placeholder="e.g. moviefan123" required pattern="^[a-zA-Z0-9._-]{3,20}$">
</div>
<div class="col-12">
<label class="form-label small-muted" for="profilePic">Profile Picture</label>
<input type="file" name="profilePic" id="profilePic" class="form-control" accept="image/*">
<small class="form-text text-muted">Optional. Max size: 2MB</small>
</div>
<div class="col-12">
<div class="form-check">
<input id="terms" name="terms" class="form-check-input" type="checkbox" required>
<label class="form-check-label small-muted" for="terms">I agree to the <a href="#" style="color:#ff2d2d">Terms</a> & <a href="#" style="color:#ff2d2d">Privacy</a>.</label>
</div>
</div>
<div class="col-12">
<button class="btn btn-primary custom w-100" type="submit">Create account</button>
</div>
<div class="col-12 text-center small-muted mt-3">
Already a member? <a href="login.php" style="color:#ff2d2d">Sign in</a>
</div>
</div>
</form>

</div>
</div>
</div>
</div>
</main>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
