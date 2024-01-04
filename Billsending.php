<?php 
include 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor1/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send'])) {
        $mail = new PHPMailer(true);
        $bill_id = $_POST['bill_id'];
        $sql = "SELECT * FROM bills WHERE id_Bill = '$bill_id'";
        $result = $conn->query($sql);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'hms2024KTU@gmail.com'; // SMTP username
            $mail->Password = 'eafn vdab zcpl ergc'; // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipient
            $to = 'vimalrajvarunjosh@gmail.com'; // Customer's email address
            $mail->setFrom('hms2024KTU@gmail.com', 'Your Name'); // Sender's email and name
            $mail->addAddress($to);

            // Email content
            $mail->isHTML(false); // Set to true if using HTML content
            $mail->Subject = 'Reservation Confirmation';

            // Fetch data from the query
            $row = $result->fetch_assoc();
            $billData = "Bill ID: " . $row['id_Bill'] . "\n";
            $billData .= "Bill Amount: " . $row['bill_amount'] . "\n";
            $billData .= "Date Generated: " . $row['date_generated'] . "\n";

            $mail->Body = 'Dear Customer,' . $billData; // Your email message

            // Send email
            if ($mail->send()) {
                echo "Email sent successfully to $to";
            } else {
                echo "Failed to send email to $to. Error: {$mail->ErrorInfo}";
            }
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bills List</title>
</head>
<body>

<h1>Bills List</h1>

<table border="1">
    <tr>
        <th>Bill ID</th>
        <th>Bill Amount</th>
        <th>Date Generated</th>
        <th>Customer Name</th>
        <th>Email Address</th>
        <th>Action</th> <!-- New header for the Action column -->
    </tr>

    <?php
    include 'db.php'; // Include the database connection file

    // SQL query to join the bills and customers tables
    $sql = "SELECT b.id_Bill, b.bill_amount, b.date_generated, CONCAT(c.firstname, ' ', c.lastname) AS customer_name, c.email_address
            FROM bills AS b
            JOIN customers AS c ON b.customer_id = c.id_User";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_Bill"] . "</td>";
            echo "<td>" . $row["bill_amount"] . "</td>";
            echo "<td>" . $row["date_generated"] . "</td>";
            echo "<td>" . $row["customer_name"] . "</td>";
            echo "<td>" . $row["email_address"] . "</td>";
            
            // Add a Send button for each row
            echo "<td><form action='' method='post'>"; // Assuming send_bill.php is where you handle the 'Send' action
            echo "<input type='hidden' name='bill_id' value='" . $row["id_Bill"] . "'>"; // Passing the bill_id as a hidden input
            echo "<input type='submit' name='send' value='Send'>"; // Send button
            echo "</form></td>";
            
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No records found</td></tr>";
    }

    $conn->close(); // Close the database connection
    ?>
</table>

</body>
</html>