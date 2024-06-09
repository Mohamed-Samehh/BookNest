<?php
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $connect = mysqli_connect("localhost", "root", "", "BookNest");
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (!isset($_SESSION['userID'])) {
        echo "<script>alert('You must be logged in to view your account'); window.location.href = 'home.php';</script>";
        exit();
    }

    $clientID = $_SESSION['userID'];

    $userData = mysqli_query($connect, "SELECT username, name, email, DOB, phoneNum FROM client WHERE clientID='$clientID'");
    $user = mysqli_fetch_assoc($userData);

    function isUsernameDuplicate($username, $connect, $clientID) {
        $query = "SELECT clientID FROM client WHERE username=? AND clientID != ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'si', $username, $clientID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $num_rows = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        return $num_rows > 0;
    }
    
    function updateClient($fields, $connect, $clientID) {
        if (isset($fields['username']) && isUsernameDuplicate($_POST['username'], $connect, $clientID)) {
            echo "<script>alert('Username is already taken. Please choose another.'); window.location.href = window.location.href;</script>";
            exit();
        }
    
        $updateParts = [];
        $params = [];
        $paramTypes = '';
        foreach ($fields as $dbField => $postField) {
            if (isset($_POST[$postField]) && !empty($_POST[$postField])) {
                $updateParts[] = "$dbField=?";
                $params[] = $_POST[$postField];
                $paramTypes .= 's';
            }
        }
        if (!empty($updateParts)) {
            $params[] = $clientID;
            $paramTypes .= 'i';
            $stmt = mysqli_prepare($connect, "UPDATE client SET " . implode(", ", $updateParts) . " WHERE clientID=?");
            mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "<script>alert('Your information has been updated successfully.'); window.location.href = window.location.href;</script>";
            } else {
                echo "<script>alert('No changes were made or update failed.'); window.location.href = window.location.href;</script>";
            }
            mysqli_stmt_close($stmt);
        }
    }
    

    if (isset($_POST['generalbtn'])) {
        updateClient([
            "username" => "username",
            "name" => "name",
            "email" => "email"
        ], $connect, $clientID);
    }

    if (isset($_POST['passbtn'])) {
        updateClient(["password" => "newPassword"], $connect, $clientID);
    }

    if (isset($_POST['infobtn'])) {
        updateClient([
            "DOB" => "dob",
            "phoneNum" => "phone"
        ], $connect, $clientID);
    }

    $bookingQuery = "SELECT b.bookID, b.roomType, b.checkIN, b.checkOUT, b.guestsNum, b.bookDate, h.hotelName AS hotelName, p.amount AS paymentAmount 
                    FROM booking b
                    LEFT JOIN hotel h ON b.hotelID = h.hotelID
                    LEFT JOIN payment p ON b.paymentID = p.paymentID
                    WHERE b.clientID = ?
                    ORDER BY b.bookID DESC";

    $bookingsData = array();

    if ($stmt = mysqli_prepare($connect, $bookingQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $clientID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $bookID, $roomType, $checkIN, $checkOUT, $guestsNum, $bookDate, $hotelName, $paymentAmount);

        while (mysqli_stmt_fetch($stmt)) {
            $bookingsData[] = array(
                'bookID' => $bookID,
                'roomType' => $roomType,
                'checkIN' => $checkIN,
                'checkOUT' => $checkOUT,
                'guestsNum' => $guestsNum,
                'bookDate' => $bookDate,
                'hotelName' => $hotelName,
                'paymentAmount' => $paymentAmount
            );
        }

        mysqli_stmt_close($stmt);
    } else {
        $bookingsData = array(array('error' => 'Unable to execute the query.'));
    }

    mysqli_close($connect);
    ?>



    


<!DOCTYPE html>
<html lang="en">
<style>
    img {
        width: 2000px;
        height: 400px;
    }

    .footer {
        margin-top: 30px;
    }

    #pd {
        padding-bottom: 100px;
    }

    #pd2 {
        padding-bottom: 150px;
    }

    .list-group-item-action {
        text-decoration: none;
    }

    .list-group-item-action:hover {
        text-decoration: none;
        color: #0056b3;
    }

    a, a:hover {
        text-decoration: none !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        overflow: hidden;
    }

    th {
        background-color: gray;
        color: white;
        font-size: 16px;
        padding: 12px 15px;
    }

    td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:nth-child(odd) {
        background-color: #ffffff;
    }

    tr:hover {
        background-color: #ddd;
        cursor: pointer;
    }

    h2 {
        font-size: 24px;
        text-align: center;
        padding: 20px 0;
        margin-bottom: 20px;
    }

    hr {
        border: none;
        height: 2px;
        background-color: #ddd;
        margin: 40px 0;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet" async>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
  <link rel="stylesheet" href="./includes/styles.css">
  <script src="./includes/script.js"></script>

  <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "headernav.html");
    ?>

