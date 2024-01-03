<?php
session_start();
$userid = $_SESSION['user_id'];

include 'db.php'; // Database connection file

// Fetch all bills from the database with customer information
$sql = "SELECT b.id_Bill, b.date_generated, b.bill_amount, c.id_User AS customer_id, CONCAT(c.id_User, '-', c.firstname) AS customer_name, b.fk_Employeeid_User
        FROM Bills b 
        INNER JOIN Customers c ON b.customer_id = c.id_User";
$result = $conn->query($sql);

$billsData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $billsData[] = $row;
    }
} else {
    echo "No bills found.";
    exit; // Exit if no bills are found
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bill_id'])) {
    $delete_bill_id = $_POST['bill_id'];

    // Check if the user is authorized to delete this bill
    $authorization_sql = "SELECT * FROM Bills WHERE id_Bill = $delete_bill_id AND fk_Employeeid_User = $userid";
    $authorization_result = $conn->query($authorization_sql);

    if ($authorization_result && $authorization_result->num_rows > 0) {
        // User is authorized to delete this bill
        $delete_sql = "DELETE FROM Bills WHERE id_Bill = $delete_bill_id";
        
        if ($conn->query($delete_sql) === TRUE) {
            header("Location: Bill-form.php");
            exit; // Exit the script after redirection
        } else {
            echo "Error deleting bill: " . $conn->error;
        }
    } else {
        echo "Not authorized to delete this bill";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Bills</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Delete Bills</h2>
    <table>
        <tr>
            <th>Bill ID</th>
            <th>Date Generated</th>
            <th>Bill Amount</th>
            <th>Customer Details</th>
            <th>Delete</th>
        </tr>
        <?php foreach ($billsData as $bill): ?>
            <tr>
                <form action="bill-deleting.php" method="post">
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
                            <input type="submit" value="Delete">
                        <?php else: ?>
                            Not authorized
                        <?php endif; ?>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="Bill-form.php">Back to Bill form</a>
</body>
</html>
