<?php
include 'db.php';  // Including the database connection file

// Fetch rooms from the database
$query = "SELECT * FROM rooms";
$result = mysqli_query($conn, $query);

// Check if rooms are fetched successfully
if ($result) {
    $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $rooms = [];  // Empty array if no rooms are fetched
}

// Assuming you have a mechanism to determine the selected room
$selectedRoom = isset($_POST['selectedRoom']) ? $_POST['selectedRoom'] : '';
if (isset($_POST['remove'])) {
    $selectedRoomNumber = $_POST['selectedRoom'];
    $deleteQuery = "DELETE FROM rooms WHERE room_number = '$selectedRoomNumber'";
    
    if (mysqli_query($conn, $deleteQuery)) {
        // Success message or any other action
        echo "Room removed successfully.";
    } else {
        // Error message
        echo "Error: " . mysqli_error($conn);
    }
}
if (isset($_POST['unassign'])) {
    $selectedRoomNumber = $_POST['selectedRoom'];
    $updateStatusQuery = "UPDATE rooms SET status = 'available' WHERE room_number = '$selectedRoomNumber'";
    
    if (mysqli_query($conn, $updateStatusQuery)) {
        // Success message or any other action
        echo "Room unassigned successfully.";
    } else {
        // Error message
        echo "Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Room</title>
</head>
<body>
    <h1>Select Room</h1>

    <form action="" method="post">
        <ul>
            <?php foreach ($rooms as $room): ?>
                <li>
                    <input type="radio" name="selectedRoom" value="<?php echo $room['room_number']; ?>"
                        <?php echo ($selectedRoom == $room['room_number']) ? 'checked' : ''; ?>>
                    <?php 
                        echo $room['room_number'] . ' - ' . $room['type'] . ' - ' . $room['price'] . ' - ' . 
                             $room['size'] . ' - ' . $room['maximum_occupancy'] . ' - ' . $room['room_view'] . 
                             ' - ' . $room['air_conditioning'] . ' - ' . $room['coffee_maker'] . ' - ' . 
                             $room['status']; 
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Buttons for actions -->
        <input type="button" name="assign" value="Assign Room" onclick="redirectToRoomForm()">
        <input type="submit" name="remove" value="Remove Room" onclick="return confirm('Are you sure you want to remove this room?');">
        <input type="submit" name="unassign" value="Unassign Room" onclick="return confirmUnassign();">
        <input type="button" name="update" value="Update Room" onclick="redirectToRoomForm()">
        <input type="submit" name="similar" value="Show Similar Room">
        <input type="submit" name="clear" value="Clear Selection">
    </form>
    

    <!-- Include any other scripts or styles if necessary -->
    <script>
    function redirectToRoomForm() {
        // Assuming you want to redirect to room_form.php when Assign Room is clicked
        window.location.href = 'roomform.php';
    }
    function confirmUnassign() {
    return confirm('Are you sure you want to unassign this room?');
}

</script>

</body>
</html>
