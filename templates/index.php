<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Germ Zero - Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .hero {
      background: url('assets/images/her1.png') no-repeat center center/cover;
      color: white;
      height: 100vh;
      display: flex;
      align-items: center;
    }
    .hero h1 {
      font-size: 3rem;
      font-weight: bold;
    }
    .section-light {
      background-color: #f8f9fa;
    }
    .feature-image {
      border-radius: 20px;
    }
    .testimonial-card {
      background: white;
      border-radius: 10px;
      padding: 1rem;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    footer {
      background: #dbe7e2;
      padding: 2rem 0;
    }
    .custom-toggle::after {
      border-top-color: black !important;
    }
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
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
        <?php if ($loggedIn): ?>
          <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
          <li class="nav-item"><a class="nav-link" href="my-orders.php">My Orders</a></li>
        <?php endif; ?>
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
      <?php else: ?>
        <a class="btn btn-outline-dark me-2" href="login.php">Sign In</a>
        <a class="btn btn-success" href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero text-start" data-aos="fade-in" data-aos-duration="1000">
  <div class="container">
    <h1>Achieve<br>Immaculate Cleanliness</h1>
    <p class="lead">Transform your space with Germ Zero's premium cleaning products.</p>
    <a href="products.php" class="btn btn-success">Discover More</a>
  </div>
</section>

<!-- Feature -->
<section class="py-5" data-aos="fade-up" data-aos-duration="1000">
  <div class="container d-lg-flex align-items-center">
    <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
      <img src="assets/images/cleaningperson.png" class="img-fluid feature-image mb-3 mb-lg-0" alt="">
    </div>
    <div class="col-lg-6 ps-lg-5" data-aos="fade-left" data-aos-delay="200">
      <h2 class="fw-bold">Elevate Your Hygiene Routine</h2>
      <p>Discover exceptional hygiene and cleaning products crafted to meet your specific needs.</p>
      <ul>
        <li>Exceptional cleaning products that reflect your standards</li>
        <li>High-quality ingredients for effective hygiene</li>
        <li>Thoughtful formulations designed for ease of use</li>
        <li>Personalized support to guide you in choosing the right products</li>
      </ul>
      <a href="products.php" class="btn btn-success mt-2">Explore Our Products</a>
    </div>
  </div>
</section>

<!-- Product Showcase -->
<section class="py-5 section-light" data-aos="fade-up">
  <div class="container text-center">
    <h2 class="fw-bold mb-4">Our Premium Product Showcase</h2>
    <div class="row g-4">
      <div class="col-md-3" data-aos="zoom-in">
        <img src="assets/images/p1.png" class="img-fluid rounded mb-2">
        <h6 class="fw-bold text-success">Elegance in Hygiene</h6>
        <p class="text-muted">Experience a stunning transformation with our premium range.</p>
      </div>
      <div class="col-md-3" data-aos="zoom-in" data-aos-delay="100">
        <img src="assets/images/p2.png" class="img-fluid rounded mb-2">
        <h6 class="fw-bold text-success">A Symphony of Freshness</h6>
        <p class="text-muted">Exquisite solutions and fresh detailing for your home.</p>
      </div>
      <div class="col-md-3" data-aos="zoom-in" data-aos-delay="200">
        <img src="assets/images/p3.png" class="img-fluid rounded mb-2">
        <h6 class="fw-bold text-success">Efficiency Meets Convenience</h6>
        <p class="text-muted">Blending effectiveness and ease-of-use seamlessly.</p>
      </div>
      <div class="col-md-3" data-aos="zoom-in" data-aos-delay="300">
        <img src="assets/images/p4.png" class="img-fluid rounded mb-2">
        <h6 class="fw-bold text-success">Quality Craftsmanship</h6>
        <p class="text-muted">Exceptional quality and durability you can trust.</p>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials & Contact -->
<section class="py-5" data-aos="fade-up">
  <div class="container">
    <h2 class="fw-bold text-center mb-4">Our Happy Clients</h2>
    <div class="row mb-5 text-center">
      <div class="col-md-4" data-aos="flip-left">
        <div class="testimonial-card">
          <p>“Germ Zero gave my home the cleanliness it needed.”</p>
          <strong>Kemani Senevirathne ⭐⭐⭐⭐⭐</strong>
        </div>
      </div>
      <div class="col-md-4" data-aos="flip-up" data-aos-delay="100">
        <div class="testimonial-card">
          <p>“Their team turned my concerns into a pristine space.”</p>
          <strong>Silva Peries ⭐⭐⭐⭐⭐</strong>
        </div>
      </div>
      <div class="col-md-4" data-aos="flip-right" data-aos-delay="200">
        <div class="testimonial-card">
          <p>“Exceeded expectations in every way.”</p>
          <strong>Sarah Connor ⭐⭐⭐⭐⭐</strong>
        </div>
      </div>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-4 text-white bg-success p-4 rounded-start" data-aos="fade-right">
        <h5>GET IN TOUCH</h5>
        <h3>Your Satisfaction is Our Priority</h3>
        <p>📍 2/A, Germanwatta, Papiliyawela.<br>✉ info@germzero.com<br>📞 +94 704468716</p>
      </div>
      <div class="col-lg-8 bg-light p-4 rounded-end" data-aos="fade-left">
        <form>
          <div class="row g-2">
            <div class="col-md-6"><input type="text" class="form-control" placeholder="First Name"></div>
            <div class="col-md-6"><input type="text" class="form-control" placeholder="Last Name"></div>
            <div class="col-md-6"><input type="email" class="form-control" placeholder="Your Email"></div>
            <div class="col-md-6"><input type="text" class="form-control" placeholder="Your Phone"></div>
            <div class="col-12"><textarea class="form-control" rows="3" placeholder="Your Message"></textarea></div>
          </div>
          <button class="btn btn-success mt-3">SEND YOUR INQUIRY</button>
        </form>
      </div>
    </div>
  </div>
</section>

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

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>
