<?php
    session_start();

    $isLoggedIn = isset($_SESSION['userID']);

    function logout() {
        session_unset();
        session_destroy();

        header("Location: home.php");
        exit();
    }

    if (isset($_POST['logout'])) {
        logout();
    }

    $connect = mysqli_connect("localhost", "root", "", "BookNest");
        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }


    $hotelData = [];
    $query = "SELECT * FROM hotel ORDER BY hotelID DESC LIMIT 6";
    $result = mysqli_query($connect, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $hotelData[] = $row;
        }
    }

    $reviewData = [];
    $queryReviews = "SELECT * FROM review ORDER BY reviewID DESC LIMIT 3";
    $resultReviews = mysqli_query($connect, $queryReviews);
    if (mysqli_num_rows($resultReviews) > 0) {
        while ($row = mysqli_fetch_assoc($resultReviews)) {
            $reviewData[] = $row;
        }
    }

    mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="./includes/styles.css">
    <script src="./includes/script.js"></script>
    <style>
       #add1{
            margin-top: 40px;
        }

        #add2{
            margin-top: 40px;
        }

        #add3{
            margin-top: 40px;
        }

        #add4{
            margin-top: 40px;
        }

        #add5{
            margin-top: 40px;
        }

        #add6{
            margin-top: 12px;
        }

        .nav__links {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .nav__links li {
            display: inline;
            margin-right: 20px;
        }

        .nav__links button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .nav__links button:hover {
            background-color: #0056b3;
        }
    </style>
    <title>BookNest</title>
</head>
<body>
    <nav>
        <div class="nav__logo"><a href="home.php">BookNest</a></div>
        <ul class="nav__links">
            <?php if ($isLoggedIn): ?>
                <li class="links"><form method="post"><button type="submit" name="logout">Logout</button></form></li>
            <?php else: ?>
                <li class="links"><a href="Login.php"><button>Login</button></a></li>
                <li class="links"><a href="Sign Up.php"><button>Sign Up</button></a></li>
            <?php endif; ?>
            <li class="links"><a href="home.php">Home</a></li>
            <li class="links"><a href="search_result.php">Hotels</a></li>
            <li class="links"><a href="blog.php">Blog</a></li>
            <li class="links"><a href="Contact Us.php">Contact Us</a></li>
            <li class="links"><a href="account.php">User Account</a></li>
        </ul>
    </nav>
    
    <header class="section__container  header__container">
        <div class="header__image__container">
            <div class="header__container">
                <h1>Enjoy Your Dream Vacation</h1>
                <p>Book Hotels, Flights and stay packages at lowest price.</p>
            </div>
            <div class="booking__container">
                <form id="form" action="search_result.php" method="get">
                    <div class="form__group">
                        <div id="input__group">
                            <input type="text" id="hotel_home" name="hotel">
                            <label>Hotel</label>
                        </div>
                        <p>Add Hotel Name</p>
                    </div>
                    <div class="form__group">
                        <div id="input__group">
                            <input type="text" id="city_home" name="city">
                            <label>City</label>
                        </div>
                        <p>Add City</p>
                    </div>
                    <div class="form__group">
                        <div id="input__group">
                            <input type="text" id="country_home" name="country">
                            <label>Country</label>
                        </div>
                        <p>Add Country</p>
                    </div>
                    <button type="submit" onclick="handleSubmit()" id="btn"><i class="ri-search-line"></i></button>
                    *Enter one field only
            </form>
            </div>
        </div>
    </header>

    <?php if (!empty($hotelData)): ?>
    <section class="section__container popular__container">
        <h2 class="section__header">Last Added to BookNest</h2>
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
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    
    <?php if (!empty($reviewData)): ?>
    <section class="section__container popular__container">
    <h2 class="section__header">Latest Reviews from our clients</h2>
    <div id="reviews-container" class="popular__grid">
        <?php foreach ($reviewData as $review): ?>
            <div class="popular__card">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($review['img']); ?>" alt="Reviewer Image">
                <div class="popular__content">
                    <div class="popular__card__header">
                        <h4><?php echo htmlspecialchars($review['clientName']); ?></h4>
                        <small>Added on: <?php echo date("F j, Y, g:i a", strtotime($review['created_at'])); ?></small>
                    </div>
                    <p><?php echo htmlspecialchars($review['review']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </section>
    <?php endif; ?>



    <section class="section__container">
        <div class="reward__container">
            <p>100+ hotels</p>
            <h4>Join our journey for a better hotel reservation</h4>
            <a href="About Us.php"><button class="reward__btn">Read More</button></a>
        </div>
    </section>

    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "footer.html");
    ?>
</body>
</html>
