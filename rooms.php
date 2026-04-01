<?php
include("config.php");

if(!isset($_GET['hotel_id'])){
    echo "Hotel not found";
    exit();
}

$hotel_id = $_GET['hotel_id'];

$sql="SELECT * FROM hotels WHERE hotel_id=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("i",$hotel_id);
$stmt->execute();
$result=$stmt->get_result();
$hotel=$result->fetch_assoc();

if(!$hotel){
    echo "Hotel not found";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title><?php echo htmlspecialchars($hotel['hotel_name']); ?></title>
<link rel="stylesheet" href="room.css">

</head>

<body>

<div class="container">

<h1><?php echo $hotel['hotel_name']; ?></h1>

<p class="location">
<?php echo $hotel['city']; ?>, <?php echo $hotel['state']; ?>
</p>

<p class="address"><?php echo $hotel['address']; ?></p>


<!-- HOTEL IMAGES -->

<div class="hotel-gallery">

<?php

$sql="SELECT * FROM hotel_images WHERE hotel_id=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("i",$hotel_id);
$stmt->execute();
$images=$stmt->get_result();

$hotelImages=[];

while($img=$images->fetch_assoc()){
$hotelImages[]=$img['image_name'];
}

?>

<img id="hotelSlide"
src="uploads/hotel_images/<?php echo $hotelImages[0]; ?>">

<div class="thumbnail-row">

<?php foreach($hotelImages as $img){ ?>

<img class="thumb"
src="uploads/hotel_images/<?php echo $img; ?>"
onclick="changeHotelImage(this)">

<?php } ?>

</div>

</div>


<!-- DESCRIPTION -->

<div class="hotel-description">

<h2>About this hotel</h2>

<p><?php echo $hotel['description']; ?></p>

</div>


<!-- AMENITIES -->
<div class="amenities-section">

<h2>Amenities</h2>

<div class="amenities">

<?php
$amenities=explode(",",$hotel['amenities']);

foreach($amenities as $a){
?>

<span class="amenity-item"><?php echo trim($a); ?></span>

<?php } ?>

</div>

</div>
<div class="map-section">

<h2>Hotel Location</h2>

<?php
$location = urlencode($hotel['google_map_link']);
?>

<iframe
src="https://maps.google.com/maps?q=<?php echo $location; ?>&output=embed"
width="100%"
height="350"
style="border:0;">
</iframe>

</div>


<h2 class="room-title">Available Rooms</h2>

<?php

$sql="SELECT * FROM rooms WHERE hotel_id=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("i",$hotel_id);
$stmt->execute();
$rooms=$stmt->get_result();

$roomIndex=0;

while($room=$rooms->fetch_assoc()){

?>

<div class="room-box">


<!-- ROOM IMAGE -->

<div class="room-gallery">

<?php

$sql2="SELECT * FROM room_images WHERE room_id=?";
$stmt2=$conn->prepare($sql2);
$stmt2->bind_param("i",$room['room_id']);
$stmt2->execute();
$rimgs=$stmt2->get_result();

$roomImages=[];

while($img=$rimgs->fetch_assoc()){
$roomImages[]=$img['image_name'];
}

?>

<img id="roomSlide<?php echo $roomIndex; ?>"
src="uploads/room_images/<?php echo $roomImages[0]; ?>">

</div>


<!-- ROOM INFO -->

<div class="room-info">

<h3><?php echo $room['room_type']; ?></h3>

<p>Price per night: ₹<?php echo $room['price_per_night']; ?></p>

<p>Adults: <?php echo $room['max_adults']; ?></p>

<p>Children: <?php echo $room['max_children']; ?></p>


<a href="book_room.php?room_id=<?php echo $room['room_id']; ?>&hotel_id=<?php echo $hotel_id; ?>" class="book-btn">
    Book Now
</a>
</div>

</div>



<script>

let roomImages<?php echo $roomIndex; ?> = [
<?php
foreach($roomImages as $img){
echo "'uploads/room_images/$img',";
}
?>
];

let roomIndex<?php echo $roomIndex; ?> = 0;

setInterval(function(){

roomIndex<?php echo $roomIndex; ?>++;

if(roomIndex<?php echo $roomIndex; ?> >= roomImages<?php echo $roomIndex; ?>.length){
roomIndex<?php echo $roomIndex; ?> = 0;
}

document.getElementById("roomSlide<?php echo $roomIndex; ?>").src =
roomImages<?php echo $roomIndex; ?>[roomIndex<?php echo $roomIndex; ?>];

},3000);

</script>

<?php
$roomIndex++;
}
?>

</div>


<script>

let hotelImages = [
<?php
foreach($hotelImages as $img){
echo "'uploads/hotel_images/$img',";
}
?>
];

let hotelIndex=0;

function changeHotelImage(img){
document.getElementById("hotelSlide").src=img.src;
}

setInterval(function(){

hotelIndex++;

if(hotelIndex>=hotelImages.length){
hotelIndex=0;
}

document.getElementById("hotelSlide").src=hotelImages[hotelIndex];

},3000);

</script>

<a href="main.php" class="back"> Back </a>


</body>
</html>