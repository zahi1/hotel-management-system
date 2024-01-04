<?php
include 'db.php';  // Including the database connection file
function refreshPage() {
    header("Refresh:1");  // This will refresh the page after 0 seconds
 }
// Fetch rooms from the database
$query = "SELECT * FROM rooms";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if rooms are fetched successfully
if ($result) {
    $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $rooms = [];  // Empty array if no rooms are fetched
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
    if (isset($_POST['remove'])) {
        $selectedRoomNumber = $_POST['selectedRoom'];
        $deleteQuery = "DELETE FROM rooms WHERE room_number = '$selectedRoomNumber'";
        //$stmt = mysqli_prepare($conn, $deleteQuery);
        //mysqli_stmt_bind_param($stmt, 's', $selectedRoomNumber);
        if ($conn->query($deleteQuery) === TRUE) {
            echo "<p>Room removed successfully.</p>";
            refreshPage();
        } else {
            echo "<p>Error canceling reservation: " . $conn->error . "</p>";
        }
      
    }
    
    // Handle room unassignment
    if (isset($_POST['unassign'])) {
        $selectedRoomNumber = $_POST['selectedRoom'];
        echo "$selectedRoomNumber";
        $updateStatusQuery = "UPDATE rooms SET status = 'available' WHERE room_number ='$selectedRoomNumber'";
        if ($conn->query($updateStatusQuery) === TRUE) {
            echo "<p>Unassigned successfully.</p>";
            refreshPage();


        } else {
            echo "<p>Error canceling reservation: " . $conn->error . "</p>";
        }
    }
    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    
    
    <meta charset="UTF-8">
    <title>Select Room</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td input[type="radio"] {
            margin-right: 5px;
        }

        button, input[type="submit"], input[type="button"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover, input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #0056b3;
        }

        p {
            color: red;
        }

        /* Add more styles as needed */
    </style>
</head>
<body>
    <h1>Select Room</h1>
    <button onclick="redirectToAdminPage()">Home</button>

    <form action="" method="post">
    
    <br><br>
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
        
        <input type="submit" name="remove" value="Remove Room" onclick="return confirm('Are you sure you want to remove this room?');">
        <input type="submit" name="unassign" value="Unassign Room" onclick="return confirmUnassign();">
       
        <input type="submit" name="similar" value="Show Similar Room">
        <br><br>
        <input type="button" name="assign" value="Room Form" onclick="redirectToRoomForm()">
        
        

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