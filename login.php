<?php
session_start();
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check user in database
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $full_name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $full_name;
            header("Location: main.php");
            exit();
        } else {
            $error = "❌ Invalid password.";
        }
    } else {
        $error = "❌ Email not registered.";
    }

    $stmt->close();
}
?>
<?php if (isset($_GET['reset']) && $_GET['reset'] == 'success'): ?>
    <p style="color:green;">✅ Password reset successful. You can now log in.</p>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - TravelNest</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Left Slideshow Section -->
        <div class="left-section">
            <div class="slideshow">
                <div class="slide slide1"></div>
                <div class="slide slide2"></div>
                <div class="slide slide3"></div>
            </div>
            <div class="overlay-content">
                <h1>TravelNest</h1>
                <p>Where Every Journey Finds a Home.</p>
            </div>
        </div>

        <!-- Right Login Form Section -->
        <div class="right-section">
            <div class="form-box">
                <h2>LOGIN INTO YOUR ACCOUNT</h2>

                <?php if ($error): ?>
                    <p style="color: red; text-align:center;"><?= $error ?></p>
                <?php endif; ?>

                <form method="post" autocomplete="off">
                    <div class="input-group">
                        <i class="fa fa-envelope"></i>
                        <input type="email" name="email" placeholder="Enter your email" required />
                    </div>
                    <div class="input-group">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" placeholder="Enter your password" required />
                    </div>
                    <button type="submit" class="btn-login">Login</button>

                    <div class="login-link">
                        <p>Don't have an account? <a href="register.php" class="register">Register here</a></p>
                        <p><a href="forgot_password.php" class="forgot">Forgot Password?</a></p>
                        <p><a href="login_choice.php" class="back-home">← Back</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>