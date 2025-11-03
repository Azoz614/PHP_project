<?php
session_start();
include 'db.php';

// Fetch moods
$moodQuery = "SELECT DISTINCT mood FROM mood_movies";
$moodResult = $conn->query($moodQuery);
$moods = [];
if ($moodResult && $moodResult->num_rows > 0) {
    while ($row = $moodResult->fetch_assoc()) {
        $moods[] = $row;
    }
}

// Blog placeholder data
$blogs = [
    ["title" => "The Rise of Tamil Cinema", "content" => "Discover how Tamil cinema has influenced storytelling with unique direction and acting depth.", "image" => "img/blog1.jpg"],
    ["title" => "Top 5 Feel-Good Movies", "content" => "From laughter to tears, these movies will light up your mood instantly.", "image" => "img/blog2.jpg"],
    ["title" => "Behind the Scenes: Making of Kanguva", "content" => "A deep dive into the visual and sound design magic that shaped Kanguva.", "image" => "img/blog3.jpg"]
];

// Fetch upcoming releases
$upcoming = [];
$upcomingQuery = "SELECT * FROM upcoming_releases ORDER BY release_date ASC LIMIT 3";
$upcomingResult = $conn->query($upcomingQuery);
if ($upcomingResult && $upcomingResult->num_rows > 0) {
    while ($row = $upcomingResult->fetch_assoc()) {
        $upcoming[] = $row;
    }
}

// Fetch poll of the day
$poll = null;
$pollOptions = [];
$pollQuery = "SELECT * FROM poll_of_the_day ORDER BY created_at DESC LIMIT 1";
$pollResult = $conn->query($pollQuery);
if ($pollResult && $pollResult->num_rows > 0) {
    $poll = $pollResult->fetch_assoc();
    $pollId = $poll['id'];
    $pollOptionsResult = $conn->query("SELECT * FROM poll_options WHERE poll_id = $pollId");
    if ($pollOptionsResult && $pollOptionsResult->num_rows > 0) {
        while ($row = $pollOptionsResult->fetch_assoc()) {
            $pollOptions[] = $row;
        }
    }
}

// Handle Poll Vote
$voted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {
    $option_id = $_POST['option'];
    $poll_id = $_POST['poll_id'];
    $user = session_id();

    $stmt = $conn->prepare("INSERT INTO poll_votes (poll_id, option_id, user_identifier) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $poll_id, $option_id, $user);
    $stmt->execute();
    $stmt->close();
    $voted = true;
}

// Handle Mood Suggestion
$moodMovies = [];
if (isset($_POST['mood_select'])) {
    $selectedMood = $_POST['mood'];
    $stmt = $conn->prepare("SELECT movie_title FROM mood_movies WHERE mood = ?");
    $stmt->bind_param("s", $selectedMood);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $moodMovies[] = $row;
    }
    $stmt->close();
}

// Handle Spin Wheel
$spin = null;
$spinQuery = "SELECT movie_title, poster FROM spin_pool ORDER BY RAND() LIMIT 1";
$spinResult = $conn->query($spinQuery);
if ($spinResult && $spinResult->num_rows > 0) {
    $spin = $spinResult->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FlickFix | Blog & Fun Zone</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #000;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
    }
    .hero {
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('img/blog_banner.jpg') center/cover no-repeat;
      text-align: center;
      padding: 100px 20px;
      color: #fff;
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }
    .section-title {
      border-bottom: 3px solid #dc3545;
      display: inline-block;
      margin-bottom: 20px;
      padding-bottom: 5px;
    }
    .blog-card {
      background: #111;
      border: 1px solid #dc3545;
      border-radius: 15px;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .blog-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 20px #dc3545;
    }
    .poll-option {
      background: #222;
      border: 1px solid #444;
      border-radius: 10px;
      padding: 10px;
      cursor: pointer;
      transition: 0.3s;
    }
    .poll-option:hover {
      background: #dc3545;
    }
    .countdown {
      font-size: 1.2rem;
      color: #ffcc00;
    }
    .mood-card {
      background: #111;
      border: 1px solid #ffcc00;
      border-radius: 10px;
      padding: 15px;
    }
  </style>
</head>
<body>

<div class="hero">
  <h1 class="display-4 fw-bold">🎬 FlickFix Blog & Fun Zone</h1>
  <p class="lead">Explore news, fun activities & mood-based movie magic!</p>
</div>

<div class="container py-5">
  <!-- Blog Section -->
  <h2 class="section-title text-danger">Latest Movie Blogs</h2>
  <div class="row g-4 mb-5">
    <?php foreach ($blogs as $blog): ?>
      <div class="col-md-4">
        <div class="blog-card p-3">
          <img src="<?= htmlspecialchars($blog['image']) ?>" class="img-fluid rounded mb-3" alt="">
          <h4 class="text-warning"><?= htmlspecialchars($blog['title']) ?></h4>
          <p><?= htmlspecialchars($blog['content']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  
  <!-- Upcoming Releases Countdown -->
  <h2 class="section-title text-info mt-5">Upcoming Releases</h2>
  <div class="row g-3">
    <?php foreach ($upcoming as $up): ?>
      <div class="col-md-4">
        <div class="card bg-dark text-light border border-danger p-2">
          <img src="img/<?= htmlspecialchars($up['poster']) ?>" class="card-img-top" alt="">
          <div class="card-body">
            <h5><?= htmlspecialchars($up['title']) ?></h5>
            <div class="countdown" data-date="<?= $up['release_date'] ?>"></div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

 

 
<script>
// Countdown timers
document.querySelectorAll('.countdown').forEach(cd => {
  const date = new Date(cd.dataset.date).getTime();
  setInterval(() => {
    const now = new Date().getTime();
    const diff = date - now;
    if (diff < 0) {
      cd.textContent = "🎥 Released!";
      return;
    }
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hrs = Math.floor((diff / (1000 * 60 * 60)) % 24);
    const mins = Math.floor((diff / (1000 * 60)) % 60);
    cd.textContent = `${days}d ${hrs}h ${mins}m left`;
  }, 1000);
});
</script>
</body>
</html>
