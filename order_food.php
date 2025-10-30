<?php
session_start();
include 'db_connect.php'; // Your DB connection file

// Search feature
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$query = "SELECT * FROM foods";
if (!empty($search)) {
    $query .= " WHERE name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Food — FLICK-FIX</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/global.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
<script src="js/bootstrap.bundle.min.js"></script>
<style>
body {
  font-family:'Rajdhani',sans-serif;
  background:#030303;
  color:#fff;
}
.food-card {
  background:#1a1a1a;
  border-radius:10px;
  overflow:hidden;
  transition:transform .2s;
}
.food-card:hover {
  transform:scale(1.03);
}
.food-card img {
  width:100%;
  height:180px;
  object-fit:cover;
}
.food-card .info {
  padding:15px;
}
.search-bar input {
  background:#000;
  color:#fff;
  border:1px solid #ff2d2d;
}
.search-bar button {
  background:#ff2d2d;
  border:none;
  color:#fff;
}
.order-btn {
  background:#ff2d2d;
  color:#fff;
  border:none;
  padding:6px 12px;
  border-radius:5px;
  transition:0.2s;
}
.order-btn:hover {
  background:#ff4d4d;
}
</style>
</head>
<body>

<section class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="fa fa-cutlery col_red"></i> Order Food</h3>
    <form class="search-bar d-flex" method="GET" action="">
      <input type="text" name="search" class="form-control me-2" placeholder="Search for food..." value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit" class="btn">Search</button>
    </form>
  </div>

  <div class="row">
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($food = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-3 mb-4">
          <div class="food-card">
            <img src="uploads/<?php echo htmlspecialchars($food['image']); ?>" alt="Food">
            <div class="info">
              <h5 class="text-white"><?php echo htmlspecialchars($food['name']); ?></h5>
              <p class="text-white-50 small"><?php echo htmlspecialchars($food['description']); ?></p>
              <p class="fw-bold text-danger">$<?php echo htmlspecialchars($food['price']); ?></p>
              <form action="place_order.php" method="POST">
                <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                <input type="number" name="quantity" value="1" min="1" class="form-control mb-2 bg-black text-white">
                <button type="submit" class="order-btn w-100">Order Now</button>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="text-center mt-5">
        <p class="text-white-50">No food items found.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

</body>
</html>
