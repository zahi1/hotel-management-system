<?php
session_start();
include 'db.php'; // Include your database connection
if (isset($_POST['edit_service'])) {
    $reservation_id = $_POST['reservation_id'];
    $selected_service = $_POST['requested_service'];
    if($selected_service == ""){
        header("Location: myreservations.php");
        return;
    }

    // Update the requested_service field in the Reservations table
    $update_service_sql = "UPDATE Reservations SET requested_service = '$selected_service' WHERE id_Reservation = '$reservation_id'";
    
    if ($conn->query($update_service_sql) === TRUE) {
        echo "<p>Service updated successfully.</p>";
        sleep(1);
    header("Location: myreservations.php");
    } else {
        echo "<p>Error updating service: " . $conn->error . "</p>";
    }
}
?>