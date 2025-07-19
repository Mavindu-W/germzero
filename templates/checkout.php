<?php
session_start();
include 'backend/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$loggedIn = true;
$confirmation = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user_id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $payment = $_POST['payment'];
  $total = $_POST['total'];
  $slipImage = null;

  if ($payment === 'bank' && isset($_FILES['receipt']) && $_FILES['receipt']['error'] === 0) {
    $uploadDir = 'assets/slips/';
    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
    $slipImage = uniqid() . "_" . basename($_FILES['receipt']['name']);
    move_uploaded_file($_FILES['receipt']['tmp_name'], $uploadDir . $slipImage);
  }

  $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, address, payment_method, slip_image, total) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("isssssd", $userId, $name, $email, $address, $payment, $slipImage, $total);
  $stmt->execute();
  $orderId = $stmt->insert_id;
  $stmt->close();

  $items = json_decode($_POST['items'], true);
  if (is_array($items)) {
    foreach ($items as $item) {
      $productId = $item['id'];
      $qty = $item['qty'];
      $price = $item['price'];
      $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("iiid", $orderId, $productId, $qty, $price);
      $stmt->execute();
      $stmt->close();
    }
  }

  // Clear user's cart table
  $clearStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
  $clearStmt->bind_param("i", $userId);
  $clearStmt->execute();
  $clearStmt->close();

  $confirmation = "✅ Your order has been submitted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Germ Zero</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#"><img src="assets/images/logo1.png" width="50"> GER<span class="text-success">M ZERO</span></a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
        <li class="nav-item"><a class="nav-link active" href="checkout.php">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="my-orders.php">My Orders</a></li>
      </ul>
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
          <img src="assets/images/avatar-default.jpg" alt="avatar" width="40" height="40" class="rounded-circle shadow-sm">
          <span class="ms-2 d-none d-md-inline text-dark fw-semibold"><?= $_SESSION['user_name'] ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> My Profile</a></li>
          <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<main>
<div class="container py-5">
  <?php if ($confirmation): ?>
    <div class="alert alert-success"><?= $confirmation ?></div>
  <?php endif; ?>
  <form method="POST" enctype="multipart/form-data" id="checkoutForm">
    <input type="hidden" name="total" id="totalInput">
    <input type="hidden" name="items" id="itemsInput">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="col-12">
        <label class="form-label">Delivery Address</label>
        <textarea name="address" class="form-control" rows="3" required></textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Payment Method</label>
        <select name="payment" id="payment" class="form-select" onchange="toggleReceipt()" required>
          <option value="">Select a method</option>
          <option value="cod">Cash on Delivery</option>
          <option value="bank">Bank Deposit</option>
        </select>
      </div>
      <div class="col-md-6" id="receiptUpload" style="display:none;">
        <label class="form-label">Upload Bank Transfer Receipt</label>
        <input type="file" name="receipt" class="form-control" accept="image/*">
        <div class="border p-3 rounded bg-light mt-2">
          <strong>Bank Details</strong><br>
          <strong>Acc:</strong> 0142584645101235<br>
          <strong>Name:</strong> Germ Zero Pvt<br>
          <strong>Bank:</strong> Sampath Bank<br>
          <strong>Branch:</strong> Nawinna
        </div>
      </div>
    </div>
    <div class="text-end mt-4">
      <button type="submit" class="btn btn-success">Place Order</button>
    </div>
  </form>
</div>
</main>

<!-- Footer -->
<footer>
  <div class="container d-md-flex justify-content-between text-center text-md-start">
    <div><img src="assets/images/logo1.png" width="80"><p class="mt-2 mb-0">We are a small scale hygiene and cleaning products selling company.</p></div>
    <div><p><a href="#">Privacy Policy</a><br><a href="#">Terms & Conditions</a></p></div>
    <div><p>456 Design Avenue, Denver, CO 80203<br>(720) 555-4321<br>contact@germzero.com</p></div>
  </div>
  <div class="text-center mt-3">&copy; 2025 Germ Zero, All Rights Reserved</div>
</footer>

<script>
function toggleReceipt() {
  document.getElementById("receiptUpload").style.display =
    document.getElementById("payment").value === "bank" ? "block" : "none";
}

document.getElementById('checkoutForm').addEventListener('submit', function (e) {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  if (cart.length === 0) {
    e.preventDefault();
    alert("❌ Your cart is empty.");
    return;
  }
  document.getElementById('itemsInput').value = JSON.stringify(cart.map(item => ({
    id: item.id, qty: item.qty, price: item.price
  })));
  document.getElementById('totalInput').value = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  localStorage.removeItem("cart"); // ✅ Clear after submitting
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
