<?php
include("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$manager_id = $_GET['id'];

$result = $conn->query("SELECT email FROM managers WHERE manager_id=$manager_id");
$row = $result->fetch_assoc();
$email = $row['email'];

$conn->query("UPDATE managers SET status='approved' WHERE manager_id=$manager_id");

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'aahanabavale@gmail.com';
    $mail->Password = 'miee cqin xrff liss';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('aahanabavale@gmail.com', 'TravelNest');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'TravelNest – Manager Account Approved';
    $mail->Body = "
        Hello,<br><br>
        Your manager account has been <b>approved</b>.<br>
        You may login now.<br><br>
        Regards,<br>
        TravelNest Team
    ";

    $mail->send();
    sleep(2);

} catch (Exception $e) {
    // ignore
}

header("Location: admin_dashboard.php");
exit;
