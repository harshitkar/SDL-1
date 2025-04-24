<?php
  session_start();

  $user_id = 1; // Simulating a logged-in user with ID 1

  $conn = new mysqli('localhost', 'root', '');
  $conn->query("CREATE DATABASE IF NOT EXISTS grocery_store");
  $conn->select_db('grocery_store');

  $conn->query("CREATE TABLE IF NOT EXISTS products (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL,
      price DECIMAL(10,2) NOT NULL,
      stock INT DEFAULT 0
  )");

  $conn->query("CREATE TABLE IF NOT EXISTS cart (
      user_id INT NOT NULL,
      product_id INT NOT NULL,
      quantity INT NOT NULL,
      PRIMARY KEY (user_id, product_id)
  )");

  $res = $conn->query("SELECT COUNT(*) AS count FROM products");
  if ($res->fetch_assoc()['count'] == 0) {
      $conn->query("INSERT INTO products (name, price, stock) VALUES
          ('Apple', 0.99, 20),
          ('Banana', 0.59, 25),
          ('Carrot', 0.39, 30)");
  }

  if (isset($_GET['add'])) {
      $id = (int) $_GET['add'];
      $conn->query("INSERT INTO cart (user_id, product_id, quantity)
          VALUES ($user_id, $id, 1)
          ON DUPLICATE KEY UPDATE quantity = quantity + 1");
  }

  if (isset($_GET['remove'])) {
      $id = (int) $_GET['remove'];
      $conn->query("UPDATE cart SET quantity = quantity - 1 WHERE user_id = $user_id AND product_id = $id");
      $conn->query("DELETE FROM cart WHERE user_id = $user_id AND quantity <= 0");
  }

  if (isset($_GET['clear'])) {
      $conn->query("DELETE FROM cart WHERE user_id = $user_id");
  }

  if (isset($_GET['remove_item'])) {
    $id = (int) $_GET['remove_item'];
    $conn->query("DELETE FROM cart WHERE user_id = $user_id AND product_id = $id");
  }

  $result = $conn->query("SELECT * FROM products");
  $allProducts = [];
  while ($row = $result->fetch_assoc()) {
      $allProducts[$row['id']] = $row;
  }

  $cart = [];
  $res = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");
  while ($row = $res->fetch_assoc()) {
      $cart[$row['product_id']] = $row['quantity'];
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grocery Store</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f4f4f4;
    }
    h1, h2 {
      color: #333;
    }
    .products {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .cart {
      display: flex;
      flex-wrap: wrap;
      flex-direction: column;
      gap: 20px;
    }
    .card {
      background: #fff;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 6px;
      width: 200px;
    }
    .btn {
      display: inline-block;
      padding: 6px 12px;
      text-decoration: none;
      background: #28a745;
      color: white;
      border-radius: 4px;
      font-size: 14px;
    }
    .btn-danger {
      background: #dc3545;
    }
    .btn-warning {
      background: #ffc107;
      color: black;
    }
    .btn-sm {
      font-size: 12px;
      padding: 4px 8px;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    li {
      background: white;
      padding: 10px;
      margin-bottom: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .item-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
  </style>
</head>
<body>

  <h1>Grocery Store</h1>

  <h2>Products</h2>
  <div class="products">
    <?php foreach ($allProducts as $id => $p): ?>
      <div class="card">
        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
        <p>Price: $<?php echo $p['price']; ?></p>
        <p>Stock: <?php echo $p['stock']; ?></p>
        <a href="index.php?add=<?php echo $id; ?>" class="btn">Add to Cart</a>
      </div>
    <?php endforeach; ?>
  </div>

  <hr>

  <h2>Your Cart</h2>
  <a href="index.php?clear=1" class="btn btn-danger">Clear Cart</a>

  <?php if (!empty($cart)): ?>
    <ul class="cart">
      <?php $total = 0; ?>
      <?php foreach ($cart as $id => $qty): ?>
        <?php if (isset($allProducts[$id])): ?>
          <li>
            <div class="item-actions">
              <span><?php echo $allProducts[$id]['name']; ?> (x<?php echo $qty; ?>)</span>
              <div>
                <a href="index.php?add=<?php echo $id; ?>" class="btn btn-sm">+</a>
                <a href="index.php?remove=<?php echo $id; ?>" class="btn btn-warning btn-sm">-</a>
                <a href="index.php?remove_item=<?php echo $id; ?>" class="btn btn-danger btn-sm">Remove</a>
                <strong>$<?php echo $allProducts[$id]['price'] * $qty; ?></strong>
              </div>
            </div>
          </li>
          <?php $total += $allProducts[$id]['price'] * $qty; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
    <h4>Total: $<?php echo number_format($total, 2); ?></h4>
  <?php else: ?>
    <p>Your cart is empty.</p>
  <?php endif; ?>

</body>
</html>
