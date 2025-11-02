<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Flick-Fix | Tamil Cinema Reviews & Trailers</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="../css/style.css" rel="stylesheet" />
  <style>
   
.nav-profile img {
  width: 40px;            
  height: 40px;
  object-fit: cover;        
  border-radius: 50%;       
  border: 2px solid #ff2d2d; 
  transition: transform 0.2s ease-in-out;
}

.nav-profile img:hover {
  transform: scale(1.05);   
}


.nav-profile {
  position: relative;
  margin-left: 15px;
}

.dropdown-menu {
  background: #0f0f10;
  border-radius: 10px;
  color: #fff;
}

.dropdown-item:hover {
  background: rgba(255, 45, 45, 0.15);
  color: #ff2d2d;
}
</style>
  
</head>
<body class="bg-dark text-white">

  <!-- ========================= NAVBAR ========================= -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-black py-3 sticky-top border-bottom border-danger">
    <div class="container">
      <a class="navbar-brand text-danger fw-bold fs-3" href="#"><img src="img/newlogo.png" style="width:100px;height:70px;"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto text-uppercase">
          <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#latest">Latest</a></li>
          <li class="nav-item"><a class="nav-link" href="#trending">Trending</a></li>
          <li class="nav-item"><a class="nav-link" href="#trailers">Trailers</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="booking.php">Ticket booking</a></li>
        </ul>
      </div>
    </div>
     <?php if (isset($_SESSION['user'])): 
          $profile_pic = !empty($_SESSION['user']['profile_pic']) ? $_SESSION['user']['profile_pic'] : 'default.png';
        ?>
      
          <div class="nav-profile dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
               id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="uploads/<?php echo htmlspecialchars($profile_pic); ?>" 
                   alt="Profile" class="me-2">
              <span><?php echo htmlspecialchars($_SESSION['user']['first_name']); ?></span>
            </a>

  <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2 p-3"
    aria-labelledby="profileDropdown"
    style="min-width:250px; background:#0f0f10; color:#fff; border-radius:10px;">

  <li class="text-center mb-2">
    <img src="uploads/<?php echo htmlspecialchars($profile_pic); ?>" 
         alt="Profile"
         class="rounded-circle mb-2"
         style="width:70px; height:70px; object-fit:cover; border:2px solid #ff2d2d;">
    <div class="fw-bold text-white">
      <?php echo htmlspecialchars($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']); ?>
    </div>
    <div class="small text-white-50">
      <?php echo htmlspecialchars($_SESSION['user']['email']); ?>
    </div>
  </li>

  <li><hr class="dropdown-divider" style="border-color:rgba(255,255,255,0.15);"></li>

  <li>
    <a class="dropdown-item text-white d-flex align-items-center" href="update_profile.php"
       style="transition:all 0.2s;">
      <i class="fa fa-user me-2 col_red"></i> Update Profile
    </a>
  </li>

  <li>
    <a class="dropdown-item text-white d-flex align-items-center" href="change_password.php"
       style="transition:all 0.2s;">
      <i class="fa fa-lock me-2 col_red"></i> Change Password
    </a>
  </li>

  <li><hr class="dropdown-divider" style="border-color:rgba(255,255,255,0.15);"></li>

  <li>
    <a class="dropdown-item text-white d-flex align-items-center" href="logout.php"
       style="transition:all 0.2s;">
      <i class="fa fa-sign-out me-2 col_red"></i> Logout
    </a>
  </li>
</ul>


              
            
          </div>
        <?php else: ?>
          <a class="btn btn-sm btn-danger" href="login.php">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  </nav>

  <!-- ========================= HERO SECTION ========================= -->
  <section class="py-5 text-center bg-black border-bottom border-danger">
    <div class="container">
      <h1 class="display-4 fw-bold text-danger">Tamil Cinema Reviews & Trailers</h1>
      <p class="lead text-light">Dive into the latest movie buzz, reviews, and exclusive trailers.</p>
      
      <form id="searchForm" class="d-flex justify-content-center mt-4">
        <input id="searchInput" type="text" class="form-control w-50 me-2" placeholder="Search movies...">
        <button class="btn btn-danger">Search</button>
      </form>

      <div id="searchResults" class="row justify-content-center mt-4"></div>
    </div>
    
  </section>

  <!-- ========================= LATEST MOVIES ========================= -->
  <section id="latest" class="py-5 bg-dark">
    <div class="container">
      <h2 class="text-danger fw-bold mb-4 border-bottom border-danger pb-2">Latest Movies</h2>
      <div class="row g-4">

        <!-- ========== Movie Card 1 ========== -->
        <div class="col-md-4">
          <div class="card bg-black border border-danger">
            <img src="uploads/leo.jpg" class="card-img-top" alt="Leo">
            <div class="card-body">
              <h5 class="card-title text-danger">Leo</h5>
              <p class="card-text">A man with a mysterious past faces violent forces from his previous life.</p>
              <button class="btn btn-outline-danger btn-sm mb-3" onclick="watchTrailer(1)">Watch Trailer</button>

              <!-- Review Form -->
              <form method="POST" action="api/reviews.php">
                <input type="hidden" name="movieId" value="1">
                <div class="mb-2">
                  <input type="number" step="0.1" name="rating" class="form-control form-control-sm" placeholder="Rating (0-10)" required>
                </div>
                <div class="mb-2">
                  <textarea name="comment" class="form-control form-control-sm" placeholder="Write your review..." required></textarea>
                </div>
                <button type="submit" class="btn btn-danger btn-sm w-100">Submit Review</button>
              </form>
            </div>
          </div>
        </div>

        <!-- ========== Movie Card 2 ========== -->
        <div class="col-md-4">
          <div class="card bg-black border border-danger">
            <img src="uploads/jailer.webp" class="card-img-top" alt="Jailer">
            <div class="card-body">
              <h5 class="card-title text-danger">Jailer</h5>
              <p class="card-text">A retired jailer sets out to find his missing son, leading to an unexpected clash.</p>
              <button class="btn btn-outline-danger btn-sm mb-3" onclick="watchTrailer(2)">Watch Trailer</button>

              <!-- Review Form -->
              <form method="POST" action="api/reviews.php">
                <input type="hidden" name="movieId" value="2">
                <div class="mb-2">
                  <input type="number" step="0.1" name="rating" class="form-control form-control-sm" placeholder="Rating (0-10)" required>
                </div>
                <div class="mb-2">
                  <textarea name="comment" class="form-control form-control-sm" placeholder="Write your review..." required></textarea>
                </div>
                <button type="submit" class="btn btn-danger btn-sm w-100">Submit Review</button>
              </form>
            </div>
          </div>
        </div>

        <!-- ========== Movie Card 3 ========== -->
        <div class="col-md-4">
          <div class="card bg-black border border-danger">
            <img src="img/vikram.jpg" class="card-img-top" alt="Vikram">
            <div class="card-body">
              <h5 class="card-title text-danger">Vikram</h5>
              <p class="card-text">A special agent unravels a network of dangerous crimes beneath the city.</p>
              <button class="btn btn-outline-danger btn-sm mb-3" onclick="watchTrailer(3)">Watch Trailer</button>

              <!-- Review Form -->
              <form method="POST" action="api/reviews.php">
                <input type="hidden" name="movieId" value="3">
                <div class="mb-2">
                  <input type="number" step="0.1" name="rating" class="form-control form-control-sm" placeholder="Rating (0-10)" required>
                </div>
                <div class="mb-2">
                  <textarea name="comment" class="form-control form-control-sm" placeholder="Write your review..." required></textarea>
                </div>
                <button type="submit" class="btn btn-danger btn-sm w-100">Submit Review</button>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ========================= TRENDING MOVIES ========================= -->
  <section id="trending" class="py-5 bg-black">
    <div class="container">
      <h2 class="text-danger fw-bold mb-4 border-bottom border-danger pb-2">Trending Now</h2>
      <div class="row g-4">
        <div class="col-md-3">
          <div class="card bg-dark border border-danger">
            <img src="img/gilli.webp" class="card-img-top" alt="Ponniyin Selvan 2">
            <div class="card-body text-center">
              <h6 class="text-danger">Gilli</h6>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-dark border border-danger">
            <img src="img/varisu.webp" class="card-img-top" alt="Varisu">
            <div class="card-body text-center">
              <h6 class="text-danger">Varisu</h6>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-dark border border-danger">
            <img src="img/vikramveda.webp" class="card-img-top" alt="Vikram Vedha">
            <div class="card-body text-center">
              <h6 class="text-danger">Vikram Vedha</h6>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-dark border border-danger">
            <img src="img/master.webp" class="card-img-top" alt="Master">
            <div class="card-body text-center">
              <h6 class="text-danger">Master</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================= TRAILERS SECTION ========================= -->
  <section id="trailers" class="py-5 bg-dark border-top border-danger">
    <div class="container">
      <h2 class="text-danger fw-bold mb-4 border-bottom border-danger pb-2">Latest Trailers</h2>
      
      <div id="trailer-player" class="ratio ratio-16x9 mb-3" style="display:none;">
        <iframe id="trailer-frame" src="" title="YouTube Trailer" allowfullscreen></iframe>
      </div>
      <p id="no-trailer" class="text-muted">Click on a movie’s “Watch Trailer” button to play the trailer here.</p>
    </div>
  </section>

  <!-- ========================= FOOTER ========================= -->
  <footer class="text-center py-4 bg-dark border-top border-danger">
    <p class="mb-0 text-light">© 2025 Flick-Fix | All Rights Reserved.</p>
  </footer>

  <!-- ========================= SCRIPTS ========================= -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
  // Search Function
  document.getElementById('searchForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const term = document.getElementById('searchInput').value.trim();
    const res = await fetch('api/search.php?term=' + encodeURIComponent(term));
    const data = await res.json();
    const resultsDiv = document.getElementById('searchResults');
    if (data.results && data.results.length > 0) {
      resultsDiv.innerHTML = data.results.map(r =>
        `<div class='col-md-3'>
          <div class='card bg-dark border border-danger'>
            <img src='${r.poster}' class='card-img-top'>
            <div class='card-body text-center'>
              <h6 class='text-danger'>${r.title}</h6>
            </div>
          </div>
        </div>`).join('');
    } else {
      resultsDiv.innerHTML = "<p class='text-danger mt-3'>No results found.</p>";
    }
  });

  // Watch Trailer Function
  function watchTrailer(movieId) {
    fetch('api/get_trailer.php?movie_id=' + movieId)
      .then(res => res.json())
      .then(data => {
        const iframe = document.getElementById('trailer-frame');
        const trailerDiv = document.getElementById('trailer-player');
        const message = document.getElementById('no-trailer');

        if (data.status === 'success') {
          let videoId = '';
          const url = data.youtube_link;
          if (url.includes('v=')) {
            videoId = url.split('v=')[1].split('&')[0];
          } else if (url.includes('youtu.be/')) {
            videoId = url.split('youtu.be/')[1];
          }
          iframe.src = 'https://www.youtube.com/embed/' + videoId;
          trailerDiv.style.display = 'block';
          message.style.display = 'none';
          window.scrollTo({ top: trailerDiv.offsetTop - 100, behavior: 'smooth' });
        } else {
          iframe.src = '';
          trailerDiv.style.display = 'none';
          message.innerText = '🚫 Trailer not available for this movie.';
          message.style.display = 'block';
        }
      })
      .catch(err => console.error('Trailer fetch error:', err));
  }
  </script>
</body>
</html>
