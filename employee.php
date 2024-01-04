<?php
session_start();

$userid =  $_SESSION['user_id'];
$username=$_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Home</title>
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
            header{
                background: url(imgs/employee.jpg);

            background-size: cover;
            background-position:center ;
            min-height: 1000px;
        }
        .image-with-text {
            display: flex!important;
            flex-direction: column;
            align-items: center;
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
                <a href="#" class="brand-logo " style="color: black" >Welcome, Employee!</a>
                <a href="#" data-target="mobile-links" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class ="right hide-on-med-and-down">
            <li><a href="Bill-form.php"class="btn">Bill Form</a></li>
            <li><a href="Customer-requests-page.php"class="btn">Customer Requests</a></li>
            <li><a href="Reservation-form.php"class="btn">Reservation Form</a></li>
            <li><a href="Report-form.php"class="btn">Report</a></li>
            <li><form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="btn">Logout</button>
            </form></li>
            </ul>     
            <ul class="sidenav" id="mobile-links"><li>
                <li><a href="/home"class="btn">Home</a></li>
                <li><a href="/logout"class="btn">Logout</a></li>
                </ul>  
         </div>
        </nav><br>  
    </header> 
</body>
</html>
