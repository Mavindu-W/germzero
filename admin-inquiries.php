<?php
include 'backend/db.php';
$result = $conn->query("SELECT * FROM contact_inquiries ORDER BY submitted_at DESC");
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Customer Inquiries</h2>
    <a href="admin-dashboard.php" class="btn btn-outline-dark">← Back to Dashboard</a>
  </div>

<div class="table-responsive">
  <table class="table table-bordered table-hover table-striped align-middle">
    <thead class="table-success">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Message</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$i}</td>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['message']}</td>
              </tr>";
        $i++;
      }
      ?>
    </tbody>
  </table>
</div>

