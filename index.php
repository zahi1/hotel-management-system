<?php
session_start();
include 'db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $_SESSION['username'] =$username;

    // Check if the username exists in any of the tables based on id_User
    $sql = "(SELECT 'admin' AS role, id_User,email FROM Administrators WHERE id_User = 
                (SELECT id_User FROM Users WHERE Login = '$username'))
             UNION
             (SELECT 'customer' AS role, id_User,email_address AS email FROM Customers WHERE id_User = 
                (SELECT id_User FROM Users WHERE Login = '$username'))
             UNION
             (SELECT 'employee' AS role, id_User,contact_information AS email FROM Employees WHERE id_User = 
                (SELECT id_User FROM Users WHERE Login = '$username'))";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Username found, authenticate the user and redirect based on the role
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id_User'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['email'] = $row['email'];

        switch ($_SESSION['role']) {
            case 'admin':

                sleep(1);
                header("Location: admin.php");
                break;
            case 'customer':
                sleep(1);
                header("Location: customer.php");
                break;
            case 'employee':
                sleep(1);
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
    <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- font awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    </head>
<body>
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="username">Username</label>
        <div class="input-field col s12">
            <input type="text" name="username" required />
        </div>            
        <label for="password">Password</label>
        <div class="input-field col s12">
            <input type="text" name="password" required /><br>
        </div>
        <input type="submit" class="waves-effect waves-light btn" value="Login">
        <a href="registration.php" class="waves-effect waves-light btn">Register</a>
    </form>
</body>
</html>
