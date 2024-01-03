<?php
session_start();
$userid = $_SESSION['user_id'];
include 'db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bill_id'], $_POST['amount'])) {
    $bill_id = $_POST['bill_id'];
    $amount = $_POST['amount'];

    // Store the payment details in the database
    $insert_sql = "INSERT INTO payments (amount, date_time, fk_Customerid_User) VALUES ('$amount', NOW(), '$userid')";
    if ($conn->query($insert_sql) === TRUE) {
        // Proceed to payment processing using Stripe
        header("Location: checkout.php?bill_id=$bill_id&amount=$amount");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all bills of the logged-in customer
$sql = "SELECT * FROM Bills WHERE customer_id = $userid";
$result = $conn->query($sql);

$billsData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $billsData[] = $row;
    }
} else {
    echo "No bills found for this customer.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Bills</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Customer Bills</h2>
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
