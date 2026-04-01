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

/* Fetch rooms for this hotel */
$stmt = $conn->prepare("SELECT * FROM rooms WHERE hotel_id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Rooms</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 40px;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background: #c47a2c;
            color: white;
        }

        .btn {
            padding: 6px 10px;
            margin: 1%;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            color: black;
        }

        .edit {
            background: #28a745;
            border:1;
        }

        .delete {
            background: #dc3545;
        }
        .photos{
             background: #f48fa8;
    
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #444;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .no-room {
            text-align: center;
            padding: 20px;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Rooms List</h2>

    <?php if ($result->num_rows > 0) { ?>

        <table>
            <tr>
                <th>Room Type</th>
                <th>Price (₹)</th>
                <th>Total Rooms</th>
                <th>Max Adults</th>
                <th>Max Children</th>
                <th>Actions</th>
            </tr>

            <?php while ($room = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($room['room_type']); ?></td>
                    <td><?php echo htmlspecialchars($room['price_per_night']); ?></td>
                    <td><?php echo htmlspecialchars($room['total_rooms']); ?></td>
                    <td><?php echo htmlspecialchars($room['max_adults']); ?></td>
                    <td><?php echo htmlspecialchars($room['max_children']); ?></td>
                   <td>
                    <a class="btn edit"
                        href="edit_room.php?room_id=<?php echo $room['room_id']; ?>&hotel_id=<?php echo $hotel_id; ?>">
                        Edit
                    </a>

                    <a class="btn delete"
                    href="delete_room.php?room_id=<?php echo $room['room_id']; ?>&hotel_id=<?php echo $hotel_id; ?>"
                    onclick="return confirm('Are you sure you want to delete this room?');">
                    Delete
                     </a>

                    <a class="btn photos" 
                        href="add_room_photos.php?room_id=<?php echo $room['room_id']; ?>">
                        Add Room Photos
                    </a>
                    </td>

                </tr>
            <?php } ?>

        </table>

    <?php } else { ?>
        <div class="no-room">
            <p>No rooms added yet for this hotel.</p>
        </div>
    <?php } ?>
   
    <a href="manager_dashboard.php" class="back">← Back to Dashboard</a>


</div>

</body>
</html>
