<?php
session_start();
include("config.php");

// Check if manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];

// Check if image_id and hotel_id are passed
if (!isset($_GET['image_id']) || !isset($_GET['hotel_id'])) {
    echo "Missing parameters!";
    exit;
}

$image_id = $_GET['image_id'];
$hotel_id = $_GET['hotel_id'];

// Make sure this hotel belongs to this manager
$stmt = $conn->prepare("SELECT * FROM hotels WHERE hotel_id=? AND manager_id=?");
$stmt->bind_param("ii", $hotel_id, $manager_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Unauthorized action!";
    exit;
}

// Get image name
$stmt2 = $conn->prepare("SELECT * FROM hotel_images WHERE image_id=? AND hotel_id=?");
$stmt2->bind_param("ii", $image_id, $hotel_id);
$stmt2->execute();
$res2 = $stmt2->get_result();

if ($res2->num_rows == 0) {
    echo "Image not found!";
    exit;
}

$img = $res2->fetch_assoc();
$image_path = "uploads/hotel_images/" . $img['image_name'];

// Delete the file from server
if (file_exists($image_path)) {
    unlink($image_path);
}

// Delete from database
$stmt3 = $conn->prepare("DELETE FROM hotel_images WHERE image_id=? AND hotel_id=?");
$stmt3->bind_param("ii", $image_id, $hotel_id);
$stmt3->execute();

// Redirect back to add_hotel_photos page
header("Location: add_hotel_photos.php?hotel_id=" . $hotel_id);
exit;
?>
