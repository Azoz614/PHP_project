<?php
session_start();
require_once "db.php";

// --- Ensure session user exists ---
$user_profile_pic = $_SESSION['user']['profile_pic'] ?? 'default.png';
$user_first_name = $_SESSION['user']['first_name'] ?? 'Guest';
$user_last_name  = $_SESSION['user']['last_name'] ?? '';
$user_email      = $_SESSION['user']['email'] ?? '';
$user_id         = $_SESSION['user']['id'] ?? 0;

// --- Handle Order Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_data'])) {
    $order_items = json_decode($_POST['order_data'], true);
    if (!empty($order_items)) {
        foreach ($order_items as $item) {
            $food_id = intval($item['id']);
            $qty     = intval($item['qty'] ?? 1);
            $price   = floatval($item['price']);
            $total   = $price * $qty;

            $stmt = $conn->prepare("INSERT INTO orders (user_id, food_id, quantity, total_price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $user_id, $food_id, $qty, $total);
            $stmt->execute();
        }
        echo "<script>alert('✅ Order confirmed successfully!');</script>";
    } else {
        echo "<script>alert('Cart is empty!');</script>";
    }
}

// --- Fetch Categories ---
$categories = [];
$cat_result = $conn->query("SELECT DISTINCT category FROM foods");
while ($row = $cat_result->fetch_assoc()) {
    $categories[] = $row['category'];
}

