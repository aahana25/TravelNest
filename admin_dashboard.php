<?php
session_start();
include("config.php");

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h2>Admin Dashboard</h2>
<a href="admin_logout.php">Logout</a>

<hr>

<h3>Users</h3>
<table border="1" cellpadding="10">
<tr>
    <th>Name</th><th>Email</th><th>Phone</th><th>Action</th>
</tr>

<?php
$users = $conn->query("SELECT * FROM users");
while ($u = $users->fetch_assoc()) {
    echo "<tr>
        <td>{$u['full_name']}</td>
        <td>{$u['email']}</td>
        <td>{$u['phone']}</td>
        <td><a href='delete_user.php?id={$u['id']}'>Delete</a></td>
    </tr>";
}
?>
</table>

<hr>

<h3>Hotel Managers</h3>
<table border="1" cellpadding="10">
<tr>
    <th>Name</th><th>Email</th><th>ID Proof</th><th>Status</th><th>Action</th>
</tr>

<?php
$managers = $conn->query("SELECT * FROM managers");
while ($m = $managers->fetch_assoc()) {
    echo "<tr>
        <td>{$m['full_name']}</td>
        <td>{$m['email']}</td>
        <td><a href='uploads/id_proof_manager/{$m['id_proof_pdf']}' target='_blank'>View PDF</a></td>
        <td>{$m['status']}</td>
        <td>
            <a href='approve_manager.php?id={$m['manager_id']}'>Approve</a> |
            <a href='reject_manager.php?id={$m['manager_id']}'>Reject</a>
        </td>
    </tr>";
}
?>
</table>

</body>
</html>
