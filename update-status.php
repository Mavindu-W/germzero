<?php
session_start();
include 'backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $orderId = $_POST['order_id'];
  $newStatus = $_POST['status'];

  $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
  $stmt->bind_param("si", $newStatus, $orderId);
  $stmt->execute();
  $stmt->close();

  $hist = $conn->prepare("INSERT INTO order_status_history (order_id, status) VALUES (?, ?)");
  $hist->bind_param("is", $orderId, $newStatus);
  $hist->execute();
  $hist->close();

  header("Location: admin-orders.php");
  exit();
}
