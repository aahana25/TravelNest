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

/* Fetch room details */
$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ? AND hotel_id = ?");
$stmt->bind_param("ii", $room_id, $hotel_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Room not found!");
}

$room = $result->fetch_assoc();

/* Update room */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $room_type     = $_POST['room_type'];
    $price         = $_POST['price_per_night'];
    $total_rooms   = $_POST['total_rooms'];
    $max_adults    = $_POST['max_adults'];
    $max_children  = $_POST['max_children'];

    $update = $conn->prepare("UPDATE rooms SET room_type=?, price_per_night=?, total_rooms=?, max_adults=?, max_children=? WHERE room_id=? AND hotel_id=?");
    $update->bind_param("sdiiiii", $room_type, $price, $total_rooms, $max_adults, $max_children, $room_id, $hotel_id);

    if ($update->execute()) {
        echo "<script>alert('Room updated successfully!'); window.location='view_rooms.php?hotel_id=$hotel_id';</script>";
        exit();
    } else {
        echo "Error updating room!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Room</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 30px; }
        .container { background: #fff; padding: 20px; border-radius: 10px; max-width: 500px; margin: auto; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: #c47a2c; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #a86422; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Room</h2>
    <form method="POST">
        <label>Room Type</label>
        <input type="text" name="room_type" value="<?php echo htmlspecialchars($room['room_type']); ?>" required>

        <label>Price Per Night (₹)</label>
        <input type="number" step="0.01" name="price_per_night" value="<?php echo htmlspecialchars($room['price_per_night']); ?>" required>

        <label>Total Rooms</label>
        <input type="number" name="total_rooms" value="<?php echo htmlspecialchars($room['total_rooms']); ?>" required>

        <label>Max Adults</label>
        <input type="number" name="max_adults" value="<?php echo htmlspecialchars($room['max_adults']); ?>" required>

        <label>Max Children</label>
        <input type="number" name="max_children" value="<?php echo htmlspecialchars($room['max_children']); ?>" required>

        <button type="submit">Update Room</button>
    </form>
</div>

</body>
</html>
