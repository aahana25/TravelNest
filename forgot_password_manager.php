<?php
session_start();
require 'config.php'; // DB and SMTP settings

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if email exists in DB
    $stmt = $conn->prepare("SELECT * FROM managers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send Email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // or your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'aahanabavale@gmail.com'; // replace
            $mail->Password = 'miee cqin xrff liss';     
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Email content
            $mail->setFrom('aahanabavale@gmail.com', 'TravelNest');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'TravelNest Password Reset OTP';
            $mail->Body    = "<h2>Your OTP is: $otp</h2>";

            $mail->send();
            header("Location: verify_manager_otp.php");
            exit();
        } catch (Exception $e) {
            $message = "❌ OTP sending failed. Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "❌ Email not found.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css"> <!-- Link your CSS file -->
</head>
<body class="forgot-password-page">
    <div class="forgot-container">
        <h2>Forgot Password</h2>
        <p>Enter your email address to receive an OTP.</p>

        <form method="POST">
            <input type="email" name="email" required placeholder="Enter your email"><br>
            <button type="submit">Send OTP</button>
        </form>

        <?php if ($message): ?>
            <p style="color:red;"><?php echo $message; ?></p>
        <?php endif; ?>

        <p><a href="login.php">Back to Login</a></p>
    </div> 
</body>
</html>
