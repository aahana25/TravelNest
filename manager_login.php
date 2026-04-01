<?php
session_start();
include("config.php");

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM managers WHERE email=? AND status='approved'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $manager = $result->fetch_assoc();

        // ✅ VERIFY HASHED PASSWORD
        if (password_verify($password, $manager['password'])) {

            $_SESSION['manager_id'] = $manager['manager_id'];
            $_SESSION['manager_name'] = $manager['full_name'];

            header("Location: manager_dashboard.php");
            exit;

        } else {
            $error = "❌ Wrong password";
        }

    } else {
        $error = "❌ Account not approved or email not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Login</title>
    <link rel="stylesheet" href="login_manager.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Left Section with Image -->
        <div class="left-section">
            <div class="overlay-content">
                <h1>TravelNest</h1>
                <p>A HOTEL BOOKING WEBSITE</p>
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="right-section">
            <div class="form-box">
                <h2>LOGIN INTO YOUR ACCOUNT</h2>

                <?php if (isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>

                <form method="post" autocomplete="off">
                    <div class="input-group">
                        <i class="fa fa-envelope"></i>
                        <input type="email" name="email" placeholder="Enter your email" autocomplete="off" required />
                    </div>
                    <div class="input-group">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" placeholder="Enter your password" required />
                    </div>
                    <button type="submit" name="login" class="btn-login">Login</button>

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