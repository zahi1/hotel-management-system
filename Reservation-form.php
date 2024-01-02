<?php
session_start();

$userid = $_SESSION['user_id'];

include 'db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['checkinstatus'], $_POST['checkoutstatus'])) {
    // Retrieve modified form data
    $updated_reservation_id = $_POST['reservation_id'];
    $updated_checkinstatus = $_POST['checkinstatus'];
    $updated_checkoutstatus = $_POST['checkoutstatus'];

    // Perform any necessary data validation here...

    // Update the specific reservation in the database
    $update_sql = "UPDATE reservations 
                    SET checkinstatus = '$updated_checkinstatus', 
                        checkoutstatus = '$updated_checkoutstatus'
                    WHERE id_Reservation = $updated_reservation_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "Reservation updated successfully!";
        // Redirect to employee.php after successful update
        header("Location: employee.php");
        exit(); // Ensure that code execution stops after redirection
    } else {
        echo "Error updating reservation: " . $conn->error;
    }
}

// Fetch reservation details with customer information
$sql = "SELECT reservations.id_Reservation, reservations.checkinstatus, reservations.checkoutstatus, reservations.fk_Roomroom_number, reservations.fk_Customerid_User, customers.firstname, customers.lastname 
        FROM reservations 
        INNER JOIN customers ON reservations.fk_Customerid_User = customers.id_User";
$result = $conn->query($sql);

$reservationData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservationData[] = $row;
    }
} else {
    echo "No reservations found.";
    exit; // Exit if no reservations are found
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reservation Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Reservation Details</h2>
    <table>
        <tr>
            <th>Room Number</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Check-in Status</th>
            <th>Check-out Status</th>
            <th>Modify</th>
        </tr>
        <?php foreach ($reservationData as $reservation): ?>
            <tr>
                <form action="Reservation-form.php" method="post">
                    <td><?php echo $reservation['fk_Roomroom_number']; ?></td>
                    <td><?php echo $reservation['fk_Customerid_User']; ?></td>
                    <td><?php echo $reservation['firstname'] . ' ' . $reservation['lastname']; ?></td>
                    <td>
                        <input type="hidden" name="reservation_id" value="<?php echo $reservation['id_Reservation']; ?>">
                        <input type="text" name="checkinstatus" value="<?php echo $reservation['checkinstatus']; ?>" required>
                    </td>
                    <td>
                        <input type="text" name="checkoutstatus" value="<?php echo $reservation['checkoutstatus']; ?>" required>
                    </td>
                    <td>
                        <input type="submit" value="Update">
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="employee.php">Back</a>
</body>
</html>
