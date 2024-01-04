<?php
        session_start();
        include 'db.php'; // Include your database connection
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Reservation</title>
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
        header{
                background: url(imgs/reservation.jpg);
            background-size: cover;
            background-position:center ;
            min-height: 1000px;
        }
        .image-with-text {
            display: flex!important;
            flex-direction: column;
            align-items: center;
        }
        .reservation-form h2,
        .reservation-form h3,
        .reservation-form p
        {
    font-family: Arial, sans-serif; /* Example font family */
    color: #f4ff81  /* Example text color */
    /* Add more styling as needed */
}
.reservation-form button {
    background-color: white
    ;
}
        @media screen and (max-width:670px){
            header{
                min-height: 500px;
            }
            
        }
    </style>
</head>
<body>
<header>
    <nav class="nav-wrapper transparent" >
                <div class="container">
                <a href="#" class="brand-logo " style="color: black" >Reserve a Room</a>
                <a href="#" data-target="mobile-links" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class ="right hide-on-med-and-down">
            <li><a href="customer.php" class="btn">Home</a></li>
            <li><a href="myreservations.php"class="btn">My Reservations</a></li>
            <li><a href="billpage.php"class="btn">View Bill</a></li>
            <li><form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="btn">Logout</button>
            </form></li>
            </ul>     
            <ul class="sidenav" id="mobile-links">
            <li><a href="customer.php" class="btn">Go Back</a></li>
            <li><a href="myreservations.php"class="btn">My Reservations</a></li>
            <li><a href="billpage.php"class="btn">View Bill</a></li>
            <li><form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="btn">Logout</button>
            </form></li>
                </ul>  
         </div>
        </nav><br>
    
    <div class="reservation-form">
        <form action="" method="POST" id="reservationForm">
            <?php
            // Set the minimum value for check-in date to today's date
            $today = date("Y-m-d");
            echo "<label for='check_in_date'><b>Check-In Date:</b></label>";
            echo "<input type='date' id='check_in_date' name='check_in_date' min='{$today}' required><br><br>";
            ?>
            <label for="check_out_date" ><b>Check-Out-Date:</b></label>
            <input type="date" id="check_out_date" name="check_out_date" required><br><br>
            
            <input type="submit" class="btn" value="Check Availability">
        </form>

        <?php

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
                echo "<div class='reservation-form'>";
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
            echo "</div>";
            
            
            
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

</header>
</body>
<script src="/app.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script> $(document).ready(function(){ $('.sidenav').sidenav(); })</script>
</html>
