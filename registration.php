<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="styles.css">
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
        <label for="payment_method">Payment Method:</label>
        <input type="text" id="payment_method" name="payment_method" required><br><br>
        <input type="submit" value="Register">
    </form>

    <?php
    include 'db.php'; // Database connection file

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $billing_address = $_POST['billing_address'];
        $payment_method = $_POST['payment_method'];
        $loyalty_points = 0; // Initial loyalty points for a new user
        $membership_status = 'Regular'; // Initial membership status for a new user

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into Users table
        $sql = "INSERT INTO Users (Login, Password) VALUES ('$username', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            $user_id = $conn->insert_id;

            // Insert into Customers table
            $sql = "INSERT INTO Customers (firstname, lastname, email_address, phone_number, billing_address, payment_method, loyalty_points, membership_status, id_User) 
                    VALUES ('$username', '', '$email', '$phone_number', '$billing_address', '$payment_method', '$loyalty_points', '$membership_status', '$user_id')";
            if ($conn->query($sql) === TRUE) {
                echo "Registration successful!";
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
