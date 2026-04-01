<?php include("config.php"); $message = "";
 $success = false; if ($_SERVER["REQUEST_METHOD"] == "POST") 
 { $full_name = $_POST["full_name"]; 
   $email = $_POST["email"]; $phone = $_POST["phone"];    
   $password = $_POST["password"]; $confirm_password = $_POST["confirm_password"]; 
   if ($password !== $confirm_password) { $message = "❌ Passwords do not match!"; } 
   else { $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
   $sql = "INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)"; 
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("ssss", $full_name, $email, $phone, $hashed_password); 
   if ($stmt->execute()) { $success = true; $message = "✅ You have successfully registered! Redirecting to login..."; 
   header("refresh:3;url=login.php"); // Redirect after 3 seconds 
   } else { $message = "❌ Error: " . $stmt->error; 
   } } } ?> 
<!DOCTYPE html> 
<html> 
    <head> <title>Register</title> 
    <link rel="stylesheet" href="register.css"> </head> 
    <body class="register-page"> <div class="register-container"> 
        <?php if ($message): ?> 
            <p style="color: <?= $success ? 'green' : 'red'; ?>; text-align:center;">
                <?php echo $message; ?></p> <?php endif; ?> <?php if (!$success): ?> 
                     <div class="container">
                        <div class="left-section">
                        </div>
                        <div class="right-section">
                            <div class="form-box">
                                <h2>Create your Account</h2>
                                <p>Sign up to book your next vacation!</p>

                                
                                <form method="post" action="">

                                    <div class="input-group">
                                        <input type="text" name="full_name"
                                        placeholder="Full Name" required>
                                    </div>

                                    <div class="input-group">
                                        <input type="email" name="email" placeholder="Email" required> 
                                    </div>

                                    <div class="input-group">    
                                        <input type="tel" name="phone" placeholder="Phone Number" required>
                                    </div> 

                                    <div class="input-group">
                                        <input type="password" name="password" placeholder="Password" required> 
                                    </div>


                                    <div class="input-group">
                                        <input type="password" name="confirm_password" placeholder="Confirm Password" required> 
                                    </div>

                                    <button type="submit" class="btn">Register</button> 
                                    <?php endif; ?>
                                    <div class="login-link"> 
                                        <p>Already have an account? 
                                        <a href="login.php">Login here</a></p> 
                                        <p><a href="register_choice.php" class="back-home">< Back </a>
                                        </p>
                                    </div>

                                </form>
                            </div>
                        </div> 
                    </div>
    </body>
</html>