<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./includes/styles.css">
    <script>
        $(document).ready(function () {
            var currentSlide = 0;
            var slides = $('.slideshow img');
            slides.eq(1).fadeOut(0);
            slides.eq(2).fadeOut(0);
            slides.eq(3).fadeOut(0);
            slides.eq(4).fadeOut(0);

            function showNextSlide() {
                slides.eq(currentSlide).fadeOut(0);
                currentSlide = (currentSlide + 1) % slides.length;
                slides.eq(currentSlide).fadeIn(1000);
            }

            let interval = setInterval(showNextSlide, 5000);

            $("#button2").click(function () {
                clearInterval(interval);

                slides.eq(currentSlide).fadeOut(0);
                currentSlide = (currentSlide + 1) % slides.length;
                slides.eq(currentSlide).fadeIn(1000);

                interval = setInterval(showNextSlide, 5000);
            });

            $("#button1").click(function () {
                clearInterval(interval);

                slides.eq(currentSlide).fadeOut(0);
                currentSlide = (currentSlide - 1) % slides.length;
                slides.eq(currentSlide).fadeIn(1000);

                interval = setInterval(showNextSlide, 5000);
            });
        });
    </script>

    <style>
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #ccc;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.75);
        }

        .content {
            margin-top: 20px;
            font-size: 17px;
        }

        .slideshow {
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            padding-left: 0;
            padding-top: 0;
            padding-bottom: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        img {
            border-radius: 20px;
        }

        .container {
            text-align: center;
            margin-bottom: 75px;
        }

        button {
            background-color: transparent;
            border: none;
        }

        .header__image__container__abt {
            position: relative;
            min-height: 500px;
            background-image: url(../Internet_project/images/Abt_BG.jpg);
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
            border-radius: 2rem;
        }

        img {
            border: 2px solid black;
            border-radius: 2rem;
        }
    </style>
    
    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "headernav.html");
    ?>

    <section>
        <div class="header__image__container__abt">
            <div class="slideshow">
                <button id="button1"><i class="fa fa-angle-left" style="font-size: 100px;"></i></button>
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                <img src="../Internet_project/images/Abt1.jpg" alt="Pic 1" width="1000px" height="550px">
                <img src="../Internet_project/images/Abt2.jpg" alt="Pic 2" width="1000px" height="550px">
                <img src="../Internet_project/images/Abt3.jpg" alt="Pic 3" width="1000px" height="550px">
                <img src="../Internet_project/images/Abt4.jpg" alt="Pic 4" width="1000px" height="550px">
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                <button id="button2"><i class="fa fa-angle-right" style="font-size: 100px;"></i></button>
            </div>
        </div>
        <br>
        <br>
        <div class="container">
            <h2>Who are we</h2>
            <div class="content">
                <p>
                    Welcome to BookNest, your premier destination for seamless hotel bookings and unforgettable travel
                    experiences. At BookNest, we're more than just a booking platform – we're your trusted companion on
                    your journey to discovering the perfect accommodations for your next adventure.
                    Founded with a passion for travel and a commitment to excellence, BookNest is dedicated to
                    empowering travelers with the freedom to explore the world on their own terms. Whether you're
                    seeking a luxurious resort, a cozy bed and breakfast, or a budget-friendly stay, we've got you
                    covered with our extensive selection of handpicked hotels.
                    Driven by innovation and fueled by a desire to exceed expectations, the BookNest team is dedicated
                    to providing you with a seamless and personalized booking experience. With our intuitive platform,
                    advanced search functionality, and expert customer support, we strive to make every aspect of your
                    travel journey as effortless and enjoyable as possible.
                    Join us on a journey of discovery and exploration. Whether you're planning a romantic getaway, a
                    family vacation, or a business trip, let BookNest be your trusted companion as you embark on your
                    next adventure.
                </p>
            </div>
        </div>

        <br>
    </section>

    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "footer.html");
    ?>
</body>

</html>