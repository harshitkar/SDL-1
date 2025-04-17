<?php
// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username']; // Get input from form
  // Set cookie for 1 minute (60 seconds)
  setcookie("user", $username, time() + 60, "/");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Store User Input in Cookie</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <h2>Enter Your Name 🌟</h2>

  <!-- Form to get user input -->
  <form method="POST" action="">
    <div class="mb-3">
      <input type="text" name="username" class="form-control" placeholder="Enter your name" required>
    </div>
    <button type="submit" class="btn btn-primary">Save to Cookie</button>
  </form>

  <hr>

  <!-- Display cookie if it exists -->
  <?php
  if (isset($_COOKIE["user"])) {
    echo '<div class="alert alert-success mt-3">Hello, ' . htmlspecialchars($_COOKIE["user"]) . '!</div>';
  } else {
    echo '<div class="alert alert-info mt-3">No cookie set yet.</div>';
  }
  ?>

</body>
</html>
