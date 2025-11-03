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
        <li class="nav-item"><a class="nav-link" href="service.php"> Service</a></li>


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
      <img src="uploads/eleven.jpg" class="d-block w-100" alt="...">
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
      <img src="uploads/pushpa.png" class="d-block w-100" alt="...">
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
      <img src="uploads/kaththi.jpg" class="d-block w-100" alt="...">
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


<!-- =================== NEW FEATURES SECTION (placed inside #upcome) =================== -->
<section id="upcome" class="pt-4 pb-5">
  <div class="container">
    <div class="row g-4">

      <!-- Mood Based Movie Finder -->
      <div class="col-lg-4">
        <div class="widget-card p-3">
          <h5 class="mb-3">Mood-based Movie Finder</h5>
          <p class="small text-white-50">Select how you feel and we'll suggest a movie.</p>
          <div class="mood-buttons mb-3">
            <button class="btn btn-sm btn-outline-light" data-mood="happy">Happy</button>
            <button class="btn btn-sm btn-outline-light" data-mood="sad">Sad</button>
            <button class="btn btn-sm btn-outline-light" data-mood="heartbroken">Heartbroken</button>
            <button class="btn btn-sm btn-outline-light" data-mood="chill">Chill</button>
            <button class="btn btn-sm btn-outline-light" data-mood="adventure">Adventure</button>
          </div>
          <div id="moodResult" class="mt-2"></div>
        </div>
      </div>

      <!-- Pick For You: Mini Quiz -->
      <div class="col-lg-4">
        <div class="widget-card p-3">
          <h5 class="mb-3">Pick for You — Mini Quiz</h5>
          <p class="small text-white-50">Take a 3-question mini quiz to get a personalized recommendation.</p>
          <div id="quizArea">
            <div id="quizQuestions" class="mb-3">Loading quiz...</div>
            <button id="submitQuiz" class="btn btn-sm bg_red">Get Recommendation</button>
            <div id="quizResult" class="mt-3 spin-result"></div>
          </div>
        </div>
      </div>

      <!-- Upcoming release countdown -->
      <div class="col-lg-4">
        <div class="widget-card p-3">
          <h5 class="mb-3">Upcoming Releases — Countdown</h5>
          <p class="small text-white-50">Countdowns for the next 3 releases.</p>
          <div id="upcomingList" class="row g-2"></div>
        </div>
      </div>

      <!-- Daily Spin Wheel -->
      <div class="col-lg-4">
        <div class="widget-card p-3">
          <h5 class="mb-3">Daily Spin — Try your luck</h5>
          <p class="small text-white-50">Click the wheel to get a movie pick for today.</p>
          <div class="d-flex justify-content-center mb-3">
            <div id="spinWheel" class="animate__animated" title="Click to spin">
              <div style="text-align:center;color:#fff;">
                <i class="fa fa-random fa-2x"></i><div style="font-size:12px;margin-top:6px;">SPIN</div>
              </div>
            </div>
          </div>
          <div id="spinResult" class="spin-result"></div>
        </div>
      </div>

      <!-- Poll of the Day -->
      <div class="col-lg-8">
        <div class="widget-card p-3">
          <h5 class="mb-3">Poll of the Day</h5>
          <div id="pollArea">
            <?php
            // show active poll (most recent)
            $pollQ = $conn->query("SELECT id, question FROM poll_of_the_day ORDER BY created_at DESC LIMIT 1");
            if ($pollQ && $pollQ->num_rows > 0) {
              $poll = $pollQ->fetch_assoc();
              $poll_id = (int)$poll['id'];
              echo '<div id="pollQuestion" class="mb-2">'.htmlspecialchars($poll['question']).'</div>';
              $opts = $conn->prepare("SELECT id, option_text FROM poll_options WHERE poll_id = ?");
              $opts->bind_param('i',$poll_id);
              $opts->execute();
              $resOpts = $opts->get_result();
              echo '<div id="pollOptions" class="row g-2">';
              while ($o = $resOpts->fetch_assoc()) {
                echo '<div class="col-md-4"><div class="p-2 bg_black" style="background:#0b0b0b;border-radius:8px;">';
                echo '<div class="poll-option" data-poll="'.$poll_id.'" data-option="'.(int)$o['id'].'">'.htmlspecialchars($o['option_text']).'</div>';
                echo '</div></div>';
              }
              echo '</div>';
              echo '<div class="mt-3" id="pollResultsSmall"></div>';
            } else {
              echo '<div class="small text-white-50">No poll available.</div>';
            }
            ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- =================== END NEW FEATURES =================== -->

<section id="footer">
<div class="footer_m clearfix">
 <div class="container">
   <div class="row footer_1">
    <div class="col-md-4">
	 <div class="footer_1i">
	   <h3><a class="text-white" href="index.html"><i class="fa fa-video-camera col_red me-1"></i> Flick-fix</a></h3>
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

<script>
$(document).ready(function(){

    // existing search functionality
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

    // --- Mood finder ---
    $('.mood-buttons .btn').on('click', function(){
        var mood = $(this).data('mood');
        $('#moodResult').html('<div class="text-white small">Searching...</div>');
        $.post('?action=get_mood_suggestion', {mood: mood}, function(resp){
            try { resp = JSON.parse(resp); } catch(e){ resp = resp; }
            if (resp.status === 'ok') {
                $('#moodResult').html(resp.html);
            } else {
                $('#moodResult').html('<div class="text-white small">No suggestion.</div>');
            }
        });
    });

    // --- Quiz load & submit ---
    function loadQuiz(){
        $.get('?action=get_quiz', function(resp){
            try { resp = JSON.parse(resp); } catch(e){ resp = resp; }
            if (resp.status === 'ok') {
                $('#quizQuestions').html(resp.html);
            } else {
                $('#quizQuestions').html('<div class="small text-white-50">Quiz not available.</div>');
            }
        });
    }
    loadQuiz();

    $('#submitQuiz').on('click', function(){
        var answers = [];
        $('#quizQuestions input[type=radio]:checked').each(function(){
            answers.push($(this).val());
        });
        if (answers.length < 1) { alert('Please answer the quiz.'); return; }
        $('#quizResult').html('<div class="small text-white-50">Generating recommendation...</div>');
        $.post('?action=quiz_submit', {answers: answers}, function(resp){
            try { resp = JSON.parse(resp); } catch(e){ resp = resp; }
            if (resp.status === 'ok') {
                $('#quizResult').html(resp.html);
            } else {
                $('#quizResult').html('<div class="small text-white-50">Could not generate recommendation.</div>');
            }
        });
    });

    // --- Spin Wheel ---
    var spinning = false;
    $('#spinWheel').on('click', function(){
        if (spinning) return;
        spinning = true;
        var el = $(this);
        el.addClass('animate__rotateIn');
        el.css({'transition':'transform 2.5s ease-out','transform':'rotate(1080deg)'});
        setTimeout(function(){
            // reset rotation smoothly
            el.css({'transform':'rotate(0deg)','transition':'none'});
            // get random movie
            $.get('?action=spin_wheel', function(resp){
                try { resp = JSON.parse(resp); } catch(e){ resp = resp; }
                if (resp.status === 'ok') {
                    var html = '<div class="d-flex align-items-center"><img src="uploads/'+(resp.poster||'default_movie.jpg')+'" style="width:80px;height:110px;object-fit:cover;border-radius:6px;margin-right:12px;"><div><div class="small text-white-50">Your pick</div><div class="fw-bold">'+resp.title+'</div><a class="button mt-2 d-inline-block" href="search_results.php?title='+encodeURIComponent(resp.title)+'">View</a></div></div>';
                    $('#spinResult').html(html);
                } else {
                    $('#spinResult').html('<div class="small text-white-50">'+(resp.msg||'No movie found')+'</div>');
                }
                spinning = false;
            });
        }, 900);
    });

    // --- Poll ---
    $('#pollOptions .poll-option').on('click', function(){
        var poll = $(this).data('poll');
        var option = $(this).data('option');
        if (!poll || !option) return;
        $.post('?action=poll_vote', {poll_id: poll, option_id: option}, function(resp){
            try { resp = JSON.parse(resp); } catch(e){ resp = resp; }
            if (resp.status === 'ok') {
                loadPollResults(poll);
            } else {
                alert('Could not submit vote.');
            }
        });
    });

    function loadPollResults(poll_id){
        $.get('?action=poll_results&poll_id='+poll_id, function(resp){
            try { resp = JSON.parse(resp); } catch(e){ resp = resp; }
            if (resp.status === 'ok') {
                var total = resp.total || 0;
                var html = '';
                resp.options.forEach(function(opt){
                    var pct = total ? Math.round((opt.votes/total)*100) : 0;
                    html += '<div class="mb-2"><div class="small text-white-50">'+opt.option_text+' — '+opt.votes+' votes</div><div class="progress" style="height:8px;background:#0b0b0b"><div class="progress-bar" role="progressbar" style="width:'+pct+'%"></div></div></div>';
                });
                $('#pollResultsSmall').html(html);
            }
        });
    }

    // initial load poll results if poll exists
    var firstPoll = $('#pollOptions').length ? $('#pollOptions .poll-option').first().data('poll') : null;
    if (firstPoll) loadPollResults(firstPoll);

    // --- Upcoming countdowns ---
    function loadUpcoming() {
        $.get('?action=get_upcoming', function(resp){
            try { resp = JSON.parse(resp); } catch(e){ resp = resp; }
            if (resp.status === 'ok') {
                var html = '';
                resp.items.forEach(function(item, idx){
                    var id = 'countdown_'+item.id;
                    html += '<div class="col-md-4"><div class="p-2 bg_black" style="background:#0b0b0b;border-radius:8px;"><div class="mb-2"><img src="uploads/'+(item.poster || 'default_movie.jpg')+'" style="width:100%;height:120px;object-fit:cover;border-radius:6px;"></div><div class="fw-bold">'+item.title+'</div><div class="small text-white-50 mb-2">Release: '+item.release_date+'</div><div id="'+id+'" class="countdown">--:--:--</div></div></div>';
                });
                $('#upcomingList').html(html);
                // start countdowns
                resp.items.forEach(function(item){
                    startCountdown('countdown_'+item.id, item.release_date);
                });
            }
        });
    }
    function startCountdown(elid, targetDateStr) {
        var el = document.getElementById(elid);
        if (!el) return;
        var target = new Date(targetDateStr).getTime();
        function tick(){
            var now = new Date().getTime();
            var diff = target - now;
            if (diff <= 0) { el.innerText = 'Released!'; return; }
            var days = Math.floor(diff / (1000*60*60*24));
            var hours = Math.floor((diff % (1000*60*60*24)) / (1000*60*60));
            var mins = Math.floor((diff % (1000*60*60)) / (1000*60));
            var secs = Math.floor((diff % (1000*60)) / 1000);
            el.innerText = days+'d '+hours+'h '+mins+'m '+secs+'s';
            setTimeout(tick, 1000);
        }
        tick();
    }
    loadUpcoming();

});
</script>
</script>


</body>

</html>