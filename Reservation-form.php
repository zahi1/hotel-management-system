<?php
session_start();

$userid = $_SESSION['user_id'];

include 'db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['status'])) {
    // Retrieve modified form data
    $updated_reservation_id = $_POST['reservation_id'];
    $updated_status = $_POST['status'];

    // Perform any necessary data validation here...

    // Update the specific reservation in the database
    $update_sql = "UPDATE reservations 
                    SET status = '$updated_status'
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

// Fetch reservation details with customer information, including check-in and check-out dates
$sql = "SELECT reservations.id_Reservation, reservations.status, reservations.fk_Roomroom_number, reservations.fk_Customerid_User, customers.firstname, customers.lastname, reservations.check_in_date, reservations.check_out_date 
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
    <!-- Include Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style>
        header {
            background-size: cover;
            background-position: center;
            min-height: 1000px;
        }
        .image-with-text {
            display: flex!important;
            flex-direction: column;
            align-items: center;
        }
        @media screen and (max-width:670px) {
            header {
                min-height: 500px;
            }
        }
    </style>
</head>
<body>

    <nav>
        <div class="nav-wrapper">
            <a href="employee.php" class="brand-logo">Employee</a>
           
        </div>
    </nav>

    <div class="container">
        <h2>Reservation Details</h2>
        <table class="striped">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Customer ID</th>
                    <th>Customer Full Name</th>
                    <th>Reservation from</th>
                    <th>Reservation till</th>
                    <th>Status</th>
                    <th>Modify</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservationData as $reservation): ?>
                    <tr>
                        <td><?php echo $reservation['fk_Roomroom_number']; ?></td>
                        <td><?php echo $reservation['fk_Customerid_User']; ?></td>
                        <td><?php echo $reservation['firstname'] . ' ' . $reservation['lastname']; ?></td>
                        <td><?php echo $reservation['check_in_date']; ?></td>
                        <td><?php echo $reservation['check_out_date']; ?></td>
                        <td>
                            <form action="Reservation-form.php" method="post">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id_Reservation']; ?>">
                                <select class="browser-default" name="status" required>
                                    <option value="pending" <?php if ($reservation['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="checked-in" <?php if ($reservation['status'] === 'checked-in') echo 'selected'; ?>>Checked-in</option>
                                    <option value="checked-out" <?php if ($reservation['status'] === 'checked-out') echo 'selected'; ?>>Checked-out</option>
                                </select>
                        </td>
                        <td>
                                <button class="btn waves-effect waves-light" type="submit" name="action">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form action="employee.php">
            <button class="btn waves-effect waves-light" type="submit" name="action">Back</button>
        </form>
    </div>

    <!-- Include Materialize JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
