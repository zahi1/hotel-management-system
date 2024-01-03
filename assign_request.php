<?php
session_start();
include 'db.php'; // Include your database connection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['assign'])) {
        $reservation_id = $_POST['reservation_id'];
        $assigned_employee = $_POST['assigned_employee'];

        // Fetch reservation details to get service information
        $reservation_query = "SELECT * FROM Reservations WHERE id_Reservation = $reservation_id";
        $reservation_result = $conn->query($reservation_query);

        if ($reservation_result->num_rows > 0) {
            $reservation_data = $reservation_result->fetch_assoc();
            
            // Extract service details
            $service_type = $reservation_data['requested_service'];
            $service_price = 0; // You might retrieve this from somewhere else
            $service_availability = 1; // Assuming service is now available

            // Insert new row into services table
            $insert_service_sql = "INSERT INTO services (service_type, service_price, service_availability, fk_Reservationid_Reservation, fk_Employee_ID) 
                                   VALUES ('$service_type', $service_price, $service_availability, $reservation_id, $assigned_employee)";
            
            $insert_result = $conn->query($insert_service_sql);

            if ($insert_result) {
                // Successfully inserted, perform any additional actions or redirects
                header("Location: Customer-requests-page.php"); // Redirect to services table or any other page
                exit();
            } else {
                // Handle insert failure
                echo "Error inserting service: " . $conn->error;
            }
        } else {
            echo "No reservation found for ID: $reservation_id";
        }
    }
}
?>