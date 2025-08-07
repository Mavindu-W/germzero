<?php
session_start();
include 'backend/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$userId = $_SESSION['user_id'];
$loggedIn = true;

// Handle Cancel Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
  $cancelId = $_POST['cancel_order_id'];

  // Ensure order belongs to user and is still pending
  $check = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ? AND status = 'Pending'");
  $check->bind_param("ii", $cancelId, $userId);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ?");
    $stmt->bind_param("i", $cancelId);
    $stmt->execute();
    $stmt->close();
  }

  $check->close();
  header("Location: my-orders.php");
  exit();
}

// Fetch orders
$orders = [];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $orders[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders - Germ Zero</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

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
      </ul>
      <?php if ($loggedIn): ?>
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
      <?php endif; ?>
    </div>
  </div>
</nav>

<main>
<div class="container py-5">
  <h3 class="mb-4">🧾 My Orders</h3>

  <?php if (count($orders) === 0): ?>
    <div class="text-center text-muted">
      <h4>📦 No orders placed yet.</h4>
      <a href="products.php" class="btn btn-outline-success mt-3">Browse Products</a>
    </div>
  <?php else: ?>
    <?php foreach ($orders as $order): ?>
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong>Order ID: #<?= $order['id'] ?></strong>
          <span class="badge <?= getStatusBadge($order['status']) ?>"><?= $order['status'] ?></span>
        </div>
        <div class="card-body">
          <p><strong>Date:</strong> <?= date("Y-m-d H:i", strtotime($order['created_at'])) ?></p>
          <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
          <p><strong>Delivery Address:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
          <p><strong>Payment Method:</strong> <?= strtoupper($order['payment_method']) ?></p>

          <?php if ($order['slip_image']): ?>
            <p><strong>Bank Slip:</strong><br>
              <img src="assets/slips/<?= $order['slip_image'] ?>" class="img-fluid rounded" width="250">
            </p>
          <?php endif; ?>

          <h6 class="mt-3">Items Ordered:</h6>
          <ul>
            <?php
              $stmt = $conn->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
              $stmt->bind_param("i", $order['id']);
              $stmt->execute();
              $itemsResult = $stmt->get_result();
              while ($item = $itemsResult->fetch_assoc()):
            ?>
              <li><?= $item['name'] ?> × <?= $item['qty'] ?> = LKR <?= number_format($item['price'] * $item['qty'], 2) ?></li>
            <?php endwhile; $stmt->close(); ?>
          </ul>

          <p class="fw-bold mt-2">Total: LKR <?= number_format($order['total'], 2) ?></p>

          <?php if ($order['status'] === 'Pending'): ?>
            <form method="POST" class="mt-3" onsubmit="return confirm('Are you sure you want to cancel this order?');">
              <input type="hidden" name="cancel_order_id" value="<?= $order['id'] ?>">
              <button type="submit" class="btn btn-outline-danger btn-sm">Cancel Order</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</main>

<!-- Footer -->
<footer class="mt-5">
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

<?php
function getStatusBadge($status) {
  return match ($status) {
    'Pending' => 'bg-warning text-dark',
    'Dispatched' => 'bg-primary',
    'Out for Delivery' => 'bg-info text-dark',
    'Delivered' => 'bg-success',
    'Cancelled' => 'bg-danger',
    default => 'bg-secondary',
  };
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
