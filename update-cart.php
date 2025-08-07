<?php
session_start();
include 'backend/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cartId = $_POST['cart_id'];
  $qty = (int) $_POST['qty'];

  if ($qty < 1) {
    header("Location: cart.php");
    exit();
  }

  // Get the unit price
  $stmt = $conn->prepare("SELECT price FROM cart WHERE id = ?");
  $stmt->bind_param("i", $cartId);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $unitPrice = $row['price'];
  $stmt->close();

  $totalItemPrice = $qty * $unitPrice;

  // Update quantity and total_itemprice
  $update = $conn->prepare("UPDATE cart SET qty = ?, total_itemprice = ? WHERE id = ?");
  $update->bind_param("idi", $qty, $totalItemPrice, $cartId);
  $update->execute();
  $update->close();

  header("Location: cart.php");
  exit();
}
?>
