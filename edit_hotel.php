<?php
session_start();
include("config.php");

if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit();
}

$manager_id = $_SESSION['manager_id'];

if (!isset($_GET['hotel_id'])) {
    echo "Hotel ID missing!";
    exit();
}

$hotel_id = $_GET['hotel_id'];

// Fetch hotel
$stmt = $conn->prepare("SELECT * FROM hotels WHERE hotel_id = ? AND manager_id = ?");
$stmt->bind_param("ii", $hotel_id, $manager_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Hotel not found!";
    exit();
}

$hotel = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hotel_name = $_POST['hotel_name'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $amenities = $_POST['amenities'];
    $google_map = $_POST['google_map'];

    $hotel_image = $hotel['hotel_image'];

    if (!empty($_FILES['hotel_image']['name'])) {

        $upload_dir = "uploads/hotels/";
        $new_image = uniqid() . "_" . $_FILES['hotel_image']['name'];
        $target = $upload_dir . $new_image;

        move_uploaded_file($_FILES['hotel_image']['tmp_name'], $target);
        $hotel_image = $new_image;
    }

    $update = $conn->prepare("UPDATE hotels 
        SET hotel_name=?, state=?, city=?, address=?, description=?, amenities=?, google_map_link=?, hotel_image=? 
        WHERE hotel_id=? AND manager_id=?");

    $update->bind_param(
        "ssssssssii",
        $hotel_name,
        $state,
        $city,
        $address,
        $description,
        $amenities,
        $google_map,
        $hotel_image,
        $hotel_id,
        $manager_id
    );

    if ($update->execute()) {
        echo "<script>alert('Hotel updated successfully!'); window.location='manager_dashboard.php';</script>";
        exit();
    } else {
        echo "Error updating hotel.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Hotel</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            background: #fff;
            max-width: 650px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #555;
        }

        input[type="text"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        img {
            display: block;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        button {
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

        button:hover {
            background: #a86422;
        }
    </style>

</head>
<body>

<h2>Edit Hotel</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Hotel Name</label>
    <input type="text" name="hotel_name" value="<?php echo htmlspecialchars($hotel['hotel_name']); ?>" required>

    <label>State</label>
    <input type="text" name="state" value="<?php echo htmlspecialchars($hotel['state']); ?>" required>

    <label>City</label>
    <input type="text" name="city" value="<?php echo htmlspecialchars($hotel['city']); ?>" required>

    <label>Address</label>
    <input type="text" name="address" value="<?php echo htmlspecialchars($hotel['address']); ?>" required>

    <label>Description</label>
    <textarea name="description" required><?php echo htmlspecialchars($hotel['description']); ?></textarea>

    <label>Amenities (comma separated)</label>
    <input type="text" name="amenities" value="<?php echo htmlspecialchars($hotel['amenities']); ?>">

    <label>Google Map Link</label>
    <input type="text" name="google_map" value="<?php echo htmlspecialchars($hotel['google_map_link'] ?? ''); ?>">

    <label>Current Hotel Image</label>
    <img src="uploads/hotels/<?php echo $hotel['hotel_image']; ?>" width="200">

    <label>Change Hotel Image</label>
    <input type="file" name="hotel_image">

    <button type="submit">Update Hotel</button>

</form>

</body>
</html>
