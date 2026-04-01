<?php
session_start();
include("config.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['room_id']) || !isset($_GET['hotel_id'])){
    echo "Room not found";
    exit();
}

$room_id = $_GET['room_id'];
$hotel_id = $_GET['hotel_id'];

/* GET ROOM DETAILS */
$query = "SELECT * FROM rooms WHERE room_id='$room_id'";
$result = mysqli_query($conn,$query);
$room = mysqli_fetch_assoc($result);
if(isset($_POST['payment_method'])){
    $payment_method = $_POST['payment_method'];
}else{
    $payment_method = '';
}
if(!$room){
    echo "Room not found";
    exit();
}

/* BOOKING LOGIC */
if(isset($_POST['book'])){

    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $guests = $_POST['guests'];

    $today = date("Y-m-d");

    if($checkin < $today){
        echo "<script>alert('Check-in date cannot be in the past');</script>";
    }
    elseif($checkout <= $checkin){
        echo "<script>alert('Check-out must be after check-in');</script>";
    }
    else{

        $days = (strtotime($checkout) - strtotime($checkin)) / (60*60*24);
        $total_price = $days * $room['price_per_night'];

        /* CHECK ROOM AVAILABILITY */
        $check_query = "SELECT COUNT(*) as booked 
        FROM bookings 
        WHERE room_id='$room_id'
        AND (
        checkin_date < '$checkout'
        AND checkout_date > '$checkin'
        )";

        $result_check = mysqli_query($conn,$check_query);
        $row = mysqli_fetch_assoc($result_check);

        $booked_rooms = $row['booked'];
        $total_rooms = $room['total_rooms'];

        if($booked_rooms >= $total_rooms){

            echo "<script>alert('Room Not Available for selected dates');</script>";

        }else{

           $insert = "INSERT INTO bookings 
           (user_id, hotel_id, room_id, checkin_date, checkout_date, guests, total_price, payment_method)
            VALUES 
            ('$user_id','$hotel_id','$room_id','$checkin','$checkout','$guests','$total_price','$payment_method')";

            mysqli_query($conn,$insert);

            $booking_id = mysqli_insert_id($conn);

            if($payment_method == "online"){
                header("Location: payment.php?booking_id=".$booking_id);
                }
            else{
                header("Location: booking_confirm.php?booking_id=".$booking_id);
                }

            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Room</title>
<link rel="stylesheet" href="book_room.css">
</head>

<body>

<div class="booking-box">

<h2>Book <?php echo $room['room_type']; ?></h2>

<p>Price per night: 
<span class="price-highlight">₹<?php echo $room['price_per_night']; ?></span>
</p>

<form method="POST">

<label>Check-in Date</label>
<input type="date" name="checkin" min="<?php echo date('Y-m-d'); ?>" required>

<label>Check-out Date</label>
<input type="date" name="checkout" min="<?php echo date('Y-m-d'); ?>" required>

<label>Guests</label>
<input type="number" name="guests" min="1" required>

<div class="payment-methods">
<label>Select Payment Method</label>

<label class="payment-card">
<input type="radio" name="payment_method" value="pay_at_hotel" required>
<div class="payment-content">
<h4>Pay at Hotel</h4>
<p>Pay when you check-in at the hotel.</p>
</div>
</label>

<label class="payment-card">
<input type="radio" name="payment_method" value="online">
<div class="payment-content">
<h4>Online Payment</h4>
<p>Pay securely online using card or UPI.</p>
</div>
</label>

</div>

<button type="submit" name="book">Confirm Booking</button>




</form>

</div>

</body>
</html>