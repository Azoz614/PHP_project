<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Flick-FIX</title>
<link href="css/bootstrap.min.css" rel="stylesheet" >
<link href="css/font-awesome.min.css" rel="stylesheet" >
<link href="css/global.css" rel="stylesheet">
<link href="css/index.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
<script src="js/bootstrap.bundle.min.js"></script>
<style>
body {font-family:'Rajdhani',sans-serif; background:#030303; color:#fff;}
.nav-profile img {border-radius:50%; width:40px; height:40px; object-fit:cover;}
.col_red {color:#ff2d2d;}
.bg_red {background-color:#ff2d2d;}
.bg_grey {background-color:#1a1a1a;}
.button {background:#ff2d2d;color:#fff;padding:6px 15px;border-radius:4px;text-decoration:none;}
</style>
</head>
<body>

<section id="top">
<div class="container">
 <div class="row top_1 align-items-center">
  <div class="col-md-3">
   <div class="top_1l pt-1">
    <h3 class="mb-0"><a class="text-white" href="index.php"><img src="img/newlogo.png" style="width:100px;height:70px;"></a></h3>
   </div>
  </div>
  <div class="col-md-5">
   <div class="top_1m">
    <div class="input-group" style="width:800px;">
    <input type="text" id="movieSearch" class="form-control bg-black" placeholder="Search Movie..." style="width:600px;">
    <span class="input-group-btn">
        <button class="btn btn text-white bg_red rounded-0 border-0" type="button" id="searchBtn">Search</button>
    </span>
</div>
<!-- Search results container -->
<div id="searchResults" class="mt-2"></div>

	
   </div>
  </div>
  <div class="col-md-4 text-end d-flex justify-content-end align-items-center">
     

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
</section>


<section id="header">
<nav class="navbar navbar-expand-md navbar-light" id="navbar_sticky">
  <div class="container">
    <a class="navbar-brand text-white fw-bold" href="index.php"><img src="img/newlogo.png" style="width:100px;height:70px;"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mb-0">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.html">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
        <li class="nav-item"><a class="nav-link" href="booking.php"> Ticket booking</a></li>


      </ul>
    </div>
  </div>
</nav>
</section>


<section id="center" class="center_home">
 <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2" class="" aria-current="true"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="img/dragon.png" class="d-block w-100" alt="...">
      <div class="carousel-caption d-md-block">
       <h1 class="font_60"> Entertainment FLICK-FIX</h1>
	   <h6 class="mt-3">
	    <span class="col_red me-3">
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star-half-o"></i>
		</span>
		 4.5 (Imdb)      Year : 2022
		 <a class="bg_red p-2 pe-4 ps-4 ms-3 text-white d-inline-block" href="#">Action</a>
	   </h6>
	   <p class="mt-3">Four waves of increasingly deadly alien attacks have left most of Earth in ruin. Cassie is on the run, desperately trying to save her younger brother.</p>
	   <p class="mb-2"><span class="col_red me-1 fw-bold">Starring:</span> Eget Nulla Semper Porta Dapibus Diam Ipsum</p>
	   <p class="mb-2"><span class="col_red me-1 fw-bold">Genres:</span> Music</p>
	   <p><span class="col_red me-1 fw-bold">Runtime:</span> 1h 32m</p>	
	   <h6 class="mt-4"><a class="button" href="#"><i class="fa fa-play-circle align-middle me-1"></i> Watch Trailer</a></h6>
      </div>
    </div>
    <div class="carousel-item">
      <img src="img/pushpa.png" class="d-block w-100" alt="...">
      <div class="carousel-caption d-md-block">
       <h1 class="font_60"> Lorem Semper Nulla</h1>
	   <h6 class="mt-3">
	    <span class="col_red me-3">
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star-half-o"></i>
		</span>
		 4.5 (Imdb)      Year : 2022
		 <a class="bg_red p-2 pe-4 ps-4 ms-3 text-white d-inline-block" href="#">Action</a>
	   </h6>
	   <p class="mt-3">Four waves of increasingly deadly alien attacks have left most of Earth in ruin. Cassie is on the run, desperately trying to save her younger brother.</p>
	   <p class="mb-2"><span class="col_red me-1 fw-bold">Starring:</span> Eget Nulla Semper Porta Dapibus Diam Ipsum</p>
	   <p class="mb-2"><span class="col_red me-1 fw-bold">Genres:</span> Music</p>
	   <p><span class="col_red me-1 fw-bold">Runtime:</span> 1h 32m</p>	
	   <h6 class="mt-4"><a class="button" href="#"><i class="fa fa-play-circle align-middle me-1"></i> Watch Trailer</a></h6>
      </div>
    </div>
    <div class="carousel-item">
      <img src="img/kaththi.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-md-block">
       <h1 class="font_60"> Eget Diam Ipsum</h1>
	   <h6 class="mt-3">
	    <span class="col_red me-3">
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star"></i>
		 <i class="fa fa-star-half-o"></i>
		</span>
		 4.5 (Imdb)      Year : 2022
		 <a class="bg_red p-2 pe-4 ps-4 ms-3 text-white d-inline-block" href="#">Action</a>
	   </h6>
	   <p class="mt-3">Four waves of increasingly deadly alien attacks have left most of Earth in ruin. Cassie is on the run, desperately trying to save her younger brother.</p>
	   <p class="mb-2"><span class="col_red me-1 fw-bold">Starring:</span> Eget Nulla Semper Porta Dapibus Diam Ipsum</p>
	   <p class="mb-2"><span class="col_red me-1 fw-bold">Genres:</span> Music</p>
	   <p><span class="col_red me-1 fw-bold">Runtime:</span> 1h 32m</p>	
	   <h6 class="mt-4 mb-0"><a class="button" href="#"><i class="fa fa-play-circle align-middle me-1"></i> Watch Trailer</a></h6>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
</section>

<section id="trend" class="pt-4 pb-5">
  <div class="container">
    <h2 class="text-center mb-4">Trending Movies</h2>

    <div id="movieCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        $sql = "
        SELECT m.*, 
               IFNULL(ROUND(AVG(r.rating),1), 0) AS avg_rating, 
               COUNT(r.id) AS total_reviews
        FROM movies m
        LEFT JOIN reviews r ON m.id = r.movie_id
        GROUP BY m.id
        ORDER BY m.created_at DESC
        LIMIT 8";
        $result = $conn->query($sql);
        $active = 'active';
        $count = 0;

        while ($row = $result->fetch_assoc()) {
          if ($count % 4 == 0) {
            if ($count > 0) echo '</div></div>';
            echo '<div class="carousel-item ' . $active . '"><div class="row">';
            $active = '';
          }
          $poster = !empty($row['poster']) ? $row['poster'] : 'default_movie.jpg';
        ?>
          <div class="col-md-3 mb-4">
            <div class="card bg_grey text-white border-0">
              <img src="uploads/<?php echo htmlspecialchars($poster); ?>" class="card-img-top" style="height:350px;object-fit:cover;">
              <div class="card-body">
                <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="small mb-1"><?php echo htmlspecialchars($row['year']); ?></p>
                <p>
                  <span class="col_red me-2">
                    <?php
                      $stars = floor($row['avg_rating']);
                      for ($i = 0; $i < $stars; $i++) echo '<i class="fa fa-star"></i>';
                      if ($row['avg_rating'] - $stars >= 0.5) echo '<i class="fa fa-star-half-o"></i>';
                    ?>
                  </span>
                  <?php echo htmlspecialchars($row['avg_rating']); ?>/5 (<?php echo htmlspecialchars($row['total_reviews']); ?> reviews)
                </p>
                <p class="small text-muted"><?php echo htmlspecialchars(substr($row['description'], 0, 60)); ?>...</p>
                <a href="movie.php?id=<?php echo $row['id']; ?>" class="button btn-sm">View</a>
              </div>
            </div>
          </div>
        <?php
          $count++;
        }
        if ($count > 0) echo '</div></div>';
        ?>
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#movieCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#movieCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </div>
</section>



<section id="upcome" class="pt-4 pb-5">




</section>




<section id="footer">
<div class="footer_m clearfix">
 <div class="container">
   <div class="row footer_1">
    <div class="col-md-4">
	 <div class="footer_1i">
	   <h3><a class="text-white" href="index.html"><i class="fa fa-video-camera col_red me-1"></i> Planet</a></h3>
	   <p class="mt-3">Lorem ipsum dolor sit amet consect etur adi pisicing elit sed do eiusmod tempor incididunt. Lorem ipsum dolor sit amet consect etur. </p>
	   <h6 class="fw-normal"><i class="fa fa-map-marker fs-5 align-middle col_red me-1"></i> 5311 Ceaver Sidge Td.
Pakland, DE 13507</h6>
        <h6 class="fw-normal mt-3"><i class="fa fa-envelope fs-5 align-middle col_red me-1"></i> info@gmail.com</h6>
		<h6 class="fw-normal mt-3 mb-0"><i class="fa fa-phone fs-5 align-middle col_red me-1"></i>  +123 123 456</h6>
	 </div>
	</div>
	
	   

		<ul class="social-network social-circle mb-0 mt-4">
			<li><a href="#" class="icoRss" title="Rss"><i class="fa fa-instagram"></i></a></li>
			<li><a href="#" class="icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>
			<li><a href="#" class="icoTwitter" title="Twitter"><i class="fa fa-twitter"></i></a></li>
			<li><a href="#" class="icoGoogle" title="Google +"><i class="fa fa-youtube"></i></a></li>
			<li><a href="#" class="icoLinkedin" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
		</ul>
	 </div>
	</div>
   </div>
</div>
</div>
</section>

<section id="footer_b" class="pt-3 pb-3 bg_grey">
<div class="container">
   <div class="row footer_1">
     <div class="col-md-8">
	  <div class="footer_1l">
	   <p class="mb-0">© 2013 Your Website Name. All Rights Reserved | Design by </p>
	  </div>
	 </div>
	 
</div>
</section>

<script>
window.onscroll = function() {myFunction()};

var navbar_sticky = document.getElementById("navbar_sticky");
var sticky = navbar_sticky.offsetTop;
var navbar_height = document.querySelector('.navbar').offsetHeight;

function myFunction() {
  if (window.pageYOffset >= sticky + navbar_height) {
    navbar_sticky.classList.add("sticky")
	document.body.style.paddingTop = navbar_height + 'px';
  } else {
    navbar_sticky.classList.remove("sticky");
	document.body.style.paddingTop = '0'
  }
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('#movieSearch').on('keyup', function(){
        var term = $(this).val();
        if(term.length > 1){
            $.ajax({
                url: 'search.php',
                method: 'GET',
                data: {term: term},
                success: function(data){
                    $('#searchResults').html(data);
                }
            });
        } else {
            $('#searchResults').html('');
        }
    });

    // Optional: search button click
    $('#searchBtn').on('click', function(){
        var term = $('#movieSearch').val();
        if(term.length > 0){
            $.ajax({
                url: 'search.php',
                method: 'GET',
                data: {term: term},
                success: function(data){
                    $('#searchResults').html(data);
                }
            });
        }
    });
});
</script>


</body>

</html>