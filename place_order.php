<?php
session_start();
include 'backend/db.php'; // Update path if needed

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

// Sanitize & validate inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');
$payment = $_POST['payment'] ?? '';
$cart_json = $_POST['cart_data'] ?? '';

if (!$name || !$email || !$address || !$payment || !$cart_json) {
  echo "Missing fields";
  exit;
}

$user_id = $_SESSION['user_id'];
$cart_items = json_decode($cart_json, true);
if (!is_array($cart_items) || empty($cart_items)) {
  echo "Invalid cart";
  exit;
}

// Handle receipt image upload (optional)
$receipt_filename = null;
if ($payment === 'bank' && isset($_FILES['receipt']) && $_FILES['receipt']['error'] === 0) {
  $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
  $receipt_filename = uniqid("receipt_") . "." . $ext;
  $upload_path = "uploads/receipts/" . $receipt_filename;
  if (!move_uploaded_file($_FILES['receipt']['tmp_name'], $upload_path)) {
    echo "Receipt upload failed";
    exit;
  }
}

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
  $total += $item['price'] * $item['qty'];
}

// Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (user_id, full_name, email, address, payment_method, total, receipt, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())");
$stmt->bind_param("issssds", $user_id, $name, $email, $address, $payment, $total, $receipt_filename);
if (!$stmt->execute()) {
  echo "Order failed: " . $conn->error;
  exit;
}
$order_id = $stmt->insert_id;
$stmt->close();

// Insert order items
$item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
foreach ($cart_items as $item) {
  $item_stmt->bind_param("iisdi", $order_id, $item['id'], $item['name'], $item['price'], $item['qty']);
  $item_stmt->execute();
}
$item_stmt->close();

echo "success";
