<?php
session_start();
include 'backend/db.php'; // adjust if path differs

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $full_name = $_POST["full_name"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Check if email already exists
  $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    $message = "❌ Email already registered.";
  } else {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $full_name, $email, $hashed);

    if ($stmt->execute()) {
      header("Location: login.php?registered=true");
      exit();
    } else {
      $message = "❌ Registration failed: " . $conn->error;
    }
    $stmt->close();
  }
  $check->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Germ Zero</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<main class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow p-4 mt-5">
        <div class="text-center mb-3">
          <img src="assets/images/logo1.png" width="70">
          <h4 class="mt-2">Create an Account</h4>
        </div>

        <?php if ($message): ?>
          <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>

        <form id="registerForm" method="POST" action="">
          <div class="mb-3">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" class="form-control" placeholder="John Doe" required>
          </div>
          <div class="mb-3">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" required>
          </div>
          <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Create a strong password" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Register</button>
          <div class="text-center mt-3">
            <small>Already have an account? <a href="login.php">Log In</a></small>
          </div>
          <hr>
        </form>

      </div>
    </div>
  </div>
</main>

</body>
</html>