// --- Fetch Foods ---
$selected_category = $_GET['category'] ?? '';
if ($selected_category != '') {
    $stmt = $conn->prepare("SELECT * FROM foods WHERE category = ?");
    $stmt->bind_param("s", $selected_category);
    $stmt->execute();
    $foods = $stmt->get_result();
} else {
    $foods = $conn->query("SELECT * FROM foods");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QuickBite | Order Foods</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body { font-family:'Rajdhani',sans-serif; background:#030303; color:#fff; margin:0; padding:0; }
#top { background:#0f0f10; padding:10px 0; }
#top .nav-profile img { width:40px; height:40px; border-radius:50%; object-fit:cover; }
#top .dropdown-menu { background:#1a1a1a; }

.slider-section { padding:40px 0; }
.slider-section h1 { color:#fff; text-align:center; margin-bottom:20px; }
.slider-container { position: relative; max-width:1100px; margin:auto; overflow:hidden; }
.slider { display:flex; gap:10px; scroll-behavior:smooth; overflow-x:auto; padding-bottom:10px; }
.slide { flex: 0 0 220px; background:#1a1a1a; border-radius:10px; text-align:center; padding:15px; scroll-snap-align:start; transition:transform 0.3s; }
.slide:hover { transform:scale(1.05); }
.slide img { width:100%; height:150px; object-fit:cover; border-radius:8px; }
.slide h3 { margin:10px 0 5px; }
.slide p { color:#ccc; }
.slide button { background:#ff4757; color:#fff; border:none; border-radius:5px; padding:8px 12px; cursor:pointer; margin-top:5px; }
.slide button:hover { background:#e84118; }

.arrow {
  position:absolute;
  top:50%;
  transform:translateY(-50%);
  background:#ffb300;
  color:#000;
  font-size:22px;
  border:none;
  padding:10px 14px;
  border-radius:50%;
  cursor:pointer;
  transition:all 0.3s ease;
  box-shadow:0 0 10px rgba(255,179,0,0.4);
  z-index:10;
  opacity:0;
}
.arrow:hover {
  background:#ff9900;
  box-shadow:0 0 15px rgba(255,153,0,0.7);
  transform:translateY(-50%) scale(1.1);
}
.slider-container:hover .arrow {
  opacity:1;
}
.arrow.left { left:-25px; }
.arrow.right { right:-25px; }

.cart-container { margin:40px auto; background:#1a1a1a; padding:20px; border-radius:10px; max-width:500px; }
.cart-container h2 { margin-bottom:15px; text-align:center; }
.cart-actions { display:flex; justify-content:space-between; margin-top:15px; }
.cart-actions button { width:48%; padding:10px; border:none; border-radius:5px; cursor:pointer; font-weight:bold; }
.confirm { background:#28a745; color:#fff; }
.drop { background:#dc3545; color:#fff; }
.category-select { text-align:center; margin-bottom:20px; }
select, input.form-control { background:#1a1a1a; color:#fff; border-radius:4px; border:1px solid #333; }
button, .btn { border-radius:4px; }
</style>
</head>
<body>

<!-- ======= TOP HEADER ======= -->
<section id="top">
<div class="container">
 <div class="row align-items-center">
  <div class="col-md-3">
   <a href="index2.php"><img src="img/newlogo.png" style="width:100px;height:70px;"></a>
  </div>
  <div class="col-md-5">
   <div class="input-group"></div>
   <div id="searchResults" class="mt-2"></div>
  </div>
  <div class="col-md-4 text-end d-flex justify-content-end align-items-center">
    <div class="nav-profile dropdown">
     <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
        id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="uploads/<?= htmlspecialchars($user_profile_pic) ?>" alt="Profile" class="me-2">
        <span><?= htmlspecialchars($user_first_name) ?></span>
     </a>
      <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="profileDropdown">
        <li class="text-center mb-2">
          <img src="uploads/<?= htmlspecialchars($user_profile_pic) ?>" alt="Profile" class="rounded-circle mb-2" style="width:70px;height:70px; object-fit:cover; border:2px solid #ff2d2d;">
          <div class="fw-bold text-white"><?= htmlspecialchars($user_first_name . ' ' . $user_last_name) ?></div>
          <div class="small text-white-50"><?= htmlspecialchars($user_email) ?></div>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-white" href="update_profile.php"><i class="fa fa-user me-2"></i> Update Profile</a></li>
        <li><a class="dropdown-item text-white" href="change_password.php"><i class="fa fa-lock me-2"></i> Change Password</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-white" href="logout.php"><i class="fa fa-sign-out me-2"></i> Logout</a></li>
      </ul>
    </div>
  </div>
 </div>
</div>
</section>

<!-- ======= FOOD ORDER SECTION ======= -->
<section class="slider-section">
<h1>🍽 Order Your Favorite Foods</h1>

<div class="category-select">
<form method="GET">
    <select name="category" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <?php foreach($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= ($selected_category==$cat)?'selected':'' ?>><?= htmlspecialchars($cat) ?></option>
        <?php endforeach; ?>
    </select>
</form>
</div>

<div class="slider-container">
  <button class="arrow left"><i class="fa-solid fa-chevron-left"></i></button>
  <div class="slider" id="slider">
    <?php if($foods->num_rows>0): ?>
        <?php while($food=$foods->fetch_assoc()): ?>
            <?php $imgPath = preg_match('/^(http|https|\/)/',$food['image']) ? $food['image'] : 'uploads/'.basename($food['image']); ?>
            <div class="slide">
                <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($food['name']) ?>">
                <h3><?= htmlspecialchars($food['name']) ?></h3>
                <p>Rs.<?= number_format($food['price'],2) ?></p>
                <button onclick="addToCart(<?= $food['id'] ?>,'<?= addslashes($food['name']) ?>',<?= $food['price'] ?>)">+</button>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No foods available.</p>
    <?php endif; ?>
  </div>
  <button class="arrow right"><i class="fa-solid fa-chevron-right"></i></button>
</div>

<div class="cart-container">
<h2>🛒 Your Order</h2>
<div id="cart"></div>
<div class="cart-actions">
<button class="confirm" onclick="confirmOrder()">✅ Confirm Order</button>
<button class="drop" onclick="dropOrder()">🗑 Drop Order</button>
</div>
</div>

<form id="orderForm" method="POST" style="display:none;">
<input type="hidden" name="order_data" id="order_data">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let slider = document.getElementById("slider");
const slideWidth = document.querySelector('.slide').offsetWidth + 10;

document.querySelector(".arrow.left").addEventListener("click", ()=> slider.scrollBy({left:-slideWidth*4, behavior:'smooth'}));
document.querySelector(".arrow.right").addEventListener("click", ()=> slider.scrollBy({left:slideWidth*4, behavior:'smooth'}));

// --- Cart functionality ---
let cart = [];
function addToCart(id,name,price){
  const item = cart.find(i=>i.id===id);
  if(item) item.qty+=1;
  else cart.push({id,name,price,qty:1});
  updateCart();
}
function updateCart(){
  let cartDiv=document.getElementById("cart");
  cartDiv.innerHTML='';
  if(cart.length===0){cartDiv.innerHTML="<p>No items added yet.</p>"; return;}
  let total=0;
  cart.forEach(item=>{
    total+=item.price*item.qty;
    cartDiv.innerHTML+=`<p>${item.name} x ${item.qty} — Rs.${item.price*item.qty}</p>`;
  });
  cartDiv.innerHTML+=`<hr><strong>Total: Rs.${total}</strong>`;
}
function confirmOrder(){
  if(cart.length===0){alert("Cart is empty!"); return;}
  document.getElementById("order_data").value=JSON.stringify(cart);
  document.getElementById("orderForm").submit();
}
function dropOrder(){
  if(confirm("Clear your current order?")){cart=[]; updateCart();}
}
</script>
</body>
</html>
