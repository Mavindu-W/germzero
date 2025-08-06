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
        <a class="btn btn-outline-dark me-2" href="login.php">Log In</a>
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
        <p>📍 12/A, Germanwatta, Papiliyawela.<br>✉ germzero0@gmail.com<br>📞 +94 (70) 446 8716</p>
      </div>
      <div class="col-lg-8 bg-light p-4 rounded-end" data-aos="fade-left">
        <form action="contact-submit.php" method="POST">
          <div class="row g-2">
            <div class="col-md-6">
              <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
            </div>
            <div class="col-md-6">
              <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
            </div>
            <div class="col-md-6">
              <input type="email" name="email" class="form-control" placeholder="Your Email" required>
            </div>
            <div class="col-md-6">
              <input type="text" name="phone" class="form-control" placeholder="Your Phone">
            </div>
            <div class="col-12">
              <textarea name="message" class="form-control" rows="3" placeholder="Your Message" required></textarea>
            </div>
          </div>
          <button type="submit" class="btn btn-success mt-3">SEND YOUR INQUIRY</button>
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

<!-- Chat Button -->
<div id="chat-icon" onclick="toggleChat()" style="position: fixed; bottom: 20px; right: 20px; background-color: #059669; color: white; border-radius: 50%; padding: 15px; cursor: pointer; z-index: 999;">
  💬
</div>

<!-- Chat Box -->
<div id="chat-box" style="display:none; position: fixed; bottom: 80px; right: 20px; width: 300px; background: white; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); z-index: 999;">
  <div style="padding: 10px; background: #059669; color: white; border-radius: 8px 8px 0 0;">Germ Zero ChatBot</div>
  <div id="chat-messages" style="padding: 10px; height: 200px; overflow-y: auto; font-size: 14px;"></div>
  <div style="display: flex; border-top: 1px solid #ccc;">
    <input type="text" id="chat-input" placeholder="Type a message..." style="flex: 1; border: none; padding: 10px;">
    <button onclick="sendMessage()" style="background: #059669; color: white; border: none; padding: 10px;">Send</button>
  </div>
</div>

<style>
#chat-icon {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #059669;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  cursor: pointer;
  z-index: 999;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  transition: transform 0.2s ease-in-out;
}

#chat-icon:hover {
  transform: scale(1.15); /* 👈 makes the button pop */
}
</style>


<script>
function toggleChat() {
  const box = document.getElementById('chat-box');
  box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

function sendMessage() {
  const input = document.getElementById('chat-input');
  const message = input.value.trim();
  if (!message) return;

  const chat = document.getElementById('chat-messages');
  // User message (right)
  chat.innerHTML += `
    <div style="text-align: right; margin-bottom: 8px;">
      <div style="display: inline-block; background-color: #DCF8C6; color: #333; padding: 8px 12px; border-radius: 16px 0 16px 16px; max-width: 80%;">
        ${message}
      </div>
    </div>
  `;
  input.value = '';

  // Rule-based reply or fetch from chatbot.php
  const msg = message.toLowerCase();
  let staticResponse = "";

  if (msg.includes("delivery")) {
    staticResponse = "We have two options. Bank Transfer and COD. And also we deliver island-wide in 3–5 days.";
  } else if (msg.includes("stock")) {
    staticResponse = "Most items are in stock. Check product page.";
  } else if (msg.includes("payment")) {
    staticResponse = "We have two payment methods for our valuble customers. Bank Slip Transfer or COD"; 
  } else if (msg.includes("hi") || msg.includes("hello")) {
    staticResponse = "Hello! How can I help you today?";
  } else if (msg.includes("order")) {
    staticResponse = "You can view your orders under 'My Orders' after login.";
  }

  if (staticResponse) {
    setTimeout(() => {
      // Bot message (left)
      chat.innerHTML += `
        <div style="text-align: left; margin-bottom: 8px;">
          <div style="display: inline-block; background-color: #E4E6EB; color: #000; padding: 8px 12px; border-radius: 0 16px 16px 16px; max-width: 80%;">
            ${staticResponse}
          </div>
        </div>
      `;
      chat.scrollTop = chat.scrollHeight;
    }, 400);
  } else {
    fetch('chatbot.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'message=' + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {
      chat.innerHTML += `
        <div style="text-align: left; margin-bottom: 8px;">
          <div style="display: inline-block; background-color: #E4E6EB; color: #000; padding: 8px 12px; border-radius: 0 16px 16px 16px; max-width: 80%;">
            ${data.response}
          </div>
        </div>
      `;
      chat.scrollTop = chat.scrollHeight;
    })
    .catch(error => {
      chat.innerHTML += `
        <div style="text-align: left; margin-bottom: 8px;">
          <div style="display: inline-block; background-color: #f8d7da; color: #721c24; padding: 8px 12px; border-radius: 0 16px 16px 16px;">
            ⚠️ Bot error. Please try again later.
          </div>
        </div>
      `;
    });
  }
}
</script>

</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>
