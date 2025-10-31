<?php
session_start();
include('db.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // ✅ Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ✅ Verify password
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); // security

            // ✅ Store user info in session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'username' => $user['username'],
                'profile_pic' => $user['profile_pic'],
                'role' => $user['role'] ?? 'user'
            ];

            $_SESSION['login_time'] = time();

            // ✅ Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
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
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>FLICK-FIX — Login</title>

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/font-awesome.min.css" rel="stylesheet">
  <link href="css/global.css" rel="stylesheet">
  <link href="css/index.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      font-family: 'Rajdhani', sans-serif;
      background: #0b0b0b;
      color: #fff;
    }

    .auth-wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
    }

    .card-auth {
      background: #0f0f10;
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 12px;
      box-shadow: 0 6px 30px rgba(0, 0, 0, 0.6);
    }

    .brand {
      color: #ff2d2d;
      font-weight: 700;
      letter-spacing: 0.6px;
    }

    .form-control:focus {
      box-shadow: none;
      border-color: rgba(255, 45, 45, 0.85);
    }

    .btn-primary.custom {
      background: #ff2d2d;
      border-color: #ff2d2d;
    }

    .btn-primary.custom:hover {
      background: #e02222;
      border-color: #e02222;
    }

    .small-muted {
      color: rgba(255, 255, 255, 0.6);
    }

    .divider {
      height: 1px;
      background: rgba(255, 255, 255, 0.04);
      margin: 1.25rem 0;
    }

    .social-btn {
      width: 48%;
    }

    .pw-toggle {
      cursor: pointer;
    }
  </style>
</head>

<body>

  <header class="mb-4">
    <nav class="navbar navbar-expand-md navbar-light" id="navbar_sticky">
      <div class="container">
        <a class="navbar-brand text-white fw-bold brand" href="index.html">
          <img src="img/newlogo.png" style="width:100px;height:50px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
          <ul class="navbar-nav ms-auto mb-0">
            <li class="nav-item"><a class="nav-link text-white" href="index.html">Home</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="about.html">About</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="contact.html">Contact</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main class="auth-wrap">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="card card-auth p-4 p-md-5">
            <div class="d-flex align-items-center mb-3">
              <h3 class="mb-0 me-auto text-white">
                <i class="fa fa-video-camera me-2" style="color:#ff2d2d;"></i>
                Sign in to <span class="brand">FLICK-FIX</span>
              </h3>
            </div>

            <form id="loginForm" method="POST" novalidate>
              <div class="mb-3">
                <label for="loginEmail" class="form-label small-muted">Email address</label>
                <input type="email" class="form-control" id="loginEmail" name="email"
                  placeholder="name@domain.com" required>
              </div>

              <div class="mb-3 position-relative">
                <label for="loginPassword" class="form-label small-muted">Password</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="loginPassword" name="password"
                    placeholder="Enter password" required minlength="6">
                  <span class="input-group-text bg-transparent border-start-0 pw-toggle" id="togglePw"
                    title="Show / hide password">
                    <i class="fa fa-eye"></i>
                  </span>
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="rememberMe">
                  <label class="form-check-label small-muted" for="rememberMe">Remember me</label>
                </div>
                <a href="forgot-password.html" class="small-muted">Forgot password?</a>
              </div>

              <button type="submit" class="btn btn-primary custom w-100">Sign In</button>
            </form>

            <?php if ($message): ?>
              <div class="alert alert-danger mt-3 p-2 text-center"><?= $message ?></div>
            <?php endif; ?>

            <div class="text-center mt-3 small-muted">
              Don’t have an account?
              <a href="register.php" class="text-decoration-none" style="color:#ff2d2d">Create one</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="pt-4 pb-4 mt-4" style="background:transparent;">
    <div class="container text-center small-muted">
      © <span id="year"></span> FLICK-FIX. All rights reserved.
    </div>
  </footer>

  <script>
    (function ($) {
      $('#year').text(new Date().getFullYear());
      $('#togglePw').on('click', function () {
        var $pw = $('#loginPassword');
        var type = $pw.attr('type') === 'password' ? 'text' : 'password';
        $pw.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        $pw.focus();
      });
    })(jQuery);
  </script>

</body>
</html>
