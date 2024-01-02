<?php
session_start();

$userid = $_SESSION['user_id'];

include 'db.php'; // Database connection file



// Fetch list of customers
$sql = "SELECT id_User, CONCAT(id_User, '-', firstname) AS display_name FROM Customers";
$result = $conn->query($sql);

$customerOptions = '';
while ($row = $result->fetch_assoc()) {
    $customerId = $row['id_User'];
    $displayName = $row['display_name'];
    $customerOptions .= "<option value='$customerId'>$displayName</option>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $bill_amount = $_POST['bill_amount'];
    $date_generated = $_POST['date_generated'];
    $customer_id = $_POST['customer_id']; // Updated field name
    $employee_id = $userid;
 

    // Perform any necessary data validation here...

    // Insert into Bills table
    $sql = "INSERT INTO Bills (bill_amount, date_generated, customer_id, fk_Employeeid_User)
            VALUES ('$bill_amount', '$date_generated', '$customer_id', '$employee_id')";
    
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
        
        <label for="customer_id">Select Customer:</label>
        <select id="customer_id" name="customer_id" required>
            <?php echo $customerOptions; ?>
        </select><br><br>  
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
