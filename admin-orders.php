<?php
session_start();
include 'backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
  $orderId = $_POST['order_id'];
  $newStatus = $_POST['status'];
  $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
  $stmt->bind_param("si", $newStatus, $orderId);
  $stmt->execute();
  $stmt->close();
  header("Location: admin-orders.php");
  exit();
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $deleteId = $_GET['delete'];
  $conn->query("DELETE FROM orders WHERE id = $deleteId AND status = 'Cancelled'");
  header("Location: admin-orders.php");
  exit();
}

$filterStatus = $_GET['status'] ?? '';
$query = "SELECT * FROM orders";
if ($filterStatus && in_array($filterStatus, ['Pending', 'Dispatched', 'Out for Delivery', 'Delivered', 'Cancelled'])) {
  $query .= " WHERE status = '$filterStatus'";
}
$query .= " ORDER BY created_at DESC";
$result = $conn->query($query);
$orders = [];
while ($row = $result->fetch_assoc()) $orders[] = $row;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Orders - Germ Zero</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f7f9fc;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.08);
      transition: transform 0.2s ease;
    }
    .card:hover {
      transform: translateY(-3px);
    }
    .badge-status {
      font-size: 0.85rem;
      padding: 0.5em 0.75em;
    }
    .card-header {
      background: #f1f5f9;
      border-bottom: 1px solid #dee2e6;
    }
    .form-select-sm, .btn-sm {
      font-size: 0.85rem;
    }
    h2, h6 {
      font-weight: 600;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary">🛒 Manage Orders</h2>
    <a href="admin-dashboard.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
  </div>

  <div class="mb-4 d-flex justify-content-between align-items-center">
    <form method="GET" class="d-flex align-items-center gap-2">
      <label class="me-2 fw-semibold">Filter by Status:</label>
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">All</option>
        <?php foreach (["Pending", "Dispatched", "Out for Delivery", "Delivered", "Cancelled"] as $s): ?>
          <option value="<?= $s ?>" <?= $filterStatus === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>

  <?php if (count($orders) === 0): ?>
    <div class="alert alert-warning text-center">No orders found for this status.</div>
  <?php else: ?>
    <?php foreach ($orders as $order): ?>
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong>Order #<?= $order['id'] ?> - <?= htmlspecialchars($order['name']) ?> (<?= $order['email'] ?>)</strong>
          <div class="d-flex gap-2 align-items-center">
            <form method="POST" class="d-flex gap-2">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
              <select name="status" class="form-select form-select-sm">
                <?php foreach (["Pending", "Dispatched", "Out for Delivery", "Delivered", "Cancelled"] as $status): ?>
                  <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                <?php endforeach; ?>
              </select>
              <button type="submit" class="btn btn-sm btn-primary">Update</button>
            </form>
            <?php if ($order['status'] === 'Cancelled'): ?>
              <a href="?delete=<?= $order['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this cancelled order?')">Delete</a>
            <?php endif; ?>
          </div>
        </div>
        <div class="card-body">
          <p><strong>Date:</strong> <?= date("Y-m-d H:i", strtotime($order['created_at'])) ?></p>
          <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone'] ?? '-') ?></p>
          <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
          <p><strong>Payment:</strong> <?= strtoupper($order['payment_method']) ?></p>

          <?php if ($order['slip_image']): ?>
            <p><strong>Bank Slip:</strong><br>
              <img src="assets/slips/<?= $order['slip_image'] ?>" width="200" class="img-fluid rounded border">
            </p>
          <?php endif; ?>

          <h6 class="mt-4">🧾 Ordered Items:</h6>
          <ul class="mb-2">
            <?php
              $stmt = $conn->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
              $stmt->bind_param("i", $order['id']);
              $stmt->execute();
              $items = $stmt->get_result();
              while ($item = $items->fetch_assoc()):
            ?>
              <li><?= $item['name'] ?> × <?= $item['qty'] ?> = <strong>LKR <?= number_format($item['qty'] * $item['price'], 2) ?></strong></li>
            <?php endwhile; $stmt->close(); ?>
          </ul>
          <p class="fw-bold text-end">Total: <span class="text-success">LKR <?= number_format($order['total'], 2) ?></span></p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
