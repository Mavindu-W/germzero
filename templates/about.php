<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - Germ Zero</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

<!-- Hero Section -->
<section class="hero text-start" style="background: url('assets/images/hero2.png') no-repeat center center/cover; height: 60vh; display: flex; align-items: center; color: white;">
  <div class="container">
    <h1 class="fw-bold">About Germ Zero</h1>
    <p class="lead">Clean. Protect. Empower.</p>
  </div>
</section>

<!-- About Content -->
<main class="container py-5">
  <div class="row align-items-center mb-5">
    <div class="col-lg-6">
      <img src="assets/images/about-cleaning.png" class="img-fluid rounded" alt="Cleaning Products">
    </div>
    <div class="col-lg-6">
      <h3 class="fw-bold">Our Story</h3>
      <p>Germ Zero is a proudly Sri Lankan hygiene brand focused on delivering premium-quality cleaning products to homes and businesses nationwide. We started with a simple goal — to empower people with reliable, eco-conscious solutions that make cleanliness simple, safe, and accessible.</p>
    </div>
  </div>

  <div class="text-center mb-5">
    <h3 class="fw-bold">Our Core Values</h3>
  </div>
  <div class="row text-center">
    <div class="col-md-4 mb-4">
      <img src="assets/images/icon-quality.png" width="60">
      <h6 class="mt-3 fw-semibold">Quality First</h6>
      <p>We never compromise on the purity, effectiveness, and safety of our products.</p>
    </div>
    <div class="col-md-4 mb-4">
      <img src="assets/images/icon-sustainability.png" width="60">
      <h6 class="mt-3 fw-semibold">Eco-Friendly Mission</h6>
      <p>From production to packaging, we strive for sustainable, planet-conscious practices.</p>
    </div>
    <div class="col-md-4 mb-4">
      <img src="assets/images/icon-support.png" width="60">
      <h6 class="mt-3 fw-semibold">Customer-Centric</h6>
      <p>We listen, we care, and we build products around what people really need.</p>
    </div>
  </div>

  <div class="text-center mt-5">
    <h4 class="fw-bold">Thank you for supporting local clean-tech 💚</h4>
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
