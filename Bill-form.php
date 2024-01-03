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
    $customer_id = $_POST['customer_id'];
    $employee_id = $userid;
    $remarks = $_POST['remarks']; // Added remarks field

    // Perform any necessary data validation here...

    // Insert into Bills table
    $sql = "INSERT INTO Bills (bill_amount, date_generated, customer_id, fk_Employeeid_User, remarks)
            VALUES ('$bill_amount', '$date_generated', '$customer_id', '$employee_id', '$remarks')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Bill created successfully!";
        // Redirect to another page or perform other actions upon successful submission
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} // No need for an 'else' block for request method check
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bill Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <h2>Bill Form</h2>
    <form action="Bill-form.php" method="post" onsubmit="return confirm('Are you sure you want to submit the form?')">
        <label for="bill_amount">Bill Amount:</label>
        <input type="text" id="bill_amount" name="bill_amount" required><br><br>
        
        <label for="date_generated">Date Generated:</label>
        <input type="datetime-local" id="date_generated" name="date_generated" required><br><br>
        
        <label for="customer_id">Select Customer:</label>
        <select id="customer_id" name="customer_id" required>
            <?php echo $customerOptions; ?>
        </select><br><br>    

        <label for="remarks">Remarks:</label>
        <input type="text" id="remarks" name="remarks"><br><br> <!-- Added remarks field -->

        <input type="submit" value="Submit">
    </form>
    
    <form action="bill-editing.php">
        <button type="submit">Edit Bill</button> <!-- Edit button -->
    </form>
    <form action="bill-deleting.php">
        <button type="submit">Delete Bill</button> <!-- Delete button -->
    </form>
    <form action="employee.php">
        <button type="submit">Back</button> <!-- Back button -->
    </form>
    <br><br>
    <button onclick="redirectToSend()">Bill Sending Form</button>
    <script>
        function redirectToSend() {
            window.location.href = 'Billsending.php';
        }
    </script>

</body>
</html>
