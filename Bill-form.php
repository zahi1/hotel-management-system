<?php
include 'db.php'; // Database connection file


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $bill_amount = $_POST['bill_amount'];
    $date_generated = $_POST['date_generated'];
    $customer_name = $_POST['customer_name'];
    $employee_id = $_POST['fk_Employeeid_User'];
    $payment_id = $_POST['fk_Paymentid_Payment'];

    // Perform any necessary data validation here...

    // Insert into Bills table
    $sql = "INSERT INTO Bills (bill_amount, date_generated, customer_name, fk_Employeeid_User, fk_Paymentid_Payment)
            VALUES ('$bill_amount', '$date_generated', '$customer_name', '$employee_id', '$payment_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Bill created successfully!";
        // Redirect to another page or perform other actions upon successful submission
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bill Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Bill Form</h2>
    <form action="Bill-form.php" method="post">
        <label for="bill_amount">Bill Amount:</label>
        <input type="text" id="bill_amount" name="bill_amount" required><br><br>
        
        <label for="date_generated">Date Generated:</label>
        <input type="datetime-local" id="date_generated" name="date_generated" required><br><br>
        
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br><br>
        
        <!-- Other necessary fields for employee ID and payment ID -->
        <label for="fk_Employeeid_User">Employee ID:</label>
        <input type="text" id="fk_Employeeid_User" name="fk_Employeeid_User" required><br><br>
        
        <label for="fk_Paymentid_Payment">Payment ID:</label>
        <input type="text" id="fk_Paymentid_Payment" name="fk_Paymentid_Payment"><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
