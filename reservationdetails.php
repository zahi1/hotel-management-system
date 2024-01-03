<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Reservation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="edit-reservation">
        <h1>Edit Reservation</h1>
        <a href = "myreservations.php">Go Back</a>
        <?php
        session_start();
        include 'db.php'; // Include your database connection
        
        //if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_reservation'])) {
            $reservation_id = $_POST['reservation_id'];
            $user_id = $_SESSION['user_id']; // Assuming you have stored the user ID in the session
            
            // Query the specific reservation for the logged-in user
            $sql = "SELECT * FROM Reservations
                    WHERE id_Reservation = '$reservation_id' AND fk_Customerid_User = '$user_id'";
                    
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                $reservation = $result->fetch_assoc();
                $room_number = $reservation['fk_Roomroom_number']; // Fetch the room number from the reservation
                
                // Query to get room details including maximum occupancy
                $room_query = "SELECT * FROM Rooms WHERE room_number = '$room_number'";
                $room_result = $conn->query($room_query);
                
                if ($room_result->num_rows > 0) {
                    $room_details = $room_result->fetch_assoc();
                    $maximum_occupancy = $room_details['maximum_occupancy'];
                }
            
                // Display the form to edit reservation details
                ?>
                <form action="" method="POST">
                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['id_Reservation']; ?>">
                    
                    <label for="guest_name">Customer Name:</label>
                    <input type="text" id="guest_name" name="guest_name" value="<?php echo $reservation['guest_name']; ?>" readonly><br><br>
                    
                    <label for="check_in_date">Check-In Date:</label>
                    <input type="date" id="check_in_date" name="check_in_date" value="<?php echo $reservation['check_in_date']; ?>" required><br><br>
                    
                    <label for="check_out_date">Check-Out Date:</label>
                    <input type="date" id="check_out_date" name="check_out_date" value="<?php echo $reservation['check_out_date']; ?>" required><br><br>
                    
                    <label for="number_of_guests">Number of Guests (Max <?php echo $maximum_occupancy; ?>):</label>
                    <input type="number" id="number_of_guests" name="number_of_guests" value="<?php echo $reservation['number_of_guests']; ?>" required max="<?php echo $maximum_occupancy; ?>"><br><br>
                    
                    <!-- Add more fields as needed -->
                    
                    <input type="submit" name="update_reservation" value="Update Reservation">
                </form>
                <?php
            }
            
        if (isset($_POST['update_reservation'])) {
            $check_in_date = $_POST['check_in_date'];
            $check_out_date = $_POST['check_out_date'];
            $number_of_guests = $_POST['number_of_guests'];
            
            // Perform the update query here using the collected data
            $update_sql = "UPDATE Reservations 
                           SET check_in_date = '$check_in_date', check_out_date = '$check_out_date', number_of_guests = '$number_of_guests'
                           WHERE id_Reservation = '$reservation_id'";
            
            if ($conn->query($update_sql) === TRUE) {
                echo "<p>Reservation updated successfully.</p>";
                // You might redirect the user or show a success message here
            } else {
                echo "<p>Error updating reservation: " . $conn->error . "</p>";
            }
        } 
        ?>
    </div>
</body>
</html>
