<?php
session_start();

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
    $service_id = $_POST['service_id'];

    $insertSQL = "INSERT INTO Reports (customer_name, date_time, creator, bill_amount, fk_Employeeid_User, services)
                  VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($insertSQL)) {
        $stmt->bind_param("sssdii", $customer_name, $date_time, $userid, $bill_amount, $userid, $service_id);

        if ($stmt->execute()) {
            echo "Report created successfully!";
        } else {
            echo "Error in insertion: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Unable to prepare statement";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Report Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Report Form</h2>
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
        <select id="service_id" name="service_id" required>
            <?php echo $serviceOptions; ?>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
