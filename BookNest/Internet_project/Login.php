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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uname = mysqli_real_escape_string($conn, $_POST['username']);
        $pwd = mysqli_real_escape_string($conn, $_POST['password']);
        $isAdminChecked = isset($_POST['isAdmin']) ? 1 : 0;

        if ($isAdminChecked) {
            $sql = "SELECT adminID, password FROM admin WHERE username='$uname'";
        } else {
            $sql = "SELECT clientID, password FROM client WHERE username='$uname'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error = "Account not found!";
        } else {
            $user = $result->fetch_assoc();
            if ($pwd === $user['password']) {
                $_SESSION['username'] = $uname;
                if ($isAdminChecked) {
                    $_SESSION['adminID'] = $user['adminID'];
                    $_SESSION['userID'] = null;
                } else {
                    $_SESSION['userID'] = $user['clientID'];
                    $_SESSION['adminID'] = null;
                }
                
                $redirectPage = $isAdminChecked ? "./admin/Manage hotel.php" : "home.php";
                echo "<script>alert('Login successful!'); window.location='" . $redirectPage . "';</script>";
                exit();
            } else {
                $error = "Wrong password!";
            }
        }
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
            var submitButton = document.getElementById("submitButton");
            var form = document.getElementById('login-form');
            var subscribeText = document.getElementById('subscription-message');
    
            submitButton.addEventListener('click', function() {
                var userInput = document.getElementById('username');
                var passwordInput = document.getElementById('password');
    
                if (userInput.value !== "" && passwordInput.value !== "") {
                    form.removeEventListener('submit', preventSubmit);
                }
                
                else {
                    form.addEventListener('submit', preventSubmit);
                    showErrorMessage();
                }
            });
    
            function preventSubmit(event) {
                event.preventDefault();
            }
    
            function showErrorMessage() {
                subscribeText.textContent = "Error! Invalid format";
            }
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

    #login-form{
        border: 2px solid black;
        border-radius: 2rem;
        padding-top: 50px;
        padding-bottom: 50px;
        margin-right: 175px;
        margin-left: 175px;

        position: relative;
        min-height: 500px;
        background-image: linear-gradient(to right, rgba(44,56,85,0.9), rgba(100,125,187,0.1)), url(../Internet_project/images/sea-with-waves-cloudy-sky.jpg);
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
    }

    #password{
    margin-right: 33px;
    margin-top: 30px;
    }

    #submitButton{
        margin-top: 50px;
        height: 30px;
        width: 150px;
        background-color:dimgray;
    }

    #username{
        margin-top: 30px;
        margin-right: 40px;
    }

    #subscription-message{
        font-size: 22px;
        color: red;
        padding-top: 25px;
        text-align: center;
    }
    </style>
    
    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "headernav.html");
    ?>

    <h1></h1>
    <section>
        <form id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Login to BookNest</h2>
            <br>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" class="inputs">
            <br>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" class="inputs">
            <br>
            <button type="submit" id="submitButton" value="Submit">Login</button>
            <br>
            <br>
            <input type="checkbox" id="isAdmin" name="isAdmin"> Admin
            <div id="subscription-message"><?php if(isset($error)) echo $error; ?></div>
        </form>
    </section>
</body>
</html>
