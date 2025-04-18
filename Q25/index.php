<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'grocery_store');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Add to cart
if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Remove one
if (isset($_GET['remove'])) {
    $id = (int) $_GET['remove'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]--;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Clear all cart
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all products
$result = $conn->query("SELECT * FROM products");
$allProducts = [];
while ($row = $result->fetch_assoc()) {
    $allProducts[$row['id']] = $row;
}

// Current page (for links)
$self = htmlspecialchars($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grocery Store (Single File)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <h1>🥦 Grocery Store</h1>

  <h2>Products</h2>
  <div class="row">
    <?php foreach ($allProducts as $id => $p): ?>
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($p['name']); ?></h5>
            <p class="card-text">$<?php echo $p['price']; ?></p>
            <p class="card-text text-muted">Stock: <?php echo $p['stock']; ?></p>
            <a href="<?php echo $self; ?>?add=<?php echo $id; ?>" class="btn btn-success">Add to Cart</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <hr>

  <h2>🛒 Your Cart</h2>
  <a href="<?php echo $self; ?>?clear=1" class="btn btn-danger mb-3">Clear Cart</a>

  <?php if (!empty($_SESSION['cart'])): ?>
    <ul class="list-group mb-3">
      <?php $total = 0; ?>
      <?php foreach ($_SESSION['cart'] as $id => $qty): ?>
        <?php if (isset($allProducts[$id])): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?php echo $allProducts[$id]['name']; ?> (x<?php echo $qty; ?>)
            <div>
              <a href="<?php echo $self; ?>?add=<?php echo $id; ?>" class="btn btn-success btn-sm">+</a>
              <a href="<?php echo $self; ?>?remove=<?php echo $id; ?>" class="btn btn-warning btn-sm">-</a>
              <strong class="ms-3">$<?php echo $allProducts[$id]['price'] * $qty; ?></strong>
            </div>
          </li>
          <?php $total += $allProducts[$id]['price'] * $qty; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
    <h4>Total: $<?php echo number_format($total, 2); ?></h4>
  <?php else: ?>
    <div class="alert alert-info">Your cart is empty.</div>
  <?php endif; ?>

</body>
</html>
