<?php
session_start();
include("config.php");

// Check if manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];

// Check if hotel_id is passed
if (!isset($_GET['hotel_id'])) {
    echo "Hotel ID missing!";
    exit;
}

$hotel_id = $_GET['hotel_id'];

// Fetch hotel to make sure this manager owns it
$stmt = $conn->prepare("SELECT * FROM hotels WHERE hotel_id=? AND manager_id=?");
$stmt->bind_param("ii", $hotel_id, $manager_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Hotel not found or you do not have permission!";
    exit;
}

$hotel = $result->fetch_assoc();
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['hotel_images'])) {

    $upload_dir = "uploads/hotel_images/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // create folder if it doesn't exist
    }

    $files = $_FILES['hotel_images'];

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
            // Insert into database
            $insert = $conn->prepare("INSERT INTO hotel_images (hotel_id, image_name) VALUES (?, ?)");
            $insert->bind_param("is", $hotel_id, $file_name);
            $insert->execute();
        } else {
            $message .= "❌ Failed to upload {$files['name'][$i]}<br>";
        }
    }

    if ($message === "") {
        $message = "✅ Images uploaded successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Hotel Photos</title>
   <style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(135deg, #f5f7fa, #e4ecf5);
        padding: 40px;
    }

    .container {
        background: #ffffff;
        padding: 35px;
        border-radius: 15px;
        max-width: 900px;
        margin: auto;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        animation: fadeIn 0.5s ease-in-out;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    h3 {
        margin-top: 30px;
        margin-bottom: 15px;
        color: #444;
        border-bottom: 2px solid #c47a2c;
        display: inline-block;
        padding-bottom: 5px;
    }

    form {
        text-align: center;
        margin-bottom: 20px;
    }

    input[type=file] {
        margin: 15px 0;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background: #fafafa;
        width: 100%;
        max-width: 400px;
    }

    .btn {
        padding: 10px 18px;
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
        margin-top: 15px;
    }

    .photo-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 12px rgba(0,0,0,0.08);
        transition: 0.3s ease;
        text-align: center;
        padding: 8px;
    }

    .photo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
    }

    .photo-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 8px;
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

    p {
        text-align: center;
        font-weight: 500;
        color: #28a745;
    }

    hr {
        margin: 30px 0;
        border: none;
        height: 1px;
        background: #eee;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

</head>
<body>

<div class="container">
    <h2>Upload Photos for <?php echo htmlspecialchars($hotel['hotel_name']); ?></h2>
    <?php if($message) echo "<p>{$message}</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Select Hotel Images (multiple allowed)</label><br>
        <input type="file" name="hotel_images[]" multiple accept="image/*" required><br>
        <button type="submit" class="btn">Upload Images</button>
    </form>

    <br>
    <a href="manager_dashboard.php" class="btn">← Back to Dashboard</a>

    <hr>

    <h3>Existing Photos:</h3>
    <div class="photo-grid">
        <?php
        $stmt2 = $conn->prepare("SELECT * FROM hotel_images WHERE hotel_id=?");
        $stmt2->bind_param("i", $hotel_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        while ($img = $result2->fetch_assoc()) {
            echo "<div class='photo-card'>
                    <img src='uploads/hotel_images/{$img['image_name']}'>
                    <a href='delete_hotel_image.php?image_id={$img['image_id']}&hotel_id={$hotel_id}' class='delete-link'>Delete</a>
                  </div>";
        }
        ?>
    </div>
</div>

</body>
</html>
