<?php
    session_start();

    $connect = mysqli_connect("localhost", "root", "", "BookNest");
    if (!$connect) {
        die("Cannot connect to database");
    }

    function logout() {
        session_unset();
        session_destroy();
        
        header("Location: ../home.php");
        exit();
    }
    
    if (isset($_POST['logout'])) {
        logout();
    }

    if (!isset($_SESSION['adminID'])) {
        echo "<script>alert('You must be logged in as an admin to view this page'); window.location.href = '../home.php';</script>";
        exit();
    }

    if (isset($_POST["username"])) {
        $username = $_POST["username"];
        $clientID = isset($_POST["clientID"]) ? $_POST["clientID"] : 0;

        $query = "SELECT * FROM client WHERE username = ? AND clientID != ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "si", $username, $clientID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Username already exists');</script>";
        } else {
            if (!empty($clientID)) {
                $query = "UPDATE client SET name=?, phoneNum=?, DOB=?, email=?, gender=?, username=?, password=? WHERE clientID=?";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, "sssssssi", $_POST["name"], $_POST["number"], $_POST["DOB"], $_POST["email"], $_POST["sex"], $_POST["username"], $_POST["createpassword"], $clientID);
                mysqli_stmt_execute($stmt);

                if (mysqli_stmt_affected_rows($stmt) == 0) {
                    echo "<script>alert('Client ID was not found');</script>";
                } else {
                    echo "<script>alert('Client successfully updated'); window.location.href = window.location.href;</script>";
                    exit();
                }
            } else {
                $query = "INSERT INTO client (name, phoneNum, DOB, email, gender, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, "sssssss", $_POST["name"], $_POST["number"], $_POST["DOB"], $_POST["email"], $_POST["sex"], $_POST["username"], $_POST["createpassword"]);
                mysqli_stmt_execute($stmt);
                echo "<script>alert('Client successfully added'); window.location.href = window.location.href;</script>";
                exit();
            }
        }
        mysqli_stmt_close($stmt);
    }

    $clientData = [];
    $query = "SELECT * FROM client ORDER BY clientID ASC";
    $result = mysqli_query($connect, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $clientData[] = $row;
        }
    }


    if (isset($_POST['IDToRemove'])) {
        $IDToDelete = $_POST['IDToRemove'];

        $query = "DELETE FROM client WHERE clientID = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "i", $IDToDelete);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>alert('Client deleted successfully'); window.location.href = window.location.href;</script>";
            exit();
        } else {
            echo "<script>alert('Error deleting client or client not found'); window.location.href = window.location.href;</script>";
            exit();
        }
        mysqli_stmt_close($stmt);
    }

    
    mysqli_close($connect);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../includes/styles.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var submitButton = document.getElementById("submitButton");
            var form = document.getElementById('addform');
            var msg = document.getElementById('message');
    
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
                    msg.textContent = "Error! Client has to be 18 or older";
                    msg.style.fontSize = '30px';
                    msg.style.color = 'red';
                    msg.style.paddingTop = '15px';
                    msg.style.textAlign = 'center';
                }
            }

            else if(crpassInput.value != conpassInput.value){
                    form.addEventListener('submit', preventSubmit);
                    msg.textContent = "Error! Passwords don't match";
                    msg.style.fontSize = '30px';
                    msg.style.color = 'red';
                    msg.style.paddingTop = '15px';
                    msg.style.textAlign = 'center';
                    }
                
                else {
                    if((crpassInput.value.length < 5)){
                        form.addEventListener('submit', preventSubmit);
                        msg.textContent = "Password should be 5 characters or more";
                        msg.style.fontSize = '30px';
                        msg.style.color = 'red';
                        msg.style.paddingTop = '15px';
                        msg.style.textAlign = 'center';
                    }

                    else{
                    form.addEventListener('submit', preventSubmit);
                    msg.textContent = "Error! Invalid format";
                    msg.style.fontSize = '30px';
                    msg.style.color = 'red';
                    msg.style.paddingTop = '15px';
                    msg.style.textAlign = 'center';
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
    </script>
    <style>
    .inputs {
        width: 300px;
        height: 40px;

    }

    .header__image__container{
    position: relative;
    min-height: 500px;
    background-image: linear-gradient(to right, rgba(44,56,85,0.9), rgba(100,125,187,0.1)), url(../images/sea-with-waves-cloudy-sky.jpg);
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    border-radius: 2rem;
}

    #addform{
        padding-bottom: 50px;
        text-align: center;
    }

    #pass{
    margin-right: 33px;
    margin-top: 30px;
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

    #clientID{
        margin-top: 30px;
        margin-right: 110px;
    }

    .Client {
    padding-top: 30px;
    border: 2px solid black;
    border-radius: 2px;
    text-align: center;
    margin-left: 300px;
    margin-right: 300px;
    margin-top: 100px;
    padding-bottom: 10px;
}

#cl1{
    margin-top: 10px;
}

.clremove{
    margin-top: 40px;

}

#recent{
margin-top: 125px;
}

#remove{
    padding-top: 150px;
}

#age{
    color: black;
}
    </style>
    
    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "adminnav.html");
    ?>

    <header class="section__container  header__container">
        <div class="header__image__container">
            <div class="header__container">
                <h1>Manage Client</h1>
                <h2>Add Client</h2>
            </div>
            <form id="addform" method="POST">
                <label for="clientID">Client ID (optional)</label>
                <input type="text" id="clientID" name="clientID" placeholder="Enter Client ID for updating" class="inputs">
                <br>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter name" class="inputs">
                <br>
                <label for="number">Phone</label>
                <input type="text" id="number" name="number" placeholder="Enter phone number" class="inputs">
                <br>
                <label for="DOB">DOB</label>
                <input type="Date" id="DOB" name="DOB" placeholder="Enter your date of birth" class="inputs">
                <br>
                <label for="sex">Gender</label>
                <select id="sex" name="sex" class="inputs">
                    <option selected disabled>Select gender</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>
                <br>
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Enter email address" class="inputs">
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
                <button type="submit" id="submitButton" value="Submit">Add Client</button>
                <p id="age">*Client has to be 18 or older</p>
                <div id="message"></div>
            </form>
            </div>
            
        </div>

        <?php if (!empty($clientData)): ?>
        <h2 class="section__header" id="remove">Remove Client</h2>
        <div id="allClients">
            <?php foreach ($clientData as $client): ?>
                <div class="Client" data-client-id="<?= $client['clientID'] ?>">
                    Client Number: <div><?= htmlspecialchars($client['clientID']) ?></div>
                    <br>
                    Name: <div id="clname"><?= htmlspecialchars($client['name']) ?></div>
                    <br>
                    Phone: <div id="clphone"><?= htmlspecialchars($client['phoneNum']) ?></div>
                    <br>
                    DOB: <div id="clDOB"><?= htmlspecialchars($client['DOB']) ?></div>
                    <br>
                    Gender: <div id="clsex"><?= htmlspecialchars($client['gender']) ?></div>
                    <br>
                    Username: <div id="cluser"><?= htmlspecialchars($client['username']) ?></div>
                    <br>
                    Email: <div id="clemail"><?= htmlspecialchars($client['email']) ?></div>
                    <br>
                    Password: <div id="clpass"><?= htmlspecialchars($client['password']) ?></div>
                    <form method="POST">
                    <input type="hidden" id="IDToRemove" name="IDToRemove" value="<?= htmlspecialchars($client['clientID']) ?>">
                    <button class="clremove">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
    </div>
    <?php endif; ?>
    </header>

    
    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "footer.html");
    ?>
</body>
</html>
