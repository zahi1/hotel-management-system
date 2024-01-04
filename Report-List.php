<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

// Handle POST request for updating or deleting a report
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $reportId = $_POST['id_Report'];


   

    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // SQL to delete report
        $deleteSQL = "DELETE FROM Reports WHERE id_Report = $reportId";
        if ($conn->query($deleteSQL) === TRUE) {
            echo "<p>Report deleted successfully!</p>";
        } else {
            echo "<p>Error deleting report: " . $conn->error . "</p>";
        }
    } else {
        // Update logic 
        $customer_name = $conn->real_escape_string($_POST['customer_name']);
        $date_time = $_POST['date_time']; // Format and sanitize
        $bill_amount = $_POST['bill_amount'];
        $services = $conn->real_escape_string($_POST['services']);

        $updateSQL = "UPDATE Reports SET customer_name = '$customer_name', date_time = '$date_time', bill_amount = '$bill_amount', services = '$services' 
        WHERE id_Report = $reportId";

        if ($conn->query($updateSQL) === TRUE) {
            echo "<p>Report updated successfully!</p>";
        } else {
            echo "<p>Error updating report: " . $conn->error . "</p>";
        }
        
}
    }


// Fetch all reports
$query = "SELECT * FROM Reports"; // Adjust the query as needed
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Reports</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function showEditForm(id) {
            var form = document.getElementById('editForm-' + id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function confirmUpdate() {
            return confirm("Are you sure you want to update this report?");
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this report?");
        }
    </script>
</head>
<body>
    <h2>All Reports</h2>
    <table>
        <tr>
            <th>Customer Name</th>
            <th>Date Time</th>
            <th>Bill Amount</th>
            <th>Services</th>
            <th>Actions</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            $reportId = $row['id_Report'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date_time']) . "</td>";
            echo "<td>" . htmlspecialchars($row['bill_amount']) . "</td>";
            echo "<td>" . htmlspecialchars($row['services']) . "</td>";
            echo "<td>";
            echo "<button onclick='showEditForm($reportId)'>Edit</button>";
            echo "<form method='post' action='Report-List.php' onsubmit='return confirmDelete()' style='display:inline;'>";
            echo "<input type='hidden' name='id_Report' value='$reportId'>";
            echo "<input type='hidden' name='action' value='delete'>";
            echo "<input type='submit' value='Remove'>";

            echo "</form>";
            echo '<form method="POST" action="Report-List.php">';
            echo "<input type='hidden' name='id_Report' value='$reportId'>";

           
            echo "</td>";
            echo "</tr>";

            // Inline Edit Form
            echo "<tr id='editForm-$reportId' style='display:none;'>";
            echo "<td colspan='5'>";
            echo "<form action='Report-List.php' method='post' onsubmit='return confirmUpdate()'>";
            echo "<input type='hidden' name='id_Report' value='$reportId'>";
            echo "<input type='text' name='customer_name' value='" . htmlspecialchars($row['customer_name']) . "'>";
            echo "<input type='datetime-local' name='date_time' value='" . htmlspecialchars($row['date_time']) . "'>";
            echo "<input type='number' name='bill_amount' value='" . htmlspecialchars($row['bill_amount']) . "'>";
            echo "<input type='text' name='services' value='" . htmlspecialchars($row['services']) . "'>";
            echo "<input type='submit' value='Update'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";

        }
        ?>
    </table>
    <?php $conn->close(); ?>
</body>
</html>
