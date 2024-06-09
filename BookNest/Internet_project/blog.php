<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "booknest");

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['userID'])) {
    echo "<script>alert('You should be logged in to view our blog'); window.location='home.php';</script>";
    exit();
}

$clientID = $_SESSION['userID'];

$query = "SELECT * FROM review ORDER BY reviewID DESC";
$result = mysqli_query($connect, $query);
$reviews = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['clientID'] == $clientID) {
            $row['canDelete'] = true;
        } else {
            $row['canDelete'] = false;
        }
        $reviews[] = $row;
    }
}


if (isset($_POST['delete']) && isset($_POST['reviewID'])) {
    $reviewID = $_POST['reviewID'];

    $query = "SELECT clientID FROM review WHERE reviewID = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $reviewID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dbClientID);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($dbClientID == $clientID) {
        $query = "DELETE FROM review WHERE reviewID = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "i", $reviewID);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Review deleted successfully";
        } else {
            echo "Error deleting review";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "You do not have permission to delete this review";
    }
}


if (isset($_POST['submit'])) {
    if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $imgData = file_get_contents($_FILES['img']['tmp_name']);
        $clientName = $_POST['clientName'];
        $reviewText = $_POST['review'];

        $query = "INSERT INTO review (clientID, clientName, img, review, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "isss", $clientID, $clientName, $imgData, $reviewText);
        mysqli_stmt_execute($stmt);
        echo "<script>alert('Review successfully added'); window.location.href = window.location.href;</script>";
        exit();
            
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error in file upload');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
    #more {display: none;}

    .review-button-container {
        display: flex;
        justify-content: center;
        margin: 20px 0;
    }

    .add-review-btn {
            background-color: gray;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: auto;
            display: inline-block;
        }

    .add-review-btn:hover {
        background-color: #45a049;
    }

    .review-form-container {
        background: #f2f2f2;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        max-width: 500px;
        margin: 20px auto;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .review-form label {
        margin-bottom: 5px;
        display: block;
        font-weight: bold;
    }

    .review-form input[type="text"],
    .review-form textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: vertical;
    }

    .review-form input[type="file"] {
        display: block;
        margin-top: 5px;
    }

    .submit-btn {
    background-color: #008CBA;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: block;
    width: 100%;
    margin-top: 10px;
    box-sizing: border-box;
    }

    .submit-btn:hover {
        background-color: #007B9A;
    }

    #preview-image {
        margin-top: 10px;
        max-width: 100px;
        max-height: 100px;
    }

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
    </style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="./includes/styles.css">
    <script src="./includes/script.js"></script>
    <script>
        $(document).ready(function() {
        $(".delete-icon").click(function() {
            var button = $(this);
            var reviewId = button.data('reviewid');
            
            $.ajax({
                type: "POST",
                url: "blog.php",
                data: { reviewID: reviewId, delete: true },
                success: function(response) {
                    alert('Review deleted successfully');
                    button.closest('.popular__card').remove();
                },
                error: function() {
                    alert('Error deleting review');
                }
            });
        });
    });

    </script>

    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "headernav.html");
    ?>

    <header class="section__container  header__container">
        <div class="header__image__container">
            <div class="header__container">
                <h1>What they say about us!</h1>
                <p>From reviews all the way to experiences.</p>
            </div>
        </div>
    </header>

    <?php if (!empty($reviews)): ?>
    <section class="section__container popular__container">
    <h2 class="section__header">Reviews</h2>
    <div id="reviews-container" class="popular__grid">

    <?php foreach ($reviews as $review): ?>
    <div class="popular__card">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($review['img']); ?>" alt="Reviewer Image">
        <div class="popular__content">
            <div class="popular__card__header">
                <h4><?php echo htmlspecialchars($review['clientName']); ?></h4>
                <small>Added on: <?php echo date("F j, Y, g:i a", strtotime($review['created_at'])); ?></small>
            </div>
            <p><?php echo htmlspecialchars($review['review']); ?></p>
            <?php if ($review['canDelete']): ?>
            <button class="delete-icon" data-reviewid="<?php echo $review['reviewID']; ?>" style="background: none; border: none;">
                <i class="fa fa-trash" style="font-size:32px"></i>
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    </div>

    </div>
    </section>


    <div class="review-button-container">
    <button id="add-review-btn" class="add-review-btn">Write Your Review</button>
    </div>

<div id="add-review-form" class="review-form-container" style="display: none;">
    <form id="review-form" class="review-form" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="reviewer-name">Display Name:</label>
            <input type="text" id="reviewer-name" name="clientName" required>
        </div>
        <div class="form-group">
            <label for="review-picture">Upload Your Picture:</label>
            <input type="file" id="img" name="img" required>
            <img id="preview-image" alt="Preview" style="display: none;">
        </div>
        <div class="form-group">
            <label for="review-content">Your Review:</label>
            <textarea id="review-content" name="review" rows="4" cols="50" required></textarea>
            <button type="submit" class="submit-btn" name="submit">Submit Review</button>
        </div>
        
    </form>
</div>

<script>
    $(document).ready(function() {
        $("#add-review-btn").click(function() {
            $("#add-review-form").toggle();
        });
    });
</script>

</body>
    <?php
    $IPATH = $_SERVER["DOCUMENT_ROOT"] . "/Internet_project/assets/"; 
    include($IPATH . "footer.html");
    ?>
</body>
</html>