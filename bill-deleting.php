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
    echo "No bills!.";
    exit; // Exit if no unpaid bills are found
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bill_id'])) {
    $delete_bill_id = $_POST['bill_id'];

    // Check if the user is authorized to delete this bill
    $authorization_sql = "SELECT * FROM Bills WHERE id_Bill = $delete_bill_id AND fk_Employeeid_User = $userid AND status = 'unpaid'";
    $authorization_result = $conn->query($authorization_sql);

    if ($authorization_result && $authorization_result->num_rows > 0) {
        // User is authorized to delete this unpaid bill
        if (isset($_POST['confirmed']) && $_POST['confirmed'] === 'true') {
            $delete_sql = "DELETE FROM Bills WHERE id_Bill = $delete_bill_id";
        
            if ($conn->query($delete_sql) === TRUE) {
                header("Location: Bill-form.php");
                sleep(1);
                exit; // Exit the script after redirection
            } else {
                echo "Error deleting bill: " . $conn->error;
            }
        }
    } else {
        echo "Not authorized to delete this bill or it's already paid";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Unpaid Bills</title>
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
    <script>
        function confirmDelete(billId) {
            if (confirm("Are you sure you want to delete this bill?")) {
                var form = document.getElementById("deleteForm_" + billId);
                form.elements["confirmed"].value = "true";
                form.submit();
            }
        }
    </script>
</head>
<body>
    <header>
        <nav>
            <div class="nav-wrapper blue-grey darken-4">
                <a href="employee.php" class="brand-logo">Employee</a>
            </div>
        </nav>

        <div class="container">
            <h2>Remove Unpaid Bills</h2>
            <table class="striped">
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Date Generated</th>
                        <th>Bill Amount</th>
                        <th>Customer Details</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($billsData as $bill): ?>
                        <tr>
                            <form id="deleteForm_<?php echo $bill['id_Bill']; ?>" action="bill-deleting.php" method="post">
                                <td><?php echo $bill['id_Bill']; ?></td>
                                <td><?php echo $bill['date_generated']; ?></td>
                                <td><?php echo $bill['bill_amount']; ?></td>
                                <td>
                                    <input type="hidden" name="customer_id" value="<?php echo $bill['customer_id']; ?>">
                                    <span>ID: <?php echo $bill['customer_id']; ?></span><br>
                                    <span>Name: <?php echo substr($bill['customer_name'], strpos($bill['customer_name'], '-') + 1); ?></span>
                                </td>
                                <td>
                                    <?php if ($userid == $bill['fk_Employeeid_User']): ?>
                                        <input type="hidden" name="bill_id" value="<?php echo $bill['id_Bill']; ?>">
                                        <input type="hidden" name="confirmed" value="false">
                                        <button type="button" onclick="confirmDelete(<?php echo $bill['id_Bill']; ?>)" class="btn red">Remove</button>
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
