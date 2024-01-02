<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reservations</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .cancel-btn {
            background-color: #f44336;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .cancel-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="reservation-list">
        <h1>My Reservations</h1>
        <?php
        session_start();
        include 'db.php'; // Include your database connection
        
        // Check if user is logged in and get their user ID from session
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            
            // Check if cancellation form is submitted
            if (isset($_POST['cancel_reservation'])) {
                $reservation_id = $_POST['reservation_id'];
                
                // Handle cancellation logic here (e.g., delete the reservation from the database)
                $cancel_sql = "DELETE FROM Reservations WHERE id_Reservation = '$reservation_id' AND fk_Customerid_User = '$user_id'";
                if ($conn->query($cancel_sql) === TRUE) {
                    echo "<p>Reservation canceled successfully.</p>";
                } else {
                    echo "<p>Error canceling reservation: " . $conn->error . "</p>";
                }
            }
            
            // Query reservations for the logged-in user including room type
            $sql = "SELECT Reservations.id_Reservation, Reservations.check_in_date, Reservations.check_out_date, Rooms.room_number, Rooms.type, Reservations.requested_service
                    FROM Reservations
                    INNER JOIN Rooms ON Reservations.fk_Roomroom_number = Rooms.room_number
                    WHERE Reservations.fk_Customerid_User = '$user_id'";
                    
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr>
                        <th>Reservation ID</th>
                        <th>Check-In Date</th>
                        <th>Check-Out Date</th>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Requested Service</th>
                        <th>Action</th>
                      </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id_Reservation'] . "</td>";
                    echo "<td>" . $row['check_in_date'] . "</td>";
                    echo "<td>" . $row['check_out_date'] . "</td>";
                    echo "<td>" . $row['room_number'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    echo "<td>" . $row['requested_service'] . "</td>";
                    echo "<td>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='reservation_id' value='" . $row['id_Reservation'] . "'>";
                    echo "<input type='submit' class='cancel-btn' name='cancel_reservation' value='Cancel'>";
                    echo "</form>";
                    echo "</td>";
                    echo "<td>";
                    echo "<form method='POST' action='reservationdetails.php'>";
                    echo "<input type='hidden' name='reservation_id' value='" . $row['id_Reservation'] . "'>";
                    echo "<input type='submit' class='edit-btn' name='edit_reservation' value='Edit'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No reservations found.</p>";
            }
        } else {
            echo "<p>Please log in to view your reservations.</p>";
        }
        ?>
    </div>
</body>
</html>
