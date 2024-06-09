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
        echo "<script>alert('You should be logged in to submit a comment'); window.location='home.php';</script>";
        exit();
    }

    $clientID = $_SESSION['userID'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $clientName = $conn->real_escape_string($_POST['name']);
        $clientEmail = $conn->real_escape_string($_POST['email']);
        $comment = $conn->real_escape_string($_POST['comment']);

        $sql = "INSERT INTO comments (clientID, clientName, clientEmail, comment) VALUES ('$clientID', '$clientName', '$clientEmail', '$comment')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Comment sent successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . addslashes($conn->error) . "');</script>";
        }
    }

    $conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./includes/styles.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var submitButton = document.getElementById("submitButton");
            var contactForm = document.getElementById('contactForm');
            var subscribeText = document.getElementById('subscription-message');

            submitButton.addEventListener('click', function() {
                var nameInput = document.getElementById('name');
                var emailInput = document.getElementById('email');
                var commentInput = document.getElementById('comment');
    
                if ((nameInput.value !== "" && commentInput.value !== "") && isValidEmail(emailInput.value)) {
                    contactForm.removeEventListener('submit', preventSubmit);
                }
                
                else {
                    contactForm.addEventListener('submit', preventSubmit);
                    subscribeText.textContent = "Invalid Input!";
                    subscribeText.style.fontSize = '22px';
                    subscribeText.style.color = 'red';
                    subscribeText.style.paddingTop = '25px';
                    subscribeText.style.textAlign = 'center';
                }
            });
    
            function preventSubmit(event) {
                event.preventDefault();
            }
    
            function isValidEmail(email) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            document.getElementById('comment').addEventListener('input', function() {
                var maxLength = parseInt(document.getElementById('comment').getAttribute('maxlength'));
                var currentLength = document.getElementById('comment').value.length;
                var remainingLength = maxLength - currentLength;
                document.getElementById('charCount').textContent = 'Characters remaining: ' + remainingLength;
            });
        });
    </script>
    <style>
        #contactForm div {
            margin-bottom: 15px;
            padding-bottom: 15px;
        }

        #contactForm {
        padding-top: 100px;
        width: 300px;
        font-family: Arial, sans-serif;
        margin-left: 475px;
        }
        
        
        #contactForm label {
            display: block;
            font-weight: bold;
        }
        
        #contactForm input[type="text"],
        #contactForm textarea {
            width: 400px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        #contactForm textarea {
            height: 150px;
        }

        #contactForm input {
            height: 40px;
        }
        
        #charCount {
            font-size: 12px;
            color: rgb(190, 190, 190);
        }

        #contactForm button {
            background-color: #5b92d1;
            color: white;
            padding: 10px 40px;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 auto;
            display: block;
            border-radius: 7px;
        }

        #contactForm button:hover {
            background-color: #45a049;
        }
        
        #paddingBottom {
            padding-bottom: 50px;
        }

        label{
            font-family: "Poppins", sans-serif;
        }

        p{
            font-family: "Poppins", sans-serif;
        }

        button{
            font-family: "Poppins", sans-serif;
        }

        #subscription-message{
            font-family: "Poppins", sans-serif;
            font-weight: bolder;
        }

        h1{
            padding-left: 35px;
            padding-top: 30px;
            color: white;
        }

        .footer{
            margin-top: 50px;
        }

        #info{
            padding-top: 50px;
            padding-left: 30px;
            font-size: 27px;
        }
    </style>

    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "headernav.html");
    ?>
    
        <div class="header__image__container__Cont">
            <h1>Contact Us</h1>
                <form id="contactForm" method="post" action="Contact Us.php">
                    <div>
                        <label for="name"><i class="fa fa-user"></i>&nbsp; &nbsp;Name</label>
                        <input type="text" id="name" name="name">
                    </div>
                    <div>
                        <label for="email"><i class="fa fa-envelope"></i>&nbsp; &nbsp;Email</label>
                        <input type="text" id="email" name="email">
                    </div>
                    <div>
                        <label for="comment"><i class="fa fa-comment"></i>&nbsp; &nbsp;Comment</label>
                        <textarea id="comment" name="comment" maxlength="250"></textarea>
                        <p id="charCount">Characters remaining: 250</p>
                    </div>
                    <button type="submit" id="submitButton" value="Submit">Submit</button>
                    <div id="subscription-message"></div>
                </form>
                <div id="paddingBottom">
                </div>
        </div>
        <div id="info">
            <i class="fa fa-phone"></i>&nbsp; Hotline: 19923
            <p></p>
            <i class="fa fa-envelope-o"></i>&nbsp;Email: CustomerService@BookNest.com
            <p></p>
            <i class="fa fa-map-marker"></i>&nbsp;&nbsp;Office: Maadi - Street 9
        </div>

    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "footer.html");
    ?>
</body>
</html>
