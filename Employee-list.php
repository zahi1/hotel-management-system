<?php
include('db.php');
session_start();

// Check if the form is submitted for editing
if (isset($_POST['edit_employee'])) {
    $id_User = $_POST['edit_employee'];

    // Retrieve employee data for editing
    $edit_query = "SELECT * FROM employees WHERE id_User = ?";
    $stmt = mysqli_prepare($conn, $edit_query);
    mysqli_stmt_bind_param($stmt, "i", $id_User);
    mysqli_stmt_execute($stmt);
    $edit_result = mysqli_stmt_get_result($stmt);

    if ($edit_result) {
        $edit_row = mysqli_fetch_assoc($edit_result);
    }
}

// Check if the form is submitted for removing
if (isset($_POST['remove_employee'])) {
    $id_User = $_POST['remove_employee'];

    // Show confirmation message for deletion
    echo "<script>
            var confirmDelete = confirm('Are you sure you want to delete this employee?');
            if (confirmDelete) {
                window.location.href = 'Employee-list.php?delete_employee=$id_User';
            }
          </script>";
}

// Check if the form is submitted for updating
if (isset($_POST['update_employee'])) {
    $id_User = $_POST['id_User'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $position = $_POST['position'];
    // Add new fields
    $contact_information = $_POST['contact_information'];
    $salary = $_POST['salary'];
    $working_hours = $_POST['working_hours'];
    $office_number = $_POST['office_number'];
    $address = $_POST['address'];

    // Show confirmation message for update
    echo "<script>
            var confirmUpdate = confirm('Are you sure you want to update this employee?');
            if (confirmUpdate) {
                window.location.href = 'Employee-list.php?update_employee=$id_User&name=$name&surname=$surname&position=$position&contact_information=$contact_information&salary=$salary&working_hours=$working_hours&office_number=$office_number&address=$address';
            }
          </script>";
}

// Check if the form is submitted for deleting via confirmation link
// Check if the form is submitted for deleting via confirmation link
if (isset($_GET['delete_employee'])) {
    $id_User = $_GET['delete_employee'];

    // Remove employee from both tables
    $remove_employee_query = "DELETE FROM employees WHERE id_User = ?";
    $remove_user_query = "DELETE FROM users WHERE id_User = ?";
    
    // Using a transaction to ensure both queries are executed or none
    mysqli_autocommit($conn, false);

    $stmt_employee = mysqli_prepare($conn, $remove_employee_query);
    mysqli_stmt_bind_param($stmt_employee, "i", $id_User);

    $stmt_user = mysqli_prepare($conn, $remove_user_query);
    mysqli_stmt_bind_param($stmt_user, "i", $id_User);

    $remove_employee_result = mysqli_stmt_execute($stmt_employee);
    $remove_user_result = mysqli_stmt_execute($stmt_user);

    if ($remove_employee_result && $remove_user_result) {
        mysqli_commit($conn);
    } else {
        mysqli_rollback($conn);
        die("Remove queries failed: " . mysqli_error($conn));
    }

    // Redirect to the same page to refresh the employee list
    header("Location: Employee-list.php");
    exit();
}

// Check if the form is submitted for updating via confirmation link
if (isset($_GET['update_employee'])) {
    $id_User = $_GET['update_employee'];
    $name = $_GET['name'];
    $surname = $_GET['surname'];
    $position = $_GET['position'];
    // Retrieve new fields
    $contact_information = $_GET['contact_information'];
    $salary = $_GET['salary'];
    $working_hours = $_GET['working_hours'];
    $office_number = $_GET['office_number'];
    $address = $_GET['address'];

    // Update employee data in the database
    $update_query = "UPDATE employees SET name=?, surname=?, position=?, contact_information=?, salary=?, working_hours=?, office_number=?, address=? WHERE id_User = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssssssi", $name, $surname, $position, $contact_information, $salary, $working_hours, $office_number, $address, $id_User);
    $update_result = mysqli_stmt_execute($stmt);

    if (!$update_result) {
        die("Update query failed: " . mysqli_error($conn));
    }

    // Redirect to the same page to refresh the employee list
    header("Location: Employee-list.php");
    exit();
}

$query = "SELECT * FROM employees";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link rel="stylesheet" href="stylesss.css">
</head>
<body>

<h2>Employee List</h2>
<a href="admin.php"><button>Admin HomePage</button></a>


<table border="1">
    <tr>
        <th>Name</th>
        <th>Surname</th>
        <th>Position</th>
        <!-- Add new table headers for the additional fields -->
        <th>Contact Information</th>
        <th>Salary</th>
        <th>Working Hours</th>
        <th>Office Number</th>
        <th>Address</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>

    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['surname']}</td>";
        echo "<td>{$row['position']}</td>";
        // Display values for the new fields
        echo "<td>{$row['contact_information']}</td>";
        echo "<td>{$row['salary']}</td>";
        echo "<td>{$row['working_hours']}</td>";
        echo "<td>{$row['office_number']}</td>";
        echo "<td>{$row['address']}</td>";
        echo "<td>
                <form method='post'>
                    <input type='hidden' name='edit_employee' value='{$row['id_User']}'>
                    <button type='submit'>Edit</button>
                </form>
              </td>";
        echo "<td>
                <form method='post'>
                    <input type='hidden' name='remove_employee' value='{$row['id_User']}'>
                    <button type='submit'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }
    ?>

</table>

<?php
if (isset($edit_row)) {
    ?>
    <h2>Edit Employee</h2>
    <form method="post" action="Employee-list.php">
        <input type="hidden" name="id_User" value="<?php echo $edit_row['id_User']; ?>">
        Name: <input type="text" name="name" value="<?php echo $edit_row['name']; ?>" required><br>
        Surname: <input type="text" name="surname" value="<?php echo $edit_row['surname']; ?>" required><br>
        Position: <input type="text" name="position" value="<?php echo $edit_row['position']; ?>" required><br>
        <!-- Add new input fields for the additional fields -->
        Contact Information: <input type="text" name="contact_information" value="<?php echo $edit_row['contact_information']; ?>" required><br>
        Salary: <input type="text" name="salary" value="<?php echo $edit_row['salary']; ?>" required><br>
        Working Hours: <input type="text" name="working_hours" value="<?php echo $edit_row['working_hours']; ?>" required><br>
        Office Number: <input type="text" name="office_number" value="<?php echo $edit_row['office_number']; ?>" required><br>
        Address: <input type="text" name="address" value="<?php echo $edit_row['address']; ?>" required><br>
        <button type="submit" name="update_employee">Update Employee</button>
    </form>
    <?php
}
?>

</body>
</html>

<?php
mysqli_close($conn);
?>
