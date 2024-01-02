<?php
session_start();
include 'db.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_reservation'])) {
    $selected_room_number = $_POST['room_number'];
    $user_id = $_SESSION['user_id']; // Assuming 'user_id' is stored in the session
    
    $check_in_date = $_SESSION['check_in_date']; // Retrieve this value from your form
    $check_out_date = $_SESSION['check_out_date']; // Retrieve this value from your form

    // Retrieve guest name and number of guests from the form
    $guest_name = $_POST['guest_name'];
    $number_of_guests = $_POST['number_of_guests'];
    
    // Insert the reservation details into the Reservations table
    $insert_reservation_sql = "INSERT INTO Reservations (check_in_date, check_out_date, fk_Roomroom_number, fk_Customerid_User, guest_name, number_of_guests) VALUES ('$check_in_date', '$check_out_date', '$selected_room_number', '$user_id', '$guest_name', '$number_of_guests')";
    
    if ($conn->query($insert_reservation_sql) === TRUE) {
        // Update the room status to 'booked' in the Rooms table
        $update_room_status_sql = "UPDATE Rooms SET status = 'booked' WHERE room_number = '$selected_room_number'";
        
        if ($conn->query($update_room_status_sql) === TRUE) {
            echo "Room reserved successfully!";
        } else {
            echo "Error updating room status: " . $conn->error;
        }
    } else {
        echo "Error reserving room: " . $conn->error;
    }
    
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
