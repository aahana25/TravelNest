<?php
include("config.php");

$manager_id = $_GET['id'];

// get pdf name
$result = $conn->query("SELECT id_proof_pdf FROM managers WHERE manager_id = $manager_id");
$row = $result->fetch_assoc();

// delete pdf file
$file = "uploads/id_proof_manager/" . $row['id_proof_pdf'];
if (file_exists($file)) {
    unlink($file);
}

// delete manager
$conn->query("DELETE FROM managers WHERE manager_id = $manager_id");

header("Location: admin_dashboard.php");
exit;
?>
