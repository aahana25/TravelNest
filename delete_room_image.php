<?php
session_start();
include("config.php");

if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit;
}

if (!isset($_GET['image_id'], $_GET['room_id'])) {
    die("Missing parameters!");
}

$image_id = $_GET['image_id'];
$room_id = $_GET['room_id'];

// Verify that the manager owns this room
$stmt = $conn->prepare("
    SELECT r.room_id, h.manager_id 
    FROM rooms r 
    JOIN hotels h ON r.hotel_id = h.hotel_id 
    WHERE r.room_id=? AND h.manager_id=?
");
$stmt->bind_param("ii", $room_id, $_SESSION['manager_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("You do not have permission!");
}

// Get image file name
$stmt2 = $conn->prepare("SELECT image_name FROM room_images WHERE image_id=? AND room_id=?");
$stmt2->bind_param("ii", $image_id, $room_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
if ($res2->num_rows > 0) {
    $img = $res2->fetch_assoc();
    $file = "uploads/room_images/" . $img['image_name'];
    if (file_exists($file)) unlink($file);

    // Delete from database
    $stmt3 = $conn->prepare("DELETE FROM room_images WHERE image_id=?");
    $stmt3->bind_param("i", $image_id);
    $stmt3->execute();
}

header("Location: add_room_photos.php?room_id={$room_id}");
exit;
?>