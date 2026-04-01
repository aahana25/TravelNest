<?php
// Sample static version
$hotelId = $_GET['id'] ?? 1;
$hotelNames = [
  1 => "Hotel Sunrise",
  2 => "Sea Breeze Resort",
  3 => "Royal Palace"
];
?>

<!DOCTYPE html>
<html>
<head>
  <title><?= $hotelNames[$hotelId] ?? 'Hotel Details' ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f5f5f5;
    }
    .hotel-details {
      max-width: 800px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
    }
    .hotel-details img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-radius: 10px;
    }
    .room {
      border: 1px solid #ccc;
      margin-top: 20px;
      padding: 10px;
      border-radius: 5px;
      background: #f9f9f9;
    }
  </style>
</head>
<body>

<div class="hotel-details">
  <h2><?= $hotelNames[$hotelId] ?? 'Hotel' ?></h2>
  <img src="img/hotel<?= $hotelId ?>.jpg" alt="Hotel Image">

  <p><strong>Address:</strong> Some Street, Some City</p>
  <p><strong>Description:</strong> A comfortable stay with all modern amenities, ideal for your vacation or business trip.</p>

  <h3>Available Rooms:</h3>
  <div class="room">
    <p>🛏️ Standard Room - ₹2000/night</p>
    <p>✅ AC, Wi-Fi, TV</p>
  </div>
  <div class="room">
    <p>🛏️ Deluxe Room - ₹3000/night</p>
    <p>✅ AC, Wi-Fi, TV, Bathtub, Sea View</p>
  </div>
</div>

</body>
</html>
