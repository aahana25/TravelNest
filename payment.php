<?php
session_start();
include("config.php");

if(!isset($_GET['booking_id'])){
    echo "Invalid Payment Request";
    exit();
}

$booking_id = $_GET['booking_id'];

/* GET BOOKING DETAILS */

$query = "SELECT bookings.*, rooms.room_type, hotels.hotel_name 
          FROM bookings
          JOIN rooms ON bookings.room_id = rooms.room_id
          JOIN hotels ON bookings.hotel_id = hotels.hotel_id
          WHERE bookings.booking_id='$booking_id'";

$result = mysqli_query($conn,$query);
$booking = mysqli_fetch_assoc($result);

if(!$booking){
    echo "Booking not found";
    exit();
}

/* PAYMENT LOGIC */

if(isset($_POST['pay'])){

    $update = "UPDATE bookings 
               SET payment_status='paid'
               WHERE booking_id='$booking_id'";

    mysqli_query($conn,$update);

    header("Location: booking_confirm.php?booking_id=".$booking_id);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Online Payment</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.payment-box{
background:white;
padding:30px;
border-radius:10px;
width:400px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

.payment-box h2{
margin-bottom:15px;
}

.payment-box p{
margin:8px 0;
}

.pay-btn{
margin-top:20px;
width:100%;
padding:12px;
background:#ff6b00;
border:none;
color:white;
font-size:18px;
border-radius:6px;
cursor:pointer;
}

.pay-btn:hover{
background:#e65c00;
}

</style>

</head>

<body>

<div class="payment-box">

<h2>Online Payment</h2>

<p><b>Hotel:</b> <?php echo $booking['hotel_name']; ?></p>

<p><b>Room:</b> <?php echo $booking['room_type']; ?></p>

<p><b>Check-in:</b> <?php echo $booking['checkin_date']; ?></p>

<p><b>Check-out:</b> <?php echo $booking['checkout_date']; ?></p>

<p><b>Total Price:</b> ₹<?php echo $booking['total_price']; ?></p>

<form method="POST">
<button type="submit" name="pay" class="pay-btn">
Pay Now
</button>
</form>

</div>

</body>
</html>