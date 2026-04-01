<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_POST['username'] === "admin" && $_POST['password'] === "admin123") {
        $_SESSION['admin'] = true;   // ✅ this sets the session
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>

<h2>Admin Login</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
</form>
