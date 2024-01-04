<?php
session_start();

$userid = $_SESSION['user_id'];

include 'db.php'; 

// Fetch unpaid bills from the database with customer information
$sql = "SELECT b.id_Bill, b.date_generated, b.bill_amount, c.id_User AS customer_id, CONCAT(c.id_User, '-', c.firstname) AS customer_name, b.fk_Employeeid_User
        FROM Bills b 
        INNER JOIN Customers c ON b.customer_id = c.id_User
        WHERE b.status = 'unpaid'"; // Condition added to fetch only unpaid bills

$result = $conn->query($sql);

$billsData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $billsData[] = $row;
    }
} else {
    echo "No bills !";
    exit; // Exit if no unpaid bills are found
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bill_id'], $_POST['date_generated'], $_POST['bill_amount'])) {
    // Retrieve modified form data
    $updated_bill_id = $_POST['bill_id'];
    $updated_date_generated = $_POST['date_generated'];
    $updated_bill_amount = $_POST['bill_amount'];

    // Perform any necessary data validation here...

    // Update the specific bill in the database
    $update_sql = "UPDATE Bills 
                    SET date_generated = '$updated_date_generated', 
                        bill_amount = '$updated_bill_amount'
                    WHERE id_Bill = $updated_bill_id AND fk_Employeeid_User = $userid";

    if ($conn->query($update_sql) === TRUE) {
        header("Location: Bill-form.php");
        sleep(1);
        exit;
    } else {
        echo "Error updating bill: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Unpaid Bills</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            </div>
        </nav>

        <div class="container">
            <h2>Edit Bills</h2>
            <table class="striped">
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Date Generated</th>
                        <th>Bill Amount</th>
                        <th>Customer Details</th>
                        <th>Modify</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($billsData as $bill): ?>
                        <tr>
                            <form action="bill-editing.php" method="post" onsubmit="return confirm('Are you sure you want to update this bill?')">
                                <td><?php echo $bill['id_Bill']; ?></td>
                                <td>
                                    <input type="hidden" name="bill_id" value="<?php echo $bill['id_Bill']; ?>">
                                    <input type="datetime-local" name="date_generated" value="<?php echo $bill['date_generated']; ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="bill_amount" value="<?php echo $bill['bill_amount']; ?>" required>
                                </td>
                                <td>
                                    <input type="hidden" name="customer_id" value="<?php echo $bill['customer_id']; ?>">
                                    <span>ID: <?php echo $bill['customer_id']; ?></span><br>
                                    <span>Name: <?php echo substr($bill['customer_name'], strpos($bill['customer_name'], '-') + 1); ?></span>
                                </td>
                                <td>
                                    <?php if ($userid == $bill['fk_Employeeid_User']): ?>
                                        <button type="submit" class="btn">Update</button>
                                    <?php else: ?>
                                        <span>Not authorized</span>
                                    <?php endif; ?>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form action="Bill-form.php">
                <button type="submit" class="btn">Back</button>
            </form>
        </div>
    </header>

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
</body>
</html>
