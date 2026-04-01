<?php
session_start();
include("config.php");

if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit();
}

$manager_id = $_SESSION['manager_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hotel_name = $_POST['hotel_name'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $amenities = $_POST['amenities'];
    $google_map = $_POST['google_map'];

    // Image Upload
    $image_name = $_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];
    $folder = "uploads/hotels/" . $image_name;

    move_uploaded_file($temp_name, $folder);

    $stmt = $conn->prepare("INSERT INTO hotels 
        (hotel_name, state, city, address, description, amenities, google_map_link, hotel_image, manager_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssssi",
        $hotel_name,
        $state,
        $city,
        $address,
        $description,
        $amenities,
        $google_map,
        $image_name,
        $manager_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Hotel added successfully!'); window.location='manager_dashboard.php';</script>";
        exit();
    } else {
        echo "Error adding hotel.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Hotel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            background: #fff;
            max-width: 600px;
            margin: 20px auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], input[type="file"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

      form button {
        background: #c47a2c;
        color: white;
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
        display: block;          
        margin: 0 auto;        
    }

form button:hover {
    background: #a86422;
}

    </style>
</head>
<body>

<h2>Add New Hotel</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Hotel Name</label>
    <input type="text" name="hotel_name" required>

    <label>State</label>
    <input type="text" name="state" required>

    <label>City</label>
    <input type="text" name="city" required>

    <label>Address</label>
    <input type="text" name="address" required>

    <label>Description</label>
    <textarea name="description" required></textarea>

    <label>Amenities (comma separated)</label>
    <input type="text" name="amenities">

    <label>Google Map Link</label>
    <input type="text" name="google_map">

    <label>Main Hotel Image</label>
    <input type="file" name="image" required>

    <button type="submit">Add Hotel</button>

</form>

</body>
</html>
