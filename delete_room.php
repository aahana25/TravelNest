<?php
session_start();
include("config.php");

if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['room_id']) || !isset($_GET['hotel_id'])) {
    die("Room or Hotel not selected!");
}

$room_id = intval($_GET['room_id']);
$hotel_id = intval($_GET['hotel_id']);

$stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ? AND hotel_id = ?");
$stmt->bind_param("ii", $room_id, $hotel_id);

if ($stmt->execute()) {
    echo "<script>alert('Room deleted successfully!'); window.location='view_rooms.php?hotel_id=$hotel_id';</script>";
    exit();
} else {
    echo "Error deleting room!";
}
?>
