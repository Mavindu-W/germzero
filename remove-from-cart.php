<?php
session_start();
include 'backend/db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: cart.php");
  exit();
}

$cartId = $_POST['cart_id'];
$stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $cartId, $_SESSION['user_id']);
$stmt->execute();

header("Location: cart.php");
exit();
