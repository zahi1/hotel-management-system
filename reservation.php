<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Reservation</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Your existing CSS styles */
        /* ... */

        /* Styles for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        img{
            width: 200px;
            height: 200px;
        }
    </style>
</head>
<body>
    <div class="reservation-form">
        <h1>Room Reservation</h1>
        <a href="customer.php">Go Back</a>
        <form action="" method="POST" id="reservationForm">
            <?php
            // Set the minimum value for check-in date to today's date
            $today = date("Y-m-d");
            echo "<label for='check_in_date'>Check-In Date:</label>";
            echo "<input type='date' id='check_in_date' name='check_in_date' min='{$today}' required><br><br>";
            ?>
            <label for="check_out_date">Check-Out Date:</label>
            <input type="date" id="check_out_date" name="check_out_date" required><br><br>
            
            <input type="submit" value="Check Availability">
        </form>
        
        <?php
        session_start();
        include 'db.php'; // Include your database connection

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $_SESSION['check_in_date'] = $_POST["check_in_date"];
            $_SESSION['check_out_date'] =$_POST["check_out_date"];
            $check_in_date = $_SESSION['check_in_date'];
            $check_out_date = $_SESSION['check_out_date'];
            $sql ="SELECT * FROM Rooms 
            WHERE room_number NOT IN (
                SELECT fk_Roomroom_number FROM Reservations 
                WHERE ('$check_in_date' <=   check_out_date) AND ('$check_out_date' >= check_in_date))";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<h2>Available Rooms:</h2>";   
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li>";
                    echo "<img src='imgs/" . $row['image'] . "' alt='" . $row['image'] . "'>";
                    echo "<h3>Type: " . $row['type'] . "</h3>";
                    echo "<p>Price: $" . $row['price'] . "</p>";
                    echo "<p>Maximum Occupancy: " . $row['maximum_occupancy'] . "</p>";
                    echo "<button type='button' class='reserve-btn' data-room-number='" . $row['room_number'] . "' data-max-occupancy='" . $row['maximum_occupancy'] . "'>Reserve</button>";
                    echo "</li>";
                    echo"<br>";
                }
                echo "</ul>";
            } else {
                echo "<p>No available rooms for selected dates.</p>";
            }
            
            
            
        }
        ?>
        
    </div>

    <!-- The modal -->
    <div id="reservationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Reservation Details</h2>
            <form action="reserve_room.php" method="POST">
                <input type="hidden" id="selectedRoom" name="selected_room_number" value="">
                <label for="customer_name">Customer Name:</label>
                <input type="text" id="guest_name" name="guest_name" required><br><br>
                
                <label for="number_of_guests">Number of Guests:</label>
                <input type="number" id="number_of_guests" name="number_of_guests" required><br><br>
    
    <!-- Add the room number input field -->
                <input type="hidden" id="room_number" name="room_number" value="">
    
                <input type="submit" name="confirm_reservation" value="Confirm Reservation">
            </form>

        </div>
    </div>

    <script>
    const reserveButtons = document.querySelectorAll('.reserve-btn');
    const modal = document.getElementById('reservationModal');
    const selectedRoomInput = document.getElementById('selectedRoom');
    const roomNumberInput = document.getElementById('room_number');

    reserveButtons.forEach(button => {
    button.addEventListener('click', () => {
        const roomNumber = button.getAttribute('data-room-number');
        const maxOccupancy = button.getAttribute('data-max-occupancy');

        selectedRoomInput.value = roomNumber;
        roomNumberInput.value = roomNumber;

        document.getElementById('number_of_guests').setAttribute('max', maxOccupancy);

        modal.style.display = 'block';
    });
});


    function closeModal() {
        modal.style.display = 'none';
    }
    document.querySelector('form').addEventListener('submit', function(event) {
        const checkInDate = new Date(document.getElementById('check_in_date').value);
        const checkOutDate = new Date(document.getElementById('check_out_date').value);

        if (checkOutDate < checkInDate) {
            alert('Check-out date cannot be earlier than the check-in date');
            event.preventDefault(); // Prevent form submission
        }
    });
</script>

</body>
</html>
