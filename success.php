<?php
session_start();
include 'db.php'; // Database connection file

if (isset($_SESSION['bill_id'])) {
    $bill_id = $_SESSION['bill_id'];

    // Prepare and bind parameters
    $update_stmt = $conn->prepare("UPDATE bills SET status = 'paid' WHERE id_Bill = ?");
    $update_stmt->bind_param("i", $bill_id);

    // Execute the update statement
    if ($update_stmt->execute()) {
        echo "<p>Bill ID: $bill_id has been successfully paid.</p>";
    } else {
        echo "<p>Error updating bill status: " . $conn->error . "</p>";
    }

    // Close the prepared statement
    $update_stmt->close();
} else {
    echo "<p>Bill ID is not available.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
</head>
<body>
    <h1>Thank you for your payment</h1>
    <form action="customer.php">
        <button type="submit">Back</button>
    </form>
</body>
</html>
