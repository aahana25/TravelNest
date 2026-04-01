<?php
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';   
require 'phpmailer/src/PHPMailer.php'; 
require 'phpmailer/src/SMTP.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

    $name = htmlspecialchars($_POST['name']); 
    $email = htmlspecialchars($_POST['email']);     
    $message = htmlspecialchars($_POST['message']); 

    $mail = new PHPMailer(true);  

    try { 
        // SMTP settings
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'aahanabavale@gmail.com'; 
        $mail->Password = 'miee cqin xrff liss'; // your Gmail app password
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = 587; 

        // Sender & Receiver
        $mail->setFrom('aahanabavale@gmail.com', 'TravelNest Contact Form'); 
        $mail->addAddress('aahanabavale@gmail.com'); // where you receive messages

        // Email content
        $mail->isHTML(true); 
        $mail->Subject = '📩 New Contact Form Submission'; 
        $mail->Body = "
            <h2>New Contact Form Message</h2>
            <p><b>Name:</b> {$name}</p>
            <p><b>Email:</b> {$email}</p>
            <p><b>Message:</b><br>{$message}</p>
        ";

        $mail->send();
        echo "<p style='color:green; font-weight:bold;'>✅ Message sent successfully! We’ll get back to you soon.</p>"; 
        echo "<a href='contact.php'>⬅ Go Back</a>"; 

    } catch (Exception $e) { 
        echo "<p style='color:red; font-weight:bold;'>❌ Message could not be sent. Error: {$mail->ErrorInfo}</p>"; 
        echo "<a href='contact.php'>⬅ Go Back</a>"; 
    } 
} 
?>