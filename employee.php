<?php
session_start();

$username =  $_SESSION['user_id'];
echo 'Hello, ' . $username . '!';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Welcome, Employee!</h2>
    <ul>
        <li><a href="Bill-form.php">Bill Form</a></li>
        <li><a href="Customer-requests-page.php">Customer Requests</a></li>
        <li><a href="Reservation-form.php">Reservation Form</a></li>
    </ul>
    <form action="logout.php" method="post" class="logout-form">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
