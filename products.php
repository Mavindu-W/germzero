<?php
session_start();
include 'backend/db.php';
$loggedIn = isset($_SESSION['user_id']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products - Germ Zero</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
// Fetch products from DB
$products = [];
$result = $conn->query("SELECT * FROM products");
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#"><img src="assets/images/logo1.png" width="50"> GER<span class="text-success">M ZERO</span></a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link active" href="products.php">Products</a></li>
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

<!-- Filters -->
<div class="container py-4">
  <div class="row mb-3">
    <div class="col-md-6 offset-md-3">
      <input type="text" id="searchInput" class="form-control" placeholder="Search products..." onkeyup="renderProducts()">
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="btn-group" role="group" id="categoryButtons">
        <button class="btn btn-outline-dark active" onclick="setCategory('All')">All</button>
        <button class="btn btn-outline-dark" onclick="setCategory('Toilet & Bathroom Care')">Toilet</button>
        <button class="btn btn-outline-dark" onclick="setCategory('Hand Hygiene Products')">Hand</button>
        <button class="btn btn-outline-dark" onclick="setCategory('Dishwashing Essentials')">Dish</button>
        <button class="btn btn-outline-dark" onclick="setCategory('Surface Cleaners')">Surface</button>
      </div>
    </div>
    <div class="col-md-6 text-end">
      <select id="sortSelect" class="form-select w-auto d-inline" onchange="renderProducts()">
        <option value="">Sort By</option>
        <option value="low">Price: Low to High</option>
        <option value="high">Price: High to Low</option>
      </select>
    </div>
  </div>

  <!-- Products -->
  <div class="row" id="product-list"></div>
</div>

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
  <div id="cartToast" class="toast align-items-center text-bg-success border-0" role="alert" data-bs-delay="2500">
    <div class="d-flex">
      <div class="toast-body">✅ Product added to cart!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="mt-5">
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
    staticResponse = "We deliver island-wide in 3–5 days.";
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


<script>
  const products = <?= json_encode($products) ?>;
  const isLoggedIn = <?= json_encode($loggedIn) ?>;
  let currentCategory = 'All';

  function setCategory(cat) {
    currentCategory = cat;
    document.querySelectorAll('#categoryButtons .btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    renderProducts();
  }

  function renderProducts() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const sort = document.getElementById('sortSelect').value;
    const list = document.getElementById('product-list');
    let filtered = products;

    if (currentCategory !== 'All') {
      filtered = filtered.filter(p => p.category === currentCategory);
    }

    if (search) {
      filtered = filtered.filter(p => p.name.toLowerCase().includes(search));
    }

    if (sort === 'low') filtered.sort((a, b) => a.price - b.price);
    if (sort === 'high') filtered.sort((a, b) => b.price - a.price);

    list.innerHTML = '';
    if (filtered.length === 0) {
      list.innerHTML = `<div class="text-center text-muted">No products found.</div>`;
      return;
    }

    filtered.forEach(p => {
      list.innerHTML += `
        <div class="col-md-3 mb-4">
          <div class="card h-100">
            <img src="assets/images/products/${p.image}" class="card-img-top" alt="${p.name}">
            <div class="card-body text-center">
              <h6 class="fw-bold mb-0">${p.name}</h6>
              <p class="text-muted small mb-1">${p.category}</p>
              <p class="mb-2">${p.description}</p>
              <p class="fw-semibold">LKR ${p.price}</p>
              ${isLoggedIn 
                ? `<button class="btn btn-success" onclick="addToCart(${p.id})">Add to Cart</button>`
                : `<a href='login.php' class='btn btn-outline-secondary'>Login to Order</a>`}
            </div>
          </div>
        </div>
      `;
    });
  }

 function addToCart(productId) {
  fetch("add-to-cart.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `product_id=${productId}&qty=1`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "success") {
      new bootstrap.Toast(document.getElementById('cartToast')).show();
    } else {
      alert("❌ " + data.message);
    }
  })
  .catch(err => {
    alert("❌ Failed to add to cart");
    console.error(err);
  });
}

  renderProducts();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
