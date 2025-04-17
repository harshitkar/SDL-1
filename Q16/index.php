<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Web App</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
        input { padding: 10px; margin: 10px; }
        button { padding: 10px 20px; background: blue; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>

    <h2>Enter Your Name</h2>
    
    <form method="POST">
        <input type="text" name="username" placeholder="Your Name" required>
        <input type="text" name="surname">
        <button type="submit">Submit</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST["username"]);
        $surname = htmlspecialchars($_POST["surname"]);
        echo "<h3>Welcome, $name $surname!</h3>";
    }
    ?>

</body>
</html>