<body>
  <div class="slideshow-container">

  <div class="mySlides fade">
    <div class="numbertext">1 / 3</div>
    <img src="../Internet_project/images/high-resolution-paradise-photo-l.jpg" class="slideshow-container-img" style="max-width: 100%; max-height: 400px;">

    <div class="text" style="color: #f2f2f2;">Aloft Abu Dhabi</div>
  </div>
  
  <div class="mySlides fade">
    <div class="numbertext">2 / 3</div>
    <img src="../Internet_project/images/The Ritz-Carlton New York, NoMad_Madison-Suite-Bedroom.jpg" class="slideshow-container-img" style="max-width: 100%; max-height: 400px;">

     <div class="text" style="color: #f2f2f2;">Cronulla Hotel</div>
  </div>
  
  <div class="mySlides fade">
    <div class="numbertext">3 / 3</div>
    <img src="../Internet_project/images/test.jpg" class="slideshow-container-img" style="max-width: 100%; max-height: 400px;">

     <div class="text" style="color: #f2f2f2;">The Scenic Hotel Australia</div>
  </div>
  <br>

  <script>
    document.addEventListener('DOMContentLoaded', function() {

        var generalForm = document.getElementById('generalForm');
        var passForm = document.getElementById('passForm');
        var infoForm = document.getElementById('infoForm');
        var msg1 = document.getElementById('msg1');
        var msg2 = document.getElementById('msg2');
        var msg3 = document.getElementById('msg3');
        var isGeneralValid = false;
        var isPassValid = false;
        var isInfoValid = false;
        

        generalForm.addEventListener('submit', function(event) {
            var username = document.getElementById("username").value;
            var name = document.getElementById("name").value;
            var email = document.getElementById("email").value;
            
            
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (username == '') {
                event.preventDefault();
                msg1.textContent = "Username can't be empty!";
                msg2.textContent = "";
                msg3.textContent = "";
                msg1.style.fontSize = '22px';
                msg1.style.color = 'white';
                msg1.style.paddingTop = '25px';
                msg1.style.paddingBottom = '10px';
                msg1.style.textAlign = 'center';
            }

            else if (name == '') {
                event.preventDefault();
                msg1.textContent = "Name can't be empty";
                msg2.textContent = "";
                msg3.textContent = "";
                msg1.style.fontSize = '22px';
                msg1.style.color = 'white';
                msg1.style.paddingTop = '25px';
                msg1.style.paddingBottom = '10px';
                msg1.style.textAlign = 'center';
            }

            else if (!emailPattern.test(email) || email== '') {
                event.preventDefault();
                msg1.textContent = "Please enter a valid email address";
                msg2.textContent = "";
                msg3.textContent = "";
                msg1.style.fontSize = '22px';
                msg1.style.color = 'white';
                msg1.style.paddingTop = '25px';
                msg1.style.paddingBottom = '10px';
                msg1.style.textAlign = 'center';
            }
        });



        passForm.addEventListener('submit', function(event) {
            var newPassword = document.getElementById("newPassword").value;
            var repeatPassword = document.getElementById("rePassword").value;
            

            if (newPassword.length < 5) {
                event.preventDefault();
                msg2.textContent = "New password should be at least 5 characters long";
                msg1.textContent = "";
                msg3.textContent = "";
                msg2.style.fontSize = '22px';
                msg2.style.color = 'white';
                msg2.style.paddingTop = '25px';
                msg2.style.paddingBottom = '10px';
                msg2.style.textAlign = 'center';
            }

            else if (newPassword != repeatPassword) {
                event.preventDefault();
                msg2.textContent = "New passwords do not match";
                msg1.textContent = "";
                msg3.textContent = "";
                msg2.style.fontSize = '22px';
                msg2.style.color = 'white';
                msg2.style.paddingTop = '25px';
                msg2.style.paddingBottom = '10px';
                msg2.style.textAlign = 'center';
            }
        });



        infoForm.addEventListener('submit', function(event) {
            var phone = document.getElementById("phone").value;
            var dob = document.getElementById("dob").value;
            

            if (dob === '') {
                event.preventDefault();
                msg3.textContent = "Date of birth can't be empty";
                msg1.textContent = "";
                msg2.textContent = "";
                msg3.style.fontSize = '22px';
                msg3.style.color = 'white';
                msg3.style.paddingTop = '25px';
                msg3.style.paddingBottom = '10px';
                msg3.style.textAlign = 'center';
            }

            else if (phone === '') {
                event.preventDefault();
                msg3.textContent = "Phone number can't be empty";
                msg1.textContent = "";
                msg2.textContent = "";
                msg3.style.fontSize = '22px';
                msg3.style.color = 'white';
                msg3.style.paddingTop = '25px';
                msg3.style.paddingBottom = '10px';
                msg3.style.textAlign = 'center';
            }
            
            else {
                var today = new Date();
                var birthDate = new Date(dob);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age < 18) {
                    event.preventDefault();
                    msg3.textContent = "You must be at least 18 years old";
                    msg1.textContent = "";
                    msg2.textContent = "";
                    msg3.style.fontSize = '22px';
                    msg3.style.color = 'white';
                    msg3.style.paddingTop = '25px';
                    msg3.style.paddingBottom = '10px';
                    msg3.style.textAlign = 'center';
                }
            }
        });

});

  </script>
  <script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {slideIndex = 1}
        slides[slideIndex-1].style.display = "block";
        setTimeout(showSlides, 2100);
        
    }
  </script>
  </div>

    <div id="pd">
    </div>

    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Account Details
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Change password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-info">Info</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <hr class="border-light m-0">
                            <div class="card-body">
                            <form id="generalForm" method="post" action="account.php">
                                <div class="form-group">
                                    <label class="form-label">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" value="<?php echo $user['username']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $user['name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">E-mail</label>
                                    <input type="text" id="email" name="email" class="form-control" value="<?php echo $user['email']; ?>">
                                </div>
                                <button type="submit" name="generalbtn" class="btn btn-primary">Submit</button>
                                </form>
                                <div id="msg1">
                            </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                            <form id="passForm" method="post" action="account.php">
                                <div class="form-group">
                                    <label class="form-label">New password</label>
                                    <input type="password" id="newPassword" name="newPassword" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Repeat new password</label>
                                    <input type="password" id="rePassword" name="rePassword" class="form-control">
                                </div>
                                <button type="submit" name="passbtn" class="btn btn-primary">Submit</button>
                            </form>
                            <div id="msg2">
                            </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="account-info">
                            <div class="card-body pb-2">
                            <form id="infoForm" method="post" action="account.php">
                                <div class="form-group">
                                    <label class="form-label">Birthday</label>
                                    <input type="date" id="dob" name="dob" class="form-control" value="<?php echo $user['DOB']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="text" id="phone" name="phone" class="form-control" value="<?php echo $user['phoneNum']; ?>">
                                </div>
                                <button type="submit" name="infobtn" class="btn btn-primary">Submit</button>
                            </form>
                            <div id="msg3">
                            </div>
                            </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="pd">
        </div>
        <hr>

        <div id="pd">
        </div>

        <h2>Booking Details</h2>
        <div id="bookings">
            <table>
                <tr>
                    <th>Booking ID</th>
                    <th>Room Type</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Guests</th>
                    <th>Booking Date</th>
                    <th>Hotel Name</th>
                    <th>Payment Amount</th>
                </tr>
                <?php if (empty($bookingsData)): ?>
                <tr><td colspan="8">No bookings found.</td></tr>
            <?php else: ?>
                <?php foreach ($bookingsData as $booking): ?>
                    <tr>
                    <td><?= $booking['bookID'] ?></td>
                        <td><?= $booking['roomType'] ?></td>
                        <td><?= $booking['checkIN'] ?></td>
                        <td><?= $booking['checkOUT'] ?></td>
                        <td><?= $booking['guestsNum'] ?></td>
                        <td><?= $booking['bookDate'] ?></td>
                        <td><?= $booking['hotelName'] ?></td>
                        <td>$<?= $booking['paymentAmount'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </table>
        </div>

        <div id="pd">
        </div>
        
    </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

    </script>
</body>
    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "footer.html");
    ?>
</html>
