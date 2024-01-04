<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//require 'vendor1/autoload.php';
require 'C:\Windows\System32\vendor\autoload.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userid = $_SESSION['user_id'];
include 'db.php';

// Fetch customers
$customerOptions = '';
$customerQuery = "SELECT id_User, CONCAT(firstname, ' ', lastname) AS full_name FROM Customers";
$customerResult = $conn->query($customerQuery);
while ($row = $customerResult->fetch_assoc()) {
    $customerOptions .= "<option value='{$row['full_name']}'>{$row['full_name']}</option>";
}

// Fetch service types
$serviceOptions = '';
$serviceQuery = "SELECT id_Service, service_type FROM Services";
$serviceResult = $conn->query($serviceQuery);
while ($row = $serviceResult->fetch_assoc()) {
    $serviceOptions .= "<option value='{$row['id_Service']}'>{$row['service_type']}</option>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $date_time = $_POST['date_time'];
    $bill_amount = $_POST['bill_amount'];
    if (isset( $_POST['service_id'])) {
        $service_id = $_POST['service_id'];
        $insertSQL = "INSERT INTO Reports (customer_name, date_time, creator, bill_amount, fk_Employeeid_User, services)
                  VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($insertSQL)) {
        $stmt->bind_param("sssdii", $customer_name, $date_time, $userid, $bill_amount, $userid, $service_id);

        if ($stmt->execute()) {
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
                $to = 'zahihelouzh@gmail.com'; 
                $mail->setFrom('hms2024KTU@gmail.com', 'Your Name'); 
                $mail->addAddress($to);

                // Email content
                $mail->isHTML(false);
                $mail->Subject = 'Report Creation';
                $mail->Body    = "Customer Name: $customer_name\n"
                       . "Date and Time: $date_time\n"
                       . "Bill Amount: $bill_amount\n"
                       . "Service Type: $service_id\n";

                // Send email
                if ($mail->send()) {
                    //echo "Email sent successfully to $to";
                } else {
                    echo "Failed to send email to $to. Error: {$mail->ErrorInfo}";
                }
            } catch (Exception $e) {
                echo "Mailer Error: {$mail->ErrorInfo}";
            }

            echo "Report created successfully!";
        } else {
            echo "Error in insertion: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Unable to prepare statement";
    }
    }else{
        $insertSQL = "INSERT INTO Reports (customer_name, date_time, creator, bill_amount, fk_Employeeid_User)
                  VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($insertSQL)) {
        $stmt->bind_param("sssdi", $customer_name, $date_time, $userid, $bill_amount, $userid);

        if ($stmt->execute()) {
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
                $to = 'zahihelouzh@gmail.com'; 
                $mail->setFrom('hms2024KTU@gmail.com', 'Your Name'); 
                $mail->addAddress($to);

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
            echo "Report created successfully!";
        } else {
            echo "Error in insertion: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Unable to prepare statement";
    }

    }
    

    
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Report Form</title>
    <link rel="stylesheet" href="styless.css">
    <script>
        function toggleReportForm() {
            var x = document.getElementById("reportForm");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        function goBack() {
            window.location.href = 'employee.php'; // This will take the user to the previous page
            // Alternatively, you can direct the user to a specific page:
            // window.location.href = 'somepage.php';
        }
    </script>
</head>
<body>
    <h2>Report Management</h2>
    <button onclick="toggleReportForm()">Add a Report</button>
    <button onclick="location.href='Report-List.php'">View Reports</button>
    <button onclick="goBack()">Back to Home Page</button> <!-- Back button -->
    <div id="reportForm" style="display:none;">
        <h3>Add Report</h3>
        <form action="" method="post">
            <label for="customer_name">Customer Name:</label>
            <select id="customer_name" name="customer_name" required>
                <?php echo $customerOptions; ?>
            </select><br><br>

            <label for="date_time">Date and Time:</label>
            <input type="datetime-local" id="date_time" name="date_time" required><br><br>

            <label for="bill_amount">Bill Amount:</label>
            <input type="number" id="bill_amount" name="bill_amount" required><br><br>

            <label for="service_id">Service Type:</label>
            <select id="service_id" name="service_id" >
                <?php echo $serviceOptions; ?>
            </select><br><br>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
