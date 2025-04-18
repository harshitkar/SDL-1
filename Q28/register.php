<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', '', 'email_verification');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(16));

    $stmt = $conn->prepare("INSERT INTO users (email, verification_token) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $token);

    if ($stmt->execute()) {
        $verify_link = "http://localhost/verify.php?token=$token";
        $subject = "Verify your email address";
        $body = "Please click the link to verify: $verify_link";
        $headers = "From: no-reply@example.com";

        mail($email, $subject, $body, $headers);

        $message = '<div class="alert alert-success">Registration successful! Check your email to verify.</div>';
    } else {
        $message = '<div class="alert alert-danger">This email is already registered.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card p-4 shadow">
                <h2 class="text-center mb-4">Register Your Email</h2>

                <?php echo $message; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label>Email address</label>
                        <input type="email" name="email" class="form-control" required />
                    </div>
                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                </form>

            </div>

        </div>
    </div>
</div>

</body>
</html>
