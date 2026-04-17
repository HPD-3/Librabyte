<?php
$activePage = basename($_SERVER['PHP_SELF']);
include('./config/db.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) as user_count FROM users";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userCount = $row['user_count'];
} else {
    $userCount = 0;
}

$sql_reviews = "SELECT * FROM website_reviews ORDER BY created_at DESC";
$reviewsResult = $conn->query($sql_reviews);
$reviews = $reviewsResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reviewerName = mysqli_real_escape_string($conn, $_POST['name']);
    $rating = $_POST['rating'];
    $reviewText = mysqli_real_escape_string($conn, $_POST['review']);

    $insertReviewSql = "INSERT INTO website_reviews (reviewer_name, rating, review) VALUES ('$reviewerName', $rating, '$reviewText')";

    if ($conn->query($insertReviewSql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);  
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>LibraByte - Online Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/indexstyle.css">
    <link rel="stylesheet" href="assets/reviewstyle.css">
    <script src="scripts/index.js"></script>
</head>

<body>
    <?php include('navbar/indexnav.php'); ?>
    <div class="overlay"></div>
    <div class="reviews-container">
        <h1>Website Reviews</h1>

        <div class="reviews">
            <h2>What Our Users Are Saying</h2>
            <?php foreach ($reviews as $review) { ?>
                <div class="review">
                    <h4><?php echo htmlspecialchars($review['reviewer_name']); ?> - <span>Rating:
                            <?php echo $review['rating']; ?> stars</span></h4>
                    <p><?php echo nl2br(htmlspecialchars($review['review'])); ?></p>
                    <p><small>Submitted on: <?php echo $review['created_at']; ?></small></p>
                </div>
            <?php } ?>
        </div>

        <div class="submit-review">
            <h3>Leave a Review</h3>
            <form method="POST">
                <label for="name">Your Name:</label>
                <input type="text" name="name" id="name" required><br>

                <label for="rating">Rating:</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5">&#9733;</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
                </div>
                <br>

                <label for="review">Your Review:</label><br>
                <textarea name="review" id="review" rows="5" required></textarea><br>

                <button type="submit">Submit Review</button>
            </form>
        </div>
    </div>
    </div>
    <script src="scripts/index.js"></script>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h2>Libra<span>Byte</span></h2>
                <p>
                    123 main Street<br>
                    Bogor, 13213<br>
                    Indonesia
                </p>
                <hr>
            </div>
            <div class="footer-section">
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Account</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <ul>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Help</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
                <p>@LibraByte 2025</p>
            </div>
        </div>
    </footer>
</body>

</html>