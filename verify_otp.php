<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = trim($_POST['otp']);

    if ($entered_otp == $_SESSION['otp']) {
        header("Location: reset_password.php");
        exit();
    } else {
        $message = "❌ Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="verify-otp-page">
    <div class="otp-container">
        <h2>Verify OTP</h2>
        <p>Enter the OTP sent to your email.</p>

        <form method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit">Verify</button>
        </form>

        <?php if ($message): ?>
            <p style="color: red;"><?= $message ?></p>
        <?php endif; ?>

        <p><a href="forgot_password.php">← Back</a></p>
    </div>
</body>
</html>
