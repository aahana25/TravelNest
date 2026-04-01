<?php
session_start();
require 'config.php'; // Include DB connection

$message = '';

// Make sure email is stored in session from forgot password step
if (!isset($_SESSION['email'])) {
    header("Location: manager_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_passwxord) {
        $message = "❌ Passwords do not match!";
    } else {
        // Hash password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update in DB
        $email = $_SESSION['email'];
        $sql = "UPDATE managers SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();

        // Clear OTP session
        unset($_SESSION['otp']);
        unset($_SESSION['email']);

        // Redirect
        header("Location: manager_login.php?reset=success");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="reset-password-page">
    <div class="reset-container">
        <h2>Reset Your Password</h2>
        <form method="POST">
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Reset Password</button>
        </form>

        <?php if ($message): ?>
            <p style="color: red;"><?= $message ?></p>
        <?php endif; ?>

        <p><a href="manager_login.php">← Back to Login</a></p>
    </div>
</body>
</html>
