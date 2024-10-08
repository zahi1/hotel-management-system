<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor1/autoload.php';


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
    $customer_email = $_SESSION['email'];
    // Insert the reservation details into the Reservations table
    $insert_reservation_sql = "INSERT INTO Reservations (check_in_date, check_out_date, fk_Roomroom_number, fk_Customerid_User, guest_name, number_of_guests) VALUES ('$check_in_date', '$check_out_date', '$selected_room_number', '$user_id', '$guest_name', '$number_of_guests')";
    
    if ($conn->query($insert_reservation_sql) === TRUE) {
        // Update the room status to 'booked' in the Rooms table
        $update_room_status_sql = "UPDATE Rooms SET status = 'booked' WHERE room_number = '$selected_room_number'";
        
        if ($conn->query($update_room_status_sql) === TRUE) {
            // Create a PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'hms2024KTU@gmail.com'; // SMTP username
                $mail->Password = 'eafn vdab zcpl ergc'; 
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Recipient
                $to = 'vimalrajvarunjosh@gmail.com'; 
                $mail->setFrom('hms2024KTU@gmail.com', 'Your Name'); 
                $mail->addAddress($customer_email);

                // Email content
                $mail->isHTML(false); 
                $mail->Subject = 'Reservation Confirmation';
                $mail->Body = 'Dear Customer, Your reservation has been confirmed.';

                // Send email
                if ($mail->send()) {
                    //echo "Email sent successfully to $to";
                } else {
                    echo "Failed to send email to $to. Error: {$mail->ErrorInfo}";
                }
            } catch (Exception $e) {
                echo "Mailer Error: {$mail->ErrorInfo}";
            }
            header("Location: myreservations.php");
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
