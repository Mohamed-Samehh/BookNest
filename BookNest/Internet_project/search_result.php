<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booknest";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = $conn->real_escape_string($search);

$query = "SELECT hotelID, hotelName, city, country, img, link FROM hotel WHERE hotelName LIKE '%$search%' OR city LIKE '%$search%' OR country LIKE '%$search%'";
$result = $conn->query($query);

$hotels = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $hotels[] = $row;
    }
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>BookNest - Search Page</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            background-image: url("images/sea-with-waves-cloudy-sky.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 200%;
        }

        header {
            background-color: rgba(135, 207, 235, 0.122);
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

        .search_result_container {
            position: relative;
            height: 110px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .search_container {
            position: absolute;
            height: 100px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search_bar {
            height: 50px;
            width: 450px;
            display: flex;
            margin: 2px;
            position: relative;
            border-radius: 30px;
            border: solid black 2px;
        }

        .search_bar img {
            position: relative;
            top: 9px;
            margin: 5px;
        }

        .search_bar input {
            font-size: large;
            width: 450px;
            border: none;
            border-radius: 30px;
            outline: none;
        }

        #root {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 20px;
        }

        .box {
            background-color: #28929a2b;
            margin: 2px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: solid  rgba(128, 128, 128, 0.166) 1px;
            border-radius: 6px;
            padding: 5px;
        }

        .img-box {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .images {
            max-height: 100%;
            max-width: 110%;
            object-fit: cover;
            object-position: center;
            border-radius: 10px;
        }

        .bottom {
            margin-top: 20px;
            width: 100%;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            height: 110px;
        }

        .bottom p {
            font-weight: bolder;
        }
        .bottom button{
            width:120px;
            height: 25px;
            border-radius: 5px;
            border:solid 1px #00000053;
            background-color:#ffffff;
            color: #09555a;
            font-weight: 600;
            font-size: 15px;
            transition: 0.5S ease;
            margin-left: 10px;
            margin-right: 10px;
        }
        .bottom button:hover{
            cursor: pointer;
            transform: scale(1.1);
        }

        footer {
            margin-top: 20px;
            position: relative;
            background-image: linear-gradient(to right, #3285c98e, #28929ab4);
            bottom: 0px;
            height: 400px;
            width: 100%;
            position: relative;
        }

        .footer_container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 375px;

        }

        .footer_col {
            position: relative;
            left: 100px;
            align-items: center;
            justify-content: center;
            width: 300px;
            height: 250px;
        }

        .footer_cool {
            position: relative;
            left: 10px;
            align-items: center;
            justify-content: center;
            width: 400px;
            height: 250px;
        }

        .footer_bar{
    position: relative;
    max-width: 1200px;
    margin: auto;
    padding: 1rem;
    font-size: 0.9rem;
    font-weight: 500;
    color: #767268;
    text-align: right;
    overflow: hidden;
}

.footer_bar::before {
    position: absolute;
    content: "";
    top: 50%;
    right: 28rem;
    transform: translateY(-50%);
    width: 20rem;
    height: 2px;
    background-color: #767268;
}

        .footer_cool h3{
    margin-bottom: 1rem;
    font-size: 1.5rem;
    font-weight: 600;
    color: #333333;
}

.footer_col h4{
    margin-bottom: 1rem;
    font-size: 1.2rem;
    font-weight: 600;
    color: #333333;
}

.footer_col p{
    margin-bottom: 1rem;
    cursor: pointer;
    transition: 0.3s;
}

.footer_col p:hover{
    color: #333333;
}

.bottom_info{
    padding-bottom: 15px;
    padding-top: 15px;
}

#no_hotels{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 50vh;
}

.no-hotels{
    text-align: center;
    margin: 20px;
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
    <div class="search_result_container">
        <div class="search_container">
            <form method="GET">
                <div class="search_bar">
                    <img src="images/search-icon.png" height="21px" width="21px">
                    <input id="searchBar" name="search" placeholder="where do you wish to go?" type="text" value="<?= htmlspecialchars($search) ?>">
                </div>
            </form>
        </div>
    </div>
    
    <?php if (empty($hotels)): ?>
    <div id="no_hotels">
        <div class='no-hotels'>
            <p style="font-size: 32px; color: black;">There are no hotels at the moment</p>
        </div>
    </div>
    <?php else: ?>
        <div id="root">
        <?php foreach ($hotels as $hotel): ?>
            <div class='box'>
                <div class='img-box'>
                    <img class='images' src='data:image/jpeg;base64,<?= base64_encode($hotel['img']) ?>' alt='Hotel Image'></img>
                </div>
                <div class='bottom'>
                    <p><?= htmlspecialchars($hotel['hotelName']) ?></p>
                    <p><?= htmlspecialchars($hotel['city']) ?>, <?= htmlspecialchars($hotel['country']) ?></p>
                    <div class="bottom_info">
                        <a href="<?= htmlspecialchars($hotel['link']) ?>"><button>Learn More</button></a>
                        <button onclick="location.href='Rooms.php?hotelID=<?= $hotel['hotelID'] ?>'">View Rooms</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <footer>
        <div class="footer_container">
            <div class="footer_cool">
                <h3>BookNest</h3>
                <p>
                    BookNest, your gateway to limitless hotel bookings. Welcome to BookNest, designed only for your
                    comfort! BookNest will help you find your perfect-match hotel just by using your smart device. This
                    website will help you find the best hotel in the best price range anywhere in the world.
                </p>
                <p>
                    With a user-friendly interface and a vast selection of hotels, BookNest aims to provide a
                    stress-free experience for travelers seeking the perfect stay.
                </p>
                <br>
                <p>Copyright @ 2024 BookNest. All rights reserved.</p>
            </div>
            <div class="footer_col">
                <h4>Company</h4>
                <p>About Us</p>
                <p>Our Team</p>
                <p>Blog</p>
                <p>Book</p>
                <p>Contact Us</p>
            </div>
            <div class="footer_col">
                <h4>Legal</h4>
                <p>FAQs</p>
                <p>Terms & Conditions</p>
                <p>Privacy Policy</p>
            </div>
            <div class="footer_col">
                <h4>Resources</h4>
                <p>Social Media</p>
                <p>Help Center</p>
                <p>Partnerships</p>
            </div>
        </div>
        <div class="footer_bar">
            
        </div>
    </footer>
    <script>
    window.addEventListener('beforeunload', function () {
        localStorage.removeItem("HOTELROOMS");
        localStorage.removeItem("CITYROOMS");
        localStorage.removeItem("COUNTRYROOMS");
        localStorage.removeItem("HOTEL");
        localStorage.removeItem("CITY");
        localStorage.removeItem("COUNTRY");
    });

    document.addEventListener('DOMContentLoaded', function () {
        const hotelValueRooms = localStorage.getItem("HOTELROOMS");
        const cityValueRooms = localStorage.getItem("CITYROOMS");
        const countryValueRooms = localStorage.getItem("COUNTRYROOMS");
        const hotelValueHome = localStorage.getItem("HOTEL");
        const cityValueHome = localStorage.getItem("CITY");
        const countryValueHome = localStorage.getItem("COUNTRY");
        
        document.getElementById('searchBar').value = hotelValueHome || cityValueHome || countryValueHome || hotelValueRooms || cityValueRooms || countryValueRooms || '';
    });

    document.getElementById('searchBar').addEventListener('keyup', (e) => {
        const searchData = e.target.value.toLowerCase();
        const filterData = categories.filter((item) => {
            return (
                item.title.toLowerCase().includes(searchData) ||
                item.location.toLowerCase().includes(searchData) ||
                item.city.toLowerCase().includes(searchData)
            );
        });
        displayItem(filterData);
    });
</script>

</body>

</html>