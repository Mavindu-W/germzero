<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Get current data
$stmt = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($full_name, $email);
$stmt->fetch();
$stmt->close();

// Update name/email
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profile'])) {
  $new_name = $_POST['full_name'];
  $new_email = $_POST['email'];

  $update = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
  $update->bind_param("ssi", $new_name, $new_email, $user_id);
  if ($update->execute()) {
    $_SESSION['user_name'] = $new_name;
    $message = "✅ Profile updated successfully!";
  } else {
    $message = "❌ Failed to update profile.";
  }
  $update->close();
}

// Update password
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['change_password'])) {
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  if ($new_password === $confirm_password) {
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $pwd_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $pwd_stmt->bind_param("si", $hashed, $user_id);
    if ($pwd_stmt->execute()) {
      $message = "✅ Password changed successfully!";
    } else {
      $message = "❌ Failed to change password.";
    }
    $pwd_stmt->close();
  } else {
    $message = "❌ Passwords do not match.";
  }
}
$loggedIn = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile - Germ Zero</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .custom-toggle::after { border-top-color: black !important; }
  </style>
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
        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="my-orders.php">My Orders</a></li>
      </ul>
      <!-- Avatar Dropdown -->
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
    </div>
  </div>
</nav>

<main>
  <div class="container py-5">
    <h3 class="mb-4">👤 My Profile</h3>
    <?php if ($message): ?>
      <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <!-- Update Info -->
    <div class="card p-4 mb-4">
      <h5>Update Name & Email</h5>
      <form method="POST">
        <input type="hidden" name="update_profile" value="1">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($full_name) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <button class="btn btn-success">Save Changes</button>
      </form>
    </div>

    <!-- Change Password -->
    <div class="card p-4">
      <h5>Change Password</h5>
      <form method="POST">
        <input type="hidden" name="change_password" value="1">
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button class="btn btn-warning">Update Password</button>
      </form>
    </div>
  </div>
</main>

<!-- Footer -->
<footer>
  <div class="container d-md-flex justify-content-between text-center text-md-start">
    <div>
      <img src="assets/images/logo1.png" width="80">
      <p class="mb-0 mt-2">We are a hygiene and cleaning products selling company.</p>
    </div>
    <div>
      <p><a href="#">Privacy Policy</a><br><a href="#">Terms & Conditions</a></p>
    </div>
    <div>
      <p>📍 12/A, Germanwatta, Papiliyawela.<br>📞 +94 (70) 446 8716<br>✉ germzero0@gmail.com</p>
    </div>
  </div>
  <div class="text-center mt-3">&copy; 2025 Germ Zero, All Rights Reserved</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
