<?php
session_start();
include 'db.php'; // Include your database connection

if ($_SESSION['role'] !== 'employee') {
    // Redirect to login page or unauthorized access page if not logged in as employee
    header("Location: login.php");
    exit();
}

// Fetch service requests
$sql = "SELECT * FROM Reservations WHERE requested_service IS NOT NULL AND id_Reservation NOT IN (SELECT fk_Reservationid_Reservation FROM services)";
$result = $conn->query($sql);

// Fetch list of employees
$employees_sql = "SELECT * FROM Employees";
$employees_result = $conn->query($employees_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Requests</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Additional styles or frameworks as needed -->
</head>
<body>
    <a href="employee.php">Back</a>
    <h1>Service Requests</h1>
    <table>
        <tr>
            <th>Reservation ID</th>
            <th>Guest Name</th>
            <th>Requested Service</th>
            <th>Action</th>
        </tr>
        <?php
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                
                echo "<tr>";
                echo "<td>" . $row['id_Reservation'] . "</td>";
                echo "<td>" . $row['guest_name'] . "</td>";
                echo "<td>" . $row['requested_service'] . "</td>";
                echo "<td>";
                echo "<form method='POST' action='assign_request.php'>";
                echo "<input type='hidden' name='reservation_id' value='" . $row['id_Reservation'] . "'>";
                echo "<select name='assigned_employee'>";

                // Reset the internal pointer for employees_result
                mysqli_data_seek($employees_result, 0);

                while ($employee = $employees_result->fetch_assoc()) {
                    echo "<option value='" . $employee['id_User'] . "'>" . $employee['name'] . "</option>";
                }
                echo "</select>";
                echo "<input type='submit' name='assign' value='Assign'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No pending requests.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

