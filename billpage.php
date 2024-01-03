<?php
session_start();
$userid = $_SESSION['user_id'];
include 'db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bill_id'], $_POST['amount'])) {
    $bill_id = $_POST['bill_id'];
    $amount = $_POST['amount'];

    // Store the payment details in the database
    $insert_sql = "INSERT INTO payments (amount, date_time, fk_Customerid_User, status) VALUES ('$amount', NOW(), '$userid', 'paid')";
    if ($conn->query($insert_sql) === TRUE) {
        // Update status in bills table
        $update_sql = "UPDATE bills SET status = 'paid' WHERE id_Bill = $bill_id";
        if ($conn->query($update_sql) === TRUE) {
            // Proceed to payment processing using Stripe
            header("Location: checkout.php?bill_id=$bill_id&amount=$amount");
            exit();
        } else {
            echo "Error updating status: " . $conn->error;
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch unpaid bills of the logged-in customer
$sql = "SELECT * FROM bills WHERE customer_id = $userid AND status = 'unpaid'";
$result = $conn->query($sql);

$billsData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $billsData[] = $row;
    }
} else {
    echo "No unpaid bills found for this customer.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Unpaid Bills</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Customer Unpaid Bills</h2>
    <table>
        <tr>
            <th>Bill ID</th>
            <th>Amount</th>
            <th>Date Generated</th>
            <th>Pay Bill</th>
        </tr>
        <?php foreach ($billsData as $bill): ?>
            <tr>
                <td><?php echo $bill['id_Bill']; ?></td>
                <td><?php echo $bill['bill_amount']; ?></td>
                <td><?php echo $bill['date_generated']; ?></td>
                <td>
                    <form action="billpage.php" method="post">
                        <input type="hidden" name="bill_id" value="<?php echo $bill['id_Bill']; ?>">
                        <input type="hidden" name="amount" value="<?php echo $bill['bill_amount']; ?>">
                        <input type="submit" value="Pay Bill">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
