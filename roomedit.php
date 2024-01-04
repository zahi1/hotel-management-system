<?php
include('db.php');  
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit'])) {
        $room_number = $_POST['room_number'];
        $type = $_POST['type'];
        $price = $_POST['price'];
        $size = $_POST['size'];
        $maximum_occupancy = $_POST['maximum_occupancy'];
        $room_view = $_POST['room_view'];
        $air_conditioning = $_POST['air_conditioning'];
        $coffee_maker = $_POST['coffee_maker'];
        $status = $_POST['status'];

        $updateQuery = "UPDATE rooms SET type='$type', price='$price', size='$size', maximum_occupancy='$maximum_occupancy', room_view='$room_view', air_conditioning='$air_conditioning', coffee_maker='$coffee_maker', status='$status' WHERE room_number='$room_number'";
        
        if ($conn->query($updateQuery) === TRUE) {
            header("Location: roomform.php");
            exit();
        } else {
            echo "Error updating room: " . $conn->error;
        }
    }

    if (isset($_POST['delete'])) {
        $room_numberToDelete = $_POST['room_number'];
        $deleteQuery = "DELETE FROM rooms WHERE room_number = '$room_numberToDelete'";
        
        if ($conn->query($deleteQuery) === TRUE) {
            header("Location: Location: roomform.php");
            exit();
        } else {
            echo "Error deleting room: " . $conn->error;
        }
    }

    // ... other parts of your code ...

if (isset($_POST['addRoom'])) {
$start_date = $_POST['start_date'];
$room_number = $_POST['room_number'];
$type = $_POST['type'];
$price = $_POST['price'];
$size = $_POST['size'];
$maximum_occupancy = $_POST['maximum_occupancy'];
$room_view = $_POST['room_view'];
$air_conditioning = $_POST['air_conditioning'];
$coffee_maker = $_POST['coffee_maker'];
$status = $_POST['status'];
$image = $_POST['image'];

// Retrieve admin_id from the session
if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Insert the new room record using the admin_id from the session
    $addQuery = "INSERT INTO rooms (fk_Administratorid_User, start_date, room_number, type, price, size, maximum_occupancy, room_view, air_conditioning, coffee_maker, status) VALUES ('$admin_id', '$start_date', '$room_number', '$type', '$price', '$size', '$maximum_occupancy', '$room_view', '$air_conditioning', '$coffee_maker', '$status')";
    
    // Execute the SQL query
    if ($conn->query($addQuery) === TRUE) {
        echo "New room added successfully.";
        header("Location: Location: roomform.php");
            exit();
    } else {
        echo "Error adding room: " . $conn->error;
    }
}
}

// ... rest of your PHP code ...

// ... rest of your PHP code ...

}
?>