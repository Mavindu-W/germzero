<?php
include 'backend/db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];
$category = $_POST['category'];
$description = $_POST['description'];

// If image is uploaded
if ($_FILES['image']['name']) {
  $image = time() . '_' . basename($_FILES["image"]["name"]);
  $targetFile = 'assets/images/products/' . $image;
  move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
  
  $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category=?, image=?, description=? WHERE id=?");
  $stmt->bind_param("sisssi", $name, $price, $category, $image, $description, $id);
} else {
  $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category=?, description=? WHERE id=?");
  $stmt->bind_param("sissi", $name, $price, $category, $description, $id);
}

$stmt->execute();
$stmt->close();

header("Location: manage-products.php");
exit;
?>
