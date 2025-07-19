<?php
session_start();
include 'backend/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$userId = $_SESSION['user_id'];
$loggedIn = true;

// Fetch cart items
$cartItems = [];
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $cartItems[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart - Germ Zero</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#"><img src="assets/images/logo1.png" width="50"> GER<span class="text-success">M ZERO</span></a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="about.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="my-orders.php">My Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
      </ul>
      <?php if ($loggedIn): ?>
         <!-- Avatar Dropdown (Option 3) -->
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
      <?php else: ?>
        <a class="btn btn-outline-dark me-2" href="login.php">Sign In</a>
        <a class="btn btn-success" href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Cart Section -->
<main class="container py-5">
  <?php if (count($cartItems) > 0): ?>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Product</th>
          <th>Price (LKR)</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $grandTotal = 0; ?>
        <?php foreach ($cartItems as $item): ?>
          <?php $itemTotal = $item['price'] * $item['qty']; ?>
          <?php $grandTotal += $itemTotal; ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price'], 2) ?></td>
            <td>
              <form action="update-cart.php" method="POST" class="d-flex">
                <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                <input type="number" name="qty" min="1" class="form-control form-control-sm" value="<?= $item['qty'] ?>" onchange="this.form.submit()">
              </form>
            </td>
            <td>LKR <?= number_format($itemTotal, 2) ?></td>
            <td>
              <form action="remove-from-cart.php" method="POST" onsubmit="return confirm('Remove this item?');">
                <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="text-end">
      <h5>Total: LKR <?= number_format($grandTotal, 2) ?></h5>
      <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
    </div>
  <?php else: ?>
    <div class="text-center text-muted">
      <h4>🛒 Your cart is empty.</h4>
      <a href="products.php" class="btn btn-outline-success mt-3">Browse Products</a>
    </div>
  <?php endif; ?>
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
