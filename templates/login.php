<?php
session_start();
include 'backend/db.php'; // adjust if needed

$message = "";

// Message from register success
if (isset($_GET['registered']) && $_GET['registered'] === 'true') {
  $message = "✅ Registration successful. Please log in.";
}

// Handle login POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Admin credentials
  $adminEmail = "admin@germzero.com";
  $adminPassword = "admin123"; // plaintext or hash as needed

  if ($email === $adminEmail && $password === $adminPassword) {
    $_SESSION['admin_logged_in'] = true;
    header("Location: admin-dashboard.php");
    exit();
  }

  // Check users table
  $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $full_name, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
      $_SESSION["user_id"] = $id;
      $_SESSION["user_name"] = $full_name;
      header("Location: products.php");
      exit();
    } else {
      $message = "❌ Incorrect password.";
    }
  } else {
    $message = "❌ No user found with that email.";
  }

  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Germ Zero</title>
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
          <h4 class="mt-2">Germ Zero Login</h4>
        </div>

        <?php if ($message): ?>
          <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" id="loginForm">
          <div class="mb-3">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" required>
          </div>
          <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="********" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Sign In</button>
          <div class="text-center mt-3">
            <small>Don't have an account? <a href="register.php">Sign Up</a></small>
          </div>
          <hr>
        </form>

      </div>
    </div>
  </div>
</main>

</body>
</html>
