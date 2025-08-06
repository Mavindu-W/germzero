<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

include 'backend/db.php'; // adjust if needed

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_product'])) {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $category = $_POST['category'];
  $description = $_POST['description'];

  // Handle image upload
  $image_name = '';
  if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $targetDir = "assets/images/products/";
    $image_name = time() . '_' . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $image_name);
  }

  $stmt = $conn->prepare("INSERT INTO products (name, price, category, description, image) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sdsss", $name, $price, $category, $description, $image_name);
  $stmt->execute();
  $stmt->close();
  header("Location: manage-products.php");
  exit();
}

// Handle delete
if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $delete_id);
  $stmt->execute();
  $stmt->close();
  header("Location: manage-products.php");
  exit();
}

// Fetch products
$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products - Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Products</h2>
    <a href="admin-dashboard.php" class="btn btn-outline-dark">← Back to Dashboard</a>
  </div>

  <!-- Add New Product Form -->
  <div class="card mb-4">
    <div class="card-header bg-success text-white">
      <strong>Add New Product</strong>
    </div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="add_product" value="1">
        <div class="row g-3">
          <div class="col-md-4">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label>Price (LKR)</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label>Category</label>
            <select name="category" class="form-select" required>
              <option value="Toilet & Bathroom Care">Toilet & Bathroom Care</option>
              <option value="Hand Hygiene Products">Hand Hygiene Products</option>
              <option value="Dishwashing Essentials">Dishwashing Essentials</option>
              <option value="Surface Cleaners">Surface Cleaners</option>
            </select>
          </div>
          <div class="col-md-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
          </div>
          <div class="col-md-12">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="2" required></textarea>
          </div>
        </div>
        <div class="text-end mt-3">
          <button type="submit" class="btn btn-success">Add Product</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" enctype="multipart/form-data" action="edit-product.php">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit_id">
        <div class="mb-3">
          <label>Name</label>
          <input type="text" name="name" id="edit_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Price (LKR)</label>
          <input type="number" name="price" id="edit_price" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Category</label>
          <select name="category" id="edit_category" class="form-select" required>
            <option value="Toilet & Bathroom Care">Toilet & Bathroom Care</option>
            <option value="Hand Hygiene Products">Hand Hygiene Products</option>
            <option value="Dishwashing Essentials">Dishwashing Essentials</option>
            <option value="Surface Cleaners">Surface Cleaners</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Description</label>
          <textarea name="description" id="edit_description" class="form-control" rows="2" required></textarea>
        </div>
        <div class="mb-3">
          <label>Change Image (optional)</label>
          <input type="file" name="image" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update Product</button>
      </div>
    </form>
  </div>
</div>

  <!-- Product Table -->
  <div class="card">
    <div class="card-header bg-dark text-white">
      <strong>Product List</strong>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price (LKR)</th>
            <th>Category</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><img src="assets/images/products/<?= $row['image'] ?>" width="60" class="rounded"></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= number_format($row['price'], 2) ?></td>
              <td><?= htmlspecialchars($row['category']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <td>
                <!-- Optional Edit feature could go here -->
                 <button class="btn btn-sm btn-primary" onclick="editProduct(<?= $row['id'] ?>, '<?= $row['name'] ?>', <?= $row['price'] ?>, '<?= $row['category'] ?>', '<?= $row['description'] ?>')">
  <i class="fas fa-edit"></i>
</button>

                <a href="manage-products.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i></a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
          <!-- Edit model-->
<script>
  function editProduct(id, name, price, category, description) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_description').value = description;
    new bootstrap.Modal(document.getElementById('editModal')).show();
  }
</script>

</body>
</html>
