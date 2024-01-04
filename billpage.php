<?php
session_start();
$userid = $_SESSION['user_id'];
include 'db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bill_id'], $_POST['amount'])) {
    $bill_id = $_POST['bill_id'];
    $amount = $_POST['amount'];
    $_SESSION['bill_id'] = $bill_id;

    // Proceed to payment processing using Stripe
    header("Location: checkout.php?bill_id=$bill_id&amount=$amount");
    exit();
}

// Fetch unpaid bills of the logged-in customer
$sql = "SELECT * FROM bills WHERE customer_id = $userid AND status = 'unpaid'";
$result = $conn->query($sql);

$billsData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $billsData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Bills</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function confirmPayment() {
            return confirm('Are you sure you want to pay this bill?');
        }
    </script>
</head>
<body>
    <?php if (!empty($billsData)): ?>
        <h2>Unpaid Bills</h2>
        <table>
            <tr>
                <th>Bill ID</th>
                <th>Amount</th>
                <th>Date Generated</th>
                <th>Remarks</th>
                <th>Pay Bill</th>
            </tr>
            <?php foreach ($billsData as $bill): ?>
                <tr>
                    <td><?php echo $bill['id_Bill']; ?></td>
                    <td><?php echo $bill['bill_amount']; ?></td>
                    <td><?php echo $bill['date_generated']; ?></td>
                    <td><?php echo $bill['remarks']; ?></td>
                    <td>
                        <form action="billpage.php" method="post" onsubmit="return confirmPayment()">
                            <input type="hidden" name="bill_id" value="<?php echo $bill['id_Bill']; ?>">
                            <input type="hidden" name="amount" value="<?php echo $bill['bill_amount']; ?>">
                            <input type="submit" value="Pay Bill">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No unpaid bills!</p>
    <?php endif; ?>

    <form action="customer.php">
        <button type="submit">Back</button>
    </form>
</body>
</html>
