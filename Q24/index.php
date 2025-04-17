<?php
session_start();

// Simple Products List
$products = [
  1 => ["name" => "T-shirt", "price" => 20],
  2 => ["name" => "Sneakers", "price" => 50],
  3 => ["name" => "Backpack", "price" => 30]
];

// Handle Add One
if (isset($_GET['add'])) {
  $id = $_GET['add'];
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
  header("Location: ?");
  exit();
}

// Handle Remove One
if (isset($_GET['removeOne'])) {
  $id = $_GET['removeOne'];
  if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]--;
    if ($_SESSION['cart'][$id] <= 0) {
      unset($_SESSION['cart'][$id]);
    }
  }
  header("Location: ?");
  exit();
}

// Handle Remove Entire Item
if (isset($_GET['removeAll'])) {
  $id = $_GET['removeAll'];
  unset($_SESSION['cart'][$id]);
  header("Location: ?");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Simple Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <h1>🛍️ Simple Shop</h1>

  <h2>Products</h2>
  <div class="row">
    <?php foreach ($products as $id => $p): ?>
    <div class="col-md-4 mb-3">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title"><?php echo $p['name']; ?></h5>
          <p class="card-text">$<?php echo $p['price']; ?></p>
          <a href="?add=<?php echo $id; ?>" class="btn btn-success">Add to Cart</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <hr>

  <h2>🛒 Your Cart</h2>
  <?php if (!empty($_SESSION['cart'])): ?>
    <ul class="list-group mb-3">
      <?php $total = 0; ?>
      <?php foreach ($_SESSION['cart'] as $id => $qty): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <?php echo $products[$id]['name']; ?> (x<?php echo $qty; ?>)
          </div>
          <div>
            <a href="?add=<?php echo $id; ?>" class="btn btn-success btn-sm">+</a>
            <a href="?removeOne=<?php echo $id; ?>" class="btn btn-warning btn-sm">-</a>
            <a href="?removeAll=<?php echo $id; ?>" class="btn btn-danger btn-sm">Remove All</a>
            <strong class="ms-3">$<?php echo $products[$id]['price'] * $qty; ?></strong>
          </div>
        </li>
        <?php $total += $products[$id]['price'] * $qty; ?>
      <?php endforeach; ?>
    </ul>
    <h4>Total: $<?php echo $total; ?></h4>
  <?php else: ?>
    <div class="alert alert-info">Your cart is empty.</div>
  <?php endif; ?>

</body>
</html>
