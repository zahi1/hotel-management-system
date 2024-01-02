<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Rooms Page</title>
</head>
<body>
    <h1>Manage Rooms</h1>
    <section>
        <h2>Home</h2>
        <p>Click the button below to go to home:</p>
    </section>

    <?php
    include 'db.php';  // Make sure this file initializes the $conn variable

    function refreshPage() {
       // header("Refresh:5");  // This will refresh the page after 0 seconds
    }
    

    $sql = "SELECT * FROM rooms";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table border="1">';
        echo '<tr><th>Room Number</th><th>Type</th><th>Price</th><th>Size</th><th>Maximum Occupancy</th><th>Room View</th><th>Air Conditioning</th><th>Coffee Maker</th><th>Status</th><th>Action</th></tr>';

        while($row = $result->fetch_assoc()) {
            echo '<form method="post" action="">';
            echo '<tr>';
            echo '<td>'.$row['room_number'].'</td>';
            echo '<td><input type="text" name="type" value="'.$row['type'].'" required></td>';
            echo '<td><input type="text" name="price" value="'.$row['price'].'" required></td>';
            echo '<td><input type="text" name="size" value="'.$row['size'].'" required></td>';
            echo '<td><input type="text" name="maximum_occupancy" value="'.$row['maximum_occupancy'].'" required></td>';
            echo '<td><input type="text" name="room_view" value="'.$row['room_view'].'" required></td>';
            echo '<td><input type="text" name="air_conditioning" value="'.$row['air_conditioning'].'" required></td>';
            echo '<td><input type="text" name="coffee_maker" value="'.$row['coffee_maker'].'" required></td>';
            echo '<td><input type="text" name="status" value="'.$row['status'].'" required></td>';
            echo '<td>';
            echo '<input type="hidden" name="room_number" value="'.$row['room_number'].'">';
            echo '<button type="submit" name="edit">Save</button>';
            echo '<button type="submit" name="delete" onclick="return confirm(\'Are you sure you want to delete this room?\')">Delete</button>';
            echo '</td>';
            echo '</tr>';
            echo '</form>';
        }
        echo '</table>';
    } else {
        echo 'No rooms found.';
    }

    // Add Room Form
    echo '<button onclick="showAddRoomForm()">Add Room</button>';
    echo '<div id="addRoomForm" style="display:none;">';
    echo '<h2>Add Room</h2>';
    echo '<form method="post" action="">';
    echo 'Start Date: <input type="date" name="start_date" required><br>';
    echo 'Room Number: <input type="text" name="room_number" required><br>';
    echo 'Type: <input type="text" name="type" required><br>';
    echo 'Price: <input type="text" name="price" required><br>';
    echo 'Size: <input type="text" name="size" required><br>';
    echo 'Maximum Occupancy: <input type="text" name="maximum_occupancy" required><br>';
    echo 'Room View: <input type="text" name="room_view" required><br>';
    echo 'Air Conditioning: <input type="text" name="air_conditioning" required><br>';
    echo 'Coffee Maker: <input type="text" name="coffee_maker" required><br>';
    echo 'Status: <input type="text" name="status" required><br>';
    echo '<input type="submit" name="addRoom" value="Save">';
    echo '</form>';
    echo '</div>';

   
    // ... [Your existing code above remains unchanged]
    
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
                echo "Room details updated successfully.";
                refreshPage();
            } else {
                echo "Error updating room details: " . $conn->error;
            }
        }
    
        if (isset($_POST['delete'])) {
            $room_numberToDelete = $_POST['room_number'];
            $deleteQuery = "DELETE FROM rooms WHERE room_number = '$room_numberToDelete'";
            
            if ($conn->query($deleteQuery) === TRUE) {
                echo "Room deleted successfully.";
                refreshPage();
            } else {
                echo "Error deleting room: " . $conn->error;
            }
        }
    
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
    
            $addQuery = "INSERT INTO rooms (start_date, room_number, type, price, size, maximum_occupancy, room_view, air_conditioning, coffee_maker, status) VALUES ('$start_date', '$room_number', '$type', '$price', '$size', '$maximum_occupancy', '$room_view', '$air_conditioning', '$coffee_maker', '$status')";
    
            if ($conn->query($addQuery) === TRUE) {
                echo "New room added successfully.";
                refreshPage();
            } else {
                echo "Error adding room: " . $conn->error;
            }
        }
    }
    
    $conn->close();
    ?>
    
    

    <script>
        function showAddRoomForm() {
            var form = document.getElementById('addRoomForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>

</body>
</html>
