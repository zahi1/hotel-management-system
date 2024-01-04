<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            background-image: url('imgs/myreservation.jpg');
    background-size: cover;
    background-position: center;
    min-height: 1000px;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
}
nav {
            background-color: #333; /* Background color of the navigation bar */
            overflow: hidden; /* Ensures proper rendering of the bar */
        }

        nav a {
            float: right; /* Aligns the links horizontally */
            display: block; /* Makes the links block elements */
            color: white; /* Text color */
            text-align: center; /* Centers the text */
            padding: 14px 16px; /* Padding around the links */
            text-decoration: none; /* Removes default underline */
        }
        nav button{
            background-color: #333;
            color: white;
        }

        nav a:hover {
            background-color: #ddd; /* Background color on hover */
            color: black; /* Text color on hover */
        }

.edit-btn, .cancel-btn {
    background-color: #f44336;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    display: block;
    width: 80px;
    margin: 0 auto;
}

.edit-btn:hover, .cancel-btn:hover {
    background-color: #d32f2f;
}

        table {
            border-collapse: collapse;
            width: 100%;
            
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        td{
    font-weight: 800; /* Makes the th font double bold */
    font-family: Arial, sans-serif; /* Example font family */
    color: #f4ff81
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
        img {
    display: block; /* Ensures margin auto works properly */
    width: 50%; /* Adjust the width as needed */
    height: auto;
    margin-left: auto;
    margin-right: auto;
}
    </style>
</head>
<body>
<nav>
        <a href="customer.php">Home</a>
        <a>
            <form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="btn">Logout</button>
            </form></a>
        <h1 style ="color:white">My Reservations</h1>

        <!-- You can add more navigation links if needed -->
    </nav>
    <div class="reservation-list">
        <a href="customer.php">Go Back</a>
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
            $sql = "SELECT Reservations.id_Reservation, Reservations.check_in_date, Reservations.check_out_date, 
            Rooms.image,Rooms.room_number, Rooms.type, Reservations.requested_service
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
                        <th></th>
                        <th>Room Type</th>
                        <th>Requested Service</th>
                        <th>Action</th>
                      </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id_Reservation'] . "</td>";
                    echo "<td>" . $row['check_in_date'] . "</td>";
                    echo "<td>" . $row['check_out_date'] . "</td>";
                    echo "<td><img src='imgs/" . $row['image'] . "' alt='" . $row['image'] . "'></td>";
                   // echo "<td>" . $row['room_number'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    //echo "<td>" . $row['requested_service']  . "</td>";
                    if (isset($_POST['edit_service'])) {
                        $reservation_id = $_POST['reservation_id'];
                        $selected_service = $_POST['requested_service'];
                
                        // Update the requested_service field in the Reservations table
                        $update_service_sql = "UPDATE Reservations SET requested_service = '$selected_service' WHERE id_Reservation = '$reservation_id'";
                        
                        if ($conn->query($update_service_sql) === TRUE) {
                            echo "<p>Service updated successfully.</p>";
                        header("Refresh:1;url=myreservations.php");
                        } else {
                            echo "<p>Error updating service: " . $conn->error . "</p>";
                        }
                    }
                    $services = array('car service', 'extra bed', 'room clean');
                    echo "<td>" ;
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='reservation_id' value='" . $row['id_Reservation'] . "'>";
                    echo "<select name='requested_service'>";
                    echo "<option value=''>Select service</option>";
                    foreach ($services as $service) {
                        echo "<option value='$service'";
                        if ($row['requested_service'] === $service) {
                            echo " selected";
                        }
                        echo ">$service</option>";
                    }
                    echo "</select><br>";
                    echo "<br>";
                    echo "<input type='submit' class='edit-btn' name='edit_service' value='Request'>";
                    echo "</form>"; 
                    echo "</td>";
                    echo "<td>";    
                    echo '<form method="POST" onsubmit="return confirm(\'Are you sure you want to cancel this reservation?\');">';

                    echo "<input type='hidden' name='reservation_id' value='" . $row['id_Reservation'] . "'>";
                    echo "<input type='submit' class='cancel-btn' name='cancel_reservation' value='Cancel'>";
                    echo "</form><br>";  
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
