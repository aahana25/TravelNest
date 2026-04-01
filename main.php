<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Guest';

/* GET FILTER VALUES */
$city = $_GET['city'] ?? '';
$search = $_GET['search'] ?? '';

/* GET STATES FOR DROPDOWN */



$location_query = "SELECT state, city FROM hotels ORDER BY state, city";
$location_result = mysqli_query($conn, $location_query);

$locations = [];

while($row = mysqli_fetch_assoc($location_result)){
    $locations[$row['state']][] = $row['city'];
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>TravelNest - Find Your Perfect Stay</title>
<link rel="stylesheet" href="main.css">
</head>

<body class="main-page">

<!-- ================= NAVBAR ================= -->

<div class="navbar">

<div class="logo-container">
<img src="img/logo.jpg" class="logo-img" alt="TravelNest Logo">
</div>

<ul>

<li><a href="main.php">Dashboard</a></li>
<li><a href="about.php">About</a></li>
<li><a href="contact.php">Contact</a></li>
<li><a href="my_booking.php">My Bookings</a></li>

<li>

<div class="dropdown">

<button class="dropbtn">Select Location</button>

<div class="dropdown-content">

<?php
while($state = mysqli_fetch_assoc($location_result)){

$state_name = $state['state'];
?>

<div class="submenu">

<span><?php echo htmlspecialchars($state_name); ?></span>

<div class="submenu-content">

<?php
$city_query = "SELECT DISTINCT city FROM hotels WHERE state='$state_name' ORDER BY city";
$city_result = mysqli_query($conn,$city_query);

while($city_row = mysqli_fetch_assoc($city_result)){
?>

<a href="main.php?city=<?php echo urlencode($city_row['city']); ?>">
<?php echo htmlspecialchars($city_row['city']); ?>
</a>

<?php } ?>

</div>

</div>

<?php } ?>
</div>

</div>

</li>

<li><a href="index.php">Logout</a></li>

</ul>

</div>

<!-- ================= HERO ================= -->

<div class="hero">

<img src="img/hotel-sunset.jpg" class="hero-bg">

<div class="hero-content">

<h2>Welcome to TravelNest</h2>
<h2><?php echo htmlspecialchars($user_name); ?>!</h2>

<p>Let's find your perfect stay</p>

<div class="search-bar">

<form method="GET" action="main.php">

<input type="text" name="search" placeholder="Search city or hotel">

<button type="submit">Search</button>

</form>

</div>

</div>

</div>

<!-- ================= HOTEL CARDS ================= -->

<div class="card-section">

<?php

/* SEARCH */

if (!empty($search)) {

$sql = "
SELECT h.*, MIN(r.price_per_night) AS starting_price
FROM hotels h
LEFT JOIN rooms r ON h.hotel_id = r.hotel_id
WHERE h.city LIKE ? OR h.hotel_name LIKE ?
GROUP BY h.hotel_id
";

$searchTerm = "%".$search."%";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss",$searchTerm,$searchTerm);

}

/* FILTER BY CITY */

elseif (!empty($city)) {

$sql = "
SELECT h.*, MIN(r.price_per_night) AS starting_price
FROM hotels h
LEFT JOIN rooms r ON h.hotel_id = r.hotel_id
WHERE h.city = ?
GROUP BY h.hotel_id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$city);

}

/* DEFAULT SHOW HOTELS */

else {

$sql = "
SELECT h.*, MIN(r.price_per_night) AS starting_price
FROM hotels h
LEFT JOIN rooms r ON h.hotel_id = r.hotel_id
GROUP BY h.hotel_id
ORDER BY h.hotel_id DESC
LIMIT 8
";

$stmt = $conn->prepare($sql);

}

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

while ($hotel = $result->fetch_assoc()) {

$image = !empty($hotel['hotel_image'])
? "uploads/hotels/".$hotel['hotel_image']
: "img/default.jpg";

?>

<div class="hotel-card">

<img src="<?php echo $image; ?>" alt="Hotel Image">

<h3><?php echo htmlspecialchars($hotel['hotel_name']); ?></h3>

<p>
<strong>Location:</strong>
<?php echo htmlspecialchars($hotel['city']); ?>,
<?php echo htmlspecialchars($hotel['state']); ?>
</p>

<p><?php echo htmlspecialchars($hotel['address']); ?></p>

<?php if(!empty($hotel['starting_price'])){ ?>

<p><strong>Starting From:</strong> ₹<?php echo $hotel['starting_price']; ?></p>

<?php } ?>

<button onclick="window.location.href='rooms.php?hotel_id=<?php echo $hotel['hotel_id']; ?>'">
View Rooms
</button>

</div>

<?php
}

} else {

echo "<h2 style='text-align:center;'>No hotels found.</h2>";

}

?>

</div>

</body>
</html>