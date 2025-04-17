<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .product { display: inline-block; padding: 20px; border: 1px solid #ccc; margin: 10px; border-radius: 10px; }
        button { background: green; color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <?php
        echo "<h2>Simple eCommerce</h2>";

        $products = [
            ["id" => 1, "name" => "One", "price" => 100],
            ["id" => 2, "name" => "Two", "price" => 200],
            ["id" => 3, "name" => "Three", "price" => 300],
        ];

        foreach($products as $product) {
            echo "<div class='product'>
                    <h3>{$product['name']}</h3>
                    <p>Price: {$product['price']} INR</p>
                    <form method= 'post'>
                        <input type='hidden' name='product_id' value='{$product['id']}'>
                        <button type='submit' name='buy'>Buy Now</button>
                    </form>
                </div>";
        }

        if( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["buy"])) {
            echo "<p style='color: green;'>Product purchased successfully!</p>";
        }
    ?>

</body>
</html>