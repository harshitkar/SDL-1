<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', '', 'email_verification');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = ?");
        $update->bind_param("s", $token);
        $update->execute();

        $message = '<div class="alert alert-success">✅ Email Verified Successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">❌ Invalid or Expired Token!</div>';
    }
} else {
    $message = '<div class="alert alert-warning">⚠️ No token provided!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verify Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card p-4 shadow text-center">
                <h2>Email Verification</h2>

                <?php echo $message; ?>

            </div>

        </div>
    </div>
</div>

</body>
</html>
