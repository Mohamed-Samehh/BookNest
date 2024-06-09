<?php
session_start();

$host = 'localhost';
$dbname = 'booknest';
$dbUsername = 'root';
$dbPassword = '';
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['userID'])) {
    echo "<script>alert('You should be logged in to book a hotel'); window.location='home.php';</script>";
    exit();
}

if (!isset($_SESSION['hotelID'])) {
    echo "<script>alert('Please select a hotel'); window.location='search_result.php';</script>";
    exit();
}

$hotelID = $_SESSION['hotelID'];
$clientID = $_SESSION['userID'];
$amount = $_POST['amount'] ?? 0;
$checkin = $_POST['checkin'] ?? " ";
$checkout = $_POST['checkout'] ?? "";
$guestsNum = $_POST['guests'] ?? 0;
$roomType = $_POST['type'] ?? "";
$payDate = $_POST['payDate'] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $nameOnCard = mysqli_real_escape_string($conn, $_POST['nameCard']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $cardNumber = mysqli_real_escape_string($conn, $_POST['cardNum']);
    $cvv = mysqli_real_escape_string($conn, $_POST['cvv']);
    $expMonth = mysqli_real_escape_string($conn, $_POST['expMonth']);
    $expYear = mysqli_real_escape_string($conn, $_POST['expYear']);

    $sqlPayment = "INSERT INTO payment (clientID, fullName, nameCard, email, address, city, cardNum, cvv, expMonth, expYear, amount, payDate)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmtPayment = $conn->prepare($sqlPayment)) {
        $stmtPayment->bind_param("isssssssssds", $clientID, $fullName, $nameOnCard, $email, $address, $city, $cardNumber, $cvv, $expMonth, $expYear, $amount, $payDate);
        if ($stmtPayment->execute()) {
            $paymentID = $conn->insert_id;

            $sqlBooking = "INSERT INTO booking (clientID, hotelID, roomType, checkIN, checkOUT, guestsNum, bookDate, paymentID)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            if ($stmtBooking = $conn->prepare($sqlBooking)) {
                $stmtBooking->bind_param("iisssssi", $clientID, $hotelID, $roomType, $checkin, $checkout, $guestsNum, $payDate, $paymentID);
                if ($stmtBooking->execute()) {
                    echo "<script>alert('Booking is successfully completed'); window.location='Rooms.php';</script>";
                } else {
                    echo "Error in booking record: " . $stmtBooking->error;
                }
                $stmtBooking->close();
            } else {
                echo "Error preparing booking statement: " . $conn->error;
            }
        } else {
            echo "Error in payment record: " . $stmtPayment->error;
        }
        $stmtPayment->close();
    } else {
        echo "Error preparing payment statement: " . $conn->error;
    }
}

