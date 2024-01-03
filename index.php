<?php
session_start();
include 'db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $_SESSION['username'] =$username;

    // Check if the username exists in any of the tables based on id_User
    $sql = "(SELECT 'admin' AS role, id_User FROM Administrators WHERE id_User = 
                (SELECT id_User FROM Users WHERE Login = '$username'))
             UNION
             (SELECT 'customer' AS role, id_User FROM Customers WHERE id_User = 
                (SELECT id_User FROM Users WHERE Login = '$username'))
             UNION
             (SELECT 'employee' AS role, id_User FROM Employees WHERE id_User = 
                (SELECT id_User FROM Users WHERE Login = '$username'))";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Username found, authenticate the user and redirect based on the role
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id_User'];
        $_SESSION['role'] = $row['role'];

        switch ($_SESSION['role']) {
            case 'admin':
                header("Location: admin.php");
                break;
            case 'customer':
                header("Location: customer.php");
                break;
            case 'employee':
                header("Location: employee.php");
                break;
            default:
                echo "Unknown role";
                break;
        }
    } else {
        echo "User does not exist";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
        <a href="registration.php">Register</a>
    </form>
</body>
</html>
