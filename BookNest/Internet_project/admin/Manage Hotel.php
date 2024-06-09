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

    if (isset($_POST['submit'])) {
        if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $imgData = file_get_contents($_FILES['img']['tmp_name']);
            $hotelName = $_POST["hotelName"];
            $city = $_POST["city"];
            $country = $_POST["country"];
            $link = $_POST["link"];
            $hotelID = $_POST["hotelID"];
    
            if (!empty($hotelID)) {
                $query = "UPDATE hotel SET hotelName=?, city=?, country=?, img=?, link=? WHERE hotelID=?";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, "sssssi", $hotelName, $city, $country, $imgData, $link, $hotelID);
                mysqli_stmt_execute($stmt);

                if (mysqli_stmt_affected_rows($stmt) == 0) {
                    echo "<script>alert('Hotel ID was not found');</script>";
                } else {
                    echo "<script>alert('Hotel successfully updated');</script>";
                }
            } else {
                $query = "INSERT INTO hotel (hotelName, city, country, img, link) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, "sssss", $hotelName, $city, $country, $imgData, $link);
                mysqli_stmt_execute($stmt);
                echo "<script>alert('Hotel successfully added');</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Error in file upload');</script>";
        }
    }


    $hotelData = [];
    $query = "SELECT * FROM hotel ORDER BY hotelID ASC";
    $result = mysqli_query($connect, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $hotelData[] = $row;
        }
    }


    if (isset($_POST['delete']) && isset($_POST['hotelID'])) {
        $query = "DELETE FROM hotel WHERE hotelID=?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "i", $_POST['hotelID']);
        if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Hotel deleted successfully";
        } else {
            echo "Error deleting hotel";
        }
        mysqli_stmt_close($stmt);
        exit();
    }
    

    mysqli_close($connect);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../includes/styles.css">
    <style>
    .popular__card {
        position: relative;
    }

    .delete-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 24px;
        color: red;
        cursor: pointer;
    }

    .delete-icon:hover {
    color: black;
    }

    a :hover{
    color: red;
    }

    .footer{
    margin-top: 80px;
    }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var message = document.getElementById('message');
        var submitButton = document.getElementById("btn");
        var contactForm = document.getElementById('form');
        var cont = document.getElementById('country');
        var hotel = document.getElementById('hotel');
        var city = document.getElementById('city');
        var link = document.getElementById('link');

        submitButton.addEventListener('click', function() {
            if((cont.value !== "" && hotel.value !== "") && (city.value !== "" && link.value !== "")){
                contactForm.removeEventListener('submit', preventSubmit);
                }

            else {
                contactForm.addEventListener('submit', preventSubmit);
                message.textContent = "Error! Check the inputs";
                message.style.fontSize = '20px';
                message.style.color = 'red';
                message.style.paddingTop = '15px';
            }
        });
        function preventSubmit(event) {
            event.preventDefault();
        }

        
        $(".delete-icon").click(function(event) {
            event.preventDefault();
            var button = $(this);
            var hotelId = button.data('hotelid');
            
            $.ajax({
                type: "POST",
                url: "Manage Hotel.php",
                data: { hotelID: hotelId, delete: true },
                success: function(response) {
                    alert('Hotel deleted successfully');
                    button.closest('.popular__card').remove();
                },
                error: function() {
                    alert('Error deleting hotel');
                }
            });
        });

        });

    </script>
    
    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "adminnav.html");
    ?>

    <header class="section__container  header__container">
        <div class="header__image__container">
            <div class="header__container">
                <h1>Manage Hotel</h1>
                <h2>Add Hotel</h2>
            </div>
            <div class="booking__container">
                <form id="form" action="Manage Hotel.php" method="post" enctype="multipart/form-data">
                    <div class="form__group">
                        <div id="input__group">
                            <input type="text" id="hotel" name="hotelName">
                            <label>Hotel</label>
                        </div>
                        <p>Add Hotel Name</p>
                    </div>
                    <div class="form__group">
                        <div id="input__group">
                            <input type="text" id="city" name="city">
                            <label>City</label>
                        </div>
                        <p>Add City</p>
                    </div>
                    <div class="form__group">
                        <div id="input__group">
                            <input type="text" id="country" name="country">
                            <label>Country</label>
                        </div>
                        <p>Add Country</p>
                    </div>
                    <div class="form__group">
                        <div id="input__group">
                            <input type="text" id="ID" name="hotelID">
                            <label>Hotel ID (Optional)</label>
                        </div>
                        <p>Add ID for updating</p>
                    </div>
                    <div class="form__group">
                        <div id="input__group">
                            <input type="url" id="link" name="link" required>
                            <label>Hotel Website</label>
                        </div>
                        <p>Add Link</p>
                    </div>
                    <div class="form__group">
                        <div id="input__group">
                            <input type="file" id="img" name="img" required>
                            <label>Add Picture</label>
                        </div>
                        <p>Add Picture</p>
                    </div>
                    <button type="submit" id="btn" name="submit">+</button>
                    <div id="message"></div>
            </form>
            </div>
        </div>
    </header>

    <?php if (!empty($hotelData)): ?>
    <section class="section__container popular__container">
    <h2 class="section__header">Remove Hotel</h2>
    <div id="message2"></div>
    <div class="popular__grid">
        <?php foreach ($hotelData as $hotel): ?>
            <div class="popular__card">
                <a href="<?php echo htmlspecialchars($hotel['link']); ?>" target="_blank">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($hotel['img']); ?>" alt="Hotel Image">
                </a>
                <div class="popular__content">
                    <div class="popular__card__header">
                        <a href="<?php echo htmlspecialchars($hotel['link']); ?>" target="_blank">
                        <h4><?php echo htmlspecialchars($hotel['hotelName']); ?></h4>
                        </a>
                    </div>
                    <p><?php echo htmlspecialchars($hotel['city']) . ', ' . htmlspecialchars($hotel['country']); ?></p>
                    <h4><?php echo htmlspecialchars($hotel['hotelID']); ?></h4>
                    
                    <form method="post">
                    <input type="hidden" name="hotelID" value="<?php echo $hotel['hotelID']; ?>">
                    <button type="submit" name="delete" class="delete-icon" data-hotelid="<?php echo $hotel['hotelID']; ?>" style="background: none; border: none;">
                    <i class="fa fa-trash" style="font-size:32px"></i>
                    </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </section>
    <?php endif; ?>




    
    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "footer.html");
    ?>
</body>
</html>

