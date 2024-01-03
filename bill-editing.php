<?php
session_start();

$userid = $_SESSION['user_id'];

include 'db.php'; // Database connection file

// Fetch all bills from the database
$sql = "SELECT * FROM Bills";
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
                    WHERE id_Bill = $updated_bill_id";

    if ($conn->query($update_sql) === TRUE) {
        header("Location: Bill-form.php");
        exit;
    } else {
        echo "Error updating bill: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Bills</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Edit Bills</h2>
    <table>
        <tr>
            <th>Bill ID</th>
            <th>Date Generated</th>
            <th>Bill Amount</th>
            <th>Modify</th>
        </tr>
        <?php foreach ($billsData as $bill): ?>
            <tr>
                <form action="bill-editing.php" method="post">
                    <td><?php echo $bill['id_Bill']; ?></td>
                    <td>
                        <input type="hidden" name="bill_id" value="<?php echo $bill['id_Bill']; ?>">
                        <input type="datetime-local" name="date_generated" value="<?php echo $bill['date_generated']; ?>" required>
                    </td>
                    <td>
                        <input type="text" name="bill_amount" value="<?php echo $bill['bill_amount']; ?>" required>
                    </td>
                    <td>
                        <?php if ($userid == $bill['fk_Employeeid_User']): ?>
                            <input type="submit" value="Update">
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
