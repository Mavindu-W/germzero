<?php
session_start();
include 'backend/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders - Germ Zero</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php $loggedIn = true; ?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#"><img src="assets/images/logo1.png" width="40"> GER<span class="text-success">M ZERO</span></a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
        <li class="nav-item"><a class="nav-link active" href="my-orders.php">My Orders</a></li>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle custom-toggle" data-bs-toggle="dropdown">
            <img src="assets/images/avatar-default.jpg" alt="avatar" width="40" height="40" class="rounded-circle shadow-sm">
            <span class="ms-2 d-none d-md-inline text-dark fw-semibold"><?= $_SESSION['user_name'] ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> My Profile</a></li>
            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
          </ul>
        </div>
      </ul>
    </div>
  </div>
</nav>

<!-- Orders Section -->
<main>
<div class="container py-5">
  <h3 class="mb-4">My Orders</h3>
  <?php
  $orderQuery = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
  $orderQuery->bind_param("i", $userId);
  $orderQuery->execute();
  $orderResult = $orderQuery->get_result();

  if ($orderResult->num_rows === 0) {
    echo '<div class="text-center text-muted"><h4>📦 No orders placed yet.</h4><a href="products.php" class="btn btn-outline-success mt-3">Browse Products</a></div>';
  } else {
    while ($order = $orderResult->fetch_assoc()) {
      echo '<div class="card mb-4">';
      echo '<div class="card-header d-flex justify-content-between">';
      echo '<span><strong>Order ID:</strong> ' . $order['id'] . '</span>';
      echo '<span><span class="badge ' . getStatusBadge($order['status']) . '">' . ucfirst($order['status']) . '</span></span>';
      echo '</div>';
      echo '<div class="card-body">';
      echo '<p><strong>Date:</strong> ' . $order['created_at'] . '</p>';
      echo '<div class="table-responsive">';
echo '<table class="table table-bordered table-sm">';
echo '<thead class="table-light"><tr><th>Product</th><th>Unit Price</th><th>Qty</th><th>Total</th></tr></thead><tbody>';

$itemsQuery = $conn->prepare("SELECT order_items.*, products.name FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_items.order_id = ?");
$itemsQuery->bind_param("i", $order['id']);
$itemsQuery->execute();
$itemsResult = $itemsQuery->get_result();

while ($item = $itemsResult->fetch_assoc()) {
  $lineTotal = $item['qty'] * $item['price'];
  echo "<tr>
          <td>{$item['name']}</td>
          <td>LKR " . number_format($item['price'], 2) . "</td>
          <td>{$item['qty']}</td>
          <td>LKR " . number_format($lineTotal, 2) . "</td>
        </tr>";
}
echo '</tbody></table>';
echo '</div>';

      echo '<p class="fw-bold">Total: LKR ' . number_format($order['total'], 2) . '</p>';
      if ($order['slip_image']) {
        echo '<p><strong>Bank Slip:</strong><br><img src="assets/slips/' . $order['slip_image'] . '" width="150" class="rounded border mt-2"></p>';
      }
      echo '</div></div>';
    }
  }

  function getStatusBadge($status) {
    switch (strtolower($status)) {
      case "pending": return "bg-warning text-dark";
      case "completed": return "bg-success";
      case "cancelled": return "bg-danger";
      default: return "bg-secondary";
    }
  }
  ?>
</div>
</main>

<!-- Footer -->
<footer>
  <div class="container d-md-flex justify-content-between text-center text-md-start">
    <div>
      <img src="assets/images/logo1.png" width="80">
      <p class="mb-0 mt-2">We are a small scale hygiene and cleaning products selling company.</p>
    </div>
    <div>
      <p><a href="#">Privacy Policy</a><br><a href="#">Terms & Conditions</a></p>
    </div>
    <div>
      <p>456 Design Avenue, Denver, CO 80203<br>(720) 555-4321<br>contact@germzero.com</p>
    </div>
  </div>
  <div class="text-center mt-3">&copy; 2025 Germ Zero, All Rights Reserved</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
