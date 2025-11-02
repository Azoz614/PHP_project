<?php
session_start();
require_once "db.php";

// ---------- GET MOVIE ID ----------
$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($movie_id <= 0) die("Invalid movie ID.");

// ---------- FETCH MOVIE DETAILS ----------
$stmt = $conn->prepare("
    SELECT 
        m.id, m.title, m.description, m.year, m.poster, m.link,
        COALESCE(ROUND(AVG(r.rating),1),0) AS avg_rating,
        COUNT(r.id) AS total_reviews
    FROM movies m
    LEFT JOIN reviews r ON m.id = r.movie_id
    WHERE m.id = ?
    GROUP BY m.id, m.title, m.description, m.year, m.poster, m.link
");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
if (!$movie) die("Movie not found.");

// ---------- PREPARE TRAILER LINK FOR YOUTUBE ----------
$trailerLink = '';
if (!empty($movie['link'])) {
    if (strpos($movie['link'], 'youtube.com/watch?v=') !== false) {
        parse_str(parse_url($movie['link'], PHP_URL_QUERY), $urlParams);
        $videoId = $urlParams['v'] ?? '';
        if ($videoId) $trailerLink = "https://www.youtube.com/embed/$videoId?autoplay=1";
    } elseif (strpos($movie['link'], 'youtu.be/') !== false) {
        $videoId = basename(parse_url($movie['link'], PHP_URL_PATH));
        $trailerLink = "https://www.youtube.com/embed/$videoId?autoplay=1";
    } else {
        $trailerLink = $movie['link']; // direct embed link
    }
}

// ---------- CURRENT USER ----------
$user_id = $_SESSION['user']['id'] ?? null;

// ---------- ADD / UPDATE REVIEW ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
    $rating = intval($_POST['rating']);
    $review = trim($_POST['review']);

    $check_stmt = $conn->prepare("SELECT id FROM reviews WHERE movie_id=? AND user_id=?");
    $check_stmt->bind_param("ii", $movie_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $update_stmt = $conn->prepare("
            UPDATE reviews 
            SET rating=?, review_text=?, updated_at=NOW() 
            WHERE movie_id=? AND user_id=?
        ");
        $update_stmt->bind_param("isii", $rating, $review, $movie_id, $user_id);
        $update_stmt->execute();
    } else {
        $insert_stmt = $conn->prepare("
            INSERT INTO reviews (movie_id, user_id, rating, review_text, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $insert_stmt->bind_param("iiis", $movie_id, $user_id, $rating, $review);
        $insert_stmt->execute();
    }

    header("Location: movie.php?id=$movie_id");
    exit;
}

// ---------- FETCH ALL REVIEWS ----------
$reviews_stmt = $conn->prepare("
    SELECT r.*, u.first_name, u.profile_pic
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.movie_id = ?
    ORDER BY r.created_at DESC
");
$reviews_stmt->bind_param("i", $movie_id);
$reviews_stmt->execute();
$reviews = $reviews_stmt->get_result();

// ---------- FETCH CURRENT USER REVIEW ----------
$user_review = null;
if ($user_id) {
    $user_stmt = $conn->prepare("SELECT * FROM reviews WHERE movie_id=? AND user_id=?");
    $user_stmt->bind_param("ii", $movie_id, $user_id);
    $user_stmt->execute();
    $ur = $user_stmt->get_result();
    if ($ur->num_rows > 0) $user_review = $ur->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($movie['title']); ?> - FlickFix</title>
<link rel="stylesheet" href="assets/css/index.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { background-color: #0d0d0d; color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin:0; padding:0;}
.navbar { background-color:#111; padding:15px 30px;}
.navbar a { color:#de1002; font-size:1.5rem; font-weight:bold; text-decoration:none; }
.container { max-width:1200px; margin:0 auto; padding:30px 15px; }
.movie-header { display:flex; flex-wrap:wrap; gap:30px; margin-bottom:40px; }
.movie-header img { border-radius:10px; width:300px; max-height:450px; object-fit:cover; flex-shrink:0; box-shadow:0 5px 20px rgba(0,0,0,0.7);}
.movie-info { flex:1; }
.movie-info h1 { font-size:2.5rem; margin-bottom:10px; }
.movie-info p { font-size:1rem; line-height:1.6; margin-bottom:10px; }
.rating { margin:10px 0; }
.rating i { color:#f5c518; font-size:1.2rem; }
.review-section { margin-top:50px; }
.review-form { background-color:#1a1a1a; padding:20px; border-radius:10px; margin-bottom:40px; }
.review-form h3 { margin-bottom:15px; }
.review-form textarea { width:100%; padding:10px; border-radius:5px; background-color:#111; color:#fff; border:1px solid #333; resize:none; }
.review-form select { padding:5px 10px; margin-bottom:15px; border-radius:5px; background-color:#111; color:#fff; border:1px solid #333; }
.review-form button { background-color:#de1002; color:#fff; border:none; padding:10px 25px; border-radius:5px; cursor:pointer; transition:0.3s; }
.review-form button:hover { background-color:#a50c01; }
.review-card { background-color:#1a1a1a; padding:15px; border-radius:10px; margin-bottom:20px; }
.review-user { display:flex; align-items:center; margin-bottom:10px; }
.review-user img { width:50px; height:50px; border-radius:50%; object-fit:cover; margin-right:10px; }
.review-card p { margin:5px 0; }
.trailer-btn { background-color:#de1002; color:#fff; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:bold; margin-top:15px; transition:0.3s;}
.trailer-btn:hover { background-color:#a50c01; }
.modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); justify-content:center; align-items:center; z-index:1000; }
.modal-content { position:relative; width:80%; max-width:900px; }
.modal-content iframe { width:100%; height:500px; border:none; border-radius:10px; }
.close-btn { position:absolute; top:10px; right:10px; color:#fff; font-size:2rem; cursor:pointer; }
footer { background-color:#111; padding:20px 0; text-align:center; color:#888; margin-top:50px; }
@media(max-width:768px) {
    .movie-header { flex-direction:column; align-items:center; }
    .movie-header img { width:100%; max-width:350px; }
    .movie-info h1 { font-size:2rem; text-align:center; }
    .modal-content iframe { height:300px; }
}
</style>
</head>
<body>

<nav class="navbar">
  <div class="container">
    <a href="index.php">🎬 FlickFix</a>
  </div>
</nav>

<div class="container">
  <!-- MOVIE HEADER -->
  <div class="movie-header">
    <img src="<?php echo (!empty($movie['poster']) && file_exists('uploads/'.$movie['poster'])) ? 'uploads/'.$movie['poster'] : 'assets/img/default-poster.jpg'; ?>" alt="Poster">
    <div class="movie-info">
      <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
      <p class="text-muted"><?php echo htmlspecialchars($movie['year']); ?></p>
      <p><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
      <div class="rating">
        ⭐ <?php echo htmlspecialchars($movie['avg_rating']); ?> / 5 
        (<?php echo htmlspecialchars($movie['total_reviews']); ?> reviews)
      </div>

      <!-- WATCH TRAILER BUTTON -->
      <?php if(!empty($trailerLink)): ?>
        <button class="trailer-btn" id="watchTrailer">▶ Watch Trailer</button>
      <?php endif; ?>
    </div>
  </div>

  <!-- REVIEW FORM -->
  <div class="review-section">
    <?php if($user_id): ?>
      <div class="review-form">
        <h3><?php echo $user_review ? 'Edit Your Review':'Write a Review'; ?></h3>
        <form method="POST">
          <label>Rating:</label>
          <select name="rating" required>
            <?php for($i=5;$i>=1;$i--): ?>
              <option value="<?php echo $i;?>" <?php echo ($user_review && $user_review['rating']==$i)?'selected':'';?>><?php echo $i;?> ⭐</option>
            <?php endfor; ?>
          </select>
          <label>Your Review:</label>
          <textarea name="review" rows="4" required><?php echo htmlspecialchars($user_review['review_text'] ?? '');?></textarea>
          <button type="submit"><?php echo $user_review ? 'Update Review':'Submit Review';?></button>
        </form>
      </div>
    <?php else: ?>
      <p>🔒 Please <a href="login.php" style="color:#de1002;">log in</a> to write a review.</p>
    <?php endif; ?>

    <h3>User Reviews</h3>
    <?php if($reviews && $reviews->num_rows>0): ?>
      <?php while($r=$reviews->fetch_assoc()): ?>
        <?php $profilePic = (!empty($r['profile_pic']) && file_exists('uploads/'.$r['profile_pic'])) ? 'uploads/'.$r['profile_pic'] : 'assets/img/default-user.png'; ?>
        <div class="review-card">
          <div class="review-user">
            <img src="<?php echo $profilePic; ?>" alt="User">
            <strong><?php echo htmlspecialchars($r['first_name']); ?></strong>
            <span style="margin-left:auto;color:#888;font-size:0.9rem;"><?php echo date('M d, Y', strtotime($r['created_at'])); ?></span>
          </div>
          <div class="rating">
            <?php for($i=1;$i<=5;$i++): ?>
              <i class="<?php echo ($i<=$r['rating'])?'fa-solid fa-star':'fa-regular fa-star';?>"></i>
            <?php endfor; ?>
          </div>
          <p><?php echo nl2br(htmlspecialchars($r['review_text'])); ?></p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No reviews yet. Be the first to write one!</p>
    <?php endif; ?>
  </div>
</div>

<!-- TRAILER MODAL -->
<div class="modal" id="trailerModal">
  <div class="modal-content">
    <span class="close-btn" id="closeTrailer">&times;</span>
    <iframe src="" id="trailerFrame" allowfullscreen></iframe>
  </div>
</div>

<footer>
  &copy; <?php echo date('Y'); ?> FlickFix
</footer>

<script>
const trailerBtn = document.getElementById('watchTrailer');
const modal = document.getElementById('trailerModal');
const closeBtn = document.getElementById('closeTrailer');
const trailerFrame = document.getElementById('trailerFrame');

<?php if(!empty($trailerLink)): ?>
trailerBtn.addEventListener('click', () => {
    trailerFrame.src = "<?php echo $trailerLink; ?>";
    modal.style.display = 'flex';
});
<?php endif; ?>

closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    trailerFrame.src = '';
});

window.addEventListener('click', (e) => {
    if(e.target == modal){
        modal.style.display = 'none';
        trailerFrame.src = '';
    }
});
</script>

</body>
</html>
