<?php
session_start();
include 'backend/db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: cart.php");
  exit();
}

$cartId = $_POST['cart_id'];
$qty = max(1, intval($_POST['qty']));

$stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("iii", $qty, $cartId, $_SESSION['user_id']);
$stmt->execute();

header("Location: cart.php");
exit();
