<?php
session_start();

if (isset($_GET['hotelID'])) {
    $_SESSION['hotelID'] = $_GET['hotelID'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./includes/styles.css">
    
    <style>
    .Buttonss {
        position: absolute;
        bottom: 10px;
        right: 10px;
        font-size: 24px;
        color: red;
        cursor: pointer;
        background: none;
        border: none;
        margin-bottom: 10px;
        bottom: 50px;
        left: 230px;
        width: 100px;
    }

    .Buttonss:hover {
    color: black;
    }

    .popular__card {
        position: relative;
    }

    .header__container h1{
    color: white;
    text-align: center;
    margin-top: 40px;
    }

    .header__container p{
    color: white;
    text-align: center;
    }

    .container2 {
    width: 1200px;
    margin: auto;
    padding: 5rem 1rem;
    }


    .container2_header {
    font-size: 2rem;
    font-weight: 600;
    color: #333333;
    text-align: center;
    }

    .container2_text {
    font-size: 1rem;
    font-weight: 600;
    color: #333333;
    text-align: center;
    }

    .Rooms_types {
    margin-top: 4rem;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    }

    .Rooms_images {
    box-shadow: 5px 5px 20px rgba(0,0,0,0.1);
    position: relative;
    margin: 0px 0px;
    margin-bottom: 30px;
    }

    .Rooms_images p{
    padding-left: 25px;
    }

    .Rooms_images span{
    padding-left: 25px;
    }

    .Rooms_images h3{
    padding-left: 25px;
    }

    .Rooms_images img {
    width: 100%;
    height: 60%;
    }

    .price .span {
    color: #fff;
    cursor: pointer;
    font-weight: 300;
    font-size: 16px;
    }

    .price .span .span {
    font-size: 24px;
    font-weight: 400;
    display: inline-block;
    color: #fff;
    margin: 0;
    margin-left: 12px;
    }

    .offers {
        width: 1450px;
        height: 600px;
        margin: auto;
        padding: 5rem 1rem;
        background-color: #f3f4f6;
        margin-left: 0;
    }

    #myImg{
        text-align: center;
    }

    .offers_header {
        text-align: center;
        font-size: 1.8rem;
    }

    .offers_images {
        width: 30%;
        height: 90%;
        display: inline-block;
        margin-left: 2.5%;
        position: relative;
    }

    .offers_images img {
        width: 100%;
        height: 100%;
        display: block;
        margin: auto;
    }

    .offers_text {
        position: absolute;
        bottom: 0;
        color: #fff;
        padding: 10px;
        margin-left: 30%;
    }

    .offers_text a {
        color: #fff;
        text-decoration: none;
    }

    .offers_text a:hover {
        color: red;
    }

    .HiddenInfo {
        width: 100%;
        height: 60%;
        top: 0;
        left: 0;
        position: absolute;
        background: rgba(0,0,0,0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        opacity: 0;
        transition: 0.6s;
    }

    .HiddenInfo:hover {
        opacity: 1;
    }

    .HiddenInfo h3 {
        text-align: center;
        font-size: 20px;
        color: #ffe100;
        margin-bottom: 15px;
    }

    .HiddenInfo a {
        text-decoration: none;
        font-size: 20px;
        color: #ffe100;
        margin-bottom: 15px;
    }

    .HiddenInfo p {
        font-size: small;
        font-size: 10px;
        color: #ffe100;
        margin-bottom: 10px;
    }

    .section_image{
        width: 80%;
        height: 200px;
        margin: auto;
        margin-top: 100px;
        margin-bottom: 100px;
    }

    .section_image h2 {
        text-align: center;
    }

    #Up_scroll {
        margin-left: 700px;
        background-color: #f3f4f6;
        border-color: #f3f4f6;
    }

    .fa-solid {
        color: black;
        font-size: 2rem;
    }

    
    </style>

    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "headernav.html");
    ?>
    
    <header class="section__container  header__container">
        <div class="header__image__container">
            <div class="header__container">
                <h1>Rooms</h1>
                <p>GET A GREAT LUXURY EXPERIENCE AND ENJOY YOUR WONDERFUL VACATION!</p>
            </div>
            <div class="booking__container">
                <form id="form" action="search_result.php" method="get">
                    <div class="form__group">
                        <div id="input__group">
                            <input type="text" id="hotel_rooms" name="hotel_rooms">
                            <label>Hotel</label>
                        </div>
                        <p>Add Hotel Name</p>
                    </div>
                    <div class="form__group">
                        <div id="input__group">
                            <input type="text" id="city_rooms" name="city_rooms">
                            <label>City</label>
                        </div>
                        <p>Add City</p>
                    </div>
                    <div class="form__group">
                        <div id="input__group">
                            <input type="text" id="country_rooms" name="country_rooms">
                            <label>Country</label>
                        </div>
                        <p>Add Country</p>
                    </div>
                    <button type="submit" onclick="handleSubmitrooms()" id="btn"><i class="ri-search-line"></i></button>
                    *Enter one field only
            </form>
            </div>
        </div>
    </header>

    <section class="container2">
        <h2 class="container2_header">Choose Room Type</h2>
        <div class="Rooms_types">
            <div class="Rooms_images">
                <img src="../Internet_project/images/Luxury_room1.jpg" alt="Luxury_room1">
                <div class="Rooms_text">
                    <h3>
                        Luxurious Room 1
                    </h3>
                    <p>3 Guests</p>
                    <div class="price">
                        <span>$300 / Day</span>
                    </div>
                    <a href="Booking_confirm.php"><button id="a1" class="Buttonss"><i class="fa fa-ticket" style="font-size:34px"></i><br>Reserve</button></a>
                </div>
            </div>
            <div class="Rooms_images">
                <img src="../Internet_project/images/Family_room1.png" alt="Family_room1">
                <div class="Rooms_text">
                    <h3>
                        Family Room 1
                    </h3>
                    <p>5 Guests</p>
                    <div class="price">
                        <span>$100 / Day</span>
                    </div>
                    <a href="Booking_confirm.php"><button id="a2" class="Buttonss"><i class="fa fa-ticket" style="font-size:34px"></i><br>Reserve</button></a>
            </div>
        </div>
            <div class="Rooms_images">
                <img src="../Internet_project/images/Couple_room1.jpeg" alt="Couple_room1">
                <div class="Rooms_text">
                    <h3>
                        Couple Room 1
                    </h3>
                    <p>2 Guests</p>
                    <div class="price">
                        <span>$200 / Day</span>
                    </div>
                    <a href="Booking_confirm.php"><button id="a3" class="Buttonss"><i class="fa fa-ticket" style="font-size:34px"></i><br>Reserve</button></a>
                </div>
            </div>
            <div class="Rooms_images">
                <img src="../Internet_project/images/single.jpg" alt="Luxury_room2">
                <div class="Rooms_text">
                    <h3>
                        Single Room
                    </h3>
                    <p>1 Guest</p>
                    <div class="price">
                        <span>$60 / Day</span>
                    </div>
                    <a href="Booking_confirm.php"><button id="a4" class="Buttonss"><i class="fa fa-ticket" style="font-size:34px"></i><br>Reserve</button></a>
                </div>
            </div>
            <div class="Rooms_images">
                <img src="../Internet_project/images/Couple_room2.webp" alt="Couple_room2">
                <div class="Rooms_text">
                    <h3>
                        Couple Room 2
                    </h3>
                    <p>2 Guests</p>
                    <div class="price">
                        <span>$100 / Day</span>
                    </div>
                    <a href="Booking_confirm.php"><button id="a5" class="Buttonss"><i class="fa fa-ticket" style="font-size:34px"></i><br>Reserve</button></a>
                </div>
            </div>
            <div class="Rooms_images">
                <img src="../Internet_project/images/Family_room2.jpg" alt="Family_room2">
                <div class="Rooms_text">
                    <h3>
                        Family Room 2
                    </h3>
                    <p>5 Guests</p>
                    <div class="price">
                        <span>$150 / Day</span>
                    </div>
                    <a href="Booking_confirm.php"><button id="a6" class="Buttonss"><i class="fa fa-ticket" style="font-size:34px"></i><br>Reserve</button></a>
                </div>
            </div>
            <div class="Rooms_images">
                <img src="../Internet_project/images/Couple_room3.webp" alt="Couple_room3">
                <div class="Rooms_text">
                    <h3>
                        Couple Room 3
                    </h3>
                    <p>2 Guests</p>
                    <div class="price">
                        <span>$80 / Day</span>
                    </div>
                    <a href="Booking_confirm.php"><button id="a7" class="Buttonss"><i class="fa fa-ticket" style="font-size:34px"></i><br>Reserve</button></a>
                </div>
            </div>
            <div class="Rooms_images">
                <img src="../Internet_project/images/Luxury_room3.jpg" alt="Luxury_room3">
                <div class="Rooms_text">
                    <h3>
                        Luxurious Room 2
                    </h3>
                    <p>3 Guests</p>
                    <div class="price">
                        <span>$300 / Day</span>
                    </div>
                    <a href="Booking_confirm.php"><button id="a8" class="Buttonss"><i class="fa fa-ticket" style="font-size:34px"></i><br>Reserve</button></a>
                </div>
            </div>
            <div class="Rooms_images">
                <img src="../Internet_project/images/Family_room3.webp" alt="Family_room3">
                <div class="Rooms_text">
                    <h3>
                        Family Room 3
                    </h3>
                    <p>5 Guests</p>
                    <div class="price">
                        <span>$100 / Day</span>
                    </div>
                    <a href="Booking_confirm.php"><button id="a9" class="Buttonss"><i class="fa fa-ticket" style="font-size:34px"></i><br>Reserve</button></a>
                </div>
            </div>
        </div>
    </section>

    <section class="offers">
        <h2 class="offers_header">Offered to Our Guests</h2>
        <div class="offers_images">
            <img src="../Internet_project/images/Free_breakfast.jpg" alt="Free_breakfast">
            <div class="HiddenInfo">
                <h3>
                    Free breakfast
                    <p>Enjoy a lovely start to your day at the hotel with our complimentary breakfast. A delectable spread of warm waffles, fluffy pancakes, and freshly baked pastries awaits you when you wake up. Choose from a variety of cereals, yoghurt, and fresh fruit to energise your morning. Savour our range of golden hash browns, crispy bacon, and scrambled eggs if you're in the mood for something savoury. Enjoy a glass of refreshing juice or a steaming cup of freshly brewed coffee to round out your meal. Our complementary breakfast guarantees a positive start to your day, whether you're getting ready for an eventful work meeting or a day of adventure. Join us every morning and allow our delicious offers to tantalise your taste buds.</p>
                </h3>
            </div>
            <div class="offers_text">
                <h3>
                    Free breakfast
                </h3>
            </div>
        </div>
        <div class="offers_images">
            <img src="../Internet_project/images/Free_drinks.webp" alt="Free_drinks">
            <div class="HiddenInfo">
                <h3>
                    Free drinks
                    <p>Relax and enjoy our complimentary cocktails. Enter our warm lounge and enjoy a selection of cool drinks as we spoil you. Enjoy a well-made drink, a glass of quality wine, or some of our selection of cold beers. We also have a selection of soft drinks, freshly squeezed juices, and mocktails for those who prefer non-alcoholic options. Our free beverages are the ideal way to enhance your stay, whether you're celebrating a productive day of touring or just relaxing after a long trip. Come celebrate with us every evening as we raise a glass to your incredible vacation at the hotel. Let's toast to creating enduring memories in style!</p>
                </h3>
            </div>
            <div class="offers_text">
                <h3>
                    Free drinks
                </h3>
            </div>
        </div>
        <div class="offers_images">
            <img src="../Internet_project/images/Free_dinner.jpg" alt="Free_dinner">
            <div class="HiddenInfo">
                <h3>
                    Free dinner
                    <p>Savour a fine dining experience without ever stepping outside of our hotel. We are happy to give you free dinner every evening as part of our dedication to making sure your stay is nothing short of spectacular. Enjoy a delicious feast cooked by our skilled culinary crew while seated in our magnificent dining area.
                        Come celebrate with us every evening, and let us enhance your stay with the unmatched service and superb food that characterise us. Let us offer you a feast to remember while you kick back and unwind.</p>
                </h3>
            </div>
            <div class="offers_text">
                <h3>
                    Free dinner
                </h3>
            </div>
        </div>
    </section>

    <section class="section_image">
        <h2>Info</h2>
        <p id="myImg">Welcome to your gateway to comfort and elegance. Our platform features a wide range of hotels, each offering modern amenities, plush bedding, and sleek decor for a luxurious stay. Browse and compare the consistent room types available at each location, including deluxe suites, cozy singles, and spacious doubles. Select the perfect room to match your needs and prepare to enjoy stunning views and personalized service during your stay. Discover your ideal home away from home with us—where you have the freedom to choose the comfort that suits you best.</p>
        <br>
    </section>

    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "footer.html");
    ?>
    <script src="./includes/script.js"></script>
</body>
</html>
