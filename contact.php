<?php
// contact.php
// include DB connection (use your db.php file)
include "db.php";

// Initialize messages
$contact_success = "";
$contact_error = "";
$news_success = "";
$news_error = "";

// -----------------------------
// CONTACT FORM BACKEND (secure prepared statements)
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {

    // Collect and sanitize (basic)
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if ($name === '' || $email === '' || $message === '') {
        $contact_error = "Please fill in Name, Email and Message.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contact_error = "Please enter a valid email address.";
    } else {
        // Prepared statement insert into contact_messages
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, phone, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("sssss", $name, $email, $subject, $phone, $message);
            if ($stmt->execute()) {
                $contact_success = "✅ Message sent successfully!";
            } else {
                $contact_error = "❌ Database error while saving message: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        } else {
            $contact_error = "❌ Database prepare error: " . htmlspecialchars($conn->error);
        }
    }
}

// -----------------------------
// NEWSLETTER BACKEND (secure prepared statements)
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_submit'])) {

    $newsletter_email = trim($_POST['newsletter_email'] ?? '');

    if ($newsletter_email === '') {
        $news_error = "Please enter your email.";
    } elseif (!filter_var($newsletter_email, FILTER_VALIDATE_EMAIL)) {
        $news_error = "Please enter a valid email address.";
    } else {
        // Check existing
        $check_stmt = $conn->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        if ($check_stmt) {
            $check_stmt->bind_param("s", $newsletter_email);
            $check_stmt->execute();
            $check_stmt->store_result();
            if ($check_stmt->num_rows > 0) {
                $news_error = "❌ Email already subscribed!";
            } else {
                // Insert
                $ins_stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email, subscribed_at) VALUES (?, NOW())");
                if ($ins_stmt) {
                    $ins_stmt->bind_param("s", $newsletter_email);
                    if ($ins_stmt->execute()) {
                        $news_success = "✅ You have successfully subscribed!";
                    } else {
                        $news_error = "❌ Database error while subscribing: " . htmlspecialchars($ins_stmt->error);
                    }
                    $ins_stmt->close();
                } else {
                    $news_error = "❌ Database prepare error: " . htmlspecialchars($conn->error);
                }
            }
            $check_stmt->close();
        } else {
            $news_error = "❌ Database prepare error: " . htmlspecialchars($conn->error);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Planet</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" >
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/global.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
	<script src="js/bootstrap.bundle.min.js"></script>

</head>
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
<body>

<section id="top">

  <nav class="navbar navbar-expand-lg navbar-dark bg-black py-3 sticky-top border-bottom border-danger">
    <div class="container">
      <a class="navbar-brand text-danger fw-bold fs-3" href="#"><img src="img/newlogo.png" style="width:100px;height:70px;"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto text-uppercase">
          <li class="nav-item"><a class="nav-link active" href="index2.php">Home</a></li>
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
 </div>
</div>
</section>



<section id="center" class="center_o pt-2 pb-2">
 <div class="container-xl">
  <div class="row center_o1">
   <div class="col-md-5">
     <div class="center_o1l">
	  <h2 class="mb-0">Contact</h2>
	 </div>
   </div>
   <div class="col-md-7">
     <div class="center_o1r text-end">
	  <h6 class="mb-0 col_red"><a href="#">Home</a> <span class="me-2 ms-2 text-light"><i class="fa fa-caret-right align-middle"></i></span> Contact</h6>
	 </div>
   </div>
  </div>
 </div>
</section>

<section id="contact" class="pt-4 pb-4 bg_grey">
 <div class="container-xl">
  <div class="row contact_1 bg_dark  pt-5 pb-5">
   <div class="col-md-3">
    <div class="contact_1i row">
	 <div class="col-md-2 col-2">
	  <div class="contact_1il">
	   <span class="col_red fs-3"><i class="fa fa-map-marker"></i></span>
	  </div>
	 </div>
	 <div class="col-md-10 col-10">
	  <div class="contact_1ir">
	   <h5 class="col_red">Company Address</h5>
	   <p class="mb-0">5311 Ceaver Sidge Td. Pakland, DE 13507</p>
	  </div>
	 </div>
	</div>
   </div>   
   <div class="col-md-3">
    <div class="contact_1i row">
	 <div class="col-md-2 col-2">
	  <div class="contact_1il">
	   <span class="col_red fs-3"><i class="fa fa-clock-o"></i></span>
	  </div>
	 </div>
	 <div class="col-md-10 col-10">
	  <div class="contact_1ir">
	   <h5 class="col_red">Office Hours</h5>
	   <p class="mb-0">Monday To Saturday - 10.00 - 07.00</p>
	   <p class="mb-0">Sunday - Closed</p>
	  </div>
	 </div>
	</div>
   </div>
   <div class="col-md-3">
    <div class="contact_1i row">
	 <div class="col-md-2 col-2">
	  <div class="contact_1il">
	   <span class="col_red fs-3"><i class="fa fa-envelope"></i></span>
	  </div>
	 </div>
	 <div class="col-md-10 col-10">
	  <div class="contact_1ir">
	   <h5 class="col_red">E-mail</h5>
	   <p class="mb-0">info@gmail.com</p>
	   <p class="mb-0">info@gmail.com</p>
	  </div>
	 </div>
	</div>
   </div>
   <div class="col-md-3">
    <div class="contact_1i row">
	 <div class="col-md-2 col-2">
	  <div class="contact_1il">
	   <span class="col_red fs-3"><i class="fa fa-phone"></i></span>
	  </div>
	 </div>
	 <div class="col-md-10 col-10">
	  <div class="contact_1ir">
	   <h5 class="col_red">Phone Numbers</h5>
	   <p class="mb-0">+123 123 456</p>
	   <p class="mb-0">+123 123 456</p>
	  </div>
	 </div>
	</div>
   </div>
  </div>

  <div class="row contact_2 mt-4">
   <div class="col-md-3">
    <div class="contact_2r">
	  <h5 class="mb-3">LONDON OFFICE</h5>
	  <p><i class="fa fa-car col_red me-1"></i> 111 Queen Sv, WIC 1, India</p>
	  <p><i class="fa fa-phone col_red me-1"></i> +123 123 456</p>
	   <p><i class="fa fa-globe col_red me-1"></i> <a href="#">info@gmail.com</a></p>
	    <p><i class="fa fa-envelope col_red me-1"></i> <a href="#">info@gmail.com</a></p>
		<h5 class="mb-3 mt-4">BUSINESS HOURS</h5>
		<p>Hotline is available for 24 hours a day!..</p>
		<p>Monday – Friday : <span class="fw-bold text-white">9am to 7pm</span></p>
		<p>Saturday : <span class="fw-bold text-white">11am to 3pm</span></p>
		<p>Sunday : <span class="fw-bold text-white">Closed</span></p>
	</div>
   </div>
   <div class="col-md-9">
    <div class="contact_2l row">
	 <div class="col-md-12">
	  <h4>GET IN TOUCH</h4>
	 </div> 
	</div>

	<!-- Display contact success/error messages here -->
	<?php if ($contact_success): ?>
	    <div class="alert alert-success mt-3"><?php echo $contact_success; ?></div>
	<?php endif; ?>
	<?php if ($contact_error): ?>
	    <div class="alert alert-danger mt-3"><?php echo $contact_error; ?></div>
	<?php endif; ?>

	<!-- Contact form (keeps the exact visual layout you had) -->
	<form method="POST" action="">
	<div class="contact_2l1 mt-3 row">
	 <div class="col-md-6">
	  <div class="contact_2l1i">
	    <input name="name" class="form-control" placeholder="Name*" type="text" required>
	  </div>
	 </div> 
	 <div class="col-md-6">
	  <div class="contact_2l1i">
	    <input name="email" class="form-control" placeholder="Email*" type="email" required>
	  </div>
	 </div>
	</div>

	<div class="contact_2l1 mt-3 row">
	 <div class="col-md-6">
	  <div class="contact_2l1i">
	    <!-- original second-row placeholder was Name*; to keep layout but be functional we use Subject* -->
	    <input name="subject" class="form-control" placeholder="Subject*" type="text">
	  </div>
	 </div> 
	 <div class="col-md-6">
	  <div class="contact_2l1i">
	    <!-- original second-row placeholder was Email*; we keep a phone field as extra (preserves two inputs) -->
	    <input name="phone" class="form-control" placeholder="Phone" type="text">
	  </div>
	 </div>
	</div>

	<div class="contact_2l1 mt-3 row">
	 <div class="col-md-12">
	  <div class="contact_2l1i">
	    <textarea name="message" placeholder="Comment" class="form-control form_text" required></textarea>
		<h6 class="mt-3 mb-0">
		  <!-- keep the same visual style for the submit as your original design -->
		  <button type="submit" name="contact_submit" class="button"> Submit</button>
		</h6>
	  </div>
	 </div> 
	</div>
	</form>

   </div>
  </div>

  <div class="row contact_3 mt-4">
   <div class="col-md-12">
     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114964.53925916665!2d-80.29949920266738!3d25.782390733064336!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d9b0a20ec8c111%3A0xff96f271ddad4f65!2sMiami%2C+FL%2C+USA!5e0!3m2!1sen!2sin!4v1530774403788" height="450" style="border:0; width:100%;" allowfullscreen=""></iframe>
   </div>
  </div>
 </div>
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
	<div class="col-md-4">
	 <div class="footer_1i">
	  <h4>Flickr <span class="col_red">Stream</span></h4>
      <div class="footer_1i1 row mt-4">
	   <div class="col-md-3 col-3">
	    <div class="footer_1i1i">
		  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="#"><img src="img/img1.jpg" height="70" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
		</div>
	   </div>
	   <div class="col-md-3 col-3">
	    <div class="footer_1i1i">
		  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="#"><img src="img/img2.jpg" height="70" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
		</div>
	   </div>
	   <div class="col-md-3 col-3">
	    <div class="footer_1i1i">
		  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="#"><img src="img/img3.jpg" height="70" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
		</div>
	   </div>
	   <div class="col-md-3 col-3">
	    <div class="footer_1i1i">
		  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="#"><img src="img/img4.jpg" height="70" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
		</div>
	   </div>
	  </div>
	  <div class="footer_1i1 row mt-3">
	   <div class="col-md-3 col-3">
	    <div class="footer_1i1i">
		  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="#"><img src="img/img5.jpg" height="70" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
		</div>
	   </div>
	   <div class="col-md-3 col-3">
	    <div class="footer_1i1i">
		  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="#"><img src="img/img6.jpg" height="70" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
		</div>
	   </div>
	   <div class="col-md-3 col-3">
	    <div class="footer_1i1i">
		  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="#"><img src="img/img7.jpg" height="70" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
		</div>
	   </div>
	   <div class="col-md-3 col-3">
	    <div class="footer_1i1i">
		  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="#"><img src="img/img8.jpg" height="70" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
		</div>
	   </div>
	  </div>
	 </div>
	</div>
	<div class="col-md-4">
	 <div class="footer_1i">
	  <h4>Sign  <span class="col_red">Newsletter</span></h4>
      <p class="mt-3">Subscribe to our newsletter list to get latest news and updates from us</p>

      <!-- Newsletter success / error messages -->
      <?php if ($news_success): ?>
          <div class="alert alert-success mt-2"><?php echo $news_success; ?></div>
      <?php endif; ?>
      <?php if ($news_error): ?>
          <div class="alert alert-danger mt-2"><?php echo $news_error; ?></div>
      <?php endif; ?>

	  <div class="input-group">
        <!-- Working newsletter form (submits to same page) -->
        <form method="POST" action="" class="d-flex w-100">
            <input type="email" name="newsletter_email" class="form-control bg-black" placeholder="Email" required>
            <button type="submit" name="newsletter_submit" class="btn btn text-white bg_red rounded-0 border-0">Subscribe</button>
        </form>
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
	   <p class="mb-0">© 2013 Your Website Name. All Rights Reserved | Design by <a class="col_red" href="http://www.templateonweb.com">TemplateOnWeb</a></p>
	  </div>
	 </div>
	 <div class="col-md-4">
	  <div class="footer_1r">
	   <ul class="mb-0">
	    <li class="d-inline-block me-2"><a href="#">Home</a></li>
		<li class="d-inline-block me-2"><a href="#">Features</a></li>
		<li class="d-inline-block me-2"><a href="#">Pages</a></li>
		<li class="d-inline-block me-2"><a href="#">Portfolio</a></li>
		<li class="d-inline-block me-2"><a href="#">Blog</a></li>
		<li class="d-inline-block"><a href="#">Contact</a></li>
	   </ul>
	  </div>
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

</body>

</html>