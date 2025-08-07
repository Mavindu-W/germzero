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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