$conn->close();
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <title>BookNest - Booking Confirmation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
        }

        h2{
            padding-top: 10px;
            padding-bottom: 15px;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            background-image: url("images/big-swimming-pool-with-trees-deck-chairs.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }

        header {
            background-color: rgb(255, 255, 255);
            height: 100px;
            width: 100%;
            display: flex;
            border-bottom: solid rgb(185, 185, 185) 1px;
        }

        nav {
            position: relative;
            height: 100px;
            width: 100%;
            display: flex;
        }

        .nav_links {
            height: 100px;
            width: 100%;
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .nav__logo {
            height: 100px;
            width: 200px;
            position: absolute;
            list-style: none;
            top: 32px;
            left: 15px;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333333;
        }

        .nav__logo a {
            text-decoration: none;
            color: black;
        }

        .links {
            width: 600px;
            height: 100px;
            position: absolute;
            right: 10px;
            list-style: none;
            gap: 2rem;
        }

        .links a {
            font-weight: 500;
            text-decoration: none;
            color: #3d3b35;
            transition: 0.3s;
        }

        .links a:hover {
            color: #000000;
        }

        header div {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 250px;
            width: 100%;
        }

        header div img {

            height: 200px;
            width: 90%;
            border-radius: 10px;
        }

        .root {
            width: 100%;
            height: 800px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 20px;
        }

        .left_section {
            background-color: #59d6df41;
            margin: 2px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: solid rgba(128, 128, 128, 0.166) 1px;
            border-radius: 6px;
            padding: 5px;
        }

        .section_header {
            margin-bottom: 40px;
            height: 50px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .first-left-container {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card_container {
            position: absolute;
            top: 150px;
            height: 600px;
            width: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card_container img {
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card_container h4 {
            height: 30px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card_container h5 {
            height: 30px;
            font-size: large;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .first-left-container .buttons {
            margin: 20px;
            padding: 20px;
            position: absolute;
            top: 700px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 48px;
            width: 320px;
        }

        .buttons input {
            height: 30px;
            border-radius: 5px;
            border: solid rgba(0, 0, 0, 0.284) 1px;

        }

        .check-out-label {
            font-weight: 600;
            position: absolute;
            top: 7px;
            right: 42px;
        }

        .check-in-label {
            font-weight: 600;
            position: absolute;
            top: 7px;
            left: 45px;
        }

        .guests-input {
            position: absolute;
            top: 75px;
            left: 92px;
            padding-left: 10px;
        }

        .right_section {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            background-color: #59d6df41;
            border-radius: 10px;
        }

        .right-container {
            height: 700px;
            width: 650px;
            background-color: white;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 0 20px #000000;
        }

        .right-container h2 {
            justify-content: center;
            height: 35px;
            font-size: 25px;
            margin: 5px;
            text-align: center;
            border: 1px solid;
        }

        .right-container h5 {
            height: 20px;
            font-size: 20px;
            padding: 5px;
        }

        .input_group {
            position: relative;
            display: flex;
            flex-direction: row;
            padding: 3px 0px;
            margin-bottom: 10px;
            width: 100%;
        }

        .payment-input-container {
            width: 100%;
            margin-right: 10px;
            position: relative;
        }

        .payment-input-container:last-child {
            margin-right: 0;
        }

        .payment-input-container .fullname {
            padding: 10px 10px 10px 50px;
            width: 80%;
            background-color: #fcfcfc;
            border: 1px solid rgba(0, 0, 0, 0.671);
            outline: none;
            letter-spacing: 1px;
            transition: 0.3s;
            border-radius: 4px;
            color: #333333;
        }

        .payment-input-container .fullname:focus,
        .dob:focus {
            box-shadow: 0 0 2px 1px #21cdd3b8;
            border: solid 1px #21cdd3b8;
        }

        .payment-input-container .person-icon {
            width: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            left: 0;
            bottom: 0;
            top: 0;
            color: #333333;
            background-color: #f1f1f1;
            border-radius: 3px 0 0 3px;
            transition: 0.3;
            pointer-events: none;
            border: 1px solid #00000348;
            border-right: none;
        }
        .fullname:focus+.person-icon{
            background-color: #21cdd3b8;
            border: 1px solid #21cdd3b8;
        }
        .payment-input-container button{
            display: flex;
            align-items: center;
            justify-content: center;
            width:100%;
            height: 40px;
            background-color: #21cdd3b8;
            color: #f1f1f1;
            border:none;
            transition: all 0.3s ease;
            padding: 15px;
            border-radius: 7px;
            font-size: 18px;
            font-weight: 600;
        }
        .payment-input-container button:hover{
            cursor: pointer;
            background-color: #0f9196b8;
            
        }
        .payment-input-container #msg{
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            padding:15px;
            width:100%;
            font-weight: 600;
        }

        h5{
            margin-top: 0px;
        }

        #pay2{
            width: 100px;
            height: 40px;
            margin-top: 15px;
        }

        #inn{
            width: 150px;
        }

        #out{
            width: 150px;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <ul class="nav__links">
                <li class="nav__logo"><a href="home.php">BookNest</a></li>
                <div class="links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="search_result.php">Hotels</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="Contact Us.php">Contact Us</a></li>
                    <li><a href="account.php">User Account</a></li>
                </div>
            </ul>
        </nav>
    </header>
    <div class="section_header">
        <h2>Confirm Booking</h2>
    </div>
    <div class="root">
        <div class="left_section">
            <div class="first-left-container">
                <div class="card_container">
                    <form id="form2">
                    <div id="1">
                        <img src="../Internet_project/images/Luxury_room1.jpg"
                        width="500px" height="300px">
                            <h4>Luxurious Room 1</h4>
                        <h4>3 Guests</h4>
                        <h5>$300 / Day</h5>
                    </div>
                </div>
                <div class="card_container">
                    <div id="2">
                        <img src="../Internet_project/images/Family_room1.png" alt="popular hotel" width="500px" height="350px">
                        <h4>Family Room 1</h4>
                        <h4>5 Guests</h4>
                        <h5>$100 / Day</h5>
                    </div>
                </div>
                <div class="card_container">
                    <div id="3">
                        <img src="../Internet_project/images/Couple_room1.jpeg" alt="popular hotel"
                            width="500px" height="325px">
                            <h4>Couple Room 1</h4>
                        <h4>2 Guests</h4>
                        <h5>$200 / Day</h5>
                    </div>
                </div>
                <div class="card_container">
                    <div id="4">
                        <img src="../Internet_project/images/single.jpg" alt="popular hotel"
                            width="500px" height="300px">
                            <h4>Single Room</h4>
                        <h4>1 Guest</h4>
                        <h5>$60 / Day</h5>
                    </div>
                </div>
                <div class="card_container">
                    <div id="5">
                        <img src="../Internet_project/images/Couple_room2.webp" alt="popular hotel"
                            width="500px" height="325px">
                            <h4>Couple Room 2</h4>
                        <h4>2 Guests</h4>
                        <h5>$100 / Day</h5>
                    </div>
                </div>
                <div class="card_container">
                    <div id="6">
                        <img src="../Internet_project/images/Family_room2.jpg"
                            alt="popular hotel" width="500px" height="325px">
                            <h4>Family Room 2</h4>
                        <h4>5 Guests</h4>
                        <h5>$150 / Day</h5>
                    </div>
                </div>
                <div class="card_container">
                    <div id="7">
                        <img src="../Internet_project/images/Couple_room3.webp"
                            alt="popular hotel" width="500px" height="325px">
                            <h4>Couple Room 3</h4>
                        <h4>2 Guests</h4>
                        <h5>$80 / Day</h5>
                    </div>
                </div>
                <div class="card_container">
                    <div id="8">
                        <img src="../Internet_project/images/Luxury_room3.jpg"
                            alt="popular hotel" width="500px" height="350px">
                            <h4>Luxurious Room 2</h4>
                        <h4>3 Guests</h4>
                        <h5>$300 / Day</h5>
                    </div>
                </div>
                <div class="card_container">
                    <div id="9">
                        <img src="../Internet_project/images/Family_room3.webp"
                            alt="popular hotel" width="500px" height="300px">
                            <h4>Family Room 3</h4>
                        <h4>5 Guests</h4>
                        <h5>$100 / Day</h5>
                    </div>
                </div>
                
                
                <div class="buttons">
                    <label class="check-in-label">Check-In</label>
                    <input type="date" class="check-in-input" id="inn">
                    <label class="check-out-label">Check-Out</label>
                    <input type="date" class="check-out-input" id="out">
                    <input type="number" placeholder="Number of Guests" class="guests-input" id="guest" oninput="updateGuestInput(this)">
                </div>
            
            </div>
            <div id="msg2"></div>
            <button id="pay2">Submit</button>
        </form>
        </div>
    
        
        <div class="right_section">
            <div class="right-container">
                <h2>Payment Form</h2>
                <form id="payform" method="post">
                    <h5>Information</h5>
                    <div class="input_group">
                        <div class="payment-input-container">
                            <input class="fullname" type="text" id="name" name="fullName" placeholder="Full Name">
                            <i class="person-icon"><img src="images/person_FILL1_wght700_GRAD200_opsz24.png"></i>
                        </div>
                        <div class="payment-input-container">
                            <input class="fullname" type="text" id="namecard" name="nameCard" placeholder="Name On Card">
                            <i class="person-icon"><img src="images/person_FILL1_wght700_GRAD200_opsz24.png"></i>
                        </div>
                    </div>
                    <div class="input_group">
                        <div class="payment-input-container">
                            <input class="fullname" type="text" id="email" name="email" placeholder="Email Address">
                            <i class="person-icon"><img src="images/mail_FILL1_wght700_GRAD200_opsz24.png"></i>
                        </div>
                    </div>
                    <div class="input_group">
                        <div class="payment-input-container">
                            <input class="fullname" aria-hidden="true" type="text" id="address" name="address" placeholder="Address">
                            <i class="person-icon"><img src="images/location_on_FILL1_wght700_GRAD200_opsz24.png"></i>
                        </div>
                    </div>
                    <div class="input_group">
                        <div class="payment-input-container">
                            <input class="fullname" type="text" id="city" name="city" placeholder="City">
                            <i class="person-icon"><img src="images/location_city_FILL1_wght700_GRAD200_opsz24.png"></i>
                        </div>
                    </div>

                    <h5>Payment Details</h5>
                    <div class="input_group">

                        <div class="payment-input-container">

                            <input type="text" class="fullname" name="cardNum" placeholder="Card Number 1111-2222-3333-4444" id="cardnum">
                            <i class="person-icon"><img src="images/credit_card_FILL0_wght700_GRAD200_opsz24.png"></i>
                        </div>
                    </div>
                    <div class="input_group">
                        <div class="payment-input-container">
                            <input type="text" class="fullname" name="cvv" placeholder="Card CVV" id="cvv">
                            <i class="person-icon"><img src="images/credit_card_FILL0_wght700_GRAD200_opsz24.png"></i>
                        </div>
                    </div>
                    <div class="input_group">
                        <div class="payment-input-container">
                            <input type="number" class="fullname" name="expMonth" placeholder="EXP Month" id="month" oninput = "if(parseInt(value) <= 0 || parseInt(value) > 12) value='';">
                            <i class="person-icon"><img
                                    src="images/calendar_month_FILL0_wght700_GRAD200_opsz24.png"></i>
                        </div>
                    </div>
                    <div class="input_group">
                        <div class="payment-input-container">
                        <input type="number" class="fullname" name="expYear" placeholder="EXP Year" id="year" oninput="if(parseInt(value) <= 0) value='';" onmouseout="if(parseInt(value) < 2024 || parseInt(value) > 2050) value='';">
                            <i class="person-icon"><img
                                    src="images/calendar_month_FILL0_wght700_GRAD200_opsz24.png"></i>
                        </div>
                    </div>
                    <h4>Total Amount:</h4>
                    <h4><div id="total">$</div></h4>
                    <div class="input_group">
                        <div class="payment-input-container">
                            <button id="pay">Pay</button> 
                            <div id="msg"></div>
                        </div>
                    </div>
                    <input type="hidden" name="amount" id="amount" value="">
                    <input type="hidden" name="guests" id="guests" value="">
                    <input type="hidden" name="checkin" id="checkin" value="">
                    <input type="hidden" name="checkout" id="checkout" value="">
                    <input type="hidden" name="payDate" id="payDate" value="">
                    <input type="hidden" name="type" id="type" value="">
                </form>

           
            </div>
        </div>
    </div>
    <script>
        function updateGuestInput(input) {
            var maxGuests = parseInt(localStorage.getItem('guests'));
            var currentValue = parseInt(input.value);
            if (currentValue <= 0) {
                input.value = '';
            } else if (currentValue > maxGuests) {
                input.value = maxGuests;
            }
        }


        $(document).ready(function () {

            $("#1").hide();
            $("#2").hide();
            $("#3").hide();
            $("#4").hide();
            $("#5").hide();
            $("#6").hide();
            $("#7").hide();
            $("#8").hide();
            $("#9").hide();

            var Confirm = localStorage.getItem('Confirm');

            if (Confirm == 1) {
                $("#1").show();
            }

            if (Confirm == 2) {
                $("#2").show();
            }

            if (Confirm == 3) {
                $("#3").show();
            }

            if (Confirm == 4) {
                $("#4").show();
            }

            if (Confirm == 5) {
                $("#5").show();
            }

            if (Confirm == 6) {
                $("#6").show();
            }

            if (Confirm == 7) {
                $("#7").show();
            }

            if (Confirm == 8) {
                $("#8").show();
            }

            if (Confirm == 9) {
                $("#9").show();
            }

            var submitted = false;
            var pay = document.getElementById('pay');
            var form = document.getElementById('payform');
            var txt = document.getElementById('msg');
            var today = new Date();
            var payDate = document.getElementById('payDate');
            payDate.value = new Date();

            pay.addEventListener('click', function() {
                var nameInput = document.getElementById('name');
                var namecardInput = document.getElementById('namecard');
                var emailInput = document.getElementById('email');
                var addressInput = document.getElementById('address');
                var cityInput = document.getElementById('city');
                var cardnumInput = document.getElementById('cardnum');
                var cvvInput = document.getElementById('cvv');
                var monthInput = document.getElementById('month');
                var yearInput = document.getElementById('year');




                if(submitted == true){
                    form.addEventListener('submit', preventSubmit);

                if ((nameInput.value !== "" && namecardInput.value !== "") && (addressInput.value !== "" && cityInput.value !== "") && (cardnumInput.value !== "" && cvvInput.value !== "") && (monthInput.value !== "" && yearInput.value !== "") && isValidEmail(emailInput.value)) {
                    form.removeEventListener('submit', preventSubmit);
                }
                
                else {
                    form.addEventListener('submit', preventSubmit);
                    txt.textContent = "Error! Invalid inputs";
                    txt.style.fontSize = '25px';
                    txt.style.color = 'red';
                    txt.style.paddingTop = '23px';
                    txt.style.textAlign = 'center';
                }
            }

            else{
                form.addEventListener('submit', preventSubmit);
                    txt.textContent = "Error! Enter hotel booking details";
                    txt.style.fontSize = '25px';
                    txt.style.color = 'red';
                    txt.style.paddingTop = '23px';
                    txt.style.textAlign = 'center';
            }
            });

            var amount = localStorage.getItem('amount');
            var pay2 = document.getElementById('pay2');
            var form2 = document.getElementById('form2');
            var txt2 = document.getElementById('msg2');


            pay2.addEventListener('click', function() {
                var innInput = document.getElementById('inn');
                var outInput = document.getElementById('out');
                var guestInput = document.getElementById('guest');
                var gsts = localStorage.getItem('guests');
                var roomType = localStorage.getItem('roomType');
                var type = document.getElementById('type');
                var amountInput = document.getElementById('amount');
                var guests = document.getElementById('guests');
                var checkin = document.getElementById('checkin');
                var checkout = document.getElementById('checkout');
                var innDate = new Date(innInput.value);
                var outDate = new Date(outInput.value);
                

                if((innInput.value !== "" && outInput.value !== "") && guestInput.value !== ""){
                    form2.addEventListener('submit', preventSubmit);
                    var difference = outDate - innDate;
                    var differenceDays = Math.ceil(difference / (1000 * 60 * 60 * 24));

                    if(guestInput.value > 0){
                        if(gsts >= guestInput.value && 10 > guestInput.value){
                        if(innDate >= today){
                        if(differenceDays > 0){
                        form2.addEventListener('submit', preventSubmit);
                        txt2.textContent = "Booking details submitted";
                        txt2.style.fontSize = '25px';
                        txt2.style.color = 'black';
                        txt2.style.paddingTop = '15px';
                        txt2.style.textAlign = 'center';
                        txt2.style.fontWeight = 'bold';
                        submitted = true;
                        var price = amount * differenceDays * guestInput.value;
                        $('#total').text("$" + price);
                        amountInput.value = price;
                        guests.value = guestInput.value;
                        type.value = roomType;
                        checkin.value = innDate;
                        checkout.value = outDate;
                        }

                    else{
                        form2.addEventListener('submit', preventSubmit);
                        txt2.textContent = "Error! Check in should be before Check out";
                        txt2.style.fontSize = '25px';
                        txt2.style.color = 'black';
                        txt2.style.paddingTop = '15px';
                        txt2.style.textAlign = 'center';
                        txt2.style.fontWeight = 'bold';
                    }
                    }
                    else{
                        form2.addEventListener('submit', preventSubmit);
                        txt2.textContent = "Error! Check in can't be today or any day before";
                        txt2.style.fontSize = '25px';
                        txt2.style.color = 'black';
                        txt2.style.paddingTop = '15px';
                        txt2.style.textAlign = 'center';
                        txt2.style.fontWeight = 'bold';
                    }
                    }
                    else{
                        form2.addEventListener('submit', preventSubmit);
                        txt2.textContent = "Error! You have exceeded the max number of guests";
                        txt2.style.fontSize = '25px';
                        txt2.style.color = 'black';
                        txt2.style.paddingTop = '15px';
                        txt2.style.textAlign = 'center';
                        txt2.style.fontWeight = 'bold';
                    }
                }

                else{
                        form2.addEventListener('submit', preventSubmit);
                        txt2.textContent = "Error! Guests number must be greater than zero";
                        txt2.style.fontSize = '25px';
                        txt2.style.color = 'black';
                        txt2.style.paddingTop = '15px';
                        txt2.style.textAlign = 'center';
                        txt2.style.fontWeight = 'bold';
                    }
            }

                else {
                    form2.addEventListener('submit', preventSubmit);
                    txt2.textContent = "Error! Enter booking details";
                    txt2.style.fontSize = '25px';
                    txt2.style.color = 'black';
                    txt2.style.paddingTop = '15px';
                    txt2.style.textAlign = 'center';
                    txt2.style.fontWeight = 'bold';
                }
            });
            
            function isValidEmail(email) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function preventSubmit(event) {
                event.preventDefault();
            }

        });
    </script>
</body>

</html>
