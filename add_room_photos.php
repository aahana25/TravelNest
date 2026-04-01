<?php
session_start();
include("config.php");

// Check if manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];

// Check if room_id is passed
if (!isset($_GET['room_id'])) {
    echo "Room ID missing!";
    exit;
}

$room_id = $_GET['room_id'];

// Fetch room and hotel info to verify manager ownership
$stmt = $conn->prepare("
    SELECT r.*, h.hotel_name 
    FROM rooms r 
    JOIN hotels h ON r.hotel_id=h.hotel_id 
    WHERE r.room_id=? AND h.manager_id=?
");
$stmt->bind_param("ii", $room_id, $manager_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Room not found or you do not have permission!";
    exit;
}

$room = $result->fetch_assoc();
$message = "";

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['room_images'])) {

        $upload_dir = "uploads/room_images/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $files = $_FILES['room_images'];

        for ($i = 0; $i < count($files['name']); $i++) {

            $file_name = uniqid() . "_" . basename($files['name'][$i]);
            $target_file = $upload_dir . $file_name;
            $file_tmp = $files['tmp_name'][$i];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed = ['jpg','jpeg','png','webp','gif'];
            if (!in_array($file_ext, $allowed)) {
                $message .= "❌ File {$files['name'][$i]} not allowed!<br>";
                continue;
            }

            if (move_uploaded_file($file_tmp, $target_file)) {
                $insert = $conn->prepare("INSERT INTO room_images (room_id, hotel_id, image_name) VALUES (?, ?, ?)");
                $insert->bind_param("iis", $room_id, $room['hotel_id'], $file_name);
                $insert->execute();
            } else {
                $message .= "❌ Failed to upload {$files['name'][$i]}<br>";
            }
        }

        if ($message === "") {
            $message = "✅ Images uploaded successfully!";
        }
    } else {
        $message = "❌ No files selected!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Room Photos</title>

    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #eef2f7, #dce6f2);
            margin: 0;
            padding: 40px;
        }

        .container {
            background: #ffffff;
            max-width: 1000px;
            margin: auto;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        h3 {
            margin-top: 30px;
            color: #444;
            border-bottom: 2px solid #c47a2c;
            display: inline-block;
            padding-bottom: 5px;
        }

        p {
            text-align: center;
            font-weight: 500;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="file"] {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: #fafafa;
            width: 100%;
            max-width: 400px;
        }

        .btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #c47a2c, #a86422);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s ease;
            display: inline-block;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .photo-card {
            background: #fff;
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 5px 12px rgba(0,0,0,0.08);
            transition: 0.3s ease;
            text-align: center;
        }

        .photo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        }

        .photo-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .delete-link {
            color: #dc3545;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }

        .delete-link:hover {
            text-decoration: underline;
        }

        hr {
            margin: 30px 0;
            border: none;
            height: 1px;
            background: #eee;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>
        Upload Photos for Room:
        <?php echo htmlspecialchars($room['room_type']); ?>
        (<?php echo htmlspecialchars($room['hotel_name']); ?>)
    </h2>

    <?php if($message) echo "<p>{$message}</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Select Room Images (multiple allowed)</label><br><br>
        <input type="file" name="room_images[]" multiple accept="image/*" required><br><br>
        <button type="submit" class="btn">Upload Images</button>
    </form>

    <a href="view_rooms.php?hotel_id=<?php echo $room['hotel_id']; ?>" class="btn">← Back to Rooms</a>

    <hr>

    <h3>Existing Photos:</h3>

    <div class="photo-grid">
        <?php
        $stmt2 = $conn->prepare("SELECT * FROM room_images WHERE room_id=?");
        $stmt2->bind_param("i", $room_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        while ($img = $result2->fetch_assoc()) {
        ?>
            <div class="photo-card">
                <img src="uploads/room_images/<?php echo $img['image_name']; ?>">
                <a href="delete_room_image.php?image_id=<?php echo $img['image_id']; ?>&room_id=<?php echo $room_id; ?>" class="delete-link">
                    Delete
                </a>
            </div>
        <?php } ?>
    </div>

</div>

</body>
</html>
x