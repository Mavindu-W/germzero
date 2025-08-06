<?php
session_start();
include 'backend/db.php';

// Only allow admin (adjust this condition if needed)
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

// Fetch all orders with user info
$sql = "SELECT o.*, u.full_name, u.email 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Orders - Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .order-card {
      border: 1px solid #dee2e6;
      border-radius: 10px;
      margin-bottom: 1.5rem;
    }
    .order-header {
      background-color: #f8f9fa;
      padding: 1rem;
      border-bottom: 1px solid #dee2e6;
    }
    .order-body {
      padding: 1rem;
    }
    .slip-preview {
      max-height: 120px;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="text-center mb-4">📦 All Received Orders</h2>
  <?php if ($result->num_rows > 0): ?>
    <?php while ($order = $result->fetch_assoc()): 
      $items = json_decode($order['items'], true); ?>
      <div class="order-card bg-white shadow-sm">
        <div class="order-header d-flex justify-content-between">
          <div><strong>Order ID:</strong> <?= $order['id'] ?></div>
          <div><strong>Date:</strong> <?= $order['date'] ?></div>
        </div>
        <div class="order-body">
          <p><strong>User:</strong> <?= htmlspecialchars($order['full_name']) ?> (<?= $order['email'] ?>)</p>
          <p><strong>Status:</strong> <span class="badge bg-<?=
            $order['status'] === 'Completed' ? 'success' : ($order['status'] === 'Cancelled' ? 'danger' : 'warning') ?>">
            <?= $order['status'] ?>
          </span></p>
          <ul>
            <?php foreach ($items as $item): ?>
              <li><?= $item['name'] ?> × <?= $item['qty'] ?> - LKR <?= $item['qty'] * $item['price'] ?></li>
            <?php endforeach; ?>
          </ul>
          <p><strong>Total:</strong> LKR <?= $order['total'] ?></p>
          <?php if ($order['payment_method'] === 'bank'): ?>
            <div class="mt-3">
              <strong>Bank Transfer Slip:</strong><br>
              <?php if (!empty($order['bank_slip'])): ?>
                <img src="uploads/slips/<?= $order['bank_slip'] ?>" class="img-fluid slip-preview">
              <?php else: ?>
                <span class="text-danger">❌ Not Uploaded</span>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <!-- Optional: Form to update status -->
          <form method="POST" action="update-order-status.php" class="mt-3">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <select name="status" class="form-select d-inline w-auto" required>
              <option value="">Change Status</option>
              <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
              <option value="Completed" <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
              <option value="Cancelled" <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-center text-muted">No orders found.</p>
  <?php endif; ?>
</div>
</body>
</html>
