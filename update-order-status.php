<?php
include 'backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
  $orderId = intval($_POST['order_id']);
  $status = $_POST['status'];

  $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
  $stmt->bind_param("si", $status, $orderId);
  $stmt->execute();
  $stmt->close();
}

header("Location: admin-orders.php");
exit();
