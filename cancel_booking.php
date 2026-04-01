<?php
session_start();
include("config.php");

if(!isset($_GET['booking_id'])){
    echo "Invalid Request";
    exit();
}

$booking_id = $_GET['booking_id'];

/* UPDATE STATUS */

$query = "UPDATE bookings 
          SET status='cancelled' 
          WHERE booking_id='$booking_id'";

mysqli_query($conn,$query);

header("Location: my_bookings.php");
exit();
?>