roomform.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Rooms Page</title>
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

        section {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="date"] {
            padding: 8px;
            width: 100%;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Add more styles as needed */
    </style>
</head>
<body>
    <h1>Manage Rooms</h1>
    <section>
        <h2>Home</h2>
        <p>Click the button below to go to home:</p>
    </section>

    <?php
    include 'db.php';  // Make sure this file initializes the $conn variable
    session_start();

    function refreshPage() {
       // header("Refresh:5");  // This will refresh the page after 0 seconds
    }
    


    $sql = "SELECT * FROM rooms";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table border="1">';
        echo '<tr><th>Room Number</th><th>Type</th><th>Price</th><th>Size</th><th>Maximum Occupancy</th><th>Room View</th><th>Air Conditioning</th><th>Coffee Maker</th><th>Status</th><th>Action</th></tr>';

        while($row = $result->fetch_assoc()) {
            echo '<form method="post" action="roomedit.php">';
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
            echo '<button type="submit" name="edit" onclick="return confirm(\'Are you sure you want to save changes?\')">Save</button>';
            //echo '<button type="submit" name="delete" onclick="return confirm(\'Are you sure you want to delete this room?\')">Delete</button>';
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
    echo '<form method="post" action="roomedit.php">';
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
    echo 'Image: <input type="text" name="image" required><br>';
    echo '<input type="submit" name="addRoom" value="Save">';
    echo '</form>';
    echo '</div>';

    
    
    $conn->close();
    ?>
    <br><br>
    <br><br>
    <button onclick="redirectToAdminPage()">Home</button>
    <br><br>
    <button onclick="redirectrooms()">Rooms page</button>
    
    <script>
        function showAddRoomForm() {
            var form = document.getElementById('addRoomForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
        function redirectToAdminPage() {
            window.location.href = 'admin.php';
        }
        function redirectrooms() {
            window.location.href = 'Roomlistpage.php';
        }
    </script>

</body>
</html>