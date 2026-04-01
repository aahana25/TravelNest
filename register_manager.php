<?php
include("config.php");
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "❌ Passwords do not match";
    }
    elseif (!isset($_FILES['id_proof']) || $_FILES['id_proof']['error'] !== 0) {
        $message = "❌ ID proof PDF is required";
    }
    else {

        $ext = strtolower(pathinfo($_FILES['id_proof']['name'], PATHINFO_EXTENSION));

        if ($ext !== "pdf") {
            $message = "❌ Only PDF files are allowed";
        }
        else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $upload_dir = "uploads/id_proof_manager/";
            $file_name = uniqid("id_") . ".pdf";
            $target_file = $upload_dir . $file_name;

            if (!move_uploaded_file($_FILES['id_proof']['tmp_name'], $target_file)) {
                $message = "❌ File upload failed";
            }
            else {

                $sql = "INSERT INTO managers
                        (full_name, email, phone, password, id_proof_pdf, status)
                        VALUES (?, ?, ?, ?, ?, 'pending')";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "sssss",
                    $full_name,
                    $email,
                    $phone,
                    $hashed_password,
                    $file_name
                );

                if ($stmt->execute()) {
                    echo "<p style='color:green;text-align:center;'>
                            ✅ Manager registered successfully.<br>
                            Wait for admin approval.
                          </p>";
                    echo "<script>
                            setTimeout(() => window.location='index.php', 4000);
                          </script>";
                    exit;
                } else {
                    $message = "❌ Database error: " . $stmt->error;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Hotel Manager Registration</title>
        <link rel="stylesheet" href="register_manager.css">
    </head>


    <body class="register_manager">
        <div class="register_manager-container">
            <?php if (!empty($message)) echo "<p style='color:red'>$message</   p>"; ?>
                    <div class="container">
                        <div class="left-section">
                        </div>
                        <div class="right-section">
                            <div class="form-box">
                                <h2>REGISTER YOUR ACCOUNT</h2>

                                <form method="POST" enctype="multipart/form-data">
                                    <div class="input-group">
                                        <input type="text" name="full_name" placeholder="Full Name" required><br><br>
                                    </div>

                                    <div class="input-group">
                                        <input type="email" name="email" placeholder="Email" required><br><br>
                                    </div>

                                    <div class="input-group">
                                        <input type="text" name="phone" placeholder="Phone" required><br><br>
                                    </div>

                                    <div class="input-group">
                                        <input type="password" name="password" placeholder="Password" required><br><br>
                                    </div>

                                    <div class="input-group">
                                        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
                                    </div>
                                    <label class="proof">ID Proof (PDF only)</label><br>
                                    <div class="input-group">
                                        
                                        <input type="file" name="id_proof" accept="application/pdf" required><br><br>
                                    </div>

                                    <button type="submit" class="btn">Register</button>
                                    <div class="login-link">
                                        <p>Already have an account? 
                                        <a href="login.php">Login here</a></p> 
                                        <p><a href="register_choice.php" class="back-home"> < Back </a></p>
                                    </div>
                                </form>
                            </div>
                        </div>    

        </div>
    </body>
</html>
