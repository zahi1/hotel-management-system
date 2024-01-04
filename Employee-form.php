<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor1/autoload.php';

// Include the database connection file (replace 'your_db_connection.php' with your actual file)
include('db.php');

// Start the session (make sure it's started in your application)
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $position = $_POST['position'];
    $employment_start_date = $_POST['employment_start_date'];
    $contact_information = $_POST['contact_information'];
    $salary = $_POST['salary'];
    $working_hours = $_POST['working_hours'];
    $office_number = $_POST['office_number'];
    $contract_date = $_POST['contract_date'];
    $address = $_POST['address'];
    $employee_username = $_POST['employee_username'];
    $employee_password = $_POST['employee_password'];

    // Retrieve the ID of the logged-in administrator from the session
    $fk_Administratorid_User = $_SESSION['user_id'];

    // SQL query to insert new user data into the users table
    $insertUserQuery = "INSERT INTO users (Login, Password) VALUES ('$employee_username', '$employee_password')";

    // Perform the query
    if (mysqli_query($conn, $insertUserQuery)) {
        // Retrieve the auto-incremented id_User for the new user
        $id_User = mysqli_insert_id($conn);

        // SQL query to insert employee data into the employees table
        $insertEmployeeQuery = "INSERT INTO employees (name, surname, position, employment_start_date, contact_information, salary, working_hours, office_number, contract_date, address, fk_Administratorid_User, id_User) 
                                VALUES ('$name', '$surname', '$position', '$employment_start_date', '$contact_information', '$salary', '$working_hours', '$office_number', '$contract_date', '$address', '$fk_Administratorid_User', '$id_User')";

        // Perform the query
        if (mysqli_query($conn, $insertEmployeeQuery)) {
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
                $mail->setFrom('hms2024KTU@gmail.com', 'HMS'); 
                $mail->addAddress($contact_information);

                // Email content
                $mail->isHTML(false); 
                $mail->Subject = 'Joining Credentials';
                //$mail->Body = 'Dear Employee,WELCOME!!.';
                //$row = $result->fetch_assoc();
                $Credentials = "Username: " . $employee_username. "\n";
                $Credentials .= "Password: " .$employee_password  . "\n";
    
                $mail->Body = 'Dear Employee, WELCOME!!' . $Credentials; // Your email message
                // Send email
                if ($mail->send()) {
                    //echo "Email sent successfully to $to";
                } else {
                    echo "Failed to send email to $to. Error: {$mail->ErrorInfo}";
                }
            } catch (Exception $e) {
                echo "Mailer Error: {$mail->ErrorInfo}";
            }
            // Employee added successfully
            header('Location: Employee-form.php?success=1'); // Redirect to employee form page with success flag
            exit();
        } else {
            // Error in query execution for inserting employee
            $_SESSION['error_message'] = 'Error adding employee: ' . mysqli_error($conn);
            header('Location: Employee-form.php'); // Redirect back to the form
            exit();
        }
    } else {
        // Error in query execution for inserting new user
        $_SESSION['error_message'] = 'Error adding employee: ' . mysqli_error($conn);
        header('Location: Employee-form.php'); // Redirect back to the form
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <link rel="stylesheet" href="styless.css">
</head>
<body>
    <h2>Add Employee</h2>
    <a href="admin.php"><button>Admin HomePage</button></a>

    <!-- Display success message if indicated by URL parameter -->
    <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="success-message">Employee added successfully.</div>
    <?php endif; ?>

    <!-- Display error messages if any -->
    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form action="Employee-form.php" method="post" onsubmit="return confirmAddEmployee()">
         <!-- Form fields go here -->
         <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="surname">Surname:</label>
        <input type="text" name="surname" required><br>

        <label for="position">Position:</label>
        <input type="text" name="position" required><br>

        <label for="employment_start_date">Employment Start Date:</label>
        <input type="date" name="employment_start_date" required><br>

        <label for="contact_information">Contact Information:</label>
        <input type="text" name="contact_information" required><br>

        <label for="salary">Salary:</label>
        <input type="text" name="salary" required><br>

        <label for="working_hours">Working Hours:</label>
        <input type="text" name="working_hours"><br>

        <label for="office_number">Office Number:</label>
        <input type="text" name="office_number"><br>

        <label for="contract_date">Contract Date:</label>
        <input type="date" name="contract_date"><br>

        <label for="address">Address:</label>
        <input type="text" name="address"><br>

        <label for="employee_username">Employee Username:</label>
        <input type="text" name="employee_username" required><br>

        <label for="employee_password">Employee Password:</label>
        <input type="password" name="employee_password" required><br>

        <button type="submit">Add Employee</button>
    </form>

    <script>
        function confirmAddEmployee() {
            return confirm("Are you sure you want to add this employee?");
        }
    </script>
</body>
</html>
