<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- font awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
</head>
<body>
    <h2>Register</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required><br><br>
        <label for="billing_address">Billing Address:</label>
        <input type="text" id="billing_address" name="billing_address" required><br><br>
        <input type="submit" value="Register">
    </form>

    <?php
    include 'db.php'; // Database connection file
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor1/autoload.php';


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $billing_address = $_POST['billing_address'];

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into Users table
        $sql = "INSERT INTO Users (Login, Password) VALUES ('$username', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            $user_id = $conn->insert_id;

            // Insert into Customers table
            $sql = "INSERT INTO Customers (firstname, lastname, email_address, phone_number, billing_address,  id_User) 
                    VALUES ('$username', '', '$email', '$phone_number', '$billing_address', '$user_id')";
            if ($conn->query($sql) === TRUE) {
                echo "Registration successful!";
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
                    //$to = 'vimalrajvarunjosh@gmail.com'; 
                    $mail->setFrom('hms2024KTU@gmail.com', 'Your Name'); 
                    $mail->addAddress($email);
    
                    // Email content
                    $mail->isHTML(false); 
                    $mail->Subject = 'Registration Confirmation';
                    $mail->Body = 'Dear Customer, Your have successfully registered. Continue with the login process.';
    
                    // Send email
                    if ($mail->send()) {
                        //echo "Email sent successfully to $to";
                    } else {
                        echo "Failed to send email to $to. Error: {$mail->ErrorInfo}";
                    }
                } catch (Exception $e) {
                    echo "Mailer Error: {$mail->ErrorInfo}";
                }
                // Redirect to dashboard after successful registration
                 header("Location: index.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>

</body>
</html>
