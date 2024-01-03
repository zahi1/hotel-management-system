<?php
include 'db.php';  // Including the database connection file

// Fetch rooms from the database
$query = "SELECT * FROM rooms";
$result = mysqli_query($conn, $query);

if ($result) {
    $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $rooms = [];
}

$selectedRoom = isset($_POST['selectedRoom']) ? $_POST['selectedRoom'] : '';
$similarRooms = [];

if (isset($_POST['similar'])) {
    $selectedRoomView = '';
    foreach ($rooms as $room) {
        if ($room['room_number'] == $selectedRoom) {
            $selectedRoomView = $room['room_view'];
            break;
        }
    }
    
    if (!empty($selectedRoomView)) {
        $query = "SELECT * FROM rooms WHERE room_view = '$selectedRoomView' AND room_number != '$selectedRoom'";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $similarRooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $noSimilarRooms = true;  // Flag to indicate no similar rooms
        }
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
        <table border="1">
            <tr>
                <th>Select</th>
                <th>Room Number</th>
                <th>Type</th>
                <th>Price</th>
                <th>Size</th>
                <th>Maximum Occupancy</th>
                <th>Room View</th>
                <th>Air Conditioning</th>
                <th>Coffee Maker</th>
                <th>Status</th>
            </tr>
            <?php foreach ($rooms as $room): ?>
                <tr>
                    <td>
                        <input type="radio" name="selectedRoom" value="<?php echo $room['room_number']; ?>"
                            <?php echo ($selectedRoom == $room['room_number']) ? 'checked' : ''; ?>>
                    </td>
                    <td><?php echo $room['room_number']; ?></td>
                    <td><?php echo $room['type']; ?></td>
                    <td><?php echo $room['price']; ?></td>
                    <td><?php echo $room['size']; ?></td>
                    <td><?php echo $room['maximum_occupancy']; ?></td>
                    <td><?php echo $room['room_view']; ?></td>
                    <td><?php echo $room['air_conditioning']; ?></td>
                    <td><?php echo $room['coffee_maker']; ?></td>
                    <td><?php echo $room['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Buttons for actions -->
        <input type="button" name="assign" value="Assign Room" onclick="redirectToRoomForm()">
        <input type="submit" name="remove" value="Remove Room" onclick="return confirm('Are you sure you want to remove this room?');">
        <input type="submit" name="unassign" value="Unassign Room" onclick="return confirmUnassign();">
        <input type="button" name="update" value="Update Room" onclick="redirectToRoomForm()">
        <input type="submit" name="similar" value="Show Similar Room">
        <input type="submit" name="clear" value="Clear Selection">

        <!-- Display Similar Rooms -->
        <?php if (!empty($similarRooms)): ?>
            <h2>Similar Rooms</h2>
            <table border="1">
                <tr>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Size</th>
                    <th>Maximum Occupancy</th>
                    <th>Room View</th>
                    <th>Air Conditioning</th>
                    <th>Coffee Maker</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($similarRooms as $room): ?>
                    <tr>
                        <td><?php echo $room['room_number']; ?></td>
                        <td><?php echo $room['type']; ?></td>
                        <td><?php echo $room['price']; ?></td>
                        <td><?php echo $room['size']; ?></td>
                        <td><?php echo $room['maximum_occupancy']; ?></td>
                        <td><?php echo $room['room_view']; ?></td>
                        <td><?php echo $room['air_conditioning']; ?></td>
                        <td><?php echo $room['coffee_maker']; ?></td>
                        <td><?php echo $room['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif (isset($noSimilarRooms)): ?>
            <p>No similar rooms found for the selected room view.</p>
        <?php endif; ?>
    </form>
    <button onclick="redirectToAdminPage()">Home</button>

    <!-- Include any other scripts or styles if necessary -->
    <script>
        function redirectToRoomForm() {
            window.location.href = 'roomform.php';
        }
        function confirmUnassign() {
            return confirm('Are you sure you want to unassign this room?');
        }
        function redirectToAdminPage() {
            window.location.href = 'admin.php';
        }
    </script>
   
</body>
</html>
