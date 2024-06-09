<?php
    session_start();

    $connect = mysqli_connect("localhost", "root", "", "BookNest");
    if (!$connect) {
        die("Cannot connect to database");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $number = $_POST["number"];
        $DOB = $_POST["DOB"];
        $email = $_POST["email"];
        $sex = $_POST["sex"];
        $username = $_POST["username"];
        $password = $_POST["createpassword"];

        $checkUsernameQuery = "SELECT * FROM client WHERE username = ?";
        $stmt = mysqli_prepare($connect, $checkUsernameQuery);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "<script>alert('Error during registration: Username already exists.');</script>";
        } else {
        $query = "INSERT INTO client (name, phoneNum, DOB, email, gender, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $name, $number, $DOB, $email, $sex, $username, $password);
        mysqli_stmt_execute($stmt);
        
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['username'] = $username;
            $_SESSION['userID'] = mysqli_insert_id($connect);
            echo "<script>alert('Signup successful!'); window.location.href='home.php';</script>";
        } else {
            echo "<script>alert('Error during registration');</script>";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($connect);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./includes/styles.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
7
            var submitButton = document.getElementById("submitButton");
            var form = document.getElementById('signup-form');
            var subscribeText = document.getElementById('subscription-message');
    
            submitButton.addEventListener('click', function() {
                var nameInput = document.getElementById('name');
                var emailInput = document.getElementById('email');
                var numberInput = document.getElementById('number');
                var userInput = document.getElementById('username');
                var crpassInput = document.getElementById('createpassword');
                var conpassInput = document.getElementById('confirmpassword');
                var DOBInput = document.getElementById('DOB');
                var sexInput = document.getElementById('sex');
                var today = new Date();
                var DOBDate = new Date(DOBInput.value);
    
                if ((nameInput.value !== "" && numberInput.value !== "") && (crpassInput.value !== "" && conpassInput.value !== "") && (crpassInput.value == conpassInput.value) && (userInput.value !== "") && (DOBInput.value !== "" && crpassInput.value.length >= 5) && (sexInput.value == "Male" || sexInput.value == "Female") && isValidEmail(emailInput.value)) {
                    var differenceMs = today - DOBDate;
                    var differenceYears = differenceMs / (1000 * 60 * 60 * 24 * 365.25);

                    if(differenceYears >= 18){
                    form.removeEventListener('submit', preventSubmit);
                    }

                    else{
                    form.addEventListener('submit', preventSubmit);
                    subscribeText.textContent = "Error! You have to be 18 or older";
                    subscribeText.style.fontSize = '30px';
                    subscribeText.style.color = 'red';
                    subscribeText.style.paddingTop = '15px';
                    subscribeText.style.textAlign = 'center';
                }
                }

                else if(crpassInput.value != conpassInput.value){
                    form.addEventListener('submit', preventSubmit);
                    subscribeText.textContent = "Error! Passwords don't match";
                    subscribeText.style.fontSize = '30px';
                    subscribeText.style.color = 'red';
                    subscribeText.style.paddingTop = '15px';
                    subscribeText.style.textAlign = 'center';
                    }
                
                else {
                    if((crpassInput.value.length < 5)){
                        form.addEventListener('submit', preventSubmit);
                        subscribeText.textContent = "Password should be 5 characters or more";
                        subscribeText.style.fontSize = '30px';
                        subscribeText.style.color = 'red';
                        subscribeText.style.paddingTop = '15px';
                        subscribeText.style.textAlign = 'center';
                    }

                    else{
                    form.addEventListener('submit', preventSubmit);
                    subscribeText.textContent = "Error! Invalid format";
                    subscribeText.style.fontSize = '30px';
                    subscribeText.style.color = 'red';
                    subscribeText.style.paddingTop = '15px';
                    subscribeText.style.textAlign = 'center';
                    }
                }
            });
    
            function preventSubmit(event) {
                event.preventDefault();
            }
    
            function isValidEmail(email) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        });

    $(document).ready(function() {

  $('#myCheckbox').click(function() {

    if ($(this).is(':checked')) {
        submitButton.disabled = false;
    }
      
      else {
        submitButton.disabled = true;
    }
  });
});
    </script>
    <style>
    h1 {
        text-align: center;
        border-top: 2px solid #333;
    }

    section{
        text-align: center;
        padding-top: 100px;
        padding-bottom: 100px;
    }

    .inputs {
        width: 300px;
        height: 40px;

    }

    #signup-form{
        border: 2px solid black;
        border-radius: 2rem;
        padding-top: 50px;
        padding-bottom: 50px;
        margin-right: 150px;
        margin-left: 150px;

        position: relative;
        min-height: 500px;
        background-image: linear-gradient(to right, rgba(44,56,85,0.9), rgba(100,125,187,0.1)), url(../Internet_project/images/sea-with-waves-cloudy-sky.jpg);
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
    }

    #createpassword{
    margin-right: 94px;
    margin-top: 30px;
    }

    #confirmpassword{
    margin-right: 102px;
    margin-top: 30px;
    }

    #submitButton{
        margin-top: 50px;
        height: 30px;
        width: 150px;
        background-color:dimgray;
    }

    #name{
        margin-top: 30px;
        margin-right: 7px;
    }

    #number{
        margin-top: 30px;
        margin-right: 7px;
    }

    #email{
        margin-top: 30px;
    }

    #username{
        margin-top: 30px;
        margin-right: 40px;
    }

    #DOB{
        margin-top: 30px;
        margin-left: 8px;
    }

    #sex{
        margin-top: 30px;
        margin-right: 16px;
    }

    #myCheckbox{
        margin-top: 20px;
    }
    </style>
    
    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "headernav.html");
    ?>

    <h1></h1>
    <section>
        <form id="signup-form" action="Sign Up.php" method="post">
            <h2>Sign up to BookNest</h2>
            <br>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" class="inputs">
            <br>
            <label for="number">Phone</label>
            <input type="text" id="number" name="number" placeholder="Enter your phone number" class="inputs">
            <br>
            <label for="DOB">DOB</label>
            <input type="Date" id="DOB" name="DOB" placeholder="Enter your date of birth" class="inputs">
            <br>
            <label for="sex">Gender</label>
            <select id="sex" name="sex" class="inputs">
                <option selected disabled>Select your gender</option>
                <option>Male</option>
                <option>Female</option>
            </select>
            <br>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Enter your email address" class="inputs">
            <br>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter username" class="inputs">
            <br>
            <label for="create password">Create Password</label>
            <input type="password" id="createpassword" name="createpassword" placeholder="Enter password" class="inputs">
            <br>
            <label for="confirm password">Confirm Password</label>
            <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Re-enter password" class="inputs">
            <br>
            <button type="submit" id="submitButton" value="Submit" disabled>Create Account</button>
            <br>
            <input type="checkbox" id="myCheckbox"> Agree to Terms & Conditions
            <p>*You have to be 18 or older</p>
            <p>*Password should be 5 characters long or more</p>
            <div id="subscription-message"></div>
        </form>
    </section>
</body>
</html>
