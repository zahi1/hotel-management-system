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
<meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- font awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
</head>
<body>
<nav>
        <div class="nav-wrapper">
            <a href="employee.php" class="brand-logo">Service Requests</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="employee.php">Back</a></li>
            </ul>
        </div>
    </nav>
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

