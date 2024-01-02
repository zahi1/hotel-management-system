<?php
// Include the database connection file (replace 'your_db_connection.php' with your actual file)
include('db.php');

// Start the session (make sure it's started in your application)
session_start();

// Retrieve the list of employees from the database
$query = "SELECT * FROM employees";
$result = mysqli_query($conn, $query);

// Check if there are employees
if ($result && mysqli_num_rows($result) > 0) {
    $employees = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $employees = [];
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link rel="stylesheet" href="styless.css">
</head>
<body>
    <h2>Employee List</h2>

    <!-- Display success message if any -->
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="success-message"><?php echo $_SESSION['success_message']; ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Surname</th>
                <th>Position</th>
                <th>Employment Start Date</th>
                <th>Contact Information</th>
                <th>Salary</th>
                <th>Working Hours</th>
                <th>Office Number</th>
                <th>Contract Date</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?php echo $employee['name']; ?></td>
                    <td><?php echo $employee['surname']; ?></td>
                    <td><?php echo $employee['position']; ?></td>
                    <td><?php echo $employee['employment_start_date']; ?></td>
                    <td><?php echo $employee['contact_information']; ?></td>
                    <td><?php echo $employee['salary']; ?></td>
                    <td><?php echo $employee['working_hours']; ?></td>
                    <td><?php echo $employee['office_number']; ?></td>
                    <td><?php echo $employee['contract_date']; ?></td>
                    <td><?php echo $employee['address']; ?></td>
                    <td>
    <form action="edit-employee.php" method="post">
        <input type="hidden" name="employee_id" value="<?php echo $employee['id']; ?>">
        <button type="submit">Edit</button>
    </form>
</td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form action="logout.php" method="post" class="logout-form">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
