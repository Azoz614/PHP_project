<?php
include "db.php";

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Contact form
    if (isset($_POST['contact_submit'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);
        if ($name && $email && $message) {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $subject, $message);
            $stmt->execute();
            $success_message = "Thanks $name! Your message has been received — we’ll reach out soon!";
        } else $error_message = "Please fill all required fields.";
    }

    // Newsletter
    if (isset($_POST['subscribe'])) {
        $email = trim($_POST['newsletter_email']);
        if ($email) {
            $stmt = $conn->prepare("INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $success_message = "Subscribed successfully! You’ll now get the latest movie news!";
        } else $error_message = "Enter a valid email to subscribe.";
    }

    // Recommend Movie
    if (isset($_POST['recommend_submit'])) {
        $name = trim($_POST['rec_name']);
        $email = trim($_POST['rec_email']);
        $movie = trim($_POST['movie_title']);
        $reason = trim($_POST['reason']);
        if ($name && $email && $movie) {
            $stmt = $conn->prepare("INSERT INTO new_movies (user_name, email, movie_title, reason) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $movie, $reason);
            $stmt->execute();
            $success_message = "Awesome, $name! We’ve received your recommendation for '$movie'.";
        } else $error_message = "All fields required for recommendation.";
    }

    // Schedule Call
    if (isset($_POST['schedule_submit'])) {
        $name = trim($_POST['sch_name']);
        $email = trim($_POST['sch_email']);
        $team = intval($_POST['team_id']);
        $time = $_POST['schedule_time'];
        $message = trim($_POST['sch_message']);
        if ($name && $email && $team && $time) {
            $stmt = $conn->prepare("INSERT INTO scheduled_calls (user_name, email, team_id, schedule_time, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $name, $email, $team, $time, $message);
            $stmt->execute();
            $success_message = "Meeting scheduled successfully, $name! We’ll confirm shortly.";
        } else $error_message = "Please complete all schedule details.";
    }
}

$teams = $conn->query("SELECT id, team_name FROM support_teams");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Us | Flick-Fix</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  background: radial-gradient(circle at top, #141414, #0b0b0b);
  color: #fff;
  font-family: 'Poppins', sans-serif;
  overflow-x: hidden;
}
.contact-header {
  text-align: center;
  padding: 60px 0;
  background: linear-gradient(to right, #1b1b1b, #121212);
  border-bottom: 3px solid #dc3545;
  box-shadow: 0 0 15px rgba(220, 53, 69, 0.3);
}
.contact-header h1 {
  color: #dc3545;
  font-weight: 800;
  letter-spacing: 2px;
  text-shadow: 0 0 10px #ff5050;
}
.contact-info {
  display: flex;
  justify-content: center;
  gap: 40px;
  background: #121212;
  padding: 30px;
  flex-wrap: wrap;
  border-bottom: 2px solid #dc3545;
}
.info-box {
  text-align: center;
  transition: 0.3s;
}
.info-box i {
  font-size: 2.5rem;
  color: #ff4040;
  margin-bottom: 10px;
  transition: transform 0.4s ease;
}
.info-box:hover i {
  transform: scale(1.2) rotate(10deg);
  color: #fff;
}
.contact-form-section {
  max-width: 900px;
  margin: 50px auto;
  background: rgba(20,20,20,0.95);
  padding: 40px;
  border-radius: 15px;
  box-shadow: 0 0 25px rgba(220, 53, 69, 0.4);
  animation: fadeInUp 1s;
}
.contact-form-section h2 {
  text-align: center;
  color: #dc3545;
  font-weight: 700;
  margin-bottom: 10px;
}
.section-intro {
  text-align: center;
  font-size: 0.95rem;
  color: #aaa;
  margin-bottom: 25px;
}
.form-control {
  background-color: #222;
  border: 1px solid #ff5050;
  color: #fff;
  border-radius: 8px;
  transition: 0.3s;
}
.form-control:focus {
  box-shadow: 0 0 10px #ff5050;
  border-color: #ff5050;
}
.btn-danger {
  background-color: #dc3545;
  border: none;
  transition: all 0.3s;
  border-radius: 8px;
}
.btn-danger:hover {
  background-color: #ff4040;
  transform: scale(1.05);
  box-shadow: 0 0 10px #ff5050;
}
iframe {
  width: 100%;
  height: 300px;
  border: 3px solid #dc3545;
  border-radius: 10px;
  margin-top: 40px;
  animation: fadeIn 2s;
}
@keyframes fadeInUp {
  from {opacity: 0; transform: translateY(50px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<div class="contact-header animate__animated animate__fadeInDown">
  <h1>Contact Flick-Fix</h1>
  <p class="text-light">We’re here to help you with your movie journey!</p>
</div>

<div class="contact-info animate__animated animate__fadeInUp">
  <div class="info-box">
    <i class="bi bi-telephone-fill"></i>
    <h5>Phone</h5>
    <p>+94 71 123 4567</p>
  </div>
  <div class="info-box">
    <i class="bi bi-envelope-fill"></i>
    <h5>Email</h5>
    <p>support@flickfix.com</p>
  </div>
  <div class="info-box">
    <i class="bi bi-geo-alt-fill"></i>
    <h5>Address</h5>
    <p>No. 42, Cinema Street, Colombo</p>
  </div>
</div>

<div class="contact-form-section animate__animated animate__zoomIn">
  <h2>Send Us a Message</h2>
  <p class="section-intro">Have a question or feedback? Drop us a note and our team will get back to you soon.</p>
  <form method="POST" class="mb-5">
    <input type="text" name="name" class="form-control mb-2" placeholder="Your Name" required>
    <input type="email" name="email" class="form-control mb-2" placeholder="Your Email" required>
    <input type="text" name="subject" class="form-control mb-2" placeholder="Subject">
    <textarea name="message" class="form-control mb-3" rows="3" placeholder="Your Message" required></textarea>
    <button type="submit" name="contact_submit" class="btn btn-danger w-100">Send Message</button>
  </form>

  <h2>Recommend a Movie</h2>
  <p class="section-intro">Know a hidden gem or trending movie? Suggest it here and we’ll feature it!</p>
  <form method="POST" class="mb-5">
    <input type="text" name="rec_name" class="form-control mb-2" placeholder="Your Name" required>
    <input type="email" name="rec_email" class="form-control mb-2" placeholder="Your Email" required>
    <input type="text" name="movie_title" class="form-control mb-2" placeholder="Movie Title" required>
    <textarea name="reason" class="form-control mb-3" rows="2" placeholder="Why do you recommend it?" required></textarea>
    <button type="submit" name="recommend_submit" class="btn btn-danger w-100">Submit Recommendation</button>
  </form>

  <h2>Subscribe to Newsletter</h2>
  <p class="section-intro">Join our community and stay updated on the latest box-office buzz and reviews.</p>
  <form method="POST" class="text-center mb-5">
    <div class="input-group">
      <input type="email" name="newsletter_email" class="form-control" placeholder="Enter your email" required>
      <button type="submit" name="subscribe" class="btn btn-danger">Subscribe</button>
    </div>
  </form>

  <h2>Schedule a Call / Meeting</h2>
  <p class="section-intro">Want to partner or collaborate? Schedule a call with our support or media team.</p>
  <form method="POST">
    <input type="text" name="sch_name" class="form-control mb-2" placeholder="Your Name" required>
    <input type="email" name="sch_email" class="form-control mb-2" placeholder="Your Email" required>
    <select name="team_id" class="form-control mb-2" required>
      <option value="">Select Team</option>
      <?php while($row = $teams->fetch_assoc()): ?>
      <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['team_name']) ?></option>
      <?php endwhile; ?>
    </select>
    <input type="datetime-local" name="schedule_time" class="form-control mb-2" required>
    <textarea name="sch_message" class="form-control mb-3" rows="2" placeholder="Additional Message"></textarea>
    <button type="submit" name="schedule_submit" class="btn btn-danger w-100">Schedule Meeting</button>
  </form>

  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63310.31329927611!2d79.8308812!3d6.9270786!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2593c1c21a4cf%3A0xd9f28dffce7f63b5!2sColombo!5e0!3m2!1sen!2slk!4v1691234567890"></iframe>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const success = <?= json_encode($success_message) ?>;
  const error = <?= json_encode($error_message) ?>;

  if (success) {
    Swal.fire({
      icon: 'success',
      title: '✨ Success!',
      text: success,
      background: '#1b1b1b',
      color: '#fff',
      confirmButtonColor: '#dc3545',
      showClass: {popup: 'animate__animated animate__zoomIn'},
      hideClass: {popup: 'animate__animated animate__fadeOutUp'}
    });
  } else if (error) {
    Swal.fire({
      icon: 'error',
      title: '⚠️ Error!',
      text: error,
      background: '#1b1b1b',
      color: '#fff',
      confirmButtonColor: '#dc3545',
      showClass: {popup: 'animate__animated animate__shakeX'},
      hideClass: {popup: 'animate__animated animate__fadeOutUp'}
    });
  }
});
</script>
</body>
</html>
