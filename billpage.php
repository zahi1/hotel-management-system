<?php
session_start();
$userid = $_SESSION['user_id'];
include 'db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bill_id'], $_POST['amount'])) {
    $bill_id = $_POST['bill_id'];
    $amount = $_POST['amount'];
    $_SESSION['bill_id'] = $bill_id;

    // Proceed to payment processing using Stripe
    header("Location: checkout.php?bill_id=$bill_id&amount=$amount");
    exit();
}

// Fetch unpaid bills of the logged-in customer
$sql = "SELECT * FROM bills WHERE customer_id = $userid AND status = 'unpaid'";
$result = $conn->query($sql);

$billsData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $billsData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Bills</title>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- font awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
</head>
<script>
        function confirmPayment() {
            return confirm('Are you sure you want to pay this bill?');
        }
    </script>
</head>
<body>
<header>
<nav class="nav-wrapper black" >
                <div class="container">
                <a href="#" class="brand-logo " style="color: white" >Bills</a>
                <a href="#" data-target="mobile-links" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class ="right hide-on-med-and-down">
            <li><a href="reservation.php"class="btn">Make a Reservation</a></li>
            <li><a href="myreservations.php"class="btn">My Reservations</a></li>
            <li><a href="billpage.php"class="btn">View Bill</a></li>
            <li><form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="btn">Logout</button>
            </form></li>
            </ul>     
            <ul class="sidenav" id="mobile-links">
                <li><a href="reservation.php"class="btn">Make a Reservation</a></li>
            <li><a href="myreservations.php"class="btn">My Reservations</a></li>
            <li><a href="billpage.php"class="btn">View Bill</a></li>
            <li><form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="btn">Logout</button>
            </form></li>
                </ul>  
         </div>
        </nav><br>
    
    </header>
    <?php if (!empty($billsData)): ?>
        <table>
            <tr>
                <th>Bill ID</th>
                <th>Amount</th>
                <th>Date Generated</th>
                <th>Remarks</th>
                <th>Pay Bill</th>
            </tr>
            <?php foreach ($billsData as $bill): ?>
                <tr>
                    <td><?php echo $bill['id_Bill']; ?></td>
                    <td><?php echo $bill['bill_amount']; ?></td>
                    <td><?php echo $bill['date_generated']; ?></td>
                    <td><?php echo $bill['remarks']; ?></td>
                    <td>
                        <form action="billpage.php" method="post" onsubmit="return confirmPayment()">
                            <input type="hidden" name="bill_id" value="<?php echo $bill['id_Bill']; ?>">
                            <input type="hidden" name="amount" value="<?php echo $bill['bill_amount']; ?>">
                            <input type="submit" class ="btn" value="Pay Bill">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No unpaid bills!</p>
    <?php endif; ?>

    <form action="customer.php">
        <button type="submit" class ="btn">Back</button>
    </form>
</body>
<script src="/app.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script> $(document).ready(function(){ $('.sidenav').sidenav(); })</script>
</html>
