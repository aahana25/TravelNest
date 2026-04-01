<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("config.php");

if(!isset($_GET['booking_id'])){
    echo "Booking not found";
    exit();
}

$booking_id = $_GET['booking_id'];

$query = "SELECT b.*, h.hotel_name, r.room_type
FROM bookings b
JOIN hotels h ON b.hotel_id = h.hotel_id
JOIN rooms r ON b.room_id = r.room_id
WHERE b.booking_id='$booking_id'";

$result = mysqli_query($conn,$query);

if(!$result){
    die(mysqli_error($conn));
}

$booking = mysqli_fetch_assoc($result);

if(!$booking){
    echo "Booking not found";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking Confirmed</title>
<link rel="stylesheet" href="booking_confirm.css">
</head>

<body>

<div class="confirm-box">

<h2>Booking Confirmed ✅</h2>

<p><strong>Hotel:</strong> <?php echo $booking['hotel_name']; ?></p>

<p><strong>Room:</strong> <?php echo $booking['room_type']; ?></p>

<p><strong>Check-in:</strong> <?php echo $booking['checkin_date']; ?></p>

<p><strong>Check-out:</strong> <?php echo $booking['checkout_date']; ?></p>

<p><strong>Guests:</strong> <?php echo $booking['guests']; ?></p>

<p><strong>Total Price:</strong> ₹<?php echo $booking['total_price']; ?></p>

<a href="main.php">Back to Home</a>

</div>

</body>
</html>