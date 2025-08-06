<?php
include 'backend/db.php';
header('Content-Type: application/json');

$input = strtolower($_POST['message'] ?? '');
$response = "Sorry, I didn’t understand that.";

if ($input) {
  $query = $conn->prepare("SELECT name, price FROM products");
  $query->execute();
  $result = $query->get_result();

  $matches = [];

  while ($row = $result->fetch_assoc()) {
    $productName = strtolower($row['name']);
    $productWords = explode(" ", $productName);

foreach ($productWords as $word) {
    if ((strpos($input, $word) !== false) && (strpos($input, "price") !== false || strpos($input, "much") !== false)) {
        $matches[] = "{$row['name']}: LKR " . number_format($row['price'], 2);
        break;
    }
}
      }
    }

  if (!empty($matches)) {
    $response = implode("<br>", $matches);
  }

  $query->close();

echo json_encode(['response' => $response]);
