<?php
session_start();
include("config.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* CANCEL BOOKING */

if(isset($_POST['cancel_booking'])){

    $booking_id = $_POST['booking_id'];

    $cancel_query = "UPDATE bookings 
                     SET status='cancelled'
                     WHERE booking_id='$booking_id'
                     AND user_id='$user_id'";

    mysqli_query($conn,$cancel_query);

    echo "<script>alert('Booking Cancelled Successfully');</script>";
}

/* FETCH BOOKINGS */

$query = "SELECT bookings.*, hotels.hotel_name, rooms.room_type
          FROM bookings
          JOIN hotels ON bookings.hotel_id = hotels.hotel_id
          JOIN rooms ON bookings.room_id = rooms.room_id
          WHERE bookings.user_id='$user_id'
          ORDER BY bookings.booking_id DESC";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>

<head>
<title>My Bookings</title>
<link rel="stylesheet" href="my_booking.css">
</head>

<body>

<h2>My Bookings</h2>

<table>

<tr>
<th>Hotel</th>
<th>Room</th>
<th>Check-in</th>
<th>Check-out</th>
<th>Total Price</th>
<th>Payment</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['hotel_name']; ?></td>

<td><?php echo $row['room_type']; ?></td>

<td><?php echo $row['checkin_date']; ?></td>

<td><?php echo $row['checkout_date']; ?></td>

<td>₹<?php echo $row['total_price']; ?></td>

<td><?php echo $row['payment_method']; ?></td>

<td>

<?php 
if($row['status'] == "cancelled"){
echo "<span class='status-cancelled'>Cancelled</span>";
}
else{
echo "<span class='status-confirmed'>Confirmed</span>";
}
?>

</td>

<td>

<?php if($row['status'] != "cancelled"){ ?>

<form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?')">

<input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">

<button type="submit" name="cancel_booking" class="cancel-btn">
Cancel
</button>

</form>

<?php } else { ?>

—

<?php } ?>

</td>

</tr>

<?php } ?>

</table>
<br><br>
<a href="main.php" class="back-btn  ">← Back</a>

</body>
</html>