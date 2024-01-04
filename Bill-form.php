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
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <style>
        header {
            background-size: cover;
            background-position: center;
            min-height: 1000px;
        }
        .image-with-text {
            display: flex!important;
            flex-direction: column;
            align-items: center;
        }
        @media screen and (max-width:670px) {
            header {
                min-height: 500px;
            }
        }
    </style>
</head>
<body>

<header>
    <nav>
        <div class="nav-wrapper blue-grey darken-4">
            <a href="employee.php" class="brand-logo">Employee</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="bill-editing.php">Edit Bill</a></li>
                <li><a href="bill-deleting.php">Delete Bill</a></li>
                <li><a href="Billsending.php">Bill Sending Form</a></li>
                <li><a href="employee.php">Back</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Bill Form</h2>
        <form action="Bill-form.php" method="post" onsubmit="return confirm('Are you sure you want to submit the form?')">
            <label for="bill_amount">Bill Amount:</label>
            <input type="text" id="bill_amount" name="bill_amount" required><br><br>
            
            <label for="date_generated">Date Generated:</label>
            <input type="datetime-local" id="date_generated" name="date_generated" required><br><br>
            
            <div class="input-field">
                <select id="customer_id" name="customer_id" required>
                    <?php echo $customerOptions; ?>
                </select>
                <label for="customer_id">Select Customer:</label>
            </div>

            <label for="remarks">Remarks:</label>
            <input type="text" id="remarks" name="remarks"><br><br> <!-- Added remarks field -->

            <input type="submit" value="Submit" class="btn">
        </form>
    </div>
</header>

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    });
</script>
</body>
</html>
