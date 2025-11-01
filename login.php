<?php
session_start();
require_once __DIR__ . '/db.php';         
require_once __DIR__ . '/admin/function.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Security: regenerate session ID

            // Store user info in session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'first_name' => $user['first_name'] ?? '',
                'last_name' => $user['last_name'] ?? '',
                'email' => $user['email'],
                'username' => $user['username'] ?? '',
                'profile_pic' => $user['profile_pic'] ?? 'default.png',
                'role' => $user['role'] ?? 'user'
            ];

            $_SESSION['login_time'] = time();

            // Redirect based on role
            if ($_SESSION['user']['role'] === 'admin') {
                redirect('admin/dashboard.php');
            } else {
                redirect('index.php');
            }
        } else {
            $message = "❌ Invalid password!";
        }
    } else {
        $message = "❌ No account found with that email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>FLICK-FIX — Login</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/global.css" rel="stylesheet">
<link href="css/index.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>

<style>
body { font-family: 'Rajdhani', sans-serif; background: #0b0b0b; color: #fff; }
.auth-wrap { min-height: 100vh; display: flex; align-items: center; }
.card-auth { background: #0f0f10; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 12px; box-shadow: 0 6px 30px rgba(0,0,0,0.6); padding: 30px; }
.brand { color: #ff2d2d; font-weight: 700; }
.form-control:focus { box-shadow: none; border-color: rgba(255, 45, 45, 0.85); }
.btn-primary.custom { background: #ff2d2d; border-color: #ff2d2d; }
.btn-primary.custom:hover { background: #e02222; border-color: #e02222; }
.small-muted { color: rgba(255, 255, 255, 0.6); }
.pw-toggle { cursor: pointer; }
.alert { border-radius: 6px; font-weight: 500; }
</style>
</head>
<body>

<div class="container auth-wrap">
  <div class="row justify-content-center w-100">
    <div class="col-md-6 col-lg-5">
      <div class="card card-auth">
        <h3 class="text-center mb-4">
          <i class="fa fa-video-camera me-2" style="color:#ff2d2d;"></i>Sign in to <span class="brand">FLICK-FIX</span>
        </h3>

        <form method="POST" novalidate>
          <div class="mb-3">
            <label for="loginEmail" class="form-label small-muted">Email address</label>
            <input type="email" id="loginEmail" name="email" class="form-control" placeholder="name@domain.com" required>
          </div>

          <div class="mb-3 position-relative">
            <label for="loginPassword" class="form-label small-muted">Password</label>
            <div class="input-group">
              <input type="password" id="loginPassword" name="password" class="form-control" placeholder="Enter password" required minlength="6">
              <span class="input-group-text bg-transparent border-start-0 pw-toggle" id="togglePw" title="Show / hide password">
                <i class="fa fa-eye"></i>
              </span>
            </div>
          </div>

          <button type="submit" class="btn btn-primary custom w-100">Sign In</button>
        </form>

        <?php if ($message): ?>
          <div class="alert alert-danger mt-3 text-center"><?= esc($message) ?></div>
        <?php endif; ?>

        <div class="text-center mt-3 small-muted">
          Don’t have an account? <a href="register.php" style="color:#ff2d2d">Create one</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$('#togglePw').on('click', function () {
  let $pw = $('#loginPassword');
  let type = $pw.attr('type') === 'password' ? 'text' : 'password';
  $pw.attr('type', type);
  $(this).find('i').toggleClass('fa-eye fa-eye-slash');
});
</script>

</body>
</html>
