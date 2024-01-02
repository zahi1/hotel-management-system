<?php
// Include the database connection file (replace 'your_db_connection.php' with your actual file)
include('db.php');

// Start the session (make sure it's started in your application)
session_start();

// Check if the employee_id is set in the POST request
if (isset($_POST['id_User'])) {
    // Retrieve the employee_id from the POST request
    $employee_id = $_POST['id_User'];

    // Use the $employee_id to fetch the specific employee details from the database
    $query = "SELECT * FROM employees WHERE id = $employee_id";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful and if there is a matching employee
    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);

        // Now, you have the details of the selected employee in the $employee variable
        // You can use this data to populate the form for editing

        // Close the database connection
        mysqli_close($conn);

        // Your HTML and form for editing go here
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Employee</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <h2>Edit Employee</h2>

            <!-- Your form for editing the employee goes here -->
            <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $employee['name']; ?>" required><br>

        <label for="surname">Surname:</label>
        <input type="text" name="surname" value="<?php echo $employee['surname']; ?>" required><br>

        <label for="position">Position:</label>
        <input type="text" name="position" value="<?php echo $employee['position']; ?>" required><br>

        <label for="contact_information">Contact Information:</label>
        <input type="text" name="contact_information" value="<?php echo $employee['contact_information']; ?>" required><br>


        </body>
        </html>
        <?php
    } else {
        // Handle the case where the employee with the given id was not found
        echo "Employee not found.";
    }
} else {
    // Handle the case where employee_id is not set in the POST request
    echo "Invalid request.";
}
?>
