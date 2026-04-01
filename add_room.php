<?php
session_start();
include("config.php");

if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['hotel_id'])) {
    die("Hotel not selected.");
}

$hotel_id = intval($_GET['hotel_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $room_type        = $_POST['room_type'];
    $price_per_night  = $_POST['price_per_night'];
    $total_rooms      = $_POST['total_rooms'];
    $max_adults       = $_POST['max_adults'];
    $max_children     = $_POST['max_children'];

    $stmt = $conn->prepare("INSERT INTO rooms 
        (hotel_id, room_type, price_per_night, total_rooms, max_adults, max_children) 
        VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("isdiis",
        $hotel_id,
        $room_type,
        $price_per_night,
        $total_rooms,
        $max_adults,
        $max_children
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('Room added successfully!');
                window.location='manager_dashboard.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Room</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 40px;
        }

        .form-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            width: 420px;
            margin: auto;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0 15px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .btn {
            background: #c47a2c;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        .btn:hover {
            background: #a86422;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>

<body>

<div class="form-box">
    <h2>Add Room</h2>

    <form method="POST">

        <label>Room Type</label>
        <input type="text" name="room_type" required>

        <label>Price Per Night (₹)</label>
        <input type="number" step="0.01" name="price_per_night" required>

        <label>Total Rooms Available</label>
        <input type="number" name="total_rooms" required>

        <label>Max Adults</label>
        <input type="number" name="max_adults" required>

        <label>Max Children</label>
        <input type="number" name="max_children" required>

        <button type="submit" class="btn">Add Room</button>
    </form>

    <a href="manager_dashboard.php" class="back">← Back to Dashboard</a>
</div>

</body>
</html>
