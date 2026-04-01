<?php
session_start();
include("config.php");

if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit;
}

$manager_id   = $_SESSION['manager_id'];
$manager_name = $_SESSION['full_name'] ?? "Manager";

/* Fetch ALL hotels of this manager */
$hotel_sql = "SELECT * FROM hotels WHERE manager_id = ?";
$hotel_stmt = $conn->prepare($hotel_sql);
$hotel_stmt->bind_param("i", $manager_id);
$hotel_stmt->execute();
$hotel_result = $hotel_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 30px;
        }

        .box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 8px 14px;
            background: #c47a2c;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-right: 8px;
            margin-top: 5px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #a86422;
        }

        .hotel-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            background: #fafafa;
        }
    </style>
</head>

<body>

<!-- TOP SECTION -->
<div class="box">
    <h2>Hello, <?php echo htmlspecialchars($manager_name); ?> 👋</h2>
    <button class="btn" onclick="window.location.href='index.php'">Logout</button>
</div>

<!-- HOTEL SECTION -->
<div class="box">
    <h3>Your Hotels</h3>

    <button class="btn" onclick="window.location.href='add_hotel.php'">+ Add New Hotel</button>
    <br><br>

    <?php 
    if ($hotel_result->num_rows > 0) {

        while ($hotel = $hotel_result->fetch_assoc()) {
    ?>

        <div class="hotel-card">
    
            <p><strong>Hotel Name:</strong> 
                <?php echo htmlspecialchars($hotel['hotel_name']); ?>
            </p>

            <p><strong>City:</strong> 
                <?php echo htmlspecialchars($hotel['city']); ?>
            </p>

            <p><strong>Address:</strong> 
                <?php echo htmlspecialchars($hotel['address']); ?>
            </p>

            <button class="btn" onclick="window.location.href='edit_hotel.php?hotel_id=<?php echo $hotel['hotel_id']; ?>'">
                Edit
            </button>

            <button class="btn" onclick="window.location.href='add_room.php?hotel_id=<?php echo $hotel['hotel_id']; ?>'">
                Add Room
            </button>

            <button class="btn" onclick="window.location.href='view_rooms.php?hotel_id=<?php echo $hotel['hotel_id']; ?>'">
                View Rooms
            </button>

            <button class="btn" onclick="window.location.href='add_hotel_photos.php?hotel_id=<?php echo $hotel['hotel_id']; ?>'">
                Add Photos
            </button>

       

        </div>

    <?php 
        }
    } else {
        echo "<p>No hotel added yet.</p>";
    }
    ?>
</div>

</body>
</html>
